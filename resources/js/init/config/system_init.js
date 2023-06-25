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

function company_init(dom){
	ajax_form(dom, "config/company_init").done(function(res) {
		set_msg(res.msgs);
		swal_redirection(res.type, res.msg, window.location.href);
	});
}
/* end company */

/* start account */
function account_init(dom){
	ajax_form(dom, "config/account_init").done(function(res) {
		set_msg(res.msgs);
		swal_redirection(res.type, res.msg, window.location.href);
	});
}
/* end account */

/* start sale type */
function add_sale_type(dom){
	ajax_form(dom, "config/add_sale_type").done(function(res) {
		set_msg(res.msgs);
		swal_redirection(res.type, res.msg, window.location.href);
	});
}

function remove_sale_type(id){
	ajax_simple_warning({id:id}, "config/remove_sale_type").done(function(res) {
		swal_redirection(res.type, res.msg, window.location.href);
	});
}
/* end sale type */

$(document).ready(function() {
	//company
	$("#btn_search_company").on('click',(function(e) {search_company();}));
	$("#form_company_init").submit(function(e) {e.preventDefault(); company_init(this);});
	
	//account
	$("#form_account_init").submit(function(e) {e.preventDefault(); account_init(this);});
	
	//sale type
	$("#form_add_sale_type").submit(function(e) {e.preventDefault(); add_sale_type(this);});
	$(".btn_remove_sale_type").on('click',(function(e) {remove_sale_type($(this).val());}));
});