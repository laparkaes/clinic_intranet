<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Dompdf\Dompdf;

class File_manage extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->cert_path = FCPATH.'files/';
		$this->invoice_path = FCPATH.'files/sunat/';
	}
	
	public function make_pdf($html, $filename){
		// instantiate and use the dompdf class
		$dompdf = new Dompdf();
		$dompdf->loadHtml($html);

		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper('legal', 'portrait');

		// Render the HTML as PDF
		$dompdf->render();

		// Output the generated PDF to Browser
		$dompdf->stream($filename, array("Attachment" => false));
	}
	
	public function attachment($id){
		//pending! user validation
		//pending! filename setting
		
		$img = base64_encode(file_get_contents(FCPATH."/resorces/formats/placa_hueso.jpg"));
		$data = array("img" => $img);
		
		$html = $this->load->view('/format/attachment', $data, true);
		$filename = "attachment testing";
		
		$this->make_pdf($html, $filename);
	}
	
	public function prescription($id){
		//pending! user validation
		//pending! filename setting
		
		$img = base64_encode(file_get_contents(FCPATH."/resorces/formats/receta_medica.jpg"));
		$data = array("img" => $img);
		
		$html = $this->load->view('/format/prescription', $data, true);
		$filename = "prescription testing";
		
		$this->make_pdf($html, $filename);
	}
}
