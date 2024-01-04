function add_category(dom){
	ajax_form_warning(dom, "commerce/product/add_category", "wm_category_add").done(function(res) {
		swal_redirection(res.type, res.msg, window.location.href);
	});
}

function cancel_edit_category(){
	$(".ct_name").removeClass("d-none");
	$(".form_update_category").addClass("d-none");
}

function control_edit_category_form(dom){
	var ct_id = $(dom).val();
	cancel_edit_category();
	$("#ct_name_" + ct_id).addClass("d-none");
	$("#form_update_category_" + ct_id).removeClass("d-none");
	$("#form_update_category_" + ct_id + " input[name=name]").val($("#ct_name_" + ct_id).html());
}

function update_category(dom){
	ajax_form_warning(dom, "commerce/product/update_category", "wm_category_update").done(function(res) {
		swal_redirection(res.type, res.msg, window.location.href);
	});
}

function delete_category(dom){
	ajax_simple_warning({id: $(dom).val()}, "commerce/product/delete_category", "wm_category_delete").done(function(res) {
		swal_redirection(res.type, res.msg, window.location.href);
	});
}

function move_product(dom){
	$("#form_move_product .sys_msg").html("");
	ajax_form_warning(dom, "commerce/product/move_product", "wm_category_move").done(function(res) {
		set_msg(res.msgs);
		swal_redirection(res.type, res.msg, window.location.href);
	});
}

function register_product(dom){
	$("#form_register_product .sys_msg").html("");
	ajax_form_warning(dom, "commerce/product/register", "wm_product_register").done(function(res) {
		set_msg(res.msgs);
		swal_redirection(res.type, res.msg, res.move_to);
	});
}

$(document).ready(function() {
	//general
	$(".control_bl").on('click',(function(e) {control_bl(this);}));
	
	//category
	$("#form_add_category").submit(function(e) {e.preventDefault(); add_category(this);});
	$(".form_update_category").submit(function(e) {e.preventDefault(); update_category(this);});
	$("#form_move_product").submit(function(e) {e.preventDefault(); move_product(this);});
	$(".btn_edit_ct").on('click',(function(e) {control_edit_category_form(this);}));
	$(".btn_cancel_edit_ct").on('click',(function(e) {cancel_edit_category();}));
	$(".btn_delete_ct").on('click',(function(e) {delete_category(this);}));
	
	//product
	$("#form_register_product").submit(function(e) {e.preventDefault(); register_product(this);});
	$("#ap_image").on('change',(function(e) {set_img_preview(this, "img_preview");}));
});