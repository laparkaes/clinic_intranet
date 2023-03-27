<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;

class Appointment extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/Lima');
		$this->lang->load("appointment", "spanish");
		$this->lang->load("system", "spanish");
		$this->load->model('role_model','role');
		$this->load->model('status_model','status');
		$this->load->model('account_model','account');
		$this->load->model('specialty_model','specialty');
		$this->load->model('appointment_model','appointment');
		$this->load->model('patient_file_model','patient_file');
		$this->load->model('sl_option_model','sl_option');
		$this->load->model('examination_model','examination');
		$this->load->model('image_model','image');
		$this->load->model('product_model','product');
		$this->load->model('general_model','general');
	}
	
	private function set_msg($msgs, $dom_id, $type, $msg_code){
		if ($msg_code) array_push($msgs, array("dom_id" => $dom_id, "type" => $type, "msg" => $this->lang->line($msg_code)));
		return $msgs;
	}
	
	public function index(){
		if (!$this->session->userdata('logged_in')) redirect(base_url());
		//PENDING! rol validation
		
		$f_from = $this->input->get("f_from"); if (!$f_from) $f_from = date("Y-m-d", strtotime("-3 months"));
		$f_to = $this->input->get("f_to"); if (!$f_to) $f_to = date("Y-m-d", strtotime("+3 months"));
		$f_status = $this->input->get("f_status");
		
		//getting appointments from today
		$filter = array();
		$filter["schedule_from >="] = $f_from." 00:00:00";
		$filter["schedule_to <="] = $f_to." 23:59:59";
		if ($f_status) $filter["status_id"] = $this->status->code($f_status)->id;
		$appointments = $this->appointment->filter($filter);
		
		$person_ids = array();
		$patient_ids = $this->general->only("appointment", "patient_id", $filter);
		$doctor_ids = $this->general->only("appointment", "doctor_id", $filter);
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
		$status = $this->status->all();
		foreach($status as $item) $status_arr[$item->id] = $item;
		
		$data = array(
			"filter" => array("from" => $f_from, "to" => $f_to, "status" => $f_status),
			"status_arr" => $status_arr,
			"appointments" => $appointments,
			"specialties" => $specialties,
			"specialties_arr" => $specialties_arr,
			"doctors" => $doctors,
			"doctors_arr" => $doctors_arr,
			"doc_types" => $this->general->all("doc_type", "id", "asc"),
			"people_arr" => $people_arr,
			"title" => $this->lang->line('appointments'),
			"main" => "appointment/list",
			"init_js" => "appointment/list.js"
		);
		
		$this->load->view('layout', $data);
	}
	
	private function set_appointment_data($appointment){
		$basic_data = $this->general->filter("appointment_basic_data", array("appointment_id" => $appointment->id));
		if ($basic_data){
			$basic_data = $basic_data[0];
			$basic_data->date = date("Y-m-d", strtotime($basic_data->entered_at));
			$basic_data->time = date("H:i", strtotime($basic_data->entered_at));
		}else{
			$basic_data = $this->general->structure("appointment_basic_data");
			$basic_data->date = date("Y-m-d");
			$basic_data->time = date("H:i");
		}
		$basic_data->date_f = date("d/m/Y", strtotime($basic_data->entered_at));
		$basic_data->time_f = date("H:i a", strtotime($basic_data->entered_at));
		if ($basic_data->entry_mode) $basic_data->entry_mode_txt = $this->sl_option->id($basic_data->entry_mode)->description;
		else $basic_data->entry_mode_txt = "";
		if ($basic_data->insurance) $basic_data->insurance = "y"; else $basic_data->insurance = "n";
		
		$anamnesis = $this->general->filter("appointment_anamnesis", array("appointment_id" => $appointment->id));
		if ($anamnesis) $anamnesis = $anamnesis[0];
		else{
			$patient = $this->general->id("person", $appointment->patient_id);
			if (!$patient) $patient = $this->general->structure("person");
			if ($patient->sex_id) $patient->sex = $this->general->id("sl_option", $patient->sex_id)->description;
			else $patient->sex = null;
			if ($patient->blood_type_id) $patient->blood_type = $this->general->id("sl_option", $patient->blood_type_id)->description;
			else $patient->blood_type = null;
			if ($patient->birthday) $patient->age = $this->utility_lib->age_calculator($patient->birthday, true);
			else $patient->birthday = $patient->age = null;
			
			$anamnesis = $this->general->structure("appointment_anamnesis");
			$anamnesis->name = $anamnesis->responsible = $patient->name;
			$anamnesis->age = $patient->age;
			$anamnesis->birthday = $patient->birthday;
			$anamnesis->sex_id = $patient->sex_id;
			$anamnesis->tel = $patient->tel;
			$anamnesis->address = $patient->address;
			if ($patient->birthday) $anamnesis->birthday = $patient->birthday;
		}
		if ($anamnesis->age) $anamnesis->age_txt = $anamnesis->age." ".$this->lang->line('txt_year_p');
		else $anamnesis->age_txt = "";
		if ($anamnesis->sex_id) $anamnesis->sex_txt = $this->general->id("sl_option", $anamnesis->sex_id)->description;
		else $anamnesis->sex_txt = "";
		if ($anamnesis->birthday) $anamnesis->birthday_f = date("d/m/Y", strtotime($anamnesis->birthday));
		else $anamnesis->birthday_f = "";
		if ($anamnesis->civil_status) $anamnesis->civil_status_txt = $this->sl_option->id($anamnesis->civil_status)->description;
		else $anamnesis->civil_status_txt = "";
		
		$anamnesis->patho_pre_illnesses = explode(",", $anamnesis->patho_pre_illnesses);
		$anamnesis->patho_pre_illnesses_txt = implode(", ", $anamnesis->patho_pre_illnesses);
		if ($anamnesis->patho_pre_illnesses_other) $anamnesis->patho_pre_illnesses_txt = $anamnesis->patho_pre_illnesses_txt.", ".$anamnesis->patho_pre_illnesses_other;
		
		$physical = $this->general->filter("appointment_physical", array("appointment_id" => $appointment->id));
		if ($physical) $physical = $physical[0];
		else $physical = $this->general->structure("appointment_physical");
		
		$diag_ids = array();
		$diags = $this->general->filter("appointment_diag_impression" , array("appointment_id" => $appointment->id));
		foreach($diags as $diag) array_push($diag_ids, $diag->diag_id);
		$diag_impression = $this->general->ids("diag_impression_detail", $diag_ids, "code");
		
		$result = $this->general->filter("appointment_result", array("appointment_id" => $appointment->id));
		if ($result) $result = $result[0];
		else $result = $this->general->structure("appointment_result");
		
		$checked_images = array();
		$images_ap = $this->general->filter("appointment_image", array("appointment_id" => $appointment->id));
		foreach($images_ap as $item){
			$img = $this->image->id($item->image_id);
			$item->category = $this->image->category($img->category_id)->name;
			$item->image = $img->name;
			array_push($checked_images, $item->image_id);
		}
		usort($images_ap, function($a, $b) {
			if (!strcmp($a->category, $b->category)) return strcmp($a->image, $b->image);
			else return strcmp($a->category, $b->category);
		});
		
		$appointment_datas = array(
			"basic_data" => $basic_data,
			"anamnesis" => $anamnesis,
			"physical" => $physical,
			"diag_impression" => $diag_impression,
			"result" => $result,
			"examination" => $this->validate_exam_profile($appointment->id),
			"images" => array("images" => $images_ap, "checked_images" => $checked_images),
			"therapy" => $this->set_therapy_list($appointment->id),
			"medicine" => $this->set_medicine_list($appointment->id)
		);
		
		return $appointment_datas;
	}
	
	public function detail($id){
		if (!$this->session->userdata('logged_in')) redirect(base_url());
		//PENDING! rol validation
		
		$appointment = $this->appointment->id($id);
		if (!$appointment) redirect("/appointment");
		
		$appointment->status = $this->general->id("status", $appointment->status_id);
		$appointment->is_editable = false; $actions = array();
		switch($appointment->status->code){
			case "reserved":
				array_push($actions, "reschedule");
				array_push($actions, "cancel");
				break;
			case "confirmed":
				$appointment->is_editable = true;
				array_push($actions, "reschedule");
				break;
			case "finished":
				array_push($actions, "report");
				break;
			case "canceled": $appointment->status->color = "danger"; $appointment->is_editable = false; break;
		}
		
		$doctor = $this->general->id("person", $appointment->doctor_id);
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
		
		$patient = $this->general->id("person", $appointment->patient_id);
		if ($patient){
			$patient->doc_type = $this->sl_option->id($patient->doc_type_id)->description;
			if ($patient->birthday) $patient->age = $this->utility_lib->age_calculator($patient->birthday, true);
			else $patient->age = null;
			if ($patient->sex_id) $patient->sex = $this->general->id("sl_option", $patient->sex_id)->description;
			else $patient->sex = null;
			if ($patient->blood_type_id) $patient->blood_type = $this->general->id("sl_option", $patient->blood_type_id)->description;
			else $patient->blood_type = null;
		}
		
		//start set history records > mix surgeries and appointments
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
		
		//setting patho_pre_illnesses
		$pre_illnesses = array();
		array_push($pre_illnesses, array("id" => "patho_asma", "value" => $this->lang->line("lb_asthma")));
		array_push($pre_illnesses, array("id" => "patho_hta", "value" => $this->lang->line("lb_aht")));
		array_push($pre_illnesses, array("id" => "patho_dm", "value" => $this->lang->line("lb_dm")));
		array_push($pre_illnesses, array("id" => "patho_f_tifoidea", "value" => $this->lang->line("lb_f_typhoid")));
		array_push($pre_illnesses, array("id" => "patho_f_malta", "value" => $this->lang->line("lb_f_malta")));
		array_push($pre_illnesses, array("id" => "patho_tbc", "value" => $this->lang->line("lb_tbc")));
		array_push($pre_illnesses, array("id" => "patho_contacto_tbc", "value" => $this->lang->line("lb_contact_tbc")));
		array_push($pre_illnesses, array("id" => "patho_etu", "value" => $this->lang->line("lb_etu")));
		
		//examination records
		$examinations_list = array();
		$examinations = $this->examination->all();
		foreach($examinations as $item) $examinations_list[$item->id] = $item;
		
		$exam_profiles = $this->examination->profile_all();
		foreach($exam_profiles as $i => $item){
			$exams_aux = $cate_aux = array();
			$item->examination_ids = explode(",", $item->examination_ids);
			$exams = $this->examination->ids($item->examination_ids);
			foreach($exams as $item){
				array_push($exams_aux, $item->name);
				array_push($cate_aux, $item->category_id);
			}
			
			$exam_profiles[$i]->exams = implode(", ", $exams_aux);
			$exam_profiles[$i]->categories = array_unique($cate_aux);
		}
		
		//image records
		$image_categories = $this->image->category_all();
		foreach($image_categories as $item) $item->images = $this->image->filter(array("category_id" => $item->id));
		
		//load select options
		$codes = array("entry_mode", "civil_status", "medicine_dose", "medicine_via_application", "medicine_frequency", "medicine_duration");
		$options_rec = $this->sl_option->codes($codes);
		
		$options = array();
		foreach($codes as $item) $options[$item] = array();
		foreach($options_rec as $item) array_push($options[$item->code], $item);
		
		$data = array(
			"actions" => $actions,
			"appointment" => $appointment,
			"appointment_datas" => $this->set_appointment_data($appointment),
			"doctor" => $doctor,
			"patient" => $patient,
			"histories" => $histories,
			"patient_files" => $this->patient_file->filter(array("patient_id" => $appointment->patient_id)),
			"pre_illnesses" => $pre_illnesses,
			"options" => $options,
			"exam_profiles" => $exam_profiles,
			"exam_categories" => $this->examination->category_all(),
			"examinations" => $examinations,
			"image_categories" => $image_categories,
			"sex_ops" => $this->general->filter("sl_option", array("code" => "sex")),
			"physical_therapies" => $this->general->all("physical_therapy", "name", "asc"),
			"medicines" => $this->general->all("medicine", "name", "asc"),
			"title" => "Consulta",
			"main" => "appointment/detail",
			"init_js" => "appointment/detail.js"
		);
		
		$this->load->view('layout', $data);
	}
	
	public function register(){
		$status = false; $type = "error"; $msgs = array(); $msg = null; $move_to = null;
		$app = $this->input->post("app"); $app["schedule_from"] = null;
		$sch = $this->input->post("sch");
		$pt = $this->input->post("pt");
		
		//patient validation
		if (!$pt["name"]) $msgs = $this->set_msg($msgs, "aa_pt_name_msg", "error", "error_ena");
		if (!$pt["tel"]) $msgs = $this->set_msg($msgs, "aa_pt_tel_msg", "error", "error_ete");
		if (!$pt["doc_number"]) $msgs = $this->set_msg($msgs, "aa_pt_doc_msg", "error", "error_dnu");
		
		//schedule validation
		if (!$sch["date"]) $msgs = $this->set_msg($msgs, "aa_schedule_msg", "error", "error_sda");
		elseif (!$sch["hour"]) $msgs = $this->set_msg($msgs, "aa_schedule_msg", "error", "error_sho");
		elseif (!$sch["min"]) $msgs = $this->set_msg($msgs, "aa_schedule_msg", "error", "error_smi");
		else $app["schedule_from"] = $sch["date"]." ".$sch["hour"].":".$sch["min"];
		
		//appointment validation
		if (!$app["specialty_id"]) $msgs = $this->set_msg($msgs, "aa_specialty_msg", "error", "error_ssp");
		if (!$app["doctor_id"]) $msgs = $this->set_msg($msgs, "aa_doctor_msg", "error", "error_sdo");
		if ($app["schedule_from"]){
			$app["schedule_to"] = date("Y-m-d H:i:s", strtotime("+14 minutes", strtotime($app["schedule_from"])));
			$status_ids = array();
			array_push($status_ids, $this->status->code("reserved")->id);
			array_push($status_ids, $this->status->code("confirmed")->id);
			
			//check appointment and surgery available
			$sur_available = $this->general->is_available("surgery", $app, $status_ids);
			$app_available = $this->general->is_available("appointment", $app, $status_ids);
			if (!($sur_available and $app_available)) $msgs = $this->set_msg($msgs, "aa_schedule_msg", "error", "error_dna");
		}
		
		if ($msgs) $msg = $this->lang->line('error_occurred'); 
		else{
			//patient = doctor?
			$f = array("doc_type_id" => $pt["doc_type_id"], "doc_number" => $pt["doc_number"]);
			$person = $this->general->filter("person", $f);
			if ($person){
				$person = $person[0];
				if ($app["doctor_id"] == $person->id) $msg = $this->lang->line('error_pdp');
				else $app["patient_id"] = $person->id;
			}else $app["patient_id"] = $this->general->insert("person", $pt);
			
			if (!$msg){
				$now = date('Y-m-d H:i:s', time());
				
				//check if patient exists
				if ($app["patient_id"]) $this->general->update("person", $app["patient_id"], $pt);
				else{
					$pt["registed_at"] = $now;
					$app["patient_id"] = $this->general->insert("person", $pt);
				}
				
				$app["status_id"] = $this->status->code("reserved")->id;
				$app["registed_at"] = $now;
				$appointment_id = $this->appointment->insert($app);
				if ($appointment_id){
					$status = true;
					$type = "success";
					$move_to = base_url()."appointment/detail/".$appointment_id;
					$msg = $this->lang->line('success_rap');
				}else $msg = $this->lang->line('error_internal');	
			}
		}
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msgs" => $msgs, "msg" => $msg, "move_to" => $move_to));
	}
	
	public function cancel_appointment(){
		$status = false; $type = "error"; $msg = null;
		$appointment = $this->appointment->id($this->input->post("id"));
		if ($appointment){
			if (!$appointment->payment_id){
				if ($this->appointment->update($appointment->id, array("status_id" => $this->status->code("canceled")->id))){
					$status = true;
					$type = "success";
					$msg = $this->lang->line('success_cap');
				}else $msg = $this->lang->line('error_internal');
			}else $msg = $this->lang->line('error_pap');
		}else $msg = $this->lang->line('error_nap');
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msg" => $msg));
	}
	
	public function finish_appointment(){
		$status = false; $type = "error"; $msg = null;
		//pending!! role validation
		
		$appointment = $this->appointment->id($this->input->post("id"));
		if ($appointment){
			if ($this->appointment->update($appointment->id, array("status_id" => $this->status->code("finished")->id))){
				$status = true;
				$type = "success";
				$msg = $this->lang->line('success_fap');
			}else $msg = $this->lang->line('error_internal');
		}else $msg = $this->lang->line('error_nap');
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msg" => $msg));
	}

	public function reschedule_appointment(){
		$status = false; $type = "error"; $msg = null; $msgs = array();
		$data = $this->input->post();
		
		if (!$data["hour"]) $msgs = $this->set_msg($msgs, "ra_time_msg", "error", "error_sho");
		elseif (!$data["min"]) $msgs = $this->set_msg($msgs, "ra_time_msg", "error", "error_smi");
		else{
			$appointment = $this->appointment->id($data["id"]);
			if ($appointment){
				$schedule_from = $data["date"]." ".$data["hour"].":".$data["min"];
				$app = array(
					"id" => $appointment->id,
					"doctor_id" => $appointment->doctor_id,
					"schedule_from" => $schedule_from,
					"schedule_to" => date("Y-m-d H:i:s", strtotime("+14 minutes", strtotime($schedule_from)))
				);
				
				$status_ids = array();
				array_push($status_ids, $this->status->code("reserved")->id);
				array_push($status_ids, $this->status->code("confirmed")->id);
				
				//must be updated
				//check appointment and surgery available
				$sur_available = $this->general->is_available("surgery", $app, $status_ids);
				$app_available = $this->general->is_available("appointment", $app, $status_ids, $app["id"]);
				if ($sur_available and $app_available){
					if ($this->appointment->update($app["id"], $app)){
						$status = true;
						$type = "success";
						$msg = $this->lang->line('success_rsp');
					}else $msg = $this->lang->line('error_internal');
				}else $msg = $this->lang->line('error_dna');
			}else $msg = $this->lang->line('error_internal_refresh');
		}
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "type" => $type, "msg" => $msg, "msgs" => $msgs));
	}

	private function save_data($data, $tb_name, $success_msg){
		$data = $this->utility_lib->clean_array($data);
					
		$f = array("appointment_id" => $data["appointment_id"]);
		$app_bd = $this->general->filter($tb_name, $f);
		if ($app_bd) $this->general->update_f($tb_name, $f, $data);
		else $this->general->insert($tb_name, $data);
		
		return $this->lang->line($success_msg);
	}

	public function save_basic_data(){
		$data = $this->input->post();
		$status = false; $msgs = array(); $msg = null;
		
		//data validation
		if (!$data["entry_mode"]) $msgs = $this->set_msg($msgs, "bd_entry_mode_msg", "error", "error_emo");
		if (!$data["date"]) $msgs = $this->set_msg($msgs, "bd_date_msg", "error", "error_sda");
		if (!$data["time"]) $msgs = $this->set_msg($msgs, "bd_time_msg", "error", "error_time");
		
		//insurance validation
		switch($data["insurance"]){
			case "n":
				$data["insurance"] = 0;
				$data["insurance_name"] = "";
				break;
			case "y":
				$data["insurance"] = 1;
				if (!$data["insurance_name"]) $msgs = $this->set_msg($msgs, "bd_insurance_name_msg", "error", "error_ina");
				break;
			default: $msgs = $this->set_msg($msgs, "bd_insurance_msg", "error", "error_ico");
		}
		
		if ($msgs) $msg = $this->lang->line('error_occurred');
		else{
			//status validation
			if ($data["appointment_id"]){
				$appointment = $this->appointment->id($data["appointment_id"]);
				$appointment->status = $this->status->id($appointment->status_id);
				if (!strcmp("confirmed", $appointment->status->code)){
					//set enterance time
					$data["entered_at"] = $data["date"]." ".$data["time"];
					unset($data["date"]);
					unset($data["time"]);
					
					$status = true;
					$msg = $this->save_data($data, "appointment_basic_data", "success_sbd");
				}else $msg = $this->lang->line('error_anc');
			}else $msg = $this->lang->line('error_internal_refresh');
		}
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "msgs" => $msgs, "msg" => $msg));
	}

	public function save_personal_information(){
		$data = $this->input->post();
		$status = false; $msgs = array(); $msg = null;
		
		//status validation
		if ($data["appointment_id"]){
			$appointment = $this->appointment->id($data["appointment_id"]);
			$appointment->status = $this->status->id($appointment->status_id);
			if (!strcmp("confirmed", $appointment->status->code)){
				$status = true;
				$msg = $this->save_data($data, "appointment_anamnesis", "success_spi");
			}else $msg = $this->lang->line('error_anc');
		}else $msg = $this->lang->line('error_internal_refresh');
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "msgs" => $msgs, "msg" => $msg));
	}
	
	public function save_triage(){
		$data = $this->input->post();
		$status = false; $msgs = array(); $msg = null;
		
		//status validation
		if ($data["appointment_id"]){
			$appointment = $this->appointment->id($data["appointment_id"]);
			$appointment->status = $this->status->id($appointment->status_id);
			if (!strcmp("confirmed", $appointment->status->code)){
				$status = true;
				$msg = $this->save_data($data, "appointment_physical", "success_str");
			}else $msg = $this->lang->line('error_anc');
		}else $msg = $this->lang->line('error_internal_refresh');
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "msgs" => $msgs, "msg" => $msg));
	}

	public function save_anamnesis(){
		$data = $this->input->post();
		$status = false; $msgs = array(); $msg = null;
		
		//status validation
		if ($data["appointment_id"]){
			$appointment = $this->appointment->id($data["appointment_id"]);
			$appointment->status = $this->status->id($appointment->status_id);
			if (!strcmp("confirmed", $appointment->status->code)){
				$data["patho_pre_illnesses"] = str_replace(", ",",", implode(",", $data["patho_pre_illnesses"]));
				
				$status = true;
				$msg = $this->save_data($data, "appointment_anamnesis", "success_san");
			}else $msg = $this->lang->line('error_anc');
		}else $msg = $this->lang->line('error_internal_refresh');
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "msgs" => $msgs, "msg" => $msg));
	}
		
	public function save_physical_exam(){
		$data = $this->input->post();
		$status = false; $msgs = array(); $msg = null;
		
		//status validation
		if ($data["appointment_id"]){
			$appointment = $this->appointment->id($data["appointment_id"]);
			$appointment->status = $this->status->id($appointment->status_id);
			if (!strcmp("confirmed", $appointment->status->code)){
				$status = true;
				$msg = $this->save_data($data, "appointment_physical", "success_spe");
			}else $msg = $this->lang->line('error_anc');
		}else $msg = $this->lang->line('error_internal_refresh');
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "msgs" => $msgs, "msg" => $msg));
	}
	
	public function search_diag(){
		$filter = $this->input->post("filter");
		$status = false; $msgs = array(); $diags = null; $qty = 0;
		if ($filter){
			$filter = explode(" ", $filter);
			$diags = $this->general->find("diag_impression_detail", "description", "code", $filter);
			$qty = number_format(count($diags))." ".$this->lang->line('txt_results');
			$status = true;
		}else $msgs = $this->set_msg($msgs, "di_diagnosis_msg", "error", "error_fbl");
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "msgs" => $msgs, "diags" => $diags, "qty" => $qty));
	}
	
	public function add_diag(){
		$data = $this->input->post();
		$status = true; $msg = $this->lang->line('success_adi'); $diags = array();
		
		//appointment status validation
		$appointment = $this->appointment->id($data["appointment_id"]);
		if (in_array($this->status->id($appointment->status_id)->code, array("reserved", "finished", "canceled"))){
			$msg = $this->lang->line('error_nea');
			$status = false;
		}else{
			if (!$this->general->filter("appointment_diag_impression" , $data)){
				if (!$this->general->insert("appointment_diag_impression" , $data)){
					$msg = $this->lang->line('error_internal');
					$status = false;
				}
			}	
		}
		
		$diag_ids = array();
		$diags = $this->general->filter("appointment_diag_impression" , array("appointment_id" => $data["appointment_id"]));
		foreach($diags as $diag) array_push($diag_ids, $diag->diag_id);
		$diags = $this->general->ids("diag_impression_detail", $diag_ids, "code");
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "msg" => $msg, "diags" => $diags));
	}
	
	public function delete_diag(){
		$data = $this->input->post();
		$status = true; $msg = $this->lang->line('success_ddi'); $diags = array();
		
		//appointment status validation
		$appointment = $this->appointment->id($data["appointment_id"]);
		if (in_array($this->status->id($appointment->status_id)->code, array("reserved", "finished", "canceled"))){
			$msg = $this->lang->line('error_nea');
			$status = false;
		}else{
			if (!$this->general->delete("appointment_diag_impression", $data)){
				$msg = $this->lang->line('error_internal');
				$status = false;
			}
		}
		
		$diag_ids = array();
		$diags = $this->general->filter("appointment_diag_impression" , array("appointment_id" => $data["appointment_id"]));
		foreach($diags as $diag) array_push($diag_ids, $diag->diag_id);
		$diags = $this->general->ids("diag_impression_detail", $diag_ids, "code");
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "msg" => $msg, "diags" => $diags));
	}
	
	public function save_result(){
		$data = $this->input->post();
		$status = false; $msgs = array(); $msg = null;
		
		//status validation
		if ($data["appointment_id"]){
			$appointment = $this->appointment->id($data["appointment_id"]);
			$appointment->status = $this->status->id($appointment->status_id);
			if (!strcmp("confirmed", $appointment->status->code)){
				$status = true;
				$msg = $this->save_data($data, "appointment_result", "success_sre");
			}else $msg = $this->lang->line('error_anc');
		}else $msg = $this->lang->line('error_internal_refresh');
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "msgs" => $msgs, "msg" => $msg));
	}
	
	public function control_image(){
		if (!strcmp($this->input->post("checked"), "true")) $checked = true; else $checked = false;
		$data = array(
			"image_id" => $this->input->post("image_id"),
			"appointment_id" => $this->input->post("appointment_id")
		);
		
		$status = true; $msg = null; $tb_name = "appointment_image";
		
		//appointment status validation
		$appointment = $this->appointment->id($data["appointment_id"]);
		if (in_array($this->status->id($appointment->status_id)->code, array("reserved", "finished", "canceled"))){
			$msg = $this->lang->line('error_nea');
			$status = false;
		}else{
			if ($checked){
				if (!$this->general->filter($tb_name, $data))
					if (!$this->general->insert($tb_name, $data)){
						$status = false;
						$msg = $this->lang->line('error_internal');
					}
			}elseif (!$this->general->delete($tb_name, $data)){
				$status = false;
				$msg = $this->lang->line('error_internal');
			}
		}
		
		$checked_images = array();
		$images_ap = $this->general->filter($tb_name, array("appointment_id" => $data["appointment_id"]));
		foreach($images_ap as $item){
			$img = $this->image->id($item->image_id);
			$item->category = $this->image->category($img->category_id)->name;
			$item->image = $img->name;
			array_push($checked_images, $item->image_id);
		}
		usort($images_ap, function($a, $b) {
			if (!strcmp($a->category, $b->category)) return strcmp($a->image, $b->image);
			else return strcmp($a->category, $b->category);
		});
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "images" => $images_ap, "msg" => $msg, "checked_images" => $checked_images));
	}
	
	private function validate_exam_profile($appointment_id){
		$profiles = $examinations = array();
		
		//preparing exam_ids
		$exam_ids = array();
		$exams = $this->general->filter("appointment_examination", array("appointment_id" => $appointment_id), "examination_id", "asc");
		foreach($exams as $item) array_push($exam_ids, $item->examination_id);
		$checked_exams = $exam_ids;
		//profile list
		/*
		having exam_ids
		get all profiles
		order by exam_ids array length
		compare from largest to smallist profile
		  => remove from exams_ids if get a profile
		  => insert to profile_list after get a profile
		*/
		$profiles = array();
		$prof = $this->examination->profile_all();
		foreach($prof as $item) $item->examination_ids = explode(",", $item->examination_ids);
		usort($prof, function($a, $b) {
			if (count($a->examination_ids) < count($b->examination_ids)) return true;
			else return strcmp($a->name, $b->name);
		});
		
		$checked_profs = array();
		foreach($prof as $item){
			$intersect = array_intersect($exam_ids, $item->examination_ids);
			if (count($intersect) == count($item->examination_ids)){
				//setting profile data
				$exams_arr = array();
				$exams = $this->examination->ids($item->examination_ids);
				foreach($exams as $e) array_push($exams_arr, $e->name);
				$item->exams = implode(", ", $exams_arr);
				$item->type = $this->lang->line('txt_profile');
				array_push($profiles, clone $item);
				array_push($checked_profs, $item->id);
				
				//remove from exam_ids
				$exam_ids = array_diff($exam_ids, $item->examination_ids);
			}
		}
		
		//individual examination list after profile validation
		if ($exam_ids){
			$examinations = $this->examination->ids($exam_ids);
			foreach($examinations as $item) $item->type = $this->lang->line('txt_exam');
		}
		
		return array("profiles" => $profiles, "examinations" => $examinations, "checked_profs" => $checked_profs, "checked_exams" => $checked_exams);
	}
	
	public function control_examination(){
		//post data setting
		if (!strcmp($this->input->post("checked"), "true")) $checked = true; else $checked = false;
		$data = array(
			"examination_id" => $this->input->post("id"),
			"appointment_id" => $this->input->post("appointment_id")
		);
		
		$status = true; $msg = null;
		
		//appointment status validation
		$appointment = $this->appointment->id($data["appointment_id"]);
		if (in_array($this->status->id($appointment->status_id)->code, array("reserved", "finished", "canceled"))){
			$msg = $this->lang->line('error_nea');
			$status = false;
		}else{
			$tb_name = "appointment_examination";
			if ($checked){
				if (!$this->general->filter($tb_name, $data))
					if (!$this->general->insert($tb_name, $data)){
						$msg = $this->lang->line('error_internal');
						$status = false;
					}
			}else{
				if (!$this->general->delete($tb_name, $data)){
					$msg = $this->lang->line('error_internal');
					$status = false;
				}
			}
		}
		
		$result = $this->validate_exam_profile($data["appointment_id"]);
		$result["status"] = $status;
		$result["msg"] = $msg;
		
		header('Content-Type: application/json');
		echo json_encode($result);
	}
	
	public function control_examination_profile(){
		//post data setting
		if (!strcmp($this->input->post("checked"), "true")) $checked = true; else $checked = false;
		$appointment_id = $this->input->post("appointment_id");
		
		$status = true; $msg = null;
		
		//appointment status validation
		$appointment = $this->appointment->id($appointment_id);
		if (in_array($this->status->id($appointment->status_id)->code, array("reserved", "finished", "canceled"))){
			$msg = $this->lang->line('error_nea');
			$status = false;
		}else{
			$profile = $this->examination->profile($this->input->post("id"));
			$prof_exam_ids = explode(",", $profile->examination_ids);
			$tb_name = "appointment_examination";
			if ($checked){
				$appo_exam_ids = array();
				$appo_exams = $this->general->filter($tb_name, array("appointment_id" => $appointment_id), "examination_id", "asc");
				foreach($appo_exams as $item) array_push($appo_exam_ids, $item->examination_id);
				
				$datas = array();
				$arr_diff = array_diff($prof_exam_ids, $appo_exam_ids);
				foreach($arr_diff as $id) array_push($datas, array("appointment_id" => $appointment_id, "examination_id" => $id));
				
				if ($datas) if (!$this->general->insert_multi($tb_name, $datas)){
					$status = false;
					$msg = $this->lang->line('error_internal');
				}
			}elseif (!$this->general->delete_multi($tb_name, "examination_id", $prof_exam_ids)){
				$status = false;
				$msg = $this->lang->line('error_internal');
			}
		}
		
		$result = $this->validate_exam_profile($appointment_id);
		$result["status"] = $status;
		$result["msg"] = $msg;
		
		header('Content-Type: application/json');
		echo json_encode($result);
	}
	
	private function set_therapy_list($appointment_id){
		$tb_name = "appointment_therapy";
		$therapies = $this->general->filter($tb_name, array("appointment_id" => $appointment_id));
		foreach($therapies as $item){
			$item->physical_therapy = $this->general->id("physical_therapy", $item->physical_therapy_id)->name;
			
			if ($item->session > 1) $session_txt = $this->lang->line('txt_session_p');
			else $session_txt = $this->lang->line('txt_session');
			$session_txt = $item->session." ".$session_txt;
			
			$unit_text = "txt_";
			switch($item->frequency_unit){
				case "D": $unit_text = $unit_text."day"; break;
				case "W": $unit_text = $unit_text."week"; break;
				case "M": $unit_text = $unit_text."month"; break;
				case "Y": $unit_text = $unit_text."year"; break;
			}
			if ($item->frequency > 1) $unit_text = $unit_text."_p";
			$frequency_txt = $item->frequency." ".$this->lang->line($unit_text);
			$item->sub_txt = $session_txt.", ".$this->lang->line('txt_one_session_each')." ".$frequency_txt;
		}
		usort($therapies, function($a, $b) {
			return strcmp($a->physical_therapy, $b->physical_therapy);
		});
		
		return $therapies;
	}
	
	public function add_therapy(){
		$data = $this->input->post();
		$status = true; $msgs = array(); $msg = null;
		
		//appointment status validation
		$appointment = $this->appointment->id($data["appointment_id"]);
		if (in_array($this->status->id($appointment->status_id)->code, array("reserved", "finished", "canceled"))){
			$msg = $this->lang->line('error_nea');
			$status = false;
		}else{
			if (!$data["physical_therapy_id"]) $msgs = $this->set_msg($msgs, "at_physical_therapy_msg", "error", "error_sth");
			if ($data["session"] < 1) $msgs = $this->set_msg($msgs, "at_session_msg", "error", "error_mse");
			if ($data["frequency"] < 1) $msgs = $this->set_msg($msgs, "at_frequency_msg", "error", "error_nfr");
			
			if ($msgs) $status = false;
			else{
				$tb_name = "appointment_therapy";
				$filter = array(
					"appointment_id" => $data["appointment_id"],
					"physical_therapy_id" => $data["physical_therapy_id"]
				);
				$pt_exist = $this->general->filter($tb_name, $filter);
				if ($pt_exist){
					if (!$this->general->update($tb_name, $pt_exist[0]->id, $data)){
						$status = false;
						$msg = $this->lang->line('error_internal');
					}
				}else{
					if (!$this->general->insert($tb_name, $data)){
						$status = false;
						$msg = $this->lang->line('error_internal');
					}
				}
			}
		}
		
		$therapies = $this->set_therapy_list($data["appointment_id"]);
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "msgs" => $msgs, "msg" => $msg, "therapies" => $therapies));
	}
	
	public function delete_therapy(){
		$data = $this->input->post();
		$status = true; $msg = null;
		
		//appointment status validation
		$appointment = $this->appointment->id($data["appointment_id"]);
		if (in_array($this->status->id($appointment->status_id)->code, array("reserved", "finished", "canceled"))){
			$msg = $this->lang->line('error_nea');
			$status = false;
		}else{
			if (!$this->general->delete("appointment_therapy", $data)){
				$status = false;
				$msg = $this->lang->line('error_internal');
			}
		}
		
		$therapies = $this->set_therapy_list($data["appointment_id"]);
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "msg" => $msg, "therapies" => $therapies));
	}
	
	private function set_medicine_list($appointment_id){
		$medicines = $this->general->filter("appointment_medicine", array("appointment_id" => $appointment_id));
		foreach($medicines as $item){
			$sub_txt_arr = array();
			if ($item->quantity > 1) $qty = $item->quantity." ".$this->lang->line('txt_units');
			else $qty = $item->quantity." ".$this->lang->line('txt_unit');
			array_push($sub_txt_arr, $qty);
			if ($item->dose) array_push($sub_txt_arr, $this->sl_option->id($item->dose)->description);
			if ($item->via_application) array_push($sub_txt_arr, $this->sl_option->id($item->via_application)->description);
			if ($item->frequency) array_push($sub_txt_arr, $this->sl_option->id($item->frequency)->description);
			if ($item->duration) array_push($sub_txt_arr, $this->sl_option->id($item->duration)->description);
			
			$item->medicine = $this->general->id("medicine", $item->medicine_id)->name;
			$item->sub_txt = implode(", ", $sub_txt_arr);
		}
		usort($medicines, function($a, $b) { return strcmp($a->medicine, $b->medicine); });
		
		return $medicines;
	}
	
	public function add_medicine(){
		$status = true; $msgs = array(); $msg = null;
		$data = $this->input->post();
		
		//appointment status validation
		$appointment = $this->appointment->id($data["appointment_id"]);
		if (in_array($this->status->id($appointment->status_id)->code, array("reserved", "finished", "canceled"))){
			$msg = $this->lang->line('error_nea');
			$status = false;
		}else{
			if (!$data["medicine_id"]) $msgs = $this->set_msg($msgs, "md_medicine_msg", "error", "error_sme");
			if ($data["quantity"]){
				if (is_numeric($data["quantity"])){
					if ($data["quantity"] < 1) $msgs = $this->set_msg($msgs, "md_quantity_msg", "error", "error_nmq");
				}else $msgs = $this->set_msg($msgs, "md_quantity_msg", "error", "error_inu");
			}else $msgs = $this->set_msg($msgs, "md_quantity_msg", "error", "error_imq");
				
			if ($msgs) $status = false;
			else{
				$tb_name = "appointment_medicine";
				$filter = array("appointment_id" => $data["appointment_id"], "medicine_id" => $data["medicine_id"]);
				$me_exist = $this->general->filter($tb_name, $filter);
				//print_r($me_exist);
				if ($me_exist){
					if (!$this->general->update($tb_name, $me_exist[0]->id, $data)){
						$status = false;
						$msg = $this->lang->line('error_internal');
					}
				}else{
					if (!$this->general->insert($tb_name, $data)){
						$status = false;
						$msg = $this->lang->line('error_internal');
					}
				}
			}	
		}
		
		$medicines = $this->set_medicine_list($data["appointment_id"]);
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "msgs" => $msgs, "msg" => $msg, "medicines" => $medicines));
	}
	
	public function delete_medicine(){
		$data = $this->input->post();
		$status = true; $msg = null;
		
		//appointment status validation
		$appointment = $this->appointment->id($data["appointment_id"]);
		if (in_array($this->status->id($appointment->status_id)->code, array("reserved", "finished", "canceled"))){
			$msg = $this->lang->line('error_nea');
			$status = false;
		}else{
			$tb_name = "appointment_medicine";
			if (!$this->general->delete($tb_name, $data)){
				$msg = $this->lang->line('error_internal');
				$status = false;
			}
		}
					
		$medicines = $this->set_medicine_list($data["appointment_id"]);
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "msg" => $msg, "medicines" => $medicines));
	}
	
	public function report($id){
		$appointment = $this->appointment->id($id);
		if (!$appointment) redirect("/appointment");
		
		$doctor = $this->general->id("person", $appointment->doctor_id);
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
		
		$patient = $this->general->id("person", $appointment->patient_id);
		if ($patient){
			$patient->doc_type = $this->sl_option->id($patient->doc_type_id)->description;
			if ($patient->birthday) $patient->age = $this->utility_lib->age_calculator($patient->birthday, true);
			else $patient->birthday = $patient->age = null;	
		}else $patient = $this->general->structure("person");
		
		$appointment_datas = $this->set_appointment_data($appointment);
		
		$data = array(
			"appointment" => $appointment,
			"appointment_datas" => $appointment_datas,
			"doctor" => $doctor,
			"patient" => $patient
		);
		
		//$html = $this->load->view('appointment/report_1', $data, true);
		$html = $this->load->view('appointment/report_2', $data, true);
		
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
