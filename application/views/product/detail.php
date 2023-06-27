<div class="col-md-12">
	<div class="row page-titles mx-0">
		<div class="col-sm-6 p-md-0">
			<div class="welcome-text">
				<h4><?= $product->description ?></h4>
			</div>
		</div>
		<div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?= base_url() ?>product"><?= $this->lang->line('products') ?></a></li>
				<li class="breadcrumb-item active"><a href="javascript:void(0)"><?= $this->lang->line('txt_detail') ?></a></li>
			</ol>
		</div>
	</div>
</div>
<div class="col-md-12">
	<div class="card">
		<div class="card-body" style="min-height: 500px;">
			<div class="default-tab">
				<ul class="nav nav-tabs mb-4" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" data-toggle="tab" href="#information">
							<i class="far fa-comment-alt mr-2"></i> <?= $this->lang->line('p_information') ?>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#option">
							<i class="far fa-bars mr-2"></i> <?= $this->lang->line('p_options') ?>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#image">
							<i class="far fa-image mr-2"></i> <?= $this->lang->line('p_images') ?>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#provider">
							<i class="far fa-building mr-2"></i> <?= $this->lang->line('p_provider') ?>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#edit">
							<i class="far fa-edit mr-2"></i> <?= $this->lang->line('op_edit') ?>
						</a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane fade show active" id="information" role="tabpanel">
						<div class="row">
							<div class="col-md-4">
								<?php if ($product->image) $img_path = "uploaded/products/".$product->id."/".$product->image;
								else $img_path = "uploaded/products/no_img.png"; ?>
								<img src="<?= base_url().$img_path ?>" class="mb-3" style="width: 100%;"/>
							</div>
							<div class="col-md-8">
								<div class="row">
									<div class="col-md-12 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('p_category') ?></h5>
										<div><?= $product->category ?></div>
									</div>
									<div class="col-md-4 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('p_code') ?></h5>
										<div><?= $product->code ?></div>
									</div>
									<div class="col-md-4 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('p_type') ?></h5>
										<div><?= $product->type ?></div>
									</div>
									<div class="col-md-4 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('p_stock') ?></h5>
										<div><?= $product->stock ?></div>
									</div>
									<div class="col-md-4 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('p_price') ?></h5>
										<div><?= $product->currency." ".number_format($product->price, 2) ?></div>
									</div>
									<div class="col-md-4 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('p_value') ?></h5>
										<div><?= $product->currency." ".number_format($product->value, 2) ?></div>
									</div>
									<div class="col-md-4 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('p_vat') ?></h5>
										<div><?= $product->currency." ".number_format($product->vat, 2) ?></div>
									</div>
									<div class="col-md-6 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('p_last_updated') ?></h5>
										<div><?= $product->updated_at ?></div>
									</div>
									<div class="col-md-6 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('p_register_date') ?></h5>
										<div><?= $product->registed_at ?></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="option" role="tabpanel">
						<div class="row">
							<div class="col-md-6">
								<form class="form-row" id="form_add_option" action="#">
									<input type="hidden" name="product_id" value="<?= $product->id ?>">
									<div class="form-group col-md-8">
										<label><?= $this->lang->line('p_description') ?></label>
										<input type="text" class="form-control" name="description">
										<div class="sys_msg" id="aop_description_msg"></div>
									</div>
									<div class="form-group col-md-4">
										<label><?= $this->lang->line('p_stock') ?></label>
										<input type="text" class="form-control" name="stock">
										<div class="sys_msg" id="aop_stock_msg"></div>
									</div>
									<div class="form-group col-md-12 mt-3 mb-0">
										<button type="submit" class="btn btn-primary">
											<?= $this->lang->line('btn_add') ?>
										</button>
									</div>
								</form>
							</div>
							<div class="col-md-6">
								<table class="table mb-0">
									<thead>
										<tr>
											<th class="pt-0"><strong><?= $this->lang->line('p_description') ?></strong></th>
											<th class="pt-0"><strong><?= $this->lang->line('p_stock') ?></strong></th>
											<th class="pt-0"></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach($product_options as $item){ ?>
										<tr class="row_op_info" id="row_op_info_<?= $item->id ?>">
											<td><?= $item->description ?></td>
											<td><?= number_format($item->stock) ?></td>
											<td class="text-right text-nowrap">
												<button class="btn btn-info light sharp op_edit" value="<?= $item->id ?>">
													<i class="fa fa-pencil"></i>
												</button>
												<button class="btn btn-danger light sharp op_delete" value="<?= $item->id ?>">
													<i class="fa fa-trash"></i>
												</button>
											</td>
										</tr>
										<tr class="d-none row_op_edit" id="row_op_edit_<?= $item->id ?>">
											<form class="form_edit_option" action="#">
												<input type="hidden" name="id" value="<?= $item->id ?>">
												<input type="hidden" name="product_id" value="<?= $item->product_id ?>">
												<td><input type="text" class="form-control" name="description" value="<?= $item->description ?>"></td>
												<td><input type="text" class="form-control" name="stock" value="<?= $item->stock ?>" style="width: 80px;"></td>
												<td class="text-right text-nowrap">
													<button class="btn btn-info light sharp" type="submit">
														<i class="fa fa-check"></i>
													</button>
													<button class="btn btn-danger light sharp op_cancel_edit" type="button">
														<i class="fa fa-times"></i>
													</button>
												</td>
											</form>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="image" role="tabpanel">
						<form action="#" id="form_add_image" class="form-row mb-3">
							<input type="hidden" name="product_id" value="<?= $product->id ?>">
							<div class="form-group col-md-6">
								<div class="input-group">
									<input type="file" class="form-control" name="image">
									<div class="input-group-append">
										<button class="btn btn-primary border-0" type="submit">
											<i class="fas fa-plus"></i>
										</button>
									</div>
								</div>
							</div>
						</form>
						<div id="bl_images" class="row">
							<?php foreach($images as $item){
							$img_path = base_url()."uploaded/products/".$item->product_id."/".$item->filename;
							if (!strcmp($item->filename, $product->image)) $bd_color = "border-info"; else $bd_color = ""; ?>
							<div class="col-md-3" id="img_<?= $item->id ?>">
								<div class="text-center border <?= $bd_color ?> rounded overflow-hidden mb-3 w-100">
									<div class="overflow-hidden" style="width: 100%; height: 100px;">
										<img src="<?= $img_path ?>" style="max-weight: 100px; max-height: 100px;" />
									</div>
									<div class="border-top">
										<?php if (!$bd_color){ ?><button type="button" class="btn btn-xs text-info p-1 btn_set_img" id="btn_set_img_<?= $item->id ?>" value="<?= $item->id ?>"><i class="far fa-image"></i></button><?php } ?><button type="button" class="btn btn-xs text-danger p-1 btn_delete_img" id="btn_delete_img_<?= $item->id ?>" value="<?= $item->id ?>"><i class="far fa-trash"></i></button>
									</div>
								</div>
							</div>
							<?php } ?>
						</div>
					</div>
					<div class="tab-pane fade" id="provider" role="tabpanel">
						<form action="#" class="form-row" id="form_save_provider">
							<input type="hidden" name="product_id" value="<?= $product->id ?>">
							<div class="form-group col-md-4">
								<label><?= $this->lang->line('p_ruc') ?></label>
								<div class="input-group">
									<input type="text" class="form-control" id="prov_ruc" name="tax_id" value="<?= $provider->tax_id ?>">
									<div class="input-group-append">
										<button class="btn btn-primary border-0" id="btn_search_provider" type="button">
											<i class="fas fa-search"></i>
										</button>
									</div>
								</div>
								<div class="sys_msg" id="epv_ruc_msg"></div>
							</div>
							<div class="form-group col-md-4">
								<label><?= $this->lang->line('p_company') ?></label>
								<input type="text" class="form-control" id="prov_name" name="name" value="<?= $provider->name ?>">
								<div class="sys_msg" id="epv_company_msg"></div>
							</div>
							<div class="form-group col-md-4">
								<label><?= $this->lang->line('p_web') ?></label>
								<input type="text" class="form-control" id="prov_web" name="web" value="<?= $provider->web ?>">
							</div>
							<div class="form-group col-md-4">
								<label><?= $this->lang->line('p_person') ?></label>
								<input type="text" class="form-control" id="prov_person" name="person" value="<?= $provider->person ?>">
							</div>
							<div class="form-group col-md-4">
								<label><?= $this->lang->line('p_tel') ?></label>
								<input type="text" class="form-control" id="prov_tel" name="tel" value="<?= $provider->tel ?>">
							</div>
							<div class="form-group col-md-4">
								<label><?= $this->lang->line('p_email') ?></label>
								<input type="text" class="form-control" id="prov_email" name="email" value="<?= $provider->email ?>">
							</div>
							<div class="form-group col-md-12">
								<label><?= $this->lang->line('p_address') ?></label>
								<input type="text" class="form-control" id="prov_address" name="address" value="<?= $provider->address ?>">
							</div>
							<div class="form-group col-md-12">
								<label><?= $this->lang->line('p_remark') ?></label>
								<textarea class="form-control" id="prov_remark" rows="3" name="remark"><?= $provider->remark ?></textarea>
							</div>
							<div class="form-group col-md-12 pt-3 mb-0">
								<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_save') ?></button>
								<?php if ($product->provider_id){ ?>
								<button type="button" class="btn btn-danger light" id="btn_clean_provider">
									<?= $this->lang->line('btn_clean') ?>
								</button>
								<?php } ?>
							</div>
						</form>
					</div>
					<div class="tab-pane fade" id="edit" role="tabpanel">
						<form action="#" id="form_edit_product">
							<input type="hidden" name="id" value="<?= $product->id ?>">
							<div class="form-row">
								<div class="form-group col-md-4">
									<label><?= $this->lang->line('p_type') ?></label>
									<select class="form-control" name="type_id" id="ep_type">
										<?php foreach($product_types as $item){
											if (!strcmp($product->type_id, $item->id)) $selected = "selected"; else $selected = ""; ?>
										<option value="<?= $item->id ?>" <?= $selected ?>><?= $item->description ?></option>
										<?php } ?>
									</select>
								</div>
								<div class="form-group col-md-4">
									<label><?= $this->lang->line('p_code') ?></label>
									<input type="text" class="form-control" name="code" value="<?= $product->code ?>">
									<div class="sys_msg" id="ep_code_msg"></div>
								</div>
								<div class="form-group col-md-4">
									<label><?= $this->lang->line('p_price') ?></label>
									<div class="input-group">
										<select class="form-control w-10" name="currency_id" style="max-width: 70px;">
											<?php foreach($currencies as $item){
											if ($product->currency_id == $item->id) $selected = "selected"; else $selected = ""; ?>
											<option value="<?= $item->id ?>" <?= $selected ?>><?= $item->description ?></option>
											<?php } ?>
										</select>
										<input type="text" class="form-control" name="price" value="<?= $product->price ?>">
									</div>
									<div class="sys_msg" id="ep_price_msg"></div>
								</div>
								<div class="form-group col-md-6">
									<label><?= $this->lang->line('p_category') ?></label>
									<select class="form-control" name="category_id">
										<?php foreach($categories as $item){
											if ($product->category_id == $item->id) $selected = "selected"; else $selected = ""; ?>
										<option value="<?= $item->id ?>" <?= $selected ?>><?= $item->name ?></option>
										<?php } ?>
									</select>
									<div class="sys_msg" id="ep_category_msg"></div>
								</div>
								<div class="form-group col-md-6">
									<label><?= $this->lang->line('p_name') ?></label>
									<input type="text" class="form-control" name="description" value="<?= $product->description ?>">
									<div class="sys_msg" id="ep_description_msg"></div>
								</div>
								<div class="form-group col-md-12 pt-3 mb-0">
									<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_update') ?></button>
									<button type="button" class="btn btn-danger light btn_delete" value="<?= $product->id ?>">
										<?= $this->lang->line('btn_delete') ?>
									</button>	
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<input type="hidden" id="product_id" value="<?= $product->id ?>">
<input type="hidden" id="warning_di" value="<?= $this->lang->line('warning_di') ?>">
<input type="hidden" id="warning_pri" value="<?= $this->lang->line('warning_pri') ?>">
<input type="hidden" id="warning_spv" value="<?= $this->lang->line('warning_spv') ?>">
<input type="hidden" id="warning_dp" value="<?= $this->lang->line('warning_dp') ?>">
<input type="hidden" id="warning_aop" value="<?= $this->lang->line('warning_aop') ?>">
<input type="hidden" id="warning_dop" value="<?= $this->lang->line('warning_dop') ?>">
<input type="hidden" id="warning_eop" value="<?= $this->lang->line('warning_eop') ?>">