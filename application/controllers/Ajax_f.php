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
	
	public function set_appointment(){
		$dates = array(date("Y-m-d"), date("Y-m-d", strtotime("+1 day")));
		
		$filter = array("schedule_from <=" => date("Y-m-d", strtotime("-1 day"))." 23:59:59");
		$appointments = $this->general->filter("appointment", $filter, "schedule_from", "asc");
		foreach($appointments as $item){
			$nd = $dates[array_rand($dates)];
			$sf = $nd." ".date("h:i:s", strtotime($item->schedule_from));
			$st = $nd." ".date('h:i:s', strtotime('+14 minutes', strtotime($sf)));
			$this->general->update("appointment", $item->id, array("schedule_from" => $sf, "schedule_to" => $st));
		}
		
		echo "fin";
	}
	
	public function load_doctor_schedule(){
		$status = false; $data = array();
		$doctor_id = $this->input->post("doctor_id");
		$date = $this->input->post("date");
		
		if (!$doctor_id) array_push($data, "Elija un medico.");
		if (!$date) array_push($data, "Elija una fecha.");
		
		if (!$data){
			$status_ids = array($this->status->code("reserved")->id, $this->status->code("confirmed")->id);
			$appointments = $this->appointment->doctor($doctor_id, $date, $status_ids);
			if ($appointments) foreach($appointments as $item)
				array_push($data, "<div>Consulta</div><div>".date("h:i A", strtotime($item->schedule_from))." - ".date("h:i A", strtotime($item->schedule_to))."</div>");
			
			$surgeries = $this->surgery->doctor($doctor_id, $date, $status_ids);
			if ($surgeries) foreach($surgeries as $item)
				array_push($data, "<div>Cirugia</div><div>".date("h:i A", strtotime($item->schedule_from))." - ".date("h:i A", strtotime($item->schedule_to))."</div>");
			
			if (!$data) array_push($data, "Disponibilidad Completa.");
			
			$status = true;
			$type = "success";
		}
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "data" => $data));
	}
	
	public function load_schedule(){
		$res = array();
		
		$today = date("Y-m-d");
		$tomorrow = date("Y-m-d", strtotime("+1 day"));
		
		$appointments_arr = array();
		$appointments_arr[$today]["title"] = $this->lang->line('txt_today').", ".$today;
		$appointments_arr[$today]["data"] = array();
		$appointments_arr[$tomorrow]["title"] = $this->lang->line('txt_tomorrow').", ".$tomorrow;
		$appointments_arr[$tomorrow]["data"] = array();
		
		$surgeries_arr = array();
		$surgeries_arr[$today]["title"] = $this->lang->line('txt_today').", ".$today;
		$surgeries_arr[$today]["data"] = array();
		$surgeries_arr[$tomorrow]["title"] = $this->lang->line('txt_tomorrow').", ".$tomorrow;
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
				"schedule" => date("h:i A", strtotime($item->schedule_from)),
				"doctor" => $this->general->id("person", $item->doctor_id)->name,
				"patient" => $this->general->id("person", $item->patient_id)->name,
				"speciality" => $this->general->id("specialty", $item->speciality_id)->name
			);
			array_push($appointments_arr[date("Y-m-d", strtotime($item->schedule_from))]["data"], $data);
		}
		
		/*
		$surgeries = $this->general->filter("surgery", $filter, "schedule_from", "asc");
		foreach($surgeries as $item){
			$data = array(
				"id" => $item->id,
				"color" => $this->general->id("status", $item->status_id)->color,
				"schedule" => date("h:i A", strtotime($item->schedule_from)),
				"doctor" => $this->general->id("person", $item->doctor_id)->name,
				"patient" => $this->general->id("person", $item->patient_id)->name,
				"speciality" => $this->general->id("specialty", $item->speciality_id)->name
			);
			array_push($surgeries_arr[date("Y-m-d", strtotime($item->schedule_from))]["data"], $data);
		}
		*/
		$surgeries = array();
		
		header('Content-Type: application/json');
		echo json_encode(array("appointments" => $appointments_arr, "surgeries" => $surgeries_arr));
	}
	
	public function pqt(){
		$token = "84753fbe-ca2f-4895-882b-8cc4f1add2c7";
		
		$data = '';
		
		$data_j = json_decode($data);
		$companies = $data_j->companyResultPage->companies;
		foreach($companies as $item) echo $item->name."------".$item->kompassId."<br/>";
	}
	
	public function pqtec(){
		
		// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'https://apius.reqbin.com/api/v1/requests');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, '{"id":"0","name":"","errors":"","json":"{\"method\":\"POST\",\"url\":\"https://kr.kompass.com/easybusiness/company/query/?CSRFToken=84753fbe-ca2f-4895-882b-8cc4f1add2c7\",\"apiNode\":\"US\",\"contentType\":\"JSON\",\"content\":\"{\\\"pageNumber\\\":5,\\\"pageSize\\\":\\\"50\\\",\\\"sort\\\":{\\\"column\\\":\\\"COMPANY_NAME\\\",\\\"order\\\":\\\"ASC\\\",\\\"columnName\\\":null,\\\"asc\\\":false},\\\"criterias\\\":[{\\\"@type\\\":\\\"criteria\\\",\\\"index\\\":1,\\\"code\\\":\\\"companyList\\\",\\\"count\\\":4952,\\\"label\\\":\\\"all\\\",\\\"layerId\\\":null,\\\"enabled\\\":true,\\\"active\\\":true,\\\"offset\\\":0,\\\"limit\\\":null,\\\"sort\\\":null,\\\"ids\\\":[\\\"200323644899\\\"],\\\"countryCode\\\":null,\\\"ranges\\\":[],\\\"storeInSession\\\":false,\\\"family\\\":false,\\\"order\\\":null}],\\\"freeCount\\\":false}\",\"headers\":\"Host: kr.kompass.com\\nUser-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/111.0\\nAccept: application/json, text/plain, */*\\nAccept-Language: ko-KR,ko;q=0.8,en-US;q=0.5,en;q=0.3\\nAccept-Encoding: gzip, deflate, br\\nX-NewRelic-ID: XA8BUFVaGwYIVlZVDgM=\\nContent-Type: application/json;charset=utf-8\\nContent-Length: 405\\nOrigin: https://kr.kompass.com\\nConnection: keep-alive\\nReferer: https://kr.kompass.com/easybusiness\\nCookie: timezoneoffset=300; timezoneoffset=300; timezonename=America/Bogota; datadome=7CmybE6-qynbvK_0rouSKqFyxFfOqtbUXRcKSW1_oR3YeoFnnoYh-x39mxi3KNEubG2glBpPGFxjUljKbB3ZvSSYWFdez2gqP_JNI5EHm~CQur8EfTWIT3i1HjRXpcFl; route=1679326848.76.34.536509|1ca372b33d2bad9524c20eaf607b64ca; JSESSIONID=41B73048591D860AA02DD82C0B80BEF6; _k_cty_lang=en_KR; ROUTEID=.; timezoneoffset=300; kp_uuid=e26a947c-9a9b-4e46-99aa-b6c5f7c0b39c; axeptio_cookies={%22$$token%22:%22sprqd1um58emngf78yrxr9%22%2C%22$$date%22:%222023-03-20T15:41:55.164Z%22%2C%22SnapEngage%22:true%2C%22Double_Click%22:true%2C%22$$completed%22:true}; axeptio_authorized_vendors=%2CSnapEngage%2CDouble_Click%2C; axeptio_all_vendors=%2CSnapEngage%2CDouble_Click%2C; _gcl_au=1.1.723143233.1679326850; _ga=GA1.3.215288871.1679326862; _gid=GA1.3.836776840.1679326862; acceleratorSecureGUID=59f7b04e259355e81f150a763ef7bc944bae4e84; state=1; _ga=GA1.2.215288871.1679326862; _gid=GA1.2.836776840.1679326862; SnapABugHistory=1#; SnapABugVisit=283#1679326924; timezonename=America/Bogota; clientUuid=8a6d2498-ed85-48bd-ad32-b329a0c0e3f6; SnapABugRef=https%3A%2F%2Fkr.kompass.com%2Feasybusiness%23%2F%20\\nSec-Fetch-Dest: empty\\nSec-Fetch-Mode: cors\\nSec-Fetch-Site: same-origin\\nPragma: no-cache\\nCache-Control: no-cache\",\"errors\":\"\",\"curlCmd\":\"\",\"codeCmd\":\"\",\"jsonCmd\":\"\",\"xmlCmd\":\"\",\"lang\":\"\",\"auth\":{\"auth\":\"noAuth\",\"bearerToken\":\"\",\"basicUsername\":\"\",\"basicPassword\":\"\",\"customHeader\":\"\",\"encrypted\":\"\"},\"compare\":false,\"idnUrl\":\"https://kr.kompass.com/easybusiness/company/query/?CSRFToken=84753fbe-ca2f-4895-882b-8cc4f1add2c7\"}","sessionId":1679357397696,"deviceId":"4285ed99-997b-4c98-ab67-4d1e01ef8fbbR"}');


		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);

		print_r($result);
	}
}