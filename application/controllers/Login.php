<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/Lima');
		$this->lang->load("auth", "spanish");
		$this->lang->load("system", "spanish");
		$this->load->model('georgio_model','gm');
	}
	
	public function index(){
		if ($this->session->userdata('logged_in')) redirect("/dashboard");
		
		$this->load->view('login/index');
	}
	
	public function validation(){
		$type = "error"; $msgs = []; $msg = $move_to = null;
		$data = $this->input->post();
		
		$w = ["username" => $data["username"], "is_valid" => true];
		$account = $this->gm->filter("account", $w);
		
		print_r($account);
		
		if ($account){
			
		}else $msgs[] = ["dom_id" => "lg_username_msg", "type" => "error", "msg" => "Usuario no existe."];
		
		echo "<br/><br/><br/>";
		print_r($msgs);
		
		return;
		
		if ($account){
			$account = $account[0];
			if ($data["email"]){
				if (!filter_var($data["email"], FILTER_VALIDATE_EMAIL))
					$msgs = $this->set_msg($msgs, $prefix."email_msg", "error", "e_enter_email_format");
			}else $msgs = $this->set_msg($msgs, $prefix."email_msg", "error", "e_email_format");	
			
			if ($data["password"]){
				if (!password_verify($data["password"], $account->password))
					$msgs = $this->set_msg($msgs, $prefix."pass_msg", "error", "e_password_wrong");
			}else $msgs = $this->set_msg($msgs, $prefix."pass_msg", "error", "e_required_field");
		}else $msgs = $this->set_msg($msgs, $prefix."email_msg", "error", "e_account_no_exists");
		
		
		//return $msgs;
		
		
		
		
		
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
	
	public function change_password(){
		if (!$this->session->userdata('logged_in')) redirect("/auth");
		
		$this->load->view('auth/change_password', ["account" => $this->general->id("account", $this->session->userdata('aid'))]);
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
