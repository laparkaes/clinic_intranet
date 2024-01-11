<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">
	<title><?= $this->lang->line('system_init') ?></title>
	<meta content="" name="description">
	<meta content="" name="keywords">
	<link href="<?= base_url() ?>assets/img/favicon.png" rel="icon">
	<link href="<?= base_url() ?>assets/img/apple-touch-icon.png" rel="apple-touch-icon">
	<link href="https://fonts.gstatic.com" rel="preconnect">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
	<link href="<?= base_url() ?>assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?= base_url() ?>assets/vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
	<link href="<?= base_url() ?>assets/vendor/simple-datatables/style.css" rel="stylesheet">
	<link href="<?= base_url() ?>assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
	<link href="<?= base_url() ?>assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
	<link href="<?= base_url() ?>assets/vendor/remixicon/remixicon.css" rel="stylesheet">
	<link href="<?= base_url() ?>assets/vendor/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
	<link href="<?= base_url() ?>assets/vendor/toastr/toastr.min.css" rel="stylesheet">
	<link href="<?= base_url() ?>assets/css/style.css" rel="stylesheet">
</head>
<body>
	<div class="container">
		<section class="section register min-vh-100 d-flex flex-column">
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-md-8">
						<div class="d-flex justify-content-center py-4">
							<a href="<?= base_url() ?>" class="logo d-flex align-items-center w-auto">
								<img src="<?= base_url() ?>assets/img/logo.png" alt="">
								<span class="d-none d-lg-block">Everlyn Sys</span>
							</a>
						</div>
						<div class="card">
							<div class="card-body p-3">
								<h5 class="card-title text-center p-0 m-0 fs-4"><?= $this->lang->line('system_init') ?></h5>
							</div>
						</div>
						<div class="card">
							<div class="card-body">
								<div class="d-flex justify-content-between align-items-center">
									<h5 class="card-title"><?= $this->lang->line('w_company') ?></h5>
									<?php if ($sys_conf->company_id){ ?>
									<i class="bi bi-check-lg text-success" style="font-size: 1.5rem;"></i>
									<?php }else{ ?>
									<i class="bi bi-x-lg text-danger" style="font-size: 1.5rem;"></i>
									<?php } ?>
								</div>
								<form class="row g-3" id="form_company_init">
									<div class="col-md-4">
										<label class="form-label"><?= $this->lang->line('w_tax_id') ?></label>
										<div class="input-group">
											<input type="text" class="form-control" id="com_tax_id" value="<?= $sys_conf->company->tax_id ?>" name="tax_id">
												<button class="btn btn-primary" id="btn_search_company" type="button">
													<i class="bi bi-search"></i>
												</button>
										</div>
										<div class="sys_msg" id="com_tax_id_msg"></div>
									</div>
									<div class="col-md-8">
										<label class="form-label"><?= $this->lang->line('w_name') ?></label>
										<input type="text" class="form-control" id="com_name" value="<?= $sys_conf->company->name ?>" name="name">
										<div class="sys_msg" id="com_name_msg"></div>
									</div>
									<div class="col-md-8">
										<label class="form-label"><?= $this->lang->line('w_email') ?></label>
										<input type="text" class="form-control" id="com_email" value="<?= $sys_conf->company->email ?>" name="email">
										<div class="sys_msg" id="com_email_msg"></div>
									</div>
									<div class="col-md-4">
										<label class="form-label"><?= $this->lang->line('w_tel') ?></label>
										<input type="text" class="form-control" id="com_tel" value="<?= $sys_conf->company->tel ?>" name="tel">
										<div class="sys_msg" id="com_tel_msg"></div>
									</div>
									<div class="col-md-8">
										<label class="form-label"><?= $this->lang->line('w_address') ?></label>
										<input type="text" class="form-control" id="com_address" value="<?= $sys_conf->company->address ?>" name="address">
										<div class="sys_msg" id="com_address_msg"></div>
									</div>
									<div class="col-md-4">
										<label class="form-label"><?= $this->lang->line('w_urbanization') ?></label>
										<input type="text" class="form-control" id="com_urbanization" value="<?= $sys_conf->company->urbanization ?>" name="urbanization">
									</div>
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_department') ?></label>
										<select class="form-select" id="com_department_id" name="department_id">
											<option value="">-</option>
											<?php foreach($departments as $item){
												if ($item->id == $sys_conf->company->department_id) $selected = "selected";
												else $selected = ""; ?>
											<option value="<?= $item->id ?>" <?= $selected ?>><?= $item->name ?></option>
											<?php } ?>
										</select>
										<div class="sys_msg" id="com_department_msg"></div>
									</div>
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_province') ?></label>
										<select class="form-select" id="com_province_id" name="province_id">
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
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_district') ?></label>
										<select class="form-select" id="com_district_id" name="district_id">
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
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_ubigeo') ?></label>
										<input type="text" class="form-control" id="com_ubigeo" value="<?= $sys_conf->company->ubigeo ?>" name="ubigeo">
										<div class="sys_msg" id="com_ubigeo_msg"></div>
									</div>
									<div class="col-md-12 mb-0 pt-3">
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
								</form>
							</div>
						</div>
						<div class="card">
							<div class="card-body">
								<div class="d-flex justify-content-between align-items-center">
									<h5 class="card-title"><?= $this->lang->line('w_sunat_access') ?></h5>
									<?php if ($sys_conf->sunat_access){ ?>
									<i class="bi bi-check-lg text-success" style="font-size: 1.5rem;"></i>
									<?php }else{ ?>
									<i class="bi bi-x-lg text-danger" style="font-size: 1.5rem;"></i>
									<?php } ?>
								</div>
								<form class="row g-3" id="form_sunat_access_init">
									<div class="col-md-12">
										<label class="form-label"><?= $this->lang->line('w_certificate') ?> (*.pem)</label>
										<?php if ($sys_conf->sunat_certificate){ ?>
										<div class="form-control"><?= $sys_conf->sunat_certificate ?></div>
										<?php }else{ ?>
										<input type="file" class="form-control" name="sunat_certificate" accept=".pem">
										<?php } ?>
										<div class="sys_msg" id="sunat_certificate_msg"></div>
									</div>
									<div class="col-md-4">
										<label class="form-label"><?= $this->lang->line('w_ruc') ?></label>
										<input type="text" class="form-control" value="<?= $sys_conf->company->tax_id ?>" readonly>
										<div class="sys_msg" id="sunat_ruc_msg"></div>
									</div>
									<div class="col-md-4">
										<label class="form-label"><?= $this->lang->line('w_username') ?></label>
										<input type="text" class="form-control" value="<?= $sys_conf->sunat_username ?>" name="sunat_username">
										<div class="sys_msg" id="sunat_username_msg"></div>
									</div>
									<div class="col-md-4">
										<label class="form-label"><?= $this->lang->line('w_password') ?></label>
										<input type="text" class="form-control" value="<?= $sys_conf->sunat_password ?>" name="sunat_password">
										<div class="sys_msg" id="sunat_password_msg"></div>
									</div>
									<div class="col-md-12 pt-3 mb-0">
										<?php if ($sys_conf->sunat_certificate){ ?>
										<button type="button" class="btn btn-primary" id="btn_test_sunat">
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
								</form>
							</div>
						</div>
						<div class="card">
							<div class="card-body">
								<div class="d-flex justify-content-between align-items-center">
									<h5 class="card-title"><?= $this->lang->line('w_master_account') ?></h5>
									<?php if ($sys_conf->account_id){ ?>
									<i class="bi bi-check-lg text-success" style="font-size: 1.5rem;"></i>
									<?php }else{ ?>
									<i class="bi bi-x-lg text-danger" style="font-size: 1.5rem;"></i>
									<?php } ?>
								</div>
								<form class="row g-3" id="form_account_init">
									<div class="col-md-6">
										<label class="form-label"><?= $this->lang->line('w_account') ?></label>
										<input type="email" class="form-control" value="<?= $sys_conf->account->email ?>" name="a[email]">
										<div class="sys_msg" id="acc_email_msg"></div>
									</div>
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_password') ?></label>
										<input type="password" class="form-control" name="a[password]">
										<div class="sys_msg" id="acc_password_msg"></div>
									</div>
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_confirm') ?></label>
										<input type="password" class="form-control" name="a[confirm]">
										<div class="sys_msg" id="acc_confirm_msg"></div>
									</div>
									<?php $p = $sys_conf->account->person; ?>
									<div class="col-md-3">
										<label class="form-label"><?= $this->lang->line('w_document') ?></label>
										<select class="form-select" id="pe_doc_type_id" name="p[doc_type_id]">
											<?php foreach($doc_types as $d){ if ($d->sunat_code){ 
											if ($p->doc_type_id == $d->id) $s = "selected"; else $s = ""; ?>
											<option value="<?= $d->id ?>" <?= $s ?>><?= $d->description ?></option>
											<?php }} ?>
										</select>
										<div class="sys_msg" id="pe_doc_type_msg"></div>
									</div>
									<div class="col-md-3">
										<label class="form-label d-md-block d-none">&nbsp;</label>
										<div class="input-group">
											<input type="text" class="form-control" id="pe_doc_number" name="p[doc_number]" placeholder="<?= $this->lang->line('w_number') ?>" value="<?= $p->doc_number ?>">
											<button class="btn btn-primary" type="button" id="btn_search_person">
												<i class="bi bi-search"></i>
											</button>
										</div>
										<div class="sys_msg" id="pe_doc_number_msg"></div>
									</div>
									<div class="col-md-6">
										<label class="form-label"><?= $this->lang->line('w_name') ?></label>
										<input type="text" class="form-control" id="pe_name" name="p[name]" value="<?= $p->name ?>">
										<div class="sys_msg" id="pe_name_msg"></div>
									</div>
									<div class="col-md-12 pt-3">
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
								</form>
							</div>
						</div>
						<div class="card">
							<div class="card-body">
								<div class="d-flex justify-content-between align-items-center">
									<h5 class="card-title"><?= $this->lang->line('w_sale_type') ?></h5>
									<?php if ($sys_conf->sale_type_finished){ ?>
									<i class="bi bi-check-lg text-success" style="font-size: 1.5rem;"></i>
									<?php }else{ ?>
									<i class="bi bi-x-lg text-danger" style="font-size: 1.5rem;"></i>
									<?php } ?>
								</div>
								<div class="table-responsive">
									<table class="table align-middle">
										<thead>
											<tr>
												<th><?= $this->lang->line('w_description') ?></th>
												<th><?= $this->lang->line('w_serie') ?></th>
												<th><?= $this->lang->line('w_correlative_factura') ?></th>
												<th><?= $this->lang->line('w_correlative_boleta') ?></th>
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
														<button type="submit" class="btn btn-primary">
															<i class="bi bi-plus-lg"></i>
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
														<i class="bi bi-trash"></i>
													</button>
												</td>
											</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="d-grid gap-2 mt-3 mb-5">
							<button class="btn btn-primary btn-lg" type="button" id="btn_finish"><?= $this->lang->line('btn_end_init') ?></button>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
	<input type="hidden" id="base_url" value="<?= base_url() ?>">
	<input type="hidden" id="alert_success_title" value="<?= $this->lang->line('alert_success_title') ?>">
	<input type="hidden" id="alert_error_title" value="<?= $this->lang->line('alert_error_title') ?>">
	<input type="hidden" id="alert_warning_title" value="<?= $this->lang->line('alert_warning_title') ?>">
	<input type="hidden" id="alert_confirm_btn" value="<?= $this->lang->line('alert_confirm_btn') ?>">
	<input type="hidden" id="alert_cancel_btn" value="<?= $this->lang->line('alert_cancel_btn') ?>">
	<input type="hidden" id="base_url" value="<?= base_url() ?>">
	<script>
	document.addEventListener("DOMContentLoaded", () => {
		//company
		$("#form_company_init").submit(function(e) {
			e.preventDefault();
			ajax_form(this, "sys/system_init/company").done(function(res) {
				set_msg(res.msgs);
				swal_redirection(res.type, res.msg, window.location.href);
			});
		});
		
		function control_province(department_id){
			$("#com_province_id").val("");
			$("#com_province_id .province").addClass("d-none");
			$("#com_province_id .d" + department_id).removeClass("d-none");
			$("#com_district_id").val("");
			$("#com_district_id .district").addClass("d-none");
		}

		function control_district(province_id){
			$("#com_district_id").val("");
			$("#com_district_id .district").addClass("d-none");
			$("#com_district_id .p" + province_id).removeClass("d-none");
		}
		
		$("#btn_search_company").click(function() {
			ajax_simple({tax_id: $("#com_tax_id").val()}, "ajax_f/search_company").done(function(res) {
				swal(res.type, res.msg);
				control_province(res.company.department_id);
				control_district(res.company.province_id);
				//$("#com_tax_id").val(res.company.tax_id);
				$("#com_name").val(res.company.name);
				$("#com_email").val(res.company.email);
				$("#com_tel").val(res.company.tel);
				$("#com_address").val(res.company.address);
				$("#com_urbanization").val(res.company.urbanization);
				$("#com_ubigeo").val(res.company.ubigeo);
				$("#com_department_id").val(res.company.department_id);
				$("#com_province_id").val(res.company.province_id);
				$("#com_district_id").val(res.company.district_id);
			});
		});
		
		$("#btn_remove_company").click(function() {
			ajax_simple_warning({}, "sys/system_init/remove_company", "wm_company_remove").done(function(res) {
				swal_redirection(res.type, res.msg, window.location.href);
			});
		});
		
		//account
		$("#form_account_init").submit(function(e) {
			e.preventDefault();
			ajax_form(this, "sys/system_init/account").done(function(res) {
				set_msg(res.msgs);
				swal_redirection(res.type, res.msg, window.location.href);
			});
		});
		
		$("#btn_search_person").click(function() {
			var data = {doc_type_id: $("#pe_doc_type_id").val(), doc_number: $("#pe_doc_number").val()};
			ajax_simple(data, "ajax_f/search_person").done(function(res) {
				swal(res.type, res.msg);
				if (res.type == "success") $("#pe_name").val(res.person.name);
			});
		});
		
		$("#btn_remove_account").click(function() {
			ajax_simple_warning({}, "sys/system_init/remove_account", "wm_account_remove").done(function(res) {
				swal_redirection(res.type, res.msg, window.location.href);
			});
		});
		
		//sunat
		$("#form_sunat_access_init").submit(function(e) {e.preventDefault(); sunat_access_init(this);});
		$("#btn_remove_sunat").click(function() {remove_sunat();});
		$("#btn_test_sunat").click(function() {test_sunat();});
		
		//sale type
		$("#form_add_sale_type").submit(function(e) {e.preventDefault(); add_sale_type(this);});
		$(".btn_remove_sale_type").click(function() {remove_sale_type($(this).val());});
		$("#btn_finish_sale_type").click(function() {finish_sale_type();});
		
		//general
		$("#btn_finish").click(function() {finish_init();});
	});
	</script>
	<script src="<?= base_url() ?>assets/vendor/jquery-3.7.0.min.js"></script>
	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.min.js"></script>
	<script src="<?= base_url() ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="<?= base_url() ?>assets/vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
	<script src="<?= base_url() ?>assets/vendor/tinymce/tinymce.min.js"></script>
	<script src="<?= base_url() ?>assets/vendor/simple-datatables/simple-datatables.js"></script>
	<script src="<?= base_url() ?>assets/vendor/apexcharts/apexcharts.min.js"></script>
	<script src="<?= base_url() ?>assets/vendor/sweetalert2/dist/sweetalert2.all.min.js"></script>
	<script src="<?= base_url() ?>assets/vendor/toastr/toastr.min.js"></script>
	<script src="<?= base_url() ?>assets/js/main.js"></script>
	<script src="<?= base_url() ?>assets/js/lang.js"></script>
	<script src="<?= base_url() ?>assets/js/func.js"></script>
</body>
</html>