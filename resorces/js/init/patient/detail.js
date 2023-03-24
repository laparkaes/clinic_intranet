function delete_file(dom){
	Swal.fire({
		title: $("#alert_warning_title").val(),
		text: $("#warning_dpf").val(),
		icon: 'warning',
		showCancelButton: true,
		confirmButtonText: $("#alert_confirm_btn").val(),
		cancelButtonText: $("#alert_cancel_btn").val()
	}).then((result) => {
		if (result.isConfirmed){
			$.ajax({
				url: $("#base_url").val() + "patient/delete_file",
				type: "POST",
				data: {id: $(dom).val()},
				success:function(res){
					var title;
					var icon;
					if (res.status == true){
						title = $("#alert_success_title").val();
						icon = "success";
					}else{
						title = $("#alert_error_title").val();
						icon = "error";
					}
					Swal.fire({
						title: title,
						icon: icon,
						text: res.msg,
						confirmButtonText: $("#alert_confirm_btn").val()
					}).then((result) => {
						if (res.status == true) if (result.isConfirmed == true) location.reload();
					});
				}
			});
		}
	});
}

function upload_patient_file(dom){
	$("#form_upload_patient_file .sys_msg").html("");
	$.ajax({
		url: $("#base_url").val() + "patient/upload_file",
		type: "POST",
		data: new FormData(dom),
		contentType: false,
		processData:false,
		success:function(res){
			if (res.status == true){
				Swal.fire({
					title: $("#alert_success_title").val(),
					html: res.msg,
					icon: 'success',
					confirmButtonText: $("#alert_confirm_btn").val()
				}).then((result) => {
					if (result.isConfirmed == true) location.reload();
				});
			}else set_msg(res.msgs);
		}
	});
}

function update_patient(dom){
	$("#update_form .sys_msg").html("");
	$.ajax({
		url: $("#base_url").val() + "patient/update",
		type: "POST",
		data: new FormData(dom),
		contentType: false,
		processData:false,
		success:function(res){
			if (res.status == true){
				Swal.fire({
					title: $("#alert_success_title").val(),
					html: res.msg,
					icon: 'success',
					confirmButtonText: $("#alert_confirm_btn").val()
				}).then((result) => {
					if (result.isConfirmed == true) location.reload();
				});
			}else set_msg(res.msgs);
		}
	});
}

function update_personal_data(dom){
	$("#form_update_personal_data .sys_msg").html("");
	$.ajax({
		url: $("#base_url").val() + "patient/update_personal_data",
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
		url: $("#base_url").val() + "patient/update_account_email",
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
		url: $("#base_url").val() + "patient/update_account_password",
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

function form_create_account(dom){
	$("#form_update_account_password .sys_msg").html("");
	$.ajax({
		url: $("#base_url").val() + "patient/form_create_account",
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

function aa_load_doctor_schedule(){
	load_doctor_schedule($("#aa_doctor").val(), $("#aa_date").val(), "aa_schedule_list");
}

function set_doctor_sl(dom){
	$("#aa_doctor").val("");
	$("#aa_doctor .spe").addClass("d-none");
	$("#aa_doctor .spe_" + $(dom).val()).removeClass("d-none");
}

function register_appointment(dom){
	$("#register_form .sys_msg").html("");
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

$(document).ready(function() {
	set_datatable("appointment_list", 10, false);
	set_datatable("file_list", 10, false);
	set_datatable("sale_list", 10, false);
	$(".control_bl_simple").on('click',(function(e) {control_bl_simple(this);}));
	
	//add appointment
	aa_load_doctor_schedule();
	$("#add_appointment_form").submit(function(e) {e.preventDefault(); register_appointment(this);});
	$("#aa_speciality").change(function() {set_doctor_sl(this);});
	$("#aa_speciality, #aa_doctor, #aa_date").change(function() {aa_load_doctor_schedule();});
	
	//personal data update
	$("#form_create_account").submit(function(e) {e.preventDefault(); form_create_account(this);});
	$("#form_update_personal_data").submit(function(e) {e.preventDefault(); update_personal_data(this);});
	$("#form_update_account_email").submit(function(e) {e.preventDefault(); update_account_email(this);});
	$("#form_update_account_password").submit(function(e) {e.preventDefault(); update_account_password(this);});
		
	$("#form_upload_patient_file").submit(function(e) {e.preventDefault(); upload_patient_file(this);});
	$("#update_form").submit(function(e) {e.preventDefault(); update_patient(this);});
	$(".btn_delete_file").on('click',(function(e) {delete_file(this);}));
	
	$("#upload_file").change(function(e) {
		$("#lb_selected_filename").html(e.target.files[0].name); 
		$("#ip_selected_filename").val(e.target.files[0].name);
	});
});