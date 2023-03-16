<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax_f extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/Lima');
		$this->lang->load("system", "spanish");
		//$this->load->model('sl_option_model','sl_option');
		//
		//$this->load->model('status_model','status');
		$this->load->model('appointment_model','appointment');
		$this->load->model('surgery_model','surgery');
		$this->load->model('status_model','status');
		$this->load->model('general_model','general');
	}
	
	public function search_person(){
		$status = false; $type = "error"; $msg = null; $person = null;
		$data = $this->input->post();
		
		if ($data["doc_number"]){
			$person = $this->general->filter("person", $data);
			if ($person){
				$person = $person[0];
				$person = array("id" => $person->id, "name" => $person->name, "tel" => $person->tel);
				$type = "success";
				$status = true;
			}else{
				$name = null;
				switch($this->general->filter("doc_type", array("id" => $data["doc_type_id"]))[0]->description){
					case "DNI - Documento Nacional de Identidad":
						$ud = $this->utility_lib->utildatos_dni($data["doc_number"]);
						if ($ud->status) $name = $ud->data->nombres." ".$ud->data->apellidoPaterno." ".$ud->data->apellidoMaterno;
						break;
					case "RUC - Registro Unico de Contributentes":
						$ud = $this->utility_lib->utildatos_ruc($data["doc_number"]);
						if ($ud->status) $name = $ud->data->razon_social;
						break;
				}
				
				if ($name){
					$person = array("id" => null, "name" => $name, "tel" => null);
					$type = "success";
					$status = true;
				}else $msg = $this->lang->line('error_insert_manually');
			}
		}else $msg = $this->lang->line('error_doc_number');
		
		if ($status) $msg = $this->lang->line('success_data_loaded');
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msg" => $msg, "person" => $person));
	}
	
	public function search_company(){
		$status = false; $type = "error"; $msg = null; $company = null;
		$data = $this->input->post();
		
		if ($data["ruc"]){
			$company = $this->general->filter("provider", $data);
			if ($company){
				$company = $company[0];
				
				$status = true;
				$type = "success";
				$msg = $this->lang->line('success_data_loaded');
			}else{
				$ud = $this->utility_lib->utildatos_ruc($data["ruc"]);
				$company = $this->general->structure("provider");
				if ($ud->status){
					$company->ruc = $ud->data->ruc;
					$company->company = $ud->data->razon_social;
					$company->address = $ud->data->direccion;
					
					$status = true;
					$type = "success";
					$msg = $this->lang->line('success_data_loaded');
				}else $msg = $this->lang->line('error_insert_manually');
			}
		}else $msg = $this->lang->line('error_ruc');
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msg" => $msg, "company" => $company));
	}
	
	public function set_appointment(){
		$dates = array(date("Y-m-d"), date("Y-m-d", strtotime("+1 day")));
		
		$filter = array("schedule_from <=" => date("Y-m-d", strtotime("-1 day"))." 23:59:59");
		$appointments = $this->general->filter("appointment", $filter, "schedule_from", "asc");
		foreach($appointments as $item){
			$nd = $dates[array_rand($dates)];
			$sf = $nd." ".date("h:i:s", strtotime($item->schedule_from));
			$st = $nd." ".date('h:i:s', strtotime('+14 minutes', strtotime($sf)));
			$this->general->update("appointment", $item->id, array("schedule_from" => $sf, "schedule_to" => $st));
		}
		
		echo "fin";
	}
	
	public function load_doctor_schedule(){
		$status = false; $data = array();
		$doctor_id = $this->input->post("doctor_id");
		$date = $this->input->post("date");
		
		if (!$doctor_id) array_push($data, "Elija un medico.");
		if (!$date) array_push($data, "Elija una fecha.");
		
		if (!$data){
			$status_ids = array($this->status->code("reserved")->id, $this->status->code("confirmed")->id);
			$appointments = $this->appointment->doctor($doctor_id, $date, $status_ids);
			if ($appointments) foreach($appointments as $item)
				array_push($data, "<span>".date("h:i A", strtotime($item->schedule_from))."</span><span>Consulta</span>");
			
			$surgeries = $this->surgery->doctor($doctor_id, $date, $status_ids);
			if ($surgeries) foreach($surgeries as $item)
				array_push($data, "<span>".date("h:i A", strtotime($item->schedule_from))."</span><span>Cirugia</span>");
			
			if (!$data) array_push($data, "Disponibilidad Completa.");
			
			$status = true;
			$type = "success";
		}
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "data" => $data));
	}
	
	public function load_schedule(){
		$res = array();
		
		$today = date("Y-m-d");
		$tomorrow = date("Y-m-d", strtotime("+1 day"));
		
		$appointments_arr = array();
		$appointments_arr[$today]["title"] = $this->lang->line('txt_today').", ".$today;
		$appointments_arr[$today]["data"] = array();
		$appointments_arr[$tomorrow]["title"] = $this->lang->line('txt_tomorrow').", ".$tomorrow;
		$appointments_arr[$tomorrow]["data"] = array();
		
		$surgeries_arr = array();
		$surgeries_arr[$today]["title"] = $this->lang->line('txt_today').", ".$today;
		$surgeries_arr[$today]["data"] = array();
		$surgeries_arr[$tomorrow]["title"] = $this->lang->line('txt_tomorrow').", ".$tomorrow;
		$surgeries_arr[$tomorrow]["data"] = array();
		
		$res["appointments"] = $appointments_arr;
		$res["surgeries"] = $surgeries_arr;
		
		$filter = array(
			"schedule_from >=" => $today." 00:00:00",
			"schedule_from <=" => $tomorrow." 23:59:59"
		);
		if (!strcmp($this->session->userdata('role')->name, "doctor")) $filter["doctor_id"] = $this->session->userdata('aid');
		
		$appointments = $this->general->filter("appointment", $filter, "schedule_from", "asc");
		foreach($appointments as $item){
			$data = array(
				"id" => $item->id,
				"color" => $this->general->id("status", $item->status_id)->color,
				"schedule" => date("h:i A", strtotime($item->schedule_from)),
				"doctor" => $this->general->id("person", $item->doctor_id)->name,
				"patient" => $this->general->id("person", $item->patient_id)->name,
				"speciality" => $this->general->id("specialty", $item->speciality_id)->name
			);
			array_push($appointments_arr[date("Y-m-d", strtotime($item->schedule_from))]["data"], $data);
		}
		
		/*
		$surgeries = $this->general->filter("surgery", $filter, "schedule_from", "asc");
		foreach($surgeries as $item){
			$data = array(
				"id" => $item->id,
				"color" => $this->general->id("status", $item->status_id)->color,
				"schedule" => date("h:i A", strtotime($item->schedule_from)),
				"doctor" => $this->general->id("person", $item->doctor_id)->name,
				"patient" => $this->general->id("person", $item->patient_id)->name,
				"speciality" => $this->general->id("specialty", $item->speciality_id)->name
			);
			array_push($surgeries_arr[date("Y-m-d", strtotime($item->schedule_from))]["data"], $data);
		}
		*/
		$surgeries = array();
		
		header('Content-Type: application/json');
		echo json_encode(array("appointments" => $appointments_arr, "surgeries" => $surgeries_arr));
	}
}