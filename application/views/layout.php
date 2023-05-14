<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= $title ?></title>
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url() ?>resources/images/favicon.png">
	<link rel="stylesheet" href="<?= base_url() ?>resources/vendor/fullcalendar/css/main.min.css">
	<link rel="stylesheet" href="<?= base_url() ?>resources/vendor/datatables/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>resources/vendor/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css">
	<link rel="stylesheet" href="<?= base_url() ?>resources/vendor/bootstrap-select/dist/css/bootstrap-select.min.css">
	<link rel="stylesheet" href="<?= base_url() ?>resources/vendor_/sweetalert2-11.4.35/dist/sweetalert2.min.css">
	<link rel="stylesheet" href="<?= base_url() ?>resources/vendor_/jquery-ui-1.13.2/jquery-ui.min.css">
	<link rel="stylesheet" href="<?= base_url() ?>resources/vendor_/fontawesome5pro/css/all.min.css">
	<link rel="stylesheet" href="<?= base_url() ?>resources/vendor_/jquery-smartwizard-6.0.6/smart_wizard_all.customized.min.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="<?= base_url() ?>resources/css/style.css">
	<link rel="stylesheet" href="<?= base_url() ?>resources/css/setting.css">
</head>
<body>
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    <div id="main-wrapper">
        <div class="nav-header">
            <a href="<?= base_url() ?>dashboard" class="brand-logo d-flex justify-content-center p-0">
                <img class="logo-abbr" src="<?= base_url() ?>resources/images/logo.png">
				<img class="brand-title" src="<?= base_url() ?>resources/images/logo_text.svg">
            </a>
            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>
        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <div class="header-left">
                            <div class="dashboard_bar">
                                <?= $title ?>
                            </div>
                        </div>
                        <ul class="navbar-nav header-right">
							<li class="nav-item dropdown notification_dropdown">
                                <a class="nav-link text-primary border-primary schedule_block" href="#">
                                    <i class="fas fa-laptop-medical"></i>
                                </a>
							</li>
                            <li class="nav-item dropdown header-profile">
                                <a class="nav-link" href="#" role="button" data-toggle="dropdown">
                                    <img src="<?= base_url() ?>resources/images/user.png" width="20" alt=""/>
									<div class="header-info">
										<span><?= $this->session->userdata('name') ?></span>
										<small><?= $this->lang->line($this->session->userdata('role')->name) ?></small>
									</div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="<?= base_url() ?>auth/logout" class="dropdown-item ai-icon">
										<i class="fa fa-sign-out fa-lg text-danger" style="width:18px;"></i>
                                        <span class="ml-2"><?= $this->lang->line('hd_logout') ?></span>
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
		<div class="deznav">
            <div class="deznav-scroll">
				<ul class="metismenu pt-0" id="menu">
					<?php if (!strcmp("dashboard", $this->nav_menu)) $ac = "mm-active"; else $ac = ""; ?>
                    <li class="<?= $ac ?>">
						<a class="ai-icon" href="<?= base_url() ?>dashboard" aria-expanded="false">
							<i class="fas fa-home fa-fw mr-2"></i>
							<span class="nav-text"><?= $this->lang->line('nav_dashboard') ?></span>
						</a>
                    </li>
					<?php if (!strcmp("doctor", $this->nav_menu)) $ac = "mm-active"; else $ac = ""; ?>
                    <li class="<?= $ac ?>">
						<a class="ai-icon" href="<?= base_url() ?>doctor" aria-expanded="false">
							<i class="fas fa-user-md fa-fw mr-2"></i>
							<span class="nav-text"><?= $this->lang->line('nav_doctors') ?></span>
						</a>
					</li>
					<?php if (!strcmp("patient", $this->nav_menu)) $ac = "mm-active"; else $ac = ""; ?>
					<li class="<?= $ac ?>">
						<a class="ai-icon" href="<?= base_url() ?>patient" aria-expanded="false">
							<i class="fas fa-user-injured fa-fw mr-2"></i>
							<span class="nav-text"><?= $this->lang->line('nav_patients') ?></span>
						</a>
					</li>
					<?php if (!strcmp("appointment", $this->nav_menu)) $ac = "mm-active"; else $ac = ""; ?>
					<li class="<?= $ac ?>">
						<a class="ai-icon" href="<?= base_url() ?>appointment" aria-expanded="false">
							<i class="fas fa-notes-medical fa-fw mr-2"></i>
							<span class="nav-text"><?= $this->lang->line('nav_appointments') ?></span>
						</a>
					</li>
					<?php if (!strcmp("surgery", $this->nav_menu)) $ac = "mm-active"; else $ac = ""; ?>
					<li class="<?= $ac ?>">
						<a class="ai-icon" href="<?= base_url() ?>surgery" aria-expanded="false">
							<i class="fas fa-file-medical-alt fa-fw mr-2"></i>
							<span class="nav-text"><?= $this->lang->line('nav_surgeries') ?></span>
						</a>
					</li>
					<?php if (!strcmp("product", $this->nav_menu)) $ac = "mm-active"; else $ac = ""; ?>
					<li class="<?= $ac ?>">
						<a class="ai-icon" href="<?= base_url() ?>product" aria-expanded="false">
							<i class="fas fa-box fa-fw mr-2"></i>
							<span class="nav-text"><?= $this->lang->line('nav_products') ?></span>
						</a>
					</li>
					<?php if (!strcmp("sale", $this->nav_menu)) $ac = "mm-active"; else $ac = ""; ?>
					<li class="<?= $ac ?>">
						<a class="ai-icon" href="<?= base_url() ?>sale" aria-expanded="false">
							<i class="fas fa-shopping-basket fa-fw mr-2"></i>
							<span class="nav-text"><?= $this->lang->line('nav_sales') ?></span>
						</a>
					</li>
					<?php if (!strcmp("report", $this->nav_menu)) $ac = "mm-active"; else $ac = ""; ?>
					<li class="<?= $ac ?>">
						<a class="ai-icon" href="<?= base_url() ?>report" aria-expanded="false">
							<i class="fas fa-book fa-fw mr-2"></i>
							<span class="nav-text"><?= $this->lang->line('nav_reports') ?></span>
						</a>
					</li>
					<?php if (!strcmp("config", $this->nav_menu)) $ac = "mm-active"; else $ac = ""; ?>
					<li class="<?= $ac ?>">
						<a class="ai-icon" href="<?= base_url() ?>config" aria-expanded="false">
							<i class="fas fa-cogs fa-fw mr-2"></i>
							<span class="nav-text"><?= $this->lang->line('nav_settings') ?></span>
						</a>
					</li>
                </ul>
				<div class="copyright mt-5">
					<p><strong><?= $this->lang->line('cr_owner') ?></strong></p>
					<div><?= $this->lang->line('cr_text') ?></div>
				</div>
            </div>
        </div>
        <div class="content-body">
			<div class="container-fluid">
				<div class="row">
					<?php $this->load->view($main); ?>
				</div>
            </div>
        </div>
        <div class="footer">
            <div class="copyright">
                <p><?= $this->lang->line('cr_text')." ".$this->lang->line('cr_by') ?> <strong><?= $this->lang->line('cr_owner') ?></strong></p>
            </div>
        </div>
	</div>
	<label class="btn-side-menu nav-control">
		<i class="far fa-bars text-white"></i>
	</label>
	<div class="schedule_box chatbox">
		<div class="schedule_box-close chatbox-close"></div>
		<div class="custom-tab-1">
			<ul class="nav nav-tabs d-flex justify-content-start">
				<li class="nav-item">
					<a class="nav-link active" data-toggle="tab" href="#sch_appointment"><?= $this->lang->line('txt_appointments') ?></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" data-toggle="tab" href="#sch_surgery"><?= $this->lang->line('txt_surgeries') ?></a>
				</li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane fade active show" id="sch_appointment">
					<div class="card mb-sm-3 mb-md-0 contacts_card">
						<div class="card-header chat-list-header text-center">
							<h6 class="mb-0"><?= $this->lang->line('txt_appointments_list') ?></h6>
						</div>
						<div class="card-body contacts_body p-0 dz-scroll" id="DZ_W_Contacts_Body1">
							<ul class="contacts" id="sch_list_appointment"></ul>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="sch_surgery">
					<div class="card mb-sm-3 mb-md-0 contacts_card">
						<div class="card-header chat-list-header text-center">
							<h6 class="mb-0"><?= $this->lang->line('txt_surgeries_list') ?></h6>
						</div>
						<div class="card-body contacts_body p-0 dz-scroll" id="DZ_W_Contacts_Body1">
							<ul class="contacts" id="sch_list_surgery"></ul>
						</div>
					</div>
				</div>
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
		<input type="hidden" id="j_datatable_search" value="<?= $this->lang->line('j_datatable_search') ?>">
		<input type="hidden" id="j_datatable_no_record" value="<?= $this->lang->line('j_datatable_no_record') ?>">
		<input type="hidden" id="bd_select" value="<?= $this->lang->line('bd_select') ?>">
		<input type="hidden" id="bd_cancel" value="<?= $this->lang->line('bd_cancel') ?>">
		<input type="hidden" id="bd_clean" value="<?= $this->lang->line('bd_clean') ?>">
	</div>
    <script src="<?= base_url() ?>resources/vendor/global/global.min.js"></script>
	<script src="<?= base_url() ?>resources/vendor/apexchart/apexcharts.min.js"></script>
	<script src="<?= base_url() ?>resources/vendor/moment/moment.min.js"></script>
	<script src="<?= base_url() ?>resources/vendor/fullcalendar/js/main.min.js"></script>
	<script src="<?= base_url() ?>resources/vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="<?= base_url() ?>resources/vendor/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
	<script src="<?= base_url() ?>resources/vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
    <script src="<?= base_url() ?>resources/vendor_/sweetalert2-11.4.35/dist/sweetalert2.min.js"></script>
	<script src="<?= base_url() ?>resources/vendor_/jquery-ui-1.13.2/jquery-ui.min.js"></script>
	<script src="<?= base_url() ?>resources/vendor_/jquery-smartwizard-6.0.6/jquery.smartWizard.min.js"></script>
	<?php if ($init_js){ ?><script src="<?= base_url() ?>resources/js/init/<?= $init_js ?>"></script><?php } ?>
    <script src="<?= base_url() ?>resources/js/custom.min.js"></script>
	<script src="<?= base_url() ?>resources/js/deznav-init.js"></script>
	<script src="<?= base_url() ?>resources/js/init/general.js"></script>
</body>
</html>