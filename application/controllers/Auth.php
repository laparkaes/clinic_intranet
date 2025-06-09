<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/Lima');
		$this->lang->load("auth", "spanish");
		$this->lang->load("system", "spanish");
		$this->load->model('general_model','general');
		
		$this->sys_key = "150990";//Nasthya's birthday
	}
	
	public function index(){
		if ($this->session->userdata('logged_in')) redirect("/dashboard");
		
		$this->load->view('auth/index');
	}
	
	public function login_process(){
		$type = "error"; 
		$msgs = [];
		$data = $this->input->post();
		
		$account = $this->general->filter("account", ["account" => $data["account"], "is_valid" => true]);
		$pass = "xxxxxxxxxxxxxxxx";//aux string to make error
		
		//account
		if ($data["account"]){//blank?
			if (!$account)//exists?
				$msgs[] = ["dom_id" => "lg_account_msg", "type" => "danger", "msg" => "Usuario no registrado."];
			else $pass = $account[0]->password;//save en var to compare later
		}else $msgs[] = ["dom_id" => "lg_account_msg", "type" => "danger", "msg" => "Ingrese usuario."];
		
		
		//password
		if ($data["password"]){//blank?
			if (strlen($data["password"]) < 6)//less than 6 chars?
				$msgs[] = ["dom_id" => "lg_password_msg", "type" => "danger", "msg" => "Contraseña debe ser minimo 6 letras."];
			elseif (!password_verify($data["password"], $pass))//password ok?
				$msgs[] = ["dom_id" => "lg_password_msg", "type" => "danger", "msg" => "Error de contraseña."];
		}else $msgs[] = ["dom_id" => "lg_password_msg", "type" => "danger", "msg" => "Ingrese contraseña."];
		
		if (!$msgs){
			$this->general->update("account", $account[0]->id, ["logged_at" => date('Y-m-d H:i:s', time())]);
			
			$modules = [];
			$access = $this->general->filter("account_access", ["account_id" => $account[0]->id]);
			foreach($access as $item) $modules[] = $this->general->unique("access", "id", $item->access_id)->code;
			
			
		}
		/*
		if (!$msgs){
			
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
		*/
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msgs" => $msgs]);
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
		if ($data["account"]){//blank?
			if ($this->general->filter("account", ["account" => $data["account"], "is_valid" => true]))//duplicated?
				$msgs[] = ["dom_id" => "ac_account_msg", "type" => "danger", "msg" => "Existe usuario registrado."];
		}else $msgs[] = ["dom_id" => "ac_account_msg", "type" => "danger", "msg" => "Ingrese usuario."];
		
		//password
		if ($data["password"]){//blank?
			if (strlen($data["password"]) < 6)//less than 6 chars?
				$msgs[] = ["dom_id" => "ac_password_msg", "type" => "danger", "msg" => "Contraseña debe ser minimo 6 letras."];
		}else $msgs[] = ["dom_id" => "ac_password_msg", "type" => "danger", "msg" => "Ingrese contraseña."];
		
		//confirm
		if ($data["confirm"]){//blank?
			if ($data["password"] !== $data["confirm"])//same to password?
				$msgs[] = ["dom_id" => "ac_confirm_msg", "type" => "danger", "msg" => "Ingrese confirmación correcta."];
		}else $msgs[] = ["dom_id" => "ac_confirm_msg", "type" => "danger", "msg" => "Ingrese confirmación de contraseña."];
		
		//key
		if ($data["key"]){//blank?
			if ($data["key"] !== $this->sys_key)//defined in constructor
				$msgs[] = ["dom_id" => "ac_key_msg", "type" => "danger", "msg" => "Ingrese clave correcto."];
		}else $msgs[] = ["dom_id" => "ac_key_msg", "type" => "danger", "msg" => "Ingrese clave de sistema."];
		
		if (!$msgs){
			unset($data["confirm"]);
			unset($data["key"]);
			
			$data["is_valid"] 		= true;
			$data["password"]		= password_hash($data["password"], PASSWORD_BCRYPT);
			$data["registed_at"]	= date('Y-m-d H:i:s', time());
			
			if ($this->general->insert("account", $data)){
				$type = "success";
				$msg = "Usuario ha ido creado con exito.";
				
				$this->utility_lib->add_log("Crear usuario ".$data["account"]);
			}
		}
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msgs" => $msgs, "msg" => $msg]);
	}
	
	public function reset_password(){
		$this->load->view('auth/reset_password');
	}
	
	public function reset_password_process(){
		$type = "error";
		$msg = "";
		$msgs = [];
		$data = $this->input->post();
		
		//account
		if ($data["account"]){//blank?
			if (!$this->general->filter("account", ["account" => $data["account"], "is_valid" => true]))//exist account?
				$msgs[] = ["dom_id" => "pw_account_msg", "type" => "danger", "msg" => "No existe usuario registrado."];
		}else $msgs[] = ["dom_id" => "pw_account_msg", "type" => "danger", "msg" => "Ingrese usuario."];
		
		//password
		if ($data["password"]){//blank?
			if (strlen($data["password"]) < 6)//less than 6 chars?
				$msgs[] = ["dom_id" => "pw_password_msg", "type" => "danger", "msg" => "Contraseña debe ser minimo 6 letras."];
		}else $msgs[] = ["dom_id" => "pw_password_msg", "type" => "danger", "msg" => "Ingrese contraseña nueva."];
		
		//confirm
		if ($data["confirm"]){//blank?
			if ($data["password"] !== $data["confirm"])//same to password?
				$msgs[] = ["dom_id" => "pw_confirm_msg", "type" => "danger", "msg" => "Ingrese confirmación correcta."];
		}else $msgs[] = ["dom_id" => "pw_confirm_msg", "type" => "danger", "msg" => "Ingrese confirmación de contraseña nueva."];
		
		//key
		if ($data["key"]){//blank?
			if ($data["key"] !== $this->sys_key)//defined in constructor
				$msgs[] = ["dom_id" => "pw_key_msg", "type" => "danger", "msg" => "Ingrese clave correcto."];
		}else $msgs[] = ["dom_id" => "pw_key_msg", "type" => "danger", "msg" => "Ingrese clave de sistema."];
		
		if (!$msgs){
			unset($data["confirm"]);
			unset($data["key"]);
			
			$data["password"]	= password_hash($data["password"], PASSWORD_BCRYPT);
			$data["updated_at"]	= date('Y-m-d H:i:s', time());
			
			$account = $this->general->unique("account", "account", $data["account"]);
			if ($this->general->update("account", $account->id, $data)){
				$type = "success";
				$msg = "Contraseña de usuario [".$data["account"]."] ha ido actualizada.";
				
				$this->utility_lib->add_log("Actualizar contraseña de usuario ".$data["account"]);
			}
		}
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msgs" => $msgs, "msg" => $msg]);
	}
	
	public function logout(){
		$this->session->sess_destroy();
		redirect("/", 'refresh');
	}
}
