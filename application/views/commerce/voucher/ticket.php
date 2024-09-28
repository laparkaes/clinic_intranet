<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="<?= base_url() ?>resorces/css/style.css">
	<link rel="stylesheet" href="<?= base_url() ?>resorces/css/setting.css">
	<style>
	html, body{margin: 5px; font-size: 10px; font-family: 'poppins', sans-serif; line-height: 1.5;}
	table{width: 100%; padding: 0; margin: 0; border: 0; border-collapse: collapse;}
	.mt{margin-top: 20px;}
	.text-center{text-align: center;}
	</style>
</head>
<body>
	<table>
		<tr>
			<td style="text-align: center;">
				<div style="margin-bottom: 3px;"><strong><?= $company->name ?></strong></div>
				<div><?= $company->address ?></div>
				<div><?= $company->urbanization ?></div>
				<div><?= $company->district." - ".$company->province." - ".$company->department ?></div>
			</td>
			<!--
			<td style="text-align: right; vertical-align: top;">
				<img src="data:image/png;base64,<?= $logo ?>" style="max-width: 80px;"/>
			</td>
			-->
		</tr>
	</table>
	<div class="text-center mt">
		<div><strong><?= $title ?></strong></div>
	</div>
	<table class="mt" style="border-top: 1px dashed black; border-bottom: 1px dashed black;">
		<tr><td style="width: 80px;"><?= $this->lang->line("client") ?></td><td>: <?= $client->name ?></td></tr>
		<?php if ($client->doc_type_id){ ?>
		<tr><td><?= $client->doc_type->short ?></td><td>: <?= $client->doc_number ?></td></tr>
		<?php } ?>
		<tr><td><?= $this->lang->line("date") ?></td><td>: <?= $sale->registed_at //date('Y-m-d H:i:s', time()) ?></td></tr>
		<tr><td><?= $this->lang->line("payment_method") ?></td><td>: <?= strtoupper($payments[0]->payment_method) ?></td></tr>
	</table>
	<div class="mt" style="border-bottom: 1px solid black;">
		<table>
			<tr>
				<td><?= $this->lang->line('label_description') ?><br/><?= $this->lang->line('label_qty_unit_price') ?></td>
				<td style="text-align: right; vertical-align: bottom;"><?= $this->lang->line('label_total') ?></td>
			</tr>
		</table>
		<table style="border-top: 1px solid black;">
			<?php foreach($products as $item){ $price = $item->price - $item->discount; ?>
			<tr>
				<td><?= $item->description ?><br/><?= number_format($item->qty, 2) ?> x <?= number_format($price, 2) ?></td>
				<td style="text-align: right; vertical-align: bottom;"><?= number_format($item->qty * $price, 2) ?></td>
			</tr>
			<?php } ?>
		</table>
	</div>
	<table style="margin-top: 10px; text-align: right;">
		<tr>
			<td><?= $this->lang->line('label_total') ?></td>
			<td><?= $currency->description." ".number_format($sale->total, 2) ?></td>
		</tr>
<!-- 		
		<?php foreach($payments as $item){ ?>
		<tr>
			<td><?= $item->registed_at ?></td>
			<td><?= "- ".$currency->description." ".number_format($item->received - $item->change, 2) ?></td>
		</tr>
		<?php } ?>
		 -->
	</table>
	<div class="mt text-center"><strong>Saldo por pagar: <?= $currency->description." ".number_format($sale->balance, 2) ?></strong></div>
	<div class="mt text-center">NO VÁLIDO COMO COMPROBANTE CONTABLE</div>
</body>
</html>

