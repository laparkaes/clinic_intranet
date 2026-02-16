<div class="row">
	<div class="col">
		<div class="mb-3">
			<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add">
				<i class="bi bi-plus-lg me-1"></i> Agregar
			</button>
			<div class="modal fade" id="modal_add" tabindex="-1">
				<div class="modal-dialog">
					<form class="modal-content" id="form_register_account">
						<div class="modal-header">
							<h5 class="modal-title">Nuevo Usuario</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<div class="row g-3">
								<div class="col-md-12"><strong>Datos Personales</strong></div>
								<div class="col-md-12">
									<label class="form-label">Documento</label>
									<select class="form-select" id="ra_doc_type_id" name="p[doc_type_id]">
										<?php foreach($doc_types as $d){ if ($d->sunat_code){ ?>
										<option value="<?= $d->id ?>"><?= $d->description ?></option>
										<?php }} ?>
									</select>
									<div class="sys_msg" id="ra_doc_type_msg"></div>
								</div>
								<div class="col-md-12">
									<label class="form-label d-md-block d-none">&nbsp;</label>
									<div class="input-group">
										<input type="text" class="form-control" id="ra_doc_number" name="p[doc_number]" placeholder="Ingrese número aquí">
										<button class="btn btn-primary" type="button" id="btn_search_person_ra">
											<i class="bi bi-search"></i>
										</button>
									</div>
									<div class="sys_msg" id="ra_doc_number_msg"></div>
								</div>
								<div class="col-md-8">
									<label class="form-label">Nombre</label>
									<input type="text" class="form-control" id="ra_name" name="p[name]">
									<div class="sys_msg" id="ra_name_msg"></div>
								</div>
								<div class="col-md-4">
									<label class="form-label">Teléfono</label>
									<input type="text" class="form-control" id="ra_tel" name="p[tel]">
									<div class="sys_msg" id="ra_tel_msg"></div>
								</div>
								<div class="col-md-12 pt-3"><strong>Usuario</strong></div>
								<div class="col-md-8">
									<label class="form-label">Usuario</label>
									<input type="email" class="form-control" id="ra_email" name="a[email]">
									<div class="sys_msg" id="ra_email_msg"></div>
								</div>
								<div class="col-md-4">
									<label class="form-label">Rol</label>
									<select class="form-select" name="a[role_id]">
										<option value="" selected>--</option>
										<?php foreach($roles as $item){ ?>
										<option value="<?= $item->id ?>"><?= $item->sp ?></option>
										<?php } ?>
									</select>
									<div class="sys_msg" id="ra_role_msg"></div>
								</div>
								<div class="col-md-6">
									<label class="form-label">Contraseña</label>
									<input type="password" class="form-control" name="a[password]">
									<div class="sys_msg" id="ra_password_msg"></div>
								</div>
								<div class="col-md-6">
									<label class="form-label">Confirmación</label>
									<input type="password" class="form-control" name="a[confirm]" placeholder="Repetir contraseña">
									<div class="sys_msg" id="ra_confirm_msg"></div>
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
							<h5 class="modal-title">Buscar Usuario</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<div class="row g-3">
								<input type="hidden" value="1" name="page">
								<div class="form-group col-md-12">
									<label class="form-label">Rol</label>
									<select class="form-select" id="sl_role_id" name="role_id">
										<option value="">Todos</option>
										<?php foreach($roles as $item){
											if ($item->id == $f_url["role_id"]) $s = "selected"; else $s = ""; ?>
										<option value="<?= $item->id ?>" <?= $s ?>><?= $item->sp ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="form-group col-md-12">
									<label class="form-label">Nombre</label>
									<input type="text" class="form-control" id="inp_person_name" name="person_name" value="<?= $f_url["person_name"] ?>" placeholder="Ingresar nombre de persona aquí">
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
	<div class="col-md-12">
		<div class="card pt-3">
			<div class="card-body">
				<?php if ($accounts){ ?>
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<th>#</th>
								<th>Rol</th>
								<th>Email</th>
								<th>Nombre</th>
								<th></th>
							</tr>
						</thead>
						<tbody id="account_list">
							<?php foreach($accounts as $i => $item){ ?>
							<tr>
								<th><?= number_format(($f_url["page"] - 1) * 25 + 1 + $i) ?></th>
								<td><?= $item->role ?></td>
								<td><?= $item->email ?></td>
								<td><?= $item->person ?></td>
								<td class="text-end">
									<button type="button" class="btn btn-primary btn-sm btn_reset_password" value="<?= $item->id ?>">
										<i class="bi bi-key"></i>
									</button>
									<button type="button" class="btn btn-danger btn-sm btn_remove_account" value="<?= $item->id ?>">
										<i class="bi bi-trash"></i>
									</button>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
					<div class="btn-group" role="group" aria-label="paging">
						<?php foreach($paging as $p){
						$f_url["page"] = $p[0]; ?>
						<a href="<?= base_url() ?>sys/account?<?= http_build_query($f_url) ?>" class="btn btn-<?= $p[2] ?>">
							<?= $p[1] ?>
						</a>
						<?php } ?>
					</div>
				</div>
				<?php }else{ ?>
				<div class="text-danger mt-3">No existe usuarios registrados o cumplan con datos de búsqueda.</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<script>
document.addEventListener("DOMContentLoaded", () => {
	
	$(".btn_reset_password").click(function() {
		ajax_simple_warning({id: $(this).val()}, "sys/account/reset_password", "¿Desea inicializar contraseña del usuario elegido?").done(function(res) {
			swal(res.type, res.msg);
		});
	});
	
	$(".btn_remove_account").click(function() {
		ajax_simple_warning({id: $(this).val()}, "sys/account/remove", "¿Desea inactivar usuario elegido?").done(function(res) {
			swal_redirection(res.type, res.msg, window.location.href);
		});
	});
	
	//register
	$("#form_register_account").submit(function(e) {
		e.preventDefault();
		ajax_form_warning(this, "sys/account/register", "¿Desea agregar nuevo usuario?").done(function(res) {
			set_msg(res.msgs);
			swal_redirection(res.type, res.msg, window.location.href);
		});
		
	});
	
	function reset_person(){
		$("#ra_name").val("");
		$("#ra_tel").val("");
	}
	
	$("#btn_search_person_ra").click(function() {
		var data = {doc_type_id: $("#ra_doc_type_id").val(), doc_number: $("#ra_doc_number").val()};
		ajax_simple(data, "ajax_f/search_person").done(function(res) {
			swal(res.type, res.msg);
			if (res.type == "success"){
				$("#ra_name").val(res.person.name);
				$("#ra_tel").val(res.person.tel);
			}else reset_person();
		});
	});
	
	$("#ra_doc_type_id").change(function() {
		reset_person();
	});
	
	$("#ra_doc_number").keyup(function() {
		reset_person();
	});
});
</script>