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
		$this->nav_menu = "patient";
	}
	
	public function index(){
		if (!$this->session->userdata('logged_in')) redirect(base_url());
		if (!$this->utility_lib->check_access("patient", "index")) redirect("/errors/no_permission");
		
		$f_url = [
			"page" => $this->input->get("page"),
			"keyword" => $this->input->get("keyword"),
		];
		if (!$f_url["page"]) $f_url["page"] = 1;
		
		$f_w = $f_l = $f_w_in = [];
		if ($f_url["keyword"]) $f_l["doc_number"] = $f_l["name"] = $f_l["email"] = $f_l["tel"] = $f_url["keyword"];
		
		$doc_types_arr = [];
		$doc_types = $this->general->all("doc_type", "id", "asc");
		foreach($doc_types as $item) $doc_types_arr[$item->id] = $item->short;
		
		$data = [
			"paging" => $this->my_func->set_page($f_url["page"], $this->general->counter("person", $f_w, $f_l, $f_w_in)),
			"f_url" => $f_url,
			"patients" => $this->general->filter("person", $f_w, $f_l, $f_w_in, "registed_at", "desc", 25, 25*($f_url["page"]-1)),
			"doc_types" => $doc_types,
			"doc_types_arr" => $doc_types_arr,
			"sex_ops" => $this->general->filter("sl_option", ["code" => "sex"]),
			"blood_type_ops" => $this->general->filter("sl_option", ["code" => "blood_type"]),
			"title" => $this->lang->line('patients'),
			"main" => "patient/list",
			"init_js" => "patient/list.js"
		];
		
		$this->load->view('layout', $data);
	}
	
	public function detail($id){
		if (!$this->session->userdata('logged_in')) redirect(base_url());
		if (!$this->utility_lib->check_access("patient", "detail")) redirect("/errors/no_permission");
		
		$person = $this->general->id("person", $id);
		$person->doc_type = $this->general->id("doc_type", $person->doc_type_id)->short;
		if ($person->birthday) $person->age = $this->utility_lib->age_calculator($person->birthday);
		else $person->age = "";
		if ($person->sex_id) $person->sex = $this->general->id("sl_option", $person->sex_id)->description;
		else $person->sex = "";
		if ($person->blood_type_id) $person->blood_type = $this->general->id("sl_option", $person->blood_type_id)->description;
		else $person->blood_type = "";
		
		$f_pt = ["patient_id" => $person->id];
		$appointments = $this->general->filter("appointment", $f_pt, null, null, "schedule_from", "desc");
		$surgeries = $this->general->filter("surgery", $f_pt, null, null, "schedule_from", "desc");
		
		$doctors_arr = [];
		$doctors = $this->general->all("doctor");
		foreach($doctors as $d){
			$d->name = $this->general->id("person", $d->person_id)->name;
			$doctors_arr[$d->person_id] = $d;
		}
		usort($doctors, function($a, $b) {return strcmp(strtoupper($a->name), strtoupper($b->name));});
		
		$specialty_arr = [];
		$specialties = $this->general->all("specialty", "name", "asc");
		foreach($specialties as $item) $specialty_arr[$item->id] = $item->name;
		
		$status_arr = [];
		$status = $this->status->all();
		foreach($status as $item) $status_arr[$item->id] = $item;
		
		$currencies_arr = [];
		$currencies = $this->general->all("currency");
		foreach($currencies as $item) $currencies_arr[$item->id] = $item;
		
		$rooms_arr = [];
		$rooms = $this->general->all("surgery_room", "name", "asc");
		foreach($rooms as $item) $rooms_arr[$item->id] = $item->name;
		
		$duration_ops = [];
		array_push($duration_ops, ["value" => 30, "txt" => "30 ".$this->lang->line('op_minutes')]);
		array_push($duration_ops, ["value" => 60, "txt" => "1 ".$this->lang->line('op_hour')]);
		for($i = 2; $i <= 12; $i++) $duration_ops[] = ["value" => 60 * $i, "txt" => $i." ".$this->lang->line('op_hours')];
		
		$data = [
			"person" => $person,
			"appointments" => $appointments,
			"surgeries" => $surgeries,
			"duration_ops" => $duration_ops,
			"specialties" => $specialties,
			"doctors" => $doctors,
			"rooms" => $rooms,
			"rooms_arr" => $rooms_arr,
			"doctors_arr" => $doctors_arr,
			"specialty_arr" => $specialty_arr,
			"status_arr" => $status_arr,
			"currencies_arr" => $currencies_arr,
			"sales" => $this->general->filter("sale", ["client_id" => $person->id]),
			"sex_ops" => $this->general->filter("sl_option", ["code" => "sex"]),
			"blood_type_ops" => $this->general->filter("sl_option", ["code" => "blood_type"]),
			"patient_files" => $this->general->filter("patient_file", ["patient_id" => $person->id, "active" => true], null, null, "registed_at", "desc"),
			"title" => $this->lang->line('patient'),
			"main" => "patient/detail",
			"init_js" => "patient/detail.js"
		];
		
		$this->load->view('layout', $data);
	}
	
	public function register(){
		$type = "error"; $msgs = []; $msg = null; $move_to = null;
		if ($this->utility_lib->check_access("patient", "register")){
			$data = $this->input->post();
			
			$this->load->library('my_val');
			$msgs = $this->my_val->person($msgs, "pn_", $data);
			
			if (!$msgs){
				//person data creation if does'nt exist
				$people = $this->general->filter("person", ["doc_type_id" => $data["doc_type_id"], "doc_number" => $data["doc_number"]], null, null, "registed_at", "desc");
				if ($people){
					$person = $people[0];
					$this->general->update("person", $person->id, $data);
					$person_id = $person->id;
					$this->utility_lib->add_log("person_update", $person->name);
				}else{
					$data["registed_at"] = date('Y-m-d H:i:s', time());
					$person_id = $this->general->insert("person", $data);
					$this->utility_lib->add_log("person_register", $data["name"]);
				}
				
				if ($person_id){
					$type = "success";
					$move_to = base_url()."patient/detail/".$person_id;
					$msg = $this->lang->line('success_rpa');
				}else $msg = $this->lang->line('error_internal');
			}else $msg = $this->lang->line('error_occurred');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msgs" => $msgs, "msg" => $msg, "move_to" => $move_to]);
	}
	
	public function update(){
		$type = "error"; $msgs = []; $msg = null;
		if ($this->utility_lib->check_access("patient", "update")){
			$data = $this->input->post();
			
			$this->load->library('my_val');
			$msgs = $this->my_val->person($msgs, "pu_", $data);
			
			if (!$msgs){
				if (!$data["tel"]) $data["tel"] = null;
				if (!$data["email"]) $data["email"] = null;
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
			}else $msg = $this->lang->line('error_occurred');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg, "msgs" => $msgs]);
	}
	
	public function upload_file(){
		$type = "error"; $msgs = []; $msg = null;
		if ($this->utility_lib->check_access("patient", "update")){
			$title = $this->input->post("title");
			
			$this->load->library('my_val');
			$msgs = $this->my_val->file_upload($msgs, "pf_", $title, $_FILES["upload_file"]["name"]);
			
			if (!$msgs){
				$patient = $this->general->id("person", $this->input->post("patient_id"));
				if ($patient){
					$upload_dir = "uploaded/patient_files/".$patient->doc_type_id."_".$patient->doc_number;
					if(!is_dir($upload_dir)){mkdir($upload_dir, 0777, true);}
					$upload_dir = $upload_dir."/";
					
					$this->load->library('upload');
					$config_upload = [
						'upload_path' => $upload_dir,
						'allowed_types' => '*',
						'max_size' => 0,
						'overwrite' => false,
						'file_name' => date("YmdHis")
					];
					
					$this->upload->initialize($config_upload);
					if ($this->upload->do_upload("upload_file")){
						$result = $this->upload->data();
						$patient_file = [
							"patient_id" => $patient->id,
							"title" => $title,
							"filename" => $result["file_name"],
							"active" => true,
							"registed_at" => date('Y-m-d H:i:s', time())
						];
						
						if ($this->patient_file->insert($patient_file)){
							$this->utility_lib->add_log("file_upload", $patient->name." - ".$title);
							
							$type = "success";
							$msg = $this->lang->line('success_ufi');
						}else $msgs = $this->my_val->set_msg($msgs, "pf_result_msg", "error", "error_internal");
					}else $msgs[] = ["dom_id" => "pf_result_msg", "type" => "error", "msg" => $this->upload->display_errors("<span>","</span>")];
				}else $msgs = $this->my_val->set_msg($msgs, "pf_result_msg", "error", "error_internal_refresh");
			}else $msg = $this->lang->line('error_occurred');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msgs" => $msgs, "msg" => $msg]);
	}
	
	public function delete_file(){
		$type = "error"; $msg = null;
		
		if ($this->utility_lib->check_access("patient", "update")){		
			$patient_file = $this->general->id("patient_file", $this->input->post("id"));
			
			//change "active" field of DB without removing uploaded file
			if ($this->patient_file->update($patient_file->id, ["active" => false])){
				$person = $this->general->id("person", $patient_file->patient_id);
				$this->utility_lib->add_log("file_delete", $person->name." - ".$patient_file->title);
				
				$type = "success";
				$msg = $this->lang->line('success_dfi');
			}else $msg = $this->lang->line('error_internal');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg]);
	}
}
