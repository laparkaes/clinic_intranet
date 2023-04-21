function update_company_data(dom){
	$("#form_update_company .sys_msg").html("");
	$.ajax({
		url: $("#base_url").val() + "config/update_company_data",
		type: "POST",
		data: new FormData(dom),
		contentType: false,
		processData:false,
		success:function(res){
			Swal.fire({
				title: $("#alert_" + res.type + "_title").val(),
				icon: res.type,
				html: res.msg,
				confirmButtonText: $("#alert_confirm_btn").val()
			}).then((result) => {
				if (res.status == true) location.reload();
				else set_msg(res.msgs);
			});
		}
	});
}

function control_province(){
	$("#sl_province").val("");
	$("#sl_province .province").addClass("d-none");
	$("#sl_province .d" + $(this).val()).removeClass("d-none");
	$("#sl_district").val("");
	$("#sl_district .district").addClass("d-none");
}

function control_district(){
	$("#sl_district").val("");
	$("#sl_district .district").addClass("d-none");
	$("#sl_district .p" + $(this).val()).removeClass("d-none");
}

function control_role_access(dom){
	$.ajax({
		url: $("#base_url").val() + "config/control_role_access",
		type: "POST",
		data: {setting: $(dom).is(':checked'), value: $(dom).val()}
	});
}

function remove_account(dom){
	Swal.fire({
		title: $("#alert_warning_title").val(),
		html: $("#warning_rac").val(),
		icon: 'warning',
		showCancelButton: true,
		confirmButtonText: $("#alert_confirm_btn").val(),
		cancelButtonText: $("#alert_cancel_btn").val()
	}).then((result) => {
		if (result.isConfirmed){
			$.ajax({
				url: $("#base_url").val() + "config/remove_account",
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

function register_account(dom){
	$("#form_register_account .sys_msg").html("");
	$.ajax({
		url: $("#base_url").val() + "config/register_account",
		type: "POST",
		data: new FormData(dom),
		contentType: false,
		processData:false,
		success:function(res){
			set_msg(res.msgs);
			if (res.msg != null) Swal.fire({
				title: $("#alert_" + res.type + "_title").val(),
				text: res.msg,
				icon: res.type,
				confirmButtonText: $("#alert_confirm_btn").val()
			}).then((result) => {
				if (res.status == true) location.reload();
			});
		}
	});
}

function search_person_ra(){
	$.ajax({
		url: $("#base_url").val() + "ajax_f/search_person",
		type: "POST",
		data: {doc_type_id: $("#ra_doc_type_id").val(), doc_number: $("#ra_doc_number").val()},
		success:function(res){
			Swal.fire({
				title: $("#alert_" + res.type + "_title").val(),
				icon: res.type,
				html: res.msg,
				confirmButtonText: $("#alert_confirm_btn").val()
			}).then((result) => {
				if (res.status == true){
					$("#ra_name").val(res.person.name);
					$("#ra_tel").val(res.person.tel);
					$("#ra_email").val(res.person.email);
					$("#ra_name").addClass("bg-light");
					$("#ra_name").attr("readonly", true);
				}else{
					$("#ra_name").removeClass("bg-light").attr("readonly", false);
				}
			});
		}
	});
}

function control_bl_account(dom){
	$(dom).parent().children().removeClass("btn-primary");
	$(dom).parent().children().addClass("btn-outline-primary");
	
	$(dom).removeClass("btn-outline-primary");
	$(dom).addClass("btn-primary");
	
	$(".bl_account").addClass("d-none");
	$("#" + $(dom).val()).removeClass("d-none");
}

function control_sl_group(dom){
	$(".sl_group").removeClass("active");
	$(dom).addClass("active");
	
	$(".sl_values").addClass("d-none");
	$("#sl_" + $(dom).val()).removeClass("d-none");
	
	$("#sl_add_code").val($(dom).val());
}

function add_sl_value(dom){
	$.ajax({
		url: $("#base_url").val() + "config/add_sl_value",
		type: "POST",
		data: new FormData(dom),
		contentType: false,
		processData:false,
		success:function(res){
			Swal.fire({
				title: $("#alert_" + res.type + "_title").val(),
				icon: res.type,
				html: res.msg,
				confirmButtonText: $("#alert_confirm_btn").val()
			}).then((result) => {
				if (res.status == true){
					$("#asl_description").val("");
					$("#sl_" + res.new_value.code).append('<button type="button" class="btn btn-outline-primary btn-xs mb-1 btn_sl_remove" value="' + res.new_value.id + '">' + res.new_value.description + ' <i class="fa fa-close ml-1"></i></button>&nbsp;');
					$("#sl_" + res.new_value.code + " .btn_sl_remove").last().on('click',(function(e) {remove_sl_value(this);}));
					$("#btn_sl_" + res.new_value.code).find(".badge").html($("#sl_" + res.new_value.code).find("button").length);
				}
			});
		}
	});
}

function remove_sl_value(dom){
	$.ajax({
		url: $("#base_url").val() + "config/remove_sl_value",
		type: "POST",
		data: {id: $(dom).val()},
		success:function(res){
			Swal.fire({
				title: $("#alert_" + res.type + "_title").val(),
				icon: res.type,
				html: res.msg,
				confirmButtonText: $("#alert_confirm_btn").val()
			}).then((result) => {
				if (res.status == true){
					$(dom).remove();
					$("#btn_sl_" + res.removed_value.code).find(".badge").html($("#sl_" + res.removed_value.code).find("button").length);
				}
			});
		}
	});
}

$(document).ready(function() {
	//general
	
	//account role
	$("#form_register_account").submit(function(e) {e.preventDefault(); register_account(this);});
	$("#btn_search_person_ra").on('click',(function(e) {search_person_ra();}));
	$(".remove_account").on('click',(function(e) {remove_account(this);}));
	$(".control_bl_account").on('click',(function(e) {control_bl_account(this);}));
	set_datatable("account_list", 25, false);
	
	//role & access
	$(".chk_access").on('click',(function(e) {control_role_access(this);}));
	
	//company
	$("#form_update_company_data").submit(function(e) {e.preventDefault(); update_company_data(this);});
	$("#sl_department").change(function() {control_province();});
	$("#sl_province").change(function() {control_district();});
	
	//system
	$("#form_add_sl_value").submit(function(e) {e.preventDefault(); add_sl_value(this);});
	$(".sl_group").on('click',(function(e) {control_sl_group(this);}));
	$(".btn_sl_remove").on('click',(function(e) {remove_sl_value(this);}));
});