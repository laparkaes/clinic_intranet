<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;
use Luecano\NumeroALetras\NumeroALetras;

class Sale extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/Lima');
		$this->lang->load("system", "spanish");
		$this->lang->load("sale", "spanish");
		$this->load->model('product_model','product');
		$this->load->model('appointment_model','appointment');
		$this->load->model('surgery_model','surgery');
		$this->load->model('status_model','status');
		$this->load->model('account_model','account');
		$this->load->model('specialty_model','specialty');
		$this->nav_menu = "sale";
	}
	
	private function update_balance($sale_id){
		//update paid and balance
		$total_paid = 0;
		$payments = $this->general->filter("payment", array("sale_id" => $sale_id), "registed_at", "asc");
		foreach($payments as $item) $total_paid = $total_paid + $item->received - $item->change;
		
		$sale = $this->general->id("sale", $sale_id);
		$sale_data = array("paid" => $total_paid, "balance" => $sale->total - $total_paid);
		$cancel_id = $this->status->code("canceled")->id;
		if ($sale_data["balance"]){
			if ($sale->status_id != $cancel_id) $sale_data["status_id"] = $this->status->code("in_progress")->id;
			$sale_data["is_finished"] = false;
		}else{
			if ($sale->status_id != $cancel_id) $sale_data["status_id"] = $this->status->code("finished")->id;
			$sale_data["is_finished"] = true;
		}
		
		$this->general->update("sale", $sale->id, $sale_data);
	}
	
	public function index(){
		if (!$this->session->userdata('logged_in')) redirect(base_url());
		
		$f_url = [
			"page" => $this->input->get("page"),
			"status" => $this->input->get("status"),
			"date" => $this->input->get("date"),
			"keyword" => $this->input->get("keyword"),
		];
		
		$f_w = $f_l = $f_in = [];
		if (!$f_url["page"]) $f_url["page"] = 1;
		
		
		$sales = $this->general->filter("sale", $f_w, $f_l, $f_in, "registed_at", "desc", 25, 25 * ($f_url["page"] - 1));
		
		
		
		$specialties = $this->specialty->all();
		$specialties_arr = array();
		if ($specialties) foreach($specialties as $item) $specialties_arr[$item->id] = $item->name;
		
		$clients = array();
		$clients_rec = $this->general->all("person");
		foreach($clients_rec as $c) $clients[$c->id] = $c->name;
		
		$f_from = $this->input->get("f_from"); if (!$f_from) $f_from = date("Y-m-d", strtotime("-6 months"));
		$filter = array("registed_at >=" => $f_from);
		
		$currencies = array();
		$currencies_rec = $this->general->all("currency");
		foreach($currencies_rec as $item) $currencies[$item->id] = $item;
		
		$status = array();
		$status_rec = $this->general->all("status");
		foreach($status_rec as $item) $status[$item->id] = $item;
		
		$data = array(
			"paging" => $this->my_func->set_page($f_url["page"], $this->general->counter("appointment", $f_w)),
			"f_url" => $f_url,
			"f_from" => $f_from,
			"clients" => $clients,
			"currencies" => $currencies,
			"status" => $status,
			"surgeries" => array(),
			"sale_type" => $this->general->all("sale_type", "description", "asc"),
			"doc_types" => $this->general->all("doc_type", "sunat_code", "asc"),
			"payment_methods" => $this->general->filter("sl_option", array("code" => "payment_method")),
			"sales" => $sales,
			"title" => $this->lang->line('sales'),
			"main" => "sale/list",
			"init_js" => "sale/list.js"
		);
		
		$this->load->view('layout', $data);
	}
	
	public function load_reservations(){
		$appointments = $surgeries = array();
		$filter = array("status_id" => $this->status->code("reserved")->id, "patient_id" => $this->input->post("person_id"));
		
		$appointments_db = $this->general->filter("appointment", $filter, "schedule_from", "desc");
		$surgeries_db = $this->general->filter("surgery", $filter, "schedule_from", "desc");
		
		foreach($appointments_db as $item){
			$p = $this->general->id("person", $item->doctor_id);
			$d = $this->general->filter("doctor", array("person_id" => $p->id))[0];
			$s = $this->specialty->id($d->specialty_id);
			
			array_push($appointments, array("id" => $item->id, "op" => date("d.m.Y", strtotime($item->schedule_from))." # ".date("h:i A", strtotime($item->schedule_from))." - ".date("h:i A", strtotime($item->schedule_to))." # ".$p->name." # ".$s->name));
		}
		
		foreach($surgeries_db as $item){
			$p = $this->general->id("person", $item->doctor_id);
			$d = $this->general->filter("doctor", array("person_id" => $p->id))[0];
			$s = $this->specialty->id($d->specialty_id);
			
			array_push($surgeries, array("id" => $item->id, "op" => date("d.m.Y", strtotime($item->schedule_from))." # ".date("h:i A", strtotime($item->schedule_from))." - ".date("h:i A", strtotime($item->schedule_to))." # ".$p->name." # ".$s->name." # ".$item->place));
		}
		
		header('Content-Type: application/json');
		echo json_encode(array("appointments" => $appointments, "surgeries" => $surgeries));
	}
	
	public function add(){
		$status = false; $type = "error"; $msg = null; $msgs = array(); $move_to = null;
		$sale = $this->input->post("sale");
		$client = $this->input->post("client");
		$payment = $this->input->post("payment");
		$currency = $this->input->post("currency");
		$products = $this->input->post("products");
		
		//client validation
		$doc_type = $this->general->id("doc_type", $client["doc_type_id"]);
		if ($doc_type->sunat_code){
			if (!$client["doc_number"]) $msgs = $this->set_msg($msgs, "client_doc_number_msg", "error", "error_idn");
			if (!$client["name"]) $msgs = $this->set_msg($msgs, "client_name_msg", "error", "error_icn");
		}
		
		//total vs balance validation
		if ($sale["total"] <= $payment["balance"]) $msg = $this->lang->line('error_npa');
		
		//product stock validation
		$op_stock_arr = $cat_prod_arr = array();
		if ($products){
			$product_type_id_prod = $this->general->filter("product_type", array("description" => "Producto"))[0]->id;
			foreach($products as $p){
				$prod = $this->general->id("product", $p["product_id"]);
				array_push($cat_prod_arr, $this->general->id("product_category", $prod->category_id)->name." ".$prod->description);
				
				if ($prod->type_id == $product_type_id_prod){
					if ($p["option_id"]){
						$prod_option = $this->general->id("product_option", $p["option_id"]);
						if (!array_key_exists($p["option_id"], $op_stock_arr))
							$op_stock_arr[$p["option_id"]] = $prod_option->stock;
						
						$op_stock_arr[$p["option_id"]] = $op_stock_arr[$p["option_id"]] - $p["qty"];
						if ($op_stock_arr[$p["option_id"]] < 0){
							$msg = str_replace("%product%", $prod->description, $this->lang->line('error_spons'));
							$msg = str_replace("%option%", $prod_option->description, $msg);
						}
					}else $msg = str_replace("%product%", $prod->description, $this->lang->line('error_spo'));
				}
			}
		}else $msg = $this->lang->line('error_spr');
		
		//appointment & surgery item validation
		$need_app = $need_sur = false;
		foreach($cat_prod_arr as $item){
			if (strpos($item, "Consulta") !== false) $need_app = true;
			if (strpos($item, "Cirugía") !== false) $need_sur = true;
		}
		
		if ($need_app){
			if (!$sale["appointment_id"]) $msg = $this->lang->line('error_sra');
		}else{
			if ($sale["appointment_id"]) $msg = $this->lang->line('error_sai');
			else $sale["appointment_id"] = null;
		}
		
		if ($need_sur){
			if (!$sale["surgery_id"]) $msg = $this->lang->line('error_srs');
		}else{
			if ($sale["surgery_id"]) $msg = $this->lang->line('error_ssi');
			else $sale["surgery_id"] = null;
		} 
		
		if ($msgs) $msg = $this->lang->line('error_occurred');
		elseif (!$msg){
			$now = date('Y-m-d H:i:s', time());
			
			//client id asign
			if ($doc_type->sunat_code){//any document selected (Sin Documento = 0)
				$f = $client; unset($f["name"]);
				$client_rec = $this->general->filter("person", $f);
				if ($client_rec){//update client info
					$sale["client_id"] = $client_rec[0]->id;
					$this->general->update("person", $client_rec[0]->id, $client);
				}else{//create new client
					$client["registed_at"] = $now;
					$sale["client_id"] = $this->general->insert("person", $client);
				}
			}else $sale["client_id"] = null;//sale without client info
			
			//set sale data
			$sale["currency_id"] = $this->general->filter("currency", array("description" => $currency))[0]->id;
			$sale["paid"] = 0;
			$sale["balance"] = $sale["total"];
			$sale["is_finished"] = false;
			$sale["registed_at"] = $sale["updated_at"] = $now;
			$sale_id = $this->general->insert("sale", $sale);
			if ($sale_id){
				$payment["sale_id"] = $sale_id;
				$payment["registed_at"] = $now;
				if ($this->general->insert("payment", $payment)){
					$this->update_balance($sale_id);
					$sale_type_id = $this->general->filter("sale_type", array("description" => "Servicio"))[0]->id;
					
					//process sale products
					foreach($products as $p){
						$prod = $this->general->id("product", $p["product_id"]);
						$prod->type = $this->general->id("product_type", $prod->type_id)->description;
						$p["sale_id"] = $sale_id;
						$p["price"] = $prod->price;
						if ($this->general->insert("sale_product", $p)){
							//stock control
							if (!strcmp("Producto", $prod->type)){
								$sale_type_id = $this->general->filter("sale_type", array("description" => "Producto"))[0]->id;
								$prod_op = $this->general->id("product_option", $p["option_id"]);
								$prod_op_data = array("stock" => $prod_op->stock - $p["qty"]);
								$this->general->update("product_option", $prod_op->id, $prod_op_data);
							}
						}
					}

					//update appointment or surgery if this is case
					if ($sale["appointment_id"] or $sale["surgery_id"]){
						$status_data = array("status_id" => $this->status->code("confirmed")->id);
						if ($sale["appointment_id"]) $this->appointment->update($sale["appointment_id"], $status_data);
						if ($sale["surgery_id"]) $this->surgery->update($sale["surgery_id"], $status_data);
					}
					
					$this->general->update("sale", $sale_id, array("sale_type_id" => $sale_type_id));

					//success
					$this->utility_lib->add_log("sale_register", $this->lang->line('sale')." #".$sale_id);
					
					$move_to = base_url()."sale/detail/".$sale_id;
					$status = true;
					$type = "success";
					$msg = $this->lang->line('success_isa');
				}else $msg = $this->lang->line('error_internal');
			}else $msg = $this->lang->line('error_internal');
			
			//rollback
			if (!$status) if ($sale_id){
				$this->general->delete("sale", array("id" => $sale_id));
				$this->general->delete("payment", array("sale_id" => $sale_id));
				
				$sale_products = $this->general->filter("sale_product", array("sale_id" => $sale_id));
				if ($sale_products){
					//reverse stocks to product
					foreach($sale_products as $p){
						$prod_op = $this->general->id("product_option", $p->option_id);
						$prod_op_data = array("stock" => $prod_op->stock + $p->qty);
						$this->general->update("product_option", $prod_op->id, $prod_op_data);
					}
					$this->general->delete("sale_product", array("sale_id" => $sale_id));
				}
				
			}
		}
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msg" => $msg, "msgs" => $msgs, "move_to" => $move_to));
	}
	
	public function load_product_list(){
		$currencies = array();
		$currencies_rec = $this->general->all("currency");
		foreach($currencies_rec as $item) $currencies[$item->id] = $item->description;
		
		$prod_categories = array();
		$prod_categories_rec = $this->general->all("product_category");
		foreach($prod_categories_rec as $item) $prod_categories[$item->id] = $item->name;
		
		$products = array();
		$products_rec = $this->general->filter("product", array("active" => true));
		foreach($products_rec as $item){
			$currency = $currencies[$item->currency_id];
			$label = array(
				$prod_categories[$item->category_id], 
				$item->description, 
				$currency." ".number_format($item->price)
			);
			
			array_push($products, array("value" => $item->id, "label" => implode(", ", $label), "currency" => $currency));
		}
		usort($products, function($a, $b) {return strcmp($a["label"], $b["label"]);});
		
		header('Content-Type: application/json');
		echo json_encode($products);
	}
	
	public function load_product(){
		$status = false; $msg = $prod = $options = null;
		
		
		$product = $this->general->id("product", $this->input->post("id"));
		if ($product){
			$category = $this->general->id("product_category", $product->category_id)->name;
			
			$prod = array(
				"id" => $product->id,
				"description" => $category.", ".$product->description,
				"type" => $this->general->id("product_type", $product->type_id)->description,
				"currency" => $this->general->id("currency", $product->currency_id)->description,
				"price" => $product->price,
				"price_txt" => number_format($product->price, 2),
				"value" => $product->value,
				"vat" => $product->vat
			);
			
			$options = $this->general->filter("product_option", array("product_id" => $product->id));
			
			$status = true;
		}else $msg = $this->lang->line('error_internal');
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "msg" => $msg, "prod" => $prod, "options" => $options));
	}
	
	public function detail($id){
		if (!$this->session->userdata('logged_in')) redirect(base_url());
		//pending! rol validation
		
		$this->update_balance($id);
		
		$sale = $this->general->id("sale", $id);
		$sale->type = $this->general->id("sale_type", $sale->sale_type_id);
		$sale->currency = $this->general->id("currency", $sale->currency_id)->description;
		$sale->status_code = $this->status->id($sale->status_id)->code;
		$sale->status = $this->lang->line($sale->status_code);
		switch($sale->status_code){
			case "in_progress": $sale->status_color = "warning"; break;
			case "canceled": $sale->status_color = "danger"; break;
			default: $sale->status_color = "success";
		}
		
		$company = new stdClass;
		$company->doc_type_id = $this->general->filter("doc_type", array("short" => "RUC"))[0]->id;
		if ($sale->client_id){
			$client = $this->general->id("person", $sale->client_id);
			$client->doc_type = $this->general->id("doc_type", $client->doc_type_id)->short;	
			if (!strcmp("RUC", $client->doc_type)){
				$company->name = $client->name;
				$company->ruc = $client->doc_number;
			}else{
				$company->name = null;
				$company->ruc = null;
			}
		}else{
			$client = $this->general->structure("person");
			$client->name = $this->lang->line('txt_no_name');
			$client->doc_type = null;
			$company->name = null;
			$company->ruc = null;
		}
		
		$filter = array("sale_id" => $sale->id);
		
		$payments = $this->general->filter("payment", $filter, "registed_at", "desc");
		foreach($payments as $item) $item->payment_method = $this->sl_option->id($item->payment_method_id)->description;
		
		$products = $this->general->filter("sale_product", $filter);
		foreach($products as $item){
			$item->product = $this->product->id($item->product_id);
			$item->product->category = $this->general->id("product_category", $item->product->category_id)->name;
		}
		
		$voucher = $this->general->filter("voucher", $filter);
		if ($voucher) $voucher = $voucher[0];
		
		$data = array(
			"canceled_id" => $this->status->code("canceled")->id,
			"sale" => $sale,
			"client" => $client,
			"company" => $company,
			"voucher" => $voucher,
			"payments" => $payments,
			"products" => $products,
			"payment_method" => $this->sl_option->code("payment_method"),
			"doc_types" => $this->sl_option->code("doc_identity_type"),
			"voucher_types" => $this->general->all("voucher_type", "description", "asc"),
			"title" => $this->lang->line('sale'),
			"main" => "sale/detail",
			"init_js" => "sale/detail.js"
		);
		
		$this->load->view('layout', $data);
	}

	public function add_payment(){
		$payment = $this->input->post();
		$status = false; $type = "error"; $msg = null;
		
		//update last sale status
		$this->update_balance($payment["sale_id"]);
		$sale = $this->general->id("sale", $payment["sale_id"]);
		
		//validate sale balance and total payment amount
		if ($sale->balance != $payment["total"]) $msg = $this->lang->line("error_bup");
		
		if (!$msg){
			unset($payment["total"]);//remove total field of payment
			$payment["registed_at"] = date('Y-m-d H:i:s', time());
			if ($this->general->insert("payment", $payment)){//register payment
				$this->update_balance($payment["sale_id"]);
				$this->utility_lib->add_log("payment_register", $this->lang->line('sale')." #".$sale->id);
				
				$status = true;
				$type = "success";
				$msg = $this->lang->line("success_ipa");
			}else $msg = $this->lang->line("error_internal");
		}
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msg" => $msg));
	}
	
	public function delete_payment(){
		$status = false; $type = "error"; $msg = null;
		$payment = $this->general->id("payment", $this->input->post("id"));
		
		//pending!! role validation
		if ($this->general->delete("payment", array("id" => $payment->id))){
			$this->update_balance($payment->sale_id);
			$this->utility_lib->add_log("payment_delete", $this->lang->line('sale')." #".$payment->sale_id);
			
			$status = true;
			$type = "success";
			$msg = $this->lang->line("success_dpa");
		}else $msg = $this->lang->line("error_internal");
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msg" => $msg));
	}
	
	public function cancel_sale(){
		$status = false; $type = "error"; $msg = null;
		$sale = $this->general->id("sale", $this->input->post("id"));
		
		if (!$msg){
			//update appointment or surgery if this is case
			if ($sale->appointment_id or $sale->surgery_id){
				$status_data = array("status_id" => $this->status->code("reserved")->id);
				if ($sale->appointment_id) $this->appointment->update($sale->appointment_id, $status_data);
				if ($sale->surgery_id) $this->surgery->update($sale->surgery_id, $status_data);
			}
			
			$sale_data = array("status_id" => $this->status->code("canceled")->id);
			if ($this->general->update("sale", $sale->id, $sale_data)){
				$this->utility_lib->add_log("sale_cancel", $this->lang->line('sale')." #".$sale->id);
				
				$status = true;
				$type = "success";
				$msg = $this->lang->line("success_csa");
			}else $msg = $this->lang->line("error_internal");
		}
		
		if ($status){
			//cancel voucher
			$voucher = $this->general->filter("voucher", array("sale_id" => $sale->id));
			if ($voucher){
				$voucher = $voucher[0];
				
				//update voucher status in DB
				$this->general->update("voucher", $voucher->id, ["status_id" => $this->status->code("canceled")->id]);
				$this->utility_lib->add_log("voucher_cancel", $this->lang->line('sale')." #".$sale->id);
				
				//send cancel request to sunat
				$sunat_result = $this->utility_lib->cancel_voucher_sunat($this->set_voucher_data($voucher->id));
				$this->general->update("voucher", $voucher->id, $sunat_result);
				if ($sunat_result["sunat_sent"]){$color = "success"; $ic = "check";}
				else{$color = "danger"; $ic = "times";}
				
				$msg = $msg.'<br/><br/><span class="text-'.$color.'">Sunat <i class="fas fa-'.$ic.'"></i></span><br/>'.$sunat_result["sunat_msg"];
			}
			
			//update products stocks
			$products = $this->general->filter("sale_product", ["sale_id" => $sale->id]);
			foreach($products as $item){
				$p_op = $this->general->id("product_option", $item->option_id);
				$new_stock = $p_op->stock + $item->qty;
				$this->general->update("product_option", $p_op->id, ["stock" => $new_stock]);
				
				$sum = $this->general->sum("product_option", "stock", ["product_id" => $item->product_id]);
				$this->general->update("product", $item->product_id, ["stock" => $sum->stock]);
			}
		}
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msg" => $msg));
	}
	
	private function create_voucher($sale, $voucher_type_id, $client_id){
		$filter = array("sale_id" => $sale->id);
		
		$voucher_type = $this->general->id("voucher_type", $voucher_type_id);
		$currency = $this->general->id("currency", $sale->currency_id);
		$payments = $this->general->filter("payment", $filter);
		
		$voucher_data = array(
			"status_id" => $this->status->code("confirmed")->id,
			"type" => $voucher_type->description,
			"code" => $voucher_type->sunat_code,
			"letter" => substr($voucher_type->description, 0, 1),
			"serie" => $this->general->id("sale_type", $sale->sale_type_id)->sunat_serie
		);
		
		$last_voucher = $this->general->filter("voucher", $voucher_data, "correlative", "desc", 1, 0);
		if ($last_voucher) $voucher_data["correlative"] = $last_voucher[0]->correlative + 1;
		else $voucher_data["correlative"] = 1;
		
		switch($currency->sunat_code){
			case "USD": $formatter_currency = "DÓLARES"; break;
			default: $formatter_currency = "SOLES";//PEN
		}
		
		if (count($payments) == 1){
			$payment_method = $this->sl_option->id($payments[0]->payment_method_id);
			$voucher_data["received"] = $payments[0]->received;
			$voucher_data["change"] = $payments[0]->change;
		}else{
			$payment_method = $this->sl_option->id(63);//Efectivo
			$voucher_data["received"] = $sale->total;
			$voucher_data["change"] = 0;
		}
		
		$formatter = new NumeroALetras();
		$voucher_data["client_id"] = $client_id;
		$voucher_data["payment_method"] = $payment_method->description;
		$voucher_data["currency"] = $currency->description;
		$voucher_data["currency_code"] = $currency->sunat_code;
		$voucher_data["total"] = $sale->total;
		$voucher_data["amount"] = $sale->amount;
		$voucher_data["vat"] = $sale->vat;
		$voucher_data["legend"] = $formatter->toInvoice($sale->total, 2, $formatter_currency);
		$voucher_data["hash"] = substr(password_hash(implode("", $voucher_data), PASSWORD_BCRYPT), -28, 28);
		$voucher_data["registed_at"] = date('Y-m-d H:i:s', time());
		$voucher_data["sale_id"] = $sale->id;
		
		$voucher_id = $this->general->insert("voucher", $voucher_data);
		if ($voucher_id) $this->utility_lib->add_log("voucher_register", $this->lang->line('sale')." #".$sale->id." (".$voucher_type->description.")");
		
		return $voucher_id;
	}
	
	private function make_pdf($html, $filename){
		// instantiate and use the dompdf class
		$dompdf = new Dompdf();

		// (Optional) Setup the paper size and orientation
		//$dompdf->setPaper('A4', 'portrait');//vertical [0.0, 0.0, 595.28, 841.89]
		//$dompdf->setPaper('A4', 'landscape');//horizontal
		$dompdf->setPaper(array(0,0,240,600));
		
		$GLOBALS['bodyHeight'] = 0;
		$dompdf->setCallbacks(
			array(
				'myCallbacks' => array(
				'event' => 'end_frame', 'f' => function ($infos) {
					$frame = $infos->get_frame();
					if (!strcmp("body", $frame->get_node()->nodeName))
						$GLOBALS['bodyHeight'] += $frame->get_padding_box()['h'];
				})
			)
		);
		
		$dompdf->loadHtml($html);
		$dompdf->render();
		unset($dompdf);
		
		$dompdf = new Dompdf();
		$dompdf->set_paper(array(0,0,240,$GLOBALS['bodyHeight']+20));

		// Render the HTML as PDF
		$dompdf->loadHtml($html);
		$dompdf->render();
		
		// Output the generated PDF to Browser
		if ($dompdf) $dompdf->stream($filename, array("Attachment" => false));
		else echo "Error";
	}
	
	public function make_voucher(){
		$status = false; $type = "error"; $msg = null; $msgs = array();
		$sale = $this->general->id("sale", $this->input->post("sale_id"));
		$voucher_type = $this->general->id("voucher_type", $this->input->post("voucher_type_id"));
		$company = $this->input->post("company");
		
		/*
		validations
		1. voucher exists?
		2. finished sale?
		3. company info in case of "Factura"
		*/
		if ($this->general->filter("voucher", array("sale_id" => $sale->id))) $msg = $this->lang->line('error_voe');
		elseif ($sale->balance) $msg = $this->lang->line('error_sba');
		elseif (!strcmp("Factura", $voucher_type->description)){
			if (!$company["name"]) $msgs = $this->set_msg($msgs, "vou_com_name_msg", "error", "error_icor");
			if (!$company["doc_number"]) $msgs = $this->set_msg($msgs, "vou_com_ruc_msg", "error", "error_icr");
		}
		
		if (!$msg and !$msgs){
			if (!strcmp("Factura", $voucher_type->description)){
				$f = $company; unset($f["name"]);
				$person = $this->general->filter("person", $f);
				if ($person) $client_id = $person[0]->id;
				else $client_id = $this->general->insert("person", $company);
			}else $client_id = $sale->client_id;
			
			$voucher_id = $this->create_voucher($sale, $voucher_type->id, $client_id);
			if ($voucher_id){
				//make sunat process
				$sunat_result = $this->utility_lib->send_sunat($this->set_voucher_data($voucher_id));
				$this->general->update("voucher", $voucher_id, $sunat_result);
				
				if ($sunat_result["sunat_sent"]){ $color = "success"; $ic = "check"; } 
				else{ $color = "danger"; $ic = "times"; }
				
				$status = true;
				$type = "success";
				$msg = str_replace("#type#", $voucher_type->description, $this->lang->line('success_gvo')).'<br/><br/><span class="text-'.$color.'">Sunat <i class="fas fa-'.$ic.'"></i></span><br/>'.$sunat_result["sunat_msg"];
			}
		}elseif (!$msg) $msg = $this->lang->line('error_occurred');
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msg" => $msg, "msgs" => $msgs));
	}
	
	private function set_voucher_data($id){
		$voucher = $this->general->id("voucher", $id);
		
		if ($voucher->client_id){
			$client = $this->general->id("person", $voucher->client_id);
			$client->doc_type = $this->general->id("doc_type", $client->doc_type_id);	
		}else{
			$client = $this->general->structure("person");
			$client->doc_type = $this->general->filter("doc_type", array("sunat_code" => 0))[0];
		}
		
		$company = $this->general->id("company", 1);
		$company->department = $this->general->id("address_department", $company->department_id)->name;
		$company->province = $this->general->id("address_province", $company->province_id)->name;
		$company->district = $this->general->id("address_district", $company->district_id)->name;
		
		$products = $this->general->filter("sale_product", array("sale_id" => $voucher->sale_id));
		foreach($products as $item){
			$item->unit_price = $item->price - $item->discount;
			$item->value = round($item->unit_price/1.18, 2);
			$item->vat = $item->unit_price - $item->value;
			$item->data = $this->general->id("product", $item->product_id);
			$item->type = $this->general->id("product_type", $item->data->type_id);
			if ($item->option_id) $item->data->description = $item->data->description." ".$this->general->id("product_option", $item->option_id)->description;
		}
		
		return array("voucher" => $voucher, "client" => $client, "company" => $company, "products" => $products);
	}
	
	public function voucher($id){
		$data = $this->set_voucher_data($id);
		$invoice = $this->utility_lib->make_invoice_greenter($data);
		
		//QR Code => RUC|TIPO DE DOCUMENTO|SERIE|NUMERO|MTO TOTAL IGV|MTO TOTAL DEL COMPROBANTE|FECHA DE EMISION|TIPO DE DOCUMENTO ADQUIRENTE|NUMERO DE DOCUMENTO ADQUIRENTE
		$qr_data = array(
			$invoice->getCompany()->getRuc(), $invoice->getTipoDoc(), $invoice->getSerie(), $invoice->getCorrelativo(), 
			$invoice->getTotalImpuestos(), $invoice->getMtoImpVenta(), $invoice->getFechaEmision()->format('Y-m-d'), 
			$invoice->getClient()->getTipoDoc(), $invoice->getClient()->getNumDoc(), $data["voucher"]->hash
		);
			
		$this->load->library('ciqrcode');
		$qr_params = array(
			"data" => implode("|", $qr_data), "level" => 'H', 
			"size" => 10, "savename" => FCPATH.'/uploaded/qr.png'
		);
		
		$data["qr"] = base64_encode(file_get_contents($this->ciqrcode->generate($qr_params)));
		$data["title"] = $invoice->getSerie()." - ".str_pad($invoice->getCorrelativo(), 6, '0', STR_PAD_LEFT);
		$data["logo"] = base64_encode(file_get_contents(FCPATH."/resorces/images/logo.png"));
		$data["invoice"] = $invoice;
		
		//echo $this->load->view("voucher/invoice", $data, true);
		$this->make_pdf($this->load->view("voucher/invoice", $data, true), $data["title"]);
	}
	
	public function payment_report($sale_id){
		$sale = $this->general->id("sale", $sale_id);
		$filter = array("sale_id" => $sale->id);
		//if (!$sale->balance){echo "Esta venta no cuenta con saldo pendiente."; return;}
		
		if ($sale->client_id){
			$client = $this->general->id("person", $sale->client_id);
			$client->doc_type = $this->general->id("doc_type", $client->doc_type_id);	
		}else $client = $this->general->structure("person");
		
		$company = $this->general->id("company", 1);
		$company->department = $this->general->id("address_department", $company->department_id)->name;
		$company->province = $this->general->id("address_province", $company->province_id)->name;
		$company->district = $this->general->id("address_district", $company->district_id)->name;
		
		$currency = $this->general->id("currency", $sale->currency_id);
		$payments = $this->general->filter("payment", $filter);
		
		$products = $this->general->filter("sale_product", $filter);
		foreach($products as $item){
			$prod = $this->general->id("product", $item->product_id);
			$item->unit_price = $item->price - $item->discount;
			$item->value = round($item->unit_price/1.18, 2);
			$item->vat = $item->unit_price - $item->value;
			$item->description = $prod->description;
			if ($item->option_id) $item->description = $item->description." ".$this->general->id("product_option", $item->option_id)->description;
		}
		
		$title = "REPORTE DE PAGOS";
		
		$data = array(
			"sale" => $sale,
			"client" => $client,
			"company" => $company,
			"currency" => $currency,
			"payments" => $payments,
			"products" => $products,
			"title" => $title,
			"logo" => base64_encode(file_get_contents(FCPATH."/resorces/images/logo.png")),
		);
		
		//echo $this->load->view("voucher/ticket", $data, true);
		$this->make_pdf($this->load->view("voucher/ticket", $data, true), $title);
	}
}
