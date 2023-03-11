function add_image(dom){
	$("#form_add_image .sys_msg").html("");
	$.ajax({
		url: $("#base_url").val() + "product/add_image",
		type: "POST",
		data: new FormData(dom),
		contentType: false,
		processData:false,
		success:function(res){
			Swal.fire({
				title: $("#alert_" + res.type + "_title").val(),
				html: res.msg,
				icon: res.type,
				confirmButtonText: $("#alert_confirm_btn").val()
			}).then((result) => {
				if (res.status == true){
					$("#bl_images").append('<div class="col-md-3" id="img_' + res.img.id + '"><div class="text-center border rounded overflow-hidden mb-3 w-100"><div class="overflow-hidden" style="width: 100%; height: 100px;"><img src="' + res.img.link + '" style="max-weight: 100px; max-height: 100px;" /></div><div class="border-top"><button type="button" class="btn btn-xs text-info p-1 btn_set_img" id="btn_set_img_' + res.img.id + '" value="' + res.img.id + '"><i class="far fa-image"></i></button><button type="button" class="btn btn-xs text-danger p-1 btn_delete_img" id="btn_delete_img_' + res.img.id + '" value="' + res.img.id + '"><i class="far fa-trash"></i></button></div></div></div>');
					
					$("input[name=image]").val("");
					$("#btn_delete_img_" + res.img.id).on('click',(function(e) {delete_image(this);}));
					$("#btn_set_img_" + res.img.id).on('click',(function(e) {set_product_image(this);}));
				}
			});
		}
	});
}

function delete_image(dom){
	Swal.fire({
		title: $("#alert_warning_title").val(),
		html: $("#warning_di").val(),
		icon: 'warning',
		showCancelButton: true,
		confirmButtonText: $("#alert_confirm_btn").val(),
		cancelButtonText: $("#alert_cancel_btn").val()
	}).then((result) => {
		if (result.isConfirmed){
			$.ajax({
				url: $("#base_url").val() + "product/delete_image",
				type: "POST",
				data: {id: $(dom).val()},
				success:function(res){
					Swal.fire({
						title: $("#alert_" + res.type + "_title").val(),
						html: res.msg,
						icon: res.type,
						confirmButtonText: $("#alert_confirm_btn").val()
					}).then((result) => {
						if (res.status == true) $("#img_" + res.id).remove();
					});
				}
			});
		}
	});
}

function set_product_image(dom){
	Swal.fire({
		title: $("#alert_warning_title").val(),
		html: $("#warning_pri").val(),
		icon: 'warning',
		showCancelButton: true,
		confirmButtonText: $("#alert_confirm_btn").val(),
		cancelButtonText: $("#alert_cancel_btn").val()
	}).then((result) => {
		if (result.isConfirmed){
			$.ajax({
				url: $("#base_url").val() + "product/set_product_image",
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

function save_provider(dom){
	$("#form_edit_provider .sys_msg").html("");
	$.ajax({
		url: $("#base_url").val() + "product/save_provider",
		type: "POST",
		data: new FormData(dom),
		contentType: false,
		processData:false,
		success:function(res){
			set_msg(res.msgs);
			if (res.msg != null){
				Swal.fire({
					title: $("#alert_" + res.type + "_title").val(),
					html: res.msg,
					icon: res.type,
					confirmButtonText: $("#alert_confirm_btn").val()
				}).then((result) => {
					if (res.status == true) location.reload();
				});
			}
		}
	});
}

function clean_provider(){
	$.ajax({
		url: $("#base_url").val() + "product/clean_provider",
		type: "POST",
		data: {product_id: $('input[name="product_id"]').val()},
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
	$.ajax({
		url: $("#base_url").val() + "product/edit_product",
		type: "POST",
		data: new FormData(dom),
		contentType: false,
		processData:false,
		success:function(res){
			set_msg(res.msgs);
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

function delete_product(dom){
	Swal.fire({
		title: $("#alert_warning_title").val(),
		html: $("#warning_dp").val(),
		icon: 'warning',
		showCancelButton: true,
		confirmButtonText: $("#alert_confirm_btn").val(),
		cancelButtonText: $("#alert_cancel_btn").val()
	}).then((result) => {
		if (result.isConfirmed){
			$.ajax({
				url: $("#base_url").val() + "product/delete_product",
				type: "POST",
				data: {id: $(dom).val()},
				success:function(res){
					Swal.fire({
						title: $("#alert_" + res.type + "_title").val(),
						icon: res.type,
						html: res.msg,
						confirmButtonText: $("#alert_confirm_btn").val()
					}).then((result) => {
						if (res.status == true) location.href = $("#base_url").val() + "product";
					});
				}
			});
		}
	});
}

function add_option(dom){
	Swal.fire({
		title: $("#alert_warning_title").val(),
		html: $("#warning_aop").val(),
		icon: 'warning',
		showCancelButton: true,
		confirmButtonText: $("#alert_confirm_btn").val(),
		cancelButtonText: $("#alert_cancel_btn").val()
	}).then((result) => {
		if (result.isConfirmed){
			$.ajax({
				url: $("#base_url").val() + "product/add_option",
				type: "POST",
				data: new FormData(dom),
				contentType: false,
				processData:false,
				success:function(res){
					set_msg(res.msgs);
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

function edit_option(dom){
	Swal.fire({
		title: $("#alert_warning_title").val(),
		html: $("#warning_aop").val(),
		icon: 'warning',
		showCancelButton: true,
		confirmButtonText: $("#alert_confirm_btn").val(),
		cancelButtonText: $("#alert_cancel_btn").val()
	}).then((result) => {
		if (result.isConfirmed){
			$.ajax({
				url: $("#base_url").val() + "product/edit_option",
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
					});
				}
			});
		}
	});
}

function delete_option(dom){
	Swal.fire({
		title: $("#alert_warning_title").val(),
		html: $("#warning_dop").val(),
		icon: 'warning',
		showCancelButton: true,
		confirmButtonText: $("#alert_confirm_btn").val(),
		cancelButtonText: $("#alert_cancel_btn").val()
	}).then((result) => {
		if (result.isConfirmed){
			$.ajax({
				url: $("#base_url").val() + "product/delete_option",
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
	$.ajax({
		url: $("#base_url").val() + "ajax_f/search_company",
		type: "POST",
		data: {ruc: $("#prov_ruc").val()},
		success:function(res){
			Swal.fire({
				title: $("#alert_" + res.type + "_title").val(),
				icon: res.type,
				html: res.msg,
				confirmButtonText: $("#alert_confirm_btn").val()
			}).then((result) => {
				if (res.status == true){
					$("#prov_company").val(res.company.company);
					$("#prov_web").val(res.company.web);
					$("#prov_person").val(res.company.person);
					$("#prov_tel").val(res.company.tel);
					$("#prov_email").val(res.company.email);
					$("#prov_address").val(res.company.address);
					$("#prov_remark").val(res.company.remark);
					
					$("#prov_company").addClass("bg-light").prop("readonly", true);
				}
			});
		}
	});
}

$(document).ready(function() {
	//general
	set_datatable("provider_list", 5, false);
	
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