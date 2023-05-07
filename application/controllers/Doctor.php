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
		$this->nav_menu = "doctor";
	}
	
	public function index(){
		if (!$this->session->userdata('logged_in')) redirect("/");
		if (!$this->utility_lib->check_access("doctor", "index")) redirect("/errors/no_permission");
		
		$f_url = [
			"page" => $this->input->get("page"),
			"specialty" => $this->input->get("specialty"),
			"name" => $this->input->get("name"),
		];
		if (!$f_url["page"]) $f_url["page"] = 1;
		
		$f_w = $f_l = $f_w_in = [];
		if ($f_url["specialty"]) $f_w["specialty_id"] = $f_url["specialty"];
		if ($f_url["name"]){
			$aux = [];
			$person_ids = $this->general->only("person", "id", null, ["name" => $f_url["name"]], null);
			if ($person_ids){
				foreach($person_ids as $item) $aux[] = $item->id;
				$f_w_in[] = ["field" => "person_id", "values" => $aux];
			}
		}
		if ((!$f_w_in) and $f_url["name"]) $f_w_in[] = ["field" => "person_id", "values" => [-1]];
		
		$doctors = $this->general->filter("doctor", $f_w, $f_l, $f_w_in, "registed_at", "desc", 25, 25*($f_url["page"]-1));
		foreach($doctors as $item) $item->person = $this->general->id("person", $item->person_id);
		
		$specialties_arr = array();
		$specialties = $this->specialty->all();
		foreach($specialties as $item) $specialties_arr[$item->id] = $item->name;
		
		$status = array();
		$status_rec = $this->general->all("status");
		foreach($status_rec as $item){
			$item->text = $this->lang->line($item->code);
			$status[$item->id] = $item;
		}
		
		$data = array(
			"paging" => $this->my_func->set_page($f_url["page"], $this->general->counter("doctor", $f_w, $f_l, $f_w_in)),
			"f_url" => $f_url,
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
		if (!$this->utility_lib->check_access("doctor", "detail")) redirect("/errors/no_permission");
		
		$doctor = $this->general->id("doctor", $id);
		$person = $this->general->id("person", $doctor->person_id);
		$account = $this->general->filter("account", ["person_id" => $person->id], null, null, "registed_at", "desc");
		if ($account) $account = $account[0];
		else $account = $this->general->structure("account");
		
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
		if ($person->birthday){
			$person->age = $this->utility_lib->age_calculator($person->birthday);
			$person->birthday = date("Y-m-d", strtotime($person->birthday));
		}
		else{
			$person->age = null;
			$person->birthday = null;
		}
		if ($person->sex_id) $person->sex = $this->general->id("sl_option", $person->sex_id)->description;
		else $person->sex = null;
		if ($person->blood_type_id) $person->blood_type = $this->general->id("sl_option", $person->blood_type_id)->description;
		else $person->blood_type = null;
		
		//load other data
		$patient_ids = array();
		$appointments = $this->general->filter("appointment", ["doctor_id" => $person->id], null, null, "schedule_from", "desc");
		foreach($appointments as $item) array_push($patient_ids, $item->patient_id);
		
		$surgeries = $this->general->filter("surgery", ["doctor_id" => $person->id], null, null, "schedule_from", "desc");
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
		
		$rooms_arr = array();
		$rooms = $this->general->all("surgery_room", "name", "asc");
		foreach($rooms as $item) $rooms_arr[$item->id] = $item->name;
		
		$duration_ops = array();
		array_push($duration_ops, ["value" => 30, "txt" => "30 ".$this->lang->line('op_minutes')]);
		array_push($duration_ops, ["value" => 60, "txt" => "1 ".$this->lang->line('op_hour')]);
		for($i = 2; $i <= 12; $i++) array_push($duration_ops, ["value" => 60 * $i, "txt" => $i." ".$this->lang->line('op_hours')]);
		
		$data = array(
			"doctor" => $doctor,
			"person" => $person,
			"account" => $account,
			"patients" => $patients,
			"appointments" => $appointments,
			"surgeries" => $surgeries,
			"rooms" => $rooms,
			"rooms_arr" => $rooms_arr,
			"duration_ops" => $duration_ops,
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
		$type = "error"; $msgs = []; $msg = null; $move_to = null;
		if ($this->utility_lib->check_access("doctor", "register")){
			$a = $this->input->post("account");
			$d = $this->input->post("doctor");
			$p = $this->input->post("personal");
			$p["email"] = $a["email"];
			
			$this->load->library('my_val');
			$msgs = $this->my_val->person($msgs, "dn_", $p);
			$msgs = $this->my_val->doctor($msgs, "dn_", $d);
			$msgs = $this->my_val->account($msgs, "dn_", $a);
			
			if (!$msgs){
				/* person handle */
				$person = $this->general->filter("person", ["doc_type_id" => $p["doc_type_id"], "doc_number" => $p["doc_number"]]);
				if ($person){
					$person = $person[0];
					$p["updated_at"] = date('Y-m-d H:i:s', time());
					$this->general->update("person", $person->id, $p);
					$this->utility_lib->add_log("person_update", $person->name);
				}else{
					$p["updated_at"] = $p["registed_at"] = date('Y-m-d H:i:s', time());
					$person_id = $this->general->insert("person", $p);
					$person = $this->general->id("person", $person_id);
					$this->utility_lib->add_log("person_register", $p["name"]);
				}
				
				if ($person){
					/* doctor handle */
					$doctor = $this->general->filter("doctor", ["person_id" => $person->id]);
					if ($doctor){
						$doctor = $doctor[0];
						$d["updated_at"] = date('Y-m-d H:i:s', time());
						$this->general->update("doctor", $doctor->id, $d);
						$this->utility_lib->add_log("doctor_update", $person->name);	
					}else{
						$d["person_id"] = $person->id;
						$d["status_id"] = $this->general->filter("status", array("code" => "enabled"))[0]->id;
						$d["updated_at"] = $d["registed_at"] = date('Y-m-d H:i:s', time());
						$doctor_id = $this->general->insert("doctor", $d);
						$doctor = $this->general->id("doctor", $doctor_id);
						$this->utility_lib->add_log("doctor_register", $person->name);
					}
					
					if ($doctor){
						/* account handle */
						unset($a["confirm"]);
						$a["role_id"] = $this->role->name("doctor")->id;
						$a["person_id"] = $person->id;
						$a["password"] = password_hash($a["password"], PASSWORD_BCRYPT);
						$a["active"] = true;
						$a["registed_at"] = date('Y-m-d H:i:s', time());
						if ($this->account->insert($a)){
							$this->utility_lib->add_log("doctor_register", $person->name);
							
							$type = "success";
							$move_to = base_url()."doctor/detail/".$doctor->id;
							$msg = $this->lang->line('success_rdo');
						}else $msgs = $this->my_val->set_msg($msgs, "dn_result_msg", "error", "error_rac");
					}else $msgs = $this->my_val->set_msg($msgs, "dn_result_msg", "error", "error_rdd");
				}else $msgs = $this->my_val->set_msg($msgs, "dn_result_msg", "error", "error_rpd");
			}else $msg = $this->lang->line('error_occurred');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msgs" => $msgs, "msg" => $msg, "move_to" => $move_to]);
	}
	
	public function update_personal_data(){
		$type = "error"; $msgs = []; $msg = null;
		if ($this->utility_lib->check_access("doctor", "update")){
			$data = $this->input->post();
			
			//all datas are optional => no validation
			if (!$data["tel"]) $data["tel"] = null;
			if (!$data["address"]) $data["address"] = null;
			if (!$data["birthday"]) $data["birthday"] = null;
			if (!$data["sex_id"]) $data["sex_id"] = null;
			if (!$data["blood_type_id"]) $data["blood_type_id"] = null;
			if ($this->general->update("person", $data["id"], $data)){
				$person = $this->general->id("person", $data["id"]);
				$this->utility_lib->add_log("person_update", $person->name);
				
				$type = "success"; 
				$msg = $this->lang->line('success_upd');
			}else $msg = $this->lang->line('error_internal');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg, "msgs" => $msgs]);
	}
	
	public function update_profession(){
		$type = "error"; $msgs = []; $msg = null;
		if ($this->utility_lib->check_access("doctor", "update")){
			$data = $this->input->post();
			
			$this->load->library('my_val');
			$msgs = $this->my_val->doctor($msgs, "pr_", $data);
			
			if (!$msgs){
				if ($this->general->update("doctor", $data["id"], $data)){
					$doctor = $this->general->id("doctor", $data["id"]);
					$person = $this->general->id("person", $doctor->person_id);
					$this->utility_lib->add_log("doctor_update", $person->name);
					
					$type = "success"; 
					$msg = $this->lang->line('success_upr');
				}else $msg = $this->lang->line('error_internal');
			}else $msg = $this->lang->line('error_occurred');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg, "msgs" => $msgs]);
	}
	
	public function update_account_email(){
		$type = "error"; $msgs = array(); $msg = null;
		if ($this->utility_lib->check_access("doctor", "update")){
			$data = $this->input->post();
			
			$this->load->library('my_val');
			$msgs = $this->my_val->email($msgs, "ae_", $data);
			
			if (!$msgs){
				if ($this->general->update("account", $data["id"], $data)){
					$account = $this->general->id("account", $data["id"]);
					$this->utility_lib->add_log("account_update", $account->email);
					
					$type = "success"; 
					$msg = $this->lang->line('success_uae');
				}else $msg = $this->lang->line('error_internal');
			}else $msg = $this->lang->line('error_occurred');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg, "msgs" => $msgs]);
	}
	
	public function activation_control(){
		$type = "error"; $msg = null;
		if ($this->utility_lib->check_access("doctor", "update")){
			$id = $this->input->post("id");
			$active = filter_var($this->input->post("active"), FILTER_VALIDATE_BOOLEAN);
			if ($active) $code = "enabled"; else $code = "disabled";
			
			$data = array("id" => $id, "status_id" => $this->general->filter("status", array("code" => $code))[0]->id);
			if ($this->general->update("doctor", $data["id"], $data)){
				$doctor = $this->general->id("doctor", $id);
				$person = $this->general->id("person", $doctor->person_id);
				$this->utility_lib->add_log("doctor_".$code, $person->name);
				
				$type = "success";
				if ($active) $msg = $this->lang->line('success_ado');
				else $msg = $this->lang->line('success_ddo');
			}else $msg = $this->lang->line('error_internal');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg]);
	}
	
	/* function generate($num){
		$this->load->library('my_func');
		
		$now = date('Y-m-d H:i:s', time());
		$status_id = $this->general->filter("status", array("code" => "enabled"))[0]->id;
		$specialties = $this->specialty->all();
		$char_alf = "ABCDEFGHIJKLMN";
		$char_num = "1234567890";
		
		$doc_arr = [];
		$person_ids = $this->general->only("person", "id", null, null, null, $num, 40);
		foreach($person_ids as $item){
			//print_r($item);
			$doc_arr[] = [
				"status_id" => $status_id,
				"person_id" => $item->id,
				"specialty_id" => $specialties[array_rand($specialties)]->id,
				"license" => $this->my_func->randomString($char_alf, 3).$this->my_func->randomString($char_num, 7),
				"updated_at" => $now,
				"registed_at" => $now,
			];
		}
		
		$this->general->insert_multi("doctor", $doc_arr);
	} */
}
