<div class="col-12">
	<h4 class="mb-3"><?= $this->lang->line('title_today_progress') ?></h4>
</div>
<div class="col-md-6">
	<div class="widget-stat card">
		<div class="card-body p-4">
			<h4 class="card-title"><?= $this->lang->line('lb_attended_appointments') ?></h4>
			<h3><?= number_format($app_attended) ?></h3>
			<div class="progress mb-2">
				<div class="progress-bar progress-animated bg-primary" style="width: <?= ($app_attended)/($app_attended + $app_reserved) ?>%"></div>
			</div>
			<?= number_format($app_reserved)." ".$this->lang->line('txt_reserved_today') ?>
		</div>
	</div>
</div>
<div class="col-md-6">
	<div class="widget-stat card">
		<div class="card-body p-4">
			<h4 class="card-title"><?= $this->lang->line('lb_attended_surgeries') ?></h4>
			<h3><?= number_format($sur_attended) ?></h3>
			<div class="progress mb-2">
				<div class="progress-bar progress-animated bg-secondary" style="width: <?= ($sur_attended)/($sur_attended + $sur_reserved) ?>%"></div>
			</div>
			<?= number_format($sur_reserved)." ".$this->lang->line('txt_reserved_today') ?>
		</div>
	</div>
</div>
<div class="col-md-12">
	<div class="card">
		<div class="card-body">
			<h4 class="card-title"><?= $this->lang->line('title_sales') ?></h4>
			<?php if ($sales){ ?>
			<div class="table-responsive">
				<table class="table table-responsive-md mb-0">
					<thead>
						<tr>
							<th><strong>#</strong></th>
							<th><strong><?= $this->lang->line('hd_date') ?></strong></th>
							<th><strong><?= $this->lang->line('hd_client') ?></strong></th>
							<th><strong><?= $this->lang->line('hd_total') ?></strong></th>
							<th><strong><?= $this->lang->line('hd_balance') ?></strong></th>
							<th><strong><?= $this->lang->line('hd_status') ?></strong></th>
							<th><strong><?= $this->lang->line('hd_sunat') ?></strong></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($sales as $i => $item){ $cur = $item->currency->description; ?>
						<tr>
							<td><strong><?= number_format(1 + $i) ?></strong></td>
							<td><?= $item->registed_at ?></td>
							<td>
								<?php if ($item->client) echo $item->client->name; else echo "-"; ?>
							</td>
							<td><?= $cur." ".number_format($item->total, 2) ?></td>
							<td>
								<?php if ($item->balance) echo $cur." ".number_format($item->balance, 2); else echo "-"; ?>
							</td>
							<td>
								<span class="badge light badge-<?= $item->status->color ?>">
									<?= $item->status->lang ?>
								</span>
							</td>
							<td>
								<?php if ($item->voucher){ ?>
								<i class="fas fa-circle text-<?= $item->voucher->color ?>" title="<?= $item->voucher->sunat_msg ?>"></i>
								<?php } ?>
							</td>
							<td class="text-right">
								<a href="<?= base_url() ?>sale/detail/<?= $item->id ?>">
									<button type="button" class="btn btn-primary light sharp border-0">
										<i class="fas fa-search"></i>
									</button>
								</a>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<?php }else{ ?>
			<h5 class="text-danger mt-3"><?= $this->lang->line('msg_no_sales') ?></h5>
			<?php } ?>
		</div>
	</div>
</div>