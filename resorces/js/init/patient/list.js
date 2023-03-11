function register_patient(dom){
	$(".sys_msg").html("");
	$.ajax({
		url: $("#base_url").html() + "patient/register",
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

function search_person_pn(){
	$.ajax({
		url: $("#base_url").val() + "ajax_f/search_person",
		type: "POST",
		data: {doc_type_id: $("#pn_doc_type_id").val(), doc_number: $("#pn_doc_number").val()},
		success:function(res){
			Swal.fire({
				title: $("#alert_" + res.type + "_title").val(),
				icon: res.type,
				html: res.msg,
				confirmButtonText: $("#alert_confirm_btn").val()
			}).then((result) => {
				if (res.status == true){
					$("#pn_name").val(res.person.name);
					$("#pn_tel").val(res.person.tel);
					$("#pn_name").addClass("bg-light");
					$("#pn_name").attr("readonly", true);
				}else enable_pn_name();
			});
		}
	});
}

function enable_pn_name(){$("#pn_name").removeClass("bg-light").attr("readonly", false);}

$(document).ready(function() {
	set_datatable("patient_list", 25, false);
	$(".control_bl").on('click',(function(e) {control_bl(this);}));
	$("#register_form").submit(function(e) {e.preventDefault(); register_patient(this);});
	$("#btn_search_person_pn").on('click',(function(e) {search_person_pn();}));
	$("#pn_doc_type_id").on('change',(function(e) {enable_pn_name();}));
	$("#pn_doc_number").keyup(function() {enable_pn_name();});
});