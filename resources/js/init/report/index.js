function generate_report(dom){
	$("#form_generate_report .sys_msg").html("");
	$.ajax({
		url: $("#base_url").val() + "report/generate_report",
		type: "POST",
		data: new FormData(dom),
		contentType: false,
		processData:false,
		success:function(res){
			if (res.status == true) location.href = res.link_to;
			else{
				set_msg(res.msgs);
				swal("error", res.msg);
			}
		}
	});
}

$(document).ready(function() {
	$("#form_generate_report").submit(function(e) {e.preventDefault(); generate_report(this);});
	set_between_dates("gr_from", "gr_to");
});