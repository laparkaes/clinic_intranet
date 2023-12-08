<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/Lima');
		setlocale(LC_TIME, 'spanish.utf8');
		$this->lang->load("dashboard", "spanish");
		$this->lang->load("system", "spanish");
		$this->load->model('general_model','general');
		$this->nav_menu = "dashboard";
		$this->nav_menus = $this->utility_lib->get_visible_nav_menus();
	}
	
	public function index(){
		if (!$this->session->userdata('logged_in')) redirect("/");
		if (!$this->utility_lib->check_access("dashboard", "index")) redirect("/errors/no_permission");
		
		$role_name = $this->session->userdata('role')->name;
		
		$data = array(
			"init_js" => "dashboard/".$role_name.".js",
			"title" => $this->lang->line('dashboard'),
			"main" => "dashboard/".$role_name,
		);
		
		switch($role_name){
			case "master": $data = $this->set_master_datas($data); break;
			case "admin": $data = $this->set_admin_datas($data); break;
			case "reception": $data = $this->set_reception_datas($data); break;
			case "doctor": $data = $this->set_doctor_datas($data); break;
			case "nurse": $data = $this->set_nurse_datas($data); break;
		}
		$this->load->view('layout', $data);
	}
	
	private function set_nurse_datas($data){
		//set profile
		$account = $this->general->id("account", $this->session->userdata('aid'));
		$data["profile"] = [
			"email" => $account->email,
			"name" => $this->general->id("person", $account->person_id)->name,
			"role" => $this->lang->line($this->general->id("role", $account->role_id)->name),
		];
		
		//set schedules
		$filter = [
			"status_id" => $this->general->status("confirmed")->id,
			"schedule_from >=" => date('Y-m-d 00:00:00'), 
			"schedule_from <=" => date('Y-m-d 23:59:59'),
		];
		
		$schedules = [];
		$apps = $this->general->filter("appointment", $filter);
		foreach($apps as $item) $schedules[] = ["from" => $item->schedule_from, "type" => "appointment", "patient_id" => $item->patient_id, "id" => $item->id];
		
		$surs = $this->general->filter("surgery", $filter);
		foreach($surs as $item) $schedules[] = ["from" => $item->schedule_from, "type" => "surgery", "patient_id" => $item->patient_id, "id" => $item->id];
		
		usort($schedules, function($a, $b) { return ($a["from"] > $b["from"]); });
		foreach($schedules as $i => $item) $schedules[$i]["patient"] = $this->general->id("person", $item["patient_id"])->name;
		
		$data["schedules"] = $schedules;
		
		return $data;
	}
	
	private function set_doctor_datas($data){
		//set monthly resume
		$from = date('Y-m-01 00:00:00');
		$to = date('Y-m-t 23:59:59');
		$s_finished = $this->general->status("finished");
		$filter = [
			"doctor_id" => $this->session->userdata("pid"), 
			"status_id" => $s_finished->id,
			"schedule_from >=" => $from, 
			"schedule_from <=" => $to,
		];
		
		$patient_arr = [];
		$patient_rec = array_merge($this->general->only("appointment", "patient_id", $filter), $this->general->only("surgery", "patient_id", $filter));
		foreach($patient_rec as $item) $patient_arr[] = $item->patient_id;
		array_unique($patient_arr);
		
		$data["appointment_qty"] = $this->general->counter("appointment", $filter);
		$data["surgery_qty"] = $this->general->counter("surgery", $filter);
		$data["patient_qty"] = count($patient_arr);
		
		//set profile
		unset($filter["schedule_from >="]);
		unset($filter["schedule_from <="]);
		
		$patient_arr = [];
		$patient_rec = array_merge($this->general->only("appointment", "patient_id", $filter), $this->general->only("surgery", "patient_id", $filter));
		foreach($patient_rec as $item) $patient_arr[] = $item->patient_id;
		array_unique($patient_arr);
		
		$account = $this->general->id("account", $this->session->userdata('aid'));
		$doctor = $this->general->filter("doctor", ["person_id" => $account->person_id], null, null, "registed_at", "desc");
		if ($doctor) $doctor = $doctor[0];
		else $doctor = null;
		
		$data["profile"] = [
			"email" => $account->email,
			"name" => $this->general->id("person", $account->person_id)->name,
			"license" => ($doctor != null ? $doctor->license : ""),
			"specialty" => ($doctor != null ? $this->general->id("specialty", $doctor->specialty_id)->name : ""),
			"role" => $this->lang->line($this->general->id("role", $account->role_id)->name),
			"appointment_qty" => $this->general->counter("appointment", $filter),
			"surgery_qty" => $this->general->counter("surgery", $filter),
			"patient_qty" => count($patient_arr),
		];
		
		//set schedules
		$filter["status_id"] = $this->general->status("confirmed")->id;
		$filter["schedule_from >="] = date('Y-m-d 00:00:00');
		$filter["schedule_from <="] = date('Y-m-d 23:59:59');
		
		$schedules = [];
		$apps = $this->general->filter("appointment", $filter);
		foreach($apps as $item) $schedules[] = ["from" => $item->schedule_from, "type" => "appointment", "patient_id" => $item->patient_id, "id" => $item->id];
		
		$surs = $this->general->filter("surgery", $filter);
		foreach($surs as $item) $schedules[] = ["from" => $item->schedule_from, "type" => "surgery", "patient_id" => $item->patient_id, "id" => $item->id];
		
		usort($schedules, function($a, $b) { return ($a["from"] > $b["from"]); });
		foreach($schedules as $i => $item) $schedules[$i]["patient"] = $this->general->id("person", $item["patient_id"])->name;
		
		$data["schedules"] = $schedules;
		
		return $data;
	}
	
	private function set_reception_datas($data){
		//set profile
		$account = $this->general->id("account", $this->session->userdata('aid'));
		$data["profile"] = [
			"email" => $account->email,
			"name" => $this->general->id("person", $account->person_id)->name,
			"role" => $this->lang->line($this->general->id("role", $account->role_id)->name),
		];
		
		//set schedules
		$filter = [
			"status_id" => $this->general->status("confirmed")->id,
			"schedule_from >=" => date('Y-m-d 00:00:00'), 
			"schedule_from <=" => date('Y-m-d 23:59:59'),
		];
		
		$schedules = [];
		$apps = $this->general->filter("appointment", $filter);
		foreach($apps as $item) $schedules[] = ["from" => $item->schedule_from, "type" => "appointment", "patient_id" => $item->patient_id, "id" => $item->id];
		
		$surs = $this->general->filter("surgery", $filter);
		foreach($surs as $item) $schedules[] = ["from" => $item->schedule_from, "type" => "surgery", "patient_id" => $item->patient_id, "id" => $item->id];
		
		usort($schedules, function($a, $b) { return ($a["from"] > $b["from"]); });
		foreach($schedules as $i => $item) $schedules[$i]["patient"] = $this->general->id("person", $item["patient_id"])->name;
		
		$data["schedules"] = $schedules;
		
		return $data;
	}
	
	private function set_admin_datas($data){
		//set profile
		$account = $this->general->id("account", $this->session->userdata('aid'));
		$data["profile"] = [
			"email" => $account->email,
			"name" => $this->general->id("person", $account->person_id)->name,
			"role" => $this->lang->line($this->general->id("role", $account->role_id)->name),
			"doctor_qty" => number_format($this->general->counter("doctor", [])),
			"patient_qty" => number_format($this->general->counter("person", [])),
			"account_qty" => number_format($this->general->counter("account", [])),
		];
		
		//set monthly resume
		$from = date('Y-m-01 00:00:00');
		$to = date('Y-m-t 23:59:59');
		$s_finished = $this->general->status("finished");
		$filter_1 = ["schedule_from >=" => $from, "schedule_from <=" => $to, "status_id" => $s_finished->id];
		$filter_2 = ["updated_at >=" => $from, "updated_at <=" => $to, "status_id" => $s_finished->id];
		$data["appointment_qty"] = $this->general->counter("appointment", $filter_1);
		$data["surgery_qty"] = $this->general->counter("surgery", $filter_1);
		$data["sale_qty"] = $this->general->counter("sale", $filter_2);
		
		//set currency for chart
		$filter["updated_at >="] = date('Y-m-01 00:00:00', strtotime(date("Y-m-d", strtotime("-5 months"))));
		$filter["updated_at <="] = date('Y-m-t 23:59:59', strtotime(date("Y-m-d")));
		$currencies = $this->general->all("currency", "id", "asc");
		foreach($currencies as $i => $item){
			$filter["currency_id"] = $item->id;
			if (!$this->general->sum("sale", "total", $filter)->total) unset($currencies[$i]);
		}
		$data["currencies"] = $currencies;
		
		//set sales without voucher
		$cur_arr = [];
		$cur = $this->general->all("currency");
		foreach($cur as $item) $cur_arr[$item->id] = $item->description;
		
		$sales = $this->general->filter("sale", ["voucher_id" => null, "balance" => 0], null, null, "registed_at", "desc", 10, 0);
		foreach($sales as $item) $item->currency = $cur_arr[$item->currency_id];
		$data["sales"] = $sales;
		
		//set voucher with sunat error
		$vou_arr = [];
		$vou = $this->general->all("voucher_type");
		foreach($vou as $item) $vou_arr[$item->id] = $item->description;
		
		$vouchers = $this->general->filter("voucher", ["sunat_sent !=" => 1, "sunat_resume_id" => null], null, null, "registed_at", "desc", 10, 0);
		foreach($vouchers as $item) $item->voucher_type = $vou_arr[$item->voucher_type_id];
		$data["vouchers"] = $vouchers;
		
		return $data;
	}
	
	private function set_master_datas($data){
		//set profile
		$account = $this->general->id("account", $this->session->userdata('aid'));
		$data["profile"] = [
			"email" => $account->email,
			"name" => $this->general->id("person", $account->person_id)->name,
			"role" => $this->lang->line($this->general->id("role", $account->role_id)->name),
			"doctor_qty" => number_format($this->general->counter("doctor", [])),
			"patient_qty" => number_format($this->general->counter("person", [])),
			"account_qty" => number_format($this->general->counter("account", [])),
		];
		
		//set monthly resume
		$from = date('Y-m-01 00:00:00');
		$to = date('Y-m-t 23:59:59');
		$s_finished = $this->general->status("finished");
		$filter_1 = ["schedule_from >=" => $from, "schedule_from <=" => $to, "status_id" => $s_finished->id];
		$filter_2 = ["updated_at >=" => $from, "updated_at <=" => $to, "status_id" => $s_finished->id];
		$data["appointment_qty"] = $this->general->counter("appointment", $filter_1);
		$data["surgery_qty"] = $this->general->counter("surgery", $filter_1);
		$data["sale_qty"] = $this->general->counter("sale", $filter_2);
		
		//set currency for chart
		$filter["updated_at >="] = date('Y-m-01 00:00:00', strtotime(date("Y-m-d", strtotime("-5 months"))));
		$filter["updated_at <="] = date('Y-m-t 23:59:59', strtotime(date("Y-m-d")));
		$currencies = $this->general->all("currency", "id", "asc");
		foreach($currencies as $i => $item){
			$filter["currency_id"] = $item->id;
			if (!$this->general->sum("sale", "total", $filter)->total) unset($currencies[$i]);
		}
		$data["currencies"] = $currencies;
		
		//set sales without voucher
		$cur_arr = [];
		$cur = $this->general->all("currency");
		foreach($cur as $item) $cur_arr[$item->id] = $item->description;
		
		$sales = $this->general->filter("sale", ["voucher_id" => null, "balance" => 0], null, null, "registed_at", "desc", 10, 0);
		foreach($sales as $item) $item->currency = $cur_arr[$item->currency_id];
		$data["sales"] = $sales;
		
		//set voucher with sunat error
		$vou_arr = [];
		$vou = $this->general->all("voucher_type");
		foreach($vou as $item) $vou_arr[$item->id] = $item->description;
		
		$vouchers = $this->general->filter("voucher", ["sunat_sent !=" => 1, "sunat_resume_id" => null], null, null, "registed_at", "desc", 10, 0);
		foreach($vouchers as $item) $item->voucher_type = $vou_arr[$item->voucher_type_id];
		$data["vouchers"] = $vouchers;
		
		return $data;
	}
	
	public function load_income_chart(){
		$currency = $this->general->id("currency", $this->input->post("currency_id"));
		
		$xaxis = $series = [];
		$filter = ["currency_id" => $currency->id];
		$start = date("Y-m-01", time());
		
		for($i = 5; $i >= 0; $i--){
			//xaxis
			$actual = date("Y-m-01", strtotime("-".$i." months", strtotime($start)));
			$aux = DateTime::createFromFormat("Y-m-d", $actual)->getTimestamp();
			$month = substr(ucfirst(strftime("%B", $aux)), 0, 3);
			if (strftime("%m", $aux) == 1) $month = $month." ".strftime("%Y", $aux);
			
			$xaxis[] = $month;
		}
		
		$sale_types = $this->general->all("sale_type");
		foreach($sale_types as $item){
			$chart_data = ["name" => $item->description, "data" => []];
			for($i = 5; $i >= 0; $i--){
				//xaxis
				$actual = date("Y-m-01", strtotime("-".$i." months", strtotime($start)));
				$filter["updated_at >="] = date('Y-m-01 00:00:00', strtotime($actual));
				$filter["updated_at <="] = date('Y-m-t 23:59:59', strtotime($actual));
				$filter["sale_type_id"] = $item->id;
				$chart_data["data"][] = round($this->general->sum("sale", "total", $filter)->total, 2);
			}
			$series[] = $chart_data;
		}
		
		header('Content-Type: application/json');
		echo json_encode(["series" => $series, "xaxis" => $xaxis]);
	}
}
