<div class="col-md-12">
	<div class="row page-titles mx-0">
		<div class="col-sm-6 p-md-0">
			<div class="welcome-text">
				<h4>Configuracion</h4>
			</div>
		</div>
	</div>
</div>
<div class="col-md-12">
	<form class="form-row mb-3" id="form_register_account" action="#">
		<div class="col-md-6 col-sm-12">
			<h5><?= $this->lang->line('title_personal_info') ?></h5>
			<div class="form-row">
				<div class="form-group col-md-12">
					<label><?= $this->lang->line('lb_document') ?></label>
					<div class="input-group">
						<select class="form-control" id="ra_doc_type_id" name="p[doc_type_id]">
							<?php foreach($doc_types as $d){ if ($d->sunat_code){ ?>
							<option value="<?= $d->id ?>"><?= $d->description ?></option>
							<?php }} ?>
						</select>
						<input type="text" class="form-control border-left-0" id="ra_doc_number" name="p[doc_number]" placeholder="<?= $this->lang->line('lb_number') ?>">
						<div class="input-group-append">
							<button class="btn btn-primary border-0" type="button" id="btn_search_person_ra">
								<i class="fas fa-search"></i>
							</button>
						</div>
					</div>
					<div class="sys_msg" id="ra_doc_msg"></div>
				</div>
				<div class="form-group col-md-8">
					<label><?= $this->lang->line('lb_name') ?></label>
					<input type="text" class="form-control" id="ra_name" name="p[name]">
					<div class="sys_msg" id="ra_name_msg"></div>
				</div>
				<div class="form-group col-md-4">
					<label><?= $this->lang->line('lb_tel') ?></label>
					<input type="text" class="form-control" id="ra_tel" name="p[tel]">
					<div class="sys_msg" id="ra_tel_msg"></div>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-sm-12">
			<h5><?= $this->lang->line('title_account') ?></h5>
			<div class="form-row">
				<div class="form-group col-md-4">
					<label><?= $this->lang->line('lb_role') ?></label>
					<select class="form-control" name="a[role_id]">
						<option value="" selected>--</option>
						<?php foreach($roles as $item){ ?>
						<option value="<?= $item->id ?>"><?= $this->lang->line('role_'.$item->name) ?></option>
						<?php } ?>
					</select>
					<div class="sys_msg" id="ra_role_msg"></div>
				</div>
				<div class="form-group col-md-8">
					<label><?= $this->lang->line('lb_email') ?></label>
					<input type="email" class="form-control" id="ra_email" name="a[email]" placeholder="email@example.com">
					<div class="sys_msg" id="ra_email_msg"></div>
				</div>
				<div class="form-group col-md-6">
					<label><?= $this->lang->line('lb_password') ?></label>
					<input type="password" class="form-control" name="a[password]">
					<div class="sys_msg" id="ra_password_msg"></div>
				</div>
				<div class="form-group col-md-6">
					<label><?= $this->lang->line('lb_confirm') ?></label>
					<input type="password" class="form-control" name="a[confirm]">
					<div class="sys_msg" id="ra_confirm_msg"></div>
				</div>
			</div>
		</div>
		<div class="col-md-12 pt-3">
			<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_register') ?></button>
		</div>
	</form>
	<div class="table-responsive">
		<table class="table table-responsive-md">
			<thead>
				<tr>
					<th><strong>#</strong></th>
					<th><strong>Rol</strong></th>
					<th><strong>Email</strong></th>
					<th><strong>Nombre</strong></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($accounts as $i => $item){ ?>
				<tr>
					<td><?= $i + 1 ?></td>
					<td><?= $this->lang->line('role_'.$roles_arr[$item->role_id]) ?></td>
					<td><?= $item->email ?></td>
					<td><?= $people_arr[$item->person_id] ?></td>
					<td class="text-right">
						<button type="button" class="btn btn-danger shadow btn-xs sharp remove_account" value="<?= $item->id ?>">
							<i class="fas fa-trash"></i>
						</button>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>
<div class="col-md-12">
	<div class="card">
		<div class="card-body">
			<div class="custom-tab-1">
				<ul class="nav nav-tabs">
					<li class="nav-item">
						<a class="nav-link active" data-toggle="tab" href="#bl_user_admin">
							<i class="fal fa-users-cog mr-3"></i>Usuario
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#bl_role_admin">
							<i class="fal fa-lock-alt mr-3"></i>Rol / Acceso
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#bl_company_admin">
							<i class="fal fa-building mr-3"></i>Empresa
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#bl_sistem_admin">
							<i class="fal fa-tools mr-3"></i>Sistema
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#bl_history_admin">
							<i class="fal fa-history mr-3"></i>Historial
						</a>
					</li>
				</ul>
				<div class="tab-content mt-4" style="min-height: 500px;">
					<div class="tab-pane fade show active" id="bl_user_admin" role="tabpanel">
					</div>
					<div class="tab-pane fade" id="bl_role_admin">
						<div class="table-responsive">
							<table class="table table-responsive-md text-center">
								<tbody>
									<?php $colspan = count($roles) + 1; foreach($access as $module => $a_list){ ?>
									<tr class="bg-light">
										<td class="text-left"><strong><?= $this->lang->line("module_".$module) ?></strong></td>
										<?php foreach($roles as $item){ ?>
										<td style="width: 105px;"><strong><?= $this->lang->line('role_'.$item->name) ?></strong></td>
										<?php } ?>
									</tr>
									<?php foreach($a_list as $a){ ?>
									<tr>
										<td class="text-left pl-4"><?= $this->lang->line('access_'.$a->description) ?></td>
										<?php foreach($roles as $r){ $value = $r->id."_".$a->id;
										if (in_array($value, $role_access)) $checked = "checked"; else $checked = ""; ?>
										<td>
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="custom-control-input chk_access" id="chk_<?= $value ?>" value="<?= $value ?>" <?= $checked ?>>
												<label class="custom-control-label" for="chk_<?= $value ?>"></label>
											</div>
										</td>
										<?php } ?>
									</tr>
									<?php }} ?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="tab-pane fade" id="bl_company_admin">
						<div class="row">
							<div class="col-md-6">
								<h4>Datos Legales</h4>
								<form action="#" id="form_update_company_data" class="form-row">
									<div class="form-group col-md-4">
										<label>RUC</label>
										<input type="text" class="form-control" value="<?= $company->ruc ?>" name="ruc">
										<div class="sys_msg" id="com_ruc_msg"></div>
									</div>
									<div class="form-group col-md-8">
										<label>Nombre</label>
										<input type="text" class="form-control" value="<?= $company->name ?>" name="name">
										<div class="sys_msg" id="com_name_msg"></div>
									</div>
									<div class="form-group col-md-6">
										<label>Email</label>
										<input type="text" class="form-control" value="<?= $company->email ?>" name="email">
										<div class="sys_msg" id="com_email_msg"></div>
									</div>
									<div class="form-group col-md-6">
										<label>Telefono</label>
										<input type="text" class="form-control" value="<?= $company->tel ?>" name="tel">
										<div class="sys_msg" id="com_tel_msg"></div>
									</div>
									<div class="form-group col-md-6">
										<label>Direccion</label>
										<input type="text" class="form-control" value="<?= $company->address ?>" name="address">
										<div class="sys_msg" id="com_address_msg"></div>
									</div>
									<div class="form-group col-md-6">
										<label>Urbanizacion</label>
										<input type="text" class="form-control" value="<?= $company->urbanization ?>" name="urbanization">
									</div>
									<div class="form-group col-md-4">
										<label>Departamento</label>
										<select class="form-control" id="sl_department" name="department_id">
											<option value="">-</option>
											<?php foreach($departments as $item){
												if ($item->id == $company->department_id) $selected = "selected";
												else $selected = ""; ?>
											<option value="<?= $item->id ?>" <?= $selected ?>><?= $item->name ?></option>
											<?php } ?>
										</select>
										<div class="sys_msg" id="com_department_msg"></div>
									</div>
									<div class="form-group col-md-4">
										<label>Provincia</label>
										<select class="form-control" id="sl_province" name="province_id">
											<option value="">-</option>
											<?php foreach($provinces as $item){
												$selected = ""; $class = "d-none";
												if ($item->department_id == $company->department_id){ $class = "";
													if ($item->id == $company->province_id) $selected = "selected"; } ?>
											<option class="province d<?= $item->department_id ?> <?= $class ?>" value="<?= $item->id ?>" <?= $selected ?>><?= $item->name ?></option>
											<?php } ?>
										</select>
										<div class="sys_msg" id="com_province_msg"></div>
									</div>
									<div class="form-group col-md-4">
										<label>Distrito</label>
										<select class="form-control" id="sl_district" name="district_id">
											<option value="">-</option>
											<?php foreach($districts as $item){
												$selected = ""; $class = "d-none";
												if ($item->province_id == $company->province_id){ $class = "";
													if ($item->id == $company->district_id) $selected = "selected"; } ?>
											<option class="district p<?= $item->province_id ?> <?= $class ?>" value="<?= $item->id ?>" <?= $selected ?>><?= $item->name ?></option>
											<?php } ?>
										</select>
										<div class="sys_msg" id="com_district_msg"></div>
									</div>
									<div class="form-group col-md-12 pt-3">
										<button type="submit" class="btn btn-primary">Guardar</button>
									</div>
								</form>
							</div>
							<div class="col-md-6">
								<h4>Acceso de Sunat</h4>
								<form action="#" id="form_update_sunat_data" class="form-row">
									<div class="form-group col-md-12">
										<label>Resolucion</label>
										<input type="text" class="form-control" value="<?= $company->sunat_resolution ?>" name="sunat_resolution">
										<div class="sys_msg" id="s_res_msg"></div>
									</div>
									<div class="form-group col-md-12">
										<div class="d-flex justify-content-between">
											<label>Certificado Digital</label>
											<?php if ($company->sunat_cert_filename){ ?>
											<a href="<?= base_url()."uploaded/sunat/".$company->sunat_cert_filename ?>" class="text-info" id="ic_cert" target="_blank"><i class="fas fa-download"></i></a>
											<?php } ?>
										</div>
										<input type="file" class="form-control" name="sunat_cert_file">
										<div class="sys_msg" id="s_cer_msg"></div>
									</div>
									<div class="form-group col-md-12">
										<label>Clave SOL</label>
										<input type="text" class="form-control" value="<?= $company->sunat_clave_sol ?>" name="sunat_clave_sol">
										<div class="sys_msg" id="s_cla_msg"></div>
									</div>
									<div class="form-group col-md-12">
										<label>Contraseña</label>
										<input type="text" class="form-control" value="<?= $company->sunat_password ?>" name="sunat_password">
										<div class="sys_msg" id="s_pas_msg"></div>
									</div>
									<div class="form-group col-md-12 pt-3">
										<button type="submit" class="btn btn-primary">Guardar</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="bl_sistem_admin">
						<div class="row">
							<div class="col-md-6">
								<div class="basic-list-group">
                                    <ul class="list-group">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Cras justo odio <span class="badge badge-primary badge-pill">14</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Dapibus ac facilisis in <span class="badge badge-primary badge-pill">2</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Cras justo odio <span class="badge badge-primary badge-pill">14</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Morbi leo risus <span class="badge badge-primary badge-pill">1</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Morbi leo risus <span class="badge badge-primary badge-pill">1</span>
                                        </li>
                                    </ul>
                                </div>
							</div>
							<div class="col-md-6">
								<div class="basic-list-group">
                                    <ul class="list-group">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Cras justo odio <span class="badge badge-primary badge-pill">14</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Dapibus ac facilisis in <span class="badge badge-primary badge-pill">2</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Cras justo odio <span class="badge badge-primary badge-pill">14</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Morbi leo risus <span class="badge badge-primary badge-pill">1</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Morbi leo risus <span class="badge badge-primary badge-pill">1</span>
                                        </li>
                                    </ul>
                                </div>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="bl_history_admin">
						<div class="table-responsive">
							<table class="table table-responsive-md">
								<thead>
									<tr>
										<th>#</th>
										<th>Name</th>
										<th>Status</th>
										<th>Date</th>
										<th>Price</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<th>1</th>
										<td>Kolor Tea Shirt For Man</td>
										<td><span class="badge badge-primary light">Sale</span>
										</td>
										<td>January 22</td>
										<td class="color-primary">$21.56</td>
									</tr>
									<tr>
										<th>2</th>
										<td>Kolor Tea Shirt For Women</td>
										<td><span class="badge badge-success">Tax</span>
										</td>
										<td>January 30</td>
										<td class="color-success">$55.32</td>
									</tr>
									<tr>
										<th>3</th>
										<td>Blue Backpack For Baby</td>
										<td><span class="badge badge-danger">Extended</span>
										</td>
										<td>January 25</td>
										<td class="color-danger">$14.85</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="d-none">
	<input type="hidden" id="warning_rac" value="<?= $this->lang->line('warning_rac') ?>">
</div>