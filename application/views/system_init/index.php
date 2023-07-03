<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= $title ?></title>
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url() ?>resources/images/favicon.png">
	<link href="<?= base_url() ?>resources/vendor_/sweetalert2-11.4.35/dist/sweetalert2.min.css" rel="stylesheet">
	<link href="<?= base_url() ?>resources/vendor_/fontawesome5pro/css/all.min.css" rel="stylesheet">
    <link href="<?= base_url() ?>resources/css/style.css" rel="stylesheet">
	<link href="<?= base_url() ?>resources/css/setting.css" rel="stylesheet">
</head>
<body class="vh-100">
    <div class="authincation h-100">
        <div class="container h-100 py-3">
			<div class="row d-flex justify-content-center">
				<div class="col-md-8">
					<div class="card">
						<div class="card-body">
							<h3 class="text-center mb-0"><?= $this->lang->line('system_init') ?></h3>
						</div>
					</div>
				</div>
			</div>
			<div class="row d-flex justify-content-center">
				<div class="col-md-8">
					<div class="card">
						<div class="card-header">
							<h4 class="mb-0"><?= $this->lang->line('title_company') ?></h4>
							<?php if ($sys_conf->company_id){ ?>
							<i class="fas fa-check text-success fa-lg"></i>
							<?php }else{ ?>
							<i class="fas fa-times text-danger fa-lg"></i>
							<?php } ?>
						</div>
						<div class="card-body">
							<form id="form_company_init">
								<div class="form-row">
									<div class="form-group col-md-4">
										<label><?= $this->lang->line('lb_tax_id') ?></label>
										<div class="input-group">
                                            <input type="text" class="form-control" id="com_tax_id" value="<?= $sys_conf->company->tax_id ?>" name="tax_id">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary border-0" id="btn_search_company" type="button">
													<i class="fas fa-search"></i>
												</button>
                                            </div>
                                        </div>
										<div class="sys_msg" id="com_tax_id_msg"></div>
									</div>
									<div class="form-group col-md-8">
										<label><?= $this->lang->line('lb_name') ?></label>
										<input type="text" class="form-control" id="com_name" value="<?= $sys_conf->company->name ?>" name="name">
										<div class="sys_msg" id="com_name_msg"></div>
									</div>
									<div class="form-group col-md-8">
										<label><?= $this->lang->line('lb_email') ?></label>
										<input type="text" class="form-control" id="com_email" value="<?= $sys_conf->company->email ?>" name="email">
										<div class="sys_msg" id="com_email_msg"></div>
									</div>
									<div class="form-group col-md-4">
										<label><?= $this->lang->line('lb_tel') ?></label>
										<input type="text" class="form-control" id="com_tel" value="<?= $sys_conf->company->tel ?>" name="tel">
										<div class="sys_msg" id="com_tel_msg"></div>
									</div>
									<div class="form-group col-md-8">
										<label><?= $this->lang->line('lb_address') ?></label>
										<input type="text" class="form-control" id="com_address" value="<?= $sys_conf->company->address ?>" name="address">
										<div class="sys_msg" id="com_address_msg"></div>
									</div>
									<div class="form-group col-md-4">
										<label><?= $this->lang->line('lb_urbanization') ?></label>
										<input type="text" class="form-control" id="com_urbanization" value="<?= $sys_conf->company->urbanization ?>" name="urbanization">
									</div>
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_department') ?></label>
										<select class="form-control" id="com_department_id" name="department_id">
											<option value="">-</option>
											<?php foreach($departments as $item){
												if ($item->id == $sys_conf->company->department_id) $selected = "selected";
												else $selected = ""; ?>
											<option value="<?= $item->id ?>" <?= $selected ?>><?= $item->name ?></option>
											<?php } ?>
										</select>
										<div class="sys_msg" id="com_department_msg"></div>
									</div>
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_province') ?></label>
										<select class="form-control" id="com_province_id" name="province_id">
											<option value="">-</option>
											<?php foreach($provinces as $item){
												$selected = ""; $class = "d-none";
												if ($item->department_id == $sys_conf->company->department_id){ $class = "";
													if ($item->id == $sys_conf->company->province_id) $selected = "selected"; } ?>
											<option class="province d<?= $item->department_id ?> <?= $class ?>" value="<?= $item->id ?>" <?= $selected ?>><?= $item->name ?></option>
											<?php } ?>
										</select>
										<div class="sys_msg" id="com_province_msg"></div>
									</div>
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_district') ?></label>
										<select class="form-control" id="com_district_id" name="district_id">
											<option value="">-</option>
											<?php foreach($districts as $item){
												$selected = ""; $class = "d-none";
												if ($item->province_id == $sys_conf->company->province_id){ $class = "";
													if ($item->id == $sys_conf->company->district_id) $selected = "selected"; } ?>
											<option class="district p<?= $item->province_id ?> <?= $class ?>" value="<?= $item->id ?>" <?= $selected ?>><?= $item->name ?></option>
											<?php } ?>
										</select>
										<div class="sys_msg" id="com_district_msg"></div>
									</div>
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_ubigeo') ?></label>
										<input type="text" class="form-control" id="com_ubigeo" value="<?= $sys_conf->company->ubigeo ?>" name="ubigeo">
										<div class="sys_msg" id="com_ubigeo_msg"></div>
									</div>
									<div class="form-group col-md-12 mb-0 pt-3">
										<?php if ($sys_conf->company_id){ ?>
										<button type="button" class="btn btn-danger light" id="btn_remove_company">
											<?= $this->lang->line('btn_remove') ?>
										</button>
										<?php }else{ ?>
										<button type="submit" class="btn btn-primary">
											<?= $this->lang->line('btn_save') ?>
										</button>
										<?php } ?>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="row d-flex justify-content-center">
				<div class="col-md-8">
					<div class="card">
						<div class="card-header">
							<h4 class="mb-0"><?= $this->lang->line('title_sunat_access') ?></h4>
							<?php if ($sys_conf->sunat_access){ ?>
							<i class="fas fa-check text-success fa-lg"></i>
							<?php }else{ ?>
							<i class="fas fa-times text-danger fa-lg"></i>
							<?php } ?>
						</div>
						<div class="card-body">
							<form id="form_sunat_access_init">
								<div class="row">
									<div class="col-md-12">
										<div class="form-row">
											<div class="form-group col-md-12">
												<div>
													<label><?= $this->lang->line('lb_certificate') ?> (*.pem)</label>
													<?php if ($sys_conf->sunat_certificate){ ?>
													<i class="fas fa-check text-success ml-1"></i>
													<?php } ?>
												</div>
												<input type="file" class="form-control" name="sunat_certificate">
												<div class="sys_msg" id="sunat_certificate_msg"></div>
											</div>
											<div class="form-group col-md-4">
												<label><?= $this->lang->line('lb_ruc') ?></label>
												<input type="text" class="form-control" value="<?= $sys_conf->company->tax_id ?>" readonly>
												<div class="sys_msg" id="sunat_ruc_msg"></div>
											</div>
											<div class="form-group col-md-4">
												<label><?= $this->lang->line('lb_username') ?></label>
												<input type="text" class="form-control" value="<?= $sys_conf->sunat_username ?>" name="sunat_username">
												<div class="sys_msg" id="sunat_username_msg"></div>
											</div>
											<div class="form-group col-md-4">
												<label><?= $this->lang->line('lb_password') ?></label>
												<input type="text" class="form-control" value="<?= $sys_conf->sunat_password ?>" name="sunat_password">
												<div class="sys_msg" id="sunat_password_msg"></div>
											</div>
											<div class="form-group col-md-12 pt-3 mb-0">
												<?php if ($sys_conf->sunat_certificate){ ?>
												<button type="button" class="btn btn-info" id="btn_test_sunat">
													<?= $this->lang->line('btn_test_access') ?>
												</button>
												<button type="button" class="btn btn-danger light" id="btn_remove_sunat">
													<?= $this->lang->line('btn_remove') ?>
												</button>
												<?php }else{ ?>
												<button type="submit" class="btn btn-primary">
													<?= $this->lang->line('btn_save') ?>
												</button>
												<?php } ?>
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="row d-flex justify-content-center">
				<div class="col-md-8">
					<div class="card">
						<div class="card-header">
							<h4 class="mb-0"><?= $this->lang->line('title_master_account') ?></h4>
							<?php if ($sys_conf->account_id){ ?>
							<i class="fas fa-check text-success fa-lg"></i>
							<?php }else{ ?>
							<i class="fas fa-times text-danger fa-lg"></i>
							<?php } ?>
						</div>
						<div class="card-body">
							<form id="form_account_init">
								<div class="row">
									<div class="col-md-6">
										<div class="form-row">
											<div class="form-group col-md-12">
												<label><?= $this->lang->line('lb_account') ?></label>
												<input type="email" class="form-control" value="<?= $sys_conf->account->email ?>" name="a[email]">
												<div class="sys_msg" id="acc_email_msg"></div>
											</div>
											<div class="form-group col-md-6">
												<label><?= $this->lang->line('lb_password') ?></label>
												<input type="password" class="form-control" name="a[password]">
												<div class="sys_msg" id="acc_password_msg"></div>
											</div>
											<div class="form-group col-md-6">
												<label><?= $this->lang->line('lb_confirm') ?></label>
												<input type="password" class="form-control" name="a[confirm]">
												<div class="sys_msg" id="acc_confirm_msg"></div>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<?php $p = $sys_conf->account->person; ?>
										<div class="form-row">
											<div class="form-group col-md-6">
												<label><?= $this->lang->line('lb_document') ?></label>
												<select class="form-control" id="pe_doc_type_id" name="p[doc_type_id]">
													<?php foreach($doc_types as $d){ if ($d->sunat_code){ 
													if ($p->doc_type_id == $d->id) $s = "selected"; else $s = ""; ?>
													<option value="<?= $d->id ?>" <?= $s ?>><?= $d->description ?></option>
													<?php }} ?>
												</select>
												<div class="sys_msg" id="pe_doc_type_msg"></div>
											</div>
											<div class="form-group col-md-6">
												<label class="d-md-block d-none">&nbsp;</label>
												<div class="input-group">
													<input type="text" class="form-control" id="pe_doc_number" name="p[doc_number]" placeholder="<?= $this->lang->line('lb_number') ?>" value="<?= $p->doc_number ?>">
													<div class="input-group-append">
														<button class="btn btn-primary border-0" type="button" id="btn_search_person">
															<i class="fas fa-search"></i>
														</button>
													</div>
												</div>
												<div class="sys_msg" id="pe_doc_number_msg"></div>
											</div>
											<div class="form-group col-md-12">
												<label><?= $this->lang->line('lb_name') ?></label>
												<input type="text" class="form-control" id="pe_name" name="p[name]" value="<?= $p->name ?>">
												<div class="sys_msg" id="pe_name_msg"></div>
											</div>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-row">
											<div class="form-group col-md-12 pt-3 mb-0">
												<?php if ($sys_conf->account_id){ ?>
												<button type="button" class="btn btn-danger light" id="btn_remove_account">
													<?= $this->lang->line('btn_remove') ?>
												</button>
												<?php }else{ ?>
												<button type="submit" class="btn btn-primary">
													<?= $this->lang->line('btn_save') ?>
												</button>
												<?php } ?>
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="row d-flex justify-content-center">
				<div class="col-md-8">
					<div class="card">
						<div class="card-header">
							<h4 class="mb-0"><?= $this->lang->line('title_sale_type') ?></h4>
							<?php if ($sys_conf->sale_type_finished){ ?>
							<i class="fas fa-check text-success fa-lg"></i>
							<?php }else{ ?>
							<i class="fas fa-times text-danger fa-lg"></i>
							<?php } ?>
						</div>
						<div class="card-body">
							<div class="table-responsive">
								<table class="table table-responsive-md mb-0">
									<thead>
										<tr>
											<th><strong><?= $this->lang->line('th_description') ?></strong></th>
											<th><strong><?= $this->lang->line('th_serie') ?></strong></th>
											<th><strong><?= $this->lang->line('th_correlative_factura') ?></strong></th>
											<th><strong><?= $this->lang->line('th_correlative_boleta') ?></strong></th>
											<th>
												<?php if ($sys_conf->sale_type_finished) $d = "d-none"; else $d = ""; ?>
												<button type="button" class="btn btn-primary <?= $d ?>" id="btn_finish_sale_type">
													<?= $this->lang->line('btn_finish') ?>
												</button>
											</th>
										</tr>
									</thead>
									<tbody id="tbody_sale_types">
										<form id="form_add_sale_type">
											<tr>
												<td>
													<input type="text" class="form-control" name="description">
													<div class="sys_msg" id="st_description_msg"></div>
												</td>
												<td>
													<input type="text" class="form-control" name="sunat_serie">
													<div class="sys_msg" id="st_sunat_serie_msg"></div>
												</td>
												<td>
													<input type="text" class="form-control" name="start_factura">
													<div class="sys_msg" id="st_start_factura_msg"></div>
												</td>
												<td>
													<input type="text" class="form-control" name="start_boleta">
													<div class="sys_msg" id="st_start_boleta_msg"></div>
												</td>
												<td class="text-right">
													<button type="submit" class="btn btn-success light">
														<i class="fas fa-plus"></i>
													</button>
												</td>
											</tr>
										</form>
										<?php foreach($sale_types as $item){ ?>
										<tr class="row_sale_type">
											<td><?= $item->description ?></td>
											<td><?= $item->sunat_serie ?></td>
											<td><?= $item->start_factura ?></td>
											<td><?= $item->start_boleta ?></td>
											<td class="text-right">
												<button type="button" class="btn btn-danger light btn_remove_sale_type" value="<?= $item->id ?>">
													<i class="fas fa-trash"></i>
												</button>
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
			<div class="row d-flex justify-content-center">
				<div class="col-md-8">
					<div class="card">
						<div class="card-body">
							<button type="button" class="btn btn-primary btn-lg btn-block" id="btn_finish">
								<?= $this->lang->line('btn_end_init') ?>
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
    </div>
	<input type="hidden" id="base_url" value="<?= base_url() ?>">
	<input type="hidden" id="alert_success_title" value="<?= $this->lang->line('alert_success_title') ?>">
	<input type="hidden" id="alert_error_title" value="<?= $this->lang->line('alert_error_title') ?>">
	<input type="hidden" id="alert_warning_title" value="<?= $this->lang->line('alert_warning_title') ?>">
	<input type="hidden" id="alert_confirm_btn" value="<?= $this->lang->line('alert_confirm_btn') ?>">
	<input type="hidden" id="alert_cancel_btn" value="<?= $this->lang->line('alert_cancel_btn') ?>">
	<input type="hidden" id="warning_rco" value="<?= $this->lang->line('warning_rco') ?>">
	<input type="hidden" id="warning_rac" value="<?= $this->lang->line('warning_rac') ?>">
	<input type="hidden" id="warning_rsd" value="<?= $this->lang->line('warning_rsd') ?>">
	<input type="hidden" id="warning_rst" value="<?= $this->lang->line('warning_rst') ?>">
	<input type="hidden" id="warning_fst" value="<?= $this->lang->line('warning_fst') ?>">
	<input type="hidden" id="warning_fsi" value="<?= $this->lang->line('warning_fsi') ?>">
	<script src="<?= base_url() ?>resources/vendor/global/global.min.js"></script>
	<script src="<?= base_url() ?>resources/vendor_/sweetalert2-11.4.35/dist/sweetalert2.min.js"></script>
    <script src="<?= base_url() ?>resources/js/custom.min.js"></script>
	<script src="<?= base_url() ?>resources/js/deznav-init.js"></script>
	<script src="<?= base_url() ?>resources/js/init/general.js"></script>
	<script src="<?= base_url() ?>resources/js/init/system_init/index.js"></script>
</body>
</html>