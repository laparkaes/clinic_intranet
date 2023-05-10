function cancel_surgery(dom){
	ajax_simple_warning({id: $(dom).val()}, "surgery/cancel", $("#warning_sca").val()).done(function(res) {
		swal_redirection(res.type, res.msg, window.location.href);
	});
}

function finish_surgery(dom){
	Swal.fire({
		title: $("#alert_warning_title").val(),
		text: $("#warning_sfi").val(),
		icon: 'warning',
		showCancelButton: true,
		confirmButtonText: $("#alert_confirm_btn").val(),
		cancelButtonText: $("#alert_cancel_btn").val()
	}).then((result) => {
		if (result.isConfirmed){
			$.ajax({
				url: $("#base_url").val() + "surgery/finish",
				type: "POST",
				data: new FormData(dom),
				contentType: false,
				processData:false,
				success:function(res){
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

function reschedule_surgery(dom){
	$("#reschedule_form .sys_msg").html("");
	Swal.fire({
		title: $("#alert_warning_title").val(),
		text: $("#warning_sre").val(),
		icon: 'warning',
		showCancelButton: true,
		confirmButtonText: $("#alert_confirm_btn").val(),
		cancelButtonText: $("#alert_cancel_btn").val()
	}).then((result) => {
		if (result.isConfirmed){
			$.ajax({
				url: $("#base_url").val() + "surgery/reschedule",
				type: "POST",
				data: new FormData(dom),
				contentType: false,
				processData:false,
				success:function(res){
					set_msg(res.msgs);
					Swal.fire({
						title: $("#alert_" + res.type + "_title").val(),
						icon: res.type,
						text: res.msg,
						confirmButtonText: $("#alert_confirm_btn").val(),
					}).then((result) => {
						if (res.status == true) location.reload();
					});
				}
			});
		}
	});
}

function load_doctor_schedule_surgery(){
	load_doctor_schedule($("#rs_doctor").val(), $("#rs_date").val(), "rp_schedule");
}

function control_reschedule_form(){
	if ($("#sur_reschedule").hasClass("d-none")) {
		load_doctor_schedule_surgery();
		$("#sur_reschedule").removeClass("d-none");
		$("#sur_info").addClass("d-none");
	}else{
		$("#sur_reschedule").addClass("d-none");
		$("#sur_info").removeClass("d-none");
	}
}

$(document).ready(function() {
	//general
	$("#btn_cancel").on('click',(function(e) {cancel_surgery(this);}));
	$("#btn_finish").on('click',(function(e) {finish_surgery(this);}));
	$("#btn_reschedule, #btn_reschedule_cancel").on('click',(function(e) {control_reschedule_form();}));
	
	//reschedule
	$("#form_reschedule").submit(function(e) {e.preventDefault(); reschedule_surgery(this);});
	$(".doc_schedule").change(function() {load_doctor_schedule_surgery();});
	
	//finish surgery
	$("#form_result").submit(function(e) {e.preventDefault(); finish_surgery(this);});
	
	//weekly
	$("#ic_doctor_schedule_w").on('click',(function(e) {load_doctor_schedule_weekly($("#rs_doctor").val(), null, "bl_weekly_schedule");}));
	$("#ic_room_availability_w").on('click',(function(e) {load_room_availability($("#rs_room_id").val(), null, "bl_room_availability");}));
});