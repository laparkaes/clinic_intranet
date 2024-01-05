<div class="pagetitle">
	<h1><?= $person->name ?></h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= $this->lang->line('w_home') ?></a></li>
			<li class="breadcrumb-item"><a href="<?= base_url() ?>clinic/patient"><?= $this->lang->line('patients') ?></a></li>
			<li class="breadcrumb-item active"><?= $this->lang->line('txt_detail') ?></li>
		</ol>
	</nav>
</div>
<?php if ($person->doc_number){ ?>
<div class="row">
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
	<div class="col-md-3">
		<button class="btn btn-success w-100 mb-3 control_bl_simple" value="bl_af">
			<div><i class="bi bi-file-earmark" style="font-size: 50px;"></i></div>
			<div class="fs-16 mt-2 pt-2 border-top border-white"><?= $this->lang->line('btn_add_file') ?></div>
		</button>
	</div>
	<div class="col-md-3">
		<button class="btn btn-secondary w-100 mb-3" disabled>
			<div><i class="bi bi-credit-card" style="font-size: 50px;"></i></div>
			<div class="fs-16 mt-2 pt-2 border-top border-white"><?= $this->lang->line('btn_add_credit') ?></div>
		</button>
	</div>
</div>
<?php } ?>
<div class="row">
	<div class="col">
		<div class="card bl_simple d-none" id="bl_ga">
			<?php $this->load->view("clinic/appointment/form_add_appointment", ["patient" => $person, "doctor" => null]); ?>
		</div>
		<div class="card bl_simple d-none" id="bl_gs">
			<div class="card-body">
				<h5 class="card-title"><?= $this->lang->line('w_generate_surgery') ?></h5>
				<div class="row">
					<div class="col-md-6">
						<form class="row g-3" id="sur_register_form">
							<div class="col-md-12">
								<strong><?= $this->lang->line('w_attention') ?></strong>
							</div>
							<div class="col-md-4">
								<label class="form-label"><?= $this->lang->line('w_date') ?></label>
								<input type="text" class="form-control date_picker" id="sur_date" name="sch[date]" value="<?= date('Y-m-d') ?>">
								<div class="sys_msg" id="sur_date_msg"></div>
							</div>
							<div class="col-md-8">
								<label class="form-label"><?= $this->lang->line('w_time') ?></label>
								<div class="input-group">
									<select class="form-select" id="sur_hour" name="sch[hour]">
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
									<span class="input-group-text">:</span>
									<select class="form-select" id="sur_min" name="sch[min]">
										<option value="" selected>--</option>
										<option value="00">00</option>
										<option value="15">15</option>
										<option value="30">30</option>
										<option value="45">45</option>
									</select>
								</div>
								<div class="sys_msg" id="sur_schedule_msg"></div>
							</div>
							<div class="col-md-12">
								<label class="form-label"><?= $this->lang->line('w_specialty') ?></label>
								<select class="form-select" id="sur_specialty" name="sur[specialty_id]">
									<option value="">--</option>
									<?php foreach($specialties as $item){ if($item->dr_qty){ ?>
									<option value="<?= $item->id ?>"><?= $item->name ?></option>
									<?php }} ?>
								</select>
								<div class="sys_msg" id="sur_specialty_msg"></div>
							</div>
							<div class="col-md-12">
								<label class="form-label">
									<span><?= $this->lang->line('w_doctor') ?></span>
									<i class="bi bi-alarm ms-2" id="ic_doctor_schedule_w_sur" data-bs-toggle="modal" data-bs-target="#md_weekly_doctor_agenda"></i>
								</label>
								<select class="form-select" id="sur_doctor" name="sur[doctor_id]">
									<option value="">--</option>
									<?php foreach($doctors as $item){ if ($item->status_id == $s_enabled->id){ ?>
									<option class="spe spe_<?= $item->specialty_id ?> d-none" value="<?= $item->person_id ?>"><?= $item->name ?></option>
									<?php }} ?>
								</select>
								<div class="sys_msg" id="sur_doctor_msg"></div>
							</div>
							<div class="col-md-8">
								<label class="form-label">
									<span><?= $this->lang->line('w_room') ?></span>
									<i class="bi bi-alarm ms-2" id="ic_room_availability_w" data-bs-toggle="modal" data-bs-target="#md_weekly_room_availability"></i>
								</label>
								<select class="form-select" name="sur[room_id]" id="sur_room_id">
									<option value="">--</option>
									<?php foreach($rooms as $r){ ?>
									<option value="<?= $r->id ?>"><?= $r->name ?></option>
									<?php } ?>
								</select>
								<div class="sys_msg" id="sur_room_msg"></div>
							</div>
							<div class="col-md-4">
								<label class="form-label"><?= $this->lang->line('w_duration') ?></label>
								<select class="form-select" name="sch[duration]">
									<option value="">--</option>
									<?php foreach($duration_ops as $op){ ?>
									<option value="<?= $op["value"] ?>"><?= $op["txt"] ?></option>
									<?php } ?>
								</select>
								<div class="sys_msg" id="sur_duration_msg"></div>
							</div>
							<div class="col-md-12 pt-3">
								<strong><?= $this->lang->line('w_patient') ?></strong>
								<input type="hidden" id="sur_pt_id" name="sur[patient_id]" value="">
							</div>
							<div class="col-md-12">
								<input type="hidden" name="pt[doc_type_id]" value="<?= $person->doc_type_id ?>">
								<input type="hidden" name="pt[doc_number]" value="<?= $person->doc_number ?>">
								<label class="form-label"><?= $this->lang->line('w_document') ?></label>
								<input type="text" class="form-control" value="<?= $person->doc_type." ".$person->doc_number ?>" readonly>
								<div class="sys_msg" id="pt_doc_msg"></div>
							</div>
							<div class="col-md-8">
								<label class="form-label"><?= $this->lang->line('w_name') ?></label>
								<input type="text" class="form-control" name="pt[name]" value="<?= $person->name ?>" readonly>
								<div class="sys_msg" id="pt_name_msg"></div>
							</div>
							<div class="col-md-4">
								<label class="form-label"><?= $this->lang->line('w_tel') ?></label>
								<input type="text" class="form-control" name="pt[tel]" value="<?= $person->tel ?>">
								<div class="sys_msg" id="pt_tel_msg"></div>
							</div>
							<div class="col-md-12">
								<label class="form-label"><?= $this->lang->line('w_remark') ?> (<?= $this->lang->line('w_optional') ?>)</label>
								<textarea class="form-control" rows="4" name="sur[remark]" placeholder="<?= $this->lang->line('t_remark') ?>"></textarea>
							</div>
							<div class="col-md-12 pt-3">
								<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_register') ?></button>
							</div>
						</form>
					</div>
					<div class="col-md-6">
						<div class="d-flex justify-content-between mb-3">
							<strong><?= $this->lang->line('w_doctor_agenda') ?></strong>
							<span><i class="bi bi-square-fill text-success"></i> <?= $this->lang->line('txt_busy') ?></span>
						</div>
						<div id="sur_schedule_list"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="card bl_simple d-none" id="bl_af">
			<div class="card-body">
				<h5 class="card-title"><?= $this->lang->line('w_add_file') ?></h5>
				<form class="row g-3" id="form_upload_patient_file">
					<input type="hidden" name="patient_id" value="<?= $person->id ?>">
					<div class="col-md-12">
						<label class="form-label"><?= $this->lang->line('w_title') ?></label>
						<input type="text" class="form-control" name="title">
						<div class="sys_msg" id="pf_title_msg"></div>
					</div>
					<div class="col-md-12">
						<label class="form-label"><?= $this->lang->line('w_file') ?></label>
						<input type="file" class="form-control" name="upload_file" id="upload_file">
						<div class="sys_msg" id="pf_file_msg"></div>
					</div>
					<div class="col-md-12 pt-3">
						<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_register') ?></button>
					</div>
				</form>
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
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="sales-tab" data-bs-toggle="tab" data-bs-target="#bordered-sales" type="button" role="tab" aria-controls="sales" aria-selected="false" tabindex="-1"><?= $this->lang->line('w_sales') ?></button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="files-tab" data-bs-toggle="tab" data-bs-target="#bordered-files" type="button" role="tab" aria-controls="files" aria-selected="false" tabindex="-1"><?= $this->lang->line('w_files') ?></button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="credit-tab" data-bs-toggle="tab" data-bs-target="#bordered-credit" type="button" role="tab" aria-controls="credit" aria-selected="false" tabindex="-1"><?= $this->lang->line('w_credit') ?></button>
					</li>
				</ul>
				<div class="tab-content pt-3">
					<div class="tab-pane fade show active" id="bordered-information" role="tabpanel" aria-labelledby="information-tab">
						<form class="row g-3" id="form_update_info">
							<input type="hidden" name="id" value="<?= $person->id ?>">
							<div class="col-md-3">
								<label class="form-label"><?= $this->lang->line('w_document') ?></label>
								<select class="form-select" id="pu_doc_type_id" name="doc_type_id" disabled>
									<?php foreach($doc_types as $d){ if ($d->sunat_code){ 
									if ($person->doc_type_id == $d->id) $s = "selected"; else $s = ""; ?>
									<option value="<?= $d->id ?>" <?= $s ?>><?= $d->description ?></option>
									<?php }} ?>
								</select>
								<div class="sys_msg" id="pu_doc_type_msg"></div>
							</div>
							<div class="col-md-3">
								<label class="form-label d-md-block d-none">&nbsp;</label>
								<input type="text" class="form-control" id="pu_doc_number" name="doc_number" placeholder="<?= $this->lang->line('w_number') ?>" value="<?= $person->doc_number ?>" readonly>
								<div class="sys_msg" id="pu_doc_number_msg"></div>
							</div>
							<div class="col-md-6">
								<label class="form-label"><?= $this->lang->line('w_name') ?></label>
								<input type="text" class="form-control" id="pu_name" name="name" value="<?= $person->name ?>" readonly>
								<div class="sys_msg" id="pu_name_msg"></div>
							</div>
							<div class="col-md-4">
								<label class="form-label"><?= $this->lang->line('w_tel') ?></label>
								<input type="text" class="form-control" id="pu_tel" name="tel" value="<?= $person->tel ?>" readonly>
								<div class="sys_msg" id="pu_tel_msg"></div>
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
								<input type="hidden" id="p_birthday" name="birthday" value="<?= $b ?>" readonly>
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
								<select class="form-select" name="sex_id" disabled>
									<option value="" selected="">--</option>
									<?php foreach($sex_ops as $item){
									if ($item->id == $person->sex_id) $s = "selected"; else $s = ""; ?>
									<option value="<?= $item->id ?>" <?= $s ?>><?= $item->description ?></option>
									<?php } ?>
								</select>
								<div class="sys_msg" id="pu_sex_msg"></div>
							</div>
							<div class="col-md-2">
								<label class="form-label"><?= $this->lang->line('w_blood_type') ?></label>
								<select class="form-select" name="blood_type_id" disabled>
									<option value="" selected="">--</option>
									<?php foreach($blood_type_ops as $item){
									if ($item->id == $person->blood_type_id) $s = "selected"; else $s = ""; ?>
									<option value="<?= $item->id ?>" <?= $s ?>><?= $item->description ?></option>
									<?php } ?>
								</select>
								<div class="sys_msg" id="pu_blood_type_msg"></div>
							</div>
							<div class="col-md-4">
								<label class="form-label"><?= $this->lang->line('w_email') ?></label>
								<input type="email" class="form-control" name="email" placeholder="email@example.com" value="<?= $person->email ?>" readonly>
								<div class="sys_msg" id="pu_email_msg"></div>
							</div>
							<div class="col-md-8">
								<label class="form-label"><?= $this->lang->line('w_address') ?></label>
								<input type="text" class="form-control" name="address" value="<?= $person->address ?>" readonly>
								<div class="sys_msg" id="pu_address_msg"></div>
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
										<th><?= $this->lang->line('w_doctor') ?></th>
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
											<div class="text-nowrap"><?= date("h:i A", strtotime($item->schedule_from)) ?></div>
										</td>
										<td>
											<?= $doctors_arr[$item->doctor_id]->name ?><br/>
											<?= $specialty_arr[$item->specialty_id] ?>
										</td>
										<td>
											<span class="text-<?= $status_arr[$item->status_id]->color ?>"><?= $this->lang->line($status_arr[$item->status_id]->code) ?></span>
										</td>
										<td>
											<div class="text-end">
												<a href="<?= base_url() ?>clinic/appointment/detail/<?= $item->id ?>" class="btn btn-primary btn-sm"><i class="bi bi-search"></i></a>
											</div>
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
						<?php }else{ ?>
						<div><?= $this->lang->line('t_no_appointment') ?></div>
						<?php } ?>
					</div>
					<div class="tab-pane fade" id="bordered-surgeries" role="tabpanel" aria-labelledby="surgeries-tab">
						<?php if ($surgeries){ ?>
						<div class="table-responsive">
							<table class="table datatable">
								<thead>
									<tr>
										<th><?= $this->lang->line('w_date') ?></th>
										<th><?= $this->lang->line('w_time') ?></th>
										<th><?= $this->lang->line('w_room') ?></th>
										<th><?= $this->lang->line('w_doctor') ?></th>
										<th><?= $this->lang->line('w_status') ?></th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($surgeries as $item){ ?>
									<tr>
										<td>
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
										<td class="text-end">
											<a href="<?= base_url() ?>clinic/surgery/detail/<?= $item->id ?>" class="btn btn-primary btn-sm">
												<i class="bi bi-search"></i>
											</a>
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
						<?php }else{ ?>
						<div><?= $this->lang->line('t_no_surgery') ?></div>
						<?php } ?>
					</div>
					<div class="tab-pane fade" id="bordered-sales" role="tabpanel" aria-labelledby="sales-tab">
						<?php if ($sales){ ?>
						<div class="table-responsive">
							<table class="table datatable">
								<thead>
									<tr>
										<th><?= $this->lang->line('w_date') ?></th>
										<th><?= $this->lang->line('w_client') ?></th>
										<th><?= $this->lang->line('w_total') ?></th>
										<th><?= $this->lang->line('w_balance') ?></th>
										<th><?= $this->lang->line('w_status') ?></th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($sales as $s){ $cur = $currencies_arr[$s->currency_id]->description; ?>
									<tr>
										<td><?= $s->registed_at ?></td>
										<td><?= $person->name ?></td>
										<td><?= $cur." ".number_format($s->total, 2) ?></td>
										<td><?php if ($s->balance) echo $cur." ".number_format($s->balance, 2); else echo "-"; ?></td>
										<td>
											<span class="text-<?= $status_arr[$s->status_id]->color ?>">
												<?= $this->lang->line($status_arr[$s->status_id]->code) ?>
											</span>
										</td>
										<td class="text-end">
											<a href="<?= base_url() ?>commerce/sale/detail/<?= $s->id ?>" class="btn btn-primary btn-sm">
												<i class="bi bi-search"></i>
											</a>
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
						<?php }else{ ?>
						<div><?= $this->lang->line('t_no_sale') ?></div>
						<?php } ?>
					</div>
					<div class="tab-pane fade" id="bordered-files" role="tabpanel" aria-labelledby="files-tab">
						<?php if ($patient_files){ ?>
						<div class="table-responsive">
							<table class="table datatable">
								<thead>
									<tr>
										<th><?= $this->lang->line('w_date') ?></th>
										<th><?= $this->lang->line('w_title') ?></th>
										<th><?= $this->lang->line('w_type') ?></th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php $file_path = base_url()."uploaded/pacientes/".str_replace(" ", "_", $person->name)."_".$person->doc_number."/"; foreach($patient_files as $item){ ?>
									<tr>
										<td><?= $item->registed_at ?></td>
										<td><?= $item->title ?></td>
										<td><?= explode(".", $item->filename)[1] ?></td>
										<td>
											<div class="text-end">
												<a href="<?= $file_path.$item->filename ?>" target="_blank" class="btn btn-primary btn-sm">
													<i class="bi bi-search"></i>
												</a>
												<button type="button" class="btn btn-danger btn-sm btn_delete_file" value="<?= $item->id ?>">
													<i class="bi bi-trash"></i>
												</button>
											</div>
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
						<?php }else{ ?>
						<div class="col-md-12"><?= $this->lang->line('t_no_file') ?></div>
						<?php } ?>
					</div>
					<div class="tab-pane fade" id="bordered-credit" role="tabpanel" aria-labelledby="credit-tab">
						<div class="row">
							<?php $colors = ["primary", "secondary", "info", "success", "danger"]; 
							foreach($credits as $i => $item){ ?>
							<div class="col-md-6">
								<div class="widget-stat card">
									<div class="card-body p-4">
										<div class="media ai-icon">
											<span class="mr-3 bgl-<?= $colors[$i] ?> text-<?= $colors[$i] ?>">
												<?= $currencies_arr[$item->currency_id]->description ?>
											</span>
											<div class="media-body">
												<p class="mb-1"><?= $item->updated_at ?></p>
												<h4 class="text-<?= $colors[$i] ?> mb-0">
													<?= number_format($item->balance, 2) ?>
												</h4>
											</div>
										</div>
									</div>
								</div>
							</div>
							<?php } ?>
						</div>
						<div class="row">
							<?php if ($credit_histories){ ?>
							<div class="table-responsive">
								<table class="table datatable">
									<thead>
										<tr>
											<th><?= $this->lang->line('w_date') ?></th>
											<th><?= $this->lang->line('w_amount') ?></th>
											<th><?= $this->lang->line('w_remark') ?></th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach($credit_histories as $item){ ?>
										<tr>
											<td><?= $item->registed_at ?></td>
											<?php if ($item->amount > 0){ $c = "success"; $s = "+"; }
											else{ $c = "danger"; $s = "-"; } ?>
											<td class="text-<?= $c ?>"><?= $s." ".$currencies_arr[$item->currency_id]->description." ".number_format(abs($item->amount), 2) ?></td>
											<td><?= $item->remark ?></td>
											<td>
												<div class="text-end">
													<?php if (!$item->is_reversed){ ?>
													<button type="button" class="btn btn-danger btn-sm btn_reverse_credit" value="<?= $item->id ?>">
														<i class="bi bi-trash"></i>
													</button>
													<?php } ?>
												</div>
											</td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
							<?php }else{ ?>
							<div><?= $this->lang->line('t_no_credit_history') ?></div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="md_weekly_doctor_agenda" tabindex="-1">
	<div class="modal-dialog modal-fullscreen">
		<div class="modal-content">
			<div class="modal-body">
				<div id="bl_weekly_schedule"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-bs-dismiss="modal"><?= $this->lang->line('btn_close') ?></button>
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


<div class="modal fade md_add_credit" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<form class="modal-content" id="form_add_credit">
			<input type="hidden" name="person_id" value="<?= $person->id ?>" readonly>
			<div class="modal-header pb-0 border-0">
				<h5 class="modal-title"><?= $this->lang->line('w_add_credit') ?></h5>
				<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
			</div>
			<div class="modal-body">
				<div class="form-row">
					<div class="col-md-4">
						<label class="form-label"><?= $this->lang->line('w_currency') ?></label>
						<select class="form-control" name="currency_id">
							<option value="">--</option>
							<?php foreach($currencies as $item){ ?>
							<option value="<?= $item->id ?>"><?= $item->description ?></option>
							<?php } ?>
						</select>
						<div class="sys_msg" id="ac_currency_msg"></div>
					</div>
					<div class="col-md-8">
						<label class="form-label"><?= $this->lang->line('w_amount') ?></label>
						<input type="text" class="form-control" name="amount" value="0">
						<div class="sys_msg" id="ac_amount_msg"></div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger light" data-dismiss="modal">
					<?= $this->lang->line('btn_close') ?>
				</button>
				<button type="submit" class="btn btn-primary">
					<?= $this->lang->line('btn_add') ?>
				</button>
			</div>
		</form>
	</div>
</div>