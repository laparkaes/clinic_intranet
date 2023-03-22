<div class="col-md-12">
	<div class="row page-titles mx-0">
		<div class="col-sm-6 p-md-0">
			<div class="welcome-text">
				<h4><?= $this->lang->line('surgeries') ?></h4>
			</div>
		</div>
		<div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
			<div class="btn-group">
				<button type="button" class="btn btn-primary control_bl" id="btn_list" value="bl_list">
					<i class="fas fa-list"></i>
				</button>
				<button type="button" class="btn btn-outline-primary control_bl" value="bl_filter">
					<i class="fas fa-filter"></i>
				</button>
				<button type="button" class="btn btn-outline-primary control_bl" value="bl_add">
					<i class="fas fa-plus"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<div class="col-md-12">
	<div class="card">
		<div class="card-body">
			<div class="row bl_content" id="bl_list">
				<div class="col-md-2">
					<div class="mb-3" id="surgery_list_length_new"></div>
				</div>
				<div class="col-md-6"></div>
				<div class="col-md-4">
					<div class="mb-3" id="surgery_list_filter_new"></div>
				</div>
				<div class="col-md-12">
					<div class="table-responsive">
						<table id="surgery_list" class="table display">
							<thead>
								<tr>
									<th class="text-left pt-0 pl-0"><?= $this->lang->line('hd_schedule') ?></th>
									<th class="pt-0"><?= $this->lang->line('hd_place') ?></th>
									<th class="pt-0"><?= $this->lang->line('hd_specialty') ?></th>
									<th class="pt-0"><?= $this->lang->line('hd_doctor') ?></th>
									<th class="pt-0"><?= $this->lang->line('hd_patient') ?></th>
									<th class="pt-0"><?= $this->lang->line('hd_status') ?></th>
									<th class="text-right pt-0 pr-0"></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($surgeries as $item){ ?>
								<tr>
									<td class="text-left text-nowrap pl-0">
										<?= date("d.m.Y<\b\\r>h:i A", strtotime($item->schedule_from)) ?>
									</td>
									<td><?= $item->place ?></td>
									<td><?= $specialties_arr[$doctors_arr[$item->doctor_id]->specialty_id] ?></td>
									<td><?= $people_arr[$item->doctor_id] ?></td>
									<td><?= $people_arr[$item->patient_id] ?></td>
									<td><span class="text-<?= $status_arr[$item->status_id]->color ?>"><?= $this->lang->line($status_arr[$item->status_id]->code) ?></span></td>
									<td class="text-right pr-0">
										<a href="<?= base_url() ?>surgery/detail/<?= $item->id ?>">
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
			</div>
			<div class="row bl_content d-none" id="bl_filter">
				<div class="col-md-12">
					Filter Block
				</div>
			</div>
			<div class="row bl_content d-none" id="bl_add">
				<div class="col-md-12">
					<form action="#" id="register_form">
						<div class="row">
							<div class="col-md-6 mb-3">
								<h5><?= $this->lang->line('title_attention') ?></h5>
								<div class="form-row">
									<div class="form-group col-md-6">
										<label><?= $this->lang->line('lb_speciality') ?></label>
										<select class="form-control" id="sur_speciality" name="sur[speciality_id]">
											<option value="">--</option>
											<?php foreach($specialties as $item){ ?>
											<option value="<?= $item->id ?>"><?= $item->name ?></option>
											<?php } ?>
										</select>
										<div class="sys_msg" id="sur_speciality_msg"></div>
									</div>
									<div class="form-group col-md-6">
										<label><?= $this->lang->line('lb_doctor') ?></label>
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
											<tbody class="ap_content_list" id="sur_schedule_list"></tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="col-md-6 mb-3">
								<h5><?= $this->lang->line('title_patient') ?></h5>
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
                                                <button class="btn btn-primary border-0" type="button" id="btn_search_pt">
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