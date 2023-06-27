<div class="col-md-12">
	<div class="welcome-text d-md-none d-block">
		<h4 class="text-primary mb-3"><?= $this->lang->line('accounts') ?></h4>
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
			<div class="form-group col-sm-4">
				<select class="form-control" id="sl_role_id" name="role_id">
					<option value=""><?= $this->lang->line('sl_role') ?></option>
					<?php foreach($roles as $item){
						if ($item->id == $f_url["role_id"]) $s = "selected"; else $s = ""; ?>
					<option value="<?= $item->id ?>" <?= $s ?>><?= $this->lang->line($item->name) ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="form-group col-sm-6">
				<input type="text" class="form-control" id="inp_person_name" name="person_name" placeholder="<?= $this->lang->line('txt_person_name') ?>" value="<?= $f_url["person_name"] ?>">
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
					<?php if ($accounts){ ?>
					<div class="table-responsive">
						<table class="table table-responsive-md">
							<thead>
								<tr>
									<th><strong>#</strong></th>
									<th><strong><?= $this->lang->line('hd_role') ?></strong></th>
									<th><strong><?= $this->lang->line('hd_email') ?></strong></th>
									<th><strong><?= $this->lang->line('hd_name') ?></strong></th>
									<th></th>
								</tr>
							</thead>
							<tbody id="account_list">
								<?php foreach($accounts as $i => $item){ ?>
								<tr>
									<td><strong><?= number_format(($f_url["page"] - 1) * 25 + 1 + $i) ?></strong></td>
									<td><?= $item->role ?></td>
									<td><?= $item->email ?></td>
									<td><?= $item->person ?></td>
									<td class="text-right">
										<button type="button" class="btn btn-info light sharp btn_reset_password" value="<?= $item->id ?>">
											<i class="fas fa-key"></i>
										</button>
										<button type="button" class="btn btn-danger light sharp btn_remove_account" value="<?= $item->id ?>">
											<i class="fas fa-trash"></i>
										</button>
									</td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
						<div class="btn-group" role="group" aria-label="paging">
							<?php foreach($paging as $p){
							$f_url["page"] = $p[0]; ?>
							<a href="<?= base_url() ?>account?<?= http_build_query($f_url) ?>" class="btn btn-<?= $p[2] ?>">
								<?= $p[1] ?>
							</a>
							<?php } ?>
						</div>
					</div>
					<?php }else{ ?>
					<h5 class="text-danger mt-3"><?= $this->lang->line('msg_no_accounts') ?></h5>
					<?php } ?>
				</div>
			</div>
			<div class="row bl_content d-none" id="bl_add">
				<div class="col-md-12">
					<form class="form-row" id="form_register_account" action="#">
						<div class="col-md-6 col-sm-12">
							<h5><?= $this->lang->line('title_personal_info') ?></h5>
							<div class="form-row">
							
								<div class="form-group col-md-6">
									<label><?= $this->lang->line('lb_document') ?></label>
									<select class="form-control" id="ra_doc_type_id" name="p[doc_type_id]">
										<?php foreach($doc_types as $d){ if ($d->sunat_code){ ?>
										<option value="<?= $d->id ?>"><?= $d->description ?></option>
										<?php }} ?>
									</select>
									<div class="sys_msg" id="ra_doc_type_msg"></div>
								</div>
								<div class="form-group col-md-6">
									<label class="d-md-block d-none">&nbsp;</label>
									<div class="input-group">
										<input type="text" class="form-control" id="ra_doc_number" name="p[doc_number]" placeholder="<?= $this->lang->line('lb_number') ?>">
										<div class="input-group-append">
											<button class="btn btn-primary border-0" type="button" id="btn_search_person_ra">
												<i class="fas fa-search"></i>
											</button>
										</div>
									</div>
									<div class="sys_msg" id="ra_doc_number_msg"></div>
								</div>
								<div class="form-group col-md-8">
									<label><?= $this->lang->line('lb_name') ?></label>
									<input type="text" class="form-control" id="ra_name" name="p[name]">
									<div class="sys_msg" id="ra_name_msg"></div>
								</div>
								<div class="form-group col-md-4">
									<label><?= $this->lang->line('lb_tel') ?></label>
									<input type="text" class="form-control" id="ra_tel" name="p[tel]">
									<div class="sys_msg" id="ra_tel_msg"></div>
								</div>
							</div>
						</div>
						<div class="col-md-6 col-sm-12">
							<h5><?= $this->lang->line('title_account') ?></h5>
							<div class="form-row">
								<div class="form-group col-md-4">
									<label><?= $this->lang->line('lb_role') ?></label>
									<select class="form-control" name="a[role_id]">
										<option value="" selected>--</option>
										<?php foreach($roles as $item){ ?>
										<option value="<?= $item->id ?>"><?= $this->lang->line($item->name) ?></option>
										<?php } ?>
									</select>
									<div class="sys_msg" id="ra_role_msg"></div>
								</div>
								<div class="form-group col-md-8">
									<label><?= $this->lang->line('lb_email') ?></label>
									<input type="email" class="form-control" id="ra_email" name="a[email]" placeholder="email@example.com">
									<div class="sys_msg" id="ra_email_msg"></div>
								</div>
								<div class="form-group col-md-6">
									<label><?= $this->lang->line('lb_password') ?></label>
									<input type="password" class="form-control" name="a[password]">
									<div class="sys_msg" id="ra_password_msg"></div>
								</div>
								<div class="form-group col-md-6">
									<label><?= $this->lang->line('lb_confirm') ?></label>
									<input type="password" class="form-control" name="a[confirm]">
									<div class="sys_msg" id="ra_confirm_msg"></div>
								</div>
							</div>
						</div>
						<div class="col-md-12 pt-3">
							<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_register') ?></button>
						</div>
					</form>
				
				</div>
			</div>
		</div>
	</div>
</div>
<input type="hidden" id="warning_rac" value="<?= $this->lang->line('warning_rac') ?>">
<input type="hidden" id="warning_rpa" value="<?= $this->lang->line('warning_rpa') ?>">