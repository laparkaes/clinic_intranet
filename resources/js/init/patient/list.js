function register_patient(dom){
	$("#register_form .sys_msg").html("");
	ajax_form(dom, "patient/register").done(function(res) {
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
		}else reset_person();
	});
}

function reset_person(){
	$("#pn_name").attr("readonly", false);
	$("#pn_name").val("");
	$("#pn_tel").val("");
}

$(document).ready(function() {
	//list
	$(".control_bl").on('click',(function(e) {control_bl(this);}));
	
	//register
	$("#register_form").submit(function(e) {e.preventDefault(); register_patient(this);});
	$("#btn_search_person_pn").on('click',(function(e) {search_person_pn();}));
	$("#pn_doc_type_id").on('change',(function(e) {reset_person();}));
	$("#pn_doc_number").keyup(function() {reset_person();});
});