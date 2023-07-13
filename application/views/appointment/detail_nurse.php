<?php 
$bd = $appointment_datas["basic_data"]; 
$an = $appointment_datas["anamnesis"];
$ph = $appointment_datas["physical"];
$di = $appointment_datas["diag_impression"];
$re = $appointment_datas["result"];
$ex = $appointment_datas["examination"]; $ex_profiles = $ex["profiles"]; $ex_examinations = $ex["exams"];
$im = $appointment_datas["images"];
$th = $appointment_datas["therapy"];
$me = $appointment_datas["medicine"];
?>
<div class="col-md-12">
	<div class="card">
		<div class="card-header">
			<h4 class="mb-0"><?= $this->lang->line('w_triage') ?></h4>
		</div>
		<div class="card-body">
			<form action="#" class="form-row" id="form_triage">
				<input type="hidden" name="appointment_id" value="<?= $appointment->id ?>">
				<div class="form-group col-md-3">
					<label><?= $this->lang->line('w_pa') ?></label>
					<input type="text" class="form-control" name="v_pa" value="<?= $ph->v_pa ?>">
					<div class="sys_msg" id="tr_v_pa_msg"></div>
				</div>
				<div class="form-group col-md-3">
					<label><?= $this->lang->line('w_fc') ?></label>
					<input type="text" class="form-control" name="v_fc" value="<?= $ph->v_fc ?>">
					<div class="sys_msg" id="tr_v_fc_msg"></div>
				</div>
				<div class="form-group col-md-3">
					<label><?= $this->lang->line('w_fr') ?></label>
					<input type="text" class="form-control" name="v_fr" value="<?= $ph->v_fr ?>">
					<div class="sys_msg" id="tr_v_fr_msg"></div>
				</div>
				<div class="form-group col-md-3">
					<label><?= $this->lang->line('w_temperature') ?></label>
					<input type="text" class="form-control" name="v_temperature" value="<?= $ph->v_temperature ?>">
					<div class="sys_msg" id="tr_v_temperature_msg"></div>
				</div>
				<div class="form-group col-md-3">
					<label><?= $this->lang->line('w_weight') ?></label>
					<input type="text" class="form-control set_bmi" name="v_weight" value="<?= $ph->v_weight ?>">
					<div class="sys_msg" id="tr_v_weight_msg"></div>
				</div>
				<div class="form-group col-md-3">
					<label><?= $this->lang->line('w_height') ?></label>
					<input type="text" class="form-control set_bmi" name="v_height" value="<?= $ph->v_height ?>">
					<div class="sys_msg" id="tr_v_height_msg"></div>
				</div>
				<div class="form-group col-md-3">
					<label><?= $this->lang->line('w_bmi') ?></label>
					<input type="text" class="form-control" name="v_imc" value="<?= $ph->v_imc ?>" readonly>
					<div class="sys_msg" id="tr_v_imc_msg"></div>
				</div>
				<div class="form-group col-md-3">
					<label><?= $this->lang->line('w_bmi_class') ?></label>
					<input type="text" class="form-control" name="v_imc_class" value="<?= $ph->v_imc_class ?>" readonly>
					<div class="sys_msg" id="tr_v_imc_class"></div>
				</div>
				<?php if ($appointment->is_editable){ ?>
				<div class="form-group col-md-12 pt-3 mb-0">
					<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_save') ?></button>
					<div class="sys_msg" id="tr_result_msg"></div>
				</div>
				<?php } ?>
			</form>
		</div>
	</div>
</div>
