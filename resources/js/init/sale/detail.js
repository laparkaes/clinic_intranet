function nf(num){//number format
	return parseFloat(num).toLocaleString('es-US', {maximumFractionDigits: 2, minimumFractionDigits: 2});
}

function calculate_payment(e, type){
	if ((e.which == 13) || (e.which == 0)){
		var total = parseFloat($("#payment_total").val().replace(/,/g, ""));
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

function add_payment(dom){
	ajax_form_warning(dom, "sale/add_payment", $("#warning_apa").val()).done(function(res) {
		swal_redirection(res.type, res.msg, window.location.href);
	});
}

function delete_payment(dom){
	ajax_simple_warning({id: $(dom).val()}, "sale/delete_payment", $("#warning_dpa").val()).done(function(res) {
		swal_redirection(res.type, res.msg, window.location.href);
	});
}

function cancel_sale(dom){
	ajax_simple_warning({id: $(dom).val()}, "sale/cancel_sale", $("#warning_csa").val()).done(function(res) {
		swal_redirection(res.type, res.msg, window.location.href);
	});
}

function make_voucher(dom){
	ajax_form_warning(dom, "sale/make_voucher", $("#warning_mvo").val()).done(function(res) {
		set_msg(res.msgs);
		swal_redirection(res.type, res.msg, window.location.href);
	});
}

function control_client_name(activate){
	if (activate == true){
		$("#mv_name").val("");
		$("#mv_name").prop("readonly", false);
	}else $("#mv_name").prop("readonly", true);
}

function search_person_mv(){
	var data = {doc_type_id: $("#mv_doc_type").val(), doc_number: $("#mv_doc_number").val()};
	ajax_simple(data, "ajax_f/search_person").done(function(res) {
		swal(res.type, res.msg);
		if (res.type == "success"){
			$("#mv_name").val(res.person.name);
			$("#mv_name").attr("readonly", true);
			control_client_name(false);
		}else control_client_name(true);
	});
}

function control_doc_number(){
	$("#mv_doc_number, #mv_name").val("");
	if ($("#mv_doc_type").val() == 1){
		$("#mv_doc_number, #mv_name").prop("readonly", true);
		$("#btn_search_person_mv").prop("disabled", true);
	}else{
		$("#mv_doc_number, #mv_name").prop("readonly", false);
		$("#btn_search_person_mv").prop("disabled", false);
	}
}

function asign_reservation(attn, attn_id){
	var data = {id: $("#rs_selected_product").val(), attn_id: attn_id, field: attn};
	ajax_simple(data, "sale/asign_reservation").done(function(res) {
		swal_redirection(res.type, res.msg, window.location.href);
	});
}

function search_reservations(attn){
	var data = {doc_number: $("#rs_" + attn + "_doc_number").val(), attn: attn};
	ajax_simple(data, "sale/search_reservations").done(function(res) {
		$("#rs_" + attn + "_list").html("");
		if (res.type == "error") swal(res.type, res.msg);
		$.each(res.reservations, function(index, value) {
			$("#rs_" + attn + "_list").append('<tr><td><strong>' + (index + 1) + '</strong></td><td><div>' + value.schedule + '</div><div>' + value.pt_name + '</div><small>' + value.pt_doc + '</small></td><td class="text-right"><button type="button" class="btn btn-info btn-xxs btn_rs_select" value="' + value.id + '">' + $("#btn_select_lang").val() + '</button></td></tr>');
		});
		
		$(".btn_rs_select").on('click',(function(e) {asign_reservation(attn, $(this).val());}));
	});
}

function unassign_reservation(prod_id){
	var data = {id: prod_id};
	ajax_simple_warning(data, "sale/unassign_reservation", $("#warning_siu").val()).done(function(res) {
		swal_redirection(res.type, res.msg, window.location.href);
	});
}

$(document).ready(function() {
	//asign medical attention
	$(".btn_select_product").on('click',(function(e) { $("#rs_selected_product").val($(this).val()); }));
	$("#btn_search_surgery").on('click',(function(e) {search_reservations("surgery");}));
	$("#btn_search_appointment").on('click',(function(e) {search_reservations("appointment");}));
	$(".btn_unassign_reservation").on('click',(function(e) {unassign_reservation($(this).val());}));
	
	//voucher
	$("#form_make_voucher").submit(function(e) {e.preventDefault(); make_voucher(this);});
	$("#btn_make_voucher").on('click',(function(e) {$("#form_make_voucher").submit();}));
	$("#mv_doc_type").on('change',(function(e) {control_doc_number();}));
	$("#mv_doc_number").keyup(function() {control_client_name(true);});
	$("#btn_search_person_mv").on('click',(function(e) {search_person_mv();}));
	
	//sale
	$("#btn_cancel_sale").on('click',(function(e) {cancel_sale(this);}));
	
	//payment
	$("#form_add_payment").submit(function(e) {e.preventDefault(); add_payment(this);});
	$("#btn_add_payment").on('click',(function(e) {$("#form_add_payment").submit();}));
	$("#btn_delete_payment").on('click',(function(e) {delete_payment(this);}));
	$("#payment_received_v").keypress(function(e) {calculate_payment(e, "received");});
	$("#payment_received_v").focusout(function(e) {calculate_payment(e, "received");});
	$("#payment_change_v").keypress(function(e) {calculate_payment(e, "change");});
	$("#payment_change_v").focusout(function(e) {calculate_payment(e, "change");});
});