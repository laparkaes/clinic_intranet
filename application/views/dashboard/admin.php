<div class="pagetitle">
	<h1><?= $title ?></h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="/"><?= $this->lang->line('w_home') ?></a></li>
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
						<div class="mb-3"><i class="fas fa-users-crown fa-7x"></i></div>
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