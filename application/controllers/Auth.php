<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/Lima');
		$this->lang->load("auth", "spanish");
		$this->lang->load("system", "spanish");
		$this->load->model('general_model','general');
	}
	
	public function index(){
		if ($this->session->userdata('logged_in')) redirect("/dashboard");
		else{
			$this->load->library('my_val');
			if (!$this->my_val->system_init()) redirect("system/system_init");
			//return true if systen init is completed
		}
		
		$this->load->view('auth/index');
	}
	
	public function login(){
		$type = "error"; $msgs = []; $msg = $move_to = null;
		$data = $this->input->post();
		
		$this->load->library('my_val');
		$msgs = $this->my_val->login($msgs, "lg_", $data);
		
		if (!$msgs){
			$account = $this->general->filter("account", ["email" => $data["email"], "is_valid" => true])[0];
			$this->general->update("account", $account->id, ["logged_at" => date('Y-m-d H:i:s', time())]);
			
			$person = $this->general->id("person", $account->person_id);
			
			$session_data = array(
				"aid" => $account->id,
				"pid" => $person->id,
				"name" => $person->name,
				"role" => $this->general->id("role", $account->role_id),
				"logged_in" => true
			);
			$this->session->set_userdata($session_data);
			
			$type = "success";
			if ($data["password"] === $person->doc_number) $move_to = base_url()."auth/change_password";
			else $move_to = base_url()."dashboard";
		}else $msg = $this->lang->line("error_occurred");
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msgs" => $msgs, "msg" => $msg, "move_to" => $move_to]);
	}
	
	public function create_account(){
		$this->load->view('auth/create_account');
	}
	
	public function create_account_process(){
		$type = "error";
		$msg = "";
		$msgs = [];
		$data = $this->input->post();
		
		//account
		if ($data["account"]){
			if ($this->general->filter("account", ["account" => $data["account"], "is_valid" => true]))
				$msgs[] = ["dom_id" => "ac_account_msg", "type" => "danger", "msg" => "Existe usuario registrado."];
		}else $msgs[] = ["dom_id" => "ac_account_msg", "type" => "danger", "msg" => "Ingrese usuario."];
		
		//password
		if ($data["password"]){
			if (strlen($data["password"]) < 6) $msgs[] = ["dom_id" => "ac_password_msg", "type" => "danger", "msg" => "Contraseña debe ser minimo 6 letras."];
		}else $msgs[] = ["dom_id" => "ac_password_msg", "type" => "danger", "msg" => "Ingrese contraseña."];
		
		//confirm
		if ($data["confirm"]){
			if ($data["password"] !== $data["confirm"]) $msgs[] = ["dom_id" => "ac_password_msg", "type" => "danger", "msg" => "Ingrese confirmación correcta."];
		}else $msgs[] = ["dom_id" => "ac_confirm_msg", "type" => "danger", "msg" => "Ingrese confirmación de contraseña."];
		
		//key
		if ($data["key"]){
			if ($data["key"] !== "150990") $msgs[] = ["dom_id" => "ac_key_msg", "type" => "danger", "msg" => "Ingrese clave correcto."];
		}else $msgs[] = ["dom_id" => "ac_key_msg", "type" => "danger", "msg" => "Ingrese clave de sistema."];
		
		if (!$msgs){
			$type = "success";
			
			
			
			//$msg = "";
		}
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msgs" => $msgs, "msg" => $msg]);
	}
	
	public function reset_password(){
		$this->load->view('auth/reset_password');
	}
	
	public function change_password_apply(){
		$type = "error"; $msgs = []; $msg = null;
		
		$data = $this->input->post();
		$this->load->library('my_val');
		$msgs = $this->my_val->change_password($msgs, "pw_", $data);
		
		if (!$msgs){
			$acc_id = $this->session->userdata('aid');
			$acc_data = ["password" => password_hash($data["password_new"], PASSWORD_BCRYPT)];
			
			if ($this->general->update("account", $acc_id, $acc_data)){
				$type = "success";
				$msg = $msg = $this->lang->line("s_change_password");
			}else $msg = $this->lang->line("error_internal");
		}else $msg = $this->lang->line("error_occurred");
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msgs" => $msgs, "msg" => $msg]);
	}
	
	public function logout(){
		$this->session->sess_destroy();
		redirect("/", 'refresh');
	}
}
