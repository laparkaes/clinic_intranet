<div class="pagetitle">
	<h1><?= $appointment->specialty ?></h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= $this->lang->line('w_home') ?></a></li>
			<li class="breadcrumb-item"><a href="<?= base_url() ?>doctor"><?= $this->lang->line('appointments') ?></a></li>
			<li class="breadcrumb-item active"><?= $this->lang->line('txt_detail') ?></li>
		</ol>
	</nav>
</div>
<div class="row">
	<div class="col-md-12">
		<?php if ($actions){ ?>
		<div class="btn-group">
			<?php if (in_array("clinic_history", $actions)){ ?>
			<a class="btn btn-primary" href="<?= base_url() ?>appointment/clinical_history/<?= $appointment->id ?>" target="_blank">
				<?= $this->lang->line('w_clinical_history') ?>
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
		<div class="card" id="app_info">
			<div class="card-body">
				<h5 class="card-title"><?= $this->lang->line('w_general_information') ?></h5>
				<div class="row g-3">
					<div class="col-md-3">
						<label class="form-label"><strong><?= $this->lang->line('w_status') ?></strong></label>
						<div class="text-<?= $appointment->status->color ?>"><?= $this->lang->line($appointment->status->code) ?></div>
					</div>
					<div class="col-md-4">
						<label class="form-label"><strong><?= $this->lang->line('w_date_hour') ?></strong></label>
						<div><?= date("Y-m-d h:i A", strtotime($appointment->schedule_from)) ?></div>
					</div>
					
					<div class="col-md-5">
						<label class="form-label"><strong><?= $this->lang->line('w_detail') ?></strong></label>
						<div><?php if ($appointment->sale_prod) echo $appointment->sale_prod; else echo "-"; ?></div>
					</div>
					<div class="col-md-3">
						<label class="form-label"><strong><?= $this->lang->line('w_history_number') ?></strong></label>
						<div><?= $patient->doc_number ?></div>
					</div>
					<div class="col-md-4">
						<label class="form-label"><strong><?= $this->lang->line('w_patient') ?></strong></label>
						<div><?= $patient->name ?></div>
					</div>
					<div class="col-md-5">
						<label class="form-label"><strong><?= $this->lang->line('w_doctor') ?></strong></label>
						<div><?= $doctor->name ?></div>
					</div>
				</div>
			</div>
		</div>
		<div class="card d-none" id="app_reschedule">
			<div class="card-body">
				<h5 class="card-title"><?= $this->lang->line('w_reschedule_appointment') ?></h5>
				<div class="row">
					<div class="col-md-6">
						<form class="row g-3" id="reschedule_form">
							<input type="hidden" name="id" value="<?= $appointment->id ?>" readonly>
							<input type="hidden" id="ra_doctor" value="<?= $doctor->id ?>" readonly>
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
								<input type="text" class="form-control date_picker doc_schedule schedule" id="ra_date" name="date" value="<?= date('Y-m-d') ?>">
								<div class="sys_msg" id="ra_date_msg"></div>
							</div>
							<div class="col-md-8">
								<label class="form-label"><?= $this->lang->line('w_time') ?></label>
								<div class="input-group">
									<select class="form-select schedule" id="ra_hour" name="hour">
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
									<span class="input-group-text">:</span>
									<select class="form-select schedule" id="ra_min" name="min">
										<option value="" selected>--</option>
										<option value="00">00</option>
										<option value="15">15</option>
										<option value="30">30</option>
										<option value="45">45</option>
									</select>
								</div>
								<div class="sys_msg" id="ra_schedule_msg"></div>
							</div>
							<div class="col-md-12 pt-3">
								<button type="sumit" class="btn btn-primary"><?= $this->lang->line('btn_confirm') ?></button>
								<button type="button" class="btn btn-secondary" id="btn_reschedule_cancel"><?= $this->lang->line('btn_back') ?></button>
							</div>
						</form>
					</div>
					<div class="col-md-6">
						<div class="row g-3">
							<div class="col-md-12">
								<strong><?= $this->lang->line('w_doctor_agenda') ?></strong>
							</div>
							<div class="col-md-12">
								<div id="rp_schedule"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<?php if ($appointment->remark){ ?>
		<div class="card">
			<div class="card-body">
				<h5 class="card-title"><?= $this->lang->line('w_remark') ?></h5>
				<div class="form-control" style="white-space: pre;"><?= $appointment->remark ?></div>
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
							<tr class="text-left">
								<td><?= date("Y-m-d h:i a", strtotime($item->schedule_from)) ?></td>
								<td><?= $item->type ?></td>
								<td><?= $item->specialty ?></td>
								<td class="text-end">
									<?php if ($appointment->id != $item->id){ ?>
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


<div class="col-md-6">
</div>
<?php if ($appointment->status->code === "reserved"){ ?>
<div class="col-md-12">
	<h3 class="text-center text-danger mb-5"><?= $this->lang->line('t_no_confirmed') ?></h3>
</div>
<?php } elseif ($appointment->status->code === "confirmed") $this->load->view('appointment/detail_'.$this->session->userdata("role")->name); elseif ($appointment->status->code === "finished") $this->load->view('appointment/detail_finished'); ?>
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

<input type="hidden" id="appointment_id" value="<?= $appointment->id ?>">
<input type="hidden" id="bmi_class_under_weight" value="<?= $this->lang->line('w_bmi_class_under_weight') ?>">
<input type="hidden" id="bmi_class_normal" value="<?= $this->lang->line('w_bmi_class_normal') ?>">
<input type="hidden" id="bmi_class_overweight" value="<?= $this->lang->line('w_bmi_class_overweight') ?>">
<input type="hidden" id="bmi_class_obesity_1" value="<?= $this->lang->line('w_bmi_class_obesity_1') ?>">
<input type="hidden" id="bmi_class_obesity_2" value="<?= $this->lang->line('w_bmi_class_obesity_2') ?>">
<input type="hidden" id="bmi_class_obesity_3" value="<?= $this->lang->line('w_bmi_class_obesity_3') ?>">