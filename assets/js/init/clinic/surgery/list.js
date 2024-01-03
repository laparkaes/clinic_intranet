function load_doctor_schedule_surgery(){
	$("#sur_schedule_list").html('<div class="text-center mt-5"><i class="fas fa-spinner fa-spin fa-5x"></i></div>');
	load_doctor_schedule_n($("#sur_doctor").val(), $("#sur_date").val()).done(function(res) {
		$("#sur_schedule_list").html(res);
		$("#sur_schedule_list .sch_cell").on('click',(function(e) {set_time_dom("#sur_hour", "#sur_min", this);}));
		set_time_sl("sur", "#sur_schedule_list");
	});
}

function set_doctor_sl(dom){
	$("#sur_doctor").val("");
	$("#sur_doctor .spe").addClass("d-none");
	$("#sur_doctor .spe_" + $(dom).val()).removeClass("d-none");
}

function register_surgery(dom){
	$("#register_form .sys_msg").html("");
	ajax_form_warning(dom, "surgery/register", "wm_surgery_register").done(function(res) {
		set_msg(res.msgs);
		swal_redirection(res.type, res.msg, res.move_to);
	});
}

function search_person_pt(){
	var data = {doc_type_id: $("#sur_pt_doc_type_id").val(), doc_number: $("#sur_pt_doc_number").val()};
	ajax_simple(data, "ajax_f/search_person").done(function(res) {
		swal(res.type, res.msg);
		if (res.type == "success"){
			$("#sur_pt_name").attr("readonly", true);
			$("#sur_pt_name").val(res.person.name);
			$("#sur_pt_tel").val(res.person.tel);
		}else reset_person();
	});
}

function reset_person(){
	$("#sur_pt_name").attr("readonly", false);
	$("#sur_pt_name").val("");
	$("#sur_pt_tel").val("");
}

$(document).ready(function() {
	//general
	load_doctor_schedule_surgery();
	$(".control_bl").on('click',(function(e) {control_bl(this);}));
	set_date_picker(".date_picker", new Date());
	
	var params = get_params(); console.log(params);
	if (params.a == "add") $("#btn_add").trigger("click");
	
	//register
	$("#register_form").submit(function(e) {e.preventDefault(); register_surgery(this);});
	$("#sur_specialty").change(function() {set_doctor_sl(this);});
	$("#sur_specialty, #sur_doctor").change(function() {load_doctor_schedule_surgery();});
	$("#sur_date").on('focusout',(function(e) {load_doctor_schedule_surgery();}));
	$("#sur_pt_doc_type_id").change(function() {reset_person();});
	$("#sur_pt_doc_number").keyup(function() {reset_person();});
	$("#btn_search_pt").on('click',(function(e) {search_person_pt();}));
	$("#ic_doctor_schedule_w").on('click',(function(e) {load_doctor_schedule_weekly($("#sur_doctor").val(), null, "bl_weekly_schedule");}));
	$("#ic_room_availability_w").on('click',(function(e) {load_room_availability($("#sur_room_id").val(), null, "bl_room_availability");}));
	$("#sur_hour, #sur_min").change(function() {set_time_sl("sur", "#sur_schedule_list");});
});