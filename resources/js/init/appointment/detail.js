function cancel_appointment(dom){
	ajax_simple_warning({id: $(dom).val()}, "appointment/cancel", "wm_appointment_cancel").done(function(res) {
		swal_redirection(res.type, res.msg, window.location.href);
	});
}

function finish_appointment(dom){
	ajax_simple_warning({id: $(dom).val()}, "appointment/finish", "wm_appointment_finish").done(function(res) {
		swal_redirection(res.type, res.msg, window.location.href);
	});
}

function reschedule_appointment(dom){
	$("#reschedule_form .sys_msg").html("");
	ajax_form_warning(dom, "appointment/reschedule", "wm_appointment_reschedule").done(function(res) {
		set_msg(res.msgs);
		swal_redirection(res.type, res.msg, window.location.href);
	});
}

function save_form(name, dom){
	$("#form_" + name + " .sys_msg").html("");
	ajax_form(dom, "appointment/save_" + name).done(function(res) {
		set_msg(res.msgs);
		swal(res.type, res.msg);
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
	var data = {appointment_id: $("#appointment_id").val(), diag_id: $(dom).val()};
	ajax_simple(data, "appointment/add_diag").done(function(res) {
		set_diag(res.diags);
		swal(res.type, res.msg);
	});
}

function delete_diag(dom){
	var data = {appointment_id: $("#appointment_id").val(), diag_id: $(dom).val()};
	ajax_simple(data, "appointment/delete_diag").done(function(res) {
		set_diag(res.diags);
		swal(res.type, res.msg);
	});
}

function search_diag(dom){
	$("#form_search_diag .sys_msg").html("");
	ajax_form(dom, "appointment/search_diag").done(function(res) {
		set_msg(res.msgs);
		if (res.type == "success"){
			$("#di_diagnosis_msg").html(res.qty);
			$("#search_diag_result").html("");
			res.diags.forEach(function (diag) {
				$("#search_diag_result").append('<tr class="text-left"><td class="align-top" style="width:120px;">' + diag.code + '</td><td>' + diag.description + '</td><td class="align-top" class="text-right"><button type="button" class="btn tp-btn-light btn-success p-0 btn_add_diag" value="' + diag.id + '"><i class="fas fa-plus"></i></button></td></tr>');
			});
			$(".btn_add_diag").on('click',(function(e) {add_diag(this);}));
		}
	});
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
	ajax_form(dom, "appointment/add_therapy").done(function(res) {
		set_therapy(res.therapies);
		set_msg(res.msgs);
		swal(res.type, res.msg);
		if (res.type == "success") $("#form_add_therapy")[0].reset();
	});
}

function delete_therapy(dom){
	var data = {appointment_id: $("#appointment_id").val(), id: $(dom).val()};
	ajax_simple(data, "appointment/delete_therapy").done(function(res) {
		set_therapy(res.therapies);
		swal(res.type, res.msg);
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
	ajax_form(dom, "appointment/add_medicine").done(function(res) {
		set_medicine(res.medicines);
		set_msg(res.msgs);
		swal(res.type, res.msg);
		if (res.type == "success") $("#form_add_medicine")[0].reset();
	});
}

function delete_medicine(dom){
	var data = {appointment_id: $("#appointment_id").val(), id: $(dom).val()};
	ajax_simple(data, "appointment/delete_medicine").done(function(res) {
		set_medicine(res.medicines);
		swal(res.type, res.msg);
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

function load_doctor_schedule_appointment(){
	$("#rp_schedule").html('<div class="text-center mt-5"><i class="fas fa-spinner fa-spin fa-5x"></i></div>');
	load_doctor_schedule_n($("#ra_doctor").val(), $("#ra_date").val()).done(function(res) {
		$("#rp_schedule").html(res);
		$("#rp_schedule .sch_cell").on('click',(function(e) {set_time_dom("#ra_hour", "#ra_min", this);}));
		set_time_sl("ra", "#rp_schedule");
	});
	
}

function control_reschedule_form(){
	if ($("#app_reschedule").hasClass("d-none")) {
		load_doctor_schedule_appointment();
		$("#app_reschedule").removeClass("d-none");
		$("#app_info").addClass("d-none");
	}else{
		$("#app_reschedule").addClass("d-none");
		$("#app_info").removeClass("d-none");
	}
}

function filter_exam(cat_id){
	if (cat_id == "") $(".exam_cat").removeClass("d-none");
	else{
		$(".exam_cat").addClass("d-none");
		$(".exam_cat_" + cat_id).removeClass("d-none");
	}
}

function set_profiles_exams(profiles, exams){
	$("#tbody_exams_profiles").html("");
	
	$.each(profiles, function(index, value) {
		$("#tbody_exams_profiles").append('<tr><td>' + value.type + '</td><td>' + value.name + '</td><td>' + value.exams + '</td><td class="text-right"><button type="button" class="btn btn-danger shadow btn-xs sharp btn_remove_exam_profile" value="' + value.id + '"><i class="fas fa-trash"></i></button></td></tr>');
	});
	
	$.each(exams, function(index, value) {
		$("#tbody_exams_profiles").append('<tr><td>' + value.type + '</td><td>-</td><td>' + value.name + '</td><td class="text-right"><button type="button" class="btn btn-danger shadow btn-xs sharp btn_remove_exam" value="' + value.id + '"><i class="fas fa-trash"></i></button></td></tr>');
	});
	
	$(".btn_remove_exam_profile").on('click',(function(e) {remove_exam_profile($(this).val());}));
	$(".btn_remove_exam").on('click',(function(e) {remove_exam($(this).val());}));
}

function add_exam_profile(profile_id){
	var data = {appointment_id: $("#appointment_id").val(), profile_id: profile_id};
	ajax_simple(data, "appointment/add_exam_profile").done(function(res) {
		set_profiles_exams(res.profiles, res.exams);
		swal(res.type, res.msg); 
	});
}

function add_exam(exam_id){
	var data = {appointment_id: $("#appointment_id").val(), examination_id: exam_id};
	ajax_simple(data, "appointment/add_exam").done(function(res) {
		set_profiles_exams(res.profiles, res.exams);
		swal(res.type, res.msg); 
	});
}

function remove_exam_profile(profile_id){
	var data = {appointment_id: $("#appointment_id").val(), profile_id: profile_id};
	ajax_simple(data, "appointment/remove_exam_profile").done(function(res) {
		set_profiles_exams(res.profiles, res.exams);
		swal(res.type, res.msg); 
	});
}

function remove_exam(exam_id){
	var data = {appointment_id: $("#appointment_id").val(), examination_id: exam_id};
	ajax_simple(data, "appointment/remove_exam").done(function(res) {
		set_profiles_exams(res.profiles, res.exams);
		swal(res.type, res.msg); 
	});
}

function filter_img_sl(img_cat_id){
	$("#sl_aux_img").val("");
	$(".img_cat").addClass("d-none");
	if (img_cat_id != "") $(".img_cat_" + img_cat_id).removeClass("d-none");
}

function set_image(imgs){
	$("#tbody_images").html("");
	
	$.each(imgs, function(index, value) {
		$("#tbody_images").append('<tr><td>' + value.category + '</td><td>' + value.name + '</td><td class="text-right"><button type="button" class="btn btn-danger shadow btn-xs sharp btn_remove_image" value="' + value.image_id + '"><i class="fas fa-trash"></i></button></td></tr>');
	});
	
	$(".btn_remove_image").on('click',(function(e) {remove_image($(this).val());}));
}

function add_img(img_id){
	var data = {appointment_id: $("#appointment_id").val(), image_id: img_id};
	ajax_simple(data, "appointment/add_image").done(function(res) {
		set_image(res.images);
		swal(res.type, res.msg); 
	});
}

function remove_image(image_id){
	var data = {appointment_id: $("#appointment_id").val(), image_id: image_id};
	ajax_simple(data, "appointment/remove_image").done(function(res) {
		set_image(res.images);
		swal(res.type, res.msg); 
	});
}

$(document).ready(function() {
	//general
	load_doctor_schedule_appointment();
	$(".btn_process").on('click',(function(e) {control_process_forms(this);}));
	$("#btn_cancel").on('click',(function(e) {cancel_appointment(this);}));
	$("#btn_finish").on('click',(function(e) {finish_appointment(this);}));
	$("#btn_reschedule, #btn_reschedule_cancel").on('click',(function(e) {control_reschedule_form();}));
	$("#ic_doctor_schedule_w").on('click',(function(e) {load_doctor_schedule_weekly($("#ra_doctor").val(), null, "bl_weekly_schedule");}));
	
	//reschedule
	$("#reschedule_form").submit(function(e) {e.preventDefault(); reschedule_appointment(this);});
	$(".doc_schedule").change(function() {load_doctor_schedule_appointment();});
	$("#ra_hour, #ra_min").change(function() {set_time_sl("ra", "#rp_schedule");});
	
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
	$("#sl_exam_category").change(function() {filter_exam($(this).val());});
	$("#btn_add_exam_profile").on('click',(function(e) {add_exam_profile($("#sl_profile_exam").val());}));
	$("#btn_add_exam").on('click',(function(e) {add_exam($("#sl_exam").val());}));
	$(".btn_remove_exam_profile").on('click',(function(e) {remove_exam_profile($(this).val());}));
	$(".btn_remove_exam").on('click',(function(e) {remove_exam($(this).val());}));
	
	//appointment - prescription - image
	$("#sl_aux_img_category").change(function() {filter_img_sl($(this).val());});
	$("#btn_add_img").on('click',(function(e) {add_img($("#sl_aux_img").val());}));
	$(".btn_remove_image").on('click',(function(e) {remove_image($(this).val());}));
	
	//appointment - prescription - therapy
	$("#form_add_therapy").submit(function(e) {e.preventDefault(); add_therapy(this);});
	$(".btn_delete_therapy").on('click',(function(e) {delete_therapy(this);}));
	
	//appointment - prescription - medicine
	$("#form_add_medicine").submit(function(e) {e.preventDefault(); add_medicine(this);});
	$(".btn_delete_medicine").on('click',(function(e) {delete_medicine(this);}));
});