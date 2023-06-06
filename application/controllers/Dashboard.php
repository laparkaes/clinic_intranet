<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/Lima');
		setlocale(LC_TIME, 'spanish');
		$this->lang->load("dashboard", "spanish");
		$this->lang->load("system", "spanish");
		$this->load->model('appointment_model','appointment');
		$this->load->model('status_model','status');
		$this->load->model('sl_option_model','sl_option');
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
			"title" => $this->lang->line('dashboard')." - ".$this->lang->line($role_name),
			"main" => "dashboard/".$role_name,
		);
		
		switch($role_name){
			case "master": $data = $this->set_master_datas($data); break;
			case "admin": $data = $this->set_admin_datas($data); break;
			case "reception": $data = $this->set_reception_datas($data); break;
			case "doctor": $data = $this->set_doctor_datas($data); break;
		}
		$this->load->view('layout', $data);
	}
	
	private function set_doctor_datas($data){
		$account = $this->general->id("account", $this->session->userdata("aid"));
		
		//set monthly resume
		$from = date('Y-m-d 00:00:00');
		$to = date('Y-m-d 23:59:59');
		
		$filter = ["schedule_from >=" => $from, "schedule_from <=" => $to, "doctor_id" => $account->person_id];
		
		$apps = $this->general->filter("appointment", $filter, null, null, "schedule_from", "asc");
		foreach($apps as $item){
			$item->from = date("h:i a", strtotime($item->schedule_from));
			$item->specialty = $this->general->id("specialty", $item->specialty_id)->name;
			$item->doctor = $this->general->id("person", $item->doctor_id)->name;
			$item->patient = $this->general->id("person", $item->patient_id)->name;
			$item->status = $this->general->id("status", $item->status_id);
		}
		
		$surs = $this->general->filter("surgery", $filter, null, null, "schedule_from", "asc");
		foreach($surs as $item){
			$item->from = date("h:i a", strtotime($item->schedule_from));
			$item->specialty = $this->general->id("specialty", $item->specialty_id)->name;
			$item->doctor = $this->general->id("person", $item->doctor_id)->name;
			$item->patient = $this->general->id("person", $item->patient_id)->name;
			$item->status = $this->general->id("status", $item->status_id);
		}
		
		$data["apps"] = $apps;
		$data["surs"] = $surs;
		
		return $data;
	}
	
	private function set_reception_datas($data){
		//set monthly resume
		$from = date('Y-m-d 00:00:00');
		$to = date('Y-m-d 23:59:59');
		
		$filter = ["schedule_from >=" => $from, "schedule_from <=" => $to];
		
		$apps = $this->general->filter("appointment", $filter, null, null, "schedule_from", "asc");
		foreach($apps as $item){
			$item->from = date("h:i a", strtotime($item->schedule_from));
			$item->specialty = $this->general->id("specialty", $item->specialty_id)->name;
			$item->doctor = $this->general->id("person", $item->doctor_id)->name;
			$item->patient = $this->general->id("person", $item->patient_id)->name;
			$item->status = $this->general->id("status", $item->status_id);
		}
		
		$surs = $this->general->filter("surgery", $filter, null, null, "schedule_from", "asc");
		foreach($surs as $item){
			$item->from = date("h:i a", strtotime($item->schedule_from));
			$item->specialty = $this->general->id("specialty", $item->specialty_id)->name;
			$item->doctor = $this->general->id("person", $item->doctor_id)->name;
			$item->patient = $this->general->id("person", $item->patient_id)->name;
			$item->status = $this->general->id("status", $item->status_id);
		}
		
		$data["apps"] = $apps;
		$data["surs"] = $surs;
		
		return $data;
	}
	
	private function set_admin_datas($data){
		//set monthly resume
		$from = date('Y-m-d 00:00:00');
		$to = date('Y-m-d 23:59:59');
		$s_finished = $this->general->filter("status", ["code" => "finished"])[0];
		$s_reserved = $this->general->filter("status", ["code" => "reserved"])[0];
		
		$filter_f = ["schedule_from >=" => $from, "schedule_from <=" => $to, "status_id" => $s_finished->id];
		$filter_r = ["schedule_from >=" => $from, "schedule_from <=" => $to, "status_id" => $s_reserved->id];
		
		$data["app_attended"] = $this->general->counter("appointment", $filter_f);
		$data["app_reserved"] = $this->general->counter("appointment", $filter_r);
		$data["sur_attended"] = $this->general->counter("surgery", $filter_f);
		$data["sur_reserved"] = $this->general->counter("surgery", $filter_r);
		
		$sales = $this->general->all("sale", "updated_at", "desc", 10);
		foreach($sales as $item){
			$item->sale_type = $this->general->id("sale_type", $item->sale_type_id);
			$item->currency = $this->general->id("currency", $item->currency_id);
			$item->client = $this->general->id("person", $item->client_id);
			$item->status = $this->general->id("status", $item->status_id);
			$item->status->lang = $this->lang->line($item->status->code);
			
			$voucher = $this->general->filter("voucher", ["sale_id" => $item->id]);
			if ($voucher){
				$item->voucher = $voucher[0];
				if ($item->voucher->sunat_sent) $item->voucher->color = "success";
				else $item->voucher->color = "danger";
			}else{
				$item->voucher = $this->general->structure("voucher");
				$item->voucher->color = "warning";
				$item->voucher->sunat_msg = $this->lang->line('msg_need_send_sunat');
			}
		}
		
		$data["sales"] = $sales;
		return $data;
	}
	
	private function set_master_datas($data){
		//set monthly resume
		$from = date('Y-m-01 00:00:00');
		$to = date('Y-m-t 23:59:59');
		$status_finished = $this->general->filter("status", ["code" => "finished"])[0];
		$filter_1 = ["schedule_from >=" => $from, "schedule_from <=" => $to, "status_id" => $status_finished->id];
		$filter_2 = ["updated_at >=" => $from, "updated_at <=" => $to, "status_id" => $status_finished->id];
		$data["appointment_qty"] = $this->general->counter("appointment", $filter_1);
		$data["surgery_qty"] = $this->general->counter("surgery", $filter_1);
		$data["sale_qty"] = $this->general->counter("sale", $filter_2);
		
		$filter["updated_at >="] = date('Y-m-01 00:00:00', strtotime(date("Y-m-d", strtotime("-5 months"))));
		$filter["updated_at <="] = date('Y-m-t 23:59:59', strtotime(date("Y-m-d")));
		$currencies = $this->general->all("currency", "id", "asc");
		foreach($currencies as $i => $item){
			$filter["currency_id"] = $item->id;
			if (!$this->general->sum("sale", "total", $filter)->total) unset($currencies[$i]);
		}
		
		$data["currencies"] = $currencies;
		$data["month"] = ucfirst(strftime("%B", DateTime::createFromFormat("Y-m-d", date("Y-m-d"))->getTimestamp()));
		
		return $data;
	}
	
	public function load_income_chart(){
		$currency = $this->general->id("currency", $this->input->post("currency_id"));
		
		$xaxis = $series = [];
		$filter = ["currency_id" => $currency->id];
		$start = date("Y-m-01", time());
		
		//total amount
		$total = ["name" => $this->lang->line('txt_total'), "type" => "line", "data" => []];
		for($i = 5; $i >= 0; $i--){
			//xaxis
			$actual = date("Y-m-01", strtotime("-".$i." months", strtotime($start)));
			$filter["updated_at >="] = date('Y-m-01 00:00:00', strtotime($actual));
			$filter["updated_at <="] = date('Y-m-t 23:59:59', strtotime($actual));
			$total["data"][] = round($this->general->sum("sale", "total", $filter)->total, 2);
			
			$aux = DateTime::createFromFormat("Y-m-d", $actual)->getTimestamp();
			$month = substr(ucfirst(strftime("%B", $aux)), 0, 3);
			if (strftime("%m", $aux) == 1) $month = $month." ".strftime("%Y", $aux);
			
			$xaxis[] = $month;
		}
		$series[] = $total;
		
		$sale_types = $this->general->all("sale_type");
		foreach($sale_types as $item){
			$chart_data = ["name" => $item->description, "type" => "column", "data" => []];
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
	
	public function load_doctor_calendar(){
		$account = $this->general->id("account", $this->session->userdata("aid"));
		$today = date("Y-m-d");
		$filter = [
			"doctor_id" => $account->person_id,
			"schedule_from >=" => date("Y-m-01", strtotime("-3 months", strtotime($today))),
			"schedule_from <=" => date("Y-m-t", strtotime("+3 months", strtotime($today))),
		];
		$events = [];
		
		$apps = $this->general->filter("appointment", $filter, null, null, "schedule_from", "asc");
		foreach($apps as $item){
			$patient = $this->general->id("person", $item->patient_id);
			$events[] = [
				"title" => $this->lang->line('ev_appointment')."] ".$patient->name,
				"start" => $item->schedule_from,
				//"end" => $item->schedule_to,
				//"className" => "bg-danger",
			];
		}
		
		$surs = $this->general->filter("appointment", $filter, null, null, "schedule_from", "asc");
		foreach($surs as $item){
			$patient = $this->general->id("person", $item->patient_id);
			$events[] = [
				"title" => $this->lang->line('ev_surgery')."] ".$patient->name,
				"start" => $item->schedule_from,
				//"end" => $item->schedule_to,
				//"className" => "bg-danger",
			];
		}
		
		header('Content-Type: application/json');
		echo json_encode(["events" => $events]);
	}
}
