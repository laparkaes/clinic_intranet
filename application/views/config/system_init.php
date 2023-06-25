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
									<div class="form-group col-md-12 pt-3">
										<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_save') ?></button>
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
								<div class="form-row">
									<div class="form-group col-md-12">
										<label><?= $this->lang->line('lb_account') ?></label>
										<input type="email" class="form-control" value="<?= $sys_conf->account->email ?>" name="email">
										<div class="sys_msg" id="acc_email_msg"></div>
									</div>
									<div class="form-group col-md-6">
										<label><?= $this->lang->line('lb_password') ?></label>
										<input type="password" class="form-control" name="password">
										<div class="sys_msg" id="acc_password_msg"></div>
									</div>
									<div class="form-group col-md-6">
										<label><?= $this->lang->line('lb_password_confirm') ?></label>
										<input type="password" class="form-control" name="confirm">
										<div class="sys_msg" id="acc_confirm_msg"></div>
									</div>
									<div class="form-group col-md-12 pt-3">
										<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_save') ?></button>
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
											<th><strong><?= $this->lang->line('th_serie') ?></strong></th>
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
													<input type="text" class="form-control" name="start">
													<div class="sys_msg" id="st_start_msg"></div>
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
											<td><?= $item->start ?></td>
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
	<input type="hidden" id="warning_rst" value="<?= $this->lang->line('warning_rst') ?>">
	<input type="hidden" id="warning_fst" value="<?= $this->lang->line('warning_fst') ?>">
	<input type="hidden" id="warning_fsi" value="<?= $this->lang->line('warning_fsi') ?>">
	<script src="<?= base_url() ?>resources/vendor/global/global.min.js"></script>
	<script src="<?= base_url() ?>resources/vendor_/sweetalert2-11.4.35/dist/sweetalert2.min.js"></script>
    <script src="<?= base_url() ?>resources/js/custom.min.js"></script>
	<script src="<?= base_url() ?>resources/js/deznav-init.js"></script>
	<script src="<?= base_url() ?>resources/js/init/general.js"></script>
	<script src="<?= base_url() ?>resources/js/init/config/system_init.js"></script>
</body>
</html>