function swal(type, msg){
	Swal.fire({
		title: $("#alert_" + type + "_title").val(),
		icon: type,
		html: msg,
		confirmButtonText: $("#alert_confirm_btn").val()
	});
}

function set_msg(messages){
	$(".sys_msg").removeClass("text-success");
	$(".sys_msg").removeClass("text-danger");
	$(".sys_msg").html("");
	
	messages.forEach(function(item){
		item.dom_id = "#" + item.dom_id;
		$(item.dom_id).html(item.msg);
		if (item.type == "success") $(item.dom_id).addClass("text-success");
		else $(item.dom_id).addClass("text-danger");
	});
}

function set_autocomplete(dom_id, data_dom_id){
	$("#" + dom_id).autocomplete({
		minLength: 0,
		source: function(request, response) {
			var list = jQuery.parseJSON($("#" + data_dom_id).html());
			var results = $.ui.autocomplete.filter(list, request.term);
			if (results.length > 0){
				var more_result = false;
				if (results.length > 10) more_result = true;
				
				result = results.slice(0, 10);
				if (more_result == true) result.push({label: "...", value: ""});
				
				response(result);
			}else response([]);
		}
	}).on('click',(function(e) {
		$(this).autocomplete('search', $(this).val());
	}));
}

function load_schedule(){
	$.ajax({
		url: $("#base_url").val() + "Ajax_f/load_schedule",
		type: "POST",
		success:function(res){
			var app_baseurl = $("#base_url").val() + "appointment/detail/";
			$.each(res.appointments, function(key, day) {
				$("#sch_list_appointment").append('<li class="name-first-letter">' + day.title + '</li>');
				$.each(day.data, function(key, item) {
					$("#sch_list_appointment").append('<li><div class="d-flex bd-highlight"><div class="user_info text-black fs-13"><div><i class="fas fa-user-injured fa-fw mr-1"></i>' + item.patient + '</div><div><i class="fas fa-user-md fa-fw mr-1"></i>' + item.doctor + '</div><div class="text-muted">' + item.schedule + ', ' + item.speciality + '</div></div><div class="ml-auto"><a href="' + app_baseurl + item.id + '" class="btn btn-' + item.color + ' btn-xs sharp mr-1"><i class="fa fa-search"></i></a></div></div></li>');
				});
			});
			
			$.each(res.surgeries, function(key, day) {
				$("#sch_list_surgery").append('<li class="name-first-letter">' + day.title + '</li>');
				$.each(day.data, function(key, item) {
					$("#sch_list_surgery").append('<li><div class="d-flex bd-highlight"><div class="user_info text-black fs-13"><div><i class="fas fa-user-injured fa-fw mr-1"></i>' + item.patient + '</div><div><i class="fas fa-user-md fa-fw mr-1"></i>' + item.doctor + '</div><div class="text-muted">' + item.schedule + ', ' + item.speciality + '</div></div><div class="ml-auto"><a href="' + app_baseurl + item.id + '" class="btn btn-' + item.color + ' btn-xs sharp mr-1"><i class="fa fa-search"></i></a></div></div></li>');
				});
			});
		}
	});
}

function set_datatable(dom_id, pl, o){
	var table = $('#' + dom_id).DataTable({
		pageLength: pl,
		ordering: o,
		language: {
			lengthMenu: "_MENU_",
			search: "",
			searchPlaceholder: $("#j_datatable_search").val(),
			zeroRecords: $("#j_datatable_no_record").val(),
			info: "_START_ - _END_ / _TOTAL_",
			infoEmpty: "0",
			infoFiltered: "", //"/ _MAX_",
			paginate: { 
				first: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>', 
				previous: '<i class="fa fa-angle-left" aria-hidden="true"></i>', 
				next: '<i class="fa fa-angle-right" aria-hidden="true"></i>', 
				last: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>'
			}
		}, 
    });
	
	$("#" + dom_id + "_length_new").append($("#" + dom_id + "_length select").addClass("form-control"));
	$("#" + dom_id + "_filter_new").append($("#" + dom_id + "_filter input").addClass("form-control"));
	$("#" + dom_id + "_length, #" + dom_id + "_filter").remove();
	$("#" + dom_id + " th").removeAttr('style');
}

function set_img_preview(dom, view_id){
	const file = dom.files[0];
	if (file){
		let reader = new FileReader();
		reader.onload = function(event){
			$('#' + view_id).attr('src', event.target.result);
		}
		reader.readAsDataURL(file);
	}
}

function control_bl(dom){
	var bl = "#" + $(dom).val();
	if ($(bl).hasClass("d-none") == true){
		$(".bl_content").addClass("d-none");
		$(bl).removeClass("d-none");
		$(".control_bl").removeClass("btn-primary");
		$(".control_bl").addClass("btn-outline-primary");
		$(dom).removeClass("btn-outline-primary");
		$(dom).addClass("btn-primary");
	}else{
		$(".bl_content").addClass("d-none");
		$("#bl_list").removeClass("d-none");
		$(".control_bl").removeClass("btn-primary");
		$(".control_bl").addClass("btn-outline-primary");
		$("#btn_list").removeClass("btn-outline-primary");
		$("#btn_list").addClass("btn-primary");
	}
}

function control_bl_simple(dom){
	var bl_id = "#" + $(dom).val();
	if ($(bl_id).hasClass("d-none") == true){
		$(".bl_simple").addClass("d-none");
		$(bl_id).removeClass("d-none");
	}else $(".bl_simple").addClass("d-none");
}

$(document).ready(function() {
	if ($(".date_picker").length > 0){
		$(".date_picker").bootstrapMaterialDatePicker({
			weekStart: 0,
			time: false,
			lang: 'es',
			minDate : new Date(),
			okText: $("#bd_select").val(),
			cancelText: $("#bd_cancel").val()
		});	
	}
	if ($(".date_picker_all").length > 0){
		$(".date_picker_all").bootstrapMaterialDatePicker({
			weekStart: 0,
			time: false,
			minDate : null,
			lang: 'es',
			okText: $("#bd_select").val(),
			cancelText: $("#bd_cancel").val()
		});	
	}
	
	if ($(".time_picker").length > 0){
		$('.time_picker').bootstrapMaterialDatePicker({
			format: 'HH:mm',
			time: true,
			date: false,
			lang: 'es',
			okText: $("#bd_select").val(),
			cancelText: $("#bd_cancel").val()
		});
	}
	
	$(".content-body").css("min-height", "0");
	
	load_schedule();
	$('.schedule_block').on('click',function(){ $('.schedule_box').addClass('active'); });
	$('.schedule_box-close').on('click',function(){ $('.schedule_box').removeClass('active'); });
});