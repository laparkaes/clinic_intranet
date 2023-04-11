<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Config extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/Lima');
		$this->lang->load("system", "spanish");
		$this->lang->load("config", "spanish");
		$this->load->model('account_model','account');
		$this->load->model('general_model','general');
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
		
		$data = array(
			"doc_types" => $this->general->all("doc_type", "id", "asc"),
			"role_access" => $role_access,
			"roles_arr" => $roles_arr,
			"roles" => $roles,
			"access" => $access,
			"people_arr" => $people_arr,
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
		if ($this->general->delete("account", ["id" => $this->input->post("id")])){
			$status = true;
			$type = "success";
			$msg = "Usuario ha sido eliminado.";
		}else{
			$status = false;
			$type = "error";
			$msg = "Ha ocurrido error. Vuelva a intentar.";
		}
		
		header('Content-Type: application/json');
		echo json_encode(["status" => $status, "type" => $type, "msg" => $msg]);
	}
	
	public function control_role_access(){
		$setting = $this->input->post("setting");
		$setting = isset($setting) && $setting === 'true';
		
		$value = explode("_", $this->input->post("value"));
		$data = array("role_id" => $value[0], "access_id" => $value[1]);
		
		if ($setting) $this->general->insert("role_access", $data);
		else $this->general->delete("role_access", $data);
	}
	
	public function update_company(){
		$datas = $this->input->post();
		$status = false; $msgs = array(); $msg = null; $cert_link = null;
		
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
	
		if (!$msgs){
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
				$cert_link = base_url()."uploaded/sunat/".$this->general->id("company", 1)->sunat_cert_filename;
				$status = true;
				$msg = $this->lang->line("success_cup");
			}else $msgs = $this->set_msg($msgs, "com_result_msg", "error", "error_internal");
		}
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "msgs" => $msgs, "msg" => $msg, "cert_link" => $cert_link));
	}
}
