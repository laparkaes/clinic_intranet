<div class="pagetitle">
	<h1><?= $product->description ?></h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= $this->lang->line('w_home') ?></a></li>
			<li class="breadcrumb-item"><a href="<?= base_url() ?>product"><?= $this->lang->line('products') ?></a></li>
			<li class="breadcrumb-item active"><?= $this->lang->line('txt_detail') ?></li>
		</ol>
	</nav>
</div>

<div class="col-md-12">
	<div class="card">
		<div class="card-body" style="min-height: 500px;">
			<h5 class="card-title"><?= $this->lang->line('w_product') ?></h5>
			<ul class="nav nav-tabs nav-tabs-bordered" role="tablist">
				<li class="nav-item" role="presentation">
					<button class="nav-link active" id="information-tab" data-bs-toggle="tab" data-bs-target="#bordered-information" type="button" role="tab" aria-controls="information" aria-selected="true"><?= $this->lang->line('w_information') ?></button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="nav-link" id="option-tab" data-bs-toggle="tab" data-bs-target="#bordered-option" type="button" role="tab" aria-controls="option" aria-selected="false" tabindex="-1"><?= $this->lang->line('w_options') ?></button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="nav-link" id="image-tab" data-bs-toggle="tab" data-bs-target="#bordered-image" type="button" role="tab" aria-controls="image" aria-selected="false" tabindex="-1"><?= $this->lang->line('w_images') ?></button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="nav-link" id="provider-tab" data-bs-toggle="tab" data-bs-target="#bordered-provider" type="button" role="tab" aria-controls="provider" aria-selected="false" tabindex="-1"><?= $this->lang->line('w_provider') ?></button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="nav-link" id="edit-tab" data-bs-toggle="tab" data-bs-target="#bordered-edit" type="button" role="tab" aria-controls="edit" aria-selected="false" tabindex="-1"><?= $this->lang->line('w_edit') ?></button>
				</li>
			</ul>
			<div class="tab-content pt-3">
				<div class="tab-pane fade show active" id="bordered-information" role="tabpanel" aria-labelledby="information-tab">
					<div class="row">
						<div class="col-md-4 px-3">
							<?php if ($product->image) $img_path = "uploaded/products/".$product->id."/".$product->image;
							else $img_path = "uploaded/products/no_img.png"; ?>
							<img src="<?= base_url().$img_path ?>" style="width: 100%;"/>
						</div>
						<div class="col-md-8">
							<div class="row g-3">
								<div class="col-md-12">
									<label class="form-label"><?= $this->lang->line('w_category') ?></label>
									<div class="form-control"><?= $product->category ?></div>
								</div>
								<div class="col-md-4">
									<label class="form-label"><?= $this->lang->line('w_code') ?></label>
									<div class="form-control"><?= $product->code ?></div>
								</div>
								<div class="col-md-4">
									<label class="form-label"><?= $this->lang->line('w_type') ?></label>
									<div class="form-control"><?= $product->type ?></div>
								</div>
								<div class="col-md-4">
									<label class="form-label"><?= $this->lang->line('w_stock') ?></label>
									<div class="form-control"><?= ($product->stock) ? number_format($product->stock) : "-" ?></div>
								</div>
								<div class="col-md-4">
									<label class="form-label"><?= $this->lang->line('w_price') ?></label>
									<div class="form-control"><?= $product->currency." ".number_format($product->price, 2) ?></div>
								</div>
								<div class="col-md-4">
									<label class="form-label"><?= $this->lang->line('w_value') ?></label>
									<div class="form-control"><?= $product->currency." ".number_format($product->value, 2) ?></div>
								</div>
								<div class="col-md-4">
									<label class="form-label"><?= $this->lang->line('w_vat') ?></label>
									<div class="form-control"><?= $product->currency." ".number_format($product->vat, 2) ?></div>
								</div>
								<div class="col-md-6">
									<label class="form-label"><?= $this->lang->line('w_last_updated') ?></label>
									<div class="form-control"><?= $product->updated_at ?></div>
								</div>
								<div class="col-md-6">
									<label class="form-label"><?= $this->lang->line('w_register_date') ?></label>
									<div class="form-control"><?= $product->registed_at ?></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="bordered-option" role="tabpanel" aria-labelledby="option-tab">
					<form class="row g-3 justify-content-end" id="form_add_option">
						<input type="hidden" name="product_id" value="<?= $product->id ?>">
						<div class="col-md-auto">
							<label class="fomr-label"><?= $this->lang->line('w_description') ?></label>
							<input type="text" class="form-control" name="description">
							<div class="sys_msg" id="aop_description_msg"></div>
						</div>
						<div class="col-md-auto">
							<label class="fomr-label"><?= $this->lang->line('w_stock') ?></label>
							<input type="text" class="form-control" name="stock">
							<div class="sys_msg" id="aop_stock_msg"></div>
						</div>
						<div class="col-md-auto mt-3 d-flex align-items-end">
							<button type="submit" class="btn btn-primary">
								<i class="bi bi-plus"></i>
							</button>
						</div>
					</form>
					<div class="table-responsive mt-3">
						<table class="table">
							<thead>
								<tr>
									<th>#</th>
									<th><?= $this->lang->line('w_description') ?></th>
									<th><?= $this->lang->line('w_stock') ?></th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($product_options as $i => $item){ ?>
								<tr class="row_op_info" id="row_op_info_<?= $item->id ?>">
									<th><?= number_format($i + 1) ?></th>
									<td><?= $item->description ?></td>
									<td><?= number_format($item->stock) ?></td>
									<td class="text-end">
										<button class="btn btn-success btn-sm op_edit" value="<?= $item->id ?>">
											<i class="bi bi-pencil"></i>
										</button>
										<button class="btn btn-danger btn-sm op_delete" value="<?= $item->id ?>">
											<i class="bi bi-trash"></i>
										</button>
									</td>
								</tr>
								<tr class="d-none row_op_edit" id="row_op_edit_<?= $item->id ?>">
									<form class="form_edit_option" action="#">
										<input type="hidden" name="id" value="<?= $item->id ?>">
										<input type="hidden" name="product_id" value="<?= $item->product_id ?>">
										<td></td>
										<td><input type="text" class="form-control" name="description" value="<?= $item->description ?>"></td>
										<td><input type="text" class="form-control" name="stock" value="<?= $item->stock ?>" style="width: 80px;"></td>
										<td class="text-end">
											<button class="btn btn-success" type="submit">
												<i class="bi bi-check"></i>
											</button>
											<button class="btn btn-danger op_cancel_edit" type="button">
												<i class="bi bi-x"></i>
											</button>
										</td>
									</form>
								</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="tab-pane fade" id="bordered-image" role="tabpanel" aria-labelledby="image-tab">
					<form id="form_add_image" class="row g-3 justify-content-end">
						<input type="hidden" name="product_id" value="<?= $product->id ?>">
						<div class="col-md-auto">
							<input type="file" class="form-control" name="image">
						</div>
						<div class="col-md-auto">
							<button class="btn btn-primary" type="submit">
								<i class="bi bi-plus"></i>
							</button>
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
									<?php if (!$bd_color){ ?>
									<button type="button" class="btn btn-outline-primary btn-sm border-0 btn_set_img" id="btn_set_img_<?= $item->id ?>" value="<?= $item->id ?>">
										<i class="bi bi-image"></i>
									</button>
									<?php } ?>
									<button type="button" class="btn btn-outline-danger btn-sm border-0 btn_delete_img" id="btn_delete_img_<?= $item->id ?>" value="<?= $item->id ?>">
										<i class="bi bi-trash"></i>
									</button>
								</div>
							</div>
						</div>
						<?php } ?>
					</div>
				</div>
				<div class="tab-pane fade" id="bordered-provider" role="tabpanel" aria-labelledby="provider-tab">
					<form class="row g-3" id="form_save_provider">
						<input type="hidden" name="product_id" value="<?= $product->id ?>">
						<div class="col-md-4">
							<label class="form-label"><?= $this->lang->line('w_ruc') ?></label>
							<div class="input-group">
								<input type="text" class="form-control" id="prov_ruc" name="tax_id" value="<?= $provider->tax_id ?>">
								<button class="btn btn-primary" id="btn_search_provider" type="button">
									<i class="bi bi-search"></i>
								</button>
							</div>
							<div class="sys_msg" id="epv_ruc_msg"></div>
						</div>
						<div class="col-md-4">
							<label class="form-label"><?= $this->lang->line('w_company') ?></label>
							<input type="text" class="form-control" id="prov_name" name="name" value="<?= $provider->name ?>">
							<div class="sys_msg" id="epv_company_msg"></div>
						</div>
						<div class="col-md-4">
							<label class="form-label"><?= $this->lang->line('w_web') ?></label>
							<input type="text" class="form-control" id="prov_web" name="web" value="<?= $provider->web ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label"><?= $this->lang->line('w_person') ?></label>
							<input type="text" class="form-control" id="prov_person" name="person" value="<?= $provider->person ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label"><?= $this->lang->line('w_tel') ?></label>
							<input type="text" class="form-control" id="prov_tel" name="tel" value="<?= $provider->tel ?>">
						</div>
						<div class="col-md-4">
							<label class="form-label"><?= $this->lang->line('w_email') ?></label>
							<input type="text" class="form-control" id="prov_email" name="email" value="<?= $provider->email ?>">
						</div>
						<div class="col-md-12">
							<label class="form-label"><?= $this->lang->line('w_address') ?></label>
							<input type="text" class="form-control" id="prov_address" name="address" value="<?= $provider->address ?>">
						</div>
						<div class="col-md-12">
							<label class="form-label"><?= $this->lang->line('w_remark') ?></label>
							<textarea class="form-control" id="prov_remark" rows="3" name="remark"><?= $provider->remark ?></textarea>
						</div>
						<div class="col-md-12 pt-3">
							<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_save') ?></button>
							<?php if ($product->provider_id){ ?>
							<button type="button" class="btn btn-danger" id="btn_clean_provider">
								<?= $this->lang->line('btn_clean') ?>
							</button>
							<?php } ?>
						</div>
					</form>
				</div>
				<div class="tab-pane fade" id="bordered-edit" role="tabpanel" aria-labelledby="edit-tab">
					<form class="row g-3" id="form_edit_product">
						<input type="hidden" name="id" value="<?= $product->id ?>">
						<div class="col-md-12">
							<label class="form-label"><?= $this->lang->line('w_category') ?></label>
							<select class="form-select" name="category_id">
								<?php foreach($categories as $item){
									if ($product->category_id == $item->id) $selected = "selected"; else $selected = ""; ?>
								<option value="<?= $item->id ?>" <?= $selected ?>><?= $item->name ?></option>
								<?php } ?>
							</select>
							<div class="sys_msg" id="ep_category_msg"></div>
						</div>
						<div class="col-md-12">
							<label class="form-label"><?= $this->lang->line('w_name') ?></label>
							<input type="text" class="form-control" name="description" value="<?= $product->description ?>">
							<div class="sys_msg" id="ep_description_msg"></div>
						</div>
						<div class="col-md-4">
							<label class="form-label"><?= $this->lang->line('w_type') ?></label>
							<select class="form-select" name="type_id" id="ep_type">
								<?php foreach($product_types as $item){
									if (!strcmp($product->type_id, $item->id)) $selected = "selected"; else $selected = ""; ?>
								<option value="<?= $item->id ?>" <?= $selected ?>><?= $item->description ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-md-4">
							<label class="form-label"><?= $this->lang->line('w_code') ?></label>
							<input type="text" class="form-control" name="code" value="<?= $product->code ?>">
							<div class="sys_msg" id="ep_code_msg"></div>
						</div>
						<div class="col-md-4">
							<label class="form-label"><?= $this->lang->line('w_price') ?></label>
							<div class="input-group">
								<select class="form-select" name="currency_id" style="max-width: 80px;">
									<?php foreach($currencies as $item){
									if ($product->currency_id == $item->id) $selected = "selected"; else $selected = ""; ?>
									<option value="<?= $item->id ?>" <?= $selected ?>><?= $item->description ?></option>
									<?php } ?>
								</select>
								<input type="text" class="form-control" name="price" value="<?= $product->price ?>">
							</div>
							<div class="sys_msg" id="ep_price_msg"></div>
						</div>
						<div class="col-md-12 pt-3">
							<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_update') ?></button>
							<button type="button" class="btn btn-danger btn_delete" value="<?= $product->id ?>">
								<?= $this->lang->line('btn_remove') ?>
							</button>	
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<input type="hidden" id="product_id" value="<?= $product->id ?>">