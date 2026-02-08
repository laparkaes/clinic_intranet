<div class="row">
	<div class="col">
		<div class="mb-3">
			<a href="<?= base_url() ?>commerce/sale/new_sale" class="btn btn-primary">
				<i class="bi bi-plus-lg me-1"></i> Nueva Venta
			</a>
			
			<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_search">
				<i class="bi bi-search me-1"></i> Buscar
			</button>
			<div class="modal fade" id="modal_search" tabindex="-1">
				<div class="modal-dialog">
					<form class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title">Buscar Venta</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<div class="row g-3">
								<div class="col-md-6">
									<label class="form-label">Desde</label>
									<input type="text" class="form-control" id="s_from" name="from" value="<?= $f_url["from"] ?>">
								</div>
								<div class="col-md-6">
									<label class="form-label">Hasta</label>
									<input type="text" class="form-control" id="s_to" name="to" value="<?= $f_url["to"] ?>">
								</div>
								<div class="col-md-12">
									<label class="form-label">Cliente (Número de documento o Nombre)</label>
									<input type="text" class="form-control" name="client" value="<?= $f_url["client"] ?>">
								</div>
								<div class="col-md-12">
									<label class="form-label">Producto</label>
									<input type="text" class="form-control" name="product" value="<?= $f_url["product"] ?>">
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
							<button type="reset" class="btn btn-secondary">Limpiar</button>
							<button type="submit" class="btn btn-primary">Buscar</button>
						</div>
					</form>
				</div>
			</div>
		
		</div>
	</div>
</div>
<div class="row">
	<div class="col">
		<div class="card">
			<div class="card-body">
				<h5 class="card-title"><?= $this->lang->line('w_list') ?></h5>
				<?php if ($sales){ ?>
				<div class="table-responsive">
					<table class="table align-middle">
						<thead>
							<tr>
								<th>#</th>
								<th>Fecha</th>
								<th>Cliente</th>
								<th class="text-nowrap">Total (Saldo)</th>
								<th>Estado</th>
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
								<td class="text-end">
									<a href="<?= base_url() ?>commerce/sale/detail/<?= $item->id ?>" class="btn btn-primary btn-sm">
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
						<a href="<?= base_url() ?>commerce/sale?<?= http_build_query($f_url) ?>" class="btn btn-<?= $p[2] ?>">
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
</div>
<script>
document.addEventListener("DOMContentLoaded", () => {
	
	set_date_picker("#s_from", null);
	set_date_picker("#s_to", null);
	
});
</script>