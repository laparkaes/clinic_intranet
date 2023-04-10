function add_category(dom){
	Swal.fire({
		title: $("#alert_warning_title").val(),
		html: $("#warning_ac").val(),
		icon: 'warning',
		showCancelButton: true,
		confirmButtonText: $("#alert_confirm_btn").val(),
		cancelButtonText: $("#alert_cancel_btn").val()
	}).then((result) => {
		if (result.isConfirmed){
			$.ajax({
				url: $("#base_url").val() + "product/add_category",
				type: "POST",
				data: new FormData(dom),
				contentType: false,
				processData:false,
				success:function(res){
					Swal.fire({
						title: $("#alert_" + res.type + "_title").val(),
						html: res.msg,
						icon: res.type,
						showCancelButton: true,
						confirmButtonText: $("#alert_confirm_btn").val()
					}).then((result) => {
						if (res.status == true) location.reload();
					});
				}
			});
		}
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
	Swal.fire({
		title: $("#alert_warning_title").val(),
		html: $("#warning_uc").val(),
		icon: 'warning',
		showCancelButton: true,
		confirmButtonText: $("#alert_confirm_btn").val(),
		cancelButtonText: $("#alert_cancel_btn").val()
	}).then((result) => {
		if (result.isConfirmed){
			$.ajax({
				url: $("#base_url").val() + "product/update_category",
				type: "POST",
				data: new FormData(dom),
				contentType: false,
				processData:false,
				success:function(res){
					Swal.fire({
						title: $("#alert_" + res.type + "_title").val(),
						html: res.msg,
						icon: res.type,
						showCancelButton: true,
						confirmButtonText: $("#alert_confirm_btn").val()
					}).then((result) => {
						if (res.status == true) location.reload();
					});
				}
			});
		}
	});
}

function delete_category(dom){
	Swal.fire({
		title: $("#alert_warning_title").val(),
		html: $("#warning_dc").val(),
		icon: 'warning',
		showCancelButton: true,
		confirmButtonText: $("#alert_confirm_btn").val(),
		cancelButtonText: $("#alert_cancel_btn").val()
	}).then((result) => {
		if (result.isConfirmed){
			$.ajax({
				url: $("#base_url").val() + "product/delete_category",
				type: "POST",
				data: {id: $(dom).val()}, 
				success:function(res){
					Swal.fire({
						title: $("#alert_" + res.type + "_title").val(),
						html: res.msg,
						icon: res.type,
						showCancelButton: true,
						confirmButtonText: $("#alert_confirm_btn").val()
					}).then((result) => {
						if (res.status == true) location.reload();
					});
				}
			});
		}
	});
}

function move_product(dom){
	$("#form_move_product .sys_msg").html("");
	Swal.fire({
		title: $("#alert_warning_title").val(),
		html: $("#warning_mc").val(),
		icon: 'warning',
		showCancelButton: true,
		confirmButtonText: $("#alert_confirm_btn").val(),
		cancelButtonText: $("#alert_cancel_btn").val()
	}).then((result) => {
		if (result.isConfirmed){
			$.ajax({
				url: $("#base_url").val() + "product/move_product",
				type: "POST",
				data: new FormData(dom),
				contentType: false,
				processData:false,
				success:function(res){
					if (res.status == true) location.reload();
					else set_msg(res.msgs);
				}
			});
		}
	});
}

function register_product(dom){
	$("#form_register_product .sys_msg").html("");
	$.ajax({
		url: $("#base_url").val() + "product/register",
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
				if (res.status == true) location.href = res.move_to;
			});
		}
	});
}

$(document).ready(function() {
	//general
	set_datatable("product_list", 25, false);
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