<div class="d-flex justify-content-between align-items-start">
	<div class="pagetitle">
		<h1><?= $title ?></h1>
		<nav>
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= $this->lang->line('w_home') ?></a></li>
				<li class="breadcrumb-item active"><?= $title ?></li>
			</ol>
		</nav>
	</div>
	<div class="btn-group mb-3">
		<button type="button" class="btn btn-primary control_bl" id="btn_list" value="bl_list">
			<i class="bi bi-card-list"></i>
		</button>
		<button type="button" class="btn btn-outline-primary control_bl" value="bl_add">
			<i class="bi bi-plus-lg"></i>
		</button>
	</div>
</div>
<form class="row d-flex justify-content-end g-3">
	<input type="hidden" value="1" name="page">
	<div class="col-md-auto col-12">
		<select class="form-select" id="sl_status" name="status">
			<option value=""><?= $this->lang->line('w_status') ?></option>
			<?php foreach($status as $item){ if ($item->id == $f_url["status"]) $s = "selected"; else $s = ""; ?>
			<option value="<?= $item->id ?>" <?= $s ?>><?= $this->lang->line($item->code) ?></option>
			<?php } ?>
		</select>
	</div>
	<div class="col-md-auto col-12">
		<input type="text" class="form-control" name="keyword" placeholder="<?= $this->lang->line('w_patient_name') ?>" value="<?= $f_url["keyword"] ?>">
	</div>
	<div class="col-md-auto col-12 text-center d-grid gap-2">
		<button type="submit" class="btn btn-primary btn-block">
			<i class="bi bi-search"></i>
		</button>
  </div>
</form>
<div class="row mt-3">
	<div class="col">
		<div class="card bl_content" id="bl_list">
			<div class="card-body">
				<h5 class="card-title"><?= $this->lang->line('w_list') ?></h5>
				<?php if ($appointments){ ?>
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<th><strong>#</strong></th>
								<th><strong><?= $this->lang->line('w_itinerary') ?></strong></th>
								<th><strong><?= $this->lang->line('w_specialty') ?></strong></th>
								<th><strong><?= $this->lang->line('w_doctor') ?> / <?= $this->lang->line('w_patient') ?></strong></th>
								<th><strong><?= $this->lang->line('w_status') ?></strong></th>
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
								<td class="text-end">
									<a href="<?= base_url() ?>appointment/detail/<?= $item->id ?>" class="btn btn-primary btn-sm">
										<i class="bi bi-arrow-right"></i>
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
				<h5 class="text-danger mt-3"><?= $this->lang->line('t_no_appointments') ?></h5>
				<?php } ?>
			</div>
		</div>
		<div class="card bl_content d-none" id="bl_add">
			<div class="card-body">
				<h5 class="card-title"><?= $this->lang->line('w_add_appointment') ?></h5>
				<div class="row">
					<div class="col-md-6">
						<form class="row g-3" id="register_form">
							<div class="col-md-12">
								<strong><?= $this->lang->line('w_attention') ?></strong>
							</div>
							<div class="col-md-12">
								<label class="form-label"><?= $this->lang->line('w_specialty') ?></label>
								<select class="form-select" id="aa_specialty" name="app[specialty_id]">
									<option value="">--</option>
									<?php foreach($specialties as $item){ if ($item->doctor_qty){ ?>
									<option value="<?= $item->id ?>"><?= $item->name ?></option>
									<?php }} ?>
								</select>
								<div class="sys_msg" id="aa_specialty_msg"></div>
							</div>
							<div class="col-md-12">
								<label class="form-label">
									<span><?= $this->lang->line('w_doctor') ?></span>
									<i class="bi bi-clock ms-2" id="ic_doctor_schedule_w" data-bs-toggle="modal" data-bs-target="#md_weekly_doctor_agenda"></i>
								</label>
								<select class="form-select" id="aa_doctor" name="app[doctor_id]">
									<option value="">--</option>
									<?php foreach($doctors as $item){ ?>
									<option class="spe spe_<?= $item->specialty_id ?> d-none" value="<?= $item->person_id ?>"><?= $item->name ?></option>
									<?php } ?>
								</select>
								<div class="sys_msg" id="aa_doctor_msg"></div>
							</div>
							<div class="col-md-4">
								<label class="form-label"><?= $this->lang->line('w_date') ?></label>
								<input type="text" class="form-control date_picker" id="aa_date" name="sch[date]" value="<?= date('Y-m-d') ?>">
								<div class="sys_msg" id="aa_date_msg"></div>
							</div>
							<div class="col-md-8">
								<label class="form-label"><?= $this->lang->line('w_time') ?></label>
								<div class="input-group">
									<select class="form-select" id="aa_hour" name="sch[hour]">
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
									<select class="form-select" id="aa_min" name="sch[min]">
										<option value="" selected>--</option>
										<option value="00">00</option>
										<option value="15">15</option>
										<option value="30">30</option>
										<option value="45">45</option>
									</select>
								</div>
								<div class="sys_msg" id="aa_schedule_msg"></div>
							</div>
							<div class="col-md-12 pt-3">
								<strong><?= $this->lang->line('w_patient') ?></strong>
								<input type="hidden" id="aa_pt_id" name="app[patient_id]" value="">
							</div>
							<div class="col-md-6">
								<label class="form-label"><?= $this->lang->line('w_document') ?></label>
								<select class="form-select" id="aa_pt_doc_type_id" name="pt[doc_type_id]">
									<?php foreach($doc_types as $item){ if ($item->sunat_code){ ?>
									<option value="<?= $item->id ?>"><?= $item->description ?></option>
									<?php }} ?>
								</select>
								<div class="sys_msg" id="aa_pt_doc_type_msg"></div>
							</div>
							<div class="col-md-6">
								<label class="form-label d-md-block d-none">&nbsp;</label>
								<div class="input-group">
									<input type="text" class="form-control" id="aa_pt_doc_number" name="pt[doc_number]" placeholder="<?= $this->lang->line('w_number') ?>">
									<button class="btn btn-primary" type="button" id="btn_search_pt">
										<i class="bi bi-search"></i>
									</button>
								</div>
								<div class="sys_msg" id="aa_pt_doc_number_msg"></div>
							</div>
							<div class="col-md-8">
								<label class="form-label"><?= $this->lang->line('w_name') ?></label>
								<input type="text" class="form-control" id="aa_pt_name" name="pt[name]" readonly>
								<div class="sys_msg" id="aa_pt_name_msg"></div>
							</div>
							<div class="col-md-4">
								<label class="form-label"><?= $this->lang->line('w_tel') ?></label>
								<input type="text" class="form-control" id="aa_pt_tel" name="pt[tel]">
								<div class="sys_msg" id="aa_pt_tel_msg"></div>
							</div>
							<div class="col-md-12">
								<label class="form-label"><?= $this->lang->line('w_remark') ?> (<?= $this->lang->line('w_optional') ?>)</label>
								<textarea class="form-control" rows="4" name="app[remark]" placeholder="<?= $this->lang->line('t_remark') ?>"></textarea>
							</div>
							<div class="col-md-12 pt-3">
								<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_register') ?></button>
							</div>
						</form>
					</div>
					<div class="col-md-6 mb-3">
						<h5 class="mb-3"><?= $this->lang->line('w_doctor_agenda') ?></h5>
						<div id="aa_schedule"></div>
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
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= $this->lang->line('btn_close') ?></button>
			</div>
		</div>
	</div>
</div>