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
		<div class="card">
			<div class="card-body">
				<div class="bl_content" id="bl_list">
					<h5 class="card-title"><?= $this->lang->line('w_list') ?></h5>
					<div class="row">
						<div class="col">
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
												<a href="<?= base_url() ?>sale/detail/<?= $item->id ?>" class="btn btn-primary btn-sm">
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
									<a href="<?= base_url() ?>sale?<?= http_build_query($f_url) ?>" class="btn btn-<?= $p[2] ?>">
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
				</div>
				<div class="bl_content d-none" id="bl_add">
					<h5 class="card-title"><?= $this->lang->line('w_new_sale') ?></h5>
					<form id="form_add_sale" class="row">
						<input type="hidden" id="sale_total">
						<input type="hidden" id="op_currency" name="currency" value="">
						<input type="hidden" id="payment_received" name="payment[received]">
						<input type="hidden" id="payment_change" name="payment[change]">
						<input type="hidden" id="payment_balance" name="payment[balance]">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table" id="tb_products">
									<thead>
										<tr>
											<th>#</th>
											<th><?= $this->lang->line('w_item') ?></th>
											<th><?= $this->lang->line('w_qty') ?></th>
											<th><?= $this->lang->line('w_unit_price_short') ?></th>
											<th><?= $this->lang->line('w_subtotal') ?></th>
											<th class="text-end">
												<button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#sl_product_modal">
													<i class="bi bi-plus-lg"></i>
												</button>
											</th>
										</tr>
									</thead>
									<tbody id="tb_product_list"></tbody>
								</table>
							</div>
						</div>
						<div class="col-md-12">
							<div class="alert alert-primary alert-dismissible fade show text-center my-3" role="alert">
								<strong><?= $this->lang->line('w_total') ?>: </strong>
								<strong id="sl_pr_total_amount">0.00</strong>
							</div>
						</div>
						<div class="col-md-6 payment_info d-none">
							<div class="row g-3">
								<div class="col-md-12">
									<label class="form-label"><?= $this->lang->line('w_sale_type') ?></label>
									<select class="form-select" name="sale_type_id">
										<?php foreach($sale_types as $item){ ?>
										<option value="<?= $item->id ?>"><?= $item->sunat_serie." - ".$item->description ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="col-md-6">
									<label class="form-label"><?= $this->lang->line('w_payment_method') ?></label>
									<select class="form-select" name="payment[payment_method_id]">
										<?php foreach($payment_methods as $item){ ?>
										<option value="<?= $item->id ?>"><?= $item->description ?></option>
										<?php } ?>
									</select>
									<div class="sys_msg" id="payment_method_msg"></div>
								</div>
								<div class="col-md-6">
									<label class="form-label"><?= $this->lang->line('w_received') ?></label>
									<div class="input-group">
										<span class="input-group-text payment_currency"></span>
										<input type="text" class="form-control text-end" id="payment_received_v">
									</div>
									<div class="sys_msg" id="pay_received_msg"></div>
								</div>
								<div class="col-md-6">
									<label class="form-label"><?= $this->lang->line('w_change') ?></label>
									<div class="input-group">
										<span class="input-group-text payment_currency"></span>
										<input type="text" class="form-control text-end" id="payment_change_v" value="0.00">
									</div>
									<div class="sys_msg" id="pay_change_msg"></div>
								</div>
								<div class="col-md-6">
									<label class="form-label"><?= $this->lang->line('w_balance') ?></label>
									<div class="input-group">
										<span class="input-group-text payment_currency"></span>
										<input type="text" class="form-control text-end" id="payment_balance_v" value="0.00" readonly>
									</div>
									<div class="sys_msg" id="pay_balance_msg"></div>
								</div>
							</div>
						</div>
						<div class="col-md-6 payment_info d-none">
							<div class="row g-3">
								<div class="col-md-6">
									<label class="form-label"><?= $this->lang->line('w_document') ?></label>
									<select class="form-select" id="client_doc_type" name="client[doc_type_id]">
										<?php foreach($doc_types as $item){ ?>
										<option value="<?= $item->id ?>"><?= $item->description ?></option>
										<?php } ?>
									</select>
									<div class="sys_msg" id="client_doc_type_msg"></div>
								</div>
								<div class="col-md-6">
									<label class="form-label d-md-block d-none">&nbsp;</label>
									<div class="input-group">
										<input type="text" class="form-control" id="client_doc_number" name="client[doc_number]" placeholder="<?= $this->lang->line('w_number') ?>">
										<button class="btn btn-primary" type="button" id="btn_search_client">
											<i class="bi bi-search"></i>
										</button>
									</div>
									<div class="sys_msg" id="client_doc_number_msg"></div>
								</div>
								<div class="col-md-12">
									<label class="form-label"><?= $this->lang->line('w_name') ?></label>
									<input type="text" class="form-control" id="client_name" name="client[name]">
									<div class="sys_msg" id="client_name_msg"></div>
								</div>
								<div class="col-md-12 pt-3">
									<button type="button" class="btn btn-primary" id="btn_add_sale">
										<?= $this->lang->line('btn_register_sale') ?>
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="sl_product_modal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?= $this->lang->line('w_add_product') ?></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row g-3">
					<div class="col-md-12">
						<label class="form-label"><?= $this->lang->line('w_category') ?></label>
						<select class="form-select" id="sl_pr_category">
							<option value="">--</option>
							<?php foreach($categories as $item){ ?>
							<option value="<?= $item->id ?>"><?= $item->name ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="col-md-12">
						<label class="form-label"><?= $this->lang->line('w_item') ?></label>
						<select class="form-select" id="sl_pr_items">
							<option value="">--</option>
						</select>
					</div>
					<div class="col-md-6 sl_pr_detail d-none">
						<label class="form-label"><?= $this->lang->line('w_unit_price') ?></label>
						<input type="text" class="form-control" id="sl_pr_uprice_txt" readonly>
					</div>
					<div class="col-md-6 sl_pr_detail d-none">
						<label class="form-label"><?= $this->lang->line('w_option') ?></label>
						<select class="form-select" id="sl_pr_options">
							<option value="">--</option>
						</select>
					</div>
					<div class="col-md-6 sl_pr_detail d-none">
						<label class="form-label"><?= $this->lang->line('w_unit_discount') ?></label>
						<input type="text" class="form-control" id="sl_pr_udiscount" step="0.01" min="0">
					</div>
					<div class="col-md-6 sl_pr_detail d-none">
						<label class="form-label"><?= $this->lang->line('w_quantity') ?></label>
						<input type="text" class="form-control" id="sl_pr_quantity">
					</div>
					<div class="col-md-12 sl_pr_detail d-none">
						<label class="form-label"><?= $this->lang->line('w_subtotal') ?></label>
						<input type="text" class="form-control text-end" id="sl_pr_subtotal" value="0.00" readonly>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger light" id="btn_sl_pr_cancel" data-bs-dismiss="modal">
					<?= $this->lang->line('btn_close') ?>
				</button>
				<button type="button" class="btn btn-primary" id="btn_sl_pr_add">
					<?= $this->lang->line('btn_add') ?>
				</button>
			</div>
		</div>
	</div>
</div>
<input type="hidden" id="e_item_option" value="<?= $this->lang->line('e_item_option') ?>">