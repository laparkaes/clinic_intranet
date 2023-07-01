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
		<div class="card-body text-center d-flex align-items-center justify-content-center">
			<div>
				<div class="mb-3"><i class="fas fa-user-md fa-7x"></i></div>
				<h4 class="text-black mb-3"><?= $profile["name"] ?></h4>
				<div><?= $profile["role"] ?></div>
				<div>(<?= $profile["email"] ?>)</div>
			</div>
		</div>
		<div class="card-footer p-0 text-center">
			<div class="row">
				<div class="col-4 py-3 pr-0 border-right">
					<h3 class="mb-1 text-primary"><?= $profile["doctor_qty"] ?></h3>
					<span>Medicos</span>
				</div>
				<div class="col-4 py-3 border-right">
					<h3 class="mb-1 text-primary"><?= $profile["patient_qty"] ?></h3>
					<span>Pacientes</span>
				</div>
				<div class="col-4 py-3 pl-0">
					<h3 class="mb-1 text-primary"><?= $profile["account_qty"] ?></h3>
					<span>Usuarios</span>
				</div>
			</div>
		</div>
	</div>
</div>












<div class="col-md-6">
	<div class="card">
		<div class="card-header">
			<h4 class="card-title"><?= $this->lang->line('title_appointments') ?></h4>
		</div>
		<div class="card-body">
			<?php if ($apps){ ?>
			<div class="basic-list-group">
				<div class="list-group">
					<?php foreach($apps as $item){ ?>
					<div class="list-group-item list-group-item-action flex-column align-items-start">
						<div class="d-flex justify-content-between w-100">
							<h5 class="text-<?= $item->status->color ?>"><?= $item->from ?></h5>
							<a href="<?= base_url() ?>appointment/detail/<?= $item->id ?>">
								<button type="button" class="btn btn-primary btn-xs light sharp border-0">
									<i class="far fa-search"></i>
								</button>
							</a>
						</div>
						<p class="mb-1"><?= $item->patient ?></p>
						<small><?= $item->doctor ?><br/><?= $item->specialty ?></small>
					</div>
					<?php } ?>
				</div>
			</div>
			<?php }else{ ?>
			<span class="text-muted"><?= $this->lang->line('msg_no_appointment') ?></span>
			<?php } ?>
		</div>
	</div>
</div>
<div class="col-md-6">
	<div class="card">
		<div class="card-header">
			<h4 class="card-title"><?= $this->lang->line('title_surgeries') ?></h4>
		</div>
		<div class="card-body">
			<?php if ($surs){ ?>
			<div class="basic-list-group">
				<div class="list-group">
					<?php foreach($surs as $item){ ?>
					<div class="list-group-item list-group-item-action flex-column align-items-start">
						<div class="d-flex justify-content-between w-100">
							<h5 class="text-<?= $item->status->color ?>"><?= $item->from ?></h5>
							<a href="<?= base_url() ?>surgery/detail/<?= $item->id ?>">
								<button type="button" class="btn btn-primary btn-xs light sharp border-0">
									<i class="far fa-search"></i>
								</button>
							</a>
						</div>
						<p class="mb-1"><?= $item->patient ?></p>
						<small><?= $item->doctor ?><br/><?= $item->specialty ?></small>
					</div>
					<?php } ?>
				</div>
			</div>
			<?php }else{ ?>
			<span class="text-muted"><?= $this->lang->line('msg_no_surgery') ?></span>
			<?php } ?>
		</div>
	</div>
</div>
<input type="hidden" id="cl_today" value="<?= $this->lang->line('cl_today') ?>">
<input type="hidden" id="cl_month" value="<?= $this->lang->line('cl_month') ?>">
<input type="hidden" id="cl_week" value="<?= $this->lang->line('cl_week') ?>">
<input type="hidden" id="cl_day" value="<?= $this->lang->line('cl_day') ?>">
<input type="hidden" id="cl_list" value="<?= $this->lang->line('cl_list') ?>">
<input type="hidden" id="cl_allday" value="<?= $this->lang->line('cl_allday') ?>">