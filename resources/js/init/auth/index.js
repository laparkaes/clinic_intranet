function login(dom){
	$("#form_login .sys_msg").html("");
	ajax_form(dom, "auth/login").done(function(res) {
		set_msg(res.msgs);
		if (res.type == "success") window.location.href = res.move_to;
		else swal(res.type, res.msg);
	});
}

$(document).ready(function() {
	$("#form_login").submit(function(e) {e.preventDefault(); login(this);});
});