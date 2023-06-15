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
use Greenter\Ws\Services\SunatEndpoints;
use Greenter\See;

class Greenter_lib{
	
	public function __construct(){
		$this->CI = &get_instance();
		$this->ruc = '20000000001';
		$this->user = 'MODDATOS';
		$this->pass = 'moddatos';
		$this->cert_path = FCPATH.'uploaded\sunat\certificate.pem';
	}
	
	public function cancel_voucher_sunat($voucher_data){
		$invoice = $this->set_invoice($voucher_data);
		/*
		1. cancel voucher to sunat
		2. set response parameter
		*/
		
		//return array("sunat_sent" => false, "sunat_msg" => "ocurrio error de comunicacion con sunat.");
		return array("sunat_sent" => true, "sunat_msg" => "Factura electronica anulada.");
	}
	
	public function send_sunat($voucher_data){
		$invoice = $this->set_invoice($voucher_data);
		/*
		1. send voucher to sunat
		2. set response parameter
		*/
		$see = new See();
		$see->setCertificate(file_get_contents($this->cert_path));
		$see->setService(SunatEndpoints::FE_BETA);
		$see->setClaveSOL($this->ruc, $this->user, $this->pass);
		
		return ["sunat_sent" => false, "sunat_msg" => "Sunat no chambea."];
		//return ["sunat_sent" => true, "sunat_msg" => "Comprobante aceptado."];
	}
	
	public function set_invoice($voucher_data){
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
			->setRuc($co->tax_id)
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
}