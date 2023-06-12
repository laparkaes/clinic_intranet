<div class="col-md-6">
	<div class="widget-stat card">
		<div class="card-body p-4">
			<h4 class="card-title"><?= $this->lang->line('lb_appointments') ?></h4>
			<h3><?= number_format($app_attended) ?></h3>
			<div class="progress mb-2">
				<?php if (($app_attended + $app_reserved) > 0) $w = ($app_attended)/($app_attended + $app_reserved);
				else $w = 100; ?>
				<div class="progress-bar progress-animated bg-primary" style="width: <?= $w ?>%"></div>
			</div>
			<div class="text-right"><?= number_format($app_reserved)." ".$this->lang->line('txt_more_today') ?></div>
		</div>
	</div>
</div>
<div class="col-md-6">
	<div class="widget-stat card">
		<div class="card-body p-4">
			<h4 class="card-title text-secondary"><?= $this->lang->line('lb_surgeries') ?></h4>
			<h3 class=" text-secondary"><?= number_format($sur_attended) ?></h3>
			<div class="progress mb-2">
				<?php if (($sur_attended + $sur_reserved) > 0) $w = ($sur_attended)/($sur_attended + $sur_reserved);
				else $w = 100; ?>
				<div class="progress-bar progress-animated bg-secondary" style="width: <?= $w ?>%"></div>
			</div>
			<div class="text-right"><?= number_format($sur_reserved)." ".$this->lang->line('txt_more_today') ?></div>
		</div>
	</div>
</div>
<div class="col-md-12">
	<div class="card">
		<div class="card-header">
			<h4 class="card-title mb-0"><?= $this->lang->line('title_sales') ?></h4>
		</div>
		<div class="card-body">
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