function generate_master(dom){
	$("#form_generate_master .sys_msg").html("");
	$.ajax({
		url: $("#base_url").html() + "auth/generate_master",
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
				}).then((result) => {
					if (result.isConfirmed) document.location.reload();
				});
			}else set_msg(res.msgs);
		}
	});
}

function forgot_pass(dom){
	$("#forgot_pass_form .sys_msg").html("");
	$.ajax({
		url: $("#base_url").html() + "auth/forgot_pass",
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
			}else set_msg(res.msgs);
		}
	});
}

function login(dom){
	$("#form_login .sys_msg").html("");
	$.ajax({
		url: $("#base_url").html() + "auth/login",
		type: "POST",
		data: new FormData(dom),
		contentType: false,
		processData:false,
		success:function(res){
			if (res.status == true) window.location.href = $("#base_url").html() + "dashboard";
			else set_msg(res.msgs);
		}
	});
}

$(document).ready(function() {
	$("#form_generate_master").submit(function(e) {e.preventDefault(); generate_master(this);});
	$("#form_forgot_pass").submit(function(e) {e.preventDefault(); forgot_pass(this);});
	$("#form_login").submit(function(e) {e.preventDefault(); login(this);});
});