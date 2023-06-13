<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Luecano\NumeroALetras\NumeroALetras;
use Dompdf\Dompdf;

class My_func{
	
	public function __construct(){
		$this->CI = &get_instance();
	}
	
	public function make_pdf($html, $filename){
		// instantiate and use the dompdf class
		$dompdf = new Dompdf();

		// (Optional) Setup the paper size and orientation
		//$dompdf->setPaper('A4', 'portrait');//vertical [0.0, 0.0, 595.28, 841.89]
		//$dompdf->setPaper('A4', 'landscape');//horizontal
		$dompdf->setPaper(array(0,0,240,600));
		
		$GLOBALS['bodyHeight'] = 0;
		$dompdf->setCallbacks(
			array(
				'myCallbacks' => array(
				'event' => 'end_frame', 'f' => function ($infos) {
					$frame = $infos->get_frame();
					if (!strcmp("body", $frame->get_node()->nodeName))
						$GLOBALS['bodyHeight'] += $frame->get_padding_box()['h'];
				})
			)
		);
		
		$dompdf->loadHtml($html);
		$dompdf->render();
		unset($dompdf);
		
		$dompdf = new Dompdf();
		$dompdf->set_paper(array(0,0,240,$GLOBALS['bodyHeight']+20));

		// Render the HTML as PDF
		$dompdf->loadHtml($html);
		$dompdf->render();
		
		// Output the generated PDF to Browser
		if ($dompdf) $dompdf->stream($filename, array("Attachment" => false));
		else echo "Error";
	}
	
	public function get_numletter($num, $cur){
		switch($cur){
			case "USD": $formatter_currency = "DÓLARES"; break;
			default: $formatter_currency = "SOLES";//PEN
		}
		$formatter = new NumeroALetras();
		
		return $formatter->toInvoice($num, 2, $formatter_currency);
	}
	
	public function age_calculator($birthday, $need_number = false){
		$date1 = date_create($birthday);
		$date2 = date_create(date("Y-m-d"));
		$diff = date_diff($date1, $date2);
		
		if ($need_number) return $diff->y;
		else return $diff->y."A ".$diff->m."M";
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