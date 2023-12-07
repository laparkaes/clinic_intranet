<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= $this->lang->line('w_change_password') ?></title>
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
									<h4 class="text-center mb-3"><?= $this->lang->line('w_change_password') ?></h4>
                                    <form action="#" id="form_change_password">
										<div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label class="mb-1">
													<strong><?= $this->lang->line('w_username') ?></strong>
												</label>
                                                <div><?= $account->email ?></div>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label class="mb-1">
													<strong><?= $this->lang->line('w_password_actual') ?></strong>
												</label>
                                                <input type="password" class="form-control" name="password_actual">
												<div class="sys_msg" id="pw_actual_msg"></div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="mb-1">
													<strong><?= $this->lang->line('w_password_new') ?></strong>
												</label>
                                                <input type="password" class="form-control" name="password_new">
												<div class="sys_msg" id="pw_new_msg"></div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="mb-1">
													<strong><?= $this->lang->line('w_confirm') ?></strong>
												</label>
                                                <input type="password" class="form-control" name="confirm">
												<div class="sys_msg" id="pw_confirm_msg"></div>
                                            </div>
											
                                            <div class="form-group col-md-12 pt-3">
                                                <button type="submit" class="btn btn-primary btn-block">
													<?= $this->lang->line('btn_confirm') ?>
												</button>
                                            </div>
                                            <div class="form-group col-md-12 pt-3 text-right">
                                                <a href="<?= base_url() ?>dashboard">
													<?= $this->lang->line('w_change_later') ?>
												</a>
                                            </div>
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
	<script src="<?= base_url() ?>resources/js/init/auth/change_password.js"></script>
</body>
</html>