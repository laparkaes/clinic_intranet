function nf(num){//number format
	return parseFloat(num).toLocaleString('es-US', {maximumFractionDigits: 2, minimumFractionDigits: 2});
}

function load_income_chart(dom){
	$(".btn_load_income_chart").removeClass("btn-primary").addClass("btn-outline-primary");
	$(dom).removeClass("btn-outline-primary").addClass("btn-primary");
	
	ajax_simple({currency_id: $(dom).val()}, "dashboard/load_income_chart").done(function(res) {
		var options = {
			series: res.series,
			chart: {height: 300, type: 'bar', stacked: true, toolbar: {show: false}, zoom: {enabled: false}},
			dataLabels: {enabled: false},
			//stroke: {curve: 'smooth', width: [1, 1, 1]},
			xaxis: {categories: res.xaxis},
			//yaxis: {show: false},
			yaxis: {
				labels: {
					formatter: function (value) {
						if (value >= 1000000) return (value / 1000000) + 'm';
						else if (value >= 1000) return (value / 1000) + 'k';
						else return value;
					}
				}
			},
			// topRight, topLeft, bottomRight, bottomLeft
			tooltip: {
				fixed: {enabled: true, position: 'topLeft', offsetY: 30, offsetX: 60},
				shared: true,
				intersect: false,
				custom: function({ series, seriesIndex, dataPointIndex, w }) {
					let currentTotal = 0;
					let content = "";
					let currency = $(dom).text().trim();
					
					var i = 0;
					series.forEach((s) => {
						currentTotal += s[dataPointIndex];
						content += '<tr><td class="text-left px-2" style="color: ' + w.globals.fill.colors[i] + '"><b>' + w.globals.seriesNames[i] + '</b></td><td class="px-2"></td><td class="text-right px-2">' + currency + " " + nf(s[dataPointIndex]) + '</td></tr>';
						
						i++;
					});
					
					content += '<tr><td class="text-left px-2 pt-1"><b>Total</b></td><td class="px-2 pt-1"></td><td class="text-right px-2 pt-1"><b>' + currency + " " + nf(currentTotal) + '</b></td></tr>';
					
					return '<div class="p-1"><table>' + content + '</table></div>';
				}
			},
			legend: {horizontalAlign: 'center'},
		};

		var chart = new ApexCharts(document.querySelector("#chart_monthly_income"), options);
		chart.render();
	});
}

$(document).ready(function() {
	setTimeout(function(){
		$(".btn_load_income_chart").first().click();
	}, 1000);
	
	$(".btn_load_income_chart").on('click',(function(e) {load_income_chart(this);}));
});