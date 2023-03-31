<?php $days = array("Mie<br/>29.03", "Jue<br/>30.03", "Vie<br/>31.03", "Sab<br/>01.04", "Dom<br/>02.04", "Lun<br/>03.04", "Mar<br/>04.04");
$mins = array("00", "15", "30", "45"); ?>
<div class="text-right"><i class="fas fa-square text-success"></i> <?= $this->lang->line('txt_busy_hours') ?></div>
<table class="table table-sm w-100 mb-0 text-center">
	<thead>
		<tr>
			<th></th>
			<?php foreach($days as $d){ ?>
			<th style="width: 90px;"><strong><?= $d ?></strong></th>
			<?php } ?>
		</tr>
	</thead>
	<tbody>
		<?php for($i = 9; $i <= 18; $i++){ if ($i < 12) $a = "am"; else $a = "pm"; if ($i > 12) $h = $i-12; else $h = $i; ?>
		<?php foreach($mins as $m){ ?>
		<tr>
			<?php if (!strcmp("00", $m)){ ?><td class="text-left" rowspan="4"><?= $h." ".$a ?></td><?php } ?>
			<?php foreach($days as $d){ ?><td></td><?php } ?>
		</tr>
		<?php } ?>
		
		<?php } ?>
	
	
	</tbody>
</table>