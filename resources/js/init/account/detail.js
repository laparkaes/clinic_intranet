function update_personal_data(dom){
	$("#form_update_personal_data .sys_msg").html("");
	ajax_form(dom, "doctor/update_personal_data").done(function(res) {
		set_msg(res.msgs);
		swal_redirection(res.type, res.msg, window.location.href);
	});
}

function update_profession(dom){
	$("#form_update_profession .sys_msg").html("");
	ajax_form(dom, "doctor/update_profession").done(function(res) {
		set_msg(res.msgs);
		swal_redirection(res.type, res.msg, window.location.href);
	});
}

function update_account_email(dom){
	$("#form_update_account_email .sys_msg").html("");
	ajax_form(dom, "doctor/update_account_email").done(function(res) {
		set_msg(res.msgs);
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
	load_doctor_schedule($("#aa_doctor_id").val(), $("#aa_date").val(), "aa_schedule");
}

function sur_load_doctor_schedule(){
	load_doctor_schedule($("#sur_doctor_id").val(), $("#sur_date").val(), "sur_schedule_list");
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
	$("#form_update_personal_data").submit(function(e) {e.preventDefault(); update_personal_data(this);});
	$("#form_update_profession").submit(function(e) {e.preventDefault(); update_profession(this);});
	$("#form_update_account_email").submit(function(e) {e.preventDefault(); update_account_email(this);});
	
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
	
	//surgery generate
	sur_load_doctor_schedule();
	$("#sur_register_form").submit(function(e) {e.preventDefault(); sur_register(this);});
	$("#sur_date").change(function() {sur_load_doctor_schedule();});
	$("#btn_sur_search_pt").on('click',(function(e) {search_person_sur();}));
	$("#sur_pt_doc_type_id").on('change',(function(e) {reset_person("sur_pt_");}));
	$("#sur_pt_doc_number").keyup(function() {reset_person("sur_pt_");});
});