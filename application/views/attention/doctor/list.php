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
		<select class="form-select" id="sl_type" name="specialty">
			<option value=""><?= $this->lang->line('w_specialty') ?></option>
			<?php foreach($specialties as $item){ if ($item->doctor_qty){
				if ($item->id == $f_url["specialty"]) $s = "selected"; else $s = ""; ?>
			<option value="<?= $item->id ?>" <?= $s ?>><?= $item->name ?></option>
			<?php }} ?>
		</select>
	</div>
	<div class="col-md-auto col-12">
		<input type="text" class="form-control" id="inp_name" name="name" placeholder="<?= $this->lang->line('t_search_by_name') ?>" value="<?= $f_url["name"] ?>">
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
				<?php if ($doctors){ ?>
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<th><strong>#</strong></th>
								<th><strong><?= $this->lang->line('w_license') ?></strong></th>
								<th><strong><?= $this->lang->line('w_name') ?></strong></th>
								<th><strong><?= $this->lang->line('w_tel') ?></strong></th>
								<th><strong><?= $this->lang->line('w_status') ?></strong></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($doctors as $i => $item){ ?>
							<tr>
								<td><strong><?= number_format(($f_url["page"] - 1) * 25 + 1 + $i) ?></strong></td>
								<td><?= $item->license ?></td>
								<td>
									<?= $item->person->name ?><br/>
									<?= $specialties_arr[$item->specialty_id] ?>
								</td>
								<td><?= $item->person->tel ?></td>
								<td><span class="text-<?= $status[$item->status_id]->color ?>"><?= $status[$item->status_id]->text ?></span></td>
								<td class="text-end">
									<a href="<?= base_url() ?>clinic/doctor/detail/<?= $item->id ?>" class="btn btn-primary btn-sm">
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
						<a href="<?= base_url() ?>clinic/doctor?<?= http_build_query($f_url) ?>" class="btn btn-<?= $p[2] ?>">
							<?= $p[1] ?>
						</a>
						<?php } ?>
					</div>
				</div>
				<?php }else{ ?>
				<h5 class="text-danger mt-3"><?= $this->lang->line('t_no_doctors') ?></h5>
				<?php } ?>
			</div>
		</div>
		<div class="card bl_content d-none" id="bl_add">
			<div class="card-body">
				<h5 class="card-title"><?= $this->lang->line('w_doctor_info') ?></h5>
				<form class="row g-3" id="form_register">
					<div class="col-md-3">
						<label class="form-label"><?= $this->lang->line('w_document') ?></label>
						<select class="form-select" id="dn_doc_type_id" name="personal[doc_type_id]">
							<?php foreach($doc_types as $d){ if ($d->sunat_code){ ?>
							<option value="<?= $d->id ?>"><?= $d->description ?></option>
							<?php }} ?>
						</select>
						<div class="sys_msg" id="dn_doc_type_msg"></div>
					</div>
					<div class="col-md-3">
						<label class="form-label">&nbsp;</label>
						<div class="input-group">
							<input type="text" class="form-control" id="dn_doc_number" name="personal[doc_number]" placeholder="<?= $this->lang->line('w_number') ?>">
							<button class="btn btn-primary" type="button" id="btn_search_person_dn">
								<i class="bi bi-search"></i>
							</button>
						</div>
						<div class="sys_msg" id="dn_doc_number_msg"></div>
					</div>
					<div class="col-md-6">
						<label class="form-label"><?= $this->lang->line('w_name') ?></label>
						<input type="text" class="form-control" id="dn_name" name="personal[name]">
						<div class="sys_msg" id="dn_name_msg"></div>
					</div>
					<div class="col-md-3">
						<label class="form-label"><?= $this->lang->line('w_specialty') ?></label>
						<select class="form-select" name="doctor[specialty_id]">
							<option value="" selected>--</option>
							<?php foreach($specialties as $item){ ?>
							<option value="<?= $item->id ?>"><?= $item->name ?></option>
							<?php } ?>
						</select>
						<div class="sys_msg" id="dn_specialty_msg"></div>
					</div>
					<div class="col-md-3">
						<label class="form-label"><?= $this->lang->line('w_license_number') ?></label>
						<input type="text" class="form-control" name="doctor[license]">
						<div class="sys_msg" id="dn_license_msg"></div>
					</div>
					<div class="col-md-6">
						<label class="form-label"><?= $this->lang->line('w_email') ?></label>
						<input type="email" class="form-control" name="personal[email]" placeholder="email@example.com">
						<div class="sys_msg" id="dn_email_msg"></div>
					</div>
					<div class="col-md-4">
						<label class="form-label"><?= $this->lang->line('w_tel') ?></label>
						<input type="text" class="form-control" id="dn_tel" name="personal[tel]">
						<div class="sys_msg" id="dn_tel_msg"></div>
					</div>
					<div class="col-md-4">
						<label class="form-label"><?= $this->lang->line('w_birthday') ?></label>
						<input type="hidden" id="p_birthday" name="personal[birthday]" readonly="">
						<div class="input-group">
							<select class="form-select" id="p_birthday_d">
								<option value="" selected=""><?= $this->lang->line('date_d') ?></option>
								<?php for($i = 1; $i <= 31; $i++){ ?>
								<option value="<?= $i ?>"><?= $i ?></option>
								<?php } ?>
							</select>
							<select class="form-select" id="p_birthday_m">
								<option value="" selected=""><?= $this->lang->line('date_m') ?></option>
								<?php for($i = 1; $i <= 12; $i++){ ?>
								<option value="<?= $i ?>"><?= $i ?></option>
								<?php } ?>
							</select>
							<?php $now = date('Y'); ?>
							<select class="form-select" id="p_birthday_y">
								<option value="" selected=""><?= $this->lang->line('date_y') ?></option>
								<?php for($i = 0; $i <= 130; $i++){ ?>
								<option value="<?= $now - $i ?>"><?= $now - $i ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="sys_msg" id="dn_birthday_msg"></div>
					</div>
					<div class="col-md-2">
						<label class="form-label"><?= $this->lang->line('w_sex') ?></label>
						<select class="form-select" name="personal[sex_id]">
							<option value="" selected="">--</option>
							<?php foreach($sex_ops as $item){ ?>
							<option value="<?= $item->id ?>"><?= $item->description ?></option>
							<?php } ?>
						</select>
						<div class="sys_msg" id="dn_sex_msg"></div>
					</div>
					<div class="col-md-2">
						<label class="form-label"><?= $this->lang->line('w_blood_type') ?></label>
						<select class="form-select" name="personal[blood_type_id]">
							<option value="" selected="">--</option>
							<?php foreach($blood_type_ops as $item){ ?>
							<option value="<?= $item->id ?>"><?= $item->description ?></option>
							<?php } ?>
						</select>
						<div class="sys_msg" id="dn_blood_type_msg"></div>
					</div>
					<div class="col-md-12">
						<label class="form-label"><?= $this->lang->line('w_address') ?></label>
						<input type="text" class="form-control" name="personal[address]">
						<div class="sys_msg" id="dn_address_msg"></div>
					</div>
					<div class="col-md-12 pt-3">
						<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_register') ?></button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
document.addEventListener("DOMContentLoaded", () => {
	function reset_person(){
		$("#dn_name").val("");
		$("#dn_tel").val("");
	}
	
	//general
	$(".control_bl").click(function() {
		control_bl(this);
	});
	
	//register
	$("#form_register").submit(function(e) {
		e.preventDefault();
		//birthday merge
		let d = $("#p_birthday_d").val();
		let m = $("#p_birthday_m").val();
		let y = $("#p_birthday_y").val();
		if (d != "" && m != "" && y != "") $("#p_birthday").val(y + "-" + m + "-" + d); else $("#p_birthday").val("");
		
		$("#register_form .sys_msg").html("");
		ajax_form_warning(this, "clinic/doctor/register", "wm_doctor_register").done(function(res) {
			set_msg(res.msgs);
			swal_redirection(res.type, res.msg, res.move_to);
		});
	});
	
	$("#btn_search_person_dn").click(function() {
		var data = {doc_type_id: $("#dn_doc_type_id").val(), doc_number: $("#dn_doc_number").val()};
		ajax_simple(data, "ajax_f/search_person").done(function(res) {
			swal(res.type, res.msg);
			if (res.type == "success"){
				$("#dn_name").val(res.person.name);
				$("#dn_tel").val(res.person.tel);
			}else reset_person();
		});
	});
	
	$("#dn_doc_type_id").change(function() {
		reset_person();
	});
	
	$("#dn_doc_number").keyup(function() {
		reset_person();
	});
});
</script>