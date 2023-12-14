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
													<button type="submit" class="btn btn-primary"><i class="bi bi-plus"></i></button>
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
													<button type="submit" class="btn btn-primary"><i class="bi bi-plus"></i></button>
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