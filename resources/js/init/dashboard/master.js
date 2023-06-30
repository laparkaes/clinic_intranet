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

function load_resume_charts(){
	 $("#ch_rs_appointments").sparkline([10, 15, 26, 27, 28, 31, 34, 40, 41, 44, 49, 64, 68, 69, 72], {
		type: "bar",
		height: "54",
		barWidth: "5",
		barSpacing: "1",
		barColor: "#5fe1ad"
	});
	
	$("#ch_rs_surgeries").sparkline([10, 15, 26, 27, 28, 31, 34, 40, 41, 44, 49, 64, 68, 69, 72], {
		type: "bar",
		height: "54",
		barWidth: "5",
		barSpacing: "1",
		barColor: "#AC4CBC"
	});
	
	$("#ch_rs_sales").sparkline([10, 15, 26, 27, 28, 31, 34, 40, 41, 44, 49, 64, 68, 69, 72], {
		type: "bar",
		height: "54",
		barWidth: "5",
		barSpacing: "1",
		barColor: "#3A82EF"
	});
}

$(document).ready(function() {
	setTimeout(function(){
		$(".btn_load_income_chart").first().click();
		load_resume_charts();
	}, 1000);
	
	$(".btn_load_income_chart").on('click',(function(e) {load_income_chart(this);}));
	
});