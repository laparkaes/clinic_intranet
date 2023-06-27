function delete_patient_file(dom){
	ajax_simple_warning({id: $(dom).val()}, "patient/delete_file", $("#warning_dpf").val()).done(function(res) {
		//set_msg(res.msgs);
		swal_redirection(res.type, res.msg, window.location.href);
	});
}

function upload_patient_file(dom){
	$("#form_upload_patient_file .sys_msg").html("");
	ajax_form(dom, "patient/upload_file").done(function(res) {
		set_msg(res.msgs);
		swal_redirection(res.type, res.msg, window.location.href);
	});
}

function update_patient(dom){
	$("#update_form .sys_msg").html("");
	ajax_form(dom, "patient/update").done(function(res) {
		set_msg(res.msgs);
		swal_redirection(res.type, res.msg, window.location.href);
	});
}

function update(dom){
	$("#form_update .sys_msg").html("");
	ajax_form(dom, "patient/update").done(function(res) {
		set_msg(res.msgs);
		swal_redirection(res.type, res.msg, window.location.href);
	});
}

function aa_load_doctor_schedule(){
	$("#aa_schedule_list").html('<div class="text-center mt-5"><i class="fas fa-spinner fa-spin fa-5x"></i></div>');
	load_doctor_schedule_n($("#aa_doctor").val(), $("#aa_date").val()).done(function(res) {
		$("#aa_schedule_list").html(res);
		$("#aa_schedule_list .sch_cell").on('click',(function(e) {set_time_dom("#aa_hour", "#aa_min", this);}));
		set_time_sl("aa", "#aa_schedule_list");
	});
}

function sur_load_doctor_schedule(){
	$("#sur_schedule_list").html('<div class="text-center mt-5"><i class="fas fa-spinner fa-spin fa-5x"></i></div>');
	load_doctor_schedule_n($("#sur_doctor").val(), $("#sur_date").val()).done(function(res) {
		$("#sur_schedule_list").html(res);
		$("#sur_schedule_list .sch_cell").on('click',(function(e) {set_time_dom("#sur_hour", "#sur_min", this);}));
		set_time_sl("sur", "#sur_schedule_list");
	});
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
	$("#add_appointment_form .sys_msg").html("");
	ajax_form_warning(dom, "appointment/register", $("#warning_rap").val()).done(function(res) {
		set_msg(res.msgs);
		swal_redirection(res.type, res.msg, res.move_to);
	});
}

function register_surgery(dom){
	$("#sur_register_form .sys_msg").html("");
	ajax_form_warning(dom, "surgery/register", $("#warning_rsu").val()).done(function(res) {
		set_msg(res.msgs);
		swal_redirection(res.type, res.msg, res.move_to);
	});
}

$(document).ready(function() {
	$(".control_bl_simple").on('click',(function(e) {control_bl_simple(this);}));
	$("#ic_doctor_schedule_w_aa").on('click',(function(e) {load_doctor_schedule_weekly($("#aa_doctor").val(), null, "bl_weekly_schedule");}));
	$("#ic_doctor_schedule_w_sur").on('click',(function(e) {load_doctor_schedule_weekly($("#sur_doctor").val(), null, "bl_weekly_schedule");}));
	$("#ic_room_availability_w").on('click',(function(e) {load_room_availability($("#sur_room_id").val(), null, "bl_room_availability");}));
	set_datatable("appointment_list", 10, false);
	set_datatable("surgery_list", 10, false);
	set_datatable("file_list", 10, false);
	set_datatable("sale_list", 10, false);
	
	//add appointment
	aa_load_doctor_schedule();
	$("#add_appointment_form").submit(function(e) {e.preventDefault(); register_appointment(this);});
	$("#aa_specialty").change(function() {aa_set_doctor_sl(this);});
	$("#aa_specialty, #aa_doctor, #aa_date").change(function() {aa_load_doctor_schedule();});
	$("#aa_hour, #aa_min").change(function() {set_time_sl("aa", "#aa_schedule_list");});
	
	//add surgery
	sur_load_doctor_schedule();
	$("#sur_register_form").submit(function(e) {e.preventDefault(); register_surgery(this);});
	$("#sur_specialty").change(function() {sur_set_doctor_sl(this);});
	$("#sur_specialty, #sur_doctor, #sur_date").change(function() {sur_load_doctor_schedule();});
	$("#sur_hour, #sur_min").change(function() {set_time_sl("sur", "#sur_schedule_list");});
	
	//update patient
	$("#form_update").submit(function(e) {e.preventDefault(); update(this);});
	
	//admin patient file
	$("#form_upload_patient_file").submit(function(e) {e.preventDefault(); upload_patient_file(this);});
	$(".btn_delete_file").on('click',(function(e) {delete_patient_file(this);}));
	$("#upload_file").change(function(e) {
		$("#lb_selected_filename").html(e.target.files[0].name); 
		$("#ip_selected_filename").val(e.target.files[0].name);
	});
});