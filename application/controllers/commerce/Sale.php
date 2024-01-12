<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sale extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/Lima');
		$this->lang->load("system", "spanish");
		$this->lang->load("sale", "spanish");
		$this->load->model('general_model','general');
		$this->nav_menu = ["commerce", "sale"];
		$this->nav_menus = $this->utility_lib->get_visible_nav_menus();
		$this->sunat_resolution = "0180050001138";
	}
	
	private function update_balance($sale_id){
		//update paid and balance
		$total_paid = 0;
		$payments = $this->general->filter("payment", ["sale_id" => $sale_id], null, null, "registed_at", "asc");
		foreach($payments as $item) $total_paid = $total_paid + $item->received - $item->change;
		
		$sale = $this->general->id("sale", $sale_id);
		$sale_data = array("paid" => $total_paid, "balance" => $sale->total - $total_paid);
		$cancel_id = $this->general->status("canceled")->id;
		if ($sale->status_id != $cancel_id){
			if ($sale_data["balance"]) $sale_data["status_id"] = $this->general->status("in_progress")->id;
			else $sale_data["status_id"] = $this->general->status("finished")->id;
		}
		
		$this->general->update("sale", $sale->id, $sale_data);
	}
	
	public function index(){
		if (!$this->session->userdata('logged_in')) redirect('/');
		if (!$this->utility_lib->check_access("sale", "index")) redirect("/errors/no_permission");
		
		$f_url = [
			"page" => $this->input->get("page"),
			"client" => $this->input->get("client"),
		];
		
		$f_w = $f_l = $f_in = [];
		if (!$f_url["page"]) $f_url["page"] = 1;
		if ($f_url["client"]){
			$aux = [-1];
			$people = $this->general->filter("person", null, ["name" => $f_url["client"]]);
			foreach($people as $p) $aux[] = $p->id;
			
			$f_in[] = ["field" => "client_id", "values" => $aux];
		}
		
		$sales = $this->general->filter("sale", $f_w, $f_l, $f_in, "updated_at", "desc", 25, 25 * ($f_url["page"] - 1));
		foreach($sales as $item){
			$item->sale_type = $this->general->id("sale_type", $item->sale_type_id);
			$item->currency = $this->general->id("currency", $item->currency_id);
			$item->client = $this->general->id("person", $item->client_id);
			$item->status = $this->general->id("status", $item->status_id);
			$item->status->lang = $this->lang->line($item->status->code);
			
			if ($item->voucher_id){
				$item->voucher = $this->general->id("voucher", $item->voucher_id);
				if ($item->voucher->sunat_sent) $item->voucher->color = "success";
				else $item->voucher->color = "danger";
			}else{
				$item->voucher = $this->general->structure("voucher");
				if ($item->status->code === "canceled"){
					$item->voucher->color = "success";
					$item->voucher->sunat_msg = $this->lang->line('t_canceled_sale');
				}else{
					$item->voucher->color = "warning";
					$item->voucher->sunat_msg = $this->lang->line('t_need_send_sunat');
				}
			}
		}
		
		$status = [
			$this->general->status("in_progress"),
			$this->general->status("finished"),
			$this->general->status("canceled"),
		];
		
		$data = array(
			"paging" => $this->my_func->set_page($f_url["page"], $this->general->counter("sale", $f_w)),
			"f_url" => $f_url,
			"status" => $status,
			"sales" => $sales,
			"title" => $this->lang->line('sales'),
			"main" => "commerce/sale/list",
		);
		
		$this->load->view('layout', $data);
	}
	
	public function add(){
		$type = "error"; $msg = null; $msgs = []; $move_to = null;
		
		if ($this->utility_lib->check_access("sale", "register")){
			$products_json = $this->input->post("sl_pr");
			$client = $this->input->post("client");
			$payment = $this->input->post("payment");
			$currency = $this->input->post("currency");
			$sale_type_id = $this->input->post("sale_type_id");
			$sale_id = null;
			
			//calient validation
			$this->load->library('my_val');
			$msgs = $this->my_val->sale_client($msgs, $client);
			$msgs = $this->my_val->sale_payment($msgs, $payment);
			
			if (!$msgs){
				$res = $this->my_val->sale_products($products_json);
				$msg = $res["msg"];
				if (!$msg){
					//client processing
					$doc_type = $this->general->id("doc_type", $client["doc_type_id"]);
					if ($doc_type->description === "Sin Documento") $client_id = null;
					else{
						$client_rec = $this->general->filter("person", $client);
						if ($client_rec) $client_id = $client_rec[0]->id;
						else $client_id = $this->general->insert("person", $client);
					}
					
					//set sale data
					$currency = $this->general->filter("currency", ["description" => $currency])[0];
					
					//basic sale data
					$now = date('Y-m-d H:i:s', time());
					$sale_data = [
						"sale_type_id" => $sale_type_id,
						"currency_id" => $currency->id,
						"client_id" => $client_id,
						"updated_at" => $now,
						"registed_at" => $now,
					];
					
					//insert products
					$total = 0;
					$sale_id = $this->general->insert("sale", $sale_data);
					$products = $res["products"];
					foreach($products as $item){
						$item->sale_id = $sale_id;
						$this->general->insert("sale_product", $item);
						$total += $item->qty * ($item->price - $item->discount);
						if ($item->option_id){
							$op = $this->general->id("product_option", $item->option_id);
							$this->general->update("product_option", $item->option_id, ["stock" => ($op->stock - $item->qty)]);
							
							$sum = $this->general->sum("product_option", "stock", ["product_id" => $item->product_id]);
							$this->general->update("product", $item->product_id, ["stock" => $sum->stock]);
						}
					}
					
					$payment["sale_id"] = $sale_id;
					$payment["registed_at"] = $now;
					$this->general->insert("payment", $payment);
					
					if ($total == $payment["received"]) $status = $this->general->status("finished");
					else $status = $this->general->status("in_progress");
					
					$amount = round($total / 1.18, 2);
					$vat = round($total - $amount, 2);
					
					$sale_data = [
						"status_id" => $status->id,
						"total" => $total,
						"amount" => $amount,
						"vat" => $vat,
						"paid" => $payment["received"],
						"balance" => $payment["balance"],
					];
					
					if ($this->general->update("sale", $sale_id, $sale_data)){
						$type = "success";
						$msg = $this->lang->line('s_sale_add');
						$move_to = base_url()."commerce/sale/detail/".$sale_id;
					}
				}
			}else $msg = $this->lang->line('error_occurred');
			
			//rollback
			if ($type === "error") if ($sale_id){
				$sale_products = $this->general->filter("sale_product", ["sale_id" => $sale_id]);
				if ($sale_products){
					//reverse stocks to product
					foreach($sale_products as $p){
						$prod_op = $this->general->id("product_option", $p->option_id);
						$prod_op_data = ["stock" => ($prod_op->stock + $p->qty)];
						$this->general->update("product_option", $prod_op->id, $prod_op_data);
					}
					$this->general->delete("sale_product", ["sale_id" => $sale_id]);
				}
				
				$this->general->delete("sale", ["id" => $sale_id]);
				$this->general->delete("payment", ["sale_id" => $sale_id]);
			}
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg, "msgs" => $msgs, "move_to" => $move_to]);
	}
	
	public function detail($id){
		if (!$this->session->userdata('logged_in')) redirect('/');
		if (!$this->utility_lib->check_access("sale", "detail")) redirect("/errors/no_permission");
		
		$this->update_balance($id);
		
		$sale = $this->general->id("sale", $id);
		$sale->type = $this->general->id("sale_type", $sale->sale_type_id);
		$sale->currency = $this->general->id("currency", $sale->currency_id)->description;
		$sale->status = $this->general->id("status", $sale->status_id);
		
		if ($sale->client_id){
			$client = $this->general->id("person", $sale->client_id);
			$client->doc_type = $this->general->id("doc_type", $client->doc_type_id)->short;
		}else{
			$client = $this->general->structure("person");
			$client->doc_type = "";
		}
		
		$filter = ["sale_id" => $sale->id];
		
		$payments = $this->general->filter("payment", $filter, null, null, "registed_at", "desc");
		foreach($payments as $item) 
			$item->payment_method = $this->general->id("payment_method", $item->payment_method_id)->description;
		
		$appo_qty = $surg_qty = 0;
		$products = $this->general->filter("sale_product", $filter);
		foreach($products as $item){
			$item->product = $this->general->id("product", $item->product_id);
			$item->product->category = $this->general->id("product_category", $item->product->category_id)->name;
			if ($item->option_id) $item->product->option = $this->general->id("product_option", $item->option_id)->description;
			else $item->product->option = "";
			
			$item->type = null;
			$item->attention = null;
			$item->path = null;
			if(strpos(strtoupper($item->product->description), strtoupper("consulta")) !== false){
				$appo_qty++;
				$item->type = $this->lang->line('w_appointment');
				if ($item->appointment_id) $item->path = base_url()."appointment/detail/".$item->appointment_id;
				
				if ($item->appointment_id){
					$app = $this->general->id("appointment", $item->appointment_id);
					$patient = $this->general->id("person", $app->patient_id);
					$doc_type = $this->general->id("doc_type", $patient->doc_type_id);
					$item->attention = new stdClass;
					$item->attention->schedule = $app->schedule_from;
					$item->attention->patient = $patient->name;
					$item->attention->patient_doc = $doc_type->short." ".$patient->doc_number;
				}
			}elseif(strpos(strtoupper($item->product->category), strtoupper("cirugía")) !== false){
				$surg_qty++;
				$item->type = $this->lang->line('w_surgery');
				if ($item->surgery_id) $item->path = base_url()."surgery/detail/".$item->surgery_id;
				
				if ($item->surgery_id){
					$sur = $this->general->id("surgery", $item->surgery_id);
					$patient = $this->general->id("person", $sur->patient_id);
					$doc_type = $this->general->id("doc_type", $patient->doc_type_id);
					$item->attention = new stdClass;
					$item->attention->schedule = $sur->schedule_from;
					$item->attention->patient = $patient->name;
					$item->attention->patient_doc = $doc_type->short." ".$patient->doc_number;
				}
			}
		}
		usort($products, function($a, $b) { return strcmp($a->product->description, $b->product->description); });
		
		if ($sale->voucher_id) $voucher = $this->general->id("voucher", $sale->voucher_id);
		else $voucher = $this->general->structure("voucher");
		
		if ($voucher->voucher_type_id) $voucher->type = $this->general->id("voucher_type", $voucher->voucher_type_id)->description;
		else $voucher->type = "";
		
		if ($voucher->status_id) $voucher->status = $this->general->id("status", $voucher->status_id);
		else{
			if ($sale->status->code !== "canceled") $voucher->status = $this->general->status("pending");
			else $voucher->status = $this->general->status("finished");
		}
		
		if ($voucher->sunat_sent === null){
			if ($sale->status->code !== "canceled") $voucher->sunat_msg = $this->lang->line('t_no_voucher');
			else $voucher->sunat_msg = $this->lang->line('t_canceled_sale');
		}
		
		$data = array(
			"canceled_id" => $this->general->status("canceled")->id,
			"appo_qty" => $appo_qty,
			"surg_qty" => $surg_qty,
			"sale" => $sale,
			"client" => $client,
			"voucher" => $voucher,
			"payments" => $payments,
			"products" => $products,
			"payment_method" => $this->general->all("payment_method", "description", "asc"),
			"doc_types" => $this->general->all("doc_type", "id", "asc"),
			"voucher_types" => $this->general->all("voucher_type", "description", "asc"),
			"title" => $this->lang->line('sale'),
			"main" => "commerce/sale/detail",
		);
		
		$this->load->view('layout', $data);
	}

	public function search_reservations(){
		$data = $this->input->post();
		
		$reservations = $patient_ids = [];
		if ($data["doc_number"]){
			$people = $this->general->filter("person", null, ["doc_number" => $data["doc_number"]]);
			foreach($people as $item) $patient_ids[] = $item->id;
		}
		
		if ($patient_ids){
			$w = ["status_id" => $this->general->status("reserved")->id];
			$w_in = [["field" => "patient_id", "values" => $patient_ids]];
			
			$attns = $this->general->filter($data["attn"], $w, null, $w_in, "schedule_from", "asc");
			foreach($attns as $item){
				$patient = $this->general->id("person", $item->patient_id);
				$patient->doc_type = $this->general->id("doc_type", $patient->doc_type_id)->short;
				$reservations[] = [
					"id" => $item->id, 
					"schedule" => $item->schedule_from, 
					"pt_name" => $patient->name,
					"pt_doc" => $patient->doc_type." ".$patient->doc_number,
				];
			}
		}
		
		if ($reservations){
			$type = "success";
			$msg = null;
		}else{
			$type = "error";
			$msg = $this->lang->line('e_no_reservation');
		}
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg, "reservations" => $reservations]);
	}
	
	public function asign_reservation(){
		$type = "error"; $msg = null;
		
		if ($this->utility_lib->check_access("sale", "register")){
			$data = $this->input->post();
			if ($this->general->update("sale_product", $data["id"], [$data["field"]."_id" => $data["attn_id"]])){
				$s_confirmed = $this->general->status("confirmed");
				$this->general->update($data["field"], $data["attn_id"], ["status_id" => $s_confirmed->id]);
				
				$type = "success";
				if ("appointment" === $data["field"]) $msg = $this->lang->line('s_appointment_assigned');
				else $msg = $this->lang->line('s_surgery_assigned');
			}else $msg = $this->lang->line('error_internal');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg]);
	}
	
	public function unassign_reservation(){
		$type = "error"; $msg = null;
		
		if ($this->utility_lib->check_access("sale", "register")){
			$sale_prod = $this->general->id("sale_product", $this->input->post("id"));
			$data = ["status_id" => $this->general->status("reserved")->id];
			
			if ($this->general->update("sale_product", $sale_prod->id, ["appointment_id" => null, "surgery_id" => null])){
				//reset appointment or surgery to reserved status
				if ($sale_prod->appointment_id) $this->general->update("appointment", $sale_prod->appointment_id, $data);
				if ($sale_prod->surgery_id) $this->general->update("surgery", $sale_prod->surgery_id, $data);
				
				$type = "success";
				$msg = $this->lang->line('s_item_unassign');
			}else $msg = $this->lang->line('error_internal');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg]);
	}

	public function add_payment(){
		$type = "error"; $msg = null;
		
		if ($this->utility_lib->check_access("sale", "admin_payment")){
			$payment = $this->input->post();
			
			//update last sale status
			$this->update_balance($payment["sale_id"]);
			$sale = $this->general->id("sale", $payment["sale_id"]);
			
			//validate sale balance and total payment amount at moment
			if ($sale->balance == $payment["total"]){
				unset($payment["total"]);//remove total field of payment
				$payment["registed_at"] = date('Y-m-d H:i:s', time());
				if ($this->general->insert("payment", $payment)){//register payment
					$this->update_balance($payment["sale_id"]);
					$this->general->update("sale", $sale->id, ["updated_at" => date('Y-m-d H:i:s', time())]);
					$this->utility_lib->add_log("payment_register", $this->lang->line('sale')." #".$sale->id);
					
					$type = "success";
					$msg = $this->lang->line("s_payment_add");
				}else $msg = $this->lang->line("error_internal");
			}else $msg = $this->lang->line("e_balance_update");
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg]);
	}
	
	public function delete_payment(){
		$type = "error"; $msg = null;
		
		if ($this->utility_lib->check_access("sale", "admin_payment")){
			$payment = $this->general->id("payment", $this->input->post("id"));
			if ($this->general->delete("payment", ["id" => $payment->id])){
				$this->update_balance($payment->sale_id);
				$this->general->update("sale", $payment->sale_id, ["updated_at" => date('Y-m-d H:i:s', time())]);
				$this->utility_lib->add_log("payment_delete", $this->lang->line('sale')." #".$payment->sale_id);
				
				$type = "success";
				$msg = $this->lang->line("s_payment_delete");
			}else $msg = $this->lang->line("error_internal");
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg]);
	}
	
	public function cancel_sale(){
		$type = "error"; $msg = null;
		
		//permission validation
		if ($this->utility_lib->check_access("sale", "cancel")){
			$sale = $this->general->id("sale", $this->input->post("id"));
			if (!$sale->voucher_id){
				//sale status validation => no canceled sale
				$status_canceled_id = $this->general->status("canceled")->id;
				$status_reserved_id = $this->general->status("reserved")->id;
				if ($sale->status_id != $status_canceled_id){
					$products = $this->general->filter("sale_product", ["sale_id" => $sale->id]);
					foreach($products as $item){
						//product stock update
						$prod = $this->general->id("product", $item->product_id);
						$prod_t = $this->general->id("product_type", $prod->type_id);
						if ($prod_t->description === "Producto"){
							$op = $this->general->id("product_option", $item->option_id);
							$this->general->update("product_option", $item->option_id, ["stock" => ($op->stock + $item->qty)]);
						}
						
						//appointment update
						if ($item->appointment_id) $this->general->update("appointment", $item->appointment_id, ["status_id" => $status_reserved_id]);
						
						//surgery update
						if ($item->surgery_id) $this->general->update("surgery", $item->surgery_id, ["status_id" => $status_reserved_id]);
						
						//unassign appointment & surgery from sale_product
						$this->general->update("sale_product", $item->id, ["appointment_id" => null, "surgery_id" => null]);
					}
					
					if ($this->general->update("sale", $sale->id, ["status_id" => $status_canceled_id])){
						$this->utility_lib->add_log("sale_cancel", $this->lang->line('sale')." #".$sale->id);
						
						$type = "success";
						$msg = $this->lang->line("s_sale_cancel");
					}else $msg = $this->lang->line("error_internal");
				}else $msg = $this->lang->line('e_sale_cenceled');
			}else $msg = $this->lang->line('e_voucher_exists');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg]);
	}
	
	public function make_voucher(){
		$type = "error"; $msg = null; $msgs = [];
		
		if ($this->utility_lib->check_access("sale", "admin_voucher")){
			$data = $this->input->post();
			
			$this->load->library('my_val');
			$msgs = $this->my_val->voucher($msgs, "mv_", $data);
			
			if (!$msgs){
				$sale = $this->general->id("sale", $data["sale_id"]);
				if (!$this->general->id("voucher", $sale->voucher_id)){
					if (!$sale->balance){
						$currency = $this->general->id("currency", $sale->currency_id);
						
						/* voucher structure */
						$voucher = $this->general->structure("voucher"); unset($voucher->id);
						$voucher_type = $this->general->id("voucher_type", $this->input->post("voucher_type_id"));
						
						$voucher->voucher_type_id = $voucher_type->id;
						$voucher->sale_id = $sale->id;
						$voucher->sale_type_id = $sale->sale_type_id;
						$voucher->status_id = $this->general->status("in_progress")->id;
						$voucher->legend = $this->my_func->get_numletter($sale->total, $currency->sunat_code);
						$voucher->hash = substr(password_hash(date("Ymdhims"), PASSWORD_BCRYPT), -28, 28);
						$voucher->registed_at = date('Y-m-d H:i:s', time());
						
						/* correlative
						1. search last same voucher_type and sale_type voucher
						2. record exists => actual correlative is +1
						3. record no exists => actual correlative is $sale_type->start
						*/
						$f = ["voucher_type_id" => $voucher->voucher_type_id, "sale_type_id" => $voucher->sale_type_id];
						$last_voucher = $this->general->filter("voucher", $f, null, null, "correlative", "desc", 1, 0);
						if ($last_voucher) $voucher->correlative = $last_voucher[0]->correlative + 1;
						else{
							$sale_type = $this->general->id("sale_type", $voucher->sale_type_id);
							switch($voucher_type->description){
								case "Boleta": $voucher->correlative = $sale_type->start_boleta; break;
								case "Factura": $voucher->correlative = $sale_type->start_factura; break;
								default: $voucher->correlative = 1; break;
							}
						}
						
						/* payment method
						1. load all payment
						2. one payment => same voucher payment
						3. two or more payments => efectivo
						*/
						$payments = $this->general->filter("payment", ["sale_id" => $sale->id]);
						if (count($payments) == 1){
							$voucher->payment_method_id = $payments[0]->payment_method_id;
							$voucher->received = $payments[0]->received;
						}else{
							$f = ["description" => "Efectivo"];
							$voucher->payment_method_id = $this->general->filter("payment_method", $f)[0]->id;
							$voucher->received = $sale->total;
						}
						$voucher->change = $sale->total - $voucher->received;
						
						/* client record
						1. "Sin documento (sunat_code != 0)" => $client_id = null;
						2. read client record from DB
						4. exists? => update client name and assign $client_id
						3. no exists? => insert and assign $client_id
						*/
						$client = $this->input->post("cli");
						$doc_type = $this->general->id("doc_type", $client["doc_type_id"]);
						if ($doc_type->sunat_code){
							$f = ["doc_type_id" => $client["doc_type_id"], "doc_number" => $client["doc_number"]];
							$person = $this->general->filter("person", $f);
							if ($person){
								$person = $person[0];
								$this->general->update("person", $person->id, ["name" => $client["name"]]);
								$voucher->client_id = $person->id;
							}else $voucher->client_id = $this->general->insert("person", $client);
						}else $voucher->client_id = null;
						
						if (!$sale->client_id) $this->general->update("sale", $sale->id, ["client_id" => $voucher->client_id]);
						
						$voucher_id = $this->general->insert("voucher", $voucher);
						if ($voucher_id){
							$this->general->update("sale", $sale->id, ["voucher_id" => $voucher_id]);
							$this->utility_lib->add_log("voucher_register", $this->lang->line('sale')." #".$sale->id." (".$voucher_type->description.")");
							
							/* send to sunat
							1. load greenter_lib
							2. use function send_sunat with voucher_id
							3. update sunat result of voucher
							4. set msg
							*/
							
							$this->load->library('greenter_lib');
							$res = $this->greenter_lib->send_sunat($this->set_voucher_data($voucher_id));
							if ($res["sunat_sent"]){
								$type = "success"; 
								$msg = $res["sunat_msg"];
								if ($res["sunat_notes"]) $msg = $msg."<div class='mt-3'><h5 class='text-left'>Notas:</h5><div class='text-left'>".str_replace('&&&', '<br/>', $res["sunat_notes"])."</div></div>";
								$res["status_id"] = $this->general->status("accepted")->id;
							}else{
								$msg = $res["sunat_msg"];
								$res["status_id"] = $this->general->status("rejected")->id;
							}
							
							$this->general->update("voucher", $voucher_id, $res);
						}else $msg = $this->lang->line('error_internal');
					}else $msg = $this->lang->line('e_balance_pending');
				}else $this->lang->line('e_voucher_exist');
			}else $msg = $this->lang->line('error_occurred');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg, "msgs" => $msgs]);
	}
	
	public function send_sunat(){
		$id = $this->input->post("id");
		
		$this->load->library('greenter_lib');
		$res = $this->greenter_lib->send_sunat($this->set_voucher_data($id));
		if ($res["sunat_sent"]){
			$type = "success"; 
			$msg = $res["sunat_msg"];
			if ($res["sunat_notes"]) $msg = $msg."<div class='text-left mt-3'><div>".str_replace('&&&', '<br/>', $res["sunat_notes"])."</div></div>";
			$res["status_id"] = $this->general->status("accepted")->id;
		}else{
			$type = "error"; 
			$msg = $res["sunat_msg"];
			$res["status_id"] = $this->general->status("rejected")->id;
		}
		
		$this->general->update("voucher", $id, $res);
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg]);
	}
	
	private function set_voucher_data($id){
		$voucher = $this->general->id("voucher", $id);
		
		$voucher_type = $this->general->id("voucher_type", $voucher->voucher_type_id);
		$voucher->type = $voucher_type->description;
		$voucher->code = $voucher_type->sunat_code;
		$voucher->letter = $voucher_type->description[0];
		
		$sale = $this->general->id("sale", $voucher->sale_id);
		$voucher->amount = $sale->amount;
		$voucher->vat = $sale->vat;
		$voucher->total = $sale->total;
		
		$sale_type = $this->general->id("sale_type", $sale->sale_type_id);
		$voucher->serie = $sale_type->sunat_serie;
		
		$currency = $this->general->id("currency", $sale->currency_id);
		$voucher->currency = $currency->description;
		$voucher->currency_code = $currency->sunat_code;
		
		$payment_method = $this->general->id("payment_method", $voucher->payment_method_id);
		$voucher->payment_method = $payment_method->description;
		
		if ($voucher->client_id){
			$client = $this->general->id("person", $voucher->client_id);
			$client->doc_type = $this->general->id("doc_type", $client->doc_type_id);	
		}else{
			$client = $this->general->structure("person");
			$client->doc_type = $this->general->filter("doc_type", ["sunat_code" => 0])[0];
			$client->doc_number = 0;
			$client->name = "000";
		}
		
		$company = $this->general->id("company", $this->general->id("system", 1)->company_id);
		$company->department = $this->general->id("address_department", $company->department_id)->name;
		$company->province = $this->general->id("address_province", $company->province_id)->name;
		$company->district = $this->general->id("address_district", $company->district_id)->name;
		
		$products = $this->general->filter("sale_product", ["sale_id" => $voucher->sale_id]);
		foreach($products as $item){
			$item->unit_price = $item->price - $item->discount;
			$item->value = round($item->unit_price/1.18, 2);
			$item->vat = round($item->unit_price - $item->value, 2);
			$item->data = $this->general->id("product", $item->product_id);
			$item->type = $this->general->id("product_type", $item->data->type_id);
			//if ($item->option_id) $item->data->description = $item->data->description." ".$this->general->id("product_option", $item->option_id)->description;
		}
		
		return ["voucher" => $voucher, "client" => $client, "company" => $company, "products" => $products];
	}
	
	public function void_voucher(){
		$type = "error"; $msg = null; $msgs = [];
		
		if ($this->utility_lib->check_access("sale", "admin_voucher")){
			$data = $this->input->post();
			
			$this->load->library('my_val');
			$msgs = $this->my_val->void_voucher($msgs, "vv_", $data);	
			if (!$msgs){
				/*
				1. set sunat resume correlative
				2. create sunat resume record
				3. send to sunat
				4. update sunat resume record
				5. success 
				   => update sunat data to voucher
				   => remove voucher_id from sale => not valid voucher for sale record
				   fail
				   => set message
				*/
				
				$f = ["registed_at >=" => date("Y-m-d 00:00:00")];
				$last_resume = $this->general->filter("sunat_resume", $f, null, null, "registed_at", "desc");
				if ($last_resume) $data["r_correlative"] = $last_resume[0]->correlative + 1;
				else $data["r_correlative"] = 1;
				
				$r_data = [
					"correlative" => $data["r_correlative"],
					"registed_at" => date('Y-m-d H:i:s', time())
				];
				$resume_id = $this->general->insert("sunat_resume", $r_data);
				
				$this->load->library('greenter_lib');
				$res = $this->greenter_lib->void_sunat($this->set_voucher_data($data["id"]), $data);
				$this->general->update("sunat_resume", $resume_id, $res);
				
				if ($res["is_success"]){
					$voucher = $this->general->id("voucher", $data["id"]);
					
					$v_data = [
						"status_id" => $this->general->status("canceled")->id,
						"sunat_sent" => null,
						"sunat_msg" => null,
						"sunat_notes" => null,
						"sunat_resume_id" => $resume_id,
					];
					if ($this->general->update("voucher", $voucher->id, $v_data)){
						$this->general->update("sale", $voucher->sale_id, ["voucher_id" => null]);
						
						$type = "success";
						$msg = $this->lang->line("s_voucher_voided")."<br/>".$res["message"];
					}else $msg = $this->lang->line("error_internal");
				}else $msg = $this->lang->line('error_try_again')."<br/>".$res["message"];
			}else $msg = $this->lang->line('error_occurred');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msgs" => $msgs, "msg" => $msg]);
	}
	
	public function voucher($id){
		if ($this->utility_lib->check_access("sale", "admin_voucher")){
			$data = $this->set_voucher_data($id);
			
			$this->load->library('greenter_lib');
			$invoice = $this->greenter_lib->set_invoice($data);
			
			//QR Code => RUC|TIPO DE DOCUMENTO|SERIE|NUMERO|MTO TOTAL IGV|MTO TOTAL DEL COMPROBANTE|FECHA DE EMISION|TIPO DE DOCUMENTO ADQUIRENTE|NUMERO DE DOCUMENTO ADQUIRENTE
			$qr_data = [
				$invoice->getCompany()->getRuc(), $invoice->getTipoDoc(), $invoice->getSerie(), $invoice->getCorrelativo(), 
				$invoice->getTotalImpuestos(), $invoice->getMtoImpVenta(), $invoice->getFechaEmision()->format('Y-m-d'), 
				$invoice->getClient()->getTipoDoc(), $invoice->getClient()->getNumDoc(), $data["voucher"]->hash
			];
				
			$this->load->library('ciqrcode');
			$qr_params = array(
				"data" => implode("|", $qr_data), "level" => 'H', 
				"size" => 10, "savename" => FCPATH.'/uploaded/qr.png'
			);
			
			$data["qr"] = base64_encode(file_get_contents($this->ciqrcode->generate($qr_params)));
			$data["title"] = $invoice->getSerie()." - ".str_pad($invoice->getCorrelativo(), 6, '0', STR_PAD_LEFT);
			//$data["logo"] = base64_encode(file_get_contents(FCPATH."/resources/images/logo.png"));
			$data["invoice"] = $invoice;
			
			//echo $this->load->view("commerce/voucher/invoice", $data, true);
			$this->my_func->make_pdf($this->load->view("commerce/voucher/invoice", $data, true), $data["title"]);
		}else echo $this->lang->line('error_no_permission');
	}
	
	public function payment_report($sale_id){
		if ($this->utility_lib->check_access("sale", "admin_voucher")){
			$sale = $this->general->id("sale", $sale_id);
			$filter = ["sale_id" => $sale->id];
			//if (!$sale->balance){echo "Esta venta no cuenta con saldo pendiente."; return;}
			
			if ($sale->client_id){
				$client = $this->general->id("person", $sale->client_id);
				$client->doc_type = $this->general->id("doc_type", $client->doc_type_id);	
			}else $client = $this->general->structure("person");
			
			$company = $this->general->id("company", $this->general->id("system", 1)->company_id);
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
			
			$data = [
				"sale" => $sale,
				"client" => $client,
				"company" => $company,
				"currency" => $currency,
				"payments" => $payments,
				"products" => $products,
				"title" => $title,
				//"logo" => base64_encode(file_get_contents(FCPATH."/resources/images/logo.png")),
			];
			
			//echo $this->load->view("commerce/voucher/ticket", $data, true);
			$this->my_func->make_pdf($this->load->view("commerce/voucher/ticket", $data, true), $title);
		}else echo $this->lang->line('error_no_permission');
	}
}
