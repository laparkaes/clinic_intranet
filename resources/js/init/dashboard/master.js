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

var generateDayWiseTimeSeries = function (baseval, count, yrange) {
      var i = 0;
      var series = [];
      while (i < count) {
        var x = baseval;
        var y = Math.floor(Math.random() * (yrange.max - yrange.min + 1)) + yrange.min;
  
        series.push([x, y]);
        baseval += 86400000;
        i++;
      }
      return series;
    }
	
function set_chart(){
	
	var options = {
			series: [
				{name: 'Income', type: 'column', data: [1.4, 2, 2.5, 1.5, 2.5, 2.8, 3.8, 4.6]},
				{name: 'Cashflow', type: 'column', data: [1.1, 3, 3.1, 4, 4.1, 4.9, 6.5, 8.5]},
				{name: 'Revenue', type: 'line', data: [20, 29, 37, 36, 44, 45, 50, 58]}
			],
			chart: {height: 300, type: 'line', stacked: false},
			dataLabels: {enabled: false},
			stroke: {width: [1, 1, 4]},
			title: {
				text: 'XYZ - Stock Analysis (2009 - 2016)',
				align: 'left',
				offsetX: 110
			},
			xaxis: {categories: [2009, 2010, 2011, 2012, 2013, 2014, 2015, 2016]},
			yaxis: [
				{
					axisTicks: {show: true},
					axisBorder: {show: true, color: '#008FFB'},
					labels: {style: {colors: '#008FFB'}},
					title: {text: "Income (thousand crores)", style: {color: '#008FFB'}},
					tooltip: {enabled: true}
				},
				{
					seriesName: 'Income',
					opposite: true,
					axisTicks: {show: true},
					axisBorder: {show: true, color: '#00E396'},
            labels: {
              style: {
                colors: '#00E396',
              }
            },
            title: {
              text: "Operating Cashflow (thousand crores)",
              style: {
                color: '#00E396',
              }
            },
          },
          {
            seriesName: 'Revenue',
            opposite: true,
            axisTicks: {
              show: true,
            },
            axisBorder: {
              show: true,
              color: '#FEB019'
            },
            labels: {
              style: {
                colors: '#FEB019',
              },
            },
            title: {
              text: "Revenue (thousand crores)",
              style: {
                color: '#FEB019',
              }
            }
          },
        ],
        tooltip: {
          fixed: {
            enabled: true,
            position: 'topLeft', // topRight, topLeft, bottomRight, bottomLeft
            offsetY: 30,
            offsetX: 60
          },
        },
        legend: {
          horizontalAlign: 'left',
          offsetX: 40
        }
        };

        var chart = new ApexCharts(document.querySelector("#chart_sale_type"), options);
        chart.render();
}

function load_income_chart(currency_id){
	ajax_simple({currency_id: currency_id}, "dashboard/load_income_chart").done(function(res) {
		console.log(res);
		//swal(res.type, res.msg);
		//if (res.type == "error") $(dom).prop('checked', !$(dom).is(':checked'));
	});
}

$(document).ready(function() {
	setTimeout(function(){
		load_chart_monthly_income();
		//set_chart();
	}, 1000);
	
	$(".btn_load_income_chart").on('click',(function(e) {load_income_chart($(this).val());}));
	
});