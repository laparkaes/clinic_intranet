<div class="pagetitle">
	<h1><?= $title ?></h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= $this->lang->line('w_home') ?></a></li>
			<li class="breadcrumb-item active"><?= $title ?></li>
		</ol>
	</nav>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="card">
			<div class="card-body">
				<h5 class="card-title">Reporte de Venta</h5>
				<div class="row g-3">
					<div class="col-md-6">
						<label class="form-label">Reporte Rapido</label>
						<div class="d-flex flex-row">
							<form class="me-3" action="<?= base_url() ?>sys/report/sales_report" method="POST" target="_blank">
								<input type="text" class="form-control gr_from d-none" name="f" value="<?= date("Y-m-d") ?>" readonly>
								<input type="text" class="form-control gr_to d-none" name="t" value="<?= date("Y-m-d") ?>" readonly>
								<button type="submit" class="btn btn-primary">
									Hoy
								</button>
							</form>
							<form action="<?= base_url() ?>sys/report/sales_report" method="POST" target="_blank">
								<input type="text" class="form-control gr_from d-none" name="f" value="<?= date("Y-m-01") ?>" readonly>
								<input type="text" class="form-control gr_to d-none" name="t" value="<?= date("Y-m-t") ?>" readonly>
								<button type="submit" class="btn btn-primary">
									Mes
								</button>
							</form>
						</div>
					</div>
					<form class="col-md-6" action="<?= base_url() ?>sys/report/sales_report" method="POST" target="_blank">
						<label class="form-label">Ingresar rango de fechas:</label>
						<div class="input-group mb-3">
							<input type="text" class="form-control gr_from" id="" name="f" value="<?= date("Y-m-01") ?>">
							<span class="input-group-text">~</span>
							<input type="text" class="form-control gr_to" id="" name="t" value="<?= date("Y-m-d") ?>">
							<button type="submit" class="btn btn-primary">
								Generar
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
	set_date_picker(".gr_from", null);
	set_date_picker(".gr_to", null);
	
	$("#form_generate_report").submit(function(e) {
		e.preventDefault();
		ajax_form(this, "sys/report/generate_report").done(function(res) {
			set_msg(res.msgs);
			if (res.type == "success") location.href = res.move_to;
			else swal(res.type, res.msg);
		});
	});
});
</script>