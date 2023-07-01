<div class="col-md-12">
	<h4><?= $this->lang->line('title_monthly_resume') ?></h4>
</div>
<div class="col-md-4">
	<div class="card">
		<div class="card-body row py-3">
			<div class="col-md-4 px-md-0 text-center d-flex align-items-center justify-content-center">
				<i class="fas fa-notes-medical" style="color: #5fe1ad; font-size: 40px;"></i>
			</div>
			<div class="col-md-8 pr-md-0 text-center">
				<p class="mb-1"><?= $this->lang->line('lb_appointments') ?></p>
				<h4 class="mb-0"><?= number_format($appointment_qty) ?></h4>
			</div>
		</div>
	</div>
</div>
<div class="col-md-4">
	<div class="card">
		<div class="card-body row py-3">
			<div class="col-md-4 px-md-0 text-center d-flex align-items-center justify-content-center">
				<i class="fas fa-file-medical-alt" style="color: #AC4CBC; font-size: 40px;"></i>
			</div>
			<div class="col-md-8 pr-md-0 text-center">
				<p class="mb-1"><?= $this->lang->line('lb_surgeries') ?></p>
				<h4 class="mb-0"><?= number_format($surgery_qty) ?></h4>
			</div>
		</div>
	</div>
</div>
<div class="col-md-4">
	<div class="card">
		<div class="card-body row py-3">
			<div class="col-md-4 px-md-0 text-center d-flex align-items-center justify-content-center">
				<i class="fas fa-shopping-basket" style="color: #3A82EF; font-size: 40px;"></i>
			</div>
			<div class="col-md-8 pr-md-0 text-center">
				<p class="mb-1"><?= $this->lang->line('lb_sales') ?></p>
				<h4 class="mb-0"><?= number_format($sale_qty) ?></h4>
			</div>
		</div>
	</div>
</div>
<div class="col-md-7 mb-4">
	<h4><?= $this->lang->line('title_monthly_income') ?></h4>
	<div class="card">
		<div class="card-header pb-0 border-0">
			<div class="btn-group">
				<?php $cl = ""; foreach($currencies as $item){ ?>
				<button type="button" class="btn btn<?= $cl ?>-primary btn-xs btn_load_income_chart" value="<?= $item->id ?>">
					<?= $item->description ?>
				</button>
				<?php $cl = "-outline";} ?>
			</div>
		</div>
		<div class="card-body p-0" id="chart_monthly_income"></div>
	</div>
</div>
<div class="col-md-5 mb-4">
	<h4><?= $this->lang->line('title_profile') ?></h4>
	<div class="card">
		<div class="card-body text-center d-flex align-items-center justify-content-center">
			<div>
				<div class="mb-3"><i class="fas fa-user-tie fa-7x"></i></div>
				<h4 class="text-black mb-3"><?= $profile["name"] ?></h4>
				<div><?= $profile["role"] ?></div>
				<div><?= $profile["email"] ?></div>
			</div>
		</div>
		<div class="card-footer p-0 text-center">
			<div class="row">
				<div class="col-4 py-3 pr-0 border-right">
					<h3 class="mb-1 text-primary"><?= $profile["doctor_qty"] ?></h3>
					<span><?= $this->lang->line('lb_doctors') ?></span>
				</div>
				<div class="col-4 py-3 border-right">
					<h3 class="mb-1 text-primary"><?= $profile["patient_qty"] ?></h3>
					<span><?= $this->lang->line('lb_patients') ?></span>
				</div>
				<div class="col-4 py-3 pl-0">
					<h3 class="mb-1 text-primary"><?= $profile["account_qty"] ?></h3>
					<span><?= $this->lang->line('lb_accounts') ?></span>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="col-md-6">
	<h4><?= $this->lang->line('title_sales_no_voucher') ?></h4>
	<div class="card">
		<div class="card-body">
			<?php if ($sales){ ?>
			<div class="table-responsive">
				<table class="table text-center bg-info-hover tr-rounded mb-0">
					<thead>
						<tr>
							<th class="text-left"><?= $this->lang->line('th_amount') ?></th>
							<th class="text-center"><?= $this->lang->line('th_date') ?></th>
							<th class="text-right"></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($sales as $item){ ?>
						<tr>
							<td class="text-left"><?= $item->currency." ".number_format($item->total, 2) ?></td>
							<td><?= date("Y-m-d", strtotime($item->registed_at)) ?></td>
							<td class="text-right">
								<a href="/intranet/sale/detail/<?= $item->id ?>">
									<button type="button" class="btn btn-info light sharp">
										<i class="fas fa-arrow-alt-right"></i>
									</button>
								</a>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<?php }else{ ?>
			<div class="text-success"><?= $this->lang->line('msg_sale_declare') ?></div>
			<?php } ?>
		</div>
	</div>
</div>
<div class="col-md-6">
	<h4><?= $this->lang->line('title_vouchers_no_sunat') ?></h4>
	<div class="card">
		<div class="card-body">
			<?php if ($vouchers){ ?>
			<div class="table-responsive">
				<table class="table text-center bg-info-hover tr-rounded mb-0">
					<thead>
						<tr>
							<th class="text-left"><?= $this->lang->line('th_type') ?></th>
							<th class="text-center"><?= $this->lang->line('th_date') ?></th>
							<th class="text-right"></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($vouchers as $item){ ?>
						<tr>
							<td class="text-left"><?= $item->voucher_type ?></td>
							<td><?= date("Y-m-d", strtotime($item->registed_at)) ?></td>
							<td class="text-right">
								<a href="/intranet/sale/detail/<?= $item->sale_id ?>">
									<button type="button" class="btn btn-info light sharp">
										<i class="fas fa-arrow-alt-right"></i>
									</button>
								</a>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<?php }else{ ?>
			<div class="text-success"><?= $this->lang->line('msg_voucher_sent') ?></div>
			<?php } ?>
		</div>
	</div>
</div>