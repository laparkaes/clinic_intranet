function generate_report(dom){
	$("#form_generate_report .sys_msg").html("");
	$.ajax({
		url: $("#base_url").val() + "report/generate_report",
		type: "POST",
		data: new FormData(dom),
		contentType: false,
		processData:false,
		success:function(res){
			set_msg(res.msgs);
			if (res.status == true) location.href = res.link_to;
			console.log(res);
		}
	});
}

$(document).ready(function() {
	$("#form_generate_report").submit(function(e) {e.preventDefault(); generate_report(this);});
	set_between_dates("gr_from", "gr_to");
});