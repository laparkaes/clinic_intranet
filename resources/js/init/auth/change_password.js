function change_password(dom){
	$("#form_change_password .sys_msg").html("");
	ajax_form_warning(dom, "auth/change_password_apply", $("#wm_change_password").val()).done(function(res) {
		set_msg(res.msgs);
		swal_redirection(res.type, res.msg, $("#base_url").val() + "auth/logout");
	});
}

$(document).ready(function() {
	$("#form_change_password").submit(function(e) {e.preventDefault(); change_password(this);});
});