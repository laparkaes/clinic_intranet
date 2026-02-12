<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicine</title>
    <style>
	*{margin: 0; pardding: 0; font-size: 11px;}

	#back_img{width: 100%;}
	
	#print_area{
		width: 210mm;
		height: 148mm;
		overflow: hidden;
        position: relative;
	}
	
	.border{ border: solid 1px red; }
	.line1{ position: absolute; top: 21%; }
	.line2{ position: absolute; top: 25%; }
	.line3{ position: absolute; top: 30%; }
	.line4{ position: absolute; top: 37%; }
	.line5{ position: absolute; top: 45%; }
	.line6{ position: absolute; top: 90%; }
	
	/* line1 */
	#name_1{ left: 19%; width: 30%; }
	#name_2{ left: 66%; width: 30%; }
	
	/* line2 */
	#document_1{ left: 9%; width: 10%; }
	#age_1{ left: 37%; width: 10%; }
	
	/* line3 */
	#diag_1{ left: 14%; width: 10%; }
	#cie_1{ left: 37%; width: 10%; }
	
	/* line4 */
	#medicine_2{ left: 54%; width: 43%; }
	
	/* line5 */
	#medicine_1{ left: 6%; width: 42%; }
	
	/* line6 */
	#date_1{ left: 7%; width: 10%; }
	#date_2{ left: 56%; width: 10%; }
    </style>
</head>
<body>
	<div id="print_area">
		<div class="border line1" id="name_1"><?= $patient->name ?></div>
		<div class="border line2" id="document_1"><?= $patient->doc_number ?></div>
		<div class="border line2" id="age_1"><?= $patient->age !== "-" ? $patient->age." años" : "-" ?></div>
		<div class="border line3" id="diag_1">Diag aqui</div>
		<div class="border line3" id="cie_1">CIE aqui</div>
		<div class="border line5" id="medicine_1">
			<?php foreach($medicine as $i => $item){ ?>
			<div>
				<div><strong><?= $item->medicine ?></strong></div>
				<div><?= $item->sub_txt ?></div>
				<br/>
			</div>
			<?php } ?>
		</div>
		<div class="border line6" id="date_1"><?= date("d/M/Y") ?></div>
		
		<div class="border line1" id="name_2"><?= $patient->name ?></div>
		<div class="border line4" id="medicine_2">
			<?php foreach($medicine as $i => $item){ ?>
			<div>
				<div><strong><?= $item->medicine ?></strong></div>
				<div><?= $item->sub_txt ?></div>
				<br/>
			</div>
			<?php } ?>
		</div>
		<div class="border line6" id="date_2"><?= date("d/M/Y") ?></div>
		
		<div>
			<img id="back_img" src="<?= base_url() ?>resources/images/prescription_sample.jpeg">
		</div>
	</div>
</body>

<script>
window.onload = function () {
	window.print();
	
	return;
	setTimeout(function () {
		window.close(); // 일정 시간 후 창 닫기 (일부 브라우저에서 필요)
	}, 500); 
};
</script>
</html>