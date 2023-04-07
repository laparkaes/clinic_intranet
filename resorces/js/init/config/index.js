function update_company(dom){
	$("#form_update_company .sys_msg").html("");
	$.ajax({
		url: $("#base_url").val() + "config/update_company",
		type: "POST",
		data: new FormData(dom),
		contentType: false,
		processData:false,
		success:function(res){
			if (res.status == true){
				Swal.fire({
					title: $("#alert_success_title").val(),
					html: res.msg,
					icon: 'success',
					confirmButtonText: $("#alert_confirm_btn").val()
				});
				$("#ic_cert").attr("href", res.cert_link);
			}else set_msg(res.msgs);
		}
	});
}

function control_province(){
	$("#sl_province").val("");
	$("#sl_province .province").addClass("d-none");
	$("#sl_province .d" + $(this).val()).removeClass("d-none");
	$("#sl_district").val("");
	$("#sl_district .district").addClass("d-none");
}

function control_district(){
	$("#sl_district").val("");
	$("#sl_district .district").addClass("d-none");
	$("#sl_district .p" + $(this).val()).removeClass("d-none");
}

$(document).ready(function() {
	//general
	
	
	//company
	$("#form_update_company").submit(function(e) {e.preventDefault(); update_company(this);});
	$("#sl_department").change(function() {control_province();});
	$("#sl_province").change(function() {control_district();});
});