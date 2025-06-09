<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">
	<title>Everlyn - Reestablecer contraseña</title>
	<meta content="" name="description">
	<meta content="" name="keywords">
	<link href="<?= base_url() ?>assets/img/favicon.png" rel="icon">
	<link href="<?= base_url() ?>assets/img/apple-touch-icon.png" rel="apple-touch-icon">
	<link href="https://fonts.gstatic.com" rel="preconnect">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
	<link href="<?= base_url() ?>assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?= base_url() ?>assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
	<link href="<?= base_url() ?>assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
	<link href="<?= base_url() ?>assets/vendor/remixicon/remixicon.css" rel="stylesheet">
	<link href="<?= base_url() ?>assets/vendor/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
	<link href="<?= base_url() ?>assets/css/style.css" rel="stylesheet">
</head>
<body>
	<main>
		<div class="container">
			<section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
				<div class="container">
					<div class="row justify-content-center">
						<div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
							<div class="d-flex justify-content-center py-4">
								<a href="<?= base_url() ?>" class="logo d-flex align-items-center w-auto">
									<img src="<?= base_url() ?>assets/img/logo.png" alt="">
									<span class="d-none d-lg-block">Everlyn Sys</span>
								</a>
							</div>
							<div class="card mb-3">
								<div class="card-body">
									<div class="pt-4 pb-2">
										<h5 class="card-title text-center pb-0 fs-4">Reestablecer Contraseña</h5>
										<p class="text-center small">Ingrese los datos.</p>
									</div>
									<form class="row g-3" id="form_reset_password">
										<div class="col-12">
											<label class="form-label">Usuario</label>
											<input type="text" class="form-control" name="account">
											<div class="sys_msg" id="pw_account_msg"></div>
										</div>
										<div class="col-12">
											<label class="form-label">Contraseña nueva</label>
											<input type="password" class="form-control" name="password">
											<div class="sys_msg" id="pw_password_msg"></div>
										</div>
										<div class="col-12">
											<label class="form-label">Confirmación</label>
											<input type="password" class="form-control" name="confirm">
											<div class="sys_msg" id="pw_confirm_msg"></div>
										</div>
										<div class="col-12">
											<label class="form-label">Key</label>
											<input type="password" class="form-control" name="key">
											<div class="sys_msg" id="pw_key_msg"></div>
										</div>
										<div class="col-12 py-3">
											<button class="btn btn-primary" type="submit">Reestablecer</button>
											<a type="button" class="btn btn-outline-secondary border-white" href="<?= base_url() ?>auth">Cancelar</a>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	</main>
	<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
	<input type="hidden" id="base_url" value="<?= base_url() ?>">
	<script>
	document.addEventListener("DOMContentLoaded", () => {
		$("#form_reset_password").submit(function(e) {
			e.preventDefault();
			$("#form_reset_password .sys_msg").html("");
			ajax_form(this, "auth/reset_password_process").done(function(res) {
				set_msg(res.msgs);
				if (res.type == "success") swal_redirection(res.type, res.msg, $("#base_url").val() + "auth");
			});
		});
	});
	</script>
	<script src="<?= base_url() ?>assets/vendor/jquery-3.7.0.min.js"></script>
	<script src="<?= base_url() ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="<?= base_url() ?>assets/vendor/apexcharts/apexcharts.min.js"></script>
	<script src="<?= base_url() ?>assets/vendor/sweetalert2/dist/sweetalert2.all.min.js"></script>
	<script src="<?= base_url() ?>assets/js/main.js"></script>
	<script src="<?= base_url() ?>assets/js/lang.js"></script>
	<script src="<?= base_url() ?>assets/js/func.js"></script>
</body>
</html>