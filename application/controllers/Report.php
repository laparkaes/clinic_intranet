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
		if (!$this->utility_lib->check_access("report", "index")) redirect("/errors/no_permission");
		
		$data = array(
			"report_types" => $this->general->all("report_type", "id", "asc"),
			"title" => "Reportes",
			"main" => "report/index",
			"init_js" => "report/index.js"
		);
		
		$this->load->view('layout', $data);
	}
	
	public function generate_report(){
		$status = false; $msgs = []; $msg = null; $link_to = null;
		$data = $this->input->post();
		
		//permission validateion
		if (!$this->utility_lib->check_access("report", "index")) $msg = $this->lang->line('error_no_permission');
		
		//data validation
		if (!$data["type_id"]) $msgs = $this->set_msg($msgs, "gr_type_msg", "error", "error_srt");
		if (!$data["from"]) $msgs = $this->set_msg($msgs, "gr_from_msg", "error", "error_sdf");
		if ($msgs) $msg = $this->lang->line('error_occurred');
		
		if (!$msg){
			if (!$data["to"]) $data["to"] = date("Y-m-d");
			
			$type = $this->general->id("report_type", $data["type_id"]);
			$data["type_name"] = $type->name;
			
			$link_to = $this->make_excel($data);
			if ($link_to){
				$this->utility_lib->add_log("report_generate", $type->name.", ".$data["from"]."~".$data["to"]);
				$status = true;
			}	
		}
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "msgs" => $msgs, "msg" => $msg, "link_to" => $link_to));
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
	
	private function set_sheet_patient($data){
		$sl_options_arr = [];
		$sl_options = $this->general->filter_adv("sl_option", null, [["field" => "code", "values" => ["sex", "blood_type"]]]);
		foreach($sl_options as $item) $sl_options_arr[$item->id] = $item->description;
		
		$doc_type_arr = [];
		$doc_type = $this->general->all("doc_type");
		foreach($doc_type as $item) $doc_type_arr[$item->id] = $item->description;
		
		$headers = [
			$this->lang->line('hd_id'),
			$this->lang->line('hd_registed_at'),
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
		$people = $this->general->filter("person", $filter);
		foreach($people as $person){
			if ($person->doc_type_id) $person->doc_type = $doc_type_arr[$person->doc_type_id]; else $person->doc_type = null;
			if ($person->sex_id) $person->sex = $sl_options_arr[$person->sex_id]; else $person->sex = null;
			if ($person->blood_type_id) $person->blood_type = $sl_options_arr[$person->blood_type_id]; else $person->blood_type = null;
			
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
		
		$people_arr = [];
		$people = $this->general->filter_adv("person", null, [["field" => "id", "values" => $person_ids]]);
		foreach($people as $item) $people_arr[$item->id] = $item->name;
		
		$headers = [
			$this->lang->line('hd_id'),
			$this->lang->line('hd_registed_at'),
			$this->lang->line('hd_status'),
			$this->lang->line('hd_from'),
			$this->lang->line('hd_to'),
			$this->lang->line('hd_specialty'),
			$this->lang->line('hd_doctor'),
			$this->lang->line('hd_patient'),
			$this->lang->line('hd_remark'),
		];
		
		$spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
		$sheet = $this->set_report_header($sheet, range('A', 'Z'), $headers);//report general information setting
        
		$row = 2;
		$style_arr = ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT]];
		
		$apps = $this->general->filter("appointment", $filter, "schedule_from", "desc");
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
		
		$people_arr = [];
		$people = $this->general->filter_adv("person", null, [["field" => "id", "values" => $person_ids]]);
		foreach($people as $item) $people_arr[$item->id] = $item->name;
		
		$headers = [
			$this->lang->line('hd_id'),
			$this->lang->line('hd_registed_at'),
			$this->lang->line('hd_status'),
			$this->lang->line('hd_room'),
			$this->lang->line('hd_from'),
			$this->lang->line('hd_to'),
			$this->lang->line('hd_specialty'),
			$this->lang->line('hd_doctor'),
			$this->lang->line('hd_patient'),
			$this->lang->line('hd_remark'),
			$this->lang->line('hd_result'),
		];
		
		$spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
		$sheet = $this->set_report_header($sheet, range('A', 'Z'), $headers);//report general information setting
        
		$row = 2;
		$style_arr = ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT]];
		
		$sur = $this->general->filter("surgery", $filter, "schedule_from", "desc");
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
			$this->lang->line('hd_id'),
			$this->lang->line('hd_registed_at'),
			$this->lang->line('hd_last_update'),
			$this->lang->line('hd_on_sale'),
			$this->lang->line('hd_category'),
			$this->lang->line('hd_type'),
			$this->lang->line('hd_code'),
			$this->lang->line('hd_description'),
			$this->lang->line('hd_stock'),
			$this->lang->line('hd_options_stock'),
			$this->lang->line('hd_currency'),
			$this->lang->line('hd_price'),
			$this->lang->line('hd_value'),
			$this->lang->line('hd_vat'),
			$this->lang->line('hd_sold_qty'),
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
		
		$products = $this->general->filter("product", $filter, "registed_at", "desc");
		foreach($products as $item){
			$prod_options = $this->general->filter("product_option", ["product_id" => $item->id], "id", "asc");
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
	
	private function set_sheet_sale($data){
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
		
		$people_arr = [];
		$people = $this->general->filter_adv("person", null, [["field" => "id", "values" => $person_ids]]);
		foreach($people as $item) $people_arr[$item->id] = $item->name;
		
		$headers = [
			$this->lang->line('hd_id'),
			$this->lang->line('hd_registed_at'),
			$this->lang->line('hd_last_update'),
			$this->lang->line('hd_status'),
			$this->lang->line('hd_type'),
			$this->lang->line('hd_client'),
			$this->lang->line('hd_currency'),
			$this->lang->line('hd_discount'),
			$this->lang->line('hd_total'),
			$this->lang->line('hd_amount'),
			$this->lang->line('hd_vat'),
			$this->lang->line('hd_paid'),
			$this->lang->line('hd_balance'),
			$this->lang->line('hd_detail_payment'),
			$this->lang->line('hd_appointment_id'),
			$this->lang->line('hd_surgery_id'),
			$this->lang->line('hd_products')
		];
		
		$spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
		$sheet = $this->set_report_header($sheet, range('A', 'Z'), $headers);//report general information setting
        
		$row = 2;
		$style_arr = ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT]];
		
		$sales = $this->general->filter("sale", $filter, "registed_at", "desc");
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
			$sheet->setCellValue('H'.$row, number_format($item->discount, 2));
			$sheet->setCellValue('I'.$row, number_format($item->total, 2));
			$sheet->setCellValue('J'.$row, number_format($item->amount, 2));
			$sheet->setCellValue('K'.$row, number_format($item->vat, 2));
			$sheet->setCellValue('L'.$row, number_format($item->paid, 2));
			$sheet->setCellValue('M'.$row, number_format($item->balance, 2));
			$sheet->setCellValue('N'.$row, $payment_detail);
			$sheet->setCellValue('O'.$row, $item->appointment_id);
			$sheet->setCellValue('P'.$row, $item->surgery_id);
			$sheet->setCellValue('Q'.$row, $products_txt);
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
		
		$people_arr = [];
		$people = $this->general->filter_adv("person", null, [["field" => "id", "values" => $person_ids]]);
		foreach($people as $item) $people_arr[$item->id] = $item->name;
		
		$headers = [
			$this->lang->line('hd_id'),
			$this->lang->line('hd_registed_at'),
			$this->lang->line('hd_status'),
			$this->lang->line('hd_sale_id'),
			$this->lang->line('hd_client'),
			$this->lang->line('hd_type'),
			$this->lang->line('hd_number'),
			$this->lang->line('hd_method'),
			$this->lang->line('hd_currency'),
			$this->lang->line('hd_total'),
			$this->lang->line('hd_amount'),
			$this->lang->line('hd_vat'),
			$this->lang->line('hd_paid'),
			$this->lang->line('hd_change'),
			$this->lang->line('hd_leyend'),
			$this->lang->line('hd_hash'),
			$this->lang->line('hd_sunat_sent'),
			$this->lang->line('hd_sunat_msg'),
		];
		
		$spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
		$sheet = $this->set_report_header($sheet, range('A', 'Z'), $headers);//report general information setting
        
		$row = 2;
		$style_arr = ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT]];
		
		$vouchers = $this->general->filter("voucher", $filter, "registed_at", "desc");
		foreach($vouchers as $item){
			if ($item->sunat_sent) $sunat_sent = "o"; else $sunat_sent = "x";
			if ($item->client_id) $client = $people_arr[$item->client_id]; else $client = null;
			
			if (!$item->status_id) $this->general->update("voucher", $item->id, ["status_id" => 4]);
			$sheet->setCellValue('A'.$row, $item->id);
			$sheet->setCellValue('B'.$row, $item->registed_at);
			$sheet->setCellValue('C'.$row, $status_arr[$item->status_id]);
			$sheet->setCellValue('D'.$row, $item->sale_id);
			$sheet->setCellValue('E'.$row, $client);
			$sheet->setCellValue('F'.$row, $item->type);
			$sheet->setCellValue('G'.$row, $item->letter.$item->serie."-".$item->correlative);
			$sheet->setCellValue('H'.$row, $item->payment_method);
			$sheet->setCellValue('I'.$row, $item->currency_code.", ".$item->currency);
			$sheet->setCellValue('J'.$row, number_format($item->total, 2));
			$sheet->setCellValue('K'.$row, number_format($item->amount, 2));
			$sheet->setCellValue('L'.$row, number_format($item->vat, 2));
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
			$this->lang->line('hd_id'),
			$this->lang->line('hd_registed_at'),
			$this->lang->line('hd_account'),
			$this->lang->line('hd_activity'),
			$this->lang->line('hd_detail'),
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
		$logs = $this->general->filter("log", $filter, "registed_at", "desc");
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
			$this->lang->line('hd_id'),
			$this->lang->line('hd_registed_at'),
			$this->lang->line('hd_last_access'),
			$this->lang->line('hd_enable'),
			$this->lang->line('hd_role'),
			$this->lang->line('hd_name'),
			$this->lang->line('hd_email'),
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
		
		$accounts = $this->general->filter("account", $filter, "registed_at", "desc");
		foreach($accounts as $item){
			$person = $this->general->id("person", $item->person_id);
			if ($person) $person_name = $person->name; else $person_name = "";
			if ($item->active) $enabled = "o"; else $enabled = "x";
			
			$sheet->setCellValue('A'.$row, $item->id);
			$sheet->setCellValue('B'.$row, $item->registed_at);
			$sheet->setCellValue('C'.$row, $item->logged_at);
			$sheet->setCellValue('D'.$row, $enabled);
			$sheet->setCellValue('E'.$row, $this->lang->line($role_arr[$item->role_id]));
			$sheet->setCellValue('F'.$row, $person_name);
			$sheet->setCellValue('G'.$row, $item->email);
			$sheet->getStyle($row)->applyFromArray($style_arr);
			
			$row++;
		}
		
		return $spreadsheet;
	}
}
