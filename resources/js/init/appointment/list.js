function load_doctor_schedule_appointment(){
	$("#aa_schedule").html('<div class="text-center mt-5"><i class="fas fa-spinner fa-spin fa-5x"></i></div>');
	load_doctor_schedule_n($("#aa_doctor").val(), $("#aa_date").val()).done(function(res) {
		$("#aa_schedule").html(res);
		$("#aa_schedule .sch_cell").on('click',(function(e) {set_time_dom("#aa_hour", "#aa_min", this);}));
		set_time_sl("aa", "#aa_schedule");
	});
}

function set_doctor_sl(dom){
	$("#aa_doctor").val("");
	$("#aa_doctor .spe").addClass("d-none");
	$("#aa_doctor .spe_" + $(dom).val()).removeClass("d-none");
}

function register_appointment(dom){
	$("#register_form .sys_msg").html("");
	ajax_form_warning(dom, "appointment/register", $("#w_appointment_register").val()).done(function(res) {
		set_msg(res.msgs);
		swal_redirection(res.type, res.msg, res.move_to);
	});
}

function search_person_pt(){
	var data = {doc_type_id: $("#aa_pt_doc_type_id").val(), doc_number: $("#aa_pt_doc_number").val()};
	ajax_simple(data, "ajax_f/search_person").done(function(res) {
		swal(res.type, res.msg);
		if (res.type == "success"){
			$("#aa_pt_name").attr("readonly", true);
			$("#aa_pt_name").val(res.person.name);
			$("#aa_pt_tel").val(res.person.tel);
		}else{
			reset_person();
			$("#aa_pt_name").attr("readonly", false);
		}
	});
}

function reset_person(){
	$("#aa_pt_name").attr("readonly", true);
	$("#aa_pt_name").val("");
	$("#aa_pt_tel").val("");
}

$(document).ready(function() {
	//general
	load_doctor_schedule_appointment();
	$(".control_bl").on('click',(function(e) {control_bl(this);}));
	
	var params = get_params(); console.log(params);
	if (params.a == "add") $("#btn_add").trigger("click");
	
	//register
	$("#register_form").submit(function(e) {e.preventDefault(); register_appointment(this);});
	$("#aa_specialty").change(function() {set_doctor_sl(this);});
	$("#aa_specialty, #aa_doctor, #aa_date").change(function() {load_doctor_schedule_appointment();});
	$("#aa_pt_doc_type_id").change(function() {reset_person();});
	$("#aa_pt_doc_number").keyup(function() {reset_person();});
	$("#btn_search_pt").on('click',(function(e) {search_person_pt();}));
	$("#ic_doctor_schedule_w").on('click',(function(e) {load_doctor_schedule_weekly($("#aa_doctor").val(), null, "bl_weekly_schedule");}));
	$("#aa_hour, #aa_min").change(function() {set_time_sl("aa", "#aa_schedule");});
});