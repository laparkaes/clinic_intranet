<div class="col-md-7 mb-4">
	<h4><?= $this->lang->line('title_monthly_income') ?></h4>
	<div class="card">
		<div class="card-header pb-0 border-0">
			<div class="btn-group">
				<?php $cl = ""; foreach($currencies as $item){ ?>
				<button type="button" class="btn btn<?= $cl ?>-primary btn-xs btn_load_income_chart" value="<?= $item->id ?>">
					<?= $item->description ?>
				</button>
				<?php $cl = "-outline";} ?>
			</div>
		</div>
		<div class="card-body p-0" id="chart_monthly_income"></div>
	</div>
</div>
<div class="col-md-5 mb-4">
	<h4><?= $this->lang->line('title_profile') ?></h4>
	<div class="card">
		<div class="card-body">
			hola hola
		</div>
		<div class="card-footer p-0 text-center">
			<div class="row">
				<div class="col-4 py-3 pr-0 border-right">
					<h3 class="mb-1 text-primary">150</h3>
					<span>Projects</span>
				</div>
				<div class="col-4 py-3 border-right">
					<h3 class="mb-1 text-primary">140</h3>
					<span>Uploads</span>
				</div>
				<div class="col-4 py-3 pl-0">
					<h3 class="mb-1 text-primary">45</h3>
					<span>Tasks</span>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="col-md-12">
	<h4><?= $this->lang->line('title_monthly_resume') ?></h4>
</div>
<div class="col-md-4 mb-4">
	<div class="card">
		<div class="card-body row py-3">
			<div class="col-md-4 px-md-0" id="ch_rs_appointments" style="display: flex; justify-content: flex-end;"></div>
			<div class="col-md-8 pr-md-0 text-center">
				<p class="mb-1"><?= $this->lang->line('lb_appointments') ?></p>
				<h4 class="mb-0"><?= number_format($appointment_qty) ?></h4>
			</div>
		</div>
	</div>
</div>
<div class="col-md-4 mb-4">
	<div class="card">
		<div class="card-body row py-3">
			<div class="col-md-4 px-md-0" id="ch_rs_surgeries" style="display: flex; justify-content: flex-end;"></div>
			<div class="col-md-8 pr-md-0 text-center">
				<p class="mb-1"><?= $this->lang->line('lb_surgeries') ?></p>
				<h4 class="mb-0"><?= number_format($surgery_qty) ?></h4>
			</div>
		</div>
	</div>
</div>
<div class="col-md-4 mb-4">
	<div class="card">
		<div class="card-body row py-3">
			<div class="col-md-4 px-md-0" id="ch_rs_sales" style="display: flex; justify-content: flex-end;"></div>
			<div class="col-md-8 pr-md-0 text-center">
				<p class="mb-1"><?= $this->lang->line('lb_sales') ?></p>
				<h4 class="mb-0"><?= number_format($sale_qty) ?></h4>
			</div>
		</div>
	</div>
</div>



<div class="col-md-4 mb-4">
	<div class="widget-stat card mb-0">
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
<div class="col-md-4 mb-4">
	<div class="widget-stat card mb-0">
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
<div class="col-md-4 mb-4">
	<div class="widget-stat card mb-0">
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
		<div class="card-body">
		asaas
		</div>
	</div>
</div>