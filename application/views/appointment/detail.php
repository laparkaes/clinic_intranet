<div class="col-xl-12">
	<div class="card">
		<div class="card-header pb-0 border-0">
			<h4 class="mb-0"><?= $doctor->data->specialty ?></h4>
			<div class="text-right">
				<?php if (in_array("reschedule", $actions)){ ?>
				<button type="button" class="btn tp-btn btn-info" id="btn_reschedule" value="<?= $appointment->id ?>" data-toggle="modal" data-target="#reschedule_appointment">
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
			<div class="form-row">
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
<div class="modal fade" id="reschedule_appointment" tabindex="-1" role="dialog" aria-labelledby="reschedule_appointmentLabel" aria-hidden="true">
	<div class="modal-dialog text-left" role="document">
		<div class="modal-content">
			<div class="modal-header border-0 pb-0">
				<h5 class="modal-title" id="reschedule_appointmentLabel"><?= $this->lang->line('title_reschedule_appointment') ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="#" id="reschedule_form">
				<div class="modal-body">
					<input type="hidden" name="id" value="<?= $appointment->id ?>" readonly>
					<input type="hidden" id="ra_doctor" value="<?= $doctor->id ?>">
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
						<div class="form-group col-md-12">
							<h5 class="mt-3"><?= $this->lang->line('title_doctor_agenda') ?></h5>
							<table class="table table-sm w-100 mb-0">
								<thead>
									<tr>
										<th class="w-50 pl-0"><strong><?= $this->lang->line('th_type') ?></strong></th>
										<th class="text-center"><strong><?= $this->lang->line('th_start') ?></strong></th>
										<th></th>
										<th class="text-center pr-0"><strong><?= $this->lang->line('th_end') ?></strong></th>
									</tr>
								</thead>
								<tbody id="rp_schedule"></tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="modal-footer border-0 pt-0">
					<div class="sys_msg" id="ra_result_msg"></div>
					<button type="button" class="btn tp-btn btn-secondary" data-dismiss="modal"><?= $this->lang->line('btn_close') ?></button>
					<button type="sumit" class="btn btn-primary"><?= $this->lang->line('btn_reschedule') ?></button>
				</div>
			</form>
		</div>
	</div>
</div>