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
					<span class="d-none msg"><?= $this->lang->line('warning_aca') ?></span>
					<?= $this->lang->line('btn_cancel') ?>
				</button>
				<?php } if (in_array("report", $actions)){ ?>
				<a href="<?= base_url() ?>surgery/report/<?= $surgery->id ?>" target="_blank">
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
					<input type="text" class="form-control" value="<?= date("Y-m-d", strtotime($surgery->schedule_from)) ?>" readonly>
				</div>
				<div class="form-group col-md-3">
					<label><?= $this->lang->line('lb_hour') ?></label>
					<input type="text" class="form-control" value="<?= date("H:i A", strtotime($surgery->schedule_from)) ?>" readonly>
				</div>
				<div class="form-group col-md-3">
					<label><?= $this->lang->line('lb_status') ?></label>
					<input type="text" class="form-control text-<?= $surgery->status->color ?>" value="<?= $this->lang->line($surgery->status->code) ?>" readonly>
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
<?php if ($surgery->remark){ ?>
<div class="col-xl-12">
	<div class="card">
		<div class="card-header pb-0 border-0">
			<h4 class="mb-0"><?= $this->lang->line('title_remark') ?></h4>
		</div>
		<div class="card-body">
			<textarea class="form-control" rows="3" readonly><?= $surgery->remark ?></textarea>
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
			<table class="table mb-0">
				<thead>
					<tr class="text-left">
						<th style="max-width: 150px;"><?= $this->lang->line('th_date') ?></th>
						<th style="max-width: 100px;"><?= $this->lang->line('th_time') ?></th>
						<th class="text-right"><?= $this->lang->line('th_speciality') ?></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($histories as $item){ ?>
					<tr class="text-left">
						<td><?= date("Y-m-d", strtotime($item->schedule_from)) ?></td>
						<td><?= date("H:i", strtotime($item->schedule_from)) ?></td>
						<td class="text-right"><?= $item->specialty ?></td>
						<td class="text-right">
							<a href="<?= base_url()."appointment/detail/".$item->id ?>" target="_blank">
								<i class="fas fa-search"></i>
							</a>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="col-md-6">
	<div class="card">
		<div class="card-header pb-0 border-0">
			<h4 class="mb-0"><?= $this->lang->line('title_files') ?></h4>
		</div>
		<div class="card-body ap_content_list">
			<table class="table mb-0">
				<thead>
					<tr class="text-left">
						<th style="max-width: 150px;"><?= $this->lang->line('th_date') ?></th>
						<th class="text-right"><?= $this->lang->line('th_title') ?></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php $file_path = base_url()."uploaded/patient_files/".$patient->doc_type_id."_".$patient->doc_number."/";
					foreach($patient_files as $item){ ?>
					<tr class="text-left">
						<td><?= date("Y-m-d", strtotime($item->registed_at)) ?></td>
						<td class="text-right"><?= $item->title ?></td>
						<td class="text-right">
							<a href="<?= $file_path.$item->filename ?>" target="_blank">
								<i class="fas fa-search"></i>
							</a>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php if ($surgery->is_editable){ ?>
<div class="col-xl-12">
	<div class="card">
		<div class="card-body">
			<div class="text-center">
				<button type="button" class="btn btn-primary btn-lg" id="btn_finish" value="<?= $surgery->id ?>">
					<span class="d-none msg"><?= $this->lang->line('warning_sfi') ?></span>
					<?= $this->lang->line('btn_finish_surgery') ?>
				</button>
			</div>
		</div>
	</div>
</div>
<?php } ?>
<div class="d-none">
	<input type="hidden" id="surgery_id" value="<?= $surgery->id ?>">
	<input type="hidden" id="warning_sre" value="<?= $this->lang->line('warning_sre') ?>">
</div>
<div class="modal fade" id="reschedule_surgery" tabindex="-1" role="dialog" aria-labelledby="reschedule_surgeryLabel" aria-hidden="true">
	<div class="modal-dialog text-left" role="document">
		<div class="modal-content">
			<div class="modal-header border-0 pb-0">
				<h5 class="modal-title" id="reschedule_surgeryLabel"><?= $this->lang->line('title_reschedule_surgery') ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="#" id="reschedule_form">
				<div class="modal-body">
					<input type="hidden" name="id" value="<?= $surgery->id ?>" readonly>
					<input type="hidden" id="rs_doctor" value="<?= $doctor->id ?>">
					<div class="form-row">
						<div class="form-group col-md-6">
							<label><?= $this->lang->line('lb_doctor') ?></label>
							<input type="text" class="form-control" value="<?= $doctor->name ?>" readonly>
						</div>
						<div class="form-group col-md-6">
							<label><?= $this->lang->line('lb_patient') ?></label>
							<input type="text" class="form-control" value="<?= $patient->name ?>" readonly>
						</div>
						<div class="form-group col-md-6">
							<label><?= $this->lang->line('lb_date') ?></label>
							<input type="text" class="form-control date_picker doc_schedule schedule" id="rs_date" name="date" value="<?= date('Y-m-d') ?>" readonly>
							<div class="sys_msg" id="rs_date_msg"></div>
						</div>
						<div class="form-group col-md-6">
							<label><?= $this->lang->line('lb_time') ?></label>
							<div class="d-flex">
								<select class="form-control text-center schedule px-0" id="rs_hour" name="hour">
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
								<select class="form-control text-center schedule px-0" id="rs_min" name="min">
									<option value="" selected>--</option>
									<option value="00">00</option>
									<option value="15">15</option>
									<option value="30">30</option>
									<option value="45">45</option>
								</select>
							</div>
							<div class="sys_msg" id="rs_time_msg"></div>
						</div>
					</div>
					<div class="mt-3" id="doctor_agenda">
						<h5><?= $this->lang->line('title_doctor_agenda') ?></h5>
						<ul class="list-group" id="rp_schedule"></ul>
					</div>
				</div>
				<div class="modal-footer border-0 pt-0">
					<div class="sys_msg" id="rs_result_msg"></div>
					<button type="button" class="btn tp-btn btn-secondary" data-dismiss="modal"><?= $this->lang->line('btn_close') ?></button>
					<button type="sumit" class="btn btn-primary"><?= $this->lang->line('btn_reschedule') ?></button>
				</div>
			</form>
		</div>
	</div>
</div>