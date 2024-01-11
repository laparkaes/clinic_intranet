<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Config extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/Lima');
		$this->lang->load("system", "spanish");
		$this->lang->load("config", "spanish");
		$this->lang->load("log", "spanish");
		$this->load->model('general_model','general');
		$this->nav_menu = ["sys", "config"];
		$this->nav_menus = $this->utility_lib->get_visible_nav_menus();
	}
	
	public function index(){
		if (!$this->session->userdata('logged_in')) redirect("/");
		if (!$this->utility_lib->check_access("config", "index")) redirect("/errors/no_permission");
		
		$modules = ["dashboard", "doctor", "patient", "appointment", "surgery", "product", "sale", "report", "account", "config"];
		$access = [];
		foreach($modules as $item) $access[$item] = $this->general->filter("access", ["module" => $item], null, null, "id", "asc");
		
		$role_access = [];
		$role_access_rec = $this->general->all("role_access", null);
		foreach($role_access_rec as $item) $role_access[] = $item->role_id."_".$item->access_id;
		
		$roles_arr = [];
		$roles = $this->general->all("role", "id", "asc");
		foreach($roles as $item) $roles_arr[$item->id] = $item->name;
		
		$exam_category_arr = [];
		$exam_category = $this->general->all("examination_category", "name", "asc");
		foreach($exam_category as $item) $exam_category_arr[$item->id] = $item->name;
		
		$exams_arr = [];
		$exams = $this->general->all("examination", "name", "asc");
		foreach($exams as $item){
			$exams_arr[$item->id] = $item->name;
			$item->category = $exam_category_arr[$item->category_id];
		}
		
		$exam_profiles = $this->general->all("examination_profile", "name", "asc", 20, 0);
		foreach($exam_profiles as $item){
			$aux = [];
			$exam_ids = explode(",", $item->examination_ids);
			foreach($exam_ids as $i => $exam_id){
				if (array_key_exists($exam_id, $exams_arr)) $aux[] = $exams_arr[$exam_id];
				else unset($exam_ids[$i]);
			}
			$item->exams = implode(", ", $aux);
			$this->general->update("examination_profile", $item->id, ["examination_ids" => implode(",", $exam_ids)]);
		}
		
		$log_code_arr = [];
		$log_codes = $this->general->all("log_code");
		foreach($log_codes as $item) $log_code_arr[$item->id] = $this->lang->line('log_'.$item->code);
		
		$logs = $this->general->all("log", "registed_at", "desc", 20, 0);
		foreach($logs as $item){
			$account_aux = $this->general->id("account", $item->account_id);
			if ($account_aux) $item->account = $account_aux->email; else $item->account = null;
			$item->log_txt = $log_code_arr[$item->log_code_id];
		}
		
		$image_category_arr = [];
		$image_categories = $this->general->all("image_category", "name", "asc");
		foreach($image_categories as $item) $image_category_arr[$item->id] = $item->name;
		
		$sys_conf = $this->general->id("system", 1);
		
		$data = array(
			"role_access" => $role_access,
			"roles_arr" => $roles_arr,
			"roles" => $roles,
			"access" => $access,
			"log_code_arr" => $log_code_arr,
			"logs" => $logs,
			"exam_profiles" => $exam_profiles,
			"exam_category" => $exam_category,
			"exams" => $exams,
			"image_category_arr" => $image_category_arr,
			"image_categories" => $image_categories,
			"images" => $this->general->all("image", "name", "asc", 20, 0),
			"medicines" => $this->general->all("medicine", "name", "asc", 20, 0),
			"departments" => $this->general->all("address_department", "name", "asc"),
			"provinces" => $this->general->all("address_province", "name", "asc"),
			"districts" => $this->general->all("address_district", "name", "asc"),
			"company" => $this->general->id("company", $sys_conf->company_id),
			"title" => $this->lang->line('setting'),
			"main" => "sys/config/index",
			"init_js" => "sys/config/index.js"
		);
		
		$this->load->view('layout', $data);
	}
	
	public function control_access(){
		$type = "error"; $msg = null;

		if ($this->utility_lib->check_access("config", "admin_access")){
			$setting = $this->input->post("setting");
			$setting = isset($setting) && $setting === 'true';
			
			$value = explode("_", $this->input->post("value"));
			$data = array("role_id" => $value[0], "access_id" => $value[1]);
			
			if (!(($this->general->id("role", $value[0])->name === "master") and (!$setting))){
				if ($setting) $this->general->insert("role_access", $data);
				else $this->general->delete("role_access", $data);
				
				$msg = $this->lang->line('s_access_updated');
				$type = "success";
			}else $msg = $this->lang->line('e_access_master');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg]);
	}
	
	public function update_company_data(){
		$type = "error"; $msgs = []; $msg = null;
		
		if ($this->utility_lib->check_access("config", "update_company")){
			$data = $this->input->post();
			
			$this->load->library('my_val');
			$msgs = $this->my_val->config_company($msgs, "uc_", $data);
			
			if (!$msgs){
				$data["updated_at"] = date("Y-m-d H:i:s", time());
				
				$com_id = null;
				$company_rec = $this->general->filter("company", ["tax_id" => $data["tax_id"]]);
				if ($company_rec){
					$com_id = $company_rec[0]->id;
					$this->general->update("company", $com_id, $data);
				}else $com_id = $this->general->insert("company", $data);
				
				if ($com_id){
					$this->utility_lib->add_log("company_update", null);
					$type = "success";
					$msg = $this->lang->line("s_company_update");	
				}else $msg = $this->lang->line("error_internal");
			}else $msg = $this->lang->line("error_occurred");
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msgs" => $msgs, "msg" => $msg]);
	}

	public function system_init(){
		$type = "error"; $msg = null;
		
		if ($this->session->userdata('role')->name === "master"){
			$this->general->update("system", 1, ["is_finished" => false]);
			$this->session->sess_destroy();
			
			$type = "success";
			$msg = $this->lang->line("s_system_init");
		}else $msg = $this->lang->line("error_no_permission");
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg]);
	}

	public function register_profile(){
		$type = "error"; $msgs = []; $msg = null;
		
		if ($this->utility_lib->check_access("config", "admin_profile")){
			$name = $this->input->post("name");
			$exams = $this->input->post("exams");
			
			$this->load->library('my_val');
			$msgs = $this->my_val->profile($msgs, "rp_", $name, $exams);
			
			if (!$msgs){
				sort($exams);
				if ($this->general->insert("examination_profile", ["name" => $name, "examination_ids" => implode(",", $exams)])){
					$this->utility_lib->add_log("profile_register", $name);
					
					$type = "success";
					$msg = $this->lang->line("s_exam_profile_register");
				}else $msg = $this->lang->line("error_internal");
			}else $msg = $this->lang->line("error_occurred");
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msgs" => $msgs, "msg" => $msg]);
	}
	
	public function remove_profile(){
		$type = "error"; $msg = null;
		
		if ($this->utility_lib->check_access("config", "admin_profile")){
			$profile = $this->general->id("examination_profile", $this->input->post("id"));
			if (!$this->general->filter("appointment_examination", ["profile_id" => $profile->id])){
				if ($this->general->delete("examination_profile", ["id" => $profile->id])){
					$this->utility_lib->add_log("profile_delete", $profile->name);
					
					$type = "success";
					$msg = $this->lang->line('s_exam_profile_delete');
				}else $msg = $this->lang->line('error_internal');
			}else $msg = $this->lang->line('e_profile_used');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg]);
	}

	public function load_more_profile(){
		$exams_arr = [];
		$exams = $this->general->all("examination", "name", "asc");
		foreach($exams as $item) $exams_arr[$item->id] = $item->name;
		
		$exam_profiles = $this->general->all("examination_profile", "name", "asc", 20, $this->input->post("offset"));
		foreach($exam_profiles as $item){
			$aux = [];
			$exam_ids = explode(",", $item->examination_ids);
			foreach($exam_ids as $exam_id) $aux[] = $exams_arr[$exam_id];
			$item->exams = implode(", ", $aux);
		}
		
		header('Content-Type: application/json');
		echo json_encode($exam_profiles);
	}
	
	private function get_exam_data(){
		$exam_category_arr = [];
		$exam_category = $this->general->all("examination_category", "name", "asc");
		foreach($exam_category as $item) $exam_category_arr[$item->id] = $item->name;
		
		$exams = $this->general->all("examination", "name", "asc");
		foreach($exams as $item){
			$item->category = $exam_category_arr[$item->category_id];
		}
		
		return [
			"cats" => $exam_category,
			"exams" => $exams,
		];
	}
	
	public function add_exam_category(){
		$type = "error"; $msg = null;
		
		if ($this->utility_lib->check_access("config", "admin_profile")){
			$data = $this->input->post();
			if ($data["name"]){
				if (!$this->general->filter("examination_category", $data)){
					if ($this->general->insert("examination_category", $data)){
						$type = "success";
						$msg = $this->lang->line('s_exam_category_register');
					}else $msg = $this->lang->line('error_internal');
				}else $msg = $this->lang->line('e_exam_category_duplicate');
			}else $msg = $this->lang->line('e_exam_category_name');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg, "data" => $this->get_exam_data()]);
	}
	
	public function remove_exam_category(){
		$type = "error"; $msg = null;
		
		if ($this->utility_lib->check_access("config", "admin_profile")){
			$id = $this->input->post("id");
			if (!$this->general->filter("examination", ["category_id" => $id])){
				if ($this->general->delete("examination_category", ["id" => $id])){
					$type = "success";
					$msg = $this->lang->line('s_exam_category_delete');
				}else $msg = $this->lang->line('error_internal');
			}else $msg = $this->lang->line('e_exam_category_used');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg, "data" => $this->get_exam_data()]);
	}
	
	public function add_exam(){
		$type = "error"; $msg = null;
		
		if ($this->utility_lib->check_access("config", "admin_profile")){
			$data = $this->input->post();
			if ($data["category_id"]){
				if ($data["name"]){
					if (!$this->general->filter("examination", $data)){
						if ($this->general->insert("examination", $data)){
							$type = "success";
							$msg = $this->lang->line('s_exam_register');
						}else $msg = $this->lang->line('error_internal');
					}else $msg = $this->lang->line('e_exam_duplicate');
				}else $msg = $this->lang->line('e_exam_name');
			}else $msg = $this->lang->line('e_exam_category_select');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg, "data" => $this->get_exam_data()]);
	}
	
	public function remove_exam(){
		$type = "error"; $msg = null;
		
		if ($this->utility_lib->check_access("config", "admin_profile")){
			$id = $this->input->post("id");
			if (!$this->general->filter("appointment_examination", ["examination_id" => $id])){
				if ($this->general->delete("examination", ["id" => $id])){
					$type = "success";
					$msg = $this->lang->line('s_exam_delete');
				}else $msg = $this->lang->line('error_internal');
			}else $msg = $this->lang->line('e_exam_used');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg, "data" => $this->get_exam_data()]);
	}
	
	public function register_medicine(){
		$type = "error"; $msgs = []; $msg = null;
		
		if ($this->utility_lib->check_access("config", "admin_medicine")){
			$data = $this->input->post();
			
			$this->load->library('my_val');
			$msgs = $this->my_val->medicine($msgs, "rm_", $data);
			
			if (!$msgs){
				if ($this->general->insert("medicine", $data)){
					$this->utility_lib->add_log("medicine_register", $data["name"]);
						
					$type = "success";
					$msg = $this->lang->line("s_medicine_register");	
				}else $msg = $this->lang->line('error_internal');
			}else $msg = $this->lang->line("error_occurred");
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msgs" => $msgs, "msg" => $msg]);
	}
	
	public function remove_medicine(){
		$type = "error"; $msg = null;
		
		if ($this->utility_lib->check_access("config", "admin_medicine")){
			$medicine = $this->general->id("medicine", $this->input->post("id"));
			if (!$this->general->filter("appointment_medicine", ["medicine_id" => $medicine->id])){
				if ($this->general->delete("medicine", ["id" => $medicine->id])){
					$this->utility_lib->add_log("medicine_delete", $medicine->name);
					
					$type = "success";
					$msg = $this->lang->line('s_medicine_delete');
				}else $msg = $this->lang->line('error_internal');
			}else $msg = $this->lang->line('e_medicine_used');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg]);
	}
	
	public function load_more_medicine(){
		header('Content-Type: application/json');
		echo json_encode($this->general->all("medicine", "name", "asc", 20, $this->input->post("offset")));
	}
	
	public function load_more_log(){
		$log_code_arr = [];
		$log_codes = $this->general->all("log_code");
		foreach($log_codes as $item) $log_code_arr[$item->id] = $this->lang->line('log_'.$item->code);
		
		$logs = $this->general->all("log", "registed_at", "desc", 20, $this->input->post("offset"));
		foreach($logs as $item){
			$account_aux = $this->general->id("account", $item->account_id);
			if ($account_aux) $item->account = $account_aux->email; else $item->account = null;
			$item->log_txt = $log_code_arr[$item->log_code_id];
		}
		
		header('Content-Type: application/json');
		echo json_encode($logs);
	}
	
	public function load_more_image(){
		$image_category_arr = [];
		$image_categories = $this->general->all("image_category", "name", "asc");
		foreach($image_categories as $item) $image_category_arr[$item->id] = $item->name;
		
		$images = $this->general->all("image", "name", "asc", 20, $this->input->post("offset"));
		foreach($images as $item) $item->category = $image_category_arr[$item->category_id];
		
		header('Content-Type: application/json');
		echo json_encode($images);
	}
	
	public function remove_image(){
		$type = "error"; $msg = null;
		
		if ($this->utility_lib->check_access("config", "admin_image")){
			$image = $this->general->id("image", $this->input->post("id"));
			if (!$this->general->filter("appointment_image", ["image_id" => $image->id])){
				if ($this->general->delete("image", ["id" => $image->id])){
					$this->utility_lib->add_log("image_delete", $image->name);
					
					$type = "success";
					$msg = $this->lang->line('s_image_delete');
				}else $msg = $this->lang->line('error_internal');
			}else $msg = $this->lang->line('e_image_used');
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msg" => $msg]);
	}
	
	public function register_image(){
		$type = "error"; $msgs = []; $msg = null;
		
		if ($this->utility_lib->check_access("config", "admin_image")){
			$data = $this->input->post();
			
			$this->load->library('my_val');
			$msgs = $this->my_val->image($msgs, "ri_", $data);
			
			if (!$msgs){
				if ($this->general->insert("image", $data)){
					$this->utility_lib->add_log("image_register", $data["name"]);
						
					$type = "success";
					$msg = $this->lang->line("s_image_register");	
				}else $msg = $this->lang->line('error_internal');
			}else $msg = $this->lang->line("error_occurred");
		}else $msg = $this->lang->line('error_no_permission');
		
		header('Content-Type: application/json');
		echo json_encode(["type" => $type, "msgs" => $msgs, "msg" => $msg]);
	}
}