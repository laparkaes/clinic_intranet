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
	<?php $items = $invoice->getDetails(); ?>
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
		<div><strong><?= $this->lang->line('label_ruc') ?>: <?= $company->ruc ?></strong></div>
		<div><strong><?= strtoupper($voucher->type)." ".$this->lang->line("of_electronic_sale_u") ?></strong></div>
		<div><strong><?= $title ?></strong></div>
	</div>
	<table class="mt" style="border-top: 1px dashed black; border-bottom: 1px dashed black;">
		<tr><td style="width: 80px;"><?= $this->lang->line("client") ?></td><td>: <?= $client->name ?></td></tr>
		<?php if ($client->doc_type->sunat_code){ ?>
		<tr><td><?= $client->doc_type->short ?></td><td>: <?= $client->doc_number ?></td></tr>
		<?php } ?>
		<tr><td><?= $this->lang->line("date") ?></td><td>: <?= $invoice->getFechaEmision()->format('Y-m-d H:i:s') ?></td></tr>
	</table>
	<div class="mt" style="border-bottom: 1px solid black;">
		<table>
			<tr>
				<td><?= $this->lang->line('label_description') ?><br/><?= $this->lang->line('label_qty_unit_price') ?></td>
				<td style="text-align: right; vertical-align: bottom;"><?= $this->lang->line('label_total') ?></td>
			</tr>
		</table>
		<table style="border-top: 1px solid black;">
			<?php foreach($items as $item){ ?>
			<tr>
				<td><?= $item->getDescripcion() ?><br/><?= number_format($item->getCantidad(), 2) ?> x <?= number_format($item->getMtoPrecioUnitario(), 2) ?></td>
				<td style="text-align: right; vertical-align: bottom;"><?= number_format($item->getCantidad() * $item->getMtoPrecioUnitario(), 2) ?></td>
			</tr>
			<?php } ?>
		</table>
	</div>
	<table style="margin-top: 10px; text-align: right;">
		<tr>
			<td><?= $this->lang->line('label_taxed_operation') ?></td>
			<td><?= $voucher->currency." ".number_format($invoice->getMtoOperGravadas(), 2) ?></td>
		</tr>
		<tr>
			<td><?= $this->lang->line('label_vat_per') ?></td>
			<td><?= $voucher->currency." ".number_format($invoice->getTotalImpuestos(), 2) ?></td>
		</tr>
		<tr>
			<td><?= $this->lang->line('label_total_amount') ?></td>
			<td><?= $voucher->currency." ".number_format($invoice->getMtoImpVenta(), 2) ?></td>
		</tr>
		<tr>
			<td style="padding-top: 10px;"><?= $voucher->payment_method ?></td>
			<td style="padding-top: 10px;"><?= $voucher->currency." ".number_format($voucher->received, 2) ?></td>
		</tr>
		<tr>
			<td><?= $this->lang->line('label_change') ?></td>
			<td><?= $voucher->currency." ".number_format($voucher->change, 2) ?></td>
		</tr>
	</table>
	<div style="margin-top: 10px;"><?= $this->lang->line('label_are_u')." ".$invoice->getLegends()[0]->getValue(); ?></div>
	<div class="mt" style="border-top: 1px dashed black; border-bottom: 1px dashed black;">
		<div><strong><?= $this->lang->line('label_remarks') ?></strong></div>
		<table>
			<tr>
				<td style="width: 80px;"><?= $this->lang->line('label_way_to_pay') ?></td>
				<td>: <?= $invoice->getFormaPago()->getTipo() ?></td>
			</tr>
		</table>
	</div>
	<div class="mt text-center">
		<div><img src="data:image/png;base64,<?= $qr ?>" style="max-width: 100px;"/></div>
		<div><?= $this->lang->line("resolution_num").": ".$company->sunat_resolution ?></div>
		<div><?= $this->lang->line("hash_code").": ".$voucher->hash ?></div>
	</div>
</body>
</html>

