<?php
$this->lang->load("sale", "spanish");
$categories = $this->general->all("product_category", "name", "asc");
$doc_types = $this->general->all("doc_type", "sunat_code", "asc");
$sale_types = $this->general->all("sale_type", "sunat_serie", "asc");
?>
<div class="card-body add_sale_step" id="step_set_sale_information">
	<h5 class="card-title"><?= $this->lang->line('w_new_sale') ?></h5>
	<form class="row g-3 no_enter" id="form_add_sale">
		<input type="hidden" id="sale_total">
		<input type="hidden" id="op_currency" name="currency" value="">
		<input type="hidden" id="payment_received" name="payment[received]">
		<input type="hidden" id="payment_change" name="payment[change]">
		<input type="hidden" id="payment_balance" name="payment[balance]">
		<div class="col-md-12">
			<div class="table-responsive">
				<table class="table" id="tb_products">
					<thead>
						<tr>
							<th>#</th>
							<th><?= $this->lang->line('w_item') ?></th>
							<th><?= $this->lang->line('w_qty') ?></th>
							<th><?= $this->lang->line('w_unit_price_short') ?></th>
							<th><?= $this->lang->line('w_subtotal') ?></th>
							<th class="text-end">
								<button type="button" class="btn btn-primary btn-sm" id="btn_search_product">
									<i class="bi bi-plus-lg"></i>
								</button>
							</th>
						</tr>
					</thead>
					<tbody id="tb_product_list"></tbody>
				</table>
			</div>
		</div>
		<div class="col-md-12">
			<div class="alert alert-primary alert-dismissible fade show text-center my-3" role="alert">
				<strong><?= $this->lang->line('w_total') ?>: </strong>
				<strong id="sl_pr_total_amount">0.00</strong>
			</div>
		</div>
		<div class="col-md-6 payment_info d-none">
			<div class="row g-3">
				<div class="col-md-12">
					<label class="form-label"><?= $this->lang->line('w_sale_type') ?></label>
					<input type="file" class="form-control">
				</div>
				<div class="col-md-6">
					<label class="form-label"><?= $this->lang->line('w_received') ?></label>
					<div class="input-group">
						<span class="input-group-text payment_currency"></span>
						<input type="text" class="form-control text-end" id="payment_received_v">
					</div>
					<div class="sys_msg" id="pay_received_msg"></div>
				</div>
				<div class="col-md-6">
					<label class="form-label"><?= $this->lang->line('w_change') ?></label>
					<div class="input-group">
						<span class="input-group-text payment_currency"></span>
						<input type="text" class="form-control text-end" id="payment_change_v" value="0.00">
					</div>
					<div class="sys_msg" id="pay_change_msg"></div>
				</div>
				<div class="col-md-6">
					<label class="form-label"><?= $this->lang->line('w_balance') ?></label>
					<div class="input-group">
						<span class="input-group-text payment_currency"></span>
						<input type="text" class="form-control text-end" id="payment_balance_v" value="0.00" readonly>
					</div>
					<div class="sys_msg" id="pay_balance_msg"></div>
				</div>
			</div>
		</div>
		<div class="col-md-6 payment_info d-none">
			<div class="row g-3">
				<div class="col-md-12">
					<label class="form-label"><?= $this->lang->line('w_document') ?></label>
					<select class="form-select" id="client_doc_type" name="client[doc_type_id]">
						<?php foreach($doc_types as $item){ ?>
						<option value="<?= $item->id ?>"><?= $item->description ?></option>
						<?php } ?>
					</select>
					<div class="sys_msg" id="client_doc_type_msg"></div>
				</div>
				<div class="col-md-12">
					<label class="form-label">Numero</label>
					<div class="input-group">
						<input type="text" class="form-control" id="client_doc_number" name="client[doc_number]" placeholder="<?= $this->lang->line('w_number') ?>">
						<button class="btn btn-primary" type="button" id="btn_search_client">
							<i class="bi bi-search"></i>
						</button>
					</div>
					<div class="sys_msg" id="client_doc_number_msg"></div>
				</div>
				<div class="col-md-12">
					<label class="form-label"><?= $this->lang->line('w_name') ?></label>
					<input type="text" class="form-control" id="client_name" name="client[name]">
					<div class="sys_msg" id="client_name_msg"></div>
				</div>
			</div>
		</div>
		<div class="col-md-12 payment_info d-none pt-3">
			<button type="submit" class="btn btn-primary">
				<?= $this->lang->line('btn_register_sale') ?>
			</button>
		</div>
	</form>
</div>
<div class="card-body add_sale_step d-none" id="step_search_product">
	<h5 class="card-title"><?= $this->lang->line('w_add_product') ?></h5>
	<form class="row justify-content-end g-3" id="form_search_products">
		<div class="col-md-auto col-12">
			<select class="form-select" name="category_id">
				<option value=""><?= $this->lang->line('w_category') ?></option>
				<?php foreach($categories as $item){ ?>
				<option value="<?= $item->id ?>"><?= $item->name ?></option>
				<?php } ?>
			</select>
		</div>
		<div class="col-md-auto col-12">
			<input type="text" class="form-control" name="description" placeholder="Descripcion">
		</div>
		<div class="col-md-auto col-12 pt-md-0 pt-3">
			<button type="submit" class="btn btn-primary">Buscar</button>
			<button type="button" class="btn btn-secondary" id="btn_back_to_sale_information">Volver</button>
		</div>
	</form>
	<div class="table-responsive mt-3">
		<table class="table align-middle">
			<thead>
				<tr>
					<th scope="col">#</th>
					<th scope="col"><?= $this->lang->line('w_category') ?></th>
					<th scope="col"><?= $this->lang->line('w_item') ?></th>
					<th scope="col"><?= $this->lang->line('w_unit_price_short') ?></th>
					<th scope="col"><?= $this->lang->line('w_stock') ?></th>
					<th scope="col"></th>
				</tr>
			</thead>
			<tbody id="tb_search_product">
			</tbody>
		</table>
	</div>
</div>
<div class="card-body add_sale_step d-none" id="step_set_product_detail">
	<h5 class="card-title"><?= $this->lang->line('w_product_detail') ?></h5>
	<form class="row g-3 no_enter" id="form_set_product_detail">
		<div class="col-md-12">
			<label class="form-label"><?= $this->lang->line('w_product') ?></label>
			<input type="text" class="form-control" id="product" readonly>
			<input type="text" class="form-control d-none" id="product_id" name="product_id" readonly>
		</div>
		<div class="col-md-3">
			<label class="form-label"><?= $this->lang->line('w_option') ?></label>
			<select class="form-select" id="option_id" name="option_id">
				<option value="">--</option>
			</select>
		</div>
		<div class="col-md-3">
			<label class="form-label"><?= $this->lang->line('w_unit_price_short') ?></label>
			<div class="input-group">
				<span class="input-group-text currency"></span>
				<input type="text" class="form-control" id="price" name="price">
			</div>
		</div>
		<div class="col-md-3">
			<label class="form-label"><?= $this->lang->line('w_quantity') ?></label>
			<input type="number" class="form-control" id="quantity" name="qty" value="1">
		</div>
		<div class="col-md-3">
			<label class="form-label"><?= $this->lang->line('w_subtotal') ?></label>
			<div class="input-group">
				<span class="input-group-text currency"></span>
				<input type="text" class="form-control text-end" id="subtotal_txt" value="0.00" readonly>
			</div>
		</div>
		<div class="col-md-12 pt-3">
			<button type="submit" class="btn btn-primary" id="btn_add_product_to_list">
				<?= $this->lang->line('btn_add') ?>
			</button>
			<button type="button" class="btn btn-secondary" id="btn_back_to_search">
				<?= $this->lang->line('btn_back') ?>
			</button>
		</div>
	</form>
</div>
<script>
document.addEventListener("DOMContentLoaded", () => {
	function set_step(dom_id){
		$(".add_sale_step").addClass("d-none");
		$("#" + dom_id).removeClass("d-none");
	}
	
	$("#btn_search_product").click(function() {
		set_step("step_search_product");
	});
	
	$("#btn_back_to_sale_information").click(function() {
		set_step("step_set_sale_information");
	});
	
	$("#btn_back_to_search").click(function() {
		set_step("step_search_product");
	});
	
	//step - set sale information
	function set_total(){
		var total = 0;
		var prod;
		$.each($("#tb_product_list tr"), function(index, value){
			$(value).find(".num").html(index + 1);
			prod = JSON.parse($(value).find(".prod_data").val());
			total += prod.price * prod.qty;
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
		}
	}
	
	control_doc_number();
	
	$("#form_add_sale").submit(function(e) {
		e.preventDefault();
		$("#form_sale .sys_msg").html("");
		ajax_form_warning(this, "commerce/sale/add", "wm_sale_add").done(function(res) {
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
			$.each(res.products, function(index, value){
				$("#tb_search_product").append('<tr><th scope="row" style="width: 70px;">' + (index + 1) + '</th><td>' + value.category + '</td><td>' + value.description + '</td><td class="text-nowrap">' + value.price_txt + '</td><td class="text-nowrap">' + value.stock_txt + '</td><td class="text-end"><button class="btn btn-success btn-sm btn_select_product" value="' + value.id + '">Elegir</button></td></tr>');
			});
			
			$(".btn_select_product").click(function() {
				set_step("step_set_product_detail");
				ajax_simple({id: $(this).val()}, "commerce/product/search_product").done(function(res) {
					if (res.products.length > 0){
						var prod = res.products[0];
						$(".currency").html(prod.currency);
						$("#product_id").val(prod.id);
						$("#product").val(prod.description);
						$("#price").val(0);
						$("#quantity").val(1);
						$("#subtotal_txt").val(0);
						
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
		});
	});
	
	//step - product_detail
	$("#form_set_product_detail").submit(function(e) {
		e.preventDefault();
		var data = form_to_object("form_set_product_detail");
		
		//check stock
		if (selected_product.type == "Producto"){
			if (data.option_id == ""){
				swal("error", msg_list[default_lang].e_item_option);
				return;
			}else{
				var stock_ok = false;
				$.each(selected_product.options, function(index, value){
					if (value.id == data.option_id) 
						if (parseInt(value.stock) >= parseInt(data.qty)) 
							stock_ok = true;
				});
				
				if (!stock_ok){
					swal("error", msg_list[default_lang].e_item_no_stock);
					return;
				}
			}
		}
		
		if ($("#op_currency").val() == selected_product.currency){
			$("#tb_product_list").append('<tr id="row_' + row_num + '"><td class="num"></td><td>' + selected_product.description + '</td><td>' + data.qty + '</td><td>' + selected_product.currency + ' ' + nf(data.price) + '</td><td>' + selected_product.currency + ' ' + nf(data.price * data.qty) + '</td><td class="text-end"><button type="button" class="btn btn-danger btn-sm" id="btn_remove_product_' + row_num + '" value="' + row_num + '"><i class="bi bi-trash"></i></button><textarea class="prod_data d-none" name="sl_pr[' + row_num + ']">' + JSON.stringify(data) + '</textarea></td></tr>');
			
			$("#btn_remove_product_" + row_num).click(function() {
				$("#row_" + $(this).val()).remove();
				set_total();
				
				if ($("#tb_product_list tr").length < 1){
					$("#step_set_sale_information .payment_info").addClass("d-none");
					$("#op_currency").val("");
					$(".payment_currency").html("");
				}
			});
			
			$("#step_set_sale_information .payment_info").removeClass("d-none");
			
			set_total();
			set_step("step_set_sale_information");
			row_num++;
		}else{
			swal("error", msg_list[default_lang].e_list_currency);
			return;
		}
	});
	
	$("#form_set_product_detail #quantity, #form_set_product_detail #price").change(function() {
		if (parseInt($(this).val()) <= 0) $(this).val(1);
		$("#subtotal_txt").val(nf($("#price").val() * $(this).val()));
	}).keyup(function() {
		if (parseInt($(this).val()) <= 0) $(this).val(1);
		$("#subtotal_txt").val(nf($("#price").val() * $(this).val()));
	});
});
</script>