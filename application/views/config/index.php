<div class="col-md-12 d-md-none d-block">
	<div class="row page-titles mx-0">
		<div class="col-sm-6 p-md-0">
			<div class="welcome-text">
				<h4><?= $this->lang->line("setting") ?></h4>
			</div>
		</div>
	</div>
</div>
<div class="col-md-12">
	<div class="card">
		<div class="card-body">
			<div class="custom-tab-1">
				<ul class="nav nav-tabs">
					<li class="nav-item">
						<a class="nav-link active" data-toggle="tab" href="#bl_company_admin">
							<i class="fal fa-building mr-3"></i><?= $this->lang->line("title_company") ?>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#bl_profile_admin">
							<i class="fal fa-diagnoses mr-3"></i><?= $this->lang->line("title_profile") ?>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#bl_medicine_admin">
							<i class="fal fa-pills mr-3"></i><?= $this->lang->line("title_medicine") ?>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#bl_access_admin">
							<i class="fal fa-lock-alt mr-3"></i><?= $this->lang->line("title_access") ?>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#bl_history_admin">
							<i class="fal fa-history mr-3"></i><?= $this->lang->line("title_log") ?>
						</a>
					</li>
				</ul>
				<div class="tab-content mt-4" style="min-height: 500px;">
					<div class="tab-pane fade show active" id="bl_company_admin">
						<form action="#" id="form_update_company_data" class="row">
							<div class="col-md-12">
								<h5 class="text-primary mb-3"><?= $this->lang->line('title_company_data') ?></h5>
								<div class="form-row">
									<div class="form-group col-md-4">
										<label><?= $this->lang->line('lb_tax_id') ?></label>
										<div class="input-group">
                                            <input type="text" class="form-control" id="uc_tax_id" value="<?= $company->tax_id ?>" name="tax_id">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary border-0" id="btn_search_company" type="button">
													<i class="fas fa-search"></i>
												</button>
                                            </div>
                                        </div>
										<div class="sys_msg" id="uc_tax_id_msg"></div>
									</div>
									<div class="form-group col-md-8">
										<label><?= $this->lang->line('lb_name') ?></label>
										<input type="text" class="form-control" id="uc_name" value="<?= $company->name ?>" name="name">
										<div class="sys_msg" id="uc_name_msg"></div>
									</div>
									<div class="form-group col-md-8">
										<label><?= $this->lang->line('lb_email') ?></label>
										<input type="text" class="form-control" id="uc_email" value="<?= $company->email ?>" name="email">
										<div class="sys_msg" id="uc_email_msg"></div>
									</div>
									<div class="form-group col-md-4">
										<label><?= $this->lang->line('lb_tel') ?></label>
										<input type="text" class="form-control" id="uc_tel" value="<?= $company->tel ?>" name="tel">
										<div class="sys_msg" id="uc_tel_msg"></div>
									</div>
									<div class="form-group col-md-8">
										<label><?= $this->lang->line('lb_address') ?></label>
										<input type="text" class="form-control" id="uc_address" value="<?= $company->address ?>" name="address">
										<div class="sys_msg" id="uc_address_msg"></div>
									</div>
									<div class="form-group col-md-4">
										<label><?= $this->lang->line('lb_urbanization') ?></label>
										<input type="text" class="form-control" id="uc_urbanization" value="<?= $company->urbanization ?>" name="urbanization">
									</div>
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_department') ?></label>
										<select class="form-control" id="uc_department_id" name="department_id">
											<option value="">-</option>
											<?php foreach($departments as $item){
												if ($item->id == $company->department_id) $selected = "selected";
												else $selected = ""; ?>
											<option value="<?= $item->id ?>" <?= $selected ?>><?= $item->name ?></option>
											<?php } ?>
										</select>
										<div class="sys_msg" id="uc_department_msg"></div>
									</div>
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_province') ?></label>
										<select class="form-control" id="uc_province_id" name="province_id">
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
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_district') ?></label>
										<select class="form-control" id="uc_district_id" name="district_id">
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
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_ubigeo') ?></label>
										<input type="text" class="form-control" id="uc_ubigeo" value="<?= $company->ubigeo ?>" name="ubigeo">
										<div class="sys_msg" id="uc_ubigeo_msg"></div>
									</div>
									<div class="form-group col-md-12 pt-3">
										<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_save') ?></button>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="tab-pane fade" id="bl_profile_admin">
						<div class="row">
							<div class="col-md-12 d-flex justify-content-between">
								<h5 class="text-primary"><?= $this->lang->line("title_profile_admin") ?></h5>
								<div class="btn-group">
									<button type="button" class="btn control_bl_profile btn-primary" id="btn_list_profile" value="bl_profile_list">
										<i class="fas fa-list"></i>
									</button>
									<button type="button" class="btn control_bl_profile btn-outline-primary" value="bl_profile_add">
										<i class="fas fa-plus"></i>
									</button>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 bl_profile d-none" id="bl_profile_add">
								<form class="form-row" id="form_register_profile" action="#">
									<div class="form-group col-md-12">
										<label><?= $this->lang->line('lb_profile_name') ?></label>
										<input type="text" class="form-control" name="name">
										<div class="sys_msg" id="rp_name_msg"></div>
									</div>
									<div class="form-group col-md-12">
										<label><?= $this->lang->line('lb_examinations') ?> <i class="fas fa-cog text-info" data-toggle="modal" data-target="#md_admin_exam"></i></label>
										<div class="row">
											<div class="col-md-6">
												<select class="form-control mb-3" id="rp_category">
													<option value=""><?= $this->lang->line('txt_view_all') ?></option>
													<?php foreach($exam_category as $ec){ ?>
													<option value="<?= $ec->id ?>"><?= $ec->name ?></option>
													<?php } ?>
												</select>
											</div>
											<div class="col-md-6">
												<input type="text" class="form-control mb-3" id="rp_filter" placeholder="<?= $this->lang->line('lb_filter') ?>">
											</div>
											<div class="col-md-12 sys_msg" id="rp_exams_msg"></div>
										</div>
										<div class="row" id="ex_profile_list" style="max-height: 400px; overflow-y: auto;">
											<div class="col-md-12 text-danger d-none" id="rp_no_result_msg">
												<?= $this->lang->line('msg_no_result') ?>
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
									<div class="form-group col-md-12 pt-3">
										<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_register') ?></button>
									</div>
								</form>
							</div>
							<div class="col-md-12 bl_profile" id="bl_profile_list">
								<div class="table-responsive">
									<table class="table table-responsive-md">
										<thead>
											<tr>
												<th><strong>#</strong></th>
												<th class="w-30"><strong><?= $this->lang->line('lb_profile') ?></strong></th>
												<th><strong><?= $this->lang->line('lb_examinations') ?></strong></th>
												<th></th>
											</tr>
										</thead>
										<tbody id="profile_list">
											<?php foreach($exam_profiles as $i => $item){ ?>
											<tr>
												<td><?= $i + 1 ?></td>
												<td><?= $item->name ?></td>
												<td><?= $item->exams ?></td>
												<td class="text-right">
													<button type="button" class="btn btn-danger shadow btn-xs sharp remove_profile" value="<?= $item->id ?>">
														<i class="fas fa-trash"></i>
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
					</div>
					<div class="tab-pane fade" id="bl_medicine_admin">
						<div class="row">
							<div class="col-md-12 d-flex justify-content-between">
								<h5 class="text-primary"><?= $this->lang->line("title_medicine_admin") ?></h5>
								<div class="btn-group">
									<button type="button" class="btn control_bl_medicine btn-primary" id="btn_list_medicine" value="bl_medicine_list">
										<i class="fas fa-list"></i>
									</button>
									<button type="button" class="btn control_bl_medicine btn-outline-primary" value="bl_medicine_add">
										<i class="fas fa-plus"></i>
									</button>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 bl_medicine d-none" id="bl_medicine_add">
								<form class="form-row" id="form_register_medicine" action="#">
									<div class="form-group col-md-12">
										<label><?= $this->lang->line('lb_medicine') ?></label>
										<input type="text" class="form-control" name="name">
										<div class="sys_msg" id="rm_name_msg"></div>
									</div>
									<div class="form-group col-md-12 pt-3">
										<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_register') ?></button>
									</div>
								</form>
							</div>
							<div class="col-md-12 bl_medicine" id="bl_medicine_list">
								<div class="table-responsive">
									<table class="table table-responsive-md">
										<thead>
											<tr>
												<th><strong>#</strong></th>
												<th><strong><?= $this->lang->line('lb_medicine') ?></strong></th>
												<th></th>
											</tr>
										</thead>
										<tbody id="medicine_list">
											<?php foreach($medicines as $i => $item){ ?>
											<tr>
												<td><?= $i + 1 ?></td>
												<td><?= $item->name ?></td>
												<td class="text-right">
													<button type="button" class="btn btn-danger shadow btn-xs sharp btn_remove_medicine" value="<?= $item->id ?>">
														<i class="fas fa-trash"></i>
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
					</div>
					<div class="tab-pane fade" id="bl_access_admin">
						<div class="row">
							<div class="col-md-12">
								<h5 class="text-primary mb-3"><?= $this->lang->line('title_access_admin') ?></h5>
							</div>
							<div class="col-md-12">
								<div class="table-responsive">
									<table class="table table-responsive-md text-center">
										<tbody>
											<?php $colspan = count($roles) + 1; foreach($access as $module => $a_list){ ?>
											<tr class="bg-light">
												<td class="text-left"><strong><?= $this->lang->line("module_".$module) ?></strong></td>
												<?php foreach($roles as $item){ ?>
												<td style="width: 105px;"><strong><?= $this->lang->line('role_'.$item->name) ?></strong></td>
												<?php } ?>
											</tr>
											<?php foreach($a_list as $a){ ?>
											<tr>
												<td class="text-left pl-4">
													<?= $this->lang->line('access_'.$a->description) ?>
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
						</div>
					</div>
					<div class="tab-pane fade" id="bl_history_admin">
						<div class="row">
							<div class="col-md-12">
								<h5 class="text-primary mb-3"><?= $this->lang->line('title_system_log') ?></h5>
								<div class="table-responsive">
									<table class="table display">
										<thead>
											<tr>
												<th>#</th>
												<th><?= $this->lang->line('th_account') ?></th>
												<th><?= $this->lang->line('th_detail') ?></th>
												<th><?= $this->lang->line('th_time') ?></th>
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
	</div>
</div>
<div class="d-none">
	<input type="hidden" id="warning_rpr" value="<?= $this->lang->line('warning_rpr') ?>">
	<input type="hidden" id="warning_rme" value="<?= $this->lang->line('warning_rme') ?>">
	<input type="hidden" id="txt_view_all" value="<?= $this->lang->line('txt_view_all') ?>">
</div>
<div class="modal fade" id="md_admin_exam">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?= $this->lang->line('lb_examination_admin') ?></h5>
				<button type="button" class="close" data-dismiss="modal"><span>&times;</span>
				</button>
			</div>
			<div class="card-body">
				<ul class="nav nav-tabs mb-3">
					<li class="nav-item">
						<a href="#ad_category" class="nav-link active" data-toggle="tab" aria-expanded="false"><?= $this->lang->line('btn_category') ?></a>
					</li>
					<li class="nav-item">
						<a href="#ad_examination" class="nav-link" data-toggle="tab" aria-expanded="false"><?= $this->lang->line('btn_examination') ?></a>
					</li>
				</ul>
				<div class="tab-content">
					<div id="ad_category" class="tab-pane active">
						<div class="row">
							<div class="col-md-12" style="max-height: 350px; overflow-y: auto;">
								<table class="table">
									<thead>
										<tr>
											<th><strong>#</strong></th>
											<th><strong><?= $this->lang->line("th_category") ?></strong></th>
											<th></th>
										</tr>
									</thead>
									<tbody id="ad_category_list">
										<tr>
											<form id="form_add_exam_category">
												<td colspan="2">
													<input type="text" class="form-control" name="name" placeholder="<?= $this->lang->line("lb_category_name") ?>">
												</td>
												<td class="text-right">
													<button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i></button>
												</td>
											</form>
										</tr>
										<?php foreach($exam_category as $i => $item){ ?>
										<tr class="ad_cat_rows">
											<td><strong><?= $i + 1 ?></strong></td>
											<td><?= $item->name ?></td>
											<td class="text-right">
												<button type="button" class="btn light btn-danger btn_remove_exam_category" value="<?= $item->id ?>"><i class="fas fa-trash"></i></button>
											</td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div id="ad_examination" class="tab-pane">
						<div class="row">
							<div class="col-md-12" style="max-height: 350px; overflow-y: auto;">
								<table class="table">
									<thead>
										<tr>
											<th><strong>#</strong></th>
											<th><strong><?= $this->lang->line("th_examination") ?></strong></th>
											<th></th>
										</tr>
									</thead>
									<tbody id="ad_exam_list">
										<tr>
											<form id="form_add_exam">
												<td colspan="2">
													<div class="form-row">
														<div class="col-sm-5">
															<select class="form-control" id="ad_ex_category" name="category_id">
																<option value="">--</option>
																<?php foreach($exam_category as $item){ ?>
																<option value="<?= $item->id ?>"><?= $item->name ?></option>
																<?php } ?>
															</select>
														</div>
														<div class="col-sm-7 mt-2 mt-sm-0">
															<input type="text" class="form-control" name="name" placeholder="<?= $this->lang->line("lb_examination_name") ?>">
														</div>
													</div>
												</td>
												<td class="text-right">
													<button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i></button>
												</td>
											</form>
										</tr>
										<?php foreach($exams as $i => $item){ ?>
										<tr class="ad_exam_rows">
											<td><strong><?= $i + 1 ?></strong></td>
											<td>
												<?= $item->name ?><br/>
												<small><?= $item->category ?></small>
											</td>
											<td class="text-right">
												<button type="button" class="btn light btn-danger btn_remove_exam" value="<?= $item->id ?>"><i class="fas fa-trash"></i></button>
											</td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger light" data-dismiss="modal">
					<?= $this->lang->line('btn_close') ?>
				</button>
			</div>
		</div>
	</div>
</div>
