function nf(num){//number format
	return parseFloat(num).toLocaleString('es-US', {maximumFractionDigits: 2, minimumFractionDigits: 2});
}

function set_chart_monthly_income(data){
	var options = {
		series: data.series,
		chart: {height: 300, type: 'area', zoom: {enabled: false}},
		stroke: {curve: 'smooth'},
		fill: {opacity: 0.3},
		dataLabels: {enabled: false},
        legend: {
			tooltipHoverFormatter: function(val, opts) {
				var value = opts.w.globals.series[opts.seriesIndex][opts.dataPointIndex];
				if (value == null) value = 0;
				return val + ' <strong>' + nf(value) + '</strong>'
			}
        },
		yaxis: {show: false},
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
}

$(document).ready(function() {
	setTimeout(function(){
		load_chart_monthly_income();
	}, 1000);
});