<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= $title ?></title>
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url() ?>resources/images/favicon.png">
	<link href="<?= base_url() ?>resources/vendor_/sweetalert2-11.4.35/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="<?= base_url() ?>resources/css/style.css" rel="stylesheet">
	<link href="<?= base_url() ?>resources/css/setting.css" rel="stylesheet">
</head>
<body class="vh-100">
    <div class="authincation h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-6">
                    <div class="authincation-content">
                        <div class="row no-gutters">
                            <div class="col-xl-12">
                                <div class="auth-form">
									<div class="text-center mb-3">
										<a href="<?= base_url() ?>"><img src="<?= base_url() ?>resources/images/favicon.png" alt=""></a>
									</div>
                                    <?php if ($has_master){ ?>
									<h4 class="text-center mb-3"><?= $this->lang->line('title_login') ?></h4>
                                    <form action="#" id="form_login">
                                        <div class="form-group">
                                            <label class="mb-1">
												<strong><?= $this->lang->line('lb_username') ?></strong>
											</label>
                                            <input type="text" class="form-control" placeholder="email@example.com" name="email">
											<div class="sys_msg" id="lg_email_msg"></div>
                                        </div>
                                        <div class="form-group">
                                            <label class="mb-1">
												<strong><?= $this->lang->line('lb_password') ?></strong>
											</label>
                                            <input type="password" class="form-control" name="password">
											<div class="sys_msg" id="lg_pass_msg"></div>
                                        </div>
                                        <div class="form-row d-flex justify-content-end mt-4 mb-2">
                                            <div class="form-group">
                                                <a href="#" data-toggle="modal" data-target="#forgot_pass"><?= $this->lang->line('msg_fp') ?></a>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary btn-block"><?= $this->lang->line('btn_login') ?></button>
                                        </div>
                                    </form>
									<?php }else{ ?>
									<h4 class="text-center mb-3"><?= $this->lang->line('title_master_account') ?></h4>
                                    <form action="#" id="form_generate_master">
										<div class="form-row">
											<div class="form-group col-md-12">
												<label class="mb-1">
													<strong><?= $this->lang->line('lb_document') ?></strong>
												</label>
												<div class="input-group">
													<select class="form-control" name="p[doc_type_id]">
														<?php foreach($doc_types as $d){ ?>
														<option value="<?= $d->id ?>"><?= $d->description ?></option>
														<?php } ?>
													</select>
													<input type="text" class="form-control border-left-0" name="p[doc_number]" placeholder="<?= $this->lang->line('lb_number') ?>">
												</div>
												<div class="sys_msg" id="gm_doc_msg"></div>
											</div>
											<div class="form-group col-md-12">
												<label class="mb-1">
													<strong><?= $this->lang->line('lb_name') ?></strong>
												</label>
												<input type="text" class="form-control" name="p[name]">
												<div class="sys_msg" id="gm_name_msg"></div>
											</div>
											<div class="form-group col-md-12">
												<label class="mb-1">
													<strong><?= $this->lang->line('lb_username') ?></strong>
												</label>
												<input type="email" class="form-control" placeholder="email@example.com" name="a[email]">
												<div class="sys_msg" id="gm_email_msg"></div>
											</div>
											<div class="form-group col-md-6">
												<label class="mb-1">
													<strong><?= $this->lang->line('lb_password') ?></strong>
												</label>
												<input type="password" class="form-control" name="a[password]">
												<div class="sys_msg" id="gm_pass_msg"></div>
											</div>
											<div class="form-group col-md-6">
												<label class="mb-1">
													<strong><?= $this->lang->line('lb_confirm') ?></strong>
												</label>
												<input type="password" class="form-control" name="a[confirm]">
												<div class="sys_msg" id="gm_confirm_msg"></div>
											</div>
										</div>
										<button type="submit" class="btn btn-primary btn-block"><?= $this->lang->line('btn_gm') ?></button>
										<div class="sys_msg" id="gm_result_msg"></div>
                                    </form>
									<?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<div class="modal fade" id="forgot_pass" tabindex="-1" role="dialog" aria-labelledby="forgot_passLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form action="index.html" id="form_forgot_pass">
					<div class="modal-header border-0 pb-0">
						<h5 class="modal-title" id="forgot_passLabel"><?= $this->lang->line('title_reset_password') ?></h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="mb-3"><?= $this->lang->line('msg_fp_instruction') ?></div>
						<div class="form-group">
							<label><strong><?= $this->lang->line('lb_username') ?></strong></label>
							<input type="email" class="form-control" placeholder="example@example.com" name="email">
							<div class="sys_msg" id="fg_email_msg"></div>
						</div>
					</div>
					<div class="modal-footer border-0 pt-0">
						<button type="button" class="btn tp-btn btn-secondary" data-dismiss="modal"><?= $this->lang->line('btn_cancel') ?></button>
						<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_confirm') ?></button>
					</div>
				</form>
			</div>
		  </div>
	</div>
	<div class="d-none">
		<input type="hidden" id="base_url" value="<?= base_url() ?>">
		<input type="hidden" id="alert_success_title" value="<?= $this->lang->line('alert_success_title') ?>">
		<input type="hidden" id="alert_error_title" value="<?= $this->lang->line('alert_error_title') ?>">
		<input type="hidden" id="alert_warning_title" value="<?= $this->lang->line('alert_warning_title') ?>">
		<input type="hidden" id="alert_confirm_btn" value="<?= $this->lang->line('alert_confirm_btn') ?>">
		<input type="hidden" id="alert_cancel_btn" value="<?= $this->lang->line('alert_cancel_btn') ?>">
	</div>
    <script src="<?= base_url() ?>resources/vendor/global/global.min.js"></script>
	<script src="<?= base_url() ?>resources/vendor_/sweetalert2-11.4.35/dist/sweetalert2.min.js"></script>
    <script src="<?= base_url() ?>resources/js/custom.min.js"></script>
	<script src="<?= base_url() ?>resources/js/deznav-init.js"></script>
	<script src="<?= base_url() ?>resources/js/init/general.js"></script>
	<script src="<?= base_url() ?>resources/js/init/auth/index.js"></script>
</body>
</html>