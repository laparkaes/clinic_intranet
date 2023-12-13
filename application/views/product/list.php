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
		<button type="button" class="btn btn-outline-primary control_bl" value="bl_category">
			<i class="bi bi-diagram-3"></i>
		</button>
		<button type="button" class="btn btn-outline-primary control_bl" value="bl_add">
			<i class="bi bi-plus-lg"></i>
		</button>
	</div>
</div>
<form class="row d-flex justify-content-end g-3">
	<input type="hidden" value="1" name="page">
	<div class="col-md-auto col-12">
		<select class="form-select" id="sl_type" name="type">
			<option value=""><?= $this->lang->line('w_type') ?></option>
			<?php foreach($prod_types as $item){
				if ($item->id == $f_url["type"]) $s = "selected"; else $s = ""; ?>
			<option value="<?= $item->id ?>" <?= $s ?>><?= $item->description ?></option>
			<?php } ?>
		</select>
	</div>
	<div class="col-md-auto col-12">
		<select class="form-select" id="sl_category" name="category">
			<option value=""><?= $this->lang->line('w_category') ?></option>
			<?php foreach($categories as $item){
				if ($item->id == $f_url["category"]) $s = "selected"; else $s = ""; ?>
			<option value="<?= $item->id ?>" <?= $s ?>><?= $item->name ?></option>
			<?php } ?>
		</select>
	</div>
	<div class="col-md-auto col-12">
		<input type="text" class="form-control" id="inp_keyword" name="keyword" placeholder="<?= $this->lang->line('w_search') ?>" value="<?= $f_url["keyword"] ?>">
	</div>
	<div class="col-md-auto col-12 text-center d-grid gap-2">
		<button type="submit" class="btn btn-primary btn-block">
			<i class="bi bi-search"></i>
		</button>
  </div>
</form>
<div class="row mt-3">
	<div class="col">
		<div class="card bl_content" id="bl_list">
			<div class="card-body">
				<h5 class="card-title"><?= $this->lang->line('w_list') ?></h5>
				<?php if ($products){ ?>
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<th>#</th>
								<th><?= $this->lang->line('w_image') ?></th>
								<th><?= $this->lang->line('w_type') ?></th>
								<th><?= $this->lang->line('w_product') ?></th>
								<th><?= $this->lang->line('w_price') ?></th>
								<th><?= $this->lang->line('w_stock') ?></th>
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
									<img src="<?= base_url().$img_path ?>" style="max-width: 60px; max-height: 60px;" />
								</td>
								<td><?= $prod_types_arr[$item->type_id]->description ?></td>
								<td>
									<div><?= $item->description ?></div>
									<div><?= $item->code ?>, <?= $categories_arr[$item->category_id] ?></div>
								</td>
								<td>
									<?= $currencies_arr[$item->currency_id]->description." ".number_format($item->price, 2) ?>
								</td>
								<td>
									<?php if ($item->stock) echo number_format($item->stock); else echo "-"; ?>
								</td>
								<td class="text-end">
									<a href="<?= base_url() ?>product/detail/<?= $item->id ?>" class="btn btn-primary btn-sm">
										<i class="bi bi-search"></i>
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
				<h5 class="text-danger"><?= $this->lang->line('t_no_products') ?></h5>
				<?php } ?>
			</div>
		</div>
	</div>
</div>

<div class="col">
	<div class="card">
		<div class="card-body">
			<div class="row bl_content d-none" id="bl_category">
				<div class="col-md-12">
					<div class="default-tab">
						<ul class="nav nav-tabs mb-4" role="tablist">
							<li class="nav-item">
								<a class="nav-link active" data-toggle="tab" href="#list_ct">
									<?= $this->lang->line('w_nav_cat_list') ?>
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" data-toggle="tab" href="#move_product_ct">
									<?= $this->lang->line('w_nav_move') ?>
								</a>
							</li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane fade show active" id="list_ct">
								<table class="table table-small mb-0 w-100">
									<thead>
										<tr>
											<th style="width:50px;"><strong>#</th>
											<th><?= $this->lang->line('w_category') ?></th>
											<th><?= $this->lang->line('w_products') ?></th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<tr class="">
											<form action="#" id="form_add_category">
												<td colspan="3">
													<input type="text" class="form-control" name="name" placeholder="<?= $this->lang->line('w_category_name') ?>">
												</td>
												<td class="text-end">
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
											<td class="text-end">
												<div class="dropdown">
													<button type="button" class="btn btn-info light sharp border-0" data-toggle="dropdown">
														<i class="far fa-ellipsis-h"></i>
													</button>
													<div class="dropdown-menu">
														<button class="dropdown-item text-info btn_edit_ct" value="<?= $c->id ?>">
															<i class="far fa-edit fa-fw"></i> <?= $this->lang->line('w_edit') ?>
														</button>
														<button class="dropdown-item text-danger btn_delete_ct" value="<?= $c->id ?>">
															<i class="far fa-trash fa-fw"></i> <?= $this->lang->line('w_delete') ?>
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
									<label><?= $this->lang->line('w_category') ?></label>
									<select class="form-control sl_category" name="category_id">
										<option value="">-</option>
										<?php foreach($categories as $item){ ?>
										<option value="<?= $item->id ?>"><?= $item->name ?></option>
										<?php } ?>
									</select>
									<div class="sys_msg" id="ap_category_msg"></div>
								</div>
								<div class="form-group col-md-6">
									<label><?= $this->lang->line('w_type') ?></label>
									<select class="form-control" name="type_id">
										<?php foreach($prod_types as $item){ ?>
										<option value="<?= $item->id ?>"><?= $item->description ?></option>
										<?php } ?>
									</select>
									<div class="sys_msg" id="ap_type_msg"></div>
								</div>
								<div class="form-group col-md-6">
									<label><?= $this->lang->line('w_code') ?></label>
									<input type="text" class="form-control" name="code">
									<div class="sys_msg" id="ap_code_msg"></div>
								</div>
								<div class="form-group col-md-6">
									<label><?= $this->lang->line('w_image') ?> <small>(<?= $this->lang->line('w_optional') ?>)</small></label>
									<input type="file" class="form-control" id="ap_image" name="image" accept="image/*">
									<div class="sys_msg" id="ap_image_msg"></div>
								</div>
								<div class="form-group col-md-6">
									<label><?= $this->lang->line('w_price') ?></label>
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
									<label><?= $this->lang->line('w_name') ?></label>
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