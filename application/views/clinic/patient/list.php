<div class="row">
	<div class="col">
		<div class="mb-3">
			<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add">
				<i class="bi bi-plus-lg me-1"></i> Agregar
			</button>
			<div class="modal fade" id="modal_add" tabindex="-1">
				<div class="modal-dialog">
					<form class="modal-content" id="form_register">
						<div class="modal-header">
							<h5 class="modal-title">Agregar Paciente</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<div class="row g-3">
								<div class="form-group col-md-6">
									<label class="form-label">Documento</label>
									<select class="form-select" id="pn_doc_type_id" name="doc_type_id">
										<?php foreach($doc_types as $d){ if ($d->sunat_code){ ?>
										<option value="<?= $d->id ?>"><?= $d->description ?></option>
										<?php }} ?>
									</select>
									<div class="sys_msg" id="pn_doc_type_msg"></div>
								</div>
								<div class="form-group col-md-6">
									<label class="form-label d-md-block d-none">&nbsp;</label>
									<input type="text" class="form-control" id="pn_doc_number" name="doc_number" placeholder="Número">
									<div class="sys_msg" id="pn_doc_number_msg"></div>
								</div>
								<div class="form-group col-md-8">
									<label class="form-label">Nombre</label>
									<input type="text" class="form-control" id="pn_name" name="name">
									<div class="sys_msg" id="pn_name_msg"></div>
								</div>
								<div class="form-group col-md-4">
									<label class="form-label">Teléfono</label>
									<input type="text" class="form-control" id="pn_tel" name="tel">
									<div class="sys_msg" id="pn_tel_msg"></div>
								</div>
								<div class="form-group col-md-12">
									<label class="form-label">Dirección</label>
									<input type="text" class="form-control" name="address">
									<div class="sys_msg" id="pn_address_msg"></div>
								</div>
								<div class="form-group col-md-12">
									<label class="form-label">Correo Electrónico</label>
									<input type="email" class="form-control" name="email" placeholder="email@example.com">
									<div class="sys_msg" id="pn_email_msg"></div>
								</div>
								<div class="form-group col-md-12">
									<label class="form-label">Fecha de Nacimiento</label>
									<input type="hidden" id="p_birthday" name="birthday" readonly="">
									<div class="input-group">
										<select class="form-select" id="p_birthday_d">
											<option value="" selected="">Día</option>
											<?php for($i = 1; $i <= 31; $i++){ ?>
											<option value="<?= $i ?>"><?= $i ?></option>
											<?php } ?>
										</select>
										<select class="form-select" id="p_birthday_m">
											<option value="" selected="">Mes</option>
											<?php for($i = 1; $i <= 12; $i++){ ?>
											<option value="<?= $i ?>"><?= $i ?></option>
											<?php } ?>
										</select>
										<?php $now = date('Y'); ?>
										<select class="form-select" id="p_birthday_y">
											<option value="" selected="">Año</option>
											<?php for($i = 0; $i <= 130; $i++){ ?>
											<option value="<?= $now - $i ?>"><?= $now - $i ?></option>
											<?php } ?>
										</select>
									</div>
									<div class="sys_msg" id="pn_birthday_msg"></div>
								</div>
								<div class="form-group col-md-6">
									<label class="form-label">Sexo</label>
									<select class="form-select" name="sex_id">
										<option value="" selected="">--</option>
										<?php foreach($sex_ops as $item){ ?>
										<option value="<?= $item->id ?>"><?= $item->description ?></option>
										<?php } ?>
									</select>
									<div class="sys_msg" id="pn_sex_msg"></div>
								</div>
								<div class="form-group col-md-6">
									<label class="form-label">Grupo Sanguíneo</label>
									<select class="form-select" name="blood_type_id">
										<option value="" selected="">--</option>
										<?php foreach($blood_type_ops as $item){ ?>
										<option value="<?= $item->id ?>"><?= $item->description ?></option>
										<?php } ?>
									</select>
									<div class="sys_msg" id="pn_blood_type_msg"></div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
							<button type="reset" class="btn btn-secondary">Limpiar</button>
							<button type="submit" class="btn btn-primary">Agregar</button>
						</div>
					</form>
				</div>
			</div>

			<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_search">
				<i class="bi bi-search me-1"></i> Buscar
			</button>
			<div class="modal fade" id="modal_search" tabindex="-1">
				<div class="modal-dialog">
					<form class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Buscar Paciente</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<div class="row g-3">
								<div class="form-group col-md-6">
									<label class="form-label">Documento</label>
									<select class="form-select" name="doc_type_id">
										<option value="">Todos</option>
										<?php foreach($doc_types as $d){ if ($d->sunat_code){ ?>
										<option value="<?= $d->id ?>" <?= $f_url["doc_type_id"] == $d->id ? "selected" : "" ?>><?= $d->description ?></option>
										<?php }} ?>
									</select>
								</div>
								<div class="form-group col-md-6">
									<label class="form-label d-md-block d-none">&nbsp;</label>
									<input type="text" class="form-control" name="doc_number" placeholder="Número" value="<?= $f_url["doc_number"] ?>">
								</div>
								<div class="form-group col-md-8">
									<label class="form-label">Nombre</label>
									<input type="text" class="form-control" name="name" value="<?= $f_url["name"] ?>">
								</div>
								<div class="form-group col-md-4">
									<label class="form-label">Teléfono</label>
									<input type="text" class="form-control" name="tel" value="<?= $f_url["tel"] ?>">
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
							<button type="reset" class="btn btn-secondary">Limpiar</button>
							<button type="submit" class="btn btn-primary">Buscar</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col">
		<div class="card">
			<div class="card-body pt-3">
				<?php if ($patients){ ?>
				<div class="table-responsive">
					<table class="table table-responsive-md">
						<thead>
							<tr>
								<th><strong>#</strong></th>
								<th><strong>Documento</strong></th>
								<th><strong>Nombre</strong></th>
								<th><strong>Teléfono</strong></th>
								<th><strong>Email</strong></th>
								<th><strong>Edad</strong></th>
								<th><strong>Sexo</strong></th>
								<th><strong>G.Sangre</strong></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($patients as $i => $item){ ?>
							<tr>
								<td><strong><?= number_format(($f_url["page"] - 1) * 25 + 1 + $i) ?></strong></td>
								<td><?= $item->doc_type." ".$item->doc_number ?></td>
								<td><?= $item->name ?></td>
								<td><?= $item->tel ?></td>
								<td><?= $item->email ?></td>
								<td><?= $item->birthday ?></td>
								<td><?= $item->sex ?></td>
								<td><?= $item->blood_type ?></td>
								<td class="text-end">
									<a href="<?= base_url() ?>clinic/patient/detail/<?= $item->id ?>" class="btn btn-primary btn-sm">
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
						<a href="<?= base_url() ?>clinic/patient?<?= http_build_query($f_url) ?>" class="btn btn-<?= $p[2] ?>">
							<?= $p[1] ?>
						</a>
						<?php } ?>
					</div>
				</div>
				<?php }else{ ?>
				<span class="text-danger mt-3">No existe pacientes registrados o cumplan con datos de búsqueda.</span>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<script>
document.addEventListener("DOMContentLoaded", () => {
	
	//general
	$(".control_bl").click(function() {
		control_bl(this);
	});
	
	//register
	$("#form_register").submit(function(e) {
		e.preventDefault();
		
		//birthday merge
		let d = $("#p_birthday_d").val();
		let m = $("#p_birthday_m").val();
		let y = $("#p_birthday_y").val();
		if (d != "" && m != "" && y != "") $("#p_birthday").val(y + "-" + m + "-" + d); else $("#p_birthday").val("");
		
		$("#form_register .sys_msg").html("");
		ajax_form_warning(this, "clinic/patient/register", "¿Desea agregar nuevo paciente?").done(function(res) {
			set_msg(res.msgs);
			swal_redirection(res.type, res.msg, res.move_to);
		});
	});
});
</script>