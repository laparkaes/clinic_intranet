<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Doctor extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/Lima');
		$this->lang->load("doctor", "spanish");
		$this->lang->load("system", "spanish");
		$this->load->model('sl_option_model','sl_option');
		$this->load->model('specialty_model','specialty');
		$this->load->model('role_model','role');
		$this->load->model('account_model','account');
		$this->load->model('appointment_model','appointment');
		$this->load->model('status_model','status');
	}
	
	private function set_msg($msgs, $dom_id, $type, $msg_code){
		if ($msg_code) array_push($msgs, array("dom_id" => $dom_id, "type" => $type, "msg" => $this->lang->line($msg_code)));
		return $msgs;
	}
	
	public function index(){
		if (!$this->session->userdata('logged_in')) redirect("/");
		//PENDING!!! rol validation
		
		$specialties_arr = array();
		$specialties = $this->specialty->all();
		foreach($specialties as $item) $specialties_arr[$item->id] = $item->name;
		
		$doctors = $this->general->all("doctor");
		foreach($doctors as $item) $item->person = $this->general->id("person", $item->person_id);
		usort($doctors, function($a, $b) {
			return strcmp($a->person->name, $b->person->name);
		});
		
		$status = array();
		$status_rec = $this->general->all("status");
		foreach($status_rec as $item){
			$item->text = $this->lang->line($item->code);
			$status[$item->id] = $item;
		}
		
		$data = array(
			"doc_types" => $this->general->all("doc_type", "sunat_code", "asc"),
			"specialties_arr" => $specialties_arr,
			"specialties" => $specialties,
			"doctors" => $doctors,
			"status" => $status,
			"sex_ops" => $this->general->filter("sl_option", array("code" => "sex")),
			"blood_type_ops" => $this->general->filter("sl_option", array("code" => "blood_type")),
			"title" => $this->lang->line('doctors'),
			"main" => "doctor/list",
			"init_js" => "doctor/list.js"
		);
		
		$this->load->view('layout', $data);
	}
	
	public function detail($id){
		if (!$this->session->userdata('logged_in')) redirect(base_url());
		
		$doctor = $this->general->id("doctor", $id);
		$person = $this->general->id("person", $doctor->person_id);
		$account = $this->account->filter(array("person_id" => $person->id))[0];
		
		$role = $this->role->name("doctor");
		$f = array("role_id" => $role->id, "account_id" => $account->id);
		if (!$this->general->filter("account_role", $f)){
			if ($doctor) $this->general->insert("account_role", $f);
			else redirect("/doctor");
		}
		
		//set doctor data
		$doctor->specialty = $this->specialty->id($doctor->specialty_id)->name;
		$doctor->status = $this->general->id("status", $doctor->status_id);
		if (!strcmp("enabled", $doctor->status->code)){
			$doctor->status->dom_id = "btn_deactivate";
			$doctor->status->btn_color = "danger";
			$doctor->status->btn_txt = $this->lang->line('btn_deactivate_doctor');
		}else{
			$doctor->status->dom_id = "btn_activate";
			$doctor->status->btn_color = "success";
			$doctor->status->btn_txt = $this->lang->line('btn_activate_doctor');
		}
		
		//set personal data
		$person->doc_type = $this->general->id("doc_type", $person->doc_type_id)->short;
		if ($person->birthday) $person->age = $this->utility_lib->age_calculator($person->birthday);
		else $person->age = null;
		if ($person->sex_id) $person->sex = $this->general->id("sl_option", $person->sex_id)->description;
		else $person->sex = null;
		if ($person->blood_type_id) $person->blood_type = $this->general->id("sl_option", $person->blood_type_id)->description;
		else $person->blood_type = null;
		
		//load other data
		$patient_ids = array();
		$appointments = $this->appointment->filter(array("doctor_id" => $person->id));
		foreach($appointments as $item) array_push($patient_ids, $item->patient_id);
		
		$surgeries = array();
		foreach($surgeries as $item) array_push($patient_ids, $item->patient_id);
		$patient_ids = array_unique($patient_ids);
		
		$patient_arr = array();
		$patients = $this->general->ids("person", $patient_ids);
		foreach($patients as $item) $patient_arr[$item->id] = $item->name;
		
		$status_arr = array();
		$status = $this->status->all();
		foreach($status as $item) $status_arr[$item->id] = $item;
		
		$specialties_arr = array();
		$specialties = $this->specialty->all();
		foreach($specialties as $item) $specialties_arr[$item->id] = $item->name;
		
		$data = array(
			"doctor" => $doctor,
			"person" => $person,
			"account" => $account,
			"patients" => $patients,
			"appointments" => $appointments,
			"surgeries" => $surgeries,
			"doc_types" => $this->general->all("doc_type", "id", "asc"),
			"patient_arr" => $patient_arr,
			"status_arr" => $status_arr,
			"specialties" => $specialties,
			"specialties_arr" => $specialties_arr,
			"sex_ops" => $this->general->filter("sl_option", array("code" => "sex")),
			"blood_type_ops" => $this->general->filter("sl_option", array("code" => "blood_type")),
			"title" => $this->lang->line('doctor'),
			"main" => "doctor/detail",
			"init_js" => "doctor/detail.js"
		);
		$this->load->view('layout', $data);
	}
	
	public function register(){
		$p = $this->input->post("personal");
		$d = $this->input->post("doctor");
		$a = $this->input->post("account");
		$status = false; $type = "error"; $msgs = array(); $msg = null; $move_to = null;
		
		//personal data validation
		if (!$p["name"]) $msgs = $this->set_msg($msgs, "dn_name_msg", "error", "error_ena");
		if (!$p["tel"]) $msgs = $this->set_msg($msgs, "dn_tel_msg", "error", "error_ete");
		if (!$p["doc_type_id"]) $msgs = $this->set_msg($msgs, "dn_doc_msg", "error", "error_sdt");
		if (!$p["doc_number"]) $msgs = $this->set_msg($msgs, "dn_doc_msg", "error", "error_edn");
		/* optionals: $p["birthday"], $p["sex"], $p["blood_type"], $p["address"] */
		
		//doctor data validation
		if (!$d["specialty_id"]) $msgs = $this->set_msg($msgs, "dn_specialty_msg", "error", "error_ssp");
		if (!$d["license"]) $msgs = $this->set_msg($msgs, "dn_license_msg", "error", "error_eli");
		
		//account data validation
		if ($a["email"]){
			if (filter_var($a["email"], FILTER_VALIDATE_EMAIL)){
				if ($this->account->email($a["email"])) $msgs = $this->set_msg($msgs, "dn_email_msg", "error", "error_usr");
			}else $msgs = $this->set_msg($msgs, "dn_email_msg", "error", "error_usf");
		}else $msgs = $this->set_msg($msgs, "dn_email_msg", "error", "error_eus");
		if ($a["password"]){
			if (strlen($a["password"]) < 6) $msgs = $this->set_msg($msgs, "dn_password_msg", "error", "error_pal");
		}else $msgs = $this->set_msg($msgs, "dn_password_msg", "error", "error_epa");
		if (strcmp($a["password"], $a["confirm"])) $msgs = $this->set_msg($msgs, "dn_confirm_msg", "error", "error_pac");
		
		//register to DB
		if ($msgs) $msg = $this->lang->line('error_occurred');
		else{
			$filter = array("doc_type_id" => $p["doc_type_id"], "doc_number" => $p["doc_number"]);
			$person = $this->general->filter("person", $filter);
			if ($person){
				$this->general->update("person", $person[0]->id, $p);
				$person_id = $person[0]->id;
			}else{
				$p["registed_at"] = date('Y-m-d H:i:s', time());
				$person_id = $this->general->insert("person", $p);
			}
			
			if ($person_id){
				$d["person_id"] = $person_id;
				$doctors = $this->general->filter("doctor", $d, "id", "asc", 0, 1);
				if ($doctors) $doctor_id = $doctors[0]->id;
				else{
					$d["status_id"] = $this->general->filter("status", array("code" => "enabled"))[0]->id;
					$d["registed_at"] = date('Y-m-d H:i:s', time());
					$doctor_id = $this->general->insert("doctor", $d);
				}
				
				if ($doctor_id){
					unset($a["confirm"]);
					$a["person_id"] = $person_id;
					$a["password"] = password_hash($a["password"], PASSWORD_BCRYPT);
					$a["active"] = true;
					$a["registed_at"] = date('Y-m-d H:i:s', time());
					$account_id = $this->account->insert($a);
					if ($account_id){
						$role_id = $this->role->name("doctor")->id;
						$this->general->insert("account_role", array("role_id" => $role_id, "account_id" => $account_id));
						
						$status = true;
						$type = "success";
						$move_to = base_url()."doctor/detail/".$doctor_id;
						$msg = $this->lang->line('success_rdo');
					}else $msgs = $this->set_msg($msgs, "dn_result_msg", "error", "error_rac");
				}else $msgs = $this->set_msg($msgs, "dn_result_msg", "error", "error_rdd");
			}else $msgs = $this->set_msg($msgs, "dn_result_msg", "error", "error_rpd");
		}
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msgs" => $msgs, "msg" => $msg, "move_to" => $move_to));
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
	
	public function update_profession(){
		$status = false; $type = "error"; $msgs = array(); $msg = null;
		$data = $this->input->post();
		
		if (!$data["specialty_id"]) $msgs = $this->set_msg($msgs, "pr_specialty_msg", "error", "error_ssp");
		if (!$data["license"]) $msgs = $this->set_msg($msgs, "pr_license_msg", "error", "error_eli");
		
		if ($msgs) $msg = $this->lang->line('error_occurred');
		else{
			if ($this->general->update("doctor", $data["id"], $data)){
				$status = true;
				$type = "success"; 
				$msg = $this->lang->line('success_upr');
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
			if (strlen($data["password"]) < 6) $msgs = $this->set_msg($msgs, "du_password_msg", "error", "error_pal");
		}else $msgs = $this->set_msg($msgs, "du_password_msg", "error", "error_epa");
		if (strcmp($data["password"], $data["confirm"])) $msgs = $this->set_msg($msgs, "du_confirm_msg", "error", "error_pac");
		
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
	
	public function activation_control(){
		$id = $this->input->post("id");
		$active = $this->input->post("active");
		$status = false; $type = "error"; $msg = $this->lang->line('error_internal');
		
		$active = filter_var($active, FILTER_VALIDATE_BOOLEAN);
		if ($active) $code = "enabled"; else $code = "disabled";
		
		$data = array("id" => $id, "status_id" => $this->general->filter("status", array("code" => $code))[0]->id);
		if ($this->general->update("doctor", $data["id"], $data)){
			$status = true;
			$type = "success";
			if ($active) $msg = $this->lang->line('success_ado');
			else $msg = $this->lang->line('success_ddo');
		}
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msg" => $msg));
	}
}
