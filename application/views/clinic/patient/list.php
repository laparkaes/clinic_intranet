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
		<input type="text" class="form-control" id="inp_search" name="keyword" placeholder="<?= $this->lang->line('w_search') ?>" value="<?= $f_url["keyword"] ?>">
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
				<?php if ($patients){ ?>
				<div class="table-responsive">
					<table class="table table-responsive-md">
						<thead>
							<tr>
								<th><strong>#</strong></th>
								<th><strong><?= $this->lang->line('w_document') ?></strong></th>
								<th><strong><?= $this->lang->line('w_name') ?></strong></th>
								<th><strong><?= $this->lang->line('w_tel') ?></strong></th>
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
								<td class="text-right">
									<a href="<?= base_url() ?>patient/detail/<?= $item->id ?>" class="btn btn-primary btn-sm">
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
		<div class="card bl_content d-none" id="bl_add">
			<div class="card-body">
				<h5 class="card-title"><?= $this->lang->line('w_patient_info') ?></h5>
				<form class="row g-3" id="form_register">
					<div class="form-group col-md-3">
						<label class="form-label"><?= $this->lang->line('w_document') ?></label>
						<select class="form-select" id="pn_doc_type_id" name="doc_type_id">
							<?php foreach($doc_types as $d){ if ($d->sunat_code){ ?>
							<option value="<?= $d->id ?>"><?= $d->description ?></option>
							<?php }} ?>
						</select>
						<div class="sys_msg" id="pn_doc_type_msg"></div>
					</div>
					<div class="form-group col-md-3">
						<label class="form-label d-md-block d-none">&nbsp;</label>
						<div class="input-group">
							<input type="text" class="form-control" id="pn_doc_number" name="doc_number" placeholder="<?= $this->lang->line('w_number') ?>">
							<button class="btn btn-primary" type="button" id="btn_search_person_pn">
								<i class="bi bi-search"></i>
							</button>
						</div>
						<div class="sys_msg" id="pn_doc_number_msg"></div>
					</div>
					<div class="form-group col-md-6">
						<label class="form-label"><?= $this->lang->line('w_name') ?></label>
						<input type="text" class="form-control" id="pn_name" name="name">
						<div class="sys_msg" id="pn_name_msg"></div>
					</div>
					<div class="form-group col-md-4">
						<label class="form-label"><?= $this->lang->line('w_tel') ?></label>
						<input type="text" class="form-control" id="pn_tel" name="tel">
						<div class="sys_msg" id="pn_tel_msg"></div>
					</div>
					<div class="form-group col-md-4">
						<label class="form-label"><?= $this->lang->line('w_birthday') ?></label>
						<input type="hidden" id="p_birthday" name="birthday" readonly="">
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
						<div class="sys_msg" id="pn_birthday_msg"></div>
					</div>
					<div class="form-group col-md-2">
						<label class="form-label"><?= $this->lang->line('w_sex') ?></label>
						<select class="form-select" name="sex_id">
							<option value="" selected="">--</option>
							<?php foreach($sex_ops as $item){ ?>
							<option value="<?= $item->id ?>"><?= $item->description ?></option>
							<?php } ?>
						</select>
						<div class="sys_msg" id="pn_sex_msg"></div>
					</div>
					<div class="form-group col-md-2">
						<label class="form-label"><?= $this->lang->line('w_blood_type') ?></label>
						<select class="form-select" name="blood_type_id">
							<option value="" selected="">--</option>
							<?php foreach($blood_type_ops as $item){ ?>
							<option value="<?= $item->id ?>"><?= $item->description ?></option>
							<?php } ?>
						</select>
						<div class="sys_msg" id="pn_blood_type_msg"></div>
					</div>
					<div class="form-group col-md-4">
						<label class="form-label"><?= $this->lang->line('w_email') ?></label>
						<input type="email" class="form-control" name="email" placeholder="email@example.com">
						<div class="sys_msg" id="pn_email_msg"></div>
					</div>
					<div class="form-group col-md-8">
						<label class="form-label"><?= $this->lang->line('w_address') ?></label>
						<input type="text" class="form-control" name="address">
						<div class="sys_msg" id="pn_address_msg"></div>
					</div>
					<div class="form-group col-md-12 pt-3">
						<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_register') ?></button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>