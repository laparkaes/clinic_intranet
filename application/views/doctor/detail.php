<div class="col-md-12">
	<div class="row page-titles mx-0">
		<div class="col-sm-6 p-md-0">
			<div class="welcome-text">
				<h4><?= $person->name ?></h4>
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
			<button class="btn btn-info w-100 mb-3 control_bl_simple" value="bl_bs">
				<div><i class="fal fa-clock fa-5x fa-fw"></i></div>
				<div class="fs-16 mt-2 pt-2 border-top border-white"><?= $this->lang->line('btn_block_schedule') ?></div>
			</button>
		</div>
		<div class="col-md-3">
			<button class="btn btn-outline-<?= $doctor->status->btn_color ?> w-100 mb-3" id="<?= $doctor->status->dom_id ?>" value="<?= $doctor->id ?>">
				<div><i class="fal fa-power-off fa-5x fa-fw"></i></div>
				<div class="fs-16 mt-2 pt-2 border-top border-<?= $doctor->status->btn_color ?>"><?= $doctor->status->btn_txt ?></div>
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
				<div class="col-md-12">
					<form action="#" id="app_register_form">
						<div class="row">
							<div class="col-md-6">
								<h5><?= $this->lang->line('title_attention') ?></h5>
								<div class="form-row">
									<div class="form-group col-md-6">
										<label><?= $this->lang->line('lb_specialty') ?></label>
										<input type="hidden" value="<?= $doctor->specialty_id ?>" name="app[specialty_id]" readonly>
										<input type="text" class="form-control bg-light" value="<?= $doctor->specialty ?>" readonly>
										<div class="sys_msg" id="aa_specialty_msg"></div>
									</div>
									<div class="form-group col-md-6">
										<label><?= $this->lang->line('lb_doctor') ?></label>
										<input type="hidden" value="<?= $person->id ?>" id="aa_doctor_id" name="app[doctor_id]" readonly>
										<input type="text" class="form-control bg-light" value="<?= $person->name ?>" readonly>
										<div class="sys_msg" id="aa_doctor_msg"></div>
									</div>
									<div class="form-group col-md-6">
										<label><?= $this->lang->line('lb_date') ?></label>
										<input type="text" class="form-control date_picker" id="aa_date" name="sch[date]" value="<?= date('Y-m-d') ?>" readonly>
										<div class="sys_msg" id="aa_date_msg"></div>
									</div>
									<div class="form-group col-md-6">
										<label><?= $this->lang->line('lb_time') ?></label>
										<div class="d-flex justify-content-between">
											<select class="form-control text-center px-0" id="aa_hour" name="sch[hour]">
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
											<tbody id="aa_schedule_list"></tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<h5><?= $this->lang->line('lb_patient') ?></h5>
								<input type="hidden" id="aa_pt_id" name="app[patient_id]" value="">
								<div class="form-row">
									<div class="form-group col-md-12">
										<label><?= $this->lang->line('lb_document') ?></label>
										<div class="input-group">
											<select class="form-control" id="aa_pt_doc_type_id" name="pt[doc_type_id]" style="border-right:0;">
												<?php foreach($doc_types as $item){ if ($item->sunat_code){ ?>
												<option value="<?= $item->id ?>"><?= $item->description ?></option>
												<?php }} ?>
											</select>
											<input type="text" class="form-control" id="aa_pt_doc_number" name="pt[doc_number]" style="border-left:0;" placeholder="<?= $this->lang->line('txt_number') ?>">
											<div class="input-group-append">
                                                <button class="btn btn-primary border-0" type="button" id="btn_aa_search_pt">
													<i class="fas fa-search"></i>
												</button>
                                            </div>
										</div>
										<div class="sys_msg" id="aa_pt_doc_msg"></div>
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
									<div class="form-group col-md-12 text-right pt-3 mb-0">
										<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_register') ?></button>
									</div>
								</div>
							</div>
						</div>
					</form>
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
				<div class="col-md-12">
					<form action="#" id="sur_register_form">
						<div class="row">
							<div class="col-md-6 mb-3">
								<h5><?= $this->lang->line('title_attention') ?></h5>
								<div class="form-row">
									<div class="form-group col-md-6">
										<label><?= $this->lang->line('lb_specialty') ?></label>
										<input type="hidden" value="<?= $doctor->specialty_id ?>" name="sur[specialty_id]" readonly>
										<input type="text" class="form-control bg-light" value="<?= $doctor->specialty ?>" readonly>
										<div class="sys_msg" id="sur_specialty_msg"></div>
									</div>
									<div class="form-group col-md-6">
										<label><?= $this->lang->line('lb_doctor') ?></label>
										<input type="hidden" value="<?= $person->id ?>" id="sur_doctor" name="sur[doctor_id]" readonly>
										<input type="text" class="form-control bg-light" value="<?= $person->name ?>" readonly>
										<div class="sys_msg" id="sur_doctor_msg"></div>
									</div>
									<div class="form-group col-md-6">
										<label><?= $this->lang->line('lb_date') ?></label>
										<input type="text" class="form-control date_picker" id="sur_date" name="sch[date]" value="<?= date('Y-m-d') ?>" readonly>
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
										<label><?= $this->lang->line('lb_place') ?></label>
										<input type="text" class="form-control" name="sur[place]">
										<div class="sys_msg" id="sur_place_msg"></div>
									</div>
									<div class="form-group col-md-4">
										<label><?= $this->lang->line('lb_duration') ?></label>
										<select class="form-control" name="sch[duration]">
											<option value="">--</option>
											<option value="30">30 <?= $this->lang->line('op_minutes') ?></option>
											<option value="60">1 <?= $this->lang->line('op_hour') ?></option>
											<?php for($i = 2; $i <= 6; $i++){ ?>
											<option value="<?= $i*60 ?>"><?= $i ?> <?= $this->lang->line('op_hours') ?></option>
											<?php } ?>
										</select>
										<div class="sys_msg" id="sur_duration_msg"></div>
									</div>
									<div class="form-group col-md-12">
										<h5 class="mt-3"><?= $this->lang->line('title_doctor_agenda') ?></h5>
										<table class="table table-sm w-100 mb-0">
											<thead>
												<tr>
													<th class="w-50 pl-0"><strong><?= $this->lang->line('th_type') ?></strong></th>
													<th class="text-center"><strong><?= $this->lang->line('th_start') ?></strong></th>
													<th class=""></th>
													<th class="text-center pr-0"><strong><?= $this->lang->line('th_end') ?></strong></th>
												</tr>
											</thead>
											<tbody id="sur_schedule_list"></tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="col-md-6 mb-3">
								<h5><?= $this->lang->line('lb_patient') ?></h5>
								<input type="hidden" id="sur_pt_id" name="sur[patient_id]" value="">
								<div class="form-row">
									<div class="form-group col-md-12">
										<label><?= $this->lang->line('lb_document') ?></label>
										<div class="input-group">
											<select class="form-control" id="sur_pt_doc_type_id" name="pt[doc_type_id]" style="border-right:0;">
												<?php foreach($doc_types as $item){ if ($item->sunat_code){ ?>
												<option value="<?= $item->id ?>"><?= $item->description ?></option>
												<?php }} ?>
											</select>
											<input type="text" class="form-control" id="sur_pt_doc_number" name="pt[doc_number]" style="border-left:0;" placeholder="<?= $this->lang->line('txt_number') ?>">
											<div class="input-group-append">
                                                <button class="btn btn-primary border-0" type="button" id="btn_sur_search_pt">
													<i class="fas fa-search"></i>
												</button>
                                            </div>
										</div>
										<div class="sys_msg" id="sur_pt_doc_msg"></div>
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
									<div class="form-group col-md-12 text-right pt-3">
										<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_register') ?></button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="col-md-12 bl_simple d-none" id="bl_bs">
	<div class="card">
		<div class="card-header border-0 pb-0">
			<h4><?= $this->lang->line('title_block_schedule') ?></h4>
		</div>
		<div class="card-body">
			bloquear horario
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
						<a class="nav-link" data-toggle="tab" href="#edit">
							<i class="far fa-edit mr-3"></i><span><?= $this->lang->line('tab_edit') ?></span>
						</a>
					</li>
				</ul>
				<div class="tab-content">	
					<div class="tab-pane fade active show" id="information" role="tabpanel">
						<div class="form-row">
							<div class="form-group col-md-6">
								<label><?= $this->lang->line('lb_name') ?></label>
								<input type="text" class="form-control" value="<?= $person->name ?>" readonly>
							</div>
							<div class="form-group col-md-3">
								<label><?= $this->lang->line('lb_specialty') ?></label>
								<input type="text" class="form-control" value="<?= $doctor->specialty ?>" readonly>
							</div>
							<div class="form-group col-md-3">
								<label><?= $this->lang->line('lb_license') ?></label>
								<input type="text" class="form-control" value="<?= $doctor->license ?>" readonly>
							</div>
							<div class="form-group col-md-3">
								<label><?= $this->lang->line('lb_status') ?></label>
								<input type="text" class="form-control text-<?= $doctor->status->color ?>" value="<?= $this->lang->line($doctor->status->code) ?>" readonly>
							</div>
							<div class="form-group col-md-3">
								<label><?= $this->lang->line('lb_tel') ?></label>
								<input type="text" class="form-control" value="<?= $person->tel ?>" readonly>
							</div>
							<div class="form-group col-md-3">
								<label><?= $this->lang->line('lb_email') ?></label>
								<input type="text" class="form-control" value="<?= $account->email ?>" readonly>
							</div>
							<div class="form-group col-md-3">
								<label><?= $this->lang->line('lb_document') ?></label>
								<input type="text" class="form-control" value="<?= $person->doc_type." ".$person->doc_number ?>" readonly>
							</div>
							<div class="form-group col-md-3">
								<label><?= $this->lang->line('lb_birthday') ?></label>
								<input type="text" class="form-control" value="<?= $person->birthday ?>" readonly>
							</div>
							<div class="form-group col-md-3">
								<label><?= $this->lang->line('lb_age') ?></label>
								<input type="text" class="form-control" value="<?= $person->age ?>" readonly>
							</div>
							<div class="form-group col-md-3">
								<label><?= $this->lang->line('lb_sex') ?></label>
								<input type="text" class="form-control" value="<?= $person->sex ?>" readonly>
							</div>
							<div class="form-group col-md-3">
								<label><?= $this->lang->line('lb_blood_type') ?></label>
								<input type="text" class="form-control" value="<?= $person->blood_type ?>" readonly>
							</div>
							<div class="form-group col-md-12">
								<label><?= $this->lang->line('lb_address') ?></label>
								<input type="text" class="form-control" value="<?= $person->address ?>" readonly>
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
												<th class="pt-0"><?= $this->lang->line('hd_patient') ?></th>
												<th class="pt-0"><?= $this->lang->line('hd_status') ?></th>
												<th class="text-right pt-0 pr-0"></th>
											</tr>
										</thead>
										<tbody>
											<?php foreach($appointments as $item){ ?>
											<tr>
												<td class="pl-0">
													<?= date("d.m.Y", strtotime($item->schedule_from)) ?>
												</td>
												<td>
													<div class="text-nowrap"><?= date("h:i A", strtotime($item->schedule_from)) ?></div>
													<div class="text-nowrap">- <?= date("h:i A", strtotime($item->schedule_to)) ?></div>
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
												<th class="pt-0"><?= $this->lang->line('hd_place') ?></th>
												<th class="pt-0"><?= $this->lang->line('hd_patient') ?></th>
												<th class="pt-0"><?= $this->lang->line('hd_status') ?></th>
												<th class="text-right pt-0 pr-0"></th>
											</tr>
										</thead>
										<tbody>
											<?php foreach($surgeries as $item){ ?>
											<tr>
												<td class="pl-0"><?= date("d.m.Y", strtotime($item->schedule_from)) ?></td>
												<td>
													<div class="text-nowrap"><?= date("h:i A", strtotime($item->schedule_from)) ?></div>
													<div class="text-nowrap">- <?= date("h:i A", strtotime($item->schedule_to)) ?></div>
												</td>
												<td><?= $item->place ?></td>
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
								<div class="text-danger"><?= $this->lang->line('msg_no_surgery_data') ?></div>
							</div>
							<?php } ?>
						</div>
					</div>
					<div class="tab-pane fade" id="edit" role="tabpanel">
						<div class="row">
							<div class="col-md-12">
								<form action="#" id="form_update_personal_data">
									<input type="hidden" name="id" value="<?= $person->id ?>">
									<h5 class="mb-3"><?= $this->lang->line('title_personal_info') ?></h5>
									<div class="form-row">
										<div class="form-group col-md-4">
											<label><?= $this->lang->line('lb_name') ?></label>
											<input type="text" class="form-control bg-light" value="<?= $person->name ?>" readonly>
											<div class="sys_msg" id="pd_name_msg"></div>
										</div>
										<div class="form-group col-md-4">
											<label><?= $this->lang->line('lb_document') ?></label>
											<input type="text" class="form-control bg-light" value="<?= $person->doc_type." ".$person->doc_number ?>" readonly>
											<div class="sys_msg" id="pd_doc_msg"></div>
										</div>
										<div class="form-group col-md-4">
											<label><?= $this->lang->line('lb_birthday') ?></label>
											<input type="text" class="form-control date_picker_all" name="birthday" value="<?= $person->birthday ?>" readonly>
											<div class="sys_msg" id="pd_birthday_msg"></div>
										</div>
										<div class="form-group col-md-4">
											<label><?= $this->lang->line('lb_tel') ?></label>
											<input type="text" class="form-control" name="tel" value="<?= $person->tel ?>">
											<div class="sys_msg" id="pd_tel_msg"></div>
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
											<div class="sys_msg" id="pd_sex_msg"></div>
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
											<div class="sys_msg" id="pd_blood_type_msg"></div>
										</div>
										<div class="form-group col-md-12">
											<label><?= $this->lang->line('lb_address') ?></label>
											<input type="text" class="form-control" name="address" value="<?= $person->address ?>">
											<div class="sys_msg" id="pd_address_msg"></div>
										</div>
									</div>
									<button type="submit" class="btn btn-primary mt-3">
										<?= $this->lang->line('btn_update') ?>
									</button>
								</form>
							</div>
						</div>
						<hr class="my-4">
						<div class="row">
							<div class="col-md-12">
								<form action="#" id="form_update_profession">
									<input type="hidden" name="id" value="<?= $doctor->id ?>">
									<h5 class="mb-3"><?= $this->lang->line('title_profession') ?></h5>
									<div class="form-row">
										<div class="form-group col-md-6">
											<label><?= $this->lang->line('lb_specialty') ?></label>
											<select class="form-control" name="specialty_id">
												<option value="">--</option>
												<?php foreach($specialties as $item){ if ($doctor->specialty_id == $item->id) $s = "selected"; else $s = ""; ?>
												<option value="<?= $item->id ?>" <?= $s ?>><?= $item->name ?></option>
												<?php } ?>
											</select>
											<div class="sys_msg" id="pr_specialty_msg"></div>
										</div>
										<div class="form-group col-md-6">
											<label><?= $this->lang->line('lb_license_number') ?></label>
											<input type="text" class="form-control" name="license" value="<?= $doctor->license ?>">
											<div class="sys_msg" id="pr_license_msg"></div>
										</div>
									</div>
									<button type="submit" class="btn btn-primary mt-3">
										<?= $this->lang->line('btn_update') ?>
									</button>
								</form>
							</div>
						</div>
						<hr class="my-4">
						<div class="row">
							<div class="col-md-12">
								<form action="#" id="form_update_account_email">
									<input type="hidden" name="id" value="<?= $account->id ?>">
									<h5 class="mb-3"><?= $this->lang->line('title_account') ?></h5>
									<div class="form-row">
										<div class="form-group col-md-12">
											<label><?= $this->lang->line('lb_email') ?></label>
											<input type="text" class="form-control" name="email" value="<?= $account->email ?>">
											<div class="sys_msg" id="ae_email_msg"></div>
										</div>
									</div>
									<button type="submit" class="btn btn-primary mt-3">
										<?= $this->lang->line('btn_update') ?>
									</button>
								</form>
							</div>
						</div>
						<hr class="my-4">
						<div class="row">
							<div class="col-md-12">
								<form action="#" id="form_update_account_password">
									<input type="hidden" name="id" value="<?= $account->id ?>">
									<h5 class="mb-3"><?= $this->lang->line('title_password_change') ?></h5>
									<div class="form-row">
										<div class=  "form-group col-md-6">
											<label><?= $this->lang->line('lb_password') ?></label>
											<input type="password" class="form-control" name="password">
											<div class="sys_msg" id="up_password_msg"></div>
										</div>
										<div class="form-group col-md-6">
											<label><?= $this->lang->line('lb_confirm') ?></label>
											<input type="password" class="form-control" name="confirm">
											<div class="sys_msg" id="up_confirm_msg"></div>
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
<div class="d-none">
	<input type="hidden" id="warning_ddo" value="<?= $this->lang->line('warning_ddo') ?>">
	<input type="hidden" id="warning_ado" value="<?= $this->lang->line('warning_ado') ?>">
</div>