<div class="col-md-12">
	<div class="card">
		<div class="card-body">
			<div class="row">
				<div class="col-md-4">
					<a type="button" class="btn btn-primary btn-block d-flex justify-content-between align-items-center text-white py-3 my-1" href="<?= base_url() ?>appointment?a=add">
						<i class="fas fa-notes-medical fa-2x fa-fw mr-3"></i>
						<span class="fs-20"><?= $this->lang->line('title_appointment') ?></span>
					</a>
				</div>
				<div class="col-md-4">
					<a type="button" class="btn btn-primary btn-block d-flex justify-content-between align-items-center text-white py-3 my-1" href="<?= base_url() ?>surgery?a=add">
						<i class="fas fa-file-medical-alt fa-2x fa-fw mr-3"></i>
						<span class="fs-20"><?= $this->lang->line('title_surgery') ?></span>
					</a>
				</div>
				<div class="col-md-4">
					<a type="button" class="btn btn-primary btn-block d-flex justify-content-between align-items-center text-white py-3 my-1" href="<?= base_url() ?>sale?a=add">
						<i class="fas fa-shopping-basket fa-2x fa-fw mr-3"></i>
						<span class="fs-20"><?= $this->lang->line('title_sale') ?></span>
					</a>
				</div>
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
				<div><?= $profile["email"] ?></div>
			</div>
		</div>
	</div>
</div>