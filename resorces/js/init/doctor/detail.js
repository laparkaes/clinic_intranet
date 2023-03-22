function update_personal_data(dom){
	$("#form_update_personal_data .sys_msg").html("");
	$.ajax({
		url: $("#base_url").val() + "doctor/update_personal_data",
		type: "POST",
		data: new FormData(dom),
		contentType: false,
		processData:false,
		success:function(res){
			set_msg(res.msgs);
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

function update_profession(dom){
	$("#form_update_profession .sys_msg").html("");
	$.ajax({
		url: $("#base_url").val() + "doctor/update_profession",
		type: "POST",
		data: new FormData(dom),
		contentType: false,
		processData:false,
		success:function(res){
			set_msg(res.msgs);
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

function update_account_email(dom){
	$("#form_update_account_email .sys_msg").html("");
	$.ajax({
		url: $("#base_url").val() + "doctor/update_account_email",
		type: "POST",
		data: new FormData(dom),
		contentType: false,
		processData:false,
		success:function(res){
			set_msg(res.msgs);
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

function update_account_password(dom){
	$("#form_update_account_password .sys_msg").html("");
	$.ajax({
		url: $("#base_url").val() + "doctor/update_account_password",
		type: "POST",
		data: new FormData(dom),
		contentType: false,
		processData:false,
		success:function(res){
			set_msg(res.msgs);
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

function activation_control(dom, active){
	var id = $(dom).val();
	var msg;
	
	if (active == true) msg = $("#warning_ado").val();
	else msg = $("#warning_ddo").val();
	
	Swal.fire({
		title: $("#alert_warning_title").val(),
		icon: 'warning',
		html: msg,
		showCancelButton: true,
		confirmButtonText: $("#alert_confirm_btn").val(),
		cancelButtonText: $("#alert_cancel_btn").val()
	}).then((result) => {
		if (result.isConfirmed) {
			$.ajax({
				url: $("#base_url").val() + "doctor/activation_control",
				type: "POST",
				data: {id: $(dom).val(), active: active},
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

function aa_search_patient(){
	$.ajax({
		url: $("#base_url").val() + "ajax_f/search_person",
		type: "POST",
		data: {doc_type_id: $("#aa_pt_doc_type_id").val(), doc_number: $("#aa_pt_doc_number").val()},
		success:function(res){
			Swal.fire({
				title: $("#alert_" + res.type + "_title").val(),
				icon: res.type,
				html: res.msg,
				confirmButtonText: $("#alert_confirm_btn").val()
			}).then((result) => {
				if (res.status == true){
					$("#aa_pt_id").val(res.person.id);
					$("#aa_pt_tel").val(res.person.tel);
					$("#aa_pt_name").val(res.person.name);
					$("#aa_pt_name").addClass("bg-light");
					$("#aa_pt_name").attr("readonly", true);
				}else{
					$("#aa_pt_name").removeClass("bg-light");
					$("#aa_pt_name").attr("readonly", false);
				}
			});
		}
	});
}

function aa_load_doctor_schedule(){
	load_doctor_schedule($("#aa_doctor_id").val(), $("#aa_date").val(), "aa_schedule_list");
}

function app_register(dom){
	$("#app_register_form .sys_msg").html("");
	$.ajax({
		url: $("#base_url").val() + "appointment/register",
		type: "POST",
		data: new FormData(dom),
		contentType: false,
		processData:false,
		success:function(res){
			set_msg(res.msgs);
			if (res.msg != null){
				Swal.fire({
					title: $("#alert_" + res.type + "_title").val(),
					icon: res.type,
					html: res.msg,
					confirmButtonText: $("#alert_confirm_btn").val()
				}).then((result) => {
					if (res.status == true) location.reload();
				});
			}
		}
	});
}

function sur_load_doctor_schedule(){
	load_doctor_schedule($("#sur_doctor").val(), $("#sur_date").val(), "sur_schedule_list");
}

function sur_register(dom){
	$("#register_form .sys_msg").html("");
	$.ajax({
		url: $("#base_url").val() + "surgery/register",
		type: "POST",
		data: new FormData(dom),
		contentType: false,
		processData:false,
		success:function(res){
			set_msg(res.msgs);
			if (res.msg != null){
				Swal.fire({
					title: $("#alert_" + res.type + "_title").val(),
					icon: res.type,
					html: res.msg,
					confirmButtonText: $("#alert_confirm_btn").val()
				}).then((result) => {
					if (res.status == true) location.reload();
				});
			}
		}
	});
}

function sur_search_patient(){
	$.ajax({
		url: $("#base_url").val() + "ajax_f/search_person",
		type: "POST",
		data: {doc_type_id: $("#sur_pt_doc_type_id").val(), doc_number: $("#sur_pt_doc_number").val()},
		success:function(res){
			Swal.fire({
				title: $("#alert_" + res.type + "_title").val(),
				icon: res.type,
				html: res.msg,
				confirmButtonText: $("#alert_confirm_btn").val()
			}).then((result) => {
				if (res.status == true){
					$("#sur_pt_id").val(res.person.id);
					$("#sur_pt_name").val(res.person.name);
					$("#sur_pt_name").addClass("bg-light");
					$("#sur_pt_name").attr("readonly", true);
					$("#sur_pt_tel").val(res.person.tel);
				}else{$("#sur_pt_name").removeClass("bg-light").attr("readonly", false);}
			});
		}
	});
}

$(document).ready(function() {
	set_datatable("appointment_list", 10, false);
	set_datatable("surgery_list", 10, false);
	$(".control_bl_simple").on('click',(function(e) {control_bl_simple(this);}));
	
	//doctor update
	$("#form_update_personal_data").submit(function(e) {e.preventDefault(); update_personal_data(this);});
	$("#form_update_profession").submit(function(e) {e.preventDefault(); update_profession(this);});
	$("#form_update_account_email").submit(function(e) {e.preventDefault(); update_account_email(this);});
	$("#form_update_account_password").submit(function(e) {e.preventDefault(); update_account_password(this);});
	
	//doctor activation
	$("#btn_deactivate").on('click',(function(e) {activation_control(this, false);}));
	$("#btn_activate").on('click',(function(e) {activation_control(this, true);}));
	
	//appointment generate
	aa_load_doctor_schedule();
	$("#app_register_form").submit(function(e) {e.preventDefault(); app_register(this);});
	$("#aa_date").change(function() {aa_load_doctor_schedule();});
	$("#btn_aa_search_pt").on('click',(function(e) {aa_search_patient();}));
	
	//surgery generate
	sur_load_doctor_schedule();
	$("#sur_register_form").submit(function(e) {e.preventDefault(); sur_register(this);});
	$("#sur_date").change(function() {sur_load_doctor_schedule();});
	$("#btn_sur_search_pt").on('click',(function(e) {sur_search_patient();}));
	
	
});