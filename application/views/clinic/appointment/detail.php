<div class="pagetitle">
	<h1><?= $appointment->specialty ?></h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= $this->lang->line('w_home') ?></a></li>
			<li class="breadcrumb-item"><a href="<?= base_url() ?>appointment"><?= $this->lang->line('appointments') ?></a></li>
			<li class="breadcrumb-item active"><?= $this->lang->line('txt_detail') ?></li>
		</ol>
	</nav>
</div>
<div class="row">
	<div class="col-md-12">
		<?php if ($actions){ ?>
		<div class="btn-group">
			<?php if (in_array("clinic_history", $actions)){ ?>
			<a class="btn btn-primary" href="<?= base_url() ?>clinic/appointment/clinical_history/<?= $appointment->id ?>" target="_blank">
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
						<label class="form-label"><?= $this->lang->line('w_status') ?></label>
						<div class="form-control text-<?= $appointment->status->color ?>"><?= $this->lang->line($appointment->status->code) ?></div>
					</div>
					<div class="col-md-4">
						<label class="form-label"><?= $this->lang->line('w_date_hour') ?></label>
						<div class="form-control"><?= date("Y-m-d h:i A", strtotime($appointment->schedule_from)) ?></div>
					</div>
					
					<div class="col-md-5">
						<label class="form-label"><?= $this->lang->line('w_detail') ?></label>
						<div class="form-control"><?php if ($appointment->sale_prod) echo $appointment->sale_prod; else echo "-"; ?></div>
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
							<tr>
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
<div class="row">
	<?php if ($appointment->status->code === "reserved"){ ?>
	<div class="col-md-12">
		<h3 class="text-center text-danger"><?= $this->lang->line('t_no_confirmed') ?></h3>
	</div>
	<?php }else{ 
	$bd = $appointment_datas["basic_data"]; 
	$an = $appointment_datas["anamnesis"];
	$ph = $appointment_datas["physical"];
	$di = $appointment_datas["diag_impression"];
	$re = $appointment_datas["result"];
	$ex = $appointment_datas["examination"]; $ex_profiles = $ex["profiles"]; $ex_examinations = $ex["exams"];
	$im = $appointment_datas["images"];
	$th = $appointment_datas["therapy"];
	$me = $appointment_datas["medicine"];
	
	$outline = "";
	if (in_array("information" , $operations)){
	?>
	<div class="col-md-4">
		<div class="d-grid gap-2">
			<button type="button" class="btn btn<?= $outline ?>-primary btn-lg btn_process mb-3" value="process_information">
				<i class="bi bi-info-circle me-3"></i>
				<span><?= $this->lang->line('btn_information') ?></span>
			</button>
		</div>
	</div>
	<?php $outline = "-outline";} if (in_array("triage" , $operations)){ ?>
	<div class="col-md-4">
		<div class="d-grid gap-2">
			<button type="button" class="btn btn<?= $outline ?>-primary btn-lg btn_process mb-3" value="process_triage">
				<i class="bi bi-journal-medical me-3"></i>
				<span><?= $this->lang->line('btn_triage') ?></span>
			</button>
		</div>
	</div>
	<?php $outline = "-outline";} if (in_array("attention" , $operations)){ ?>
	<div class="col-md-4">
		<div class="d-grid gap-2">
			<button type="button" class="btn btn<?= $outline ?>-primary btn-lg btn_process mb-3" value="process_attention">
				<i class="bi bi-clipboard2-pulse me-3"></i>
				<span><?= $this->lang->line('btn_attention') ?></span>
			</button>
		</div>
	</div>
	<?php } ?>
</div>
<div class="row">
	<div class="col-md-12">
		<?php $dnone = ""; if (in_array("information" , $operations)){ ?>
		<div class="card process process_information <?= $dnone ?>">
			<div class="card-body">
				<h5 class="card-title"><?= $this->lang->line('w_basic_data') ?></h5>
				<form class="row g-3" id="form_basic_data">
					<input type="hidden" name="appointment_id" value="<?= $appointment->id ?>">
					<div class="col-md-6">
						<div class="row g-3">
							<div class="col-md-12">
								<strong><?= $this->lang->line('w_entry') ?></strong>
							</div>
							<div class="col-md-12">
								<label class="form-label"><?= $this->lang->line('w_entry_mode') ?></label>
								<select class="form-select" name="entry_mode_id">
									<option value="">--</option>
									<?php $entry_mode = $options["entry_mode"]; foreach($entry_mode as $item){ 
									if ($bd->entry_mode_id == $item->id) $selected = "selected"; else $selected = ""; ?>
									<option value="<?= $item->id ?>" <?= $selected ?>><?= $item->description ?></option>
									<?php } ?>
								</select>
								<div class="sys_msg" id="bd_entry_mode_msg"></div>
							</div>
							<div class="col-md-6">
								<label class="form-label"><?= $this->lang->line('w_date') ?></label>
								<input type="text" class="form-control" value="<?= $bd->date ?>" name="date">
								<div class="sys_msg" id="bd_date_msg"></div>
							</div>
							<div class="col-md-6">
								<label class="form-label"><?= $this->lang->line('w_hour') ?></label>
								<input type="text" class="form-control time_picker" value="<?= $bd->time ?>" name="time">
								<div class="sys_msg" id="bd_time_msg"></div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="row g-3">
							<div class="col-md-12">
								<strong><?= $this->lang->line('w_insurance') ?></strong>
							</div>
							<div class="col-md-12">
								<label class="form-label"><?= $this->lang->line('w_insured') ?></label>
								<select class="form-select" name="insurance">
									<?php if ($bd->insurance) $s = "selected"; else $s = ""; ?>
									<option value="1" <?= $s ?>><?= $this->lang->line('w_yes') ?></option>
									<?php if (!$bd->insurance) $s = "selected"; else $s = ""; ?>
									<option value="" <?= $s ?>><?= $this->lang->line('w_no') ?></option>
								</select>
								<div class="sys_msg" id="bd_insurance_msg"></div>
							</div>
							<div class="col-md-12">
								<label class="form-label"><?= $this->lang->line('w_insurance_name') ?></label>
								<input type="text" class="form-control" name="insurance_name" value="<?= $bd->insurance_name ?>">
								<div class="sys_msg" id="bd_insurance_name_msg"></div>
							</div>
						</div>
					</div>
					<div class="col-md-12 pt-3">
						<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_save') ?></button>
						<div class="sys_msg" id="bd_result_msg"></div>
					</div>
				</form>
			</div>
		</div>
		<div class="card process process_information <?= $dnone ?>">
			<div class="card-body">
				<h5 class="card-title"><?= $this->lang->line('w_personal_information') ?></h5>
				<form class="row g-3" id="form_personal_information">
					<input type="hidden" name="appointment_id" value="<?= $appointment->id ?>">
					<div class="col-md-6">
						<label class="form-label"><?= $this->lang->line('w_name') ?></label>
						<input type="text" class="form-control" name="name" value="<?= $an->name ?>" readonly>
						<div class="sys_msg" id="pi_name_msg"></div>
					</div>
					<div class="col-md-3">
						<label class="form-label"><?= $this->lang->line('w_age') ?></label>
						<input type="text" class="form-control" name="age" value="<?= $an->age ?>">
						<div class="sys_msg" id="pi_age_msg"></div>
					</div>
					<div class="col-md-3">
						<label class="form-label"><?= $this->lang->line('w_sex') ?></label>
						<select class="form-select" name="sex_id">
							<option value="">--</option>
							<?php foreach($sex_ops as $item){
							if ($item->id == $an->sex_id) $s = "selected"; else $s = ""; ?>
							<option value="<?= $item->id ?>" <?= $s ?>><?= $item->description ?></option>
							<?php } ?>
						</select>
						<div class="sys_msg" id="pi_sex_msg"></div>
					</div>
					<div class="col-md-6">
						<label class="form-label"><?= $this->lang->line('w_address') ?></label>
						<input type="text" class="form-control" name="address" value="<?= $an->address ?>">
						<div class="sys_msg" id="pi_address_msg"></div>
					</div>
					<div class="col-md-3">
						<label class="form-label"><?= $this->lang->line('w_birthplace') ?></label>
						<input type="text" class="form-control" name="birthplace" value="<?= $an->birthplace ?>">
						<div class="sys_msg" id="pi_birthplace_msg"></div>
					</div>
					<div class="col-md-3">
						<label class="form-label"><?= $this->lang->line('w_birthday') ?></label>
						<input type="text" class="form-control" name="birthday" value="<?= $an->birthday ?>">
						<div class="sys_msg" id="pi_birthday_msg"></div>
					</div>
					<div class="col-md-3">
						<label class="form-label"><?= $this->lang->line('w_telephone') ?></label>
						<input type="text" class="form-control" name="tel" value="<?= $an->tel ?>">
						<div class="sys_msg" id="pi_tel_msg"></div>
					</div>
					<div class="col-md-3">
						<label class="form-label"><?= $this->lang->line('w_responsible') ?></label>
						<input type="text" class="form-control" name="responsible" value="<?= $an->responsible ?>">
						<div class="sys_msg" id="pi_responsible_msg"></div>
					</div>
					<div class="col-md-3">
						<label class="form-label"><?= $this->lang->line('w_place_of_origin') ?></label>
						<input type="text" class="form-control" name="provenance_place" value="<?= $an->provenance_place ?>">
						<div class="sys_msg" id="pi_provenance_place_msg"></div>
					</div>
					<div class="col-md-3">
						<label class="form-label"><?= $this->lang->line('w_last_trips') ?></label>
						<input type="text" class="form-control" name="last_trips" value="<?= $an->last_trips ?>">
						<div class="sys_msg" id="pi_last_trips_msg"></div>
					</div>
					<div class="col-md-3">
						<label class="form-label"><?= $this->lang->line('w_race') ?></label>
						<input type="text" class="form-control" name="race" value="<?= $an->race ?>">
						<div class="sys_msg" id="pi_race_msg"></div>
					</div>
					<div class="col-md-3">
						<label class="form-label"><?= $this->lang->line('w_marital_status') ?></label>
						<select class="form-select" name="civil_status_id">
							<option value="" selected="">--</option>
							<?php $civil_status = $options["civil_status"]; foreach($civil_status as $item){
							if ($item->id == $an->civil_status_id) $selected = "selected"; else $selected = ""; ?>
							<option value="<?= $item->id ?>" <?= $selected ?>><?= $item->description ?></option>
							<?php } ?>
						</select>
						<div class="sys_msg" id="pi_civil_status_msg"></div>
					</div>
					<div class="col-md-3">
						<label class="form-label"><?= $this->lang->line('w_occupation') ?></label>
						<input type="text" class="form-control" name="occupation" value="<?= $an->occupation ?>">
						<div class="sys_msg" id="pi_occupation_msg"></div>
					</div>
					<div class="col-md-3">
						<label class="form-label"><?= $this->lang->line('w_religion') ?></label>
						<input type="text" class="form-control" name="religion" value="<?= $an->religion ?>">
						<div class="sys_msg" id="pi_religion_msg"></div>
					</div>
					<?php if ($appointment->is_editable){ ?>
					<div class="col-md-12 pt-3">
						<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_save') ?></button>
						<div class="sys_msg" id="pi_result_msg"></div>
					</div>
					<?php } ?>
				</form>
			</div>
		</div>
		<?php  $dnone = "d-none";} if (in_array("triage" , $operations)){ ?>
		<div class="card process process_triage <?= $dnone ?>">
			<div class="card-body">
				<h5 class="card-title"><?= $this->lang->line('w_triage') ?></h5>
				<form class="row g-3" id="form_triage">
					<input type="hidden" name="appointment_id" value="<?= $appointment->id ?>">
					<div class="col-md-3">
						<label class="form-label"><?= $this->lang->line('w_pa') ?></label>
						<input type="text" class="form-control" name="v_pa" value="<?= $ph->v_pa ?>">
						<div class="sys_msg" id="tr_v_pa_msg"></div>
					</div>
					<div class="col-md-3">
						<label class="form-label"><?= $this->lang->line('w_fc') ?></label>
						<input type="text" class="form-control" name="v_fc" value="<?= $ph->v_fc ?>">
						<div class="sys_msg" id="tr_v_fc_msg"></div>
					</div>
					<div class="col-md-3">
						<label class="form-label"><?= $this->lang->line('w_fr') ?></label>
						<input type="text" class="form-control" name="v_fr" value="<?= $ph->v_fr ?>">
						<div class="sys_msg" id="tr_v_fr_msg"></div>
					</div>
					<div class="col-md-3">
						<label class="form-label"><?= $this->lang->line('w_temperature') ?></label>
						<input type="text" class="form-control" name="v_temperature" value="<?= $ph->v_temperature ?>">
						<div class="sys_msg" id="tr_v_temperature_msg"></div>
					</div>
					<div class="col-md-3">
						<label class="form-label"><?= $this->lang->line('w_weight') ?></label>
						<input type="text" class="form-control set_bmi" name="v_weight" value="<?= $ph->v_weight ?>">
						<div class="sys_msg" id="tr_v_weight_msg"></div>
					</div>
					<div class="col-md-3">
						<label class="form-label"><?= $this->lang->line('w_height') ?></label>
						<input type="text" class="form-control set_bmi" name="v_height" value="<?= $ph->v_height ?>">
						<div class="sys_msg" id="tr_v_height_msg"></div>
					</div>
					<div class="col-md-3">
						<label class="form-label"><?= $this->lang->line('w_bmi') ?></label>
						<input type="text" class="form-control" name="v_imc" value="<?= $ph->v_imc ?>" readonly>
						<div class="sys_msg" id="tr_v_imc_msg"></div>
					</div>
					<div class="col-md-3">
						<label class="form-label"><?= $this->lang->line('w_bmi_class') ?></label>
						<input type="text" class="form-control" name="v_imc_class" value="<?= $ph->v_imc_class ?>" readonly>
						<div class="sys_msg" id="tr_v_imc_class"></div>
					</div>
					<div class="col-md-12 pt-3">
						<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_save') ?></button>
						<div class="sys_msg" id="tr_result_msg"></div>
					</div>
				</form>
			</div>
		</div>
		<?php  $dnone = "d-none";} if (in_array("attention" , $operations)){ ?>
		<div class="card process process_attention <?= $dnone ?>">
			<div class="card-body">
				<h5 class="card-title"><?= $this->lang->line('w_anamnesis') ?></h5>
				<form class="row g-3" id="form_anamnesis">
					<input type="hidden" name="appointment_id" value="<?= $appointment->id ?>">
					<div class="col-md-12">
						<ul class="nav nav-tabs nav-tabs-bordered" id="anamnesis_tab" role="tablist">
							<li class="nav-item" role="presentation">
								<button class="nav-link active" id="anamnesis-1-tab" data-bs-toggle="tab" data-bs-target="#bordered-anamnesis-1" type="button" role="tab" aria-controls="anamnesis-1" aria-selected="true"><?= $this->lang->line('w_personal_information') ?></button>
							</li>
							<li class="nav-item" role="presentation">
								<button class="nav-link" id="anamnesis-2-tab" data-bs-toggle="tab" data-bs-target="#bordered-anamnesis-2" type="button" role="tab" aria-controls="anamnesis-2" aria-selected="false" tabindex="-1"><?= $this->lang->line('w_current_illness') ?></button>
							</li>
							<li class="nav-item" role="presentation">
								<button class="nav-link" id="anamnesis-3-tab" data-bs-toggle="tab" data-bs-target="#bordered-anamnesis-3" type="button" role="tab" aria-controls="anamnesis-3" aria-selected="false" tabindex="-1"><?= $this->lang->line('w_biological_functions') ?></button>
							</li>
							<li class="nav-item" role="presentation">
								<button class="nav-link" id="anamnesis-4-tab" data-bs-toggle="tab" data-bs-target="#bordered-anamnesis-4" type="button" role="tab" aria-controls="anamnesis-4" aria-selected="false" tabindex="-1"><?= $this->lang->line('w_pathological') ?></button>
							</li>
							<li class="nav-item" role="presentation">
								<button class="nav-link" id="anamnesis-5-tab" data-bs-toggle="tab" data-bs-target="#bordered-anamnesis-5" type="button" role="tab" aria-controls="anamnesis-5" aria-selected="false" tabindex="-1"><?= $this->lang->line('w_gynecological') ?></button>
							</li>
						</ul>
						<div class="tab-content pt-4" id="anamnesis_tab_content">
							<div class="tab-pane fade show active" id="bordered-anamnesis-1" role="tabpanel" aria-labelledby="anamnesis-1-tab">
								<div class="row g-3">
									<div class="col-md-6">
										<label class="form-label"><?= $this->lang->line('w_name') ?></label>
										<input type="text" class="form-control" name="name" value="<?= $an->name ?>" readonly>
										<div class="sys_msg" id="an_name_msg"></div>
									</div>
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_age') ?></label>
										<input type="text" class="form-control" name="age" value="<?= $an->age ?>">
										<div class="sys_msg" id="an_age_msg"></div>
									</div>
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_sex') ?></label>
										<select class="form-select" name="sex_id">
											<option value="">--</option>
											<?php foreach($sex_ops as $item){
											if ($item->id == $an->sex_id) $s = "selected"; else $s = ""; ?>
											<option value="<?= $item->id ?>" <?= $s ?>><?= $item->description ?></option>
											<?php } ?>
										</select>
										<div class="sys_msg" id="an_sex_msg"></div>
									</div>
									<div class="col-md-6">
										<label class="form-label"><?= $this->lang->line('w_address') ?></label>
										<input type="text" class="form-control" name="address" value="<?= $an->address ?>">
										<div class="sys_msg" id="an_address_msg"></div>
									</div>
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_birthplace') ?></label>
										<input type="text" class="form-control" name="birthplace" value="<?= $an->birthplace ?>">
										<div class="sys_msg" id="an_birthplace_msg"></div>
									</div>
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_birthday') ?></label>
										<input type="text" class="form-control" name="birthday" value="<?= $an->birthday ?>">
										<div class="sys_msg" id="an_birthday_msg"></div>
									</div>
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_telephone') ?></label>
										<input type="text" class="form-control" name="tel" value="<?= $an->tel ?>">
										<div class="sys_msg" id="an_tel_msg"></div>
									</div>
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_responsible') ?></label>
										<input type="text" class="form-control" name="responsible" value="<?= $an->responsible ?>">
										<div class="sys_msg" id="an_responsible_msg"></div>
									</div>
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_place_of_origin') ?></label>
										<input type="text" class="form-control" name="provenance_place" value="<?= $an->provenance_place ?>">
										<div class="sys_msg" id="an_provenance_place_msg"></div>
									</div>
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_last_trips') ?></label>
										<input type="text" class="form-control" name="last_trips" value="<?= $an->last_trips ?>">
										<div class="sys_msg" id="an_last_trips_msg"></div>
									</div>
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_race') ?></label>
										<input type="text" class="form-control" name="race" value="<?= $an->race ?>">
										<div class="sys_msg" id="an_race_msg"></div>
									</div>
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_marital_status') ?></label>
										<select class="form-select" name="civil_status_id">
											<option value="" selected="">--</option>
											<?php $civil_status = $options["civil_status"]; foreach($civil_status as $item){
											if ($item->id == $an->civil_status_id) $selected = "selected"; else $selected = ""; ?>
											<option value="<?= $item->id ?>" <?= $selected ?>><?= $item->description ?></option>
											<?php } ?>
										</select>
										<div class="sys_msg" id="an_civil_status_msg"></div>
									</div>
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_occupation') ?></label>
										<input type="text" class="form-control" name="occupation" value="<?= $an->occupation ?>">
										<div class="sys_msg" id="an_occupation_msg"></div>
									</div>
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_religion') ?></label>
										<input type="text" class="form-control" name="religion" value="<?= $an->religion ?>">
										<div class="sys_msg" id="an_religion_msg"></div>
									</div>
								</div>
							</div>
							<div class="tab-pane fade" id="bordered-anamnesis-2" role="tabpanel" aria-labelledby="anamnesis-2-tab">
								<div class="row g-3">
									<div class="col-md-4">
										<label class="form-label"><?= $this->lang->line('w_illness_time') ?></label>
										<input type="text" class="form-control" name="illness_time" value="<?= $an->illness_time ?>">
										<div class="sys_msg" id="an_illness_time_msg"></div>
									</div>
									<div class="col-md-4">
										<label class="form-label"><?= $this->lang->line('w_start') ?></label>
										<input type="text" class="form-control" name="illness_start" value="<?= $an->illness_start ?>">
										<div class="sys_msg" id="an_illness_start_msg"></div>
									</div>
									<div class="col-md-4">
										<label class="form-label"><?= $this->lang->line('w_grade') ?></label>
										<input type="text" class="form-control" name="illness_course" value="<?= $an->illness_course ?>">
										<div class="sys_msg" id="an_illness_course_msg"></div>
									</div>
									<div class="col-md-6">
										<label class="form-label"><?= $this->lang->line('w_main_symptoms') ?></label>
										<textarea class="form-control" rows="5" name="illness_main_symptoms"><?= $an->illness_main_symptoms ?></textarea>
										<div class="sys_msg" id="an_illness_main_symptoms_msg"></div>
									</div>
									<div class="col-md-6">
										<label class="form-label"><?= $this->lang->line('w_story') ?></label>
										<textarea class="form-control" rows="5" name="illness_story"><?= $an->illness_story ?></textarea>
										<div class="sys_msg" id="an_illness_story_msg"></div>
									</div>
								</div>
							</div>
							<div class="tab-pane fade" id="bordered-anamnesis-3" role="tabpanel" aria-labelledby="anamnesis-3-tab">
								<div class="row g-3">
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_appetite') ?></label>
										<input type="text" class="form-control" name="func_bio_appetite" value="<?= $an->func_bio_appetite ?>">
										<div class="sys_msg" id="an_func_bio_appetite_msg"></div>
									</div>
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_urine') ?></label>
										<input type="text" class="form-control" name="func_bio_urine" value="<?= $an->func_bio_urine ?>">
										<div class="sys_msg" id="an_func_bio_urine_msg"></div>
									</div>
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_thirst') ?></label>
										<input type="text" class="form-control" name="func_bio_thirst" value="<?= $an->func_bio_thirst ?>">
										<div class="sys_msg" id="an_func_bio_thirst_msg"></div>
									</div>
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_bowel_movements') ?></label>
										<input type="text" class="form-control" name="func_bio_bowel_movements" value="<?= $an->illness_course ?>">
										<div class="sys_msg" id="an_func_bio_bowel_movements_msg"></div>
									</div>
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_sweat') ?></label>
										<input type="text" class="form-control" name="func_bio_sweat" value="<?= $an->func_bio_sweat ?>">
										<div class="sys_msg" id="an_func_bio_sweat_msg"></div>
									</div>
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_weight_') ?></label>
										<input type="text" class="form-control" name="func_bio_weight" value="<?= $an->func_bio_weight ?>">
										<div class="sys_msg" id="an_func_bio_weight_msg"></div>
									</div>
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_sleep') ?></label>
										<input type="text" class="form-control" name="func_bio_sleep" value="<?= $an->func_bio_sleep ?>">
										<div class="sys_msg" id="an_func_bio_sleep_msg"></div>
									</div>
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_encouragement') ?></label>
										<input type="text" class="form-control" name="func_bio_encouragement" value="<?= $an->func_bio_encouragement ?>">
										<div class="sys_msg" id="an_func_bio_encouragement_msg"></div>
									</div>
								</div>
							</div>
							<div class="tab-pane fade" id="bordered-anamnesis-4" role="tabpanel" aria-labelledby="anamnesis-4-tab">
								<div class="row g-3">
									<div class="col-md-12">
										<label class="form-label"><?= $this->lang->line('w_previous_illnesses') ?></label>
										<div class="row">
											<?php foreach($pre_illnesses as $item){ if (in_array($item["value"], $an->patho_pre_illnesses)) $checked = "checked"; else $checked = "";?>
											<div class="col-auto">
												<div class="custom-control custom-checkbox checkbox-primary">
													<input type="checkbox" class="custom-control-input" id="<?= $item["id"] ?>" name="patho_pre_illnesses[]" value="<?= $item["value"] ?>" <?= $checked ?>>
													<label class="custom-control-label" for="<?= $item["id"] ?>"><?= $item["value"] ?></label>
												</div>
											</div>
											<?php } ?>
											<div class="col-md-12 pt-3">
												<input type="text" class="form-control" name="patho_pre_illnesses_other" id="other_pre_illnesses" placeholder="<?= $this->lang->line('t_other_illness') ?>" value="<?= $an->patho_pre_illnesses_other ?>">
												<div class="sys_msg" id="an_patho_pre_illnesses_msg"></div>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<label class="form-label"><?= $this->lang->line('w_previous_hospitalizations') ?></label>
										<textarea class="form-control" rows="3" name="patho_pre_hospitalization"><?= $an->patho_pre_hospitalization ?></textarea>
										<div class="sys_msg" id="an_patho_pre_hospitalization_msg"></div>
									</div>
									<div class="col-md-6">
										<label class="form-label"><?= $this->lang->line('w_previous_surgeries') ?></label>
										<textarea class="form-control" rows="3" name="patho_pre_surgery"><?= $an->patho_pre_surgery ?></textarea>
										<div class="sys_msg" id="an_patho_pre_surgery_msg"></div>
									</div>
									<div class="col-md-6">
										<label class="form-label"><?= $this->lang->line('w_ram') ?></label>
										<input type="text" class="form-control" name="patho_ram" value="<?= $an->patho_ram ?>">
										<div class="sys_msg" id="an_patho_ram_msg"></div>
									</div>
									<div class="col-md-6">
										<label class="form-label"><?= $this->lang->line('w_transfusions') ?></label>
										<input type="text" class="form-control" name="patho_transfusion" value="<?= $an->patho_transfusion ?>">
										<div class="sys_msg" id="an_patho_transfusion_msg"></div>
									</div>
									<div class="col-md-12">
										<label class="form-label"><?= $this->lang->line('w_prior_medication') ?></label>
										<input type="text" class="form-control" name="patho_pre_medication" value="<?= $an->patho_pre_medication ?>">
										<div class="sys_msg" id="an_patho_pre_medication_msg"></div>
									</div>
									<div class="col-md-12">
										<label class="form-label"><?= $this->lang->line('w_family_background') ?></label>
										<textarea class="form-control" rows="3" name="family_history"><?= $an->family_history ?></textarea>
										<div class="sys_msg" id="an_family_history_msg"></div>
									</div>
								</div>
							</div>
							<div class="tab-pane fade" id="bordered-anamnesis-5" role="tabpanel" aria-labelledby="anamnesis-5-tab">
								<div class="row g-3">
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_fur') ?></label>
										<input type="text" class="form-control" name="gyne_fur" value="<?= $an->gyne_fur ?>">
										<div class="sys_msg" id="an_gyne_fur_msg"></div>
									</div>
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_g') ?></label>
										<input type="text" class="form-control" name="gyne_g" value="<?= $an->gyne_g ?>">
										<div class="sys_msg" id="an_gyne_g_msg"></div>
									</div>
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_p') ?></label>
										<input type="text" class="form-control" name="gyne_p" value="<?= $an->gyne_p ?>">
										<div class="sys_msg" id="an_gyne_p_msg"></div>
									</div>
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_mac') ?></label>
										<input type="text" class="form-control" name="gyne_mac" value="<?= $an->gyne_mac ?>">
										<div class="sys_msg" id="an_gyne_mac_msg"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12 pt-3">
						<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_save') ?></button>
						<div class="sys_msg" id="an_result_msg"></div>
					</div>
				</form>
			</div>
		</div>
		<div class="card process process_attention <?= $dnone ?>">
			<div class="card-body">
				<h5 class="card-title"><?= $this->lang->line('w_physical_exam') ?></h5>
				<form class="row g-3" id="form_physical_exam">
					<input type="hidden" name="appointment_id" value="<?= $appointment->id ?>">
					<div class="col-md-12">
						<ul class="nav nav-tabs nav-tabs-bordered" id="anamnesis_tab" role="tablist">
							<li class="nav-item" role="presentation">
								<button class="nav-link active" id="physical_exam-1-tab" data-bs-toggle="tab" data-bs-target="#bordered-physical_exam-1" type="button" role="tab" aria-controls="physical_exam-1" aria-selected="true"><?= $this->lang->line('w_vital_functions') ?></button>
							</li>
							<li class="nav-item" role="presentation">
								<button class="nav-link" id="physical_exam-2-tab" data-bs-toggle="tab" data-bs-target="#bordered-physical_exam-2" type="button" role="tab" aria-controls="physical_exam-2" aria-selected="false" tabindex="-1"><?= $this->lang->line('w_general_exam') ?></button>
							</li>
							<li class="nav-item" role="presentation">
								<button class="nav-link" id="physical_exam-3-tab" data-bs-toggle="tab" data-bs-target="#bordered-physical_exam-3" type="button" role="tab" aria-controls="physical_exam-3" aria-selected="false" tabindex="-1"><?= $this->lang->line('w_regional_examination') ?></button>
							</li>
						</ul>
						<div class="tab-content pt-4" id="physical_exam_content">
							<div class="tab-pane fade show active" id="bordered-physical_exam-1" role="tabpanel" aria-labelledby="physical_exam-1-tab">
								<div class="row g-3">
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_pa') ?></label>
										<input type="text" class="form-control" name="v_pa" value="<?= $ph->v_pa ?>">
										<div class="sys_msg" id="pe_v_pa_msg"></div>
									</div>
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_fc') ?></label>
										<input type="text" class="form-control" name="v_fc" value="<?= $ph->v_fc ?>">
										<div class="sys_msg" id="pe_v_fc_msg"></div>
									</div>
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_fr') ?></label>
										<input type="text" class="form-control" name="v_fr" value="<?= $ph->v_fr ?>">
										<div class="sys_msg" id="pe_v_fr_msg"></div>
									</div>
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_temperature') ?></label>
										<input type="text" class="form-control" name="v_temperature" value="<?= $ph->v_temperature ?>">
										<div class="sys_msg" id="pe_v_temperature_msg"></div>
									</div>
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_weight') ?></label>
										<input type="text" class="form-control set_bmi" name="v_weight" value="<?= $ph->v_weight ?>">
										<div class="sys_msg" id="pe_v_weight_msg"></div>
									</div>
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_height') ?></label>
										<input type="text" class="form-control set_bmi" name="v_height" value="<?= $ph->v_height ?>">
										<div class="sys_msg" id="pe_v_height_msg"></div>
									</div>
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_bmi') ?></label>
										<input type="text" class="form-control" name="v_imc" value="<?= $ph->v_imc ?>" readonly>
										<div class="sys_msg" id="pe_v_imc_msg"></div>
									</div>
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_bmi_class') ?></label>
										<input type="text" class="form-control" name="v_imc_class" value="<?= $ph->v_imc_class ?>" readonly>
										<div class="sys_msg" id="pe_v_imc_class"></div>
									</div>
								</div>
							</div>
							<div class="tab-pane fade" id="bordered-physical_exam-2" role="tabpanel" aria-labelledby="physical_exam-2-tab">
								<div class="row g-3">
									<div class="col-md-6">
										<label class="form-label"><?= $this->lang->line('w_appearance') ?></label>
										<textarea class="form-control" rows="3" name="g_appearance"><?= $ph->g_appearance ?></textarea>
										<div class="sys_msg" id="pe_g_appearance_msg"></div>
									</div>
									<div class="col-md-6">
										<label class="form-label"><?= $this->lang->line('w_skin') ?></label>
										<textarea class="form-control" rows="3" name="g_skin"><?= $ph->g_skin ?></textarea>
										<div class="sys_msg" id="pe_g_skin_msg"></div>
									</div>
									<div class="col-md-4">
										<label class="form-label"><?= $this->lang->line('w_tcsc') ?></label>
										<textarea class="form-control" rows="3" name="g_tcsc"><?= $ph->g_tcsc ?></textarea>
										<div class="sys_msg" id="pe_g_tcsc_msg"></div>
									</div>
									<div class="col-md-4">
										<label class="form-label"><?= $this->lang->line('w_soma') ?></label>
										<textarea class="form-control" rows="3" name="g_soma"><?= $ph->g_soma ?></textarea>
										<div class="sys_msg" id="pe_g_soma_msg"></div>
									</div>
									<div class="col-md-4">
										<label class="form-label"><?= $this->lang->line('w_lymphatic') ?></label>
										<textarea class="form-control" rows="3" name="g_lymphatic"><?= $ph->g_lymphatic ?></textarea>
										<div class="sys_msg" id="pe_g_lymphatic_msg"></div>
									</div>
								</div>
							</div>
							<div class="tab-pane fade" id="bordered-physical_exam-3" role="tabpanel" aria-labelledby="physical_exam-3-tab">
								<div class="row g-3">
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_head') ?></label>
										<textarea class="form-control" rows="3" name="r_head"><?= $ph->r_head ?></textarea>
										<div class="sys_msg" id="pe_r_head_msg"></div>
									</div>
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_neck') ?></label>
										<textarea class="form-control" rows="3" name="r_neck"><?= $ph->r_neck ?></textarea>
										<div class="sys_msg" id="pe_r_neck_msg"></div>
									</div>
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_breasts') ?></label>
										<textarea class="form-control" rows="3" name="r_breasts"><?= $ph->r_breasts ?></textarea>
										<div class="sys_msg" id="pe_r_breasts_msg"></div>
									</div>
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_chest_and_lungs') ?></label>
										<textarea class="form-control" rows="3" name="r_thorax_lungs"><?= $ph->r_thorax_lungs ?></textarea>
										<div class="sys_msg" id="pe_r_thorax_lungs_msg"></div>
									</div>
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_cardiovascular') ?></label>
										<textarea class="form-control" rows="3" name="r_cardiovascular"><?= $ph->r_cardiovascular ?></textarea>
										<div class="sys_msg" id="pe_r_cardiovascular_msg"></div>
									</div>
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_abdomen') ?></label>
										<textarea class="form-control" rows="3" name="r_abdomen"><?= $ph->r_abdomen ?></textarea>
										<div class="sys_msg" id="pe_r_abdomen_msg"></div>
									</div>
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_genitourinary') ?></label>
										<textarea class="form-control" rows="3" name="r_genitourinary"><?= $ph->r_genitourinary ?></textarea>
										<div class="sys_msg" id="pe_r_genitourinary_msg"></div>
									</div>
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_neurological') ?></label>
										<textarea class="form-control" rows="3" name="r_neurologic"><?= $ph->r_neurologic ?></textarea>
										<div class="sys_msg" id="pe_r_neurologic_msg"></div>
									</div>
								</div>
							</div>
						</div>
						
					
					
					</div>
					<div class="col-md-12 pt-3">
						<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_save') ?></button>
						<div class="sys_msg" id="pe_result_msg"></div>
					</div>
				</form>
			</div>
		</div>
		<div class="card process process_attention <?= $dnone ?>">
			<div class="card-body">
				<div class="d-flex justify-content-between align-items-center">
					<h5 class="card-title"><?= $this->lang->line('w_diagnostic_impression') ?></h5>
					<form class="row g-3" id="form_search_diag">
						<div class="col-auto">
							<div class="input-group">
								<input type="text" class="form-control" name="filter">
								<button class="btn btn-primary" type="submit">
									<i class="bi bi-search"></i>
								</button>
							</div>
						</div>	
					</form>	
				</div>
				<div class="row">
					<div class="col-md-6">
						<div><strong><?= $this->lang->line('w_selected_diag') ?></strong></div>
						<table class="table">
							<tbody id="selected_diags">
								<?php foreach($di as $d){ ?>
								<tr>
									<td><?= $d->code ?></td>
									<td><?= $d->description ?></td>
									<?php if ($appointment->is_editable){ ?>
									<td class="text-end">
										<button type="button" class="btn btn-danger btn-sm btn_delete_diag" value="<?= $d->id ?>"><i class="bi bi-dash"></i></button>
									</td>
									<?php } ?>
								</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
					<div class="col-md-6">
						<div><strong><?= $this->lang->line('w_Results') ?></strong></div>
						<div class="ap_content_list_high">
							<table class="table">
								<tbody id="search_diag_result"></tbody>
								<tfoot><td class="sys_msg" id="di_diagnosis_msg" colspan="3"></td></tfoot>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="card process process_attention <?= $dnone ?>">
			<div class="card-body">
				<h5 class="card-title"><?= $this->lang->line('w_result') ?></h5>
				<form class="row g-3" id="form_result">
					<input type="hidden" name="appointment_id" value="<?= $appointment->id ?>">
					<div class="col-md-12">
						<label class="form-label"><?= $this->lang->line('w_diagnosis_type') ?></label>
						<div class="form-control d-flex align-items-center">
							<?php $diagnosis_type = $options["diagnosis_type"];
							foreach($diagnosis_type as $i => $item){ 
								if ($re->diagnosis_type_id){
									if ($item->id == $re->diagnosis_type_id) $checked = "checked"; else $checked = "";
								}elseif ($i) $checked = ""; else $checked = "checked";
							?>
							<label class="radio-inline text-center me-4 mb-0"><input type="radio" name="diagnosis_type_id" value="<?= $item->id ?>" <?= $checked ?>> <?= $item->description ?></label>
							<?php } ?>
						</div>
					</div>
					<div class="col-md-4">
						<label class="form-label"><?= $this->lang->line('w_diagnosis') ?></label>
						<textarea class="form-control" rows="3" name="diagnosis"><?= $re->diagnosis ?></textarea>
					</div>
					<div class="col-md-4">
						<label class="form-label"><?= $this->lang->line('w_workplan') ?></label>
						<textarea class="form-control" rows="3" name="plan"><?= $re->plan ?></textarea>
					</div>
					<div class="col-md-4">
						<label class="form-label"><?= $this->lang->line('w_treatment') ?></label>
						<textarea class="form-control" rows="3" name="treatment"><?= $re->treatment ?></textarea>
					</div>
					<div class="col-md-12 pt-3">
						<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_save') ?></button>
						<div class="sys_msg" id="rs_result_msg"></div>
					</div>
				</form>
			</div>
		</div>
		<div class="card process process_attention <?= $dnone ?>">
			<div class="card-body">
				<h5 class="card-title"><?= $this->lang->line('w_auxiliary_exam') ?></h5>
				<ul class="nav nav-tabs nav-tabs-bordered" id="auxiliary_exams_tab" role="tablist">
					<li class="nav-item" role="presentation">
						<button class="nav-link active" id="auxiliary_exams_lab-tab" data-bs-toggle="tab" data-bs-target="#bordered-auxiliary_exams_lab" type="button" role="tab" aria-controls="auxiliary_exams_lab" aria-selected="true"><?= $this->lang->line('w_laboratory') ?></button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="auxiliary_exams_img-tab" data-bs-toggle="tab" data-bs-target="#bordered-auxiliary_exams_img" type="button" role="tab" aria-controls="auxiliary_exams_img" aria-selected="false" tabindex="-1"><?= $this->lang->line('w_image') ?></button>
					</li>
				</ul>
				<div class="tab-content pt-4" id="auxiliary_exams_tab_content">
					<div class="tab-pane fade show active" id="bordered-auxiliary_exams_lab" role="tabpanel" aria-labelledby="auxiliary_exams_lab-tab">
						<div class="row g-3">
							<div class="col-md-5">
								<label class="form-label"><?= $this->lang->line('w_profile') ?></label>
								<div class="input-group">
									<select class="form-select" id="sl_profile_exam">
										<option value="">--</option>
										<?php foreach($exam_profiles as $item){ ?>
										<option value="<?= $item->id ?>"><?= $item->name ?></option>
										<?php } ?>
									</select>
									<button class="btn btn-primary" type="button" id="btn_add_exam_profile">
										<i class="bi bi-plus"></i>
									</button>
								</div>
							</div>
							<div class="col-md-3">
								<label class="form-label"><?= $this->lang->line('w_category') ?></label>
								<select class="form-select" id="sl_exam_category">
									<option value=""><?= $this->lang->line('w_all') ?></option>
									<?php foreach($exam_categories as $item){ ?>
									<option value="<?= $item->id ?>"><?= $item->name ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="col-md-4">
								<label class="form-label"><?= $this->lang->line('w_exam') ?></label>
								<div class="input-group">
									<select class="form-select" id="sl_exam">
										<option value="">--</option>
										<?php foreach($examinations as $item){ ?>
										<option value="<?= $item->id ?>" class="exam_cat exam_cat_<?= $item->category_id ?>"><?= $item->name ?></option>
										<?php } ?>
									</select>
									<button class="btn btn-primary" type="button" id="btn_add_exam">
										<i class="bi bi-plus"></i>
									</button>
								</div>
							</div>
						</div>
						<div class="row mt-3">
							<div class="table-responsive">
								<table class="table">
									<thead>
										<tr>
											<th><?= $this->lang->line('w_type') ?></th>
											<th><?= $this->lang->line('w_profile') ?></th>
											<th><?= $this->lang->line('w_exams') ?></th>
											<th></th>
										</tr>
									</thead>
									<tbody id="tbody_exams_profiles">
										<?php foreach($ex_profiles as $ep){ ?>
										<tr>
											<td><?= $ep->type ?></td>
											<td><?= $ep->name ?></td>
											<td><?= $ep->exams ?></td>
											<td class="text-end">
												<button type="button" class="btn btn-danger btn-sm btn_remove_exam_profile" value="<?= $ep->id ?>">
													<i class="bi bi-trash"></i>
												</button>
											</td>
										</tr>
										<?php } foreach($ex_examinations as $ee){ ?>
										<tr>
											<td><?= $ee->type ?></td>
											<td>-</td>
											<td><?= $ee->name ?></td>
											<?php if ($appointment->is_editable){ ?>
											<td class="text-end">
												<button type="button" class="btn btn-danger btn-sm btn_remove_exam" value="<?= $ee->id ?>">
													<i class="bi bi-trash"></i>
												</button>
											</td>
											<?php } ?>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="bordered-auxiliary_exams_img" role="tabpanel" aria-labelledby="auxiliary_exams_img-tab">
						<div class="row">
							<div class="col-md-12">
								<div class="row g-3">
									<div class="col-md-auto">
										<label class="form-label"><?= $this->lang->line('w_category') ?></label>
										<select class="form-select" id="sl_aux_img_category">
											<option value="">--</option>
											<?php foreach($aux_image_categories as $item){ ?>
											<option value="<?= $item->id ?>"><?= $item->name ?></option>
											<?php } ?>
										</select>
									</div>
									<div class="col-md-auto">
										<label class="form-label"><?= $this->lang->line('w_image') ?></label>
										<select class="form-select" id="sl_aux_img">
											<option value="">--</option>
											<?php foreach($aux_images as $item){ ?>
											<option class="img_cat img_cat_<?= $item->category_id ?> d-none" value="<?= $item->id ?>"><?= $item->name ?></option>
											<?php } ?>
										</select>
									</div>
									<div class="col-md-auto">
										<label class="form-label d-md-block d-none">&nbsp;</label>
										<div>
											<button type="button" class="btn btn-primary" id="btn_add_img">
												<i class="bi bi-plus"></i>
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row mt-3">
							<div class="table-responsive">
								<table class="table">
									<thead>
										<tr>
											<th><?= $this->lang->line('w_category') ?></th>
											<th><?= $this->lang->line('w_image') ?></th>
											<th></th>
										</tr>
									</thead>
									<tbody id="tbody_images">
										<?php foreach($im as $item){ ?>
										<tr>
											<td><?= $item->category ?></td>
											<td><?= $item->name ?></td>
											<td class="text-end">
												<button type="button" class="btn btn-danger btn-sm btn_remove_image" value="<?= $item->image_id ?>">
													<i class="bi bi-trash"></i>
												</button>
											</td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="card process process_attention <?= $dnone ?>">
			<div class="card-body">
				<h5 class="card-title"><?= $this->lang->line('w_treatment') ?></h5>
				<ul class="nav nav-tabs nav-tabs-bordered" id="auxiliary_exams_tab" role="tablist">
					<li class="nav-item" role="presentation">
						<button class="nav-link active" id="treatments-1-tab" data-bs-toggle="tab" data-bs-target="#bordered-treatments-1" type="button" role="tab" aria-controls="treatments-1" aria-selected="true"><?= $this->lang->line('w_medicine') ?></button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="treatments-2-tab" data-bs-toggle="tab" data-bs-target="#bordered-treatments-2" type="button" role="tab" aria-controls="treatments-2" aria-selected="false" tabindex="-1"><?= $this->lang->line('w_physical_therapy') ?></button>
					</li>
				</ul>
				<div class="tab-content pt-4" id="auxiliary_exams_tab_content">
					<div class="tab-pane fade show active" id="bordered-treatments-1" role="tabpanel" aria-labelledby="treatments-1-tab">
						<div class="row">
							<div class="col-md-6">
								<form class="row g-3" id="form_add_medicine">
									<input type="hidden" name="appointment_id" value="<?= $appointment->id ?>">
									<div class="col-md-12">
										<strong><?= $this->lang->line('w_add_medicine') ?></strong>
									</div>
									<div class="col-md-12">
										<label class="form-label"><?= $this->lang->line('w_medicine') ?></label>
										<select class="form-select" name="medicine_id">
											<option value="">--</option>
											<?php foreach($medicines as $item){ ?>
											<option value="<?= $item->id ?>"><?= $item->name ?></option>
											<?php } ?>
										</select>
										<div class="sys_msg" id="md_medicine_msg"></div>
									</div>
									<div class="col-md-6">
										<label class="form-label"><?= $this->lang->line('w_quantity') ?></label>
										<input type="number" class="form-control" name="quantity" value="1">
										<div class="sys_msg" id="md_quantity_msg"></div>
									</div>
									<div class="col-md-6">
										<label class="form-label"><?= $this->lang->line('w_dose') ?></label>
										<select class="form-select" name="dose_id">
											<option value="">--</option>
											<?php $medicine_dose = $options["medicine_dose"];
											foreach($medicine_dose as $item){ ?>
											<option value="<?= $item->id ?>"><?= $item->description ?></option>
											<?php } ?>
										</select>
										<div class="sys_msg" id="md_quantity_msg"></div>
									</div>
									<div class="col-md-4">
										<label class="form-label"><?= $this->lang->line('w_application_way') ?></label>
										<select class="form-select" name="application_way_id">
											<option value="">--</option>
											<?php $application_way = $options["medicine_application_way"];
											foreach($application_way as $item){ ?>
											<option value="<?= $item->id ?>"><?= $item->description ?></option>
											<?php } ?>
										</select>
										<div class="sys_msg" id="md_via_application_msg"></div>
									</div>
									<div class="col-md-4">
										<label class="form-label"><?= $this->lang->line('w_frequency') ?></label>
										<select class="form-select" name="frequency_id">
											<option value="">--</option>
											<?php $medicine_frequency = $options["medicine_frequency"];
											foreach($medicine_frequency as $item){ ?>
											<option value="<?= $item->id ?>"><?= $item->description ?></option>
											<?php } ?>
										</select>
										<div class="sys_msg" id="md_frequency_msg"></div>
									</div>
									<div class="col-md-4">
										<label class="form-label"><?= $this->lang->line('w_duration') ?></label>
										<select class="form-select" name="duration_id">
											<option value="">--</option>
											<?php $medicine_duration = $options["medicine_duration"];
											foreach($medicine_duration as $item){ ?>
											<option value="<?= $item->id ?>"><?= $item->description ?></option>
											<?php } ?>
										</select>
										<div class="sys_msg" id="md_duration_msg"></div>
									</div>
									<div class="col-md-12 pt-3">
										<button type="submit" class="btn btn-primary">
											<?= $this->lang->line('btn_add') ?>
										</button>
										<div class="sys_msg" id="md_result_msg"></div>
									</div>
								</form>
							</div>
							<div class="col-md-6">
								<div class="mb-3"><strong><?= $this->lang->line('w_selected_medicines') ?></strong></div>
								<table class="table">
									<tbody id="selected_medicines">
										<?php foreach($me as $m){ ?>
										<tr>
											<td>
												<div><?= $m->medicine ?></div>
												<small><?= $m->sub_txt ?></small>
											</td>
											<?php if ($appointment->is_editable){ ?>
											<td class="text-end">
												<button type="button" class="btn btn-danger btn-sm btn_delete_medicine" value="<?= $m->id ?>">
													<i class="bi bi-dash"></i>
												</button>
											</td>
											<?php } ?>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="bordered-treatments-2" role="tabpanel" aria-labelledby="treatments-2-tab">
						<div class="row">
							<div class="col-md-6">
								<form class="row g-3" id="form_add_therapy">
									<input type="hidden" name="appointment_id" value="<?= $appointment->id ?>">
									<div class="col-md-12">
										<strong><?= $this->lang->line('w_add_therapy') ?></strong>
									</div>
									<div class="col-md-12">
										<label class="form-label"><?= $this->lang->line('w_therapy') ?></label>
										<select class="form-select" name="physical_therapy_id">
											<option value="">--</option>
											<?php foreach($physical_therapies as $item){ ?>
											<option value="<?= $item->id ?>"><?= $item->name ?></option>
											<?php } ?>
										</select>
										<div class="sys_msg" id="at_physical_therapy_msg"></div>
									</div>
									<div class="col-md-6">
										<label class="form-label"><?= $this->lang->line('w_session') ?></label>
										<input type="number" class="form-control" name="session" value="1" min="1">
										<div class="sys_msg" id="at_session_msg"></div>
									</div>
									<div class="col-md-6">
										<label class="form-label"><?= $this->lang->line('w_one_session_each') ?></label>
										<div class="input-group input-normal-o">
											<input type="number" class="form-control" name="frequency" value="1" min="1">
											<select class="form-select" name="frequency_unit">
												<option value="D"><?= $this->lang->line('w_day') ?></option>
												<option value="W"><?= $this->lang->line('w_week') ?></option>
												<option value="M"><?= $this->lang->line('w_month') ?></option>
												<option value="Y"><?= $this->lang->line('w_year') ?></option>
											</select>
										</div>
										<div class="sys_msg" id="at_frequency_msg"></div>
									</div>
									<div class="col-md-12 pt-3">
										<button type="submit" class="btn btn-primary">
											<?= $this->lang->line('btn_add') ?>
										</button>
										<div class="sys_msg" id="at_result_msg"></div>
									</div>
								</form>
							</div>
							<div class="col-md-6">
								<div class="mb-3"><strong><?= $this->lang->line('w_selected_therapies') ?></strong></div>
								<table class="table">
									<tbody id="selected_therapies">
										<?php foreach($th as $t){ ?>
										<tr>
											<td>
												<div><?= $t->physical_therapy ?></div>
												<small><?= $t->sub_txt ?></small>
											</td>
											<?php if ($appointment->is_editable){ ?>
											<td class="text-end">
												<button type="button" class="btn btn-danger btn-sm btn_delete_therapy" value="<?= $t->id ?>">
													<i class="bi bi-dash"></i>
												</button>
											</td>
											<?php } ?>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php if ($appointment->status->code === "confirmed"){ ?>
		<div class="card process process_attention <?= $dnone ?> text-center">
			<div class="card-body py-3">
				<button type="button" class="btn btn-success btn-lg" id="btn_finish" value="<?= $appointment->id ?>">
					<?= $this->lang->line('btn_finish_appointment') ?>
				</button>
			</div>
		</div>
		<?php }} ?>
	</div>
</div>
<?php }//end else block ?>
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