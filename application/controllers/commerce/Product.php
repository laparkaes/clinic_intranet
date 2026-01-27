<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/Lima');
		
		$this->lang->load("product", "spanish");
		$this->lang->load("system", "spanish");	
		$this->load->model('product_model','product');
		$this->load->model('general_model','general');
		$this->nav_menu = ["commerce", "product"];
		$this->nav_menus = $this->utility_lib->get_visible_nav_menus();
	}
	
	public function index(){
		if (!$this->session->userdata('logged_in')) redirect('/');
		if (!$this->utility_lib->check_access("product", "index")) redirect("/errors/no_permission");
		
		$f_url = [
			"page" => $this->input->get("page"),
			"type" => $this->input->get("type"),
			"category_id" => $this->input->get("category_id"),
			"type_id" => $this->input->get("type_id"),
			"description" => trim($this->input->get("description")),
			"code" => trim($this->input->get("code")),
		];
		if (!$f_url["page"]) $f_url["page"] = 1;
		
		$f_w = $f_l = $f_w_in = [];
		if ($f_url["category_id"]) $f_w["category_id"] = $f_url["category_id"];
		if ($f_url["type_id"]) $f_w["type_id"] = $f_url["type_id"];
		if ($f_url["description"]) $f_l[] = ["field" => "description", "values" => explode(" ", trim($f_url["description"]))];
		if ($f_url["code"]) $f_l[] = ["field" => "code", "values" => explode(" ", trim($f_url["code"]))];
		
		$f_w["active"] = true;
		$no_img_path = "uploaded/products/no_img.png";
		
		$products = $this->general->filter("product", $f_w, $f_l, $f_w_in, "description", "asc", 25, 25*($f_url["page"]-1));
		foreach($products as $item){
			$item->options = $this->general->filter("product_option", ["product_id" => $item->id], null, null, "description", "asc");
			
			if ($item->image){
				$prod_img_path = "uploaded/products/".$item->id."/".$item->image;
				if (file_exists($prod_img_path)) $img_path = $prod_img_path;
				else $img_path = $no_img_path;
			}else $img_path = $no_img_path;
			
			$item->img_path = $img_path;
		}
		
		$categories_arr = array();
		$categories = $this->product->category_all();
		foreach($categories as $item){
			$categories_arr[$item->id] = $item->name;
			$item->prod_qty = $this->product->count(array("category_id" => $item->id));
		}
		
		$currencies_arr = array();
		$currencies = $this->general->all("currency", "description", "asc");
		foreach($currencies as $item) $currencies_arr[$item->id] = $item;
		
		$prod_types_arr = array();
		$prod_types = $this->general->all("product_type", "description", "asc");
		foreach($prod_types as $item) $prod_types_arr[$item->id] = $item;
		
		//pending! load products records
		$data = [
			"paging" => $this->my_func->set_page($f_url["page"], $this->general->counter("product", $f_w, $f_l, $f_w_in)),
			"f_url" => $f_url,
			"categories" => $categories,
			"categories_arr" => $categories_arr,
			"products" => $products,
			"prod_types" => $prod_types,
			"prod_types_arr" => $prod_types_arr,
			"currencies" => $currencies,
			"currencies_arr" => $currencies_arr,
			"title" => $this->lang->line('products'),
			"main" => "commerce/product/list",
		];
		
		$this->load->view('layout', $data);
	}
	
	public function add_category(){
		$type = "error"; $msg = null;
		
		if ($this->utility_lib->check_access("product", "admin_category")){
			$name = $this->input->post("name");
			if ($name){
				if (!$this->product->category(null, $name)){
					if ($this->product->category_insert(array("name" => $name))){
						$this->utility_lib->add_log("category_register", $name);
						
						$type = "success";
						$msg = $this->lang->line('s_category_add');
					}else $msg = $this->lang->line('error_internal');
				}else $msg = $this->lang->line('e_category_exists');
			}else $msg = $this->lang->line('e_category_name');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg]);
	}
	
	public function update_category(){
		$type = "error"; $msg = null;
		
		if ($this->utility_lib->check_access("product", "admin_category")){
			$id = $this->input->post("id");
			$name = $this->input->post("name");
			
			if ($name){
				if (!$this->product->category(null, $name)){
					if ($this->product->category_update($id, ["name" => $name])){
						$this->utility_lib->add_log("category_update", $name);
						
						$type = "success";
						$msg = $this->lang->line('s_category_update');
					}else $msg = $this->lang->line('error_internal');
				}else $msg = $this->lang->line('e_category_exists');
			}else $msg = $this->lang->line('e_category_name');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg]);
	}
	
	public function delete_category(){
		$type = "error"; $msg = null;
		
		if ($this->utility_lib->check_access("product", "admin_category")){
			$category = $this->general->id("product_category", $this->input->post("id"));
			if (!$this->product->filter(array("category_id" => $category->id))){
				if ($this->product->category_delete($category->id)){
					$this->utility_lib->add_log("category_delete", $category->name);
					
					$type = "success";
					$msg = $this->lang->line('s_category_delete');
				}else $msg = $this->lang->line('error_internal');
			}else $msg = $this->lang->line('e_category_remove_products');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg]);
	}
	
	public function move_product(){
		$type = "error"; $msgs = []; $msg = null;
		
		if ($this->utility_lib->check_access("product", "admin_category")){
			$id_from = $this->input->post("mp_id_from");
			$id_to = $this->input->post("mp_id_to");
			
			$this->load->library('my_val');
			$msgs = $this->my_val->product_category_move($msgs, "mp_", $id_from, $id_to);
			
			if (!$msgs){
				if ($this->product->change_category($id_from, $id_to)){
					$c_f = $this->general->id("product_category", $id_from);
					$c_t = $this->general->id("product_category", $id_to);
					$this->utility_lib->add_log("category_move", $c_f->name." > ".$c_t->name);
					
					$type = "success";
					$msg = $this->lang->line('s_category_move');
				}else $msg = $this->lang->line('error_internal');
			}else $msg = $this->lang->line('error_occurred');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msgs" => $msgs, "msg" => $msg]);
	}
	
	public function register(){
		$type = "error"; $msgs = []; $msg = $move_to = null;
		
		if ($this->utility_lib->check_access("product", "register")){
			$datas = $this->input->post();
			
			$this->load->library('my_val');
			$msgs = $this->my_val->product($msgs, "ap_", $datas);
			
			if (!$msgs){
				$datas["value"] = round($datas["price"] / 1.18, 2);
				$datas["vat"] = $datas["price"] - $datas["value"];
				$datas["active"] = true;
				$datas["updated_at"] = $datas["registed_at"] = date("Y-m-d H:i:s", time());
				$product_id = $this->product->insert($datas);
				if ($product_id){
					$this->utility_lib->add_log("product_register", $datas["description"]);
					
					$type = "success";
					$msg = $this->lang->line('s_product_add');
					$move_to = base_url()."commerce/product/detail/".$product_id;
					
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
							$this->product->image_insert(["product_id" => $product_id, "filename" => $result["file_name"]]);
							$this->product->update($product_id, ["image" => $result["file_name"]]);
						}else $msg = $msg."<br/>** ".$this->upload->display_errors("<span>","</span>");
					}
				}else $msg = $this->lang->line('error_internal');
			}else $msg = $this->lang->line('error_occurred');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msgs" => $msgs, "msg" => $msg, "move_to" => $move_to]);
	}
	
	public function detail($product_id){
		if (!$this->session->userdata('logged_in')) redirect('/');
		if (!$this->utility_lib->check_access("product", "detail")) redirect("/errors/no_permission");
		
		$this->update_stock($product_id);
		
		$product = $this->product->id($product_id);
		if (!$product) redirect("/errors/page_missing");
		elseif(!$product->active) redirect("/errors/page_missing");
		
		$product->category = $this->product->category($product->category_id)->name;
		$product->currency = $this->general->id("currency", $product->currency_id)->description;
		$product->type = $this->general->id("product_type", $product->type_id)->description;
		
		if ($product->provider_id) $provider = $this->general->id("company", $product->provider_id);
		else $provider = $this->general->structure("company");
		
		$data = array(
			"categories" => $this->product->category_all(),
			"product" => $product,
			"provider" => $provider,
			"product_options" => $this->general->filter("product_option", ["product_id" => $product->id], null, null, "id", "asc"),
			"product_types" => $this->general->all("product_type", "description", "asc"),
			"currencies" => $this->general->all("currency", "description", "asc"),
			"images" => $this->product->images($product_id),
			"title" => "Producto",
			"main" => "commerce/product/detail",
		);
		
		$this->load->view('layout', $data);
	}
	
	private function update_stock($product_id){
		$sum = $this->general->sum("product_option", "stock", array("product_id" => $product_id));
		$this->general->update("product", $product_id, array("stock" => $sum->stock));
	}
	
	public function add_option(){
		$type = "error"; $msgs = []; $msg = null;
		
		if ($this->utility_lib->check_access("product", "update")){
			$data = $this->input->post();
			if ($data["product_id"]){
				$this->load->library('my_val');
				$msgs = $this->my_val->product_option($msgs, "aop_", $data);
			
				if (!$msgs){
					if ($this->general->insert("product_option", $data)){
						//update product stock
						$this->update_stock($data["product_id"]);
						
						$product = $this->general->id("product", $data["product_id"]);
						$this->utility_lib->add_log("product_option_register", $product->description." > ".$data["description"]);
						
						$type = "success";
						$msg = $this->lang->line('s_option_add');
					}else $this->lang->line('error_internal');
				}else $msg = $this->lang->line('error_occurred');
			}else $msg = $this->lang->line('error_internal_refresh');
		}else $msg = $this->lang->line('error_no_permission');
		
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msgs" => $msgs, "msg" => $msg]);
	}
	
	public function delete_option(){
		$prod_op = $this->general->id("product_option", $this->input->post("id"));
		if ($this->general->delete("product_option", array("id" => $prod_op->id))){
			$this->update_stock($prod_op->product_id);
			
			$product = $this->general->id("product", $prod_op->product_id);
			$this->utility_lib->add_log("product_option_delete", $product->description." > ".$prod_op->description);
			
			$msg = $this->lang->line('s_option_delete');
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
		$type = "error"; $msg = null;
		
		$data = $this->input->post();
		if ($this->general->update("product_option", $data["id"], $data)){
			$this->update_stock($data["product_id"]);
			
			$product_op = $this->general->id("product_option", $data["id"]);
			$product = $this->general->id("product", $product_op->product_id);
			$this->utility_lib->add_log("product_option_update", $product->description." > ".$product_op->description);
			
			$type = "success";
			$msg = $this->lang->line('s_option_update');
		}else $msg = $this->lang->line('error_internal');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg]);
	}
	
	public function edit_product(){
		$type = "error"; $msgs = []; $msg = null;
		
		if ($this->utility_lib->check_access("product", "update")){
			$data = $this->input->post();
			
			$this->load->library('my_val');
			$msgs = $this->my_val->product($msgs, "ep_", $data, $data["id"]);
			
			if (!$msgs){
				$data["value"] = round($data["price"] / 1.18, 2);
				$data["vat"] = $data["price"] - $data["value"];
				$data["active"] = true;
				$data["updated_at"] = date("Y-m-d H:i:s", time());
				$this->general->update("product", $data["id"], $data);
				$this->update_stock($data["id"]);
				
				$this->utility_lib->add_log("product_update", $data["description"]);
				
				$type = "success";
				$msg = $this->lang->line('s_product_update');
			}else $msg = $this->lang->line('error_occurred');
		}else $msg = $this->lang->line('error_no_permission');
		
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msgs" => $msgs, "msg" => $msg]);
	}
	
	public function delete_product(){
		$type = "error"; $msg = null;
		
		if ($this->utility_lib->check_access("product", "delete")){
			$id = $this->input->post("id");
			if ($this->product->update($id, array("active" => false))){
				$product = $this->general->id("product", $id);
				$this->utility_lib->add_log("product_delete", $product->description);
				
				$move_to = base_url()."commerce/product";
				$type = "success";
				$msg = $this->lang->line('s_product_delete');
			}else $msg = $this->lang->line('error_internal');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg, "move_to" => $move_to]);
	}
	
	public function add_image(){
		$type = "error"; $msg = null;
		
		if ($this->utility_lib->check_access("product", "update")){
			$datas = $this->input->post();
			
			if (!$datas["product_id"]) $msg = $this->lang->line('error_internal_refresh');
			if (!$_FILES["image"]["name"]) $msg = $this->lang->line('e_image_select');
			
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
						
						$product = $this->general->id("product", $datas["product_id"]);
						$this->utility_lib->add_log("product_image_register", $product->description." > ".$datas["filename"]);
						
						$type = "success";
						$msg = $this->lang->line('s_image_add');
					}else $msg = $this->lang->line('error_internal');
				}else $msg = $this->upload->display_errors("<span>","</span>");
			}
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg, "img" => $datas]);
	}
	
	public function delete_image(){
		$type = "error"; $msg = null;
		
		if ($this->utility_lib->check_access("product", "update")){
			$image = $this->product->image($this->input->post("id"));
			$product = $this->product->id($image->product_id);
		
			//validate if this is main image
			if (strcmp($image->filename, $product->image)){
				$img_path = "uploaded/products/".$image->product_id."/".$image->filename;
				if (unlink($img_path)){
					if ($this->product->image_delete($image->id)){
						$this->utility_lib->add_log("product_image_delete", $product->description." > ".$image->filename);
						
						$type = "success";
						$msg = $this->lang->line('s_image_delete');
					}else $msg = $this->lang->line('error_internal');
				}else $msg = $this->lang->line('error_internal');	
			}else $msg = $this->lang->line('e_image_main_delete');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg, "id" => $image->id]);
	}
	
	public function set_product_image(){
		$type = "error"; $msg = null;
		
		if ($this->utility_lib->check_access("product", "update")){			
			$image = $this->product->image($this->input->post("id"));
			
			$data = array("image" => $image->filename, "updated_at" => date("Y-m-d H:i:s", time()));
			if ($this->product->update($image->product_id, $data)){
				$product = $this->general->id("product", $image->product_id);
				$this->utility_lib->add_log("product_set_main_image", $product->description." > ".$image->filename);
				
				$type = "success";
				$msg = $this->lang->line('s_image_main');
			}else $msg = $this->lang->line('error_internal');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg]);
	}

	public function save_provider(){
		$type = "error"; $msgs = []; $msg = null;
		$data = $this->input->post();
		
		$this->load->library('my_val');
		$msgs = $this->my_val->product_provider($msgs, $data);
		
		if (!$msgs){
			$product_id = $data["product_id"]; unset($data["product_id"]);
			$company = $this->general->filter("company", ["tax_id" => $data["tax_id"]]);
			if ($company){
				$provider_id = $company[0]->id;
				$this->general->update("company", $company[0]->id, $data);
			}else $provider_id = $this->general->insert("company", $data);
			
			$this->general->update("product", $product_id, ["provider_id" => $provider_id]);
			
			$provider = $this->general->id("company", $provider_id);
			$product = $this->general->id("product", $product_id);
			$this->utility_lib->add_log("provider_save", $product->description." > ".$provider->name);
			
			$type = "success";
			$msg = $this->lang->line('s_provider_save');
		}else $msg = $this->lang->line('error_occurred');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msgs" => $msgs, "msg" => $msg]);
	}
	
	public function clean_provider(){
		$type = "error"; $msg = null;
		
		if ($this->utility_lib->check_access("product", "update")){			
			$product_id = $this->input->post("product_id");
			
			if ($this->product->update($product_id, array("provider_id" => null))){
				$product = $this->general->id("product", $product_id);
				$this->utility_lib->add_log("provider_clean", $product->description);
				
				$type = "success";
				$msg = $this->lang->line('s_provider_clean');
			}else $msg = $this->lang->line('error_internal');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg]);
	}

	public function load_by_category(){
		$type = "error"; $msg = null; $list = [];
		
		$products = $this->general->filter("product", ["category_id" => $this->input->post("category_id")], null, null, "description", "asc");
		
		$prod_types = [];
		$prod_types_rec = $this->general->all("product_type");
		foreach($prod_types_rec as $item) $prod_types[$item->id] = $item->description;
		
		if ($products){
			foreach($products as $item){
				$list[] = [
					"id" => $item->id,
					"code" => $item->code,
					"type" => $prod_types[$item->type_id],
					"description" => $item->description,
					"currency" => $this->general->id("currency", $item->currency_id)->description,
					"price" => $item->price,
					"price_txt" => number_format($item->price, 2),
					"value" => $item->value,
					"vat" => $item->vat,
				];
			}
			$type = "success";
		}else $msg = $this->lang->line('e_category_no_product');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg, "list" => $list]);
	}

	public function load_option(){
		$type = "error"; $msg = null; $list = [];
		
		$f_w = $this->input->post();
		$f_w["stock >"] = 0;
		
		$list = $this->general->filter("product_option", $f_w, null, null, "id", "asc");
		if (count($list)) $type = "success";
		else $msg = $this->lang->line('e_product_no_stock');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg, "list" => $list]);
	}

	//used in commerce/sale/form_add_sale
	public function search_product(){
		$id = $this->input->post("id");
		$category_id = $this->input->post("category_id");
		$description = $this->input->post("description");
		
		$w = $l = [];
		$w["active"] = true;
		if ($id) $w["id"] = $id;
		if ($category_id) $w["category_id"] = $category_id;
		//if ($description) $l["description"] = $description;
		if ($description) $l[] = ["field" => "description", "values" => explode(" ", trim($description))];
		
		$products = $this->general->filter("product", $w, $l, null, "description", "asc");
		
		//echo $this->db->last_query();
		
		if ($products){
			$cat_arr = [];
			$categories = $this->general->all("product_category");
			foreach($categories as $c) $cat_arr[$c->id] = $c->name;
			
			$type_arr = [];
			$types = $this->general->all("product_type");
			foreach($types as $t) $type_arr[$t->id] = $t->description;
			
			$cur_arr = [];
			$currencies = $this->general->all("currency");
			foreach($currencies as $c) $cur_arr[$c->id] = $c->description;
			
			foreach($products as $p){
				$p->currency = $cur_arr[$p->currency_id];
				$p->category = $cat_arr[$p->category_id];
				$p->type = $type_arr[$p->type_id];
				$p->price_txt = number_format($p->price, 2);
				$p->stock_txt = ($p->type === "Producto") ? number_format($p->stock) : "-";
			}
		}
		
		//send with option information of first product
		if ($products) $products[0]->options = $this->general->filter("product_option", ["product_id" => $products[0]->id], null, null, "id", "asc");
		
		header('Content-Type: application/json');
		echo json_encode(["products" => $products]);
	}
}