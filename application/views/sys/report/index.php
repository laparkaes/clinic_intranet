<div class="pagetitle">
	<h1><?= $title ?></h1>
	<nav>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="<?= base_url() ?>"><?= $this->lang->line('w_home') ?></a></li>
			<li class="breadcrumb-item active"><?= $title ?></li>
		</ol>
	</nav>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<h5 class="card-title"><?= $this->lang->line('w_detailed_report') ?></h5>
				<div class="row">
					<div class="col-md-6">
						<form class="row g-3" id="form_generate_report">
							<div class="col-md-12">
								<label class="form-label"><?= $this->lang->line('w_type') ?></label>
								<select class="form-select" name="type_id">
									<?php foreach($report_types as $item){ ?>
									<option value="<?= $item->id ?>"><?= $item->name ?></option>
									<?php } ?>
								</select>
								<div class="sys_msg" id="gr_type_msg"></div>
							</div>
							<div class="col-md-6">
								<label class="form-label"><?= $this->lang->line('w_from') ?></label>
								<input type="text" class="form-control" id="gr_from" name="from" value="<?= date("Y-m-d", strtotime("-3 months")) ?>">
								<div class="sys_msg" id="gr_from_msg"></div>
							</div>
							<div class="col-md-6">
								<label class="form-label"><?= $this->lang->line('w_to') ?></label>
								<input type="text" class="form-control" id="gr_to" name="to" value="<?= date("Y-m-d") ?>">
								<div class="sys_msg" id="gr_to_msg"></div>
							</div>
							<div class="col-md-12 pt-3">
								<button type="submit" class="btn btn-primary">
									<?= $this->lang->line('btn_generate') ?>
								</button>
							</div>
						</form>
					</div>
					<div class="col-md-6">
						<img class="w-100" src="<?= base_url() ?>resources/images/report_example.png">
						<div class="text-center mt-3"><?= $this->lang->line('w_example') ?></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
document.addEventListener("DOMContentLoaded", () => {
	set_date_picker("#gr_from", null);
	set_date_picker("#gr_to", null);
	
	$("#form_generate_report").submit(function(e) {
		e.preventDefault();
		ajax_form(this, "sys/report/generate_report").done(function(res) {
			set_msg(res.msgs);
			if (res.type == "success") location.href = res.move_to;
			else swal(res.type, res.msg);
		});
	});
});
</script>