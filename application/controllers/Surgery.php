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
	}
	
	private function set_msg($msgs, $dom_id, $type, $msg_code){
		if ($msg_code) array_push($msgs, array("dom_id" => $dom_id, "type" => $type, "msg" => $this->lang->line($msg_code)));
		return $msgs;
	}
	
	public function index(){
		if (!$this->session->userdata('logged_in')) redirect(base_url());
		//PENDING! rol validation
		
		$filter = ["schedule_from >=" => date("Y-m-d", strtotime("-1 month"))];
		$surgeries = $this->general->filter("surgery", $filter, "schedule_from", "desc");
		
		$person_ids = array();
		$patient_ids = $this->general->only("surgery", "patient_id", $filter);
		$doctor_ids = $this->general->only("surgery", "doctor_id", $filter);
		foreach($patient_ids as $item) array_push($person_ids, $item->patient_id);
		foreach($doctor_ids as $item) array_push($person_ids, $item->doctor_id);
		
		array_unique($person_ids);
		
		$people_arr = array();
		$people = $this->general->ids("person", $person_ids);
		foreach($people as $p) $people_arr[$p->id] = $p->name;
		
		$se_id = $this->general->filter("status", ["code" => "enabled"])[0]->id;//status_enabled_id
		
		$specialties_arr = array();
		$specialties = $this->general->all("specialty", "name", "asc");
		foreach($specialties as $s){
			$s->doctor_qty = $this->general->counter("doctor", ["status_id" => $se_id, "specialty_id" => $s->id]);
			$specialties_arr[$s->id] = $s->name;
		}
		
		$doctors_arr = array();
		$doctors = $this->general->filter("doctor", array("status_id" => $se_id));
		foreach($doctors as $d){
			$d->name = $this->general->id("person", $d->person_id)->name;
			$doctors_arr[$d->person_id] = $d;
		}
		usort($doctors, function($a, $b) {return strcmp(strtoupper($a->name), strtoupper($b->name));});
		
		$status_arr = array();
		$status = $this->general->all("status");
		foreach($status as $item) $status_arr[$item->id] = $item;
		
		$rooms_arr = array();
		$rooms = $this->general->all("surgery_room", "name", "asc");
		foreach($rooms as $item) $rooms_arr[$item->id] = $item->name;
		
		$duration_ops = array();
		array_push($duration_ops, ["value" => 30, "txt" => "30 ".$this->lang->line('op_minutes')]);
		array_push($duration_ops, ["value" => 60, "txt" => "1 ".$this->lang->line('op_hour')]);
		for($i = 2; $i <= 12; $i++) array_push($duration_ops, ["value" => 60 * $i, "txt" => $i." ".$this->lang->line('op_hours')]);
		
		$data = array(
			"status_arr" => $status_arr,
			"surgeries" => $surgeries,
			"rooms" => $rooms,
			"rooms_arr" => $rooms_arr,
			"duration_ops" => $duration_ops,
			"specialties" => $specialties,
			"specialties_arr" => $specialties_arr,
			"doctors" => $doctors,
			"doctors_arr" => $doctors_arr,
			"doc_types" => $this->general->all("doc_type", "id", "asc"),
			"people_arr" => $people_arr,
			"title" => $this->lang->line('surgeries'),
			"main" => "surgery/list",
			"init_js" => "surgery/list.js"
		);
		
		$this->load->view('layout', $data);
	}
	
	public function detail($id){
		if (!$this->session->userdata('logged_in')) redirect(base_url());
		//PENDING! rol validation
		
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
		
		$surgery_sale = $this->general->filter("sale", array("surgery_id" => $surgery->id));
		if ($surgery_sale){
			$sale_items = $this->general->filter("sale_product", array("sale_id" => $surgery_sale[0]->id));
			foreach($sale_items as $item){
				$product = $this->general->id("product", $item->product_id);
				$category = $this->general->id("product_category", $product->category_id)->name;
				
				$str = $category.", ".$product->description;
				if (strpos($str, "Cirugía") !== false) $surgery->detail = $surgery->detail." / ".$str;
			}
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
			$patient->doc_type = $this->sl_option->id($patient->doc_type_id)->description;
			if ($patient->birthday) $patient->age = $this->utility_lib->age_calculator($patient->birthday, true);
			else $patient->age = null;
			if ($patient->sex_id) $patient->sex = $this->general->id("sl_option", $patient->sex_id)->description;
			else $patient->sex = null;
			if ($patient->blood_type_id) $patient->blood_type = $this->general->id("sl_option", $patient->blood_type_id)->description;
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
		$status = false; $type = "error"; $msgs = array(); $msg = null; $move_to = null;
		
		$sur = $this->input->post("sur"); $sur["schedule_from"] = null;
		$sch = $this->input->post("sch");
		$pt = $this->input->post("pt");
		
		//patient validation
		if (!$pt["name"]) $msgs = $this->set_msg($msgs, "sur_pt_name_msg", "error", "error_ena");
		if (!$pt["tel"]) $msgs = $this->set_msg($msgs, "sur_pt_tel_msg", "error", "error_ete");
		if (!$pt["doc_number"]) $msgs = $this->set_msg($msgs, "sur_pt_doc_msg", "error", "error_dnu");
		
		//schedule validation
		if (!$sch["duration"]) $msgs = $this->set_msg($msgs, "sur_duration_msg", "error", "error_sdu");
		if (!$sch["date"]) $msgs = $this->set_msg($msgs, "sur_schedule_msg", "error", "error_sda");
		elseif (!$sch["hour"]) $msgs = $this->set_msg($msgs, "sur_schedule_msg", "error", "error_sho");
		elseif (!$sch["min"]) $msgs = $this->set_msg($msgs, "sur_schedule_msg", "error", "error_smi");
		else $sur["schedule_from"] = $sch["date"]." ".$sch["hour"].":".$sch["min"];
		
		//surgery validation
		$status_ids = array();
		array_push($status_ids, $this->status->code("reserved")->id);
		array_push($status_ids, $this->status->code("confirmed")->id);
		
		if (!$sur["room_id"]) $msgs = $this->set_msg($msgs, "sur_room_msg", "error", "error_sro");
		if (!$sur["specialty_id"]) $msgs = $this->set_msg($msgs, "sur_specialty_msg", "error", "error_ssp");
		if (!$sur["doctor_id"]) $msgs = $this->set_msg($msgs, "sur_doctor_msg", "error", "error_sdo");
		if ($sur["schedule_from"]){
			if ($sch["duration"]){
				$sur["schedule_to"] = date("Y-m-d H:i:s", strtotime("+".($sch["duration"]-1)." minutes", strtotime($sur["schedule_from"])));
				
				//check surgery and surgery available
				$sur_available = $this->general->is_available("surgery", $sur, $status_ids);
				$app_available = $this->general->is_available("appointment", $sur, $status_ids);
				if (!($sur_available and $app_available)) $msgs = $this->set_msg($msgs, "sur_schedule_msg", "error", "error_dna");
				
				//room available
				if ($sur["room_id"]){
					$surgeries = $this->general->get_by_room("surgery", $sur, $status_ids, null, $sur["room_id"]);
					if ($surgeries) $msgs = $this->set_msg($msgs, "sur_room_msg", "error", "error_rna");
				}
			}
		}
		
		if ($msgs) $msg = $this->lang->line('error_occurred'); 
		else{
			//patient = doctor?
			$f = array("doc_type_id" => $pt["doc_type_id"], "doc_number" => $pt["doc_number"]);
			$person = $this->general->filter("person", $f);
			if ($person){
				$person = $person[0];
				if ($sur["doctor_id"] == $person->id) $msg = $this->lang->line('error_pdp');
				else $sur["patient_id"] = $person->id;
			}else $sur["patient_id"] = $this->general->insert("person", $pt);
			
			if (!$msg){
				$now = date('Y-m-d H:i:s', time());
				
				//check if patient exists
				if ($sur["patient_id"]) $this->general->update("person", $sur["patient_id"], $pt);
				else{
					$pt["registed_at"] = $now;
					$sur["patient_id"] = $this->general->insert("person", $pt);
				}
				
				$sur["status_id"] = $this->status->code("reserved")->id;
				$sur["registed_at"] = $now;
				$surgery_id = $this->general->insert("surgery", $sur);
				if ($surgery_id){
					$this->utility_lib->add_log("surgery_register", $pt["name"]);
					
					$status = true;
					$type = "success";
					$move_to = base_url()."surgery/detail/".$surgery_id;
					$msg = $this->lang->line('success_ras');
				}else $msg = $this->lang->line('error_internal');	
			}
		}
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msgs" => $msgs, "msg" => $msg, "move_to" => $move_to));
	}
	
	public function cancel(){
		$status = false; $type = "error"; $msg = null;
		$surgery = $this->surgery->id($this->input->post("id"));
		if ($surgery){
			if ($this->surgery->update($surgery->id, array("status_id" => $this->status->code("canceled")->id))){
				$person = $this->general->id("person", $surgery->patient_id);
				$this->utility_lib->add_log("surgery_cancel", $person->name);
				
				$status = true;
				$type = "success";
				$msg = $this->lang->line('success_cap');
			}else $msg = $this->lang->line('error_internal');
		}else $msg = $this->lang->line('error_nap');
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msg" => $msg));
	}
	
	public function finish(){
		$status = false; $type = "error"; $msg = null;
		//pending!! role validation
		
		$data = $this->input->post();
		$surgery = $this->general->id("surgery", $data["id"]);
		
		if ($data["result"]){
			$data["status_id"] = $this->status->code("finished")->id;
			if ($this->general->update("surgery", $data["id"], $data)){
				$person = $this->general->id("person", $surgery->patient_id);
				$this->utility_lib->add_log("surgery_finish", $person->name);
				
				$status = true;
				$type = "success";
				$msg = $this->lang->line('success_fsu');
			}else $msg = $this->lang->line('error_internal');
		}else $msg = $this->lang->line('error_sre');
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msg" => $msg));
	}

	public function reschedule(){
		$status = false; $type = "error"; $msg = null; $msgs = array();
		$data = $this->input->post();
		
		if (!$data["room_id"]) $msgs = $this->set_msg($msgs, "rs_room_msg", "error", "error_sro");
		if (!$data["duration"]) $msgs = $this->set_msg($msgs, "rs_duration_msg", "error", "error_sdu");
		if (!$data["hour"]) $msgs = $this->set_msg($msgs, "rs_time_msg", "error", "error_sho");
		elseif (!$data["min"]) $msgs = $this->set_msg($msgs, "rs_time_msg", "error", "error_smi");
		
		if ($msgs) $msg = $this->lang->line('error_occurred');
		else{
			$surgery = $this->surgery->id($data["id"]);
			if ($surgery){
				$schedule_from = $data["date"]." ".$data["hour"].":".$data["min"];
				$sur = array(
					"id" => $surgery->id,
					"room_id" => $data["room_id"],
					"doctor_id" => $surgery->doctor_id,
					"schedule_from" => $schedule_from,
					"schedule_to" => date("Y-m-d H:i:s", strtotime("+".($data["duration"]-1)." minutes", strtotime($schedule_from)))
				);
				
				$status_ids = array();
				array_push($status_ids, $this->status->code("reserved")->id);
				array_push($status_ids, $this->status->code("confirmed")->id);
				
				$sur_available = $this->general->is_available("surgery", $sur, $status_ids, $sur["id"]);
				$app_available = $this->general->is_available("appointment", $sur, $status_ids);
				
				if ($sur_available and $app_available){
					//room available
					if (!$this->general->get_by_room("surgery", $sur, $status_ids, $data["id"], $data["room_id"])){
						if ($this->surgery->update($sur["id"], $sur)){
							$person = $this->general->id("person", $surgery->patient_id);
							$this->utility_lib->add_log("surgery_reschedule", $person->name);
						
							$status = true;
							$type = "success";
							$msg = $this->lang->line('success_rsu');
						}else $msg = $this->lang->line('error_internal');						
					}else $msg = $this->lang->line('error_rna');
				}else $msg = $this->lang->line('error_dna');
			}else $msg = $this->lang->line('error_internal_refresh');
		}
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msg" => $msg, "msgs" => $msgs));
	}

	public function report($id){
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
			$patient->doc_type = $this->sl_option->id($patient->doc_type_id)->description;
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
				$surgeries = $this->general->filter_adv("surgery", $filter, $filter_in);
				
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
