<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Therapy</title>
    <style>
	* {
		margin: 0;
		padding: 0;
		box-sizing: border-box;
	}

	body {
		display: flex;
		flex-direction: column;
		height: 100vh; /* 화면 전체 높이 */
	}

	.header {
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 170px;
	}

	.content {
		flex: 1;
		margin-top: 170px;
		margin-bottom: 100px;
		overflow-y: auto;
		padding-top: 50px;
	}

	.footer {
		position: fixed;
		bottom: 0;
		left: 0;
		width: 100%;
		height: 100px;
		text-align: center;
	}
	
	table{
		font-size: 12px;
	}
    </style>
    <script>
	window.onload = function () {
		window.print(); // 인쇄 창 열기
		setTimeout(function () {
			window.close(); // 일정 시간 후 창 닫기 (일부 브라우저에서 필요)
		}, 500); 
	};
    </script>
</head>
<body>
    <div class="header">
		<div style="text-align: center;"><strong>RECETA TERAPÉUTICA / TERAPIA FÍSICA</strong></div>
		<br/>
		<div style="text-align: right;">Historia Clínica Nro. <?= $patient->doc_number ?></div>
		<br/>
		<div><label style="display: inline-block; width: 100px;">Paciente</label>: <?= $patient->name ?></div>
		<div><label style="display: inline-block; width: 100px;">Edad</label>: <?= $patient->age !== "-" ? $patient->age." años" : "" ?></div>
		<div><label style="display: inline-block; width: 100px;">Fecha</label>: <?= date("d/M/Y") ?></div>
	</div>
    <div class="content">
		<?php foreach($therapy as $i => $item){ ?>
		<div>
			<div><strong><?= $item->physical_therapy ?></strong></div>
			<div><?= $item->sub_txt ?></div>
			<br/>
		</div>
		<?php } ?>
    </div>
    <div class="footer">
		<div>..............................................................</div>
		<div><?= $doctor->name ?></div>
		<div><?= $doctor->data->specialty ?></div>
		<div><?= $doctor->data->license ?></div>
	</div>
</body>
</html>