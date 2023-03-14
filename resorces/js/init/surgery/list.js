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

function load_doctor_schedule(){
	$.ajax({
		url: $("#base_url").val() + "surgery/load_doctor_schedule",
		type: "POST",
		data: {doctor_id:$("#aa_doctor").val(), date:$("#aa_date").val()},
		success:function(res){
			$("#aa_schedule_list").html("");
			res.data.forEach((e) => {
				$("#aa_schedule_list").append('<li class="list-group-item d-flex justify-content-between py-2">' + e + '</li>');
			});	
		}
	});
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
	set_datatable("surgery_list", 25, false);
	$(".control_bl").on('click',(function(e) {control_bl(this);}));
	$("#aa_speciality").change(function() {set_doctor_sl(this);});
	$("#aa_speciality, #aa_doctor, #aa_date").change(function() {load_doctor_schedule(this);});
	$("#pt_doc_type_id").change(function() {enable_pt_name();});
	$("#pt_doc_number").keyup(function() {enable_pt_name();});
	$("#btn_search_pt").on('click',(function(e) {search_person_pt();}));
	$("#register_form").submit(function(e) {e.preventDefault(); register_surgery(this);});
});