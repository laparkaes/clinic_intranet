<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Errors extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->lang->load("error", "spanish");
	}
	
	public function page_missing(){
		$this->load->view('errors/template', ["code" => 404]);
	}
	
	public function no_permission(){
		$this->load->view('errors/template', ["code" => 403]);
	}
}
