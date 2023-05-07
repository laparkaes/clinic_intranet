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
	
	public function set_page($page, $qty){
		$pages = [];
		if ($qty){
			$last = floor($qty / 25); if ($qty % 25) $last++;
			if (3 < $page) $pages[] = [1, "<<", "outline-primary"];
			if (3 < $page) $pages[] = [$page-3, "...", "outline-primary"];
			if (2 < $page) $pages[] = [$page-2, $page-2, "outline-primary"];
			if (1 < $page) $pages[] = [$page-1, $page-1, "outline-primary"];
			$pages[] = [$page, $page, "primary"];
			if ($page+1 <= $last) $pages[] = [$page+1, $page+1, "outline-primary"];
			if ($page+2 <= $last) $pages[] = [$page+2, $page+2, "outline-primary"];
			if ($page+3 <= $last) $pages[] = [$page+3, "...", "outline-primary"];
			if ($page+3 <= $last) $pages[] = [$last, ">>", "outline-primary"];
		}
		
		return $pages;
	}
}