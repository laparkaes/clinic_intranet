<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/Lima');
		$this->lang->load("auth", "spanish");
		$this->lang->load("system", "spanish");
		$this->load->model('role_model','role');
		$this->load->model('general_model','general');
		$this->load->model('sl_option_model','sl_option');
		$this->load->model('account_model','account');
	}
	
	private function set_msg($msgs, $dom_id, $type, $msg_code){
		if ($msg_code) array_push($msgs, array("dom_id" => $dom_id, "type" => $type, "msg" => $this->lang->line($msg_code)));
		return $msgs;
	}
	
	public function index(){
		if ($this->session->userdata('logged_in')) redirect("/dashboard");
		else{
			$this->load->library('my_val');
			if (!$this->my_val->system_init()) redirect("config/system_init");
			//return true if systen init is completed
		}
		
		$data = [
			"title" => $this->lang->line('lg_title'),
		];
		$this->load->view('auth/index', $data);
	}
	
	public function login(){
		$status = false; $msgs = array();
		$email = $this->input->post("email");
		$password = $this->input->post("password");
		$msgs = array();
		
		$account = $this->account->email($email);
		if ($email){
			if (filter_var($email, FILTER_VALIDATE_EMAIL)){
				if (!$account) $msgs = $this->set_msg($msgs, "lg_email_msg", "error", "error_une");
			}else $msgs = $this->set_msg($msgs, "lg_email_msg", "error", "error_ufo");
		}else $msgs = $this->set_msg($msgs, "lg_email_msg", "error", "error_use");
		
		if ($password){
			if ($account) if (!password_verify($password, $account->password))
				$msgs = $this->set_msg($msgs, "lg_pass_msg", "error", "error_paw");
		}else $msgs = $this->set_msg($msgs, "lg_pass_msg", "error", "error_pae");

		if (!$msgs){
			$this->general->update("account", $account->id, ["logged_at" => date('Y-m-d H:i:s', time())]);
			
			$role = $this->general->id("role", $account->role_id);
			//set session datas here
			$session_data = array(
				"aid" => $account->id,
				"pid" => $account->person_id,
				"name" => $this->general->id("person", $account->person_id)->name,
				"role" => $role,
				"logged_in" => true
			);
			$this->session->set_userdata($session_data);
			$status = true;
		}
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "msgs" => $msgs));
	}
	
	public function logout(){
		$this->session->sess_destroy();
		redirect("/", 'refresh');
	}
}
