<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/Lima');
		$this->lang->load("system", "spanish");
		$this->lang->load("purchase", "spanish");
		$this->load->model('general_model','general');
		$this->nav_menu = ["commerce", "purchase"];
		$this->nav_menus = $this->utility_lib->get_visible_nav_menus();
	}
	
	public function index(){
		if (!$this->session->userdata('logged_in')) redirect('/');
		if (!$this->utility_lib->check_access("purchase", "index")) redirect("/errors/no_permission");
		
		$f_url = [
			"page" => $this->input->get("page"),
			"provider" => $this->input->get("provider"),
		];
		
		$f_w = $f_l = $f_in = [];
		if (!$f_url["page"]) $f_url["page"] = 1;
		if ($f_url["provider"]){
			$aux = [-1];
			$people = $this->general->filter("person", null, ["name" => $f_url["provider"]]);
			foreach($people as $p) $aux[] = $p->id;
			
			$f_in[] = ["field" => "provider_id", "values" => $aux];
		}
		
		$purchases = $this->general->filter("purchase", $f_w, $f_l, $f_in, "updated_at", "desc", 25, 25 * ($f_url["page"] - 1));
		foreach($purchases as $item){
			$item->currency = $this->general->id("currency", $item->currency_id);
			$item->provider = $this->general->id("person", $item->provider_id);
			$item->status = $this->general->id("status", $item->status_id);
			$item->status->lang = $this->lang->line($item->status->code);
		}
		
		$data = array(
			"paging" => $this->my_func->set_page($f_url["page"], $this->general->counter("purchase", $f_w)),
			"f_url" => $f_url,
			"purchases" => $purchases,
			"title" => $this->lang->line('purchases'),
			"main" => "commerce/purchase/list",
		);
		
		$this->load->view('layout', $data);
	}
	
	public function add(){
		$type = "error"; $msg = null; $msgs = []; $move_to = null;
		
		if ($this->utility_lib->check_access("purchase", "register")){
			$products_json = $this->input->post("sl_pr");
			$provider = $this->input->post("provider");
			$currency = $this->input->post("currency");
			$purchase_id = null;
			
			//provider validation
			$this->load->library('my_val');
			$msgs = $this->my_val->purchase_provider($msgs, $provider);
			
			if (!$msgs){
				//provider processing
				$doc_type = $this->general->id("doc_type", $provider["doc_type_id"]);
				if ($doc_type->description === "Sin Documento") $provider_id = null;
				else{
					$provider_rec = $this->general->filter("person", $provider);
					if ($provider_rec) $provider_id = $provider_rec[0]->id;
					else $provider_id = $this->general->insert("person", $provider);
				}
				
				//set purchase data
				$currency = $this->general->filter("currency", ["description" => $currency])[0];
				
				//basic purchase data
				$now = date('Y-m-d H:i:s', time());
				$purchase_data = [
					"currency_id" => $currency->id,
					"provider_id" => $provider_id,
					"updated_at" => $now,
					"registed_at" => $now,
				];
				$purchase_id = $this->general->insert("purchase", $purchase_data);
				
				//insert products
				$total = 0;
				foreach($products_json as $prod_json){//in json format
					$item = json_decode($prod_json);
					$prod = $this->general->id("product", $item->product_id);
					
					$item->purchase_id = $purchase_id;
					$this->general->insert("purchase_product", $item);
					$total += $item->qty * $item->price;
					if ($item->option_id){//in case of product, handle product stock
						$op = $this->general->id("product_option", $item->option_id);
						$this->general->update("product_option", $item->option_id, ["stock" => ($op->stock + $item->qty)]);
						
						$sum = $this->general->sum("product_option", "stock", ["product_id" => $item->product_id]);
						$this->general->update("product", $item->product_id, ["stock" => $sum->stock]);
					}
				}
				
				$status = $this->general->status("finished");
				
				$amount = round($total / 1.18, 2);
				$vat = round($total - $amount, 2);
				
				$purchase_data = [
					"status_id" => $status->id,
					"total" => $total,
					"amount" => $amount,
					"vat" => $vat,
				];
				
				if ($this->general->update("purchase", $purchase_id, $purchase_data)){
					$type = "success";
					$msg = $this->lang->line('s_purchase_add');
					$move_to = base_url()."commerce/purchase/detail/".$purchase_id;
				}
			}else $msg = $this->lang->line('error_occurred');
			
			//rollback
			if ($type === "error") if ($purchase_id){
				$purchase_products = $this->general->filter("purchase_product", ["purchase_id" => $purchase_id]);
				if ($purchase_products){
					//reverse stocks to product
					foreach($purchase_products as $p){
						$prod_op = $this->general->id("product_option", $p->option_id);
						$prod_op_data = ["stock" => ($prod_op->stock - $p->qty)];
						$this->general->update("product_option", $prod_op->id, $prod_op_data);
					}
					$this->general->delete("purchase_product", ["purchase_id" => $purchase_id]);
				}
				
				$this->general->delete("purchase", ["id" => $purchase_id]);
			}
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg, "msgs" => $msgs, "move_to" => $move_to]);
	}
	
}
