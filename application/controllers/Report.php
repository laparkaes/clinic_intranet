<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Report extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/Lima');
		$this->lang->load("system", "spanish");
		$this->lang->load("report", "spanish");
		$this->load->model('general_model','general');
	}
	
	public function index(){
		if (!$this->session->userdata('logged_in')) redirect(base_url());
		//pending! rol validation
		
		$data = array(
			"report_types" => $this->general->all("report_type", "id", "asc"),
			"title" => "Reportes",
			"main" => "report/index",
			"init_js" => "report/index.js"
		);
		
		$this->load->view('layout', $data);
	}
	
	public function generate_report(){
		$status = false; $msgs = []; $link_to = null;
		
		$data = $this->input->post();
		if (!$data["type_id"]) $msgs = $this->set_msg($msgs, "gr_type_msg", "error", "error_srt");
		if (!$data["from"]) $msgs = $this->set_msg($msgs, "gr_from_msg", "error", "error_sdf");
		if (!$data["to"]) $data["to"] = date("Y-m-d");
		
		$type = $this->general->id("report_type", $data["type_id"]);
		$data["type_name"] = $type->name;
		
		$link_to = $this->make_excel($data);
		if ($link_to) $status = true;
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "msgs" => $msgs, "link_to" => $link_to));
	}
	
	public function make_excel($data){
		$fileName = $this->lang->line('report')."_".$data["type_name"]."_".date("Ymdhis").'.xlsx';  
		$upload_dir = $this->upload_dir();
		
		/*
		1 = Médico
		2 = Paciente
		3 = Consulta
		4 = Cirugía
		5 = Producto
		6 = Venta
		7 = Historial del sistema
		8 = Usuario del sistema
		*/
		switch($data["type_id"]){
			case 1: $sheet = $this->set_sheet_doctor($data); break;
			case 2: $sheet = $this->set_sheet_data($data); break;
			case 3: $sheet = $this->set_sheet_data($data); break;
			case 4: $sheet = $this->set_sheet_data($data); break;
			case 5: $sheet = $this->set_sheet_data($data); break;
			case 6: $sheet = $this->set_sheet_data($data); break;
			case 7: $sheet = $this->set_sheet_data($data); break;
			case 8: $sheet = $this->set_sheet_data($data); break;
		}
		
        $writer = new Xlsx($sheet);
		$writer->save($upload_dir.$fileName);
		//header("Content-Type: application/vnd.ms-excel");
        //redirect("/".$upload_dir.$fileName);
		return base_url().$upload_dir.$fileName;
	}
	
	private function upload_dir(){
		$path = "uploaded/reports";
		$today = date("Ymd");
		$skip = [".", ".."];
		$folders = scandir($path, SCANDIR_SORT_ASCENDING);
		foreach($folders as $item)
			if (!in_array($item, $skip))
				if (strcmp($item, $today)){
					$files = scandir($path."/".$item, SCANDIR_SORT_ASCENDING);
					foreach($files as $file) if (!in_array($file, $skip)) unlink($path."/".$item."/".$file);
					rmdir($path."/".$item);
				}
				
		$upload_dir = "uploaded/reports/".date("Ymd");
		if(!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
		return $upload_dir."/";
	}
	
	private function set_sheet_doctor($data){
		$filter = [
			"registed_at >=" => $data["from"],
			"registed_at <" => date("Y-m-d", strtotime("+1 day", strtotime($data["to"])))
		];
		
		//doc_type_id
		$sl_options_arr = [];
		$sl_options = $this->general->filter_adv("sl_option", null, [["field" => "code", "values" => ["sex", "blood_type"]]]);
		foreach($sl_options as $item) $sl_options_arr[$item->id] = $item->description;
		
		$doc_type_arr = [];
		$doc_type = $this->general->all("doc_type");
		foreach($doc_type as $item) $doc_type_arr[$item->id] = $item->description;
		
		$person_ids = [];
		$person_ids_only = $this->general->only("doctor", "person_id");
		foreach($person_ids_only as $item) $person_ids[] = $item->person_id;
		
		$people_arr = [];
		$people = $this->general->filter_adv("person", null, [["field" => "id", "values" => $person_ids]]);
		foreach($people as $item) $people_arr[$item->id] = $item;
		
		$status_arr = [];
		$status = $this->general->all("status");
		foreach($status as $item){
			$item->lang = $this->lang->line($item->code);
			$status_arr[$item->id] = $item;
		}
		
		$specialty_arr = [];
		$specialty = $this->general->all("specialty");
		foreach($specialty as $item) $specialty_arr[$item->id] = $item->name;
		
		$doctors = $this->general->filter("doctor", $filter, "registed_at", "desc");
		
		$row = 5;
		$spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
		$sheet = $this->set_report_info($sheet);//report general information setting
       	$sheet->setCellValue('A'.$row, $this->lang->line('hd_registed_at'));
        $sheet->setCellValue('B'.$row, $this->lang->line('hd_id'));
        $sheet->setCellValue('C'.$row, $this->lang->line('hd_status'));
        $sheet->setCellValue('D'.$row, $this->lang->line('hd_specialty'));
		$sheet->setCellValue('E'.$row, $this->lang->line('hd_license'));
        $sheet->setCellValue('F'.$row, $this->lang->line('hd_name'));
		
		
		foreach ($doctors as $d){
			$row++;
			$sheet->setCellValue('A'.$row, $d->registed_at);
			$sheet->setCellValue('B'.$row, $d->id);
			$sheet->setCellValue('C'.$row, $status_arr[$d->status_id]->lang);
			$sheet->setCellValue('D'.$row, $specialty_arr[$d->specialty_id]);
			$sheet->setCellValue('E'.$row, $d->license);
			$sheet->setCellValue('F'.$row, $people_arr[$d->person_id]->name);
        }
		
		/*
		Tipo de Documento
		Numero de Documento
		Telefono
		Correo Electronico
		Direccion
		Fecha de Nacimiento
		Sexo
		Tipo de Sangre
		
		sl_options_arr
		doc_type_arr
		( [sex_id] => 72 [blood_type_id] => 78 [doc_type_id] => 2 [doc_number] => 000765808 [email] => [tel] => 998548751 [address] => Pj Dota 2 [birthday] => 1972-09-19)
		*/
		
		return $spreadsheet;
	}
	
	private function set_report_info($sheet){
		return $sheet;
	}
	
	private function set_sheet_appointmet($data){
		$filter = ["schedule_from >= " => $data["from"], "schedule_to <" => date("Y-m-d", strtotime("+1 day"))];
		$apps = $this->general->filter("appointment", $filter, "schedule_from", "desc");
		
		print_r($apps);
		
		
		$employeeData = [];
		$employeeData[] = ['skills' => "hola"];
		
		$spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
       	$sheet->setCellValue('A1', 'Id');
        $sheet->setCellValue('B1', 'Name');
        $sheet->setCellValue('C1', 'Skills');
        $sheet->setCellValue('D1', 'Address');
		$sheet->setCellValue('E1', 'Age');
        $sheet->setCellValue('F1', 'Designation');       
        $rows = 2;
		
        foreach ($employeeData as $val){
            //$sheet->setCellValue('A' . $rows, $val['id']);
            //$sheet->setCellValue('B' . $rows, $val['name']);
            $sheet->setCellValue('C' . $rows, $val['skills']);
            //$sheet->setCellValue('D' . $rows, $val['address']);
			//$sheet->setCellValue('E' . $rows, $val['age']);
            //$sheet->setCellValue('F' . $rows, $val['designation']);
            $rows++;
        }
		
		return $spreadsheet;
	}
	
	private function set_sheet_data($data){
		$employeeData = [];
		$employeeData[] = ['skills' => "hola"];
		
		$spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
       	$sheet->setCellValue('A1', 'Id');
        $sheet->setCellValue('B1', 'Name');
        $sheet->setCellValue('C1', 'Skills');
        $sheet->setCellValue('D1', 'Address');
		$sheet->setCellValue('E1', 'Age');
        $sheet->setCellValue('F1', 'Designation');       
        $rows = 2;
		
        foreach ($employeeData as $val){
            //$sheet->setCellValue('A' . $rows, $val['id']);
            //$sheet->setCellValue('B' . $rows, $val['name']);
            $sheet->setCellValue('C' . $rows, $val['skills']);
            //$sheet->setCellValue('D' . $rows, $val['address']);
			//$sheet->setCellValue('E' . $rows, $val['age']);
            //$sheet->setCellValue('F' . $rows, $val['designation']);
            $rows++;
        }
		
		return $spreadsheet;
	}
}
