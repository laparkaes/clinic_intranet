<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax_f extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/Lima');
		$this->lang->load("system", "spanish");
		$this->lang->load("appointment", "spanish");
		$this->load->model('general_model','general');
	}
	
	public function search_person(){
		$type = "error"; $msg = null; $person = null;
		$data = $this->input->post();
		
		if (!$data["doc_type_id"]) $msg = $this->lang->line('error_doc_type');
		if (!$data["doc_number"]) $msg = $this->lang->line('error_doc_number');
		
		if (!$msg){
			$person_rec = $this->general->filter("person", $data);
			if ($person_rec){
				$p = $person_rec[0];
				$person = ["id" => $p->id, "name" => $p->name, "tel" => $p->tel, "email" => $p->email];
				$type = "success";
				$msg = $this->lang->line('success_data_loaded');
			}else{
				$name = null;
				switch($this->general->id("doc_type", $data["doc_type_id"])->short){
					case "DNI":
						$ud = $this->utility_lib->utildatos_dni($data["doc_number"]);
						if ($ud->status) $name = $ud->data->nombres." ".$ud->data->apellidoPaterno." ".$ud->data->apellidoMaterno;
						break;
					case "RUC":
						$ud = $this->utility_lib->utildatos_ruc($data["doc_number"]);
						if ($ud->status) $name = $ud->data->razon_social;
						break;
				}
				
				if ($name){
					$person = ["id" => null, "name" => $name, "tel" => null, "email" => null];
					$type = "success";
					$msg = $this->lang->line('success_data_loaded');
				}else $msg = $this->lang->line('error_insert_manually');
			}
		}
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg, "person" => $person]);
	}
	
	public function search_company(){
		$type = "error"; $msg = null; $company = $this->general->structure("company");
		$data = $this->input->post();
		
		if (array_key_exists('tax_id', $data)){
			if ($data["tax_id"]){
				$company_rec = $this->general->filter("company", ["tax_id" => $data["tax_id"]], null, null, "updated_at", "desc");
				if ($company_rec) $company = $company_rec[0];
				
				$ud = $this->utility_lib->utildatos_ruc($data["tax_id"]);
				if ($ud->status){
					$company->tax_id = $ud->data->ruc;
					$company->name = $ud->data->razon_social;
					$company->address = trim($ud->data->direccion);
					$company->ubigeo = $ud->data->ubigeo;
					$company->urbanization = $ud->data->codigo_zona." ".$ud->data->tipo_zona;
					
					$district = $this->general->filter("address_district", ["ubigeo" => $company->ubigeo]);
					if ($district){
						$district = $district[0];
						$province = $this->general->id("address_province", $district->province_id);
						$department = $this->general->id("address_department", $province->department_id);
						
						$company->district_id = $district->id;
						$company->province_id = $province->id;
						$company->department_id = $department->id;
					}
				}
				
				if ($company->tax_id){
					$type = "success";
					$msg = $this->lang->line('success_data_loaded');
				}else $msg = $this->lang->line('error_insert_manually');
			}else $msg = $this->lang->line('error_ruc');
		}else $msg = $this->lang->line('error_ruc');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg, "company" => $company]);
	}
	
	private function set_doctor_schedule_cell($doctor_id, $date){
		$cells = [];
		$f = [
			"status_id" => $this->general->status("confirmed")->id, 
			"doctor_id" => $doctor_id,
			"schedule_from >=" => date("Y-m-d 00:00:00", strtotime($date)),
			"schedule_from <=" => date("Y-m-d 23:23:59", strtotime($date)),
		];
		$appointments = $this->general->filter("appointment", $f);
		$surgeries = $this->general->filter("surgery", $f);
		
		$min_range = array([0, 10], [10, 20], [20, 30], [30, 40], [40, 50], [50, 60]);
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
				$i += 600;//10 minutes in seconds
			}while($i <= $end);
		}
		
		return $cells;
	}
	
	public function test(){
		
		$status_arr = [];
		$status = $this->general->filter("status");
		foreach($status as $item) $status_arr[$item->id] = $item;
		
		
		$f = [
			//"status_id" => $this->general->status("confirmed")->id, 
			"doctor_id" => 3,
			"schedule_from >=" => date("Y-m-d 00:00:00", strtotime("2025-02-03")),
			"schedule_from <=" => date("Y-m-d 23:23:59", strtotime("2025-02-03")),
		];
		
		$schedules = [];
		
		$app = $this->general->filter("appointment", $f);
		foreach($app as $item){
			$schedules[] = [
				"type" => "appointment",
				"specialty" => $this->general->unique("specialty", "id", $item->specialty_id)->name,
				"patient" => $this->general->unique("person", "id", $item->patient_id)->name,
				"status" => $status_arr[$item->status_id],
				"reserved" => date("H:i", strtotime($item->schedule_from)),
			];
		}
		
		foreach($schedules as $item){
			print_r($item);
			echo "<br/>";
		}
	}
	
	public function load_doctor_schedule(){
		$schedules = []; $msg = null;
		$doctor_id = $this->input->post("doctor_id");
		$date = $this->input->post("date");
		if (!$date) $date = date("Y-m-d");
		
		if (!$doctor_id) $msg = $this->lang->line('error_select_doctor');
		if (!$date) $msg = $this->lang->line('error_select_date');
		
		$status_arr = [];
		$status = $this->general->filter("status");
		foreach($status as $item) $status_arr[$item->id] = $item;
		
		//if (!$msg) $cells = $this->set_doctor_schedule_cell($doctor_id, $date);
		if (!$msg){
			$f = [
				//"status_id" => $this->general->status("confirmed")->id, 
				"doctor_id" => $doctor_id,
				"schedule_from >=" => date("Y-m-d 00:00:00", strtotime($date)),
				"schedule_from <=" => date("Y-m-d 23:23:59", strtotime($date)),
			];
			
			$app = $this->general->filter("appointment", $f, null, null, "schedule_from", "asc");
			foreach($app as $item){
				$schedules[] = [
					"type" => "appointment",
					"specialty" => $this->general->unique("specialty", "id", $item->specialty_id)->name,
					"patient" => $this->general->unique("person", "id", $item->patient_id)->name,
					"status" => $status_arr[$item->status_id],
					"reserved" => date("H:i", strtotime($item->schedule_from)),
				];
			}
			
			$sur = $this->general->filter("surgery", $f, null, null, "schedule_from", "asc");
			foreach($sur as $item){
				$schedules[] = [
					"type" => "surgery",
					"specialty" => $this->general->unique("specialty", "id", $item->specialty_id)->name,
					"patient" => $this->general->unique("person", "id", $item->patient_id)->name,
					"status" => $status_arr[$item->status_id],
					"reserved" => date("H:i", strtotime($item->schedule_from)),
				];
			}
			
			usort($schedules, function ($a, $b) {
				return strtotime($a['reserved']) - strtotime($b['reserved']);
			});
		}
		
		//echo $this->load->view('clinic/doctor/tb_schedule', ["msg" => $msg, "cells" => $cells, "date" => $date], true);
		echo $this->load->view('clinic/doctor/tb_schedule', ["msg" => $msg, "status_arr" => $status_arr, "schedules" => $schedules, "date" => $date], true);
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
		echo $this->load->view('clinic/doctor/tb_schedule_weekly', $data, true);
	}
}