<div class="col-md-12">
	<div class="welcome-text d-md-none d-block">
		<h4 class="text-primary mb-3"><?= $this->lang->line('patients') ?></h4>
	</div>
</div>
<div class="col-sm-6">
	<div class="btn-group mb-3">
		<button type="button" class="btn btn-primary control_bl" id="btn_list" value="bl_list">
			<i class="fas fa-list mr-2"></i><?= $this->lang->line('btn_list') ?>
		</button>
		<button type="button" class="btn btn-outline-primary control_bl" value="bl_add">
			<i class="fas fa-plus mr-2"></i><?= $this->lang->line('btn_add') ?>
		</button>
	</div>
</div>
<div class="col-sm-6">
	<form>
		<div class="form-row">
			<input type="hidden" value="1" name="page">
			<div class="form-group col-sm-10">
				<input type="text" class="form-control" id="inp_search" name="keyword" placeholder="<?= $this->lang->line('w_search') ?>" value="<?= $f_url["keyword"] ?>">
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
					<?php if ($patients){ ?>
					<div class="table-responsive">
						<table class="table table-responsive-md">
							<thead>
								<tr>
									<th><strong>#</strong></th>
									<th><strong><?= $this->lang->line('w_document') ?></strong></th>
									<th><strong><?= $this->lang->line('w_name') ?></strong></th>
									<th><strong><?= $this->lang->line('w_tel') ?></strong></th>
									<th><strong><?= $this->lang->line('w_email') ?></strong></th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($patients as $i => $item){ ?>
								<tr>
									<td><strong><?= number_format(($f_url["page"] - 1) * 25 + 1 + $i) ?></strong></td>
									<td><?= $doc_types_arr[$item->doc_type_id]." ".$item->doc_number ?></td>
									<td><?= $item->name ?></td>
									<td><?= $item->tel ?></td>
									<td><?= $item->email ?></td>
									<td class="text-right">
										<a href="<?= base_url() ?>patient/detail/<?= $item->id ?>">
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
							<a href="<?= base_url() ?>patient?<?= http_build_query($f_url) ?>" class="btn btn-<?= $p[2] ?>">
								<?= $p[1] ?>
							</a>
							<?php } ?>
						</div>
					</div>
					<?php }else{ ?>
					<h5 class="text-danger mt-3"><?= $this->lang->line('t_no_patients') ?></h5>
					<?php } ?>
				</div>
			</div>
			<div class="row bl_content d-none" id="bl_add">
				<div class="col-md-12">
					<form id="form_register">
						<h4><?= $this->lang->line('w_patient_info') ?></h4>
						<div class="form-row mb-3">
							<div class="form-group col-md-3">
								<label><?= $this->lang->line('w_document') ?></label>
								<select class="form-control" id="pn_doc_type_id" name="doc_type_id">
									<?php foreach($doc_types as $d){ if ($d->sunat_code){ ?>
									<option value="<?= $d->id ?>"><?= $d->description ?></option>
									<?php }} ?>
								</select>
								<div class="sys_msg" id="pn_doc_type_msg"></div>
							</div>
							<div class="form-group col-md-3">
								<label class="d-md-block d-none">&nbsp;</label>
								<div class="input-group">
									<input type="text" class="form-control" id="pn_doc_number" name="doc_number" placeholder="<?= $this->lang->line('w_number') ?>">
									<div class="input-group-append">
										<button class="btn btn-primary border-0" type="button" id="btn_search_person_pn">
											<i class="fas fa-search"></i>
										</button>
									</div>
								</div>
								<div class="sys_msg" id="pn_doc_number_msg"></div>
							</div>
							<div class="form-group col-md-6">
								<label><?= $this->lang->line('w_name') ?></label>
								<input type="text" class="form-control" id="pn_name" name="name" readonly>
								<div class="sys_msg" id="pn_name_msg"></div>
							</div>
							<div class="form-group col-md-3">
								<label><?= $this->lang->line('w_tel') ?></label>
								<input type="text" class="form-control" id="pn_tel" name="tel">
								<div class="sys_msg" id="pn_tel_msg"></div>
							</div>
							<div class="form-group col-md-3">
								<label><?= $this->lang->line('w_birthday') ?></label>
								<input type="hidden" id="p_birthday" name="birthday" readonly="">
								<div class="input-group">
									<select class="form-control" id="p_birthday_d">
										<option value="" selected=""><?= $this->lang->line('date_d') ?></option>
										<?php for($i = 1; $i <= 31; $i++){ ?>
										<option value="<?= $i ?>"><?= $i ?></option>
										<?php } ?>
									</select>
									<select class="form-control" id="p_birthday_m">
										<option value="" selected=""><?= $this->lang->line('date_m') ?></option>
										<?php for($i = 1; $i <= 12; $i++){ ?>
										<option value="<?= $i ?>"><?= $i ?></option>
										<?php } ?>
									</select>
									<?php $now = date('Y'); ?>
									<select class="form-control" id="p_birthday_y">
										<option value="" selected=""><?= $this->lang->line('date_y') ?></option>
										<?php for($i = 0; $i <= 130; $i++){ ?>
										<option value="<?= $now - $i ?>"><?= $now - $i ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="sys_msg" id="pn_birthday_msg"></div>
							</div>
							<div class="form-group col-md-3">
								<label><?= $this->lang->line('w_sex') ?></label>
								<select class="form-control" name="sex_id">
									<option value="" selected="">--</option>
									<?php foreach($sex_ops as $item){ ?>
									<option value="<?= $item->id ?>"><?= $item->description ?></option>
									<?php } ?>
								</select>
								<div class="sys_msg" id="pn_sex_msg"></div>
							</div>
							<div class="form-group col-md-3">
								<label><?= $this->lang->line('w_blood_type') ?></label>
								<select class="form-control" name="blood_type_id">
									<option value="" selected="">--</option>
									<?php foreach($blood_type_ops as $item){ ?>
									<option value="<?= $item->id ?>"><?= $item->description ?></option>
									<?php } ?>
								</select>
								<div class="sys_msg" id="pn_blood_type_msg"></div>
							</div>
							<div class="form-group col-md-4">
								<label><?= $this->lang->line('w_email') ?></label>
								<input type="email" class="form-control" name="email" placeholder="email@example.com">
								<div class="sys_msg" id="pn_email_msg"></div>
							</div>
							<div class="form-group col-md-8">
								<label><?= $this->lang->line('w_address') ?></label>
								<input type="text" class="form-control" name="address">
								<div class="sys_msg" id="pn_address_msg"></div>
							</div>
						</div>
						<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_register') ?></button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>