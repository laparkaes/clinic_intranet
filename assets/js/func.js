var base_url = $("#base_url").val();

function swal(type, msg){
	Swal.fire({
		title: component_list[default_lang]["alert_" + type + "_title"],
		icon: type,
		html: msg,
		confirmButtonText: component_list[default_lang].alert_confirm_btn,
	});
}

function swal_redirection(type, msg, move_to){
	Swal.fire({
		title: component_list[default_lang]["alert_" + type + "_title"],
		icon: type,
		html: msg,
		confirmButtonText: component_list[default_lang].alert_confirm_btn,
	}).then((result) => {
		if (result.isConfirmed) if (type == "success") location.href = move_to;
	});
}

function ajax_form(dom, url){
	var deferred = $.Deferred();
	$.ajax({
		url: base_url + url,
		type: "POST",
		data: new FormData(dom),
		contentType: false,
		processData:false,
		success:function(res){
			deferred.resolve(res);
		}
	});
	
	return deferred.promise();
}

function ajax_form_warning(dom, url, w_msg_key){
	var deferred = $.Deferred();
	Swal.fire({
		title: component_list[default_lang].alert_warning_title,
		icon: 'warning',
		html: msg_list[default_lang][w_msg_key],
		showCancelButton: true,
		confirmButtonText: component_list[default_lang].alert_confirm_btn,
		cancelButtonText: component_list[default_lang].alert_cancel_btn,
	}).then((result) => {
		if (result.isConfirmed) ajax_form(dom, url).done(function(res) {
			deferred.resolve(res);
		});
	});
	
	return deferred.promise();
}

function ajax_simple(data, url){
	var deferred = $.Deferred();
	$.ajax({
		url: base_url + url,
		type: "POST",
		data: data,
		success:function(res){
			deferred.resolve(res);
		}
	});
	
	return deferred.promise();
}

function ajax_simple_warning(data, url, w_msg_key){
	var deferred = $.Deferred();
	Swal.fire({
		title: component_list[default_lang].alert_warning_title,
		icon: 'warning',
		html: msg_list[default_lang][w_msg_key],
		showCancelButton: true,
		confirmButtonText: component_list[default_lang].alert_confirm_btn,
		cancelButtonText: component_list[default_lang].alert_cancel_btn,
	}).then((result) => {
		if (result.isConfirmed) ajax_simple(data, url).done(function(res) {
			deferred.resolve(res);
		});
	});
	
	return deferred.promise();
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

function set_datatable(dom_id, pl, o){
	var table = $('#' + dom_id).DataTable({
		pageLength: pl,
		ordering: o,
		language: {
			lengthMenu: "_MENU_",
			search: "",
			searchPlaceholder: component_list[default_lang].j_datatable_search,
			zeroRecords: component_list[default_lang].j_datatable_no_record,
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
		if ($("#bl_list").length > 0){
			$(".bl_content").addClass("d-none");
			$("#bl_list").removeClass("d-none");
			$(".control_bl").removeClass("btn-primary");
			$(".control_bl").addClass("btn-outline-primary");
			$("#btn_list").removeClass("btn-outline-primary");
			$("#btn_list").addClass("btn-primary");	
		}
	}
}

function control_bl_simple(dom){
	var bl_id = "#" + $(dom).val();
	if ($(bl_id).hasClass("d-none") == true){
		$(".bl_simple").addClass("d-none");
		$(bl_id).removeClass("d-none");
	}else $(".bl_simple").addClass("d-none");
}

function load_doctor_schedule(doctor_id, date, dom_id){
	$("#" + dom_id).html('<div class="text-center mt-5"><i class="fas fa-spinner fa-spin fa-5x"></i></div>');
	$.ajax({
		url: base_url + "ajax_f/load_doctor_schedule",
		type: "POST",
		data: {doctor_id: doctor_id, date: date},
		success:function(res){
			$("#" + dom_id).html(res);
		}
	});
}

function set_time_sl(prefix, list_id){
	let hh = $("#" + prefix + "_hour").val();
	let mm = $("#" + prefix + "_min").val();
	
	if ((hh != "") && (mm != "")){
		$(list_id + " .sch_cell").html("");
		$(list_id + " #" + hh + mm + ".sch_cell").html('<i class="fas fa-check text-info"></i>');
	}
}

function set_time_dom(id_hour, id_min, dom){//used when select a cell in doctor schedule
	let val = $(dom).attr("id");
	let lastIndex = val.length - 2;
	
	$(id_hour).val(val.substring(0, lastIndex));
	$(id_min).val(val.substring(lastIndex));
	
	$(".sch_cell").html("");
	$(dom).html('<i class="fas fa-check text-info"></i>');
}

function load_doctor_schedule_n(doctor_id, date){//new function
	var deferred = $.Deferred();
	$.ajax({
		url: base_url + "ajax_f/load_doctor_schedule",
		type: "POST",
		data: {doctor_id: doctor_id, date: date},
		success:function(res){
			deferred.resolve(res);
		}
	});
	
	return deferred.promise();
}

function load_doctor_schedule_weekly(doctor_id, date, dom_id){
	$("#" + dom_id).html('<div class="text-center mt-5"><i class="fas fa-spinner fa-spin fa-5x"></i></div>');
	$.ajax({
		url: base_url + "ajax_f/load_doctor_schedule_weekly",
		type: "POST",
		data: {doctor_id: doctor_id, date: date},
		success:function(res){
			$("#" + dom_id).html(res);
			$(".btn_doctor_schedule_w").on('click',(function(e) {load_doctor_schedule_weekly(doctor_id, $(this).val(), dom_id);}));
		}
	});
}


function load_room_availability(room_id, date, dom_id){
	$("#" + dom_id).html('<div class="text-center mt-5"><i class="fas fa-spinner fa-spin fa-5x"></i></div>');
	$.ajax({
		url: base_url + "surgery/load_room_availability",
		type: "POST",
		data: {room_id: room_id, date: date},
		success:function(res){
			$("#" + dom_id).html(res);
			$(".btn_room_schedule_w").on('click',(function(e) {load_room_availability(room_id, $(this).val(), dom_id);}));
		}
	});
}

function set_between_dates(id_from, id_to){
	id_from = "#" + id_from;
	id_to = "#" + id_to;
	
	$(id_to).bootstrapMaterialDatePicker({
		weekStart: 0, format: 'YYYY-MM-DD', time: false, maxDate: $(id_to).val(), minDate: $(id_from).val()
	}).on('change', function(e, date) {
		$(id_from).bootstrapMaterialDatePicker('setMaxDate', date);
	}); 

	$(id_from).bootstrapMaterialDatePicker({
		weekStart: 0, format: 'YYYY-MM-DD', time: false, maxDate: $(id_to).val()
	}).on('change', function(e, date) {
		$(id_to).bootstrapMaterialDatePicker('setMinDate', date);
	}); 
}

function get_params(){
	var url = window.location.href;
	var params = {};
	if (url.indexOf("?") !== -1) {
		var queryString = url.split("?")[1];
		if (queryString !== ""){
			var pairs = queryString.split("&");

			for (var i = 0; i < pairs.length; i++) {
				var pair = pairs[i].split("=");
				var key = decodeURIComponent(pair[0]);
				var value = decodeURIComponent(pair[1]);
				params[key] = value;
			}	
		}
	}
	return params;
}

$(document).ready(function() {
	if ($(".date_picker").length > 0){
		$(".date_picker").bootstrapMaterialDatePicker({
			weekStart: 0,
			time: false,
			lang: 'es',
			minDate : new Date(),
			okText: component_list[default_lang].bd_select,
			cancelText: component_list[default_lang].bd_cancel,
		});	
	}
	if ($(".date_picker_all").length > 0){
		$(".date_picker_all").bootstrapMaterialDatePicker({
			weekStart: 0,
			time: false,
			minDate : null,
			lang: 'es',
			okText: component_list[default_lang].bd_select,
			cancelText: component_list[default_lang].bd_cancel,
			clearButton: true,
			clearText: component_list[default_lang].bd_clean,
		});	
	}
	
	if ($(".time_picker").length > 0){
		$('.time_picker').bootstrapMaterialDatePicker({
			format: 'HH:mm',
			shortTime: true,
			time: true,
			date: false,
			lang: 'es',
			okText: component_list[default_lang].bd_select,
			cancelText: component_list[default_lang].bd_cancel,
		});
	}
	
	$(".content-body").css("min-height", "0");
});