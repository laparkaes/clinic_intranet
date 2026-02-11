<div class="row">
	<div class="col">
		<div class="mb-3">
			<a href="<?= base_url() ?>commerce/sale" class="btn btn-primary">
				<i class="bi bi-arrow-left me-1"></i> Volver a la Lista
			</a>
		</div>
	</div>
</div>
<div class="row">
	<div class="col">
		<div class="card">			
			<div class="card-body">
				<div class="d-flex justify-content-between align-items-center">
					<h5 class="card-title">Agregar Producto</h5>
					<div>
						<form class="input-group" id="form_search_products">
							<select class="form-select" name="category_id" style="width: 250px;">
								<option value="">Categoría</option>
								<?php foreach($categories as $item){ ?>
								<option value="<?= $item->id ?>"><?= $item->name ?></option>
								<?php } ?>
							</select>
							<input type="text" class="form-control" name="description" placeholder="Descripción" style="width: 250px;">
							<button type="submit" class="btn btn-primary">
								<i class="bi bi-search"></i>
							</button>
						</form>
						
					</div>
				</div>
				<div class="text-center mt-3" id="search_msg">
					<h5>Busque producto para agregar a la lista de venta.</h5>
				</div>
				<div class="d-none text-center mt-3" id="no_result">
					<h5 class="text-danger">No hay resultado de busqueda.</h5>
				</div>
				<div class="table-responsive d-none" id="result_table" style="max-height: 400px;">
					<table class="table align-middle">
						<thead>
							<tr>
								<th scope="col">#</th>
								<th scope="col">Categoría</th>
								<th scope="col">Ítem</th>
								<th scope="col">P/U</th>
								<th scope="col">Stock</th>
								<th scope="col"></th>
							</tr>
						</thead>
						<tbody id="tb_search_product">
						</tbody>
					</table>
				</div>
				
				<div class="modal fade" id="add_product" tabindex="-1" style="display: none;" aria-hidden="true">
					<div class="modal-dialog">
						<form class="modal-content" id="form_set_product_detail">
							<div class="modal-header">
								<h5 class="modal-title">Detalle de Venta</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
							</div>
							<div class="modal-body">
								<div class="row g-3">
									<div class="col-md-12">
										<label class="form-label">Producto</label>
										<input type="text" class="form-control" id="product" readonly>
										<input type="text" class="form-control d-none" id="product_id" name="product_id">
									</div>
									<div class="col-md-6">
										<label class="form-label">Opción</label>
										<select class="form-select" id="option_id" name="option_id">
											<option value="">--</option>
										</select>
									</div>
									<div class="col-md-6">
										<label class="form-label">P/U</label>
										<div class="input-group">
											<span class="input-group-text currency"></span>
											<input type="text" class="form-control text-end" id="price_txt">
										</div>
										<input type="text" class="form-control d-none" id="price" name="price">
									</div>
									<div class="col-md-6">
										<label class="form-label">Descuento (Unidad)</label>
										<div class="input-group">
											<span class="input-group-text currency"></span>
											<input type="text" class="form-control text-end" id="discount" name="discount">
										</div>
									</div>
									<div class="col-md-6">
										<label class="form-label">Cantidad</label>
										<input type="number" class="form-control" id="quantity" name="qty" value="1">
									</div>
									<div class="col-md-12">
										<label class="form-label">Subtotal</label>
										<div class="input-group">
											<span class="input-group-text currency"></span>
											<input type="text" class="form-control text-end" id="subtotal_txt" value="0.00">
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="submit" class="btn btn-primary">Agregar</button>
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
							</div>
						</form>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<form class="row" id="form_add_sale">
	<div class="col-md-6">
		<div class="card">
			<div class="card-body">
				<h5 class="card-title">Lista de Productos</h5>
				<div class="alert alert-primary alert-dismissible fade show text-center my-3" role="alert">
					<strong>Total: </strong>
					<strong id="sl_pr_total_amount">0.00</strong>
				</div>
				<div class="table-responsive">
					<table class="table" id="tb_products">
						<thead>
							<tr>
								<th>#</th>
								<th>Ítem</th>
								<th></th>
								<th class="text-end">Subtotal</th>
								<th></th>
							</tr>
						</thead>
						<tbody id="tb_product_list"></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="card">
			<div class="card-body">
				<h5 class="card-title">Datos de Pago</h5>
				<div class="row g-3">
					<div class="col-md-6">
						<label class="form-label">Tipo de Venta</label>
						<select class="form-select" name="sale_type_id">
							<?php foreach($sale_types as $item){ ?>
							<option value="<?= $item->id ?>"><?= $item->sunat_serie." - ".$item->description ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="col-md-6">
						<label class="form-label">Forma de Pago</label>
						<select class="form-select" name="payment[payment_method_id]">
							<?php foreach($pay_methods as $item){ ?>
							<option value="<?= $item->id ?>"><?= $item->description ?></option>
							<?php } ?>
						</select>
						<div class="sys_msg" id="payment_method_msg"></div>
					</div>
		
					<div class="col-md-6">
						<label class="form-label">Documento</label>
						<select class="form-select" id="client_doc_type" name="client[doc_type_id]">
							<?php foreach($doc_types as $item){ ?>
							<option value="<?= $item->id ?>"><?= $item->description ?></option>
							<?php } ?>
						</select>
						<div class="sys_msg" id="client_doc_type_msg"></div>
					</div>
					<div class="col-md-6">
						<label class="form-label">Número</label>
						<div class="input-group">
							<input type="text" class="form-control" id="client_doc_number" name="client[doc_number]">
							<button class="btn btn-primary" type="button" id="btn_search_client">
								<i class="bi bi-search"></i>
							</button>
						</div>
						<div class="sys_msg" id="client_doc_number_msg"></div>
					</div>
					<div class="col-md-12">
						<label class="form-label">Nombre de Cliente</label>
						<input type="text" class="form-control" id="client_name" name="client[name]">
						<div class="sys_msg" id="client_name_msg"></div>
					</div>
					<div class="col-md-4">
						<label class="form-label">Recibido</label>
						<div class="input-group">
							<span class="input-group-text payment_currency"></span>
							<input type="text" class="form-control text-end" id="payment_received_v">
						</div>
						<div class="sys_msg" id="pay_received_msg"></div>
					</div>
					<div class="col-md-4">
						<label class="form-label">Vuelto</label>
						<div class="input-group">
							<span class="input-group-text payment_currency"></span>
							<input type="text" class="form-control text-end" id="payment_change_v" value="0.00">
						</div>
						<div class="sys_msg" id="pay_change_msg"></div>
					</div>
					<div class="col-md-4">
						<label class="form-label">Saldo Pendiente</label>
						<div class="input-group">
							<span class="input-group-text payment_currency"></span>
							<input type="text" class="form-control text-end" id="payment_balance_v" value="0.00">
						</div>
						<div class="sys_msg" id="pay_balance_msg"></div>
					</div>
					<div class="col-md-12 pt-3">
						<button disabled="disabled" id="btn_register_sale_in_form_sale" type="submit" class="btn btn-primary">
							Generar Venta
						</button>
					</div>
				</div>
			</div>
		</div>
		<div class="d-none">
			<input type="hidden" id="sale_total">
			<input type="hidden" id="op_currency" name="currency" value="">
			<input type="hidden" id="payment_received" name="payment[received]">
			<input type="hidden" id="payment_change" name="payment[change]">
			<input type="hidden" id="payment_balance" name="payment[balance]">
		</div>
	</div>
</form>

<script>
document.addEventListener("DOMContentLoaded", () => {
	
	//step - set sale information
	function set_total(){
		var total = 0;
		var prod;
		$.each($("#tb_product_list tr"), function(index, value){
			$(value).find(".num").html(index + 1);
			prod = JSON.parse($(value).find(".prod_data").val());
			total += (prod.price - prod.discount) * prod.qty;
		});
		
		$("#sale_total").val(total);
		$("#sl_pr_total_amount").html($("#op_currency").val() + " " + nf(total));
	}

	function control_doc_number(){
		$("#client_doc_number, #client_name").val("");
		if ($("#client_doc_type").val() == 1){
			$("#client_doc_number, #client_name").prop("readonly", true);
			$("#btn_search_client").prop("disabled", true);
		}else{
			$("#client_doc_number, #client_name").prop("readonly", false);
			$("#btn_search_client").prop("disabled", false);
		}
	}
	
	function calculate_payment(e, type){
		if ((e.which == 13) || (e.which == 0)){
			var total = $("#sale_total").val();
			if (total == ""){ swal("error", $("#e_item_select_least").val()); return; }
			else total = parseFloat(total);
			
			var received = parseFloat($("#payment_received_v").val().replace(/,/g, ""));
			var change = parseFloat($("#payment_change_v").val().replace(/,/g, ""));
			var balance = parseFloat($("#payment_balance_v").val().replace(/,/g, ""));
			
			if (isNaN(change) || (change < 0)) change = 0;
			else if (change > received) change = received;
			
			if (isNaN(received) || (received <= 0)) received = total;
			
			if (type == "received"){
				if (received > total){
					change = received - total;
					balance = 0;
				}else{
					change = 0;
					balance = total - received;
				}
			}else{//type = "change"
				if (received > total){
					var min_change = received - total;
					if (change < min_change){
						change = min_change;
						balance = 0;
					}
				}
				balance = total - received + change;
			}
			
			//set payment data
			$("#payment_received").val(received);
			$("#payment_change").val(change);
			$("#payment_balance").val(balance);
			
			//set payment view
			$("#payment_received_v").val(nf(received));
			$("#payment_change_v").val(nf(change));
			$("#payment_balance_v").val(nf(balance));

			var buttonSaleSubmit = $("#btn_register_sale_in_form_sale");
			buttonSaleSubmit.prop("disabled", balance > 0);
		}
	}
	
	control_doc_number();
	
	$("#form_add_sale").submit(function(e) {
		e.preventDefault();
		$("#form_sale .sys_msg").html("");
		ajax_form_warning(this, "commerce/sale/add", "¿Desea generar nueva venta?").done(function(res) {
			set_msg(res.msgs);
			swal_redirection(res.type, res.msg, res.move_to);
		});
	});
	
	$("#form_add_sale #client_doc_type").change(function() {
		control_doc_number();
	});
	
	$("#form_add_sale #btn_search_client").click(function() {
		var data = {doc_type_id: $("#client_doc_type").val(), doc_number: $("#client_doc_number").val()};
		ajax_simple(data, "ajax_f/search_person").done(function(res) {
			swal(res.type, res.msg);
			if (res.type == "success") $("#client_name").val(res.person.name);
		});
	});
	
	$("#form_add_sale #payment_received_v").keypress(function(e) {
		calculate_payment(e, "received");
	}).focusout(function(e) {
		calculate_payment(e, "received");
	});
	
	$("#form_add_sale #payment_change_v").keypress(function(e) {
		calculate_payment(e, "change");
	}).focusout(function(e) {
		calculate_payment(e, "change");
	});	
	
	//step - select product
	var selected_product;
	var row_num = 0;
	
	$("#form_search_products").submit(function(e) {
		e.preventDefault();
		ajax_form(this, "commerce/product/search_product").done(function(res) {
			$("#tb_search_product").html("");
			
			if (res.products.length > 0){
				$("#no_result").addClass("d-none");
				$("#search_msg").addClass("d-none"); 
				$("#result_table").removeClass("d-none"); 
				
				$.each(res.products, function(index, value){
					$("#tb_search_product").append('<tr><th scope="row" style="width: 70px;">' + (index + 1) + '</th><td>' + value.category + '</td><td>' + value.description + '</td><td class="text-nowrap">' + value.price_txt + '</td><td class="text-nowrap">' + value.stock_txt + '</td><td class="text-end"><button class="btn btn-primary btn-sm btn_select_product" value="' + value.id + '" data-bs-toggle="modal" data-bs-target="#add_product"><i class="bi bi-plus-lg"></i></button></td></tr>');
				});
				
				$(".btn_select_product").click(function() {
					
					ajax_simple({id: $(this).val()}, "commerce/product/search_product").done(function(res) {
						if (res.products.length > 0){
							var prod = res.products[0];
							$(".currency").html(prod.currency);
							$("#product_id").val(prod.id);
							$("#product").val(prod.description);
							$("#price_txt").val(prod.price_txt);
							$("#price").val(prod.price);
							$("#discount").val(0);
							$("#quantity").val(1);
							$("#subtotal_txt").val(prod.price_txt);
							
							//setting options
							$("#option_id").html("");
							$("#option_id").append('<option value="">--</option>');
							$.each(prod.options, function(index, value){
								$("#option_id").append('<option value="' + value.id + '">' + value.description + ' (' + value.stock + ')</option>');
							});
							
							if ($("#op_currency").val() == ""){
								$("#op_currency").val(prod.currency);
								$(".payment_currency").html(prod.currency);
							}
							selected_product = prod;
						}
					});
				});
			}else{
				$("#no_result").removeClass("d-none");
				$("#search_msg").addClass("d-none"); 
				$("#result_table").addClass("d-none"); 
			}
			
		});
	});
	
	//step - product_detail
	$("#form_set_product_detail").submit(function(e) {
		e.preventDefault();
		var data = form_to_object("form_set_product_detail");
		
		console.log("here1");
		
		//check stock
		if (selected_product.type == "Producto"){
			if (data.option_id == ""){
				swal("error", 'Debe elegir una opción del producto.');
				return;
			}else{
				var stock_ok = false;
				
				$.each(selected_product.options, function(index, value){
					if (value.id == data.option_id) 
						if (parseInt(value.stock) >= parseInt(data.qty)){
							stock_ok = true;
							data.option_description = value.description;
							
							//modal close
							$('#add_product').modal('hide');
							swal("success", 'Producto ha sido agregado a la lista de venta.');
							
							//set search msg
							$("#no_result").addClass("d-none");
							$("#search_msg").removeClass("d-none"); 
							$("#result_table").addClass("d-none"); 
						}
				});
				
				if (!stock_ok){
					swal("error", 'No hay stock disponible de la opción elegida.');
					return;
				}
			}
		}else{
			data.option_description = '-';
			
			//modal close
			$('#add_product').modal('hide');
			swal("success", 'Producto ha sido agregado a la lista de venta.');
			
			//set search msg
			$("#no_result").addClass("d-none");
			$("#search_msg").removeClass("d-none"); 
			$("#result_table").addClass("d-none"); 
		}
		
		console.log(data);
		
		if ($("#op_currency").val() == selected_product.currency){
			$("#tb_product_list").append('<tr id="row_' + row_num + '"><td class="num"></td><td style="width: 350px;">' + selected_product.description + '<br/>' + data.option_description + '</td><td>' + data.qty + ' * ' + selected_product.currency + ' ' + nf(data.price - data.discount) + '</td><td class="text-end">' + selected_product.currency + ' ' + nf((data.price - data.discount) * data.qty) + '</td><td class="text-end"><button type="button" class="btn btn-danger btn-sm" id="btn_remove_product_' + row_num + '" value="' + row_num + '"><i class="bi bi-trash"></i></button><textarea class="prod_data d-none" name="sl_pr[' + row_num + ']">' + JSON.stringify(data) + '</textarea></td></tr>');
			
			$("#btn_remove_product_" + row_num).click(function() {
				$("#row_" + $(this).val()).remove();
				set_total();
				
				if ($("#tb_product_list tr").length < 1){
					$("#op_currency").val("");
					$(".payment_currency").html("");
				}
			});
			
			set_total();
			row_num++;
		}else{
			swal("error", msg_list[default_lang].e_list_currency);
			return;
		}
	});
	
	$("#form_set_product_detail #quantity").change(function() {
		if (parseInt($(this).val()) <= 0) $(this).val(1);
		$("#subtotal_txt").val(nf((selected_product.price - $("#discount").val()) * $(this).val()));
	}).keyup(function() {
		if (parseInt($(this).val()) <= 0) $(this).val(1);
		$("#subtotal_txt").val(nf((selected_product.price - $("#discount").val()) * $(this).val()));
	});
	
	$("#form_set_product_detail #discount").keyup(function() {
		if (parseFloat($(this).val()) > selected_product.price) $(this).val(selected_product.price);
		else if (parseFloat($(this).val()) < 0) $(this).val(0);
		$("#subtotal_txt").val(nf((selected_product.price - $(this).val()) * $("#quantity").val()));
	});
});
</script>