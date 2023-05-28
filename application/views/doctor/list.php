<div class="col-sm-12 d-flex justify-content-between align-items-center pb-3">
	<div class="welcome-text d-md-none d-block">
		<h4 class="text-primary mb-0"><?= $this->lang->line('doctors') ?></h4>
	</div>
	<div class="btn-group">
		<button type="button" class="btn btn-primary control_bl" id="btn_list" value="bl_list">
			<i class="fas fa-list mr-2"></i><?= $this->lang->line('btn_list') ?>
		</button>
		<button type="button" class="btn btn-outline-primary control_bl" value="bl_add">
			<i class="fas fa-plus mr-2"></i><?= $this->lang->line('btn_add') ?>
		</button>
	</div>
</div>
<div class="col-md-12">
	<div class="card">
		<div class="card-body">
			<div class="row bl_content" id="bl_list">
				<div class="col-md-12 d-md-flex justify-content-end">
					<form class="form-inline">
						<input type="hidden" value="1" name="page">
						<label class="sr-only" for="sl_type"><?= $this->lang->line('sl_specialty') ?></label>
						<select class="form-control mb-2 mr-sm-2" id="sl_type" name="specialty" style="max-width: 150px;">
							<option value=""><?= $this->lang->line('sl_specialty') ?></option>
							<?php foreach($specialties as $item){
								if ($item->id == $f_url["specialty"]) $s = "selected"; else $s = ""; ?>
							<option value="<?= $item->id ?>" <?= $s ?>><?= $item->name ?></option>
							<?php } ?>
						</select>
						<label class="sr-only" for="inp_name"><?= $this->lang->line('lb_name') ?></label>
						<input type="text" class="form-control mb-2 mr-sm-2" id="inp_name" name="name" placeholder="<?= $this->lang->line('lb_search_by_name') ?>" value="<?= $f_url["name"] ?>">
						<button type="submit" class="btn btn-primary mb-2">
							<i class="far fa-search"></i>
						</button>
					</form>
				</div>
				<div class="col-md-12">
					<?php if ($doctors){ ?>
					<div class="table-responsive">
						<table class="table table-responsive-md">
							<thead>
								<tr>
									<th><strong>#</strong></th>
									<th><strong><?= $this->lang->line('hd_license') ?></strong></th>
									<th><strong><?= $this->lang->line('hd_specialty') ?></strong></th>
									<th><strong><?= $this->lang->line('hd_name') ?></strong></th>
									<th><strong><?= $this->lang->line('hd_tel') ?></strong></th>
									<th><strong><?= $this->lang->line('hd_status') ?></strong></th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($doctors as $i => $item){ ?>
								<tr>
									<td><strong><?= number_format(($f_url["page"] - 1) * 25 + 1 + $i) ?></strong></td>
									<td><?= $item->license ?></td>
									<td style="max-width: 150px;"><?= $specialties_arr[$item->specialty_id] ?></td>
									<td><?= $item->person->name ?></td>
									<td><?= $item->person->tel ?></td>
									<td><span class="badge light badge-<?= $status[$item->status_id]->color ?>"><?= $status[$item->status_id]->text ?></span></td>
									<td class="text-right">
										<a href="<?= base_url() ?>doctor/detail/<?= $item->id ?>">
											<button type="button" class="btn btn-primary light sharp border-0">
												<i class="far fa-search"></i>
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
							<a href="<?= base_url() ?>doctor?<?= http_build_query($f_url) ?>" class="btn btn-<?= $p[2] ?>">
								<?= $p[1] ?>
							</a>
							<?php } ?>
						</div>
					</div>
					<?php }else{ ?>
					<h5 class="text-danger mt-3"><?= $this->lang->line('msg_no_doctors') ?></h5>
					<?php } ?>
				</div>
			</div>
			<div class="row bl_content d-none" id="bl_add">
				<div class="col-md-12">
					<form class="form-row" id="register_form" action="#">
						<div class="col-md-6 col-sm-12">
							<h5><?= $this->lang->line('title_personal_info') ?></h5>
							<div class="form-row">
								<div class="form-group col-md-6">
									<label><?= $this->lang->line('lb_document') ?></label>
									<select class="form-control" id="dn_doc_type_id" name="personal[doc_type_id]">
										<?php foreach($doc_types as $d){ if ($d->sunat_code){ ?>
										<option value="<?= $d->id ?>"><?= $d->description ?></option>
										<?php }} ?>
									</select>
									<div class="sys_msg" id="dn_doc_type_msg"></div>
								</div>
								<div class="form-group col-md-6">
									<label class="d-md-block d-none">&nbsp;</label>
									<div class="input-group">
										<input type="text" class="form-control" id="dn_doc_number" name="personal[doc_number]" placeholder="<?= $this->lang->line('lb_number') ?>">
										<div class="input-group-append">
											<button class="btn btn-primary border-0" type="button" id="btn_search_person_dn">
												<i class="fas fa-search"></i>
											</button>
										</div>
									</div>
									<div class="sys_msg" id="dn_doc_number_msg"></div>
								</div>
								<div class="form-group col-md-8">
									<label><?= $this->lang->line('lb_name') ?></label>
									<input type="text" class="form-control" id="dn_name" name="personal[name]">
									<div class="sys_msg" id="dn_name_msg"></div>
								</div>
								<div class="form-group col-md-4">
									<label><?= $this->lang->line('lb_tel') ?></label>
									<input type="text" class="form-control" id="dn_tel" name="personal[tel]">
									<div class="sys_msg" id="dn_tel_msg"></div>
								</div>
								<div class="form-group col-md-4">
									<label><?= $this->lang->line('lb_birthday') ?></label>
									<input type="text" class="form-control bw date_picker_all" name="personal[birthday]" readonly="">
									<div class="sys_msg" id="dn_birthday_msg"></div>
								</div>
								<div class="form-group col-md-4">
									<label><?= $this->lang->line('lb_sex') ?></label>
									<select class="form-control" name="personal[sex_id]">
										<option value="" selected="">--</option>
										<?php foreach($sex_ops as $item){ ?>
										<option value="<?= $item->id ?>"><?= $item->description ?></option>
										<?php } ?>
									</select>
									<div class="sys_msg" id="dn_sex_msg"></div>
								</div>
								<div class="form-group col-md-4">
									<label><?= $this->lang->line('lb_blood_type') ?></label>
									<select class="form-control" name="personal[blood_type_id]">
										<option value="" selected="">--</option>
										<?php foreach($blood_type_ops as $item){ ?>
										<option value="<?= $item->id ?>"><?= $item->description ?></option>
										<?php } ?>
									</select>
									<div class="sys_msg" id="dn_blood_type_msg"></div>
								</div>
								<div class="form-group col-md-12">
									<label><?= $this->lang->line('lb_address') ?></label>
									<input type="text" class="form-control" name="personal[address]">
									<div class="sys_msg" id="dn_address_msg"></div>
								</div>
							</div>
						</div>
						<div class="col-md-6 col-sm-12">
							<h5><?= $this->lang->line('title_additional_data') ?></h5>
							<div class="form-row">
								<div class="form-group col-md-6">
									<label><?= $this->lang->line('lb_specialty') ?></label>
									<select class="form-control" name="doctor[specialty_id]">
										<option value="" selected><?= $this->lang->line('text_select') ?>...</option>
										<?php foreach($specialties as $item){ ?>
										<option value="<?= $item->id ?>"><?= $item->name ?></option>
										<?php } ?>
									</select>
									<div class="sys_msg" id="dn_specialty_msg"></div>
								</div>
								<div class="form-group col-md-6">
									<label><?= $this->lang->line('lb_license_number') ?></label>
									<input type="text" class="form-control" name="doctor[license]">
									<div class="sys_msg" id="dn_license_msg"></div>
								</div>
								<div class="form-group col-md-12">
									<label><?= $this->lang->line('lb_username') ?></label>
									<input type="email" class="form-control" name="account[email]" placeholder="email@example.com">
									<div class="sys_msg" id="dn_email_msg"></div>
								</div>
								<div class="form-group col-md-6">
									<label><?= $this->lang->line('lb_password') ?></label>
									<input type="password" class="form-control" name="account[password]">
									<div class="sys_msg" id="dn_password_msg"></div>
								</div>
								<div class="form-group col-md-6">
									<label><?= $this->lang->line('lb_confirm') ?></label>
									<input type="password" class="form-control" name="account[confirm]">
									<div class="sys_msg" id="dn_confirm_msg"></div>
								</div>
							</div>
						</div>
						<div class="col-md-12 col-sm-12 pt-3">
							<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_register') ?></button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>