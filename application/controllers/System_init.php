<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class System_init extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/Lima');
		$this->lang->load("system_init", "spanish");
		$this->lang->load("system", "spanish");
		$this->load->model('general_model','general');
	}
	
	public function index(){
		$this->load->library('my_val');
		if ($this->my_val->system_init()) redirect("/auth");
		//return true if systen init is completed
		
		$sys_conf = $this->general->id("system", 1);
		if (!$sys_conf) $sys_conf = $this->general->structure("system");
		
		//company data set
		if ($sys_conf->company_id) $sys_conf->company = $this->general->id("company", $sys_conf->company_id);
		else $sys_conf->company = $this->general->structure("company");
		
		//account data set
		if ($sys_conf->account_id) $sys_conf->account = $this->general->id("account", $sys_conf->account_id);
		else $sys_conf->account = $this->general->structure("account");
		
		$data = [
			"sys_conf" => $sys_conf,
			"departments" => $this->general->all("address_department", "name", "asc"),
			"provinces" => $this->general->all("address_province", "name", "asc"),
			"districts" => $this->general->all("address_district", "name", "asc"),
			"sale_types" => $this->general->all("sale_type", "sunat_serie", "asc"),
			"title" => $this->lang->line('system_init'),
		];
		
		$this->load->view("system_init/index", $data);
	}
	
	public function company(){
		$type = "error"; $msgs = []; $msg = null;
		$data = $this->input->post();
		
		$this->load->library('my_val');
		$msgs = $this->my_val->config_company($msgs, "com_", $data);
		if (!$msgs){
			$company = $this->general->filter("company", ["tax_id" => $data["tax_id"]]);
			if ($company){
				$com_id = $company[0]->id;
				$this->general->update("company", $com_id, $data);
			}else $com_id = $this->general->insert("company", $data);
			
			$sys_conf = $this->general->id("system", 1);
			if (!$sys_conf) $this->general->insert("system", ["id" => 1]);
			
			if ($this->general->update("system", 1, ["company_id" => $com_id])){
				$type = "success";
				$msg = $this->lang->line('success_cup');
			}else $msg = $this->lang->line('error_internal');
		}else $msg = $this->lang->line("error_occurred");
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msgs" => $msgs, "msg" => $msg]);
	}
	
	public function account(){
		$type = "error"; $msgs = []; $msg = null;
		$data = $this->input->post();
		
		$this->load->library('my_val');
		$msgs = $this->my_val->account($msgs, "acc_", $data);
		
		if (!$msgs){
			//create system person data
			$person = $this->general->filter("person", ["doc_type_id" => 1, "name" => "Sistema"]);
			if ($person) $person_id = $person[0]->id;
			else{
				$person_data = ["doc_type_id" => 1, "name" => "Sistema", "registed_at" => date('Y-m-d H:i:s', time())];
				$person_id = $this->general->insert("person", $person_data);
			}
			
			if ($person_id){
				$acc_data = [
					"role_id" => 1,
					"person_id" => $person_id,
					"email" => $data["email"],
					"password" => password_hash($data["password"], PASSWORD_BCRYPT),
					"registed_at" => date('Y-m-d H:i:s', time()),
				];
				
				$acc_id = $this->general->insert("account", $acc_data);
				if ($acc_id){
					$sys_conf = $this->general->id("system", 1);
					if (!$sys_conf) $this->general->insert("system", ["id" => 1]);
					
					if ($this->general->update("system", 1, ["account_id" => $acc_id])){
						$type = "success";
						$msg = $this->lang->line('success_rac');
					}else $msg = $this->lang->line('error_internal');
				}else $msg = $this->lang->line('error_internal');
			}else $msg = $this->lang->line('error_internal');
		}else $msg = $this->lang->line("error_occurred");
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msgs" => $msgs, "msg" => $msg]);
	}
	
	public function add_sale_type(){
		$type = "error"; $msgs = []; $msg = null;
		$data = $this->input->post();
		
		$sys_conf = $this->general->id("system", 1);
		if (!$sys_conf) $this->general->insert("system", ["id" => 1]);
		
		$this->general->update("system", 1, ["sale_type_finished" => false]);
		
		$this->load->library('my_val');
		$msgs = $this->my_val->sale_type($msgs, "st_", $data);
		
		if (!$msgs){
			if ($this->general->insert("sale_type", $data)){
				$type = "success";
				$msg = $this->lang->line('success_rst');
			}else $msg = $this->lang->line('error_internal');
		}else $msg = $this->lang->line("error_occurred");
		
		$sale_types = $this->general->all("sale_type", "sunat_serie", "asc");
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msgs" => $msgs, "msg" => $msg, "sale_types" => $sale_types]);
	}
	
	public function remove_sale_type(){
		$type = "error"; $msg = null;
		$sale_type = $this->general->id("sale_type", $this->input->post("id"));
		
		$sys_conf = $this->general->id("system", 1);
		if (!$sys_conf) $this->general->insert("system", ["id" => 1]);
		
		$this->general->update("system", 1, ["sale_type_finished" => false]);
		
		if ($sale_type){
			if (!$this->general->filter("sale", ["sale_type_id" => $sale_type->id])){
				if ($this->general->delete("sale_type", ["id" => $sale_type->id])){
					$type = "success";
					$msg = $this->lang->line("success_dst");
				}else $msg = $this->lang->line("error_internal");
			}else $msg = $this->lang->line("error_stu");
		}else $msg = $this->lang->line("error_stne");
		
		$sale_types = $this->general->all("sale_type", "sunat_serie", "asc");
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg, "sale_types" => $sale_types]);
	}
	
	public function finish_sale_type(){
		$type = "error"; $msg = null;
		
		$sys_conf = $this->general->id("system", 1);
		if (!$sys_conf) $this->general->insert("system", ["id" => 1]);
		
		if ($this->general->all("sale_type")){
			if ($this->general->update("system", 1, ["sale_type_finished" => true])){
				$type = "success";
				$msg = $this->lang->line("success_fst");
			}else $msg = $this->lang->line("error_internal");
		}else $msg = $this->lang->line("error_stlo");
		
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg]);
	}
	
	
	
	public function finish_init(){
		$type = "error"; $msg = null;
		
		$sys_conf = $this->general->id("system", 1);
		if (!$sys_conf) $this->general->insert("system", ["id" => 1]);
		
		$this->load->library('my_val');
		$is_finished = $this->my_val->system_init_finish();
		
		$this->general->update("system", 1, ["is_finished" => $is_finished]);
		
		if ($is_finished){
			if ($this->general->update("system", 1, ["is_finished" => true])){
				$type = "success";
				$msg = $this->lang->line("success_inf");
			}else $msg = $this->lang->line("error_internal");
		}else $msg = $this->lang->line("error_sinf");
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg]);
	}
}
