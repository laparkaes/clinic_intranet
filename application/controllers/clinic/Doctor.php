<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Doctor extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/Lima');
		$this->lang->load("doctor", "spanish");
		$this->lang->load("system", "spanish");
		$this->load->model('general_model','general');
		$this->nav_menu = ["clinic", "doctor"];
		$this->nav_menus = $this->utility_lib->get_visible_nav_menus();
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
		
		$specialties_arr = [];
		$specialties = $this->general->all("specialty", "name", "asc");
		foreach($specialties as $item){
			$item->doctor_qty = $this->general->counter("doctor", ["specialty_id" => $item->id]);
			$specialties_arr[$item->id] = $item->name;
		}
		
		$status = [];
		$status_rec = $this->general->all("status");
		foreach($status_rec as $item){
			$item->text = $this->lang->line($item->code);
			$status[$item->id] = $item;
		}
		
		$data = [
			"paging" => $this->my_func->set_page($f_url["page"], $this->general->counter("doctor", $f_w, $f_l, $f_w_in)),
			"f_url" => $f_url,
			"doc_types" => $this->general->all("doc_type", "sunat_code", "asc"),
			"specialties_arr" => $specialties_arr,
			"specialties" => $specialties,
			"doctors" => $doctors,
			"status" => $status,
			"sex_ops" => $this->general->all("sex", "description", "asc"),
			"blood_type_ops" => $this->general->all("blood_type", "description", "asc"),
			"title" => $this->lang->line('doctors'),
			"main" => "clinic/doctor/list",
		];
		
		$this->load->view('layout', $data);
	}
	
	public function detail($id){
		if (!$this->session->userdata('logged_in')) redirect('/');
		if (!$this->utility_lib->check_access("doctor", "detail")) redirect("/errors/no_permission");
		
		$doctor = $this->general->id("doctor", $id);
		$person = $this->general->id("person", $doctor->person_id);
		$account = $this->general->filter("account", ["person_id" => $person->id], null, null, "registed_at", "desc");
		if ($account) $account = $account[0]; else $account = $this->general->structure("account");
		
		//set doctor data
		$doctor->person = $person;
		$doctor->specialty = $this->general->id("specialty", $doctor->specialty_id)->name;
		$doctor->status = $this->general->id("status", $doctor->status_id);
		
		//load other data
		$patient_ids = [];
		$appointments = $this->general->filter("appointment", ["doctor_id" => $person->id], null, null, "schedule_from", "desc");
		foreach($appointments as $item) $patient_ids[] = $item->patient_id;
		
		$surgeries = $this->general->filter("surgery", ["doctor_id" => $person->id], null, null, "schedule_from", "desc");
		foreach($surgeries as $item) $patient_ids[] = $item->patient_id;
		$patient_ids = array_unique($patient_ids);
		
		$patient_arr = [];
		$patients = $this->general->ids("person", $patient_ids);
		foreach($patients as $item) $patient_arr[$item->id] = $item->name;
		
		$status_arr = [];
		$status = $this->general->all("status", "id", "asc");
		foreach($status as $item) $status_arr[$item->id] = $item;
		
		$specialties_arr = [];
		$specialties = $this->general->all("specialty", "name", "asc");
		foreach($specialties as $item) $specialties_arr[$item->id] = $item->name;
		
		$rooms_arr = [];
		$rooms = $this->general->all("surgery_room", "name", "asc");
		foreach($rooms as $item) $rooms_arr[$item->id] = $item->name;
		
		$duration_ops = [];
		$duration_ops[] = ["value" => 30, "txt" => "30 ".$this->lang->line('w_minutes')];
		$duration_ops[] = ["value" => 60, "txt" => "1 ".$this->lang->line('w_hour')];
		for($i = 2; $i <= 12; $i++) $duration_ops[] = ["value" => 60 * $i, "txt" => $i." ".$this->lang->line('w_hours')];
		
		$data = [
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
			"sex_ops" => $this->general->all("sex", "description", "asc"),
			"blood_type_ops" => $this->general->all("blood_type", "description", "asc"),
			"title" => $this->lang->line('doctor'),
			"main" => "clinic/doctor/detail",
		];
		$this->load->view('layout', $data);
	}
	
	public function register(){
		$type = "error"; $msgs = []; $msg = null; $move_to = null;
		if ($this->utility_lib->check_access("doctor", "register")){
			$d = $this->input->post("doctor");
			$p = $this->input->post("personal");
			
			$this->load->library('my_val');
			$msgs = $this->my_val->person($msgs, "dn_", $p);
			$msgs = $this->my_val->doctor($msgs, "dn_", $d);
			
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
				
				/* doctor handle */
				$doctor = $this->general->filter("doctor", ["person_id" => $person->id]);
				if ($doctor){
					$doctor = $doctor[0];
					$d["updated_at"] = date('Y-m-d H:i:s', time());
					$this->general->update("doctor", $doctor->id, $d);
					$this->utility_lib->add_log("doctor_update", $person->name);	
				}else{
					$d["person_id"] = $person->id;
					$d["status_id"] = $this->general->status("enabled")->id;
					$d["updated_at"] = $d["registed_at"] = date('Y-m-d H:i:s', time());
					$doctor_id = $this->general->insert("doctor", $d);
					$doctor = $this->general->id("doctor", $doctor_id);
					$this->utility_lib->add_log("doctor_register", $person->name);
				}
				
				/* account register if email entered */
				if ($p["email"]){
					if (!$this->general->filter("account", ["email" => $p["email"], "is_valid" => true])){
						$a = [
							"role_id" => $this->general->filter("role", ["name" => "doctor"])[0]->id,
							"person_id" => $person->id,
							"email" => $p["email"],
							"password" => password_hash($p["doc_number"], PASSWORD_BCRYPT),
							"registed_at" => date('Y-m-d H:i:s', time()),
						];
						$this->general->insert("account", $a);
					}
				}
				$this->utility_lib->add_log("doctor_register", $person->name);
					
				$type = "success";
				$move_to = base_url()."clinic/doctor/detail/".$doctor->id;
				$msg = $this->lang->line('s_register_doctor');
			}else $msg = $this->lang->line('error_occurred');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msgs" => $msgs, "msg" => $msg, "move_to" => $move_to]);
	}
	
	public function update_info(){
		$type = "error"; $msgs = []; $msg = null;
		if ($this->utility_lib->check_access("doctor", "update")){
			$p = $this->input->post("p");
			$d = $this->input->post("d");
			
			$this->load->library('my_val');
			$msgs = $this->my_val->person($msgs, "du_", $p);
			$msgs = $this->my_val->doctor($msgs, "du_", $d, false);
			
			if (!$msgs){
				foreach($d as $i => $item) if (!$item) $d[$i] = null;
				$this->general->update("doctor", $d["id"], $d);
				
				foreach($p as $i => $item) if (!$item) $p[$i] = null;
				$this->general->update("person", $p["id"], $p);
				
				if ($p["email"]){
					if (!$this->general->filter("account", ["email" => $p["email"], "is_valid" => true])){
						$r_doctor_id = $this->general->filter("role", ["name" => "doctor"])[0]->id;
						$this->general->update_f("account", ["person_id" => $p["id"], "role_id" => $r_doctor_id], ["is_valid" => false]);
						
						$a = [
							"role_id" => $r_doctor_id,
							"person_id" => $p["id"],
							"email" => $p["email"],
							"password" => password_hash($p["doc_number"], PASSWORD_BCRYPT),
							"registed_at" => date('Y-m-d H:i:s', time()),
						];
						$this->general->insert("account", $a);
					}
				}
				$this->utility_lib->add_log("doctor_update", $p["name"]);
				
				$type = "success"; 
				$msg = $this->lang->line('s_update_doctor');
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
			if ($active) $code = "enabled";
			else{
				$code = "disabled";
				$f = [
					"doctor_id" => $this->general->id("doctor", $id)->person_id,
					"schedule_from >=" => date('Y-m-d H:i:s', time()),
					"status_id" => $this->general->status("confirmed")->id
				];
				
				if ($this->general->filter("appointment", $f)) $msg = $this->lang->line('e_app_confirmed');
				elseif ($this->general->filter("surgery", $f)) $msg = $this->lang->line('e_sur_confirmed');
			}
			
			if (!$msg){
				$data = ["id" => $id, "status_id" => $this->general->status($code)->id];
				if ($this->general->update("doctor", $data["id"], $data)){
					$doctor = $this->general->id("doctor", $id);
					$person = $this->general->id("person", $doctor->person_id);
					$this->utility_lib->add_log("doctor_".$code, $person->name);
					
					$type = "success";
					if ($active) $msg = $this->lang->line('s_enable_doctor');
					else $msg = $this->lang->line('s_disable_doctor');
				}else $msg = $this->lang->line('error_internal');
			}
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg]);
	}
}
