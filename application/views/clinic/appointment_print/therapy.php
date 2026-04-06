<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Orden de Terapia Física</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            color: #000;
        }
        .container {
			width: 90%;
            margin: 0 auto;
            padding: 10px 0;
        }
        .header {
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .logo {
            max-width: 40mm;
            height: auto;
            margin-bottom: 5px;
        }
        .hospital-name {
            font-size: 16px;
            font-weight: bold;
            display: block;
        }
        .info-section {
            margin-bottom: 10px;
            line-height: 1.4;
        }
        .label {
            font-weight: bold;
        }
        .therapy-section {
            border-top: 1px double #000;
            border-bottom: 1px double #000;
            padding: 10px 0;
            margin: 10px 0;
        }
        .therapy-title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
            text-decoration: underline;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
        }
        .signature-area {
            margin-top: 40px;
            border-top: 1px solid #000;
            width: 50mm;
            margin-left: auto;
            margin-right: auto;
            padding-top: 5px;
        }
        .stamp-area {
            height: 60px;
        }
        
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
		<img src="<?= base_url() ?>resources/images/logo_300.png" style="width:100px;">
		<br/><br/>
        <span class="hospital-name">CLÍNICA EVERLYN</span>
        <small>ORDEN DE TERAPIA FÍSICA</small>
    </div>

    <div class="info-section">
        <div><span class="label">PACIENTE:</span> <?= $patient->name ?></div>
        <div><span class="label"><?= $patient->doc_type->short ?>:</span> <?= $patient->doc_number ?></div>
        <div><span class="label">FECHA:</span> <?= date("d/m/Y") ?></div>
        <div><span class="label">DIAGNÓSTICO:</span> <?= $diag_impression ? $diag_impression[0]->description : "" ?></div>
    </div>

    <div class="therapy-section">
        <div class="therapy-title">Lista de Terapia</div>
        <ul style="padding-left: 20px; margin: 5px 10px;">
			<?php foreach($therapy as $i => $item){ ?>
			<li> <?= $item->physical_therapy ?></li>
			<?php } ?>
        </ul>
    </div>

    <div class="footer">
        <div class="stamp-area">
        </div>
        <div class="signature-area">
            <span class="label">FIRMA DEL MÉDICO</span><br>
        </div>
    </div>
</div>

<script>
window.onload = function () {
	window.print();
	setTimeout(function () {
		window.close();
	}, 500); 
};
</script>

</body>
</html>