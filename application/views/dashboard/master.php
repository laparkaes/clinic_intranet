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
<div class="col-md-12">
	<div class="card">
		<div class="card-header pb-0 border-0">
			<h5 class="mb-0"><?= $this->lang->line('tab_monthly_income') ?></h5>
			<div class="btn-group">
				<?php $cl = ""; foreach($currencies as $item){ ?>
				<button type="button" class="btn btn<?= $cl ?>-primary btn-xs btn_load_income_chart" value="<?= $item->id ?>"><?= $item->description ?></button>
				<?php $cl = "-outline";} ?>
			</div>
		</div>
		<div class="card-body" id="chart_monthly_income"></div>
	</div>
</div>
<div class="col-md-6">
	<div class="card">
		<div class="card-body">
			<h5><?= $this->lang->line('tab_appointment_surgery') ?></h5>
			<div id="chart_monthly_income"></div>
		</div>
	</div>
</div>
<div class="col-md-6">
	<div class="card">
		<div class="card-body">
			<h5><?= $this->lang->line('tab_best_seller') ?></h5>
			<div id="chart_monthly_income"></div>
		</div>
	</div>
</div>