<div class="col-md-12">
	<div class="row page-titles mx-0">
		<div class="col-sm-6 p-md-0">
			<div class="welcome-text">
				<h4><?= $this->lang->line('reports') ?></h4>
			</div>
		</div>
		<div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
			<div class="btn-group">
				<button type="button" class="btn btn-primary control_bl" value="bl_chart">
					<i class="fas fa-chart-area"></i>
				</button>
				<button type="button" class="btn btn-outline-primary control_bl" value="bl_archive">
					<i class="fas fa-table"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<div class="col-md-12 bl_content" id="bl_chart">
	<div class="row">
		<div class="col-md-12">
			<h5 class="text-primary mb-3">Resumen de ultimo anio</h5>
		</div>
		<div class="col-md-6">
			<div class="card">
				<div class="card-header pb-3 border-0">
					<h4 class="card-title">Consultas</h4>
				</div>
				<div class="card-body p-0 border-0">
					<div id="chart_appointment"></div>
				</div>
				<div class="card-footer">
					<div class="row">
						<div class="col text-center">
							<h5 class="font-weight-normal">1,230</h5>
							<span>Consultas</span>
						</div>
						<div class="col text-center">
							<h5 class="font-weight-normal">320</h5>
							<span>Pacientes</span>
						</div>
						<div class="col text-center">
							<h5 class="font-weight-normal text-nowrap">S/. 33,030.00</h5>
							<span>Ingresos</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card">
				<div class="card-header pb-3 border-0">
					<h4 class="card-title">Ventas</h4>
				</div>
				<div class="card-body p-0 border-0">
					<div id="chart_sales"></div>
				</div>
				<div class="card-footer">
					<div class="row">
						<div class="col text-center">
							<h5 class="font-weight-normal">1,230</h5>
							<span>Ventas</span>
						</div>
						<div class="col text-center">
							<h5 class="font-weight-normal">3,333</h5>
							<span>Productos</span>
						</div>
						<div class="col text-center">
							<h5 class="font-weight-normal text-nowrap">S/. 11,230.00</h5>
							<span>Ingresos</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<div class="card">
				<div class="card-header pb-3 border-0">
					<h4 class="card-title">Ingresos</h4>
				</div>
				<div class="card-body p-0 border-0">
					<div id="chart_income"></div>
				</div>
				<div class="card-footer">
					<div class="row">
						<div class="col text-center">
							<h5 class="font-weight-normal">S/. 1,230.00</h5>
							<span>Medicos</span>
						</div>
						<div class="col text-center">
							<h5 class="font-weight-normal">S/. 3,230.00</h5>
							<span>Ventas</span>
						</div>
						<div class="col text-center">
							<h5><strong>S/. 5,230.00</strong></h5>
							<strong>Total</strong>
						</div>
					</div>
				</div>
			</div>
		</div>	
	</div>
</div>
<div class="col-md-12 bl_content d-none" id="bl_archive">
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