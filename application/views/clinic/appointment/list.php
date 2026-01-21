<div class="row">
	<div class="col">
		<div class="mb-3">
			<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add">
				<i class="bi bi-plus-lg me-1"></i> Agregar
			</button>
			<div class="modal fade" id="modal_add" tabindex="-1">
				<div class="modal-dialog modal-xl">
					<form class="modal-content" id="form_register">
						<div class="modal-header">
							<h5 class="modal-title">Agregar Consulta</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							
							<div class="row">
								<div class="col-md-6">
									<?php 
									//print_r($doctors);
									?>
									<div class="row g-3">
										<div class="col-md-12">
											<strong>Atención</strong>
										</div>
										<div class="col-md-12">
											<label class="form-label">Especialidad</label>
											<select class="form-select" id="aa_specialty" name="app[specialty_id]">
												<option value="">--</option>
												<?php foreach($specialties as $item){ if ($item->doctor_qty){ ?>
												<option value="<?= $item->id ?>"><?= $item->name ?></option>
												<?php }} ?>
											</select>
											<div class="sys_msg" id="aa_specialty_msg"></div>
										</div>
										<div class="col-md-12">
											<label class="form-label">Médico</label>
											<select class="form-select" id="aa_doctor" name="app[doctor_id]">
												<option value="">--</option>
												<?php foreach($doctors as $item){ ?>
												<option class="spe spe_<?= $item->specialty_id ?> d-none" value="<?= $item->person_id ?>"><?= $item->name ?></option>
												<?php } ?>
											</select>
											<div class="sys_msg" id="aa_doctor_msg"></div>
										</div>
										<div class="col-md-4">
											<label class="form-label">Fecha</label>
											<input type="text" class="form-control date_picker" id="aa_date" name="sch[date]" value="<?= date('Y-m-d') ?>">
											<div class="sys_msg" id="aa_date_msg"></div>
										</div>
										<div class="col-md-8">
											<label class="form-label">Hora</label>
											<div class="input-group">
												<select class="form-select" id="aa_hour" name="sch[hour]">
													<option value="" selected>--</option>
													<?php for($i = 0; $i < 24; $i++){ if ($i < 12) $pre = "AM"; else $pre = "PM"; ?>
													<option value="<?= $i ?>">
														<?php 
														switch(true){
															case $i < 12: echo $i." AM"; break;
															case $i == 12: echo $i." M"; break;
															case $i > 12: echo ($i - 12)." PM"; break;
														}
														?>
													</option>
													<?php } ?>
												</select>
												<span class="input-group-text">:</span>
												<select class="form-select" id="aa_min" name="sch[min]">
													<option value="" selected>--</option>
													<?php foreach(["00", "10", "20", "30", "40", "50"] as $item){ ?>
													<option value="<?= $item ?>"><?= $item ?></option>
													<?php } ?>
												</select>
											</div>
											<div class="sys_msg" id="aa_schedule_msg"></div>
										</div>
										<div class="col-md-12 pt-3">
											<strong>Paciente</strong>
										</div>
										<div class="col-md-12">
											<label class="form-label">Documento</label>
											<select class="form-select" id="aa_pt_doc_type_id" name="pt[doc_type_id]">
												<?php foreach($doc_types as $item){ if ($item->sunat_code){ ?>
												<option value="<?= $item->id ?>"><?= $item->description ?></option>
												<?php }} ?>
											</select>
											<div class="sys_msg" id="aa_pt_doc_type_msg"></div>
										</div>
										<div class="col-md-12">
											<label class="form-label">Número</label>
											<div class="input-group">
												<input type="text" class="form-control" id="aa_pt_doc_number" name="pt[doc_number]">
												<button class="btn btn-primary" type="button" id="btn_search_pt_aa">
													<i class="bi bi-search"></i>
												</button>
											</div>
											<div class="sys_msg" id="aa_pt_doc_number_msg"></div>
										</div>
										<div class="col-md-8">
											<label class="form-label">Nombre</label>
											<input type="text" class="form-control" id="aa_pt_name" name="pt[name]" readonly>
											<div class="sys_msg" id="aa_pt_name_msg"></div>
										</div>
										<div class="col-md-4">
											<label class="form-label">Teléfono</label>
											<input type="text" class="form-control" id="aa_pt_tel" name="pt[tel]">
											<div class="sys_msg" id="aa_pt_tel_msg"></div>
										</div>
										<div class="col-md-12">
											<label class="form-label">Observación (Opcional)</label>
											<textarea class="form-control" rows="4" name="app[remark]" placeholder="Síntomas, Notas del paciente, etc."></textarea>
										</div>
										<div class="col-md-12 pt-3">
											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="as_free" id="as_free">
												<label class="form-check-label" for="as_free">
													Generar como consulta gratuita
												</label>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6 mb-3">
									<div class="mb-3"><strong>Agenda del Médico</strong></div>
									<div id="aa_schedule"></div>
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
							<h5 class="modal-title">Buscar Consulta</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<div class="row g-3">
								<div class="col-md-12 pt-3">
									<strong>Por estado de consulta</strong>
								</div>
								<div class="form-group col-md-12">
									<label class="form-label">Estado</label>
									<select class="form-select" name="status_id">
										<option value="">Todos</option>
										<?php foreach($status as $item){ if ($item->id == $f_url["status_id"]) $s = "selected"; else $s = ""; ?>
										<option value="<?= $item->id ?>" <?= $s ?>><?= $this->lang->line($item->code) ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="col-md-12 pt-3">
									<strong>Por paciente</strong>
								</div>
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
								<div class="col-md-12 pt-3">
									<strong>Por diagnóstico</strong>
								</div>
								<div class="form-group col-md-12">
									<input type="text" class="form-control" name="diagnosis" value="<?= $f_url["diagnosis"] ?>" placeholder="Ingrese al menos 3 letras..." >
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
			<div class="card-body">
				<h5 class="card-title">Lista de Consultas</h5>
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
				<h5 class="text-danger mt-3">No existe consultas registradas.</h5>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<script>
document.addEventListener("DOMContentLoaded", () => {
	
	function reset_person_app(){
		$("#aa_pt_name").val("");
		$("#aa_pt_tel").val("");
	}

	function load_doctor_schedule_app(){
		$("#aa_schedule").html('<div class="text-center mt-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
		load_doctor_schedule_n($("#aa_doctor").val(), $("#aa_date").val()).done(function(res) {
			$("#aa_schedule").html(res);
			$("#aa_schedule .sch_cell").on('click',(function(e) {set_time_dom("#aa_hour", "#aa_min", this);}));
			set_time_sl("aa", "#aa_schedule");
		});
	}
	
	//set_date_picker("#aa_date", new Date());
	set_date_picker("#aa_date", null);
	load_doctor_schedule_app();
	
	$("#form_register").submit(function(e) {
		e.preventDefault();
		$("#form_register .sys_msg").html("");
		ajax_form_warning(this, "clinic/appointment/register", "¿Desea agregar nueva consulta?").done(function(res) {
			set_msg(res.msgs);
			swal_redirection(res.type, res.msg, res.move_to);
		});
	});
	
	$("#aa_specialty").change(function() {
		load_doctor_schedule_app();
		$("#aa_doctor").val("");
		$("#aa_doctor .spe").addClass("d-none");
		$("#aa_doctor .spe_" + $(this).val()).removeClass("d-none");
	});
	
	$("#aa_doctor").change(function() {
		load_doctor_schedule_app();
	});
	
	$("#aa_date").focusout(function() {
		load_doctor_schedule_app();
	});
	
	$("#aa_pt_doc_type_id").change(function() {reset_person_app();});
	$("#aa_pt_doc_number").keyup(function() {reset_person_app();});
	
	$("#btn_search_pt_aa").click(function() {
		var data = {doc_type_id: $("#aa_pt_doc_type_id").val(), doc_number: $("#aa_pt_doc_number").val()};
		
		ajax_simple(data, "ajax_f/search_person").done(function(res) {
			swal(res.type, res.msg);
			if (res.type == "success"){
				$("#aa_pt_name").val(res.person.name);
				$("#aa_pt_tel").val(res.person.tel);
			}else {
				reset_person_app();
				$("#aa_pt_name").prop("readonly", false);
			}
		});
	});
	
	$("#ic_doctor_weekly_aa").click(function() {
		load_doctor_schedule_weekly($("#aa_doctor").val(), null, "bl_doctor_weekly_app");
	});
	
	$("#aa_hour, #aa_min").change(function() {
		set_time_sl("aa", "#aa_schedule");
	});
});
</script>