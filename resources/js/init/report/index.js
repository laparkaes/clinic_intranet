function generate_report(dom){
	ajax_form(dom, "report/generate_report").done(function(res) {
		set_msg(res.msgs);
		if (res.type == "success") location.href = res.move_to;
		else swal(res.type, res.msg);
	});
}

$(document).ready(function() {
	$("#form_generate_report").submit(function(e) {e.preventDefault(); generate_report(this);});
	set_between_dates("gr_from", "gr_to");
});