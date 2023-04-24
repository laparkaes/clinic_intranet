<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/Lima');
		$this->lang->load("system", "spanish");
		$this->lang->load("report", "spanish");
	}
	
	public function index(){
		if (!$this->session->userdata('logged_in')) redirect(base_url());
		//pending! rol validation
		
		$data = array(
			"title" => "Reportes",
			"main" => "report/index",
			"init_js" => "report/index.js"
		);
		
		$this->load->view('layout', $data);
	}
	
	
}
