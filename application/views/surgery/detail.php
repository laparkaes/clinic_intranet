<div class="col-xl-12">
	<div class="card">
		<div class="card-header pb-0 border-0">
			<h4 class="mb-0"><?= $doctor->data->specialty ?></h4>
			
			<div class="text-right">
				<?php if (in_array("reschedule", $actions)){ ?>
				<button type="button" class="btn tp-btn btn-info" id="btn_reschedule" value="<?= $surgery->id ?>" data-toggle="modal" data-target="#reschedule_surgery">
					<?= $this->lang->line('btn_reschedule') ?>
				</button>
				<?php } if (in_array("cancel", $actions)){ ?>
				<button type="button" class="btn tp-btn btn-danger" id="btn_cancel" value="<?= $surgery->id ?>">
					<?= $this->lang->line('btn_cancel') ?>
				</button>
				<?php } if (in_array("report", $actions)){ ?>
				<!--
				<a href="<?= base_url() ?>surgery/report/<?= $surgery->id ?>" target="_blank">
					<button type="button" class="btn btn-primary">
						<?= $this->lang->line('btn_report') ?>
					</button>
				</a>
				-->
				<?php } ?>
			</div>
		</div>
		<div class="card-body">
			<div class="form-row" id="sur_info">
				<?php if ($surgery->detail){ ?>
				<div class="form-group col-md-12">
					<label><?= $this->lang->line('lb_detail') ?></label>
					<input type="text" class="form-control bw" value="<?= $surgery->detail ?>" readonly>
				</div>
				<?php } ?>
				<div class="form-group col-md-3">
					<label><?= $this->lang->line('lb_doctor') ?></label>
					<input type="text" class="form-control bw" value="<?= $doctor->name ?>" readonly>
				</div>
				<div class="form-group col-md-3">
					<label><?= $this->lang->line('lb_date') ?></label>
					<input type="text" class="form-control bw" value="<?= date("Y-m-d", strtotime($surgery->schedule_from)) ?>" readonly>
				</div>
				<div class="form-group col-md-3">
					<label><?= $this->lang->line('lb_hour') ?></label>
					<input type="text" class="form-control bw" value="<?= date("H:i A", strtotime($surgery->schedule_from))." - ".date("H:i A", strtotime($surgery->schedule_to)) ?>" readonly>
				</div>
				<div class="form-group col-md-3">
					<label><?= $this->lang->line('lb_status') ?></label>
					<input type="text" class="form-control bw text-<?= $surgery->status->color ?>" value="<?= $this->lang->line($surgery->status->code) ?>" readonly>
				</div>
				<div class="form-group col-md-3">
					<label><?= $this->lang->line('lb_patient') ?></label>
					<input type="text" class="form-control bw" value="<?= $patient->name ?>" readonly>
				</div>
				<div class="form-group col-md-3">
					<label><?= $this->lang->line('lb_history_number') ?></label>
					<input type="text" class="form-control bw" value="<?= $patient->doc_number ?>" readonly>
				</div>
				<div class="form-group col-md-2">
					<label><?= $this->lang->line('lb_sex') ?></label>
					<input type="text" class="form-control bw" value="<?= $patient->sex ?>" readonly>
				</div>
				<div class="form-group col-md-2">
					<label><?= $this->lang->line('lb_age') ?></label>
					<input type="text" class="form-control bw" value="<?= $patient->age ?>" readonly>
				</div>
				<div class="form-group col-md-2">
					<label><?= $this->lang->line('lb_blood_type') ?></label>
					<input type="text" class="form-control bw" value="<?= $patient->blood_type ?>" readonly>
				</div>
			</div>
			<div class="row d-none" id="sur_reschedule">
				<div class="col-md-6">
					<h5 class="mb-3"><?= $this->lang->line('title_reschedule_surgery') ?></h5>
					<form action="#" id="form_reschedule">
						<input type="hidden" name="id" value="<?= $surgery->id ?>" readonly>
						<input type="hidden" name="doctor_id" id="rs_doctor" value="<?= $doctor->id ?>">
						<div class="form-row">
							<div class="form-group col-md-12">
								<label>
									<span class="mr-1"><?= $this->lang->line('lb_doctor') ?></span>
									<span><i class="far fa-clock" id="ic_doctor_schedule_w" data-toggle="modal" data-target=".md_weekly_doctor_agenda"></i></span>
								</label>
								<input type="text" class="form-control bg-light" value="<?= $doctor->name ?>" readonly>
							</div>
							<div class="form-group col-md-12">
								<label><?= $this->lang->line('lb_patient') ?></label>
								<input type="text" class="form-control bg-light" value="<?= $patient->name ?>" readonly>
							</div>
							<div class="form-group col-md-6">
								<label><?= $this->lang->line('lb_date') ?></label>
								<input type="text" class="form-control date_picker bw doc_schedule schedule" id="rs_date" name="date" value="<?= date('Y-m-d', strtotime($surgery->schedule_from)) ?>" readonly>
								<div class="sys_msg" id="rs_date_msg"></div>
							</div>
							<div class="form-group col-md-6">
								<label><?= $this->lang->line('lb_time') ?></label>
								<?php $time = explode(":", date('H:i', strtotime($surgery->schedule_from)));
								$hour = intval($time[0]); $min = intval($time[1]); ?>
								<div class="d-flex">
									<select class="form-control text-center schedule px-0" id="rs_hour" name="hour">
										<option value="" selected>--</option>
										<?php for($i = 9; $i <= 18; $i++){ 
										if ($hour == $i) $selected = "selected"; else $selected = "";
										if ($i < 12) $pre = "AM"; else $pre = "PM"; ?>
										<option value="<?= $i ?>" <?= $selected ?>>
											<?php switch(true){
												case $i < 12: echo $i." AM"; break;
												case $i == 12: echo $i." M"; break;
												case $i > 12: echo ($i - 12)." PM"; break;
											} ?>
										</option>
										<?php } ?>
									</select>
									<span class="input-group-text bg-white px-2" style="min-width: 0;">:</span>
									<select class="form-control text-center schedule px-0" id="rs_min" name="min">
										<option value="" selected>--</option>
										<?php if ($min == 0) $selected = "selected"; else $selected = ""; ?>
										<option value="00" <?= $selected ?>>00</option>
										<?php if ($min == 15) $selected = "selected"; else $selected = ""; ?>
										<option value="15" <?= $selected ?>>15</option>
										<?php if ($min == 30) $selected = "selected"; else $selected = ""; ?>
										<option value="30" <?= $selected ?>>30</option>
										<?php if ($min == 45) $selected = "selected"; else $selected = ""; ?>
										<option value="45" <?= $selected ?>>45</option>
									</select>
								</div>
								<div class="sys_msg" id="rs_schedule_msg"></div>
							</div>
							<div class="form-group col-md-8">
								<label>
									<span class="mr-1"><?= $this->lang->line('lb_room') ?></span>
									<span><i class="far fa-clock" id="ic_room_availability_w" data-toggle="modal" data-target=".md_weekly_room_availability"></i></span>
								</label>
								<select class="form-control" id="rs_room_id" name="room_id">
									<option value="">--</option>
									<?php foreach($rooms as $r){ if ($r->id == $surgery->room_id) $selected = "selected"; else $selected = ""; ?>
									<option value="<?= $r->id ?>" <?= $selected ?>><?= $r->name ?></option>
									<?php } ?>
								</select>
								<div class="sys_msg" id="rs_room_msg"></div>
							</div>
							<div class="form-group col-md-4">
								<label><?= $this->lang->line('lb_duration') ?></label>
								<select class="form-control" name="duration">
									<option value="">--</option>
									<?php foreach($duration_ops as $op){ 
									if ($op["value"] == $surgery->duration) $selected = "selected"; else $selected = ""; ?>
									<option value="<?= $op["value"] ?>" <?= $selected ?>><?= $op["txt"] ?></option>
									<?php } ?>
								</select>
								<div class="sys_msg" id="rs_duration_msg"></div>
							</div>
							<div class="form-group col-md-12 pt-3">
								<button type="sumit" class="btn btn-primary"><?= $this->lang->line('btn_reschedule') ?></button>
								<button type="button" class="btn tp-btn btn-danger" id="btn_reschedule_cancel"><?= $this->lang->line('btn_cancel') ?></button>
							</div>
						</div>
					</form>
				</div>
				<div class="col-md-6">
					<h5 class="mb-3"><?= $this->lang->line('title_doctor_agenda') ?></h5>
					<div id="rp_schedule"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php if ($surgery->remark){ ?>
<div class="col-xl-12">
	<div class="card">
		<div class="card-header pb-0 border-0">
			<h4 class="mb-0"><?= $this->lang->line('title_remark') ?></h4>
		</div>
		<div class="card-body">
			<textarea class="form-control bw" rows="3" readonly><?= $surgery->remark ?></textarea>
		</div>
	</div>
</div>
<?php } ?>
<div class="col-md-6">
	<div class="card">
		<div class="card-header pb-0 border-0">
			<h4 class="mb-0"><?= $this->lang->line('title_clinical_histories') ?></h4>
		</div>
		<div class="card-body ap_content_list">
			<?php if ($histories){ ?>
			<table class="table mb-0">
				<thead>
					<tr>
						<th class="pt-0 pl-0"><?= $this->lang->line('th_schedule') ?></th>
						<th class="pt-0"><?= $this->lang->line('th_type') ?></th>
						<th class="pt-0"><?= $this->lang->line('th_specialty') ?></th>
						<th class="pt-0 pr-0"></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($histories as $item){ ?>
					<tr class="text-left">
						<td class="pl-0"><?= date("d.m.Y<\b\\r>H:i a", strtotime($item->schedule_from)) ?></td>
						<td><?= $item->type ?></td>
						<td><?= $item->specialty ?></td>
						<td class="text-right pr-0">
							<?php if ($surgery->id != $item->id){ ?>
							<a href="<?= base_url().$item->link_to."/detail/".$item->id ?>" target="_blank">
								<i class="fas fa-search"></i>
							</a>
							<?php }else{ ?>
								<i class="fas fa-check text-success"></i>
							<?php } ?>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<?php }else{ ?>
			<div class="text-muted text-center"><?= $this->lang->line('txt_no_records') ?></div>
			<?php } ?>
		</div>
	</div>
</div>
<div class="col-md-6">
	<div class="card">
		<div class="card-header pb-0 border-0">
			<h4 class="mb-0"><?= $this->lang->line('title_files') ?></h4>
		</div>
		<div class="card-body ap_content_list">
			<?php if ($patient_files){ ?>
			<table class="table mb-0">
				<thead>
					<tr>
						<th class="pt-0 pl-0"><?= $this->lang->line('th_date') ?></th>
						<th class="pt-0"><?= $this->lang->line('th_title') ?></th>
						<th class="pt-0 pr-0"></th>
					</tr>
				</thead>
				<tbody>
					<?php $file_path = base_url()."uploaded/patient_files/".$patient->doc_type_id."_".$patient->doc_number."/";
					foreach($patient_files as $item){ ?>
					<tr>
						<td class="pl-0"><?= date("d.m.Y<\b\\r>H:i:s", strtotime($item->registed_at)) ?></td>
						<td><?= $item->title ?></td>
						<td class="pr-0">
							<a href="<?= $file_path.$item->filename ?>" target="_blank">
								<i class="fas fa-search"></i>
							</a>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<?php }else{ ?>
			<div class="text-muted text-center"><?= $this->lang->line('txt_no_records') ?></div>
			<?php } ?>
		</div>
	</div>
</div>
<div class="col-md-12">
	<div class="card">
		<div class="card-header pb-0 border-0">
			<h4 class="mb-0"><?= $this->lang->line('title_result') ?></h4>
		</div>
		<div class="card-body">
			<form action="#" id="form_result" class="form-row">
				<div class="form-group col-md-12">
					<?php if ($surgery->is_editable) $rd = ""; else $rd = "readonly"; ?>
					<textarea class="form-control bw" rows="3" name="result" <?= $rd ?>><?= $surgery->result ?></textarea>
				</div>
				<?php if ($surgery->is_editable){ ?>
				<input type="hidden" name="id" value="<?= $surgery->id ?>">
				<div class="form-group col-md-12 mb-0 pt-3">
					<button type="submit" class="btn btn-primary">
						<?= $this->lang->line('btn_save_finish_surgery') ?>
					</button>
				</div>
				<?php } ?>
			</form>
		</div>
	</div>
</div>
<div class="modal fade md_weekly_doctor_agenda" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<h5 class="modal-title"><?= $this->lang->line('title_doctor_agenda') ?></h5>
				<button type="button" class="close" data-dismiss="modal"><span>&times;</span>
				</button>
			</div>
			<div class="modal-body" id="bl_weekly_schedule"></div>
		</div>
	</div>
</div>
<div class="modal fade md_weekly_room_availability" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<h5 class="modal-title"><?= $this->lang->line('title_room_availability') ?></h5>
				<button type="button" class="close" data-dismiss="modal"><span>&times;</span>
				</button>
			</div>
			<div class="modal-body" id="bl_room_availability"></div>
		</div>
	</div>
</div>
<div class="d-none">
	<input type="hidden" id="surgery_id" value="<?= $surgery->id ?>">
	<input type="hidden" id="warning_sre" value="<?= $this->lang->line('warning_sre') ?>">
	<input type="hidden" id="warning_sfi" value="<?= $this->lang->line('warning_sfi') ?>">
	<input type="hidden" id="warning_sca" value="<?= $this->lang->line('warning_sca') ?>">
</div>