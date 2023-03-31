<?php $days = array("Mie, 29.03", "Jue, 30.03", "Vie, 31.03", "Sab, 01.04", "Dom, 02.04", "Lun, 03.04", "Mar, 04.04");
$mins = array("00, 15, 30, 45"); ?>
<div class="text-right"><i class="fas fa-square text-success"></i> <?= $this->lang->line('txt_busy_hours') ?></div>
<table class="table table-sm w-100 mb-0 text-center">
	<thead>
		<tr>
			<th></th>
			<?php foreach($days as $d){ ?>
			<th style="width: 80px;"><strong><?= $d ?></strong></th>
			<?php } ?>
		</tr>
	</thead>
	<tbody>
		<?php for($i = 9; $i <= 18; $i++){ 
		if ($i < 12) $a = "am"; else $a = "pm";
		if ($i > 12) $h = $i-12; else $h = $i; ?>
		<?php foreach($mins as $m){ ?>
		<tr>
			<td class="text-left"><?= $h." ".$a ?></td>
			<?php foreach($days as $d){ ?>
			<td></td>
			<?php } ?>
		</tr>
		<?php }} ?>
	</tbody>
</table>
