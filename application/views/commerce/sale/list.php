<div class="d-flex justify-content-between align-items-start">
	<div class="pagetitle">
		<h1><?= $title ?></h1>
		<nav>
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= $this->lang->line('w_home') ?></a></li>
				<li class="breadcrumb-item active"><?= $title ?></li>
			</ol>
		</nav>
	</div>
	<div class="btn-group mb-3">
		<button type="button" class="btn btn-primary control_bl" id="btn_list" value="bl_list">
			<i class="bi bi-card-list"></i>
		</button>
		<button type="button" class="btn btn-outline-primary control_bl" value="bl_add">
			<i class="bi bi-plus-lg"></i>
		</button>
	</div>
</div>
<form class="row justify-content-end">
		<input type="hidden" value="1" name="page">
		<div class="col-md-auto">
			<input type="text" class="form-control" name="client" placeholder="<?= $this->lang->line('w_client') ?>" value="<?= $f_url["client"] ?>">
		</div>
		<div class="col-md-auto">
			<button type="submit" class="btn btn-primary">
				<i class="bi bi-search"></i>
			</button>
		</div>
	</div>
</form>
<div class="row mt-3">
	<div class="col">
		<div class="card bl_content" id="bl_list">
			<div class="card-body">
				<h5 class="card-title"><?= $this->lang->line('w_list') ?></h5>
				<?php if ($sales){ ?>
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<th>#</th>
								<th><?= $this->lang->line('w_date') ?></th>
								<th><?= $this->lang->line('w_client') ?></th>
								<th class="text-nowrap"><?= $this->lang->line('w_total') ?> (<?= $this->lang->line('w_balance') ?>)</th>
								<th><?= $this->lang->line('w_status') ?></th>
								<th><?= $this->lang->line('w_sunat') ?></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($sales as $i => $item){ $cur = $item->currency->description; ?>
							<tr>
								<th><?= number_format(($f_url["page"] - 1) * 25 + 1 + $i) ?></th>
								<td><?= str_replace(" ", "<br/>", $item->registed_at) ?></td>
								<td>
									<?php if ($item->client) echo $item->client->name; else echo "-"; ?>
								</td>
								<td class="text-nowrap">
									<?= $cur." ".number_format($item->total, 2) ?>
									<?php if ($item->balance) echo "<br/><small>(".$cur." ".number_format($item->balance, 2).")</small>"; ?>
								</td>
								<td class="text-nowrap text-<?= $item->status->color ?>"><?= $item->status->lang ?></td>
								<td>
									<i class="bi bi-circle-fill text-<?= $item->voucher->color ?>" title="<?= $item->voucher->sunat_msg ?>"></i>
								</td>
								<td class="text-end">
									<a href="<?= base_url() ?>commerce/sale/detail/<?= $item->id ?>" class="btn btn-primary btn-sm">
										<i class="bi bi-arrow-right"></i>
									</a>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
					<div class="btn-group" role="group" aria-label="paging">
						<?php foreach($paging as $p){
						$f_url["page"] = $p[0]; ?>
						<a href="<?= base_url() ?>commerce/sale?<?= http_build_query($f_url) ?>" class="btn btn-<?= $p[2] ?>">
							<?= $p[1] ?>
						</a>
						<?php } ?>
					</div>
				</div>
				<?php }else{ ?>
				<h5 class="text-danger"><?= $this->lang->line('t_no_sales') ?></h5>
				<?php } ?>
			</div>
		</div>
		<div class="card bl_content d-none" id="bl_add">
			<?php $this->load->view("commerce/sale/form_add_sale"); ?>
		</div>
	</div>
</div>
<input type="hidden" id="e_item_option" value="<?= $this->lang->line('e_item_option') ?>">
<script>
document.addEventListener("DOMContentLoaded", () => {
	$(".control_bl").click(function() {
		control_bl(this);
	});
	
	var params = get_params();
	if (params.a == "add") $("#btn_add").trigger("click");
});
</script>