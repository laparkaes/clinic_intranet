<div class="pagetitle">
	<h1><?= $title ?></h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= $this->lang->line('w_home') ?></a></li>
			<li class="breadcrumb-item active"><?= $title ?></li>
		</ol>
	</nav>
</div>
<div class="row">
	<div class="col">
		<div class="card">
			<div class="card-body">
				<h5 class="card-title"><?= $this->lang->line('w_sys_config') ?></h5>
				<ul class="nav nav-tabs nav-tabs-bordered" role="tablist">
					<li class="nav-item" role="presentation">
						<button class="nav-link active" id="bl_company_admin-tab" data-bs-toggle="tab" data-bs-target="#bordered-bl_company_admin" type="button" role="tab" aria-controls="bl_company_admin" aria-selected="true"><?= $this->lang->line('w_company') ?></button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="bl_laboratory_admin-tab" data-bs-toggle="tab" data-bs-target="#bordered-bl_laboratory_admin" type="button" role="tab" aria-controls="bl_laboratory_admin" aria-selected="false" tabindex="-1"><?= $this->lang->line('w_laboratory') ?></button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="bl_image_admin-tab" data-bs-toggle="tab" data-bs-target="#bordered-bl_image_admin" type="button" role="tab" aria-controls="bl_image_admin" aria-selected="false" tabindex="-1"><?= $this->lang->line('w_image') ?></button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="bl_medicine_admin-tab" data-bs-toggle="tab" data-bs-target="#bordered-bl_medicine_admin" type="button" role="tab" aria-controls="bl_medicine_admin" aria-selected="false" tabindex="-1"><?= $this->lang->line('w_medicine') ?></button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="bl_access_admin-tab" data-bs-toggle="tab" data-bs-target="#bordered-bl_access_admin" type="button" role="tab" aria-controls="bl_access_admin" aria-selected="false" tabindex="-1"><?= $this->lang->line('w_access') ?></button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="bl_history_admin-tab" data-bs-toggle="tab" data-bs-target="#bordered-bl_history_admin" type="button" role="tab" aria-controls="bl_history_admin" aria-selected="false" tabindex="-1"><?= $this->lang->line('w_log') ?></button>
					</li>
				</ul>
				<div class="tab-content pt-3">
					<div class="tab-pane fade show active" id="bordered-bl_company_admin" role="tabpanel" aria-labelledby="bl_company_admin-tab">
						<form class="row g-3" id="form_update_company_data">
							<div class="col-md-12">
								<strong><?= $this->lang->line('w_company_data') ?></strong>
							</div>
							<div class="col-md-4">
								<label class="form-label"><?= $this->lang->line('w_tax_id') ?></label>
								<div class="input-group">
									<input type="text" class="form-control" id="uc_tax_id" value="<?= $company->tax_id ?>" name="tax_id">
									<button class="btn btn-primary" id="btn_search_company" type="button">
										<i class="bi bi-search"></i>
									</button>
								</div>
								<div class="sys_msg" id="uc_tax_id_msg"></div>
							</div>
							<div class="col-md-8">
								<label class="form-label"><?= $this->lang->line('w_name') ?></label>
								<input type="text" class="form-control" id="uc_name" value="<?= $company->name ?>" name="name">
								<div class="sys_msg" id="uc_name_msg"></div>
							</div>
							<div class="col-md-8">
								<label class="form-label"><?= $this->lang->line('w_email') ?></label>
								<input type="text" class="form-control" id="uc_email" value="<?= $company->email ?>" name="email">
								<div class="sys_msg" id="uc_email_msg"></div>
							</div>
							<div class="col-md-4">
								<label class="form-label"><?= $this->lang->line('w_tel') ?></label>
								<input type="text" class="form-control" id="uc_tel" value="<?= $company->tel ?>" name="tel">
								<div class="sys_msg" id="uc_tel_msg"></div>
							</div>
							<div class="col-md-8">
								<label class="form-label"><?= $this->lang->line('w_address') ?></label>
								<input type="text" class="form-control" id="uc_address" value="<?= $company->address ?>" name="address">
								<div class="sys_msg" id="uc_address_msg"></div>
							</div>
							<div class="col-md-4">
								<label class="form-label"><?= $this->lang->line('w_urbanization') ?></label>
								<input type="text" class="form-control" id="uc_urbanization" value="<?= $company->urbanization ?>" name="urbanization">
							</div>
							<div class="col-md-3">
								<label class="form-label"><?= $this->lang->line('w_department') ?></label>
								<select class="form-select" id="uc_department_id" name="department_id">
									<option value="">-</option>
									<?php foreach($departments as $item){
										if ($item->id == $company->department_id) $selected = "selected";
										else $selected = ""; ?>
									<option value="<?= $item->id ?>" <?= $selected ?>><?= $item->name ?></option>
									<?php } ?>
								</select>
								<div class="sys_msg" id="uc_department_msg"></div>
							</div>
							<div class="col-md-3">
								<label class="form-label"><?= $this->lang->line('w_province') ?></label>
								<select class="form-select" id="uc_province_id" name="province_id">
									<option value="">-</option>
									<?php foreach($provinces as $item){
										$selected = ""; $class = "d-none";
										if ($item->department_id == $company->department_id){ $class = "";
											if ($item->id == $company->province_id) $selected = "selected"; } ?>
									<option class="province d<?= $item->department_id ?> <?= $class ?>" value="<?= $item->id ?>" <?= $selected ?>><?= $item->name ?></option>
									<?php } ?>
								</select>
								<div class="sys_msg" id="uc_province_msg"></div>
							</div>
							<div class="col-md-3">
								<label class="form-label"><?= $this->lang->line('w_district') ?></label>
								<select class="form-select" id="uc_district_id" name="district_id">
									<option value="">-</option>
									<?php foreach($districts as $item){
										$selected = ""; $class = "d-none";
										if ($item->province_id == $company->province_id){ $class = "";
											if ($item->id == $company->district_id) $selected = "selected"; } ?>
									<option class="district p<?= $item->province_id ?> <?= $class ?>" value="<?= $item->id ?>" <?= $selected ?>><?= $item->name ?></option>
									<?php } ?>
								</select>
								<div class="sys_msg" id="uc_district_msg"></div>
							</div>
							<div class="col-md-3">
								<label class="form-label"><?= $this->lang->line('w_ubigeo') ?></label>
								<input type="text" class="form-control" id="uc_ubigeo" value="<?= $company->ubigeo ?>" name="ubigeo">
								<div class="sys_msg" id="uc_ubigeo_msg"></div>
							</div>
							<div class="col-md-12 pt-3">
								<button type="submit" class="btn btn-primary">
									<?= $this->lang->line('btn_save') ?>
								</button>
								<button type="button" class="btn btn-danger" id="btn_init_system">
									<?= $this->lang->line('btn_init_system') ?>
								</button>
							</div>
						</form>
					</div>
					<div class="tab-pane fade" id="bordered-bl_laboratory_admin" role="tabpanel" aria-labelledby="bl_laboratory_admin-tab">
						<div class="d-flex justify-content-between">
							<strong><?= $this->lang->line('w_laboratory_admin') ?></strong>
							<div class="btn-group">
								<button type="button" class="btn control_bl_profile btn-primary" id="btn_list_profile" value="bl_profile_list">
									<i class="bi bi-list"></i>
								</button>
								<button type="button" class="btn control_bl_profile btn-outline-primary" value="bl_profile_add">
									<i class="bi bi-plus-lg"></i>
								</button>
							</div>
						</div>
						<div class="bl_profile d-none" id="bl_profile_add">
							<form class="row g-3" id="form_register_profile">
								<div class="col-md-12">
									<label class="form-label"><?= $this->lang->line('w_profile_name') ?></label>
									<input type="text" class="form-control" name="name">
									<div class="sys_msg" id="rp_name_msg"></div>
								</div>
								<div class="col-md-12">
									<label class="form-label"><?= $this->lang->line('w_examinations') ?> <i class="bi bi-gear" data-bs-toggle="modal" data-bs-target="#md_admin_exam"></i></label>
									<div class="row">
										<div class="col-md-6">
											<select class="form-select mb-3" id="rp_category">
												<option value=""><?= $this->lang->line('w_view_all') ?></option>
												<?php foreach($exam_category as $ec){ ?>
												<option value="<?= $ec->id ?>"><?= $ec->name ?></option>
												<?php } ?>
											</select>
										</div>
										<div class="col-md-6">
											<input type="text" class="form-control mb-3" id="rp_filter" placeholder="<?= $this->lang->line('w_filter') ?>">
										</div>
										<div class="col-md-12 sys_msg" id="rp_exams_msg"></div>
									</div>
									<div class="row" id="ex_profile_list" style="max-height: 400px; overflow-y: auto;">
										<div class="col-md-12 text-danger d-none" id="rp_no_result_msg">
											<?= $this->lang->line('t_no_result') ?>
										</div>
										<?php foreach($exams as $ex){ ?>
										<div class="col-md-6 ex_profile ex_profile_<?= $ex->category_id ?>">
											<div class="custom-control custom-checkbox mb-3">
												<input type="checkbox" class="custom-control-input" id="exam_<?= $ex->id ?>" value="<?= $ex->id ?>" name="exams[]">
												<label class="custom-control-label" for="exam_<?= $ex->id ?>"><?= $ex->name ?></label>
											</div>
										</div>
										<?php } ?>
									</div>
								</div>
								<div class="col-md-12 pt-3">
									<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_register') ?></button>
								</div>
							</form>
						</div>
						<div class="bl_profile" id="bl_profile_list">
							<div class="table-responsive">
								<table class="table table-responsive-md">
									<thead>
										<tr>
											<th>#</th>
											<th style="min-width: 200px;"><?= $this->lang->line('w_profile') ?></th>
											<th><?= $this->lang->line('w_examinations') ?></th>
											<th></th>
										</tr>
									</thead>
									<tbody id="profile_list">
										<?php foreach($exam_profiles as $i => $item){ ?>
										<tr>
											<td><?= $i + 1 ?></td>
											<td><?= $item->name ?></td>
											<td><?= $item->exams ?></td>
											<td class="text-end">
												<button type="button" class="btn btn-danger btn-sm remove_profile" value="<?= $item->id ?>">
													<i class="bi bi-x-lg"></i>
												</button>
											</td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
								<div class="text-center mt-3 pb-1">
									<button type="button" class="btn btn-outline-primary" id="btn_load_more_profile">
										<?= $this->lang->line('btn_load_more') ?>
									</button>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="bordered-bl_image_admin" role="tabpanel" aria-labelledby="bl_image_admin-tab">
						<div class="d-flex justify-content-between">
							<strong><?= $this->lang->line('w_image_admin') ?></strong>
							<div class="btn-group">
								<button type="button" class="btn control_bl_image btn-primary" id="btn_list_image" value="bl_image_list">
									<i class="bi bi-list"></i>
								</button>
								<button type="button" class="btn control_bl_image btn-outline-primary" value="bl_image_add">
									<i class="bi bi-plus-lg"></i>
								</button>
							</div>
						</div>
						<div class="bl_image d-none" id="bl_image_add">
							<form class="row g-3" id="form_register_image">
								<div class="col-md-4">
									<label class="form-label"><?= $this->lang->line('w_category') ?></label>
									<select class="form-select" name="category_id">
										<option value="">--</option>
										<?php foreach($image_categories as $item){ ?>
										<option value="<?= $item->id ?>"><?= $item->name ?></option>
										<?php } ?>
									</select>
									<div class="sys_msg" id="ri_category_id_msg"></div>
								</div>
								<div class="col-md-8">
									<label class="form-label"><?= $this->lang->line('w_name') ?></label>
									<input type="text" class="form-control" name="name">
									<div class="sys_msg" id="ri_name_msg"></div>
								</div>
								<div class="col-md-12 pt-3">
									<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_register') ?></button>
								</div>
							</form>
						</div>
						<div class="bl_image" id="bl_image_list">
							<div class="table-responsive">
								<table class="table">
									<thead>
										<tr>
											<th>#</th>
											<th style="width: 200px;"><?= $this->lang->line('w_category') ?></th>
											<th><?= $this->lang->line('w_image') ?></th>
											<th></th>
										</tr>
									</thead>
									<tbody id="image_list">
										<?php foreach($images as $i => $item){ ?>
										<tr>
											<td><?= number_format($i + 1) ?></td>
											<td><?= $image_category_arr[$item->category_id] ?></td>
											<td><?= $item->name ?></td>
											<td class="text-end">
												<button type="button" class="btn btn-danger btn-sm btn_remove_image" value="<?= $item->id ?>">
													<i class="bi bi-x-lg"></i>
												</button>
											</td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
								<div class="text-center mt-3 pb-1">
									<button type="button" class="btn btn-outline-primary" id="btn_load_more_image">
										<?= $this->lang->line('btn_load_more') ?>
									</button>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="bordered-bl_medicine_admin" role="tabpanel" aria-labelledby="bl_medicine_admin-tab">
						<div class="d-flex justify-content-between">
							<strong><?= $this->lang->line('w_medicine_admin') ?></strong>
							<div class="btn-group">
								<button type="button" class="btn control_bl_medicine btn-primary" id="btn_list_medicine" value="bl_medicine_list">
									<i class="bi bi-list"></i>
								</button>
								<button type="button" class="btn control_bl_medicine btn-outline-primary" value="bl_medicine_add">
									<i class="bi bi-plus-lg"></i>
								</button>
							</div>
						</div>
						<div class="bl_medicine d-none" id="bl_medicine_add">
							<form class="row g-3" id="form_register_medicine" action="#">
								<div class="col-md-12">
									<label class="form-label"><?= $this->lang->line('w_medicine') ?></label>
									<input type="text" class="form-control" name="name">
									<div class="sys_msg" id="rm_name_msg"></div>
								</div>
								<div class="col-md-12 pt-3">
									<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_register') ?></button>
								</div>
							</form>
						</div>
						<div class="bl_medicine" id="bl_medicine_list">
							<div class="table-responsive">
								<table class="table">
									<thead>
										<tr>
											<th>#</th>
											<th><?= $this->lang->line('w_medicine') ?></th>
											<th></th>
										</tr>
									</thead>
									<tbody id="medicine_list">
										<?php foreach($medicines as $i => $item){ ?>
										<tr>
											<td><?= number_format($i + 1) ?></td>
											<td><?= $item->name ?></td>
											<td class="text-end">
												<button type="button" class="btn btn-danger btn-sm btn_remove_medicine" value="<?= $item->id ?>">
													<i class="bi bi-x-lg"></i>
												</button>
											</td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
								<div class="text-center mt-3 pb-1">
									<button type="button" class="btn btn-outline-primary" id="btn_load_more_medicine">
										<?= $this->lang->line('btn_load_more') ?>
									</button>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="bordered-bl_access_admin" role="tabpanel" aria-labelledby="bl_access_admin-tab">
						<div class="table-responsive">
							<table class="table text-center">
								<tbody>
									<?php $colspan = count($roles) + 1; foreach($access as $module => $a_list){ ?>
									<tr>
										<td class="text-start">
											<strong><?= $this->lang->line('wm_'.$module) ?></strong>
										</td>
										<?php foreach($roles as $item){ ?>
										<td style="width: 105px;"><strong><?= $this->lang->line($item->name) ?></strong></td>
										<?php } ?>
									</tr>
									<?php foreach($a_list as $a){ ?>
									<tr>
										<td class="text-start ps-4">
											<?= $this->lang->line('wa_'.$a->description) ?>
										</td>
										<?php foreach($roles as $r){ 
										$value = $r->id."_".$a->id;
										if (in_array($value, $role_access)) $c = "checked"; else $c = ""; ?>
										<td>
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="custom-control-input chk_access" id="chk_<?= $value ?>" value="<?= $value ?>" <?= $c ?>>
												<label class="custom-control-label" for="chk_<?= $value ?>"></label>
											</div>
										</td>
										<?php } ?>
									</tr>
									<?php }} ?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="tab-pane fade" id="bordered-bl_history_admin" role="tabpanel" aria-labelledby="bl_history_admin-tab">
						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr>
										<th>#</th>
										<th><?= $this->lang->line('w_account') ?></th>
										<th><?= $this->lang->line('w_detail') ?></th>
										<th><?= $this->lang->line('w_time') ?></th>
									</tr>
								</thead>
								<tbody id="log_list">
									<?php foreach($logs as $i => $item){ ?>
									<tr>
										<td><?= $i + 1 ?></td>
										<td><?= $item->account ?></td>
										<td><?= $item->log_txt ?><br/><?= $item->detail ?></td>
										<td><?= $item->registed_at ?></td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
							<div class="text-center mt-3 pb-1">
								<button type="button" class="btn btn-outline-primary" id="btn_load_more_log">
									<?= $this->lang->line('btn_load_more') ?>
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="md_admin_exam" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?= $this->lang->line('w_examination_admin') ?></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<ul class="nav nav-tabs nav-tabs-bordered" role="tablist">
					<li class="nav-item" role="presentation">
						<button class="nav-link active" id="ad_category-tab" data-bs-toggle="tab" data-bs-target="#bordered-ad_category" type="button" role="tab" aria-controls="ad_category" aria-selected="true"><?= $this->lang->line('btn_category') ?></button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="ad_examination-tab" data-bs-toggle="tab" data-bs-target="#bordered-ad_examination" type="button" role="tab" aria-controls="ad_examination" aria-selected="false" tabindex="-1"><?= $this->lang->line('btn_examination') ?></button>
					</li>
				</ul>
				<div class="tab-content pt-3">
					<div class="tab-pane fade show active" id="bordered-ad_category" role="tabpanel" aria-labelledby="ad_category-tab">
						<div style="max-height: 350px; overflow-y: auto;">
							<table class="table">
								<thead>
									<tr>
										<th>#</th>
										<th><?= $this->lang->line('w_category') ?></th>
										<th></th>
									</tr>
								</thead>
								<tbody id="ad_category_list">
									<tr>
										<td colspan="3">
											<form class="row g-3 justify-content-end" id="form_add_exam_category">
												<div class="col-md-10">
													<input type="text" class="form-control" name="name" placeholder="<?= $this->lang->line('w_category_name') ?>">
												</div>
												<div class="col-md-auto">
													<button type="submit" class="btn btn-primary"><i class="bi bi-plus-lg"></i></button>
												</div>
											</form>
										</td>
									</tr>
									<?php foreach($exam_category as $i => $item){ ?>
									<tr class="ad_cat_rows">
										<td><strong><?= $i + 1 ?></strong></td>
										<td><?= $item->name ?></td>
										<td class="text-end">
											<button type="button" class="btn light btn-danger btn_remove_exam_category" value="<?= $item->id ?>"><i class="bi bi-x-lg"></i></button>
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="tab-pane fade" id="bordered-ad_examination" role="tabpanel" aria-labelledby="ad_examination-tab">
						<div style="max-height: 350px; overflow-y: auto;">
							<table class="table">
								<thead>
									<tr>
										<th>#</th>
										<th><?= $this->lang->line('w_examination') ?></th>
										<th></th>
									</tr>
								</thead>
								<tbody id="ad_exam_list">
									<tr>
										<td colspan="3">
											<form class="row g-3 justify-content-end" id="form_add_exam">
												<div class="col-md-5">
													<select class="form-select" id="ad_ex_category" name="category_id">
														<option value="">--</option>
														<?php foreach($exam_category as $item){ ?>
														<option value="<?= $item->id ?>"><?= $item->name ?></option>
														<?php } ?>
													</select>
												</div>
												<div class="col-md-5">
													<input type="text" class="form-control" name="name" placeholder="<?= $this->lang->line('w_examination_name') ?>">
												</div>
												<div class="col-md-auto">
													<button type="submit" class="btn btn-primary"><i class="bi bi-plus-lg"></i></button>
												</div>
											</form>
										</td>
									</tr>
									<?php foreach($exams as $i => $item){ ?>
									<tr class="ad_exam_rows">
										<td><strong><?= $i + 1 ?></strong></td>
										<td>
											<?= $item->name ?><br/>
											<small><?= $item->category ?></small>
										</td>
										<td class="text-end">
											<button type="button" class="btn light btn-danger btn_remove_exam" value="<?= $item->id ?>"><i class="bi bi-x-lg"></i></button>
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= $this->lang->line('btn_close') ?></button>
			</div>
		</div>
	</div>
</div>
<input type="hidden" id="w_view_all" value="<?= $this->lang->line('w_view_all') ?>">
<script>
document.addEventListener("DOMContentLoaded", () => {
	//general
	function control_bl_group(dom, group){
		$(dom).parent().children().removeClass("btn-primary");
		$(dom).parent().children().addClass("btn-outline-primary");
		
		$(dom).removeClass("btn-outline-primary");
		$(dom).addClass("btn-primary");
		
		$(".bl_" + group).addClass("d-none");
		$("#" + $(dom).val()).removeClass("d-none");
	}
	
	$(".control_bl_profile").click(function() {
		control_bl_group(this, "profile");
	});
	
	$(".control_bl_image").click(function() {
		control_bl_group(this, "image");
	});
	
	$(".control_bl_medicine").click(function() {
		control_bl_group(this, "medicine");
	});
	
	//role & access
	$(".chk_access").click(function() {
		var data = {setting: $(this).is(':checked'), value: $(this).val()};
		ajax_simple(data, "sys/config/control_access").done(function(res) {
			swal(res.type, res.msg);
			if (res.type == "error") $(this).prop('checked', !$(this).is(':checked'));
		});
	});
	
	//company
	$("#form_update_company_data").submit(function(e) {
		e.preventDefault();
		ajax_form(this, "sys/config/update_company_data").done(function(res) {
			set_msg(res.msgs);
			swal_redirection(res.type, res.msg, window.location.href);
		});
	});
	
	function control_province(department_id){
		$("#uc_province_id").val("");
		$("#uc_province_id .province").addClass("d-none");
		$("#uc_province_id .d" + department_id).removeClass("d-none");
		$("#uc_district_id").val("");
		$("#uc_district_id .district").addClass("d-none");
	}

	function control_district(province_id){
		$("#uc_district_id").val("");
		$("#uc_district_id .district").addClass("d-none");
		$("#uc_district_id .p" + province_id).removeClass("d-none");
	}
	
	$("#btn_search_company").click(function() {
		ajax_simple({tax_id: $("#uc_tax_id").val()}, "ajax_f/search_company").done(function(res) {
			swal(res.type, res.msg);
			control_province(res.company.department_id);
			control_district(res.company.province_id);
			$("#uc_tax_id").val(res.company.tax_id);
			$("#uc_name").val(res.company.name);
			$("#uc_email").val(res.company.email);
			$("#uc_tel").val(res.company.tel);
			$("#uc_address").val(res.company.address);
			$("#uc_urbanization").val(res.company.urbanization);
			$("#uc_ubigeo").val(res.company.ubigeo);
			$("#uc_department_id").val(res.company.department_id);
			$("#uc_province_id").val(res.company.province_id);
			$("#uc_district_id").val(res.company.district_id);
		});
	});
	
	$("#uc_department_id").change(function() {
		control_province($(this).val());
	});
	
	$("#uc_province_id").change(function() {
		control_district($(this).val());
	});
	
	$("#btn_init_system").click(function() {
		ajax_simple_warning({}, "sys/config/system_init", "wm_system_init").done(function(res) {
			swal_redirection(res.type, res.msg, $("#base_url").val() + "sys/system_init");
		});
	});
	
	//profile
	function remove_profile(dom){
		ajax_simple_warning({id: $(dom).val()}, "sys/config/remove_profile", "wm_profile_remove").done(function(res) {
			swal(res.type, res.msg);
			reset_profile_list();
		});
	}
	
	function load_more_profile(){
		var offset = $("#profile_list").children().length;
		ajax_simple({offset: offset}, "sys/config/load_more_profile").done(function(res) {
			if (res.length > 0){
				$.each(res, function(index, item) {
					$("#profile_list").append('<tr><td>' + (offset + index + 1) + '</td><td>' + item.name + '</td><td>' + item.exams + '</td><td class="text-end"><button type="button" class="btn btn-danger btn-sm remove_profile" value="' + item.id + '"><i class="bi bi-x-lg"></i></button></td></tr>');
				});
				
				$('.remove_profile').off('click').on('click',(function(e) {remove_profile(this);}));
			}else $("#btn_load_more_profile").addClass("d-none");
		});
	}
	
	function reset_profile_list(){
		$("#profile_list").html("");
		$("#btn_load_more_profile").removeClass("d-none");
		load_more_profile();
	}
	
	$("#form_register_profile").submit(function(e) {
		e.preventDefault();
		ajax_form(this, "sys/config/register_profile").done(function(res) {
			set_msg(res.msgs);
			swal(res.type, res.msg);
			reset_profile_list();
			$('#btn_list_profile').trigger('click');
		});
	});
	
	function remove_exam_category(cat_id){
		ajax_simple({id: cat_id}, "sys/config/remove_exam_category").done(function(res){
			swal(res.type, res.msg);
			set_exam_cat(res.data.cats, res.data.exams);
		});
	}
	
	function remove_exam(ex_id){
		ajax_simple({id: ex_id}, "sys/config/remove_exam").done(function(res){
			swal(res.type, res.msg);
			set_exam_cat(res.data.cats, res.data.exams);
		});
	}
	
	function set_exam_cat(categories, exams){
		if (categories.length > 0){
			$(".ad_cat_rows").remove();
			$("#ad_ex_category").html(""); $("#ad_ex_category").append('<option value="">--</option>');
			$("#rp_category").html(""); $("#rp_category").append('<option value="">' + $("#w_view_all").val() + '</option>');
			
			$.each(categories, function(index, item) {
				$("#ad_category_list").append('<tr class="ad_cat_rows"><td><strong>' + (index + 1) + '</strong></td><td>' + item.name + '</td><td class="text-end"><button type="button" class="btn light btn-danger btn_remove_exam_category" value="' + item.id + '"><i class="bi bi-x-lg"></i></button></td></tr>');
				$("#rp_category").append('<option value="' + item.id + '">' + item.name + '</option>');
				$("#ad_ex_category").append('<option value="' + item.id + '">' + item.name + '</option>');
			});
			
			$(".btn_remove_exam_category").on('click',(function(e) {remove_exam_category($(this).val());}));
		}
		
		if (exams.length > 0){
			$(".ad_exam_rows").remove();
			$(".ex_profile").remove();
			$.each(exams, function(index, item) {
				$("#ad_exam_list").append('<tr class="ad_exam_rows"><td><strong>' + (index + 1) + '</strong></td><td>' + item.name + '<br/><small>' + item.category + '</small></td><td class="text-end"><button type="button" class="btn light btn-danger btn_remove_exam" value="' + item.id + '"><i class="bi bi-x-lg"></i></button></td></tr>');
				
				$("#ex_profile_list").append('<div class="col-md-6 ex_profile ex_profile_' + item.category_id + '"><div class="custom-control custom-checkbox mb-3"><input type="checkbox" class="custom-control-input" id="exam_' + item.id + '" value="' + item.id + '"name="exams[]"><label class="custom-control-label" for="exam_' + item.id + '">' + item.name + '</label></div></div>');
			});
			
			$(".btn_remove_exam").on('click',(function(e) {remove_exam($(this).val());}));
		}
	}
	
	$("#form_add_exam_category").submit(function(e) {
		e.preventDefault();
		ajax_form(this, "sys/config/add_exam_category").done(function(res){
			swal(res.type, res.msg);
			set_exam_cat(res.data.cats, res.data.exams);
		});
	});
	
	$("#form_add_exam").submit(function(e) {
		e.preventDefault();
		ajax_form(this, "sys/config/add_exam").done(function(res){
			swal(res.type, res.msg);
			set_exam_cat(res.data.cats, res.data.exams);
		});
	});
	
	$(".remove_profile").click(function() {
		remove_profile(this);
	});
	
	function filter_exams(){
		var profile_id = $("#rp_category").val();
		var filter = $("#rp_filter").val();
		var exams = $('.ex_profile');
		var is_show_cat;//category
		var is_show_fil;//filter
		var is_show;//result
		
		$.each(exams, function(index, value) {
			if (profile_id == "") is_show_cat = true;
			else{
				if ($(value).hasClass("ex_profile_" + profile_id)) is_show_cat = true;
				else is_show_cat = false;
			}
			
			if (filter == "") is_show_fil = true;
			else{
				if ($(value).find("label").text().toUpperCase().indexOf(filter.toUpperCase()) >= 0) is_show_fil = true;
				else is_show_fil = false;	
			}
			
			is_show = (is_show_cat && is_show_fil);
			
			if (is_show == true) $(value).removeClass("d-none");
			else $(value).addClass("d-none");
		});
		
		if ($(".ex_profile:not(.d-none)").length > 0) $("#rp_no_result_msg").addClass("d-none");
		else $("#rp_no_result_msg").removeClass("d-none");
	}
	
	$("#rp_category").change(function() {
		filter_exams();
	});
	
	$("#rp_filter").keyup(function() {
		filter_exams();
	});
	
	$("#btn_load_more_profile").click(function() {
		load_more_profile();
	});
	
	$(".btn_remove_exam_category").click(function() {
		remove_exam_category($(this).val());
	});
	
	$(".btn_remove_exam").click(function() {
		remove_exam($(this).val());
	});
	
	//image
	function reset_image_list(){
		$("#image_list").html("");
		$("#btn_load_more_image").removeClass("d-none");
		load_more_image();
	}
	
	$("#form_register_image").submit(function(e) {
		e.preventDefault();
		ajax_form(this, "sys/config/register_image").done(function(res) {
			set_msg(res.msgs);
			swal(res.type, res.msg);
			reset_image_list();
			$('#btn_list_image').trigger('click');
		});
	});
	
	function remove_image(dom){
		ajax_simple_warning({id: $(dom).val()}, "sys/config/remove_image", "wm_image_remove").done(function(res) {
			swal(res.type, res.msg);
			reset_image_list();
		});
	}
	
	function load_more_image(){
		var offset = $("#image_list").children().length;
		ajax_simple({offset: offset}, "sys/config/load_more_image").done(function(res) {
			if (res.length > 0){
				$.each(res, function(index, item) {
					$("#image_list").append('<tr><td>' + (offset + index + 1) + '</td><td>' + item.category + '</td><td>' + item.name + '</td><td class="text-end"><button type="button" class="btn btn-danger btn-sm btn_remove_image" value="' + item.id + '"><i class="bi bi-x-lg"></i></button></td></tr>');
				});
				
				$('.btn_remove_image').off('click').on('click',(function(e) {remove_image(this);}));
			}else $("#btn_load_more_image").addClass("d-none");
		});
	}
	
	$("#btn_load_more_image").click(function() {
		load_more_image();
	});
	
	$(".btn_remove_image").click(function() {
		remove_image(this);
	});
	
	//medicine
	function remove_medicine(dom){
		ajax_simple_warning({id: $(dom).val()}, "sys/config/remove_medicine", "wm_medicine_remove").done(function(res) {
			swal(res.type, res.msg);
			reset_medicine_list();
		});
	}
	
	function load_more_medicine(){
		var offset = $("#medicine_list").children().length;
		ajax_simple({offset: offset}, "sys/config/load_more_medicine").done(function(res) {
			if (res.length > 0){
				$.each(res, function(index, item) {
					$("#medicine_list").append('<tr><td>' + (offset + index + 1) + '</td><td>' + item.name + '</td><td class="text-end"><button type="button" class="btn btn-danger btn-sm btn_remove_medicine" value="' + item.id + '"><i class="bi bi-x-lg"></i></button></td></tr>');
				});
				
				$('.btn_remove_medicine').off('click').on('click',(function(e) {remove_medicine(this);}));
			}else $("#btn_load_more_medicine").addClass("d-none");
		});
	}
	
	function reset_medicine_list(){
		$("#medicine_list").html("");
		$("#btn_load_more_medicine").removeClass("d-none");
		load_more_medicine();
	}
	
	$("#form_register_medicine").submit(function(e) {
		e.preventDefault();
		ajax_form(this, "sys/config/register_medicine").done(function(res) {
			set_msg(res.msgs);
			swal(res.type, res.msg);
			reset_medicine_list();
			$('#btn_list_medicine').trigger('click');
		});
	});
	
	$(".btn_remove_medicine").click(function() {
		remove_medicine(this);
	});
	
	$("#btn_load_more_medicine").click(function() {
		load_more_medicine();
	});
	
	//log
	$("#btn_load_more_log").click(function() {
		var offset = $("#log_list").children().length;
		ajax_simple({offset: offset}, "sys/config/load_more_log").done(function(res) {
			if (res.length > 0){
				$.each(res, function(index, item) {
					$("#log_list").append('<tr><td>' + (offset + index + 1) + '</td><td>' + item.account + '</td><td>' + item.log_txt + '<br/>' + item.detail + '</td><td>' + item.registed_at + '</td></tr>');
				});
			}else $("#btn_load_more_log").addClass("d-none");
		});
	});
});
</script>