<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/Lima');
		$this->lang->load("system", "spanish");
		$this->lang->load("purchase", "spanish");
		$this->load->model('general_model','general');
		$this->nav_menu = ["commerce", "purchase"];
		$this->nav_menus = $this->utility_lib->get_visible_nav_menus();
		$this->sunat_resolution = "0180050001138";
	}
	
	public function index(){
		
	}
}
