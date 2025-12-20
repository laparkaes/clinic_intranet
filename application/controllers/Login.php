<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/Lima');
		$this->load->model('georgio_model','gm');
	}
	
	public function index(){
		if ($this->session->userdata('logged_in')) redirect("/home");
		
		$this->load->view('login');
	}
	
	public function validation(){//revisado en 20251211
		//initial parameters
		$type = "error"; 
		$msgs = []; 
		$msg = "Revise los datos.";
		$move_to = null;
		
		//receiving post datas
		$data = $this->input->post();
		
		//load account record
		$w = ["email" => $data["username"], "is_valid" => true];
		$account = $this->gm->filter("account", $w);
		
		//account data validation
		if ($account){
			$account = $account[0];
			
			if ($data["password"]){
				if (password_verify($data["password"], $account->password)){
					//login process start
					
					//update last login time
					$this->gm->update("account", $account->id, ["logged_at" => date('Y-m-d H:i:s', time())]);
					
					//set session data
					$session_data = [
						"aid" => $account->id,
						"name" => $account->name,
						"logged_in" => true
					];
					$this->session->set_userdata($session_data);
					
					//success login process
					$type = "success";
					$move_to = base_url()."home";
				}else $msgs[] = ["dom_id" => "lg_password_msg", "type" => "error", "msg" => "Contraseña incorrecta."];
			}else $msgs[] = ["dom_id" => "lg_password_msg", "type" => "error", "msg" => "Campo requerido."];
		}else $msgs[] = ["dom_id" => "lg_username_msg", "type" => "error", "msg" => "Usuario no existe."];
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msgs" => $msgs, "msg" => $msg, "move_to" => $move_to]);
	}
	
	public function logout(){
		$this->session->sess_destroy();
		redirect("/", 'refresh');
	}
}
