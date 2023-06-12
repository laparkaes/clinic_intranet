<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= $this->lang->line('lg_title') ?></title>
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
												<strong><?= $this->lang->line('lb_password_actual') ?></strong>
											</label>
                                            <input type="password" class="form-control" name="password">
											<div class="sys_msg" id="lg_pass_msg"></div>
                                        </div>
                                        <div class="text-center pt-3">
                                            <button type="submit" class="btn btn-primary btn-block"><?= $this->lang->line('btn_login') ?></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
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
    <script src="<?= base_url() ?>resources/vendor/global/global.min.js"></script>
	<script src="<?= base_url() ?>resources/vendor_/sweetalert2-11.4.35/dist/sweetalert2.min.js"></script>
    <script src="<?= base_url() ?>resources/js/custom.min.js"></script>
	<script src="<?= base_url() ?>resources/js/deznav-init.js"></script>
	<script src="<?= base_url() ?>resources/js/init/general.js"></script>
	<script src="<?= base_url() ?>resources/js/init/auth/index.js"></script>
</body>
</html>