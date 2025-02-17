<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicine</title>
    <style>
	* {
		margin: 0;
		padding: 0;
		box-sizing: border-box;
	}

	body {
		font-size: 14px;
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
		<table style="width: 100%;">
			<tr>
				<td style="padding-right: 20px;">
					<div style="text-align: center;"><strong>RECETA MÉDICA</strong></div>
					<br/>
					<div style="text-align: right;">Historia Clínica Nro. <?= $patient->doc_number ?></div>
					<br/>
					<div><label style="display: inline-block; width: 100px;">Paciente</label>: <?= $patient->name ?></div>
					<div><label style="display: inline-block; width: 100px;">Edad</label>: <?= $patient->age !== "-" ? $patient->age." años" : "" ?></div>
					<div><label style="display: inline-block; width: 100px;">Fecha</label>: <?= date("d/M/Y") ?></div>
				</td>
				<td style="padding-left: 20px;">
					<div style="text-align: center;"><strong>RECETA MÉDICA</strong></div>
					<br/>
					<div style="text-align: right;">Historia Clínica Nro. <?= $patient->doc_number ?></div>
					<br/>
					<div><label style="display: inline-block; width: 100px;">Paciente</label>: <?= $patient->name ?></div>
					<div><label style="display: inline-block; width: 100px;">Edad</label>: <?= $patient->age !== "-" ? $patient->age." años" : "" ?></div>
					<div><label style="display: inline-block; width: 100px;">Fecha</label>: <?= date("d/M/Y") ?></div>
				</td>
			</tr>
		</table>
	</div>
    <div class="content">
		<table style="width: 100%;">
			<tr>
				<td style="padding-right: 20px;">
					<?php foreach($medicine as $i => $item){ ?>
					<div>
						<div><strong><?= $item->medicine ?></strong></div>
						<div><?= $item->sub_txt ?></div>
						<br/>
					</div>
					<?php } ?>

				</td>
				<td style="padding-left: 20px;">
					<?php foreach($medicine as $i => $item){ ?>
					<div>
						<div><strong><?= $item->medicine ?></strong></div>
						<div><?= $item->sub_txt ?></div>
						<br/>
					</div>
					<?php } ?>
				</td>
			</tr>
		</table>    
	</div>
    <div class="footer">
		<table style="width: 100%;">
			<tr>
				<td style="padding-right: 20px;">
					<div>..............................................................</div>
					<div><?= $doctor->name ?></div>
					<div><?= $doctor->data->specialty ?></div>
					<div><?= $doctor->data->license ?></div>
				</td>
				<td style="padding-left: 20px;">
					<div>..............................................................</div>
					<div><?= $doctor->name ?></div>
					<div><?= $doctor->data->specialty ?></div>
					<div><?= $doctor->data->license ?></div>
				</td>
			</tr>
		</table>	</div>
</body>
</html>