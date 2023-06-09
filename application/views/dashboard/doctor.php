<div class="col-md-12">
	<h4><?= $this->lang->line('title_monthly_resume') ?></h4>
</div>
<div class="col-md-4">
	<div class="card">
		<div class="card-body row py-3">
			<div class="col-md-4 px-md-0 text-center d-flex align-items-center justify-content-center">
				<i class="fas fa-notes-medical" style="color: #5fe1ad; font-size: 40px;"></i>
			</div>
			<div class="col-md-8 pr-md-0 text-center">
				<p class="mb-1"><?= $this->lang->line('lb_appointments') ?></p>
				<h4 class="mb-0"><?= number_format($appointment_qty) ?></h4>
			</div>
		</div>
	</div>
</div>
<div class="col-md-4">
	<div class="card">
		<div class="card-body row py-3">
			<div class="col-md-4 px-md-0 text-center d-flex align-items-center justify-content-center">
				<i class="fas fa-file-medical-alt" style="color: #AC4CBC; font-size: 40px;"></i>
			</div>
			<div class="col-md-8 pr-md-0 text-center">
				<p class="mb-1"><?= $this->lang->line('lb_surgeries') ?></p>
				<h4 class="mb-0"><?= number_format($surgery_qty) ?></h4>
			</div>
		</div>
	</div>
</div>
<div class="col-md-4">
	<div class="card">
		<div class="card-body row py-3">
			<div class="col-md-4 px-md-0 text-center d-flex align-items-center justify-content-center">
				<i class="fas fa-user-injured" style="color: #3A82EF; font-size: 40px;"></i>
			</div>
			<div class="col-md-8 pr-md-0 text-center">
				<p class="mb-1"><?= $this->lang->line('lb_patients') ?></p>
				<h4 class="mb-0"><?= number_format($patient_qty) ?></h4>
			</div>
		</div>
	</div>
</div>
<div class="col-md-7 mb-4">
	<h4><?= $this->lang->line('title_today') ?></h4>
	<div class="card mb-0" style="max-height: 450px; overflow-y: auto;">
		<div class="card-body">
			<?php if ($schedules){ ?>
			<div class="table-responsive">
				<table class="table text-center bg-info-hover tr-rounded mb-0">
					<thead>
						<tr>
							<th class="text-left"><?= $this->lang->line('th_time') ?></th>
							<th class="text-center"><?= $this->lang->line('th_type') ?></th>
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
			<div class="text-success"><?= $this->lang->line('msg_no_pending') ?></div>
			<?php } ?>
		</div>
	</div>
</div>
<div class="col-md-5 mb-4">
	<h4><?= $this->lang->line('title_profile') ?></h4>
	<div class="card">
		<div class="card-body text-center d-flex align-items-top justify-content-center">
			<div>
				<div class="mb-3"><i class="fas fa-user-md fa-7x"></i></div>
				<h4 class="text-black mb-3"><?= $profile["name"] ?></h4>
				<div><?= $profile["role"] ?></div>
				<div><?= $profile["specialty"] ?></div>
				<div><?= $profile["license"] ?></div>
				<div><?= $profile["email"] ?></div>
			</div>
		</div>
		<div class="card-footer p-0 text-center">
			<div class="row">
				<div class="col-4 py-3 pr-0 border-right">
					<h3 class="mb-1 text-primary"><?= $profile["appointment_qty"] ?></h3>
					<span><?= $this->lang->line('lb_appointments') ?></span>
				</div>
				<div class="col-4 py-3 border-right">
					<h3 class="mb-1 text-primary"><?= $profile["surgery_qty"] ?></h3>
					<span><?= $this->lang->line('lb_surgeries') ?></span>
				</div>
				<div class="col-4 py-3 pl-0">
					<h3 class="mb-1 text-primary"><?= $profile["patient_qty"] ?></h3>
					<span><?= $this->lang->line('lb_patients') ?></span>
				</div>
			</div>
		</div>
	</div>
</div>