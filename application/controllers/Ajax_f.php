<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax_f extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/Lima');
		$this->lang->load("system", "spanish");
		//$this->load->model('sl_option_model','sl_option');
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
	
}