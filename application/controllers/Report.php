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
		foreach($status as $item) $status_arr[$item->id] = $this->lang->line($item->code);
		
		$specialty_arr = [];
		$specialty = $this->general->all("specialty");
		foreach($specialty as $item) $specialty_arr[$item->id] = $item->name;
		
		$headers = [
			$this->lang->line('hd_id'),
			$this->lang->line('hd_registed_at'),
			$this->lang->line('hd_status'),
			$this->lang->line('hd_specialty'),
			$this->lang->line('hd_license'),
			$this->lang->line('hd_name'),
			$this->lang->line('hd_doc_type'),
			$this->lang->line('hd_doc_number'),
			$this->lang->line('hd_tel'),
			$this->lang->line('hd_email'),
			$this->lang->line('hd_address'),
			$this->lang->line('hd_birthday'),
			$this->lang->line('hd_sex'),
			$this->lang->line('hd_blood_type')
		];
		
		$spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
		$sheet = $this->set_report_header($sheet, range('A', 'Z'), $headers);//report general information setting
        
		$row = 2;
		$style_arr = ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT]];
		
		$filter = [
			"registed_at >=" => $data["from"],
			"registed_at <" => date("Y-m-d", strtotime("+1 day", strtotime($data["to"])))
		];
		$doctors = $this->general->filter("doctor", $filter, "registed_at", "desc");
		foreach ($doctors as $d){
			$person = $people_arr[$d->person_id];
			if ($person->doc_type_id) $person->doc_type = $doc_type_arr[$person->doc_type_id]; else $person->doc_type = null;
			if ($person->sex_id) $person->sex = $sl_options_arr[$person->sex_id]; else $person->sex = null;
			if ($person->blood_type_id) $person->blood_type = $sl_options_arr[$person->blood_type_id]; else $person->blood_type = null;
			
			$sheet->setCellValue('A'.$row, $d->id);
			$sheet->setCellValue('B'.$row, $d->registed_at);
			$sheet->setCellValue('C'.$row, $status_arr[$d->status_id]);
			$sheet->setCellValue('D'.$row, $specialty_arr[$d->specialty_id]);
			$sheet->setCellValue('E'.$row, $d->license);
			$sheet->setCellValue('F'.$row, $person->name);
			$sheet->setCellValue('G'.$row, $person->doc_type);
			$sheet->setCellValue('H'.$row, $person->doc_number);
			$sheet->setCellValue('I'.$row, $person->tel);
			$sheet->setCellValue('J'.$row, $person->email);
			$sheet->setCellValue('K'.$row, $person->address);
			$sheet->setCellValue('L'.$row, $person->birthday);
			$sheet->setCellValue('M'.$row, $person->sex);
			$sheet->setCellValue('N'.$row, $person->blood_type);
			$sheet->getStyle($row)->applyFromArray($style_arr);
			
			$row++;
        }
		
		return $spreadsheet;
	}
	
	private function set_report_header($sheet, $cols, $headers){
		$sheet->setTitle($this->lang->line('report'));
		
		//setting table header style
		foreach ($headers as $i => $header){
			$sheet->setCellValue($cols[$i]."1", $header);
			$sheet->getColumnDimension($cols[$i])->setWidth(25);
		}
		$sheet->getColumnDimension('A')->setWidth(10);
		
		$style_arr = [
			'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
			'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
			'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['argb' => '1a8d5f']]
		];
		$sheet->getStyle(1)->applyFromArray($style_arr);
		
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
