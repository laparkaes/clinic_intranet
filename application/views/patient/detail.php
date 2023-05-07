<div class="col-md-12">
	<div class="row page-titles mx-0">
		<div class="col-sm-6 p-md-0">
			<div class="welcome-text">
				<h4><?= $person->name ?></h4>
			</div>
		</div>
		<div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?= base_url() ?>patient"><?= $this->lang->line('patients') ?></a></li>
				<li class="breadcrumb-item active"><a href="javascript:void(0)"><?= $this->lang->line('txt_detail') ?></a></li>
			</ol>
		</div>
	</div>
</div>
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
<div class="col-md-12 bl_simple d-none" id="bl_ga">
	<div class="card">
		<div class="card-header border-0 pb-0">
			<h4><?= $this->lang->line('title_generate_appointment') ?></h4>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col-md-6 mb-3">
					<form action="#" id="add_appointment_form">
						<h5 class="mb-3"><?= $this->lang->line('title_attention') ?></h5>
						<div class="form-row">
							<div class="form-group col-md-6">
								<label><?= $this->lang->line('lb_specialty') ?></label>
								<select class="form-control" id="aa_specialty" name="app[specialty_id]">
									<option value="">--</option>
									<?php foreach($specialties as $item){ ?>
									<option value="<?= $item->id ?>"><?= $item->name ?></option>
									<?php } ?>
								</select>
								<div class="sys_msg" id="aa_specialty_msg"></div>
							</div>
							<div class="form-group col-md-6">
								<label>
									<span class="mr-1"><?= $this->lang->line('lb_doctor') ?></span>
									<span><i class="far fa-clock" id="ic_doctor_schedule_w_aa" data-toggle="modal" data-target=".md_weekly_doctor_agenda"></i></span>
								</label>
								<select class="form-control" id="aa_doctor" name="app[doctor_id]">
									<option value="">--</option>
									<?php foreach($doctors as $item){ ?>
									<option class="spe spe_<?= $item->specialty_id ?> d-none" value="<?= $item->person_id ?>"><?= $item->name ?></option>
									<?php } ?>
								</select>
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
						<h5 class="my-3"><?= $this->lang->line('title_patient') ?></h5>
						<input type="hidden" id="aa_pt_id" name="app[patient_id]" value="<?= $person->id ?>">
						<div class="form-row">
							<div class="form-group col-md-12">
								<input type="hidden" name="pt[doc_type_id]" value="<?= $person->doc_type_id ?>">
								<input type="hidden" name="pt[doc_number]" value="<?= $person->doc_number ?>">
								<label><?= $this->lang->line('lb_document') ?></label>
								<input type="text" class="form-control bg-light" value="<?= $person->doc_type." ".$person->doc_number ?>" readonly>
								<div class="sys_msg" id="pt_doc_msg"></div>
							</div>
							<div class="form-group col-md-6">
								<label><?= $this->lang->line('lb_name') ?></label>
								<input type="text" class="form-control bg-light" name="pt[name]" value="<?= $person->name ?>" readonly>
								<div class="sys_msg" id="pt_name_msg"></div>
							</div>
							<div class="form-group col-md-6">
								<label><?= $this->lang->line('lb_tel') ?></label>
								<input type="text" class="form-control" name="pt[tel]" value="<?= $person->tel ?>">
								<div class="sys_msg" id="pt_tel_msg"></div>
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
				<div class="col-md-6 mb-3">
					<h5 class="mb-3"><?= $this->lang->line('lb_doctor_agenda') ?></h5>
					<div id="aa_schedule_list"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="col-md-12 bl_simple d-none" id="bl_gs">
	<div class="card">
		<div class="card-header border-0 pb-0">
			<h4><?= $this->lang->line('title_generate_surgery') ?></h4>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col-md-6 mb-3">
					<form action="#" id="sur_register_form">
						<h5 class="mb-3"><?= $this->lang->line('title_attention') ?></h5>
						<div class="form-row">
							<div class="form-group col-md-6">
								<label><?= $this->lang->line('lb_specialty') ?></label>
								<select class="form-control" id="sur_specialty" name="sur[specialty_id]">
									<option value="">--</option>
									<?php foreach($specialties as $item){ ?>
									<option value="<?= $item->id ?>"><?= $item->name ?></option>
									<?php } ?>
								</select>
								<div class="sys_msg" id="sur_specialty_msg"></div>
							</div>
							<div class="form-group col-md-6">
								<label>
									<span class="mr-1"><?= $this->lang->line('lb_doctor') ?></span>
									<span><i class="far fa-clock" id="ic_doctor_schedule_w_sur" data-toggle="modal" data-target=".md_weekly_doctor_agenda"></i></span>
								</label>
								<select class="form-control" id="sur_doctor" name="sur[doctor_id]">
									<option value="">--</option>
									<?php foreach($doctors as $item){ ?>
									<option class="spe spe_<?= $item->specialty_id ?> d-none" value="<?= $item->person_id ?>"><?= $item->name ?></option>
									<?php } ?>
								</select>
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
								<select class="form-control" id="sur_room_id" name="sur[room_id]">
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
						<h5 class="my-3"><?= $this->lang->line('title_patient') ?></h5>
						<input type="hidden" id="sur_pt_id" name="sur[patient_id]" value="">
						<div class="form-row">
							<div class="form-group col-md-12">
								<input type="hidden" name="pt[doc_type_id]" value="<?= $person->doc_type_id ?>">
								<input type="hidden" name="pt[doc_number]" value="<?= $person->doc_number ?>">
								<label><?= $this->lang->line('lb_document') ?></label>
								<input type="text" class="form-control bg-light" value="<?= $person->doc_type." ".$person->doc_number ?>" readonly>
								<div class="sys_msg" id="pt_doc_msg"></div>
							</div>
							<div class="form-group col-md-6">
								<label><?= $this->lang->line('lb_name') ?></label>
								<input type="text" class="form-control bg-light" name="pt[name]" value="<?= $person->name ?>" readonly>
								<div class="sys_msg" id="pt_name_msg"></div>
							</div>
							<div class="form-group col-md-6">
								<label><?= $this->lang->line('lb_tel') ?></label>
								<input type="text" class="form-control" name="pt[tel]" value="<?= $person->tel ?>">
								<div class="sys_msg" id="pt_tel_msg"></div>
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
					<h5 class="mb-3"><?= $this->lang->line('lb_doctor_agenda') ?></h5>
					<div id="sur_schedule_list"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="col-md-12 bl_simple d-none" id="bl_af">
	<div class="card">
		<div class="card-header border-0 pb-0">
			<h4><?= $this->lang->line('title_add_file') ?></h4>
		</div>
		<div class="card-body">
			<form action="#" class="form-row" id="form_upload_patient_file">
				<input type="hidden" name="patient_id" value="<?= $person->id ?>">
				<div class="form-group col-md-6">
					<label><?= $this->lang->line('title_title') ?></label>
					<input type="text" class="form-control" name="title">
					<div class="sys_msg" id="pf_title_msg"></div>
				</div>
				<div class="form-group col-md-5">
					<label><?= $this->lang->line('title_file') ?></label>
					<input type="file" class="form-control d-none" name="upload_file" id="upload_file">
					<label class="form-control d-flex justify-content-between align-items-center text-truncate mb-0" for="upload_file">
						<div id="lb_selected_filename" class="text-truncate w-75 mr-auto"><?= $this->lang->line('msg_select_file') ?>...</div>
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
		<div class="card-header border-0 pb-0">
			<h4><?= $this->lang->line('title_records') ?></h4>
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
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#sales">
							<i class="far fa-shopping-basket mr-3"></i><span><?= $this->lang->line('tab_sales') ?></span>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#files">
							<i class="far fa-file mr-3"></i><span><?= $this->lang->line('tab_files') ?></span>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#edit">
							<i class="far fa-edit mr-3"></i><span><?= $this->lang->line('tab_edit') ?></span>
						</a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane fade active show" id="information" role="tabpanel">
						<div class="form-row">
							<div class="form-group col-md-5">
								<label><?= $this->lang->line('lb_name') ?></label>
								<input type="text" class="form-control bw" value="<?= $person->name ?>" readonly>
							</div>
							<div class="form-group col-md-3">
								<label><?= $this->lang->line('lb_document') ?></label>
								<input type="text" class="form-control bw" value="<?= $person->doc_type." ".$person->doc_number ?>" readonly>
							</div>
							<div class="form-group col-md-4">
								<label><?= $this->lang->line('lb_email') ?></label>
								<input type="text" class="form-control bw" value="<?= $person->email ?>" readonly>
							</div>
							<div class="form-group col-md-3">
								<label><?= $this->lang->line('lb_tel') ?></label>
								<input type="text" class="form-control bw" value="<?= $person->tel ?>" readonly>
							</div>
							<div class="form-group col-md-9">
								<label><?= $this->lang->line('lb_address') ?></label>
								<input type="text" class="form-control bw" value="<?= $person->address ?>" readonly>
							</div>
							<div class="form-group col-md-3">
								<label><?= $this->lang->line('lb_birthday') ?></label>
								<input type="text" class="form-control bw" value="<?= $person->birthday ?>" readonly>
							</div>
							<div class="form-group col-md-3">
								<label><?= $this->lang->line('lb_age') ?></label>
								<input type="text" class="form-control bw" value="<?= $person->age ?>" readonly>
							</div>
							<div class="form-group col-md-3">
								<label><?= $this->lang->line('lb_sex') ?></label>
								<input type="text" class="form-control bw" value="<?= $person->sex ?>" readonly>
							</div>
							<div class="form-group col-md-3">
								<label><?= $this->lang->line('lb_blood_type') ?></label>
								<input type="text" class="form-control bw" value="<?= $person->blood_type ?>" readonly>
							</div>
						</div>
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
												<th class="pt-0"><?= $this->lang->line('hd_doctor') ?></th>
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
							<div class="col-md-12">
								<div class="text-danger"><?= $this->lang->line('msg_no_appointment_data') ?></div>
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
												<th class="pt-0"><?= $this->lang->line('hd_doctor') ?></th>
												<th class="pt-0"><?= $this->lang->line('hd_status') ?></th>
												<th class="text-right pt-0 pr-0"></th>
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
							<div class="col-md-12">
								<div class="text-danger"><?= $this->lang->line('msg_no_surgery_data') ?></div>
							</div>
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
												<th class="text-left pt-0 pl-0"><?= $this->lang->line('hd_date') ?></th>
												<th class="pt-0"><?= $this->lang->line('hd_client') ?></th>
												<th class="pt-0"><?= $this->lang->line('hd_total') ?></th>
												<th class="pt-0"><?= $this->lang->line('hd_balance') ?></th>
												<th class="pt-0"><?= $this->lang->line('hd_status') ?></th>
												<th class="text-right pt-0 pr-0"></th>
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
							<div class="col-md-12">
								<div class="text-danger"><?= $this->lang->line('msg_no_sale_data') ?></div>
							</div>
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
												<th class="text-left pt-0 pl-0"><?= $this->lang->line('hd_date') ?></th>
												<th class="pt-0"><?= $this->lang->line('hd_title') ?></th>
												<th class="pt-0"><?= $this->lang->line('hd_type') ?></th>
												<th class="text-right pt-0 pr-0"></th>
											</tr>
										</thead>
										<tbody>
											<?php $folder = $person->doc_type_id."_".$person->doc_number;
											foreach($patient_files as $item){ ?>
											<tr>
												<td class="text-left pl-0"><?= $item->registed_at ?></td>
												<td><?= $item->title ?></td>
												<td><?= explode(".", $item->filename)[1] ?></td>
												<td class="text-right pr-0">
													<a href="<?= base_url() ?>uploaded/patient_files/<?= $folder ?>/<?= $item->filename ?>" target="_blank">
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
							<div class="col-md-12">
								<div class="text-danger"><?= $this->lang->line('msg_no_file_data') ?></div>
							</div>
							<?php } ?>
						</div>
					</div>
					<div class="tab-pane fade" id="edit" role="tabpanel">
						<div class="row">
							<div class="col-md-12">
								<form action="#" id="form_update">
									<input type="hidden" name="id" value="<?= $person->id ?>" readonly>
									<input type="hidden" name="name" value="<?= $person->name ?>" readonly>
									<input type="hidden" name="doc_type_id" value="<?= $person->doc_type_id ?>" readonly>
									<input type="hidden" name="doc_number" value="<?= $person->doc_number ?>" readonly>
									<div class="form-row">
										<div class="form-group col-md-5">
											<label><?= $this->lang->line('lb_name') ?></label>
											<input type="text" class="form-control bg-light" value="<?= $person->name ?>" readonly>
											<div class="sys_msg" id="pu_name_msg"></div>
										</div>
										<div class="form-group col-md-3">
											<label><?= $this->lang->line('lb_document') ?></label>
											<input type="text" class="form-control bg-light" value="<?= $person->doc_type." ".$person->doc_number ?>" readonly>
											<div class="sys_msg" id="pu_doc_msg"></div>
										</div>
										<div class="form-group col-md-4">
											<label><?= $this->lang->line('lb_email') ?></label>
											<input type="text" class="form-control" name="email" value="<?= $person->email ?>">
											<div class="sys_msg" id="pu_email_msg"></div>
										</div>
										<div class="form-group col-md-3">
											<label><?= $this->lang->line('lb_tel') ?></label>
											<input type="text" class="form-control" name="tel" value="<?= $person->tel ?>">
											<div class="sys_msg" id="pu_tel_msg"></div>
										</div>
										<div class="form-group col-md-9">
											<label><?= $this->lang->line('lb_address') ?></label>
											<input type="text" class="form-control" name="address" value="<?= $person->address ?>">
											<div class="sys_msg" id="pu_address_msg"></div>
										</div>
										<div class="form-group col-md-4">
											<label><?= $this->lang->line('lb_birthday') ?></label>
											<input type="text" class="form-control bw date_picker_all" name="birthday" readonly="" value="<?= $person->birthday ?>">
											<div class="sys_msg" id="pu_birthday_msg"></div>
										</div>
										<div class="form-group col-md-4">
											<label><?= $this->lang->line('lb_sex') ?></label>
											<select class="form-control" name="sex_id" id="de_p_sex">
												<option value="">--</option>
												<?php foreach($sex_ops as $item){
												if ($item->id == $person->sex_id) $s = "selected"; else $s = ""; ?>
												<option value="<?= $item->id ?>" <?= $s ?>><?= $item->description ?></option>
												<?php } ?>
											</select>
											<div class="sys_msg" id="pu_sex_msg"></div>
										</div>
										<div class="form-group col-md-4">
											<label><?= $this->lang->line('lb_blood_type') ?></label>
											<select class="form-control" name="blood_type_id" id="de_p_blood_type">
												<option value="">--</option>
												<?php foreach($blood_type_ops as $item){
												if ($item->id == $person->blood_type_id) $s = "selected"; else $s = ""; ?>
												<option value="<?= $item->id ?>" <?= $s ?>><?= $item->description ?></option>
												<?php } ?>
											</select>
											<div class="sys_msg" id="pu_blood_type_msg"></div>
										</div>
									</div>
									<button type="submit" class="btn btn-primary mt-3">
										<?= $this->lang->line('btn_update') ?>
									</button>
								</form>
							</div>
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
				<h5 class="modal-title"><?= $this->lang->line('title_doctor_agenda') ?></h5>
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
				<h5 class="modal-title"><?= $this->lang->line('title_room_availability') ?></h5>
				<button type="button" class="close" data-dismiss="modal"><span>&times;</span>
				</button>
			</div>
			<div class="modal-body" id="bl_room_availability"></div>
		</div>
	</div>
</div>
<div class="d-none">
	<input type="hidden" id="warning_rap" value="<?= $this->lang->line('warning_rap') ?>">
	<input type="hidden" id="warning_rsu" value="<?= $this->lang->line('warning_rsu') ?>">
	<input type="hidden" id="warning_dpf" value="<?= $this->lang->line('warning_dpf') ?>">
</div>