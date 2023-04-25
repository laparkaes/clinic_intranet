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
	}
	
	public function index(){
		if (!$this->session->userdata('logged_in')) redirect(base_url());
		//pending! rol validation
		
		$data = array(
			"title" => "Reportes",
			"main" => "report/index",
			"init_js" => "report/index.js"
		);
		
		$this->load->view('layout', $data);
	}
	
	public function exceltest(){
		$fileName = date("Ymdhis").'.xlsx';  
		
		$upload_dir = $this->upload_dir();
		
        $writer = new Xlsx($this->set_sheet_data());
		$writer->save($upload_dir.$fileName);
		header("Content-Type: application/vnd.ms-excel");
        redirect("/".$upload_dir.$fileName);
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
	
	private function set_sheet_data(){
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
