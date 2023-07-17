<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/* My validations */
class My_val{
	
	public function __construct(){
		$this->CI = &get_instance();
		$this->CI->lang->load("validation", "spanish");
		$this->CI->load->model('general_model','general');
	}
	
	public function set_msg($msgs, $dom_id, $type, $msg_code){
		if ($msg_code) $msgs[] = ["dom_id" => $dom_id, "type" => $type, "msg" => $this->CI->lang->line($msg_code)];
		return $msgs;
	}
	
	public function person($msgs, $prefix, $data){
		if (!$data["doc_type_id"]) $msgs = $this->set_msg($msgs, $prefix."doc_type_msg", "error", "e_required_field");
		if (!$data["doc_number"]) $msgs = $this->set_msg($msgs, $prefix."doc_number_msg", "error", "e_required_field");
		if (!$data["name"]) $msgs = $this->set_msg($msgs, $prefix."name_msg", "error", "e_required_field");
		if (array_key_exists("tel", $data))
			if (!$data["tel"]) $msgs = $this->set_msg($msgs, $prefix."tel_msg", "error", "e_required_field");
		if (array_key_exists("email", $data))
			if ($data["email"]) 
				if (!filter_var($data["email"], FILTER_VALIDATE_EMAIL))
					$msgs = $this->set_msg($msgs, $prefix."email_msg", "error", "e_email_format");
		/* optionals: $p["birthday"], $p["sex"], $p["blood_type"], $p["address"] */
		
		return $msgs;
	}
	
	public function doctor($msgs, $prefix, $data, $dup = true){
		if (!$data["specialty_id"]) $msgs = $this->set_msg($msgs, $prefix."specialty_msg", "error", "e_required_field");
		if (!$data["license"]) $msgs = $this->set_msg($msgs, $prefix."license_msg", "error", "e_required_field");
		
		return $msgs;
	}
	
	public function account($msgs, $prefix, $data){
		$msgs = $this->email($msgs, $prefix, $data);
		
		if ($data["password"]){
			if (strlen($data["password"]) >= 6){
				if (strcmp($data["password"], $data["confirm"])) 
					$msgs = $this->set_msg($msgs, $prefix."confirm_msg", "error", "e_password_confirm");
			}else $msgs = $this->set_msg($msgs, $prefix."password_msg", "error", "e_password_length");
		}else $msgs = $this->set_msg($msgs, $prefix."password_msg", "error", "e_required_field");
		
		return $msgs;
	}
	
	public function email($msgs, $prefix, $data){
		if ($data["email"]){
			if (filter_var($data["email"], FILTER_VALIDATE_EMAIL)){
				if ($this->CI->general->filter("account", ["email" => $data["email"]])) 
					$msgs = $this->set_msg($msgs, $prefix."email_msg", "error", "e_account_exists");
			}else $msgs = $this->set_msg($msgs, $prefix."email_msg", "error", "e_enter_email_format");
		}else $msgs = $this->set_msg($msgs, $prefix."email_msg", "error", "e_required_field");
		
		return $msgs;
	}
	
	public function schedule($msgs, $prefix, $data){
		if (!$data["date"] or !$data["hour"] or !$data["min"])
			$msgs = $this->set_msg($msgs, $prefix."schedule_msg", "error", "e_required_field");
		
		return $msgs;
	}
	
	public function appointment($msgs, $prefix, $app, $sch, $pt){
		$msgs = $this->person($msgs, $prefix."pt_", $pt);//patient
		$msgs = $this->schedule($msgs, $prefix, $sch);//schedule
		
		//appointment data
		if (!$app["specialty_id"]) $msgs = $this->set_msg($msgs, $prefix."specialty_msg", "error", "e_required_field");
		if (!$app["doctor_id"]) $msgs = $this->set_msg($msgs, $prefix."doctor_msg", "error", "e_required_field");
		
		//appointment availability
		if (!$msgs){
			//check doctor = patient?
			if ($pt["doc_type_id"] and $pt["doc_number"]){
				$f = ["doc_type_id" => $pt["doc_type_id"], "doc_number" => $pt["doc_number"]];
				$person = $this->CI->general->filter("person", $f);
				if ($person) 
					if ($app["doctor_id"] == $person[0]->id) 
						$msgs = $this->set_msg($msgs, $prefix."doctor_msg", "error", "e_person_doctor_patient");
			}
			
			//status ids for filter
			$app["schedule_from"] = $sch["date"]." ".$sch["hour"].":".$sch["min"];
			$app["schedule_to"] = date("Y-m-d H:i:s", strtotime("+14 minutes", strtotime($app["schedule_from"])));
			$status_ids = [
				//$this->CI->general->filter("status", ["code" => "reserved"])[0]->id,
				$this->CI->general->filter("status", ["code" => "confirmed"])[0]->id
			];
			
			//check appointment and surgery available
			$sur_available = $this->CI->general->is_available("surgery", $app, $status_ids);
			$app_available = $this->CI->general->is_available("appointment", $app, $status_ids);
			if (!($sur_available and $app_available)) 
				$msgs = $this->set_msg($msgs, $prefix."schedule_msg", "error", "e_doctor_no_available");
		}
		
		return $msgs;
	}
	
	public function appointment_reschedule($msgs, $prefix, $appointment, $data){
		$msgs = $this->schedule($msgs, $prefix, $data);//schedule
		
		if ($appointment){
			$schedule_from = $data["date"]." ".$data["hour"].":".$data["min"];
			$app = [
				"id" => $appointment->id,
				"doctor_id" => $appointment->doctor_id,
				"schedule_from" => $schedule_from,
				"schedule_to" => date("Y-m-d H:i:s", strtotime("+14 minutes", strtotime($schedule_from)))
			];
			
			$status_ids = [
				$this->CI->general->filter("status", ["code" => "reserved"])[0]->id,
				$this->CI->general->filter("status", ["code" => "confirmed"])[0]->id
			];
			
			//check appointment and surgery available
			$sur_available = $this->CI->general->is_available("surgery", $app, $status_ids);
			$app_available = $this->CI->general->is_available("appointment", $app, $status_ids);
			if (!($sur_available and $app_available)) 
				$msgs = $this->set_msg($msgs, $prefix."schedule_msg", "error", "e_doctor_no_available");
		}
		
		return $msgs;
	}
	
	public function appointment_basic_data($msgs, $data){
		//data validation
		if (!$data["entry_mode_id"]) $msgs = $this->set_msg($msgs, "bd_entry_mode_msg", "error", "e_required_field");
		if (!$data["date"]) $msgs = $this->set_msg($msgs, "bd_date_msg", "error", "e_required_field");
		if (!$data["time"]) $msgs = $this->set_msg($msgs, "bd_time_msg", "error", "e_required_field");
		
		//insurance validation
		if ($data["insurance"] === "1")
			if (!$data["insurance_name"])
				$msgs = $this->set_msg($msgs, "bd_insurance_name_msg", "error", "e_required_field");
		
		return $msgs;
	}
	
	public function appointment_physical_therapy($msgs, $data){
		if (!$data["physical_therapy_id"]) $msgs = $this->set_msg($msgs, "at_physical_therapy_msg", "error", "e_required_field");
		if ($data["session"] < 1) $msgs = $this->set_msg($msgs, "at_session_msg", "error", "e_min_session");
		if ($data["frequency"] < 1) $msgs = $this->set_msg($msgs, "at_frequency_msg", "error", "e_number_frequency");
		
		$filter = [
			"appointment_id" => $data["appointment_id"],
			"physical_therapy_id" => $data["physical_therapy_id"]
		];
		if ($this->CI->general->filter("appointment_therapy", $filter))
			$msgs = $this->set_msg($msgs, "at_physical_therapy_msg", "error", "e_therapy_exists");
		
		return $msgs;
	}
	
	public function appointment_medicine($msgs, $data){
		if (!$data["medicine_id"]) $msgs = $this->set_msg($msgs, "md_medicine_msg", "error", "e_required_field");
		if ($data["quantity"]){
			if (is_numeric($data["quantity"])){
				if ($data["quantity"] < 1) $msgs = $this->set_msg($msgs, "md_quantity_msg", "error", "e_number_medicine_quantity");
			}else $msgs = $this->set_msg($msgs, "md_quantity_msg", "error", "e_number_medicine_quantity");
		}else $msgs = $this->set_msg($msgs, "md_quantity_msg", "error", "e_insert_medicine_quantity");
		
		$filter = ["appointment_id" => $data["appointment_id"], "medicine_id" => $data["medicine_id"]];
		if ($this->CI->general->filter("appointment_medicine", $filter))
			$msgs = $this->set_msg($msgs, "md_medicine_msg", "error", "e_medicine_exists");
		
		return $msgs;
	}
	
	public function surgery($msgs, $prefix, $sur, $sch, $pt){
		$msgs = $this->person($msgs, $prefix."pt_", $pt);//patient
		$msgs = $this->schedule($msgs, $prefix, $sch);//schedule
		if (!$sch["duration"]) $msgs = $this->set_msg($msgs, $prefix."duration_msg", "error", "e_required_field");
		
		//surgery data
		if (!$sur["specialty_id"]) $msgs = $this->set_msg($msgs, $prefix."specialty_msg", "error", "e_required_field");
		if (!$sur["doctor_id"]) $msgs = $this->set_msg($msgs, $prefix."doctor_msg", "error", "e_required_field");
		if (!$sur["room_id"]) $msgs = $this->set_msg($msgs, $prefix."room_msg", "error", "e_required_field");
		
		//appointment availability
		if (!$msgs){
			//check doctor = patient?
			if ($pt["doc_type_id"] and $pt["doc_number"]){
				$f = ["doc_type_id" => $pt["doc_type_id"], "doc_number" => $pt["doc_number"]];
				$person = $this->CI->general->filter("person", $f);
				if ($person) 
					if ($sur["doctor_id"] == $person[0]->id) 
						$msgs = $this->set_msg($msgs, $prefix."doctor_msg", "error", "e_person_doctor_patient");
			}
			
			//status ids for filter
			$sur["schedule_from"] = $sch["date"]." ".$sch["hour"].":".$sch["min"];
			$sur["schedule_to"] = date("Y-m-d H:i:s", strtotime("+".($sch["duration"]-1)." minutes", strtotime($sur["schedule_from"])));
			$status_ids = [
				//$this->CI->general->filter("status", ["code" => "reserved"])[0]->id,
				$this->CI->general->filter("status", ["code" => "confirmed"])[0]->id
			];
			
			//check appointment and surgery available
			$sur_available = $this->CI->general->is_available("surgery", $sur, $status_ids);
			$app_available = $this->CI->general->is_available("appointment", $sur, $status_ids);
			if (!($sur_available and $app_available)) 
				$msgs = $this->set_msg($msgs, $prefix."schedule_msg", "error", "e_doctor_no_available");
			
			//check room availability
			if ($this->CI->general->get_by_room("surgery", $sur, $status_ids, null, $sur["room_id"])) 
				$msgs = $this->set_msg($msgs, $prefix."room_msg", "error", "e_room_no_available");
		}
		
		return $msgs;
	}
	
	public function surgery_reschedule($msgs, $prefix, $surgery, $data){
		$msgs = $this->schedule($msgs, $prefix, $data);//schedule
		if (!$data["room_id"]) $msgs = $this->set_msg($msgs, $prefix."room_msg", "error", "e_required_field");
		if (!$data["duration"]) $msgs = $this->set_msg($msgs, $prefix."duration_msg", "error", "e_required_field");
		
		if ($surgery){
			$schedule_from = $data["date"]." ".$data["hour"].":".$data["min"];
			$sur = [
				"id" => $surgery->id,
				"doctor_id" => $surgery->doctor_id,
				"room_id" => $data["room_id"],
				"schedule_from" => $schedule_from,
				"schedule_to" => date("Y-m-d H:i:s", strtotime("+14 minutes", strtotime($schedule_from)))
			];
			
			$status_ids = [
				$this->CI->general->filter("status", ["code" => "reserved"])[0]->id,
				$this->CI->general->filter("status", ["code" => "confirmed"])[0]->id
			];
			
			//check appointment and surgery available
			$sur_available = $this->CI->general->is_available("surgery", $sur, $status_ids);
			$app_available = $this->CI->general->is_available("appointment", $sur, $status_ids);
			if (!($sur_available and $app_available)) 
				$msgs = $this->set_msg($msgs, $prefix."schedule_msg", "error", "e_doctor_no_available");
			
			//check room availability
			if ($this->CI->general->get_by_room("surgery", $sur, $status_ids, null, $sur["room_id"])) 
				$msgs = $this->set_msg($msgs, $prefix."room_msg", "error", "e_room_no_available");
		}
		
		return $msgs;
	}
	
	public function file_upload($msgs, $prefix, $title, $filename){
		if (!$title) $msgs = $this->set_msg($msgs, $prefix."title_msg", "error", "e_required_field");
		if (!$filename) $msgs = $this->set_msg($msgs, $prefix."file_msg", "error", "e_required_field");
		
		return $msgs;
	}
	
	public function product_category_move($msgs, $prefix, $id_from, $id_to){
		if (!$id_from) $msgs = $this->set_msg($msgs, $prefix."id_from_msg", "error", "e_required_field");
		if (!$id_to) $msgs = $this->set_msg($msgs, $prefix."id_to_msg", "error", "e_required_field");
		elseif ($id_from == $id_to) $msgs = $this->set_msg($msgs, $prefix."id_to_msg", "error", "e_category_diff");
		
		return $msgs;
	}
	
	public function product($msgs, $prefix, $data, $id = null){
		//provider data is optional
		if ($data["code"]){
			$product = $this->CI->product->filter(array("code" => $data["code"]));
			if ($product){
				$product = $product[0];
				if ($id){
					if ($id != $product->id) $msgs = $this->set_msg($msgs, $prefix."code_msg", "error", "e_product_code_exists");
				}else $msgs = $this->set_msg($msgs, $prefix."code_msg", "error", "e_product_code_exists");
			}
		}else $msgs = $this->set_msg($msgs, $prefix."code_msg", "error", "e_required_field");
		if (!$data["description"]) $msgs = $this->set_msg($msgs, $prefix."description_msg", "error", "e_required_field");
		if (!$data["category_id"]) $msgs = $this->set_msg($msgs, $prefix."category_msg", "error", "e_required_field");
		if (!$data["currency_id"]) $msgs = $this->set_msg($msgs, $prefix."price_msg", "error", "e_required_field");
		if ($data["price"]){
			if (is_numeric($data["price"])){
				if ($data["price"] < 0) $msgs = $this->set_msg($msgs, $prefix."price_msg", "error", "e_enter_positive_num");
			}else $msgs = $this->set_msg($msgs, $prefix."price_msg", "error", "e_enter_number");
		}else $msgs = $this->set_msg($msgs, $prefix."price_msg", "error", "e_required_field");
		
		return $msgs;
	}
	
	public function product_provider($msgs, $data){
		if (!$data["name"]) $msgs = $this->set_msg($msgs, "epv_company_msg", "error", "e_required_field");
		if (!$data["tax_id"]) $msgs = $this->set_msg($msgs, "epv_ruc_msg", "error", "e_required_field");
		
		return $msgs;
	}
	
	public function product_option($msgs, $prefix, $data){
		if ($data["description"]){
			$f = array("product_id" => $data["product_id"], "description" => $data["description"]);
			if ($this->CI->general->filter("product_option", $f))
				$msgs = $this->set_msg($msgs, $prefix."description_msg", "error", "error_ope");
		}else $msgs = $this->set_msg($msgs, $prefix."description_msg", "error", "error_opd");
		if ($data["stock"]){
			if (filter_var($data["stock"], FILTER_VALIDATE_INT) !== false){
				if ($data["stock"] < 0) $msgs = $this->set_msg($msgs, $prefix."stock_msg", "error", "error_epn");
			}else $msgs = $this->set_msg($msgs, $prefix."stock_msg", "error", "error_ein");
		}else $msgs = $this->set_msg($msgs, $prefix."stock_msg", "error", "error_es");
		
		return $msgs;
	}
	
	public function sale_client($msgs, $data){
		$doc_type = $this->CI->general->id("doc_type", $data["doc_type_id"]);
		if ($doc_type->description !== "Sin Documento"){
			if (!$data["doc_number"]) $msgs = $this->set_msg($msgs, "client_doc_number_msg", "error", "e_required_field");
			if (!$data["name"]) $msgs = $this->set_msg($msgs, "client_name_msg", "error", "e_required_field");
		}
		
		return $msgs;
	}
	
	public function sale_payment($msgs, $data){
		if (($data["received"] <= 0) or (!$data["received"]))
			$msgs = $this->set_msg($msgs, "pay_received_msg", "error", "e_no_received");
		
		return $msgs;
	}
	
	public function sale_products($products_json){
		$msg = null; $products = [];
		if ($products_json){
			$types = [];
			foreach($products_json as $item){
				$prod = json_decode($item);
				$prod_rec = $this->CI->general->id("product", $prod->product_id);
				$types[] = $prod_rec->type_id;
				
				//stock validation
				if ($prod->option_id){
					if ($prod->qty > $this->CI->general->id("product_option", $prod->option_id)->stock)
						$msg = $prod_rec->description." ".$this->CI->lang->line('e_product_no_stock');
				}
				
				$products[] = $prod;
			}
			
			$types = array_unique($types);
			if (count($types) > 1) $msg = $this->CI->lang->line('e_product_type_mixed');
		}else $msg = $this->CI->lang->line('e_product_select');
		
		return ["msg" => $msg, "products" => $products];
	}
	
	public function voucher($msgs, $prefix, $data){
		$voucher_type = $this->CI->general->id("voucher_type", $data["voucher_type_id"]);
		$client = $data["cli"];
		
		$doc_type = $this->CI->general->filter("doc_type", ["id" => $client["doc_type_id"]])[0];
		if ($doc_type->description !== "Sin Documento"){
			if (!$client["name"]) $msgs = $this->set_msg($msgs, $prefix."name_msg", "error", "e_required_field");
			if (!$client["doc_type_id"]) $msgs = $this->set_msg($msgs, $prefix."doc_type_msg", "error", "e_required_field");
			if (!$client["doc_number"]) $msgs = $this->set_msg($msgs, $prefix."doc_number_msg", "error", "e_required_field");	
		}
		
		if ($voucher_type->description === "Factura")
			if ("RUC" !== $doc_type->short) $msgs = $this->set_msg($msgs, $prefix."doc_type_msg", "error", "e_required_field");
		
		return $msgs;
	}
	
	public function report($msgs, $prefix, $data){
		if (!$data["type_id"]) $msgs = $this->set_msg($msgs, $prefix."type_msg", "error", "e_required_field");
		if (!$data["from"]) $msgs = $this->set_msg($msgs, $prefix."from_msg", "error", "e_required_field");
		
		return $msgs;
	}
	
	public function config_company($msgs, $prefix, $data){
		if (!$data["tax_id"]) $msgs = $this->set_msg($msgs, $prefix."tax_id_msg", "error", "e_required_field");
		if (!$data["name"]) $msgs = $this->set_msg($msgs, $prefix."name_msg", "error", "e_required_field");
		if (!$data["email"]) $msgs = $this->set_msg($msgs, $prefix."email_msg", "error", "e_required_field");
		if (!$data["tel"]) $msgs = $this->set_msg($msgs, $prefix."tel_msg", "error", "e_required_field");
		if (!$data["address"]) $msgs = $this->set_msg($msgs, $prefix."address_msg", "error", "e_required_field");
		if (!$data["department_id"]) $msgs = $this->set_msg($msgs, $prefix."department_msg", "error", "e_required_field");
		if (!$data["province_id"]) $msgs = $this->set_msg($msgs, $prefix."province_msg", "error", "e_required_field");
		if (!$data["district_id"]) $msgs = $this->set_msg($msgs, $prefix."district_msg", "error", "e_required_field");
		
		return $msgs;
	}
	
	public function profile($msgs, $prefix, $name, $exams){
		if (!$name) $msgs = $this->set_msg($msgs, $prefix."name_msg", "error", "e_required_field");
		elseif ($this->CI->general->filter("examination_profile", ["name" => $name])) 
			$msgs = $this->set_msg($msgs, $prefix."name_msg", "error", "e_duplate_profile_name");
			
		if (!$exams) $msgs = $this->set_msg($msgs, $prefix."exams_msg", "error", "e_select_profile_exams");
		
		return $msgs;
	}
	
	public function medicine($msgs, $prefix, $data){
		if (!$data["name"]) $msgs = $this->set_msg($msgs, $prefix."name_msg", "error", "e_required_field");
		elseif ($this->CI->general->filter("medicine", $data)) 
			$msgs = $this->set_msg($msgs, $prefix."name_msg", "error", "e_duplate_medicine_name");
			
		return $msgs;
	}
	
	public function image($msgs, $prefix, $data){
		if (!$data["category_id"]) $msgs = $this->set_msg($msgs, $prefix."category_id_msg", "error", "e_required_field");
		
		if (!$data["name"]) $msgs = $this->set_msg($msgs, $prefix."name_msg", "error", "e_required_field");
		elseif ($this->CI->general->filter("image", $data)) 
			$msgs = $this->set_msg($msgs, $prefix."name_msg", "error", "e_duplate_image_name");
			
		return $msgs;
	}
	
	public function system_init(){
		$res = true;
		$sys_cfg = $this->CI->general->id("system", 1);
		if ($sys_cfg){
			foreach($sys_cfg as $key => $value) if (!$value) $res = false;
		}else $res = false;
		
		return $res;
	}
	
	public function system_init_finish(){
		$res = true;
		$sys_cfg = $this->CI->general->id("system", 1);
		
		if ($sys_cfg){
			foreach($sys_cfg as $key => $value) if ($key !== "is_finished") if (!$value) $res = false;
		}else $res = false;
		
		return $res;
	}
	
	public function login($msgs, $prefix, $data){
		$account = $this->CI->general->filter("account", ["email" => $data["email"], "is_valid" => true]);
		
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
		
		return $msgs;
	}
	
	public function change_password($msgs, $prefix, $data){
		if ($data["password_actual"]){
			if (strlen($data["password_actual"]) >= 6){
				$account = $this->CI->general->id("account", $this->CI->session->userdata('aid'));
				if (!password_verify($data["password_actual"], $account->password))
					$msgs = $this->set_msg($msgs, $prefix."actual_msg", "error", "e_password_wrong");
			}else $msgs = $this->set_msg($msgs, $prefix."actual_msg", "error", "e_password_length");
		}else $msgs = $this->set_msg($msgs, $prefix."actual_msg", "error", "e_required_field");

		if ($data["password_actual"] !== $data["password_new"]){
			if ($data["password_new"]){
				if (strlen($data["password_new"]) >= 6){
					if (strcmp($data["password_new"], $data["confirm"])) 
						$msgs = $this->set_msg($msgs, $prefix."confirm_msg", "error", "e_password_confirm");
				}else $msgs = $this->set_msg($msgs, $prefix."new_msg", "error", "e_password_length");
			}else $msgs = $this->set_msg($msgs, $prefix."new_msg", "error", "e_required_field");
		}else $msgs = $this->set_msg($msgs, $prefix."new_msg", "error", "e_required_field");
		
		return $msgs;
	}
	
	public function void_voucher($msgs, $prefix, $data){
		if (!$data["reason"]) $msgs = $this->set_msg($msgs, $prefix."reason_msg", "error", "e_required_field");
		
		return $msgs;
	}
	
	public function sale_type($msgs, $prefix, $data){
		if ($data["description"]){
			if ($this->CI->general->filter("sale_type", ["description" => $data["description"]])) 
				$msgs = $this->set_msg($msgs, $prefix."description_msg", "error", "e_duplicate_description");
		}else $msgs = $this->set_msg($msgs, $prefix."description_msg", "error", "e_required_field");
		
		if ($data["sunat_serie"]){
			if (!$this->CI->general->filter("sale_type", ["sunat_serie" => $data["sunat_serie"]])){
				if (!preg_match('/^\d+$/', $data["sunat_serie"]))
					$msgs = $this->set_msg($msgs, $prefix."sunat_serie_msg", "error", "e_numeric_sunat_serie");
			}else $msgs = $this->set_msg($msgs, $prefix."sunat_serie_msg", "error", "e_duplicate_sunat_serie");
		}else $msgs = $this->set_msg($msgs, $prefix."sunat_serie_msg", "error", "e_required_field");
		
		if ($data["start_factura"]){
			if (!is_numeric($data["start_factura"])) $msgs = $this->set_msg($msgs, $prefix."start_factura_msg", "error", "e_enter_number");
		}else $msgs = $this->set_msg($msgs, $prefix."start_factura_msg", "error", "e_required_field");
		
		if ($data["start_boleta"]){
			if (!is_numeric($data["start_boleta"])) $msgs = $this->set_msg($msgs, $prefix."start_boleta_msg", "error", "e_enter_number");
		}else $msgs = $this->set_msg($msgs, $prefix."start_boleta_msg", "error", "e_required_field");
		
		return $msgs;
	}
	
	public function sunat($msgs, $prefix, $data){
		if (!$data["sunat_certificate"]) $msgs = $this->set_msg($msgs, $prefix."certificate_msg", "error", "e_required_field");
		if (!$data["sunat_username"]) $msgs = $this->set_msg($msgs, $prefix."username_msg", "error", "e_required_field");
		if (!$data["sunat_password"]) $msgs = $this->set_msg($msgs, $prefix."password_msg", "error", "e_required_field");
		
		return $msgs;
	}
	
	
	public function credit($msgs, $prefix, $data){
		if (!$data["currency_id"]) $msgs = $this->set_msg($msgs, $prefix."currency_msg", "error", "e_required_field");
		if ($data["amount"]){
			if (!is_numeric($data["amount"])) $msgs = $this->set_msg($msgs, $prefix."amount_msg", "error", "e_enter_number");
		}else $msgs = $this->set_msg($msgs, $prefix."amount_msg", "error", "e_required_field");
		
		return $msgs;
	}
}