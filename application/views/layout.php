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
				<span class="d-none d-lg-block">Everlyn Sys</span>
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
						<li class="dropdown-header">
							<h6><?= $this->lang->line($this->session->userdata('role')->name) ?></h6>
						</li>
						<li><hr class="dropdown-divider"></li>
						<li>
							<a class="dropdown-item d-flex align-items-center" href="<?= base_url() ?>auth/change_password">
								<i class="bi bi-gear"></i>
								<span>Contraseña</span>
							</a>
						</li>
						<li><hr class="dropdown-divider"></li>
						<li>
							<a class="dropdown-item d-flex align-items-center" href="<?= base_url() ?>auth/logout">
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
			<?php if (in_array("dashboard", $this->nav_menus)){ ?>
			<li class="nav-item">
				<a class="nav-link <?= ($this->nav_menu[0] === "dashboard") ? "" : "collapsed" ?>" href="<?= base_url() ?>dashboard">
					<i class="bi bi-grid"></i>
					<span>Principal</span>
				</a>
			</li>
			<?php } if (in_array("clinic", $this->nav_menus)){ ?>
			<li class="nav-item">
				<a class="nav-link <?= ($this->nav_menu[0] === "clinic") ? "" : "collapsed" ?>" data-bs-target="#clinic-nav" data-bs-toggle="collapse" href="#" aria-expanded="<?= ($this->nav_menu[0] === "clinic") ? "true" : "false" ?>">
					<i class="bi bi-clipboard-plus"></i><span>Clínica</span><i class="bi bi-chevron-down ms-auto"></i>
				</a>
				<ul id="clinic-nav" class="nav-content collapse <?= ($this->nav_menu[0] === "clinic") ? "show" : "" ?>" data-bs-parent="#sidebar-nav">
					<?php if (in_array("appointment", $this->nav_menus)){ ?>
					<li>
						<a href="<?= base_url() ?>clinic/appointment" class="<?= ($this->nav_menu[1] === "appointment") ? "active" : "" ?>">
							<i class="bi bi-circle"></i><span>Consultas</span>
						</a>
					</li>
					<?php } if (in_array("surgery", $this->nav_menus)){ ?>
					<li>
						<a href="<?= base_url() ?>clinic/surgery" class="<?= ($this->nav_menu[1] === "surgery") ? "active" : "" ?>">
							<i class="bi bi-circle"></i><span>Cirugías</span>
						</a>
					</li>
					<?php } if (in_array("patient", $this->nav_menus)){ ?>
					<li>
						<a href="<?= base_url() ?>clinic/patient" class="<?= ($this->nav_menu[1] === "patient") ? "active" : "" ?>">
							<i class="bi bi-circle"></i><span>Pacientes</span>
						</a>
					</li>
					<?php } if (in_array("doctor", $this->nav_menus)){ ?>
					<li>
						<a href="<?= base_url() ?>clinic/doctor" class="<?= ($this->nav_menu[1] === "doctor") ? "active" : "" ?>">
							<i class="bi bi-circle"></i><span>Médicos</span>
						</a>
					</li>
					<?php } ?>
				</ul>
			</li>
			<?php } if (in_array("commerce", $this->nav_menus)){ ?>
			<li class="nav-item">
				<a class="nav-link <?= ($this->nav_menu[0] === "commerce") ? "" : "collapsed" ?>" data-bs-target="#commerce-nav" data-bs-toggle="collapse" href="#" aria-expanded="<?= ($this->nav_menu[0] === "commerce") ? "true" : "false" ?>">
					<i class="bi bi-cart"></i><span>Comercio</span><i class="bi bi-chevron-down ms-auto"></i>
				</a>
				<ul id="commerce-nav" class="nav-content collapse <?= ($this->nav_menu[0] === "commerce") ? "show" : "" ?>" data-bs-parent="#sidebar-nav">
					<?php if (in_array("sale", $this->nav_menus)){ ?>
					<li>
						<a href="<?= base_url() ?>commerce/sale" class="<?= ($this->nav_menu[1] === "sale") ? "active" : "" ?>">
							<i class="bi bi-circle"></i><span>Ventas</span>
						</a>
					</li>
					<?php } if (in_array("purchase", $this->nav_menus)){ ?>
					<li>
						<a href="<?= base_url() ?>commerce/purchase" class="<?= ($this->nav_menu[1] === "purchase") ? "active" : "" ?>">
							<i class="bi bi-circle"></i><span>Compras</span>
						</a>
					</li>
					<?php } if (in_array("product", $this->nav_menus)){ ?>
					<li>
						<a href="<?= base_url() ?>commerce/product" class="<?= ($this->nav_menu[1] === "product") ? "active" : "" ?>">
							<i class="bi bi-circle"></i><span>Productos</span>
						</a>
					</li>
					<?php } ?>
				</ul>
			</li>
			<?php } if (in_array("moneyflow", $this->nav_menus)){ ?>
			<li class="nav-item">
				<a class="nav-link <?= ($this->nav_menu[0] === "moneyflow") ? "" : "collapsed" ?>" data-bs-target="#moneyflow-nav" data-bs-toggle="collapse" href="#" aria-expanded="<?= ($this->nav_menu[0] === "moneyflow") ? "true" : "false" ?>">
					<i class="bi bi-coin"></i><span>Flujo de Caja</span><i class="bi bi-chevron-down ms-auto"></i>
				</a>
				<ul id="moneyflow-nav" class="nav-content collapse <?= ($this->nav_menu[0] === "moneyflow") ? "show" : "" ?>" data-bs-parent="#sidebar-nav">
					<li>
						<a href="<?= base_url() ?>resume" class="<?= ($this->nav_menu[1] === "resume") ? "active" : "" ?>">
							<i class="bi bi-circle"></i><span>Resumen</span>
						</a>
					</li>
					<li>
						<a href="<?= base_url() ?>inoutcome" class="<?= ($this->nav_menu[1] === "inoutcome") ? "active" : "" ?>">
							<i class="bi bi-circle"></i><span>Ingreso/Egreso</span>
						</a>
					</li>
				</ul>
			</li>
			<?php } if (in_array("system", $this->nav_menus)){ ?>
			<li class="nav-item">
				<a class="nav-link <?= ($this->nav_menu[0] === "system") ? "" : "collapsed" ?>" data-bs-target="#system-nav" data-bs-toggle="collapse" href="#" aria-expanded="<?= ($this->nav_menu[0] === "system") ? "true" : "false" ?>">
					<i class="bi bi-pc-display"></i><span>Sistema</span><i class="bi bi-chevron-down ms-auto"></i>
				</a>
				<ul id="system-nav" class="nav-content collapse <?= ($this->nav_menu[0] === "system") ? "show" : "" ?>" data-bs-parent="#sidebar-nav">
					<li>
						<a href="<?= base_url() ?>sys/account" class="<?= ($this->nav_menu[1] === "account") ? "active" : "" ?>">
							<i class="bi bi-circle"></i><span>Usuarios</span>
						</a>
					</li>
					<li>
						<a href="<?= base_url() ?>sys/report" class="<?= ($this->nav_menu[1] === "report") ? "active" : "" ?>">
							<i class="bi bi-circle"></i><span>Reportes</span>
						</a>
					</li>
					<li>
						<a href="<?= base_url() ?>sys/config" class="<?= ($this->nav_menu[1] === "config") ? "active" : "" ?>">
							<i class="bi bi-circle"></i><span>Ajustes</span>
						</a>
					</li>
				</ul>
			</li>
			<?php } ?>
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
	<?php if ($init_js){ ?>
	<script src="<?= base_url() ?>assets/js/init/<?= $init_js ?>"></script>
	<?php } ?>
</body>
</html>