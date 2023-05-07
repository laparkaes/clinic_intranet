<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/* My validations */
class My_func{
	
	public function __construct(){
		$this->CI = &get_instance();
		//$this->CI->lang->load("validation", "spanish");
		//$this->CI->load->model('general_model','general');
	}
	
	function randomString($characters, $length = 20) {
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}
}