<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ventas - Clínica Everlyn</title>
    <style>
        /* PDF 최적화를 위해 CSS 변수 대신 고정값 사용 */
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333333;
            margin: 0;
            padding: 10px;
            font-size: 10pt;
        }

        .container {
            width: 100%;
        }

        /* Header: Flex 대신 Table 사용 */
        .header-table {
            width: 100%;
            border-bottom: 3px solid #128959;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .header-table td {
            vertical-align: bottom;
            padding-bottom: 10px;
			font-size: 8px;
        }
        .header-title {
            color: #128959;
            font-size: 22pt;
            font-weight: bold;
            margin: 0;
        }
        .header-date {
            text-align: right;
            font-size: 15pt;
            color: #444;
        }
		
		.trend.up { color: #198754; }
        .trend.neutral { color: #888; }
		.trend.down { color: #dc3545; }

        /* Stats Grid: Grid 대신 Table 사용 (에러 방지 핵심) */
        .stats-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 10px 0;
            margin-bottom: 20px;
        }
        .card {
            background-color: #ffffff;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            border: 1px solid #dee2e6;
            //width: 25%;
        }
        .card h3 {
            margin: 0;
            font-size: 8pt;
            color: #777;
            text-transform: uppercase;
        }
        .card .value {
            font-size: 18pt;
            font-weight: bold;
            margin: 10px 0;
            color: #111;
        }

        /* Sección de Tabla */
        .report-section {
            background-color: #ffffff;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
			margin-bottom: 20px;
        }
        .report-section h2 {
            font-size: 12pt;
            margin-top: 0;
            margin-bottom: 15px;
            border-left: 5px solid #128959;
            padding-left: 10px;
        }

        table.main-data {
            width: 100%;
            border-collapse: collapse;
        }
        table.main-data th {
            background-color: #fcfcfc;
            text-align: left;
            padding: 10px;
            border-bottom: 2px solid #dee2e6;
            color: #555;
            font-size: 8pt;
        }
        table.main-data td {
            padding: 10px;
            border-bottom: 1px solid #f0f0f0;
            font-size: 9pt;
        }

        /* 상태 배지 */
        .badge {
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 8pt;
            font-weight: bold;
        }
        .status-success { background-color: #d4edda; color: #198754; }
        .status-warning { background-color: #fff3cd; color: #856404; }
		.status-danger { background-color: #ffd5d9; color: #dc3545; }
		
		.mb-3 { margin-bottom: 30px; }
		.mb-1 { margin-bottom: 10px; }
		.pt-3 { padding-top: 30px; }
		.w-100 { width: 100%; }
		.w-80 { width: 80%; }
		.w-60 { width: 60%; }
		.w-50 { width: 50%; }
		.w-40 { width: 40%; }
		.v-top {vertical-align: top;}
    </style>
</head>
<body>

<div class="container">
    <table class="header-table">
        <tr>
            <td>
                <div class="header-title">Reporte de Ventas</div>
            </td>
            <td class="header-date">
                <strong><?= $report_date ?></strong>
            </td>
        </tr>
    </table>
	
	<div class="report-section">
		<table class="w-100">
			<tr>
				<td class="w-40 v-top">
					<h2>Resumen</h2>
					<?php foreach($summary_total as $item){ if ($item->total){ ?>
					<div class="card w-80 mb-1">
						<h3>Total en <?= $item->currency ?></h3>
						<div class="value"><?= $item->currency ?> <?= number_format($item->total, 2) ?></div>
						<div class="trend neutral">Venta: <?= $item->currency ?> <?= number_format($item->amount, 2) ?></div>
						<div class="trend neutral">IGV: <?= $item->currency ?> <?= number_format($item->vat, 2) ?></div>
					</div>
					<?php }} ?>
				</td>
				<td class="w-60 v-top">
					<h2>Metodo de Pago</h2>
					<table class="main-data w-90">
						<thead>
							<tr>
								<th>Metodo</th>
								<th>Cantidad</th>
								<th>Venta</th>
								<th>IGV</th>
								<th>Total</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($by_pay as $pay){ ?>
							<tr>
								<td><?= $pay->payment_method ?></td>
								<td><?= number_format($pay->qty) ?></td>
								<td><?= $pay->currency." ".number_format($pay->amount , 2) ?></td>
								<td><?= $pay->currency." ".number_format($pay->vat , 2) ?></td>
								<td><?= $pay->currency." ".number_format($pay->total , 2) ?></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</td>
			</tr>
		</table>
    </div>
	
	<div class="report-section">
		<h2>Categoría de Producto</h2>
		<table class="main-data w-90">
			<thead>
				<tr>
					<th>Categoría</th>
					<th>Cantidad</th>
					<th>Venta</th>
					<th>IGV</th>
					<th>Total</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($by_cat as $cat){ ?>
				<tr>
					<td><?= $cat->category ?></td>
					<td><?= number_format($cat->qty) ?></td>
					<td><?= $cat->currency." ".number_format($cat->amount , 2) ?></td>
					<td><?= $cat->currency." ".number_format($cat->vat , 2) ?></td>
					<td><?= $cat->currency." ".number_format($cat->total , 2) ?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
	
    <div class="report-section">
        <h2>Detalle de Ventas</h2>
        <table class="main-data">
            <thead>
                <tr>
                    <th>Fecha y Hora</th>
                    <th>Cliente</th>
					<th>Categoría</th>
					<th>Producto</th>
					<th>Cantidad</th>
                    <th>Monto</th>
                    <th>Forma de Pago</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
				<?php foreach($sales as $sale){ foreach($sale->products as $product){ ?>
                <tr>
                    <td><div style="width: 70px;"><?= str_replace(" ", "<br/>", $sale->registed_at) ?></div></td>
                    <td><?= $sale->client ?></td>
					<td><?= $product->product->category ?></td>
					<td><?= $product->product->description ?><br/>[<?= $product->product->code ?>]</td>
					<td><?= $product->qty ?></td>
                    <td><?= $sale->currency." ".number_format($product->price * $product->qty , 2) ?></td>
                    <td><?= $sale->payment_method ?></td>
                    <td><span class="badge status-<?= $sale->status->color ?>"><?= $sale->status->translated ?></span></td>
                </tr>
				<?php }} ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>