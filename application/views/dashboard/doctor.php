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
					<h5 class="card-title"><?= $this->lang->line('w_patients') ?> <span>| <?= $this->lang->line('w_this_month') ?></span></h5>
					<div class="d-flex align-items-center">
						<div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
							<i class="bi bi-person"></i>
						</div>
						<div class="ps-3">
							<h6><?= number_format($patient_qty) ?></h6>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-7">
			<div class="card">
				<div class="card-body">
					<h5 class="card-title"><?= $this->lang->line('w_today') ?></h5>
					<?php if ($schedules){ ?>
					<div class="table-responsive">
						<table class="table text-center bg-info-hover tr-rounded mb-0">
							<thead>
								<tr>
									<th class="text-left"><?= $this->lang->line('w_time') ?></th>
									<th class="text-center"><?= $this->lang->line('w_type') ?></th>
									<th class="text-right"></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($schedules as $item){ ?>
								<tr>
									<td class="text-left text-nowrap"><?= date("h:i a", strtotime($item["from"])) ?></td>
									<td>
										<div><?= $this->lang->line("txt_".$item["type"]) ?></div>
										<small class="text-muted"><?= $item["patient"] ?></small>
									</td>
									<td class="text-right">
										<a href="/intranet/<?= $item["type"] ?>/detail/<?= $item["id"] ?>">
											<button type="button" class="btn btn-info light sharp">
												<i class="fas fa-arrow-alt-right"></i>
											</button>
										</a>
									</td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
					<?php }else{ ?>
					<div class="text-success"><?= $this->lang->line('t_no_pending') ?></div>
					<?php } ?>
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
							<h3 class="mb-1 text-black"><?= $profile["appointment_qty"] ?></h3>
							<span><?= $this->lang->line('w_appointments') ?></span>
						</div>
						<div class="col-4 pt-3 border-end">
							<h3 class="mb-1 text-black"><?= $profile["surgery_qty"] ?></h3>
							<span><?= $this->lang->line('w_surgeries') ?></span>
						</div>
						<div class="col-4 pt-3 pl-0">
							<h3 class="mb-1 text-black"><?= $profile["patient_qty"] ?></h3>
							<span><?= $this->lang->line('w_patients') ?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>