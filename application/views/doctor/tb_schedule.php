<?php $mins = array("00", "15", "30", "45"); ?>
<div class="text-right text-danger"><?= $msg ?></div>
<div class="text-right"><i class="fas fa-square text-success"></i> <?= $this->lang->line('txt_busy') ?></div>
<table class="table table-sm w-100 mb-0 text-center">
	<thead>
		<tr>
			<th></th>
			<?php foreach($mins as $m){ ?>
			<th style="width: 80px;"><strong><?= $m ?></strong></th>
			<?php } ?>
		</tr>
	</thead>
	<tbody>
		<?php for($i = 9; $i <= 18; $i++){ 
		if ($i < 12) $a = "am"; else $a = "pm";
		if ($i > 12) $h = $i-12; else $h = $i; ?>
		<tr>
			<td class="text-left"><?= $h." ".$a ?></td>
			<?php foreach($mins as $m){ if (in_array($i.$m, $cells)) $bg = "bg-success"; else $bg = ""; ?>
			<td id="<?= $i.$m ?>" class="<?= $bg ?>"></td>
			<?php } ?>
		</tr>
		<?php } ?>
	</tbody>
</table>
