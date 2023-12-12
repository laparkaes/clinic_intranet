<div class="pagetitle">
	<h1><?= $surgery->specialty ?></h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= $this->lang->line('w_home') ?></a></li>
			<li class="breadcrumb-item"><a href="<?= base_url() ?>doctor"><?= $this->lang->line('surgeries') ?></a></li>
			<li class="breadcrumb-item active"><?= $this->lang->line('txt_detail') ?></li>
		</ol>
	</nav>
</div>
<div class="row">
	<div class="col-md-12">
		<?php if ($actions){ ?>
		<div class="btn-group">
			<?php if (in_array("report", $actions)){ ?>
			<a class="btn btn-primary" href="<?= base_url() ?>surgery/report/<?= $surgery->id ?>" target="_blank">
				<?= $this->lang->line('btn_report') ?>
			</a>
			<?php } if (in_array("reschedule", $actions)) $d = ""; else $d = "disabled"; ?>
			<button type="button" class="btn btn-success" id="btn_reschedule" <?= $d ?>>
				<?= $this->lang->line('btn_reschedule') ?>
			</button>
			<?php if (in_array("cancel", $actions)) $d = ""; else $d = "disabled"; ?>
			<button type="button" class="btn btn-danger" id="btn_cancel" <?= $d ?>>
				<?= $this->lang->line('btn_cancel') ?>
			</button>
			<?php } ?>
		</div>		
	</div>
	<div class="col-md-12 pt-3">
		<div class="card" id="sur_info">
			<div class="card-body">
				<h5 class="card-title"><?= $this->lang->line('w_general_information') ?></h5>
				<div class="row g-3">
					<div class="col-md-3">
						<label class="form-label"><?= $this->lang->line('w_status') ?></label>
						<div class="form-control text-<?= $surgery->status->color ?>"><?= $this->lang->line($surgery->status->code) ?></div>
					</div>
					<div class="col-md-4">
						<label class="form-label"><?= $this->lang->line('w_date_hour') ?></label>
						<div class="form-control">
							<?= date("Y-m-d h:i A", strtotime($surgery->schedule_from)) ?> ~ <?= date("h:i A", strtotime($surgery->schedule_to)) ?>
						</div>
					</div>
					<div class="col-md-5">
						<label class="form-label"><?= $this->lang->line('w_detail') ?></label>
						<div class="form-control"><?php if ($surgery->product) echo $surgery->product; else echo "-"; ?></div>
					</div>
					<div class="col-md-3">
						<label class="form-label"><?= $this->lang->line('w_history_number') ?></label>
						<div class="form-control"><?= $patient->doc_number ?></div>
					</div>
					<div class="col-md-4">
						<label class="form-label"><?= $this->lang->line('w_patient') ?></label>
						<div class="form-control"><?= $patient->name ?></div>
					</div>
					<div class="col-md-5">
						<label class="form-label"><?= $this->lang->line('w_doctor') ?></label>
						<div class="form-control"><?= $doctor->name ?></div>
					</div>
				</div>
			</div>
		</div>
		<div class="card d-none" id="sur_reschedule">
			<div class="card-body">
				<h5 class="card-title"><?= $this->lang->line('w_reschedule_surgery') ?></h5>
				<div class="row">
					<div class="col-md-6">
						<form class="row g-3" id="form_reschedule">
							<input type="hidden" name="id" value="<?= $surgery->id ?>" readonly>
							<input type="hidden" name="doctor_id" id="rs_doctor" value="<?= $doctor->id ?>">
							<div class="col-md-12">
								<strong><?= $this->lang->line('w_attention') ?></strong>
							</div>
							<div class="col-md-12">
								<label class="form-label">
									<span><?= $this->lang->line('w_doctor') ?></span>
									<i class="bi bi-clock ms-2" id="ic_doctor_schedule_w" data-bs-toggle="modal" data-bs-target="#md_weekly_doctor_agenda"></i>
								</label>
								<div class="form-control"><?= $doctor->name ?></div>
							</div>
							<div class="col-md-12">
								<label class="form-label"><?= $this->lang->line('w_patient') ?></label>
								<div class="form-control"><?= $patient->name ?></div>
							</div>
							<div class="col-md-4">
								<label class="form-label"><?= $this->lang->line('w_date') ?></label>
								<input type="text" class="form-control date_picker doc_schedule schedule" id="rs_date" name="date" value="<?= date('Y-m-d', strtotime($surgery->schedule_from)) ?>">
								<div class="sys_msg" id="rs_date_msg"></div>
							</div>
							<div class="col-md-8">
								<label class="form-label"><?= $this->lang->line('w_time') ?></label>
								<?php $time = explode(":", date('H:i', strtotime($surgery->schedule_from)));
								$hour = intval($time[0]); $min = intval($time[1]); ?>
								<div class="input-group">
									<select class="form-select schedule" id="rs_hour" name="hour">
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
									<span class="input-group-text">:</span>
									<select class="form-select schedule" id="rs_min" name="min">
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
							<div class="col-md-8">
								<label class="form-label">
									<span><?= $this->lang->line('w_room') ?></span>
									<i class="bi bi-alarm ms-2" id="ic_room_availability_w" data-bs-toggle="modal" data-bs-target="#md_weekly_room_availability"></i>
								</label>
								<select class="form-select" id="rs_room_id" name="room_id">
									<option value="">--</option>
									<?php foreach($rooms as $r){ if ($r->id == $surgery->room_id) $selected = "selected"; else $selected = ""; ?>
									<option value="<?= $r->id ?>" <?= $selected ?>><?= $r->name ?></option>
									<?php } ?>
								</select>
								<div class="sys_msg" id="rs_room_msg"></div>
							</div>
							<div class="col-md-4">
								<label class="form-label"><?= $this->lang->line('w_duration') ?></label>
								<select class="form-select" name="duration">
									<option value="">--</option>
									<?php foreach($duration_ops as $op){ 
									if ($op["value"] == $surgery->duration) $selected = "selected"; else $selected = ""; ?>
									<option value="<?= $op["value"] ?>" <?= $selected ?>><?= $op["txt"] ?></option>
									<?php } ?>
								</select>
								<div class="sys_msg" id="rs_duration_msg"></div>
							</div>
							<div class="col-md-12 pt-3">
								<button type="sumit" class="btn btn-primary"><?= $this->lang->line('btn_reschedule') ?></button>
								<button type="button" class="btn btn-secondary" id="btn_reschedule_cancel"><?= $this->lang->line('btn_back') ?></button>
							</div>
						</form>
					</div>
					<div class="col-md-6">
						<div class="row g-3">
							<div class="col-md-12"><strong><?= $this->lang->line('w_doctor_agenda') ?></strong></div>
							<div class="col-md-12"><div id="rp_schedule"></div></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?php if ($surgery->remark){ ?>
		<div class="card">
			<div class="card-body">
				<h5 class="card-title"><?= $this->lang->line('w_remark') ?></h5>
				<textarea class="form-control bw" rows="3" readonly><?= $surgery->remark ?></textarea>
			</div>
		</div>
		<?php } ?>
		
		<div class="card">
			<div class="card-body">
				<h5 class="card-title"><?= $this->lang->line('w_clinical_histories') ?></h5>
				<?php if ($histories){ ?>
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<th><?= $this->lang->line('w_schedule') ?></th>
								<th><?= $this->lang->line('w_type') ?></th>
								<th><?= $this->lang->line('w_specialty') ?></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($histories as $item){ ?>
							<tr>
								<td><?= date("Y-m-d h:i a", strtotime($item->schedule_from)) ?></td>
								<td><?= $item->type ?></td>
								<td><?= $item->specialty ?></td>
								<td class="text-end">
									<?php if ($surgery->id != $item->id){ ?>
									<a href="<?= base_url().$item->link_to."/detail/".$item->id ?>" target="_blank">
										<i class="bi bi-search"></i>
									</a>
									<?php }else{ ?>
										<span class="text-success">Actual</span>
									<?php } ?>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				<?php }else{ ?>
				<div class="text-muted text-center"><?= $this->lang->line('w_no_records') ?></div>
				<?php } ?>
			</div>
		</div>
		<div class="card">
			<div class="card-body">
				<h5 class="card-title"><?= $this->lang->line('w_files') ?></h5>
				<?php if ($patient_files){ ?>
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<th><?= $this->lang->line('w_date') ?></th>
								<th><?= $this->lang->line('w_title') ?></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php $file_path = "/archivos/pacientes/".str_replace(" ", "_", $patient->name)."_".$patient->doc_number."/";
							foreach($patient_files as $item){ ?>
							<tr>
								<td><?= date("Y-m-d<\b\\r>h:i:s", strtotime($item->registed_at)) ?></td>
								<td><?= $item->title ?></td>
								<td>
									<a href="<?= $file_path.$item->filename ?>" target="_blank">
										<i class="fas fa-search"></i>
									</a>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				<?php }else{ ?>
				<div class="text-muted text-center"><?= $this->lang->line('w_no_records') ?></div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<?php if ($surgery->status->code === "reserved"){ ?>
	<div class="col-md-12">
		<h3 class="text-center text-danger"><?= $this->lang->line('t_no_confirmed') ?></h3>
	</div>
	<?php }else{ ?>
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<h5 class="card-title"><?= $this->lang->line('w_result') ?></h5>
				<form class="row g-3" id="form_result">
					<input type="hidden" name="id" value="<?= $surgery->id ?>">
					<div class="col-md-12">
						<?php if ($surgery->is_editable) $rd = ""; else $rd = "readonly"; ?>
						<textarea class="form-control bw" rows="3" name="result" <?= $rd ?>><?= $surgery->result ?></textarea>
					</div>
					<div class="col-md-12 pt-3">
						<button type="submit" class="btn btn-primary">
							<?= $this->lang->line('btn_finish_surgery') ?>
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<?php } // end else ?>
</div>


<div class="modal fade" id="md_weekly_doctor_agenda" tabindex="-1">
	<div class="modal-dialog modal-fullscreen">
		<div class="modal-content">
			<div class="modal-body">
				<div id="bl_weekly_schedule"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= $this->lang->line('btn_close') ?></button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="md_weekly_room_availability" tabindex="-1">
	<div class="modal-dialog modal-fullscreen">
		<div class="modal-content">
			<div class="modal-body">
				<div id="bl_room_availability"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-bs-dismiss="modal"><?= $this->lang->line('btn_close') ?></button>
			</div>
		</div>
	</div>
</div>
<input type="hidden" id="surgery_id" value="<?= $surgery->id ?>">