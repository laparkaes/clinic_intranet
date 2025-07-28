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
		$this->load->model('custom_query_model','customQuery');
		$this->nav_menu = ["sys", "report"];
		$this->nav_menus = $this->utility_lib->get_visible_nav_menus();
	}
	
	public function index(){
		if (!$this->session->userdata('logged_in')) redirect('/');
		if (!$this->utility_lib->check_access("report", "index")) redirect("/errors/no_permission");
		
		$data = array(
			"report_types" => $this->general->all("report_type", "id", "asc"),
			"title" => "Reportes",
			"main" => "sys/report/index",
		);
		
		$this->load->view('layout', $data);
	}
	
	public function generate_report(){
		$type = "error"; $msgs = []; $msg = null; $move_to = null;
		$data = $this->input->post();
		
		//permission validateion
		if ($this->utility_lib->check_access("report", "index")){
			$this->load->library('my_val');
			$msgs = $this->my_val->report($msgs, "gr_", $data);
			if (!$msgs){
				if (!$data["to"]) $data["to"] = date("Y-m-d");
				
				$type = $this->general->id("report_type", $data["type_id"]);
				$data["type_name"] = $type->name;
				
				$move_to = $this->make_excel($data);
				if ($move_to){
					$this->utility_lib->add_log("report_generate", $type->name.", ".$data["from"]."~".$data["to"]);
					$type = "success";
				}	
			}else $msg = $this->lang->line('error_occurred');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msgs" => $msgs, "msg" => $msg, "move_to" => $move_to]);
	}
	
	private function upload_dir(){
		/*
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
		*/
			
		//$upload_dir = "uploaded/reports/".date("Ymd");
		$upload_dir = "uploaded/reports/";
		if(!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
		return $upload_dir."/";
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
	
	private function make_excel($data){
		$fileName = $this->lang->line('report')."_".$data["type_name"]."_".date("Ymdhis").'.xlsx';
		//$fileName = $this->lang->line('report')."_".$data["type_name"].'.xlsx';
		$upload_dir = $this->upload_dir();
		
		/*
		1 = Médico
		2 = Paciente
		3 = Consulta
		4 = Cirugía
		5 = Producto
		6 = Venta
		7 = Comprobante
		8 = Historial del sistema
		9 = Usuario del sistema
		*/
		switch($data["type_id"]){
			case 1: $sheet = $this->set_sheet_doctor($data); break;
			case 2: $sheet = $this->set_sheet_patient($data); break;
			case 3: $sheet = $this->set_sheet_appointmet($data); break;
			case 4: $sheet = $this->set_sheet_surgery($data); break;
			case 5: $sheet = $this->set_sheet_product($data); break;
			//case 6: $sheet = $this->set_sheet_sale2($data); break;
			case 6: $sheet = $this->set_sheet_sale($data); break;
			case 7: $sheet = $this->set_sheet_voucher($data); break;
			case 8: $sheet = $this->set_sheet_log($data); break;
			case 9: $sheet = $this->set_sheet_account($data); break;
		}
		
        $writer = new Xlsx($sheet);
		$writer->save($upload_dir.$fileName);
		
		return base_url().$upload_dir.$fileName;
	}
	
	private function set_sheet_doctor($data){
		$sex_arr = [];
		$sex = $this->general->all("sex");
		foreach($sex as $item) $sex_arr[$item->id] = $item->description;
		
		$blood_type_arr = [];
		$blood_type = $this->general->all("blood_type");
		foreach($blood_type as $item) $blood_type_arr[$item->id] = $item->description;
		
		$doc_type_arr = [];
		$doc_type = $this->general->all("doc_type");
		foreach($doc_type as $item) $doc_type_arr[$item->id] = $item->description;
		
		$person_ids = [];
		$person_ids_only = $this->general->only("doctor", "person_id");
		foreach($person_ids_only as $item) $person_ids[] = $item->person_id;
		if (!$person_ids) $person_ids = [-1];
		
		$people_arr = [];
		$people = $this->general->filter("person", null, null, [["field" => "id", "values" => $person_ids]]);
		foreach($people as $item) $people_arr[$item->id] = $item;
		
		$status_arr = [];
		$status = $this->general->all("status");
		foreach($status as $item) $status_arr[$item->id] = $this->lang->line($item->code);
		
		$specialty_arr = [];
		$specialty = $this->general->all("specialty");
		foreach($specialty as $item) $specialty_arr[$item->id] = $item->name;
		
		$headers = [
			$this->lang->line('w_id'),
			$this->lang->line('w_registed_at'),
			$this->lang->line('w_status'),
			$this->lang->line('w_specialty'),
			$this->lang->line('w_license'),
			$this->lang->line('w_name'),
			$this->lang->line('w_doc_type'),
			$this->lang->line('w_doc_number'),
			$this->lang->line('w_tel'),
			$this->lang->line('w_email'),
			$this->lang->line('w_address'),
			$this->lang->line('w_birthday'),
			$this->lang->line('w_sex'),
			$this->lang->line('w_blood_type')
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
		$doctors = $this->general->filter("doctor", $filter, null, null, "registed_at", "desc");
		foreach ($doctors as $d){
			$person = $people_arr[$d->person_id];
			if ($person->doc_type_id) $person->doc_type = $doc_type_arr[$person->doc_type_id]; else $person->doc_type = null;
			if ($person->sex_id) $person->sex = $sex_arr[$person->sex_id]; else $person->sex = null;
			if ($person->blood_type_id) $person->blood_type = $blood_type_arr[$person->blood_type_id]; else $person->blood_type = null;
			
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
	
	private function set_sheet_patient($data){
		$sex_arr = [];
		$sex = $this->general->all("sex");
		foreach($sex as $item) $sex_arr[$item->id] = $item->description;
		
		$blood_type_arr = [];
		$blood_type = $this->general->all("blood_type");
		foreach($blood_type as $item) $blood_type_arr[$item->id] = $item->description;
		
		$doc_type_arr = [];
		$doc_type = $this->general->all("doc_type");
		foreach($doc_type as $item) $doc_type_arr[$item->id] = $item->description;
		
		$headers = [
			$this->lang->line('w_id'),
			$this->lang->line('w_registed_at'),
			$this->lang->line('w_name'),
			$this->lang->line('w_doc_type'),
			$this->lang->line('w_doc_number'),
			$this->lang->line('w_tel'),
			$this->lang->line('w_email'),
			$this->lang->line('w_address'),
			$this->lang->line('w_birthday'),
			$this->lang->line('w_sex'),
			$this->lang->line('w_blood_type')
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
		$people = $this->general->filter("person", $filter);
		foreach($people as $person){
			if ($person->doc_type_id) $person->doc_type = $doc_type_arr[$person->doc_type_id]; else $person->doc_type = null;
			if ($person->sex_id) $person->sex = $sex_arr[$person->sex_id]; else $person->sex = null;
			if ($person->blood_type_id) $person->blood_type = $blood_type_arr[$person->blood_type_id]; else $person->blood_type = null;
			
			$sheet->setCellValue('A'.$row, $person->id);
			$sheet->setCellValue('B'.$row, $person->registed_at);
			$sheet->setCellValue('C'.$row, $person->name);
			$sheet->setCellValue('D'.$row, $person->doc_type);
			$sheet->setCellValue('E'.$row, $person->doc_number);
			$sheet->setCellValue('F'.$row, $person->tel);
			$sheet->setCellValue('G'.$row, $person->email);
			$sheet->setCellValue('H'.$row, $person->address);
			$sheet->setCellValue('I'.$row, $person->birthday);
			$sheet->setCellValue('J'.$row, $person->sex);
			$sheet->setCellValue('K'.$row, $person->blood_type);
			$sheet->getStyle($row)->applyFromArray($style_arr);
			
			$row++;
		}
		
		return $spreadsheet;
	}

	private function set_sheet_appointmet($data){
		$specialty_arr = [];
		$specialty = $this->general->all("specialty");
		foreach($specialty as $item) $specialty_arr[$item->id] = $item->name;
		
		$status_arr = [];
		$status = $this->general->all("status");
		foreach($status as $item) $status_arr[$item->id] = $this->lang->line($item->code);
		
		$filter = ["schedule_from >= " => $data["from"], "schedule_to <" => date("Y-m-d", strtotime("+1 day"))];
		
		//set people array
		$person_ids = [];
		$patient_ids = $this->general->only("appointment", "patient_id", $filter);
		foreach($patient_ids as $item) $person_ids[] = $item->patient_id;
		
		$doctor_ids = $this->general->only("appointment", "doctor_id", $filter);
		foreach($doctor_ids as $item) $person_ids[] = $item->doctor_id;
		if (!$person_ids) $person_ids = [-1];
		
		$people_arr = [];
		$people = $this->general->filter("person", null, null, [["field" => "id", "values" => $person_ids]]);
		foreach($people as $item) $people_arr[$item->id] = $item->name;
		
		$headers = [
			$this->lang->line('w_id'),
			$this->lang->line('w_registed_at'),
			$this->lang->line('w_status'),
			$this->lang->line('w_from'),
			$this->lang->line('w_to'),
			$this->lang->line('w_specialty'),
			$this->lang->line('w_doctor'),
			$this->lang->line('w_patient'),
			$this->lang->line('w_remark'),
		];
		
		$spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
		$sheet = $this->set_report_header($sheet, range('A', 'Z'), $headers);//report general information setting
        
		$row = 2;
		$style_arr = ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT]];
		
		$apps = $this->general->filter("appointment", $filter, null, null, "schedule_from", "desc");
		foreach($apps as $item){
			$sheet->setCellValue('A'.$row, $item->id);
			$sheet->setCellValue('B'.$row, $item->registed_at);
			$sheet->setCellValue('C'.$row, $status_arr[$item->status_id]);
			$sheet->setCellValue('D'.$row, $item->schedule_from);
			$sheet->setCellValue('E'.$row, $item->schedule_to);
			$sheet->setCellValue('F'.$row, $specialty_arr[$item->specialty_id]);
			$sheet->setCellValue('G'.$row, $people_arr[$item->doctor_id]);
			$sheet->setCellValue('H'.$row, $people_arr[$item->patient_id]);
			$sheet->setCellValue('I'.$row, $item->remark);
			$sheet->getStyle($row)->applyFromArray($style_arr);
			
			$row++;
		}
		
		return $spreadsheet;
	}
	
	private function set_sheet_surgery($data){
		$specialty_arr = [];
		$specialty = $this->general->all("specialty");
		foreach($specialty as $item) $specialty_arr[$item->id] = $item->name;
		
		$surgery_room_arr = [];
		$surgery_room = $this->general->all("surgery_room");
		foreach($surgery_room as $item) $surgery_room_arr[$item->id] = $item->name;
		
		$status_arr = [];
		$status = $this->general->all("status");
		foreach($status as $item) $status_arr[$item->id] = $this->lang->line($item->code);
		
		$filter = ["schedule_from >= " => $data["from"], "schedule_to <" => date("Y-m-d", strtotime("+1 day"))];
		
		//set people array
		$person_ids = [];
		$patient_ids = $this->general->only("surgery", "patient_id", $filter);
		foreach($patient_ids as $item) $person_ids[] = $item->patient_id;
		
		$doctor_ids = $this->general->only("surgery", "doctor_id", $filter);
		foreach($doctor_ids as $item) $person_ids[] = $item->doctor_id;
		if (!$person_ids) $person_ids = [-1];
		
		$people_arr = [];
		$people = $this->general->filter("person", null, null, [["field" => "id", "values" => $person_ids]]);
		foreach($people as $item) $people_arr[$item->id] = $item->name;
		
		$headers = [
			$this->lang->line('w_id'),
			$this->lang->line('w_registed_at'),
			$this->lang->line('w_status'),
			$this->lang->line('w_room'),
			$this->lang->line('w_from'),
			$this->lang->line('w_to'),
			$this->lang->line('w_specialty'),
			$this->lang->line('w_doctor'),
			$this->lang->line('w_patient'),
			$this->lang->line('w_remark'),
			$this->lang->line('w_result'),
		];
		
		$spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
		$sheet = $this->set_report_header($sheet, range('A', 'Z'), $headers);//report general information setting
        
		$row = 2;
		$style_arr = ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT]];
		
		$sur = $this->general->filter("surgery", $filter, null, null, "schedule_from", "desc");
		foreach($sur as $item){
			$sheet->setCellValue('A'.$row, $item->id);
			$sheet->setCellValue('B'.$row, $item->registed_at);
			$sheet->setCellValue('C'.$row, $status_arr[$item->status_id]);
			$sheet->setCellValue('D'.$row, $surgery_room_arr[$item->room_id]);
			$sheet->setCellValue('E'.$row, $item->schedule_from);
			$sheet->setCellValue('F'.$row, $item->schedule_to);
			$sheet->setCellValue('G'.$row, $specialty_arr[$item->specialty_id]);
			$sheet->setCellValue('H'.$row, $people_arr[$item->doctor_id]);
			$sheet->setCellValue('I'.$row, $people_arr[$item->patient_id]);
			$sheet->setCellValue('J'.$row, $item->remark);
			$sheet->setCellValue('K'.$row, $item->result);
			$sheet->getStyle($row)->applyFromArray($style_arr);
			
			$row++;
		}
		
		return $spreadsheet;
	}
	
	private function set_sheet_product($data){
		$category_arr = [];
		$category = $this->general->all("product_category");
		foreach($category as $item) $category_arr[$item->id] = $item->name;
		
		$type_arr = [];
		$type = $this->general->all("product_type");
		foreach($type as $item) $type_arr[$item->id] = $item->description;
		
		$currency_arr = [];
		$currency = $this->general->all("currency");
		foreach($currency as $item) $currency_arr[$item->id] = $item->description;
		
		$headers = [
			$this->lang->line('w_id'),
			$this->lang->line('w_registed_at'),
			$this->lang->line('w_last_update'),
			$this->lang->line('w_on_sale'),
			$this->lang->line('w_category'),
			$this->lang->line('w_type'),
			$this->lang->line('w_code'),
			$this->lang->line('w_description'),
			$this->lang->line('w_stock'),
			$this->lang->line('w_options_stock'),
			$this->lang->line('w_currency'),
			$this->lang->line('w_price'),
			$this->lang->line('w_value'),
			$this->lang->line('w_vat'),
			$this->lang->line('w_sold_qty'),
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
		
		$products = $this->general->filter("product", $filter, null, null, "registed_at", "desc");
		foreach($products as $item){
			$prod_options = $this->general->filter("product_option", ["product_id" => $item->id], null, null, "id", "asc");
			if ($prod_options){
				$prod_op_aux = [];
				foreach($prod_options as $o) $prod_op_aux[] = $o->description." (".number_format($o->stock).")";
				$options_txt = implode(", ", $prod_op_aux);
			}else $options_txt = null;
			$sold_qty = $this->general->sum("sale_product", "qty", ["product_id" => $item->id])->qty;
			if ($sold_qty) $sold_qty = number_format($sold_qty); else $sold_qty = null;
			if ($item->active) $on_sale = "o"; else $on_sale = null;
			
			$sheet->setCellValue('A'.$row, $item->id);
			$sheet->setCellValue('B'.$row, $item->registed_at);

			$sheet->setCellValue('C'.$row, $item->updated_at);
			$sheet->setCellValue('D'.$row, $on_sale);
			$sheet->setCellValue('E'.$row, $category_arr[$item->category_id]);
			$sheet->setCellValue('F'.$row, $type_arr[$item->type_id]);
			$sheet->setCellValue('G'.$row, $item->code);
			$sheet->setCellValue('H'.$row, $item->description);
			$sheet->setCellValue('I'.$row, number_format($item->stock));
			$sheet->setCellValue('J'.$row, $options_txt);
			$sheet->setCellValue('K'.$row, $currency_arr[$item->currency_id]);
			$sheet->setCellValue('L'.$row, number_format($item->price, 2));
			$sheet->setCellValue('M'.$row, number_format($item->value, 2));
			$sheet->setCellValue('N'.$row, number_format($item->vat, 2));
			$sheet->setCellValue('O'.$row, $sold_qty);
			$sheet->getStyle($row)->applyFromArray($style_arr);
			
			$row++;
		}
		
		return $spreadsheet;
	}

	private function set_sheet_sale2($data){//no usar este codigo
		$main_query = $this->customQuery->custom_report_sale($data["from"], $data["to"]);
		// aqui deberia ir tu query principal
		// echo "<pre>";
		// print_r($main_query);
		// echo "</pre>";
		// exit;
		$status_arr = [];
		$status = $this->general->all("status");
		foreach($status as $item) $status_arr[$item->id] = $this->lang->line($item->code);
		
		$headers = [
			$this->lang->line('w_id'),
			$this->lang->line('w_registed_at'),
			$this->lang->line('w_last_update'),
			$this->lang->line('w_status'),
			$this->lang->line('w_type'),
			$this->lang->line('w_client'),
			$this->lang->line('w_currency'),
			$this->lang->line('w_unit_price'),
			$this->lang->line('w_discount'),
			$this->lang->line('w_quantity'),
			$this->lang->line('w_total'),
			$this->lang->line('w_amount'),
			$this->lang->line('w_vat'),
			$this->lang->line('w_payment_method'),
			$this->lang->line('w_paid'),
			$this->lang->line('w_balance'),
			$this->lang->line('w_detail_payment'),
			$this->lang->line('w_products'),
			$this->lang->line('w_detail')
		];

		$spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
		$sheet = $this->set_report_header($sheet, range('A', 'Z'), $headers);
		$row = 2;
		$style_arr = ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT]];
		$total_detalle=0;
		

		foreach($main_query as $item){
			$amount_difference = $item->paymentReceived - $item->paymentChange;
			$text_payment = $item->PaymentRegistedAt." [ $item->currencyDesc $amount_difference ]";
			$total_detalle=($item->priceProduct * $item->quantyProduct)-$item->discountProduct;
            $text_prod_opt=$item->optionDesc;
		
			$sheet->setCellValue('A'.$row, $item->id);
			$sheet->setCellValue('B'.$row, $item->registed_at);
			$sheet->setCellValue('C'.$row, $item->updated_at);
			$sheet->setCellValue('D'.$row, $status_arr[$item->status_id]);
			$sheet->setCellValue('E'.$row, $item->saleTypeDesc);
			$sheet->setCellValue('F'.$row, $item->clientFullName);
			$sheet->setCellValue('G'.$row, $item->currencyDesc);
			$sheet->setCellValue('H'.$row, $item->priceProduct);
			$sheet->setCellValue('I'.$row, $item->discountProduct);
			$sheet->setCellValue('J'.$row, $item->quantyProduct);
			$sheet->setCellValue('K'.$row, number_format($total_detalle, 2));
			$sheet->setCellValue('L'.$row, number_format($item->amount, 2));
			$sheet->setCellValue('M'.$row, number_format($item->vat, 2));
			$sheet->setCellValue('N'.$row, $item->paymentMethodDesc);
			$sheet->setCellValue('O'.$row, number_format($item->paymentReceived, 2));
			$sheet->setCellValue('P'.$row, number_format($item->paymentBalance, 2));
			$sheet->setCellValue('Q'.$row, $text_payment);
			$sheet->setCellValue('R'.$row, $item->productDesc);
			$sheet->setCellValue('S'.$row, $text_prod_opt);
			$sheet->getStyle($row)->applyFromArray($style_arr);
			
			$row++;
		}

		

		return $spreadsheet;
	}
	
	private function set_sheet_sale($data){
		//$data = ["from" => "2025-01-01", "to" => "2025-08-01"];
		
		$type_arr = [];
		$type = $this->general->all("sale_type");
		foreach($type as $item) $type_arr[$item->id] = $item->description;
		
		$status_arr = [];
		$status = $this->general->all("status");
		foreach($status as $item) $status_arr[$item->id] = $this->lang->line($item->code);
		
		$currency_arr = [];
		$currency = $this->general->all("currency");
		foreach($currency as $item) $currency_arr[$item->id] = $item->description;
		
		$product_arr = [];
		$product = $this->general->all("product");
		foreach($product as $item) $product_arr[$item->id] = $item->description;
		
		$filter = [
			"registed_at >=" => $data["from"],
			"registed_at <" => date("Y-m-d", strtotime("+1 day", strtotime($data["to"])))
		];
		
		//set people array
		$person_ids = [];
		$client_ids = $this->general->only("sale", "client_id", $filter);
		foreach($client_ids as $item) $person_ids[] = $item->client_id;
		if (!$person_ids) $person_ids = [-1];
		
		$people_arr = [];
		$people = $this->general->filter("person", null, null, [["field" => "id", "values" => $person_ids]]);
		foreach($people as $item) $people_arr[$item->id] = $item->name;
		
		$headers = [
			"# Venta",
			"Fecha",
			"YYYY",
			"MM",
			"DD",
			"Estado",
			"Tipo",
			"Cliente",
			"Item",
			"Opcion",
			"Metodo de Pago",
			"Moneda",
			"P/U",
			"Descuento",
			"Cantidad",
			"Total",
			"Op. gravada",
			"IGV",
		]; //print_R($headers); echo "<br/><br/>";
		
		$rows = [];
		$rows[] = $headers;
		
		$sales = $this->general->filter("sale", $filter, null, null, "registed_at", "desc");
		foreach($sales as $item){
			$curr = $currency_arr[$item->currency_id];
			$client = $item->client_id ? $this->general->id("person", $item->client_id)->name : null;
			$payment_method = $this->general->id("payment_method", $item->payment_method_id)->description : null;
		
			$products = $this->general->filter("sale_product", ["sale_id" => $item->id]);
			if ($products){
				foreach($products as $p){
					
					$pr = $this->general->id("product", $p->product_id);
					$op = $this->general->id("product_option", $p->option_id);
					
					$total = ($p->price - $p->discount) * $p->qty;
					$amount = round($total / 1.18, 2);
					$igv = $total - $amount;
					
					$rows[] = [
						$item->id, 
						$item->registed_at, 
						date("Y", strtotime($item->registed_at)), 
						date("m", strtotime($item->registed_at)), 
						date("d", strtotime($item->registed_at)), 
						$status_arr[$item->status_id], 
						$type_arr[$item->sale_type_id],
						$client,
						$pr->code." / ".$pr->description,
						$op ? $op->description : "",
						$payment_method,
						$curr,
						$p->price,
						$p->discount,
						$p->qty,
						$total,
						$amount,
						$igv,
					];
					
					/*
					print_r($row); echo "<br/>";echo "<br/>";
					
					print_r($item); echo "<br/>";
					print_r($p); echo "<br/>";
					print_r($pr); echo "<br/>";
					print_r($op); echo "<br/>";
					*/
				}
			}
		}
		
		$spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
		
		$sheet = $this->set_report_header($sheet, range('A', 'Z'), $headers);//report general information setting
		
		foreach($rows as $r => $row){
			foreach ($row as $c => $val){
				$sheet->setCellValueByColumnAndRow($c + 1, $r + 1, $val);
			}
		}
		
		$style_arr = [
			'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
			'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
			'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['argb' => '1a8d5f']]
		];
		$sheet->getStyle(1)->applyFromArray($style_arr);
		
		return $spreadsheet;
	}
	
	private function set_sheet_sale_backup($data){//no usar
		$type_arr = [];
		$type = $this->general->all("sale_type");
		foreach($type as $item) $type_arr[$item->id] = $item->description;
		
		$status_arr = [];
		$status = $this->general->all("status");
		foreach($status as $item) $status_arr[$item->id] = $this->lang->line($item->code);
		
		$currency_arr = [];
		$currency = $this->general->all("currency");
		foreach($currency as $item) $currency_arr[$item->id] = $item->description;
		
		$product_arr = [];
		$product = $this->general->all("product");
		foreach($product as $item) $product_arr[$item->id] = $item->description;
		
		$filter = [
			"registed_at >=" => $data["from"],
			"registed_at <" => date("Y-m-d", strtotime("+1 day", strtotime($data["to"])))
		];
		
		//set people array
		$person_ids = [];
		$client_ids = $this->general->only("sale", "client_id", $filter);
		foreach($client_ids as $item) $person_ids[] = $item->client_id;
		if (!$person_ids) $person_ids = [-1];
		
		$people_arr = [];
		$people = $this->general->filter("person", null, null, [["field" => "id", "values" => $person_ids]]);
		foreach($people as $item) $people_arr[$item->id] = $item->name;
		
		$headers = [
			$this->lang->line('w_id'),
			$this->lang->line('w_registed_at'),
			$this->lang->line('w_last_update'),
			$this->lang->line('w_status'),
			$this->lang->line('w_type'),
			$this->lang->line('w_client'),
			$this->lang->line('w_currency'),
			$this->lang->line('w_total'),
			$this->lang->line('w_amount'),
			$this->lang->line('w_vat'),
			$this->lang->line('w_paid'),
			$this->lang->line('w_balance'),
			$this->lang->line('w_detail_payment'),
			$this->lang->line('w_products')
		];
		
		$spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
		$sheet = $this->set_report_header($sheet, range('A', 'Z'), $headers);//report general information setting
        
		$row = 2;
		$style_arr = ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT]];
		
		$sales = $this->general->filter("sale", $filter, null, null, "registed_at", "desc");
		foreach($sales as $item){
			$curr = $currency_arr[$item->currency_id];
			if ($item->client_id) $client = $people_arr[$item->client_id]; else $client = null;
			
			$payments = $this->general->filter("payment", ["sale_id" => $item->id]);
			if ($payments){
				$p_arr = [];
				foreach($payments as $p) $p_arr[] = $p->registed_at." [".$curr." ".number_format($p->received - $p->change, 2)."]";
				$payment_detail = implode(", ", $p_arr);
			}else $payment_detail = null;
			
			$products = $this->general->filter("sale_product", ["sale_id" => $item->id]);
			if ($products){
				$p_arr = [];
				foreach($products as $p){
					$op = $this->general->id("product_option", $p->option_id);
					if ($op) $op_txt = $op->description.", "; else $op_txt = "";
					$p_arr[] = $product_arr[$p->product_id]." [".$op_txt.number_format($p->qty)." * ".$curr." ".number_format($p->price - $p->discount, 2)."]";
				}
				$products_txt = implode(", ", $p_arr);
			}else $products_txt = null;
			
			$sheet->setCellValue('A'.$row, $item->id);
			$sheet->setCellValue('B'.$row, $item->registed_at);
			$sheet->setCellValue('C'.$row, $item->updated_at);
			$sheet->setCellValue('D'.$row, $status_arr[$item->status_id]);
			$sheet->setCellValue('E'.$row, $type_arr[$item->sale_type_id]);
			$sheet->setCellValue('F'.$row, $client);
			$sheet->setCellValue('G'.$row, $curr);
			$sheet->setCellValue('H'.$row, number_format($item->total, 2));
			$sheet->setCellValue('I'.$row, number_format($item->amount, 2));
			$sheet->setCellValue('J'.$row, number_format($item->vat, 2));
			$sheet->setCellValue('K'.$row, number_format($item->paid, 2));
			$sheet->setCellValue('L'.$row, number_format($item->balance, 2));
			$sheet->setCellValue('M'.$row, $payment_detail);
			$sheet->setCellValue('N'.$row, $products_txt);
			$sheet->getStyle($row)->applyFromArray($style_arr);
			
			$row++;
		}
		
		return $spreadsheet;
	}
	
	private function set_sheet_voucher($data){
		$status_arr = [];
		$status = $this->general->all("status");
		foreach($status as $item) $status_arr[$item->id] = $this->lang->line($item->code);
		
		$filter = [
			"registed_at >=" => $data["from"],
			"registed_at <" => date("Y-m-d", strtotime("+1 day", strtotime($data["to"])))
		];
		
		//set people array
		$person_ids = [];
		$client_ids = $this->general->only("voucher", "client_id", $filter);
		foreach($client_ids as $item) $person_ids[] = $item->client_id;
		if (!$person_ids) $person_ids = [-1];
		
		$people_arr = [];
		$people = $this->general->filter("person", null, null, [["field" => "id", "values" => $person_ids]]);
		foreach($people as $item) $people_arr[$item->id] = $item->name;
		
		$headers = [
			$this->lang->line('w_id'),
			$this->lang->line('w_registed_at'),
			$this->lang->line('w_status'),
			$this->lang->line('w_sale_id'),
			$this->lang->line('w_client'),
			$this->lang->line('w_type'),
			$this->lang->line('w_number'),
			$this->lang->line('w_method'),
			$this->lang->line('w_currency'),
			$this->lang->line('w_total'),
			$this->lang->line('w_amount'),
			$this->lang->line('w_vat'),
			$this->lang->line('w_paid'),
			$this->lang->line('w_change'),
			$this->lang->line('w_leyend'),
			$this->lang->line('w_hash'),
			$this->lang->line('w_sunat_sent'),
			$this->lang->line('w_sunat_msg'),
		];
		
		$spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
		$sheet = $this->set_report_header($sheet, range('A', 'Z'), $headers);//report general information setting
        
		$row = 2;
		$style_arr = ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT]];
		
		$currencies = [];
		$currencies_rec = $this->general->all("currency");
		foreach($currencies_rec as $item) $currencies[$item->id] = $item->description;
		
		$pay_methods = [];
		$pay_method_rec = $this->general->all("payment_method");
		foreach($pay_method_rec as $item) $pay_methods[$item->id] = $item->description;
		
		$sale_types = [];
		$sale_types_rec = $this->general->all("sale_type");
		foreach($sale_types_rec as $item) $sale_types[$item->id] = $item;
		
		$voucher_types = [];
		$voucher_types_rec = $this->general->all("voucher_type");
		foreach($voucher_types_rec as $item) $voucher_types[$item->id] = $item->description;
		
		$vouchers = $this->general->filter("voucher", $filter, null, null, "registed_at", "desc");
		foreach($vouchers as $item){
			if ($item->sunat_sent) $sunat_sent = "o"; else $sunat_sent = "x";
			if ($item->client_id) $client = $people_arr[$item->client_id]; else $client = null;
			
			$item->type = $voucher_types[$item->voucher_type_id];
			$item->letter = $item->type[0];
			$item->serie = $sale_types[$item->sale_type_id]->sunat_serie;
			
			$sale = $this->general->id("sale", $item->sale_id);
			if ($item->sunat_resume_id) $item->sunat_msg = $this->general->id("sunat_resume", $item->sunat_resume_id)->message;
			
			if (!$item->status_id) $this->general->update("voucher", $item->id, ["status_id" => 4]);
			$sheet->setCellValue('A'.$row, $item->id);
			$sheet->setCellValue('B'.$row, $item->registed_at);
			$sheet->setCellValue('C'.$row, $status_arr[$item->status_id]);
			$sheet->setCellValue('D'.$row, $item->sale_id);
			$sheet->setCellValue('E'.$row, $client);
			$sheet->setCellValue('F'.$row, $item->type);
			$sheet->setCellValue('G'.$row, $item->letter.$item->serie."-".$item->correlative);
			$sheet->setCellValue('H'.$row, $pay_methods[$item->payment_method_id]);
			$sheet->setCellValue('I'.$row, $currencies[$sale->currency_id]);
			$sheet->setCellValue('J'.$row, number_format($sale->total, 2));
			$sheet->setCellValue('K'.$row, number_format($sale->amount, 2));
			$sheet->setCellValue('L'.$row, number_format($sale->vat, 2));
			$sheet->setCellValue('M'.$row, number_format($item->received, 2));
			$sheet->setCellValue('N'.$row, number_format($item->change, 2));
			$sheet->setCellValue('O'.$row, $item->legend);
			$sheet->setCellValue('P'.$row, $item->hash);
			$sheet->setCellValue('Q'.$row, $sunat_sent);
			$sheet->setCellValue('R'.$row, $item->sunat_msg);
			$sheet->getStyle($row)->applyFromArray($style_arr);
			
			$row++;
		}
		
		return $spreadsheet;
	}
	
	private function set_sheet_log($data){
		$this->lang->load("log", "spanish");
		
		$account_arr = [];
		$account = $this->general->all("account");
		foreach($account as $item) $account_arr[$item->id] = $item->email;
		
		$code_arr = [];
		$code = $this->general->all("log_code");
		foreach($code as $item) $code_arr[$item->id] = $item->code;
		
		$headers = [
			$this->lang->line('w_id'),
			$this->lang->line('w_registed_at'),
			$this->lang->line('w_account'),
			$this->lang->line('w_activity'),
			$this->lang->line('w_detail'),
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
		$logs = $this->general->filter("log", $filter, null, null, "registed_at", "desc");
		foreach($logs as $item){
			$sheet->setCellValue('A'.$row, $item->id);
			$sheet->setCellValue('B'.$row, $item->registed_at);
			$sheet->setCellValue('C'.$row, $account_arr[$item->account_id]);
			$sheet->setCellValue('D'.$row, $this->lang->line("log_".$code_arr[$item->log_code_id]));
			$sheet->setCellValue('E'.$row, $item->detail);
			$sheet->getStyle($row)->applyFromArray($style_arr);
			
			$row++;
		}
		
		return $spreadsheet;
	}

	private function set_sheet_account($data){
		$role_arr = [];
		$role = $this->general->all("role");
		foreach($role as $item) $role_arr[$item->id] = $item->name;
		
		$headers = [
			$this->lang->line('w_id'),
			$this->lang->line('w_registed_at'),
			$this->lang->line('w_last_access'),
			$this->lang->line('w_role'),
			$this->lang->line('w_name'),
			$this->lang->line('w_email'),
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
		
		$accounts = $this->general->filter("account", $filter, null, null, "registed_at", "desc");
		foreach($accounts as $item){
			$person = $this->general->id("person", $item->person_id);
			if ($person) $person_name = $person->name; else $person_name = "";
			
			$sheet->setCellValue('A'.$row, $item->id);
			$sheet->setCellValue('B'.$row, $item->registed_at);
			$sheet->setCellValue('C'.$row, $item->logged_at);
			$sheet->setCellValue('E'.$row, $this->lang->line($role_arr[$item->role_id]));
			$sheet->setCellValue('F'.$row, $person_name);
			$sheet->setCellValue('G'.$row, $item->email);
			$sheet->getStyle($row)->applyFromArray($style_arr);
			
			$row++;
		}
		
		return $spreadsheet;
	}

	private function set_consolidado_ventas($data){
		$main_query = $this->customQuery->custom_report_consolidated_sale($data["from"], $data["to"]);
		// aqui deberia ir tu query principal
		// echo "<pre>";
		// print_r($main_query);
		// echo "</pre>";
		// exit;
		$efectivo =  [];
		$yape =  [];
		$transferencia = [];

		foreach($main_query as $item){
			if($item->payment_method_id == 1) { // efectivo
			
				// si ya existe actualizamos la cantidad
				$index = $this->findIndexByProperty($efectivo, "ProductId", $item->ProductId );
				//array_search($item->ProductId, $efectivo);
				echo "<pre> pre index";
				print_r($index);
				echo "</pre> <br>";
				//$this->findIndexByProperty($efectivo, "ProductId", $item->ProductId );
				if ($index >= 0) {
					echo "<pre> index";
					print_r($index);
					echo "</pre> <br>";
					exit;
					//$efectivo[index].totalQty = $var_custom + $efectivo[index].qty;
				} else {
					// en el caso de un registro nuevo
					$item->totalQty = $item->qty;
					array_push($efectivo, $item);
					
				}
			} else if($item->payment_method_id == 2) { // yape 

			} else if($item->payment_method_id == 3) { // transferencia

			}
		}


	}

	function findObjectById($array, $property, $value){
		foreach ( $array as $element ) {
			if ( $value == $element[$property] ) {
				return $element;
			}
		}
		return false;
	}

	function findIndexByProperty($array, $property, $value){
		$index = 0;
		foreach ( $array as $element ) {
			if ( $value == $element->$property ) {
				return $index;
			}
			$index++;
		}
		return -1;
	}
}
