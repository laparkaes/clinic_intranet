<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Greenter\Model\Client\Client;
use Greenter\Model\Company\Company;
use Greenter\Model\Company\Address;
use Greenter\Model\Sale\FormaPagos\FormaPagoContado;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;
use Greenter\Model\Voided\Voided;
use Greenter\Model\Voided\VoidedDetail;
use Greenter\Model\Summary\Summary;
use Greenter\Model\Summary\SummaryDetail;
use Greenter\Report\HtmlReport;
use Greenter\Ws\Services\SunatEndpoints;
use Greenter\See;

class Greenter_lib{
	
	public function __construct($is_production = false){
		$this->CI = &get_instance();

		$sys_conf = $this->CI->general->id("system", 1);
		$this->ruc = $this->CI->general->id("company", $sys_conf->company_id)->tax_id;
		$this->user = $sys_conf->sunat_username;
		$this->pass = $sys_conf->sunat_password	;
		$this->cert_path = FCPATH."uploaded/sunat/".$sys_conf->sunat_certificate;

		if ($is_production) $this->service_link = SunatEndpoints::FE_PRODUCCION;
		else $this->service_link = SunatEndpoints::FE_BETA;
		
		
		//$this->ruc = '20000000001'; $this->user = 'MODDATOS'; $this->pass = 'moddatos'; $this->cert_path = FCPATH."uploaded/sunat/cert.pem";
		
	}
	
	private function set_see(){
		$see = new See();
		$see->setCertificate(file_get_contents($this->cert_path));
		$see->setService($this->service_link);
		$see->setClaveSOL($this->ruc, $this->user, $this->pass);
		
		return $see;
	}
	
	private function set_client($cl){
		$client = (new Client())
			->setTipoDoc($cl->doc_type->sunat_code)
			->setNumDoc($cl->doc_number)
			->setRznSocial($cl->name);
		
		return $client;
	}
	
	private function set_company($co){
		$address = (new Address())
			->setUbigueo($co->ubigeo)
			->setDepartamento($co->department)
			->setProvincia($co->province)
			->setDistrito($co->district)
			->setUrbanizacion($co->urbanization)
			->setDireccion($co->address)
			->setCodLocal('0000'); // Codigo de establecimiento asignado por SUNAT, 0000 por defecto.
		
		$company = (new Company())
			->setRuc($co->tax_id)
			->setRazonSocial($co->name)
			->setNombreComercial($co->name)
			->setAddress($address);
			
		return $company;
	}
	
	public function set_invoice($voucher_data){
		$vo = $voucher_data["voucher"];
		$pr = $voucher_data["products"];

		//Cliente
		$client = $this->set_client($voucher_data["client"]);
		
		//Emisor
		$company = $this->set_company($voucher_data["company"]);

		// Venta
		$invoice = (new Invoice())
			->setUblVersion('2.1')
			->setTipoOperacion('0101') //venta interna // Venta - Catalog. 51
			->setTipoDoc($vo->code) // Factura - Catalog. 01 
			->setSerie($vo->letter.$vo->serie)
			->setCorrelativo($vo->correlative)
			->setFechaEmision(new DateTime($vo->registed_at."-05:00")) // Zona horaria: Lima
			->setFormaPago(new FormaPagoContado()) // FormaPago: Contado
			->setTipoMoneda($vo->currency_code) // Sol - Catalog. 02
			->setCompany($company)
			->setClient($client)
			->setMtoOperGravadas($vo->amount)
			->setMtoIGV($vo->vat)
			->setTotalImpuestos($vo->vat)
			->setValorVenta($vo->amount)
			->setSubTotal($vo->total)
			->setMtoImpVenta($vo->total);
			
		$items = [];
		foreach($pr as $item){
			$items[] = (new SaleDetail())
				->setCodProducto($item->data->code)
				->setUnidad($item->type->sunat_code) // Unidad - Catalog. 03
				->setCantidad($item->qty)
				->setMtoValorUnitario($item->value)
				->setDescripcion($item->data->description)
				->setMtoBaseIgv($item->value * $item->qty)
				->setPorcentajeIgv(18.00) // 18%
				->setIgv($item->vat * $item->qty)
				->setTipAfeIgv('10') // Gravado Op. Onerosa - Catalog. 07
				->setTotalImpuestos($item->vat * $item->qty) // Suma de impuestos en el detalle
				->setMtoValorVenta($item->value * $item->qty)
				->setMtoPrecioUnitario($item->value + $item->vat);
		}

		$legend = (new Legend())
			->setCode('1000') // Monto en letras - Catalog. 52
			->setValue($vo->legend);

		$invoice->setDetails($items)->setLegends([$legend]);
		
		return $invoice;
	}
	
	public function send_sunat($voucher_data){
		$sunat_sent = false; $sunat_msg = $sunat_notes = null;
		
		$see = $this->set_see();
		$invoice = $this->set_invoice($voucher_data);
		$result = $see->send($invoice);

		// Verificamos que la conexión con SUNAT fue exitosa.
		if ($result->isSuccess()){
			$upload_dir = $_SERVER['DOCUMENT_ROOT']."/archivos/sunat/".date("Ym");
			if(!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
			$upload_dir = $upload_dir."/";

			// Guardar XML firmado digitalmente.
			file_put_contents($upload_dir.$invoice->getName().'.xml', $see->getFactory()->getLastXml());
			
			// Guardamos el CDR
			file_put_contents($upload_dir.'R-'.$invoice->getName().'.zip', $result->getCdrZip());
			
			$sunat_sent = true;
			$sunat_msg = $result->getCdrResponse()->getDescription();
			$notes = $result->getCdrResponse()->getNotes();
			if ($notes) $sunat_notes = implode("&&&", $notes);
		}else{
			$sunat_sent = false;
			$sunat_msg = $result->getError()->getCode()." - ".$result->getError()->getMessage();
			
			// Mostrar error al conectarse a SUNAT.
			//echo 'Codigo Error: '.$result->getError()->getCode();
			//echo 'Mensaje Error: '.$result->getError()->getMessage();
			//exit();
		}
		
		return ["sunat_sent" => $sunat_sent, "sunat_msg" => $sunat_msg, "sunat_notes" => $sunat_notes];
	}
	
	private function set_invoice_void($voucher_data, $data){
		$vo = $voucher_data["voucher"];
		
		$company = $this->set_company($voucher_data["company"]);
		
		if ($vo->letter === "B"){ // Es boleta
			$cl = $voucher_data["client"];
			
			$detail = new SummaryDetail();
			$detail->setTipoDoc($vo->code) // Boleta
				->setSerieNro($vo->letter.$vo->serie."-".$vo->correlative)
				->setEstado('3') // Anulación
				->setClienteTipo($cl->doc_type->sunat_code) // Tipo de documento
				->setClienteNro($cl->doc_number) // Numero de documento
				->setTotal($vo->total)
				->setMtoOperGravadas($vo->amount)
				->setMtoIGV($vo->vat);

			$invoice_void = new Summary();
			$invoice_void->setFecGeneracion(new \DateTime(date("Y-m-d", strtotime($vo->registed_at)))) // Fecha de emisión de las boletas.
				->setFecResumen(new \DateTime(date("Y-m-d"))) // Fecha de envío del resumen diario.
				->setCorrelativo($data["r_correlative"]) // Correlativo, necesario para diferenciar de otros Resumen diario del mismo día.
				->setCompany($company)
				->setDetails([$detail]);
		}elseif ($vo->letter === "F"){ // Es factura
			$detail = new VoidedDetail();
			$detail->setTipoDoc($vo->code) // Factura
				->setSerie($vo->letter.$vo->serie)
				->setCorrelativo($vo->correlative)
				->setDesMotivoBaja($data["reason"]); // Motivo por el cual se da de baja.
				
			$invoice_void = new Voided();
			$invoice_void->setCorrelativo($data["r_correlative"]) // Correlativo, necesario para diferenciar c. de baja de en un mismo día.
				->setFecGeneracion(new \DateTime(date("Y-m-d", strtotime($vo->registed_at)))) // Fecha de emisión de los comprobantes a dar de baja
				->setFecComunicacion(new \DateTime(date("Y-m-d"))) // Fecha de envio de la C. de baja
				->setCompany($company)
				->setDetails([$detail]);
		}else $invoice_void = null; // Ninguno
		
		return $invoice_void;
	}
	
	public function void_sunat($voucher_data, $data){
		$is_success = false; $message = $ticket = null;
		
		$see = $this->set_see();
		$invoice_void = $this->set_invoice_void($voucher_data, $data);
		if ($invoice_void){
			$result = $see->send($invoice_void);
			if ($result->isSuccess()){
				$ticket = $result->getTicket();
				$statusResult = $see->getStatus($ticket);
				if ($statusResult->isSuccess()) {
					$upload_dir = $_SERVER['DOCUMENT_ROOT']."/archivos/sunat/".date("Ym")."/anulados";
					if(!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
					$upload_dir = $upload_dir."/";
					
					// Guardar XML
					file_put_contents($upload_dir.$invoice_void->getName().'.xml', $see->getFactory()->getLastXml());

					// Guardar CDR
					file_put_contents($upload_dir.'R-'.$invoice_void->getName().'.zip', $statusResult->getCdrZip());
					
					$is_success = true;
					$message = $statusResult->getCdrResponse()->getDescription();
				}else $message = $statusResult->getError()->getCode()." - ".$statusResult->getError()->getMessage();
			}else $message = $result->getError()->getCode()." - ".$result->getError()->getMessage();
		}
		
		return ["ticket" => $ticket, "is_success" => $is_success, "message" => $message, "reason" => $data["reason"]];
	}
	
	public function auth_test(){
		$msg = null;
		
		// Company
		$sys_conf = $this->CI->general->id("system", 1);
		$co = $this->CI->general->id("company", $sys_conf->company_id);
		$co->department = $this->CI->general->id("address_department", $co->department_id)->name;
		$co->province = $this->CI->general->id("address_province", $co->province_id)->name;
		$co->district = $this->CI->general->id("address_district", $co->district_id)->name;
		$company = $this->set_company($co);
		
		// Cliente
		$client = new Client();
		$client->setTipoDoc('1')
			->setNumDoc('20203030')
			->setRznSocial('PERSON 1');

		// Venta
		$invoice = new Invoice();
		$invoice
			->setUblVersion('2.1')
			->setTipoOperacion('0101')
			->setTipoDoc('03')
			->setSerie('B001')
			->setCorrelativo('1')
			->setFechaEmision(new DateTime())
			->setTipoMoneda('PEN')
			->setCompany($company)
			->setClient($client)
			->setMtoOperGravadas(100)
			->setMtoIGV(18)
			->setTotalImpuestos(18)
			->setValorVenta(100)
			->setSubTotal(118)
			->setMtoImpVenta(118)
			;

		$item1 = new SaleDetail();
		$item1->setCodProducto('C023')
			->setUnidad('NIU')
			->setCantidad(2)
			->setDescripcion('PROD 1')
			->setMtoBaseIgv(100)
			->setPorcentajeIgv(18)
			->setIgv(18)
			->setTipAfeIgv('10')
			->setTotalImpuestos(18)
			->setMtoValorVenta(100)
			->setMtoValorUnitario(50)
			->setMtoPrecioUnitario(59);

		$legend = new Legend();
		$legend->setCode('1000')
			->setValue('SON CIENTO DIECIOCHO CON 00/100 SOLES');

		$invoice->setDetails([$item1])->setLegends([$legend]);
		
		$see = $this->set_see();
		$see->setService(SunatEndpoints::FE_BETA);
		//$see->setService(SunatEndpoints::FE_PRODUCCION);
		
		try{
			ini_set('display_errors', 0);
			$result = $see->send($invoice);
			ini_set('display_errors', 1);
			
			if (!$result->isSuccess()) $msg = $result->getError()->getMessage();
		}catch (Exception $e){ $msg = "Problema de certificado.<br/><br/>".$e->getMessage(); }
		
		return $msg;
	}
}