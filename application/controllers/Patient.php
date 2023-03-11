<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Patient extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/Lima');
		$this->lang->load("patient", "spanish");
		$this->lang->load("system", "spanish");
		$this->load->model('role_model','role');
		$this->load->model('appointment_model','appointment');
		$this->load->model('account_model','account');
		$this->load->model('patient_file_model','patient_file');
		$this->load->model('specialty_model','specialty');
		$this->load->model('status_model','status');
		$this->load->model('patient_file_model','patient_file');
	}
	
	private function set_msg($msgs, $dom_id, $type, $msg_code){
		if ($msg_code) array_push($msgs, array("dom_id" => $dom_id, "type" => $type, "msg" => $this->lang->line($msg_code)));
		return $msgs;
	}
	
	public function index(){
		if (!$this->session->userdata('logged_in')) redirect(base_url());
		//PENDING! rol validation
		
		$doc_types_arr = array();
		$doc_types = $this->general->all("doc_type", "id", "asc");
		foreach($doc_types as $item) $doc_types_arr[$item->id] = $item->short;
		
		$data = array(
			"patients" => $this->general->all("person", "name", "asc"),
			"doc_types" => $doc_types,
			"doc_types_arr" => $doc_types_arr,
			"sex_ops" => $this->general->filter("sl_option", array("code" => "sex")),
			"blood_type_ops" => $this->general->filter("sl_option", array("code" => "blood_type")),
			"title" => $this->lang->line('patients'),
			"main" => "patient/list",
			"init_js" => "patient/list.js"
		);
		
		$this->load->view('layout', $data);
	}
	
	public function detail($id){
		if (!$this->session->userdata('logged_in')) redirect(base_url());
		
		$person = $this->general->id("person", $id);
		$person->doc_type = $this->general->id("doc_type", $person->doc_type_id)->short;
		if ($person->birthday) $person->age = $this->utility_lib->age_calculator($person->birthday);
		else $person->age = null;
		if ($person->sex_id) $person->sex = $this->general->id("sl_option", $person->sex_id)->description;
		else $person->sex = null;
		if ($person->blood_type_id) $person->blood_type = $this->general->id("sl_option", $person->blood_type_id)->description;
		else $person->blood_type = null;
		
		$account = $this->account->filter(array("person_id" => $person->id));
		if ($account){
			$account = $account[0];
			$person->email = $account->email;
		}else $person->email = "";
		
		$appointments = $this->appointment->filter(array("patient_id" => $person->id));
		$surgeries = array();
		
		$specialty_arr = array();
		$specialties = $this->general->all("specialty", "name", "asc");
		foreach($specialties as $item) $specialty_arr[$item->id] = $item->name;
		
		$doctors_arr = array();
		$doctors = $this->general->all("doctor");
		foreach($doctors as $d){
			$d->name = $this->general->id("person", $d->person_id)->name;
			$doctors_arr[$d->person_id] = $d;
		}
		usort($doctors, function($a, $b) {return strcmp(strtoupper($a->name), strtoupper($b->name));});
		
		$status_arr = array();
		$status = $this->status->all();
		foreach($status as $item) $status_arr[$item->id] = $item;
		
		$currencies_arr = array();
		$currencies = $this->general->all("currency");
		foreach($currencies as $item) $currencies_arr[$item->id] = $item;
		
		$sales = $this->general->filter("sale", array("client_id" => $person->id));
		
		$data = array(
			"person" => $person,
			"account" => $account,
			"appointments" => $appointments,
			"surgeries" => $surgeries,
			"specialties" => $specialties,
			"doctors" => $doctors,
			"sales" => $sales,
			"doctors_arr" => $doctors_arr,
			"specialty_arr" => $specialty_arr,
			"status_arr" => $status_arr,
			"currencies_arr" => $currencies_arr,
			"sex_ops" => $this->general->filter("sl_option", array("code" => "sex")),
			"blood_type_ops" => $this->general->filter("sl_option", array("code" => "blood_type")),
			"patient_files" => $this->patient_file->filter(array("patient_id" => $person->id, "active" => true)),
			"title" => $this->lang->line('patient'),
			"main" => "patient/detail",
			"init_js" => "patient/detail.js"
		);
		
		$this->load->view('layout', $data);
	}
	
	public function register(){
		$p = $this->input->post("personal");
		$a = $this->input->post("account");
		$status = false; $type = "error"; $msgs = array(); $msg = $this->lang->line('error_occurred'); $move_to = null;
		
		//personal data validation
		if (!$p["name"]) $msgs = $this->set_msg($msgs, "pn_name_msg", "error", "error_ena");
		if (!$p["tel"]) $msgs = $this->set_msg($msgs, "pn_tel_msg", "error", "error_ete");
		if (!$p["doc_type_id"]) $msgs = $this->set_msg($msgs, "pn_doc_msg", "error", "error_sdt");
		if (!$p["doc_number"]) $msgs = $this->set_msg($msgs, "pn_doc_msg", "error", "error_edn");
		if ($this->general->filter("person", array("doc_type_id" => $p["doc_type_id"], "doc_number" => $p["doc_number"])))
			 $msgs = $this->set_msg($msgs, "pn_doc_msg", "error", "error_pex");
		
		//account data validation > account is optional
		if ($a["email"]){
			if (filter_var($a["email"], FILTER_VALIDATE_EMAIL)){
				if ($this->account->email($a["email"])) $msgs = $this->set_msg($msgs, "pn_email_msg", "error", "error_usr");
			}else $msgs = $this->set_msg($msgs, "pn_email_msg", "error", "error_usf");
			if ($a["password"]){
				if (strlen($a["password"]) < 6) $msgs = $this->set_msg($msgs, "pn_password_msg", "error", "error_pal");
			}else $msgs = $this->set_msg($msgs, "pn_password_msg", "error", "error_epa");
			if (strcmp($a["password"], $a["confirm"])) $msgs = $this->set_msg($msgs, "pn_confirm_msg", "error", "error_pac");
		}
		
		if (!$msgs){
			$filter = array("doc_type_id" => $p["doc_type_id"], "doc_number" => $p["doc_number"]);
			$people = $this->general->filter("person", $filter, "id", "asc", 0, 1);
			if ($people){
				$person = $people[0];
				$this->general->update("person", $person->id, $p);
				$person_id = $person->id;
			}else{
				$p["registed_at"] = date('Y-m-d H:i:s', time());
				$person_id = $this->general->insert("person", $p);
			}
			
			if ($person_id){
				if ($a["email"]){
					unset($a["confirm"]);
					$a["person_id"] = $person_id;
					$a["password"] = password_hash($a["password"], PASSWORD_BCRYPT);
					$a["active"] = true;
					$a["registed_at"] = date('Y-m-d H:i:s', time());
					$account_id = $this->account->insert($a);
					if ($account_id) $this->general->insert("account_role", array("account_id" => $account_id, "role_id" => $this->role->name("patient")->id));
				}
				
				$status = true;
				$type = "success";
				$move_to = base_url()."patient/detail/".$person_id;
				$msg = $this->lang->line('success_rpa');
			}else $msgs = $this->set_msg($msgs, "dn_result_msg", "error", "error_rpd");
		}
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msgs" => $msgs, "msg" => $msg, "move_to" => $move_to));
	}
	
	public function form_create_account(){
		$status = false; $type = "error"; $msgs = array(); $msg = null;
		$data = $this->input->post();
		
		if ($data["email"]){
			if (filter_var($data["email"], FILTER_VALIDATE_EMAIL)){
				if ($this->account->email($data["email"])) $msgs = $this->set_msg($msgs, "ca_email_msg", "error", "error_usr");
			}else $msgs = $this->set_msg($msgs, "ca_email_msg", "error", "error_usf");
		}else $msgs = $this->set_msg($msgs, "ca_email_msg", "error", "error_eus");
		if ($data["password"]){
			if (strlen($data["password"]) < 6) $msgs = $this->set_msg($msgs, "ca_password_msg", "error", "error_pal");
		}else $msgs = $this->set_msg($msgs, "ca_password_msg", "error", "error_epa");
		if (strcmp($data["password"], $data["confirm"])) $msgs = $this->set_msg($msgs, "ca_confirm_msg", "error", "error_pac");
		
		if ($msgs) $msg = $this->lang->line('error_occurred');
		else{
			unset($data["confirm"]);
			$data["password"] = password_hash($data["password"], PASSWORD_BCRYPT);
			$data["active"] = true;
			$data["registed_at"] = date('Y-m-d H:i:s', time());
			$account_id = $this->general->insert("account", $data);
			if ($account_id){
				$role = $this->role->name("patient");
				$this->general->insert("account_role", array("account_id" => $account_id, "role_id" => $role->id));
				
				$status = true;
				$type = "success"; 
				$msg = $this->lang->line('success_cao');
			}else $msg = $this->lang->line('error_internal');
		}
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msg" => $msg, "msgs" => $msgs));
	}
	
	public function update_personal_data(){
		$status = false; $type = "error"; $msgs = array(); $msg = null;
		$data = $this->input->post();
		
		if ($msgs) $msg = $this->lang->line('error_occurred');
		else{
			if (!$data["tel"]) $data["tel"] = null;
			if (!$data["address"]) $data["address"] = null;
			if (!$data["birthday"]) $data["birthday"] = null;
			if (!$data["sex_id"]) $data["sex_id"] = null;
			if (!$data["blood_type_id"]) $data["blood_type_id"] = null;
			if ($this->general->update("person", $data["id"], $data)){
				$status = true;
				$type = "success"; 
				$msg = $this->lang->line('success_upd');
			}else $msg = $this->lang->line('error_internal');
		}
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msg" => $msg, "msgs" => $msgs));
	}
	
	public function update_account_email(){
		$status = false; $type = "error"; $msgs = array(); $msg = null;
		$data = $this->input->post();
		
		if ($data["email"]){
			if (!filter_var($data["email"], FILTER_VALIDATE_EMAIL))
				$msgs = $this->set_msg($msgs, "ae_email_msg", "error", "error_usf");
		}else $msgs = $this->set_msg($msgs, "ae_email_msg", "error", "error_eus");
		
		if ($msgs) $msg = $this->lang->line('error_occurred');
		else{
			if ($this->general->update("account", $data["id"], $data)){
				$status = true;
				$type = "success"; 
				$msg = $this->lang->line('success_uae');
			}else $msg = $this->lang->line('error_internal');
		}
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msg" => $msg, "msgs" => $msgs));
	}
	
	public function update_account_password(){
		$status = false; $type = "error"; $msgs = array(); $msg = null;
		$data = $this->input->post();
		
		if ($data["password"]){
			if (strlen($data["password"]) < 6) $msgs = $this->set_msg($msgs, "up_password_msg", "error", "error_pal");
		}else $msgs = $this->set_msg($msgs, "up_password_msg", "error", "error_epa");
		if (strcmp($data["password"], $data["confirm"])) $msgs = $this->set_msg($msgs, "up_confirm_msg", "error", "error_pac");
		
		if ($msgs) $msg = $this->lang->line('error_occurred');
		else{
			if ($data["password"]) $data["password"] = password_hash($data["password"], PASSWORD_BCRYPT);
			unset($data["confirm"]);
			
			if ($this->general->update("account", $data["id"], $data)){
				$status = true;
				$type = "success"; 
				$msg = $this->lang->line('success_uep');
			}else $msg = $this->lang->line('error_internal');
		}
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msg" => $msg, "msgs" => $msgs));
	}
	
	public function update(){
		$person = $this->general->id("person", $this->input->post("patient_id"));
		$p = $this->input->post("personal");
		$a = $this->input->post("account");
		$result = array("status" => false, "msg" => null);
		$msgs = array();
		
		$account = $this->account->filter(array("person_id" => $person->id, "role_id" => $this->role->name("patient")->id));
		if ($account) $account = $account[0];
		
		//personal data validation
		if (!$p["tel"]) $msgs = $this->set_msg($msgs, "pu_tel_msg", "error", "error_ete");
		if (!$p["doc_type_id"]) $msgs = $this->set_msg($msgs, "pu_doc_type_msg", "error", "error_sdt");
		if (!$p["doc_number"]) $msgs = $this->set_msg($msgs, "pu_doc_number_msg", "error", "error_edn");
		//optional datas
		if (!$p["birthday"]) unset($p["birthday"]);
		if (!$p["sex"]) unset($p["sex"]);
		if (!$p["blood_type"]) unset($p["blood_type"]);
		if (!$p["address"]) unset($p["address"]);
		
		//password data validation
		if ($a["email"]){
			if (!filter_var($a["email"], FILTER_VALIDATE_EMAIL)) 
				$msgs = $this->set_msg($msgs, "pu_email_msg", "error", "error_usf");
		}else $msgs = $this->set_msg($msgs, "pu_email_msg", "error", "error_eus");
		if ($a["password"] or $a["confirm"]){
			if ($a["password"]){
				if (strlen($a["password"]) < 6) $msgs = $this->set_msg($msgs, "pu_password_msg", "error", "error_pal");
			}else $msgs = $this->set_msg($msgs, "pu_password_msg", "error", "error_epa");
			if (strcmp($a["password"], $a["confirm"])) $msgs = $this->set_msg($msgs, "pu_confirm_msg", "error", "error_pac");	
		}
		
		if (!$msgs){
			if ($this->general->update("person", $person->id, $p)){
				if ($a["password"]) $a["password"] = password_hash($a["password"], PASSWORD_BCRYPT);
				else unset($a["password"]);
				unset($a["confirm"]);
				
				if ($a["email"]){
					if ($account){
						if (!$this->account->update($account->id, $a)) $msgs = $this->set_msg($msgs, "du_result_msg", "error", "error_uac");
					}else{
						$a["role_id"] = $this->role->name("patient")->id;
						$a["person_id"] = $person->id;
						$a["active"] = true;
						$a["registed_at"] = date('Y-m-d H:i:s', time());
						if (!$this->account->insert($a)) $msgs = $this->set_msg($msgs, "du_result_msg", "error", "error_uac");
					}
				}
			}else $msgs = $this->set_msg($msgs, "du_result_msg", "error", "error_upd");
		}
		
		
		if ($msgs) $result["msgs"] = $msgs;
		else{
			$result["msg"] = $this->lang->line('success_upa');
			$result["status"] = true;	
		}
		
		header('Content-Type: application/json');
		echo json_encode($result);
	}
	
	public function upload_file(){
		$status = false; $msgs = array(); $msg = null;
		
		$title = $this->input->post("title");
		
		if (!$title) $msgs = $this->set_msg($msgs, "pf_title_msg", "error", "error_fte");
		if (!$_FILES["upload_file"]["name"]) $msgs = $this->set_msg($msgs, "pf_file_msg", "error", "error_fse");
		
		if (!$msgs){
			$patient = $this->general->id("person", $this->input->post("patient_id"));
			if ($patient){
				$upload_dir = "uploaded/patient_files/".$patient->doc_type_id."_".$patient->doc_number;
				if(!is_dir($upload_dir)){mkdir($upload_dir, 0777, true);}
				$upload_dir = $upload_dir."/";
				
				$this->load->library('upload');
				$config_upload = array(
					'upload_path' => $upload_dir,
					'allowed_types' => '*',
					'max_size' => 0,
					'overwrite' => false,
					'file_name' => date("YmdHis")
				);
				
				$this->upload->initialize($config_upload);
				if ($this->upload->do_upload("upload_file")){
					$result = $this->upload->data();
					$patient_file = array(
						"patient_id" => $patient->id,
						"title" => $title,
						"filename" => $result["file_name"],
						"active" => true,
						"registed_at" => date('Y-m-d H:i:s', time())
					);
					
					if ($this->patient_file->insert($patient_file)){
						$msg = $this->lang->line('success_ufi');
						$status = true;
					}
					else $msgs = $this->set_msg($msgs, "pf_result_msg", "error", "error_internal");
				}else array_push($msgs, array("dom_id" => "pf_result_msg", "type" => "error", "msg" => $this->upload->display_errors("<span>","</span>")));
			}else $msgs = $this->set_msg($msgs, "pf_result_msg", "error", "error_internal_refresh");
		}
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "msgs" => $msgs, "msg" => $msg));
	}
	
	public function delete_file(){
		//change "active" field of DB without removing uploaded file
		//PENDING! role validation
		
		$id = $this->input->post("id");
		if ($this->patient_file->update($id, array("active" => false))){
			$status = true;
			$msg = $this->lang->line('success_dfi');
		}else{
			$status = false;
			$msg = $this->lang->line('error_internal');
		}
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "msg" => $msg));
	}
}
