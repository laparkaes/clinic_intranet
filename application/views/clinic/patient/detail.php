<div class="row">
	<div class="col">
		<div class="mb-3">
			<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_appointment">
				<i class="bi bi-clipboard-plus me-1"></i> Generar Consulta
			</button>
			<div class="modal fade" id="modal_appointment" tabindex="-1">
				<div class="modal-dialog modal-xl">
					<form class="modal-content" id="app_register_form">
						<div class="modal-header">
							<h5 class="modal-title">Generar Consulta</h5>
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
												<?php foreach($doctors as $item){ if ($item->status_id == $s_enabled->id){ ?>
												<option class="spe spe_<?= $item->specialty_id ?> d-none" value="<?= $item->person_id ?>"><?= $item->name ?></option>
												<?php }} ?>
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
												<?php $mins = ["00", "10", "20", "30", "40", "50"]; ?>
												<select class="form-select" id="aa_min" name="sch[min]">
													<option value="" selected>--</option>
													<?php foreach($mins as $item){ ?>
													<option value="<?= $item ?>"><?= $item ?></option>
													<?php } ?>
												</select>
											</div>
											<div class="sys_msg" id="aa_schedule_msg"></div>
										</div>
										<div class="col-md-12 pt-3">
											<strong>Paciente</strong>
										</div>
										<input type="hidden" id="aa_pt_id" name="app[patient_id]" value="<?= $person->id ?>">
										<div class="col-md-12">
											<input type="hidden" name="pt[doc_type_id]" value="<?= $person->doc_type_id ?>">
											<input type="hidden" name="pt[doc_number]" value="<?= $person->doc_number ?>">
											<label class="form-label">Documento</label>
											<input type="text" class="form-control" value="<?= $person->doc_type." ".$person->doc_number ?>" readonly>
											<div class="sys_msg" id="pt_doc_msg"></div>
										</div>
										<div class="col-md-8">
											<label class="form-label">Nombre</label>
											<input type="text" class="form-control" name="pt[name]" value="<?= $person->name ?>" readonly>
											<div class="sys_msg" id="pt_name_msg"></div>
										</div>
										<div class="col-md-4">
											<label class="form-label">Teléfono</label>
											<input type="text" class="form-control" name="pt[tel]" value="<?= $person->tel ?>">
											<div class="sys_msg" id="pt_tel_msg"></div>
										</div>
										<div class="col-md-12">
											<label class="form-label">Observación (Opcional)</label>
											<textarea class="form-control" rows="4" name="app[remark]" placeholder="Síntomas, Notas del paciente, etc."></textarea>
										</div>
										<div class="col-md-12 pt-3">
											<div class="form-check">
												<input class="form-check-input" type="checkbox" name="as_free" id="as_free">
												<label class="form-check-label" for="as_free">Generar como consulta gratuita</label>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="mb-3"><strong>Agenda del Médico</strong></div>
									<div id="aa_schedule" style="max-height:700px;overflow-y:scroll;"></div>
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

			<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_surgery">
				<i class="bi bi bi-activity me-1"></i> Generar Cirugía
			</button>
			<div class="modal fade" id="modal_surgery" tabindex="-1">
				<div class="modal-dialog modal-xl">
					<form class="modal-content" id="sur_register_form">
						<div class="modal-header">
							<h5 class="modal-title">Generar Cirugía</h5>
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
												<?php foreach($doctors as $item){ if ($item->status_id == $s_enabled->id){ ?>
												<option class="spe spe_<?= $item->specialty_id ?> d-none" value="<?= $item->person_id ?>"><?= $item->name ?></option>
												<?php }} ?>
											</select>
											<div class="sys_msg" id="sur_doctor_msg"></div>
										</div>
										<div class="col-md-4">
											<label class="form-label">Fecha</label>
											<input type="text" class="form-control" id="sur_date" name="sch[date]" value="<?= date('Y-m-d') ?>">
											<div class="sys_msg" id="sur_date_msg"></div>
										</div>
										<div class="col-md-8">
											<label class="form-label">Hora</label>
											<div class="input-group">
												<select class="form-select" id="sur_hour" name="sch[hour]">
													<option value="" selected>--</option>
													<?php for($i = 9; $i <= 18; $i++){ if ($i < 12) $pre = "AM"; else $pre = "PM"; ?>
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
												<?php $mins = ["00", "10", "20", "30", "40", "50"]; ?>
												<select class="form-select" id="sur_min" name="sch[min]">
													<option value="" selected>--</option>
													<?php foreach($mins as $item){ ?>
													<option value="<?= $item ?>"><?= $item ?></option>
													<?php } ?>
												</select>
											</div>
											<div class="sys_msg" id="sur_schedule_msg"></div>
										</div>
										<div class="col-md-8">
											<label class="form-label">Sala</label>
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
										<input type="hidden" id="aa_pt_id" name="sur[patient_id]" value="<?= $person->id ?>">
										<div class="col-md-12">
											<input type="hidden" name="pt[doc_type_id]" value="<?= $person->doc_type_id ?>">
											<input type="hidden" name="pt[doc_number]" value="<?= $person->doc_number ?>">
											<label class="form-label">Documento</label>
											<input type="text" class="form-control" value="<?= $person->doc_type." ".$person->doc_number ?>">
											<div class="sys_msg" id="pt_doc_msg"></div>
										</div>
										<div class="col-md-8">
											<label class="form-label">Nombre</label>
											<input type="text" class="form-control" name="pt[name]" value="<?= $person->name ?>">
											<div class="sys_msg" id="pt_name_msg"></div>
										</div>
										<div class="col-md-4">
											<label class="form-label">Teléfono</label>
											<input type="text" class="form-control" name="pt[tel]" value="<?= $person->tel ?>">
											<div class="sys_msg" id="pt_tel_msg"></div>
										</div>
										<div class="col-md-12">
											<label class="form-label">Observación (Opcional)</label>
											<textarea class="form-control" rows="4" name="sur[remark]" placeholder="Síntomas, Notas del paciente, etc."></textarea>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="col-md-12"><strong>Agenda del Médico</strong></div>
									<div class="col-md-12"><div id="sur_schedule" style="max-height:700px;overflow-y:scroll;"></div></div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
							<button type="reset" class="btn btn-secondary">Limpiar</button>
							<button type="submit" class="btn btn-primary">Generar</button>
						</div>
					</form>
				</div>
			</div>

		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-4">
		<div class="card">
			<div class="card-body">
				<div class="d-flex justify-content-between align-items-center">
					<h5 class="card-title">Datos Personales</h5>
					<button type="button" class="btn btn-primary btn-sm" id="btn_update_info">
						<i class="bi bi-pencil-square"></i>
					</button>
				</div>
				<form class="row g-3" id="form_update_info">
					<input type="hidden" name="id" value="<?= $person->id ?>">
					<div class="col-md-6">
						<label class="form-label">Documento</label>
						<select class="form-select" id="pu_doc_type_id" name="doc_type_id" disabled>
							<?php foreach($doc_types as $d){ if ($d->sunat_code){ 
							if ($person->doc_type_id == $d->id) $s = "selected"; else $s = ""; ?>
							<option value="<?= $d->id ?>" <?= $s ?>><?= $d->description ?></option>
							<?php }} ?>
						</select>
						<div class="sys_msg" id="pu_doc_type_msg"></div>
					</div>
					<div class="col-md-6">
						<label class="form-label d-md-block d-none">&nbsp;</label>
						<input type="text" class="form-control" id="pu_doc_number" name="doc_number" placeholder="<?= $this->lang->line('w_number') ?>" value="<?= $person->doc_number ?>" readonly>
						<div class="sys_msg" id="pu_doc_number_msg"></div>
					</div>
					<div class="col-md-8">
						<label class="form-label">Nombre</label>
						<input type="text" class="form-control" id="pu_name" name="name" value="<?= $person->name ?>" readonly>
						<div class="sys_msg" id="pu_name_msg"></div>
					</div>
					<div class="col-md-4">
						<label class="form-label">Teléfono</label>
						<input type="text" class="form-control" id="pu_tel" name="tel" value="<?= $person->tel ?>" readonly>
						<div class="sys_msg" id="pu_tel_msg"></div>
					</div>
					<div class="col-md-12">
						<label class="form-label">Correo Electrónico</label>
						<input type="email" class="form-control" name="email" placeholder="email@example.com" value="<?= $person->email ?>" readonly>
						<div class="sys_msg" id="pu_email_msg"></div>
					</div>
					<div class="col-md-12">
						<label class="form-label">Dirección</label>
						<input type="text" class="form-control" name="address" value="<?= $person->address ?>" readonly>
						<div class="sys_msg" id="pu_address_msg"></div>
					</div>
					<div class="col-md-12">
						<?php
						if ($person->birthday){
							$aux = strtotime($person->birthday);
							$b = date("Y-m-d", $aux);
							$d = (int)date("d", $aux); $m = (int)date("m", $aux); $y = (int)date("Y", $aux);	
						}else $b = $d = $m = $y = null;
						?>
						<label class="form-label">Fecha de Nacimiento</label>
						<input type="hidden" id="p_birthday" name="birthday" value="<?= $b ?>" readonly>
						<div class="input-group">
							<select class="form-select" id="p_birthday_d" disabled>
								<option value="" selected="">Día</option>
								<?php for($i = 1; $i <= 31; $i++){
								if ($i == $d) $s = "selected"; else $s = ""; ?>
								<option value="<?= $i ?>" <?= $s ?>><?= $i ?></option>
								<?php } ?>
							</select>
							<select class="form-select" id="p_birthday_m" disabled>
								<option value="" selected="">Mes</option>
								<?php for($i = 1; $i <= 12; $i++){
								if ($i == $m) $s = "selected"; else $s = ""; ?>
								<option value="<?= $i ?>" <?= $s ?>><?= $i ?></option>
								<?php } ?>
							</select>
							<?php $now = date('Y'); ?>
							<select class="form-select" id="p_birthday_y" disabled>
								<option value="" selected="">Año</option>
								<?php for($i = 0; $i <= 130; $i++){ $aux = $now - $i;
								if ($aux == $y) $s = "selected"; else $s = ""; ?>
								<option value="<?= $aux ?>" <?= $s ?>><?= $aux ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="sys_msg" id="du_birthday_msg"></div>
					</div>
					<div class="col-md-6">
						<label class="form-label">Sexo</label>
						<select class="form-select" name="sex_id" disabled>
							<option value="" selected="">--</option>
							<?php foreach($sex_ops as $item){
							if ($item->id == $person->sex_id) $s = "selected"; else $s = ""; ?>
							<option value="<?= $item->id ?>" <?= $s ?>><?= $item->description ?></option>
							<?php } ?>
						</select>
						<div class="sys_msg" id="pu_sex_msg"></div>
					</div>
					<div class="col-md-6">
						<label class="form-label">Grupo Sanguíneo</label>
						<select class="form-select" name="blood_type_id" disabled>
							<option value="" selected="">--</option>
							<?php foreach($blood_type_ops as $item){
							if ($item->id == $person->blood_type_id) $s = "selected"; else $s = ""; ?>
							<option value="<?= $item->id ?>" <?= $s ?>><?= $item->description ?></option>
							<?php } ?>
						</select>
						<div class="sys_msg" id="pu_blood_type_msg"></div>
					</div>
					<div class="col-md-12 pt-3 d-none" id="action_btns">
						<button type="submit" class="btn btn-primary" id="btn_update_confirm" disabled>
							Confirmar
						</button>
						<button type="button" class="btn btn-secondary" id="btn_update_cancel" disabled>
							Cancelar
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="card">
			<div class="card-body">
				<div class="d-flex justify-content-between align-items-center">
					<h5 class="card-title">Archivos</h5>
					<button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal_file">
						<i class="bi bi bi-plus-lg"></i>
					</button>
					<div class="modal fade" id="modal_file" tabindex="-1">
						<div class="modal-dialog">
							<form class="modal-content" id="form_upload_patient_file">
								<input type="hidden" name="patient_id" value="<?= $person->id ?>">
								<div class="modal-header">
									<h5 class="modal-title">Registrar Archivo</h5>
									<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
								</div>
								<div class="modal-body">
									<div class="row g-3">
										<div class="col-md-12">
											<label class="form-label"><?= $this->lang->line('w_title') ?></label>
											<input type="text" class="form-control" name="title">
											<div class="sys_msg" id="pf_title_msg"></div>
										</div>
										<div class="col-md-12">
											<label class="form-label"><?= $this->lang->line('w_file') ?></label>
											<input type="file" class="form-control" name="upload_file" id="upload_file">
											<div class="sys_msg" id="pf_file_msg"></div>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
									<button type="reset" class="btn btn-secondary">Limpiar</button>
									<button type="submit" class="btn btn-primary">Registrar</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<?php if ($patient_files){ ?>
				<table class="table datatable">
					<thead>
						<tr>
							<th>Fecha</th>
							<th>Nombre</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php $file_path = base_url()."uploaded/pacientes/".str_replace(" ", "_", $person->name)."_".$person->doc_number."/"; foreach($patient_files as $item){ ?>
						<tr>
							<td><?= explode(" ", $item->registed_at)[0] ?></td>
							<td><?= $item->title ?></td>
							<td>
								<div class="text-end">
									<a href="<?= $file_path.$item->filename ?>" target="_blank" class="btn btn-primary btn-sm">
										<i class="bi bi-folder2-open"></i>
									</a>
									<button type="button" class="btn btn-secondary btn-sm btn_delete_file" value="<?= $item->id ?>">
										<i class="bi bi-trash"></i>
									</button>
								</div>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
				<?php }else{ ?>
				<div>No hay archivos registrados.</div>
				<?php } ?>
			</div>
		</div>
		<div class="card">
			<div class="card-body">
				<div class="d-flex justify-content-between align-items-center">
					<h5 class="card-title">Credito</h5>
					<button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal_credit" >
						<i class="bi bi bi-plus-lg"></i>
					</button>
					<div class="modal fade" id="modal_credit" tabindex="-1">
						<div class="modal-dialog">
							<form class="modal-content" id="form_add_credit">
								<div class="modal-header">
									<h5 class="modal-title">Agregar Credito</h5>
									<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
								</div>
								<div class="modal-body">
									<div class="row g-3">
										<div class="col-md-4">
											<label class="form-label">Moneda</label>
											<select class="form-select" name="currency_id">
												<?php foreach($currencies as $item){ ?>
												<option value="<?= $item->id ?>"><?= $item->description ?></option>
												<?php } ?>
											</select>
											<div class="sys_msg" id="ac_currency_msg"></div>
										</div>
										<div class="col-md-8">
											<label class="form-label">Monto</label>
											<input type="text" class="form-control" name="amount" value="0">
											<div class="sys_msg" id="ac_amount_msg"></div>
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
				</div>
				<?php if ($credit_histories){ ?>
				<table class="table datatable">
					<thead>
						<tr>
							<th>Fecha</th>
							<th>Monto</th>
							<th>Observación</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($credit_histories as $item){ ?>
						<tr>
							<td><?= $item->registed_at ?></td>
							<?php if ($item->amount > 0){ $c = "success"; $s = "+"; }
							else{ $c = "danger"; $s = "-"; } ?>
							<td class="text-<?= $c ?>"><?= $s." ".$currencies_arr[$item->currency_id]->description." ".number_format(abs($item->amount), 2) ?></td>
							<td><?= $item->remark ?></td>
							<td>
								<div class="text-end">
									<?php if (!$item->is_reversed){ ?>
									<button type="button" class="btn btn-secondary btn-sm btn_reverse_credit" value="<?= $item->id ?>">
										<i class="bi bi-trash"></i>
									</button>
									<?php } ?>
								</div>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
				<?php }else{ ?>
				<div>No hay historial de credito.</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="card">
			<div class="card-body">
				<h5 class="card-title">Ventas</h5>
				<?php if ($sales){ ?>
				<table class="table datatable">
					<thead>
						<tr>
							<th>Fecha</th>
							<th>Monto</th>
							<th>Estado</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($sales as $s){ $cur = $currencies_arr[$s->currency_id]->description; ?>
						<tr>
							<td><?= explode(" ", $s->registed_at)[0] ?></td>
							<td><?= $cur." ".number_format($s->total, 2) ?></td>
							<td>
								<span class="text-<?= $status_arr[$s->status_id]->color ?>">
									<?= $this->lang->line($status_arr[$s->status_id]->code) ?>
								</span>
							</td>
							<td>
								<div class="text-end">
									<a href="<?= base_url() ?>commerce/sale/detail/<?= $s->id ?>" class="btn btn-primary btn-sm">
										<i class="bi bi-arrow-right"></i>
									</a>
								</div>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
				<?php }else{ ?>
				<div>No hay ventas registradas.</div>
				<?php } ?>
			</div>
		</div>
		<div class="card">
			<div class="card-body">
				<h5 class="card-title">Consultas</h5>
				<?php if ($appointments){ ?>
				<table class="table datatable">
					<thead>
						<tr>
							<th>Fecha</th>
							<th>Especialidad</th>
							<th>Estado</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($appointments as $item){ ?>
						<tr>
							<td><?= date("Y-m-d", strtotime($item->schedule_from)) ?></td>
							<td><?= $specialty_arr[$item->specialty_id] ?></td>
							<td>
								<span class="text-<?= $status_arr[$item->status_id]->color ?>"><?= $this->lang->line($status_arr[$item->status_id]->code) ?></span>
							</td>
							<td>
								<div class="text-end">
									<a href="<?= base_url() ?>clinic/appointment/detail/<?= $item->id ?>" class="btn btn-primary btn-sm">
										<i class="bi bi-arrow-right"></i>
									</a>
								</div>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
				<?php }else{ ?>
				<div>No hay consultas registradas.</div>
				<?php } ?>
			</div>
		</div>
		<div class="card">
			<div class="card-body">
				<h5 class="card-title">Cirugías</h5>
				<?php if ($surgeries){ ?>
				<table class="table datatable">
					<thead>
						<tr>
							<th>Fecha</th>
							<th>Especialidad</th>
							<th>Estado</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($surgeries as $item){ ?>
						<tr>
							<td><?= date("Y-m-d", strtotime($item->schedule_from)) ?></td>
							<td><?= $specialty_arr[$item->specialty_id] ?></td>
							<td>
								<div class="text-<?= $status_arr[$item->status_id]->color ?>">
									<?= $this->lang->line($status_arr[$item->status_id]->code) ?>
								</div>
							</td>
							<td>
								<div class="text-end">
									<a href="<?= base_url() ?>clinic/surgery/detail/<?= $item->id ?>" class="btn btn-primary btn-sm">
										<i class="bi bi-arrow-right"></i>
									</a>
								</div>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
				<?php }else{ ?>
				<div>No hay cirugías registradas.</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<script>
function load_doctor_schedule_app(){
	$("#aa_schedule").html('<div class="text-center mt-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
	load_doctor_schedule_n($("#aa_doctor").val(), $("#aa_date").val()).done(function(res) {
		$("#aa_schedule").html(res);
		$("#aa_schedule .sch_cell").on('click',(function(e) {set_time_dom("#aa_hour", "#aa_min", this);}));
		set_time_sl("aa", "#aa_schedule");
	});
}

function load_doctor_schedule_sur(){
	$("#sur_schedule").html('<div class="text-center mt-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
	load_doctor_schedule_n($("#sur_doctor").val(), $("#sur_date").val()).done(function(res) {
		$("#sur_schedule").html(res);
		$("#sur_schedule .sch_cell").on('click',(function(e) {set_time_dom("#sur_hour", "#sur_min", this);}));
		set_time_sl("sur", "#sur_schedule");
	});
}

function disable_update_form(){
	$("#form_update_info input").prop("readonly", true);
	$("#form_update_info input").prop("disabled", true);
	$("#form_update_info select").prop("disabled", true);
	$("#form_update_info button").prop("disabled", true);
	
	$("#btn_update_info").removeClass("d-none").prop("disabled", false);
	$("#form_update_info #action_btns").addClass("d-none");
}
	
document.addEventListener("DOMContentLoaded", () => {
	
	//appointment start
	set_date_picker("#aa_date", null);
	load_doctor_schedule_app();
	
	$("#app_register_form").submit(function(e) {
		e.preventDefault();
		$("#app_register_form .sys_msg").html("");
		ajax_form_warning(this, "clinic/appointment/register", "wm_appointment_register").done(function(res) {
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
	//appointment end
	
	//surgery start
	set_date_picker("#sur_date", new Date());
	load_doctor_schedule_sur();
	
	$("#sur_register_form").submit(function(e) {
		e.preventDefault();
		$("#sur_register_form .sys_msg").html("");
		ajax_form_warning(this, "clinic/surgery/register", "wm_surgery_register").done(function(res) {
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
	
	$("#sur_hour, #sur_min").change(function() {
		set_time_sl("sur", "#sur_schedule");
	});
	//surgery end
	
	
	
	
	//general
	$(".control_bl_simple").click(function() {
		control_bl_simple(this);
	});
	
	//update information
	$("#form_update_info").submit(function(e) {
		e.preventDefault();
		
		//birthday merge
		let d = $("#p_birthday_d").val();
		let m = $("#p_birthday_m").val();
		let y = $("#p_birthday_y").val();
		if (d != "" && m != "" && y != "") $("#p_birthday").val(y + "-" + m + "-" + d); else $("#p_birthday").val("");
		
		//doc_type_id field
		$("#pu_doc_type_id").prop("disabled", false);
		
		$("#form_update_info .sys_msg").html("");
		ajax_form(this, "clinic/patient/update_info").done(function(res) {
			set_msg(res.msgs);
			swal(res.type, res.msg);
			if (res.type == "success") disable_update_form();
			//swal_redirection(res.type, res.msg, window.location.href);
		});
	});
	
	disable_update_form();
	
	$("#btn_update_info").click(function() {
		$("#form_update_info input").prop("readonly", false);
		$("#form_update_info input").prop("disabled", false);
		$("#form_update_info select").prop("disabled", false);
		$("#form_update_info button").prop("disabled", false);
		
		$("#btn_update_info").addClass("d-none").prop("disabled", true);
		$("#form_update_info #action_btns").removeClass("d-none");
	});
	
	$("#btn_update_cancel").click(function() {
		disable_update_form();
		document.getElementById("form_update_info").reset();
	});
	
	//admin credit
	$("#form_add_credit").submit(function(e) {
		e.preventDefault();
		$("#form_add_credit .sys_msg").html("");
		ajax_form_warning(this, "clinic/patient/add_credit", "wm_add_credit").done(function(res) {
			set_msg(res.msgs);
			swal_redirection(res.type, res.msg, window.location.href);
		});
	});
	
	$(".btn_reverse_credit").click(function() {
		ajax_simple_warning({id: $(this).val()}, "clinic/patient/reverse_credit", "wm_reverse_credit").done(function(res) {
			swal_redirection(res.type, res.msg, window.location.href);
		});
	});
	
	//admin patient file
	$("#form_upload_patient_file").submit(function(e) {
		e.preventDefault();
		$("#form_upload_patient_file .sys_msg").html("");
		ajax_form(this, "clinic/patient/upload_file").done(function(res) {
			set_msg(res.msgs);
			swal_redirection(res.type, res.msg, window.location.href);
		});
	});
	
	$(".btn_delete_file").click(function() {
		ajax_simple_warning({id: $(this).val()}, "clinic/patient/delete_file", "wm_delete_file").done(function(res) {
			swal_redirection(res.type, res.msg, window.location.href);
		});
	});
	
	$("#upload_file").change(function(e) {
		$("#lb_selected_filename").html(e.target.files[0].name); 
		$("#ip_selected_filename").val(e.target.files[0].name);
	});
});
</script>