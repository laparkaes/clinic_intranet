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
use Greenter\Report\HtmlReport;
use Greenter\Ws\Services\SunatEndpoints;
use Greenter\See;

class Greenter_lib{
	
	public function __construct(){
		$this->CI = &get_instance();
		$this->ruc = '20000000001';
		$this->user = 'MODDATOS';
		$this->pass = 'moddatos';
		$this->cert_path = FCPATH."uploaded/sunat/cert.pem";
	}
	
	private function set_see(){
		$see = new See();
		$see->setCertificate(file_get_contents($this->cert_path));
		$see->setService(SunatEndpoints::FE_BETA);
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
		$client = $this->set_company($voucher_data["client"]);
		
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

		$upload_dir = $_SERVER['DOCUMENT_ROOT']."/archivos/sunat/".date("Ymd");
		if(!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
		$upload_dir = $upload_dir."/";

		// Guardar XML firmado digitalmente.
		file_put_contents($upload_dir.$invoice->getName().'.xml', $see->getFactory()->getLastXml());

		// Verificamos que la conexión con SUNAT fue exitosa.
		if ($result->isSuccess()){
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

		// Guardamos el CDR
		file_put_contents($upload_dir.'R-'.$invoice->getName().'.zip', $result->getCdrZip());
		
		return ["sunat_sent" => $sunat_sent, "sunat_msg" => $sunat_msg, "sunat_notes" => $sunat_notes];
	}
	
	private function set_invoice_void($voucher_data, $reason){
		$company = $this->set_company($voucher_data["company"]);
		
		$detail1 = new VoidedDetail();
		$detail1->setTipoDoc('01') // Factura
			->setSerie('F001')
			->setCorrelativo('1')
			->setDesMotivoBaja('ERROR EN CÁLCULOS'); // Motivo por el cual se da de baja.

		$detail2 = new VoidedDetail();
		$detail2->setTipoDoc('07') // Nota de Crédito
			->setSerie('FC01')
			->setCorrelativo('2')
			->setDesMotivoBaja('ERROR DE RUC');

		$invoice_void = new Voided();
		$invoice_void->setCorrelativo('00001') // Correlativo, necesario para diferenciar c. de baja de en un mismo día.
			->setFecGeneracion(new \DateTime('2020-08-01')) // Fecha de emisión de los comprobantes a dar de baja
			->setFecComunicacion(new \DateTime('2020-08-02')) // Fecha de envio de la C. de baja
			->setCompany($company)
			->setDetails([$detail1, $detail2]);

		return $invoice_void;
	}
	
	public function void_sunat($voucher_data, $data){
		
		
		$see = $this->set_see();
		$invoice_void = $this->set_invoice_void($voucher_data, $data["reason"]);
		$result = $see->send($invoice_void);
		
		$upload_dir = $_SERVER['DOCUMENT_ROOT']."/archivos/sunat/".date("Ymd")."/anulados";
		if(!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
		$upload_dir = $upload_dir."/";
		
		// Guardar XML
		file_put_contents($upload_dir.$invoice_void->getName().'.xml', $see->getFactory()->getLastXml());

		if (!$result->isSuccess()) {
			// Si hubo error al conectarse al servicio de SUNAT.
			var_dump($result->getError());
			exit();
		}

		$ticket = $result->getTicket();
		echo 'Ticket : '.$ticket.PHP_EOL;

		$statusResult = $see->getStatus($ticket);
		if (!$statusResult->isSuccess()) {
			// Si hubo error al conectarse al servicio de SUNAT.
			var_dump($statusResult->getError());
			return;
		}

		echo $statusResult->getCdrResponse()->getDescription();
		// Guardar CDR
		file_put_contents($upload_dir.'R-'.$invoice_void->getName().'.zip', $statusResult->getCdrZip());
		
		//
		/*
		1. cancel voucher to sunat
		2. set response parameter
		*/
		
		//return array("sunat_sent" => false, "sunat_msg" => "ocurrio error de comunicacion con sunat.");
		return array("sunat_sent" => true, "sunat_msg" => "Factura electronica anulada.");
	}
}