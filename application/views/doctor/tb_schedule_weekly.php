<?php $mins = array("00", "15", "30", "45"); ?>
<div class="row">
	<div class="col-md-12 text-center">
		<div class="btn-group">
			<button type="button" class="btn btn-primary btn_doctor_schedule_w" value="<?= $prev ?>">
				<i class="fas fa-chevron-double-left"></i>
			</button>
			<button type="button" class="btn btn-primary btn_doctor_schedule_w" value="<?= date("Y-m-d") ?>">
				<?= $this->lang->line('txt_today') ?>
			</button>
			<button type="button" class="btn btn-primary btn_doctor_schedule_w" value="<?= $next ?>">
				<i class="fas fa-chevron-double-right"></i>
			</button>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="text-danger"><?= $msg ?></div>
		<table class="table table-sm w-100 mb-0 text-center">
			<thead>
				<tr class="sticky-top bg-white">
					<th class="align-middle">
						<i class="fas fa-square text-success"></i> <?= $this->lang->line('txt_busy') ?>
					</th>
					<?php foreach($dates as $i => $d){ ?>
					<th style="width: 90px;"><strong><?= $d["hd"] ?></strong></th>
					<?php } ?>
				</tr>
			</thead>
			<tbody>
				<?php
				for($i = 9; $i <= 18; $i++){
					if ($i % 2) $row_bg = "bg-light"; else $row_bg = "";
					if ($i < 12) $a = "am"; else $a = "pm";
					if ($i > 12) $h = $i-12; else $h = $i;
					$h = str_pad($h, 2, '0', STR_PAD_LEFT);
					foreach($mins as $m){ ?>
				<tr class="<?= $row_bg ?>">
					<?php if (!strcmp("00", $m)){ ?>
						<td class="text-left" rowspan="4"><?= $h." ".$a ?></td>
					<?php } foreach($dates as $d){ 
					$cell_num = $d["num"].str_pad($i, 2, '0', STR_PAD_LEFT).$m;
					if (in_array($cell_num, $cells)) $bg = "bg-success"; else $bg = ""; ?>
						<td class="<?= $bg ?>"></td>
					<?php } ?>
				</tr>
				<?php }} ?>
			</tbody>
		</table>
	</div>
</div>