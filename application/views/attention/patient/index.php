<div class="d-flex justify-content-between pb-3">
	<div>
		<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#search_modal">
			<i class="bi bi-search me-1"></i> Buscar
		</button> 
		<button type="button" class="btn btn-success"><i class="bi bi-file-earmark-spreadsheet me-1"></i> Descargar</button>
		<button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#registration_modal">
			<i class="bi bi-plus-square me-1"></i> Registrar
		</button>
	</div>
	<div>
		<?php if ($f_url["name"]){ $aux = $f_url; unset($aux["name"]); ?>
		<a href="<?= base_url() ?>attention/patient?<?= http_build_query($aux) ?>" class="badge bg-info text-dark me-1"><?= $f_url["name"] ?> | X</a>
		<?php } if ($f_url["doc_number"]){ $aux = $f_url; unset($aux["doc_number"]); ?>
		<a href="<?= base_url() ?>attention/patient?<?= http_build_query($aux) ?>" class="badge bg-info text-dark me-1"><?= $f_url["doc_number"] ?> | X</a>
		<?php } if ($f_url["tel"]){ $aux = $f_url; unset($aux["tel"]); ?>
		<a href="<?= base_url() ?>attention/patient?<?= http_build_query($aux) ?>" class="badge bg-info text-dark me-1"><?= $f_url["tel"] ?> | X</a>
		<?php } ?>
	</div>
</div>
<div class="card">
	<div class="card-body pt-3">
		<?php if ($patients){ ?>
		<table class="table">
			<thead>
				<tr>
					<th><strong>Nombre</strong></th>
					<th><strong>Edad</strong></th>
					<th><strong>Documento</strong></th>
					<th><strong>Sexo</strong></th>
					<th><strong>Teléfono</strong></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($patients as $i => $item){ ?>
				<tr>
					<td><?= $item->name ?></td>
					<td><?= $item->age ?></td>
					<td><?= $item->doc_type." ".$item->doc_number ?></td>
					<td><?= $item->sex ?></td>
					<td><?= $item->tel ?></td>
					<td class="text-end">
						<a href="<?= base_url() ?>attention/patient/detail/<?= $item->id ?>" class="btn btn-outline-primary btn-sm">
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
			<a href="<?= base_url() ?>attention/patient?<?= http_build_query($f_url) ?>" class="btn btn-<?= $p[2] ?>">
				<?= $p[1] ?>
			</a>
			<?php } ?>
		</div>
		<?php }else{ ?>
		<h5 class="text-danger mt-3">No hay pacientes registrados.</h5>
		<?php } ?>
	</div>
</div>

<div class="modal fade" id="search_modal" tabindex="-1" style="display: none;" aria-hidden="true">
	<div class="modal-dialog">
		<form class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Buscar</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<input type="hidden" value="1" name="page">
				
				<div class="row g-3">
					<div class="col-md-12">
						<label class="form-label">Nombre</label>
						<input type="text" class="form-control" name="name" value="<?= $f_url["name"] ?>">
					</div>
					<div class="col-md-6">
						<label class="form-label">DNI/CE/Pasaporte</label>
						<input type="text" class="form-control" name="doc_number" value="<?= $f_url["doc_number"] ?>">
					</div>
					<div class="col-md-6">
						<label class="form-label">Teléfono</label>
						<input type="text" class="form-control" name="tel" value="<?= $f_url["tel"] ?>">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
				<button type="submit" class="btn btn-primary">Buscar</button>
			</div>
		</form>
	</div>
</div>

<div class="modal fade" id="registration_modal" tabindex="-1" style="display: none;" aria-hidden="true">
	<div class="modal-dialog">
		<form class="modal-content" id="form_register">
			<div class="modal-header">
				<h5 class="modal-title">Registrar</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row g-3">
				
				
				
					<div class="form-group col-md-3">
						<label class="form-label"><?= $this->lang->line('w_document') ?></label>
						<select class="form-select" id="pn_doc_type_id" name="doc_type_id">
							<?php foreach($doc_types as $d){ if ($d->sunat_code){ ?>
							<option value="<?= $d->id ?>"><?= $d->description ?></option>
							<?php }} ?>
						</select>
						<div class="sys_msg" id="pn_doc_type_msg"></div>
					</div>
					<div class="form-group col-md-3">
						<label class="form-label d-md-block d-none">&nbsp;</label>
						<div class="input-group">
							<input type="text" class="form-control" id="pn_doc_number" name="doc_number" placeholder="<?= $this->lang->line('w_number') ?>">
							<button class="btn btn-primary" type="button" id="btn_search_person_pn">
								<i class="bi bi-search"></i>
							</button>
						</div>
						<div class="sys_msg" id="pn_doc_number_msg"></div>
					</div>
					<div class="form-group col-md-6">
						<label class="form-label"><?= $this->lang->line('w_name') ?></label>
						<input type="text" class="form-control" id="pn_name" name="name">
						<div class="sys_msg" id="pn_name_msg"></div>
					</div>
					<div class="form-group col-md-4">
						<label class="form-label"><?= $this->lang->line('w_tel') ?></label>
						<input type="text" class="form-control" id="pn_tel" name="tel">
						<div class="sys_msg" id="pn_tel_msg"></div>
					</div>
					<div class="form-group col-md-4">
						<label class="form-label"><?= $this->lang->line('w_birthday') ?></label>
						<input type="hidden" id="p_birthday" name="birthday" readonly="">
						<div class="input-group">
							<select class="form-select" id="p_birthday_d">
								<option value="" selected=""><?= $this->lang->line('date_d') ?></option>
								<?php for($i = 1; $i <= 31; $i++){ ?>
								<option value="<?= $i ?>"><?= $i ?></option>
								<?php } ?>
							</select>
							<select class="form-select" id="p_birthday_m">
								<option value="" selected=""><?= $this->lang->line('date_m') ?></option>
								<?php for($i = 1; $i <= 12; $i++){ ?>
								<option value="<?= $i ?>"><?= $i ?></option>
								<?php } ?>
							</select>
							<?php $now = date('Y'); ?>
							<select class="form-select" id="p_birthday_y">
								<option value="" selected=""><?= $this->lang->line('date_y') ?></option>
								<?php for($i = 0; $i <= 130; $i++){ ?>
								<option value="<?= $now - $i ?>"><?= $now - $i ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="sys_msg" id="pn_birthday_msg"></div>
					</div>
					<div class="form-group col-md-2">
						<label class="form-label"><?= $this->lang->line('w_sex') ?></label>
						<select class="form-select" name="sex_id">
							<option value="" selected="">--</option>
							<?php foreach($sex_ops as $item){ ?>
							<option value="<?= $item->id ?>"><?= $item->description ?></option>
							<?php } ?>
						</select>
						<div class="sys_msg" id="pn_sex_msg"></div>
					</div>
					<div class="form-group col-md-2">
						<label class="form-label"><?= $this->lang->line('w_blood_type') ?></label>
						<select class="form-select" name="blood_type_id">
							<option value="" selected="">--</option>
							<?php foreach($blood_type_ops as $item){ ?>
							<option value="<?= $item->id ?>"><?= $item->description ?></option>
							<?php } ?>
						</select>
						<div class="sys_msg" id="pn_blood_type_msg"></div>
					</div>
					<div class="form-group col-md-4">
						<label class="form-label"><?= $this->lang->line('w_email') ?></label>
						<input type="email" class="form-control" name="email" placeholder="email@example.com">
						<div class="sys_msg" id="pn_email_msg"></div>
					</div>
					<div class="form-group col-md-8">
						<label class="form-label"><?= $this->lang->line('w_address') ?></label>
						<input type="text" class="form-control" name="address">
						<div class="sys_msg" id="pn_address_msg"></div>
					</div>
				
				
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
				<button type="submit" class="btn btn-primary">Registrar</button>
			</div>
		</form>
	</div>
</div>


<div class="row mt-3">
	<div class="col">
		<div class="card bl_content" id="bl_list"></div>
		<div class="card bl_content" id="bl_add">
			<div class="card-body">
				<h5 class="card-title"><?= $this->lang->line('w_patient_info') ?></h5>
				<form class="row g-3">
					<div class="form-group col-md-12 pt-3">
						<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_register') ?></button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
document.addEventListener("DOMContentLoaded", () => {
	function reset_person(){
		$("#pn_name").val("");
		$("#pn_tel").val("");
	}
	
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
		ajax_form_warning(this, "clinic/patient/register", "wm_patient_register").done(function(res) {
			set_msg(res.msgs);
			swal_redirection(res.type, res.msg, res.move_to);
		});
	});
	
	$("#btn_search_person_pn").click(function() {
		search_person_pn();
		var data = {doc_type_id: $("#pn_doc_type_id").val(), doc_number: $("#pn_doc_number").val()};
		ajax_simple(data, "ajax_f/search_person").done(function(res) {
			swal(res.type, res.msg);
			if (res.type == "success"){
				$("#pn_name").val(res.person.name);
				$("#pn_tel").val(res.person.tel);
			}else reset_person();
		});
	});
	
	$("#pn_doc_type_id").change(function() {
		reset_person();
	});
	
	$("#pn_doc_number").keyup(function() {
		reset_person();
	});
});
</script>