<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Appointment extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/Lima');
		$this->lang->load("appointment", "spanish");
		$this->lang->load("system", "spanish");
		
		$this->load->model('general_model','general');
		$this->nav_menu = ["clinic", "appointment"];
		$this->nav_menus = $this->utility_lib->get_visible_nav_menus();
	}
	
	public function index(){//appointment list
		if (!$this->session->userdata('logged_in')) redirect('/');
		if (!$this->utility_lib->check_access("appointment", "index")) redirect("/errors/no_permission");
		
		$f_url = [
			"page" => $this->input->get("page"),
			"status" => $this->input->get("status"),
			"doc_type" => $this->input->get("doc_type"),
			"doc_number" => $this->input->get("doc_number"),
			"name" => $this->input->get("name"),
			"tel" => $this->input->get("tel"),
			"diagnosis" => $this->input->get("diagnosis"),
		];
		
		if (!$f_url["page"]) $f_url["page"] = 1;
		
		$f_w = $f_l = $f_w_in = [];
		
		//by appointment status
		if ($f_url["status"]) $f_w["status"] = $f_url["status"];
		
		//by patient
		if ($f_url["doc_number"] or $f_url["doc_type"] or $f_url["name"] or $f_url["tel"]){
			$aux = [-1];
			
			$f_w_person = $f_l_person = [];
			if ($f_url["doc_type"]) $f_w_person["doc_type_id"] = $f_url["doc_type"];
			if ($f_url["doc_number"]) $f_l_person[] = ["field" => "doc_number_id", "values" => explode(" ", trim($f_url["doc_number"]))];
			if ($f_url["name"]) $f_l_person[] = ["field" => "name", "values" => explode(" ", trim($f_url["name"]))];
			if ($f_url["tel"]) $f_l_person[] = ["field" => "tel", "values" => explode(" ", trim($f_url["tel"]))];
			
			$people = $this->general->filter("person", $f_w_person, $f_l_person);
			foreach($people as $p) $aux[] = $p->id;
			//echo $this->db->last_query(); return;
			$f_w_in[] = ["field" => "patient_id", "values" => $aux];
		}
		
		//by diagnosis
		if (strlen($f_url["diagnosis"]) > 2){
			$diags = $this->general->filter("diag_impression_detail", null, [["field" => "description", "values" => explode(" ", trim($f_url["diagnosis"]))]]);
			
			$aux = [-1];
			foreach($diags as $item) $aux[] = $item->id;
			$aux = array_unique($aux);
			
			$app_diag = $this->general->filter("appointment_diag_impression", null, null, [["field" => "diag_id", "values" => $aux]]);
			
			$aux = [-1];
			foreach($app_diag as $item) $aux[] = $item->appointment_id;
			$aux = array_unique($aux);
			
			$f_w_in[] = ["field" => "id", "values" => $aux];
		}else $f_url["diagnosis"] = null;
		
		if ($this->session->userdata('role')->name === "doctor") $f_w["doctor_id"] = $this->session->userdata('pid');
		
		//status language & color assign
		$status_sp = [
			'reserved'	=> 'Reservado',
			'confirmed'	=> 'Confirmado',
			'finished'	=> 'Finalizado',
			'canceled'	=> 'Cancelado',
			'in_progress' => 'En Progreso',
			'enabled'	=> 'Activado',
			'disabled'	=> 'Desactivado',
			'accepted'	=> 'Aceptado',
			'rejected'	=> 'Rechazado',
			'pending'	=> 'Pendiente',
		];
		
		$status_aux = [];
		$status_ids = $this->general->only("appointment", "status_id");
		foreach($status_ids as $item) $status_aux[] = $item->status_id;
		
		if (!$status_aux) $status_aux = [-1];
		$f_status = [["field" => "id", "values" => $status_aux]];
		
		$status_arr = [];
		$status = $this->general->filter("status", null, null, [["field" => "id", "values" => $status_aux]]);
		foreach($status as $item){
			$item->sp = $status_sp[$item->code];
			$status_arr[$item->id] = $item;
		}
		
		$appointments = $this->general->filter("appointment", $f_w, $f_l, $f_w_in, "schedule_from", "desc", 25, 25*($f_url["page"]-1));
		foreach($appointments as $item){
			$item->patient = $this->general->id("person", $item->patient_id)->name;
			$item->doctor = $this->general->id("person", $item->doctor_id)->name;
			$item->specialty = $this->general->id("specialty", $item->specialty_id)->name;
			
			//status
			$item->status_color = $status_arr[$item->status_id]->color;
			$item->status_sp = $status_sp[$status_arr[$item->status_id]->code];
		}
		
		//for add appointment
		$enabled_id = $this->general->status("enabled")->id;
		
		//load specialties
		$aux_f = ["status_id" => $enabled_id];
		$specialties = $this->general->all("specialty", "name", "asc");
		foreach($specialties as $s){
			$aux_f["specialty_id"] = $s->id;
			$s->doctor_qty = $this->general->counter("doctor", $aux_f);
		}
		
		//load doctors
		$aux_f = ["status_id" => $enabled_id];
		$doctors = $this->general->filter("doctor", $aux_f);
		foreach($doctors as $d){
			if (!$this->general->id("person", $d->person_id)) echo $d->person_id."<br/>";
			$d->name = $this->general->id("person", $d->person_id)->name;
		}
		
		$data = [
			"paging" => $this->my_func->set_page($f_url["page"], $this->general->counter("appointment", $f_w, null, $f_w_in)),
			"f_url" => $f_url,
			"status" => $status,
			"appointments" => $appointments,
			"specialties"	=> $specialties,
			"doctors"	=> $doctors,
			"doc_types" => $this->general->all("doc_type", "id", "asc"),
			"title" => $this->lang->line('appointments'),
			"main" => "clinic/appointment/list",
		];
		
		$this->load->view('layout', $data);
	}
	
	public function add(){
		/*
		Updated: 2025 0622
		Notes
		- 2025 0622: Created as a page for remove tab actions
		*/
		
		$enabled_id = $this->general->status("enabled")->id;
		
		//load specialties
		$aux_f = ["status_id" => $enabled_id];
		$specialties = $this->general->all("specialty", "name", "asc");
		foreach($specialties as $s){
			$aux_f["specialty_id"] = $s->id;
			$s->doctor_qty = $this->general->counter("doctor", $aux_f);
		}
		unset($aux_f["specialty_id"]);
		
		//load doctors
		$aux_f = ["status_id" => $enabled_id];
		$doctors = $this->general->filter("doctor", $aux_f);
		foreach($doctors as $d){
			if (!$this->general->id("person", $d->person_id)) echo $d->person_id."<br/>";
			$d->name = $this->general->id("person", $d->person_id)->name;
		}
		
		//sort by name
		usort($doctors, function($a, $b) {
			return strcmp(strtoupper($a->name), strtoupper($b->name));
		});
		
		$data = [
			"specialties"	=> $specialties,
			"doctors"		=> $doctors,
			"mins"			=> ["00", "10", "20", "30", "40", "50"],
			"doc_types"		=> $this->general->all("doc_type", "id", "asc"),
			"title" 		=> $this->lang->line('appointments'),
			"main" 			=> "clinic/appointment/add",
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
			$anamnesis = null;
			
			$f = [
				"patient_id" => $appointment->patient_id,
				"status_id" => $this->general->status("finished")->id,
			];
			$app_aux = $this->general->filter("appointment", $f, null, null, "schedule_from", "desc", 1, 0);
			if ($app_aux){
				$app_anam_aux = $this->general->filter("appointment_anamnesis", ["appointment_id" => $app_aux[0]->id]);
				if ($app_anam_aux){
					$app_anam_aux = $app_anam_aux[0];
					$app_anam_arr = [
						"appointment_id" => $appointment->id,
						"name" => $app_anam_aux->name,
						"age" => $this->my_func->age_calculator($app_anam_aux->birthday, false),
						"sex_id" => $app_anam_aux->sex_id,
						"address" => $app_anam_aux->address,
						"birthplace" => $app_anam_aux->birthplace,
						"birthday" => $app_anam_aux->birthday,
						"tel" => $app_anam_aux->tel,
						"responsible" => $app_anam_aux->responsible,
						"provenance_place" => $app_anam_aux->provenance_place,
						"last_trips" => $app_anam_aux->last_trips,
						"race" => $app_anam_aux->race,
						"civil_status_id" => $app_anam_aux->civil_status_id,
						"occupation" => $app_anam_aux->occupation,
						"religion" => $app_anam_aux->religion,
						"patho_pre_illnesses" => $app_anam_aux->patho_pre_illnesses,
						"patho_pre_illnesses_other" => $app_anam_aux->patho_pre_illnesses_other,
						"patho_pre_hospitalization" => $app_anam_aux->patho_pre_hospitalization,
						"patho_pre_surgery" => $app_anam_aux->patho_pre_surgery,
						"patho_ram" => $app_anam_aux->patho_ram,
						"patho_transfusion" => $app_anam_aux->patho_transfusion,
						"patho_pre_medication" => $app_anam_aux->patho_pre_medication,
						"family_history" => $app_anam_aux->family_history,
					];
					$this->general->insert("appointment_anamnesis", $app_anam_arr);
					$anamnesis = $this->general->filter("appointment_anamnesis", ["appointment_id" => $appointment->id])[0];
				}
			}
			
			if ($anamnesis == null){
				$anamnesis = $this->general->structure("appointment_anamnesis");
				$patient = $this->general->id("person", $appointment->patient_id);
				if ($patient){
					$anamnesis->name = $anamnesis->responsible = $patient->name;
					$anamnesis->sex_id = $patient->sex_id;
					$anamnesis->tel = $patient->tel;
					$anamnesis->address = $patient->address;
					if ($patient->birthday){
						$anamnesis->birthday = $patient->birthday;
						$anamnesis->age = $this->my_func->age_calculator($patient->birthday, false);
					}
				}
			}
		}
		
		if ($anamnesis->sex_id) $anamnesis->sex = $this->general->id("sex", $anamnesis->sex_id)->description;
		else $anamnesis->sex = null;
		
		if ($anamnesis->birthday) $anamnesis->birthday = date("Y-m-d", strtotime($anamnesis->birthday));
		else $anamnesis->birthday = null;
		
		if ($anamnesis->civil_status_id) $anamnesis->civil_status = $this->general->id("civil_status", $anamnesis->civil_status_id)->description;
		else $anamnesis->civil_status = null;
		
		$aux_pre_illnesses = [];
		if ($anamnesis->patho_pre_illnesses) $aux_pre_illnesses[] = $anamnesis->patho_pre_illnesses;
		if ($anamnesis->patho_pre_illnesses_other) $aux_pre_illnesses[] = $anamnesis->patho_pre_illnesses_other;
		
		foreach($aux_pre_illnesses as $i => $item) if (!trim($item)) unset($aux_pre_illnesses[$i]);
		$anamnesis->patho_pre_illnesses_txt = $aux_pre_illnesses ? implode(", ", $aux_pre_illnesses) : "-";
		$anamnesis->patho_pre_illnesses = ($anamnesis->patho_pre_illnesses_txt !== "-") ? explode(", ", $anamnesis->patho_pre_illnesses_txt) : [];
		
		$physical = $this->general->filter("appointment_physical", ["appointment_id" => $appointment->id]);
		if ($physical) $physical = $physical[0];
		else $physical = $this->general->structure("appointment_physical");
		
		$diag_ids = [];
		$diags = $this->general->filter("appointment_diag_impression" , ["appointment_id" => $appointment->id]);
		foreach($diags as $diag) array_push($diag_ids, $diag->diag_id);
		$diag_impression = $this->general->ids("diag_impression_detail", $diag_ids, "code");
		
		$result = $this->general->filter("appointment_result", ["appointment_id" => $appointment->id]);
		if ($result){
			$result = $result[0];
			$result->type = $this->general->id("diagnosis_type", $result->diagnosis_type_id)->description;
		}else{
			$result = $this->general->structure("appointment_result");
			$result->type = "";
		}
		
		/* load all history for medical history viewer */
		$apps = $this->general->filter("appointment", ["patient_id" => $appointment->patient_id], null, null, "schedule_from", "desc");
		$apps_dates = $apps_ids = [];
		foreach($apps as $item){
			$apps_dates[$item->id] = date("Y-m-d", strtotime($item->schedule_from));
			$apps_ids[] = $item->id;
			//print_r($item); echo "<br/><br/>";
		}
		
		//diag_impression
		$diag_multi = $this->general->filter("appointment_diag_impression" , null, null, [["field" => "appointment_id", "values" => $apps_ids]]);
		foreach($diag_multi as $item){
			$item->app_date = $apps_dates[$item->appointment_id];
			$item->detail = $this->general->id("diag_impression_detail", $item->diag_id);
		}
		
		usort($diag_multi, function($a, $b) { return ($a->app_date < $b->app_date); });
		
		//result
		$result_multi = $this->general->filter("appointment_result" , null, null, [["field" => "appointment_id", "values" => $apps_ids]]);
		foreach($result_multi as $i => $item){
			if (!$item->diagnosis and !$item->plan and !$item->treatment) unset($result_multi[$i]);
			else{
				$item->app_date = $apps_dates[$item->appointment_id];
				$item->res_type = $this->general->id("diagnosis_type", $result->diagnosis_type_id)->description;
			}
		}
		
		usort($result_multi, function($a, $b) { return ($a->app_date < $b->app_date); });
		
		//examination
		$exam_multi = [];
		foreach($apps_ids as $item){
			$details = $this->set_profiles_exams($item);
			if ($details["profiles"] or $details["exams"]) $exam_multi[] = ["app_date" => $apps_dates[$item], "details" => $details];
		}
		
		usort($exam_multi, function($a, $b) { return ($a["app_date"] < $b["app_date"]); });
		
		//////////////////working
		//image
		$image_multi = $this->general->filter("appointment_image" , null, null, [["field" => "appointment_id", "values" => $apps_ids]]);
		foreach($image_multi as $item){
			$item->app_date = $apps_dates[$item->appointment_id];
			$item->images = $this->set_images($item->appointment_id);
		}
		
		usort($image_multi, function($a, $b) { return ($a->app_date < $b->app_date); });
		
		//medicine
		$medicine_multi = $this->general->filter("appointment_medicine" , null, null, [["field" => "appointment_id", "values" => $apps_ids]]);
		foreach($medicine_multi as $item){
			$item->app_date = $apps_dates[$item->appointment_id];
			$item->medicines = $this->set_medicine_list($item->appointment_id);
		}
		
		usort($medicine_multi, function($a, $b) { return ($a->app_date < $b->app_date); });
		
		//therapy
		$therapy_multi = $this->general->filter("appointment_therapy" , null, null, [["field" => "appointment_id", "values" => $apps_ids]]);
		foreach($therapy_multi as $item){
			$item->app_date = $apps_dates[$item->appointment_id];
			$item->therapies = $this->set_therapy_list($item->appointment_id);
			
			//print_r($item); echo "<br/><br/>";
		}
		
		usort($therapy_multi, function($a, $b) { return ($a->app_date < $b->app_date); });
		
		/*
		foreach($result_multi as $item){
			print_r($item); echo "<br/><br/>";
		}
		
		print_r($apps_dates); echo "<br/><br/>";
		print_r($apps_ids); echo "<br/><br/>";
		print_r($multi_diags); echo "<br/><br/>";
		*/
		
		
		//////////////////////////////working
		$appointment_datas = array(
			"basic_data" => $basic_data,
			"anamnesis" => $anamnesis,
			"physical" => $physical,
			"diag_impression" => $diag_impression,
			"diag_multi" => $diag_multi,
			"result" => $result,
			"result_multi" => $result_multi,
			"examination" => $this->set_profiles_exams($appointment->id),
			"exam_multi" => $exam_multi,
			"images" => $this->set_images($appointment->id),
			"image_multi" => $image_multi,
			"medicine" => $this->set_medicine_list($appointment->id),
			"medicine_multi" => $medicine_multi,
			"therapy" => $this->set_therapy_list($appointment->id),
			"therapy_multi" => $therapy_multi,
		);
		
		return $appointment_datas;
	}
	
	public function detail($id){
		if (!$this->session->userdata('logged_in')) redirect('/');
		if (!$this->utility_lib->check_access("appointment", "detail")) redirect("/errors/no_permission");
		
		$appointment = $this->general->id("appointment", $id);
		if (!$appointment) redirect("/clinic/appointment");
		
		$appointment->status = $this->general->id("status", $appointment->status_id);
		
		$actions = [];
		switch($appointment->status->code){
			case "reserved":
				$appointment->is_editable = false; 
				array_push($actions, "reschedule");
				array_push($actions, "cancel");
				break;
			case "confirmed":
				$appointment->is_editable = true;
				array_push($actions, "reschedule");
				break;
			case "finished":
				$appointment->is_editable = true;
				array_push($actions, "clinic_history");
				break;
			case "canceled": 
				$appointment->is_editable = false;
				$appointment->status->color = "danger";
				break;
		}
		
		//define posible operations by role
		switch($this->session->userdata("role")->name){
			case "master": $operations = ["information", "triage", "attention"]; break;
			case "admin": $operations = ["information", "triage", "attention"]; break;
			case "doctor": $operations = ["attention"]; break;
			case "nurse": $operations = ["triage"]; break;
			default: $operations = ["information"]; //reception
		}
		
		$appointment->specialty = $this->general->id("specialty", $appointment->specialty_id)->name;
		$appointment->sale_id = $appointment->sale_prod = null;
		$appointment_sale = $this->general->filter("sale_product", ["appointment_id" => $appointment->id]);
		if ($appointment_sale){
			$appointment->sale_id = $appointment_sale[0]->sale_id;
			$appointment->sale_prod = $this->general->id("product", $appointment_sale[0]->product_id)->description;
		}
		
		$doctor = $this->general->id("person", $appointment->doctor_id);
		if ($doctor){
			$data = $this->general->filter("doctor", ["person_id" => $doctor->id]);
			if ($data) $doctor->data = $data[0];
			else $doctor->data = $this->general->structure("doctor");
		}else{
			$doctor = $this->general->structure("person");
			$doctor->data = $this->general->structure("doctor");
		}
		
		$patient = $this->general->id("person", $appointment->patient_id);
		if ($patient){
			$patient->doc_type = $this->general->id("doc_type", $patient->doc_type_id)->description;
			if ($patient->birthday) $patient->age = $this->my_func->age_calculator($patient->birthday);
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
		
		$s_finished = $this->general->status("finished");
		$filter = array("patient_id" => $patient->id, "status_id" => $s_finished->id);
		
		$surgery_histories = $this->general->filter("surgery", $filter);
		foreach($surgery_histories as $item){
			$d = $this->general->filter("doctor", ["person_id" => $doctor->id])[0];
			$item->specialty = $specialties[$d->specialty_id];
			$item->link_to = "surgery";
			$item->type = "Cirugía";
		}
		
		$appointment_histories = $this->general->filter("appointment", $filter);
		foreach($appointment_histories as $item){
			$d = $this->general->filter("doctor", ["person_id" => $doctor->id])[0];
			$item->specialty = $specialties[$d->specialty_id];
			$item->link_to = base_url()."clinic/appointment_print/medical_history/".$item->id;
			$item->type = "Consulta";
		}
		
		$histories = array_merge($surgery_histories, $appointment_histories);
		usort($histories, function($a, $b) { return ($a->schedule_from < $b->schedule_from); });
		//end set history records
		
		//setting patho_pre_illnesses
		$pre_illnesses = [];
		array_push($pre_illnesses, array("id" => "patho_asma", "value" => $this->lang->line("w_asthma")));
		array_push($pre_illnesses, array("id" => "patho_hta", "value" => $this->lang->line("w_aht")));
		array_push($pre_illnesses, array("id" => "patho_dm", "value" => $this->lang->line("w_dm")));
		array_push($pre_illnesses, array("id" => "patho_f_tifoidea", "value" => $this->lang->line("w_f_typhoid")));
		array_push($pre_illnesses, array("id" => "patho_f_malta", "value" => $this->lang->line("w_f_malta")));
		array_push($pre_illnesses, array("id" => "patho_tbc", "value" => $this->lang->line("w_tbc")));
		array_push($pre_illnesses, array("id" => "patho_contacto_tbc", "value" => $this->lang->line("w_contact_tbc")));
		array_push($pre_illnesses, array("id" => "patho_etu", "value" => $this->lang->line("w_etu")));
		
		//examination records
		$examinations_list = [];
		$examinations = $this->general->all("examination", "name", "asc");
		foreach($examinations as $item) $examinations_list[$item->id] = $item;
		
		$exam_profiles = $this->general->all("examination_profile", "name", "asc");
		foreach($exam_profiles as $i => $item){
			$exams_aux = $cate_aux = [];
			$item->examination_ids = explode(",", $item->examination_ids);
			$f_in = [["field" => "id", "values" => $item->examination_ids]];
			$exams = $this->general->filter("examination", null, null, $f_in, "name", "asc");
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
		
		$patient_files = $this->general->filter("patient_file", ["patient_id" => $appointment->patient_id], null, null, "registed_at", "desc");
		
		$data = array(
			"actions" => $actions,
			"operations" => $operations,
			"appointment" => $appointment,
			"appointment_datas" => $this->set_appointment_data($appointment),
			"doctor" => $doctor,
			"patient" => $patient,
			"histories" => $histories,
			"patient_files" => $patient_files,
			"pre_illnesses" => $pre_illnesses,
			"options" => $options,
			"exam_profiles" => $exam_profiles,
			"exam_categories" => $this->general->all("examination_category", "name", "asc"),
			"examinations" => $examinations,
			"aux_image_categories" => $this->general->all("image_category", "name", "asc"),
			"aux_images" => $this->general->all("image", "name", "asc"),
			"sex_ops" => $this->general->all("sex", "description", "asc"),
			"physical_therapies" => $this->general->all("physical_therapy", "name", "asc"),
			"medicines" => $this->general->all("medicine", "name", "asc"),
			"title" => "Consulta",
			"main" => "clinic/appointment/detail",
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
				$app["schedule_to"] = date("Y-m-d H:i:s", strtotime("+9 minutes", strtotime($app["schedule_from"])));
				$app["status_id"] = ($this->input->post("as_free") == null) ? $this->general->status("reserved")->id : $this->general->status("confirmed")->id;
				$app["registed_at"] = $now;
				
				$appointment_id = $this->general->insert("appointment", $app);
				if ($appointment_id){
					$this->utility_lib->add_log("appointment_register", $pt["name"]);
					
					$type = "success";
					$move_to = base_url()."clinic/appointment/detail/".$appointment_id;
					$msg = $this->lang->line('s_app_register');
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
				$s_cancel = $this->general->status("canceled");
				if ($this->general->update("appointment", $appointment->id, ["status_id" => $s_cancel->id])){
					$person = $this->general->id("person", $appointment->patient_id);
					$this->utility_lib->add_log("appointment_cancel", $person->name);
					
					$type = "success";
					$msg = $this->lang->line('s_app_cancel');
				}else $msg = $this->lang->line('error_internal');
			}else $msg = $this->lang->line('e_no_app_record');
		}else $msg = $this->lang->line('error_no_permission');
				
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg]);
	}
	
	public function finish(){
		$type = "error"; $msg = null;
		
		if ($this->utility_lib->check_access("appointment", "update_medical_attention")){
			$appointment = $this->general->id("appointment", $this->input->post("id"));
			if ($appointment){
				$s_finished = $this->general->status("finished");
				if ($this->general->update("appointment", $appointment->id, ["status_id" => $s_finished->id])){
					$person = $this->general->id("person", $appointment->patient_id);
					$this->utility_lib->add_log("appointment_finish", $person->name);
					
					$type = "success";
					$msg = $this->lang->line('s_app_finish');
				}else $msg = $this->lang->line('error_internal');
			}else $msg = $this->lang->line('e_no_app_record');
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
				//$msgs = $this->my_val->appointment_reschedule($msgs, "ra_", $appointment, $data);
				
				if (!$msgs){
					$schedule_from = $data["date"]." ".$data["hour"].":".$data["min"];
					$app = [
						"id" => $appointment->id,
						"schedule_from" => $schedule_from,
						"schedule_to" => date("Y-m-d H:i:s", strtotime("+14 minutes", strtotime($schedule_from)))
					];
					
					if ($this->general->update("appointment", $app["id"], $app)){
						$person = $this->general->id("person", $appointment->patient_id);
						$this->utility_lib->add_log("appointment_reschedule", $person->name);
						
						$type = "success";
						$msg = $this->lang->line('s_app_reschedule');
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
					$appointment = $this->general->id("appointment", $data["appointment_id"]);
					$appointment->status = $this->general->id("status", $appointment->status_id);
					if ("reserved" !== $appointment->status->code){
						//set enterance time
						$data["entered_at"] = $data["date"]." ".$data["time"];
						unset($data["date"]);
						unset($data["time"]);
						
						$type = "success";
						$msg = $this->save_data($data, "appointment_basic_data", "s_basic_data");
					}else $msg = $this->lang->line('e_app_unconfirmed');
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
				$appointment = $this->general->id("appointment", $data["appointment_id"]);
				$appointment->status = $this->general->id("status", $appointment->status_id);
					
				if ("reserved" !== $appointment->status->code){
					$type = "success";
					$msg = $this->save_data($data, "appointment_anamnesis", "s_personal_info");
				}else $msg = $this->lang->line('e_app_unconfirmed');
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
				$appointment = $this->general->id("appointment", $data["appointment_id"]);
				$appointment->status = $this->general->id("status", $appointment->status_id);
				if ("reserved" !== $appointment->status->code){
					$type = "success";
					$msg = $this->save_data($data, "appointment_physical", "s_triage");
				}else $msg = $this->lang->line('e_app_unconfirmed');
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
				$appointment = $this->general->id("appointment", $data["appointment_id"]);
				$appointment->status = $this->general->id("status", $appointment->status_id);
				if ("reserved" !== $appointment->status->code){
					if (array_key_exists("patho_pre_illnesses", $data))
						$data["patho_pre_illnesses"] = str_replace(", ",",", implode(",", $data["patho_pre_illnesses"]));
					else $data["patho_pre_illnesses"] = null;
					
					$type = "success";
					$msg = $this->save_data($data, "appointment_anamnesis", "s_anamnesis");
				}else $msg = $this->lang->line('e_app_unconfirmed');
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
				$appointment = $this->general->id("appointment", $data["appointment_id"]);
				$appointment->status = $this->general->id("status", $appointment->status_id);
				if ("reserved" !== $appointment->status->code){
					$type = "success";
					$msg = $this->save_data($data, "appointment_physical", "s_physical_exam");
				}else $msg = $this->lang->line('e_app_unconfirmed');
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
			$qty = number_format(count($diags))." ".$this->lang->line('w_results');
			
			$type = "success"; 
		}else $msgs = $this->my_val->set_msg($msgs, "di_diagnosis_msg", "error", "e_filter_blank");
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msgs" => $msgs, "diags" => $diags, "qty" => $qty]);
	}
	
	public function add_diag(){
		$type = "success"; $msg = $this->lang->line('s_diag_add'); $diags = [];
		
		if ($this->utility_lib->check_access("appointment", "update_medical_attention")){			
			$data = $this->input->post();
			
			//appointment status validation
			$appointment = $this->general->id("appointment", $data["appointment_id"]);
			$appointment->status = $this->general->id("status", $appointment->status_id);
			
			if ("reserved" !== $appointment->status->code){
				if (!$this->general->filter("appointment_diag_impression" , $data)){
					if (!$this->general->insert("appointment_diag_impression" , $data)){
						$msg = $this->lang->line('error_internal');
						$type = "error";
					}
				}	
			}else{
				$msg = $this->lang->line('e_reserved_appointment');
				$type = "error";
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
		$type = "success"; $msg = $this->lang->line('s_diag_remove'); $diags = [];
		
		if ($this->utility_lib->check_access("appointment", "update_medical_attention")){			
			$data = $this->input->post();
			
			//appointment status validation
			$appointment = $this->general->id("appointment", $data["appointment_id"]);
			$appointment->status = $this->general->id("status", $appointment->status_id);
			
			if ("reserved" !== $appointment->status->code){
				if (!$this->general->delete("appointment_diag_impression", $data)){
					$msg = $this->lang->line('error_internal');
					$type = "error";
				}
			}else{
				$msg = $this->lang->line('e_reserved_appointment');
				$type = "error";
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
				$appointment = $this->general->id("appointment", $data["appointment_id"]);
				$appointment->status = $this->general->id("status", $appointment->status_id);
				
				if ("reserved" !== $appointment->status->code){
					$type = "success";
					$msg = $this->save_data($data, "appointment_result", "s_result");
				}else $msg = $this->lang->line('e_app_unconfirmed');
			}else $msg = $this->lang->line('error_internal_refresh');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msgs" => $msgs, "msg" => $msg]);
	}
	
	public function set_images($app_id){
		$images = $this->general->filter("appointment_image", ["appointment_id" => $app_id]);
		foreach($images as $i => $item){
			$img = $this->general->id("image", $item->image_id); 
			if ($img){
				$img_category = $this->general->id("image_category", $img->category_id);
				$item->category = $img_category->name;
				$item->category_id = $img->category_id;
				$item->name = $img->name;	
			}else unset($images[$i]);
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
					$msg = $this->lang->line('s_image_add');
				}else $msg = $this->lang->line('error_internal');
			}else $msg = $this->lang->line('e_duplicated_image');
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
				$msg = $this->lang->line('s_image_remove');
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
				
				$item->type = $this->lang->line('w_profile');
				$item->exams = implode(", ", $aux_ex_arr);
			}	
		}else $profiles = [];
		
		if ($ex_ids){
			$exams = $this->general->filter("examination", null, null, [["field" => "id", "values" => $ex_ids]], "name", "asc");
			foreach($exams as $item){
				$item->category = $this->general->id("examination_category", $item->category_id);
				$item->type = $this->lang->line('w_exam');
			}
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
						$msg = $this->lang->line('s_profile_add');
					}else $msg = $this->lang->line('error_internal');
				}else $msg = $this->lang->line('e_duplicated_profile');
			}else $msg = $this->lang->line('e_select_profile');
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
				$msg = $this->lang->line('s_profile_remove');
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
							$msg = $this->lang->line('s_exam_add');
						}else $msg = $this->lang->line('error_internal');	
					}else $msg = str_replace("&profile&", $profile->name, $this->lang->line('e_profile_includes_exam'));
				}else $msg = $this->lang->line('e_duplicated_exam');
			}else $msg = $this->lang->line('e_select_exam');
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
				$msg = $this->lang->line('s_exam_remove');
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
			
			if ($item->session > 1) $session_txt = $this->lang->line('w_sessions');
			else $session_txt = $this->lang->line('w_session');
			$session_txt = $item->session." ".$session_txt;
			
			$unit_text = "w_";
			switch($item->frequency_unit){
				case "D": $unit_text = $unit_text."day"; break;
				case "W": $unit_text = $unit_text."week"; break;
				case "M": $unit_text = $unit_text."month"; break;
				case "Y": $unit_text = $unit_text."year"; break;
			}
			if ($item->frequency > 1) $unit_text = $unit_text."_p";
			$frequency_txt = $item->frequency." ".$this->lang->line($unit_text);
			$item->sub_txt = $session_txt.", ".$this->lang->line('t_one_session_each')." ".$frequency_txt;
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
				$appointment = $this->general->id("appointment", $data["appointment_id"]);
				$appointment->status = $this->general->id("status", $appointment->status_id);
				
				if ("reserved" !== $appointment->status->code){
					$this->general->insert("appointment_therapy", $data);
					$msg = $this->lang->line('s_therapy_add');
					$type = "success";
				}else $msg = $this->lang->line('e_app_unconfirmed');
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
			$appointment = $this->general->id("appointment", $data["appointment_id"]);
			$appointment->status = $this->general->id("status", $appointment->status_id);
			
			if ("reserved" !== $appointment->status->code){
				$this->general->delete("appointment_therapy", $data);
				$msg = $this->lang->line('s_therapy_remove');
				$type = "success";
			}else $msg = $this->lang->line('e_app_unconfirmed');
		}else $msg = $this->lang->line('error_no_permission');
		
		$therapies = $this->set_therapy_list($data["appointment_id"]);
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg, "therapies" => $therapies]);
	}
	
	private function set_medicine_list($appointment_id){
		$medicines = $this->general->filter("appointment_medicine", ["appointment_id" => $appointment_id]);
		foreach($medicines as $item){
			$item->medicine = $this->general->id("medicine", $item->medicine_id)->name;
			$item->unit = $item->quantity > 1 ? $this->lang->line('w_units') : $this->lang->line('w_unit');
			$item->dose = $item->dose_id ? $this->general->id("medicine_dose", $item->dose_id) : $this->general->structure("medicine_dose");
			$item->application_way = $item->application_way_id ? $this->general->id("medicine_application_way", $item->application_way_id) : $this->general->structure("medicine_application_way");
			$item->frequency = $item->frequency_id ? $this->general->id("medicine_frequency", $item->frequency_id) : $this->general->structure("medicine_frequency");
			$item->duration = $item->duration_id ? $this->general->id("medicine_duration", $item->duration_id) : $this->general->structure("medicine_duration");
			
			$sub_txt_arr = [];
			
			$sub_txt_arr[] = number_format($item->quantity)." ".$item->unit;
			if ($item->dose->description) $sub_txt_arr[] = $item->dose->description;
			if ($item->application_way->description) $sub_txt_arr[] = $item->application_way->description;
			if ($item->frequency->description) $sub_txt_arr[] = $item->frequency->description;
			if ($item->duration->description) $sub_txt_arr[] = $item->duration->description;
			
			$item->sub_txt = implode(", ", $sub_txt_arr);
		}
		
		usort($medicines, function($a, $b) { 
			return strcmp($a->medicine, $b->medicine);
		});
		
		return $medicines;
	}
	
	public function add_medicine(){
		$type = "error"; $msgs = []; $msg = null;
		
		if ($this->utility_lib->check_access("appointment", "update_medical_attention")){			
			$data = $this->input->post();
			
			$this->load->library('my_val');
			$msgs = $this->my_val->appointment_medicine($msgs, $data);
			
			if (!$msgs){
				$appointment = $this->general->id("appointment", $data["appointment_id"]);
				$appointment->status = $this->general->id("status", $appointment->status_id);
				
				if ("reserved" !== $appointment->status->code){
					$this->general->insert("appointment_medicine", $data);
					$msg = $this->lang->line('s_medicine_add');
					$type = "success";
				}else $msg = $this->lang->line('e_app_unconfirmed');
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
			$appointment = $this->general->id("appointment", $data["appointment_id"]);
			$appointment->status = $this->general->id("status", $appointment->status_id);
			
			if ("reserved" !== $appointment->status->code){
				$this->general->delete("appointment_medicine", $data);
				$msg = $this->lang->line('s_medicine_remove');
				$type = "success";
			}else $msg = $this->lang->line('e_app_unconfirmed');
		}else $msg = $this->lang->line('error_no_permission');
		
		$medicines = $this->set_medicine_list($data["appointment_id"]);
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg, "medicines" => $medicines]);
	}
	
	public function medical_history($id){
		if (!$this->utility_lib->check_access("appointment", "report")) redirect("/errors/no_permission");
		
		$appointment = $this->general->id("appointment", $id);
		if (!$appointment) redirect("/clinic/appointment");
		
		$doctor = $this->general->id("person", $appointment->doctor_id);
		if ($doctor){
			$data = $this->general->filter("doctor", ["person_id" => $doctor->id]);
			if ($data){
				$doctor->data = $data[0];
				$doctor->data->specialty = $this->general->id("specialty", $doctor->data->specialty_id)->name;
			}
		}else{
			$doctor = $this->general->structure("person");
			$doctor->data = $this->general->structure("doctor");
			$doctor->data->specialty = "";
		}
		
		$patient = $this->general->id("person", $appointment->patient_id);
		if ($patient){
			$patient->doc_type = $this->general->id("doc_type", $patient->doc_type_id)->description;
			if ($patient->birthday) $patient->age = $this->my_func->age_calculator($patient->birthday, true);
			else $patient->birthday = $patient->age = null;	
		}else $patient = $this->general->structure("person");
		
		$appointment_datas = $this->set_appointment_data($appointment);
		
		$data = [
			"appointment" => $appointment,
			"appointment_datas" => $appointment_datas,
			"doctor" => $doctor,
			"patient" => $patient
		];
		
		$html = $this->load->view('clinic/appointment/medical_history', $data, true);
		$filename = str_replace(" ", "_", $patient->name)."_".$patient->doc_number."_".$appointment->id;
		
		$this->load->library('dompdf_lib');
		$this->dompdf_lib->make_pdf_a4($html, $filename);
		//echo $html;
	}

	public function print_examination($id){
		$appointment = $this->general->id("appointment", $id);
		
		$doctor = $this->general->id("person", $appointment->doctor_id);
		if ($doctor){
			$doctor->age = $doctor->birthday ? $this->my_func->age_calculator($doctor->birthday, true) : "-";
			
			$data = $this->general->filter("doctor", ["person_id" => $doctor->id]);
			if ($data) $doctor->data = $data[0];
			else $doctor->data = $this->general->structure("doctor");
			
			$doctor->data->specialty = $doctor->data->specialty_id ? $this->general->id("specialty", $doctor->data->specialty_id)->name : "";
		}else{
			$doctor = $this->general->structure("person");
			$doctor->data = $this->general->structure("doctor");
		}
		
		$patient = $this->general->id("person", $appointment->patient_id);
		$patient->age = $patient->birthday ? $this->my_func->age_calculator($patient->birthday, true) : "-";
		
		$data = [
			"doctor" => $doctor,
			"patient" => $patient,
			"examination" => $this->set_profiles_exams($id),
		];
		
		$this->load->view('print_template/examination', $data);
	}
	
	public function print_image($id){
		$appointment = $this->general->id("appointment", $id);
		
		$doctor = $this->general->id("person", $appointment->doctor_id);
		if ($doctor){
			$doctor->age = $doctor->birthday ? $this->my_func->age_calculator($doctor->birthday, true) : "-";
			
			$data = $this->general->filter("doctor", ["person_id" => $doctor->id]);
			if ($data) $doctor->data = $data[0];
			else $doctor->data = $this->general->structure("doctor");
			
			$doctor->data->specialty = $doctor->data->specialty_id ? $this->general->id("specialty", $doctor->data->specialty_id)->name : "";
		}else{
			$doctor = $this->general->structure("person");
			$doctor->data = $this->general->structure("doctor");
		}
		
		$patient = $this->general->id("person", $appointment->patient_id);
		$patient->age = $patient->birthday ? $this->my_func->age_calculator($patient->birthday, true) : "-";
		
		$data = [
			"doctor" => $doctor,
			"patient" => $patient,
			"image" => $this->set_images($id),
		];
		
		$this->load->view('print_template/image', $data);
	}
	
	public function print_medicine($id){//to be removed
		$appointment = $this->general->id("appointment", $id);
		
		$doctor = $this->general->id("person", $appointment->doctor_id);
		if ($doctor){
			$doctor->age = $doctor->birthday ? $this->my_func->age_calculator($doctor->birthday, true) : "-";
			
			$data = $this->general->filter("doctor", ["person_id" => $doctor->id]);
			if ($data) $doctor->data = $data[0];
			else $doctor->data = $this->general->structure("doctor");
			
			$doctor->data->specialty = $doctor->data->specialty_id ? $this->general->id("specialty", $doctor->data->specialty_id)->name : "";
		}else{
			$doctor = $this->general->structure("person");
			$doctor->data = $this->general->structure("doctor");
		}
		
		$patient = $this->general->id("person", $appointment->patient_id);
		$patient->age = $patient->birthday ? $this->my_func->age_calculator($patient->birthday, true) : "-";
		
		$data = [
			"doctor" => $doctor,
			"patient" => $patient,
			"medicine" => $this->set_medicine_list($id),
		];
		
		$this->load->view('print_template/medicine', $data);
	}
	
	public function print_therapy($id){
		$appointment = $this->general->id("appointment", $id);
		
		$doctor = $this->general->id("person", $appointment->doctor_id);
		if ($doctor){
			$doctor->age = $doctor->birthday ? $this->my_func->age_calculator($doctor->birthday, true) : "-";
			
			$data = $this->general->filter("doctor", ["person_id" => $doctor->id]);
			if ($data) $doctor->data = $data[0];
			else $doctor->data = $this->general->structure("doctor");
			
			$doctor->data->specialty = $doctor->data->specialty_id ? $this->general->id("specialty", $doctor->data->specialty_id)->name : "";
		}else{
			$doctor = $this->general->structure("person");
			$doctor->data = $this->general->structure("doctor");
		}
		
		$patient = $this->general->id("person", $appointment->patient_id);
		$patient->age = $patient->birthday ? $this->my_func->age_calculator($patient->birthday, true) : "-";
		
		$data = [
			"doctor" => $doctor,
			"patient" => $patient,
			"therapy" => $this->set_therapy_list($id),
		];
		//print_r($data);
		$this->load->view('print_template/therapy', $data);
	}
}
