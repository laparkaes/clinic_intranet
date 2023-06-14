<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Utility_lib{
	
	public function __construct(){
		$this->CI = &get_instance();
	}
	
	public function add_log($code, $detail){
		$log_code = $this->CI->general->filter("log_code", ["code" => $code]);
		if ($log_code) $this->CI->general->insert("log", ["account_id" => $this->CI->session->userdata('aid'), "log_code_id" => $log_code[0]->id, "detail" => $detail, "registed_at" => date('Y-m-d H:i:s', time())]);
	}
	
	public function check_access($module, $description){
		$result = false;
		$role_id = $this->CI->session->userdata('role')->id;
		//$role_id = 3;
		
		$access = $this->CI->general->filter("access", ["module" => $module, "description" => $description]);
		if ($access)
			if ($this->CI->general->filter("role_access", ["role_id" => $role_id, "access_id" => $access[0]->id]))
				$result = true;
		
		return $result;
	}
	
	public function utildatos_dni($dni){
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://utildatos.com/api/dni',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => false,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => array('dni' => $dni),
			CURLOPT_HTTPHEADER => array('Authorization: Bearer {3a0d55aad08e889e277a8585e6d24e}'),
		));

		$response = json_decode(curl_exec($curl));
		curl_close($curl);
		
		$res = new stdClass;
		$res->status = false;
		$res->data = null;
		if ($response){
			if (property_exists($response, 'success')){
				$res->status = $response->success;
				$res->data = $response->result;
			}	
		}
		
		return $res;
	}
	
	public function utildatos_ruc($ruc){
		$curl = curl_init();

		curl_setopt_array($curl, [
			CURLOPT_URL => 'https://utildatos.com/api/sunat-reducido',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => false,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => ['ruc' => $ruc],
			CURLOPT_HTTPHEADER => ['Authorization: Bearer {3a0d55aad08e889e277a8585e6d24e}']
		]);

		$response = json_decode(curl_exec($curl));
		curl_close($curl);
		
		$res = new stdClass;
		$res->status = false;
		$res->data = null;
		if ($response){
			if (property_exists($response, 'success')){
				$res->status = $response->success;
				$res->data = $response->result;
			}	
		}
		
		return $res;
		
		//stdClass Object ( [status] => [message] => No se encontro el ruc ) 
		//stdClass Object ( [success] => 1 [result] => stdClass Object ( [ruc] => 20557939645 [razon_social] => MOARA PERU E.I.R.L. [estado] => SUSPENSION TEMPORAL [condicion_domicilio] => HABIDO [ubigeo] => 150130 [tipo_via] => AV. [nombre_via] => SAN BORJA SUR [codigo_zona] => URB. [tipo_zona] => SAN BORJA [numero] => 689 [interior] => - [lote] => - [departamento] => 401 [manzana] => - [kilometro] => - [direccion] => AV. SAN BORJA SUR URB. SAN BORJA Nro. 689 Dpto. 401 ) ) 
	}
	
	public function get_visible_nav_menus(){
		$access_ids = [];
		
		if ($this->CI->session->userdata('role')) $role_id = $this->CI->session->userdata('role')->id;
		else $role_id = -1;
		
		$role_access = $this->CI->general->filter("role_access", ["role_id" => $role_id]);
		if ($role_access){ foreach($role_access as $item) $access_ids[] = $item->access_id; }
		else $access_ids = [-1];
		
		$nav_menus = [];
		$access = $this->CI->general->filter("access", ["description" => "index"], null, [["field" => "id", "values" => $access_ids]]);
		foreach($access as $item) $nav_menus[] = $item->module;
		
		return $nav_menus;
	}
	
}