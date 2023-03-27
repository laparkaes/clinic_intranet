<div class="col-xl-12">
	<div class="card">
		<div class="card-header pb-0 border-0">
			<h4 class="mb-0"><?= $doctor->data->specialty ?></h4>
			<div class="text-right">
				<?php if (in_array("reschedule", $actions)){ ?>
				<button type="button" class="btn tp-btn btn-info" id="btn_reschedule">
					<?= $this->lang->line('btn_reschedule') ?>
				</button>
				<?php } if (in_array("cancel", $actions)){ ?>
				<button type="button" class="btn tp-btn btn-danger" id="btn_cancel" value="<?= $appointment->id ?>">
					<span class="d-none msg"><?= $this->lang->line('warning_aca') ?></span>
					<?= $this->lang->line('btn_cancel') ?>
				</button>
				<?php } if (in_array("report", $actions)){ ?>
				<a href="<?= base_url() ?>appointment/report/<?= $appointment->id ?>" target="_blank">
					<button type="button" class="btn btn-primary">
						<?= $this->lang->line('btn_report') ?>
					</button>
				</a>
				<?php } ?>
			</div>
		</div>
		<div class="card-body">
			<div class="form-row" id="app_info">
				<?php if ($appointment->detail){ ?>
				<div class="form-group col-md-12">
					<label><?= $this->lang->line('lb_detail') ?></label>
					<input type="text" class="form-control" value="<?= $appointment->detail ?>" readonly>
				</div>
				<?php } ?>
				<div class="form-group col-md-3">
					<label><?= $this->lang->line('lb_doctor') ?></label>
					<input type="text" class="form-control" value="<?= $doctor->name ?>" readonly>
				</div>
				<div class="form-group col-md-3">
					<label><?= $this->lang->line('lb_date') ?></label>
					<input type="text" class="form-control" value="<?= date("Y-m-d", strtotime($appointment->schedule_from)) ?>" readonly>
				</div>
				<div class="form-group col-md-3">
					<label><?= $this->lang->line('lb_hour') ?></label>
					<input type="text" class="form-control" value="<?= date("H:i A", strtotime($appointment->schedule_from)) ?>" readonly>
				</div>
				<div class="form-group col-md-3">
					<label><?= $this->lang->line('lb_status') ?></label>
					<input type="text" class="form-control text-<?= $appointment->status->color ?>" value="<?= $this->lang->line($appointment->status->code) ?>" readonly>
				</div>
				<div class="form-group col-md-3">
					<label><?= $this->lang->line('lb_patient') ?></label>
					<input type="text" class="form-control" value="<?= $patient->name ?>" readonly>
				</div>
				<div class="form-group col-md-3">
					<label><?= $this->lang->line('lb_history_number') ?></label>
					<input type="text" class="form-control" value="<?= $patient->doc_number ?>" readonly>
				</div>
				<div class="form-group col-md-2">
					<label><?= $this->lang->line('lb_sex') ?></label>
					<input type="text" class="form-control" value="<?= $patient->sex ?>" readonly>
				</div>
				<div class="form-group col-md-2">
					<label><?= $this->lang->line('lb_age') ?></label>
					<input type="text" class="form-control" value="<?= $patient->age ?>" readonly>
				</div>
				<div class="form-group col-md-2">
					<label><?= $this->lang->line('lb_blood_type') ?></label>
					<input type="text" class="form-control" value="<?= $patient->blood_type ?>" readonly>
				</div>
			</div>
			<div class="row d-none" id="app_reschedule">
				<div class="col-md-6">
					<h5 class="mb-3"><?= $this->lang->line('title_reschedule_appointment') ?></h5>
					<form action="#" id="reschedule_form">
						<input type="hidden" name="id" value="<?= $appointment->id ?>" readonly>
						<input type="hidden" id="ra_doctor" value="<?= $doctor->id ?>" readonly>
						<div class="form-row">
							<div class="form-group col-md-12">
								<label><?= $this->lang->line('lb_doctor') ?></label>
								<input type="text" class="form-control bg-light" value="<?= $doctor->name ?>" readonly>
							</div>
							<div class="form-group col-md-12">
								<label><?= $this->lang->line('lb_patient') ?></label>
								<input type="text" class="form-control bg-light" value="<?= $patient->name ?>" readonly>
							</div>
							<div class="form-group col-md-6">
								<label><?= $this->lang->line('lb_date') ?></label>
								<input type="text" class="form-control date_picker doc_schedule schedule" id="ra_date" name="date" value="<?= date('Y-m-d') ?>" readonly>
								<div class="sys_msg" id="ra_date_msg"></div>
							</div>
							<div class="form-group col-md-6">
								<label><?= $this->lang->line('lb_time') ?></label>
								<div class="d-flex">
									<select class="form-control text-center schedule px-0" id="ra_hour" name="hour">
										<option value="" selected>--</option>
										<?php for($i = 9; $i < 18; $i++){ if ($i < 12) $pre = "AM"; else $pre = "PM"; ?>
										<option value="<?= $i ?>">
											<?php 
											switch(true){
												case $i < 12: echo $i." AM"; break;
												case $i == 12: echo $i." M"; break;
												case $i > 12: echo ($i - 12)." PM"; break;
											}
											?>
										</option>
										<?php } ?>
									</select>
									<span class="input-group-text bg-white px-2" style="min-width: 0;">:</span>
									<select class="form-control text-center schedule px-0" id="ra_min" name="min">
										<option value="" selected>--</option>
										<option value="00">00</option>
										<option value="15">15</option>
										<option value="30">30</option>
										<option value="45">45</option>
									</select>
								</div>
								<div class="sys_msg" id="ra_time_msg"></div>
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
<?php if ($appointment->remark){ ?>
<div class="col-xl-12">
	<div class="card">
		<div class="card-header pb-0 border-0">
			<h4 class="mb-0"><?= $this->lang->line('title_remark') ?></h4>
		</div>
		<div class="card-body">
			<textarea class="form-control" rows="3" readonly><?= $appointment->remark ?></textarea>
		</div>
	</div>
</div>
<?php } $this->load->view("appointment/detail_".$this->session->userdata("role")->name); ?>
<div class="d-none">
	<input type="hidden" id="appointment_id" value="<?= $appointment->id ?>">
	<input type="hidden" id="bmi_class_under_weight" value="<?= $this->lang->line('txt_bmi_class_under_weight') ?>">
	<input type="hidden" id="bmi_class_normal" value="<?= $this->lang->line('txt_bmi_class_normal') ?>">
	<input type="hidden" id="bmi_class_overweight" value="<?= $this->lang->line('txt_bmi_class_overweight') ?>">
	<input type="hidden" id="bmi_class_obesity_1" value="<?= $this->lang->line('txt_bmi_class_obesity_1') ?>">
	<input type="hidden" id="bmi_class_obesity_2" value="<?= $this->lang->line('txt_bmi_class_obesity_2') ?>">
	<input type="hidden" id="bmi_class_obesity_3" value="<?= $this->lang->line('txt_bmi_class_obesity_3') ?>">
	<input type="hidden" id="warning_are" value="<?= $this->lang->line('warning_are') ?>">
</div>