/* start company */
function control_province(department_id){
	$("#com_province_id").val("");
	$("#com_province_id .province").addClass("d-none");
	$("#com_province_id .d" + department_id).removeClass("d-none");
	$("#com_district_id").val("");
	$("#com_district_id .district").addClass("d-none");
}

function control_district(province_id){
	$("#com_district_id").val("");
	$("#com_district_id .district").addClass("d-none");
	$("#com_district_id .p" + province_id).removeClass("d-none");
}

function search_company(){
	ajax_simple({tax_id: $("#com_tax_id").val()}, "ajax_f/search_company").done(function(res) {
		swal(res.type, res.msg);
		control_province(res.company.department_id);
		control_district(res.company.province_id);
		//$("#com_tax_id").val(res.company.tax_id);
		$("#com_name").val(res.company.name);
		$("#com_email").val(res.company.email);
		$("#com_tel").val(res.company.tel);
		$("#com_address").val(res.company.address);
		$("#com_urbanization").val(res.company.urbanization);
		$("#com_ubigeo").val(res.company.ubigeo);
		$("#com_department_id").val(res.company.department_id);
		$("#com_province_id").val(res.company.province_id);
		$("#com_district_id").val(res.company.district_id);
	});
}

function remove_company(){
	ajax_simple_warning({}, "system_init/remove_company", $("#warning_rco").val()).done(function(res) {
		swal_redirection(res.type, res.msg, window.location.href);
	});
}

function company_init(dom){
	ajax_form(dom, "system_init/company").done(function(res) {
		set_msg(res.msgs);
		swal_redirection(res.type, res.msg, window.location.href);
	});
}
/* end company */

/* start account */
function account_init(dom){
	ajax_form(dom, "system_init/account").done(function(res) {
		set_msg(res.msgs);
		swal_redirection(res.type, res.msg, window.location.href);
	});
}

function search_person(){
	var data = {doc_type_id: $("#pe_doc_type_id").val(), doc_number: $("#pe_doc_number").val()};
	ajax_simple(data, "ajax_f/search_person").done(function(res) {
		swal(res.type, res.msg);
		if (res.type == "success") $("#pe_name").val(res.person.name);
		else reset_person();
	});
}

function remove_account(){
	ajax_simple_warning({}, "system_init/remove_account", $("#warning_rac").val()).done(function(res) {
		swal_redirection(res.type, res.msg, window.location.href);
	});
}
/* end account */

/* start sunat access */
function sunat_access_init(dom){
	ajax_form(dom, "system_init/sunat_access").done(function(res) {
		set_msg(res.msgs);
		swal_redirection(res.type, res.msg, window.location.href);
	});
}
/* end sunat access */

/* start sale type */
function set_sale_types(sale_types){
	$(".row_sale_type").remove();
	$.each(sale_types, function(index, item) {
		$("#tbody_sale_types").append('<tr class="row_sale_type"><td>' + item.description + '</td><td>' + item.sunat_serie + '</td><td>' + item.start + '</td><td class="text-right"><button type="button" class="btn btn-danger light btn_remove_sale_type" value="' + item.id + '"><i class="fas fa-trash"></i></button></td></tr>');
	});
	
	$(".btn_remove_sale_type").on('click',(function(e) {remove_sale_type($(this).val());}));
}

function add_sale_type(dom){
	ajax_form(dom, "system_init/add_sale_type").done(function(res) {
		set_msg(res.msgs);
		swal(res.type, res.msg);
		if (res.type == "success"){
			dom.reset();
			$("#btn_finish_sale_type").removeClass("d-none");
			set_sale_types(res.sale_types);
		}
		
	});
}

function remove_sale_type(id){
	ajax_simple_warning({id:id}, "system_init/remove_sale_type", $("#warning_rst").val()).done(function(res) {
		swal(res.type, res.msg);
		if (res.type == "success"){
			$("#btn_finish_sale_type").removeClass("d-none");
			set_sale_types(res.sale_types);
		}
	});
}

function finish_sale_type(){
	ajax_simple_warning({}, "system_init/finish_sale_type", $("#warning_fst").val()).done(function(res) {
		swal_redirection(res.type, res.msg, window.location.href);
	});
}
/* end sale type */

function finish_init(){
	ajax_simple_warning({}, "system_init/finish_init", $("#warning_fsi").val()).done(function(res) {
		swal_redirection(res.type, res.msg, window.location.href);
	});
}

$(document).ready(function() {
	//company
	$("#form_company_init").submit(function(e) {e.preventDefault(); company_init(this);});
	$("#btn_search_company").on('click',(function(e) {search_company();}));
	$("#btn_remove_company").on('click',(function(e) {remove_company();}));
	
	//account
	$("#form_account_init").submit(function(e) {e.preventDefault(); account_init(this);});
	$("#btn_search_person").on('click',(function(e) {search_person();}));
	$("#btn_remove_account").on('click',(function(e) {remove_account();}));
	
	//sunat
	$("#form_sunat_access_init").submit(function(e) {e.preventDefault(); sunat_access_init(this);});
	
	
	//sale type
	$("#form_add_sale_type").submit(function(e) {e.preventDefault(); add_sale_type(this);});
	$(".btn_remove_sale_type").on('click',(function(e) {remove_sale_type($(this).val());}));
	$("#btn_finish_sale_type").on('click',(function(e) {finish_sale_type();}));
	
	//general
	$("#btn_finish").on('click',(function(e) {finish_init();}));
});