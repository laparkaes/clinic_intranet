<div class="col-md-12">
	<div class="welcome-text d-md-none d-block">
		<h4 class="text-primary mb-3"><?= $this->lang->line('appointments') ?></h4>
	</div>
</div>
<div class="col-sm-6">
	<div class="btn-group mb-3">
		<button type="button" class="btn btn-primary control_bl" id="btn_list" value="bl_list">
			<i class="fas fa-list mr-2"></i><?= $this->lang->line('btn_list') ?>
		</button>
		<button type="button" class="btn btn-outline-primary control_bl" id="btn_add" value="bl_add">
			<i class="fas fa-plus mr-2"></i><?= $this->lang->line('btn_add') ?>
		</button>
	</div>
</div>
<div class="col-sm-6">
	<form>
		<div class="form-row">
			<input type="hidden" value="1" name="page">
			<div class="form-group col-sm-4">
				<select class="form-control" id="sl_status" name="status">
					<option value=""><?= $this->lang->line('lb_status') ?></option>
					<?php foreach($status as $item){ if ($item->id == $f_url["status"]) $s = "selected"; else $s = ""; ?>
					<option value="<?= $item->id ?>" <?= $s ?>><?= $this->lang->line($item->code) ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="form-group col-sm-6">
				<input type="text" class="form-control date_picker_all bw" id="inp_date" name="date" placeholder="<?= $this->lang->line('lb_date') ?>" value="<?= $f_url["date"] ?>">
			</div>
			<div class="form-group col-sm-2">
				<button type="submit" class="btn btn-primary btn-block">
					<i class="far fa-search"></i>
				</button>
			</div>
		</div>
	</form>
</div>
<div class="col-md-12">
	<div class="card">
		<div class="card-body">
			<div class="row bl_content" id="bl_list">
				<div class="col-md-12">
					<?php if ($appointments){ ?>
					<div class="table-responsive">
						<table class="table table-responsive-md">
							<thead>
								<tr>
									<th><strong>#</strong></th>
									<th><strong><?= $this->lang->line('hd_itinerary') ?></strong></th>
									<th><strong><?= $this->lang->line('hd_specialty') ?></strong></th>
									<th><strong><?= $this->lang->line('hd_doctor') ?> / <?= $this->lang->line('hd_patient') ?></strong></th>
									<th><strong><?= $this->lang->line('hd_status') ?></strong></th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($appointments as $i => $item){ ?>
								<tr>
									<td><strong><?= number_format(($f_url["page"] - 1) * 25 + 1 + $i) ?></strong></td>
									<td>
										<div class="text-nowrap"><?= date("h:i A", strtotime($item->schedule_from)); ?></div>
										<div><?= date("Y-m-d", strtotime($item->schedule_from)); ?></div>
									</td>
									<td><?= $item->specialty ?></td>
									<td><?= $item->doctor ?><br/>/ <?= $item->patient ?></td>
									<td><span class="text-<?= $status_arr[$item->status_id]->color ?>"><?= $this->lang->line($status_arr[$item->status_id]->code) ?></span></td>
									<td class="text-right">
										<a href="<?= base_url() ?>appointment/detail/<?= $item->id ?>">
											<button type="button" class="btn btn-info light sharp">
												<i class="fas fa-arrow-alt-right"></i>
											</button>
										</a>
									</td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
						<div class="btn-group" role="group" aria-label="paging">
							<?php foreach($paging as $p){
							$f_url["page"] = $p[0]; ?>
							<a href="<?= base_url() ?>appointment?<?= http_build_query($f_url) ?>" class="btn btn-<?= $p[2] ?>">
								<?= $p[1] ?>
							</a>
							<?php } ?>
						</div>
					</div>
					<?php }else{ ?>
					<h5 class="text-danger mt-3"><?= $this->lang->line('msg_no_appointments') ?></h5>
					<?php } ?>
				</div>
			</div>
			<div class="row bl_content d-none" id="bl_add">
				<div class="col-md-6 mb-3">
					<form action="#" id="register_form">
						<h5 class="mb-3"><?= $this->lang->line('title_attention') ?></h5>
						<div class="form-row">
							<div class="form-group col-md-6">
								<label><?= $this->lang->line('lb_specialty') ?></label>
								<select class="form-control" id="aa_specialty" name="app[specialty_id]">
									<option value="">--</option>
									<?php foreach($specialties as $item){ if ($item->doctor_qty){ ?>
									<option value="<?= $item->id ?>"><?= $item->name ?></option>
									<?php }} ?>
								</select>
								<div class="sys_msg" id="aa_specialty_msg"></div>
							</div>
							<div class="form-group col-md-6">
								<label>
									<span class="mr-1"><?= $this->lang->line('lb_doctor') ?></span>
									<span><i class="far fa-clock" id="ic_doctor_schedule_w" data-toggle="modal" data-target=".md_weekly_doctor_agenda"></i></span>
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
										<button class="btn btn-primary border-0" type="button" id="btn_search_pt">
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
							<div class="form-group col-md-12 pt-3">
								<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_register') ?></button>
							</div>
						</div>
					</form>
				</div>
				<div class="col-md-6 mb-3">
					<h5 class="mb-3"><?= $this->lang->line('title_doctor_agenda') ?></h5>
					<div id="aa_schedule"></div>
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
<div class="d-none">
	<input type="hidden" id="warning_rap" value="<?= $this->lang->line('warning_rap') ?>">
</div>