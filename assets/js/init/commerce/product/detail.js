function add_image(dom){
	$("#form_add_image .sys_msg").html("");
	ajax_form(dom, "commerce/product/add_image").done(function(res) {
		swal(res.type, res.msg);
		if (res.type == "success"){
			$("#bl_images").append('<div class="col-md-3" id="img_' + res.img.id + '"><div class="text-center border rounded overflow-hidden mb-3 w-100"><div class="overflow-hidden" style="width: 100%; height: 100px;"><img src="' + res.img.link + '" style="max-weight: 100px; max-height: 100px;" /></div><div class="border-top"><button type="button" class="btn btn-xs text-primary p-1 btn_set_img" id="btn_set_img_' + res.img.id + '" value="' + res.img.id + '"><i class="bi bi-image"></i></button><button type="button" class="btn btn-xs text-danger p-1 btn_delete_img" id="btn_delete_img_' + res.img.id + '" value="' + res.img.id + '"><i class="bi bi-trash"></i></button></div></div></div>');
			
			$("input[name=image]").val("");
			$("#btn_delete_img_" + res.img.id).on('click',(function(e) {delete_image(this);}));
			$("#btn_set_img_" + res.img.id).on('click',(function(e) {set_product_image(this);}));
		}
	});
}

function delete_image(dom){
	ajax_simple_warning({id: $(dom).val()}, "commerce/product/delete_image", "wm_image_delete").done(function(res) {
		swal(res.type, res.msg);
		if (res.type == "success") $("#img_" + res.id).remove();
	});
}

function set_product_image(dom){
	ajax_simple_warning({id: $(dom).val()}, "commerce/product/set_product_image", "wm_image_main").done(function(res) {
		swal_redirection(res.type, res.msg, window.location.href);
	});
}

function save_provider(dom){
	$("#form_edit_provider .sys_msg").html("");
	ajax_form(dom, "commerce/product/save_provider").done(function(res) {
		set_msg(res.msgs);
		swal_redirection(res.type, res.msg, window.location.href);
	});
}

function clean_provider(){
	ajax_simple_warning({product_id: $('input[name="product_id"]').val()}, "commerce/product/clean_provider", "wm_provider_delete").done(function(res) {
		swal_redirection(res.type, res.msg, window.location.href);
	});
}

function set_stock(dom){
	if ($(dom).val() == 71){//service type
		$("#ep_stock").addClass("bg-light");
		$("#ep_stock").prop("readonly", true);
		$("#ep_stock").val(0);
	}else{
		$("#ep_stock").removeClass("bg-light");
		$("#ep_stock").prop("readonly", false);
	}
}

function edit_product(dom){
	$("#form_edit_product .sys_msg").html("");
	ajax_form(dom, "commerce/product/edit_product").done(function(res) {
		set_msg(res.msgs);
		swal_redirection(res.type, res.msg, window.location.href);
	});
}

function delete_product(dom){
	ajax_simple_warning({id: $(dom).val()}, "commerce/product/delete_product", "wm_product_delete").done(function(res) {
		swal_redirection(res.type, res.msg, res.move_to);
	});
}

function add_option(dom){
	ajax_form_warning(dom, "commerce/product/add_option", "wm_option_add").done(function(res) {
		swal_redirection(res.type, res.msg, window.location.href);
	});
}

function edit_option(dom){
	ajax_form_warning(dom, "commerce/product/edit_option", "wm_option_edit").done(function(res) {
		swal_redirection(res.type, res.msg, window.location.href);
	});
}

function delete_option(dom){
	ajax_simple_warning({id: $(dom).val()}, "commerce/product/delete_option", "wm_option_delete").done(function(res) {
		swal_redirection(res.type, res.msg, window.location.href);
	});
}

function hide_op_edit_form(){
	$(".row_op_info").removeClass("d-none");
	$(".row_op_edit").addClass("d-none");
}

function control_op_edit_form(dom){
	var id = $(dom).val();
	hide_op_edit_form();
	$("#row_op_info_" + id).addClass("d-none");
	$("#row_op_edit_" + id).removeClass("d-none");
}

function search_provider(){
	ajax_simple({tax_id: $("#prov_ruc").val()}, "ajax_f/search_company").done(function(res) {
		swal(res.type, res.msg);
		if (res.type == "success"){
			$("#prov_name").val(res.company.name);
			$("#prov_web").val(res.company.web);
			$("#prov_person").val(res.company.person);
			$("#prov_tel").val(res.company.tel);
			$("#prov_email").val(res.company.email);
			$("#prov_address").val(res.company.address);
			$("#prov_remark").val(res.company.remark);
		}
	});
}

$(document).ready(function() {
	//option
	$("#form_add_option").submit(function(e) {e.preventDefault(); add_option(this);});
	$(".form_edit_option").submit(function(e) {e.preventDefault(); edit_option(this);});
	$(".op_delete").on('click',(function(e) {delete_option(this);}));
	$(".op_edit").on('click',(function(e) {control_op_edit_form(this);}));
	$(".op_cancel_edit").on('click',(function(e) {hide_op_edit_form();}));
	
	//image
	$("#form_add_image").submit(function(e) {e.preventDefault(); add_image(this);});
	$(".btn_delete_img").on('click',(function(e) {delete_image(this);}));
	$(".btn_set_img").on('click',(function(e) {set_product_image(this);}));
	
	//provider
	$("#form_save_provider").submit(function(e) {e.preventDefault(); save_provider(this);});
	$("#btn_clean_provider").on('click',(function(e) {clean_provider();}));
	$("#btn_search_provider").on('click',(function(e) {search_provider();}));
	$("#prov_ruc").on('keyup',(function(e) {$("#prov_company").removeClass("bg-light").prop("readonly", false);}));
	
	//product
	$("#form_edit_product").submit(function(e) {e.preventDefault(); edit_product(this);});
	$("#ep_type").on('change',(function(e) {set_stock(this);}));
	$(".btn_delete").on('click',(function(e) {delete_product(this);}));
	set_stock($("#ep_type"));
});