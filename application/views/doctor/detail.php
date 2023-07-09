<div class="col-md-12">
	<div class="row page-titles mx-0">
		<div class="col-sm-6 p-md-0">
			<div class="welcome-text">
				<h3><?= $person->name ?></h3>
			</div>
		</div>
		<div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?= base_url() ?>doctor"><?= $this->lang->line('doctors') ?></a></li>
				<li class="breadcrumb-item active"><a href="javascript:void(0)"><?= $this->lang->line('txt_detail') ?></a></li>
			</ol>
		</div>
	</div>
</div>
<div class="col-md-12">
	<div class="row d-flex justify-content-center">
		<div class="col-md-3">
			<button class="btn btn-primary w-100 mb-3 control_bl_simple" value="bl_ga">
				<div><i class="fal fa-notes-medical fa-5x fa-fw"></i></div>
				<div class="fs-16 mt-2 pt-2 border-top border-white"><?= $this->lang->line('btn_generate_appointment') ?></div>
			</button>
		</div>
		<div class="col-md-3">
			<button class="btn btn-primary w-100 mb-3 control_bl_simple" value="bl_gs">
				<div><i class="fal fa-file-medical-alt fa-5x fa-fw"></i></div>
				<div class="fs-16 mt-2 pt-2 border-top border-white"><?= $this->lang->line('btn_generate_surgery') ?></div>
			</button>
		</div>
		<div class="col-md-3">
			<button class="btn btn-info w-100 mb-3 control_bl_simple" id="btn_weekly_agenda" value="bl_bs">
				<div><i class="fal fa-clock fa-5x fa-fw"></i></div>
				<div class="fs-16 mt-2 pt-2 border-top border-white"><?= $this->lang->line('btn_weekly_agenda') ?></div>
			</button>
		</div>
		<?php if ($doctor->status->code === "enabled"){ ?>
		<div class="col-md-3">
			<button class="btn btn-outline-danger w-100 mb-3" id="btn_deactivate" value="<?= $doctor->id ?>">
				<div><i class="fal fa-power-off fa-5x fa-fw"></i></div>
				<div class="fs-16 mt-2 pt-2 border-top border-danger"><?= $this->lang->line('btn_deactivate_doctor') ?></div>
			</button>
		</div>
		<?php }else{ ?>
		<div class="col-md-3">
			<button class="btn btn-outline-success w-100 mb-3" id="btn_activate" value="<?= $doctor->id ?>">
				<div><i class="fal fa-power-off fa-5x fa-fw"></i></div>
				<div class="fs-16 mt-2 pt-2 border-top border-success"><?= $this->lang->line('btn_activate_doctor') ?></div>
			</button>
		</div>
		<?php } ?>
	</div>
</div>
<div class="col-md-12 bl_simple d-none" id="bl_ga">
	<div class="card">
		<div class="card-header">
			<h4 class="mb-0"><?= $this->lang->line('title_generate_appointment') ?></h4>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col-md-6">
					<form action="#" id="app_register_form">
						<h5 class="mb-3"><?= $this->lang->line('title_attention') ?></h5>
						<div class="form-row">
							<div class="form-group col-md-12">
								<label><?= $this->lang->line('lb_specialty') ?></label>
								<input type="hidden" value="<?= $doctor->specialty_id ?>" name="app[specialty_id]" readonly>
								<input type="text" class="form-control" value="<?= $doctor->specialty ?>" readonly>
								<div class="sys_msg" id="aa_specialty_msg"></div>
							</div>
							<div class="form-group col-md-12">
								<label><?= $this->lang->line('lb_doctor') ?></label>
								<input type="hidden" value="<?= $person->id ?>" id="aa_doctor_id" name="app[doctor_id]" readonly>
								<input type="text" class="form-control" value="<?= $person->name ?>" readonly>
								<div class="sys_msg" id="aa_doctor_msg"></div>
							</div>
							<div class="form-group col-md-6">
								<label><?= $this->lang->line('lb_date') ?></label>
								<input type="text" class="form-control bw date_picker" id="aa_date" name="sch[date]" value="<?= date('Y-m-d') ?>" readonly>
								<div class="sys_msg" id="aa_date_msg"></div>
							</div>
							<div class="form-group col-md-6">
								<label><?= $this->lang->line('lb_time') ?></label>
								<div class="d-flex justify-content-between">
									<select class="form-control text-center px-0" id="aa_hour" name="sch[hour]">
										<option value="" selected>--</option>
										<?php for($i = 9; $i <= 18; $i++){ if ($i < 12) $pre = "AM"; else $pre = "PM"; ?>
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
									<select class="form-control text-center px-0" id="aa_min" name="sch[min]">
										<option value="" selected>--</option>
										<option value="00">00</option>
										<option value="15">15</option>
										<option value="30">30</option>
										<option value="45">45</option>
									</select>
								</div>
								<div class="sys_msg" id="aa_schedule_msg"></div>
							</div>
						</div>
						<h5 class="my-3"><?= $this->lang->line('lb_patient') ?></h5>
						<input type="hidden" id="aa_pt_id" name="app[patient_id]" value="">
						<div class="form-row">
							<div class="form-group col-md-6">
								<label><?= $this->lang->line('lb_document') ?></label>
								<select class="form-control" id="aa_pt_doc_type_id" name="pt[doc_type_id]">
									<?php foreach($doc_types as $item){ if ($item->sunat_code){ ?>
									<option value="<?= $item->id ?>"><?= $item->description ?></option>
									<?php }} ?>
								</select>
								<div class="sys_msg" id="aa_pt_doc_type_msg"></div>
							</div>
							<div class="form-group col-md-6">
								<label class="d-md-block d-none">&nbsp;</label>
								<div class="input-group">
									<input type="text" class="form-control" id="aa_pt_doc_number" name="pt[doc_number]" placeholder="<?= $this->lang->line('txt_number') ?>">
									<div class="input-group-append">
										<button class="btn btn-primary border-0" type="button" id="btn_aa_search_pt">
											<i class="fas fa-search"></i>
										</button>
									</div>
								</div>
								<div class="sys_msg" id="aa_pt_doc_number_msg"></div>
							</div>
							<div class="form-group col-md-6">
								<label><?= $this->lang->line('lb_name') ?></label>
								<input type="text" class="form-control" id="aa_pt_name" name="pt[name]">
								<div class="sys_msg" id="aa_pt_name_msg"></div>
							</div>
							<div class="form-group col-md-6">
								<label><?= $this->lang->line('lb_tel') ?></label>
								<input type="text" class="form-control" id="aa_pt_tel" name="pt[tel]">
								<div class="sys_msg" id="aa_pt_tel_msg"></div>
							</div>
							<div class="form-group col-md-12">
								<label><?= $this->lang->line('lb_remark') ?> (<?= $this->lang->line('lb_optional') ?>)</label>
								<textarea class="form-control" rows="4" name="app[remark]" placeholder="<?= $this->lang->line('txt_remark') ?>"></textarea>
							</div>
							<div class="form-group col-md-12 pt-3 mb-0">
								<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_register') ?></button>
							</div>
						</div>
					</form>
				</div>
				<div class="col-md-6">
					<h5 class="mb-3"><?= $this->lang->line('title_doctor_agenda') ?></h5>
					<div id="aa_schedule"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="col-md-12 bl_simple d-none" id="bl_gs">
	<div class="card">
		<div class="card-header">
			<h4 class="mb-0"><?= $this->lang->line('title_generate_surgery') ?></h4>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col-md-6 mb-3">
					<form action="#" id="sur_register_form">
						<h5 class="mb-3"><?= $this->lang->line('title_attention') ?></h5>
						<div class="form-row">
							<div class="form-group col-md-12">
								<label><?= $this->lang->line('lb_specialty') ?></label>
								<input type="hidden" value="<?= $doctor->specialty_id ?>" name="sur[specialty_id]" readonly>
								<input type="text" class="form-control" value="<?= $doctor->specialty ?>" readonly>
								<div class="sys_msg" id="sur_specialty_msg"></div>
							</div>
							<div class="form-group col-md-12">
								<label><?= $this->lang->line('lb_doctor') ?></label>
								<input type="hidden" value="<?= $person->id ?>" id="sur_doctor_id" name="sur[doctor_id]" readonly>
								<input type="text" class="form-control" value="<?= $person->name ?>" readonly>
								<div class="sys_msg" id="sur_doctor_msg"></div>
							</div>
							<div class="form-group col-md-6">
								<label><?= $this->lang->line('lb_date') ?></label>
								<input type="text" class="form-control bw date_picker" id="sur_date" name="sch[date]" value="<?= date('Y-m-d') ?>" readonly>
								<div class="sys_msg" id="sur_date_msg"></div>
							</div>
							<div class="form-group col-md-6">
								<label><?= $this->lang->line('lb_time') ?></label>
								<div class="d-flex justify-content-between">
									<select class="form-control text-center px-0" id="sur_hour" name="sch[hour]">
										<option value="" selected>--</option>
										<?php for($i = 9; $i <= 18; $i++){ if ($i < 12) $pre = "AM"; else $pre = "PM"; ?>
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
									<select class="form-control text-center px-0" id="sur_min" name="sch[min]">
										<option value="" selected>--</option>
										<option value="00">00</option>
										<option value="15">15</option>
										<option value="30">30</option>
										<option value="45">45</option>
									</select>
								</div>
								<div class="sys_msg" id="sur_schedule_msg"></div>
							</div>
							<div class="form-group col-md-8">
								<label>
									<span class="mr-1"><?= $this->lang->line('lb_room') ?></span>
									<span><i class="far fa-clock" id="ic_room_availability_w" data-toggle="modal" data-target=".md_weekly_room_availability"></i></span>
								</label>
								<select class="form-control" name="sur[room_id]" id="sur_room_id">
									<option value="">--</option>
									<?php foreach($rooms as $r){ ?>
									<option value="<?= $r->id ?>"><?= $r->name ?></option>
									<?php } ?>
								</select>
								<div class="sys_msg" id="sur_room_msg"></div>
							</div>
							<div class="form-group col-md-4">
								<label><?= $this->lang->line('lb_duration') ?></label>
								<select class="form-control" name="sch[duration]">
									<option value="">--</option>
									<?php foreach($duration_ops as $op){ ?>
									<option value="<?= $op["value"] ?>"><?= $op["txt"] ?></option>
									<?php } ?>
								</select>
								<div class="sys_msg" id="sur_duration_msg"></div>
							</div>
						</div>
						<h5 class="my-3"><?= $this->lang->line('lb_patient') ?></h5>
						<input type="hidden" id="sur_pt_id" name="sur[patient_id]" value="">
						<div class="form-row">
							<div class="form-group col-md-6">
								<label><?= $this->lang->line('lb_document') ?></label>
								<select class="form-control" id="sur_pt_doc_type_id" name="pt[doc_type_id]">
									<?php foreach($doc_types as $item){ if ($item->sunat_code){ ?>
									<option value="<?= $item->id ?>"><?= $item->description ?></option>
									<?php }} ?>
								</select>
								<div class="sys_msg" id="sur_pt_doc_type_msg"></div>
							</div>
							<div class="form-group col-md-6">
								<label class="d-md-block d-none">&nbsp;</label>
								<div class="input-group">
									<input type="text" class="form-control" id="sur_pt_doc_number" name="pt[doc_number]" placeholder="<?= $this->lang->line('txt_number') ?>">
									<div class="input-group-append">
										<button class="btn btn-primary border-0" type="button" id="btn_sur_search_pt">
											<i class="fas fa-search"></i>
										</button>
									</div>
								</div>
								<div class="sys_msg" id="sur_pt_doc_number_msg"></div>
							</div>
							<div class="form-group col-md-6">
								<label><?= $this->lang->line('lb_name') ?></label>
								<input type="text" class="form-control" id="sur_pt_name" name="pt[name]">
								<div class="sys_msg" id="sur_pt_name_msg"></div>
							</div>
							<div class="form-group col-md-6">
								<label><?= $this->lang->line('lb_tel') ?></label>
								<input type="text" class="form-control" id="sur_pt_tel" name="pt[tel]">
								<div class="sys_msg" id="sur_pt_tel_msg"></div>
							</div>
							<div class="form-group col-md-12">
								<label><?= $this->lang->line('lb_remark') ?> (<?= $this->lang->line('lb_optional') ?>)</label>
								<textarea class="form-control" rows="4" name="sur[remark]" placeholder="<?= $this->lang->line('txt_remark') ?>"></textarea>
							</div>
							<div class="form-group col-md-12 pt-3">
								<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_register') ?></button>
							</div>
						</div>
					</form>
				</div>
				<div class="col-md-6 mb-3">
					<h5 class="mb-3"><?= $this->lang->line('title_doctor_agenda') ?></h5>
					<div id="sur_schedule_list"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="col-md-12 bl_simple d-none" id="bl_bs">
	<div class="card">
		<div class="card-header">
			<h4 class="mb-0"><?= $this->lang->line('title_weekly_agenda') ?></h4>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col-md-12" id="bl_weekly_agenda"></div>
			</div>
		</div>
	</div>
</div>
<div class="col-md-12">
	<div class="card">
		<div class="card-header">
			<h4 class="mb-0"><?= $this->lang->line('title_records') ?></h4>
		</div>
		<div class="card-body" style="min-height: 500px;">
			<!-- Nav tabs -->
			<div class="custom-tab-1">
				<ul class="nav nav-tabs mb-4">
					<li class="nav-item">
						<a class="nav-link active" data-toggle="tab" href="#information">
							<i class="far fa-comment-alt mr-3"></i><span><?= $this->lang->line('tab_information') ?></span>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#appointments">
							<i class="far fa-notes-medical mr-3"></i><span><?= $this->lang->line('tab_appointments') ?></span>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#surgeries">
							<i class="far fa-file-medical-alt mr-3"></i><span><?= $this->lang->line('tab_surgeries') ?></span>
						</a>
					</li>
				</ul>
				<div class="tab-content">	
					<div class="tab-pane fade active show" id="information" role="tabpanel">
						<form id="form_update_info">
							<input type="hidden" name="p[id]" value="<?= $person->id ?>">
							<input type="hidden" name="d[id]" value="<?= $doctor->id ?>">
							<div class="form-row mb-3">
								<div class="form-group col-md-3">
									<label><?= $this->lang->line('lb_document') ?></label>
									<select class="form-control" id="du_doc_type_id" name="p[doc_type_id]" disabled>
										<?php foreach($doc_types as $d){ if ($d->sunat_code){ 
										if ($person->doc_type_id == $d->id) $s = "selected"; else $s = ""; ?>
										<option value="<?= $d->id ?>" <?= $s ?>><?= $d->description ?></option>
										<?php }} ?>
									</select>
									<div class="sys_msg" id="du_doc_type_msg"></div>
								</div>
								<div class="form-group col-md-3">
									<label class="d-md-block d-none">&nbsp;</label>
									<input type="text" class="form-control" id="du_doc_number" name="p[doc_number]" placeholder="<?= $this->lang->line('lb_number') ?>" value="<?= $person->doc_number ?>" readonly>
									<div class="sys_msg" id="du_doc_number_msg"></div>
								</div>
								<div class="form-group col-md-6">
									<label><?= $this->lang->line('lb_name') ?></label>
									<input type="text" class="form-control" id="du_name" name="p[name]" value="<?= $person->name ?>" readonly>
									<div class="sys_msg" id="du_name_msg"></div>
								</div>
								<div class="form-group col-md-3">
									<label><?= $this->lang->line('lb_tel') ?></label>
									<input type="text" class="form-control" id="du_tel" name="p[tel]" value="<?= $person->tel ?>" readonly>
									<div class="sys_msg" id="du_tel_msg"></div>
								</div>
								<div class="form-group col-md-3">
									<?php
									if ($person->birthday){
										$aux = strtotime($person->birthday);
										$b = date("Y-m-d", $aux);
										$d = (int)date("d", $aux); $m = (int)date("m", $aux); $y = (int)date("Y", $aux);	
									}else $b = $d = $m = $y = null;
									?>
									<label><?= $this->lang->line('lb_birthday') ?></label>
									<input type="hidden" id="p_birthday" name="p[birthday]" value="<?= $b ?>" readonly>
									<div class="input-group">
										<select class="form-control" id="p_birthday_d" disabled>
											<option value="" selected=""><?= $this->lang->line('date_d') ?></option>
											<?php for($i = 1; $i <= 31; $i++){
											if ($i == $d) $s = "selected"; else $s = ""; ?>
											<option value="<?= $i ?>" <?= $s ?>><?= $i ?></option>
											<?php } ?>
										</select>
										<select class="form-control" id="p_birthday_m" disabled>
											<option value="" selected=""><?= $this->lang->line('date_m') ?></option>
											<?php for($i = 1; $i <= 12; $i++){
											if ($i == $m) $s = "selected"; else $s = ""; ?>
											<option value="<?= $i ?>" <?= $s ?>><?= $i ?></option>
											<?php } ?>
										</select>
										<?php $now = date('Y'); ?>
										<select class="form-control" id="p_birthday_y" disabled>
											<option value="" selected=""><?= $this->lang->line('date_y') ?></option>
											<?php for($i = 0; $i <= 130; $i++){ $aux = $now - $i;
											if ($aux == $y) $s = "selected"; else $s = ""; ?>
											<option value="<?= $aux ?>" <?= $s ?>><?= $aux ?></option>
											<?php } ?>
										</select>
									</div>
									<div class="sys_msg" id="du_birthday_msg"></div>
								</div>
								<div class="form-group col-md-3">
									<label><?= $this->lang->line('lb_sex') ?></label>
									<select class="form-control" name="p[sex_id]" disabled>
										<option value="" selected="">--</option>
										<?php foreach($sex_ops as $item){ 
										if ($person->sex_id == $item->id) $s = "selected"; else $s = ""; ?>
										<option value="<?= $item->id ?>" <?= $s ?>><?= $item->description ?></option>
										<?php } ?>
									</select>
									<div class="sys_msg" id="du_sex_msg"></div>
								</div>
								<div class="form-group col-md-3">
									<label><?= $this->lang->line('lb_blood_type') ?></label>
									<select class="form-control" name="p[blood_type_id]" disabled>
										<option value="" selected="">--</option>
										<?php foreach($blood_type_ops as $item){ 
										if ($person->blood_type_id == $item->id) $s = "selected"; else $s = ""; ?>
										<option value="<?= $item->id ?>" <?= $s ?>><?= $item->description ?></option>
										<?php } ?>
									</select>
									<div class="sys_msg" id="du_blood_type_msg"></div>
								</div>
								<div class="form-group col-md-6">
									<label><?= $this->lang->line('lb_email') ?></label>
									<input type="email" class="form-control" name="p[email]" placeholder="email@example.com" value="<?= $person->email ?>" readonly>
									<div class="sys_msg" id="du_email_msg"></div>
								</div>
								<div class="form-group col-md-3">
									<label><?= $this->lang->line('lb_specialty') ?></label>
									<select class="form-control" name="d[specialty_id]" disabled>
										<option value="" selected><?= $this->lang->line('text_select') ?>...</option>
										<?php foreach($specialties as $item){ 
										if ($doctor->specialty_id == $item->id) $s = "selected"; else $s = ""; ?>
										<option value="<?= $item->id ?>" <?= $s ?>><?= $item->name ?></option>
										<?php } ?>
									</select>
									<div class="sys_msg" id="du_specialty_msg"></div>
								</div>
								<div class="form-group col-md-3">
									<label><?= $this->lang->line('lb_license_number') ?></label>
									<input type="text" class="form-control" name="d[license]" value="<?= $doctor->license ?>" readonly>
									<div class="sys_msg" id="du_license_msg"></div>
								</div>
								<div class="form-group col-md-12">
									<label><?= $this->lang->line('lb_address') ?></label>
									<input type="text" class="form-control" name="p[address]" value="<?= $person->address ?>" readonly>
									<div class="sys_msg" id="du_address_msg"></div>
								</div>
							</div>
							<button type="button" class="btn btn-primary" id="btn_update_info">
								<?= $this->lang->line('btn_update') ?>
							</button>
							<button type="submit" class="btn btn-primary d-none" id="btn_update_confirm" disabled>
								<?= $this->lang->line('btn_confirm') ?>
							</button>
							<button type="button" class="btn btn-danger light d-none" id="btn_update_cancel" disabled>
								<?= $this->lang->line('btn_cancel') ?>
							</button>
						</form>
					</div>
					<div class="tab-pane fade" id="appointments" role="tabpanel">
						<div class="row">
							<?php if ($appointments){ ?>
							<div class="col-md-2">
								<div class="mb-3" id="appointment_list_length_new"></div>
							</div>
							<div class="col-md-6"></div>
							<div class="col-md-4">
								<div class="mb-3" id="appointment_list_filter_new"></div>
							</div>
							<div class="col-md-12">
								<div class="table-responsive">
									<table id="appointment_list" class="display">
										<thead>
											<tr>
												<th class="pt-0 pl-0"><?= $this->lang->line('hd_date') ?></th>
												<th class="pt-0"><?= $this->lang->line('hd_time') ?></th>
												<th class="pt-0"><?= $this->lang->line('hd_patient') ?></th>
												<th class="pt-0"><?= $this->lang->line('hd_status') ?></th>
												<th class="text-right pt-0 pr-0"></th>
											</tr>
										</thead>
										<tbody>
											<?php foreach($appointments as $item){ ?>
											<tr>
												<td class="pl-0">
													<?= date("Y-m-d", strtotime($item->schedule_from)) ?>
												</td>
												<td>
													<div class="text-nowrap"><?= date("h:i A", strtotime($item->schedule_from))." - ".date("h:i A", strtotime($item->schedule_to)) ?></div>
												</td>
												<td><?= $patient_arr[$item->patient_id] ?></td>
												<td class="text-<?= $status_arr[$item->status_id]->color ?>">
													<?= $this->lang->line($status_arr[$item->status_id]->code) ?>
												</td>
												<td class="text-right pr-0">
													<a href="<?= base_url() ?>appointment/detail/<?= $item->id ?>">
														<button class="btn btn-primary light sharp border-0">
															<i class="fas fa-search"></i>
														</button>
													</a>
												</td>
											</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
							</div>
							<?php }else{ ?>
							<div class="col-md-12">
								<?= $this->lang->line('msg_no_appointment') ?>
							</div>
							<?php } ?>
						</div>
					</div>
					<div class="tab-pane fade" id="surgeries" role="tabpanel">
						<div class="row">
							<?php if ($surgeries){ ?>
							<div class="col-md-2">
								<div class="mb-3" id="surgery_list_length_new"></div>
							</div>
							<div class="col-md-6"></div>
							<div class="col-md-4">
								<div class="mb-3" id="surgery_list_filter_new"></div>
							</div>
							<div class="col-md-12">
								<div class="table-responsive">
									<table id="surgery_list" class="display">
										<thead>
											<tr>
												<th class="pt-0 pl-0"><?= $this->lang->line('hd_date') ?></th>
												<th class="pt-0"><?= $this->lang->line('hd_time') ?></th>
												<th class="pt-0"><?= $this->lang->line('hd_room') ?></th>
												<th class="pt-0"><?= $this->lang->line('hd_patient') ?></th>
												<th class="pt-0"><?= $this->lang->line('hd_status') ?></th>
												<th class="text-right pt-0 pr-0"></th>
											</tr>
										</thead>
										<tbody>
											<?php foreach($surgeries as $item){ ?>
											<tr>
												<td class="pl-0"><?= date("Y-m-d", strtotime($item->schedule_from)) ?></td>
												<td>
													<div class="text-nowrap"><?= date("h:i A", strtotime($item->schedule_from))." - ".date("h:i A", strtotime($item->schedule_to)) ?></div>
												</td>
												<td><?= $rooms_arr[$item->room_id] ?></td>
												<td><?= $patient_arr[$item->patient_id] ?></td>
												<td class="text-<?= $status_arr[$item->status_id]->color ?>">
													<?= $this->lang->line($status_arr[$item->status_id]->code) ?>
												</td>
												<td class="text-right pr-0">
													<a href="<?= base_url() ?>surgery/detail/<?= $item->id ?>">
														<button class="btn btn-primary light sharp border-0">
															<i class="fas fa-search"></i>
														</button>
													</a>
												</td>
											</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
							</div>
							<?php }else{ ?>
							<div class="col-md-12">
								<?= $this->lang->line('msg_no_surgery') ?>
							</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
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
	<input type="hidden" id="doctor_id" value="<?= $person->id ?>">
	<input type="hidden" id="warning_ddo" value="<?= $this->lang->line('warning_ddo') ?>">
	<input type="hidden" id="warning_ado" value="<?= $this->lang->line('warning_ado') ?>">
</div>