function register_surgery(dom){
	$("#register_form .sys_msg").html("");
	$.ajax({
		url: $("#base_url").val() + "surgery/register",
		type: "POST",
		data: new FormData(dom),
		contentType: false,
		processData:false,
		success:function(res){
			set_msg(res.msgs);
			if (res.msg != null){
				Swal.fire({
					title: $("#alert_" + res.type + "_title").val(),
					icon: res.type,
					html: res.msg,
					confirmButtonText: $("#alert_confirm_btn").val()
				}).then((result) => {
					if (res.status == true) location.href = res.move_to;
				});
			}
		}
	});
}

function load_doctor_schedule_surgery(){
	load_doctor_schedule($("#sur_doctor").val(), $("#sur_date").val(), "sur_schedule_list");
}

function load_doctor_schedule_weekly_surgery(){
	load_doctor_schedule_weekly($("#sur_doctor").val(), null, "bl_weekly_schedule");
}

function set_doctor_sl(dom){
	$("#sur_doctor").val("");
	$("#sur_doctor .spe").addClass("d-none");
	$("#sur_doctor .spe_" + $(dom).val()).removeClass("d-none");
}

function search_person_pt(){
	$.ajax({
		url: $("#base_url").val() + "ajax_f/search_person",
		type: "POST",
		data: {doc_type_id: $("#sur_pt_doc_type_id").val(), doc_number: $("#sur_pt_doc_number").val()},
		success:function(res){
			Swal.fire({
				title: $("#alert_" + res.type + "_title").val(),
				icon: res.type,
				html: res.msg,
				confirmButtonText: $("#alert_confirm_btn").val()
			}).then((result) => {
				if (res.status == true){
					$("#sur_pt_name").val(res.person.name);
					$("#sur_pt_name").addClass("bg-light");
					$("#sur_pt_name").attr("readonly", true);
					$("#sur_pt_tel").val(res.person.tel);
				}else enable_pt_name();
			});
		}
	});
}

function enable_pt_name(){$("#sur_pt_name").removeClass("bg-light").attr("readonly", false);}

$(document).ready(function() {
	set_datatable("surgery_list", 25, false);
	load_doctor_schedule_surgery();
	$(".control_bl").on('click',(function(e) {control_bl(this);}));
	$("#sur_specialty").change(function() {set_doctor_sl(this);});
	$("#sur_specialty, #sur_doctor, #sur_date").change(function() {load_doctor_schedule_surgery();});
	$("#pt_doc_type_id").change(function() {enable_pt_name();});
	$("#pt_doc_number").keyup(function() {enable_pt_name();});
	$("#btn_search_pt").on('click',(function(e) {search_person_pt();}));
	$("#ic_doctor_schedule_w").on('click',(function(e) {load_doctor_schedule_weekly_surgery();}));
	$("#register_form").submit(function(e) {e.preventDefault(); register_surgery(this);});
});