<div class="col-md-12">
	<div class="welcome-text d-md-none d-block">
		<h4 class="text-primary mb-3"><?= $this->lang->line('products') ?></h4>
	</div>
</div>
<div class="col-sm-5">
	<div class="btn-group mb-3">
		<button type="button" class="btn btn-primary control_bl" id="btn_list" value="bl_list">
			<i class="fas fa-list mr-2"></i><?= $this->lang->line('btn_list') ?>
		</button>
		<button type="button" class="btn btn-outline-primary control_bl" value="bl_category">
			<i class="fas fa-sitemap mr-2"></i><?= $this->lang->line('btn_categories') ?>
		</button>
		<button type="button" class="btn btn-outline-primary control_bl" value="bl_add">
			<i class="fas fa-plus mr-2"></i><?= $this->lang->line('btn_add') ?>
		</button>
	</div>
</div>
<div class="col-sm-7">
	<form>
		<div class="form-row">
			<input type="hidden" value="1" name="page">
			<div class="form-group col-sm-3">
				<select class="form-control" id="sl_type" name="type">
					<option value=""><?= $this->lang->line('sl_type') ?></option>
					<?php foreach($prod_types as $item){
						if ($item->id == $f_url["type"]) $s = "selected"; else $s = ""; ?>
					<option value="<?= $item->id ?>" <?= $s ?>><?= $item->description ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="form-group col-sm-3">
				<select class="form-control" id="sl_category" name="category">
					<option value=""><?= $this->lang->line('sl_category') ?></option>
					<?php foreach($categories as $item){
						if ($item->id == $f_url["category"]) $s = "selected"; else $s = ""; ?>
					<option value="<?= $item->id ?>" <?= $s ?>><?= $item->name ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="form-group col-sm-4">
				<input type="text" class="form-control" id="inp_keyword" name="keyword" placeholder="<?= $this->lang->line('inp_search') ?>" value="<?= $f_url["keyword"] ?>">
			</div>
			<div class="form-group col-sm-2">
				<button type="submit" class="btn btn-primary btn-block">
					<i class="far fa-search"></i>
				</button>
			</div>
		</div>
	</form>
</div>
<div class="col-md-12">
	<div class="card">
		<div class="card-body">
			<div class="row bl_content" id="bl_list">
				<div class="col-md-12">
					<?php if ($products){ ?>
					<div class="table-responsive">
						<table class="table table-responsive-md">
							<thead>
								<tr>
									<th><strong>#</strong></th>
									<th><strong><?= $this->lang->line('th_image') ?></strong></th>
									<th><strong><?= $this->lang->line('th_product') ?></strong></th>
									<th><strong><?= $this->lang->line('th_price') ?></strong></th>
									<th><strong><?= $this->lang->line('th_stock') ?></strong></th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php $color_arr = array("success", "info", "warning", "danger");
								$no_img_path = "uploaded/products/no_img.png";
								foreach($products as $i => $item){ ?>
								<tr>
									<td><strong><?= number_format(($f_url["page"] - 1) * 25 + 1 + $i) ?></strong></td>
									<td>
										<?php if ($item->image){
											$prod_img_path = "uploaded/products/".$item->id."/".$item->image;
											if (file_exists($prod_img_path)) $img_path = $prod_img_path;
											else $img_path = $no_img_path;
										}else $img_path = $no_img_path; ?>
										<div class="div_thumb">
											<img src="<?= base_url().$img_path ?>" class="img_thumb" />
										</div>
									</td>
									<td>
										<div><span class="badge light badge-<?= $color_arr[$item->type_id-1] ?> mr-1"><?= $prod_types_arr[$item->type_id]->description ?></span><span class="badge light badge-secondary"><?= $item->code ?></span></div>
										<div><?= $item->description ?></div>
										<div><small><?= $categories_arr[$item->category_id] ?></small></div>
									</td>
									<td>
										<?= $currencies_arr[$item->currency_id]->description." ".number_format($item->price, 2) ?>
									</td>
									<td>
										<?php if ($item->stock) echo number_format($item->stock); else echo "-"; ?>
									</td>
									<td class="text-right">
										<a href="<?= base_url() ?>product/detail/<?= $item->id ?>">
											<button type="button" class="btn btn-info light sharp">
												<i class="fas fa-arrow-alt-right"></i>
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
							<a href="<?= base_url() ?>product?<?= http_build_query($f_url) ?>" class="btn btn-<?= $p[2] ?>">
								<?= $p[1] ?>
							</a>
							<?php } ?>
						</div>
					</div>
					<?php }else{ ?>
					<h5 class="text-danger mt-3"><?= $this->lang->line('msg_no_products') ?></h5>
					<?php } ?>
				</div>
			</div>
			<div class="row bl_content d-none" id="bl_category">
				<div class="col-md-12">
					<div class="default-tab">
						<ul class="nav nav-tabs mb-4" role="tablist">
							<li class="nav-item">
								<a class="nav-link active" data-toggle="tab" href="#list_ct">
									<?= $this->lang->line('c_nav_category_list') ?>
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" data-toggle="tab" href="#move_product_ct">
									<?= $this->lang->line('c_nav_move') ?>
								</a>
							</li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane fade show active" id="list_ct">
								<table class="table table-small mb-0 w-100">
									<thead>
										<tr>
											<th style="width:50px;"><strong>#</strong></th>
											<th><strong><?= $this->lang->line('th_category') ?></strong></th>
											<th><strong><?= $this->lang->line('th_products') ?></strong></th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<tr class="">
											<form action="#" id="form_add_category">
												<td colspan="3">
													<input type="text" class="form-control" name="name" placeholder="<?= $this->lang->line('txt_category_name') ?>">
												</td>
												<td class="text-right">
													<button class="btn btn-primary sharp" type="submit">
														<i class="far fa-plus"></i>
													</button>
												</td>
											</form>
										</tr>
										<?php $i = 1; foreach($categories as $c){ ?>
										<tr>
											<td><?= number_format($i) ?></td>
											<td>
												<div id="ct_name_<?= $c->id ?>" class="ct_name"><?= $c->name ?></div>
												<form action="#" id="form_update_category_<?= $c->id ?>" class="form_update_category d-none">
													<input type="hidden" name="id" value="<?= $c->id ?>">
													<div class="input-group">
														<input type="text" class="form-control" name="name" value="<?= $c->name ?>">
														<div class="input-group-append">
															<button class="btn btn-success border-0" type="submit">
																<i class="far fa-check"></i>
															</button>
															<button class="btn btn-danger light border-0 btn_cancel_edit_ct" type="button">
																<i class="far fa-times"></i>
															</button>
														</div>
													</div>
												</form>
											</td>
											<td><?= number_format($c->prod_qty) ?></td>
											<td class="text-right">
												<div class="dropdown">
													<button type="button" class="btn btn-info light sharp border-0" data-toggle="dropdown">
														<i class="far fa-ellipsis-h"></i>
													</button>
													<div class="dropdown-menu">
														<button class="dropdown-item text-info btn_edit_ct" value="<?= $c->id ?>">
															<i class="far fa-edit fa-fw"></i> <?= $this->lang->line('op_edit') ?>
														</button>
														<button class="dropdown-item text-danger btn_delete_ct" value="<?= $c->id ?>">
															<i class="far fa-trash fa-fw"></i> <?= $this->lang->line('op_delete') ?>
														</button>
													</div>
												</div>
											</td>
										</tr>
										<?php $i++;} ?>
									</tbody>
								</table>
							</div>
							<div class="tab-pane fade" id="move_product_ct">
								<form action="#" id="form_move_product">
									<div class="form-row">
										<div class="form-group col-md-5">
											<select class="form-control sl_category" name="mp_id_from">
												<option value="">-</option>
												<?php foreach($categories as $item){ ?>
												<option value="<?= $item->id ?>"><?= $item->name ?></option>
												<?php } ?>
											</select>
											<div class="sys_msg" id="mp_id_from_msg"></div>
										</div>
										<div class="form-group col-md-2">
											<div class="btn w-100 mx-auto text-primary"><i class="fas fa-arrow-right"></i></div>
										</div>
										<div class="form-group col-md-5">
											<select class="form-control sl_category" name="mp_id_to">
												<option value="">-</option>
												<?php foreach($categories as $item){ ?>
												<option value="<?= $item->id ?>"><?= $item->name ?></option>
												<?php } ?>
											</select>
											<div class="sys_msg" id="mp_id_to_msg"></div>
										</div>
									</div>
									<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_confirm') ?></button>
									<div class="sys_msg" id="mp_result_msg"></div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row bl_content d-none" id="bl_add">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-4">
							<div class="text-center" style="max-width: 100%;">
								<img src="uploaded/products/no_img.png" id="img_preview" style="max-width: 100%;">
							</div>
						</div>
						<div class="col-md-8">
							<form action="#" class="form-row" id="form_register_product">
								<div class="form-group col-md-12">
									<label><?= $this->lang->line('p_category') ?></label>
									<select class="form-control sl_category" name="category_id">
										<option value="">-</option>
										<?php foreach($categories as $item){ ?>
										<option value="<?= $item->id ?>"><?= $item->name ?></option>
										<?php } ?>
									</select>
									<div class="sys_msg" id="ap_category_msg"></div>
								</div>
								<div class="form-group col-md-6">
									<label><?= $this->lang->line('p_type') ?></label>
									<select class="form-control" name="type_id">
										<?php foreach($prod_types as $item){ ?>
										<option value="<?= $item->id ?>"><?= $item->description ?></option>
										<?php } ?>
									</select>
									<div class="sys_msg" id="ap_type_msg"></div>
								</div>
								<div class="form-group col-md-6">
									<label><?= $this->lang->line('p_code') ?></label>
									<input type="text" class="form-control" name="code">
									<div class="sys_msg" id="ap_code_msg"></div>
								</div>
								<div class="form-group col-md-6">
									<label><?= $this->lang->line('p_image') ?> <small>(<?= $this->lang->line('p_optional') ?>)</small></label>
									<input type="file" class="form-control" id="ap_image" name="image" accept="image/*">
									<div class="sys_msg" id="ap_image_msg"></div>
								</div>
								<div class="form-group col-md-6">
									<label><?= $this->lang->line('p_price') ?></label>
									<div class="input-group">
										<select class="form-control w-10" name="currency_id" style="max-width: 70px;">
											<?php foreach($currencies as $item){ ?>
											<option value="<?= $item->id ?>"><?= $item->description ?></option>
											<?php } ?>
										</select>
										<input type="text" class="form-control" name="price">
									</div>
									<div class="sys_msg" id="ap_price_msg"></div>
								</div>
								<div class="form-group col-md-12">
									<label><?= $this->lang->line('p_name') ?></label>
									<input type="text" class="form-control" name="description">
									<div class="sys_msg" id="ap_description_msg"></div>
								</div>
								<div class="form-group col-md-12 pt-3 mb-0">
									<button type="submit" class="btn btn-primary">
										<?= $this->lang->line('btn_register') ?>
									</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<input type="hidden" id="warning_ac" value="<?= $this->lang->line('warning_ac') ?>">
<input type="hidden" id="warning_uc" value="<?= $this->lang->line('warning_uc') ?>">
<input type="hidden" id="warning_dc" value="<?= $this->lang->line('warning_dc') ?>">
<input type="hidden" id="warning_mc" value="<?= $this->lang->line('warning_mc') ?>">
<input type="hidden" id="warning_rpr" value="<?= $this->lang->line('warning_rpr') ?>">