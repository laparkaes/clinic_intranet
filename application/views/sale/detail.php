<div class="pagetitle">
	<h1><?= $this->lang->line('w_sale_detail') ?></h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= $this->lang->line('w_home') ?></a></li>
			<li class="breadcrumb-item"><a href="<?= base_url() ?>sale"><?= $this->lang->line('sales') ?></a></li>
			<li class="breadcrumb-item active"><?= $this->lang->line('txt_detail') ?></li>
		</ol>
	</nav>
</div>
<?php if ($sale->status_id != $canceled_id){ ?>
<div class="row">
	<?php if ($voucher->sale_id){ ?>
	<div class="col-md-3">
		<a href="<?= base_url() ?>sale/voucher/<?= $voucher->id ?>" target="_blank" class="btn btn-primary w-100 mb-3">
			<div><i class="bi bi-file-earmark-text" style="font-size: 50px;"></i></div>
			<div class="fs-16 mt-2 pt-2 border-top border-white"><?= $voucher->type ?></div>
		</a>
	</div>
	<?php }else{ ?>
	<div class="col-md-3">
		<button class="btn btn-primary w-100 mb-3" data-bs-toggle="modal" data-bs-target="#md_voucher">
			<div><i class="bi bi-file-earmark-text" style="font-size: 50px;"></i></div>
			<div class="mt-2 pt-2 border-top border-white"><?= $this->lang->line('btn_voucher') ?></div>
		</button>
	</div>
	<?php } ?>
	<div class="col-md-3">
		<a href="<?= base_url() ?>sale/payment_report/<?= $sale->id ?>" target="_blank" class="btn btn-primary w-100 mb-3">
			<div><i class="bi bi-file-earmark-ruled" style="font-size: 50px;"></i></div>
			<div class="mt-2 pt-2 border-top border-white"><?= $this->lang->line('btn_payment_report') ?></div>
		</a>
	</div>
	<div class="col-md-3">
		<?php if ($sale->balance && ($sale->status_id != $canceled_id)) $d = ""; else $d = "disabled"; ?>
		<button class="btn btn-success w-100 mb-3" data-bs-toggle="modal" data-bs-target="#md_add_payment" <?= $d ?>>
			<div><i class="bi bi-credit-card" style="font-size: 50px;"></i></div>
			<div class="mt-2 pt-2 border-top border-white"><?= $this->lang->line('btn_add_payment') ?></div>
		</button>
	</div>
	<div class="col-md-3">
		<button class="btn btn-outline-danger w-100 mb-3" id="btn_cancel_sale" value="<?= $sale->id ?>">
			<div><i class="bi bi-trash" style="font-size: 50px;"></i></div>
			<div class="mt-2 pt-2 border-top border-danger"><?= $this->lang->line('btn_cancel_sale') ?></div>
		</button>
	</div>
</div>
<?php } ?>
<div class="row mt-3">
	<div class="col">
		<div class="card" style="min-height: 400px;">
			<div class="card-body">
				<h5 class="card-title"><?= $this->lang->line('w_detail') ?></h5>
				<ul class="nav nav-tabs nav-tabs-bordered" role="tablist">
					<li class="nav-item" role="presentation">
						<button class="nav-link active" id="tab_data-tab" data-bs-toggle="tab" data-bs-target="#bordered-tab_data" type="button" role="tab" aria-controls="tab_data" aria-selected="true"><?= $this->lang->line('w_data') ?></button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="tab_medical-tab" data-bs-toggle="tab" data-bs-target="#bordered-tab_medical" type="button" role="tab" aria-controls="tab_medical" aria-selected="false" tabindex="-1"><?= $this->lang->line('w_medical') ?></button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="tab_payments-tab" data-bs-toggle="tab" data-bs-target="#bordered-tab_payments" type="button" role="tab" aria-controls="tab_payments" aria-selected="false" tabindex="-1"><?= $this->lang->line('w_payments') ?></button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="tab_items-tab" data-bs-toggle="tab" data-bs-target="#bordered-tab_items" type="button" role="tab" aria-controls="tab_items" aria-selected="false" tabindex="-1"><?= $this->lang->line('w_items') ?></button>
					</li>
				</ul>
				<div class="tab-content pt-3">
					<div class="tab-pane fade show active" id="bordered-tab_data" role="tabpanel" aria-labelledby="tab_data-tab">
						<div class="row g-3">
							<div class="col-md-6">
								<label class="form-label"><?= $this->lang->line('w_client') ?></label>
								<div class="form-control"><?= $client->name ?></div>
							</div>
							<div class="col-md-3">
								<?php if ($client->doc_number) $doc_val = $client->doc_type." ".$client->doc_number;
								else $doc_val = ""; ?>
								<label class="form-label"><?= $this->lang->line('w_document') ?></label>
								<div class="form-control"><?= $doc_val ?></div>
							</div>
							<div class="col-md-3">
								<label class="form-label"><?= $this->lang->line('w_status') ?></label>
								<div class="form-control text-<?= $sale->status->color ?>"><?= $this->lang->line($sale->status->code) ?></div>
							</div>
							<div class="col-md-3">
								<label class="form-label"><?= $this->lang->line('w_total') ?></label>
								<div class="form-control"><?= $sale->currency." ".number_format($sale->total, 2) ?></div>
							</div>
							<div class="col-md-3">
								<label class="form-label"><?= $this->lang->line('w_balance') ?></label>
								<div class="form-control"><?= $sale->currency." ".number_format($sale->balance, 2) ?></div>
							</div>
							<div class="col-md-3">
								<label class="form-label"><?= $this->lang->line('w_last_update') ?></label>
								<div class="form-control"><?= $sale->updated_at ?></div>
							</div>
							<div class="col-md-3">
								<label class="form-label"><?= $this->lang->line('w_date') ?></label>
								<div class="form-control"><?= $sale->registed_at ?></div>
							</div>
						</div>
						<hr>
						<div class="row g-3 mt-3">
							<div class="col-md-12 d-flex justify-content-between">
								<h4><strong><?= $this->lang->line('w_sunat') ?></strong></h4>
								<div>
									<?php if ((($voucher->id) and (!$voucher->sunat_sent)) or ($voucher->sunat_notes)){ ?>
									<button type="button" class="btn btn-primary btn-sm" id="btn_send_sunat" value="<?= $voucher->id ?>">
										<?= $this->lang->line('btn_send_again') ?>
									</button>
									<?php } if ($voucher->id and $voucher->sunat_sent){ ?>
									<button type="button" class="btn btn-danger btn-sm" value="<?= $voucher->id ?>" data-bs-toggle="modal" data-bs-target="#md_void_voucher">
										<?= $this->lang->line('btn_void')." ".$voucher->type ?>
									</button>
									<?php } ?>
								</div>
							</div>
							<div class="col-md-9">
								<label class="form-label"><?= $this->lang->line('w_messages') ?></label>
								<div class="form-control"><?= $voucher->sunat_msg ?></div>
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
							<div class="col-md-3">
								<label class="form-label"><?= $this->lang->line('w_status') ?></label>
								<div class="form-control text-<?= $voucher->status->color ?>">
									<?= $this->lang->line($voucher->status->code) ?>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="bordered-tab_medical" role="tabpanel" aria-labelledby="tab_medical-tab">
						<?php if ($appo_qty or $surg_qty){ ?>
						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr>
										<th>#</th>
										<th><?= $this->lang->line('w_type') ?></th>
										<th><?= $this->lang->line('w_product') ?></th>
										<th><?= $this->lang->line('w_attention') ?></th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($products as $i => $item){ if($item->type){ ?>
									<tr>
										<td><?= number_format($i + 1) ?></td>
										<td>
											<div><?= $item->type ?></div>
											<?php if ($item->path){ ?>
											<div>
												<a href="<?= $item->path ?>" target="_blank">
													<i class="bi bi-search"></i>
												</a>
											</div>
											<?php } ?>
										</td>
										<td>
											<div><?= $item->product->description ?></div>
											<div><?= $item->product->category ?></div>
										</td>
										<td>
											<?php if ($item->attention){ ?>
											<div><?= $item->attention->patient ?></div>
											<div><?= $item->attention->patient_doc ?></div>
											<div><?= $item->attention->schedule ?></div>
											<?php } ?>
										</td>
										<td class="text-end">
											<?php if ($item->appointment_id or $item->surgery_id){ ?>
											<button type="button" class="btn btn-danger btn_unassign_reservation" value="<?= $item->id ?>">
												<i class="bi bi-trash"></i>
											</button>
											<?php }else{ 
											if ($item->type === $this->lang->line('w_surgery')) $md = "surgery";
											else $md = "appointment"; ?>
											<button type="button" class="btn btn-primary btn_select_product" data-bs-toggle="modal" data-bs-target="#md_reservation_<?= $md ?>" value="<?= $item->id ?>">
												<i class="bi bi-plus"></i>
											</button>
											<?php } ?>
										</td>
									</tr>
									<?php }} ?>
								</tbody>
							</table>
						</div>
						<?php }else{ ?>
						<span><?= $this->lang->line('t_no_medical') ?></span>
						<?php } ?>
					</div>
					<div class="tab-pane fade" id="bordered-contact" role="tabpanel" aria-labelledby="contact-tab">
						Saepe animi et soluta ad odit soluta sunt. Nihil quos omnis animi debitis cumque. Accusantium quibusdam perspiciatis qui qui omnis magnam. Officiis accusamus impedit molestias nostrum veniam. Qui amet ipsum iure. Dignissimos fuga tempore dolor.
					</div>
					<div class="tab-pane fade" id="bordered-contact" role="tabpanel" aria-labelledby="contact-tab">
						Saepe animi et soluta ad odit soluta sunt. Nihil quos omnis animi debitis cumque. Accusantium quibusdam perspiciatis qui qui omnis magnam. Officiis accusamus impedit molestias nostrum veniam. Qui amet ipsum iure. Dignissimos fuga tempore dolor.
					</div>
				</div>
			
				<div class="custom-tab-1">
					<div class="tab-content">
						<div class="tab-pane fade" id="tab_payments" role="tabpanel">
							<div class="table-responsive">
								<table class="table table-responsive-md">
									<thead>
										<tr>
											<th style="width: 70px;"><strong>#</th>
											<th><?= $this->lang->line('w_date') ?></th>
											<th><?= $this->lang->line('w_form_of_payment') ?></th>
											<th><?= $this->lang->line('w_received') ?></th>
											<th><?= $this->lang->line('w_change') ?></th>
											<th><?= $this->lang->line('w_balance') ?></th>
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
											<td class="text-end">
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
											<th>#</th>
											<th><?= $this->lang->line('w_product') ?></th>
											<th><?= $this->lang->line('w_unit_price_short') ?></th>
											<th><?= $this->lang->line('w_discount_short') ?></th>
											<th><?= $this->lang->line('w_qty') ?></th>
											<th class="text-end"><strong><?= $this->lang->line('w_subtotal') ?></th>
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
											<td class="text-nowrap text-end"><?= $sale->currency." ".number_format($price * $p->qty, 2) ?></td>
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
</div>
<?php if ($sale->voucher_id){ ?>
<div class="modal fade " tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="md_void_voucher" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?= $this->lang->line('w_void_voucher') ?></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form class="row g-3" id="form_void_voucher">
					<input type="hidden" name="id" value="<?= $sale->voucher_id ?>">
					<div class="col-md-12">
						<label class="form-label"><?= $this->lang->line('w_reason') ?></label>
						<input class="form-control" type="text" name="reason">
						<div class="sys_msg" id="vv_reason_msg"></div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-bs-dismiss="modal">
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
<div class="modal fade" id="md_voucher" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?= $this->lang->line('w_issuance_receipt') ?></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<?php if ($sale->balance){ ?>
				<p class="text-danger mb-0"><?= $this->lang->line('w_pending_payment').": ".$sale->currency." ".number_format($sale->balance, 2) ?></p>
				<?php }else{ ?>
				<form class="row g-3" id="form_make_voucher">
					<input type="hidden" name="sale_id" value="<?= $sale->id ?>">
					<div class="col-md-6">
						<label class="form-label"><?= $this->lang->line('w_document') ?></label>
						<select class="form-select" id="mv_doc_type" name="cli[doc_type_id]">
							<?php foreach($doc_types as $item){
								if ($item->id == $client->doc_type_id) $s = "selected"; else $s= ""; ?>
							<option value="<?= $item->id ?>" <?= $s ?>><?= $item->description ?></option>
							<?php } ?>
						</select>
						<div class="sys_msg" id="mv_doc_type_msg"></div>
					</div>
					<div class="col-md-6">
						<label class="form-label d-md-block d-none">&nbsp;</label>
						<div class="input-group">
							<input type="text" class="form-control" id="mv_doc_number" name="cli[doc_number]" value="<?= $client->doc_number ?>">
							<button class="btn btn-primary" type="button" id="btn_search_person_mv">
								<i class="bi bi-search"></i>
							</button>
						</div>
						<div class="sys_msg" id="mv_doc_number_msg"></div>
					</div>
					<div class="col-md-12">
						<label class="form-label"><?= $this->lang->line('w_client') ?></label>
						<input type="text" class="form-control" id="mv_name" name="cli[name]" value="<?= $client->name ?>">
						<div class="sys_msg" id="mv_name_msg"></div>
					</div>
					<div class="col-md-12">
						<label class="form-label"><?= $this->lang->line('w_voucher') ?></label>
						<select class="form-select" name="voucher_type_id">
							<?php foreach($voucher_types as $item){ ?>
							<option value="<?= $item->id ?>"><?= $item->description ?></option>
							<?php } ?>
						</select>
						<div class="sys_msg" id="mv_voucher_type_msg"></div>
					</div>
				</form>
				<?php } ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-bs-dismiss="modal">
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
<div class="modal fade" id="md_reservation_appointment" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?= $this->lang->line('w_assign_appointment') ?></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row g-3">
					<div class="col-md-12">
						<label class="form-label"><?= $this->lang->line('w_doc_number') ?></label>
						<div class="input-group">
							<input type="text" class="form-control" id="rs_appointment_doc_number" value="<?= $client->doc_number ?>">
							<button class="btn btn-primary" type="button" id="btn_search_appointment">
								<i class="bi bi-search"></i>
							</button>
						</div>
					</div>
					<div class="col-md-12" style="max-height: 300px; overflow-y: auto;">
						<table class="table">
							<thead>
								<tr>
									<th>#</th>
									<th><?= $this->lang->line('w_reservations') ?></th>
									<th></th>
								</tr>
							</thead>
							<tbody id="rs_appointment_list"></tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-bs-dismiss="modal">
					<?= $this->lang->line('btn_close') ?>
				</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="md_reservation_surgery" tabindex="-1">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?= $this->lang->line('w_assign_surgery') ?></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row g-3">
					<div class="col-md-12">
						<label class="form-label"><?= $this->lang->line('w_doc_number') ?></label>
						<div class="input-group">
							<input type="text" class="form-control" id="rs_surgery_doc_number" value="<?= $client->doc_number ?>">
							<button class="btn btn-primary" type="button" id="btn_search_surgery">
								<i class="bi bi-search"></i>
							</button>
						</div>
					</div>
					<div class="col-md-12" style="max-height: 300px; overflow-y: auto;">
						<table class="table">
							<thead>
								<tr>
									<th>#</th>
									<th><?= $this->lang->line('w_reservations') ?></th>
									<th></th>
								</tr>
							</thead>
							<tbody id="rs_surgery_list"></tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-bs-dismiss="modal">
					<?= $this->lang->line('btn_close') ?>
				</button>
			</div>
		</div>
	</div>
</div>
<?php } if ($sale->balance && ($sale->status_id != $canceled_id)){ ?>
<div class="modal fade" id="md_add_payment" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?= $this->lang->line('w_new_payment') ?></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form class="row g-3" id="form_add_payment">
					<input type="hidden" name="sale_id" value="<?= $sale->id ?>" readonly>
					<input type="hidden" id="payment_total" name="total" value="<?= $sale->balance ?>" readonly>
					<input type="hidden" id="payment_received" name="received" value="<?= $sale->balance ?>">
					<input type="hidden" id="payment_change" name="change" value="0">
					<input type="hidden" id="payment_balance" name="balance" value="0">
					<div class="col-md-12 d-flex justify-content-between text-primary">
						<h3 class="text-primary"><?= $this->lang->line('w_amount_to_pay') ?></h3>
						<h3 class="text-primary"><?= $sale->currency." ".number_format($sale->balance, 2) ?></h3>
					</div>
					<div class="col-md-6">
						<label class="form-label"><?= $this->lang->line('w_payment_method') ?></label>
						<select class="form-select" name="payment_method_id">
							<?php foreach($payment_method as $item){ ?>
							<option value="<?= $item->id ?>"><?= $item->description ?></option>
							<?php } ?>
						</select>
						<div class="sys_msg" id="payment_method_msg"></div>
					</div>
					<div class="col-md-6">
						<label class="form-label"><?= $this->lang->line('w_received') ?></label>
						<div class="input-group">
							<span class="input-group-text"><?= $sale->currency ?></span>
							<input type="text" class="form-control text-end" id="payment_received_v" value="<?= number_format($sale->balance, 2) ?>">
						</div>
						<div class="sys_msg" id="pay_received_msg"></div>
					</div>
					<div class="col-md-6">
						<label class="form-label"><?= $this->lang->line('w_change') ?></label>
						<div class="input-group">
							<span class="input-group-text"><?= $sale->currency ?></span>
							<input type="text" class="form-control text-end" id="payment_change_v" value="0.00">
						</div>
						<div class="sys_msg" id="pay_change_msg"></div>
					</div>
					<div class="col-md-6">
						<label class="form-label"><?= $this->lang->line('w_balance') ?></label>
						<div class="input-group">
							<span class="input-group-text"><?= $sale->currency ?></span>
							<input type="text" class="form-control text-end" id="payment_balance_v" value="0.00" readonly>
						</div>
						<div class="sys_msg" id="pay_balance_msg"></div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-bs-dismiss="modal">
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
<input type="hidden" id="btn_select_lang" value="<?= $this->lang->line('btn_select') ?>">