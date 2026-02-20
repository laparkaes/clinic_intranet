<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Appointment_print extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/Lima');
		$this->lang->load("appointment", "spanish");
		$this->lang->load("system", "spanish");
		
		$this->load->model('general_model','general');
		$this->nav_menu = ["clinic", "appointment"];
		$this->nav_menus = $this->utility_lib->get_visible_nav_menus();
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
			
			$sub_txt_arr[] = number_format($item->quantity);//." ".$item->unit;
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
			foreach($exams as $item) $item->type = $this->lang->line('w_exam');	
		}else $exams = [];
		
		return ["profiles" => $profiles, "exams" => $exams];
	}
	
	private function set_images($app_id){
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
		if ($result){
			$result = $result[0];
			$result->type = $this->general->id("diagnosis_type", $result->diagnosis_type_id)->description;
		}else{
			$result = $this->general->structure("appointment_result");
			$result->type = "";
		}
		
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
	
	public function prescription($id){
		$appointment = $this->general->id("appointment", $id);
		
		$diags = $this->general->filter("appointment_diag_impression", ["appointment_id" => $id]);
		$diag = $diags ? $this->general->id("diag_impression_detail", $diags[0]->diag_id) : $this->general->structure("diag_impression_detail");//take first diagnostic
		
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
			"diag" => $diag,
			"medicine" => $this->set_medicine_list($id),
		];
		
		$this->load->view('clinic/appointment_print/prescription', $data);
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
		
		$html = $this->load->view('clinic/appointment_print/medical_history_pdf', $data, true);
		$filename = str_replace(" ", "_", $patient->name)."_".$patient->doc_number."_".$appointment->id;
		
		$this->load->library('dompdf_lib');
		$this->dompdf_lib->make_pdf_a4($html, $filename);
		//echo $html;
	}






	public function examination($id){
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
		
		$this->load->view('clinic/appointment_print/examination', $data);
	}
	
	public function image($id){
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
		
		$this->load->view('clinic/appointment_print/image', $data);
	}
	
	public function therapy($id){
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
		$this->load->view('clinic/appointment_print/therapy', $data);
	}
}
