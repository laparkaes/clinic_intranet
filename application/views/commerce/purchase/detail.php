<div class="pagetitle">
	<h1><?= $this->lang->line('w_purchase_detail') ?></h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= $this->lang->line('w_home') ?></a></li>
			<li class="breadcrumb-item"><a href="<?= base_url() ?>purchase"><?= $this->lang->line('purchases') ?></a></li>
			<li class="breadcrumb-item active"><?= $this->lang->line('txt_detail') ?></li>
		</ol>
	</nav>
</div>
<div class="row mt-3">
	<div class="col">
		<div class="card">
			<div class="card-body">
				<div class="d-flex justify-content-between align-items-center">
					<h5 class="card-title"><?= $this->lang->line('w_detail') ?></h5>
					<?php if ($purchase->status_id == 3){ ?>
					<button type="button" class="btn btn-danger btn-sm">Cancelar</button>
					<?php } ?>
				</div>
				<div class="row g-3">
					<div class="col-md-6">
						<label class="form-label"><?= $this->lang->line('w_date') ?></label>
						<div class="form-control"><?= $purchase->registed_at ?></div>
					</div>
					<div class="col-md-6">
						<label class="form-label"><?= $this->lang->line('w_last_update') ?></label>
						<div class="form-control"><?= $purchase->updated_at ?></div>
					</div>
					<div class="col-md-3">
						<label class="form-label"><?= $this->lang->line('w_status') ?></label>
						<div class="form-control text-<?= $purchase->status->color ?>">
							<?= $this->lang->line($purchase->status->code) ?>
						</div>
					</div>
					<div class="col-md-3">
						<label class="form-label"><?= $this->lang->line('w_total') ?></label>
						<div class="input-group">
							<span class="input-group-text"><?= $purchase->currency->description ?></span>
							<div class="form-control"><?= number_format($purchase->total, 2) ?></div>
						</div>
					</div>
					<div class="col-md-3">
						<label class="form-label"><?= $this->lang->line('w_amount') ?></label>
						<div class="input-group">
							<span class="input-group-text"><?= $purchase->currency->description ?></span>
							<div class="form-control"><?= number_format($purchase->amount, 2) ?></div>
						</div>
					</div>
					<div class="col-md-3">
						<label class="form-label"><?= $this->lang->line('w_vat') ?></label>
						<div class="input-group">
							<span class="input-group-text"><?= $purchase->currency->description ?></span>
							<div class="form-control"><?= number_format($purchase->vat, 2) ?></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-body">
				<h5 class="card-title"><?= $this->lang->line('w_products') ?></h5>
				<div class="table-responsive">
					<table class="table align-middle">
						<thead>
							<tr>
								<th scope="col">#</th>
								<th scope="col">Imagen</th>
								<th scope="col">Producto</th>
								<th scope="col">Opcion</th>
								<th scope="col">Precio de Compra</th>
								<th scope="col">Cantidad</th>
								<th scope="col"></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($products as $i => $p){ ?>
							<tr>
								<th scope="row"><?= $i + 1 ?></th>
								<td>
									<img src="<?= base_url() ?>uploaded/products/<?= $p->prod->image ? $p->prod->id."/".$p->prod->image : "no_img.png" ?>" style="max-width: 60px; max-height: 60px;">
								</td>
								<td>
									<div><?= $p->prod->description ?></div>
									<div><?= $p->prod->type->description ?> > <?= $p->prod->category->name ?></div>
								</td>
								<td><?= $p->option ? $p->option->description : "" ?></td>
								<td><?= $p->prod->currency->description." ".number_format($p->price, 2) ?></td>
								<td><?= number_format($p->qty) ?></td>
								<td class="text-end">
									<a href="<?= base_url() ?>commerce/product/detail/<?= $p->product_id ?>" class="btn btn-primary btn-sm">
										<i class="bi bi-search"></i>
									</a>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-body">
				<h5 class="card-title"><?= $this->lang->line('w_provider') ?></h5>
				<form class="row g-3" id="form_save_provider">
					<div class="col-md-4">
						<label class="form-label"><?= $this->lang->line('w_ruc') ?></label>
						<div class="input-group">
							<input type="text" class="form-control" id="prov_ruc" name="provider[tax_id]" value="<?= $provider->tax_id ?>">
							<button class="btn btn-primary" id="btn_search_provider" type="button">
								<i class="bi bi-search"></i>
							</button>
						</div>
						<div class="sys_msg" id="epv_ruc_msg"></div>
					</div>
					<div class="col-md-4">
						<label class="form-label"><?= $this->lang->line('w_company') ?></label>
						<input type="text" class="form-control" id="prov_name" name="provider[name]" value="<?= $provider->name ?>">
						<div class="sys_msg" id="epv_company_msg"></div>
					</div>
					<div class="col-md-4">
						<label class="form-label"><?= $this->lang->line('w_web') ?></label>
						<input type="text" class="form-control" id="prov_web" name="provider[web]" value="<?= $provider->web ?>">
					</div>
					<div class="col-md-4">
						<label class="form-label"><?= $this->lang->line('w_person') ?></label>
						<input type="text" class="form-control" id="prov_person" name="provider[person]" value="<?= $provider->person ?>">
					</div>
					<div class="col-md-4">
						<label class="form-label"><?= $this->lang->line('w_tel') ?></label>
						<input type="text" class="form-control" id="prov_tel" name="provider[tel]" value="<?= $provider->tel ?>">
					</div>
					<div class="col-md-4">
						<label class="form-label"><?= $this->lang->line('w_email') ?></label>
						<input type="text" class="form-control" id="prov_email" name="provider[email]" value="<?= $provider->email ?>">
					</div>
					<div class="col-md-12">
						<label class="form-label"><?= $this->lang->line('w_address') ?></label>
						<input type="text" class="form-control" id="prov_address" name="provider[address]" value="<?= $provider->address ?>">
					</div>
					<div class="col-md-12">
						<label class="form-label"><?= $this->lang->line('w_remark') ?></label>
						<textarea class="form-control" id="prov_remark" rows="3" name="provider[remark]"><?= $provider->remark ?></textarea>
					</div>
					<div class="col-md-12 pt-3">
						<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_save') ?></button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
document.addEventListener("DOMContentLoaded", () => {
	//purchase
	//cancel purchase
	$("#btn_delete_payment").click(function() {
		ajax_simple_warning({id: $(this).val()}, "commerce/purchase/delete_payment", "wm_payment_delete").done(function(res) {
			swal_redirection(res.type, res.msg, window.location.href);
		});
	});
	
	
	//provider
	$("#form_save_provider").submit(function(e) {
		e.preventDefault();
		$("#form_edit_provider .sys_msg").html("");
		ajax_form(this, "commerce/purchase/save_provider").done(function(res) {
			swal(res.type, res.msg);
		});
	});
	
	$("#btn_search_provider").click(function() {
		ajax_simple({tax_id: $("#prov_ruc").val()}, "ajax_f/search_company").done(function(res) {
			swal(res.type, res.msg);
			if (res.type == "success"){
				$("#prov_name").val(res.company.name);
				$("#prov_web").val(res.company.web);
				$("#prov_person").val(res.company.person);
				$("#prov_tel").val(res.company.tel);
				$("#prov_email").val(res.company.email);
				$("#prov_address").val(res.company.address);
				$("#prov_remark").val(res.company.remark);
			}
		});
	});
});
</script>