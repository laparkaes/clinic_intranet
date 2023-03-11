function nf(num){//number format
	return parseFloat(num).toLocaleString('es-US', {maximumFractionDigits: 2, minimumFractionDigits: 2});
}

function set_chart_monthly_income(data){
	var options = {
		series: data.series,
		chart: {height: 350, type: 'line', zoom: {enabled: false}},
		stroke: {curve: 'smooth'},
		dataLabels: {enabled: false},
        legend: {
			tooltipHoverFormatter: function(val, opts) {
				var value = opts.w.globals.series[opts.seriesIndex][opts.dataPointIndex];
				if (value == null) value = 0;
				return val + ' <strong>' + nf(value) + '</strong>'
			}
        },
		yaxis: {show: false, showForNullSeries: false},
		xaxis: {categories: data.xaxis},
		tooltip: {
			y: {
				formatter: function(value, { series, seriesIndex, dataPointIndex, w }) {
					if (value == null) value = 0;
					return nf(value)
				}
			}
		}
	};

	var chart = new ApexCharts(document.querySelector("#chart_monthly_income"), options);
	chart.render();
}

function load_chart_monthly_income(){
	$.ajax({
		url: $("#base_url").val() + "dashboard/load_chart_monthly_income",
		type: "POST",
		success:function(res){
			set_chart_monthly_income(res);
		}
	});
	
	/*
	var options = {
		series: data.series,
		chart: {height: 330, type: 'line', toolbar: {show: false}},
        stroke: {curve: 'smooth'},
        grid: {borderColor: '#e7e7e7', row: {colors: ['#f3f3f3', 'transparent'], opacity: 0.5}},
        markers: {size: 1},
        xaxis: {categories: data.xaxis},
        yaxis: {
			title: {text: data.yaxis},
			labels:{ formatter: function(val) {
				//return val.toLocaleString('en-US', {minimumFractionDigits: 2});
				return val.toLocaleString('en-US');
			}}},
        legend: {position: 'bottom', horizontalAlign: 'center'}
	};

    var chart = new ApexCharts(document.querySelector("#chart_progress"), options);
	chart.render();
	*/
}

var calendar_hd;

function set_calendar(){
	var data = jQuery.parseJSON($("#calendar_data").html());
	calendar_hd = data.hd;
	//console.log(data);
	var calendarEl = document.getElementById('calendar_schedule');
	var calendar = new FullCalendar.Calendar(calendarEl, {
		headerToolbar: {start: 'prev,next today', center: 'title', end: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'},
		buttonText: data.btns,
		navLinks: true, // can click day/week names to navigate views
		dayMaxEvents: true, // allow "more" link when too many events
		moreLinkContent:function(args){return '+'+args.num+' mas';},
		locale: 'es',
		events: data.events,
		eventClick: function(info) {
			//console.log(info.event.extendedProps);
			var op_d = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };		
			var op_t = {hour: '2-digit', minute:'2-digit', hour12: true}
			var date = info.event.start.toLocaleDateString('es-PE', op_d);
			var time_s = info.event.start.toLocaleTimeString('es-PE', op_t);
			var time_e = info.event.end.toLocaleTimeString('es-PE', op_t);
			var time = time_s + " ~ " + time_e;
			
			$("#btn_agenda_detail").attr("href", info.event.extendedProps.link);
			$("#agenda_detail_body").html('<div class="d-flex justify-content-between mb-3"><strong>' + calendar_hd.date + '</strong><span>' + date + '</span></div><div class="d-flex justify-content-between mb-3"><strong>' + calendar_hd.schedule + '</strong><span>' + time + '</span></div><div class="d-flex justify-content-between"><strong>' + calendar_hd.type + '</strong><span>' + info.event.title + '</span></div>');
			$('#modal_agenda_detail').modal('show');
		}
	});
	calendar.render();
	$(".fc-toolbar").css("margin-top", 0);
}

$(document).ready(function() {
	setTimeout(function(){
		load_chart_monthly_income();
		
		//set_calendar();
	}, 1000);
});