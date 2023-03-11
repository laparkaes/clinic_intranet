<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends CI_Controller {

	public function __construct(){
		parent::__construct();
		date_default_timezone_set('America/Lima');
		$this->lang->load("system", "spanish");
	}
	
	public function index(){
		if (!$this->session->userdata('logged_in')) redirect(base_url());
		//pending! rol validation
		//pending! load account record
		$data = array(
			"title" => "Usuario",
			"main" => "account/index",
			"init_js" => "account/index.js"
		);
		
		$this->load->view('layout', $data);
	}
	
	
}
