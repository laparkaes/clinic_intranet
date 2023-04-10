<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/Lima');
		$this->lang->load("product", "spanish");
		$this->lang->load("system", "spanish");
		$this->load->model('product_model','product');
	}
	
	private function set_msg($msgs, $dom_id, $type, $msg_code){
		if ($msg_code) array_push($msgs, array("dom_id" => $dom_id, "type" => $type, "msg" => $this->lang->line($msg_code)));
		return $msgs;
	}
	
	public function index(){
		if (!$this->session->userdata('logged_in')) redirect(base_url());
		//pending! rol validation
		
		$categories_arr = array();
		$categories = $this->product->category_all();
		foreach($categories as $item){
			$categories_arr[$item->id] = $item->name;
			$item->prod_qty = $this->product->count(array("category_id" => $item->id));
		}
		
		$products = $this->product->all();
		
		//sl_options
		$codes = array("product_type");
		$options_rec = $this->sl_option->codes($codes);
		
		$options = $options_arr = array();
		foreach($codes as $item) $options[$item] = array();
		foreach($options_rec as $item){
			$options_arr[$item->id] = $item;
			array_push($options[$item->code], $item);
		}
		
		$currencies_arr = array();
		$currencies = $this->general->all("currency", "description", "asc");
		foreach($currencies as $item) $currencies_arr[$item->id] = $item;
		
		$prod_types_arr = array();
		$prod_types = $this->general->all("product_type", "description", "asc");
		foreach($prod_types as $item) $prod_types_arr[$item->id] = $item;
		
		//pending! load products records
		$data = array(
			"categories" => $categories,
			"categories_arr" => $categories_arr,
			"products" => $products,
			"prod_types" => $prod_types,
			"prod_types_arr" => $prod_types_arr,
			"currencies" => $currencies,
			"currencies_arr" => $currencies_arr,
			"title" => $this->lang->line('products'),
			"main" => "product/list",
			"init_js" => "product/list.js"
		);
		
		$this->load->view('layout', $data);
	}
	
	public function add_category(){
		$name = $this->input->post("name");
		$status = false; $type = "error"; $msg = null;
		
		if ($name){
			if (!$this->product->category(null, $name)){
				if ($this->product->category_insert(array("name" => $name))){
					$status = true;
					$type = "success";
					$msg = $this->lang->line('success_ac');
				}else $msg = $this->lang->line('error_internal');
			}else $msg = $this->lang->line('error_ce');
		}else $msg = $this->lang->line('error_cn');
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msg" => $msg));
	}
	
	public function update_category(){
		$id = $this->input->post("id");
		$name = $this->input->post("name");
		$status = false; $type = "error"; $msg = null;
		
		if ($name){
			if (!$this->product->category(null, $name)){
				if ($this->product->category_update($id, array("name" => $name))){
					$status = true;
					$type = "success";
					$msg = $this->lang->line('success_uc');
				}else $msg = $this->lang->line('error_internal');
			}else $msg = $this->lang->line('error_ce');
		}else $msg = $this->lang->line('error_cn');
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msg" => $msg));
	}
	
	public function delete_category(){
		$id = $this->input->post("id");
		$status = false; $type = "error"; $msg = null;
		
		if (!$this->product->filter(array("category_id" => $id))){
			if ($this->product->category_delete($id)){
				$status = true;
				$type = "success";
				$msg = $this->lang->line('success_dc');
			}else $msg = $this->lang->line('error_internal');
		}else $msg = $this->lang->line('error_dcp');
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msg" => $msg));
	}
	
	public function move_product(){
		$id_from = $this->input->post("mp_id_from");
		$id_to = $this->input->post("mp_id_to");
		$status = false; $msgs = array();
		
		if (!$id_from) $msgs = $this->set_msg($msgs, "mp_id_from_msg", "error", "error_cf");
		if (!$id_to) $msgs = $this->set_msg($msgs, "mp_id_to_msg", "error", "error_ct");
		elseif ($id_from == $id_to) $msgs = $this->set_msg($msgs, "mp_id_to_msg", "error", "error_cd");
		
		if (!$msgs){
			if ($this->product->change_category($id_from, $id_to)) $status = true;
			else $msgs = $this->set_msg($msgs, "mp_result_msg", "error", "error_internal");
		}
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "msgs" => $msgs));
	}
	
	public function register(){
		$datas = $this->input->post();
		$status = false; $type = "error"; $msgs = array(); $msg = $this->lang->line('error_occurred'); $move_to = null;
		
		//product datas validation. provider data is optional
		if ($datas["code"]){
			if ($this->product->filter(array("code" => $datas["code"]))) 
				$msgs = $this->set_msg($msgs, "ap_code_msg", "error", "error_pcoe");
		}else $msgs = $this->set_msg($msgs, "ap_code_msg", "error", "error_pco");
		if (!$datas["description"]) $msgs = $this->set_msg($msgs, "ap_description_msg", "error", "error_pn");
		if (!$datas["category_id"]) $msgs = $this->set_msg($msgs, "ap_category_msg", "error", "error_pca");
		if (!$datas["currency_id"]) $msgs = $this->set_msg($msgs, "ap_price_msg", "error", "error_pcu");
		if ($datas["price"]){
			if (is_numeric($datas["price"])){
				if ($datas["price"] < 0) $msgs = $this->set_msg($msgs, "ap_price_msg", "error", "error_epn");
			}else $msgs = $this->set_msg($msgs, "ap_price_msg", "error", "error_enu");
		}else $msgs = $this->set_msg($msgs, "ap_price_msg", "error", "error_ep");
		
		if (!$msgs){
			$datas["value"] = round($datas["price"] / 1.18, 2);
			$datas["vat"] = $datas["price"] - $datas["value"];
			$datas["active"] = true;
			$datas["updated_at"] = date("Y-m-d H:i:s", time());
			$datas["registed_at"] = date("Y-m-d H:i:s", time());
			$product_id = $this->product->insert($datas);
			if ($product_id){
				$move_to = base_url()."product/detail/".$product_id;
				
				if ($_FILES["image"]["name"]){
					$upload_dir = "uploaded/products/".$product_id;
					if(!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
					$upload_dir = $upload_dir."/";
					
					$this->load->library('upload');
					$config_upload = array(
						'upload_path' => $upload_dir,
						'allowed_types' => 'gif|jpg|png|jpeg',
						'max_size' => 0,
						'overwrite' => false,
						'file_name' => date("YmdHis")
					);
					
					$this->upload->initialize($config_upload);
					if ($this->upload->do_upload("image")){
						$result = $this->upload->data();
						$img_data = array("product_id" => $product_id, "filename" => $result["file_name"]);
						if ($this->product->image_insert($img_data)){
							if ($this->product->update($product_id, array("image" => $result["file_name"]))){
								$status = true;
								$type = "success";
								$msg = $this->lang->line('success_ap');
							}else $msgs = $this->set_msg($msgs, "ap_result_msg", "error", "error_internal");
						}else $msgs = $this->set_msg($msgs, "ap_result_msg", "error", "error_internal");
					}else array_push($msgs, array("dom_id" => "ap_image_msg", "type" => "error", "msg" => $this->upload->display_errors("<span>","</span>")));	
				}else{
					$status = true;
					$type = "success";
					$msg = $this->lang->line('success_ap');
				}
			}else $msgs = $this->set_msg($msgs, "ap_result_msg", "error", "error_internal");
		}
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msgs" => $msgs, "msg" => $msg, "move_to" => $move_to));
	}
	
	public function detail($product_id){
		if (!$this->session->userdata('logged_in')) redirect(base_url());
		//pending! rol validation
		//pending! load product information
		
		$product = $this->product->id($product_id);
		if (!$product) redirect("/product");
		$product->category = $this->product->category($product->category_id)->name;
		$product->currency = $this->general->id("currency", $product->currency_id)->description;
		$product->type = $this->general->id("product_type", $product->type_id)->description;
		
		if ($product->provider_id) $provider = $this->general->id("provider", $product->provider_id);
		else $provider = $this->general->structure("provider");
		
		$data = array(
			"categories" => $this->product->category_all(),
			"product" => $product,
			"provider" => $provider,
			"product_options" => $this->general->filter("product_option", array("product_id" => $product->id), "id", "asc"),
			"product_types" => $this->general->all("product_type", "description", "asc"),
			"currencies" => $this->general->all("currency", "description", "asc"),
			"images" => $this->product->images($product_id),
			"title" => "Producto",
			"main" => "product/detail",
			"init_js" => "product/detail.js"
		);
		
		$this->load->view('layout', $data);
	}
	
	private function update_stock($product_id){
		$sum = $this->general->sum("product_option", "stock", array("product_id" => $product_id));
		$this->general->update("product", $product_id, array("stock" => $sum->stock));
	}
	
	public function add_option(){
		$status = false; $type = "error"; $msgs = array(); $msg = null;
		$data = $this->input->post();
		
		if ($data["product_id"]){
			if ($data["description"]){
				$f = array("product_id" => $data["product_id"], "description" => $data["description"]);
				if ($this->general->filter("product_option", $f))
					$msgs = $this->set_msg($msgs, "aop_description_msg", "error", "error_ope");
			}else $msgs = $this->set_msg($msgs, "aop_description_msg", "error", "error_opd");
			if ($data["stock"]){
				if (filter_var($data["stock"], FILTER_VALIDATE_INT) !== false){
					if ($data["stock"] < 0) $msgs = $this->set_msg($msgs, "aop_stock_msg", "error", "error_epn");
				}else $msgs = $this->set_msg($msgs, "aop_stock_msg", "error", "error_ein");
			}else $msgs = $this->set_msg($msgs, "aop_stock_msg", "error", "error_es");
		
			if ($msgs) $msg = $this->lang->line('error_occurred');
			else{
				if ($this->general->insert("product_option", $data)){
					//update product stock
					$this->update_stock($data["product_id"]);
					
					$status = true;
					$type = "success";
					$msg = $this->lang->line('success_aop');
				}else $this->lang->line('error_internal');
			}
		}else $msg = $this->lang->line('error_internal_refresh');
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msgs" => $msgs, "msg" => $msg));
	}
	
	public function delete_option(){
		$prod_op = $this->general->id("product_option", $this->input->post("id"));
		if ($this->general->delete("product_option", array("id" => $prod_op->id))){
			$this->update_stock($prod_op->product_id);
			
			$msg = $this->lang->line('success_dop');
			$status = true;
			$type = "success";
		}else{
			$msg = $this->lang->line('error_internal');
			$status = false;
			$type = "error";
		}
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msg" => $msg));
	}
	
	public function edit_option(){
		$data = $this->input->post();
		if ($this->general->update("product_option", $data["id"], $data)){
			$this->update_stock($data["product_id"]);
			
			$msg = $this->lang->line('success_eop');
			$status = true;
			$type = "success";
		}else{
			$msg = $this->lang->line('error_internal');
			$status = false;
			$type = "error";
		}
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msg" => $msg));
	}
	
	public function edit_product(){
		$data = $this->input->post();
		$status = false; $type = "error"; $msgs = array(); $msg = null;
		
		//product datas validation. provider data is optional
		if ($data["code"]){
			$prod_f = $this->product->filter(array("code" => $data["code"]));
			if ($prod_f) if ($prod_f[0]->id != $data["id"]) 
				$msgs = $this->set_msg($msgs, "ep_code_msg", "error", "error_pcoe");
		}else $msgs = $this->set_msg($msgs, "ep_code_msg", "error", "error_pco");
		if (!$data["description"]) $msgs = $this->set_msg($msgs, "ep_description_msg", "error", "error_pn");
		if (!$data["category_id"]) $msgs = $this->set_msg($msgs, "ep_category_msg", "error", "error_pca");
		if (!$data["currency_id"]) $msgs = $this->set_msg($msgs, "ep_price_msg", "error", "error_pcu");
		if ($data["price"]){
			if (is_numeric($data["price"])){
				if ($data["price"] < 0) $msgs = $this->set_msg($msgs, "ep_price_msg", "error", "error_epn");
			}else $msgs = $this->set_msg($msgs, "ep_price_msg", "error", "error_enu");
		}else $msgs = $this->set_msg($msgs, "ep_price_msg", "error", "error_ep");
		
		if ($msgs) $msg = $this->lang->line('error_occurred');
		else{
			$data["value"] = round($data["price"] / 1.18, 2);
			$data["vat"] = $data["price"] - $data["value"];
			$data["active"] = true;
			$data["updated_at"] = date("Y-m-d H:i:s", time());
			$this->general->update("product", $data["id"], $data);
			$this->update_stock($data["id"]);
			
			$status = true;
			$type = "success";
			$msg = $this->lang->line('success_up');
		}
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msgs" => $msgs, "msg" => $msg));
	}
	
	public function delete_product(){
		$id = $this->input->post("id");
		$status = false; $type = "error"; $msg = null;
		
		if ($this->product->update($id, array("active" => false))){
			$status = true;
			$type = "success";
			$msg = $this->lang->line('success_dp');
		}else $msg = $this->lang->line('error_internal');
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msg" => $msg));
	}
	
	public function add_image(){
		$status = false; $type = "error"; $msg = null;
		$datas = $this->input->post();
		
		if (!$datas["product_id"]) $msg = $this->lang->line('error_internal_refresh');
		if (!$_FILES["image"]["name"]) $msg = $this->lang->line('error_sim');
		
		if (!$msg){
			$upload_dir = "uploaded/products/".$datas["product_id"];
			if(!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
			$upload_dir = $upload_dir."/";
			
			$this->load->library('upload');
			$config_upload = array(
				'upload_path' => $upload_dir,
				'allowed_types' => 'gif|jpg|png|jpeg',
				'max_size' => 0,
				'overwrite' => false,
				'file_name' => date("YmdHis")
			);
			
			$this->upload->initialize($config_upload);
			if ($this->upload->do_upload("image")){
				$result = $this->upload->data();
				$datas["filename"] = $result["file_name"];
				
				$id = $this->product->image_insert($datas);
				if ($id){
					$datas["id"] = $id;
					$datas["link"] = base_url()."uploaded/products/".$datas["product_id"]."/".$datas["filename"];
					$msg = $this->lang->line('success_ai');
					$status = true;
					$type = "success";
				}else $msg = $this->lang->line('error_internal');
			}else $msg = $this->upload->display_errors("<span>","</span>");
		}
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msg" => $msg, "img" => $datas));
	}
	
	public function delete_image(){
		$status = false; $type = "error"; $msg = null;
		$image = $this->product->image($this->input->post("id"));
		$product = $this->product->id($image->product_id);
		
		//validate if this is main image
		if (strcmp($image->filename, $product->image)){
			$img_path = "uploaded/products/".$image->product_id."/".$image->filename;
			if (unlink($img_path)){
				if ($this->product->image_delete($image->id)){
					$status = true;
					$type = "success";
					$msg = $this->lang->line('success_di');
				}else $msg = $this->lang->line('error_internal');
			}else $msg = $this->lang->line('error_internal');	
		}else $msg = $this->lang->line('error_dmi');	
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msg" => $msg, "id" => $image->id));
	}
	
	public function set_product_image(){
		$status = false; $type = "error"; $msg = null;
		$image = $this->product->image($this->input->post("id"));
		
		$data = array("image" => $image->filename, "updated_at" => date("Y-m-d H:i:s", time()));
		if ($this->product->update($image->product_id, $data)){
			$status = true;
			$type = "success";
			$msg = $this->lang->line('success_pri');
		}else $msg = $this->lang->line('error_internal');
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msg" => $msg));
	}

	public function save_provider(){
		$status = false; $type = "error"; $msgs = array(); $msg = null;
		$data = $this->input->post();
		
		if (!$data["company"]) $msgs = $this->set_msg($msgs, "epv_company_msg", "error", "error_eco");
		if (!$data["ruc"]) $msgs = $this->set_msg($msgs, "epv_ruc_msg", "error", "error_eruc");
		
		if ($msgs) $msg = $this->lang->line('error_occurred');
		else{
			$product_id = $data["product_id"]; unset($data["product_id"]);
			$company = $this->general->filter("provider", array("ruc" => $data["ruc"]));
			if ($company){
				$this->general->update("provider", $company[0]->id, $data);
			}else{
				$provider_id = $this->general->insert("provider", $data);
				$this->general->update("product", $product_id, array("provider_id" => $provider_id));
			}
			
			$status = true;
			$type = "success";
			$msg = $this->lang->line('success_spv');
		}
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msgs" => $msgs, "msg" => $msg));
	}
	
	public function clean_provider(){
		$product_id = $this->input->post("product_id");
		$status = false; $type = "error"; $msg = null;
		
		if ($this->product->update($product_id, array("provider_id" => null))){
			$status = true;
			$type = "success";
			$msg = $this->lang->line('success_cpv');
		}else $msg = $this->lang->line('error_internal');
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msg" => $msg));
	}
}