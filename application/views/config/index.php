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
	<div class="row d-flex justify-content-center">
		<div class="col-md-3">
			<button class="btn btn-primary w-100 mb-3 control_bl_simple" value="bl_user_admin">
				<div><i class="fal fa-users-cog fa-5x fa-fw"></i></div>
				<div class="fs-16 mt-2 pt-2 border-top border-white">Usuario</div>
			</button>
		</div>
		<div class="col-md-3">
			<button class="btn btn-primary w-100 mb-3 control_bl_simple" value="bl_rol_admin">
				<div><i class="fal fa-lock-alt fa-5x fa-fw"></i></div>
				<div class="fs-16 mt-2 pt-2 border-top border-white">Rol / Acceso</div>
			</button>
		</div>
		<div class="col-md-3">
			<button class="btn btn-secondary w-100 mb-3 control_bl_simple" value="bl_company_admin">
				<div><i class="fal fa-building fa-5x fa-fw"></i></div>
				<div class="fs-16 mt-2 pt-2 border-top border-white">Empresa</div>
			</button>
		</div>
		<div class="col-md-3">
			<button class="btn btn-info w-100 mb-3 control_bl_simple" value="bl_system_admin">
				<div><i class="fal fa-tools fa-5x fa-fw"></i></div>
				<div class="fs-16 mt-2 pt-2 border-top border-white">Sistema</div>
			</button>
		</div>
	</div>
</div>
<div class="col-md-12" id="bl_user_admin">
	<div class="card">
		<div class="card-header border-0 pb-0">
			<h4 class="card-title">Usuario</h4>
		</div>
		<div class="card-body">
		</div>
	</div>
</div>
<div class="col-md-12" id="bl_rol_admin">
	<div class="card">
		<div class="card-header border-0 pb-0">
			<h4 class="card-title">Rol / Acceso</h4>
		</div>
		<div class="card-body">
		</div>
	</div>
</div>
<div class="col-md-12" id="bl_company_admin">
	<div class="card">
		<div class="card-header border-0 pb-0">
			<h4 class="card-title">Empresa</h4>
		</div>
		<div class="card-body">
			<div class="basic-form">
				<form action="#" id="form_update_company">
					<div class="row">
						<div class="col-md-6">
							<h5>Datos Legales</h5>
							<div class="form-row">
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
							</div>
						</div>
						<div class="col-md-6">
							<h5>Acceso de Sunat</h5>
							<div class="form-row">
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
							</div>
						</div>
						<div class="col-md-12 p-3">
							<button type="submit" class="btn btn-primary">Guardar</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<div class="col-md-12" id="bl_system_admin">
	<div class="card">
		<div class="card-header border-0 pb-0">
			<h4 class="card-title">Sistema</h4>
		</div>
		<div class="card-body">
		</div>
	</div>
</div>