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
				$person = array("id" => $person->id, "name" => $person->name, "tel" => $person->tel);
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
					$person = array("id" => null, "name" => $name, "tel" => null);
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
	
	public function load_doctor_schedule(){
		$cells = array(); $msg = null;
		$doctor_id = $this->input->post("doctor_id");
		$date = $this->input->post("date");
		
		if (!$doctor_id) $msg = $this->lang->line('error_select_doctor');
		if (!$date) $msg = $this->lang->line('error_select_date');
		
		if (!$msg){
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
		}
		
		echo $this->load->view('doctor/tb_schedule', array("msg" => $msg, "cells" => $cells), true);
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
	
	//test data creation
	public function make_app_sur($qty){
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
	
	public function pqt_post($page){
		echo '<meta charset="UTF-8" />';
	
		//$this->load->view('pqt');
		$url = "https://kr.kompass.com/easybusiness/company/query/?CSRFToken=437d2ee6-3ebf-4180-82a3-bdd6be2d9c47";

		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		$headers = array(
		   "Host: kr.kompass.com",
		   "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/111.0",
		   "Accept: application/json, text/plain, */*",
		   "Accept-Language: ko-KR,ko;q=0.8,en-US;q=0.5,en;q=0.3",
		   "Accept-Encoding: *",
		   "X-NewRelic-ID: XA8BUFVaGwYIVlZVDgM=",
		   "Content-Type: application/json",
		   //"Content-Length: 340",
		   "Origin: https://kr.kompass.com",
		   "Connection: keep-alive",
		   "Referer: https://kr.kompass.com/easybusiness/",
		   'Cookie: timezoneoffset=300; timezonename=America/Lima; datadome=7IwMRPCRIGfnBJIvkA5SxbfGPJ3CUtjHG5jkgoW5j0pboEdnOEDV4BrdDaGZXGsSEwndcUCyJ8MWqa8soGWEBfygsPUzHhxkh0b~oTqSvAMAUMvksx75EFmY3bAFTk~W; route=1679359672.502.36.713028|1ca372b33d2bad9524c20eaf607b64ca; JSESSIONID=01B173474CC2F92044BC25FFFCE4C8BD; _k_cty_lang=en_KR; kp_uuid=223a966a-94da-4984-b1b0-72629a884969; ROUTEID=.; axeptio_cookies={%22$$token%22:%22a3on45kc8vq9cizl2fhbsf%22%2C%22$$date%22:%222023-03-21T00:47:53.316Z%22%2C%22$$completed%22:false}; axeptio_authorized_vendors=%2C%2C; axeptio_all_vendors=%2C%2C; _gcl_au=1.1.1727085236.1679359673; _ga=GA1.3.1913626510.1679359674; _gid=GA1.3.2035161986.1679359674; timezoneoffset=300; acceleratorSecureGUID=269c256f5e7b8ce997eb8721081df2561eb79fe6; state=1; _ga=GA1.2.1913626510.1679359674; _gid=GA1.2.2035161986.1679359674; SnapABugRef=https%3A%2F%2Fkr.kompass.com%2Feasybusiness%2F%3Fj_force_login%3Don%26CSRFToken%3D306e0b51-77c6-474a-814f-b63ca988cda5%23%2F%20https%3A%2F%2Fkr.kompass.com%2F%3Fkick%3Dtrue; SnapABugHistory=1#; SnapABugVisit=2#1679359698; clientUuid=b9df729c-f1cd-43c5-b715-f531e344affc',
		   "Sec-Fetch-Dest: empty",
		   "Sec-Fetch-Mode: cors",
		   "Sec-Fetch-Site: same-origin",
		   "Pragma: no-cache",
		   "Cache-Control: no-cache",
		   "TE: trailers",
		);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

		$data = '{"pageNumber":'.$page.',"pageSize":"50","sort":null,"criterias":[{"@type":"criteria","index":1,"code":"companyList","count":4952,"label":"all","layerId":null,"enabled":true,"active":true,"offset":0,"limit":null,"sort":null,"ids":["200323644899"],"countryCode":null,"ranges":[],"storeInSession":false,"family":false,"order":null}],"freeCount":false}';

		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

		//for debug only!
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

		$resp = json_decode(curl_exec($curl));
		
		curl_close($curl);
		if ($resp) return $resp->companyResultPage->companies;
		else return array();
	}
	
	public function pqt_list(){
		$start = 0;
		$end = $start + 110;
		
		echo "<table style='width: 100%;'>";
		for($i = $start; $i < $end; $i++){
			//sleep(3);
			$companies = $this->pqt_post($i);
			foreach($companies as $item){
				echo "<tr><td>".$i."</td><td>".$item->kompassId."</td><td>".$item->country."</td><td>".$item->name."</td><td>".$item->addressComplement."</td><td>".$item->phone."</td></tr>";
			}
		}
		echo "</table>";
	}
	
	public function pqt_detail($i){
		echo '<meta charset="UTF-8" />';
	
		//$this->load->view('pqt');
		$url = "https://kr.kompass.com/easybusiness/company/detail?CSRFToken=46c868e6-a9c7-469d-aff5-2837e1ce4c22";

		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		$headers = array(
		   "Host: kr.kompass.com",
"User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/111.0",
"Accept: application/json, text/plain, */*",
"Accept-Language: ko-KR,ko;q=0.8,en-US;q=0.5,en;q=0.3",
"Accept-Encoding: *",
"X-NewRelic-ID: XA8BUFVaGwYIVlZVDgM=",
"Content-Type: application/json;charset=utf-8",
"Origin: https://kr.kompass.com",
"Connection: keep-alive",
"Referer: https://kr.kompass.com/easybusiness",
'Cookie: route=1680065770.459.34.453554|1ca372b33d2bad9524c20eaf607b64ca; timezoneoffset=300; timezonename=America/Lima; datadome=51-amLKcJV05JqppybO405aVVV43OhM2xSgo8zxTgMOEudPP~U85Ifs2rlHV9GMdQ~t1u7Z7NdDpZDoh61N-whzl_nFbRe3OujFhwpSCSDVEXm6rxuyDQ0eoquLC0Qd4; kp_uuid=003af787-bbac-4c53-bd65-b35d2154259d; axeptio_cookies={%22$$token%22:%22a3on45kc8vq9cizl2fhbsf%22%2C%22$$date%22:%222023-03-23T03:54:28.176Z%22%2C%22SnapEngage%22:true%2C%22Double_Click%22:true%2C%22$$completed%22:true}; axeptio_authorized_vendors=%2CSnapEngage%2CDouble_Click%2C; axeptio_all_vendors=%2CSnapEngage%2CDouble_Click%2C; _gcl_au=1.1.1727085236.1679359673; _ga=GA1.3.1913626510.1679359674; _ga=GA1.2.1913626510.1679359674; SnapABugHistory=1#; _gid=GA1.3.1181550127.1679975836; route=1680065780.026.34.172487|1ca372b33d2bad9524c20eaf607b64ca; timezonename=America/Lima; timezoneoffset=300; _k_cty_lang=en_KR; ROUTEID=.; JSESSIONID=8987A0C7ED1FF4075D0D4086EBD6FE35; acceleratorSecureGUID=5e43ed3650f52610c6a511f99d9a1b3b44a1999a; state=1; SnapABugVisit=49#1679359698; _gat_UA-45018983-1=1; SnapABugRef=https%3A%2F%2Fkr.kompass.com%2Flogin%20https%3A%2F%2Fkr.kompass.com%2Feasybusiness%2F; _gid=GA1.2.1181550127.1679975836; _gat_gtag_UA_45018983_1=1; clientUuid=86ceec83-650d-489a-a12e-a48888205b02',
"Sec-Fetch-Dest: empty",
"Sec-Fetch-Mode: cors",
"Sec-Fetch-Site: same-origin",
"Pragma: no-cache",
"Cache-Control: no-cache",
"TE: trailers",


		);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

		$data = '{"start":'.$i.',"end":1,"companyNumber":null,"criterias":[{"@type":"criteria","index":1,"code":"localisation","enabled":true,"order":"008","countryCode":null,"count":851355,"label":"Brazil","ids":["BR"],"ranges":[],"active":true}],"sort":null}';
		
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

		//for debug only!
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

		$resp = json_decode(curl_exec($curl));
		
		curl_close($curl);
		if ($resp) return $resp->companies;
		else return array();
	}
	
	public function pqt(){
		set_time_limit(600000); 
		ini_set('display_errors','0'); 
		
		$start = 114071;//851500;
		$end = $start + 2;//100000;
		
		echo "<table style='width: 100%;'>";
		for($i = $start; $i < $end; $i++){
			//sleep(3);
			$companies = $this->pqt_detail($i);
			foreach($companies as $item){
				if ($item->mainActivities){
					$ma = array();
					foreach($item->mainActivities as $m) array_push($ma, explode(" - ", $m->label)[1]);
					$item->mainActivities = implode("; ", $ma);
				}else $item->mainActivities = "";
				
				if ($item->websites) $item->websites = implode(", ",array_unique($item->websites)); else $item->websites = "";
				if ($item->emails) $item->emails = implode(", ",array_unique($item->emails)); else $item->emails = "";
				if ($item->phones) $item->phones = implode(", ",array_unique($item->phones)); else $item->phones = "";
				
				$aux_executives = array();
				if ($item->executives){
					$item->executives_txt = $item->executives[0]->name."</td><td>".$item->executives[0]->functionName;
				}else $item->executives_txt = "</td><td>";
				
				echo "<tr><td>".$i."</td><td>".$item->companyId."</td><td>".$item->country."</td><td>".$item->city."</td><td>".$item->name."</td><td>".$item->websites."</td><td>".$item->executives_txt."</td><td>".$item->emails."</td><td>".$item->phones."</td><td>".$item->addressLine1."</td><td>".$item->creationYear."</td><td>".$item->mainActivities."</td></tr>";
			}
		}
		echo "</table>";
	}
}