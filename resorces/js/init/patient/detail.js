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

function update(dom){
	$("#form_update .sys_msg").html("");
	$.ajax({
		url: $("#base_url").val() + "patient/update",
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

function sur_load_doctor_schedule(){
	load_doctor_schedule($("#sur_doctor").val(), $("#sur_date").val(), "sur_schedule_list");
}

function aa_set_doctor_sl(dom){
	$("#aa_doctor").val("");
	$("#aa_doctor .spe").addClass("d-none");
	$("#aa_doctor .spe_" + $(dom).val()).removeClass("d-none");
}

function sur_set_doctor_sl(dom){
	$("#sur_doctor").val("");
	$("#sur_doctor .spe").addClass("d-none");
	$("#sur_doctor .spe_" + $(dom).val()).removeClass("d-none");
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

function register_surgery(dom){
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
					if (res.status == true) location.href = res.move_to;
				});
			}
		}
	});
}

$(document).ready(function() {
	set_datatable("appointment_list", 10, false);
	set_datatable("surgery_list", 10, false);
	set_datatable("file_list", 10, false);
	set_datatable("sale_list", 10, false);
	$(".control_bl_simple").on('click',(function(e) {control_bl_simple(this);}));
	$("#ic_doctor_schedule_w_aa").on('click',(function(e) {load_doctor_schedule_weekly($("#aa_doctor").val(), null, "bl_weekly_schedule");}));
	$("#ic_doctor_schedule_w_sur").on('click',(function(e) {load_doctor_schedule_weekly($("#sur_doctor").val(), null, "bl_weekly_schedule");}));
	$("#ic_room_availability_w").on('click',(function(e) {load_room_availability($("#sur_room_id").val(), null, "bl_room_availability");}));
	
	//add appointment
	aa_load_doctor_schedule();
	$("#add_appointment_form").submit(function(e) {e.preventDefault(); register_appointment(this);});
	$("#aa_specialty").change(function() {aa_set_doctor_sl(this);});
	$("#aa_specialty, #aa_doctor, #aa_date").change(function() {aa_load_doctor_schedule();});
	
	//add surgery
	sur_load_doctor_schedule();
	$("#sur_register_form").submit(function(e) {e.preventDefault(); register_surgery(this);});
	$("#sur_specialty").change(function() {sur_set_doctor_sl(this);});
	$("#sur_specialty, #sur_doctor, #sur_date").change(function() {sur_load_doctor_schedule();});
	
	//update patient
	$("#form_update").submit(function(e) {e.preventDefault(); update(this);});
	
	//admin patient file
	$("#form_upload_patient_file").submit(function(e) {e.preventDefault(); upload_patient_file(this);});
	$(".btn_delete_file").on('click',(function(e) {delete_file(this);}));
	$("#upload_file").change(function(e) {
		$("#lb_selected_filename").html(e.target.files[0].name); 
		$("#ip_selected_filename").val(e.target.files[0].name);
	});
});