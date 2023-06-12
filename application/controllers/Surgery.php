<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;

class Surgery extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/Lima');
		$this->lang->load("surgery", "spanish");
		$this->lang->load("system", "spanish");
		$this->load->model('surgery_model','surgery');
		$this->load->model('status_model','status');
		$this->load->model('general_model','general');
		$this->nav_menu = "surgery";
		$this->nav_menus = $this->utility_lib->get_visible_nav_menus();
	}
	
	public function index(){
		if (!$this->session->userdata('logged_in')) redirect(base_url());
		if (!$this->utility_lib->check_access("surgery", "index")) redirect("/errors/no_permission");
		
		$f_url = [
			"page" => $this->input->get("page"),
			"status" => $this->input->get("status"),
			"date" => $this->input->get("date"),
		];
		
		$f_w = [];
		if (!$f_url["page"]) $f_url["page"] = 1;
		if ($f_url["status"]) $f_w["status_id"] = $f_url["status"];
		if ($f_url["date"]){
			$f_w["schedule_from >="] = $f_url["date"]." 00:00:00";
			$f_w["schedule_to <="] = $f_url["date"]." 23:59:59";
		}
		
		if ($this->session->userdata('role')->name === "doctor") $f_w["doctor_id"] = $this->session->userdata('pid');
		$surgeries = $this->general->filter("surgery", $f_w, null, null, "schedule_from", "desc", 25, 25*($f_url["page"]-1));
		foreach($surgeries as $item){
			$item->patient = $this->general->id("person", $item->patient_id)->name;
			$item->doctor = $this->general->id("person", $item->doctor_id)->name;
			$item->specialty = $this->general->id("specialty", $item->specialty_id)->name;
			$item->room = $this->general->id("surgery_room", $item->room_id)->name;
		}
		
		$aux_f = ["status_id" => $this->general->filter("status", ["code" => "enabled"])[0]->id];
		$specialties = $this->general->all("specialty", "name", "asc");
		foreach($specialties as $s){
			$aux_f["specialty_id"] = $s->id;
			$s->doctor_qty = $this->general->counter("doctor", $aux_f);
		}
		unset($aux_f["specialty_id"]);
		
		$doctors = $this->general->filter("doctor", $aux_f);
		foreach($doctors as $d) $d->name = $this->general->id("person", $d->person_id)->name;
		usort($doctors, function($a, $b) {return strcmp(strtoupper($a->name), strtoupper($b->name));});
		
		$status_aux = [];
		$status_ids = $this->general->only("surgery", "status_id");
		foreach($status_ids as $item) $status_aux[] = $item->status_id;
		
		$f_status = [["field" => "id", "values" => $status_aux]];
		
		$status_arr = [];
		$status = $this->general->filter("status", null, null, [["field" => "id", "values" => $status_aux]]);
		foreach($status as $item) $status_arr[$item->id] = $item;
		
		$rooms_arr = array();
		$rooms = $this->general->all("surgery_room", "name", "asc");
		foreach($rooms as $item) $rooms_arr[$item->id] = $item->name;
		
		$duration_ops = array();
		array_push($duration_ops, ["value" => 30, "txt" => "30 ".$this->lang->line('op_minutes')]);
		array_push($duration_ops, ["value" => 60, "txt" => "1 ".$this->lang->line('op_hour')]);
		for($i = 2; $i <= 12; $i++) array_push($duration_ops, ["value" => 60 * $i, "txt" => $i." ".$this->lang->line('op_hours')]);
		
		$data = array(
			"paging" => $this->my_func->set_page($f_url["page"], $this->general->counter("surgery", $f_w)),
			"f_url" => $f_url,
			"status" => $status,
			"status_arr" => $status_arr,
			"surgeries" => $surgeries,
			"rooms" => $rooms,
			"rooms_arr" => $rooms_arr,
			"duration_ops" => $duration_ops,
			"specialties" => $specialties,
			"doctors" => $doctors,
			"doc_types" => $this->general->all("doc_type", "id", "asc"),
			"title" => $this->lang->line('surgeries'),
			"main" => "surgery/list",
			"init_js" => "surgery/list.js"
		);
		
		$this->load->view('layout', $data);
	}
	
	public function detail($id){
		if (!$this->session->userdata('logged_in')) redirect(base_url());
		if (!$this->utility_lib->check_access("surgery", "detail")) redirect("/errors/no_permission");
		
		$surgery = $this->general->id("surgery", $id);
		if (!$surgery) redirect("/surgery");
		
		//duration diff give last minute as additional
		$surgery->duration = (strtotime($surgery->schedule_to) - strtotime($surgery->schedule_from) + 60)/60;
		
		$surgery->status = $this->general->id("status", $surgery->status_id);
		$surgery->is_editable = false; $actions = array();
		switch($surgery->status->code){
			case "reserved":
				array_push($actions, "reschedule");
				array_push($actions, "cancel");
				break;
			case "confirmed":
				$surgery->is_editable = true;
				array_push($actions, "reschedule");
				break;
			case "finished":
				array_push($actions, "report");
				break;
		}
		
		$room = $this->general->id("surgery_room", $surgery->room_id);
		if ($room) $surgery->detail = $room->name; else $surgery->detail = "";
		
		$surgery_sale = $this->general->filter("sale_product", ["surgery_id" => $surgery->id]);
		if ($surgery_sale){
			$product = $this->general->id("product", $surgery_sale[0]->product_id);
			$category = $this->general->id("product_category", $product->category_id)->name;
			
			$str = $category.", ".$product->description;
			if (strpos($str, "Cirugía") !== false) $surgery->detail = $surgery->detail." / ".$str;
		}
		
		$doctor = $this->general->id("person", $surgery->doctor_id);
		if ($doctor){
			$data = $this->general->filter("doctor", array("person_id" => $doctor->id));
			if ($data){
				$doctor->data = $data[0];
				$doctor->data->specialty = $this->general->id("specialty", $doctor->data->specialty_id)->name;
			}
		}else{
			$doctor = $this->general->structure("person");
			$doctor->data = $this->general->structure("doctor");
			$doctor->data->specialty = "";
		}
		
		$patient = $this->general->id("person", $surgery->patient_id);
		if ($patient){
			$patient->doc_type = $this->general->id("doc_type", $patient->doc_type_id)->description;
			if ($patient->birthday) $patient->age = $this->utility_lib->age_calculator($patient->birthday, true);
			else $patient->age = null;
			
			if ($patient->sex_id) $patient->sex = $this->general->id("sex", $patient->sex_id)->description;
			else $patient->sex = null;
			
			if ($patient->blood_type_id)
				$patient->blood_type = $this->general->id("blood_type", $patient->blood_type_id)->description;
			else $patient->blood_type = null;
		}
		
		//start set history records > mix surgeries and surgerys
		$specialties = array();
		$specialties_rec = $this->general->all("specialty");
		foreach($specialties_rec as $item) $specialties[$item->id] = $item->name;
		
		$filter = array("patient_id" => $patient->id, "status_id" => $this->status->code("finished")->id);
		
		$surgery_histories = $this->general->filter("surgery", $filter);
		foreach($surgery_histories as $item){
			$d = $this->general->filter("doctor", array("person_id" => $doctor->id))[0];
			$item->specialty = $specialties[$d->specialty_id];
			$item->link_to = "surgery";
			$item->type = $this->lang->line($item->link_to);
		}
		
		$appointment_histories = $this->general->filter("appointment", $filter);
		foreach($appointment_histories as $item){
			$d = $this->general->filter("doctor", array("person_id" => $doctor->id))[0];
			$item->specialty = $specialties[$d->specialty_id];
			$item->link_to = "appointment";
			$item->type = $this->lang->line($item->link_to);
		}
		
		$histories = array_merge($surgery_histories, $appointment_histories);
		usort($histories, function($a, $b) { return ($a->schedule_from < $b->schedule_from); });
		//end set history records
		
		$duration_ops = array();
		array_push($duration_ops, ["value" => 30, "txt" => "30 ".$this->lang->line('op_minutes')]);
		array_push($duration_ops, ["value" => 60, "txt" => "1 ".$this->lang->line('op_hour')]);
		for($i = 2; $i <= 12; $i++) array_push($duration_ops, ["value" => 60 * $i, "txt" => $i." ".$this->lang->line('op_hours')]);
		
		$data = array(
			"actions" => $actions,
			"surgery" => $surgery,
			"doctor" => $doctor,
			"patient" => $patient,
			"histories" => $histories,
			"rooms" => $this->general->all("surgery_room", "name", "asc"),
			"duration_ops" => $duration_ops,
			"patient_files" => $this->general->filter("patient_file", array("patient_id" => $surgery->patient_id)),
			"title" => $this->lang->line('surgery'),
			"main" => "surgery/detail",
			"init_js" => "surgery/detail.js"
		);
		
		$this->load->view('layout', $data);
	}
	
	public function register(){
		$type = "error"; $msgs = array(); $msg = null; $move_to = null;
		 	
		if ($this->utility_lib->check_access("surgery", "register")){			
			$sur = $this->input->post("sur"); $sur["schedule_from"] = null;
			$sch = $this->input->post("sch");
			$pt = $this->input->post("pt");
			
			$this->load->library('my_val');
			$msgs = $this->my_val->surgery($msgs, "sur_", $sur, $sch, $pt);
			
			if (!$msgs){
				$now = date('Y-m-d H:i:s', time());
				$person = $this->general->filter("person", ["doc_type_id" => $pt["doc_type_id"], "doc_number" => $pt["doc_number"]]);
				if ($person){
					$person = $person[0];
					$pt["updated_at"] = $now;
					$this->general->update("person", $person->id, $pt);
					$sur["patient_id"] = $person->id;
					$this->utility_lib->add_log("person_update", $person->name);
				}else{
					$pt["registed_at"] = $now;
					$sur["patient_id"] = $this->general->insert("person", $pt);
					$person = $this->general->id("person", $sur["patient_id"]);
					$this->utility_lib->add_log("person_register", $person->name);
				}
				
				$sur["schedule_from"] = $sch["date"]." ".$sch["hour"].":".$sch["min"];
				$sur["schedule_to"] = date("Y-m-d H:i:s", strtotime("+".($sch["duration"]-1)." minutes", strtotime($sur["schedule_from"])));
				$sur["status_id"] = $this->status->code("reserved")->id;
				$sur["registed_at"] = $now;
				$surgery_id = $this->general->insert("surgery", $sur);
				if ($surgery_id){
					$this->utility_lib->add_log("surgery_register", $pt["name"]);
					
					$type = "success";
					$move_to = base_url()."surgery/detail/".$surgery_id;
					$msg = $this->lang->line('success_ras');
				}else $msg = $this->lang->line('error_internal');	
			}else $msg = $this->lang->line('error_occurred');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msgs" => $msgs, "msg" => $msg, "move_to" => $move_to]);
	}
	
	public function cancel(){
		$status = false; $type = "error"; $msg = null;
		
		if ($this->utility_lib->check_access("surgery", "update")){
			$surgery = $this->surgery->id($this->input->post("id"));
			if ($surgery){
				if ($this->surgery->update($surgery->id, array("status_id" => $this->status->code("canceled")->id))){
					$person = $this->general->id("person", $surgery->patient_id);
					$this->utility_lib->add_log("surgery_cancel", $person->name);
					
					$status = true;
					$type = "success";
					$msg = $this->lang->line('success_csu');
				}else $msg = $this->lang->line('error_internal');
			}else $msg = $this->lang->line('error_nap');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msg" => $msg));
	}
	
	public function finish(){
		$type = "error"; $msg = null;
		
		if ($this->utility_lib->check_access("surgery", "update")){			
			$data = $this->input->post();
			$surgery = $this->general->id("surgery", $data["id"]);
			
			if ($data["result"]){
				$data["status_id"] = $this->status->code("finished")->id;
				if ($this->general->update("surgery", $data["id"], $data)){
					$person = $this->general->id("person", $surgery->patient_id);
					$this->utility_lib->add_log("surgery_finish", $person->name);
					
					$type = "success";
					$msg = $this->lang->line('success_fsu');
				}else $msg = $this->lang->line('error_internal');
			}else $msg = $this->lang->line('error_sre');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg]);
	}

	public function reschedule(){
		$type = "error"; $msg = null; $msgs = [];
		
		if ($this->utility_lib->check_access("surgery", "update")){			
			$data = $this->input->post();
			$surgery = $this->general->id("surgery", $data["id"]);
			
			if($surgery){
				$this->load->library('my_val');
				$msgs = $this->my_val->surgery_reschedule($msgs, "rs_", $surgery, $data);
				
				if (!$msgs){
					$schedule_from = $data["date"]." ".$data["hour"].":".$data["min"];
					$app = [
						"id" => $surgery->id,
						"room_id" => $data["room_id"],
						"schedule_from" => $schedule_from,
						"schedule_to" => date("Y-m-d H:i:s", strtotime("+".($data["duration"]-1)." minutes", strtotime($schedule_from)))
					];
					
					if ($this->surgery->update($app["id"], $app)){
						$person = $this->general->id("person", $surgery->patient_id);
						$this->utility_lib->add_log("surgery_reschedule", $person->name);
						
						$type = "success";
						$msg = $this->lang->line('success_rsu');
					}else $msg = $this->lang->line('error_internal');
				}else $msg = $this->lang->line('error_occurred');
			}else $msg = $this->lang->line('error_internal_refresh');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg, "msgs" => $msgs]);
	}

	public function report($id){
		if (!$this->utility_lib->check_access("surgery", "report")) redirect("/errors/no_permission");
		
		$surgery = $this->surgery->id($id);
		if (!$surgery) redirect("/surgery");
		
		$doctor = $this->general->id("person", $surgery->doctor_id);
		if ($doctor){
			$data = $this->general->filter("doctor", array("person_id" => $doctor->id));
			if ($data){
				$doctor->data = $data[0];
				$doctor->data->specialty = $this->specialty->id($doctor->data->specialty_id)->name;
			}
		}else{
			$doctor = $this->general->structure("person");
			$doctor->data = $this->general->structure("doctor");
			$doctor->data->specialty = "";
		}
		
		$patient = $this->general->id("person", $surgery->patient_id);
		if ($patient){
			$patient->doc_type = $this->general->id("doc_type", $patient->doc_type_id)->description;
			if ($patient->birthday) $patient->age = $this->utility_lib->age_calculator($patient->birthday, true);
			else $patient->birthday = $patient->age = null;	
		}else $patient = $this->general->structure("person");
		
		$surgery_datas = $this->set_surgery_data($surgery);
		
		$data = array(
			"surgery" => $surgery,
			"surgery_datas" => $surgery_datas,
			"doctor" => $doctor,
			"patient" => $patient
		);
		
		//$html = $this->load->view('surgery/report_1', $data, true);
		$html = $this->load->view('surgery/report_2', $data, true);
		
		//echo $html;
		
		// instantiate and use the dompdf class
		$dompdf = new Dompdf();

		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper('A4', 'portrait');//vertical [0.0, 0.0, 595.28, 841.89]

		// Render the HTML as PDF
		$dompdf->loadHtml($html);
		$dompdf->render();
		
		//Output the generated PDF to Browser
		if ($dompdf) $dompdf->stream("Reporte", array("Attachment" => false)); else echo "Error";
		//echo $html;
	}
	
	function load_room_availability(){
		$msg = null; $cells = array();
		$date = $this->input->post("date"); if (!$date) $date = date("Y-m-d");
		$room_id = $this->input->post("room_id");
		
		$prev = date("Y-m-d", strtotime("-1 week", strtotime($date)));
		$next = date("Y-m-d", strtotime("+1 week", strtotime($date)));
		
		$dates = array();
		for($i = 0; $i < 7; $i++){
			$date_aux = date("Y-m-d", strtotime("+".$i." days", strtotime($date)));
			
			array_push($dates, array("hd" => $this->lang->line('day_'.date("D", strtotime($date_aux)))."<br/>".date("d.m", strtotime($date_aux)), "num" => date("Ymd", strtotime($date_aux))));
		}
		
		if ($room_id){
			$room = $this->general->id("surgery_room", $room_id);
			$run = $date; $date_end = $next;
			
			$status_ids = array($this->status->code("reserved")->id, $this->status->code("confirmed")->id);
			$filter_in = array();
			array_push($filter_in, ["field" => "status_id", "values" => $status_ids]);
			
			while(strtotime($run) < strtotime($date_end)){
				$filter = ["room_id" => $room->id, "schedule_from >=" => $run." 00:00:00", "schedule_to <=" => $run." 23:59:59"];
				$surgeries = $this->general->filter("surgery", $filter, null, $filter_in);
				
				$min_range = array([0, 15], [15, 30], [30, 45], [45, 60]);
				$aux = array();
				
				if ($surgeries) foreach($surgeries as $item) array_push($aux, array("sh" => date("H", strtotime($item->schedule_from)), "sm" => date("i", strtotime($item->schedule_from)), "eh" => date("H", strtotime($item->schedule_to)), "em" => date("i", strtotime($item->schedule_to))));;
				
				foreach($aux as $item){
					foreach($min_range as $key => $r){
						if (($r[0] <= $item["sm"]) and ($item["sm"] < $r[1])) $item["sm"] = str_pad($r[0], 2, "0", STR_PAD_LEFT);
						if (($r[0] <= $item["em"]) and ($item["em"] < $r[1])) $item["em"] = str_pad($r[0], 2, "0", STR_PAD_LEFT);
					}
					
					$i = strtotime($run." ".$item["sh"].":".$item["sm"]);
					$end = strtotime($run." ".$item["eh"].":".$item["em"]);
					
					do{
						array_push($cells, date("YmdHi", $i));
						$i += 900;//15 minutes in seconds
					}while($i <= $end);
				}
				
				$run = date("Y-m-d", strtotime("+1 day", strtotime($run)));
			}
			
			$cells = array_unique($cells);
		}else $msg = "Elija una sala de cirugia";
		
		$data = array("msg" => $msg, "dates" => $dates, "cells" => $cells, "prev" => $prev, "next" => $next);
		echo $this->load->view('surgery/tb_weekly_availability', $data, true);
	}
}
