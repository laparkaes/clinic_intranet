function update_company_data(dom){
	ajax_form(dom, "config/update_company_data").done(function(res) {
		set_msg(res.msgs);
		swal_redirection(res.type, res.msg, window.location.href);
	});
}

function control_province(dom){
	$("#sl_province").val("");
	$("#sl_province .province").addClass("d-none");
	$("#sl_province .d" + $(dom).val()).removeClass("d-none");
	$("#sl_district").val("");
	$("#sl_district .district").addClass("d-none");
}

function control_district(dom){
	$("#sl_district").val("");
	$("#sl_district .district").addClass("d-none");
	$("#sl_district .p" + $(dom).val()).removeClass("d-none");
}

function control_access(dom){
	var data = {setting: $(dom).is(':checked'), value: $(dom).val()};
	ajax_simple(data, "config/control_access").done(function(res) {
		swal(res.type, res.msg);
		if (res.type == "error") $(dom).prop('checked', !$(dom).is(':checked'));
	});
}

function reset_password(dom){
	ajax_simple_warning({id: $(dom).val()}, "config/reset_password", $("#warning_rpa").val()).done(function(res) {
		swal(res.type, res.msg);
	});
}

function remove_account(dom){
	ajax_simple_warning({id: $(dom).val()}, "config/remove_account", $("#warning_rac").val()).done(function(res) {
		swal_redirection(res.type, res.msg, window.location.href);
	});
}

function register_account(dom){
	ajax_form(dom, "config/register_account").done(function(res) {
		set_msg(res.msgs);
		swal_redirection(res.type, res.msg, window.location.href);
	});
}

function search_person_ra(){
	var data = {doc_type_id: $("#ra_doc_type_id").val(), doc_number: $("#ra_doc_number").val()};
	ajax_simple(data, "ajax_f/search_person").done(function(res) {
		swal(res.type, res.msg);
		if (res.type == "success"){
			$("#ra_name").attr("readonly", true);
			$("#ra_name").val(res.person.name);
			$("#ra_tel").val(res.person.tel);
		}else reset_person();
	});
}

function reset_person(){
	$("#ra_name").attr("readonly", false);
	$("#ra_name").val("");
	$("#ra_tel").val("");
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
	
	if ($(".ex_profile:not(.d-none)").length > 0) $("#rp_no_result_msg").addClass("d-none");
	else $("#rp_no_result_msg").removeClass("d-none");
}

function register_profile(dom){
	ajax_form(dom, "config/register_profile").done(function(res) {
		set_msg(res.msgs);
		swal_redirection(res.type, res.msg, window.location.href);
	});
}

function remove_profile(dom){
	ajax_simple_warning({id: $(dom).val()}, "config/remove_profile", $("#warning_rpr").val()).done(function(res) {
		swal_redirection(res.type, res.msg, window.location.href);
	});
}

function load_more_account(){
	var offset = $("#account_list").children().length;
	ajax_simple({offset: offset}, "config/load_more_account").done(function(res) {
		if (res.length > 0){
			$.each(res, function(index, item) {
				$("#account_list").append('<tr><td>' + (offset + index + 1) + '</td><td>' + item.role + '</td><td>' + item.email + '</td><td>' + item.person + '</td><td class="text-right"><button type="button" class="btn btn-info shadow btn-xs sharp reset_password" value="' + item.id + '"><i class="fas fa-key"></i></button> <button type="button" class="btn btn-danger shadow btn-xs sharp remove_account" value="' + item.id + '"><i class="fas fa-trash"></i></button></td></tr>');
			});
			
			$('.reset_password').off('click').on('click',(function(e) {reset_password(this);}));
			$('.remove_account').off('click').on('click',(function(e) {remove_account(this);}));
		}else $("#btn_load_more_account").remove();
	});
}

function load_more_profile(){
	var offset = $("#profile_list").children().length;
	ajax_simple({offset: offset}, "config/load_more_profile").done(function(res) {
		if (res.length > 0){
			$.each(res, function(index, item) {
				$("#profile_list").append('<tr><td>' + (offset + index + 1) + '</td><td>' + item.name + '</td><td>' + item.exams + '</td><td class="text-right"><button type="button" class="btn btn-danger shadow btn-xs sharp remove_profile" value="' + item.id + '"><i class="fas fa-trash"></i></button></td></tr>');
			});
			
			$('.remove_profile').off('click').on('click',(function(e) {remove_profile(this);}));
		}else $("#btn_load_more_profile").remove();
	});
}

function add_exam_category(dom){
	ajax_form(dom, "config/add_exam_category").done(function(res){
		swal(res.type, res.msg);
	});
}

function remove_exam_category(cat_id){
	ajax_simple({id: cat_id}, "config/remove_exam_category").done(function(res){
		swal(res.type, res.msg);
	});
}

function add_exam(dom){
	ajax_form(dom, "config/add_exam").done(function(res){
		swal(res.type, res.msg);
	});
}

function remove_exam(ex_id){
	ajax_simple({id: ex_id}, "config/remove_exam").done(function(res){
		swal(res.type, res.msg);
	});
}

$(document).ready(function() {
	//account
	$("#form_register_account").submit(function(e) {e.preventDefault(); register_account(this);});
	$("#btn_search_person_ra").on('click',(function(e) {search_person_ra();}));
	$("#ra_doc_type_id").on('change',(function(e) {reset_person();}));
	$("#ra_doc_number").keyup(function() {reset_person();});
	$(".reset_password").on('click',(function(e) {reset_password(this);}));
	$(".remove_account").on('click',(function(e) {remove_account(this);}));
	$(".control_bl_account").on('click',(function(e) {control_bl_group(this, "account");}));
	$("#btn_load_more_account").on('click',(function(e) {load_more_account();}));
	
	//role & access
	$(".chk_access").on('click',(function(e) {control_access(this);}));
	
	//company
	$("#form_update_company_data").submit(function(e) {e.preventDefault(); update_company_data(this);});
	$("#sl_department").change(function() {control_province(this);});
	$("#sl_province").change(function() {control_district(this);});
	
	//profile
	$("#form_register_profile").submit(function(e) {e.preventDefault(); register_profile(this);});
	$("#form_add_exam_category").submit(function(e) {e.preventDefault(); add_exam_category(this);});
	$("#form_add_exam").submit(function(e) {e.preventDefault(); add_exam(this);});
	
	$(".remove_profile").on('click',(function(e) {remove_profile(this);}));
	$("#rp_category").change(function() {filter_exams();});
	$("#rp_filter").keyup(function() {filter_exams();});
	$(".control_bl_profile").on('click',(function(e) {control_bl_group(this, "profile");}));
	$("#btn_load_more_profile").on('click',(function(e) {load_more_profile();}));
	$(".btn_remove_exam_category").on('click',(function(e) {remove_exam_category($(this).val());}));
	$(".btn_remove_exam").on('click',(function(e) {remove_exam($(this).val());}));
	
	//medicine
	
	//log
	set_datatable("log_list", 25, false);
});