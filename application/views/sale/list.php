<div class="col-md-12">
	<div class="row page-titles mx-0">
		<div class="col-sm-6 p-md-0">
			<div class="welcome-text">
				<h4><?= $this->lang->line('sales') ?></h4>
			</div>
		</div>
		<div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
			<div class="btn-group">
				<button type="button" class="btn btn-primary control_bl" id="btn_list" value="bl_list">
					<i class="fas fa-list"></i>
				</button>
				<button type="button" class="btn btn-outline-primary control_bl" value="bl_add">
					<i class="fas fa-plus"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<div class="col-md-12">
	<div class="card">
		<div class="card-body">
			<div class="row bl_content" id="bl_list">
				<div class="col-md-12 d-md-flex justify-content-end">
					<form class="form-inline">
						<input type="hidden" value="1" name="page">
						<label class="sr-only" for="sl_type"><?= $this->lang->line('sl_specialty') ?></label>
						<select class="form-control mb-2 mr-sm-2" id="sl_type" name="specialty" style="max-width: 150px;">
							<option value=""><?= $this->lang->line('sl_specialty') ?></option>
							<?php foreach($specialties as $item){
								if ($item->id == $f_url["specialty"]) $s = "selected"; else $s = ""; ?>
							<option value="<?= $item->id ?>" <?= $s ?>><?= $item->name ?></option>
							<?php } ?>
						</select>
						<label class="sr-only" for="inp_name"><?= $this->lang->line('lb_name') ?></label>
						<input type="text" class="form-control mb-2 mr-sm-2" id="inp_name" name="name" placeholder="<?= $this->lang->line('lb_search_by_name') ?>" value="<?= $f_url["name"] ?>">
						<button type="submit" class="btn btn-primary mb-2">
							<i class="far fa-search"></i>
						</button>
					</form>
				</div>
				<div class="col-md-12">
					<?php if ($doctors){ ?>
					<div class="table-responsive">
						<table class="table table-responsive-md">
							<thead>
								<tr>
									<th><strong>#</strong></th>
									<th><strong><?= $this->lang->line('hd_license') ?></strong></th>
									<th><strong><?= $this->lang->line('hd_specialty') ?></strong></th>
									<th><strong><?= $this->lang->line('hd_name') ?></strong></th>
									<th><strong><?= $this->lang->line('hd_tel') ?></strong></th>
									<th><strong><?= $this->lang->line('hd_status') ?></strong></th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($doctors as $i => $item){ ?>
								<tr>
									<td><strong><?= number_format(($f_url["page"] - 1) * 25 + 1 + $i) ?></strong></td>
									<td><?= $item->license ?></td>
									<td style="max-width: 150px;"><?= $specialties_arr[$item->specialty_id] ?></td>
									<td><?= $item->person->name ?></td>
									<td><?= $item->person->tel ?></td>
									<td><span class="badge light badge-<?= $status[$item->status_id]->color ?>"><?= $status[$item->status_id]->text ?></span></td>
									<td class="text-right">
										<a href="<?= base_url() ?>doctor/detail/<?= $item->id ?>">
											<button type="button" class="btn btn-primary light sharp border-0">
												<i class="far fa-search"></i>
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
							<a href="<?= base_url() ?>doctor?<?= http_build_query($f_url) ?>" class="btn btn-<?= $p[2] ?>">
								<?= $p[1] ?>
							</a>
							<?php } ?>
						</div>
					</div>
					<?php }else{ ?>
					<h5 class="text-danger mt-3"><?= $this->lang->line('msg_no_doctors') ?></h5>
					<?php } ?>
				</div>
			
			
			
				<div class="col-md-2">
					<div class="mb-3" id="sale_list_length_new"></div>
				</div>
				<div class="col-md-6"></div>
				<div class="col-md-4">
					<div class="mb-3" id="sale_list_filter_new"></div>
				</div>
				<div class="col-md-12">
					<?php if ($sales){ ?>
					<div class="table-responsive">
						<table id="sale_list" class="table display text-left">
							<thead>
								<tr>
									<th class="pt-0 pl-0"><?= $this->lang->line('hd_date') ?></th>
									<th class="pt-0"><?= $this->lang->line('hd_client') ?></th>
									<th class="pt-0"><?= $this->lang->line('hd_total') ?></th>
									<th class="pt-0"><?= $this->lang->line('hd_balance') ?></th>
									<th class="pt-0"><?= $this->lang->line('hd_status') ?></th>
									<th class="text-right pt-0 pr-0"></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($sales as $s){ $cur = $currencies[$s->currency_id]->description; ?>
								<tr>
									<td class="text-left pl-0"><?= $s->registed_at ?></td>
									<td>
										<?php if ($s->client_id) echo $clients[$s->client_id]; 
										else echo $this->lang->line('txt_no_name') ?>
									</td>
									<td><?= $cur." ".number_format($s->total, 2) ?></td>
									<td>
										<?php if ($s->balance) echo $cur." ".number_format($s->balance, 2); else echo "-"; ?>
									</td>
									<td class="text-<?= $status[$s->status_id]->color ?>">
										<?= $this->lang->line($status[$s->status_id]->code) ?>
									</td>
									<td class="text-right pr-0">
										<a href="<?= base_url() ?>sale/detail/<?= $s->id ?>">
											<button type="button" class="btn btn-primary light sharp border-0">
												<i class="fas fa-search"></i>
											</button>
										</a>
									</td>
								</tr>
								<?php } ?>
							</tbody>
							<tfoot>
								<tr>
									<th><?= $this->lang->line('hd_date') ?></th>
									<th><?= $this->lang->line('hd_client') ?></th>
									<th><?= $this->lang->line('hd_total') ?></th>
									<th><?= $this->lang->line('hd_balance') ?></th>
									<th></th>
									<th></th>
								</tr>
							</tfoot>
						</table>
					</div>
					<?php }else{ ?>
					<div class="text-center">
						<span class="text-danger"><?= $this->lang->line('txt_no_sale') ?></span>
					</div>
					<?php } ?>
				</div>
			</div>
			<div class="row bl_content d-none" id="bl_add">
				<div class="col-md-12">
					<form id="form_sale" action="#">
						<div class="row">
							<input type="hidden" id="sale_total" name="sale[total]">
							<input type="hidden" id="sale_discount" name="sale[discount]">
							<input type="hidden" id="sale_amount" name="sale[amount]">
							<input type="hidden" id="sale_vat" name="sale[vat]">
							<input type="hidden" id="payment_received" name="payment[received]">
							<input type="hidden" id="payment_change" name="payment[change]">
							<input type="hidden" id="payment_balance" name="payment[balance]">
							<input type="hidden" id="op_currency" name="currency" value="">
							<div class="col-md-12">
								<h4 class="mb-3">1. <?= $this->lang->line('tab_item_selection') ?></h4>
								<div class="mb-3">
									<input type="text" class="form-control border-primary" id="pr_search" placeholder="<?= $this->lang->line('search_product') ?>...">
								</div>
								<div class="table-responsive">
									<table class="table table-small table-responsive-md mb-0" id="tb_products">
										<thead>
											<tr>
												<th><strong>#</strong></th>
												<th><strong><?= $this->lang->line('hd_item') ?></strong></th>
												<th><strong><?= $this->lang->line('hd_option') ?></strong></th>
												<th style="width:100px;"><strong><?= $this->lang->line('hd_quantity') ?></strong></th>
												<th style="width:100px;"><strong><?= $this->lang->line('hd_discount') ?></strong></th>
												<th><strong><?= $this->lang->line('hd_subtotal') ?></strong></th>
												<th></th>
											</tr>
										</thead>
										<tbody id="sale_product_list"></tbody>
										<tfoot>
											<tr>
												<th class="text-right text-primary pt-4" colspan="5">
													<?= $this->lang->line('rs_total') ?>
												</th>
												<th class="text-right text-primary pt-4" colspan="2" id="pay_total">
													S/ 0.00
												</th>
											</tr>
											<tr>
												<td class="text-right border-top-0" colspan="5">
													<?= $this->lang->line('rs_sale') ?>
												</td>
												<td class="text-right border-top-0" colspan="2" id="pay_amount">
													S/ 0.00
												</td>
											</tr>
											<tr>
												<td class="text-right border-top-0" colspan="5">
													<?= $this->lang->line('rs_vat') ?>
												</td>
												<td class="text-right border-top-0" colspan="2" id="pay_vat">
													S/ 0.00
												</td>
											</tr>
										</tfoot>
									</table>
								</div>
							</div>
							<div class="col-md-12 d-none" id="bl_payment_data">
								<hr>
								<h4 class="mb-3">2. <?= $this->lang->line('tab_payment_data') ?></h4>
								<div class="row">
									<div class="col-md-6 col-sm-12">
										<h5><?= $this->lang->line('title_client') ?></h5>
										<div class="form-row">
											<div class="form-group col-md-12">
												<label><?= $this->lang->line('lb_document') ?></label>
												<div class="input-group">
													<select class="form-control" id="client_doc_type" name="client[doc_type_id]" style="border-right:0;">
														<?php foreach($doc_types as $item){ ?>
														<option value="<?= $item->id ?>"><?= $item->description ?></option>
														<?php } ?>
													</select>
													<input type="text" class="form-control" id="client_doc_number" name="client[doc_number]" style="border-left:0;" placeholder="<?= $this->lang->line('txt_number') ?>">
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
											<div class="form-group col-md-6" id="sl_appointment">
												<label><?= $this->lang->line('label_appointment') ?></label>
												<select class="form-control" name="sale[appointment_id]" id="app_select">
													<option value="">--</option>
												</select>
												<div class="sys_msg" id="appointment_msg"></div>
											</div>
											<div class="form-group col-md-6" id="sl_surgery">
												<label><?= $this->lang->line('label_surgery') ?></label>
												<select class="form-control" name="sale[surgery_id]" id="sur_select">
													<option value="">--</option>
												</select>
												<div class="sys_msg" id="surgery_msg"></div>
											</div>
										</div>
									</div>
									<div class="col-md-6 col-sm-12">
										<h5><?= $this->lang->line('title_operation') ?></h5>
										<div class="form-row">
											<div class="form-group col-md-12">
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
												<label><?= $this->lang->line('label_amount_to_pay') ?></label>
												<div class="input-group input-info-o">
													<div class="input-group-prepend">
														<span class="input-group-text bg-light payment_currency"></span>
													</div>
													<input type="text" class="form-control bg-light text-right" id="payment_total_v" readonly>
												</div>
												<div class="sys_msg" id="total_msg"></div>
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
										<button type="button" class="btn btn-primary mt-3" id="btn_register_sale">
											<?= $this->lang->line('btn_register_sale') ?>
										</button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="d-none">
	<input type="hidden" id="warning_asa" value="<?= $this->lang->line('warning_asa') ?>">
	<input type="hidden" id="error_prl" value="<?= $this->lang->line('error_prl') ?>">
	<input type="hidden" id="error_prlc" value="<?= $this->lang->line('error_prlc') ?>">
	<input type="hidden" id="error_prsl" value="<?= $this->lang->line('error_prsl') ?>">
</div>