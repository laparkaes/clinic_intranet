<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">
	<title>JW Everlyn</title>
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
	<header id="header" class="header fixed-top d-flex align-items-center">
		<div class="d-flex align-items-center justify-content-between">
			<a href="<?= base_url() ?>dashboard" class="logo d-flex align-items-center">
				<img src="<?= base_url() ?>assets/img/logo.png" alt="">
				<span class="d-none d-lg-block">JW Everlyn</span>
			</a>
			<i class="bi bi-list toggle-sidebar-btn"></i>
		</div>
		<div class="search-bar">
			<h2 class="mb-0"><strong><?= $title ?></strong></h2>
		</div>
		<nav class="header-nav ms-auto">
			<ul class="d-flex align-items-center">
				<li class="nav-link nav-profile">
					<small>
					<?php setlocale(LC_TIME, 'spanish.utf8'); 
					echo ucfirst(strftime("%A, %d de %B de %Y", time())) ?>
					</small>
				</li>
				<li class="nav-item dropdown px-3">
					<a class="nav-link nav-profile nav-icon d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
						<i class="bi bi-person-circle"></i>
						<span class="d-none d-md-block dropdown-toggle ps-2"><?= $this->session->userdata('name') ?></span>
					</a>
					<ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
						<li>
							<a class="dropdown-item d-flex align-items-center" href="<?= base_url() ?>auth/change_password">
								<i class="bi bi-person-circle"></i>
								<span>Mis Datos</span>
							</a>
						</li>
						<li><hr class="dropdown-divider"></li>
						<li>
							<a class="dropdown-item d-flex align-items-center" href="<?= base_url() ?>login/logout">
								<i class="bi bi-box-arrow-right"></i>
								<span>Salir</span>
							</a>
						</li>
					</ul>
				</li>
			</ul>
		</nav>
	</header>
	<aside id="sidebar" class="sidebar">
		<ul class="sidebar-nav" id="sidebar-nav">
			<li class="nav-item">
				<a class="nav-link <?= ($this->nav_menu === "home") ? "" : "collapsed" ?>" href="<?= base_url() ?>home">
					<i class="bi bi-grid"></i>
					<span>Inicio</span>
				</a>
			</li>
			
			<!-- Attention Menus -->
			<li class="nav-heading pt-2">Atención</li>
			<li class="nav-item">
				<a class="nav-link <?= ($this->nav_menu === "appointment") ? "" : "collapsed" ?>" href="<?= base_url() ?>attention/appointment">
					<i class="bi bi-capsule"></i>
					<span>Citas</span>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link <?= ($this->nav_menu === "surgery") ? "" : "collapsed" ?>" href="<?= base_url() ?>attention/surgery">
					<i class="bi bi-heart-pulse"></i>
					<span>Cirugías</span>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link <?= ($this->nav_menu === "patient") ? "" : "collapsed" ?>" href="<?= base_url() ?>attention/patient">
					<i class="bi bi-person-vcard"></i>
					<span>Pacientes</span>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link <?= ($this->nav_menu === "doctor") ? "" : "collapsed" ?>" href="<?= base_url() ?>attention/doctor">
					<i class="bi bi-person-plus"></i>
					<span>Médicos</span>
				</a>
			</li>
			
			<!-- Commerce Menus -->
			<li class="nav-heading pt-2">Comercio</li>
			<li class="nav-item">
				<a class="nav-link <?= ($this->nav_menu === "sale") ? "" : "collapsed" ?>" href="<?= base_url() ?>commerce/sale">
					<i class="bi bi-shop"></i>
					<span>Ventas</span>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link <?= ($this->nav_menu === "purchase") ? "" : "collapsed" ?>" href="<?= base_url() ?>commerce/purchase">
					<i class="bi bi-cart"></i>
					<span>Compras</span>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link <?= ($this->nav_menu === "product") ? "" : "collapsed" ?>" href="<?= base_url() ?>commerce/product">
					<i class="bi bi-box"></i>
					<span>Productos</span>
				</a>
			</li>
			
			<!-- System Menus -->
			<li class="nav-heading pt-2">Sistema</li>
			<li class="nav-item">
				<a class="nav-link <?= ($this->nav_menu === "account") ? "" : "collapsed" ?>" href="<?= base_url() ?>sys/account">
					<i class="bi bi-person-circle"></i>
					<span>Usuarios</span>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link <?= ($this->nav_menu === "report") ? "" : "collapsed" ?>" href="<?= base_url() ?>sys/report">
					<i class="bi bi-file-earmark-bar-graph"></i>
					<span>Reportes</span>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link <?= ($this->nav_menu === "config") ? "" : "collapsed" ?>" href="<?= base_url() ?>sys/config">
					<i class="bi bi-gear"></i>
					<span>Ajustes</span>
				</a>
			</li>
			
			
			
			<li class="nav-item pt-5">
				<a class="nav-link <?= ($this->nav_menu[0] === "moneyflow") ? "" : "collapsed" ?>" data-bs-target="#moneyflow-nav" data-bs-toggle="collapse" href="#" aria-expanded="<?= ($this->nav_menu[0] === "moneyflow") ? "true" : "false" ?>">
					<i class="bi bi-coin"></i><span>Flujo de Caja</span><i class="bi bi-chevron-down ms-auto"></i>
				</a>
				<ul id="moneyflow-nav" class="nav-content collapse <?= ($this->nav_menu[0] === "moneyflow") ? "show" : "" ?>" data-bs-parent="#sidebar-nav">
					<li>
						<a href="<?= base_url() ?>moneyflow/resume" class="<?= ($this->nav_menu[1] === "resume") ? "active" : "" ?>">
							<i class="bi bi-circle"></i><span>Resumen</span>
						</a>
					</li>
					<li>
						<a href="<?= base_url() ?>moneyflow/inoutcome" class="<?= ($this->nav_menu[1] === "inoutcome") ? "active" : "" ?>">
							<i class="bi bi-circle"></i><span>Ingreso/Egreso</span>
						</a>
					</li>
				</ul>
			</li>
			
		</ul>
	</aside>
	<main id="main" class="main">
		<?php $this->load->view($main); ?>
	</main>
	<footer id="footer" class="footer">
		<div class="copyright">
		&copy; Copyright <strong><span>JW Everlyn S.A.C.</span></strong>. Todos los derechos reservados
		</div>
	</footer>
	<input type="hidden" id="base_url" value="<?= base_url() ?>">
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