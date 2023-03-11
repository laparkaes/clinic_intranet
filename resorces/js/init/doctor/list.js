function register_doctor(dom){
	$("#register_form .sys_msg").html("");
	$.ajax({
		url: $("#base_url").val() + "doctor/register",
		type: "POST",
		data: new FormData(dom),
		contentType: false,
		processData:false,
		success:function(res){
			set_msg(res.msgs);
			if (res.msg != null) Swal.fire({
				title: $("#alert_" + res.type + "_title").val(),
				text: res.msg,
				icon: res.type,
				confirmButtonText: $("#alert_confirm_btn").val()
			}).then((result) => {
				if (res.status == true) location.href = res.move_to;
			});
		}
	});
}

function search_person_dn(){
	$.ajax({
		url: $("#base_url").val() + "ajax_f/search_person",
		type: "POST",
		data: {doc_type_id: $("#dn_doc_type_id").val(), doc_number: $("#dn_doc_number").val()},
		success:function(res){
			Swal.fire({
				title: $("#alert_" + res.type + "_title").val(),
				icon: res.type,
				html: res.msg,
				confirmButtonText: $("#alert_confirm_btn").val()
			}).then((result) => {
				if (res.status == true){
					$("#dn_name").val(res.person.name);
					$("#dn_tel").val(res.person.tel);
					$("#dn_name").addClass("bg-light");
					$("#dn_name").attr("readonly", true);
				}else enable_dn_name();
			});
		}
	});
}

function enable_dn_name(){$("#dn_name").removeClass("bg-light").attr("readonly", false);}

$(document).ready(function() {
	set_datatable("doctor_list", 25, false);
	$("#register_form").submit(function(e) {e.preventDefault(); register_doctor(this);});
	$(".control_bl").on('click',(function(e) {control_bl(this);}));
	$("#btn_search_person_dn").on('click',(function(e) {search_person_dn();}));
	$("#dn_doc_type_id").on('change',(function(e) {enable_dn_name();}));
	$("#dn_doc_number").keyup(function() {enable_dn_name();});
});