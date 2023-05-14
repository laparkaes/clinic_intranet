function nf(num){//number format
	return parseFloat(num).toLocaleString('es-US', {maximumFractionDigits: 2, minimumFractionDigits: 2});
}

function set_sale_process(){
	$("#sale_process").smartWizard({keyboard: {keyNavigation: false}, anchor: {anchorClickable: false}});
	$('#sale_process').smartWizard("goToStep", 0, true);
	$("#sale_process .sw-toolbar-elm").addClass("d-none");
	$("#sale_process .nav-link").unbind();
	$("#sale_process .nav-link").css("cursor","initial");
	$(".btn_prev").on('click',(function(e) {$('#sale_process').smartWizard("prev");}));
}

function make_voucher(dom){
	Swal.fire({
		title: $("#alert_warning_title").val(),
		html: $(dom).find(".msg").html(),
		icon: 'warning',
		showCancelButton: true,
		confirmButtonText: $("#alert_confirm_btn").val(),
		cancelButtonText: $("#alert_cancel_btn").val()
	}).then((result) => {
		if (result.isConfirmed){
			$.ajax({
				url: $("#base_url").val() + "payment/generate_invoice",
				type: "POST",
				data: {payment_id: $(dom).val()},
				success:function(res){
					if (res.status == true) window.open($("#base_url").val() + "payment/make_voucher/" + $(dom).val(), '_blank');
					else Swal.fire({
						title: $("#alert_error_title").val(),
						icon: "error",
						html: res.msg,
						confirmButtonText: $("#alert_confirm_btn").val()
					});
				}
			});
		}
	});
}

function set_amount(){
	var currency = $("#op_currency").val(); var rows = $(".row_pr");
	var prod; var qty; var i_subtotal; var i_discount; var i_amount; var i_vat; //usted for each item
	var total = 0; var discount = 0; var amount = 0; var vat = 0; //usted for resume
	
	if (rows.length > 0){
		$(".payment_currency").html(currency);
		rows.each(function(){
			//product data load
			prod = JSON.parse($(this).find(".pr_data").html());
			i_discount = parseFloat($(this).find(".discount").val());
			
			//discount validation
			if (isNaN(i_discount)){
				i_discount = 0;
				$(this).find(".discount").val("");
			}else if (i_discount < 0){
				i_discount = 0;
				$(this).find(".discount").val("");
			}if (i_discount > prod.price){
				i_discount = prod.price;
				$(this).find(".discount").val(prod.price);
			}
			
			//qty validation
			qty = parseInt($(this).find(".pr_qty").val());
			if (isNaN(qty) || qty < 1) qty = 1;//min qty = 1
			if (qty > 9999) qty = 9999;//max qty = 9999
			if ((prod.stock > 0) && (qty > prod.stock)) qty = prod.stock;
			qty = Math.trunc(qty); $(this).find(".pr_qty").val(qty);//no decimal
			
			//calculate subtotal and put to td_subtotal
			i_subtotal = parseFloat((prod.price - i_discount) * qty);
			i_amount = parseFloat((i_subtotal / 1.18).toFixed(2));
			i_vat = i_subtotal - i_amount;
			
			//set subtotal in row
			$(this).find(".td_subtotal").html(currency + " " + nf(i_subtotal));
			
			//resume processing
			total += i_subtotal;
			discount += i_discount;
			amount += i_amount;
			vat += i_vat;
		});
		
	}else $("#op_currency").val("");
	
	//set sales data
	$("#sale_total").val(total);
	$("#sale_discount").val(discount);
	$("#sale_amount").val(amount);
	$("#sale_vat").val(vat);
	
	//set payment data
	$("#payment_received").val(total);
	$("#payment_change, #payment_balance").val(0);
	
	//set sale view
	$("#pay_total").html(currency + " " + nf(total));
	$("#pay_amount").html(currency + " " + nf(amount));
	$("#pay_vat").html(currency + " " + nf(vat));
	
	//set payment view
	$("#payment_total_v, #payment_received_v").val(nf(total));
	$("#payment_change_v, #payment_balance_v").val("0.00");
}

function remove_row(id){
	$("#row_pr_" + id).remove();
	set_amount();
	set_row_num();
}

var row_count = 0;

function set_row_num(){
	var i = 1;
	var rows = $(".row_pr");
	if (rows.length > 0){
		rows.each(function(){
			$(this).find(".td_num").html(i);
			i++;
		});
	}else $("#bl_payment_data").addClass("d-none");
}

function add_product_row(id){
	$.ajax({
		url: $("#base_url").val() + "sale/load_product",
		type: "POST",
		data: {id: id},
		success:function(res){
			if (res.status == true){
				row_count++;
				$("#sale_product_list").append('<tr class="row_pr" id="row_pr_' + row_count + '"><td class="td_num"></td><td><input type="hidden" name="products[' + row_count + '][product_id]" value="' + res.prod.id + '">' + res.prod.description + '<div><small>' + res.prod.currency + " " + res.prod.price_txt + '</small></div><div class="pr_data d-none">' + JSON.stringify(res.prod) + '</div></td><td><select id="op_' + row_count + '" class="form-control" name="products[' + row_count + '][option_id]"><option value="">--</option></select></td><td><input type="number" step="1" class="form-control pr_qty" value="1" name="products[' + row_count + '][qty]"></td><td><input type="text" class="form-control text-right discount" style="max-width: 80px;" name="products[' + row_count + '][discount]" value="0"></td><td class="text-nowrap td_subtotal">' + res.prod.currency + " " + res.prod.price_txt + '</td><td class="text-right"><button type="button" class="btn tp-btn-light btn-danger sharp border-0 btn_delete" value="' + row_count + '"><i class="fas fa-trash"></i></button></td></tr>');
				
				if (res.prod.type == "Producto"){
					res.options.forEach(function (element) {
						$("#op_" + row_count).append('<option value="' + element.id + '">' + element.description + ' (' + element.stock + ')</option>');
					});
				}else $("#op_" + row_count).remove();
			}else swal("error", res.msg);
			
			set_amount();
			set_row_num();
			$("#pr_search").val("");
			$("#bl_payment_data").removeClass("d-none");
	
			$("#row_pr_" + row_count + " .pr_qty").keyup(function() {set_amount();});
			$("#row_pr_" + row_count + " .pr_qty").change(function() {set_amount();});
			$("#row_pr_" + row_count + " .discount").keyup(function() {set_amount();});
			$("#row_pr_" + row_count + " .btn_delete").on('click',(function(e) {remove_row($(this).val());}));
		}
	});
}

function set_product_list(products){
	$("#pr_search").autocomplete({
		minLength: 0,
		source: function(request, response) {
			var list = products;
			var results = $.ui.autocomplete.filter(list, request.term);
			if (results.length > 0){
				var more_result = false;
				if (results.length > 20) more_result = true;
				
				result = results.slice(0, 20);
				if (more_result == true) result.push({label: "...", value: ""});
				
				response(result);
			}else response([]);
		},
		select: function(event, ui){
			if (ui.item.label != "..."){
				var op_currency = $("#op_currency").val();
				if (op_currency.length == 0 || op_currency == ui.item.currency){
					$("#op_currency").val(ui.item.currency);
					add_product_row(ui.item.value);
				}else Swal.fire({
					title: $("#alert_error_title").val(),
					icon: "error",
					html: $("#error_prlc").val(),
					confirmButtonText: $("#alert_confirm_btn").val()
				});
			}
			
			return false;
		}
	}).on('click',(function(e) {
		$(this).autocomplete('search', $(this).val());
	}));
}

/*
function load_product_list(){
	$.ajax({
		url: $("#base_url").val() + "sale/load_product_list",
		type: "POST",
		success:function(res){
			set_product_list(res);
		}
	});
}
*/

function load_items(category_id){
	ajax_simple({category_id: category_id}, "product/load_by_category").done(function(res) {
		if (res.type == "success"){
			console.log(res.list);
		}else swal(res.type, res.msg);
		
	});
}

function confirm_items(dom){
	if ($(".row_pr").length > 0) $('#sale_process').smartWizard("next");//pass to next step
	else Swal.fire({
		title: $("#alert_error_title").val(),
		icon: "error",
		html: $("#error_prsl").val(),
		confirmButtonText: $("#alert_confirm_btn").val()
	});
}

function calculate_payment(e, type){
	if ((e.which == 13) || (e.which == 0)){
		var total = parseFloat($("#sale_total").val());
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

function control_client_name(activate){
	if (activate == true){
		$("#client_name").val("");
		$("#client_name").removeClass("bg-light");
		$("#client_name").prop("readonly", false);
	}else{
		$("#client_name").addClass("bg-light");
		$("#client_name").prop("readonly", true);
	}
}

function search_person_ns(){
	$.ajax({
		url: $("#base_url").val() + "ajax_f/search_person",
		type: "POST",
		data: {doc_type_id: $("#client_doc_type").val(), doc_number: $("#client_doc_number").val()},
		success:function(res){
			Swal.fire({
				title: $("#alert_" + res.type + "_title").val(),
				icon: res.type,
				html: res.msg,
				confirmButtonText: $("#alert_confirm_btn").val()
			}).then((result) => {
				if (res.status == true){
					$("#client_name").val(res.person.name);
					$("#client_name").addClass("bg-light");
					$("#client_name").attr("readonly", true);
					control_client_name(false);
					load_reservations(res.person.id);
				}else control_client_name(true);
			});
		}
	});
}

function add_sale(dom){
	$("#form_sale .sys_msg").html("");
	Swal.fire({
		title: $("#alert_warning_title").val(),
		text: $("#warning_asa").val(),
		icon: 'warning',
		showCancelButton: true,
		confirmButtonText: $("#alert_confirm_btn").val(),
		cancelButtonText: $("#alert_cancel_btn").val()
	}).then((result) => {
		if (result.isConfirmed){
			$.ajax({
				url: $("#base_url").val() + "sale/add",
				type: "POST",
				data: new FormData(dom),
				contentType: false,
				processData:false,
				success:function(res){
					set_msg(res.msgs);
					Swal.fire({
						title: $("#alert_" + res.type + "_title").val(),
						text: res.msg,
						icon: res.type,
						confirmButtonText: $("#alert_confirm_btn").val()
					}).then((result) => {
						if (res.status == true) location.href = res.move_to;
					});
				}
			});
		}
	});
}

function control_doc_number(){
	if ($("#client_doc_type").val() == 1){
		$("#client_doc_number, #client_name").addClass("bg-light").prop("readonly", true);
		$("#btn_search_client").prop("disabled", true);
	}else{
		$("#client_doc_number, #client_name").removeClass("bg-light").prop("readonly", false);
		$("#btn_search_client").prop("disabled", false);
	}
}

function load_reservations(person_id){
	$.ajax({
		url: $("#base_url").val() + "sale/load_reservations",
		type: "POST",
		data: {person_id: person_id},
		success:function(res){
			$("#app_select, #sur_select").html('<option value="">--</option>');
			
			res.appointments.forEach(function(element) {
				console.log(element);
				$("#app_select").append('<option value="' + element.id + '">' + element.op + '</option>');
			});	
			
			res.surgeries.forEach(function(element) {
				console.log(element);
				$("#sur_select").append('<option value="' + element.id + '">' + element.op + '</option>');
			});	
		}
	});
}

$(document).ready(function() {
	//general
	$(".control_bl").on('click',(function(e) {control_bl(this);}));
	$("#btn_register_sale").on('click',(function(e) {$("#form_sale").submit();}));
	
	//new sale - select item
	$("#sl_pr_category").on('change',(function(e) {load_items($(this).val());}));
	
	/*
	load_product_list();
	$(".discount").on('change',(function(e) {set_amount();}));
	$("#btn_confirm_items").on('click',(function(e) {confirm_items();}));
	*/
	
	//new sale - payment data
	$("#form_sale").submit(function(e) {e.preventDefault(); add_sale(this);});
	$("#btn_search_client").on('click',(function(e) {search_person_ns();}));
	$("#payment_received_v").keypress(function(e) {calculate_payment(e, "received");});
	$("#payment_received_v").focusout(function(e) {calculate_payment(e, "received");});
	$("#payment_change_v").keypress(function(e) {calculate_payment(e, "change");});
	$("#payment_change_v").focusout(function(e) {calculate_payment(e, "change");});
	$("#client_doc_type").on('change',(function(e) {control_doc_number();}));
	$("#client_doc_number").keyup(function() {control_client_name(true);});
	control_doc_number();
});