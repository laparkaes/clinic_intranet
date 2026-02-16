<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/Lima');
		
		$this->load->model('general_model','general');
		$this->nav_menu = ["sys", "account"];
		$this->nav_menus = $this->utility_lib->get_visible_nav_menus();
	}
	
	public function index(){
		if (!$this->session->userdata('logged_in')) redirect('/');
		if (!$this->utility_lib->check_access("account", "index")) redirect("/errors/no_permission");
		
		$f_url = [
			"page" => $this->input->get("page"),
			"role_id" => $this->input->get("role_id"),
			"person_name" => $this->input->get("person_name"),
		];
		
		$f_w = $f_l = $f_in = [];
		if (!$f_url["page"]) $f_url["page"] = 1;
		if ($f_url["role_id"]) $f_w["role_id"] = $f_url["role_id"];
		if ($f_url["person_name"]){
			$people = $this->general->filter("person", null, [["field" => "name", "values" => explode(" ", trim($f_url["person_name"]))]]);
			
			$values = [];
			foreach($people as $item) $values[] = $item->id;
			if (!$values) $values[] = -1;
			$f_in[] = ["field" => "person_id", "values" => $values];
		}
		
		$f_w["is_valid"] = true;
		
		$rol_sp = [
			'master' => 'Maestro',
			'admin' => 'Admin',
			'doctor' => 'Médico',
			'nurse' => 'Enfermera',
			'patient' => 'Paciente',
			'reception' => 'Recepción',
		];
		
		$accounts = $this->general->filter("account", $f_w, $f_l, $f_in, "registed_at", "desc", 25, 25*($f_url["page"]-1));
		foreach($accounts as $item){
			$item->role = $rol_sp[$this->general->id("role", $item->role_id)->name];
			$item->person = $this->general->id("person", $item->person_id)->name;
		}
		
		
		$roles = $this->general->all("role", "id", "asc");
		foreach($roles as $item){
			$item->sp = $rol_sp[$item->name];
		}
		
		$data = array(
			"paging" => $this->my_func->set_page($f_url["page"], $this->general->counter("account", $f_w, $f_l, $f_in)),
			"f_url" => $f_url,
			"roles" => $roles,
			"doc_types" => $this->general->all("doc_type", "id", "asc"),
			"accounts" => $accounts,
			"title" => "Usuarios",
			"main" => "sys/account/list",
		);
		
		$this->load->view('layout', $data);
	}
	
	public function register(){
		$type = "error"; $msgs = []; $msg = null;
		
		if ($this->utility_lib->check_access("account", "register")){			
			$p = $this->input->post("p");
			$a = $this->input->post("a");
			
			$this->load->library('my_val');
			$msgs = $this->my_val->person($msgs, "ra_", $p);
			$msgs = $this->my_val->account($msgs, "ra_", $a);
			if (!$a["role_id"]) $msgs = $this->my_val->set_msg($msgs, "ra_role_msg", "error", "error_sro");
			if (!$msgs){
				$person = $this->general->filter("person", ["doc_type_id" => $p["doc_type_id"], "doc_number" => $p["doc_number"]]);
				if ($person){
					$this->general->update("person", $person[0]->id, $p);
					$a["person_id"] = $person[0]->id;
				}else{
					$p["registed_at"] = date('Y-m-d H:i:s', time());
					$a["person_id"] = $this->general->insert("person", $p);
				}
				
				if (!$this->general->filter("account", ["email" => $a["email"]])){
					unset($a["confirm"]);
					
					$a["is_valid"] = true;
					$a["password"] = password_hash($a["password"], PASSWORD_BCRYPT);
					$a["registed_at"] = date('Y-m-d H:i:s', time());
					
					if ($this->general->insert("account", $a)){
						$this->utility_lib->add_log("account_register", $a["email"]);
						
						$type = "success";
						$msg = 'Usuario ha sido registrado.';
					}else $msg = 'Ocurrió un error interno. Intente de nuevo.';
				}else $msg = 'Persona ya cuenta con usuario de rol elegido.';
			}else $msg = 'Ocurrió un error. Revise los datos.';
		}else $msg = 'Usted no cuenta con permiso suficiente para realizar esta operación.';
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msgs" => $msgs, "msg" => $msg]);
	}
	
	public function remove(){
		$type = "error"; $msg = null;
		
		if ($this->utility_lib->check_access("account", "delete")){
			$account = $this->general->id("account", $this->input->post("id"));
			if ($account){
				$role = $this->general->id("role", $account->role_id);
				if ($role->name !== "master"){
					if ($this->general->delete("account", ["id" => $account->id])){
						$this->utility_lib->add_log("account_delete", $account->email);
						
						$type = "success";
						$msg = 'Usuario ha sido desactivado.';
					}else $msg = 'Ocurrió un error interno. Intente de nuevo.';
				}else $msg = 'No puede eliminar un usuario maestro.';
			}else $msg = 'Ocurrió un error desconocido. Actualice página y vuelva intentar.';
		}else $msg = 'Usted no cuenta con permiso suficiente para realizar esta operación.';
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg]);
	}
	
	public function reset_password(){
		$type = "error"; $msg = null;
		
		if ($this->utility_lib->check_access("account", "reset_password")){
			$account = $this->general->id("account", $this->input->post("id"));
			if ($account){
				$pw = substr(password_hash("123456", PASSWORD_BCRYPT), 10, 6);
				
				if ($this->general->update("account", $account->id, ["password" => password_hash($pw, PASSWORD_BCRYPT)])){
					$type = "success";
					$msg = str_replace("&pw&", $pw, 'Contraseña ha sido configurado a "&pw&".');
				}else $msg = 'Ocurrió un error interno. Intente de nuevo.';
			}else $msg = 'Ocurrió un error desconocido. Actualice página y vuelva intentar.';
		}else $msg = 'Usted no cuenta con permiso suficiente para realizar esta operación.';
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg]);
	}
}
