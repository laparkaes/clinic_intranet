<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/* My validations */
class My_val{
	
	public function __construct(){
		$this->CI = &get_instance();
		$this->CI->lang->load("validation", "spanish");
		$this->CI->load->model('general_model','general');
	}
	
	public function set_msg($msgs, $dom_id, $type, $msg_code){
		//if ($msg_code) array_push($msgs, array("dom_id" => $dom_id, "type" => $type, "msg" => $this->lang->line($msg_code)));
		if ($msg_code) $msgs[] = ["dom_id" => $dom_id, "type" => $type, "msg" => $this->CI->lang->line($msg_code)];
		return $msgs;
	}
	
	public function person($msgs, $prefix, $data){
		if (!$data["name"]) $msgs = $this->set_msg($msgs, $prefix."name_msg", "error", "e_enter_name");
		if (!$data["tel"]) $msgs = $this->set_msg($msgs, $prefix."tel_msg", "error", "e_enter_tel");
		if (!$data["doc_type_id"]) $msgs = $this->set_msg($msgs, $prefix."doc_type_msg", "error", "e_select_doc_type");
		if (!$data["doc_number"]) $msgs = $this->set_msg($msgs, $prefix."doc_number_msg", "error", "e_enter_doc_number");
		if (array_key_exists("email", $data))
			if ($data["email"]) 
				if (!filter_var($data["email"], FILTER_VALIDATE_EMAIL))
					$msgs = $this->set_msg($msgs, $prefix."email_msg", "error", "e_enter_email_format");
		/* optionals: $p["birthday"], $p["sex"], $p["blood_type"], $p["address"] */
		
		return $msgs;
	}
	
	public function doctor($msgs, $prefix, $data){
		if ($data["specialty_id"] and $data["license"]){
			if ($this->CI->general->filter("doctor", $data)) 
				$msgs = $this->set_msg($msgs, $prefix."license_msg", "error", "e_doctor_exists");
		}else{
			if (!$data["specialty_id"]) $msgs = $this->set_msg($msgs, $prefix."specialty_msg", "error", "e_select_specialty");
			if (!$data["license"]) $msgs = $this->set_msg($msgs, $prefix."license_msg", "error", "e_enter_license");
		}
		
		return $msgs;
	}
	
	public function account($msgs, $prefix, $data){
		$msgs = $this->email($msgs, $prefix, $data);
		
		if ($data["password"]){
			if (strlen($data["password"]) >= 6){
				if (strcmp($data["password"], $data["confirm"])) 
					$msgs = $this->set_msg($msgs, $prefix."confirm_msg", "error", "e_password_confirm");
			}else $msgs = $this->set_msg($msgs, $prefix."password_msg", "error", "e_password_length");
		}else $msgs = $this->set_msg($msgs, $prefix."password_msg", "error", "e_enter_password");
		
		return $msgs;
	}
	
	public function email($msgs, $prefix, $data){
		if ($data["email"]){
			if (filter_var($data["email"], FILTER_VALIDATE_EMAIL)){
				if ($this->CI->general->filter("account", ["email" => $data["email"]])) 
					$msgs = $this->set_msg($msgs, $prefix."email_msg", "error", "e_account_exists");
			}else $msgs = $this->set_msg($msgs, $prefix."email_msg", "error", "e_enter_email_format");
		}else $msgs = $this->set_msg($msgs, $prefix."email_msg", "error", "e_enter_account");
		
		return $msgs;
	}
	
	public function schedule($msgs, $prefix, $data){
		if (!$data["date"]) $msgs = $this->set_msg($msgs, $prefix."schedule_msg", "error", "e_select_date");
		elseif (!$data["hour"]) $msgs = $this->set_msg($msgs, $prefix."schedule_msg", "error", "e_select_hour");
		elseif (!$data["min"]) $msgs = $this->set_msg($msgs, $prefix."schedule_msg", "error", "e_select_minute");
		
		return $msgs;
	}
	
	public function appointment($msgs, $prefix, $app, $sch, $pt){
		$msgs = $this->person($msgs, $prefix."pt_", $pt);//patient
		$msgs = $this->schedule($msgs, $prefix, $sch);//schedule
		
		//appointment data
		if (!$app["specialty_id"]) $msgs = $this->set_msg($msgs, $prefix."specialty_msg", "error", "e_select_specialty");
		if (!$app["doctor_id"]) $msgs = $this->set_msg($msgs, $prefix."doctor_msg", "error", "e_select_doctor");
		
		//appointment availability
		if (!$msgs){
			//check doctor = patient?
			if ($pt["doc_type_id"] and $pt["doc_number"]){
				$f = ["doc_type_id" => $pt["doc_type_id"], "doc_number" => $pt["doc_number"]];
				$person = $this->CI->general->filter("person", $f);
				if ($person) 
					if ($app["doctor_id"] == $person[0]->id) 
						$msgs = $this->set_msg($msgs, $prefix."doctor_msg", "error", "e_person_doctor_patient");
			}
			
			//status ids for filter
			$app["schedule_from"] = $sch["date"]." ".$sch["hour"].":".$sch["min"];
			$app["schedule_to"] = date("Y-m-d H:i:s", strtotime("+14 minutes", strtotime($app["schedule_from"])));
			$status_ids = [
				$this->CI->general->filter("status", ["code" => "reserved"])[0]->id,
				$this->CI->general->filter("status", ["code" => "confirmed"])[0]->id
			];
			
			//check appointment and surgery available
			$sur_available = $this->CI->general->is_available("surgery", $app, $status_ids);
			$app_available = $this->CI->general->is_available("appointment", $app, $status_ids);
			if (!($sur_available and $app_available)) 
				$msgs = $this->set_msg($msgs, $prefix."schedule_msg", "error", "e_doctor_no_available");
		}
		
		return $msgs;
	}
	
	public function appointment_reschedule($msgs, $prefix, $appointment, $data){
		$msgs = $this->schedule($msgs, $prefix, $data);//schedule
		
		if ($appointment){
			$schedule_from = $data["date"]." ".$data["hour"].":".$data["min"];
			$app = [
				"id" => $appointment->id,
				"doctor_id" => $appointment->doctor_id,
				"schedule_from" => $schedule_from,
				"schedule_to" => date("Y-m-d H:i:s", strtotime("+14 minutes", strtotime($schedule_from)))
			];
			
			$status_ids = [
				$this->CI->general->filter("status", ["code" => "reserved"])[0]->id,
				$this->CI->general->filter("status", ["code" => "confirmed"])[0]->id
			];
			
			//check appointment and surgery available
			$sur_available = $this->CI->general->is_available("surgery", $app, $status_ids);
			$app_available = $this->CI->general->is_available("appointment", $app, $status_ids);
			if (!($sur_available and $app_available)) 
				$msgs = $this->set_msg($msgs, $prefix."schedule_msg", "error", "e_doctor_no_available");
		}
		
		return $msgs;
	}
	
	public function appointment_basic_data($msgs, $data){
		//data validation
		if (!$data["entry_mode"]) $msgs = $this->set_msg($msgs, "bd_entry_mode_msg", "error", "e_entry_mode");
		if (!$data["date"]) $msgs = $this->set_msg($msgs, "bd_date_msg", "error", "e_select_date");
		if (!$data["time"]) $msgs = $this->set_msg($msgs, "bd_time_msg", "error", "e_select_hour");
		
		//insurance validation
		
		if ($data["insurance"] !== ""){
			if ($data["insurance"] === "1")
				if (!$data["insurance_name"])
					$msgs = $this->set_msg($msgs, "bd_insurance_name_msg", "error", "e_insurance_name");
		}else $msgs = $this->set_msg($msgs, "bd_insurance_msg", "error", "e_insurance_confirm");
		
		return $msgs;
	}
	
	public function surgery($msgs, $prefix, $sur, $sch, $pt){
		$msgs = $this->person($msgs, $prefix."pt_", $pt);//patient
		$msgs = $this->schedule($msgs, $prefix, $sch);//schedule
		if (!$sch["duration"]) $msgs = $this->set_msg($msgs, $prefix."duration_msg", "error", "e_select_duration");
		
		//surgery data
		if (!$sur["specialty_id"]) $msgs = $this->set_msg($msgs, $prefix."specialty_msg", "error", "e_select_specialty");
		if (!$sur["doctor_id"]) $msgs = $this->set_msg($msgs, $prefix."doctor_msg", "error", "e_select_doctor");
		if (!$sur["room_id"]) $msgs = $this->set_msg($msgs, $prefix."room_msg", "error", "e_select_room");
		
		//appointment availability
		if (!$msgs){
			//check doctor = patient?
			if ($pt["doc_type_id"] and $pt["doc_number"]){
				$f = ["doc_type_id" => $pt["doc_type_id"], "doc_number" => $pt["doc_number"]];
				$person = $this->CI->general->filter("person", $f);
				if ($person) 
					if ($sur["doctor_id"] == $person[0]->id) 
						$msgs = $this->set_msg($msgs, $prefix."doctor_msg", "error", "e_person_doctor_patient");
			}
			
			//status ids for filter
			$sur["schedule_from"] = $sch["date"]." ".$sch["hour"].":".$sch["min"];
			$sur["schedule_to"] = date("Y-m-d H:i:s", strtotime("+".($sch["duration"]-1)." minutes", strtotime($sur["schedule_from"])));
			$status_ids = [
				$this->CI->general->filter("status", ["code" => "reserved"])[0]->id,
				$this->CI->general->filter("status", ["code" => "confirmed"])[0]->id
			];
			
			//check appointment and surgery available
			$sur_available = $this->CI->general->is_available("surgery", $sur, $status_ids);
			$app_available = $this->CI->general->is_available("appointment", $sur, $status_ids);
			if (!($sur_available and $app_available)) 
				$msgs = $this->set_msg($msgs, $prefix."schedule_msg", "error", "e_doctor_no_available");
			
			//check room availability
			if ($this->CI->general->get_by_room("surgery", $sur, $status_ids, null, $sur["room_id"])) 
				$msgs = $this->set_msg($msgs, $prefix."room_msg", "error", "e_room_no_available");
		}
		
		return $msgs;
	}
	
	public function file_upload($msgs, $prefix, $title, $filename){
		if (!$title) $msgs = $this->set_msg($msgs, $prefix."title_msg", "error", "e_enter_file_title");
		if (!$filename) $msgs = $this->set_msg($msgs, $prefix."file_msg", "error", "e_select_file");
		
		return $msgs;
	}
}