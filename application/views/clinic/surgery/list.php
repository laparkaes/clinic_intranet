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



<div class="d-flex justify-content-between align-items-start">
	<div class="btn-group mb-3">
		<button type="button" class="btn btn-primary control_bl" id="btn_list" value="bl_list">
			<i class="bi bi-card-list"></i>
		</button>
		<button type="button" class="btn btn-outline-primary control_bl" value="bl_add">
			<i class="bi bi-plus-lg"></i>
		</button>
	</div>
</div>
<div class="row mt-3">
	<div class="col-md-12">
		<div class="card bl_content" id="bl_list">
			<div class="card-body">
				<h5 class="card-title"><?= $this->lang->line('w_list') ?></h5>
				<?php if ($surgeries){ ?>
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<th>#</th>
								<th><?= $this->lang->line('w_itinerary') ?></th>
								<th><?= $this->lang->line('w_room') ?></th>
								<th><?= $this->lang->line('w_specialty') ?></th>
								<th><?= $this->lang->line('w_doctor') ?> / <?= $this->lang->line('w_patient') ?></th>
								<th><?= $this->lang->line('w_status') ?></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($surgeries as $i => $item){ ?>
							<tr>
								<td><strong><?= number_format(($f_url["page"] - 1) * 25 + 1 + $i) ?></strong></td>
								<td>
									<div class="text-nowrap"><?= date("h:i A", strtotime($item->schedule_from)); ?></div>
									<div><?= date("Y-m-d", strtotime($item->schedule_from)); ?></div>
								</td>
								<td class="text-nowrap"><?= $item->room ?></td>
								<td><?= $item->specialty ?></td>
								<td><?= $item->doctor ?><br/>/ <?= $item->patient ?></td>
								<td><span class="text-<?= $item->status_color ?>"><?= $item->status_sp ?></span></td>
								<td class="text-end">
									<a href="<?= base_url() ?>clinic/surgery/detail/<?= $item->id ?>" class="btn btn-primary btn-sm">
										<i class="bi bi-search"></i>
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
				<h5 class="text-danger"><?= $this->lang->line('t_no_surgeries') ?></h5>
				<?php } ?>
			</div>
		</div>
		<div class="card bl_content d-none" id="bl_add">
			<?php $this->load->view("clinic/surgery/form_add_surgery", ["patient" => null, "doctor" => null]); ?>
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