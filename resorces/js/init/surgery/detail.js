function cancel_surgery(dom){
	Swal.fire({
		title: $("#alert_warning_title").val(),
		text: $(dom).find(".msg").html(),
		icon: 'warning',
		showCancelButton: true,
		confirmButtonText: $("#alert_confirm_btn").val(),
		cancelButtonText: $("#alert_cancel_btn").val()
	}).then((result) => {
		if (result.isConfirmed){
			$.ajax({
				url: $("#base_url").val() + "surgery/cancel_surgery",
				type: "POST",
				data: {id: $(dom).val()},
				success:function(res){
					Swal.fire({
						title: $("#alert_" + res.type + "_title").val(),
						html: res.msg,
						icon: res.type,
						confirmButtonText: $("#alert_confirm_btn").val()
					}).then((result) => {
						if (res.status == true) location.reload();
					});
				}
			});
		}
	});
}

function finish_surgery(dom){
	Swal.fire({
		title: $("#alert_warning_title").val(),
		text: $("#warning_sfi").val(),
		icon: 'warning',
		showCancelButton: true,
		confirmButtonText: $("#alert_confirm_btn").val(),
		cancelButtonText: $("#alert_cancel_btn").val()
	}).then((result) => {
		if (result.isConfirmed){
			$.ajax({
				url: $("#base_url").val() + "surgery/finish_surgery",
				type: "POST",
				data: new FormData(dom),
				contentType: false,
				processData:false,
				success:function(res){
					Swal.fire({
						title: $("#alert_" + res.type + "_title").val(),
						html: res.msg,
						icon: res.type,
						confirmButtonText: $("#alert_confirm_btn").val()
					}).then((result) => {
						if (res.status == true) location.reload();
					});
				}
			});
		}
	});
}

function reschedule_surgery(dom){
	$("#reschedule_form .sys_msg").html("");
	Swal.fire({
		title: $("#alert_warning_title").val(),
		text: $("#warning_sre").val(),
		icon: 'warning',
		showCancelButton: true,
		confirmButtonText: $("#alert_confirm_btn").val(),
		cancelButtonText: $("#alert_cancel_btn").val()
	}).then((result) => {
		if (result.isConfirmed){
			$.ajax({
				url: $("#base_url").val() + "surgery/reschedule_surgery",
				type: "POST",
				data: new FormData(dom),
				contentType: false,
				processData:false,
				success:function(res){
					set_msg(res.msgs);
					Swal.fire({
						title: $("#alert_" + res.type + "_title").val(),
						icon: res.type,
						text: res.msg,
						confirmButtonText: $("#alert_confirm_btn").val(),
					}).then((result) => {
						if (res.status == true) location.reload();
					});
				}
			});
		}
	});
}

function load_doctor_schedule_surgery(){
	load_doctor_schedule($("#rs_doctor").val(), $("#rs_date").val(), "rp_schedule");
}

$(document).ready(function() {
	//general
	$("#btn_cancel").on('click',(function(e) {cancel_surgery(this);}));
	$("#btn_finish").on('click',(function(e) {finish_surgery(this);}));
	$("#btn_reschedule").on('click',(function(e) {load_doctor_schedule_surgery();}));
	
	//reschedule
	$("#form_reschedule").submit(function(e) {e.preventDefault(); reschedule_surgery(this);});
	$(".doc_schedule").change(function() {load_doctor_schedule_surgery();});
	
	//finish surgery
	$("#form_result").submit(function(e) {e.preventDefault(); finish_surgery(this);});
});