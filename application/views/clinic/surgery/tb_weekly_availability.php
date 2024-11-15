<?php if ($msg){ ?>
<div class="text-danger"><?= $msg ?></div>
<?php }else{ $mins = array("00", "15", "30", "45"); ?>
<div class="row text-center">
	<div class="col-md-12 mb-3">
		<strong><?= $room->name ?></strong>
	</div>
	<div class="col-md-12">
		<div class="btn-group">
			<button type="button" class="btn btn-primary btn_room_schedule_w" value="<?= $prev ?>">
				<i class="bi bi-chevron-left"></i>
			</button>
			<button type="button" class="btn btn-primary btn_room_schedule_w" value="<?= date("Y-m-d") ?>">
				<?= $this->lang->line('txt_today') ?>
			</button>
			<button type="button" class="btn btn-primary btn_room_schedule_w" value="<?= $next ?>">
				<i class="bi bi-chevron-right"></i>
			</button>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<table class="table">
			<thead>
				<tr class="sticky-top bg-white" style="top: -17px;">
					<th class="align-middle" style="width: 120px;">
						<i class="bi bi-square-fill text-success"></i> <?= $this->lang->line('txt_busy') ?>
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
<?php } ?>