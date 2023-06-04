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

function load_income_chart(currency_id){
	ajax_simple({currency_id: currency_id}, "dashboard/load_income_chart").done(function(res) {
		var options = {
			series: res.series,
			chart: {height: 300, type: 'line', stacked: false, toolbar: {show: false}, zoom: {enabled: false}},
			dataLabels: {enabled: false},
			stroke: {curve: 'smooth', width: [4, 1, 1]},
			xaxis: {categories: res.xaxis},
			yaxis: {show: false},
			// topRight, topLeft, bottomRight, bottomLeft
			tooltip: {fixed: {enabled: true, position: 'topLeft', offsetY: 30, offsetX: 60}},
			legend: {horizontalAlign: 'center'},
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
	});
}

$(document).ready(function() {
	setTimeout(function(){
		$(".btn_load_income_chart").first().click();
	}, 1000);
	
	$(".btn_load_income_chart").on('click',(function(e) {load_income_chart($(this).val());}));
	
});