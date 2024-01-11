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
<form class="row justify-content-end">
		<input type="hidden" value="1" name="page">
		<div class="col-md-auto">
			<select class="form-select" id="sl_role_id" name="role_id">
				<option value=""><?= $this->lang->line('w_role') ?></option>
				<?php foreach($roles as $item){
					if ($item->id == $f_url["role_id"]) $s = "selected"; else $s = ""; ?>
				<option value="<?= $item->id ?>" <?= $s ?>><?= $this->lang->line($item->name) ?></option>
				<?php } ?>
			</select>
		</div>
		<div class="col-md-auto">
			<input type="text" class="form-control" id="inp_person_name" name="person_name" placeholder="<?= $this->lang->line('w_person_name') ?>" value="<?= $f_url["person_name"] ?>">
		</div>
		<div class="col-md-auto">
			<button type="submit" class="btn btn-primary">
				<i class="bi bi-search"></i>
			</button>
		</div>
	</div>
</form>
<div class="row mt-3">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<div class="bl_content" id="bl_list">
					<h5 class="card-title"><?= $this->lang->line('w_list') ?></h5>
					<?php if ($accounts){ ?>
					<div class="table-responsive">
						<table class="table">
							<thead>
								<tr>
									<th>#</th>
									<th><?= $this->lang->line('w_role') ?></th>
									<th><?= $this->lang->line('w_email') ?></th>
									<th><?= $this->lang->line('w_name') ?></th>
									<th></th>
								</tr>
							</thead>
							<tbody id="account_list">
								<?php foreach($accounts as $i => $item){ ?>
								<tr>
									<th><?= number_format(($f_url["page"] - 1) * 25 + 1 + $i) ?></th>
									<td><?= $item->role ?></td>
									<td><?= $item->email ?></td>
									<td><?= $item->person ?></td>
									<td class="text-end">
										<button type="button" class="btn btn-primary btn-sm btn_reset_password" value="<?= $item->id ?>">
											<i class="bi bi-key"></i>
										</button>
										<button type="button" class="btn btn-danger btn-sm btn_remove_account" value="<?= $item->id ?>">
											<i class="bi bi-trash"></i>
										</button>
									</td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
						<div class="btn-group" role="group" aria-label="paging">
							<?php foreach($paging as $p){
							$f_url["page"] = $p[0]; ?>
							<a href="<?= base_url() ?>sys/account?<?= http_build_query($f_url) ?>" class="btn btn-<?= $p[2] ?>">
								<?= $p[1] ?>
							</a>
							<?php } ?>
						</div>
					</div>
					<?php }else{ ?>
					<div class="text-danger mt-3"><?= $this->lang->line('t_no_accounts') ?></div>
					<?php } ?>
				</div>
				<div class="bl_content d-none" id="bl_add">
					<h5 class="card-title"><?= $this->lang->line('w_new_account') ?></h5>
					<form class="row g-3" id="form_register_account">
						<div class="col-md-12"><strong><?= $this->lang->line('w_personal_info') ?></strong></div>
						<div class="col-md-6">
							<label class="form-label"><?= $this->lang->line('w_document') ?></label>
							<select class="form-select" id="ra_doc_type_id" name="p[doc_type_id]">
								<?php foreach($doc_types as $d){ if ($d->sunat_code){ ?>
								<option value="<?= $d->id ?>"><?= $d->description ?></option>
								<?php }} ?>
							</select>
							<div class="sys_msg" id="ra_doc_type_msg"></div>
						</div>
						<div class="col-md-6">
							<label class="form-label d-md-block d-none">&nbsp;</label>
							<div class="input-group">
								<input type="text" class="form-control" id="ra_doc_number" name="p[doc_number]" placeholder="<?= $this->lang->line('w_number') ?>">
								<button class="btn btn-primary" type="button" id="btn_search_person_ra">
									<i class="bi bi-search"></i>
								</button>
							</div>
							<div class="sys_msg" id="ra_doc_number_msg"></div>
						</div>
						<div class="col-md-8">
							<label class="form-label"><?= $this->lang->line('w_name') ?></label>
							<input type="text" class="form-control" id="ra_name" name="p[name]">
							<div class="sys_msg" id="ra_name_msg"></div>
						</div>
						<div class="col-md-4">
							<label class="form-label"><?= $this->lang->line('w_tel') ?></label>
							<input type="text" class="form-control" id="ra_tel" name="p[tel]">
							<div class="sys_msg" id="ra_tel_msg"></div>
						</div>
						<div class="col-md-12 pt-3"><strong><?= $this->lang->line('account') ?></strong></div>
						<div class="col-md-4">
							<label class="form-label"><?= $this->lang->line('w_role') ?></label>
							<select class="form-select" name="a[role_id]">
								<option value="" selected>--</option>
								<?php foreach($roles as $item){ ?>
								<option value="<?= $item->id ?>"><?= $this->lang->line($item->name) ?></option>
								<?php } ?>
							</select>
							<div class="sys_msg" id="ra_role_msg"></div>
						</div>
						<div class="col-md-8">
							<label class="form-label"><?= $this->lang->line('w_email') ?></label>
							<input type="email" class="form-control" id="ra_email" name="a[email]" placeholder="email@example.com">
							<div class="sys_msg" id="ra_email_msg"></div>
						</div>
						<div class="col-md-6">
							<label class="form-label"><?= $this->lang->line('w_password') ?></label>
							<input type="password" class="form-control" name="a[password]">
							<div class="sys_msg" id="ra_password_msg"></div>
						</div>
						<div class="col-md-6">
							<label class="form-label"><?= $this->lang->line('w_confirm') ?></label>
							<input type="password" class="form-control" name="a[confirm]">
							<div class="sys_msg" id="ra_confirm_msg"></div>
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
<script>
document.addEventListener("DOMContentLoaded", () => {
	//list
	$(".control_bl").click(function() {
		control_bl(this);
	});
	
	$(".btn_reset_password").click(function() {
		ajax_simple_warning({id: $(this).val()}, "sys/account/reset_password", "wm_password_reset").done(function(res) {
			swal(res.type, res.msg);
		});
	});
	
	$(".btn_remove_account").click(function() {
		ajax_simple_warning({id: $(this).val()}, "sys/account/remove", "wm_account_remove").done(function(res) {
			swal_redirection(res.type, res.msg, window.location.href);
		});
	});
	
	//register
	$("#form_register_account").submit(function(e) {
		e.preventDefault();
		ajax_form_warning(this, "sys/account/register", "wm_account_add").done(function(res) {
			set_msg(res.msgs);
			swal_redirection(res.type, res.msg, window.location.href);
		});
		
	});
	
	function reset_person(){
		$("#ra_name").val("");
		$("#ra_tel").val("");
	}
	
	$("#btn_search_person_ra").click(function() {
		var data = {doc_type_id: $("#ra_doc_type_id").val(), doc_number: $("#ra_doc_number").val()};
		ajax_simple(data, "ajax_f/search_person").done(function(res) {
			swal(res.type, res.msg);
			if (res.type == "success"){
				$("#ra_name").val(res.person.name);
				$("#ra_tel").val(res.person.tel);
			}else reset_person();
		});
	});
	
	$("#ra_doc_type_id").change(function() {
		reset_person();
	});
	
	$("#ra_doc_number").keyup(function() {
		reset_person();
	});
});
</script>