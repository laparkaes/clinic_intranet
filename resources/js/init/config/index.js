function control_bl_group(dom, group){
	$(dom).parent().children().removeClass("btn-primary");
	$(dom).parent().children().addClass("btn-outline-primary");
	
	$(dom).removeClass("btn-outline-primary");
	$(dom).addClass("btn-primary");
	
	$(".bl_" + group).addClass("d-none");
	$("#" + $(dom).val()).removeClass("d-none");
}

/* start access */
function control_access(dom){
	var data = {setting: $(dom).is(':checked'), value: $(dom).val()};
	ajax_simple(data, "config/control_access").done(function(res) {
		swal(res.type, res.msg);
		if (res.type == "error") $(dom).prop('checked', !$(dom).is(':checked'));
	});
}
/* end access */

/* start company */
function control_province(department_id){
	$("#uc_province_id").val("");
	$("#uc_province_id .province").addClass("d-none");
	$("#uc_province_id .d" + department_id).removeClass("d-none");
	$("#uc_district_id").val("");
	$("#uc_district_id .district").addClass("d-none");
}

function control_district(province_id){
	$("#uc_district_id").val("");
	$("#uc_district_id .district").addClass("d-none");
	$("#uc_district_id .p" + province_id).removeClass("d-none");
}

function search_company(){
	ajax_simple({tax_id: $("#uc_tax_id").val()}, "ajax_f/search_company").done(function(res) {
		swal(res.type, res.msg);
		control_province(res.company.department_id);
		control_district(res.company.province_id);
		$("#uc_tax_id").val(res.company.tax_id);
		$("#uc_name").val(res.company.name);
		$("#uc_email").val(res.company.email);
		$("#uc_tel").val(res.company.tel);
		$("#uc_address").val(res.company.address);
		$("#uc_urbanization").val(res.company.urbanization);
		$("#uc_ubigeo").val(res.company.ubigeo);
		$("#uc_department_id").val(res.company.department_id);
		$("#uc_province_id").val(res.company.province_id);
		$("#uc_district_id").val(res.company.district_id);
	});
}

function update_company_data(dom){
	ajax_form(dom, "config/update_company_data").done(function(res) {
		set_msg(res.msgs);
		swal_redirection(res.type, res.msg, window.location.href);
	});
}
/* end company */

/* start account */
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

function reset_password(dom){
	ajax_simple_warning({id: $(dom).val()}, "config/reset_password", $("#warning_rpa").val()).done(function(res) {
		swal(res.type, res.msg);
	});
}

function reset_account_list(){
	$("#account_list").html("");
	$("#btn_load_more_account").removeClass("d-none");
	load_more_account();
}

function register_account(dom){
	ajax_form(dom, "config/register_account").done(function(res) {
		set_msg(res.msgs);
		swal(res.type, res.msg);
		reset_account_list();
		$('#btn_list_account').trigger('click');
	});
}

function remove_account(dom){
	ajax_simple_warning({id: $(dom).val()}, "config/remove_account", $("#warning_rac").val()).done(function(res) {
		swal(res.type, res.msg);
		reset_account_list();
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
		}else $("#btn_load_more_account").addClass("d-none");
	});
}
/* end account */

/* start profile */
function reset_profile_list(){
	$("#profile_list").html("");
	$("#btn_load_more_profile").removeClass("d-none");
	load_more_profile();
}

function register_profile(dom){
	ajax_form(dom, "config/register_profile").done(function(res) {
		set_msg(res.msgs);
		swal(res.type, res.msg);
		reset_profile_list();
		$('#btn_list_profile').trigger('click');
	});
}

function remove_profile(dom){
	ajax_simple_warning({id: $(dom).val()}, "config/remove_profile", $("#warning_rpr").val()).done(function(res) {
		swal(res.type, res.msg);
		reset_profile_list();
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
		}else $("#btn_load_more_profile").addClass("d-none");
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
	
	if ($(".ex_profile:not(.d-none)").length > 0) $("#rp_no_result_msg").addClass("d-none");
	else $("#rp_no_result_msg").removeClass("d-none");
}

function set_exam_cat(categories, exams){
	if (categories.length > 0){
		$(".ad_cat_rows").remove();
		$("#ad_ex_category").html(""); $("#ad_ex_category").append('<option value="">--</option>');
		$("#rp_category").html(""); $("#rp_category").append('<option value="">' + $("#txt_view_all").val() + '</option>');
		
		$.each(categories, function(index, item) {
			$("#ad_category_list").append('<tr class="ad_cat_rows"><td><strong>' + (index + 1) + '</strong></td><td>' + item.name + '</td><td class="text-right"><button type="button" class="btn light btn-danger btn_remove_exam_category" value="' + item.id + '"><i class="fas fa-trash"></i></button></td></tr>');
			$("#rp_category").append('<option value="' + item.id + '">' + item.name + '</option>');
			$("#ad_ex_category").append('<option value="' + item.id + '">' + item.name + '</option>');
		});
		
		$(".btn_remove_exam_category").on('click',(function(e) {remove_exam_category($(this).val());}));
	}
	
	if (exams.length > 0){
		$(".ad_exam_rows").remove();
		$(".ex_profile").remove();
		$.each(exams, function(index, item) {
			$("#ad_exam_list").append('<tr class="ad_exam_rows"><td><strong>' + (index + 1) + '</strong></td><td>' + item.name + '<br/><small>' + item.category + '</small></td><td class="text-right"><button type="button" class="btn light btn-danger btn_remove_exam" value="' + item.id + '"><i class="fas fa-trash"></i></button></td></tr>');
			
			$("#ex_profile_list").append('<div class="col-md-6 ex_profile ex_profile_' + item.category_id + '"><div class="custom-control custom-checkbox mb-3"><input type="checkbox" class="custom-control-input" id="exam_' + item.id + '" value="' + item.id + '"name="exams[]"><label class="custom-control-label" for="exam_' + item.id + '">' + item.name + '</label></div></div>');
		});
		
		$(".btn_remove_exam").on('click',(function(e) {remove_exam($(this).val());}));
	}
}

function add_exam_category(dom){
	ajax_form(dom, "config/add_exam_category").done(function(res){
		swal(res.type, res.msg);
		set_exam_cat(res.data.cats, res.data.exams);
	});
}

function remove_exam_category(cat_id){
	ajax_simple({id: cat_id}, "config/remove_exam_category").done(function(res){
		swal(res.type, res.msg);
		set_exam_cat(res.data.cats, res.data.exams);
	});
}

function add_exam(dom){
	ajax_form(dom, "config/add_exam").done(function(res){
		swal(res.type, res.msg);
		set_exam_cat(res.data.cats, res.data.exams);
	});
}

function remove_exam(ex_id){
	ajax_simple({id: ex_id}, "config/remove_exam").done(function(res){
		swal(res.type, res.msg);
		set_exam_cat(res.data.cats, res.data.exams);
	});
}
/* end profile */

/* start medicine */
function reset_medicine_list(){
	$("#medicine_list").html("");
	$("#btn_load_more_medicine").removeClass("d-none");
	load_more_medicine();
}

function register_medicine(dom){
	ajax_form(dom, "config/register_medicine").done(function(res) {
		set_msg(res.msgs);
		swal(res.type, res.msg);
		reset_medicine_list();
		$('#btn_list_medicine').trigger('click');
	});
}

function remove_medicine(dom){
	ajax_simple_warning({id: $(dom).val()}, "config/remove_medicine", $("#warning_rme").val()).done(function(res) {
		swal(res.type, res.msg);
		reset_medicine_list();
	});
}

function load_more_medicine(){
	var offset = $("#medicine_list").children().length;
	ajax_simple({offset: offset}, "config/load_more_medicine").done(function(res) {
		if (res.length > 0){
			$.each(res, function(index, item) {
				$("#medicine_list").append('<tr><td>' + (offset + index + 1) + '</td><td>' + item.name + '</td><td class="text-right"><button type="button" class="btn btn-danger shadow btn-xs sharp btn_remove_medicine" value="' + item.id + '"><i class="fas fa-trash"></i></button></td></tr>');
			});
			
			$('.btn_remove_medicine').off('click').on('click',(function(e) {remove_medicine(this);}));
		}else $("#btn_load_more_medicine").addClass("d-none");
	});
}
/* end medicine */

/* start log */
function load_more_log(){
	var offset = $("#log_list").children().length;
	ajax_simple({offset: offset}, "config/load_more_log").done(function(res) {
		if (res.length > 0){
			$.each(res, function(index, item) {
				$("#log_list").append('<tr><td>' + (offset + index + 1) + '</td><td>' + item.account + '</td><td>' + item.log_txt + '<br/>' + item.detail + '</td><td>' + item.registed_at + '</td></tr>');
			});
		}else $("#btn_load_more_log").addClass("d-none");
	});
}
/* end log */

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
	$("#btn_search_company").on('click',(function(e) {search_company();}));
	$("#uc_department_id").change(function() {control_province($(this).val());});
	$("#uc_province_id").change(function() {control_district($(this).val());});
	
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
	$("#form_register_medicine").submit(function(e) {e.preventDefault(); register_medicine(this);});
	$(".control_bl_medicine").on('click',(function(e) {control_bl_group(this, "medicine");}));
	$(".btn_remove_medicine").on('click',(function(e) {remove_medicine(this);}));
	$("#btn_load_more_medicine").on('click',(function(e) {load_more_medicine();}));
	
	//log
	$("#btn_load_more_log").on('click',(function(e) {load_more_log();}));
});