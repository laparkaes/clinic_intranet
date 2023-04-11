<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax_f extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/Lima');
		$this->lang->load("system", "spanish");
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
				$person = array("id" => $person->id, "name" => $person->name, "tel" => $person->tel, "email" => $person->email);
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
					$person = array("id" => null, "name" => $name, "tel" => null, "email" => null);
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
	
	private function set_doctor_schedule_cell($doctor_id, $date){
		$cells = array();
		$status_ids = array($this->status->code("reserved")->id, $this->status->code("confirmed")->id);
		$appointments = $this->appointment->doctor($doctor_id, $date, $status_ids);
		$surgeries = $this->surgery->doctor($doctor_id, $date, $status_ids);
		
		$min_range = array([0, 15], [15, 30], [30, 45], [45, 60]);
		$aux = array();
		
		if ($appointments) foreach($appointments as $item) array_push($aux, array("sh" => date("H", strtotime($item->schedule_from)), "sm" => date("i", strtotime($item->schedule_from)), "eh" => date("H", strtotime($item->schedule_to)), "em" => date("i", strtotime($item->schedule_to))));
		
		if ($surgeries) foreach($surgeries as $item) array_push($aux, array("sh" => date("H", strtotime($item->schedule_from)), "sm" => date("i", strtotime($item->schedule_from)), "eh" => date("H", strtotime($item->schedule_to)), "em" => date("i", strtotime($item->schedule_to))));;
		
		foreach($aux as $item){
			foreach($min_range as $key => $r){
				if (($r[0] <= $item["sm"]) and ($item["sm"] < $r[1])) $item["sm"] = str_pad($r[0], 2, "0", STR_PAD_LEFT);
				if (($r[0] <= $item["em"]) and ($item["em"] < $r[1])) $item["em"] = str_pad($r[0], 2, "0", STR_PAD_LEFT);
			}
			
			$i = strtotime($date." ".$item["sh"].":".$item["sm"]);
			$end = strtotime($date." ".$item["eh"].":".$item["em"]);
			
			do{
				array_push($cells, date("Hi", $i));
				$i += 900;//15 minutes in seconds
			}while($i <= $end);
		}
		
		return $cells;
	}
	
	public function load_doctor_schedule(){
		$cells = array(); $msg = null;
		$doctor_id = $this->input->post("doctor_id");
		$date = $this->input->post("date");
		
		if (!$doctor_id) $msg = $this->lang->line('error_select_doctor');
		if (!$date) $msg = $this->lang->line('error_select_date');
		
		if (!$msg) $cells = $this->set_doctor_schedule_cell($doctor_id, $date);
		
		echo $this->load->view('doctor/tb_schedule', array("msg" => $msg, "cells" => $cells), true);
	}
	
	public function load_doctor_schedule_weekly(){
		$cells = array(); $msg = null;
		$doctor_id = $this->input->post("doctor_id");
		$date = $this->input->post("date"); if (!$date) $date = date("Y-m-d");
		
		$prev = date("Y-m-d", strtotime("-1 week", strtotime($date)));
		$next = date("Y-m-d", strtotime("+1 week", strtotime($date)));
		
		if (!$doctor_id) $msg = $this->lang->line('error_select_doctor');
		
		$dates = array();
		for($i = 0; $i < 7; $i++){
			$date_aux = date("Y-m-d", strtotime("+".$i." days", strtotime($date)));
			
			array_push($dates, array("hd" => $this->lang->line('day_'.date("D", strtotime($date_aux)))."<br/>".date("d.m", strtotime($date_aux)), "num" => date("Ymd", strtotime($date_aux))));
			
			if (!$msg){
				$aux_cells = $this->set_doctor_schedule_cell($doctor_id, $date_aux);
				if ($aux_cells){
					$date_num = date("Ymd", strtotime($date_aux));
					foreach($aux_cells as $c) array_push($cells, $date_num.$c);
				}
			}
		}
		
		$data = array("msg" => $msg, "dates" => $dates, "cells" => $cells, "prev" => $prev, "next" => $next);
		echo $this->load->view('doctor/tb_schedule_weekly', $data, true);
	}
	
	public function load_schedule(){
		$res = array();
		
		$today = date("Y-m-d");
		$tomorrow = date("Y-m-d", strtotime("+1 day"));
		
		$appointments_arr = array();
		$appointments_arr[$today]["title"] = $this->lang->line('txt_today').", ".date("d.m.Y", strtotime($today));
		$appointments_arr[$today]["data"] = array();
		$appointments_arr[$tomorrow]["title"] = $this->lang->line('txt_tomorrow').", ".date("d.m.Y", strtotime($tomorrow));
		$appointments_arr[$tomorrow]["data"] = array();
		
		$surgeries_arr = array();
		$surgeries_arr[$today]["title"] = $this->lang->line('txt_today').", ".date("d.m.Y", strtotime($today));
		$surgeries_arr[$today]["data"] = array();
		$surgeries_arr[$tomorrow]["title"] = $this->lang->line('txt_tomorrow').", ".date("d.m.Y", strtotime($tomorrow));
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
				"schedule" => date("h:i a", strtotime($item->schedule_from)),
				"doctor" => $this->general->id("person", $item->doctor_id)->name,
				"patient" => $this->general->id("person", $item->patient_id)->name,
				"specialty" => $this->general->id("specialty", $item->specialty_id)->name
			);
			array_push($appointments_arr[date("Y-m-d", strtotime($item->schedule_from))]["data"], $data);
		}
		
		
		$surgeries = $this->general->filter("surgery", $filter, "schedule_from", "asc");
		foreach($surgeries as $item){
			$data = array(
				"id" => $item->id,
				"color" => $this->general->id("status", $item->status_id)->color,
				"schedule" => date("h:i a", strtotime($item->schedule_from)),
				"doctor" => $this->general->id("person", $item->doctor_id)->name,
				"patient" => $this->general->id("person", $item->patient_id)->name,
				"specialty" => $this->general->id("specialty", $item->specialty_id)->name,
				"room" => $this->general->id("surgery_room", $item->room_id)->name
			);
			array_push($surgeries_arr[date("Y-m-d", strtotime($item->schedule_from))]["data"], $data);
		}
		
		header('Content-Type: application/json');
		echo json_encode(array("appointments" => $appointments_arr, "surgeries" => $surgeries_arr));
	}
	
	public function print_functions(){
		echo "<table>";
		foreach(glob(APPPATH . 'controllers/*') as $controller) {
			if(pathinfo($controller, PATHINFO_EXTENSION) == "php"){
				include_once $controller;
				
				$controllername = basename($controller, ".php");
				$class_methods = get_class_methods($controllername);
				if ($class_methods) foreach($class_methods as $method){
					if($method != '__construct' && $method != 'get_instance' && $method != $controllername) {
						echo "<tr><td>".$controllername."</td><td>".$method."</td><td>".strtolower($controllername."_".$method)."</td></tr>";
					}
				}
			}
		}
		echo "</table>";
	}
	
	//test data creation
	private function make_app_sur($qty){
		$doctors_arr = array();
		$doctors = $this->general->all("doctor");
		$people = $this->general->all("person");
		
		$times = array();
		for($i = 9; $i < 19; $i++){
			array_push($times, $i.":00");
			array_push($times, $i.":15");
			array_push($times, $i.":30");
			array_push($times, $i.":45");
		}
		
		$today = date("Y-m-d");
		$schedules = array();
		for($i = 1; $i < 300; $i++){
			$day = date("Y-m-d", strtotime("+".$i." day", strtotime($today)));
			foreach($times as $t) array_push($schedules, $day." ".$t);
		}
		
		$doctor_ids = $patient_ids = array();
		foreach($doctors as $item){
			array_push($doctor_ids, $item->person_id);
			$doctors_arr[$item->person_id] = $item;
		}
		foreach($people as $item) array_push($patient_ids, $item->id);
		
		$types = array();
		array_push($types, array("type" => "surgery", "duration" => 89));
		array_push($types, array("type" => "appointment", "duration" => 14));
		
		$places = array("Hospital Nacional Arzobispo Loayza", "Hospital Almenara", "Hospital Nacional Dos de Mayo", "Hospital Rebagliati", "Hospital de Emergencias Grau", "Hospital del Niño", "Hospital Nacional Guillermo Almenara Irigoyen", "Hospital Nacional Hipólito Unanue", "Hospital Nacional Cayetano Heredia", "Hospital Maria Auxiliadora");
		
		$status_id = $this->status->code("reserved")->id;
		
		$status_ids = array();
		array_push($status_ids, $this->status->code("reserved")->id);
		array_push($status_ids, $this->status->code("confirmed")->id);
		
		$count = 0;
		for($i = 0; $i < $qty; $i++){
			$data = array();
			
			$type = $types[array_rand($types)];
			if (!strcmp("surgery", $type["type"])) $type["duration"] = rand(30, 120);
			
			$data["doctor_id"] = $doctor_ids[array_rand($doctor_ids)];
			$data["schedule_from"] = $schedules[array_rand($schedules)];
			$data["schedule_to"] = date("Y-m-d H:i:s", strtotime("+".$type["duration"]." minutes", strtotime($data["schedule_from"])));
			
			
			$sur_available = $this->general->is_available("surgery", $data, $status_ids);
			$app_available = $this->general->is_available("appointment", $data, $status_ids);
			if ($sur_available and $app_available){
				if (!strcmp("surgery", $type["type"])) $data["place"] = $places[array_rand($places)];
				
				$data["patient_id"] = $patient_ids[array_rand($patient_ids)];
				$data["specialty_id"] = $doctors_arr[$data["doctor_id"]]->specialty_id;
				$data["status_id"] = $status_id;
				if ($data["doctor_id"] != $data["patient_id"]){
					echo $i." >> ".implode(" - ", $data)."<br/>";
					$this->general->insert($type["type"], $data);
					$count++;
				}
			}
		}
		echo "<br/><br/>".number_format($count)." registros generados";
	}
}