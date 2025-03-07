<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imagenes</title>
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
		padding-left: 50px;
		padding-right: 50px;
	}

	.footer {
		position: fixed;
		bottom: 0;
		left: 0;
		width: 100%;
		height: 100px;
		text-align: center;
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
		<div style="text-align: center;"><strong>ORDEN MÉDICA DE IMAGEN</strong></div>
		<br/>
		<div style="text-align: right;">Historia Clínica Nro. <?= $patient->doc_number ?></div>
		<br/>
		<div><label style="display: inline-block; width: 100px;">Paciente</label>: <?= $patient->name ?></div>
		<div><label style="display: inline-block; width: 100px;">Edad</label>: <?= $patient->age !== "-" ? $patient->age." años" : "" ?></div>
		<div><label style="display: inline-block; width: 100px;">Fecha</label>: <?= date("d/M/Y") ?></div>
	</div>
    <div class="content">
		<?php foreach($image as $i => $item){ ?>
		<div><?= ($i + 1).". [".$item->category."] ".$item->name ?></div>
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