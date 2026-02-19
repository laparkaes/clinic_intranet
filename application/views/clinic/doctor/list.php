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
							<h5 class="modal-title">Agregar Médico</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<div class="row g-3">							
								<div class="form-group col-md-12">
									<label class="form-label">Especialidad</label>
									<select class="form-select" name="doctor[specialty_id]">
										<option value="" selected>--</option>
										<?php foreach($specialties as $item){ ?>
										<option value="<?= $item->id ?>"><?= $item->name ?></option>
										<?php } ?>
									</select>
									<div class="sys_msg" id="dn_specialty_msg"></div>
								</div>
								<div class="form-group col-md-12">
									<label class="form-label">Licencia</label>
									<input type="text" class="form-control" name="doctor[license]">
									<div class="sys_msg" id="dn_license_msg"></div>
								</div>
								<div class="form-group col-md-8">
									<label class="form-label">Documento</label>
									<select class="form-select" id="dn_doc_type_id" name="personal[doc_type_id]">
										<?php foreach($doc_types as $d){ if ($d->sunat_code){ ?>
										<option value="<?= $d->id ?>"><?= $d->description ?></option>
										<?php }} ?>
									</select>
									<div class="sys_msg" id="dn_doc_type_msg"></div>
								</div>
								<div class="form-group col-md-4">
									<label class="form-label d-md-block d-none">&nbsp;</label>
									<input type="text" class="form-control" id="dn_doc_number" name="personal[doc_number]" placeholder="Número">
									<div class="sys_msg" id="dn_doc_number_msg"></div>
								</div>
								<div class="form-group col-md-8">
									<label class="form-label">Nombre</label>
									<input type="text" class="form-control" id="dn_name" name="personal[name]">
									<div class="sys_msg" id="dn_name_msg"></div>
								</div>
								<div class="form-group col-md-4">
									<label class="form-label">Teléfono</label>
									<input type="text" class="form-control" id="dn_tel" name="personal[tel]">
									<div class="sys_msg" id="dn_tel_msg"></div>
								</div>
								<div class="form-group col-md-12">
									<label class="form-label">Dirección</label>
									<input type="text" class="form-control" name="personal[address]">
									<div class="sys_msg" id="dn_address_msg"></div>
								</div>
								<div class="form-group col-md-12">
									<label class="form-label">Correo Electrónico</label>
									<input type="email" class="form-control" name="personal[email]" placeholder="email@example.com">
									<div class="sys_msg" id="dn_email_msg"></div>
								</div>
								<div class="form-group col-md-12">
									<label class="form-label">Fecha de Nacimiento</label>
									<input type="hidden" id="p_birthday" name="personal[birthday]" readonly="">
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
									<div class="sys_msg" id="dn_birthday_msg"></div>
								</div>
								<div class="form-group col-md-6">
									<label class="form-label">Sexo</label>
									<select class="form-select" name="personal[sex_id]">
										<option value="" selected="">--</option>
										<?php foreach($sex_ops as $item){ ?>
										<option value="<?= $item->id ?>"><?= $item->description ?></option>
										<?php } ?>
									</select>
									<div class="sys_msg" id="dn_sex_msg"></div>
								</div>
								<div class="form-group col-md-6">
									<label class="form-label">Grupo Sanguíneo</label>
									<select class="form-select" name="personal[blood_type_id]">
										<option value="" selected="">--</option>
										<?php foreach($blood_type_ops as $item){ ?>
										<option value="<?= $item->id ?>"><?= $item->description ?></option>
										<?php } ?>
									</select>
									<div class="sys_msg" id="dn_blood_type_msg"></div>
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
						<input type="hidden" value="1" name="page">
						<div class="modal-header">
							<h5 class="modal-title">Buscar Médico</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<div class="row g-3">
								<div class="form-group col-md-12">
									<label class="form-label">Especialidad</label>
									<select class="form-select" name="specialty">
										<option value="">Todos</option>
										<?php foreach($specialties as $item){ if ($item->doctor_qty){
											if ($item->id == $f_url["specialty"]) $s = "selected"; else $s = ""; ?>
										<option value="<?= $item->id ?>" <?= $s ?>><?= $item->name ?></option>
										<?php }} ?>
									</select>
								</div>
								<div class="form-group col-md-6">
									<label class="form-label">Documento</label>
									<select class="form-select" name="doc_type">
										<option value="">Todos</option>
										<?php foreach($doc_types as $d){ if ($d->sunat_code){ ?>
										<option value="<?= $d->id ?>" <?= $f_url["doc_type"] == $d->id ? "selected" : "" ?>><?= $d->description ?></option>
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
				<?php if ($doctors){ ?>
				<div class="table-responsive">
					<table class="table align-middle">
						<thead>
							<tr>
								<th><strong>Estado</strong></th>
								<th><strong>Especialidad</strong></th>
								<th><strong>Licencia</strong></th>
								<th><strong>Documento</strong></th>
								<th><strong>Nombre</strong></th>
								<th><strong>Teléfono</strong></th>
								<th><strong>Email</strong></th>
								<th><strong>Edad</strong></th>
								<th><strong>Sexo</strong></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($doctors as $i => $item){ ?>
							<tr>
								<td><span class="text-<?= $status[$item->status_id]->color ?>"><?= $status[$item->status_id]->text ?></span></td>
								<td><?= $specialties_arr[$item->specialty_id] ?></td>
								<td><?= $item->license ?></td>
								<td><?= $item->person->doc_type." ".$item->person->doc_number ?></td>
								<td><?= $item->person->name ?></td>
								<td><?= $item->person->tel ?></td>
								<td><?= $item->person->email ?></td>
								<td><?= $item->person->age ?></td>
								<td><?= $item->person->sex ?></td>
								<td class="text-end">
									<a href="<?= base_url() ?>clinic/doctor/detail/<?= $item->id ?>" class="btn btn-primary btn-sm">
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
						<a href="<?= base_url() ?>clinic/doctor?<?= http_build_query($f_url) ?>" class="btn btn-<?= $p[2] ?>">
							<?= $p[1] ?>
						</a>
						<?php } ?>
					</div>
				</div>
				<?php }else{ ?>
				<h5 class="text-danger mt-3"><?= $this->lang->line('t_no_doctors') ?></h5>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<script>
document.addEventListener("DOMContentLoaded", () => {
	function reset_person(){
		$("#dn_name").val("");
		$("#dn_tel").val("");
	}
	
	//register
	$("#form_register").submit(function(e) {
		e.preventDefault();
		//birthday merge
		let d = $("#p_birthday_d").val();
		let m = $("#p_birthday_m").val();
		let y = $("#p_birthday_y").val();
		if (d != "" && m != "" && y != "") $("#p_birthday").val(y + "-" + m + "-" + d); else $("#p_birthday").val("");
		
		$("#register_form .sys_msg").html("");
		ajax_form_warning(this, "clinic/doctor/register", "¿Desea agregar nuevo paciente?").done(function(res) {
			set_msg(res.msgs);
			swal_redirection(res.type, res.msg, res.move_to);
		});
	});
	
	$("#btn_search_person_dn").click(function() {
		var data = {doc_type_id: $("#dn_doc_type_id").val(), doc_number: $("#dn_doc_number").val()};
		ajax_simple(data, "ajax_f/search_person").done(function(res) {
			swal(res.type, res.msg);
			if (res.type == "success"){
				$("#dn_name").val(res.person.name);
				$("#dn_tel").val(res.person.tel);
			}else reset_person();
		});
	});
	
	$("#dn_doc_type_id").change(function() {
		reset_person();
	});
	
	$("#dn_doc_number").keyup(function() {
		reset_person();
	});
});
</script>