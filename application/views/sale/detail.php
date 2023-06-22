<div class="col-md-12">
	<div class="row page-titles mx-0">
		<div class="col-sm-6 p-md-0">
			<div class="welcome-text">
				<h4><?= $this->lang->line('title_sale_detail') ?></h4>
			</div>
		</div>
		<div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?= base_url() ?>sale"><?= $this->lang->line('sales') ?></a></li>
				<li class="breadcrumb-item active"><a href="javascript:void(0)"><?= $this->lang->line('txt_detail') ?></a></li>
			</ol>
		</div>
	</div>
</div>
<?php if ($sale->status_id != $canceled_id){ ?>
<div class="col-md-12">
	<div class="row d-flex justify-content-center">
		<?php if ($voucher->sale_id){ ?>
		<div class="col-md-3">
			<a href="<?= base_url() ?>sale/voucher/<?= $voucher->id ?>" target="_blank">
				<button class="btn btn-primary w-100 mb-3">
					<div><i class="fal fa-sticky-note fa-5x fa-fw"></i></div>
					<div class="fs-16 mt-2 pt-2 border-top border-white"><?= $voucher->type ?></div>
				</button>
			</a>
		</div>
		<?php }else{ ?>
		<div class="col-md-3">
			<button class="btn btn-primary w-100 mb-3" data-toggle="modal" data-target=".md_voucher">
				<div><i class="fal fa-sticky-note fa-5x fa-fw"></i></div>
				<div class="fs-16 mt-2 pt-2 border-top border-white"><?= $this->lang->line('btn_voucher') ?></div>
			</button>
		</div>
		<?php } ?>
		<div class="col-md-3">
			<a href="<?= base_url() ?>sale/payment_report/<?= $sale->id ?>" target="_blank">
				<button class="btn btn-primary w-100 mb-3">
					<div><i class="fal fa-money-check-edit fa-5x fa-fw"></i></div>
					<div class="fs-16 mt-2 pt-2 border-top border-white"><?= $this->lang->line('btn_payment_report') ?></div>
				</button>
			</a>
		</div>
		<div class="col-md-3">
			<?php if ($sale->balance && ($sale->status_id != $canceled_id)) $d = ""; else $d = "disabled"; ?>
			<button class="btn btn-info w-100 mb-3" data-toggle="modal" data-target="#md_add_payment" <?= $d ?>>
				<div><i class="fal fa-cash-register fa-5x fa-fw"></i></div>
				<div class="fs-16 mt-2 pt-2 border-top border-white"><?= $this->lang->line('btn_add_payment') ?></div>
			</button>
		</div>
		<div class="col-md-3">
			<button class="btn btn-outline-danger w-100 mb-3" id="btn_cancel_sale" value="<?= $sale->id ?>">
				<div><i class="fal fa-trash fa-5x fa-fw"></i></div>
				<div class="fs-16 mt-2 pt-2 border-top border-danger"><?= $this->lang->line('btn_cancel_sale') ?></div>
			</button>
		</div>
	</div>
</div>
<?php } ?>
<div class="col-md-12">
	<div class="card" style="min-height: 400px;">
		<div class="card-body">
			<div class="custom-tab-1">
				<ul class="nav nav-tabs mb-4" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" data-toggle="tab" href="#tab_data">
							<i class="far fa-comment-alt fa-fw mr-3"></i><?= $this->lang->line("tab_data") ?>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#tab_medical">
							<i class="far fa-notes-medical fa-fw mr-3"></i><?= $this->lang->line("tab_medical") ?>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#tab_payments">
							<i class="far fa-money-check fa-fw mr-3"></i><?= $this->lang->line("tab_payments") ?>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#tab_items">
							<i class="far fa-box fa-fw mr-3"></i><?= $this->lang->line("tab_items") ?>
						</a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane fade show active" id="tab_data" role="tabpanel">
						<div class="row">
							<div class="col-md-6 mb-3">
								<h5 class="mb-1"><?= $this->lang->line('label_client') ?></h5>
								<div><?= $client->name ?></div>
							</div>
							<div class="col-md-3 mb-3">
								<?php if ($client->doc_number) $doc_val = $client->doc_type." ".$client->doc_number;
								else $doc_val = ""; ?>
								<h5 class="mb-1"><?= $this->lang->line('label_document') ?></h5>
								<div><?= $doc_val ?></div>
							</div>
							<div class="col-md-3 mb-3">
								<h5 class="mb-1"><?= $this->lang->line('label_status') ?></h5>
								<div class="text-<?= $sale->status->color ?>"><?= $this->lang->line($sale->status->code) ?></div>
							</div>
							<div class="col-md-3 mb-3">
								<h5 class="mb-1"><?= $this->lang->line('label_total') ?></h5>
								<div><?= $sale->currency." ".number_format($sale->total, 2) ?></div>
							</div>
							<div class="col-md-3 mb-3">
								<h5 class="mb-1"><?= $this->lang->line('label_balance') ?></h5>
								<div><?= $sale->currency." ".number_format($sale->balance, 2) ?></div>
							</div>
							<div class="col-md-3 mb-3">
								<h5 class="mb-1"><?= $this->lang->line('label_last_update') ?></h5>
								<div><?= $sale->updated_at ?></div>
							</div>
							<div class="col-md-3 mb-3">
								<h5 class="mb-1"><?= $this->lang->line('label_date') ?></h5>
								<div><?= $sale->registed_at ?></div>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12 mb-3  d-flex justify-content-between">
								<h4 class="mr-5 mb-0"><?= $this->lang->line('label_sunat') ?></h4>
								<div>
									<?php if ((($voucher->id) and (!$voucher->sunat_sent)) or ($voucher->sunat_notes)){ ?>
									<button type="button" class="btn btn-primary btn-xs mb-1" id="btn_send_sunat" value="<?= $voucher->id ?>">
										<?= $this->lang->line('btn_send_again') ?>
									</button>
									<?php } if ($voucher->id){ ?>
									<button type="button" class="btn btn-danger btn-xs mb-1" value="<?= $voucher->id ?>" data-toggle="modal" data-target=".md_void_voucher">
										<?= $this->lang->line('btn_void')." ".$voucher->type ?>
									</button>
									<?php } ?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-9 mb-3">
								<h5 class="mb-1"><?= $this->lang->line('lb_messages') ?></h5>
								<div><?= $voucher->sunat_msg ?></div>
								<?php if ($voucher->sunat_notes){ ?>
								<div class="mt-2">
									<?php $voucher->sunat_notes = explode("&&&", $voucher->sunat_notes); 
									foreach($voucher->sunat_notes as $item){ ?>
									<div class="mt-1">
										> <?= $item ?>
									</div>
									<?php } ?>
								</div>
								<?php } ?>
							</div>
							<div class="col-md-3 mb-3">
								<h5 class="mb-1"><?= $this->lang->line('label_status') ?></h5>
								<div class="text-<?= $voucher->status->color ?>">
									<?= $this->lang->line($voucher->status->code) ?>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="tab_medical" role="tabpanel">
						<?php if ($appo_qty or $surg_qty){ ?>
						<div class="table-responsive">
							<table class="table table-responsive-md">
								<thead>
									<tr>
										<th style="width: 70px;"><strong>#</strong></th>
										<th><strong><?= $this->lang->line('hd_type') ?></strong></th>
										<th><strong><?= $this->lang->line('hd_product') ?></strong></th>
										<th><strong><?= $this->lang->line('hd_attention') ?></strong></th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($products as $i => $item){ if($item->type){ ?>
									<tr>
										<td><?= $i + 1 ?></td>
										<td><?= $item->type ?></td>
										<td>
											<div><?= $item->product->description ?></div>
											<div><small><?= $item->product->category ?></small></div>
										</td>
										<td>
											<?php if ($item->attention){ ?>
											<div><?= $item->attention->schedule ?></div>
											<div><?= $item->attention->patient ?></div>
											<small><?= $item->attention->patient_doc ?></small>
											<?php } ?>
										</td>
										<td class="text-right">
											<?php if ($item->appointment_id or $item->surgery_id){ ?>
											<button type="button" class="btn btn-danger btn_unassign_reservation" value="<?= $item->id ?>">
												<i class="fas fa-trash"></i>
											</button>
											<?php }else{ 
											if ($item->type === $this->lang->line('txt_surgery')) $md = "surgery";
											else $md = "appointment"; ?>
											<button type="button" class="btn btn-primary btn_select_product" data-toggle="modal" data-target="#md_reservation_<?= $md ?>" value="<?= $item->id ?>">
												<i class="fas fa-plus"></i>
											</button>
											<?php } ?>
										</td>
									</tr>
									<?php }} ?>
								</tbody>
							</table>
						</div>
						<?php }else{ ?>
						<h4><?= $this->lang->line('msg_no_medical_attention') ?></h4>
						<?php } ?>
					</div>
					<div class="tab-pane fade" id="tab_payments" role="tabpanel">
						<div class="table-responsive">
							<table class="table table-responsive-md">
								<thead>
									<tr>
										<th style="width: 70px;"><strong>#</strong></th>
										<th><strong><?= $this->lang->line('hd_date') ?></strong></th>
										<th><strong><?= $this->lang->line('hd_form_of_payment') ?></strong></th>
										<th><strong><?= $this->lang->line('hd_received') ?></strong></th>
										<th><strong><?= $this->lang->line('hd_change') ?></strong></th>
										<th><strong><?= $this->lang->line('hd_balance') ?></strong></th>
										<?php if (!$voucher->sale_id){ ?>
										<th></th>
										<?php } ?>
									</tr>
								</thead>
								<tbody>
									<?php $p_qty = count($payments); foreach($payments as $i => $p){ ?>
									<tr>
										<td><?= $i + 1 ?></td>
										<td><?= $p->registed_at ?></td>
										<td><?= $p->payment_method ?></td>
										<td><?= $sale->currency." ".number_format($p->received, 2) ?></td>
										<td><?php if ($p->change) echo $sale->currency." ".number_format($p->change, 2);
										else echo "-" ?></td>
										<td><?= $sale->currency." ".number_format($p->balance, 2) ?></td>
										<?php if (!$voucher->sale_id){ ?>
										<td class="text-right">
											<?php if ((!$i) and ($p_qty > 1)){ ?>
											<button type="button" class="btn btn-danger" id="btn_delete_payment" value="<?= $p->id ?>">
												<i class="fas fa-trash"></i>
											</button>
											<?php } ?>
										</td>
										<?php } ?>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="tab-pane fade" id="tab_items" role="tabpanel">
						<div class="table-responsive">
							<table class="table table-responsive-md mb-0">
								<thead>
									<tr>
										<th><strong>#</strong></th>
										<th><strong><?= $this->lang->line('hd_product') ?></strong></th>
										<th><strong><?= $this->lang->line('hd_unit_price_short') ?></strong></th>
										<th><strong><?= $this->lang->line('hd_discount_short') ?></strong></th>
										<th><strong><?= $this->lang->line('hd_qty') ?></strong></th>
										<th class="text-right"><strong><?= $this->lang->line('hd_subtotal') ?></strong></th>
									</tr>
								</thead>
								<tbody>
									<?php $i = 0; foreach($products as $p){ $price = $p->price - $p->discount; $i++; ?>
									<tr>
										<td><?= $i ?></td>
										<td>
											<div><?= $p->product->category ?></div>
											<div><?= $p->product->code ?></div>
											<div><?= $p->product->description ?></div>
											<?php if ($p->product->option){ ?>
											<div><?= $p->product->option ?></div>
											<?php } ?>
										</td>
										<td class="text-nowrap"><?= $sale->currency." ".number_format($p->price, 2) ?></td>
										<td>
											<?php if ($p->discount) echo $sale->currency." ".number_format($p->discount, 2);
											else echo "-" ?>
										</td>
										<td><?= number_format($p->qty) ?></td>
										<td class="text-nowrap text-right"><?= $sale->currency." ".number_format($price * $p->qty, 2) ?></td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php if ($sale->voucher_id){ ?>
<div class="modal fade md_void_voucher" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?= $this->lang->line('title_void_voucher') ?></h5>
				<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
			</div>
			<div class="modal-body">
				<form action="#" id="form_void_voucher">
					<input type="hidden" name="id" value="<?= $sale->voucher_id ?>">
					<div class="form-row">
						<div class="form-group col-md-12 mb-0">
							<label><?= $this->lang->line('label_reason') ?></label>
							<input type="text" class="form-control" name="reason">
							<div class="sys_msg" id="vv_reason_msg"></div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger light" data-dismiss="modal">
					<?= $this->lang->line('btn_close') ?>
				</button>
				<?php if (!$sale->balance){ ?>
				<button type="button" class="btn btn-primary" id="btn_void_voucher">
					<?= $this->lang->line('btn_void') ?>
				</button>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<?php }else{ ?>
<div class="modal fade md_voucher" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?= $this->lang->line('title_issuance_receipt') ?></h5>
				<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
			</div>
			<div class="modal-body">
				<?php if ($sale->balance){ ?>
				<p class="text-danger mb-0"><?= $this->lang->line('txt_pending_payment').": ".$sale->currency." ".number_format($sale->balance, 2) ?></p>
				<?php }else{ ?>
				<form action="#" id="form_make_voucher">
					<input type="hidden" name="sale_id" value="<?= $sale->id ?>">
					<div class="form-row">
						<div class="form-group col-md-6">
							<label><?= $this->lang->line('label_document') ?></label>
							<select class="form-control" id="mv_doc_type" name="cli[doc_type_id]">
								<?php foreach($doc_types as $item){
									if ($item->id == $client->doc_type_id) $s = "selected"; else $s= ""; ?>
								<option value="<?= $item->id ?>" <?= $s ?>><?= $item->description ?></option>
								<?php } ?>
							</select>
							<div class="sys_msg" id="mv_doc_type_msg"></div>
						</div>
						<div class="form-group col-md-6">
							<label class="d-md-block d-none">&nbsp;</label>
							<div class="input-group">
								<input type="text" class="form-control" id="mv_doc_number" name="cli[doc_number]" value="<?= $client->doc_number ?>">
								<div class="input-group-append">
									<button class="btn btn-primary border-0" type="button" id="btn_search_person_mv">
										<i class="fas fa-search"></i>
									</button>
								</div>
							</div>
							<div class="sys_msg" id="mv_doc_number_msg"></div>
						</div>
						<div class="form-group col-md-12">
							<label><?= $this->lang->line('label_client') ?></label>
							<input type="text" class="form-control" id="mv_name" name="cli[name]" value="<?= $client->name ?>">
							<div class="sys_msg" id="mv_name_msg"></div>
						</div>
						<div class="form-group col-md-12 mb-0">
							<label><?= $this->lang->line('label_voucher') ?></label>
							<select class="form-control" name="voucher_type_id">
								<?php foreach($voucher_types as $item){ ?>
								<option value="<?= $item->id ?>"><?= $item->description ?></option>
								<?php } ?>
							</select>
							<div class="sys_msg" id="mv_voucher_type_msg"></div>
						</div>
					</div>
				</form>
				<?php } ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger light" data-dismiss="modal">
					<?= $this->lang->line('btn_close') ?>
				</button>
				<?php if (!$sale->balance){ ?>
				<button type="button" class="btn btn-primary" id="btn_make_voucher">
					<?= $this->lang->line('btn_emit') ?>
				</button>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<?php } if ($appo_qty or $surg_qty){ ?>
<input type="hidden" id="rs_selected_product">
<div class="modal fade" id="md_reservation_appointment" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?= $this->lang->line('title_assign_appointment') ?></h5>
				<button type="button" class="close" data-dismiss="modal"><span>×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-row">
					<div class="form-group col-md-12">
						<label><?= $this->lang->line('label_doc_number') ?></label>
						<div class="input-group">
							<input type="text" class="form-control" id="rs_appointment_doc_number">
							<div class="input-group-append">
								<button class="btn btn-primary border-0" type="button" id="btn_search_appointment">
									<i class="fas fa-search"></i>
								</button>
							</div>
						</div>
					</div>
					<div class="form-group col-md-12" style="max-height: 300px; overflow-y: auto;">
						<table class="table">
							<thead>
								<tr>
									<th><strong>#</strong></th>
									<th><strong><?= $this->lang->line('hd_reservations') ?></strong></th>
									<th></th>
								</tr>
							</thead>
							<tbody id="rs_appointment_list"></tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger light" data-dismiss="modal">
					<?= $this->lang->line('btn_close') ?>
				</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="md_reservation_surgery" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?= $this->lang->line('title_assign_surgery') ?></h5>
				<button type="button" class="close" data-dismiss="modal"><span>×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-row">
					<div class="form-group col-md-12">
						<label><?= $this->lang->line('label_doc_number') ?></label>
						<div class="input-group">
							<input type="text" class="form-control" id="rs_surgery_doc_number">
							<div class="input-group-append">
								<button class="btn btn-primary border-0" type="button" id="btn_search_surgery">
									<i class="fas fa-search"></i>
								</button>
							</div>
						</div>
					</div>
					<div class="form-group col-md-12" style="max-height: 300px; overflow-y: auto;">
						<table class="table">
							<thead>
								<tr>
									<th><strong>#</strong></th>
									<th><strong><?= $this->lang->line('hd_reservations') ?></strong></th>
									<th></th>
								</tr>
							</thead>
							<tbody id="rs_surgery_list"></tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger light" data-dismiss="modal">
					<?= $this->lang->line('btn_close') ?>
				</button>
			</div>
		</div>
	</div>
</div>
<?php } if ($sale->balance && ($sale->status_id != $canceled_id)){ ?>
<div class="modal fade" id="md_add_payment" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?= $this->lang->line('title_new_payment') ?></h5>
				<button type="button" class="close" data-dismiss="modal"><span>×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="form_add_payment">
					<input type="hidden" name="sale_id" value="<?= $sale->id ?>" readonly>
					<input type="hidden" id="payment_total" name="total" value="<?= $sale->balance ?>" readonly>
					<input type="hidden" id="payment_received" name="received" value="<?= $sale->balance ?>">
					<input type="hidden" id="payment_change" name="change" value="0">
					<input type="hidden" id="payment_balance" name="balance" value="0">
					<div class="form-row">
						<div class="form-group col-md-12 d-flex justify-content-between text-primary">
							<h3 class="text-primary"><?= $this->lang->line('label_amount_to_pay') ?></h3>
							<h3 class="text-primary"><?= $sale->currency." ".number_format($sale->balance, 2) ?></h3>
						</div>
						<div class="form-group col-md-6">
							<label><?= $this->lang->line('label_payment_method') ?></label>
							<select class="form-control" name="payment_method_id">
								<?php foreach($payment_method as $item){ ?>
								<option value="<?= $item->id ?>"><?= $item->description ?></option>
								<?php } ?>
							</select>
							<div class="sys_msg" id="payment_method_msg"></div>
						</div>
						<div class="form-group col-md-6">
							<label><?= $this->lang->line('label_received') ?></label>
							<div class="input-group input-normal-o">
								<div class="input-group-prepend">
									<span class="input-group-text"><?= $sale->currency ?></span>
								</div>
								<input type="text" class="form-control text-right" id="payment_received_v" value="<?= number_format($sale->balance, 2) ?>">
							</div>
							<div class="sys_msg" id="pay_received_msg"></div>
						</div>
						<div class="form-group col-md-6">
							<label><?= $this->lang->line('label_change') ?></label>
							<div class="input-group input-normal-o">
								<div class="input-group-prepend">
									<span class="input-group-text"><?= $sale->currency ?></span>
								</div>
								<input type="text" class="form-control text-right" id="payment_change_v" value="0.00">
							</div>
							<div class="sys_msg" id="pay_change_msg"></div>
						</div>
						<div class="form-group col-md-6">
							<label><?= $this->lang->line('label_balance') ?></label>
							<div class="input-group input-warning-o">
								<div class="input-group-prepend">
									<span class="input-group-text bg-light"><?= $sale->currency ?></span>
								</div>
								<input type="text" class="form-control bg-light text-right" id="payment_balance_v" value="0.00" readonly>
							</div>
							<div class="sys_msg" id="pay_balance_msg"></div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger light" data-dismiss="modal">
					<?= $this->lang->line('btn_cancel') ?>
				</button>
				<button type="button" class="btn btn-primary" id="btn_add_payment">
					<?= $this->lang->line('btn_save') ?>
				</button>
			</div>
		</div>
	</div>
</div>
<?php } ?>
<input type="hidden" id="warning_apa" value="<?= $this->lang->line('warning_apa') ?>">
<input type="hidden" id="warning_dpa" value="<?= $this->lang->line('warning_dpa') ?>">
<input type="hidden" id="warning_csa" value="<?= $this->lang->line('warning_csa') ?>">
<input type="hidden" id="warning_mvo" value="<?= $this->lang->line('warning_mvo') ?>">
<input type="hidden" id="warning_mti" value="<?= $this->lang->line('warning_mti') ?>">
<input type="hidden" id="warning_siu" value="<?= $this->lang->line('warning_siu') ?>">
<input type="hidden" id="warning_svs" value="<?= $this->lang->line('warning_svs') ?>">
<input type="hidden" id="warning_vvo" value="<?= $this->lang->line('warning_vvo') ?>">
<input type="hidden" id="btn_select_lang" value="<?= $this->lang->line('btn_select') ?>">