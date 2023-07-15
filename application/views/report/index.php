<div class="col-md-12 d-md-none d-block">
	<div class="row page-titles mx-0">
		<div class="col-sm-12 p-md-0">
			<div class="welcome-text">
				<h4><?= $this->lang->line('reports') ?></h4>
			</div>
		</div>
	</div>
</div>
<div class="col-md-12">
	<div class="card">
		<div class="card-body">
			<div class="row">
				<div class="col-md-6">
					<h5 class="text-primary mb-3"><?= $this->lang->line('w_detailed_report') ?></h5>
					<form class="form-row" id="form_generate_report" action="#">
						<div class="form-group col-md-12">
							<label><?= $this->lang->line('w_type') ?></label>
							<select class="form-control" name="type_id">
								<?php foreach($report_types as $item){ ?>
								<option value="<?= $item->id ?>"><?= $item->name ?></option>
								<?php } ?>
							</select>
							<div class="sys_msg" id="gr_type_msg"></div>
						</div>
						<div class="form-group col-md-6">
							<label><?= $this->lang->line('w_from') ?></label>
							<input type="text" class="form-control" id="gr_from" name="from" value="<?= date("Y-m-d", strtotime("-3 months")) ?>">
							<div class="sys_msg" id="gr_from_msg"></div>
						</div>
						<div class="form-group col-md-6">
							<label><?= $this->lang->line('w_to') ?></label>
							<input type="text" class="form-control" id="gr_to" name="to" value="<?= date("Y-m-d") ?>">
							<div class="sys_msg" id="gr_to_msg"></div>
						</div>
						<div class="form-group col-md-12 pt-3">
							<button type="submit" class="btn btn-primary">
								<?= $this->lang->line('btn_generate') ?>
							</button>
						</div>
					</form>
				</div>
				<div class="col-md-6">
					<img class="w-100" src="./resources/images/report_example.png">
					<h5 class="text-center text-muted mt-3"><?= $this->lang->line('w_example') ?></h5>
				</div>
			</div>
		</div>
	</div>
</div>