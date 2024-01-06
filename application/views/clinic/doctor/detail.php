<div class="pagetitle">
	<h1><?= $person->name ?></h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= $this->lang->line('w_home') ?></a></li>
			<li class="breadcrumb-item"><a href="<?= base_url() ?>clinic/doctor"><?= $this->lang->line('doctors') ?></a></li>
			<li class="breadcrumb-item active"><?= $this->lang->line('txt_detail') ?></li>
		</ol>
	</nav>
</div>
<div class="row">
	<?php if ($doctor->status->code === "enabled"){ ?>
	<div class="col-md-3">
		<button class="btn btn-primary w-100 mb-3 control_bl_simple" value="bl_ga">
			<div><i class="bi bi-clipboard2-pulse" style="font-size: 50px;"></i></div>
			<div class="fs-16 mt-2 pt-2 border-top border-white"><?= $this->lang->line('btn_generate_appointment') ?></div>
		</button>
	</div>
	<div class="col-md-3">
		<button class="btn btn-primary w-100 mb-3 control_bl_simple" value="bl_gs">
			<div><i class="bi bi-heart-pulse" style="font-size: 50px;"></i></div>
			<div class="fs-16 mt-2 pt-2 border-top border-white"><?= $this->lang->line('btn_generate_surgery') ?></div>
		</button>
	</div>
	<?php } ?>
	<div class="col-md-3">
		<button class="btn btn-success w-100 mb-3 control_bl_simple" id="btn_weekly_agenda" value="bl_bs">
			<div><i class="bi bi-journal" style="font-size: 50px;"></i></div>
			<div class="fs-16 mt-2 pt-2 border-top border-white"><?= $this->lang->line('btn_weekly_agenda') ?></div>
		</button>
	</div>
	<?php if ($doctor->status->code === "enabled"){ ?>
	<div class="col-md-3">
		<button class="btn btn-outline-danger w-100 mb-3" id="btn_deactivate" value="<?= $doctor->id ?>">
			<div><i class="bi bi-lightbulb-off" style="font-size: 50px;"></i></div>
			<div class="fs-16 mt-2 pt-2 border-top border-danger"><?= $this->lang->line('btn_deactivate_doctor') ?></div>
		</button>
	</div>
	<?php }else{ ?>
	<div class="col-md-3">
		<button class="btn btn-outline-success w-100 mb-3" id="btn_activate" value="<?= $doctor->id ?>">
			<div><i class="bi bi-lightbulb" style="font-size: 50px;"></i></div>
			<div class="fs-16 mt-2 pt-2 border-top border-success"><?= $this->lang->line('btn_activate_doctor') ?></div>
		</button>
	</div>
	<?php } ?>
</div>
<div class="row">
	<div class="col">
		<?php if ($doctor->status->code === "enabled"){ ?>
		<div class="card bl_simple d-none" id="bl_ga">
			<?php $this->load->view("clinic/appointment/form_add_appointment", ["patient" => null, "doctor" => $doctor]); ?>
		</div>
		<div class="card bl_simple d-none" id="bl_gs">
			<?php $this->load->view("clinic/surgery/form_add_surgery", ["patient" => null, "doctor" => $doctor]); ?>
		</div>
		<?php } ?>
		<div class="card bl_simple d-none" id="bl_bs">
			<div class="card-body">
				<h5 class="card-title"><?= $this->lang->line('w_weekly_agenda') ?></h5>
				<div id="bl_weekly_agenda"></div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col">
		<div class="card">
			<div class="card-body" style="min-height: 500px;">
				<h5 class="card-title"><?= $this->lang->line('w_records') ?></h5>
				<ul class="nav nav-tabs nav-tabs-bordered" role="tablist">
					<li class="nav-item" role="presentation">
						<button class="nav-link active" id="information-tab" data-bs-toggle="tab" data-bs-target="#bordered-information" type="button" role="tab" aria-controls="information" aria-selected="true"><?= $this->lang->line('w_information') ?></button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="appointments-tab" data-bs-toggle="tab" data-bs-target="#bordered-appointments" type="button" role="tab" aria-controls="appointments" aria-selected="false" tabindex="-1"><?= $this->lang->line('w_appointments') ?></button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="surgeries-tab" data-bs-toggle="tab" data-bs-target="#bordered-surgeries" type="button" role="tab" aria-controls="surgeries" aria-selected="false" tabindex="-1"><?= $this->lang->line('w_surgeries') ?></button>
					</li>
				</ul>
				<div class="tab-content pt-3">
					<div class="tab-pane fade show active" id="bordered-information" role="tabpanel" aria-labelledby="information-tab">
						<form class="row g-3" id="form_update_info">
							<input type="hidden" name="p[id]" value="<?= $person->id ?>">
							<input type="hidden" name="d[id]" value="<?= $doctor->id ?>">
							<div class="col-md-3">
								<label class="form-label"><?= $this->lang->line('w_document') ?></label>
								<select class="form-select" id="du_doc_type_id" name="p[doc_type_id]" disabled>
									<?php foreach($doc_types as $d){ if ($d->sunat_code){ 
									if ($person->doc_type_id == $d->id) $s = "selected"; else $s = ""; ?>
									<option value="<?= $d->id ?>" <?= $s ?>><?= $d->description ?></option>
									<?php }} ?>
								</select>
								<div class="sys_msg" id="du_doc_type_msg"></div>
							</div>
							<div class="col-md-3">
								<label class="form-label d-md-block d-none">&nbsp;</label>
								<input type="text" class="form-control" id="du_doc_number" name="p[doc_number]" placeholder="<?= $this->lang->line('w_number') ?>" value="<?= $person->doc_number ?>" readonly>
								<div class="sys_msg" id="du_doc_number_msg"></div>
							</div>
							<div class="col-md-6">
								<label class="form-label"><?= $this->lang->line('w_name') ?></label>
								<input type="text" class="form-control" id="du_name" name="p[name]" value="<?= $person->name ?>" readonly>
								<div class="sys_msg" id="du_name_msg"></div>
							</div>
							<div class="col-md-3">
								<label class="form-label"><?= $this->lang->line('w_specialty') ?></label>
								<select class="form-select" name="d[specialty_id]" disabled>
									<option value="" selected><?= $this->lang->line('w_select') ?>...</option>
									<?php foreach($specialties as $item){ 
									if ($doctor->specialty_id == $item->id) $s = "selected"; else $s = ""; ?>
									<option value="<?= $item->id ?>" <?= $s ?>><?= $item->name ?></option>
									<?php } ?>
								</select>
								<div class="sys_msg" id="du_specialty_msg"></div>
							</div>
							<div class="col-md-3">
								<label class="form-label"><?= $this->lang->line('w_license_number') ?></label>
								<input type="text" class="form-control" name="d[license]" value="<?= $doctor->license ?>" readonly>
								<div class="sys_msg" id="du_license_msg"></div>
							</div>
							<div class="col-md-6">
								<label class="form-label"><?= $this->lang->line('w_email') ?></label>
								<input type="email" class="form-control" name="p[email]" placeholder="email@example.com" value="<?= $person->email ?>" readonly>
								<div class="sys_msg" id="du_email_msg"></div>
							</div>
							<div class="col-md-4">
								<label class="form-label"><?= $this->lang->line('w_tel') ?></label>
								<input type="text" class="form-control" id="du_tel" name="p[tel]" value="<?= $person->tel ?>" readonly>
								<div class="sys_msg" id="du_tel_msg"></div>
							</div>
							<div class="col-md-4">
								<?php
								if ($person->birthday){
									$aux = strtotime($person->birthday);
									$b = date("Y-m-d", $aux);
									$d = (int)date("d", $aux); $m = (int)date("m", $aux); $y = (int)date("Y", $aux);	
								}else $b = $d = $m = $y = null;
								?>
								<label class="form-label"><?= $this->lang->line('w_birthday') ?></label>
								<input type="hidden" id="p_birthday" name="p[birthday]" value="<?= $b ?>" readonly>
								<div class="input-group">
									<select class="form-select" id="p_birthday_d" disabled>
										<option value="" selected=""><?= $this->lang->line('date_d') ?></option>
										<?php for($i = 1; $i <= 31; $i++){
										if ($i == $d) $s = "selected"; else $s = ""; ?>
										<option value="<?= $i ?>" <?= $s ?>><?= $i ?></option>
										<?php } ?>
									</select>
									<select class="form-select" id="p_birthday_m" disabled>
										<option value="" selected=""><?= $this->lang->line('date_m') ?></option>
										<?php for($i = 1; $i <= 12; $i++){
										if ($i == $m) $s = "selected"; else $s = ""; ?>
										<option value="<?= $i ?>" <?= $s ?>><?= $i ?></option>
										<?php } ?>
									</select>
									<?php $now = date('Y'); ?>
									<select class="form-select" id="p_birthday_y" disabled>
										<option value="" selected=""><?= $this->lang->line('date_y') ?></option>
										<?php for($i = 0; $i <= 130; $i++){ $aux = $now - $i;
										if ($aux == $y) $s = "selected"; else $s = ""; ?>
										<option value="<?= $aux ?>" <?= $s ?>><?= $aux ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="sys_msg" id="du_birthday_msg"></div>
							</div>
							<div class="col-md-2">
								<label class="form-label"><?= $this->lang->line('w_sex') ?></label>
								<select class="form-select" name="p[sex_id]" disabled>
									<option value="" selected="">--</option>
									<?php foreach($sex_ops as $item){ 
									if ($person->sex_id == $item->id) $s = "selected"; else $s = ""; ?>
									<option value="<?= $item->id ?>" <?= $s ?>><?= $item->description ?></option>
									<?php } ?>
								</select>
								<div class="sys_msg" id="du_sex_msg"></div>
							</div>
							<div class="col-md-2">
								<label class="form-label"><?= $this->lang->line('w_blood_type') ?></label>
								<select class="form-select" name="p[blood_type_id]" disabled>
									<option value="" selected="">--</option>
									<?php foreach($blood_type_ops as $item){ 
									if ($person->blood_type_id == $item->id) $s = "selected"; else $s = ""; ?>
									<option value="<?= $item->id ?>" <?= $s ?>><?= $item->description ?></option>
									<?php } ?>
								</select>
								<div class="sys_msg" id="du_blood_type_msg"></div>
							</div>
							<div class="col-md-12">
								<label class="form-label"><?= $this->lang->line('w_address') ?></label>
								<input type="text" class="form-control" name="p[address]" value="<?= $person->address ?>" readonly>
								<div class="sys_msg" id="du_address_msg"></div>
							</div>
							<div class="col-md-12 pt-3">
								<button type="button" class="btn btn-primary" id="btn_update_info">
									<?= $this->lang->line('btn_update') ?>
								</button>
								<button type="submit" class="btn btn-primary d-none" id="btn_update_confirm" disabled>
									<?= $this->lang->line('btn_confirm') ?>
								</button>
								<button type="button" class="btn btn-danger light d-none" id="btn_update_cancel" disabled>
									<?= $this->lang->line('btn_cancel') ?>
								</button>
							</div>
						</form>
					</div>
					<div class="tab-pane fade" id="bordered-appointments" role="tabpanel" aria-labelledby="appointments-tab">
						<?php if ($appointments){ ?>
						<div class="table-responsive">
							<table class="table datatable">
								<thead>
									<tr>
										<th><?= $this->lang->line('w_date') ?></th>
										<th><?= $this->lang->line('w_time') ?></th>
										<th><?= $this->lang->line('w_patient') ?></th>
										<th><?= $this->lang->line('w_status') ?></th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($appointments as $item){ ?>
									<tr>
										<td>
											<?= date("Y-m-d", strtotime($item->schedule_from)) ?>
										</td>
										<td>
											<div class="text-nowrap">
												<?= date("h:i a", strtotime($item->schedule_from)) ?>
											</div>
										</td>
										<td><?= $patient_arr[$item->patient_id] ?></td>
										<td>
											<span class="text-<?= $status_arr[$item->status_id]->color ?>"><?= $this->lang->line($status_arr[$item->status_id]->code) ?></span>
										</td>
										<td class="text-right">
											<a href="<?= base_url() ?>clinic/appointment/detail/<?= $item->id ?>" class="btn btn-primary btn-sm">
												<i class="bi bi-search"></i>
											</a>
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
						<?php }else{ ?>
						<div>
							<?= $this->lang->line('t_no_appointment') ?>
						</div>
						<?php } ?>
					</div>
					<div class="tab-pane fade" id="bordered-surgeries" role="tabpanel" aria-labelledby="surgeries-tab">
						<?php if ($surgeries){ ?>
						<div class="table-responsive">
							<table class="table datatable">
								<thead>
									<tr>
										<th class="pl-0"><?= $this->lang->line('w_date') ?></th>
										<th><?= $this->lang->line('w_start') ?></th>
										<th><?= $this->lang->line('w_end') ?></th>
										<th><?= $this->lang->line('w_room') ?></th>
										<th><?= $this->lang->line('w_patient') ?></th>
										<th><?= $this->lang->line('w_status') ?></th>
										<th class="text-right pr-0"></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($surgeries as $item){ ?>
									<tr>
										<td class="pl-0"><?= date("Y-m-d", strtotime($item->schedule_from)) ?></td>
										<td>
											<div class="text-nowrap"><?= date("h:i a", strtotime($item->schedule_from)) ?></div>
										</td>
										<td>
											<div class="text-nowrap"><?= date("h:i a", strtotime($item->schedule_to)) ?></div>
										</td>
										<td><?= $rooms_arr[$item->room_id] ?></td>
										<td><?= $patient_arr[$item->patient_id] ?></td>
										<td class="text-<?= $status_arr[$item->status_id]->color ?>">
											<?= $this->lang->line($status_arr[$item->status_id]->code) ?>
										</td>
										<td class="text-right pr-0">
											<a href="<?= base_url() ?>clinic/surgery/detail/<?= $item->id ?>" class="btn btn-primary">
												<i class="bi bi-search"></i>
											</a>
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
						<?php }else{ ?>
						<div class="col-md-12">
							<?= $this->lang->line('t_no_surgery') ?>
						</div>
						<?php } ?>
					</div>
				</div>
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
<input type="hidden" id="doctor_id" value="<?= $person->id ?>">