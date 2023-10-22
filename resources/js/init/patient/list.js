function register_patient(dom){
	//birthday merge
	let d = $("#p_birthday_d").val();
	let m = $("#p_birthday_m").val();
	let y = $("#p_birthday_y").val();
	if (d != "" && m != "" && y != "") $("#p_birthday").val(y + "-" + m + "-" + d); else $("#p_birthday").val("");
	
	$("#form_register .sys_msg").html("");
	ajax_form_warning(dom, "patient/register", "wm_patient_register").done(function(res) {
		set_msg(res.msgs);
		swal_redirection(res.type, res.msg, res.move_to);
	});
}

function search_person_pn(){
	var data = {doc_type_id: $("#pn_doc_type_id").val(), doc_number: $("#pn_doc_number").val()};
	ajax_simple(data, "ajax_f/search_person").done(function(res) {
		swal(res.type, res.msg);
		if (res.type == "success"){
			$("#pn_name").attr("readonly", true);
			$("#pn_name").val(res.person.name);
			$("#pn_tel").val(res.person.tel);
		}else{
			reset_person();
			$("#pn_name").attr("readonly", false);
		}
	});
}

function reset_person(){
	$("#pn_name").attr("readonly", true);
	$("#pn_name").val("");
	$("#pn_tel").val("");
}

$(document).ready(function() {
	//list
	$(".control_bl").on('click',(function(e) {control_bl(this);}));
	
	//register
	$("#form_register").submit(function(e) {e.preventDefault(); register_patient(this);});
	$("#btn_search_person_pn").on('click',(function(e) {search_person_pn();}));
	$("#pn_doc_type_id").on('change',(function(e) {reset_person();}));
	$("#pn_doc_number").keyup(function() {reset_person();});
});