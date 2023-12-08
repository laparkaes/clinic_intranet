<div class="col-md-12">
	<h4 class="fs-24 font-w600 mb-3"><?= $appointment->specialty ?></h4>
	<?php if ($actions){ ?>
	<div class="btn-group mb-3">
		<?php if (in_array("report", $actions)) $d = ""; else $d = "disabled"; ?>
		<a class="btn btn-primary" href="<?= base_url() ?>appointment/clinical_history/<?= $appointment->id ?>" target="_blank">
			<?= $this->lang->line('w_clinical_history') ?>
		</a>
		<?php if (in_array("reschedule", $actions)) $d = ""; else $d = "disabled"; ?>
		<button type="button" class="btn btn-info" id="btn_reschedule" <?= $d ?>>
			<?= $this->lang->line('btn_reschedule') ?>
		</button>
		<?php if (in_array("cancel", $actions)) $d = ""; else $d = "disabled"; ?>
		<button type="button" class="btn btn-danger" id="btn_cancel" <?= $d ?>>
			<?= $this->lang->line('btn_cancel') ?>
		</button>
		<?php } ?>
	</div>
</div>
<div class="col-md-12">
	<div class="card">
		<div class="card-body">
			<div class="row" id="app_info">
				<div class="col-md-3 col-6 mb-3">
					<h5 class="mb-1"><?= $this->lang->line('w_status') ?></h5>
					<div class="text-<?= $appointment->status->color ?>"><?= $this->lang->line($appointment->status->code) ?></div>
				</div>
				<div class="col-md-4 col-6 mb-3">
					<h5 class="mb-1"><?= $this->lang->line('w_date_hour') ?></h5>
					<div><?= date("Y-m-d h:i A", strtotime($appointment->schedule_from)) ?></div>
				</div>
				
				<div class="col-md-5 mb-3">
					<h5 class="mb-1"><?= $this->lang->line('w_detail') ?></h5>
					<div><?php if ($appointment->sale_prod) echo $appointment->sale_prod; else echo "-"; ?></div>
				</div>
				<div class="col-md-3 mb-3">
					<h5 class="mb-1"><?= $this->lang->line('w_history_number') ?></h5>
					<div><?= $patient->doc_number ?></div>
				</div>
				<div class="col-md-4 mb-3">
					<h5 class="mb-1"><?= $this->lang->line('w_patient') ?></h5>
					<div><?= $patient->name ?></div>
				</div>
				<div class="col-md-5 mb-3">
					<h5 class="mb-1"><?= $this->lang->line('w_doctor') ?></h5>
					<div><?= $doctor->name ?></div>
				</div>
			</div>
			<div class="row d-none" id="app_reschedule">
				<div class="col-md-6">
					<h5 class="mb-3"><?= $this->lang->line('w_reschedule_appointment') ?></h5>
					<form action="#" id="reschedule_form">
						<input type="hidden" name="id" value="<?= $appointment->id ?>" readonly>
						<input type="hidden" id="ra_doctor" value="<?= $doctor->id ?>" readonly>
						<div class="form-row">
							<div class="form-group col-md-12">
								<label>
									<span class="mr-1"><?= $this->lang->line('w_doctor') ?></span>
									<span><i class="far fa-clock" id="ic_doctor_schedule_w" data-toggle="modal" data-target=".md_weekly_doctor_agenda"></i></span>
								</label>
								<div><strong><?= $doctor->name ?></strong></div>
							</div>
							<div class="form-group col-md-12">
								<label><?= $this->lang->line('w_patient') ?></label>
								<div><strong><?= $patient->name ?></strong></div>
							</div>
							<div class="form-group col-md-6">
								<label><?= $this->lang->line('w_date') ?></label>
								<input type="text" class="form-control bw date_picker doc_schedule schedule" id="ra_date" name="date" value="<?= date('Y-m-d') ?>" readonly>
								<div class="sys_msg" id="ra_date_msg"></div>
							</div>
							<div class="form-group col-md-6">
								<label><?= $this->lang->line('w_time') ?></label>
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
								<div class="sys_msg" id="ra_schedule_msg"></div>
							</div>
							<div class="form-group col-md-12 pt-3">
								<button type="sumit" class="btn btn-primary"><?= $this->lang->line('btn_confirm') ?></button>
								<button type="button" class="btn btn-danger light" id="btn_reschedule_cancel"><?= $this->lang->line('btn_cancel') ?></button>
							</div>
						</div>
					</form>
				</div>
				<div class="col-md-6">
					<h5 class="mb-3"><?= $this->lang->line('w_doctor_agenda') ?></h5>
					<div id="rp_schedule"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php if ($appointment->remark){ ?>
<div class="col-md-12">
	<div class="card">
		<div class="card-header">
			<h4 class="mb-0"><?= $this->lang->line('w_remark') ?></h4>
		</div>
		<div class="card-body">
			<div style="white-space: pre;"><?= $appointment->remark ?></div>
		</div>
	</div>
</div>
<?php } ?>
<div class="col-md-6">
	<div class="card">
		<div class="card-header">
			<h4 class="mb-0"><?= $this->lang->line('w_clinical_histories') ?></h4>
		</div>
		<div class="card-body ap_content_list">
			<?php if ($histories){ ?>
			<table class="table mb-0">
				<thead>
					<tr>
						<th class="pt-0 pl-0"><?= $this->lang->line('w_schedule') ?></th>
						<th class="pt-0"><?= $this->lang->line('w_type') ?></th>
						<th class="pt-0"><?= $this->lang->line('w_specialty') ?></th>
						<th class="pt-0 pr-0"></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($histories as $item){ ?>
					<tr class="text-left">
						<td class="pl-0"><?= date("Y-m-d<\b\\r>h:i a", strtotime($item->schedule_from)) ?></td>
						<td><?= $item->type ?></td>
						<td><?= $item->specialty ?></td>
						<td class="text-right pr-0">
							<?php if ($appointment->id != $item->id){ ?>
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
			<div class="text-muted text-center"><?= $this->lang->line('w_no_records') ?></div>
			<?php } ?>
		</div>
	</div>
</div>
<div class="col-md-6">
	<div class="card">
		<div class="card-header">
			<h4 class="mb-0"><?= $this->lang->line('w_files') ?></h4>
		</div>
		<div class="card-body ap_content_list">
			<?php if ($patient_files){ ?>
			<table class="table mb-0">
				<thead>
					<tr>
						<th class="pt-0 pl-0"><?= $this->lang->line('w_date') ?></th>
						<th class="pt-0"><?= $this->lang->line('w_title') ?></th>
						<th class="pt-0 pr-0"></th>
					</tr>
				</thead>
				<tbody>
					<?php $file_path = "/archivos/pacientes/".str_replace(" ", "_", $patient->name)."_".$patient->doc_number."/";
					foreach($patient_files as $item){ ?>
					<tr>
						<td class="pl-0"><?= date("Y-m-d<\b\\r>h:i:s", strtotime($item->registed_at)) ?></td>
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
			<div class="text-muted text-center"><?= $this->lang->line('w_no_records') ?></div>
			<?php } ?>
		</div>
	</div>
</div>
<?php if ($appointment->status->code === "reserved"){ ?>
<div class="col-md-12">
	<h3 class="text-center text-danger mb-5"><?= $this->lang->line('t_no_confirmed') ?></h3>
</div>
<?php } elseif ($appointment->status->code === "confirmed") $this->load->view('appointment/detail_'.$this->session->userdata("role")->name); elseif ($appointment->status->code === "finished") $this->load->view('appointment/detail_finished'); ?>
<div class="modal fade md_weekly_doctor_agenda" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<h5 class="modal-title"><?= $this->lang->line('w_doctor_agenda') ?></h5>
				<button type="button" class="close" data-dismiss="modal"><span>&times;</span>
				</button>
			</div>
			<div class="modal-body" id="bl_weekly_schedule"></div>
		</div>
	</div>
</div>
<input type="hidden" id="appointment_id" value="<?= $appointment->id ?>">
<input type="hidden" id="bmi_class_under_weight" value="<?= $this->lang->line('w_bmi_class_under_weight') ?>">
<input type="hidden" id="bmi_class_normal" value="<?= $this->lang->line('w_bmi_class_normal') ?>">
<input type="hidden" id="bmi_class_overweight" value="<?= $this->lang->line('w_bmi_class_overweight') ?>">
<input type="hidden" id="bmi_class_obesity_1" value="<?= $this->lang->line('w_bmi_class_obesity_1') ?>">
<input type="hidden" id="bmi_class_obesity_2" value="<?= $this->lang->line('w_bmi_class_obesity_2') ?>">
<input type="hidden" id="bmi_class_obesity_3" value="<?= $this->lang->line('w_bmi_class_obesity_3') ?>">