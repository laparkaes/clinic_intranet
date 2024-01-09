//function search_person_ns(){}

function load_items(category_id){
	$("#sl_pr_items").html('');
	$("#sl_pr_items").append('<option value="">--</option>');
	if (category_id == "") $(".sl_pr_detail").addClass("d-none");
	else{
		ajax_simple({category_id: category_id}, "product/load_by_category").done(function(res) {
			if (res.type == "success"){
				$.each(res.list, function(index, value) {
					$("#sl_pr_items").append("<option value='" + JSON.stringify(value) + "'>"+ value.type + "] " + value.description + "</option>");
				});
			}else swal(res.type, res.msg);
		});
		$(".sl_pr_detail").addClass("d-none");
	}
}

function reset_pr_sl_form(){
	$("#sl_pr_uprice_txt").val(nf(0));
	$("#sl_pr_udiscount").val(0);
	$("#sl_pr_udiscount_txt").val(nf(0));
	$("#sl_pr_subtotal").val("");
	$(".sl_pr_detail").addClass("d-none");
}

function select_item(){
	var item = $("#sl_pr_items").val();
	$("#sl_pr_quantity").val(1);
	if (item == "") reset_pr_sl_form();
	else{
		var item = jQuery.parseJSON(item);
		$("#sl_pr_uprice_txt").val(item.price_txt);
		$("#sl_pr_udiscount").val(0);
		$("#sl_pr_udiscount_txt").val(nf(0));
		$("#sl_pr_subtotal").val(item.currency + " " + item.price_txt);
		$(".sl_pr_detail").removeClass("d-none");
		
		//option load
		$("#sl_pr_options").html('<option value="">--</option>');
		if (item.type == "Producto"){
			ajax_simple({product_id: item.id}, "product/load_option").done(function(res) {
				if (res.type == "error") swal(res.type, res.msg);
				$.each(res.list, function(index, value) {
					$("#sl_pr_options").append("<option value='" + JSON.stringify(value) + "'>" + value.description + " (" + value.stock + ")</option>");
				});
				$("#sl_pr_options").prop("disabled", false);
				$("#sl_pr_quantity").prop("readonly", false);
			});
		}else{
			$("#sl_pr_options").prop("disabled", true);
			$("#sl_pr_quantity").prop("readonly", true);
		}
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

function calculate_subtotal(e){
	if (((e.which == 13) || (e.which == 0)) && (item != $("#sl_pr_items").val())){
		var item = jQuery.parseJSON($("#sl_pr_items").val());
		var qty = parseInt($("#sl_pr_quantity").val().replace(/,/g, ""));
		var discount = parseFloat($("#sl_pr_udiscount").val().replace(/,/g, ""));
		var price = item.price;
		
		if (isNaN(qty) || (qty < 1)) qty = 1;
		if (isNaN(discount) || (discount < 0 )) discount = 0;
		else if (discount > price) discount = price;
		
		var opt = $("#sl_pr_options").val();
		if (opt != ""){
			opt = jQuery.parseJSON(opt);
			if (qty > opt.stock) qty = opt.stock;
		}
		
		$("#sl_pr_subtotal").val(item.currency + " " + nf(qty * (price - discount)));
		$("#sl_pr_quantity").val(qty);
		$("#sl_pr_udiscount").val(discount);
	}
}

function set_sl_pr_num(){
	var rows = $(".sl_pr_num");
	$.each(rows, function(index, value){
		$(value).html("<strong>" + (index + 1) + "</strong>");
	});
}

function set_sl_pr_total(){
	var rows = $(".sl_pr_arr");
	var amount = 0;
	var prod;
	$.each(rows, function(index, value){
		prod = $.parseJSON($(value).val());
		amount += (prod.price - prod.discount) * prod.qty;
	});
	
	var cur = $("#op_currency").val();
	if (amount > 0) $("#sl_pr_total_amount").html(cur + " " + nf(amount));
	else{
		cur = "";
		$("#op_currency").val(cur);
		$("#sl_pr_total_amount").html("0.00");
	}
	
	$("#sale_total").val(amount);
	$("#payment_received").val(amount); $("#payment_received_v").val(nf(amount));
	$("#payment_change").val(0); $("#payment_change_v").val(nf(0));
	$("#payment_balance").val(0); $("#payment_balance_v").val(nf(0));
	
	$(".payment_currency").html(cur);
}

function sl_product_delete(row_id){
	$("#sl_pr_" + row_id).remove();
	set_sl_pr_num();
	set_sl_pr_total();
	control_payment_info_form();
}

function sl_product_add(){
	var item = $("#sl_pr_items").val();
	if (item == ""){ swal("error", $("#e_item_select").val()); return; }
	else item = jQuery.parseJSON(item);
	
	var op_currency = $("#op_currency").val();
	if (op_currency == "") $("#op_currency").val(item.currency);
	else if (op_currency != item.currency){ swal("error", $("#e_list_currency").val()); return; }
	
	var qty = parseInt($("#sl_pr_quantity").val().replace(/,/g, ""));
	var discount = parseFloat($("#sl_pr_udiscount").val().replace(/,/g, ""));
	var price = item.price;
	var opt_id = "";
	var opt_description = "";
	var opt = $("#sl_pr_options").val();
	
	if (opt == ""){ if (item.type == "Producto"){ swal("error", $("#e_item_option").val()); return; } }
	else{
		opt = jQuery.parseJSON(opt);
		opt_id = opt.id;
		opt_description = opt.description;
		
		if (qty > opt.stock){ swal("error", $("#e_item_stock").val()); return; }
	}
	
	if ($('#sl_pr_' + item.id + '_' + opt_id).length > 0) { swal("error", $("#e_list_duplicate").val()); return; }
	
	if (qty < 1) qty = 1;
	if (discount < 0 ) discount = 0;
	else if (discount > price) discount = price;
	
	var subtotal = (item.price - discount) * qty;
	var prod = {product_id: item.id, option_id: opt_id, price: item.price, discount: discount, qty: qty};
	var dom_str = '<tr id="sl_pr_' + item.id + '_' + opt_id + '"><td class="sl_pr_num"></td><td><div>' + item.description + '</div>';
	if (opt_description != "") dom_str += '<small>' + opt_description + '</small>';
	dom_str += '</td><td>' + qty + '</td><td>' + item.currency + ' ' + nf(price - discount) + '</td><td>' + item.currency + ' ' + nf((price - discount) * qty) + '</td><td class="text-end"><textarea class="sl_pr_arr d-none" name="sl_pr[' + item.id + '_' + opt_id + ']">' + JSON.stringify(prod) + '</textarea><button type="button" class="btn btn-danger btn-sm" id="btn_sl_pr_delete_' + item.id + '_' + opt_id + '" value="' + item.id + '_' + opt_id + '"><i class="bi bi-trash"></i></button></td></tr>';
	
	$("#tb_product_list").append(dom_str);
	control_payment_info_form();
	
	$("#sl_pr_items").val("");
	reset_pr_sl_form();
	set_sl_pr_num();
	set_sl_pr_total();
	$('#btn_sl_pr_delete_' + item.id + '_' + opt_id).on('click',(function(e) {sl_product_delete($(this).val());}));
	
	$("#sl_product_modal").modal("hide");	
			
}

function add_sale(dom){
	$("#form_sale .sys_msg").html("");
	ajax_form_warning(dom, "sale/add", "wm_sale_add").done(function(res) {
		set_msg(res.msgs);
		swal_redirection(res.type, res.msg, res.move_to);
	});
}

$(document).ready(function() {
	//general
	$(".control_bl").on('click',(function(e) {control_bl(this);}));
	$("#form_add_sale").submit(function(e) {e.preventDefault(); add_sale(this);});
	$("#btn_add_sale").on('click',(function(e) {$("#form_add_sale").submit();}));
	
	var params = get_params();
	if (params.a == "add") $("#btn_add").trigger("click");
	
	//new sale - select item
	
	//new sale - payment data
});