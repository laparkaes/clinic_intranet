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
					<i class="fas fa-user-injured"></i>
				</span>
				<div class="media-body text-center">
					<p class="mb-1"><?= $this->lang->line('lb_patients') ?></p>
					<h4 class="mb-0"><?= number_format($patient_qty) ?></h4>
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