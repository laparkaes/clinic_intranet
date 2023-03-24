function register_appointment(dom){
	$("#register_form .sys_msg").html("");
	$.ajax({
		url: $("#base_url").val() + "appointment/register",
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

function load_doctor_schedule_appointment(){
	load_doctor_schedule($("#aa_doctor").val(), $("#aa_date").val(), "aa_schedule_list");
}

function set_doctor_sl(dom){
	$("#aa_doctor").val("");
	$("#aa_doctor .spe").addClass("d-none");
	$("#aa_doctor .spe_" + $(dom).val()).removeClass("d-none");
}

function search_person_pt(){
	$.ajax({
		url: $("#base_url").val() + "ajax_f/search_person",
		type: "POST",
		data: {doc_type_id: $("#aa_pt_doc_type_id").val(), doc_number: $("#aa_pt_doc_number").val()},
		success:function(res){
			Swal.fire({
				title: $("#alert_" + res.type + "_title").val(),
				icon: res.type,
				html: res.msg,
				confirmButtonText: $("#alert_confirm_btn").val()
			}).then((result) => {
				if (res.status == true){
					$("#aa_pt_name").val(res.person.name);
					$("#aa_pt_name").addClass("bg-light");
					$("#aa_pt_name").attr("readonly", true);
					$("#aa_pt_tel").val(res.person.tel);
				}else enable_pt_name();
			});
		}
	});
}

function enable_pt_name(){$("#aa_pt_name").removeClass("bg-light").attr("readonly", false);}

$(document).ready(function() {
	set_datatable("appointment_list", 25, false);
	load_doctor_schedule_appointment();
	$(".control_bl").on('click',(function(e) {control_bl(this);}));
	$("#aa_specialty").change(function() {set_doctor_sl(this);});
	$("#aa_specialty, #aa_doctor, #aa_date").change(function() {load_doctor_schedule_appointment();});
	$("#pt_doc_type_id").change(function() {enable_pt_name();});
	$("#pt_doc_number").keyup(function() {enable_pt_name();});
	$("#btn_search_pt").on('click',(function(e) {search_person_pt();}));
	$("#register_form").submit(function(e) {e.preventDefault(); register_appointment(this);});
});