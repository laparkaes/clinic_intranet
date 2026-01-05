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
            font-size: 9pt;
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
            width: 25%;
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

    <table class="stats-table">
        <tr>
            <td class="card">
                <h3>Ingresos Totales</h3>
                <div class="value">$ 124,500</div>
            </td>
            <td class="card">
                <h3>Consultas</h3>
                <div class="value">$ 124,500</div>
				<div class="trend neutral">3 Nuevos Prospectos</div>
            </td>
            <td class="card">
                <h3>Terapias</h3>
                <div class="value">$ 124,500</div>
                <div class="trend neutral">3 Nuevos Prospectos</div>
            </td>
            <td class="card">
                <h3>Ventas</h3>
                <div class="value">$ 2,964</div>
				<div class="trend neutral">3 Nuevos Prospectos</div>
            </td>
        </tr>
    </table>
	
	<div class="d-none">
	<?php
	//foreach($sales as $item){ print_r($item); echo "<br/><br/>"; }
	?>
	</div>

    <div class="report-section">
        <h2>Detalle de Transacciones Recientes</h2>
        <table class="main-data">
            <thead>
                <tr>
                    <th>Fecha y Hora</th>
                    <th>Cliente</th>
					<th>Categoria</th>
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