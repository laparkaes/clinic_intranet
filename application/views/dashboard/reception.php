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
<div class="col-md-6">
	<div class="card">
		<div class="card-header">
			<h4 class="card-title"><?= $this->lang->line('title_appointments') ?></h4>
		</div>
		<div class="card-body">
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
		</div>
	</div>
</div>
<div class="col-md-6">
	<div class="card">
		<div class="card-header">
			<h4 class="card-title"><?= $this->lang->line('title_surgeries') ?></h4>
		</div>
		<div class="card-body">
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
		</div>
	</div>
</div>