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
	
	/* return 36A 3M */
	public function age_calculator_1($birthday, $need_number = false){
		$date1 = date_create($birthday);
		$date2 = date_create(date("Y-m-d"));
		$diff = date_diff($date1, $date2);
		
		if ($need_number) return $diff->y;
		else return $diff->y."A ".$diff->m."M";
	}
	
	/* return 36 */
	function age_calculator(string $birthDate): ?int{//checked 20241213
		// 입력된 생년월일 포맷 확인
		if (empty($birthDate) or ($birthDate === "0000-00-00")) {
			return null;
		}

		try {
			// 1. 생년월일과 현재 날짜를 DateTime 객체로 생성합니다.
			// 입력 포맷이 'YYYY-MM-DD'이므로 DateTime::createFromFormat을 사용하지 않아도 안전합니다.
			$birth = new DateTime($birthDate);
			$now = new DateTime('now');
			
			// 2. 두 날짜 사이의 차이를 DateInterval 객체로 계산합니다.
			// $interval은 날짜, 월, 년도의 차이를 포함합니다.
			$interval = $now->diff($birth);
			
			// 3. DateInterval 객체의 'y' 속성(차이나는 년도)을 반환합니다.
			// 이것이 정확한 '만 나이'가 됩니다.
			return $interval->y;

		} catch (Exception $e) {
			// 날짜 포맷이 잘못되었을 경우 예외 처리
			error_log("유효하지 않은 날짜 포맷: " . $e->getMessage());
			return null;
		}
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