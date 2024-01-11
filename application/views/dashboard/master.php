<div class="pagetitle">
	<h1><?= $title ?></h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= $this->lang->line('w_home') ?></a></li>
			<li class="breadcrumb-item active"><?= $title ?></li>
		</ol>
	</nav>
</div>
<section class="section dashboard">
	<div class="row">
		<div class="col-md-4">
			<div class="card info-card sales-card">
				<div class="card-body">
					<h5 class="card-title"><?= $this->lang->line('w_appointments') ?> <span>| <?= $this->lang->line('w_this_month') ?></span></h5>
					<div class="d-flex align-items-center">
						<div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
							<i class="bi bi-clipboard2-pulse"></i>
						</div>
						<div class="ps-3">
							<h6><?= number_format($appointment_qty) ?></h6>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card info-card revenue-card">
				<div class="card-body">
					<h5 class="card-title"><?= $this->lang->line('w_surgeries') ?> <span>| <?= $this->lang->line('w_this_month') ?></span></h5>
					<div class="d-flex align-items-center">
						<div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
							<i class="bi bi-heart-pulse"></i>
						</div>
						<div class="ps-3">
							<h6><?= number_format($surgery_qty) ?></h6>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card info-card customers-card">
				<div class="card-body">
					<h5 class="card-title"><?= $this->lang->line('w_sales') ?> <span>| <?= $this->lang->line('w_this_month') ?></span></h5>
					<div class="d-flex align-items-center">
						<div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
							<i class="bi bi-cart"></i>
						</div>
						<div class="ps-3">
							<h6><?= number_format($sale_qty) ?></h6>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-7">
			<div class="card">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-center">
						<h5 class="card-title"><?= $this->lang->line('w_monthly_income') ?></h5>
						<div class="btn-group">
							<?php $cl = ""; foreach($currencies as $item){ ?>
							<button type="button" class="btn btn<?= $cl ?>-primary btn-sm btn_load_income_chart" value="<?= $item->id ?>">
								<?= $item->description ?>
							</button>
							<?php $cl = "-outline";} ?>
						</div>
					</div>
					<div id="chart_monthly_income"></div>
				</div>
			</div>
		</div>
		<div class="col-md-5">
			<div class="card">
				<div class="card-body">
					<h5 class="card-title"><?= $this->lang->line('w_profile') ?></h5>
					<div class="text-center pb-4">
						<h4 class="text-black mb-3"><?= $profile["name"] ?></h4>
						<div><?= $profile["role"] ?></div>
						<div><?= $profile["email"] ?></div>
					</div>
					<div class="row text-center border-top">
						<div class="col-4 pt-3 pr-0 border-end">
							<h3 class="mb-1 text-black"><?= $profile["doctor_qty"] ?></h3>
							<span><?= $this->lang->line('w_doctors') ?></span>
						</div>
						<div class="col-4 pt-3 border-end">
							<h3 class="mb-1 text-black"><?= $profile["patient_qty"] ?></h3>
							<span><?= $this->lang->line('w_patients') ?></span>
						</div>
						<div class="col-4 pt-3 pl-0">
							<h3 class="mb-1 text-black"><?= $profile["account_qty"] ?></h3>
							<span><?= $this->lang->line('w_accounts') ?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<script>
document.addEventListener("DOMContentLoaded", () => {
	$(".btn_load_income_chart").click(function() {
		$(".btn_load_income_chart").removeClass("btn-primary").addClass("btn-outline-primary");
		$(this).removeClass("btn-outline-primary").addClass("btn-primary");
		
		ajax_simple({currency_id: $(this).val()}, "dashboard/load_income_chart").done(function(res) {
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
						let currency = $(this).text().trim();
						
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
	});
	$(".btn_load_income_chart").first().click();
});
</script>