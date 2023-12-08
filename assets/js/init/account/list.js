function search_person_ra(){
	var data = {doc_type_id: $("#ra_doc_type_id").val(), doc_number: $("#ra_doc_number").val()};
	ajax_simple(data, "ajax_f/search_person").done(function(res) {
		swal(res.type, res.msg);
		if (res.type == "success"){
			$("#ra_name").attr("readonly", true);
			$("#ra_name").val(res.person.name);
			$("#ra_tel").val(res.person.tel);
		}else reset_person();
	});
}

function reset_person(){
	$("#ra_name").attr("readonly", false);
	$("#ra_name").val("");
	$("#ra_tel").val("");
}

function register_account(dom){
	ajax_form_warning(dom, "account/register", "wm_account_add").done(function(res) {
		set_msg(res.msgs);
		swal_redirection(res.type, res.msg, window.location.href);
	});
}

function remove_account(dom){
	ajax_simple_warning({id: $(dom).val()}, "account/remove", "wm_account_remove").done(function(res) {
		swal_redirection(res.type, res.msg, window.location.href);
	});
}

function reset_password(dom){
	ajax_simple_warning({id: $(dom).val()}, "account/reset_password", "wm_password_reset").done(function(res) {
		swal(res.type, res.msg);
	});
}

$(document).ready(function() {
	//list
	$(".control_bl").on('click',(function(e) {control_bl(this);}));
	$(".btn_reset_password").on('click',(function(e) {reset_password(this);}));
	$(".btn_remove_account").on('click',(function(e) {remove_account(this);}));
	
	//register
	$("#form_register_account").submit(function(e) {e.preventDefault(); register_account(this);});
	$("#btn_search_person_ra").on('click',(function(e) {search_person_ra();}));
	$("#ra_doc_type_id").on('change',(function(e) {reset_person();}));
	$("#ra_doc_number").keyup(function() {reset_person();});
});