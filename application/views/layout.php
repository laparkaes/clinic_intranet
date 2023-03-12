<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= $title ?></title>
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url() ?>resorces/images/favicon.png">
	<link href="<?= base_url() ?>resorces/vendor/fullcalendar/css/main.min.css" rel="stylesheet">
	<link href="<?= base_url() ?>resorces/vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="<?= base_url() ?>resorces/vendor/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet">
	<link href="<?= base_url() ?>resorces/vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
	<link href="<?= base_url() ?>resorces/vendor_/sweetalert2-11.4.35/dist/sweetalert2.min.css" rel="stylesheet">
	<link href="<?= base_url() ?>resorces/vendor_/jquery-ui-1.13.2/jquery-ui.min.css" rel="stylesheet">
	<link href="<?= base_url() ?>resorces/vendor_/fontawesome5pro/css/all.min.css" rel="stylesheet">
	<link href="<?= base_url() ?>resorces/vendor_/jquery-smartwizard-6.0.6/smart_wizard_all.customized.min.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url() ?>resorces/css/style.css">
	<link rel="stylesheet" href="<?= base_url() ?>resorces/css/setting.css">
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
                <img class="logo-abbr" src="<?= base_url() ?>resorces/images/logo.png">
				<img class="brand-title" src="<?= base_url() ?>resorces/images/logo_text.svg">
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
                                    <img src="<?= base_url() ?>resorces/images/user.png" width="20" alt=""/>
									<div class="header-info">
										<span><?= $this->session->userdata('name') ?></span>
										<small><?= $this->lang->line($this->session->userdata('role')->name) ?></small>
									</div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="<?= base_url() ?>account" class="dropdown-item ai-icon">
										<i class="fa fa-user fa-lg text-success" style="width:18px;"></i>
                                        <span class="ml-2"><?= $this->lang->line('hd_profile') ?></span>
                                    </a>
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
				<hr>
				<div class="px-3" id="bl_schedule">
					<h5><?= $this->lang->line('nav_today') ?></h5>
					<div id="lt_schedule"></div>
				</div>
				<hr>
				<ul class="metismenu pt-0" id="menu">
					<li>
						<a class="ai-icon" href="<?= base_url() ?>dashboard" aria-expanded="false">
							<i class="fas fa-home fa-fw mr-2"></i>
							<span class="nav-text"><?= $this->lang->line('nav_dashboard') ?></span>
						</a>
                    </li>
                    <li>
						<a class="ai-icon" href="<?= base_url() ?>doctor" aria-expanded="false">
							<i class="fas fa-user-md fa-fw mr-2"></i>
							<span class="nav-text"><?= $this->lang->line('nav_doctors') ?></span>
						</a>
					</li>
					<li>
						<a class="ai-icon" href="<?= base_url() ?>patient" aria-expanded="false">
							<i class="fas fa-users-medical fa-fw mr-2"></i>
							<span class="nav-text"><?= $this->lang->line('nav_patients') ?></span>
						</a>
					</li>
					<li>
						<a class="ai-icon" href="<?= base_url() ?>appointment" aria-expanded="false">
							<i class="fas fa-notes-medical fa-fw mr-2"></i>
							<span class="nav-text"><?= $this->lang->line('nav_appointments') ?></span>
						</a>
					</li>
					<li>
						<a class="ai-icon" href="<?= base_url() ?>product" aria-expanded="false">
							<i class="fas fa-box fa-fw mr-2"></i>
							<span class="nav-text"><?= $this->lang->line('nav_products') ?></span>
						</a>
					</li>
					<li>
						<a class="ai-icon" href="<?= base_url() ?>sale" aria-expanded="false">
							<i class="fas fa-shopping-basket fa-fw mr-2"></i>
							<span class="nav-text"><?= $this->lang->line('nav_sales') ?></span>
						</a>
					</li>
					<li>
						<a class="ai-icon" href="<?= base_url() ?>report" aria-expanded="false">
							<i class="fas fa-book fa-fw mr-2"></i>
							<span class="nav-text"><?= $this->lang->line('nav_reports') ?></span>
						</a>
					</li>
					<li>
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
			<ul class="nav nav-tabs">
				<li class="nav-item">
					<a class="nav-link active" data-toggle="tab" href="#notes">Notes</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" data-toggle="tab" href="#alerts">Alerts</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" data-toggle="tab" href="#chat">Chat</a>
				</li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane fade active show" id="notes">
					<div class="card mb-sm-3 mb-md-0 note_card">
						<div class="card-header chat-list-header text-center">
							<a href="#"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect fill="#000000" x="4" y="11" width="16" height="2" rx="1"/><rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) rotate(-270.000000) translate(-12.000000, -12.000000) " x="4" y="11" width="16" height="2" rx="1"/></g></svg></a>
							<div>
								<h6 class="mb-1">Notes</h6>
								<p class="mb-0">Add New Nots</p>
							</div>
							<a href="#"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><path d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/><path d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z" fill="#000000" fill-rule="nonzero"/></g></svg></a>
						</div>
						<div class="card-body contacts_body p-0 dz-scroll" id="DZ_W_Contacts_Body2">
							<ul class="contacts">
								<li class="active">
									<div class="d-flex bd-highlight">
										<div class="user_info">
											<span>New order placed..</span>
											<p>10 Aug 2020</p>
										</div>
										<div class="ml-auto">
											<a href="#" class="btn btn-primary btn-xs sharp mr-1"><i class="fa fa-pencil"></i></a>
											<a href="#" class="btn btn-danger btn-xs sharp"><i class="fa fa-trash"></i></a>
										</div>
									</div>
								</li>
								<li>
									<div class="d-flex bd-highlight">
										<div class="user_info">
											<span>Youtube, a video-sharing website..</span>
											<p>10 Aug 2020</p>
										</div>
										<div class="ml-auto">
											<a href="#" class="btn btn-primary btn-xs sharp mr-1"><i class="fa fa-pencil"></i></a>
											<a href="#" class="btn btn-danger btn-xs sharp"><i class="fa fa-trash"></i></a>
										</div>
									</div>
								</li>
								<li>
									<div class="d-flex bd-highlight">
										<div class="user_info">
											<span>john just buy your product..</span>
											<p>10 Aug 2020</p>
										</div>
										<div class="ml-auto">
											<a href="#" class="btn btn-primary btn-xs sharp mr-1"><i class="fa fa-pencil"></i></a>
											<a href="#" class="btn btn-danger btn-xs sharp"><i class="fa fa-trash"></i></a>
										</div>
									</div>
								</li>
								<li>
									<div class="d-flex bd-highlight">
										<div class="user_info">
											<span>Athan Jacoby</span>
											<p>10 Aug 2020</p>
										</div>
										<div class="ml-auto">
											<a href="#" class="btn btn-primary btn-xs sharp mr-1"><i class="fa fa-pencil"></i></a>
											<a href="#" class="btn btn-danger btn-xs sharp"><i class="fa fa-trash"></i></a>
										</div>
									</div>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="alerts" role="tabpanel">
					<div class="card mb-sm-3 mb-md-0 contacts_card">
						<div class="card-header chat-list-header text-center">
							<a href="#"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="5" cy="12" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="19" cy="12" r="2"/></g></svg></a>
							<div>
								<h6 class="mb-1">Notications</h6>
								<p class="mb-0">Show All</p>
							</div>
							<a href="#"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><path d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/><path d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z" fill="#000000" fill-rule="nonzero"/></g></svg></a>
						</div>
						<div class="card-body contacts_body p-0 dz-scroll" id="DZ_W_Contacts_Body1">
							<ul class="contacts">
								<li class="name-first-letter">SEVER STATUS</li>
								<li class="active">
									<div class="d-flex bd-highlight">
										<div class="img_cont primary">KK</div>
										<div class="user_info">
											<span>David Nester Birthday</span>
											<p class="text-primary">Today</p>
										</div>
									</div>
								</li>
								<li class="name-first-letter">SOCIAL</li>
								<li>
									<div class="d-flex bd-highlight">
										<div class="img_cont success">RU</div>
										<div class="user_info">
											<span>Perfection Simplified</span>
											<p>Jame Smith commented on your status</p>
										</div>
									</div>
								</li>
								<li class="name-first-letter">SEVER STATUS</li>
								<li>
									<div class="d-flex bd-highlight">
										<div class="img_cont primary">AU</div>
										<div class="user_info">
											<span>AharlieKane</span>
											<p>Sami is online</p>
										</div>
									</div>
								</li>
								<li>
									<div class="d-flex bd-highlight">
										<div class="img_cont info">MO</div>
										<div class="user_info">
											<span>Athan Jacoby</span>
											<p>Nargis left 30 mins ago</p>
										</div>
									</div>
								</li>
							</ul>
						</div>
						<div class="card-footer"></div>
					</div>
				</div>
				<div class="tab-pane fade" id="chat" role="tabpanel">
					<div class="card mb-sm-3 mb-md-0 contacts_card dz-chat-user-box">
						<div class="card-header chat-list-header text-center">
							<a href="#"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect fill="#000000" x="4" y="11" width="16" height="2" rx="1"/><rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) rotate(-270.000000) translate(-12.000000, -12.000000) " x="4" y="11" width="16" height="2" rx="1"/></g></svg></a>
							<div>
								<h6 class="mb-1">Chat List</h6>
								<p class="mb-0">Show All</p>
							</div>
							<a href="#"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="5" cy="12" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="19" cy="12" r="2"/></g></svg></a>
						</div>
						<div class="card-body contacts_body p-0 dz-scroll  " id="DZ_W_Contacts_Body">
							<ul class="contacts">
								<li class="name-first-letter">A</li>
								<li class="active dz-chat-user">
									<div class="d-flex bd-highlight">
										<div class="img_cont">
											<img src="images/avatar/1.jpg" class="rounded-circle user_img" alt=""/>
											<span class="online_icon"></span>
										</div>
										<div class="user_info">
											<span>Archie Parker</span>
											<p>Kalid is online</p>
										</div>
									</div>
								</li>
								<li class="dz-chat-user">
									<div class="d-flex bd-highlight">
										<div class="img_cont">
											<img src="images/avatar/2.jpg" class="rounded-circle user_img" alt=""/>
											<span class="online_icon offline"></span>
										</div>
										<div class="user_info">
											<span>Alfie Mason</span>
											<p>Taherah left 7 mins ago</p>
										</div>
									</div>
								</li>
								<li class="dz-chat-user">
									<div class="d-flex bd-highlight">
										<div class="img_cont">
											<img src="images/avatar/3.jpg" class="rounded-circle user_img" alt=""/>
											<span class="online_icon"></span>
										</div>
										<div class="user_info">
											<span>AharlieKane</span>
											<p>Sami is online</p>
										</div>
									</div>
								</li>
								<li class="dz-chat-user">
									<div class="d-flex bd-highlight">
										<div class="img_cont">
											<img src="images/avatar/4.jpg" class="rounded-circle user_img" alt=""/>
											<span class="online_icon offline"></span>
										</div>
										<div class="user_info">
											<span>Athan Jacoby</span>
											<p>Nargis left 30 mins ago</p>
										</div>
									</div>
								</li>
								<li class="name-first-letter">B</li>
								<li class="dz-chat-user">
									<div class="d-flex bd-highlight">
										<div class="img_cont">
											<img src="images/avatar/5.jpg" class="rounded-circle user_img" alt=""/>
											<span class="online_icon offline"></span>
										</div>
										<div class="user_info">
											<span>Bashid Samim</span>
											<p>Rashid left 50 mins ago</p>
										</div>
									</div>
								</li>
								<li class="dz-chat-user">
									<div class="d-flex bd-highlight">
										<div class="img_cont">
											<img src="images/avatar/1.jpg" class="rounded-circle user_img" alt=""/>
											<span class="online_icon"></span>
										</div>
										<div class="user_info">
											<span>Breddie Ronan</span>
											<p>Kalid is online</p>
										</div>
									</div>
								</li>
								<li class="dz-chat-user">
									<div class="d-flex bd-highlight">
										<div class="img_cont">
											<img src="images/avatar/2.jpg" class="rounded-circle user_img" alt=""/>
											<span class="online_icon offline"></span>
										</div>
										<div class="user_info">
											<span>Ceorge Carson</span>
											<p>Taherah left 7 mins ago</p>
										</div>
									</div>
								</li>
								<li class="name-first-letter">D</li>
								<li class="dz-chat-user">
									<div class="d-flex bd-highlight">
										<div class="img_cont">
											<img src="images/avatar/3.jpg" class="rounded-circle user_img" alt=""/>
											<span class="online_icon"></span>
										</div>
										<div class="user_info">
											<span>Darry Parker</span>
											<p>Sami is online</p>
										</div>
									</div>
								</li>
								<li class="dz-chat-user">
									<div class="d-flex bd-highlight">
										<div class="img_cont">
											<img src="images/avatar/4.jpg" class="rounded-circle user_img" alt=""/>
											<span class="online_icon offline"></span>
										</div>
										<div class="user_info">
											<span>Denry Hunter</span>
											<p>Nargis left 30 mins ago</p>
										</div>
									</div>
								</li>
								<li class="name-first-letter">J</li>
								<li class="dz-chat-user">
									<div class="d-flex bd-highlight">
										<div class="img_cont">
											<img src="images/avatar/5.jpg" class="rounded-circle user_img" alt=""/>
											<span class="online_icon offline"></span>
										</div>
										<div class="user_info">
											<span>Jack Ronan</span>
											<p>Rashid left 50 mins ago</p>
										</div>
									</div>
								</li>
								<li class="dz-chat-user">
									<div class="d-flex bd-highlight">
										<div class="img_cont">
											<img src="images/avatar/1.jpg" class="rounded-circle user_img" alt=""/>
											<span class="online_icon"></span>
										</div>
										<div class="user_info">
											<span>Jacob Tucker</span>
											<p>Kalid is online</p>
										</div>
									</div>
								</li>
								<li class="dz-chat-user">
									<div class="d-flex bd-highlight">
										<div class="img_cont">
											<img src="images/avatar/2.jpg" class="rounded-circle user_img" alt=""/>
											<span class="online_icon offline"></span>
										</div>
										<div class="user_info">
											<span>James Logan</span>
											<p>Taherah left 7 mins ago</p>
										</div>
									</div>
								</li>
								<li class="dz-chat-user">
									<div class="d-flex bd-highlight">
										<div class="img_cont">
											<img src="images/avatar/3.jpg" class="rounded-circle user_img" alt=""/>
											<span class="online_icon"></span>
										</div>
										<div class="user_info">
											<span>Joshua Weston</span>
											<p>Sami is online</p>
										</div>
									</div>
								</li>
								<li class="name-first-letter">O</li>
								<li class="dz-chat-user">
									<div class="d-flex bd-highlight">
										<div class="img_cont">
											<img src="images/avatar/4.jpg" class="rounded-circle user_img" alt=""/>
											<span class="online_icon offline"></span>
										</div>
										<div class="user_info">
											<span>Oliver Acker</span>
											<p>Nargis left 30 mins ago</p>
										</div>
									</div>
								</li>
								<li class="dz-chat-user">
									<div class="d-flex bd-highlight">
										<div class="img_cont">
											<img src="images/avatar/5.jpg" class="rounded-circle user_img" alt=""/>
											<span class="online_icon offline"></span>
										</div>
										<div class="user_info">
											<span>Oscar Weston</span>
											<p>Rashid left 50 mins ago</p>
										</div>
									</div>
								</li>
							</ul>
						</div>
					</div>
					<div class="card chat dz-chat-history-box d-none">
						<div class="card-header chat-list-header text-center">
							<a href="#" class="dz-chat-history-back">
								<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><polygon points="0 0 24 0 24 24 0 24"/><rect fill="#000000" opacity="0.3" transform="translate(15.000000, 12.000000) scale(-1, 1) rotate(-90.000000) translate(-15.000000, -12.000000) " x="14" y="7" width="2" height="10" rx="1"/><path d="M3.7071045,15.7071045 C3.3165802,16.0976288 2.68341522,16.0976288 2.29289093,15.7071045 C1.90236664,15.3165802 1.90236664,14.6834152 2.29289093,14.2928909 L8.29289093,8.29289093 C8.67146987,7.914312 9.28105631,7.90106637 9.67572234,8.26284357 L15.6757223,13.7628436 C16.0828413,14.136036 16.1103443,14.7686034 15.7371519,15.1757223 C15.3639594,15.5828413 14.7313921,15.6103443 14.3242731,15.2371519 L9.03007346,10.3841355 L3.7071045,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(9.000001, 11.999997) scale(-1, -1) rotate(90.000000) translate(-9.000001, -11.999997) "/></g></svg>
							</a>
							<div>
								<h6 class="mb-1">Chat with Khelesh</h6>
								<p class="mb-0 text-success">Online</p>
							</div>							
							<div class="dropdown">
								<a href="#" data-toggle="dropdown" aria-expanded="false"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="5" cy="12" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="19" cy="12" r="2"/></g></svg></a>
								<ul class="dropdown-menu dropdown-menu-right">
									<li class="dropdown-item"><i class="fa fa-user-circle text-primary mr-2"></i> View profile</li>
									<li class="dropdown-item"><i class="fa fa-users text-primary mr-2"></i> Add to close friends</li>
									<li class="dropdown-item"><i class="fa fa-plus text-primary mr-2"></i> Add to group</li>
									<li class="dropdown-item"><i class="fa fa-ban text-primary mr-2"></i> Block</li>
								</ul>
							</div>
						</div>
						<div class="card-body msg_card_body dz-scroll" id="DZ_W_Contacts_Body3">
							<div class="d-flex justify-content-start mb-4">
								<div class="img_cont_msg">
									<img src="images/avatar/1.jpg" class="rounded-circle user_img_msg" alt=""/>
								</div>
								<div class="msg_cotainer">
									Hi, how are you samim?
									<span class="msg_time">8:40 AM, Today</span>
								</div>
							</div>
							<div class="d-flex justify-content-end mb-4">
								<div class="msg_cotainer_send">
									Hi Khalid i am good tnx how about you?
									<span class="msg_time_send">8:55 AM, Today</span>
								</div>
								<div class="img_cont_msg">
							<img src="images/avatar/2.jpg" class="rounded-circle user_img_msg" alt=""/>
								</div>
							</div>
							<div class="d-flex justify-content-start mb-4">
								<div class="img_cont_msg">
									<img src="images/avatar/1.jpg" class="rounded-circle user_img_msg" alt=""/>
								</div>
								<div class="msg_cotainer">
									I am good too, thank you for your chat template
									<span class="msg_time">9:00 AM, Today</span>
								</div>
							</div>
							<div class="d-flex justify-content-end mb-4">
								<div class="msg_cotainer_send">
									You are welcome
									<span class="msg_time_send">9:05 AM, Today</span>
								</div>
								<div class="img_cont_msg">
							<img src="images/avatar/2.jpg" class="rounded-circle user_img_msg" alt=""/>
								</div>
							</div>
							<div class="d-flex justify-content-start mb-4">
								<div class="img_cont_msg">
									<img src="images/avatar/1.jpg" class="rounded-circle user_img_msg" alt=""/>
								</div>
								<div class="msg_cotainer">
									I am looking for your next templates
									<span class="msg_time">9:07 AM, Today</span>
								</div>
							</div>
							<div class="d-flex justify-content-end mb-4">
								<div class="msg_cotainer_send">
									Ok, thank you have a good day
									<span class="msg_time_send">9:10 AM, Today</span>
								</div>
								<div class="img_cont_msg">
									<img src="images/avatar/2.jpg" class="rounded-circle user_img_msg" alt=""/>
								</div>
							</div>
							<div class="d-flex justify-content-start mb-4">
								<div class="img_cont_msg">
									<img src="images/avatar/1.jpg" class="rounded-circle user_img_msg" alt=""/>
								</div>
								<div class="msg_cotainer">
									Bye, see you
									<span class="msg_time">9:12 AM, Today</span>
								</div>
							</div>
							<div class="d-flex justify-content-start mb-4">
								<div class="img_cont_msg">
									<img src="images/avatar/1.jpg" class="rounded-circle user_img_msg" alt=""/>
								</div>
								<div class="msg_cotainer">
									Hi, how are you samim?
									<span class="msg_time">8:40 AM, Today</span>
								</div>
							</div>
							<div class="d-flex justify-content-end mb-4">
								<div class="msg_cotainer_send">
									Hi Khalid i am good tnx how about you?
									<span class="msg_time_send">8:55 AM, Today</span>
								</div>
								<div class="img_cont_msg">
							<img src="images/avatar/2.jpg" class="rounded-circle user_img_msg" alt=""/>
								</div>
							</div>
							<div class="d-flex justify-content-start mb-4">
								<div class="img_cont_msg">
									<img src="images/avatar/1.jpg" class="rounded-circle user_img_msg" alt=""/>
								</div>
								<div class="msg_cotainer">
									I am good too, thank you for your chat template
									<span class="msg_time">9:00 AM, Today</span>
								</div>
							</div>
							<div class="d-flex justify-content-end mb-4">
								<div class="msg_cotainer_send">
									You are welcome
									<span class="msg_time_send">9:05 AM, Today</span>
								</div>
								<div class="img_cont_msg">
							<img src="images/avatar/2.jpg" class="rounded-circle user_img_msg" alt=""/>
								</div>
							</div>
							<div class="d-flex justify-content-start mb-4">
								<div class="img_cont_msg">
									<img src="images/avatar/1.jpg" class="rounded-circle user_img_msg" alt=""/>
								</div>
								<div class="msg_cotainer">
									I am looking for your next templates
									<span class="msg_time">9:07 AM, Today</span>
								</div>
							</div>
							<div class="d-flex justify-content-end mb-4">
								<div class="msg_cotainer_send">
									Ok, thank you have a good day
									<span class="msg_time_send">9:10 AM, Today</span>
								</div>
								<div class="img_cont_msg">
									<img src="images/avatar/2.jpg" class="rounded-circle user_img_msg" alt=""/>
								</div>
							</div>
							<div class="d-flex justify-content-start mb-4">
								<div class="img_cont_msg">
									<img src="images/avatar/1.jpg" class="rounded-circle user_img_msg" alt=""/>
								</div>
								<div class="msg_cotainer">
									Bye, see you
									<span class="msg_time">9:12 AM, Today</span>
								</div>
							</div>
						</div>
						<div class="card-footer type_msg">
							<div class="input-group">
								<textarea class="form-control" placeholder="Type your message..."></textarea>
								<div class="input-group-append">
									<button type="button" class="btn btn-primary"><i class="fa fa-location-arrow"></i></button>
								</div>
							</div>
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
	</div>
    <script src="<?= base_url() ?>resorces/vendor/global/global.min.js"></script>
	<script src="<?= base_url() ?>resorces/vendor/apexchart/apexcharts.min.js"></script>
	<script src="<?= base_url() ?>resorces/vendor/moment/moment.min.js"></script>
	<script src="<?= base_url() ?>resorces/vendor/fullcalendar/js/main.min.js"></script>
	<script src="<?= base_url() ?>resorces/vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="<?= base_url() ?>resorces/vendor/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
	<script src="<?= base_url() ?>resorces/vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
    <script src="<?= base_url() ?>resorces/vendor_/sweetalert2-11.4.35/dist/sweetalert2.min.js"></script>
	<script src="<?= base_url() ?>resorces/vendor_/jquery-ui-1.13.2/jquery-ui.min.js"></script>
	<script src="<?= base_url() ?>resorces/vendor_/jquery-smartwizard-6.0.6/jquery.smartWizard.min.js"></script>
	<?php if ($init_js){ ?><script src="<?= base_url() ?>resorces/js/init/<?= $init_js ?>"></script><?php } ?>
    <script src="<?= base_url() ?>resorces/js/custom.min.js"></script>
	<script src="<?= base_url() ?>resorces/js/deznav-init.js"></script>
	<script src="<?= base_url() ?>resorces/js/init/general.js"></script>
</body>
</html>