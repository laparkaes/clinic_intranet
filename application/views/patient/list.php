<div class="col-sm-12 d-flex justify-content-between align-items-center pb-3">
	<div class="welcome-text d-md-none d-block">
		<h4 class="text-primary mb-0"><?= $this->lang->line('patients') ?></h4>
	</div>
	<div class="btn-group">
		<button type="button" class="btn btn-primary control_bl" id="btn_list" value="bl_list">
			<i class="fas fa-list mr-2"></i><?= $this->lang->line('btn_list') ?>
		</button>
		<button type="button" class="btn btn-outline-primary control_bl" value="bl_add">
			<i class="fas fa-plus mr-2"></i><?= $this->lang->line('btn_add') ?>
		</button>
	</div>
</div>
<div class="col-md-12">
	<div class="card">
		<div class="card-body">
			<div class="row bl_content" id="bl_list">
				<div class="col-md-12 d-md-flex justify-content-end">
					<form class="form-inline">
						<input type="hidden" value="1" name="page">
						<label class="sr-only" for="inp_search"><?= $this->lang->line('lb_search') ?></label>
						<input type="text" class="form-control mb-2 mr-sm-2" id="inp_search" name="keyword" placeholder="<?= $this->lang->line('lb_search') ?>" value="<?= $f_url["keyword"] ?>">
						<button type="submit" class="btn btn-primary mb-2">
							<i class="far fa-search"></i>
						</button>
					</form>
				</div>
				<div class="col-md-12">
					<?php if ($patients){ ?>
					<div class="table-responsive">
						<table class="table table-responsive-md">
							<thead>
								<tr>
									<th><strong>#</strong></th>
									<th><strong><?= $this->lang->line('hd_document') ?></strong></th>
									<th><strong><?= $this->lang->line('hd_name') ?></strong></th>
									<th><strong><?= $this->lang->line('hd_tel') ?></strong></th>
									<th><strong><?= $this->lang->line('hd_email') ?></strong></th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($patients as $i => $item){ ?>
								<tr>
									<td><strong><?= number_format(($f_url["page"] - 1) * 25 + 1 + $i) ?></strong></td>
									<td><?= $doc_types_arr[$item->doc_type_id]." ".$item->doc_number ?></td>
									<td><?= $item->name ?></td>
									<td><?= $item->tel ?></td>
									<td><?= $item->email ?></td>
									<td class="text-right">
										<a href="<?= base_url() ?>patient/detail/<?= $item->id ?>">
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
							<a href="<?= base_url() ?>patient?<?= http_build_query($f_url) ?>" class="btn btn-<?= $p[2] ?>">
								<?= $p[1] ?>
							</a>
							<?php } ?>
						</div>
					</div>
					<?php }else{ ?>
					<h5 class="text-danger mt-3"><?= $this->lang->line('msg_no_patients') ?></h5>
					<?php } ?>
				</div>
			</div>
			<div class="row bl_content d-none" id="bl_add">
				<div class="col-md-12">
					<form class="form-row" id="register_form" action="#">
						<div class="col-md-12">
							<h4 class="mb-3"><?= $this->lang->line('title_patient_register') ?></h4>
							<div class="form-row">
								<div class="form-group col-md-3">
									<label><?= $this->lang->line('lb_document') ?></label>
									<select class="form-control" id="pn_doc_type_id" name="doc_type_id">
										<?php foreach($doc_types as $d){ if ($d->sunat_code){ ?>
										<option value="<?= $d->id ?>"><?= $d->description ?></option>
										<?php }} ?>
									</select>
									<div class="sys_msg" id="pn_doc_type_msg"></div>
								</div>
								<div class="form-group col-md-3">
									<label class="d-md-block d-none">&nbsp;</label>
									<div class="input-group">
										<input type="text" class="form-control" id="pn_doc_number" name="doc_number" placeholder="<?= $this->lang->line('lb_number') ?>">
										<div class="input-group-append">
											<button class="btn btn-primary border-0" type="button" id="btn_search_person_pn">
												<i class="fas fa-search"></i>
											</button>
										</div>
									</div>
									<div class="sys_msg" id="pn_doc_number_msg"></div>
								</div>
								<div class="form-group col-md-4">
									<label><?= $this->lang->line('lb_name') ?></label>
									<input type="text" class="form-control" id="pn_name" name="name">
									<div class="sys_msg" id="pn_name_msg"></div>
								</div>
								<div class="form-group col-md-2">
									<label><?= $this->lang->line('lb_tel') ?></label>
									<input type="text" class="form-control" id="pn_tel" name="tel">
									<div class="sys_msg" id="pn_tel_msg"></div>
								</div>
								<div class="form-group col-md-6">
									<label><?= $this->lang->line('lb_email') ?></label>
									<input type="email" class="form-control" name="email" placeholder="email@example.com">
									<div class="sys_msg" id="pn_email_msg"></div>
								</div>
								<div class="form-group col-md-2">
									<label><?= $this->lang->line('lb_birthday') ?></label>
									<input type="text" class="form-control bw date_picker_all" name="birthday" readonly="">
									<div class="sys_msg" id="pn_birthday_msg"></div>
								</div>
								<div class="form-group col-md-2">
									<label><?= $this->lang->line('lb_sex') ?></label>
									<select class="form-control" name="sex_id">
										<option value="" selected="">--</option>
										<?php foreach($sex_ops as $item){ ?>
										<option value="<?= $item->id ?>"><?= $item->description ?></option>
										<?php } ?>
									</select>
									<div class="sys_msg" id="pn_sex_msg"></div>
								</div>
								<div class="form-group col-md-2">
									<label><?= $this->lang->line('lb_blood_type') ?></label>
									<select class="form-control" name="blood_type_id">
										<option value="" selected="">--</option>
										<?php foreach($blood_type_ops as $item){ ?>
										<option value="<?= $item->id ?>"><?= $item->description ?></option>
										<?php } ?>
									</select>
									<div class="sys_msg" id="pn_blood_type_msg"></div>
								</div>
								<div class="form-group col-md-12">
									<label><?= $this->lang->line('lb_address') ?></label>
									<input type="text" class="form-control" name="address">
									<div class="sys_msg" id="pn_address_msg"></div>
								</div>
							</div>
						</div>
						<div class="col-md-12 pt-3">
							<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_register') ?></button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>