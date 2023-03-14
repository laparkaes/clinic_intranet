function cancel_appointment(dom){
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
				url: $("#base_url").val() + "appointment/cancel_appointment",
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

function finish_appointment(dom){
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
				url: $("#base_url").val() + "appointment/finish_appointment",
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

function reschedule_appointment(dom){
	$("#reschedule_form .sys_msg").html("");
	Swal.fire({
		title: $("#alert_warning_title").val(),
		text: $("#warning_are").val(),
		icon: 'warning',
		showCancelButton: true,
		confirmButtonText: $("#alert_confirm_btn").val(),
		cancelButtonText: $("#alert_cancel_btn").val()
	}).then((result) => {
		if (result.isConfirmed){
			$.ajax({
				url: $("#base_url").val() + "appointment/reschedule_appointment",
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

function save_form(name, dom){
	$("#form_" + name + " .sys_msg").html("");
	var type = "error";
	$.ajax({
		url: $("#base_url").val() + "appointment/save_" + name,
		type: "POST",
		data: new FormData(dom),
		contentType: false,
		processData:false,
		success:function(res){
			set_msg(res.msgs);
			if (res.status == true) type = "success";
			if (res.msg != "") Swal.fire({
				title: $("#alert_" + type + "_title").val(),
				icon: type,
				html: res.msg,
				confirmButtonText: $("#alert_confirm_btn").val()
			});
		}
	});
}

function set_diag(diags){
	$("#selected_diags").html("");
	diags.forEach(function (diag) {
		$("#selected_diags").append('<tr class="text-left"><td class="align-top" style="width:120px;">' + diag.code + '</td><td>' + diag.description + '</td><td class="align-top" class="text-right"><button type="button" class="btn tp-btn-light btn-danger p-0 btn_delete_diag" value="' + diag.id + '"><i class="fas fa-minus"></i></button></td></tr>');
	});
	$(".btn_delete_diag").on('click',(function(e) {delete_diag(this);}));
}

function add_diag(dom){
	$.ajax({
		url: $("#base_url").val() + "appointment/add_diag",
		type: "POST",
		data: {appointment_id: $("#appointment_id").val(), diag_id: $(dom).val()},
		success:function(res){
			if (res.status == true){
				set_diag(res.diags);
				swal("success", res.msg);
			}else swal("error", res.msg);
		}
	});
}

function delete_diag(dom){
	$.ajax({
		url: $("#base_url").val() + "appointment/delete_diag",
		type: "POST",
		data: {appointment_id: $("#appointment_id").val(), diag_id: $(dom).val()},
		success:function(res){
			if (res.status == true){
				set_diag(res.diags);
				 swal("success", res.msg);
			}else swal("error", res.msg);
		}
	});
}

function search_diag(dom){
	$("#form_search_diag .sys_msg").html("");
	$.ajax({
		url: $("#base_url").val() + "appointment/search_diag",
		type: "POST",
		data: new FormData(dom),
		contentType: false,
		processData:false,
		success:function(res){
			set_msg(res.msgs);
			if (res.status == true){
				$("#di_diagnosis_msg").html(res.qty);
				$("#search_diag_result").html("");
				res.diags.forEach(function (diag) {
					$("#search_diag_result").append('<tr class="text-left"><td class="align-top" style="width:120px;">' + diag.code + '</td><td>' + diag.description + '</td><td class="align-top" class="text-right"><button type="button" class="btn tp-btn-light btn-success p-0 btn_add_diag" value="' + diag.id + '"><i class="fas fa-plus"></i></button></td></tr>');
				});
				$(".btn_add_diag").on('click',(function(e) {add_diag(this);}));
			}
		}
	});
}

function filter_exam_checkbox(){
	var category_id = $("#ex_category").val();
	var search = $("#ex_search").val().toLowerCase();
	var result_qty;
	var f_category;
	var f_search;
	
	//profiles handle
	result_qty = 0;
	$("#list_exams .examination_profiles").addClass("d-none");
	$("#list_exams .examination_profiles").each(function(index, elem){
		if (category_id == "") f_category = true; else f_category = $(elem).hasClass("exam_category_" + category_id);
		f_search = $(elem).find(".search_filter").html().toLowerCase().includes(search);
		if (f_category && f_search){
			$(elem).removeClass("d-none");
			result_qty++;
		}
	});
	if (result_qty == 0) $("#exam_profile_no_result").removeClass("d-none");
	else $("#exam_profile_no_result").addClass("d-none");
	
	//examinations handle
	result_qty = 0;
	$("#list_exams .examinations").addClass("d-none");
	$("#list_exams .examinations").each(function(index, elem){
		if (category_id == "") f_category = true; else f_category = $(elem).hasClass("exam_category_" + category_id);
		f_search = $(elem).find(".search_filter").html().toLowerCase().includes(search);
		if (f_category && f_search){
			$(elem).removeClass("d-none");
			result_qty++;
		}
	});
	if (result_qty == 0) $("#exam_no_result").removeClass("d-none");
	else $("#exam_no_result").addClass("d-none");
}

function set_exam_checkbox(exams, profiles, checked_profs, checked_exams){
	$("#selected_exams").html("");
	
	//profiles handle
	profiles.forEach(function(element, index){
		$("#selected_exams").append('<tr class="text-left"><td class="align-top" style="width:120px;">' + element.type + '</td><td><div>' + element.name + '</div><div><small>' + element.exams + '</small></div></td><td class="align-top" class="text-right"><button type="button" class="btn tp-btn-light btn-danger p-0 btn_delete_exam_profile" value="' + element.id + '"><i class="fas fa-minus"></i></button></td></tr>');
	});
	$(".btn_delete_exam_profile").on('click',(function(e) {delete_exam_profile(this);}));
	
	//examinations handle
	exams.forEach(function(element, index){
		$("#selected_exams").append('<tr class="text-left"><td class="align-top" style="width:120px;">' + element.type + '</td><td>' + element.name + '</td><td class="align-top" class="text-right"><button type="button" class="btn tp-btn-light btn-danger p-0 btn_delete_exam" value="' + element.id + '"><i class="fas fa-minus"></i></button></td></tr>');
	});
	$(".btn_delete_exam").on('click',(function(e) {delete_exam(this);}));
	
	//checkbox setting
	$(".chk_exam_profile").prop("checked", false);
	checked_profs.forEach(function(element){
		$("#exam_profile_" + element).prop("checked", true);
	});
	
	$(".chk_exam").prop("checked", false);
	checked_exams.forEach(function(element){
		$("#exam_" + element).prop("checked", true);
	});
}

function process_exam_profile(data){
	$.ajax({
		url: $("#base_url").val() + "appointment/control_examination_profile",
		type: "POST",
		data: data,
		success:function(res){
			set_exam_checkbox(res.examinations, res.profiles, res.checked_profs, res.checked_exams);
			if (res.status == false) swal("error", res.msg);
		}
	});
}

function delete_exam_profile(dom){
	process_exam_profile({checked: "", id: $(dom).val(), appointment_id: $("#appointment_id").val()});
}

function control_exam_profile(dom){
	process_exam_profile({checked: $(dom).prop("checked"), id: $(dom).val(), appointment_id: $("#appointment_id").val()});
}

function process_exam(data){
	$.ajax({
		url: $("#base_url").val() + "appointment/control_examination",
		type: "POST",
		data: data,
		success:function(res){
			set_exam_checkbox(res.examinations, res.profiles, res.checked_profs, res.checked_exams);
			if (res.status == false) swal("error", res.msg);
		}
	});
}

function delete_exam(dom){
	process_exam({checked: "", id: $(dom).val(), appointment_id: $("#appointment_id").val()});
}

function control_exam(dom){
	process_exam({checked: $(dom).prop("checked"), id: $(dom).val(), appointment_id: $("#appointment_id").val()});
}

function filter_img_checkbox(){
	var category_id = $("#img_category").val();
	var search = $("#img_search").val().toLowerCase();
	var result_qty;
	var f_category;
	var f_search;
	
	//examinations handle
	result_qty = 0;
	$("#list_images .images").addClass("d-none");
	$("#list_images .images").each(function(index, elem){
		if (category_id == "") f_category = true; else f_category = $(elem).hasClass("image_category_" + category_id);
		f_search = $(elem).find(".search_filter").html().toLowerCase().includes(search);
		if (f_category && f_search){
			$(elem).removeClass("d-none");
			result_qty++;
		}
	});
	if (result_qty == 0) $("#img_no_result").removeClass("d-none");
	else $("#img_no_result").addClass("d-none");
}

function set_img_checkbox(images, checked_images){
	$("#selected_images").html("");
	
	images.forEach(function(element, index){
		$("#selected_images").append('<tr class="text-left"><td style="width:120px;">' + element.category + '</td><td>' + element.image + '</td><td class="text-right"><button type="button" class="btn tp-btn-light btn-danger btn-xs btn_delete_image" value="' + element.image_id + '"><i class="fas fa-minus"></i></button></td></tr>');
	});
	$(".btn_delete_image").on('click',(function(e) {delete_image(this);}));
	
	$(".chk_img").prop("checked", false);
	checked_images.forEach(function(element){
		$("#img_" + element).prop("checked", true);
	});
}

function process_image(data){
	$.ajax({
		url: $("#base_url").val() + "appointment/control_image",
		type: "POST",
		data: data,
		success:function(res){
			set_img_checkbox(res.images, res.checked_images);
			if (res.status == false) swal("error", res.msg);
		}
	});
}

function delete_image(dom){
	process_image({checked: "", image_id: $(dom).val(), appointment_id: $("#appointment_id").val()});
}

function control_image(dom){
	process_image({checked: $(dom).prop("checked"), image_id: $(dom).val(), appointment_id: $("#appointment_id").val()});
}

function set_therapy(therapies){
	$("#selected_therapies").html("");
	therapies.forEach(function(element, index){
		$("#selected_therapies").append('<tr class="text-left"><td><div>' + element.physical_therapy + '</div><small>' + element.sub_txt + '</small></td><td class="text-right"><button type="button" class="btn tp-btn-light btn-danger btn-xs btn_delete_therapy" value="' + element.id + '"><i class="fas fa-minus"></i></button></td></tr>');
	});
	
	$(".btn_delete_therapy").on('click',(function(e) {delete_therapy(this);}));
}

function add_therapy(dom){
	$("#form_add_therapy .sys_msg").html("");
	$.ajax({
		url: $("#base_url").val() + "appointment/add_therapy",
		type: "POST",
		data: new FormData(dom),
		contentType: false,
		processData:false,
		success:function(res){
			set_therapy(res.therapies);
			if (res.status == true) $("#form_add_therapy")[0].reset();
			else{
				set_msg(res.msgs);
				if (res.msg != null) swal("error", res.msg);
			}
		}
	});
}

function delete_therapy(dom){
	console.log(dom);
	$.ajax({
		url: $("#base_url").val() + "appointment/delete_therapy",
		type: "POST",
		data: {appointment_id: $("#appointment_id").val(), id: $(dom).val()},
		success:function(res){
			set_therapy(res.therapies);
			if (res.status == false) swal("error", res.msg);
		}
	});
}

function set_medicine(medicines){
	$("#selected_medicines").html("");
	medicines.forEach(function(element, index){
		$("#selected_medicines").append('<tr class="text-left"><td><div>' + element.medicine + '</div><small>' + element.sub_txt + '</small></td><td class="text-right"><button type="button" class="btn tp-btn-light btn-danger btn-xs btn_delete_medicine" value="' + element.id + '"><i class="fas fa-minus"></i></button></td></tr>');
	});
	
	$(".btn_delete_medicine").on('click',(function(e) {delete_medicine(this);}));
}

function add_medicine(dom){
	$("#form_add_medicine .sys_msg").html("");
	$.ajax({
		url: $("#base_url").val() + "appointment/add_medicine",
		type: "POST",
		data: new FormData(dom),
		contentType: false,
		processData:false,
		success:function(res){
			set_medicine(res.medicines);
			if (res.status == true) $("#form_add_medicine")[0].reset();
			else{
				set_msg(res.msgs);
				if (res.msg != null) swal("error", res.msg);
			}
		}
	});
}

function delete_medicine(dom){
	$.ajax({
		url: $("#base_url").val() + "appointment/delete_medicine",
		type: "POST",
		data: {id: $(dom).val(), appointment_id: $("#appointment_id").val()},
		success:function(res){
			set_medicine(res.medicines);
			if (res.status == false) swal("error", res.msg);
		}
	});
}

function control_process_forms(dom){
	$(".btn_process").removeClass("btn-primary");
	$(".btn_process").addClass("btn-outline-primary");
	
	$(dom).removeClass("btn-outline-primary");
	$(dom).addClass("btn-primary");
	
	$(".process").addClass("d-none");
	$("." + $(dom).val()).removeClass("d-none");
}

function set_bmi(dom){
	var parent_form = $(dom).parents("form");
	var weight = $(parent_form).find('input[name="v_weight"]').val();
	var height = $(parent_form).find('input[name="v_height"]').val() / 100;//convert from centimeter to meter
	var bmi = 0;
	if ((weight > 0) && (height > 0)){
		bmi = Math.round(weight / height / height * 100) / 100;
		$(parent_form).find('input[name="v_imc"]').val(bmi);
	}else $(parent_form).find('input[name="v_imc"]').val("");
	
	var bmi_class = "";
	var bmi_color = "";
	var bc_dom = $(parent_form).find('input[name="v_imc_class"]');
	
	switch (true) {
		case (bmi < 18.5): bmi_class = "under_weight"; bmi_color = "info"; break;
		case (bmi < 25): bmi_class = "normal"; bmi_color = "primary"; break;
		case (bmi < 30): bmi_class = "overweight"; bmi_color = "secondary"; break;
		case (bmi < 35): bmi_class = "obesity_1"; bmi_color = "warning"; break;
		case (bmi < 40): bmi_class = "obesity_2"; bmi_color = "danger"; break;
		default: bmi_class = "obesity_3"; bmi_color = "danger";
	}
	
	$(bc_dom).removeClass("text-primary text-secondary text-info text-warning text-danger");
	$(bc_dom).removeClass("border-primary border-secondary border-info border-warning border-danger");
	$(bc_dom).addClass("text-" + bmi_color);
	$(bc_dom).addClass("border-" + bmi_color);
	$(bc_dom).val($("#bmi_class_" + bmi_class).val());
}

function load_doctor_schedule(){
	var doctor_id = $("#ra_doctor").val();
	var date = $("#ra_date").val();
	
	if ((doctor_id != "") && (date != "")){
		$.ajax({
			url: $("#base_url").val() + "appointment/load_doctor_schedule",
			type: "POST",
			data: {doctor_id: doctor_id, date: date},
			success:function(res){
				$("#rp_schedule").html("");
				if (res.status == true){
					res.data.forEach((e) => {
						$("#rp_schedule").append('<li class="list-group-item d-flex justify-content-between py-2">' + e + '</li>');
					});	
				}else{
					Swal.fire({
						title: $("#alert_error_title").val(),
						html: res.msg,
						icon: 'error',
						confirmButtonText: $("#alert_confirm_btn").val()
					});
				}
			}
		});
	}
}

$(document).ready(function() {
	//general
	$(".btn_process").on('click',(function(e) {control_process_forms(this);}));
	$("#btn_cancel").on('click',(function(e) {cancel_appointment(this);}));
	$("#btn_finish").on('click',(function(e) {finish_appointment(this);}));
	$("#btn_reschedule").on('click',(function(e) {load_doctor_schedule();}));
	
	//reschedule
	$("#reschedule_form").submit(function(e) {e.preventDefault(); reschedule_appointment(this);});
	$(".doc_schedule").change(function() {load_doctor_schedule();});
	
	//information
	$("#form_basic_data").submit(function(e) {e.preventDefault(); save_form("basic_data", this);});
	$("#form_personal_information").submit(function(e) {e.preventDefault(); save_form("personal_information", this);});
	
	//triage
	$("#form_triage").submit(function(e) {e.preventDefault(); save_form("triage", this);});
	$(".set_bmi").keyup(function() {set_bmi(this);});
	set_bmi($(".set_bmi"));
	
	//appointment - anamnesis
	$("#form_anamnesis").submit(function(e) {e.preventDefault(); save_form("anamnesis", this);});
	
	//appointment - physical exam
	$("#form_physical_exam").submit(function(e) {e.preventDefault(); save_form("physical_exam", this);});
	
	//appointment - diagnostic
	$("#form_search_diag").submit(function(e) {e.preventDefault(); search_diag(this);});
	$(".btn_delete_diag").on('click',(function(e) {delete_diag(this);}));
	
	//appointment - result
	$("#form_result").submit(function(e) {e.preventDefault(); save_form("result", this);});
	
	//appointment - prescription - examination
	$("#ex_category").change(function() {filter_exam_checkbox();});
	$("#ex_search").keyup(function() {filter_exam_checkbox();});
	$(".chk_exam_profile").change(function() {control_exam_profile(this);});
	$(".chk_exam").change(function() {control_exam(this);});
	$(".btn_delete_exam_profile").on('click',(function(e) {delete_exam_profile(this);}));
	$(".btn_delete_exam").on('click',(function(e) {delete_exam(this);}));
	
	//appointment - prescription - image
	$("#img_category").change(function() {filter_img_checkbox();});
	$("#img_search").keyup(function() {filter_img_checkbox();});
	$(".chk_img").change(function() {control_image(this);});
	$(".btn_delete_image").on('click',(function(e) {delete_image(this);}));
	
	//appointment - prescription - therapy
	$("#form_add_therapy").submit(function(e) {e.preventDefault(); add_therapy(this);});
	$(".btn_delete_therapy").on('click',(function(e) {delete_therapy(this);}));
	
	//appointment - prescription - medicine
	$("#form_add_medicine").submit(function(e) {e.preventDefault(); add_medicine(this);});
	$(".btn_delete_medicine").on('click',(function(e) {delete_medicine(this);}));
});