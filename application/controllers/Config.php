<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Config extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/Lima');
		$this->lang->load("system", "spanish");
		$this->lang->load("config", "spanish");
		$this->lang->load("log", "spanish");
		$this->load->model('account_model','account');
		$this->load->model('general_model','general');
		$this->nav_menu = "config";
	}
	
	private function set_msg($msgs, $dom_id, $type, $msg_code){
		if ($msg_code) array_push($msgs, array("dom_id" => $dom_id, "type" => $type, "msg" => $this->lang->line($msg_code)));
		return $msgs;
	}
	
	public function index(){
		if (!$this->session->userdata('logged_in')) redirect(base_url());
		//pending! rol validation
		
		$modules = array("dashboard", "doctor", "patient", "appointment", "surgery", "product", "sale", "report", "config");
		$access = array();
		foreach($modules as $item) $access[$item] = $this->general->filter("access", ["module" => $item], "id", "asc");
		
		$role_access = [];
		$role_access_rec = $this->general->all("role_access", null);
		foreach($role_access_rec as $item) $role_access[] = $item->role_id."_".$item->access_id;
		
		$roles_arr = [];
		$roles = $this->general->all("role", "id", "asc");
		foreach($roles as $item) $roles_arr[$item->id] = $item->name;
		
		$people_ids_arr = [];
		$people_ids = $this->general->only("account", "person_id", null);
		foreach($people_ids as $item) $people_ids_arr[] = $item->person_id;
		
		$people_arr = [];
		$people = $this->general->filter_adv("person", null, [["field" => "id", "values" => $people_ids_arr]]);
		foreach($people as $item) $people_arr[$item->id] = $item->name;
		
		$exams_arr = [];
		$exams = $this->general->all("examination", "name", "asc");
		foreach($exams as $item) $exams_arr[$item->id] = $item->name;
		
		$sl_options = $this->general->only("sl_option", "code");
		foreach($sl_options as $item){
			$item->lang = $this->lang->line("slop_".$item->code);
			$item->values = $this->general->filter("sl_option", ["code" => $item->code], "id", "asc");
		}
		usort($sl_options, function($a, $b) { return strcmp($a->lang, $b->lang); });
		
		$account_arr = [];
		$accounts = $this->general->all("account");
		foreach($accounts as $item) $account_arr[$item->id] = $item->email;
		
		$log_code_arr = [];
		$log_codes = $this->general->all("log_code");
		foreach($log_codes as $item) $log_code_arr[$item->id] = $this->lang->line('log_'.$item->code);
		
		$logs = $this->general->filter("log", ["registed_at <=" => date("Y-m-d 00:00:00", strtotime("-6 months")), "registed_at <=" => date("Y-m-d 00:00:00", strtotime("+1 day"))], "registed_at", "desc");
		
		$data = array(
			"doc_types" => $this->general->all("doc_type", "id", "asc"),
			"role_access" => $role_access,
			"roles_arr" => $roles_arr,
			"roles" => $roles,
			"access" => $access,
			"people_arr" => $people_arr,
			"sl_options" => $sl_options,
			"account_arr" => $account_arr,
			"log_code_arr" => $log_code_arr,
			"logs" => $logs,
			"exam_profiles" => $this->general->all("examination_profile", "name", "asc"),
			"exam_category" => $this->general->all("examination_category", "name", "asc"),
			"exams_arr" => $exams_arr,
			"exams" => $exams,
			"accounts" => $this->general->all("account", "role_id", "asc"),
			"departments" => $this->general->all("address_department", "name", "asc"),
			"provinces" => $this->general->all("address_province", "name", "asc"),
			"districts" => $this->general->all("address_district", "name", "asc"),
			"company" => $this->general->id("company", 1),
			"title" => $this->lang->line('setting'),
			"main" => "config/index",
			"init_js" => "config/index.js"
		);
		
		$this->load->view('layout', $data);
	}
	
	public function register_account(){
		$status = false; $type = "error"; $msgs = []; $msg = null;
		
		$p = $this->input->post("p");
		$a = $this->input->post("a");
		
		//personal data validation
		if (!$p["name"]) $msgs = $this->set_msg($msgs, "ra_name_msg", "error", "error_ena");
		if (!$p["tel"]) $msgs = $this->set_msg($msgs, "ra_tel_msg", "error", "error_ete");
		if (!$p["doc_type_id"]) $msgs = $this->set_msg($msgs, "ra_doc_msg", "error", "error_sdt");
		if (!$p["doc_number"]) $msgs = $this->set_msg($msgs, "ra_doc_msg", "error", "error_edn");
		
		//account data validation
		if (!$a["role_id"]) $msgs = $this->set_msg($msgs, "ra_role_msg", "error", "error_sro");
		if ($a["email"]){
			if (filter_var($a["email"], FILTER_VALIDATE_EMAIL)){
				if ($this->account->email($a["email"])) $msgs = $this->set_msg($msgs, "ra_email_msg", "error", "error_usr");
			}else $msgs = $this->set_msg($msgs, "ra_email_msg", "error", "error_usf");
		}else $msgs = $this->set_msg($msgs, "ra_email_msg", "error", "error_eus");
		if ($a["password"]){
			if (strlen($a["password"]) < 6) $msgs = $this->set_msg($msgs, "ra_password_msg", "error", "error_pal");
		}else $msgs = $this->set_msg($msgs, "ra_password_msg", "error", "error_epa");
		if (strcmp($a["password"], $a["confirm"])) $msgs = $this->set_msg($msgs, "ra_confirm_msg", "error", "error_pac");
		
		if ($msgs) $msg = $this->lang->line('error_occurred');
		else{
			$person = $this->general->filter("person", ["doc_type_id" => $p["doc_type_id"], "doc_number" => $p["doc_number"]]);
			if ($person){
				$this->general->update("person", $person[0]->id, $p);
				$a["person_id"] = $person[0]->id;
			}else{
				$p["registed_at"] = date('Y-m-d H:i:s', time());
				$a["person_id"] = $this->general->insert("person", $p);
			}
			
			if ($this->general->filter("account", ["role_id" => $a["role_id"], "person_id" => $a["person_id"]])) 
				$msg = $this->lang->line('error_pra');
			else{
				unset($a["confirm"]);
				$a["password"] = password_hash($a["password"], PASSWORD_BCRYPT);
				$a["active"] = true;
				$a["registed_at"] = date('Y-m-d H:i:s', time());
				if ($this->account->insert($a)){
					$this->utility_lib->add_log("account_register", $a["email"]);
					
					$status = true;
					$type = "success";
					$msg = $this->lang->line('success_rac');
				}else $msg = $this->lang->line('error_internal');	
			}
		}
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msgs" => $msgs, "msg" => $msg));
	}
	
	public function remove_account(){
		$account = $this->general->id("account", $this->input->post("id"));
		if ($this->general->delete("account", ["id" => $account->id])){
			$this->utility_lib->add_log("account_delete", $account->email);
			
			$status = true;
			$type = "success";
			$msg = $this->lang->line('success_dac');
		}else{
			$status = false;
			$type = "error";
			$msg = $this->lang->line('error_internal');
		}
		
		header('Content-Type: application/json');
		echo json_encode(["status" => $status, "type" => $type, "msg" => $msg]);
	}
	
	public function reset_password(){
		$status = false; $type = "error"; $msg = null;
		
		$account = $this->general->id("account", $this->input->post("id"));
		if ($account){
			$person = $this->general->id("person", $account->person_id);
			if ($person) $pw = $person->doc_number;
			else $pw = "1234567890";
			
			if ($this->general->update("account", $account->id, ["password" => password_hash($pw, PASSWORD_BCRYPT)])){
				$status = true;
				$type = "success";
				$msg = str_replace("&pw&", $pw, $this->lang->line('success_uap'));
			}else $msg = $this->lang->line('error_internal');
		}else $msg = $this->lang->line('error_internal_refresh');
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msg" => $msg));
	}
	
	public function control_role_access(){
		$setting = $this->input->post("setting");
		$setting = isset($setting) && $setting === 'true';
		
		$value = explode("_", $this->input->post("value"));
		$data = array("role_id" => $value[0], "access_id" => $value[1]);
		
		if ($setting) $this->general->insert("role_access", $data);
		else $this->general->delete("role_access", $data);
	}
	
	public function update_company_data(){
		$datas = $this->input->post();
		$status = false; $type = "error"; $msgs = array(); $msg = null; $cert_link = null;
		
		//validations
		if (!$datas["ruc"]) $msgs = $this->set_msg($msgs, "com_ruc_msg", "error", "error_cru");
		if (!$datas["name"]) $msgs = $this->set_msg($msgs, "com_name_msg", "error", "error_cna");
		if (!$datas["email"]) $msgs = $this->set_msg($msgs, "com_email_msg", "error", "error_cem");
		if (!$datas["tel"]) $msgs = $this->set_msg($msgs, "com_tel_msg", "error", "error_cte");
		if (!$datas["address"]) $msgs = $this->set_msg($msgs, "com_address_msg", "error", "error_cad");
		if (!$datas["department_id"]) $msgs = $this->set_msg($msgs, "com_department_msg", "error", "error_cde");
		if (!$datas["province_id"]) $msgs = $this->set_msg($msgs, "com_province_msg", "error", "error_cpr");
		if (!$datas["district_id"]) $msgs = $this->set_msg($msgs, "com_district_msg", "error", "error_cdi");
		if (!$datas["sunat_resolution"]) $msgs = $this->set_msg($msgs, "s_res_msg", "error", "error_sre");
		if (!$datas["sunat_clave_sol"]) $msgs = $this->set_msg($msgs, "s_cla_msg", "error", "error_scs");
		if (!$datas["sunat_password"]) $msgs = $this->set_msg($msgs, "s_pas_msg", "error", "error_spa");
		if (!$_FILES["sunat_cert_file"]["name"]) if (!$this->general->id("company", 1)->sunat_cert_filename) 
			$msgs = $this->set_msg($msgs, "s_cer_msg", "error", "error_sce");
	
		if ($msgs) $msg = $this->lang->line("error_occurred");
		else{
			$datas["ubigeo"] = $this->general->id("address_district", $datas["district_id"])->ubigeo;
			$datas["updated_by"] = $this->session->userdata('aid');
			$datas["updated_at"] = date("Y-m-d H:i:s", time());
			if ($_FILES["sunat_cert_file"]["name"]){
				$upload_dir = "uploaded/sunat";
				if(!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
				$upload_dir = $upload_dir."/";
				
				$this->load->library('upload');
				$config_upload = array(
					'upload_path' => $upload_dir,
					'allowed_types' => '*',
					'max_size' => 0,
					'overwrite' => true
				);
				
				$this->upload->initialize($config_upload);
				if ($this->upload->do_upload("sunat_cert_file")){
					$result = $this->upload->data();
					$datas["sunat_cert_filename"] = $result["file_name"];
				}else $msgs = $this->set_msg($msgs, "s_cer_msg", "error", $this->upload->display_errors("<span>","</span>"));
			}
			
			if ($this->general->update("company", 1, $datas)){
				$this->utility_lib->add_log("company_update", null);
				
				$status = true;
				$type = "success";
				$msg = $this->lang->line("success_cup");
			}else $msg = $this->lang->line("error_internal");
		}
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msgs" => $msgs, "msg" => $msg));
	}

	public function add_sl_value(){
		$data = $this->input->post();
		$status = false; $type = "error"; $msg = null;
		
		if ($data["description"]){
			$new_id = $this->general->insert("sl_option", $data);
			if ($new_id){
				$new_value = $this->general->id("sl_option", $new_id);
				$this->utility_lib->add_log("sl_value_register", $new_value->description);
				
				$msg = $this->lang->line("success_rsv");
				$type = "success";
				$status = true;
			}else $msg = $this->lang->line("error_internal");
		}else $msg = $this->lang->line("error_evd");
		
		header('Content-Type: application/json');
		echo json_encode(["status" => $status, "type" => $type, "msg" => $msg, "new_value" => $new_value]);
	}
	
	public function remove_sl_value(){
		$id = $this->input->post("id");
		$status = false; $type = "error"; $msg = null;
		
		$removed_value = $this->general->id("sl_option", $id);
		if ($this->general->delete("sl_option", ["id" => $id])){
			$this->utility_lib->add_log("sl_value_delete", $removed_value->description);
			
			$msg = $this->lang->line("success_dsv");
			$type = "success";
			$status = true;
		}else $msg = $this->lang->line("error_internal");
		
		header('Content-Type: application/json');
		echo json_encode(["status" => $status, "type" => $type, "msg" => $msg, "removed_value" => $removed_value]);
	}
	
	public function register_profile(){
		$status = false; $type = "error"; $msgs = []; $msg = null;
		
		$name = $this->input->post("name");
		$exams = $this->input->post("exams");
		
		if (!$name) $msgs = $this->set_msg($msgs, "rp_name_msg", "error", "error_epn");
		elseif ($this->general->filter("examination_profile", ["name" => $name])) $msgs = $this->set_msg($msgs, "rp_name_msg", "error", "error_dpn");
		if (!$exams) $msgs = $this->set_msg($msgs, "rp_exams_msg", "error", "error_spe");
		
		if ($msgs) $msg = $this->lang->line("error_occurred");
		else{
			sort($exams);
			if ($this->general->insert("examination_profile", ["name" => $name, "examination_ids" => implode(",", $exams)])){
				$this->utility_lib->add_log("profile_register", $name);
				
				$status = true;
				$type = "success";
				$msg = $this->lang->line("success_rep");
			}
		}
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msgs" => $msgs, "msg" => $msg));
	}
	
	public function remove_profile(){
		$status = false; $type = "error"; $msg = null;
		
		$profile = $this->general->id("examination_profile", $this->input->post("id"));
		if ($this->general->delete("examination_profile", ["id" => $profile->id])){
			$this->utility_lib->add_log("profile_delete", $profile->name);
			
			$status = true;
			$type = "success";
			$msg = $this->lang->line('success_dep');
		}else{
			$status = false;
			$type = "error";
			$msg = $this->lang->line('error_internal');
		}
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msg" => $msg));
	}
}
