<div class="row">
	<div class="col">
		<div class="mb-3">
			<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_category">
				<i class="bi bi-diagram-3 me-1"></i> Categoría
			</button>
			<div class="modal fade" id="modal_category" tabindex="-1">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Gestión de Categoría</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<div class="row g-3">
								<div class="col-md-12">
									<strong>Agregar nueva categoría</strong>
								</div>
								<div class="col-md-12">
									<form class="input-group" id="form_add_category">
										<input type="text" class="form-control" name="name" placeholder="<?= $this->lang->line('w_category_name') ?>">
										<button class="btn btn-primary" type="submit">
											<i class="bi bi-plus"></i> Agregar
										</button>
									</form>
								</div>
								<div class="col-md-12 pt-3">
									<strong>Mover productos</strong>
								</div>
								<div class="col-md-12">
									<form class="row g-3" id="form_move_product">
										<div class="form-group col-md-6">
											<label class="form-label">De</label>
											<select class="form-select sl_category" name="mp_id_from">
												<option value="">-</option>
												<?php foreach($categories as $item){ ?>
												<option value="<?= $item->id ?>"><?= $item->name ?></option>
												<?php } ?>
											</select>
											<div class="sys_msg" id="mp_id_from_msg"></div>
										</div>
										<div class="form-group col-md-6">
											<label class="form-label">A</label>
											<select class="form-select sl_category" name="mp_id_to">
												<option value="">-</option>
												<?php foreach($categories as $item){ ?>
												<option value="<?= $item->id ?>"><?= $item->name ?></option>
												<?php } ?>
											</select>
											<div class="sys_msg" id="mp_id_to_msg"></div>
										</div>
										<div class="col-md-12">
											<button type="submit" class="btn btn-primary">Confirmar</button>
											<div class="sys_msg" id="mp_result_msg"></div>
										</div>
									</form>
								</div>
								<div class="col-md-12 pt-3">
									<strong>Lista de categorías</strong>
								</div>
								<div class="col-md-12">
									<table class="table">
										<thead>
											<tr>
												<th>#</th>
												<th>Categoría</th>
												<th>Productos</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											<?php $i = 1; foreach($categories as $c){ ?>
											<tr>
												<td><?= number_format($i) ?></td>
												<td>
													<div id="ct_name_<?= $c->id ?>" class="ct_name"><?= $c->name ?></div>
													<form id="form_update_category_<?= $c->id ?>" class="form_update_category d-none">
														<input type="hidden" name="id" value="<?= $c->id ?>">
														<div class="input-group input-group-sm">
															<input type="text" class="form-control" name="name" value="<?= $c->name ?>">
															<button class="btn btn-success" type="submit">
																<i class="bi bi-check"></i>
															</button>
															<button class="btn btn-danger btn_cancel_edit_ct" type="button">
																<i class="bi bi-x"></i>
															</button>
														</div>
													</form>
												</td>
												<td><?= number_format($c->prod_qty) ?></td>
												<td class="text-end">
													<button class="btn btn-success btn-sm btn_edit_ct" value="<?= $c->id ?>">
														<i class="bi bi-pencil"></i>
													</button>
													<button class="btn btn-danger btn-sm btn_delete_ct" value="<?= $c->id ?>">
														<i class="bi bi-trash"></i>
													</button>
												</td>
											</tr>
											<?php $i++;} ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
						</div>
					</div>
				</div>
			</div>
		
			<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add">
				<i class="bi bi-plus-lg me-1"></i> Agregar
			</button>
			<div class="modal fade" id="modal_add" tabindex="-1">
				<div class="modal-dialog">
					<form class="modal-content" id="form_register_product">
						<div class="modal-header">
							<h5 class="modal-title">Agregar Producto</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<div class="row g-3">
								<div class="col-md-12">
									<label class="form-label">Categoría</label>
									<select class="form-select sl_category" name="category_id">
										<option value="">-</option>
										<?php foreach($categories as $item){ ?>
										<option value="<?= $item->id ?>"><?= $item->name ?></option>
										<?php } ?>
									</select>
									<div class="sys_msg" id="ap_category_msg"></div>
								</div>
								<div class="col-md-12">
									<label class="form-label">Nombre</label>
									<input type="text" class="form-control" name="description">
									<div class="sys_msg" id="ap_description_msg"></div>
								</div>
								<div class="col-md-6">
									<label class="form-label">Tipo</label>
									<select class="form-select" name="type_id">
										<?php foreach($prod_types as $item){ ?>
										<option value="<?= $item->id ?>"><?= $item->description ?></option>
										<?php } ?>
									</select>
									<div class="sys_msg" id="ap_type_msg"></div>
								</div>
								<div class="col-md-6">
									<label class="form-label">Código</label>
									<input type="text" class="form-control" name="code">
									<div class="sys_msg" id="ap_code_msg"></div>
								</div>
								<div class="col-md-12">
									<label class="form-label">Precio</label>
									<div class="input-group">
										<select class="form-select w-10" name="currency_id" style="max-width: 70px;">
											<?php foreach($currencies as $item){ ?>
											<option value="<?= $item->id ?>"><?= $item->description ?></option>
											<?php } ?>
										</select>
										<input type="text" class="form-control" name="price">
									</div>
									<div class="sys_msg" id="ap_price_msg"></div>
								</div>
								<div class="col-md-12">
									<label class="form-label">Imagen (Opcional)</label>
									<input type="file" class="form-control" id="ap_image" name="image" accept="image/*">
									<div class="sys_msg" id="ap_image_msg"></div>
								</div>
								<div class="col-md-12 text-center">
									<img src="<?= base_url() ?>uploaded/products/no_img.png" id="img_preview" style="max-width: 100%;">
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
							<h5 class="modal-title">Buscar Producto</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<div class="row g-3">
								<div class="col-md-12">
									<label class="form-label">Categoría</label>
									<select class="form-select sl_category" name="category_id">
										<option value="">Todos</option>
										<?php foreach($categories as $item){ ?>
										<option value="<?= $item->id ?>" <?= $f_url["category_id"] == $item->id ? "selected" : "" ?>><?= $item->name ?></option>
										<?php } ?>
									</select>
									<div class="sys_msg" id="ap_category_msg"></div>
								</div>
								<div class="col-md-12">
									<label class="form-label">Nombre</label>
									<input type="text" class="form-control" name="description" value="<?= $f_url["description"] ?>">
									<div class="sys_msg" id="ap_description_msg"></div>
								</div>
								<div class="col-md-6">
									<label class="form-label">Tipo</label>
									<select class="form-select" name="type_id">
										<option value="">Todos</option>
										<?php foreach($prod_types as $item){ ?>
										<option value="<?= $item->id ?>" <?= $f_url["type_id"] == $item->id ? "selected" : "" ?>><?= $item->description ?></option>
										<?php } ?>
									</select>
									<div class="sys_msg" id="ap_type_msg"></div>
								</div>
								<div class="col-md-6">
									<label class="form-label">Código</label>
									<input type="text" class="form-control" name="code" value="<?= $f_url["code"] ?>">
									<div class="sys_msg" id="ap_code_msg"></div>
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
				<?php if ($products){ ?>
				<div class="table-responsive">
					<table class="table align-middle">
						<thead>
							<tr>
								<th>#</th>
								<th>Imagen</th>
								<th>Tipo</th>
								<th>Categoría</th>
								<th>Código</th>
								<th>Producto</th>
								<th>Precio</th>
								<th>Stock</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php 
							$no_img_path = "uploaded/products/no_img.png";
							
							foreach($products as $i => $item){ ?>
							<tr>
								<td><strong><?= number_format(($f_url["page"] - 1) * 25 + 1 + $i) ?></strong></td>
								<td>
									<?php if ($item->image){
										$prod_img_path = "uploaded/products/".$item->id."/".$item->image;
										if (file_exists($prod_img_path)) $img_path = $prod_img_path;
										else $img_path = $no_img_path;
									}else $img_path = $no_img_path; ?>
									<img src="<?= base_url().$img_path ?>" style="max-width: 60px; max-height: 60px;" />
								</td>
								<td><?= $prod_types_arr[$item->type_id]->description ?></td>
								<td><?= $categories_arr[$item->category_id] ?></td>
								<td><?= $item->code ?></td>
								<td><?= $item->description ?></td>
								<td><?= $currencies_arr[$item->currency_id]->description." ".number_format($item->price, 2) ?></td>
								<td><?php if ($item->stock) echo number_format($item->stock); else echo "-"; ?></td>
								<td class="text-end">
									<a href="<?= base_url() ?>commerce/product/detail/<?= $item->id ?>" class="btn btn-primary btn-sm">
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
						<a href="<?= base_url() ?>commerce/product?<?= http_build_query($f_url) ?>" class="btn btn-<?= $p[2] ?>">
							<?= $p[1] ?>
						</a>
						<?php } ?>
					</div>
				</div>
				<?php }else{ ?>
				<h5 class="text-danger">No existe productos registrados o cumplan con datos de búsqueda.</h5>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<script>
document.addEventListener("DOMContentLoaded", () => {
	function cancel_edit_category(){
		$(".ct_name").removeClass("d-none");
		$(".form_update_category").addClass("d-none");
	}
	
	//general
	$(".control_bl").click(function() {
		control_bl(this);
	});
	
	//category
	$("#form_add_category").submit(function(e) {
		e.preventDefault();
		ajax_form_warning(this, "commerce/product/add_category", "¿Desea agregar nueva categoría?").done(function(res) {
			swal_redirection(res.type, res.msg, window.location.href);
		});
	});
	
	$(".form_update_category").submit(function(e) {
		e.preventDefault();
		ajax_form_warning(this, "commerce/product/update_category", "¿Desea actualizar categoría?").done(function(res) {
			swal_redirection(res.type, res.msg, window.location.href);
		});
	});
	
	$("#form_move_product").submit(function(e) {
		e.preventDefault();
		$("#form_move_product .sys_msg").html("");
		ajax_form_warning(this, "commerce/product/move_product", "¿Desea mover todos los productos de categoría?").done(function(res) {
			set_msg(res.msgs);
			swal_redirection(res.type, res.msg, window.location.href);
		});
	});
	
	$(".btn_edit_ct").click(function() {
		var ct_id = $(this).val();
		cancel_edit_category();
		$("#ct_name_" + ct_id).addClass("d-none");
		$("#form_update_category_" + ct_id).removeClass("d-none");
		$("#form_update_category_" + ct_id + " input[name=name]").val($("#ct_name_" + ct_id).html());
	});
	
	$(".btn_cancel_edit_ct").click(function() {
		cancel_edit_category();
	});
	
	$(".btn_delete_ct").click(function() {
		ajax_simple_warning({id: $(this).val()}, "commerce/product/delete_category", "¿Desea eliminar categoría?").done(function(res) {
			swal_redirection(res.type, res.msg, window.location.href);
		});
	});
	
	//product
	$("#form_register_product").submit(function(e) {
		e.preventDefault();
		$("#form_register_product .sys_msg").html("");
		ajax_form_warning(this, "commerce/product/register", "¿Desea registrar nuevo producto?").done(function(res) {
			set_msg(res.msgs);
			swal_redirection(res.type, res.msg, res.move_to);
		});
	});
	
	$("#ap_image").change(function() {
		set_img_preview(this, "img_preview");
	});
});
</script>