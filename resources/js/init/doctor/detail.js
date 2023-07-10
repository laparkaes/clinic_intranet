function enable_update_form(){
	$("#form_update_info input").prop("readonly", false);
	$("#form_update_info select").prop("disabled", false);
	$("#form_update_info button").prop("disabled", false);
	
	$("#du_doc_type_id").prop("disabled", true);
	$("#du_doc_number").prop("readonly", true);
	$("#du_name").prop("readonly", true);
	
	$("#btn_update_info").addClass("d-none").prop("disabled", true);
	$("#btn_update_confirm").removeClass("d-none").prop("disabled", false);
	$("#btn_update_cancel").removeClass("d-none").prop("disabled", false);
}

function disable_update_form(){
	$("#form_update_info input").prop("readonly", true);
	$("#form_update_info select").prop("disabled", true);
	$("#form_update_info button").prop("disabled", true);
	
	$("#btn_update_info").removeClass("d-none").prop("disabled", false);
	$("#btn_update_confirm").addClass("d-none").prop("disabled", true);
	$("#btn_update_cancel").addClass("d-none").prop("disabled", true);
}

function update_info(dom){
	//birthday merge
	let d = $("#p_birthday_d").val();
	let m = $("#p_birthday_m").val();
	let y = $("#p_birthday_y").val();
	if (d != "" && m != "" && y != "") $("#p_birthday").val(y + "-" + m + "-" + d); else $("#p_birthday").val("");
	
	//doc_type_id field
	$("#du_doc_type_id").prop("disabled", false);
	
	$("#form_update_info .sys_msg").html("");
	ajax_form(dom, "doctor/update_info").done(function(res) {
		set_msg(res.msgs);
		swal(res.type, res.msg);
		if (res.type == "success") disable_update_form();
		//swal_redirection(res.type, res.msg, window.location.href);
	});
}

function activation_control(dom, active){
	var msg;
	
	if (active == true) msg = $("#warning_ado").val();
	else msg = $("#warning_ddo").val();
	
	ajax_simple_warning({id: $(dom).val(), active: active}, "doctor/activation_control", msg).done(function(res) {
		swal_redirection(res.type, res.msg, window.location.href);
	});
}

function reset_person(prefix){
	$("#" + prefix + "name").attr("readonly", false);
	$("#" + prefix + "name").val("");
	$("#" + prefix + "tel").val("");
	$("#" + prefix + "id").val("");
}

function search_person_aa(){
	var data = {doc_type_id: $("#aa_pt_doc_type_id").val(), doc_number: $("#aa_pt_doc_number").val()};
	ajax_simple(data, "ajax_f/search_person").done(function(res) {
		swal(res.type, res.msg);
		if (res.type == "success"){
			$("#aa_pt_name").attr("readonly", true);
			$("#aa_pt_name").val(res.person.name);
			$("#aa_pt_tel").val(res.person.tel);
			$("#aa_pt_id").val(res.person.id);
		}else reset_person("aa_pt_");
	});
}

function search_person_sur(){
	var data = {doc_type_id: $("#sur_pt_doc_type_id").val(), doc_number: $("#sur_pt_doc_number").val()};
	ajax_simple(data, "ajax_f/search_person").done(function(res) {
		swal(res.type, res.msg);
		if (res.type == "success"){
			$("#sur_pt_name").attr("readonly", true);
			$("#sur_pt_name").val(res.person.name);
			$("#sur_pt_tel").val(res.person.tel);
			$("#sur_pt_id").val(res.person.id);
		}else reset_person("sur_pt_");
	});
}

function aa_load_doctor_schedule(){
	$("#aa_schedule").html('<div class="text-center mt-5"><i class="fas fa-spinner fa-spin fa-5x"></i></div>');
	load_doctor_schedule_n($("#aa_doctor_id").val(), $("#aa_date").val()).done(function(res) {
		$("#aa_schedule").html(res);
		$("#aa_schedule .sch_cell").on('click',(function(e) {set_time_dom("#aa_hour", "#aa_min", this);}));
		set_time_sl("aa", "#aa_schedule");
	});
}

function sur_load_doctor_schedule(){
	$("#sur_schedule_list").html('<div class="text-center mt-5"><i class="fas fa-spinner fa-spin fa-5x"></i></div>');
	load_doctor_schedule_n($("#sur_doctor_id").val(), $("#sur_date").val()).done(function(res) {
		$("#sur_schedule_list").html(res);
		$("#sur_schedule_list .sch_cell").on('click',(function(e) {set_time_dom("#sur_hour", "#sur_min", this);}));
		set_time_sl("sur", "#sur_schedule_list");
	});
}

function app_register(dom){
	$("#app_register_form .sys_msg").html("");
	ajax_form(dom, "appointment/register").done(function(res) {
		set_msg(res.msgs);
		swal_redirection(res.type, res.msg, res.move_to);
	});
}

function sur_register(dom){
	$("#sur_register_form .sys_msg").html("");
	ajax_form(dom, "surgery/register").done(function(res) {
		set_msg(res.msgs);
		swal_redirection(res.type, res.msg, res.move_to);
	});
}

$(document).ready(function() {
	$(".control_bl_simple").on('click',(function(e) {control_bl_simple(this);}));
	$("#btn_weekly_agenda").on('click',(function(e) {load_doctor_schedule_weekly($("#doctor_id").val(), null, "bl_weekly_agenda");}));
	$("#ic_room_availability_w").on('click',(function(e) {load_room_availability($("#sur_room_id").val(), null, "bl_room_availability");}));
	set_datatable("appointment_list", 10, false);
	set_datatable("surgery_list", 10, false);
	
	//doctor update
	$("#form_update_info").submit(function(e) {e.preventDefault(); update_info(this);});
	$("#btn_update_info").on('click',(function(e) {enable_update_form();}));
	$("#btn_update_cancel").on('click',(function(e) {
		disable_update_form();
		document.getElementById("form_update_info").reset();
	}));
	
	//doctor activation
	$("#btn_deactivate").on('click',(function(e) {activation_control(this, false);}));
	$("#btn_activate").on('click',(function(e) {activation_control(this, true);}));
	
	//appointment generate
	aa_load_doctor_schedule();
	$("#app_register_form").submit(function(e) {e.preventDefault(); app_register(this);});
	$("#aa_date").change(function() {aa_load_doctor_schedule();});
	$("#btn_aa_search_pt").on('click',(function(e) {search_person_aa();}));
	$("#aa_pt_doc_type_id").on('change',(function(e) {reset_person("aa_pt_");}));
	$("#aa_pt_doc_number").keyup(function() {reset_person("aa_pt_");});
	$("#aa_hour, #aa_min").change(function() {set_time_sl("aa", "#aa_schedule");});
	
	//surgery generate
	sur_load_doctor_schedule();
	$("#sur_register_form").submit(function(e) {e.preventDefault(); sur_register(this);});
	$("#sur_date").change(function() {sur_load_doctor_schedule();});
	$("#btn_sur_search_pt").on('click',(function(e) {search_person_sur();}));
	$("#sur_pt_doc_type_id").on('change',(function(e) {reset_person("sur_pt_");}));
	$("#sur_pt_doc_number").keyup(function() {reset_person("sur_pt_");});
	$("#sur_hour, #sur_min").change(function() {set_time_sl("sur", "#sur_schedule_list");});
});