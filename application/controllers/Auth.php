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
		
		if ($this->general->filter("account", array("role_id" => $this->role->name("master")->id))) $has_master = true;
		else $has_master = false;
		
		$data = array(
			"title" => $this->lang->line('lg_title'),
			"doc_types" => $this->general->all("doc_type", "id", "asc"),
			"has_master" => $has_master
		);
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
	
	public function forgot_pass(){
		$status = false; $msgs = array(); $msg = null;
		$email = $this->input->post("email");
		
		if ($email){
			$account = $this->account->email($email);
			if ($account){
				if ($this->account->update($account->id, array("reset_request" => true))){
					$status = true;
					$msg = $this->lang->line('success_prr');
				}else $msgs = $this->set_msg($msgs, "gm_result_msg", "error", "error_internal");
			}else $msgs = $this->set_msg($msgs, "fg_email_msg", "error", "error_une");
		}else $msgs = $this->set_msg($msgs, "fg_email_msg", "error", "error_use");
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "msgs" => $msgs, "msg" => $msg));
	}
	
	public function generate_master(){
		$status = false; $type = "error"; $msgs = array(); $msg = null;
		
		$p = $this->input->post("p");//person
		$a = $this->input->post("a");//account
		
		//person validation
		if (!$p["doc_number"]) $msgs = $this->set_msg($msgs, "gm_doc_msg", "error", "error_edn");
		if (!$p["name"]) $msgs = $this->set_msg($msgs, "gm_name_msg", "error", "error_nae");
		
		//account validation
		if ($a["email"]){
			if (filter_var($a["email"], FILTER_VALIDATE_EMAIL)){
				if ($this->account->email($a["email"])) $msgs = $this->set_msg($msgs, "gm_email_msg", "error", "error_usr");
			}else $msgs = $this->set_msg($msgs, "gm_email_msg", "error", "error_ufo");
		}else $msgs = $this->set_msg($msgs, "gm_email_msg", "error", "error_use");
		if (strlen($a["password"]) < 6) $msgs = $this->set_msg($msgs, "gm_pass_msg", "error", "error_pal");
		if (strcmp($a["password"], $a["confirm"])) $msgs = $this->set_msg($msgs, "gm_confirm_msg", "error", "error_par");
		
		if ($msgs) $msg = $this->lang->line('error_occurred');
		else{
			$p_aux = $p; unset($p_aux["name"]);
			$person_aux = $this->general->filter("person", $p_aux);
			if ($person_aux) $person_id = $person_aux[0]->id;
			else $person_id = $this->general->insert("person", $p);
			
			if ($person_id){
				unset($a["confirm"]);
				$a["person_id"] = $person_id;
				$a["password"] = password_hash($a["password"], PASSWORD_BCRYPT);
				$a["registed_at"] = date('Y-m-d H:i:s', time());
				$account_id = $this->general->insert("account", $a);
				if ($account_id){
					$role_id = $this->role->name("master")->id;
					if ($this->general->update("account", $account_id, ["role_id" => $role_id])){
						$status = true;
						$type = "success";
						$msg = $this->lang->line('success_mac');
					}else $msg = $this->lang->line('error_ari');
				}else $msg = $this->lang->line('error_age');
				
				if (!$status) $this->general->delete("account", array("id" => $account_id));
			}else $msg = $this->lang->line('error_pin');
		}
		
		header('Content-Type: application/json');
		echo json_encode(array("status" => $status, "msgs" => $msgs, "msg" => $msg));
	}
	
	public function logout(){
		$this->session->sess_destroy();
		redirect("/", 'refresh');
	}
}
