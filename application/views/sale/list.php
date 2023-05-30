<div class="col-sm-12 d-flex justify-content-between align-items-center pb-3">
	<div class="welcome-text d-md-none d-block">
		<h4 class="text-primary mb-0"><?= $this->lang->line('sales') ?></h4>
	</div>
	<div class="btn-group">
		<button type="button" class="btn btn-primary control_bl" id="btn_list" value="bl_list">
			<i class="fas fa-list mr-2"></i><?= $this->lang->line('btn_list') ?>
		</button>
		<button type="button" class="btn btn-outline-primary control_bl" value="bl_add">
			<i class="fas fa-plus mr-2"></i><?= $this->lang->line('btn_add') ?>
		</button>
	</div>
</div>
<div class="col-md-12">
	<div class="card">
		<div class="card-body">
			<div class="row bl_content" id="bl_list">
				<div class="col-md-12 d-md-flex justify-content-end">
					<form class="form-inline">
						<input type="hidden" value="1" name="page">
						<label class="sr-only" for="sl_status"><?= $this->lang->line('lb_status') ?></label>
						<select class="form-control mb-2 mr-sm-2" id="sl_status" name="status" style="max-width: 150px;">
							<option value=""><?= $this->lang->line('lb_status') ?></option>
							<?php foreach($status as $item){ if ($item->id == $f_url["status"]) $s = "selected"; else $s = ""; ?>
							<option value="<?= $item->id ?>" <?= $s ?>><?= $this->lang->line($item->code) ?></option>
							<?php } ?>
						</select>
						<label class="sr-only" for="inp_date"><?= $this->lang->line('lb_date') ?></label>
						<input type="text" class="form-control date_picker_all bw mb-2 mr-sm-2" id="inp_date" name="date" placeholder="<?= $this->lang->line('lb_date') ?>" value="<?= $f_url["date"] ?>">
						<button type="submit" class="btn btn-primary mb-2">
							<i class="far fa-search"></i>
						</button>
					</form>
				</div>
				<div class="col-md-12">
					<?php if ($sales){ ?>
					<div class="table-responsive">
						<table class="table table-responsive-md">
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
									<td><strong><?= number_format(($f_url["page"] - 1) * 25 + 1 + $i) ?></strong></td>
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
					<h5 class="text-danger mt-3"><?= $this->lang->line('msg_no_sales') ?></h5>
					<?php } ?>
				</div>
			</div>
			<div class="row bl_content d-none" id="bl_add">
				<form id="form_add_sale">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-12">
								<h4 class="mb-3">1. <?= $this->lang->line('tab_item_selection') ?></h4>
								<div class="table-responsive">
									<table class="table table-small table-responsive-md mb-0" id="tb_products">
										<thead>
											<tr>
												<th><strong>#</strong></th>
												<th><strong><?= $this->lang->line('hd_item') ?></strong></th>
												<th class="text-center"><strong><?= $this->lang->line('hd_quantity') ?></strong></th>
												<th class="text-right"><strong><?= $this->lang->line('hd_price') ?></strong></th>
												<th class="text-right"><strong><?= $this->lang->line('hd_subtotal') ?></strong></th>
												<th class="text-right"></th>
											</tr>
										</thead>
										<tbody id="tb_product_list"></tbody>
									</table>
								</div>
							</div>
							<div class="col-md-6 pt-3">
								<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#sl_product_modal"><i class="fas fa-plus mr-2"></i><?= $this->lang->line('title_add_product') ?></button>
							</div>
							<div class="col-md-6 d-flex justify-content-between text-primary pt-5">
								<h3 class="text-primary">Total</h3>
								<h3 class="text-primary" id="sl_pr_total_amount">0.00</h3>
							</div>
						</div>
						<hr/>
						<div class="row mt-3">
							<div class="col-md-12">
								<h4 class="mb-3">2. <?= $this->lang->line('tab_payment_data') ?></h4>
							</div>
							<div class="col-md-6 col-sm-12">
								<h5><?= $this->lang->line('title_client') ?></h5>
								<div class="form-row">
									<div class="form-group col-md-6">
										<label><?= $this->lang->line('lb_document') ?></label>
										<select class="form-control" id="client_doc_type" name="client[doc_type_id]">
											<?php foreach($doc_types as $item){ ?>
											<option value="<?= $item->id ?>"><?= $item->description ?></option>
											<?php } ?>
										</select>
										<div class="sys_msg" id="client_doc_type_msg"></div>
									</div>
									<div class="form-group col-md-6">
										<label class="d-md-block d-none">&nbsp;</label>
										<div class="input-group">
											<input type="text" class="form-control" id="client_doc_number" name="client[doc_number]" placeholder="<?= $this->lang->line('txt_number') ?>">
											<div class="input-group-append">
												<button class="btn btn-primary border-0" type="button" id="btn_search_client">
													<i class="fas fa-search"></i>
												</button>
											</div>
										</div>
										<div class="sys_msg" id="client_doc_number_msg"></div>
									</div>
									<div class="form-group col-md-12">
										<label><?= $this->lang->line('label_name') ?></label>
										<input type="text" class="form-control" id="client_name" name="client[name]">
										<div class="sys_msg" id="client_name_msg"></div>
									</div>
								</div>
							</div>
							<div class="col-md-6 col-sm-12">
								<input type="hidden" id="sale_total">
								<input type="hidden" id="op_currency" name="currency" value="">
								<input type="hidden" id="payment_received" name="payment[received]">
								<input type="hidden" id="payment_change" name="payment[change]">
								<input type="hidden" id="payment_balance" name="payment[balance]">
								<h5><?= $this->lang->line('title_operation') ?></h5>
								<div class="form-row">
									<div class="form-group col-md-6">
										<label><?= $this->lang->line('label_payment_method') ?></label>
										<select class="form-control" name="payment[payment_method_id]">
											<?php foreach($payment_methods as $item){ ?>
											<option value="<?= $item->id ?>"><?= $item->description ?></option>
											<?php } ?>
										</select>
										<div class="sys_msg" id="payment_method_msg"></div>
									</div>
									<div class="form-group col-md-6">
										<label><?= $this->lang->line('label_received') ?></label>
										<div class="input-group input-normal-o">
											<div class="input-group-prepend">
												<span class="input-group-text payment_currency"></span>
											</div>
											<input type="text" class="form-control text-right" id="payment_received_v">
										</div>
										<div class="sys_msg" id="pay_received_msg"></div>
									</div>
									<div class="form-group col-md-6">
										<label><?= $this->lang->line('label_change') ?></label>
										<div class="input-group input-normal-o">
											<div class="input-group-prepend">
												<span class="input-group-text payment_currency"></span>
											</div>
											<input type="text" class="form-control text-right" id="payment_change_v" value="0.00">
										</div>
										<div class="sys_msg" id="pay_change_msg"></div>
									</div>
									<div class="form-group col-md-6">
										<label><?= $this->lang->line('label_balance') ?></label>
										<div class="input-group input-warning-o">
											<div class="input-group-prepend">
												<span class="input-group-text bg-light payment_currency"></span>
											</div>
											<input type="text" class="form-control bg-light text-right" id="payment_balance_v" value="0.00" readonly>
										</div>
										<div class="sys_msg" id="pay_balance_msg"></div>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<button type="button" class="btn btn-primary mt-3" id="btn_add_sale">
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
<div class="d-none">
	<input type="hidden" id="warning_asa" value="<?= $this->lang->line('warning_asa') ?>">
	<input type="hidden" id="error_prl" value="<?= $this->lang->line('error_prl') ?>">
	<input type="hidden" id="error_prlc" value="<?= $this->lang->line('error_prlc') ?>">
	<input type="hidden" id="error_prsl" value="<?= $this->lang->line('error_prsl') ?>">
	<input type="hidden" id="error_sit" value="<?= $this->lang->line('error_sit') ?>">
	<input type="hidden" id="error_sio" value="<?= $this->lang->line('error_sio') ?>">
	<input type="hidden" id="error_psq" value="<?= $this->lang->line('error_psq') ?>">
</div>
<div class="modal fade" id="sl_product_modal" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header border-0 pb-0">
				<h5 class="modal-title"><?= $this->lang->line('title_add_product') ?></h5>
				<button type="button" class="close" data-dismiss="modal"><span>×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-row">
					<div class="form-group col-md-12">
						<label><?= $this->lang->line('lb_category') ?></label>
						<select class="form-control" id="sl_pr_category">
							<option value="">--</option>
							<?php foreach($categories as $item){ ?>
							<option value="<?= $item->id ?>"><?= $item->name ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="form-group col-md-12">
						<label><?= $this->lang->line('lb_item') ?></label>
						<select class="form-control" id="sl_pr_items">
							<option value="">--</option>
						</select>
					</div>
					<div class="form-group col-md-6 sl_pr_detail d-none">
						<label><?= $this->lang->line('lb_unit_price') ?></label>
						<input type="text" class="form-control" id="sl_pr_uprice_txt" readonly>
					</div>
					<div class="form-group col-md-6 sl_pr_detail d-none">
						<label><?= $this->lang->line('lb_option') ?></label>
						<select class="form-control" id="sl_pr_options">
							<option value="">--</option>
						</select>
					</div>
					<div class="form-group col-md-6 sl_pr_detail d-none">
						<label><?= $this->lang->line('lb_unit_discount') ?></label>
						<input type="text" class="form-control" id="sl_pr_udiscount" step="0.01" min="0">
					</div>
					<div class="form-group col-md-6 sl_pr_detail d-none">
						<label><?= $this->lang->line('lb_quantity') ?></label>
						<input type="text" class="form-control" id="sl_pr_quantity">
					</div>
					<div class="form-group col-md-12 sl_pr_detail d-none">
						<label><?= $this->lang->line('lb_subtotal') ?></label>
						<input type="txt" class="form-control bw border-0 text-right font-weight-bold" id="sl_pr_subtotal" value="0.00" readonly>
					</div>
				</div>
			</div>
			<div class="modal-footer border-0 pt-0">
				<button type="button" class="btn btn-danger light" id="btn_sl_pr_cancel" data-dismiss="modal"><?= $this->lang->line('btn_close') ?></button>
				<button type="button" class="btn btn-primary" id="btn_sl_pr_add"><?= $this->lang->line('btn_add') ?></button>
			</div>
		</div>
	</div>
</div>