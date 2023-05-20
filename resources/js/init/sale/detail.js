function nf(num){//number format
	return parseFloat(num).toLocaleString('es-US', {maximumFractionDigits: 2, minimumFractionDigits: 2});
}

function calculate_payment(e, type){
	if ((e.which == 13) || (e.which == 0)){
		var total = parseFloat($("#payment_total_v").val().replace(/,/g, ""));
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
	Swal.fire({
		title: $("#alert_warning_title").val(),
		text: $("#warning_apa").val(),
		icon: 'warning',
		showCancelButton: true,
		confirmButtonText: $("#alert_confirm_btn").val(),
		cancelButtonText: $("#alert_cancel_btn").val()
	}).then((result) => {
		if (result.isConfirmed){
			$.ajax({
				url: $("#base_url").val() + "sale/add_payment",
				type: "POST",
				data: new FormData(dom),
				contentType: false,
				processData:false,
				success:function(res){
					Swal.fire({
						title: $("#alert_" + res.type + "_title").val(),
						icon: res.type,
						text: res.msg,
						confirmButtonText: $("#alert_confirm_btn").val()
					}).then((result) => {
						if (res.status == true) location.reload();
					});		
				}
			});
		}
	});
}

function delete_payment(dom){
	Swal.fire({
		title: $("#alert_warning_title").val(),
		text: $("#warning_dpa").val(),
		icon: 'warning',
		showCancelButton: true,
		confirmButtonText: $("#alert_confirm_btn").val(),
		cancelButtonText: $("#alert_cancel_btn").val()
	}).then((result) => {
		if (result.isConfirmed){
			$.ajax({
				url: $("#base_url").val() + "sale/delete_payment",
				type: "POST",
				data: {id: $(dom).val()},
				success:function(res){
					Swal.fire({
						title: $("#alert_" + res.type + "_title").val(),
						icon: res.type,
						text: res.msg,
						confirmButtonText: $("#alert_confirm_btn").val()
					}).then((result) => {
						if (res.status == true) location.reload();
					});
				}
			});
		}
	});
}

function cancel_sale(dom){
	Swal.fire({
		title: $("#alert_warning_title").val(),
		text: $("#warning_csa").val(),
		icon: 'warning',
		showCancelButton: true,
		confirmButtonText: $("#alert_confirm_btn").val(),
		cancelButtonText: $("#alert_cancel_btn").val()
	}).then((result) => {
		if (result.isConfirmed){
			$.ajax({
				url: $("#base_url").val() + "sale/cancel_sale",
				type: "POST",
				data: {id: $(dom).val()},
				success:function(res){
					Swal.fire({
						title: $("#alert_" + res.type + "_title").val(),
						icon: res.type,
						html: res.msg,
						confirmButtonText: $("#alert_confirm_btn").val()
					}).then((result) => {
						if (res.status == true) location.reload();
					});
				}
			});
		}
	});
}

function make_ticket(dom){
	Swal.fire({
		title: $("#alert_warning_title").val(),
		text: $("#warning_mti").val(),
		icon: 'warning',
		showCancelButton: true,
		confirmButtonText: $("#alert_confirm_btn").val(),
		cancelButtonText: $("#alert_cancel_btn").val()
	}).then((result) => {
		if (result.isConfirmed) window.open($("#base_url").val() + "sale/ticket/" + $(dom).val(), '_blank');
	});
}

function make_voucher(dom){
	Swal.fire({
		title: $("#alert_warning_title").val(),
		text: $("#warning_mvo").val(),
		icon: 'warning',
		showCancelButton: true,
		confirmButtonText: $("#alert_confirm_btn").val(),
		cancelButtonText: $("#alert_cancel_btn").val()
	}).then((result) => {
		if (result.isConfirmed){
			$.ajax({
				url: $("#base_url").val() + "sale/make_voucher",
				type: "POST",
				data: new FormData(dom),
				contentType: false,
				processData:false,
				success:function(res){
					set_msg(res.msgs);
					Swal.fire({
						title: $("#alert_" + res.type + "_title").val(),
						html: res.msg,
						icon: res.type,
						confirmButtonText: $("#alert_confirm_btn").val()
					}).then((result) => {
						if (res.status == true) location.reload();
					});
				}
			});
		}
	});
}

function set_company_info(dom){
	if ("Factura" == $(dom).find("option:selected").text()){
		$("#company_info").removeClass("d-none");
		$("#client_info").addClass("d-none");
	}else{
		$("#company_info").addClass("d-none");
		$("#client_info").removeClass("d-none");
	}
}

function search_company_mv(){
	$.ajax({
		url: $("#base_url").val() + "ajax_f/search_person",
		type: "POST",
		data: {doc_type_id: $("#company_doc_type").val(), doc_number: $("#company_doc_number").val()},
		success:function(res){
			Swal.fire({
				title: $("#alert_" + res.type + "_title").val(),
				icon: res.type,
				html: res.msg,
				confirmButtonText: $("#alert_confirm_btn").val()
			}).then((result) => {
				if (res.status == true){
					$("#company_name").val(res.person.name);
					$("#company_name").addClass("bg-light");
					$("#company_name").attr("readonly", true);
				}else{
					$("#company_name").val("");
					$("#company_name").removeClass("bg-light");
					$("#company_name").attr("readonly", false);
				}
			});
		}
	});
}

function asign_reservation(attn, attn_id){
	var data = {id: $("#rs_selected_product").val(), attn_id: attn_id, field: attn};
	ajax_simple(data, "sale/asign_reservation").done(function(res) {
		swal_redirection(res.type, res.msg, window.location.href);
	});
}

function search_reservations(attn){
	var data = {doc_number: $("#rs_sur_doc_number").val(), attn: attn};
	ajax_simple(data, "sale/search_reservations").done(function(res) {
		$("#rs_list").html("");
		if (res.type == "error") swal(res.type, res.msg);
		$.each(res.reservations, function(index, value) {
			$("#rs_list").append('<tr><td><strong>' + (index + 1) + '</strong></td><td><div>' + value.schedule + '</div><div>' + value.pt_name + '</div><small>' + value.pt_doc + '</small></td><td class="text-right"><button type="button" class="btn btn-info btn-xxs btn_rs_select" value="' + value.id + '">' + $("#btn_select_lang").val() + '</button></td></tr>');
		});
		
		$(".btn_rs_select").on('click',(function(e) {asign_reservation(attn, $(this).val());}));
	});
}

$(document).ready(function() {
	//$("#btn_add_payment").on('click',(function(e) {make_voucher(this);}));
	
	//asign medical attention
	$(".btn_select_product").on('click',(function(e) { $("#rs_selected_product").val($(this).val()); }));
	$("#btn_search_surgery").on('click',(function(e) {search_reservations("surgery");}));
	
	
	//voucher
	$("#form_make_voucher").submit(function(e) {e.preventDefault(); make_voucher(this);});
	$("#btn_make_voucher").on('click',(function(e) {$("#form_make_voucher").submit();}));
	$("#voucher_type").on('change',(function(e) {set_company_info(this);}));
	$("#company_ruc").keyup(function() {$("#company_name").val("");});
	$("#btn_search_company").on('click',(function(e) {search_company_mv();}));
	
	//sale
	$("#btn_cancel_sale").on('click',(function(e) {cancel_sale(this);}));
	$("#btn_make_ticket").on('click',(function(e) {make_ticket(this);}));
	
	//payment
	$("#form_payment").submit(function(e) {e.preventDefault(); add_payment(this);});
	$("#btn_add_payment").on('click',(function(e) {$("#form_payment").submit();}));
	$("#btn_delete_payment").on('click',(function(e) {delete_payment(this);}));
	$("#payment_received_v").keypress(function(e) {calculate_payment(e, "received");});
	$("#payment_received_v").focusout(function(e) {calculate_payment(e, "received");});
	$("#payment_change_v").keypress(function(e) {calculate_payment(e, "change");});
	$("#payment_change_v").focusout(function(e) {calculate_payment(e, "change");});
});