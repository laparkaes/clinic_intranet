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
	}
	
	public function index(){
		if (!$this->session->userdata('logged_in')) redirect('/');
		if (!$this->utility_lib->check_access("purchase", "index")) redirect("/errors/no_permission");
		
		$f_url = [
			"page" => $this->input->get("page"),
			"provider" => $this->input->get("provider"),
		];
		
		$purchases = [];
		
		$data = array(
			"paging" => 1,//$this->my_func->set_page($f_url["page"], $this->general->counter("purchase", $f_w)),
			"f_url" => $f_url,
			"purchases" => $purchases,
			"title" => $this->lang->line('purchases'),
			"main" => "commerce/purchase/list",
		);
		
		$this->load->view('layout', $data);
	}
}
