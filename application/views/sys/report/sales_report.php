<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<style>
	html, body{padding: 0; font-size: 12px; font-family: 'poppins', sans-serif; line-height: 1.5; color: black;}
	
	table {
		font-size: 12px;
        width: 100%;
        border-collapse: collapse; /* 테두리 사이의 간격을 없애 실선 하나로 합침 */
    }
    
    table, td {
        border: 1px solid black; /* 실선(solid) 추가 */
    }

    td {
        padding: 2px; /* 셀 안쪽 여백 (선택 사항) */
    }
	</style>
</head>
<body>
<strong>Reporte de Venta</strong><br/>
Fecha: <?= $from ?> ~ <?= $to ?>
<br/><br/>
<strong>Total</strong>
<br/>
<table style="width: 300px;">
	<tr>
		<td>Monto Total</td><td>S/ <?= number_format($total[0], 2) ?></td>
	</tr>
	<tr>
		<td>Venta</td><td>S/ <?= number_format($total[1], 2) ?></td>
	</tr>
	<tr>
		<td>IGV</td><td>S/ <?= number_format($total[2], 2) ?></td>
	</tr>
</table>
<br/>
<strong>Resumen</strong>
<br/>
<table>
	<tr>
		<td>Codigo</td>
		<td>Item</td>
		<td>Moneda</td>
		<td>P/U</td>
		<td>Descuento</td>
		<td>Cantidad</td>
		<td>Total</td>
		<td>Op. gravada</td>
		<td>IGV</td>
	</tr>
	<?php foreach($summary as $item){ ?>
	<tr>
		<?php for($i = 0; $i < 9; $i++){ ?>
		<td><?= $item[$i] ?></td>
		<?php } ?>
	</tr>
	<?php } ?>
</table>
<br/>
<strong>Detalle</strong>
<br/>
<table>
	<?php foreach($rows as $item){ ?>
	<tr>
		<?php for($i = 0; $i < 13; $i++){ ?>
		<td><?= $item[$i] ?></td>
		<?php } ?>
	</tr>
	<?php } ?>
</table>

</body>
</html>