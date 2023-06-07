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
		<div class="card-header pb-0 border-0">
			<h4 class="mb-0"><?= $this->lang->line('title_basic_data') ?></h4>
		</div>
		<div class="card-body">
			<form action="#" class="row" id="form_basic_data">
				<input type="hidden" name="appointment_id" value="<?= $appointment->id ?>">
				<div class="col-md-6">
					<h5><?= $this->lang->line('title_entry') ?></h5>
					<div class="form-row">
						<div class="form-group col-md-12">
							<label><?= $this->lang->line('lb_entry_mode') ?></label>
							<select class="form-control" name="entry_mode_id">
								<option value="">--</option>
								<?php $entry_mode = $options["entry_mode"]; foreach($entry_mode as $item){ 
								if ($bd->entry_mode_id == $item->id) $selected = "selected"; else $selected = ""; ?>
								<option value="<?= $item->id ?>" <?= $selected ?>><?= $item->description ?></option>
								<?php } ?>
							</select>
							<div class="sys_msg" id="bd_entry_mode_msg"></div>
						</div>
						<div class="form-group col-md-6">
							<label><?= $this->lang->line('lb_date') ?></label>
							<input type="text" class="form-control date_picker" value="<?= $bd->date ?>" name="date">
							<div class="sys_msg" id="bd_date_msg"></div>
						</div>
						<div class="form-group col-md-6">
							<label><?= $this->lang->line('lb_hour') ?></label>
							<input type="text" class="form-control time_picker" value="<?= $bd->time ?>" name="time">
							<div class="sys_msg" id="bd_time_msg"></div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<h5><?= $this->lang->line('title_insurance') ?></h5>
					<div class="form-row">
						<div class="form-group col-md-12">
							<label><?= $this->lang->line('lb_insured') ?></label>
							<select class="form-control" name="insurance">
								<?php if ($bd->insurance) $s = "selected"; else $s = ""; ?>
								<option value="1" <?= $s ?>><?= $this->lang->line('txt_yes') ?></option>
								<?php if (!$bd->insurance) $s = "selected"; else $s = ""; ?>
								<option value="" <?= $s ?>><?= $this->lang->line('txt_no') ?></option>
							</select>
							<div class="sys_msg" id="bd_insurance_msg"></div>
						</div>
						<div class="form-group col-md-12">
							<label><?= $this->lang->line('lb_insurance_name') ?></label>
							<input type="text" class="form-control" name="insurance_name" value="<?= $bd->insurance_name ?>">
							<div class="sys_msg" id="bd_insurance_name_msg"></div>
						</div>
					</div>
				</div>
				<div class="col-md-12 pt-3 mb-0">
				<?php if ($appointment->is_editable){ ?>
					<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_save') ?></button>
					<div class="sys_msg" id="bd_result_msg"></div>
				<?php } ?>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="col-md-12">
	<div class="card">
		<div class="card-header pb-0 border-0">
			<h4 class="mb-0"><?= $this->lang->line('title_personal_information') ?></h4>
		</div>
		<div class="card-body">
			<form action="#" class="form-row" id="form_personal_information">
				<input type="hidden" name="appointment_id" value="<?= $appointment->id ?>">
				<div class="form-group col-md-6">
					<label><?= $this->lang->line('lb_name') ?></label>
					<input type="text" class="form-control" name="name" value="<?= $an->name ?>" readonly>
					<div class="sys_msg" id="pi_name_msg"></div>
				</div>
				<div class="form-group col-md-3">
					<label><?= $this->lang->line('lb_age') ?></label>
					<input type="text" class="form-control" name="age" value="<?= $an->age ?>">
					<div class="sys_msg" id="pi_age_msg"></div>
				</div>
				<div class="form-group col-md-3">
					<label><?= $this->lang->line('lb_sex') ?></label>
					<select class="form-control" name="sex_id">
						<option value="">--</option>
						<?php foreach($sex_ops as $item){
						if ($item->id == $an->sex_id) $s = "selected"; else $s = ""; ?>
						<option value="<?= $item->id ?>" <?= $s ?>><?= $item->description ?></option>
						<?php } ?>
					</select>
					<div class="sys_msg" id="pi_sex_msg"></div>
				</div>
				<div class="form-group col-md-6">
					<label><?= $this->lang->line('lb_address') ?></label>
					<input type="text" class="form-control" name="address" value="<?= $an->address ?>">
					<div class="sys_msg" id="pi_address_msg"></div>
				</div>
				<div class="form-group col-md-3">
					<label><?= $this->lang->line('lb_birthplace') ?></label>
					<input type="text" class="form-control" name="birthplace" value="<?= $an->birthplace ?>">
					<div class="sys_msg" id="pi_birthplace_msg"></div>
				</div>
				<div class="form-group col-md-3">
					<label><?= $this->lang->line('lb_birthday') ?></label>
					<input type="text" class="form-control date_picker_all" name="birthday" value="<?= $an->birthday ?>">
					<div class="sys_msg" id="pi_birthday_msg"></div>
				</div>
				<div class="form-group col-md-3">
					<label><?= $this->lang->line('lb_telephone') ?></label>
					<input type="text" class="form-control" name="tel" value="<?= $an->tel ?>">
					<div class="sys_msg" id="pi_tel_msg"></div>
				</div>
				<div class="form-group col-md-3">
					<label><?= $this->lang->line('lb_responsible') ?></label>
					<input type="text" class="form-control" name="responsible" value="<?= $an->responsible ?>">
					<div class="sys_msg" id="pi_responsible_msg"></div>
				</div>
				<div class="form-group col-md-3">
					<label><?= $this->lang->line('lb_place_of_origin') ?></label>
					<input type="text" class="form-control" name="provenance_place" value="<?= $an->provenance_place ?>">
					<div class="sys_msg" id="pi_provenance_place_msg"></div>
				</div>
				<div class="form-group col-md-3">
					<label><?= $this->lang->line('lb_last_trips') ?></label>
					<input type="text" class="form-control" name="last_trips" value="<?= $an->last_trips ?>">
					<div class="sys_msg" id="pi_last_trips_msg"></div>
				</div>
				<div class="form-group col-md-3">
					<label><?= $this->lang->line('lb_race') ?></label>
					<input type="text" class="form-control" name="race" value="<?= $an->race ?>">
					<div class="sys_msg" id="pi_race_msg"></div>
				</div>
				<div class="form-group col-md-3">
					<label><?= $this->lang->line('lb_marital_status') ?></label>
					<select class="form-control" name="civil_status_id">
						<option value="" selected="">--</option>
						<?php $civil_status = $options["civil_status"]; foreach($civil_status as $item){
						if ($item->id == $an->civil_status_id) $selected = "selected"; else $selected = ""; ?>
						<option value="<?= $item->id ?>" <?= $selected ?>><?= $item->description ?></option>
						<?php } ?>
					</select>
					<div class="sys_msg" id="pi_civil_status_msg"></div>
				</div>
				<div class="form-group col-md-3">
					<label><?= $this->lang->line('lb_occupation') ?></label>
					<input type="text" class="form-control" name="occupation" value="<?= $an->occupation ?>">
					<div class="sys_msg" id="pi_occupation_msg"></div>
				</div>
				<div class="form-group col-md-3">
					<label><?= $this->lang->line('lb_religion') ?></label>
					<input type="text" class="form-control" name="religion" value="<?= $an->religion ?>">
					<div class="sys_msg" id="pi_religion_msg"></div>
				</div>
				<?php if ($appointment->is_editable){ ?>
				<div class="form-group col-md-12 pt-3 mb-0">
					<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_save') ?></button>
					<div class="sys_msg" id="pi_result_msg"></div>
				</div>
				<?php } ?>
			</form>
		</div>
	</div>
</div>