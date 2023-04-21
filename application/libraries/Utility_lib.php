<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Greenter\Model\Client\Client;
use Greenter\Model\Company\Company;
use Greenter\Model\Company\Address;
use Greenter\Model\Sale\FormaPagos\FormaPagoContado;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;
use Greenter\Report\HtmlReport;

class Utility_lib{
	
	public function __construct(){
		$this->CI = &get_instance();
		$this->CI->lang->load("system", "spanish");
		$this->CI->load->model('sl_option_model','sl_option');
		$this->CI->load->model('general_model','general');
	}
	
	public function clean_array($data){
		foreach($data as $i => $val) if (!$val) $data[$i] = null;
		return $data;
	}
	
	public function age_calculator($birthday, $need_number = false){
		$date1 = date_create($birthday);
		$date2 = date_create(date("Y-m-d"));
		$diff = date_diff($date1, $date2);
		
		if ($need_number) return $diff->y;
		else return $diff->y."A ".$diff->m."M";
	}
	
	public function cancel_voucher_sunat($voucher_data){
		$invoice = $this->make_invoice_greenter($voucher_data);
		/*
		1. cancel voucher to sunat
		2. set response parameter
		*/
		
		//return array("sunat_sent" => false, "sunat_msg" => "ocurrio error de comunicacion con sunat.");
		return array("sunat_sent" => true, "sunat_msg" => "Factura electronica anulada.");
	}
	
	public function utildatos_dni($dni){
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://utildatos.com/api/dni',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => false,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => array('dni' => $dni),
			CURLOPT_HTTPHEADER => array('Authorization: Bearer {3a0d55aad08e889e277a8585e6d24e}'),
		));

		$response = json_decode(curl_exec($curl));
		curl_close($curl);
		
		$res = new stdClass;
		$res->status = false;
		$res->msg = $this->CI->lang->line("error_search_dni");
		$res->data = null;
		
		if (property_exists($response, 'success')){
			if ($response->success){
				$res->status = true;
				$res->msg = null;
				$res->data = $response->result;
			}
		}
		
		return $res;
	}
	
	public function utildatos_ruc($ruc){
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://utildatos.com/api/sunat-reducido',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => false,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => array('ruc' => $ruc),
			CURLOPT_HTTPHEADER => array('Authorization: Bearer {3a0d55aad08e889e277a8585e6d24e}')
		));

		$response = json_decode(curl_exec($curl));
		curl_close($curl);
		
		$res = new stdClass;
		$res->status = false;
		$res->msg = $this->CI->lang->line("error_search_ruc");
		$res->data = null;
		
		if (property_exists($response, 'success')){
			if ($response->success){
				$res->status = true;
				$res->msg = null;
				$res->data = $response->result;
			}
		}
		
		return $res;
		
		//stdClass Object ( [status] => [message] => No se encontro el ruc ) 
		//stdClass Object ( [success] => 1 [result] => stdClass Object ( [ruc] => 20557939645 [razon_social] => MOARA PERU E.I.R.L. [estado] => SUSPENSION TEMPORAL [condicion_domicilio] => HABIDO [ubigeo] => 150130 [tipo_via] => AV. [nombre_via] => SAN BORJA SUR [codigo_zona] => URB. [tipo_zona] => SAN BORJA [numero] => 689 [interior] => - [lote] => - [departamento] => 401 [manzana] => - [kilometro] => - [direccion] => AV. SAN BORJA SUR URB. SAN BORJA Nro. 689 Dpto. 401 ) ) 
	}
	
	public function send_sunat($voucher_data){
		$invoice = $this->make_invoice_greenter($voucher_data);
		/*
		1. send voucher to sunat
		2. set response parameter
		*/
		
		//return array("sunat_sent" => false, "sunat_msg" => "ocurrio error de comunicacion con sunat.");
		return array("sunat_sent" => true, "sunat_msg" => "Factura electronica recibida.");
	}
	
	public function make_invoice_greenter($voucher_data){
		$vo = $voucher_data["voucher"];
		$cl = $voucher_data["client"];
		$co = $voucher_data["company"];
		$pr = $voucher_data["products"];
	
		//Cliente
		$client = (new Client())
			->setTipoDoc($cl->doc_type->sunat_code)
			->setNumDoc($cl->doc_number)
			->setRznSocial($cl->name);
		
		//Emisor
		$address = (new Address())
			->setUbigueo($co->ubigeo)
			->setDepartamento($co->department)
			->setProvincia($co->province)
			->setDistrito($co->district)
			->setUrbanizacion($co->urbanization)
			->setDireccion($co->address)
			->setCodLocal('0000'); // Codigo de establecimiento asignado por SUNAT, 0000 por defecto.
			
		$company = (new Company())
			->setRuc($co->ruc)
			->setRazonSocial($co->name)
			->setNombreComercial($co->name)
			->setAddress($address);

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

		$items = array();
		foreach($pr as $item){
			$aux = (new SaleDetail())
				->setCodProducto($item->data->code)
				->setUnidad($item->type->sunat_code) // Unidad - Catalog. 03
				->setCantidad($item->qty)
				->setMtoValorUnitario($item->unit_price)
				->setDescripcion($item->data->description)
				->setMtoBaseIgv($item->unit_price * $item->qty)
				->setPorcentajeIgv(18.00) // 18%
				->setIgv($item->vat * $item->qty)
				->setTipAfeIgv('10') // Gravado Op. Onerosa - Catalog. 07
				->setTotalImpuestos($item->vat * $item->qty) // Suma de impuestos en el detalle
				->setMtoValorVenta($item->unit_price * $item->qty)
				->setMtoPrecioUnitario($item->unit_price);
			array_push($items, $aux);	
		}

		$legend = (new Legend())
			->setCode('1000') // Monto en letras - Catalog. 52
			->setValue($vo->legend);

		$invoice->setDetails($items)->setLegends([$legend]);
		
		return $invoice;
	}
	
	public function set_voucher_datas($payment_id){
		$payment = $this->CI->general->id("payment", $payment_id);
		if ($payment){
			$payment->currency = $this->CI->sl_option->id($payment->currency_id)->description;
			$payment->payment_method = $this->CI->sl_option->id($payment->payment_method_id)->description;
			$payment->voucher_type = $this->CI->sl_option->id($payment->voucher_type_id)->description;
			$payment->payer_doc_type = $this->CI->sl_option->id($payment->payer_doc_type_id)->description;
			
			$company_rec = $this->CI->general->id("company", 1);
			$company_rec->department = $this->CI->general->id("address_department", $company_rec->department_id)->name;
			$company_rec->province = $this->CI->general->id("address_province", $company_rec->province_id)->name;
			$company_rec->district = $this->CI->general->id("address_district", $company_rec->district_id)->name;
			
			$invoice = $this->set_greenter($payment, $company_rec);
			
			//set qr string
			$qr_data = array(
				$invoice->getCompany()->getRuc(), $invoice->getTipoDoc(), $invoice->getSerie(), $invoice->getCorrelativo(), $invoice->getTotalImpuestos(), 
				$invoice->getMtoImpVenta(), $invoice->getFechaEmision()->format('Y-m-d'), $invoice->getClient()->getTipoDoc(), $invoice->getClient()->getNumDoc(), $payment->sunat_hash
			);
			//'RUC|TIPO DE DOCUMENTO|SERIE|NUMERO|MTO TOTAL IGV|MTO TOTAL DEL COMPROBANTE|FECHA DE EMISION|TIPO DE DOCUMENTO ADQUIRENTE|NUMERO DE DOCUMENTO ADQUIRENTE'
			
			$datas = array(
				"company" => $company_rec,
				"payment" => $payment,
				"title" => $this->CI->lang->line($this->CI->sl_option->id($payment->voucher_type_id)->description."_u")." ".$this->CI->lang->line("of_electronic_sale_u"),
				"logo" => base64_encode(file_get_contents(FCPATH."/resorces/images/logo.png")),
				"resolution_text" => $this->CI->lang->line("resolution_num")." ".$company_rec->sunat_resolution,
				"qr_text" => implode("|", $qr_data)
			);
			
			return array("invoice" => $invoice, "datas" => $datas);
		}else return null;
	}
	
	public function add_log($code, $detail){
		$log_code = $this->CI->general->filter("log_code", ["code" => $code]);
		if ($log_code) $this->CI->general->insert("log", ["account_id" => $this->CI->session->userdata('aid'), "log_code_id" => $log_code[0]->id, "detail" => $detail, "registed_at" => date('Y-m-d H:i:s', time())]);
	}
}