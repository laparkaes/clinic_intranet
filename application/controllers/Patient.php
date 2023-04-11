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
		
		$specialty_arr = array();
		$specialties = $this->general->all("specialty", "name", "asc");
		foreach($specialties as $item) $specialty_arr[$item->id] = $item->name;
		
		$appointments = $this->general->filter("appointment", array("patient_id" => $person->id), "schedule_from", "desc");
		$surgeries = $this->general->filter("surgery", array("patient_id" => $person->id), "schedule_from", "desc");
		
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
		
		$rooms_arr = array();
		$rooms = $this->general->all("surgery_room", "name", "asc");
		foreach($rooms as $item) $rooms_arr[$item->id] = $item->name;
		
		$duration_ops = array();
		array_push($duration_ops, ["value" => 30, "txt" => "30 ".$this->lang->line('op_minutes')]);
		array_push($duration_ops, ["value" => 60, "txt" => "1 ".$this->lang->line('op_hour')]);
		for($i = 2; $i <= 12; $i++) array_push($duration_ops, ["value" => 60 * $i, "txt" => $i." ".$this->lang->line('op_hours')]);
		
		$data = array(
			"person" => $person,
			"appointments" => $appointments,
			"surgeries" => $surgeries,
			"rooms" => $rooms,
			"rooms_arr" => $rooms_arr,
			"duration_ops" => $duration_ops,
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
		$data = $this->input->post();
		$status = false; $type = "error"; $msgs = array(); $msg = $this->lang->line('error_occurred'); $move_to = null;
		
		//personal data validation
		if (!$data["name"]) $msgs = $this->set_msg($msgs, "pn_name_msg", "error", "error_ena");
		if (!$data["tel"]) $msgs = $this->set_msg($msgs, "pn_tel_msg", "error", "error_ete");
		if (!$data["doc_type_id"]) $msgs = $this->set_msg($msgs, "pn_doc_msg", "error", "error_sdt");
		if (!$data["doc_number"]) $msgs = $this->set_msg($msgs, "pn_doc_msg", "error", "error_edn");
		if ($this->general->filter("person", array("doc_type_id" => $data["doc_type_id"], "doc_number" => $data["doc_number"])))
			 $msgs = $this->set_msg($msgs, "pn_doc_msg", "error", "error_pex");
		
		//email is optional
		if ($data["email"]){
			if (filter_var($data["email"], FILTER_VALIDATE_EMAIL)){
				if ($this->account->email($data["email"])) $msgs = $this->set_msg($msgs, "pn_email_msg", "error", "error_usr");
			}else $msgs = $this->set_msg($msgs, "pn_email_msg", "error", "error_usf");
		}
		
		if (!$msgs){
			//person data creation if does'nt exist
			$filter = array("doc_type_id" => $data["doc_type_id"], "doc_number" => $data["doc_number"]);
			$people = $this->general->filter("person", $filter, "id", "asc", 0, 1);
			if ($people){
				$person = $people[0];
				$this->general->update("person", $person->id, $data);
				$person_id = $person->id;
			}else{
				$data["registed_at"] = date('Y-m-d H:i:s', time());
				$person_id = $this->general->insert("person", $data);
			}
			
			if ($person_id){
				$status = true;
				$type = "success";
				$move_to = base_url()."patient/detail/".$person_id;
				$msg = $this->lang->line('success_rpa');
			}else $msgs = $this->set_msg($msgs, "dn_result_msg", "error", "error_rpd");
		}
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msgs" => $msgs, "msg" => $msg, "move_to" => $move_to));
	}
	
	public function update(){
		$status = false; $type = "error"; $msgs = array(); $msg = null;
		$data = $this->input->post();
		
		if ($data["email"]){
			if (filter_var($data["email"], FILTER_VALIDATE_EMAIL)){
				if ($this->account->email($data["email"])) $msgs = $this->set_msg($msgs, "pu_email_msg", "error", "error_usr");
			}else $msgs = $this->set_msg($msgs, "pu_email_msg", "error", "error_usf");
		}else $data["email"] = null;
		
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
