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
		$this->load->model('examination_model','examination');
		$this->load->model('image_model','image');
		$this->load->model('product_model','product');
		$this->load->model('general_model','general');
		$this->nav_menu = "appointment";
	}
	
	public function index(){
		if (!$this->session->userdata('logged_in')) redirect(base_url());
		if (!$this->utility_lib->check_access("appointment", "index")) redirect("/errors/no_permission");
		
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
		
		$appointments = $this->general->filter("appointment", $f_w, null, null, "schedule_from", "desc", 25, 25*($f_url["page"]-1));
		foreach($appointments as $item){
			$item->patient = $this->general->id("person", $item->patient_id)->name;
			$item->doctor = $this->general->id("person", $item->doctor_id)->name;
			$item->specialty = $this->general->id("specialty", $item->specialty_id)->name;
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
		$status_ids = $this->general->only("appointment", "status_id");
		foreach($status_ids as $item) $status_aux[] = $item->status_id;
		
		$f_status = [["field" => "id", "values" => $status_aux]];
		
		$status_arr = [];
		$status = $this->general->filter("status", null, null, [["field" => "id", "values" => $status_aux]]);
		foreach($status as $item) $status_arr[$item->id] = $item;
		
		$data = [
			"paging" => $this->my_func->set_page($f_url["page"], $this->general->counter("appointment", $f_w)),
			"f_url" => $f_url,
			"status" => $status,
			"status_arr" => $status_arr,
			"appointments" => $appointments,
			"specialties" => $specialties,
			"doctors" => $doctors,
			"doc_types" => $this->general->all("doc_type", "id", "asc"),
			"title" => $this->lang->line('appointments'),
			"main" => "appointment/list",
			"init_js" => "appointment/list.js"
		];
		
		$this->load->view('layout', $data);
	}
	
	private function set_appointment_data($appointment){
		$basic_data = $this->general->filter("appointment_basic_data", ["appointment_id" => $appointment->id]);
		if ($basic_data){
			$basic_data = $basic_data[0];
			$basic_data->date = date("Y-m-d", strtotime($basic_data->entered_at));
			$basic_data->time = date("H:i", strtotime($basic_data->entered_at));
		}else{
			$basic_data = $this->general->structure("appointment_basic_data");
			$basic_data->date = date("Y-m-d");
			$basic_data->time = date("H:i");
		}
		if ($basic_data->entry_mode_id)
			$basic_data->entry_mode = $this->general->id("entry_mode", $basic_data->entry_mode_id)->description;
		else $basic_data->entry_mode = "";
		
		$anamnesis = $this->general->filter("appointment_anamnesis", ["appointment_id" => $appointment->id]);
		if ($anamnesis) $anamnesis = $anamnesis[0];
		else{
			$patient = $this->general->id("person", $appointment->patient_id);
			if (!$patient) $patient = $this->general->structure("person");
				
			if ($patient->sex_id) $patient->sex = $this->general->id("sex", $patient->sex_id)->description;
			else $patient->sex = null;
			if ($patient->blood_type_id) $patient->blood_type = $this->general->id("blood_type", $patient->blood_type_id)->description;
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
		if ($anamnesis->age) $anamnesis->age = $anamnesis->age." ".$this->lang->line('txt_year_p');
		else $anamnesis->age = null;
		
		if ($anamnesis->sex_id) $anamnesis->sex = $this->general->id("sex", $anamnesis->sex_id)->description;
		else $anamnesis->sex = null;
		
		if ($anamnesis->birthday) $anamnesis->birthday = date("Y-m-d", strtotime($anamnesis->birthday));
		else $anamnesis->birthday = null;
		
		if ($anamnesis->civil_status_id)
			$anamnesis->civil_status = $this->general->id("civil_status", $anamnesis->civil_status_id)->description;
		else $anamnesis->civil_status = null;
		
		$anamnesis->patho_pre_illnesses = explode(",", $anamnesis->patho_pre_illnesses);
		$anamnesis->patho_pre_illnesses_txt = implode(", ", $anamnesis->patho_pre_illnesses);
		if ($anamnesis->patho_pre_illnesses_other) $anamnesis->patho_pre_illnesses_txt = $anamnesis->patho_pre_illnesses_txt.", ".$anamnesis->patho_pre_illnesses_other;
		
		$physical = $this->general->filter("appointment_physical", ["appointment_id" => $appointment->id]);
		if ($physical) $physical = $physical[0];
		else $physical = $this->general->structure("appointment_physical");
		
		$diag_ids = [];
		$diags = $this->general->filter("appointment_diag_impression" , ["appointment_id" => $appointment->id]);
		foreach($diags as $diag) array_push($diag_ids, $diag->diag_id);
		$diag_impression = $this->general->ids("diag_impression_detail", $diag_ids, "code");
		
		$result = $this->general->filter("appointment_result", ["appointment_id" => $appointment->id]);
		if ($result) $result = $result[0];
		else $result = $this->general->structure("appointment_result");
		
		$appointment_datas = array(
			"basic_data" => $basic_data,
			"anamnesis" => $anamnesis,
			"physical" => $physical,
			"diag_impression" => $diag_impression,
			"result" => $result,
			"examination" => $this->set_profiles_exams($appointment->id),
			"images" => $this->set_images($appointment->id),
			"therapy" => $this->set_therapy_list($appointment->id),
			"medicine" => $this->set_medicine_list($appointment->id)
		);
		
		return $appointment_datas;
	}
	
	public function detail($id){
		if (!$this->session->userdata('logged_in')) redirect(base_url());
		if (!$this->utility_lib->check_access("appointment", "detail")) redirect("/errors/no_permission");
		
		$appointment = $this->appointment->id($id);
		if (!$appointment) redirect("/appointment");
		
		$appointment->status = $this->general->id("status", $appointment->status_id);
		$appointment->is_editable = false; $actions = [];
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
		
		$appointment->detail = null;
		$appointment_sale = $this->general->filter("sale_product", ["appointment_id" => $appointment->id]);
		if ($appointment_sale){
			$product = $this->general->id("product", $appointment_sale[0]->product_id);
			$category = $this->general->id("product_category", $product->category_id)->name;
			
			$str = $category.", ".$product->description;
			if (strpos($str, "Consulta") !== false) $appointment->detail = $str;
		}
		
		$doctor = $this->general->id("person", $appointment->doctor_id);
		if ($doctor){
			$data = $this->general->filter("doctor", ["person_id" => $doctor->id]);
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
			$patient->doc_type = $this->general->id("doc_type", $patient->doc_type_id)->description;
			if ($patient->birthday) $patient->age = $this->utility_lib->age_calculator($patient->birthday, true);
			else $patient->age = null;
			if ($patient->sex_id) $patient->sex = $this->general->id("sex", $patient->sex_id)->description;
			else $patient->sex = null;
			if ($patient->blood_type_id) $patient->blood_type = $this->general->id("blood_type", $patient->blood_type_id)->description;
			else $patient->blood_type = null;
		}
		
		//start set history records > mix surgeries and appointments
		$specialties = [];
		$specialties_rec = $this->general->all("specialty");
		foreach($specialties_rec as $item) $specialties[$item->id] = $item->name;
		
		$filter = array("patient_id" => $patient->id, "status_id" => $this->status->code("finished")->id);
		
		$surgery_histories = $this->general->filter("surgery", $filter);
		foreach($surgery_histories as $item){
			$d = $this->general->filter("doctor", ["person_id" => $doctor->id])[0];
			$item->specialty = $specialties[$d->specialty_id];
			$item->link_to = "surgery";
			$item->type = $this->lang->line($item->link_to);
		}
		
		$appointment_histories = $this->general->filter("appointment", $filter);
		foreach($appointment_histories as $item){
			$d = $this->general->filter("doctor", ["person_id" => $doctor->id])[0];
			$item->specialty = $specialties[$d->specialty_id];
			$item->link_to = "appointment";
			$item->type = $this->lang->line($item->link_to);
		}
		
		$histories = array_merge($surgery_histories, $appointment_histories);
		usort($histories, function($a, $b) { return ($a->schedule_from < $b->schedule_from); });
		//end set history records
		
		//setting patho_pre_illnesses
		$pre_illnesses = [];
		array_push($pre_illnesses, array("id" => "patho_asma", "value" => $this->lang->line("lb_asthma")));
		array_push($pre_illnesses, array("id" => "patho_hta", "value" => $this->lang->line("lb_aht")));
		array_push($pre_illnesses, array("id" => "patho_dm", "value" => $this->lang->line("lb_dm")));
		array_push($pre_illnesses, array("id" => "patho_f_tifoidea", "value" => $this->lang->line("lb_f_typhoid")));
		array_push($pre_illnesses, array("id" => "patho_f_malta", "value" => $this->lang->line("lb_f_malta")));
		array_push($pre_illnesses, array("id" => "patho_tbc", "value" => $this->lang->line("lb_tbc")));
		array_push($pre_illnesses, array("id" => "patho_contacto_tbc", "value" => $this->lang->line("lb_contact_tbc")));
		array_push($pre_illnesses, array("id" => "patho_etu", "value" => $this->lang->line("lb_etu")));
		
		//examination records
		$examinations_list = [];
		$examinations = $this->examination->all();
		foreach($examinations as $item) $examinations_list[$item->id] = $item;
		
		$exam_profiles = $this->examination->profile_all();
		foreach($exam_profiles as $i => $item){
			$exams_aux = $cate_aux = [];
			$item->examination_ids = explode(",", $item->examination_ids);
			$exams = $this->examination->ids($item->examination_ids);
			foreach($exams as $item){
				array_push($exams_aux, $item->name);
				array_push($cate_aux, $item->category_id);
			}
			
			$exam_profiles[$i]->exams = implode(", ", $exams_aux);
			$exam_profiles[$i]->categories = array_unique($cate_aux);
		}
		
		//load select options
		$options = [
			"entry_mode" => $this->general->all("entry_mode", "description", "asc"), 
			"civil_status" => $this->general->all("civil_status", "description", "asc"), 
			"medicine_dose" => $this->general->all("medicine_dose", "description", "asc"), 
			"medicine_application_way" => $this->general->all("medicine_application_way", "description", "asc"), 
			"medicine_frequency" => $this->general->all("medicine_frequency", "description", "asc"), 
			"medicine_duration" => $this->general->all("medicine_duration", "description", "asc"), 
			"diagnosis_type" => $this->general->all("diagnosis_type", "description", "asc"),
		];
		
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
			"aux_image_categories" => $this->general->all("image_category", "name", "asc"),
			"aux_images" => $this->general->all("image", "name", "asc"),
			"sex_ops" => $this->general->all("sex", "description", "asc"),
			"physical_therapies" => $this->general->all("physical_therapy", "name", "asc"),
			"medicines" => $this->general->all("medicine", "name", "asc"),
			"title" => "Consulta",
			"main" => "appointment/detail",
			"init_js" => "appointment/detail.js"
		);
		
		$this->load->view('layout', $data);
	}
	
	public function register(){
		$type = "error"; $msgs = []; $msg = null; $move_to = null;
		 	
		if ($this->utility_lib->check_access("appointment", "register")){
			$app = $this->input->post("app");
			$sch = $this->input->post("sch");
			$pt = $this->input->post("pt");
			
			$this->load->library('my_val');
			$msgs = $this->my_val->appointment($msgs, "aa_", $app, $sch, $pt);
			
			if (!$msgs){
				$now = date('Y-m-d H:i:s', time());
				$person = $this->general->filter("person", ["doc_type_id" => $pt["doc_type_id"], "doc_number" => $pt["doc_number"]]);
				if ($person){
					$person = $person[0];
					$app["patient_id"] = $person->id;
					$this->general->update("person", $person->id, $pt);
					$this->utility_lib->add_log("person_update", $person->name);
				}else{
					$app["patient_id"] = $this->general->insert("person", $pt);
					$person = $this->general->id("person", $app["patient_id"]);
					$this->utility_lib->add_log("person_register", $person->name);
				}
				
				$app["schedule_from"] = $sch["date"]." ".$sch["hour"].":".$sch["min"];
				$app["schedule_to"] = date("Y-m-d H:i:s", strtotime("+14 minutes", strtotime($app["schedule_from"])));
				$app["status_id"] = $this->status->code("reserved")->id;
				$app["registed_at"] = $now;
				$appointment_id = $this->appointment->insert($app);
				if ($appointment_id){
					$this->utility_lib->add_log("appointment_register", $pt["name"]);
					
					$type = "success";
					$move_to = base_url()."appointment/detail/".$appointment_id;
					$msg = $this->lang->line('success_rap');
				}else $msg = $this->lang->line('error_internal');
			}else $msg = $this->lang->line('error_occurred');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msgs" => $msgs, "msg" => $msg, "move_to" => $move_to]);
	}
	
	public function cancel(){
		$type = "error"; $msg = null;
		
		if ($this->utility_lib->check_access("appointment", "update")){
			$appointment = $this->general->id("appointment", $this->input->post("id"));
			if ($appointment){
				if ($this->general->update("appointment", $appointment->id, ["status_id" => $this->status->code("canceled")->id])){
					$person = $this->general->id("person", $appointment->patient_id);
					$this->utility_lib->add_log("appointment_cancel", $person->name);
					
					$type = "success";
					$msg = $this->lang->line('success_cap');
				}else $msg = $this->lang->line('error_internal');
			}else $msg = $this->lang->line('error_nap');
		}else $msg = $this->lang->line('error_no_permission');
				
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg]);
	}
	
	public function finish(){
		$type = "error"; $msg = null;
		
		if ($this->utility_lib->check_access("appointment", "update_medical_attention")){
			$appointment = $this->general->id("appointment", $this->input->post("id"));
			if ($appointment){
				if ($this->general->update("appointment", $appointment->id, ["status_id" => $this->status->code("finished")->id])){
					$person = $this->general->id("person", $appointment->patient_id);
					$this->utility_lib->add_log("appointment_finish", $person->name);
					
					$type = "success";
					$msg = $this->lang->line('success_fap');
				}else $msg = $this->lang->line('error_internal');
			}else $msg = $this->lang->line('error_nap');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg]);
	}

	public function reschedule(){
		$type = "error"; $msg = null; $msgs = [];
		
		if ($this->utility_lib->check_access("appointment", "update")){			
			$data = $this->input->post();
			$appointment = $this->general->id("appointment", $data["id"]);
			
			if($appointment){
				$this->load->library('my_val');
				$msgs = $this->my_val->appointment_reschedule($msgs, "ra_", $appointment, $data);
				
				if (!$msgs){
					$schedule_from = $data["date"]." ".$data["hour"].":".$data["min"];
					$app = [
						"id" => $appointment->id,
						"schedule_from" => $schedule_from,
						"schedule_to" => date("Y-m-d H:i:s", strtotime("+14 minutes", strtotime($schedule_from)))
					];
					
					if ($this->appointment->update($app["id"], $app)){
						$person = $this->general->id("person", $appointment->patient_id);
						$this->utility_lib->add_log("appointment_reschedule", $person->name);
						
						$type = "success";
						$msg = $this->lang->line('success_rsp');
					}else $msg = $this->lang->line('error_internal');
				}else $msg = $this->lang->line('error_occurred');
			}else $msg = $this->lang->line('error_internal_refresh');
		}else $msg = $this->lang->line('error_no_permission');

		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg, "msgs" => $msgs]);
	}

	private function save_data($data, $tb_name, $success_msg){
		foreach($data as $i => $val) if (!$val) $data[$i] = null;
					
		$f = array("appointment_id" => $data["appointment_id"]);
		$app_bd = $this->general->filter($tb_name, $f);
		if ($app_bd) $this->general->update_f($tb_name, $f, $data);
		else $this->general->insert($tb_name, $data);
		
		return $this->lang->line($success_msg);
	}

	public function save_basic_data(){
		$type = "error"; $msgs = []; $msg = null;
		
		if ($this->utility_lib->check_access("appointment", "update")){
			$data = $this->input->post();
			
			$this->load->library('my_val');
			$msgs = $this->my_val->appointment_basic_data($msgs, $data);
			
			if (!$msgs){
				if (!$data["insurance"]) $data["insurance_name"] = null;
				
				//status validation
				if ($data["appointment_id"]){
					$appointment = $this->appointment->id($data["appointment_id"]);
					$appointment->status = $this->status->id($appointment->status_id);
					if (!strcmp("confirmed", $appointment->status->code)){
						//set enterance time
						$data["entered_at"] = $data["date"]." ".$data["time"];
						unset($data["date"]);
						unset($data["time"]);
						
						$type = "success";
						$msg = $this->save_data($data, "appointment_basic_data", "success_sbd");
					}else $msg = $this->lang->line('error_anc');
				}else $msg = $this->lang->line('error_internal_refresh');
			}else $msg = $this->lang->line('error_occurred');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msgs" => $msgs, "msg" => $msg]);
	}

	public function save_personal_information(){
		$type = "error"; $msgs = []; $msg = null;
		
		if ($this->utility_lib->check_access("appointment", "update")){
			$data = $this->input->post();
			
			//status validation
			if ($data["appointment_id"]){
				$appointment = $this->appointment->id($data["appointment_id"]);
				$appointment->status = $this->status->id($appointment->status_id);
				if (!strcmp("confirmed", $appointment->status->code)){
					$type = "success";
					$msg = $this->save_data($data, "appointment_anamnesis", "success_spi");
				}else $msg = $this->lang->line('error_anc');
			}else $msg = $this->lang->line('error_internal_refresh');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msgs" => $msgs, "msg" => $msg]);
	}
	
	public function save_triage(){
		$type = "error"; $msgs = []; $msg = null;
		
		if ($this->utility_lib->check_access("appointment", "update_medical_attention")){
			$data = $this->input->post();
			
			//status validation
			if ($data["appointment_id"]){
				$appointment = $this->appointment->id($data["appointment_id"]);
				$appointment->status = $this->status->id($appointment->status_id);
				if (!strcmp("confirmed", $appointment->status->code)){
					$type = "success";
					$msg = $this->save_data($data, "appointment_physical", "success_str");
				}else $msg = $this->lang->line('error_anc');
			}else $msg = $this->lang->line('error_internal_refresh');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msgs" => $msgs, "msg" => $msg]);
	}

	public function save_anamnesis(){
		$type = "error"; $msgs = []; $msg = null;
		
		if ($this->utility_lib->check_access("appointment", "update_medical_attention")){			
			$data = $this->input->post();
			
			//status validation
			if ($data["appointment_id"]){
				$appointment = $this->appointment->id($data["appointment_id"]);
				$appointment->status = $this->status->id($appointment->status_id);
				if (!strcmp("confirmed", $appointment->status->code)){
					if (array_key_exists("patho_pre_illnesses", $data))
						$data["patho_pre_illnesses"] = str_replace(", ",",", implode(",", $data["patho_pre_illnesses"]));
					else $data["patho_pre_illnesses"] = null;
					
					$type = "success";
					$msg = $this->save_data($data, "appointment_anamnesis", "success_san");
				}else $msg = $this->lang->line('error_anc');
			}else $msg = $this->lang->line('error_internal_refresh');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msgs" => $msgs, "msg" => $msg]);
	}
		
	public function save_physical_exam(){
		$type = "error"; $msgs = []; $msg = null;
		
		if ($this->utility_lib->check_access("appointment", "update_medical_attention")){			
			$data = $this->input->post();
			
			//status validation
			if ($data["appointment_id"]){
				$appointment = $this->appointment->id($data["appointment_id"]);
				$appointment->status = $this->status->id($appointment->status_id);
				if (!strcmp("confirmed", $appointment->status->code)){
					$type = "success";
					$msg = $this->save_data($data, "appointment_physical", "success_spe");
				}else $msg = $this->lang->line('error_anc');
			}else $msg = $this->lang->line('error_internal_refresh');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msgs" => $msgs, "msg" => $msg]);
	}
	
	public function search_diag(){
		$type = "error"; $msgs = []; $diags = null; $qty = 0;
		$filter = $this->input->post("filter");
		
		if ($filter){
			$filter = explode(" ", $filter);
			$diags = $this->general->find("diag_impression_detail", "description", "code", $filter);
			$qty = number_format(count($diags))." ".$this->lang->line('txt_results');
			
			$type = "success"; 
		}else $msgs = $this->my_val->set_msg($msgs, "di_diagnosis_msg", "error", "error_fbl");
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msgs" => $msgs, "diags" => $diags, "qty" => $qty]);
	}
	
	public function add_diag(){
		$type = "success"; $msg = $this->lang->line('success_adi'); $diags = [];
		
		if ($this->utility_lib->check_access("appointment", "update_medical_attention")){			
			$data = $this->input->post();
			
			//appointment status validation
			$appointment = $this->appointment->id($data["appointment_id"]);
			if (in_array($this->status->id($appointment->status_id)->code, ["reserved", "finished", "canceled"])){
				$msg = $this->lang->line('error_nea');
				$type = "error";
			}else{
				if (!$this->general->filter("appointment_diag_impression" , $data)){
					if (!$this->general->insert("appointment_diag_impression" , $data)){
						$msg = $this->lang->line('error_internal');
						$type = "error";
					}
				}	
			}
		}else{
			$msg = $this->lang->line('error_no_permission');
			$type = "error";
		}
		
		$diag_ids = [];
		$diags = $this->general->filter("appointment_diag_impression" , ["appointment_id" => $data["appointment_id"]]);
		foreach($diags as $diag) array_push($diag_ids, $diag->diag_id);
		$diags = $this->general->ids("diag_impression_detail", $diag_ids, "code");
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg, "diags" => $diags]);
	}
	
	public function delete_diag(){
		$type = "success"; $msg = $this->lang->line('success_ddi'); $diags = [];
		
		if ($this->utility_lib->check_access("appointment", "update_medical_attention")){			
			$data = $this->input->post();
			
			//appointment status validation
			$appointment = $this->appointment->id($data["appointment_id"]);
			if (in_array($this->status->id($appointment->status_id)->code, ["reserved", "finished", "canceled"])){
				$msg = $this->lang->line('error_nea');
				$type = "error";
			}else{
				if (!$this->general->delete("appointment_diag_impression", $data)){
					$msg = $this->lang->line('error_internal');
					$type = "error";
				}
			}
		}else{
			$msg = $this->lang->line('error_no_permission');
			$type = "error";
		}
		
		$diag_ids = [];
		$diags = $this->general->filter("appointment_diag_impression" , ["appointment_id" => $data["appointment_id"]]);
		foreach($diags as $diag) array_push($diag_ids, $diag->diag_id);
		$diags = $this->general->ids("diag_impression_detail", $diag_ids, "code");
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg, "diags" => $diags]);
	}
	
	public function save_result(){
		$type = "error"; $msgs = []; $msg = null;
		
		if ($this->utility_lib->check_access("appointment", "update_medical_attention")){			
			$data = $this->input->post();
			
			//status validation
			if ($data["appointment_id"]){
				$appointment = $this->appointment->id($data["appointment_id"]);
				$appointment->status = $this->status->id($appointment->status_id);
				if (!strcmp("confirmed", $appointment->status->code)){
					$type = "success";
					$msg = $this->save_data($data, "appointment_result", "success_sre");
				}else $msg = $this->lang->line('error_anc');
			}else $msg = $this->lang->line('error_internal_refresh');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msgs" => $msgs, "msg" => $msg]);
	}
	
	public function set_images($app_id){
		$images = $this->general->filter("appointment_image", ["appointment_id" => $app_id]);
		foreach($images as $item){
			$img = $this->general->id("image", $item->image_id);
			$img_category = $this->general->id("image_category", $img->category_id);
			$item->category = $img_category->name;
			$item->category_id = $img->category_id;
			$item->name = $img->name;
		}
		
		usort($images, function($a, $b) {
			if ($a->category_id == $b->category_id) return strcmp($a->category, $b->category);
			else return strcmp($a->name, $b->name);
		});
		
		return $images;
	}
	
	public function add_image(){
		$type = "error"; $msg = null;
		
		if ($this->utility_lib->check_access("appointment", "update_medical_attention")){			
			$data = $this->input->post();
			if (!$this->general->filter("appointment_image", $data)){
				if ($this->general->insert("appointment_image", $data)){
					$type = "success";
					$msg = $this->lang->line('success_aim');
				}else $msg = $this->lang->line('error_internal');
			}else $msg = $this->lang->line('error_dim');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg, "images" => $this->set_images($data["appointment_id"])]);
	}
	
	public function remove_image(){
		$type = "error"; $msg = null;
		
		if ($this->utility_lib->check_access("appointment", "update_medical_attention")){			
			$data = $this->input->post();
			
			if ($this->general->delete("appointment_image", $data)){
				$type = "success";
				$msg = $this->lang->line('success_rim');
			}else $msg = $this->lang->line('error_internal');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg, "images" => $this->set_images($data["appointment_id"])]);
	}
	
	private function set_profiles_exams($app_id){
		$pr_ids = $ex_ids = [];
		$app_exams = $this->general->filter("appointment_examination", ["appointment_id" => $app_id]);
		foreach($app_exams as $item){
			if ($item->profile_id) $pr_ids[] = $item->profile_id;
			if ($item->examination_id) $ex_ids[] = $item->examination_id;
		}
		
		if ($pr_ids){
			$profiles = $this->general->filter("examination_profile", null, null, [["field" => "id", "values" => $pr_ids]], "name", "asc");
			foreach($profiles as $item){
				$aux_ex_arr = [];
				$aux_ex_ids = explode(",", $item->examination_ids);
				$aux_exams = $this->general->filter("examination", null, null, [["field" => "id", "values" => $aux_ex_ids]]);
				foreach($aux_exams as $e) $aux_ex_arr[] = $e->name;
				
				$item->type = $this->lang->line('txt_profile');
				$item->exams = implode(", ", $aux_ex_arr);
			}	
		}else $profiles = [];
		
		if ($ex_ids){
			$exams = $this->general->filter("examination", null, null, [["field" => "id", "values" => $ex_ids]], "name", "asc");
			foreach($exams as $item) $item->type = $this->lang->line('txt_exam');	
		}else $exams = [];
		
		return ["profiles" => $profiles, "exams" => $exams];
	}
	
	public function add_exam_profile(){
		$type = "error"; $msg = null;
		
		if ($this->utility_lib->check_access("appointment", "update_medical_attention")){			
			$data = $this->input->post();
			if ($data["profile_id"]){
				if (!$this->general->filter("appointment_examination", $data)){
					if ($this->general->insert("appointment_examination", $data)){
						$type = "success";
						$msg = $this->lang->line('success_apr');
					}else $msg = $this->lang->line('error_internal');
				}else $msg = $this->lang->line('error_dpr');
			}else $msg = $this->lang->line('error_spr');
		}else $msg = $this->lang->line('error_no_permission');
		
		$result = $this->set_profiles_exams($data["appointment_id"]);
		$profiles = $result["profiles"];
		$exams = $result["exams"];
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg, "profiles" => $profiles, "exams" => $exams]);
	}
	
	public function remove_exam_profile(){
		$type = "error"; $msg = null;
		
		if ($this->utility_lib->check_access("appointment", "update_medical_attention")){			
			$data = $this->input->post();
			
			if ($this->general->delete("appointment_examination", $data)){
				$type = "success";
				$msg = $this->lang->line('success_rex');
			}else $msg = $this->lang->line('error_internal');
		}else $msg = $this->lang->line('error_no_permission');
		
		$result = $this->set_profiles_exams($data["appointment_id"]);
		$profiles = $result["profiles"];
		$exams = $result["exams"];
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg, "profiles" => $profiles, "exams" => $exams]);
	}
	
	public function add_exam(){
		$type = "error"; $msg = null; $profiles = []; $exams = [];
		
		if ($this->utility_lib->check_access("appointment", "update_medical_attention")){			
			$data = $this->input->post();
			
			$tb_name = "appointment_examination";
			if ($data["examination_id"]){
				if (!$this->general->filter($tb_name, $data)){
					$profile_ids_arr = [];
					$profile_ids = $this->general->filter($tb_name, ["appointment_id" => $data["appointment_id"], "profile_id !=" => null]);
					
					$is_include = false;
					foreach($profile_ids as $item){
						$profile = $this->general->id("examination_profile", $item->profile_id);
						$exam_ids_aux = explode(",", $profile->examination_ids);
						
						if (in_array($data["examination_id"], $exam_ids_aux)){
							$is_include = true;
							break;
						}
					}
					
					if (!$is_include){
						if ($this->general->insert($tb_name, $data)){
							$type = "success";
							$msg = $this->lang->line('success_aex');
						}else $msg = $this->lang->line('error_internal');	
					}else $msg = str_replace("&profile&", $profile->name, $this->lang->line('error_pie'));
				}else $msg = $this->lang->line('error_dex');
			}else $msg = $this->lang->line('error_sex');
		}else $msg = $this->lang->line('error_no_permission');
		
		$result = $this->set_profiles_exams($data["appointment_id"]);
		$profiles = $result["profiles"];
		$exams = $result["exams"];
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg, "profiles" => $profiles, "exams" => $exams]);
	}
	
	public function remove_exam(){
		$type = "error"; $msg = null;
		
		if ($this->utility_lib->check_access("appointment", "update_medical_attention")){			
			$data = $this->input->post();
			
			if ($this->general->delete("appointment_examination", $data)){
				$type = "success";
				$msg = $this->lang->line('success_rex');
			}else $msg = $this->lang->line('error_internal');
		}else $msg = $this->lang->line('error_no_permission');
		
		$result = $this->set_profiles_exams($data["appointment_id"]);
		$profiles = $result["profiles"];
		$exams = $result["exams"];
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg, "profiles" => $profiles, "exams" => $exams]);
	}
	
	private function set_therapy_list($appointment_id){
		$tb_name = "appointment_therapy";
		$therapies = $this->general->filter($tb_name, ["appointment_id" => $appointment_id]);
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
		$type = "error"; $msgs = []; $msg = null;
		
		if ($this->utility_lib->check_access("appointment", "update_medical_attention")){			
			$data = $this->input->post();
			
			$this->load->library('my_val');
			$msgs = $this->my_val->appointment_physical_therapy($msgs, $data);
			
			if (!$msgs){
				$appointment = $this->appointment->id($data["appointment_id"]);
				if (in_array($this->status->id($appointment->status_id)->code, ["reserved", "finished", "canceled"]))
					$msg = $this->lang->line('error_nea');
				elseif (!$this->general->insert("appointment_therapy", $data))
					$msg = $this->lang->line('error_internal');
				else{
					$msg = $this->lang->line('success_ath');
					$type = "success";
				}
			}else $msg = $this->lang->line('error_occurred');
		}else $msg = $this->lang->line('error_no_permission');
		
		$therapies = $this->set_therapy_list($data["appointment_id"]);
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msgs" => $msgs, "msg" => $msg, "therapies" => $therapies]);
	}
	
	public function delete_therapy(){
		$type = "error"; $msg = null;
		
		if ($this->utility_lib->check_access("appointment", "update_medical_attention")){
			$data = $this->input->post();
		
			//appointment status validation
			$appointment = $this->appointment->id($data["appointment_id"]);
			if (in_array($this->status->id($appointment->status_id)->code, ["reserved", "finished", "canceled"])){
				$msg = $this->lang->line('error_nea');
			}else{
				if (!$this->general->delete("appointment_therapy", $data)) $msg = $this->lang->line('error_internal');
				else{
					$msg = $this->lang->line('success_rth');
					$type = "success";
				}
			}
		}else $msg = $this->lang->line('error_no_permission');
		
		$therapies = $this->set_therapy_list($data["appointment_id"]);
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg, "therapies" => $therapies]);
	}
	
	private function set_medicine_list($appointment_id){
		$medicines = $this->general->filter("appointment_medicine", ["appointment_id" => $appointment_id]);
		foreach($medicines as $item){
			$sub_txt_arr = [];
			if ($item->quantity > 1) $sub_txt_arr[] = number_format($item->quantity)." ".$this->lang->line('txt_units');
			else $sub_txt_arr[] = number_format($item->quantity)." ".$this->lang->line('txt_unit');
			
			if ($item->dose_id) 
				$sub_txt_arr[] = $this->general->id("medicine_dose", $item->dose_id)->description;
			
			if ($item->application_way_id) 
				$sub_txt_arr[] = $this->general->id("medicine_application_way ", $item->application_way_id)->description;
			
			if ($item->frequency_id) 
				$sub_txt_arr[] = $this->general->id("medicine_frequency", $item->frequency_id)->description;
			
			if ($item->duration_id) 
				$sub_txt_arr[] = $this->general->id("medicine_duration", $item->duration_id)->description;
			
			$item->medicine = $this->general->id("medicine", $item->medicine_id)->name;
			$item->sub_txt = implode(", ", $sub_txt_arr);
		}
		usort($medicines, function($a, $b) { return strcmp($a->medicine, $b->medicine); });
		
		return $medicines;
	}
	
	public function add_medicine(){
		$type = "error"; $msgs = []; $msg = null;
		
		if ($this->utility_lib->check_access("appointment", "update_medical_attention")){			
			$data = $this->input->post();
			
			$this->load->library('my_val');
			$msgs = $this->my_val->appointment_medicine($msgs, $data);
			
			if (!$msgs){
				$appointment = $this->appointment->id($data["appointment_id"]);
				if (in_array($this->status->id($appointment->status_id)->code, ["reserved", "finished", "canceled"]))
					$msg = $this->lang->line('error_nea');
				elseif (!$this->general->insert("appointment_medicine", $data))
					$msg = $this->lang->line('error_internal');
				else{
					$msg = $this->lang->line('success_ame');
					$type = "success";
				}
			}else $msg = $this->lang->line('error_occurred');
		}else $msg = $this->lang->line('error_no_permission');
		
		$medicines = $this->set_medicine_list($data["appointment_id"]);
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg, "msgs" => $msgs, "medicines" => $medicines]);
	}
	
	public function delete_medicine(){
		$type = "error"; $msg = null;
		
		if ($this->utility_lib->check_access("appointment", "update_medical_attention")){			
			$data = $this->input->post();
			
			//appointment status validation
			$appointment = $this->appointment->id($data["appointment_id"]);
			if (in_array($this->status->id($appointment->status_id)->code, array("reserved", "finished", "canceled"))){
				$msg = $this->lang->line('error_nea');
			}else{
				if (!$this->general->delete("appointment_medicine", $data)) $msg = $this->lang->line('error_internal');
				else{
					$msg = $this->lang->line('success_rme');
					$type = "success";
				}
			}
		}else $msg = $this->lang->line('error_no_permission');
		
		$medicines = $this->set_medicine_list($data["appointment_id"]);
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg, "medicines" => $medicines]);
	}
	
	public function report($id){
		if (!$this->utility_lib->check_access("appointment", "report")) redirect("/errors/no_permission");
		
		$appointment = $this->appointment->id($id);
		if (!$appointment) redirect("/appointment");
		
		$doctor = $this->general->id("person", $appointment->doctor_id);
		if ($doctor){
			$data = $this->general->filter("doctor", ["person_id" => $doctor->id]);
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
			$patient->doc_type = $this->general->id("doc_type", $patient->doc_type_id)->description;
			if ($patient->birthday) $patient->age = $this->utility_lib->age_calculator($patient->birthday, true);
			else $patient->birthday = $patient->age = null;	
		}else $patient = $this->general->structure("person");
		
		$appointment_datas = $this->set_appointment_data($appointment);
		
		$data = [
			"appointment" => $appointment,
			"appointment_datas" => $appointment_datas,
			"doctor" => $doctor,
			"patient" => $patient
		];
		
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
		if ($dompdf) $dompdf->stream("Reporte", ["Attachment" => false]); else echo "Error";
		//echo $html;
	}
}
