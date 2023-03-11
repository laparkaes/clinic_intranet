<div class="col-12">
	<h4 class="mb-3"><?= $this->lang->line('title_monthly_resume') ?></h4>
</div>
<div class="col-md-4">
	<div class="widget-stat card">
		<div class="card-body p-0">
			<div class="media ai-icon">
				<span class="bgl-primary text-white rounded-left" style="border-radius: 0;">
					<i class="fas fa-notes-medical"></i>
				</span>
				<div class="media-body text-center">
					<p class="mb-1"><?= $this->lang->line('lb_appointments') ?></p>
					<h4 class="mb-0"><?= number_format($appointment_qty) ?></h4>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="col-md-4">
	<div class="widget-stat card">
		<div class="card-body p-0">
			<div class="media ai-icon">
				<span class="bgl-secondary text-secondary rounded-left" style="border-radius: 0;">
					<i class="fas fa-file-medical-alt"></i>
				</span>
				<div class="media-body text-center">
					<p class="mb-1"><?= $this->lang->line('lb_surgeries') ?></p>
					<h4 class="mb-0"><?= number_format($surgery_qty) ?></h4>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="col-md-4">
	<div class="widget-stat card">
		<div class="card-body p-0">
			<div class="media ai-icon">
				<span class="bgl-info text-info rounded-left" style="border-radius: 0;">
					<i class="fas fa-shopping-basket"></i>
				</span>
				<div class="media-body text-center">
					<p class="mb-1"><?= $this->lang->line('lb_sales') ?></p>
					<h4 class="mb-0"><?= number_format($sale_qty) ?></h4>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="col-12">
	<h4 class="mb-3"><?= $this->lang->line('title_monthly_income') ?></h4>
</div>
<div class="col-md-12">
	<div class="card">
		<div class="card-body">
			<div id="chart_monthly_income"></div>
		</div>
	</div>
</div>