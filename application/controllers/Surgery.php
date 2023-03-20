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
		
		$f_from = $this->input->get("f_from"); if (!$f_from) $f_from = date("Y-m-d", strtotime("-6 months"));
		$f_to = $this->input->get("f_to");
		$f_status = $this->input->get("f_status");
		
		//getting surgeries from today
		$filter = array();
		$filter["schedule_from >="] = $f_from." 00:00:00";
		if ($f_status) $filter["status_id"] = $this->status->code($f_status)->id;
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
		
		$specialties_arr = array();
		$specialties = $this->general->all("specialty", "name", "asc");
		foreach($specialties as $s) $specialties_arr[$s->id] = $s->name;
		
		$status_enabled = $this->general->filter("status", array("code" => "enabled"))[0];
		$doctors_arr = array();
		$doctors = $this->general->filter("doctor", array("status_id" => $status_enabled->id));
		foreach($doctors as $d){
			$d->name = $this->general->id("person", $d->person_id)->name;
			$doctors_arr[$d->person_id] = $d;
		}
		usort($doctors, function($a, $b) {return strcmp(strtoupper($a->name), strtoupper($b->name));});
		
		$status_arr = array();
		$status = $this->general->all("status");
		foreach($status as $item) $status_arr[$item->id] = $item;
		
		$data = array(
			"filter" => array("from" => $f_from, "to" => $f_to, "status" => $f_status),
			"status_arr" => $status_arr,
			"surgeries" => $surgeries,
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
		
		$surgery_histories = $this->general->filter("surgery", $filter);
		foreach($surgery_histories as $item){
			$d = $this->general->filter("doctor", array("person_id" => $doctor->id))[0];
			$item->specialty = $specialties[$d->specialty_id];
			$item->link_to = "surgery";
			$item->type = $this->lang->line($item->link_to);
		}
		
		$histories = array_merge($surgery_histories, $surgery_histories);
		usort($histories, function($a, $b) { return ($a->schedule_from < $b->schedule_from); });
		//end set history records
		
		$data = array(
			"actions" => $actions,
			"surgery" => $surgery,
			"doctor" => $doctor,
			"patient" => $patient,
			"histories" => $histories,
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
		if (!$sur["place"]) $msgs = $this->set_msg($msgs, "sur_place_msg", "error", "error_enp");
		if (!$sur["speciality_id"]) $msgs = $this->set_msg($msgs, "sur_speciality_msg", "error", "error_ssp");
		if (!$sur["doctor_id"]) $msgs = $this->set_msg($msgs, "sur_doctor_msg", "error", "error_sdo");
		if ($sur["schedule_from"]){
			$sur["schedule_to"] = date("Y-m-d H:i:s", strtotime("+".$sch["duration"]." minutes", strtotime($sur["schedule_from"])));
			$status_ids = array();
			array_push($status_ids, $this->status->code("reserved")->id);
			array_push($status_ids, $this->status->code("confirmed")->id);
			
			//check surgery and surgery available
			$sur_available = $this->general->is_available("surgery", $sur, $status_ids);
			$app_available = $this->general->is_available("appointment", $sur, $status_ids);
			if (!($sur_available and $app_available)) $msgs = $this->set_msg($msgs, "sur_schedule_msg", "error", "error_dna");
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
	
	public function cancel_surgery(){
		$status = false; $type = "error"; $msg = null;
		$surgery = $this->surgery->id($this->input->post("id"));
		if ($surgery){
			if (!$surgery->payment_id){
				if ($this->surgery->update($surgery->id, array("status_id" => $this->status->code("canceled")->id))){
					$status = true;
					$type = "success";
					$msg = $this->lang->line('success_cap');
				}else $msg = $this->lang->line('error_internal');
			}else $msg = $this->lang->line('error_pap');
		}else $msg = $this->lang->line('error_nap');
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msg" => $msg));
	}
	
	public function finish_surgery(){
		$status = false; $type = "error"; $msg = null;
		//pending!! role validation
		
		$data = $this->input->post();
		if ($data["result"]){
			$data["status_id"] = $this->status->code("finished")->id;
			if ($this->general->update("surgery", $data["id"], $data)){
				$status = true;
				$type = "success";
				$msg = $this->lang->line('success_fsu');
			}else $msg = $this->lang->line('error_internal');
		}else $msg = $this->lang->line('error_sre');
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msg" => $msg));
	}

	public function reschedule_surgery(){
		$status = false; $type = "error"; $msg = null; $msgs = array();
		$data = $this->input->post();
		
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
					"doctor_id" => $surgery->doctor_id,
					"schedule_from" => $schedule_from,
					"schedule_to" => date("Y-m-d H:i:s", strtotime("+".$data["duration"]." minutes", strtotime($schedule_from)))
				);
				
				$status_ids = array();
				array_push($status_ids, $this->status->code("reserved")->id);
				array_push($status_ids, $this->status->code("confirmed")->id);
				
				$sur_available = $this->general->is_available("surgery", $sur, $status_ids);
				$app_available = $this->general->is_available("appointment", $sur, $status_ids);
				if (!($sur_available and $app_available)) $msg = $this->lang->line('error_dna');
				else{
					if ($this->surgery->update($sur["id"], $sur)){
						$status = true;
						$type = "success";
						$msg = $this->lang->line('success_rsu');
					}else $msg = $this->lang->line('error_internal');
				}
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
}
