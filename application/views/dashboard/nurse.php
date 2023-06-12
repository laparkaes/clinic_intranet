<div class="col-md-6">
	<div class="card">
		<div class="card-header pb-0 border-0">
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
		<div class="card-header pb-0 border-0">
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