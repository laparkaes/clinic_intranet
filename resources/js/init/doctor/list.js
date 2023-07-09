function register_doctor(dom){
	//birthday merge
	let d = $("#p_birthday_d").val();
	let m = $("#p_birthday_m").val();
	let y = $("#p_birthday_y").val();
	if (d != "" && m != "" && y != "") $("#p_birthday").val(y + "-" + m + "-" + d); else $("#p_birthday").val("");
	
	$("#register_form .sys_msg").html("");
	ajax_form(dom, "doctor/register").done(function(res) {
		set_msg(res.msgs);
		swal_redirection(res.type, res.msg, res.move_to);
	});
}

function search_person_dn(){
	var data = {doc_type_id: $("#dn_doc_type_id").val(), doc_number: $("#dn_doc_number").val()};
	ajax_simple(data, "ajax_f/search_person").done(function(res) {
		swal(res.type, res.msg);
		if (res.type == "success"){
			$("#dn_name").attr("readonly", true);
			$("#dn_name").val(res.person.name);
			$("#dn_tel").val(res.person.tel);
		}else{
			reset_person();
			$("#dn_name").attr("readonly", false);
		}
	});
}

function reset_person(){
	$("#dn_name").attr("readonly", true);
	$("#dn_name").val("");
	$("#dn_tel").val("");
}

$(document).ready(function() {
	//list
	$(".control_bl").on('click',(function(e) {control_bl(this);}));
	
	//register
	$("#form_register").submit(function(e) {e.preventDefault(); register_doctor(this);});
	$("#btn_search_person_dn").on('click',(function(e) {search_person_dn();}));
	$("#dn_doc_type_id").on('change',(function(e) {reset_person();}));
	$("#dn_doc_number").keyup(function() {reset_person();});
});