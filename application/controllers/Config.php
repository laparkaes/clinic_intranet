<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Config extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/Lima');
		$this->lang->load("system", "spanish");
		$this->lang->load("config", "spanish");
		$this->load->model('general_model','general');
	}
	
	private function set_msg($msgs, $dom_id, $type, $msg_code){
		if ($msg_code) array_push($msgs, array("dom_id" => $dom_id, "type" => $type, "msg" => $this->lang->line($msg_code)));
		return $msgs;
	}
	
	public function index(){
		if (!$this->session->userdata('logged_in')) redirect(base_url());
		//pending! rol validation
		
		$roles = $this->general->all("role", "id", "asc");
		
		$data = array(
			"roles" => $roles,
			"departments" => $this->general->all("address_department", "name", "asc"),
			"provinces" => $this->general->all("address_province", "name", "asc"),
			"districts" => $this->general->all("address_district", "name", "asc"),
			"company" => $this->general->id("company", 1),
			"title" => $this->lang->line('setting'),
			"main" => "config/index",
			"init_js" => "config/index.js"
		);
		
		$this->load->view('layout', $data);
	}
	
	public function update_company(){
		$datas = $this->input->post();
		$status = false; $msgs = array(); $msg = null; $cert_link = null;
		
		//validations
		if (!$datas["ruc"]) $msgs = $this->set_msg($msgs, "com_ruc_msg", "error", "error_cru");
		if (!$datas["name"]) $msgs = $this->set_msg($msgs, "com_name_msg", "error", "error_cna");
		if (!$datas["email"]) $msgs = $this->set_msg($msgs, "com_email_msg", "error", "error_cem");
		if (!$datas["tel"]) $msgs = $this->set_msg($msgs, "com_tel_msg", "error", "error_cte");
		if (!$datas["address"]) $msgs = $this->set_msg($msgs, "com_address_msg", "error", "error_cad");
		if (!$datas["department_id"]) $msgs = $this->set_msg($msgs, "com_department_msg", "error", "error_cde");
		if (!$datas["province_id"]) $msgs = $this->set_msg($msgs, "com_province_msg", "error", "error_cpr");
		if (!$datas["district_id"]) $msgs = $this->set_msg($msgs, "com_district_msg", "error", "error_cdi");
		if (!$datas["sunat_resolution"]) $msgs = $this->set_msg($msgs, "s_res_msg", "error", "error_sre");
		if (!$datas["sunat_clave_sol"]) $msgs = $this->set_msg($msgs, "s_cla_msg", "error", "error_scs");
		if (!$datas["sunat_password"]) $msgs = $this->set_msg($msgs, "s_pas_msg", "error", "error_spa");
		if (!$_FILES["sunat_cert_file"]["name"]) if (!$this->general->id("company", 1)->sunat_cert_filename) 
			$msgs = $this->set_msg($msgs, "s_cer_msg", "error", "error_sce");
	
		if (!$msgs){
			$datas["ubigeo"] = $this->general->id("address_district", $datas["district_id"])->ubigeo;
			$datas["updated_by"] = $this->session->userdata('aid');
			$datas["updated_at"] = date("Y-m-d H:i:s", time());
			if ($_FILES["sunat_cert_file"]["name"]){
				$upload_dir = "uploaded/sunat";
				if(!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
				$upload_dir = $upload_dir."/";
				
				$this->load->library('upload');
				$config_upload = array(
					'upload_path' => $upload_dir,
					'allowed_types' => '*',
					'max_size' => 0,
					'overwrite' => true
				);
				
				$this->upload->initialize($config_upload);
				if ($this->upload->do_upload("sunat_cert_file")){
					$result = $this->upload->data();
					$datas["sunat_cert_filename"] = $result["file_name"];
				}else $msgs = $this->set_msg($msgs, "s_cer_msg", "error", $this->upload->display_errors("<span>","</span>"));
			}
			
			if ($this->general->update("company", 1, $datas)){
				$cert_link = base_url()."uploaded/sunat/".$this->general->id("company", 1)->sunat_cert_filename;
				$status = true;
				$msg = $this->lang->line("success_cup");
			}else $msgs = $this->set_msg($msgs, "com_result_msg", "error", "error_internal");
		}
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "msgs" => $msgs, "msg" => $msg, "cert_link" => $cert_link));
	}
}
