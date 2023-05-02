function update_company_data(dom){
	$("#form_update_company .sys_msg").html("");
	$.ajax({
		url: $("#base_url").val() + "config/update_company_data",
		type: "POST",
		data: new FormData(dom),
		contentType: false,
		processData:false,
		success:function(res){
			Swal.fire({
				title: $("#alert_" + res.type + "_title").val(),
				icon: res.type,
				html: res.msg,
				confirmButtonText: $("#alert_confirm_btn").val()
			}).then((result) => {
				if (res.status == true) location.reload();
				else set_msg(res.msgs);
			});
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

function control_role_access(dom){
	$.ajax({
		url: $("#base_url").val() + "config/control_role_access",
		type: "POST",
		data: {setting: $(dom).is(':checked'), value: $(dom).val()}
	});
}

function reset_password(dom){
	Swal.fire({
		title: $("#alert_warning_title").val(),
		html: $("#warning_rpa").val(),
		icon: 'warning',
		showCancelButton: true,
		confirmButtonText: $("#alert_confirm_btn").val(),
		cancelButtonText: $("#alert_cancel_btn").val()
	}).then((result) => {
		if (result.isConfirmed){
			$.ajax({
				url: $("#base_url").val() + "config/reset_password",
				type: "POST",
				data: {id: $(dom).val()},
				success:function(res){
					swal(res.type, res.msg);
				}
			});
		}
	});
}

function remove_account(dom){
	Swal.fire({
		title: $("#alert_warning_title").val(),
		html: $("#warning_rac").val(),
		icon: 'warning',
		showCancelButton: true,
		confirmButtonText: $("#alert_confirm_btn").val(),
		cancelButtonText: $("#alert_cancel_btn").val()
	}).then((result) => {
		if (result.isConfirmed){
			$.ajax({
				url: $("#base_url").val() + "config/remove_account",
				type: "POST",
				data: {id: $(dom).val()},
				success:function(res){
					Swal.fire({
						title: $("#alert_" + res.type + "_title").val(),
						icon: res.type,
						html: res.msg,
						confirmButtonText: $("#alert_confirm_btn").val()
					}).then((result) => {
						if (res.status == true) location.reload();
					});
				}
			});
		}
	});
}

function register_account(dom){
	$("#form_register_account .sys_msg").html("");
	$.ajax({
		url: $("#base_url").val() + "config/register_account",
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
				if (res.status == true) location.reload();
			});
		}
	});
}

function search_person_ra(){
	$.ajax({
		url: $("#base_url").val() + "ajax_f/search_person",
		type: "POST",
		data: {doc_type_id: $("#ra_doc_type_id").val(), doc_number: $("#ra_doc_number").val()},
		success:function(res){
			Swal.fire({
				title: $("#alert_" + res.type + "_title").val(),
				icon: res.type,
				html: res.msg,
				confirmButtonText: $("#alert_confirm_btn").val()
			}).then((result) => {
				if (res.status == true){
					$("#ra_name").val(res.person.name);
					$("#ra_tel").val(res.person.tel);
					$("#ra_email").val(res.person.email);
					$("#ra_name").addClass("bg-light");
					$("#ra_name").attr("readonly", true);
				}else{
					$("#ra_name").removeClass("bg-light").attr("readonly", false);
				}
			});
		}
	});
}

function control_bl_group(dom, group){
	$(dom).parent().children().removeClass("btn-primary");
	$(dom).parent().children().addClass("btn-outline-primary");
	
	$(dom).removeClass("btn-outline-primary");
	$(dom).addClass("btn-primary");
	
	$(".bl_" + group).addClass("d-none");
	$("#" + $(dom).val()).removeClass("d-none");
}

function control_sl_group(dom){
	$(".sl_group").removeClass("active");
	$(dom).addClass("active");
	
	$(".sl_values").addClass("d-none");
	$("#sl_" + $(dom).val()).removeClass("d-none");
	
	$("#sl_add_code").val($(dom).val());
}

function add_sl_value(dom){
	$.ajax({
		url: $("#base_url").val() + "config/add_sl_value",
		type: "POST",
		data: new FormData(dom),
		contentType: false,
		processData:false,
		success:function(res){
			Swal.fire({
				title: $("#alert_" + res.type + "_title").val(),
				icon: res.type,
				html: res.msg,
				confirmButtonText: $("#alert_confirm_btn").val()
			}).then((result) => {
				if (res.status == true){
					$("#asl_description").val("");
					$("#sl_" + res.new_value.code).append('<button type="button" class="btn btn-outline-primary btn-xs mb-1 btn_sl_remove" value="' + res.new_value.id + '">' + res.new_value.description + ' <i class="fa fa-close ml-1"></i></button>&nbsp;');
					$("#sl_" + res.new_value.code + " .btn_sl_remove").last().on('click',(function(e) {remove_sl_value(this);}));
					$("#btn_sl_" + res.new_value.code).find(".badge").html($("#sl_" + res.new_value.code).find("button").length);
				}
			});
		}
	});
}

function remove_sl_value(dom){
	$.ajax({
		url: $("#base_url").val() + "config/remove_sl_value",
		type: "POST",
		data: {id: $(dom).val()},
		success:function(res){
			Swal.fire({
				title: $("#alert_" + res.type + "_title").val(),
				icon: res.type,
				html: res.msg,
				confirmButtonText: $("#alert_confirm_btn").val()
			}).then((result) => {
				if (res.status == true){
					$(dom).remove();
					$("#btn_sl_" + res.removed_value.code).find(".badge").html($("#sl_" + res.removed_value.code).find("button").length);
				}
			});
		}
	});
}

function filter_exams(){
	var profile_id = $("#rp_category").val();
	var filter = $("#rp_filter").val();
	var exams = $('.ex_profile');
	var is_show_cat;//category
	var is_show_fil;//filter
	var is_show;//result
	
	$.each(exams, function(index, value) {
		if (profile_id == "") is_show_cat = true;
		else{
			if ($(value).hasClass("ex_profile_" + profile_id)) is_show_cat = true;
			else is_show_cat = false;
		}
		
		if (filter == "") is_show_fil = true;
		else{
			if ($(value).find("label").text().toUpperCase().indexOf(filter.toUpperCase()) >= 0) is_show_fil = true;
			else is_show_fil = false;	
		}
		
		is_show = (is_show_cat && is_show_fil);
		
		if (is_show == true) $(value).removeClass("d-none");
		else $(value).addClass("d-none");
	});
	
	if ($(".ex_profile:not(.d-none)").length > 1) $("#rp_no_result_msg").addClass("d-none");
	else $("#rp_no_result_msg").removeClass("d-none");
}

function control_exams(profile_id){
	if (profile_id == "") $(".ex_profile").removeClass("d-none");
	else{
		$(".ex_profile").addClass("d-none");
		$(".ex_profile_" + profile_id).removeClass("d-none");	
	}
}

function filter_exams1(filter){
	var is_show;
	var exams = $('.ex_profile:not(.d-none)');
	$.each(exams, function(index, value) {
		if (filter == "") is_show = true;
		else{
			if ($(value).find("label").text().indexOf(filter) >= 0) is_show = true;
			else is_show = false;	
		}
		
		if (is_show == true) $(value).removeClass("d-none");
		else $(value).addClass("d-none");
	});
}

function register_profile(dom){
	$("#form_register_profile .sys_msg").html("");
	$.ajax({
		url: $("#base_url").val() + "config/register_profile",
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
				if (res.status == true) location.reload();
			});
		}
	});
}

$(document).ready(function() {
	//account role
	$("#form_register_account").submit(function(e) {e.preventDefault(); register_account(this);});
	$("#btn_search_person_ra").on('click',(function(e) {search_person_ra();}));
	$(".reset_password").on('click',(function(e) {reset_password(this);}));
	$(".remove_account").on('click',(function(e) {remove_account(this);}));
	$(".control_bl_account").on('click',(function(e) {control_bl_group(this, "account");}));
	set_datatable("account_list", 25, false);
	
	//role & access
	$(".chk_access").on('click',(function(e) {control_role_access(this);}));
	
	//company
	$("#form_update_company_data").submit(function(e) {e.preventDefault(); update_company_data(this);});
	$("#sl_department").change(function() {control_province();});
	$("#sl_province").change(function() {control_district();});
	
	//profile
	$("#form_register_profile").submit(function(e) {e.preventDefault(); register_profile(this);});
	$("#rp_category").change(function() {filter_exams();});
	$("#rp_filter").keyup(function() {filter_exams();});
	$(".control_bl_profile").on('click',(function(e) {control_bl_group(this, "profile");}));
	set_datatable("profile_list", 25, false);
	
	//system
	$("#form_add_sl_value").submit(function(e) {e.preventDefault(); add_sl_value(this);});
	$(".sl_group").on('click',(function(e) {control_sl_group(this);}));
	$(".btn_sl_remove").on('click',(function(e) {remove_sl_value(this);}));
	
	//log
	set_datatable("log_list", 25, false);
});