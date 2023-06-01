<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/Lima');
		$this->lang->load("dashboard", "spanish");
		$this->lang->load("system", "spanish");
		$this->load->model('appointment_model','appointment');
		$this->load->model('status_model','status');
		$this->load->model('sl_option_model','sl_option');
		$this->load->model('general_model','general');
		$this->nav_menu = "dashboard";
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
		
		switch($this->session->userdata('role')->name){
			case "master": $data = $this->set_master_datas($data); break;
		}
		$this->load->view('layout', $data);
	}
	
	private function set_master_datas($data){
		//set monthly resume
		$from = date('Y-m-01 00:00:00');
		$to = date('Y-m-t 23:59:59');
		$status_finished = $this->general->filter("status", array("code" => "finished"))[0];
		$filter_1 = array("schedule_from >=" => $from, "schedule_from <=" => $to, "status_id" => $status_finished->id);
		$filter_2 = array("updated_at >=" => $from, "updated_at <=" => $to, "status_id" => $status_finished->id);
		$data["appointment_qty"] = $this->general->counter("appointment", $filter_1);
		$data["surgery_qty"] = 333;//$this->general->counter("surgery", $filter_1);
		$data["sale_qty"] = $this->general->counter("sale", $filter_2);
		
		$filter["updated_at >="] = date('Y-m-01 00:00:00', strtotime(date("Y-m-d", strtotime("-5 months"))));
		$filter["updated_at <="] = date('Y-m-t 23:59:59', strtotime(date("Y-m-d")));
		$currencies = $this->general->all("currency", "id", "asc");
		foreach($currencies as $i => $item){
			$filter["currency_id"] = $item->id;
			if (!$this->general->sum("sale", "total", $filter)->total) unset($currencies[$i]);
		}
		
		$data["currencies"] = $currencies;
		
		return $data;
	}
	
	public function load_income_chart(){
		$currency = $this->general->id("currency", $this->input->post("currency_id"));
		setlocale(LC_TIME, 'spanish');
		
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
	
	public function load_chart_data(){
		
		$currencies = $this->general->all("currency");
		$sale_types = $this->general->all("sale_type");
		$yaxis = $series = [];
		
		setlocale(LC_TIME, 'spanish');
		
		$filter = ["status_id" => $this->general->filter("status", ["code" => "finished"])[0]->id];
		foreach($currencies as $cur){
			$filter["currency_id"] = $cur->id;
			$filter["updated_at >="] = date('Y-m-01 00:00:00', strtotime(date("Y-m-d", strtotime("-5 months"))));
			$filter["updated_at <="] = date('Y-m-t 23:59:59', strtotime(date("Y-m-d")));
			
			if ($this->general->sum("sale", "total", $filter)->total){
				foreach($sale_types as $st){
					$serie = [
						"name" => $st->description,
						"type" => "area",
						"data" => [],
					];
					
					$filter["sale_type_id"] = $st->id;
					for($i = 5; $i >= 0; $i--){//last 6 months including this month
						$actual = date("Y-m-t", strtotime("-".$i." months"));
						$filter["updated_at >="] = date('Y-m-01 00:00:00', strtotime($actual));
						$filter["updated_at <="] = date('Y-m-t 23:59:59', strtotime($actual));
						$serie["data"][] = round($this->general->sum("sale", "total", $filter)->total, 2);
					}
					
					echo $cur->description." ".$st->description."<br/><br/>";
				
					$yaxis[] = $cur->description;
					$series[] = $serie;
				}
			}
		}
		
		foreach($series as $item){
			print_r($item);
			echo "<br/>";
		}
		print_r($yaxis);
	}
	
	public function load_chart_monthly_income(){
		$currencies = $this->general->all("currency");
		
		$status_finished = $this->general->filter("status", array("code" => "finished"))[0];
		$filter = array("status_id" => $status_finished->id);
		
		$series = $xaxis = array();
		foreach($currencies as $currency){
			$filter["currency_id"] = $currency->id;
			$filter["updated_at >="] = date('Y-m-01 00:00:00', strtotime(date("Y-m-d", strtotime("-5 months"))));
			$filter["updated_at <="] = date('Y-m-t 23:59:59', strtotime(date("Y-m-d")));
			
			if ($this->general->sum("sale", "total", $filter)->total){
				$values = $months = array(); 
				setlocale(LC_TIME, 'spanish');
				for($i = 5; $i >= 0; $i--){//last 6 months including this month
					$actual = date("Y-m-d", strtotime("-".$i." months"));
					$filter["updated_at >="] = date('Y-m-01 00:00:00', strtotime($actual));
					$filter["updated_at <="] = date('Y-m-t 23:59:59', strtotime($actual));
					$values[] = round($this->general->sum("sale", "total", $filter)->total, 2);
					
					if (!$xaxis){
						$aux = DateTime::createFromFormat("Y-m-d", $actual)->getTimestamp();
						$month = substr(ucfirst(strftime("%B", $aux)), 0, 3);
						if (strftime("%m", $aux) == 1) $month = $month." ".strftime("%Y", $aux);
						
						array_push($months, $month);	
					}
				}
				
				array_push($series, array("name" => $currency->description, "data" => $values));
				if (!$xaxis) $xaxis = $months;
			}
		}
		
		header('Content-Type: application/json');
		echo json_encode(array("series" => $series, "xaxis" => $xaxis));
	}
}
