<div class="row">
	<div class="col">
		<div class="mb-3">
			<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add">
				<i class="bi bi-plus-lg me-1"></i> Agregar
			</button>
			<div class="modal fade" id="modal_add" tabindex="-1">
				<div class="modal-dialog modal-xl">
					<form class="modal-content" id="sur_register_form">
						<div class="modal-header">
							<h5 class="modal-title">Agregar Consulta</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<input type="hidden" id="sur_pt_id" name="sur[patient_id]" value="">
							<div class="row">
								<div class="col-md-6">
									<div class="row g-3">
										<div class="col-md-12">
											<strong>Atención</strong>
										</div>
										<div class="col-md-12">
											<label class="form-label">Especialidad</label>
											<select class="form-select" id="sur_specialty" name="sur[specialty_id]">
												<option value="">--</option>
												<?php foreach($specialties as $item){ if ($item->doctor_qty){ ?>
												<option value="<?= $item->id ?>"><?= $item->name ?></option>
												<?php }} ?>
											</select>
											<div class="sys_msg" id="sur_specialty_msg"></div>
										</div>
										<div class="col-md-12">
											<label class="form-label">Médico</label>
											<select class="form-select" id="sur_doctor" name="sur[doctor_id]">
												<option value="">--</option>
												<?php foreach($doctors as $item){ ?>
												<option class="spe spe_<?= $item->specialty_id ?> d-none" value="<?= $item->person_id ?>"><?= $item->name ?></option>
												<?php } ?>
											</select>
											<div class="sys_msg" id="sur_doctor_msg"></div>
										</div>
										<div class="col-md-4">
											<label class="form-label">Fecha</label>
											<input type="text" class="form-control date_picker" id="sur_date" name="sch[date]" value="<?= date('Y-m-d') ?>">
											<div class="sys_msg" id="sur_date_msg"></div>
										</div>
										<div class="col-md-8">
											<label class="form-label">Hora</label>
											<div class="input-group">
												<select class="form-select" id="sur_hour" name="sch[hour]">
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
												<select class="form-select" id="sur_min" name="sch[min]">
													<option value="" selected>--</option>
													<?php foreach(["00", "10", "20", "30", "40", "50"] as $item){ ?>
													<option value="<?= $item ?>"><?= $item ?></option>
													<?php } ?>
												</select>
											</div>
											<div class="sys_msg" id="sur_schedule_msg"></div>
										</div>
										<div class="col-md-8">
											<label class="form-label">
												<span>Sala</span>
												<i class="bi bi-alarm ms-2" id="ic_room_weekly_sur" data-bs-toggle="modal" data-bs-target="#md_room_weekly_sur"></i>
											</label>
											<select class="form-select" id="sur_room_id" name="sur[room_id]">
												<option value="">--</option>
												<?php foreach($rooms as $r){ ?>
												<option value="<?= $r->id ?>"><?= $r->name ?></option>
												<?php } ?>
											</select>
											<div class="sys_msg" id="sur_room_msg"></div>
										</div>
										<div class="col-md-4">
											<label class="form-label">Duración</label>
											<select class="form-select" name="sch[duration]">
												<option value="">--</option>
												<?php foreach($duration_ops as $op){ ?>
												<option value="<?= $op["value"] ?>"><?= $op["txt"] ?></option>
												<?php } ?>
											</select>
											<div class="sys_msg" id="sur_duration_msg"></div>
										</div>
										<div class="col-md-12 pt-3">
											<strong>Paciente</strong>
										</div>
										<div class="col-md-12">
											<label class="form-label">Documento</label>
											<select class="form-select" id="sur_pt_doc_type_id" name="pt[doc_type_id]">
												<?php foreach($doc_types as $item){ if ($item->sunat_code){ ?>
												<option value="<?= $item->id ?>"><?= $item->description ?></option>
												<?php }} ?>
											</select>
											<div class="sys_msg" id="sur_pt_doc_type_msg"></div>
										</div>
										<div class="col-md-12">
											<label class="form-label">Número</label>
											<div class="input-group">
												<input type="text" class="form-control" id="sur_pt_doc_number" name="pt[doc_number]">
												<button class="btn btn-primary" type="button" id="btn_search_pt_sur">
													<i class="bi bi-search"></i>
												</button>
											</div>
											<div class="sys_msg" id="sur_pt_doc_number_msg"></div>
										</div>
										<div class="col-md-8">
											<label class="form-label">Nombre</label>
											<input type="text" class="form-control" id="sur_pt_name" name="pt[name]">
											<div class="sys_msg" id="sur_pt_name_msg"></div>
										</div>
										<div class="col-md-4">
											<label class="form-label">Teléfono</label>
											<input type="text" class="form-control" id="sur_pt_tel" name="pt[tel]">
											<div class="sys_msg" id="sur_pt_tel_msg"></div>
										</div>
										<div class="col-md-12">
											<label class="form-label">Observación (Opcional)</label>
											<textarea class="form-control" rows="4" name="sur[remark]" placeholder="Síntomas, Notas del paciente, etc."></textarea>
										</div>
									</div>
								</div>
								<div class="col-md-6 mb-3">
									<div class="mb-3"><strong>Agenda del Médico</strong></div>
									<div id="sur_schedule"></div>
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
								<div class="col-md-12">
									<strong>Por estado de cirugía</strong>
								</div>
								<div class="form-group col-md-12">
									<label class="form-label">Estado</label>
									<select class="form-select" name="status">
										<option value="">Todos</option>
										<?php foreach($status as $item){ if ($item->id == $f_url["status"]) $s = "selected"; else $s = ""; ?>
										<option value="<?= $item->id ?>" <?= $s ?>><?= $item->sp ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="col-md-12 pt-3">
									<strong>Por paciente</strong>
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
				<?php if ($surgeries){ ?>
				<div class="table-responsive">
					<table class="table align-middle">
						<thead>
							<tr>
								<th>Estado</th>
								<th>Fecha</th>
								<th>Hora</th>
								<th>Sala</th>
								<th>Especialidad</th>
								<th>Médico</th>
								<th>Paciente</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($surgeries as $i => $item){ ?>
							<tr>
								<td><span class="text-<?= $item->status_color ?>"><?= $item->status_sp ?></span></td>
								<td><?= date("Y-m-d", strtotime($item->schedule_from)); ?></td>
								<td><?= date("h:i A", strtotime($item->schedule_from)); ?></td>
								<td class="text-nowrap"><?= $item->room ?></td>
								<td><?= $item->specialty ?></td>
								<td><?= $item->doctor ?></td>
								<td><?= $item->patient ?></td>
								<td class="text-end">
									<a href="<?= base_url() ?>clinic/surgery/detail/<?= $item->id ?>" class="btn btn-primary btn-sm">
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
						<a href="<?= base_url() ?>clinic/surgery?<?= http_build_query($f_url) ?>" class="btn btn-<?= $p[2] ?>">
							<?= $p[1] ?>
						</a>
						<?php } ?>
					</div>
				</div>
				<?php }else{ ?>
				<h5 class="text-danger">'No existe cirugías registradas.'</h5>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<script>
document.addEventListener("DOMContentLoaded", () => {
	
	function reset_person_sur(){
		$("#sur_pt_name").val("");
		$("#sur_pt_tel").val("");
	}
	
	function load_doctor_schedule_sur(){
		$("#sur_schedule").html('<div class="text-center mt-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
		load_doctor_schedule_n($("#sur_doctor").val(), $("#sur_date").val()).done(function(res) {
			$("#sur_schedule").html(res);
			$("#sur_schedule .sch_cell").on('click',(function(e) {set_time_dom("#sur_hour", "#sur_min", this);}));
			set_time_sl("sur", "#sur_schedule");
		});
	}

	set_date_picker("#sur_date", new Date());
	load_doctor_schedule_sur();
	
	$("#sur_register_form").submit(function(e) {
		e.preventDefault();
		$("#sur_register_form .sys_msg").html("");
		ajax_form_warning(this, "clinic/surgery/register", "¿Desea agregar nueva cirugía?").done(function(res) {
			set_msg(res.msgs);
			swal_redirection(res.type, res.msg, res.move_to);
		});
	});

	$("#sur_specialty").change(function() {
		load_doctor_schedule_sur();
		$("#sur_doctor").val("");
		$("#sur_doctor .spe").addClass("d-none");
		$("#sur_doctor .spe_" + $(this).val()).removeClass("d-none");
	});
	
	$("#sur_doctor").change(function() {
		load_doctor_schedule_sur();
	});
	
	$("#sur_date").focusout(function() {
		load_doctor_schedule_sur();
	});
	
	$("#sur_pt_doc_type_id").change(function() {reset_person_sur();});
	$("#sur_pt_doc_number").keyup(function() {reset_person_sur();});
	
	$("#btn_search_pt_sur").click(function() {
		var data = {doc_type_id: $("#sur_pt_doc_type_id").val(), doc_number: $("#sur_pt_doc_number").val()};
		
		ajax_simple(data, "ajax_f/search_person").done(function(res) {
			swal(res.type, res.msg);
			if (res.type == "success"){
				$("#sur_pt_name").val(res.person.name);
				$("#sur_pt_tel").val(res.person.tel);
			}else reset_person_sur();
		});
	});
	
	$("#ic_doctor_weekly_sur").click(function() {
		load_doctor_schedule_weekly($("#sur_doctor").val(), null, "bl_weekly_schedule_sur");
	});
	
	$("#ic_room_weekly_sur").click(function() {
		load_room_availability($("#sur_room_id").val(), null, "bl_room_weekly_sur");
	});
	
	$("#sur_hour, #sur_min").change(function() {
		set_time_sl("sur", "#sur_schedule");
	});
	
});
</script>