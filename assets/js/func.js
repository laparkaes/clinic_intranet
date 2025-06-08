var base_url = $("#base_url").val();

var swal_lang = {
	"success"	: '¡ Éxito !',
	"error"		: '¡ Error !',
	"warning"	: '¡ Un Momento !',
	"confirm"	: 'Confirmar',
	"cancel"	: 'Cancelar',
};

function nf(num){//number format
	return parseFloat(num).toLocaleString('es-US', {maximumFractionDigits: 2, minimumFractionDigits: 2});
}

function swal(type, msg){
	Swal.fire({
		title: swal_lang[type],
		icon: type,
		html: msg,
		confirmButtonText: swal_lang[confirm],
	});
}

function swal_redirection(type, msg, move_to){
	Swal.fire({
		title: swal_lang[type],
		icon: type,
		html: msg,
		confirmButtonText: swal_lang[confirm],
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

function ajax_form_warning(dom, url, msg){
	var deferred = $.Deferred();
	Swal.fire({
		title: swal_lang["warning"],
		icon: 'warning',
		html: msg,
		showCancelButton: true,
		confirmButtonText: swal_lang["confirm"],
		cancelButtonText: swal_lang["cancel"],
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

function ajax_simple_warning(data, url, msg){
	var deferred = $.Deferred();
	Swal.fire({
		title: swal_lang["warning"],
		icon: 'warning',
		html: msg,
		showCancelButton: true,
		confirmButtonText: swal_lang["confirm"],
		cancelButtonText: swal_lang["cancel"],
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
	$("#" + dom_id).html('<div class="text-center mt-5"><div class="spinner-border" style="width: 50px; height: 50px;" role="status"><span class="visually-hidden">Loading...</span></div></div>');
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
		$(list_id + " #" + hh + mm + ".sch_cell").html('<i class="bi bi-check-lg text-primary"></i>');
	}
}

function set_time_dom(id_hour, id_min, dom){//used when select a cell in doctor schedule
	let val = $(dom).attr("id");
	let lastIndex = val.length - 2;
	
	$(id_hour).val(val.substring(0, lastIndex));
	$(id_min).val(val.substring(lastIndex));
	
	$(".sch_cell").html("");
	$(dom).html('<i class="bi bi-check-lg text-primary"></i>');
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
		url: base_url + "clinic/surgery/load_room_availability",
		type: "POST",
		data: {room_id: room_id, date: date},
		success:function(res){
			$("#" + dom_id).html(res);
			$(".btn_room_schedule_w").on('click',(function(e) {load_room_availability(room_id, $(this).val(), dom_id);}));
		}
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

function form_to_object(form_id){
	var formData = new FormData($('#' + form_id)[0]);

	var formDataObject = {};
	formData.forEach(function(value, key) {
		formDataObject[key] = value;
	});
	
	return formDataObject;
}

function format_date(currentDate){//for javascript operation
	// 년, 월, 일을 가져오기
	var year = currentDate.getFullYear();
	var month = (currentDate.getMonth() + 1).toString().padStart(2, '0'); // 월은 0부터 시작하므로 1을 더하고, 두 자리로 표현
	var day = currentDate.getDate().toString().padStart(2, '0'); // 일을 두 자리로 표현

	// "YYYY-MM-DD" 형식으로 조합
	return `${year}-${month}-${day}`;
}

function set_date_picker(dom, minDate){
	var option = {
		"allowInputToggle": true,
		"showClose": false,
		"showClear": false,
		"showTodayButton": true,
		"format": "YYYY-MM-DD",
		locale: 'es',
		icons: {
			time: "bi bi-clock",
			date: "bi bi-calendar",
			up: "bi bi-chevron-up",
			down: "bi bi-chevron-down",
			previous: "bi bi-chevron-left",
			next: "bi bi-chevron-right",
			today: "bi bi-calendar",
			clear: "bi bi-trash",
			close: "bi bi-x",
		}
	};
	if (minDate != null) option.minDate = format_date(minDate);//minDate;
	$(dom).datetimepicker(option);
}

function set_time_picker(dom){
	var option = {
		"allowInputToggle": true,
		"showClose": false,
		"showClear": false,
		"showTodayButton": true,
		"format": "HH:mm",
		locale: 'es',
		icons: {
			time: "bi bi-clock",
			date: "bi bi-calendar",
			up: "bi bi-chevron-up",
			down: "bi bi-chevron-down",
			previous: "bi bi-chevron-left",
			next: "bi bi-chevron-right",
			today: "bi bi-calendar",
			clear: "bi bi-trash",
			close: "bi bi-x",
		}
	};
	$(dom).datetimepicker(option);
}

$('form.no_enter input').keypress(function(event) {
	if (event.keyCode === 13) {
		event.preventDefault();
		return false;
	}
});