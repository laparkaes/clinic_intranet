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
	}
	
	public function index(){
		if (!$this->session->userdata('logged_in')) redirect("/");
		
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
		/*
		
		
		$filter = array("registed_at >=" => date('Y-m-01 00:00:00'), "registed_at <=" => date('Y-m-t 23:59:59'));
		$income = $this->sl_option->code("currency");
		$currency_arr = array();
		foreach($income as $item){
			$filter["currency_id"] = $item->id;
			$item->total = $this->general->sum("payment", "total", $filter)->total;
			$currency_arr[$item->id] = $item->description;
		}
		$data["income"] = $income;
		
		//set lastest payments
		$data["payments"] = $this->general->all("payment", "registed_at", "desc", 5, 0);
		$data["currencies"] = $currency_arr;
		
		//chart datas
		setlocale(LC_ALL, 'spanish');
		$chart_xaxis = $dates = array();
		for($i = 5; $i >= 0; $i--){
			$actual = date("Y-m-d", strtotime("-".$i." months"));
			$from = date('Y-m-01 00:00:00', strtotime($actual));
			$to = date('Y-m-t 23:59:59', strtotime($actual));
			array_push($dates, array("from" => $from, "to" =>$to));
			
			$aux = DateTime::createFromFormat("Y-m-d", $actual);
			array_push($chart_xaxis, ucfirst(substr(strftime("%B",$aux->getTimestamp()),0,3)));
		}
		
		$chart_series = array();
		foreach($income as $item){
			$show = false;
			$aux_data = array();
			$filter = array("currency_id" => $item->id);
			foreach($dates as $d){
				$filter["registed_at >="] = $d["from"];
				$filter["registed_at <="] = $d["to"];
				$aux_total = $this->general->sum("payment", "total", $filter)->total;
				if ($aux_total) $show = true; else $aux_total = 0;
				
				array_push($aux_data, $aux_total);
			}
			
			if ($show) array_push($chart_series, array("name" => $item->description, "data" => $aux_data));
		}
		
		$chart = array(
			"xaxis" => $chart_xaxis,
			"yaxis" => $this->lang->line('income'),
			"series" => $chart_series,
		);
		$data["chart"] = json_encode($chart);
		
		//calendar datas
		$calendar_events = $agenda = array(); $today = strtotime(date('Y-m-d 00:00:00', time()));
		
		$filter = array(
			"status_id" => $this->status->code("confirmed")->id,
			"schedule_from >=" => date('Y-m-d 00:00:00', strtotime("-3 months", time()))
			//"schedule_from >=" => "2022-01-01 00:00:00"
		);
		$appointments = $this->appointment->filter($filter, "", "", "schedule_from", "asc");
		foreach($appointments as $item){
			$item->schedule_from = date('Y-m-d H:i', strtotime($item->schedule_from));
			if (!$item->schedule_to) $item->schedule_to = date('Y-m-d H:i', strtotime('+30 minutes', strtotime($item->schedule_from)));
			
			$date_f = date('Y-m-d', strtotime($item->schedule_from));
			$date_t =  date('Y-m-d', strtotime($item->schedule_to));
			$schedule = date('H:i', strtotime($item->schedule_from))." ~<br/>".date('H:i', strtotime($item->schedule_to));
			if (strcmp($date_f, $date_t)){
				$d1 = new DateTime($date_f);
				$d2 = new DateTime($date_t);
				$diff = $d1->diff($d2);
				$schedule = $schedule."(+".$diff->d.")";
			}
			
			$aux_c = array(
				"borderColor" => "green",
				"title" => "Consulta",
				"start" => $item->schedule_from,
				"end" => $item->schedule_to,
				"link" => base_url()."appointment/detail/".$item->id,
			);
			array_push($calendar_events, $aux_c);
			
			if (($today <= strtotime($item->schedule_to)) and (count($agenda) < 10)){
				$aux_a = array(
					"doctor" => $this->general->id("person", $item->doctor_id)->name,
					"date" => $date_f,
					"schedule" => $schedule
				);
				array_push($agenda, $aux_a);
			}
		}
		
		$calendar_btns = array(
			"today" => $this->lang->line('btn_today'),
			"month" => $this->lang->line('btn_month'),
			"week" => $this->lang->line('btn_week'),
			"day" => $this->lang->line('btn_day'),
			"list" => $this->lang->line('btn_list')
		);
		
		$calendar_hds = array(
			"date" => $this->lang->line('hd_date'),
			"schedule" => $this->lang->line('hd_schedule'),
			"type" => $this->lang->line('hd_type')
		);
		
		$data["agenda"] = $agenda;
		$data["calendar"] = json_encode(array("events" => $calendar_events, "btns" => $calendar_btns, "hd" => $calendar_hds));
		*/
		return $data;
	}
	
	public function load_chart_monthly_income(){
		$currencies = $this->general->all("currency");
		
		$status_finished = $this->general->filter("status", array("code" => "finished"))[0];
		$filter = array("status_id" => $status_finished->id);
		
		$series = $xaxis = array();
		foreach($currencies as $currency){
			$filter["currency_id"] = $currency->id;
			
			$values = $months = array(); 
			setlocale(LC_TIME, 'spanish');
			for($i = 11; $i >= 0; $i--){//last 12 months including this month
				$actual = date("Y-m-d", strtotime("-".$i." months"));
				$filter["updated_at >="] = date('Y-m-01 00:00:00', strtotime($actual));
				$filter["updated_at <="] = date('Y-m-t 23:59:59', strtotime($actual));
				$value = round($this->general->sum("sale", "total", $filter)->total, 2);
				if (!$value) $value = null;
				array_push($values, $value);
				
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
		
		header('Content-Type: application/json');
		echo json_encode(array("series" => $series, "xaxis" => $xaxis));
	}
}
