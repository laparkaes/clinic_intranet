<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Patient extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/Lima');
		$this->lang->load("patient", "spanish");
		$this->lang->load("system", "spanish");
		$this->load->model('general_model','general');
		$this->nav_menu = "patient";
		$this->nav_menus = $this->utility_lib->get_visible_nav_menus();
	}
	
	public function index(){
		if (!$this->session->userdata('logged_in')) redirect('/');
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
			"sex_ops" => $this->general->all("sex", "description", "asc"),
			"blood_type_ops" => $this->general->all("blood_type", "description", "asc"),
			"title" => $this->lang->line('patients'),
			"main" => "patient/list",
			"init_js" => "patient/list.js"
		];
		
		$this->load->view('layout', $data);
	}
	
	public function detail($id){
		if (!$this->session->userdata('logged_in')) redirect('/');
		if (!$this->utility_lib->check_access("patient", "detail")) redirect("/errors/no_permission");
		
		$person = $this->general->id("person", $id);
		$person->doc_type = $this->general->id("doc_type", $person->doc_type_id)->short;
		if ($person->birthday) $person->age = $this->my_func->age_calculator($person->birthday);
		else $person->age = null;
		if ($person->sex_id) $person->sex = $this->general->id("sex", $person->sex_id)->description;
		else $person->sex = null;
		if ($person->blood_type_id) $person->blood_type = $this->general->id("blood_type", $person->blood_type_id)->description;
		else $person->blood_type = null;
		
		$f_pt = ["patient_id" => $person->id];
		$appointments = $this->general->filter("appointment", $f_pt, null, null, "schedule_from", "desc");
		$surgeries = $this->general->filter("surgery", $f_pt, null, null, "schedule_from", "desc");
		
		$doctors_arr = [];
		$doctors = $this->general->all("doctor");
		foreach($doctors as $d){
			$d->name = $this->general->id("person", $d->person_id)->name;;
			$doctors_arr[$d->person_id] = $d;
		}
		usort($doctors, function($a, $b) {return strcmp(strtoupper($a->name), strtoupper($b->name));});
		
		$s_enabled = $this->general->status("enabled");
		$f_aux = ["status_id" => $s_enabled->id];
		$specialty_arr = [];
		$specialties = $this->general->all("specialty", "name", "asc");
		foreach($specialties as $item){
			$f_aux["specialty_id"] = $item->id;
			$item->dr_qty = $this->general->counter("doctor", $f_aux);
			$specialty_arr[$item->id] = $item->name;
		}
		
		$status_arr = [];
		$status = $this->general->all("status", "id", "asc");
		foreach($status as $item) $status_arr[$item->id] = $item;
		
		$currencies_arr = [];
		$currencies = $this->general->all("currency");
		foreach($currencies as $item) $currencies_arr[$item->id] = $item;
		
		$rooms_arr = [];
		$rooms = $this->general->all("surgery_room", "name", "asc");
		foreach($rooms as $item) $rooms_arr[$item->id] = $item->name;
		
		$duration_ops = [];
		array_push($duration_ops, ["value" => 30, "txt" => "30 ".$this->lang->line('w_minutes')]);
		array_push($duration_ops, ["value" => 60, "txt" => "1 ".$this->lang->line('w_hour')]);
		for($i = 2; $i <= 12; $i++) $duration_ops[] = ["value" => 60 * $i, "txt" => $i." ".$this->lang->line('w_hours')];
		/*
		$credit_ids = [];
		$credits = $this->general->filter("credit", ["person_id" => $person->id]);
		foreach($credits as $item) $credit_ids[] = $item->id;
		if (!$credit_ids) $credit_ids[] = -1;
		
		$f_in = [["field" => "credit_id", "values" => $credit_ids]];
		$credit_histories = $this->general->filter("credit_history", null, null, $f_in, "registed_at", "desc");
		*/
		$data = [
			"person" => $person,
			"appointments" => $appointments,
			"surgeries" => $surgeries,
			"duration_ops" => $duration_ops,
			"specialties" => $specialties,
			"doctors" => $doctors,
			"rooms" => $rooms,
			"rooms_arr" => $rooms_arr,
			"s_enabled" => $s_enabled,
			"doctors_arr" => $doctors_arr,
			"specialty_arr" => $specialty_arr,
			"status_arr" => $status_arr,
			"currencies" => $currencies,
			"currencies_arr" => $currencies_arr,
			"doc_types" => $this->general->all("doc_type", "id", "asc"),
			"sales" => $this->general->filter("sale", ["client_id" => $person->id]),
			"sex_ops" => $this->general->all("sex", "description", "asc"),
			"blood_type_ops" => $this->general->all("blood_type", "description", "asc"),
			"credits" => $credits,
			//"credit_histories" => $credit_histories,
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
					$msg = $this->lang->line('s_register');
				}else $msg = $this->lang->line('error_internal');
			}else $msg = $this->lang->line('error_occurred');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msgs" => $msgs, "msg" => $msg, "move_to" => $move_to]);
	}
	
	public function update_info(){
		$type = "error"; $msgs = []; $msg = null;
		if ($this->utility_lib->check_access("patient", "update")){
			$data = $this->input->post();
			
			$this->load->library('my_val');
			$msgs = $this->my_val->person($msgs, "pu_", $data);
			
			if (!$msgs){
				foreach($data as $i => $item) if (!$item) $data[$i] = null;
				$this->general->update("person", $data["id"], $data);
				$this->utility_lib->add_log("person_update", $data["name"]);
				
				$type = "success"; 
				$msg = $this->lang->line('s_update_info');
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
					$upload_dir = "uploaded/pacientes/".str_replace(" ", "_", $patient->name)."_".$patient->doc_number;
					if(!is_dir($upload_dir)){mkdir($upload_dir, 0777, true);}
					$upload_dir = $upload_dir."/";
					
					$this->load->library('upload');
					$config_upload = [
						'upload_path' => $upload_dir,
						'allowed_types' => '*',
						'max_size' => 0,
						'overwrite' => false,
						'file_name' => date("Ymd_His")."_".str_replace(" ", "_", $title)
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
						
						if ($this->general->insert("patient_file", $patient_file)){
							$this->utility_lib->add_log("file_upload", $patient->name." - ".$title);
							
							$type = "success";
							$msg = $this->lang->line('s_upload_file');
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
			if ($this->general->update("patient_file", $patient_file->id, ["active" => false])){
				$person = $this->general->id("person", $patient_file->patient_id);
				$this->utility_lib->add_log("file_delete", $person->name." - ".$patient_file->title);
				
				$type = "success";
				$msg = $this->lang->line('s_delete_file');
			}else $msg = $this->lang->line('error_internal');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg]);
	}
	
	public function add_credit(){
		$type = "error"; $msgs = []; $msg = null;
		
		if ($this->utility_lib->check_access("patient", "admin_credit")){
			$data = $this->input->post();
			
			$this->load->library('my_val');
			$msgs = $this->my_val->credit($msgs, "ac_", $data);
			
			if (!$msgs){
				$f = $data; unset($f["amount"]);
				
				//check if person has a credit account
				$credit_acc = $this->general->filter("credit", $f);
				if ($credit_acc) $credit_id = $credit_acc[0]->id;
				else $credit_id = $this->general->insert("credit", $f);
				
				$credit_history = [
					"credit_id" => $credit_id,
					"currency_id" => $data["currency_id"],
					"amount" => $data["amount"],
					"remark" => $this->lang->line('w_added_by')." ".$this->session->userdata('name'),
					"registed_at" => date('Y-m-d H:i:s', time()),
				];
				if ($this->general->insert("credit_history", $credit_history)){
					//update credit balance in credit table
					$credit_data = [
						"balance" => $this->general->sum("credit_history", "amount", ["credit_id" => $credit_id])->amount,
						"updated_at" => date('Y-m-d H:i:s', time()),
					];
					$this->general->update("credit", $credit_id, $credit_data);
					
					$type = "success";
					$msg = $this->lang->line('s_update_credit');
				}else $msg = $this->lang->line('error_internal');
			}else $msg = $this->lang->line('error_occurred');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msgs" => $msgs, "msg" => $msg]);
	}
	
	public function reverse_credit(){
		$type = "error"; $msg = null;
		
		if ($this->utility_lib->check_access("patient", "admin_credit")){
			$id = $this->input->post("id");
			$credit_history = (array)$this->general->id("credit_history", $id);
			if ($credit_history){
				unset($credit_history["id"]);
				$credit_history["amount"] = -$credit_history["amount"];
				
				if ($credit_history["amount"] > 0) $msg = "w_added_by"; else $msg = "w_discounted_by";
				$credit_history["remark"] = $this->lang->line($msg)." ".$this->session->userdata('name');
				
				$credit_history["registed_at"] = date('Y-m-d H:i:s', time());
				if ($this->general->insert("credit_history", $credit_history)){
					$this->general->update("credit_history", $id, ["is_reversed" => true]);
					
					//update credit balance in credit table
					$credit_id = $credit_history["credit_id"];
					$credit_data = [
						"balance" => $this->general->sum("credit_history", "amount", ["credit_id" => $credit_id])->amount,
						"updated_at" => date('Y-m-d H:i:s', time()),
					];
					$this->general->update("credit", $credit_id, $credit_data);
					
					$type = "success";
					$msg = $this->lang->line('s_update_credit');
				}else $msg = $this->lang->line('error_internal');
			}else $msg = $this->lang->line('error_internal_refresh');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg]);
	}
}
