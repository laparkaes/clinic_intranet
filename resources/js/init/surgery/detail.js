function cancel_surgery(dom){
	ajax_simple_warning({id: $(dom).val()}, "surgery/cancel", $("#warning_sca").val()).done(function(res) {
		swal_redirection(res.type, res.msg, window.location.href);
	});
}

function finish_surgery(dom){
	ajax_form_warning(dom, "surgery/finish", $("#warning_sfi").val()).done(function(res) {
		swal_redirection(res.type, res.msg, window.location.href);
	});
}

function reschedule_surgery(dom){
	$("#reschedule_form .sys_msg").html("");
	ajax_form_warning(dom, "surgery/reschedule", $("#warning_sre").val()).done(function(res) {
		set_msg(res.msgs);
		swal_redirection(res.type, res.msg, window.location.href);
	});
}

function load_doctor_schedule_surgery(){
	$("#rp_schedule").html('<div class="text-center mt-5"><i class="fas fa-spinner fa-spin fa-5x"></i></div>');
	load_doctor_schedule_n($("#rs_doctor").val(), $("#rs_date").val()).done(function(res) {
		$("#rp_schedule").html(res);
		$("#rp_schedule .sch_cell").on('click',(function(e) {set_time_dom("#rs_hour", "#rs_min", this);}));
		set_time_sl("rs", "#rp_schedule");
	});
	
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
	$("#rs_hour, #rs_min").change(function() {set_time_sl("rs", "#rp_schedule");});
	
	//finish surgery
	$("#form_result").submit(function(e) {e.preventDefault(); finish_surgery(this);});
	
	//weekly
	$("#ic_doctor_schedule_w").on('click',(function(e) {load_doctor_schedule_weekly($("#rs_doctor").val(), null, "bl_weekly_schedule");}));
	$("#ic_room_availability_w").on('click',(function(e) {load_room_availability($("#rs_room_id").val(), $("#rs_date").val(), "bl_room_availability");}));
});