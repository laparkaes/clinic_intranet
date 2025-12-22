<div class="d-flex justify-content-between align-items-start">
	<div class="pagetitle">
		<h1>Consultas</h1>
		<nav>
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?= base_url() ?>">Inicio</a></li>
				<li class="breadcrumb-item">Consultas</li>
				<li class="breadcrumb-item active">Lista</li>
			</ol>
		</nav>
	</div>
	<div class="btn-group mb-3">
		<a class="btn btn-primary" href="<?= base_url() ?>clinic/appointment">
			Lista
		</a>
		<button type="button" class="btn btn-outline-primary control_bl" value="bl_add">
			Búsqueda
		</button>
		<a class="btn btn-outline-primary" href="<?= base_url() ?>clinic/appointment/add">
			Agregar Consulta
		</a>
	</div>
</div>
<div class="row">
	<div class="col">
		<div class="card bl_content" id="bl_list">
			<div class="card-body">
				<div class="d-flex justify-content-between">
					<h5 class="card-title">Lista de Consultas</h5>
					<form class="row d-flex justify-content-end align-items-center">
						<input type="hidden" value="1" name="page">
						<div class="input-group mb-3">
							<select class="form-select" id="sl_status" name="status" style="width: 150px;">
								<option value="">Estado</option>
								<?php foreach($status as $item){ if ($item->id == $f_url["status"]) $s = "selected"; else $s = ""; ?>
								<option value="<?= $item->id ?>" <?= $s ?>><?= $this->lang->line($item->code) ?></option>
								<?php } ?>
							</select>
							<input type="text" class="form-control" name="keyword" placeholder="Paciente" value="<?= $f_url["keyword"] ?>" style="width: 250px;">
							<input type="text" class="form-control" name="diagnosis" placeholder="Diagnóstico (min 3 letras)" value="<?= $f_url["diagnosis"] ?>" style="width: 250px;">
							<button type="submit" class="btn btn-primary btn-block">
								<i class="bi bi-search"></i>
							</button>
						</div>
					</form>
				</div>
				
				<?php if ($appointments){ ?>
				<div class="table-responsive">
					<table class="table align-middle">
						<thead>
							<tr>
								<th>Estado</th>
								<th>Fecha</th>
								<th>Hora</th>
								<th>Especialidad</th>
								<th>Médico</th>
								<th>Paciente</th>
								<th class="text-end">Ver</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($appointments as $i => $item){ ?>
							<tr>
								<td><span class="text-<?= $status_arr[$item->status_id]->color ?>"><?= $this->lang->line($status_arr[$item->status_id]->code) ?></span></td>
								<td><?= date("Y-m-d", strtotime($item->schedule_from)); ?></td>
								<td><?= date("h:i A", strtotime($item->schedule_from)); ?></td>
								<td><?= $item->specialty ?></td>
								<td><?= $item->doctor ?></td>
								<td><?= $item->patient ?></td>
								<td class="text-end">
									<a href="<?= base_url() ?>clinic/appointment/detail/<?= $item->id ?>" class="btn btn-success">
										<i class="bi bi-arrow-right"></i>
									</a>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
					<div class="btn-group" role="group" aria-label="paging">
						<?php foreach($paging as $p){
						$f_url["page"] = $p[0]; ?>
						<a href="<?= base_url() ?>clinic/appointment?<?= http_build_query($f_url) ?>" class="btn btn-<?= $p[2] ?>">
							<?= $p[1] ?>
						</a>
						<?php } ?>
					</div>
				</div>
				<?php }else{ ?>
				<h5 class="text-danger mt-3"><?= $this->lang->line('t_no_appointments') ?></h5>
				<?php } ?>
			</div>
		</div>
		<div class="card bl_content d-none" id="bl_add">
			<?php $this->load->view("clinic/appointment/form_add_appointment", ["patient" => null, "doctor" => null]); ?>
		</div>
	</div>
</div>
<script>
document.addEventListener("DOMContentLoaded", () => {
	var params = get_params();
	if (params.a == "add") $("#btn_add").trigger("click");
	$(".control_bl").click(function() {
		control_bl(this);
	});
});
</script>