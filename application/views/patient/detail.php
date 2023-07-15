<div class="col-md-12">
	<div class="row page-titles mx-0">
		<div class="col-md-8 p-md-0">
			<div class="welcome-text">
				<h3><?= $person->name ?></h3>
			</div>
		</div>
		<div class="col-md-4 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?= base_url() ?>patient"><?= $this->lang->line('patients') ?></a></li>
				<li class="breadcrumb-item active"><a href="javascript:void(0)"><?= $this->lang->line('txt_detail') ?></a></li>
			</ol>
		</div>
	</div>
</div>
<?php if ($person->doc_number){ ?>
<div class="col-md-12">
	<div class="row d-flex justify-content-center">
		<div class="col-md-4">
			<button class="btn btn-primary w-100 mb-3 control_bl_simple" value="bl_ga">
				<div><i class="fal fa-notes-medical fa-5x fa-fw"></i></div>
				<div class="fs-16 mt-2 pt-2 border-top border-white"><?= $this->lang->line('btn_generate_appointment') ?></div>
			</button>
		</div>
		<div class="col-md-4">
			<button class="btn btn-primary w-100 mb-3 control_bl_simple" value="bl_gs">
				<div><i class="fal fa-file-medical-alt fa-5x fa-fw"></i></div>
				<div class="fs-16 mt-2 pt-2 border-top border-white"><?= $this->lang->line('btn_generate_surgery') ?></div>
			</button>
		</div>
		<div class="col-md-4">
			<button class="btn btn-info w-100 mb-3 control_bl_simple" value="bl_af">
				<div><i class="fal fa-file fa-5x fa-fw"></i></div>
				<div class="fs-16 mt-2 pt-2 border-top border-white"><?= $this->lang->line('btn_add_file') ?></div>
			</button>
		</div>
	</div>
</div>
<?php } ?>
<div class="col-md-12 bl_simple d-none" id="bl_ga">
	<div class="card">
		<div class="card-header">
			<h4 class="mb-0"><?= $this->lang->line('w_generate_appointment') ?></h4>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col-md-6 mb-3">
					<form action="#" id="add_appointment_form">
						<h5 class="mb-3"><?= $this->lang->line('w_attention') ?></h5>
						<div class="form-row">
							<div class="form-group col-md-6">
								<label><?= $this->lang->line('w_specialty') ?></label>
								<select class="form-control" id="aa_specialty" name="app[specialty_id]">
									<option value="">--</option>
									<?php foreach($specialties as $item){ if($item->dr_qty){ ?>
									<option value="<?= $item->id ?>"><?= $item->name ?></option>
									<?php }} ?>
								</select>
								<div class="sys_msg" id="aa_specialty_msg"></div>
							</div>
							<div class="form-group col-md-6">
								<label>
									<span class="mr-1"><?= $this->lang->line('w_doctor') ?></span>
									<span><i class="far fa-clock" id="ic_doctor_schedule_w_aa" data-toggle="modal" data-target=".md_weekly_doctor_agenda"></i></span>
								</label>
								<select class="form-control" id="aa_doctor" name="app[doctor_id]">
									<option value="">--</option>
									<?php foreach($doctors as $item){ if ($item->status_id == $s_enabled->id){ ?>
									<option class="spe spe_<?= $item->specialty_id ?> d-none" value="<?= $item->person_id ?>"><?= $item->name ?></option>
									<?php }} ?>
								</select>
								<div class="sys_msg" id="aa_doctor_msg"></div>
							</div>
							<div class="form-group col-md-6">
								<label><?= $this->lang->line('w_date') ?></label>
								<input type="text" class="form-control bw date_picker" id="aa_date" name="sch[date]" value="<?= date('Y-m-d') ?>" readonly>
								<div class="sys_msg" id="aa_date_msg"></div>
							</div>
							<div class="form-group col-md-6">
								<label><?= $this->lang->line('w_time') ?></label>
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
						<h5 class="my-3"><?= $this->lang->line('w_patient') ?></h5>
						<input type="hidden" id="aa_pt_id" name="app[patient_id]" value="<?= $person->id ?>">
						<div class="form-row">
							<div class="form-group col-md-12">
								<input type="hidden" name="pt[doc_type_id]" value="<?= $person->doc_type_id ?>">
								<input type="hidden" name="pt[doc_number]" value="<?= $person->doc_number ?>">
								<label><?= $this->lang->line('w_document') ?></label>
								<input type="text" class="form-control" value="<?= $person->doc_type." ".$person->doc_number ?>" readonly>
								<div class="sys_msg" id="pt_doc_msg"></div>
							</div>
							<div class="form-group col-md-8">
								<label><?= $this->lang->line('w_name') ?></label>
								<input type="text" class="form-control" name="pt[name]" value="<?= $person->name ?>" readonly>
								<div class="sys_msg" id="pt_name_msg"></div>
							</div>
							<div class="form-group col-md-4">
								<label><?= $this->lang->line('w_tel') ?></label>
								<input type="text" class="form-control" name="pt[tel]" value="<?= $person->tel ?>">
								<div class="sys_msg" id="pt_tel_msg"></div>
							</div>
							<div class="form-group col-md-12">
								<label><?= $this->lang->line('w_remark') ?> (<?= $this->lang->line('w_optional') ?>)</label>
								<textarea class="form-control" rows="4" name="app[remark]" placeholder="<?= $this->lang->line('t_remark') ?>"></textarea>
							</div>
							<div class="form-group col-md-12 pt-3 mb-0">
								<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_register') ?></button>
							</div>
						</div>
					</form>
				</div>
				<div class="col-md-6 mb-3">
					<h5 class="mb-3"><?= $this->lang->line('w_doctor_agenda') ?></h5>
					<div id="aa_schedule_list"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="col-md-12 bl_simple d-none" id="bl_gs">
	<div class="card">
		<div class="card-header">
			<h4 class="mb-0"><?= $this->lang->line('w_generate_surgery') ?></h4>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col-md-6 mb-3">
					<form action="#" id="sur_register_form">
						<h5 class="mb-3"><?= $this->lang->line('w_attention') ?></h5>
						<div class="form-row">
							<div class="form-group col-md-6">
								<label><?= $this->lang->line('w_specialty') ?></label>
								<select class="form-control" id="sur_specialty" name="sur[specialty_id]">
									<option value="">--</option>
									<?php foreach($specialties as $item){ if($item->dr_qty){ ?>
									<option value="<?= $item->id ?>"><?= $item->name ?></option>
									<?php }} ?>
								</select>
								<div class="sys_msg" id="sur_specialty_msg"></div>
							</div>
							<div class="form-group col-md-6">
								<label>
									<span class="mr-1"><?= $this->lang->line('w_doctor') ?></span>
									<span><i class="far fa-clock" id="ic_doctor_schedule_w_sur" data-toggle="modal" data-target=".md_weekly_doctor_agenda"></i></span>
								</label>
								<select class="form-control" id="sur_doctor" name="sur[doctor_id]">
									<option value="">--</option>
									<?php foreach($doctors as $item){ if ($item->status_id == $s_enabled->id){ ?>
									<option class="spe spe_<?= $item->specialty_id ?> d-none" value="<?= $item->person_id ?>"><?= $item->name ?></option>
									<?php }} ?>
								</select>
								<div class="sys_msg" id="sur_doctor_msg"></div>
							</div>
							<div class="form-group col-md-6">
								<label><?= $this->lang->line('w_date') ?></label>
								<input type="text" class="form-control bw date_picker" id="sur_date" name="sch[date]" value="<?= date('Y-m-d') ?>" readonly>
								<div class="sys_msg" id="sur_date_msg"></div>
							</div>
							<div class="form-group col-md-6">
								<label><?= $this->lang->line('w_time') ?></label>
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
									<span class="mr-1"><?= $this->lang->line('w_room') ?></span>
									<span><i class="far fa-clock" id="ic_room_availability_w" data-toggle="modal" data-target=".md_weekly_room_availability"></i></span>
								</label>
								<select class="form-control" id="sur_room_id" name="sur[room_id]">
									<option value="">--</option>
									<?php foreach($rooms as $r){ ?>
									<option value="<?= $r->id ?>"><?= $r->name ?></option>
									<?php } ?>
								</select>
								<div class="sys_msg" id="sur_room_msg"></div>
							</div>
							<div class="form-group col-md-4">
								<label><?= $this->lang->line('w_duration') ?></label>
								<select class="form-control" name="sch[duration]">
									<option value="">--</option>
									<?php foreach($duration_ops as $op){ ?>
									<option value="<?= $op["value"] ?>"><?= $op["txt"] ?></option>
									<?php } ?>
								</select>
								<div class="sys_msg" id="sur_duration_msg"></div>
							</div>
						</div>		
						<h5 class="my-3"><?= $this->lang->line('w_patient') ?></h5>
						<input type="hidden" id="sur_pt_id" name="sur[patient_id]" value="">
						<div class="form-row">
							<div class="form-group col-md-12">
								<input type="hidden" name="pt[doc_type_id]" value="<?= $person->doc_type_id ?>">
								<input type="hidden" name="pt[doc_number]" value="<?= $person->doc_number ?>">
								<label><?= $this->lang->line('w_document') ?></label>
								<input type="text" class="form-control" value="<?= $person->doc_type." ".$person->doc_number ?>" readonly>
								<div class="sys_msg" id="pt_doc_msg"></div>
							</div>
							<div class="form-group col-md-8">
								<label><?= $this->lang->line('w_name') ?></label>
								<input type="text" class="form-control" name="pt[name]" value="<?= $person->name ?>" readonly>
								<div class="sys_msg" id="pt_name_msg"></div>
							</div>
							<div class="form-group col-md-4">
								<label><?= $this->lang->line('w_tel') ?></label>
								<input type="text" class="form-control" name="pt[tel]" value="<?= $person->tel ?>">
								<div class="sys_msg" id="pt_tel_msg"></div>
							</div>
							<div class="form-group col-md-12">
								<label><?= $this->lang->line('w_remark') ?> (<?= $this->lang->line('w_optional') ?>)</label>
								<textarea class="form-control" rows="4" name="sur[remark]" placeholder="<?= $this->lang->line('t_remark') ?>"></textarea>
							</div>
							<div class="form-group col-md-12 pt-3">
								<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_register') ?></button>
							</div>
						</div>
					</form>
				</div>
				<div class="col-md-6 mb-3">
					<h5 class="mb-3"><?= $this->lang->line('w_doctor_agenda') ?></h5>
					<div id="sur_schedule_list"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="col-md-12 bl_simple d-none" id="bl_af">
	<div class="card">
		<div class="card-header">
			<h4 class="mb-0"><?= $this->lang->line('w_add_file') ?></h4>
		</div>
		<div class="card-body">
			<form action="#" class="form-row" id="form_upload_patient_file">
				<input type="hidden" name="patient_id" value="<?= $person->id ?>">
				<div class="form-group col-md-6">
					<label><?= $this->lang->line('w_title') ?></label>
					<input type="text" class="form-control" name="title">
					<div class="sys_msg" id="pf_title_msg"></div>
				</div>
				<div class="form-group col-md-5">
					<label><?= $this->lang->line('w_file') ?></label>
					<input type="file" class="form-control d-none" name="upload_file" id="upload_file">
					<label class="form-control d-flex justify-content-between align-items-center text-truncate mb-0" for="upload_file">
						<div id="lb_selected_filename" class="text-truncate w-75 mr-auto"><?= $this->lang->line('t_select_file') ?>...</div>
						<div><i class="far fa-paperclip"></i></div>
					</label>
					<div class="sys_msg" id="pf_file_msg"></div>
				</div>
				<div class="form-group col-md-1">
					<label class="d-md-block d-none">&nbsp;</label>
					<button type="submit" class="btn btn-primary w-100"><i class="fas fa-plus"></i></button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="col-md-12">
	<div class="card">
		<div class="card-header">
			<h4 class="mb-0"><?= $this->lang->line('w_records') ?></h4>
		</div>
		<div class="card-body" style="min-height: 500px;">
			<!-- Nav tabs -->
			<div class="custom-tab-1">
				<ul class="nav nav-tabs mb-4">
					<li class="nav-item">
						<a class="nav-link active" data-toggle="tab" href="#information">
							<i class="far fa-comment-alt mr-3"></i><span><?= $this->lang->line('w_information') ?></span>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#appointments">
							<i class="far fa-notes-medical mr-3"></i><span><?= $this->lang->line('w_appointments') ?></span>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#surgeries">
							<i class="far fa-file-medical-alt mr-3"></i><span><?= $this->lang->line('w_surgeries') ?></span>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#sales">
							<i class="far fa-shopping-basket mr-3"></i><span><?= $this->lang->line('w_sales') ?></span>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#files">
							<i class="far fa-file mr-3"></i><span><?= $this->lang->line('w_files') ?></span>
						</a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane fade active show" id="information" role="tabpanel">
						<form id="form_update_info">
							<input type="hidden" name="id" value="<?= $person->id ?>">
							<div class="form-row mb-3">
								<div class="form-group col-md-3">
									<label><?= $this->lang->line('w_document') ?></label>
									<select class="form-control" id="pu_doc_type_id" name="doc_type_id" disabled>
										<?php foreach($doc_types as $d){ if ($d->sunat_code){ 
										if ($person->doc_type_id == $d->id) $s = "selected"; else $s = ""; ?>
										<option value="<?= $d->id ?>" <?= $s ?>><?= $d->description ?></option>
										<?php }} ?>
									</select>
									<div class="sys_msg" id="pu_doc_type_msg"></div>
								</div>
								<div class="form-group col-md-3">
									<label class="d-md-block d-none">&nbsp;</label>
									<input type="text" class="form-control" id="pu_doc_number" name="doc_number" placeholder="<?= $this->lang->line('w_number') ?>" value="<?= $person->doc_number ?>" readonly>
									<div class="sys_msg" id="pu_doc_number_msg"></div>
								</div>
								<div class="form-group col-md-6">
									<label><?= $this->lang->line('w_name') ?></label>
									<input type="text" class="form-control" id="pu_name" name="name" value="<?= $person->name ?>" readonly>
									<div class="sys_msg" id="pu_name_msg"></div>
								</div>
								<div class="form-group col-md-3">
									<label><?= $this->lang->line('w_tel') ?></label>
									<input type="text" class="form-control" id="pu_tel" name="tel" value="<?= $person->tel ?>" readonly>
									<div class="sys_msg" id="pu_tel_msg"></div>
								</div>
								<div class="form-group col-md-3">
									<?php
									if ($person->birthday){
										$aux = strtotime($person->birthday);
										$b = date("Y-m-d", $aux);
										$d = (int)date("d", $aux); $m = (int)date("m", $aux); $y = (int)date("Y", $aux);	
									}else $b = $d = $m = $y = null;
									?>
									<label><?= $this->lang->line('w_birthday') ?></label>
									<input type="hidden" id="p_birthday" name="birthday" value="<?= $b ?>" readonly>
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
									<label><?= $this->lang->line('w_sex') ?></label>
									<select class="form-control" name="sex_id" disabled>
										<option value="" selected="">--</option>
										<?php foreach($sex_ops as $item){
										if ($item->id == $person->sex_id) $s = "selected"; else $s = ""; ?>
										<option value="<?= $item->id ?>" <?= $s ?>><?= $item->description ?></option>
										<?php } ?>
									</select>
									<div class="sys_msg" id="pu_sex_msg"></div>
								</div>
								<div class="form-group col-md-3">
									<label><?= $this->lang->line('w_blood_type') ?></label>
									<select class="form-control" name="blood_type_id" disabled>
										<option value="" selected="">--</option>
										<?php foreach($blood_type_ops as $item){
										if ($item->id == $person->blood_type_id) $s = "selected"; else $s = ""; ?>
										<option value="<?= $item->id ?>" <?= $s ?>><?= $item->description ?></option>
										<?php } ?>
									</select>
									<div class="sys_msg" id="pu_blood_type_msg"></div>
								</div>
								<div class="form-group col-md-4">
									<label><?= $this->lang->line('w_email') ?></label>
									<input type="email" class="form-control" name="email" placeholder="email@example.com" value="<?= $person->email ?>" readonly>
									<div class="sys_msg" id="pu_email_msg"></div>
								</div>
								<div class="form-group col-md-8">
									<label><?= $this->lang->line('w_address') ?></label>
									<input type="text" class="form-control" name="address" value="<?= $person->address ?>" readonly>
									<div class="sys_msg" id="pu_address_msg"></div>
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
												<th class="pl-0"><?= $this->lang->line('w_date') ?></th>
												<th><?= $this->lang->line('w_time') ?></th>
												<th><?= $this->lang->line('w_doctor') ?></th>
												<th><?= $this->lang->line('w_status') ?></th>
												<th class="text-right pr-0"></th>
											</tr>
										</thead>
										<tbody>
											<?php foreach($appointments as $item){ ?>
											<tr>
												<td class="pl-0">
													<?= date("Y-m-d", strtotime($item->schedule_from)) ?>
												</td>
												<td>
													<div class="text-nowrap"><?= date("h:i A", strtotime($item->schedule_from)) ?></div>
													<div class="text-nowrap">- <?= date("h:i A", strtotime($item->schedule_to)) ?></div>
												</td>
												<td><?= $doctors_arr[$item->doctor_id]->name ?><br/><?= $specialty_arr[$item->specialty_id] ?></td>
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
							<div class="col-md-12"><?= $this->lang->line('t_no_appointment') ?></div>
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
												<th class="pl-0"><?= $this->lang->line('w_date') ?></th>
												<th><?= $this->lang->line('w_time') ?></th>
												<th><?= $this->lang->line('w_room') ?></th>
												<th><?= $this->lang->line('w_doctor') ?></th>
												<th><?= $this->lang->line('w_status') ?></th>
												<th class="text-right pr-0"></th>
											</tr>
										</thead>
										<tbody>
											<?php foreach($surgeries as $item){ ?>
											<tr>
												<td class="pl-0">
													<?= date("Y-m-d", strtotime($item->schedule_from)) ?>
												</td>
												<td>
													<div class="text-nowrap">
														<?= date("h:i A", strtotime($item->schedule_from)) ?>
													</div>
													<div class="text-nowrap">
														- <?= date("h:i A", strtotime($item->schedule_to)) ?>
													</div>
												</td>
												<td><?= $rooms_arr[$item->room_id] ?></td>
												<td><?= $doctors_arr[$item->doctor_id]->name ?><br/><?= $specialty_arr[$item->specialty_id] ?></td>
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
							<div class="col-md-12"><?= $this->lang->line('t_no_surgery') ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="tab-pane fade" id="sales" role="tabpanel">
						<div class="row">
							<?php if ($sales){ ?>
							<div class="col-md-2">
								<div class="mb-3" id="sale_list_length_new"></div>
							</div>
							<div class="col-md-6"></div>
							<div class="col-md-4">
								<div class="mb-3" id="sale_list_filter_new"></div>
							</div>
							<div class="col-md-12">
								<div class="table-responsive">
									<table id="sale_list" class="table display">
										<thead>
											<tr>
												<th class="text-left pl-0"><?= $this->lang->line('w_date') ?></th>
												<th><?= $this->lang->line('w_client') ?></th>
												<th><?= $this->lang->line('w_total') ?></th>
												<th><?= $this->lang->line('w_balance') ?></th>
												<th><?= $this->lang->line('w_status') ?></th>
												<th class="text-right pr-0"></th>
											</tr>
										</thead>
										<tbody>
											<?php foreach($sales as $s){ $cur = $currencies_arr[$s->currency_id]->description; ?>
											<tr>
												<td class="text-left pl-0"><?= $s->registed_at ?></td>
												<td><?= $person->name ?></td>
												<td><?= $cur." ".number_format($s->total, 2) ?></td>
												<td><?php if ($s->balance) echo $cur." ".number_format($s->balance, 2); else echo "-"; ?></td>
												<td class="text-<?= $status_arr[$s->status_id]->color ?>">
													 <?= $this->lang->line($status_arr[$s->status_id]->code) ?>
												</td>
												<td class="text-right pr-0">
													<a href="<?= base_url() ?>sale/detail/<?= $s->id ?>">
														<button type="button" class="btn btn-primary light sharp border-0">
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
							<div class="col-md-12"><?= $this->lang->line('t_no_sale') ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="tab-pane fade" id="files" role="tabpanel">
						<div class="row">
							<?php if ($patient_files){ ?>
							<div class="col-md-2">
								<div class="mb-3" id="file_list_length_new"></div>
							</div>
							<div class="col-md-6"></div>
							<div class="col-md-4">
								<div class="mb-3" id="file_list_filter_new"></div>
							</div>
							<div class="col-md-12">
								<div class="table-responsive">
									<table id="file_list" class="display">
										<thead>
											<tr>
												<th class="text-left pl-0"><?= $this->lang->line('w_date') ?></th>
												<th><?= $this->lang->line('w_title') ?></th>
												<th><?= $this->lang->line('w_type') ?></th>
												<th class="text-right pr-0"></th>
											</tr>
										</thead>
										<tbody>
											<?php $file_path = base_url()."uploaded/pacientes/".str_replace(" ", "_", $person->name)."_".$person->doc_number."/"; foreach($patient_files as $item){ ?>
											<tr>
												<td class="text-left pl-0"><?= $item->registed_at ?></td>
												<td><?= $item->title ?></td>
												<td><?= explode(".", $item->filename)[1] ?></td>
												<td class="text-right pr-0">
													<a href="<?= $file_path.$item->filename ?>" target="_blank">
														<button type="button" class="btn btn-primary light sharp border-0">
															<i class="far fa-search"></i>
														</button>
													</a>
													<button type="button" class="btn btn-danger light sharp border-0 btn_delete_file" value="<?= $item->id ?>">
														<i class="far fa-trash"></i>
													</button>
												</td>
											</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
							</div>
							<?php }else{ ?>
							<div class="col-md-12"><?= $this->lang->line('t_no_file') ?></div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
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
<div class="modal fade md_weekly_room_availability" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header pb-0 border-0">
				<h5 class="modal-title"><?= $this->lang->line('w_room_availability') ?></h5>
				<button type="button" class="close" data-dismiss="modal"><span>&times;</span>
				</button>
			</div>
			<div class="modal-body" id="bl_room_availability"></div>
		</div>
	</div>
</div>
<div class="d-none">
	<input type="hidden" id="wm_register_app" value="<?= $this->lang->line('wm_register_app') ?>">
	<input type="hidden" id="wm_register_sur" value="<?= $this->lang->line('wm_register_sur') ?>">
	<input type="hidden" id="wm_delete_file" value="<?= $this->lang->line('wm_delete_file') ?>">
</div>