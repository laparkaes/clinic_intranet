<div class="col-md-12">
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
					<h5 class="text-primary mb-3">Generar Reporte Detallado</h5>
					<form class="form-row" id="form_generate_report" action="#">
						<div class="form-group col-md-12">
							<label>Tipo</label>
							<select class="form-control" name="type">
								<option value="1">Medico</option>
								<option value="1">Paciente</option>
								<option value="1">Consulta</option>
								<option value="1">Cirugia</option>
								<option value="1">Producto</option>
								<option value="1">Venta</option>
							</select>
							<div class="sys_msg" id="gr_type_msg"></div>
						</div>
						<div class="form-group col-md-6">
							<label>Desde</label>
							<input type="text" class="form-control" id="gr_from" name="from">
							<div class="sys_msg" id="gr_from_msg"></div>
						</div>
						<div class="form-group col-md-6">
							<label>Hasta</label>
							<input type="text" class="form-control" id="gr_to" name="to">
							<div class="sys_msg" id="gr_to_msg"></div>
						</div>
						<div class="form-group col-md-12 pt-3">
							<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_register') ?></button>
						</div>
					</form>
				</div>
				<div class="col-md-6">
					<img class="w-100" src="./resorces/images/report_example.png">
					<div class="text-center text-muted mt-3">Ejemplo de Reporte</div>
				</div>
			</div>
		</div>
	</div>
</div>