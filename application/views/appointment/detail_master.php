<?php 
$bd = $appointment_datas["basic_data"]; 
$an = $appointment_datas["anamnesis"];
$ph = $appointment_datas["physical"];
$di = $appointment_datas["diag_impression"];
$re = $appointment_datas["result"];
$ex = $appointment_datas["examination"]; $ex_profiles = $ex["profiles"]; $ex_examinations = $ex["examinations"];
$im = $appointment_datas["images"]; $images_ap = $im["images"]; $checked_images = $im["checked_images"];
$th = $appointment_datas["therapy"];
$me = $appointment_datas["medicine"];
?>
<div class="col-md-12">
	<div class="card">
		<div class="card-body text-center">
			<div class="row">
				<div class="col-md-4">
					<button type="button" class="btn btn-primary btn-lg btn-block my-1 d-flex justify-content-between align-items-center btn_process" value="process_information">
						<i class="fas fa-info-circle fa-2x fa-fw mr-3"></i>
						<span class="fs-20"><?= $this->lang->line('btn_information') ?></span>
					</button>
				</div>
				<div class="col-md-4">
					<button type="button" class="btn btn-outline-primary btn-lg btn-block my-1 d-flex justify-content-between align-items-center btn_process" value="process_triage">
						<i class="fas fa-weight fa-2x fa-fw mr-3"></i>
						<span class="fs-20"><?= $this->lang->line('btn_triage') ?></span>
					</button>
				</div>
				<div class="col-md-4">
					<button type="button" class="btn btn-outline-primary btn-lg btn-block my-1 d-flex justify-content-between align-items-center btn_process" value="process_attention">
						<i class="fas fa-laptop-medical fa-2x fa-fw mr-3"></i>
						<span class="fs-20"><?= $this->lang->line('btn_attention') ?></span>
					</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="col-md-12 process process_information">
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
							<select class="form-control" name="entry_mode">
								<option value=""><?= $this->lang->line('txt_select') ?>...</option>
								<?php $entry_mode = $options["entry_mode"]; foreach($entry_mode as $item){ 
								if ($bd->entry_mode == $item->id) $selected = "selected"; else $selected = ""; ?>
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
								<option name=""><?= $this->lang->line('txt_select') ?>...</option>
								<?php if (!strcmp($bd->insurance, "y")) $selected = "selected"; else $selected = ""; ?>
								<option value="y" <?= $selected ?>><?= $this->lang->line('txt_yes') ?></option>
								<?php if (!strcmp($bd->insurance, "n")) $selected = "selected"; else $selected = ""; ?>
								<option value="n" <?= $selected ?>><?= $this->lang->line('txt_no') ?></option>
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
<div class="col-md-12 process process_information">
	<div class="card">
		<div class="card-header pb-0 border-0">
			<h4 class="mb-0"><?= $this->lang->line('title_personal_information') ?></h4>
		</div>
		<div class="card-body">
			<form action="#" class="form-row" id="form_personal_information">
				<input type="hidden" name="appointment_id" value="<?= $appointment->id ?>">
				<div class="form-group col-md-6">
					<label><?= $this->lang->line('lb_name') ?></label>
					<input type="text" class="form-control bg-light" name="name" value="<?= $an->name ?>" readonly>
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
					<select class="form-control" name="civil_status">
						<option value="" selected="">--</option>
						<?php $civil_status = $options["civil_status"]; foreach($civil_status as $item){
						if ($item->id == $an->civil_status) $selected = "selected"; else $selected = ""; ?>
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
<div class="col-md-12 process process_triage d-none">
	<div class="card">
		<div class="card-header pb-0 border-0">
			<h4 class="mb-0"><?= $this->lang->line('title_triage') ?></h4>
		</div>
		<div class="card-body">
			<form action="#" class="form-row" id="form_triage">
				<input type="hidden" name="appointment_id" value="<?= $appointment->id ?>">
				<div class="form-group col-md-3">
					<label><?= $this->lang->line('lb_pa') ?></label>
					<input type="text" class="form-control" name="v_pa" value="<?= $ph->v_pa ?>">
					<div class="sys_msg" id="tr_v_pa_msg"></div>
				</div>
				<div class="form-group col-md-3">
					<label><?= $this->lang->line('lb_fc') ?></label>
					<input type="text" class="form-control" name="v_fc" value="<?= $ph->v_fc ?>">
					<div class="sys_msg" id="tr_v_fc_msg"></div>
				</div>
				<div class="form-group col-md-3">
					<label><?= $this->lang->line('lb_fr') ?></label>
					<input type="text" class="form-control" name="v_fr" value="<?= $ph->v_fr ?>">
					<div class="sys_msg" id="tr_v_fr_msg"></div>
				</div>
				<div class="form-group col-md-3">
					<label><?= $this->lang->line('lb_temperature') ?></label>
					<input type="text" class="form-control" name="v_temperature" value="<?= $ph->v_temperature ?>">
					<div class="sys_msg" id="tr_v_temperature_msg"></div>
				</div>
				<div class="form-group col-md-3">
					<label><?= $this->lang->line('lb_weight') ?></label>
					<input type="text" class="form-control set_bmi" name="v_weight" value="<?= $ph->v_weight ?>">
					<div class="sys_msg" id="tr_v_weight_msg"></div>
				</div>
				<div class="form-group col-md-3">
					<label><?= $this->lang->line('lb_height') ?></label>
					<input type="text" class="form-control set_bmi" name="v_height" value="<?= $ph->v_height ?>">
					<div class="sys_msg" id="tr_v_height_msg"></div>
				</div>
				<div class="form-group col-md-3">
					<label><?= $this->lang->line('lb_bmi') ?></label>
					<input type="text" class="form-control bg-light" name="v_imc" value="<?= $ph->v_imc ?>" readonly>
					<div class="sys_msg" id="tr_v_imc_msg"></div>
				</div>
				<div class="form-group col-md-3">
					<label><?= $this->lang->line('lb_bmi_class') ?></label>
					<input type="text" class="form-control bg-light" name="v_imc_class" value="<?= $ph->v_imc_class ?>" readonly>
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
<div class="col-md-6 process process_attention d-none">
	<div class="card">
		<div class="card-header pb-0 border-0">
			<h4 class="mb-0"><?= $this->lang->line('title_clinical_histories') ?></h4>
		</div>
		<div class="card-body ap_content_list">
			<?php if ($histories){ ?>
			<table class="table mb-0">
				<thead>
					<tr>
						<th class="pt-0 pl-0"><?= $this->lang->line('hd_schedule') ?></th>
						<th class="pt-0"><?= $this->lang->line('th_type') ?></th>
						<th class="pt-0"><?= $this->lang->line('th_specialty') ?></th>
						<th class="pt-0 pr-0"></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($histories as $item){ ?>
					<tr class="text-left">
						<td class="pl-0"><?= date("d.m.Y<\b\\r>H:i a", strtotime($item->schedule_from)) ?></td>
						<td><?= $item->type ?></td>
						<td><?= $item->specialty ?></td>
						<td class="text-right pr-0">
							<a href="<?= base_url().$item->link_to."/detail/".$item->id ?>" target="_blank">
								<i class="fas fa-search"></i>
							</a>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<?php }else{ ?>
			<div class="text-muted text-center"><?= $this->lang->line('txt_no_records') ?></div>
			<?php } ?>
		</div>
	</div>
</div>
<div class="col-md-6 process process_attention d-none">
	<div class="card">
		<div class="card-header pb-0 border-0">
			<h4 class="mb-0"><?= $this->lang->line('title_files') ?></h4>
		</div>
		<div class="card-body ap_content_list">
			<?php if ($histories){ ?>
			<table class="table mb-0">
				<thead>
					<tr>
						<th class="pt-0 pl-0"><?= $this->lang->line('th_date') ?></th>
						<th class="pt-0"><?= $this->lang->line('th_title') ?></th>
						<th class="pt-0 pr-0"></th>
					</tr>
				</thead>
				<tbody>
					<?php $file_path = base_url()."uploaded/patient_files/".$patient->doc_type_id."_".$patient->doc_number."/";
					foreach($patient_files as $item){ ?>
					<tr>
						<td class="pl-0"><?= date("d.m.Y<\b\\r>H:i:s", strtotime($item->registed_at)) ?></td>
						<td><?= $item->title ?></td>
						<td class="pr-0">
							<a href="<?= $file_path.$item->filename ?>" target="_blank">
								<i class="fas fa-search"></i>
							</a>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<?php }else{ ?>
			<div class="text-muted text-center"><?= $this->lang->line('txt_no_records') ?></div>
			<?php } ?>
		</div>
	</div>
</div>
<div class="col-md-12 process process_attention d-none">
	<div class="card">
		<div class="card-header pb-0 border-0">
			<h4 class="mb-0"><?= $this->lang->line('title_anamnesis') ?></h4>
		</div>
		<div class="card-body">
			<form action="#" class="row" id="form_anamnesis">
				<input type="hidden" name="appointment_id" value="<?= $appointment->id ?>">
				<div class="col-md-12">
					<div class="default-tab">
						<ul class="nav nav-tabs mb-4">
							<li class="nav-item">
								<a href="#anamnesis-1" class="nav-link active" data-toggle="tab">
									<?= $this->lang->line('title_personal_information') ?>
								</a>
							</li>
							<li class="nav-item">
								<a href="#anamnesis-2" class="nav-link" data-toggle="tab">
									<?= $this->lang->line('title_current_illness') ?>
								</a>
							</li>
							<li class="nav-item">
								<a href="#anamnesis-3" class="nav-link" data-toggle="tab">
									<?= $this->lang->line('title_biological_functions') ?>
								</a>
							</li>
							<li class="nav-item">
								<a href="#anamnesis-4" class="nav-link" data-toggle="tab">
									<?= $this->lang->line('title_personal_background') ?>
								</a>
							</li>
						</ul>
						<div class="tab-content">
							<div id="anamnesis-1" class="tab-pane active">
								<div class="form-row">
									<div class="form-group col-md-6">
										<label><?= $this->lang->line('lb_name') ?></label>
										<input type="text" class="form-control bg-light" name="name" value="<?= $an->name ?>" readonly>
										<div class="sys_msg" id="an_name_msg"></div>
									</div>
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_age') ?></label>
										<input type="text" class="form-control" name="age" value="<?= $an->age ?>">
										<div class="sys_msg" id="an_age_msg"></div>
									</div>
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_sex') ?></label>
										<select class="form-control" name="sex">
											<option value=""><?= $this->lang->line('txt_select') ?>...</option>
											<?php if (!strcmp("M", $an->sex)) $selected = "selected"; else $selected = ""; ?>
											<option value="M" <?= $selected ?>><?= $this->lang->line('txt_male') ?></option>
											<?php if (!strcmp("F", $an->sex)) $selected = "selected"; else $selected = ""; ?>
											<option value="F" <?= $selected ?>><?= $this->lang->line('txt_female') ?></option>
										</select>
										<div class="sys_msg" id="an_sex_msg"></div>
									</div>
									<div class="form-group col-md-6">
										<label><?= $this->lang->line('lb_address') ?></label>
										<input type="text" class="form-control" name="address" value="<?= $an->address ?>">
										<div class="sys_msg" id="an_address_msg"></div>
									</div>
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_birthplace') ?></label>
										<input type="text" class="form-control" name="birthplace" value="<?= $an->birthplace ?>">
										<div class="sys_msg" id="an_birthplace_msg"></div>
									</div>
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_birthday') ?></label>
										<input type="text" class="form-control date_picker" name="birthday" value="<?= $an->birthday ?>">
										<div class="sys_msg" id="an_birthday_msg"></div>
									</div>
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_telephone') ?></label>
										<input type="text" class="form-control" name="tel" value="<?= $an->tel ?>">
										<div class="sys_msg" id="an_tel_msg"></div>
									</div>
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_responsible') ?></label>
										<input type="text" class="form-control" name="responsible" value="<?= $an->responsible ?>">
										<div class="sys_msg" id="an_responsible_msg"></div>
									</div>
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_place_of_origin') ?></label>
										<input type="text" class="form-control" name="provenance_place" value="<?= $an->provenance_place ?>">
										<div class="sys_msg" id="an_provenance_place_msg"></div>
									</div>
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_last_trips') ?></label>
										<input type="text" class="form-control" name="last_trips" value="<?= $an->last_trips ?>">
										<div class="sys_msg" id="an_last_trips_msg"></div>
									</div>
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_race') ?></label>
										<input type="text" class="form-control" name="race" value="<?= $an->race ?>">
										<div class="sys_msg" id="an_race_msg"></div>
									</div>
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_marital_status') ?></label>
										<select class="form-control" name="civil_status">
											<option value="" selected=""><?= $this->lang->line('txt_select') ?>...</option>
											<?php $civil_status = $options["civil_status"]; foreach($civil_status as $item){
											if ($item->id == $an->civil_status) $selected = "selected"; else $selected = ""; ?>
											<option value="<?= $item->id ?>" <?= $selected ?>><?= $item->description ?></option>
											<?php } ?>
										</select>
										<div class="sys_msg" id="an_civil_status_msg"></div>
									</div>
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_occupation') ?></label>
										<input type="text" class="form-control" name="occupation" value="<?= $an->occupation ?>">
										<div class="sys_msg" id="an_occupation_msg"></div>
									</div>
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_religion') ?></label>
										<input type="text" class="form-control" name="religion" value="<?= $an->religion ?>">
										<div class="sys_msg" id="an_religion_msg"></div>
									</div>
								</div>
							</div>
							<div id="anamnesis-2" class="tab-pane">
								<div class="form-row">
									<div class="form-group col-md-4">
										<label><?= $this->lang->line('lb_illness_time') ?></label>
										<input type="text" class="form-control" name="illness_time" value="<?= $an->illness_time ?>">
										<div class="sys_msg" id="an_illness_time_msg"></div>
									</div>
									<div class="form-group col-md-4">
										<label><?= $this->lang->line('lb_start') ?></label>
										<input type="text" class="form-control" name="illness_start" value="<?= $an->illness_start ?>">
										<div class="sys_msg" id="an_illness_start_msg"></div>
									</div>
									<div class="form-group col-md-4">
										<label><?= $this->lang->line('lb_grade') ?></label>
										<input type="text" class="form-control" name="illness_course" value="<?= $an->illness_course ?>">
										<div class="sys_msg" id="an_illness_course_msg"></div>
									</div>
									<div class="form-group col-md-6">
										<label><?= $this->lang->line('lb_main_symptoms') ?></label>
										<textarea class="form-control" rows="5" name="illness_main_symptoms"><?= $an->illness_main_symptoms ?></textarea>
										<div class="sys_msg" id="an_illness_main_symptoms_msg"></div>
									</div>
									<div class="form-group col-md-6">
										<label><?= $this->lang->line('lb_story') ?></label>
										<textarea class="form-control" rows="5" name="illness_story"><?= $an->illness_story ?></textarea>
										<div class="sys_msg" id="an_illness_story_msg"></div>
									</div>
								</div>
							</div>
							<div id="anamnesis-3" class="tab-pane">
								<div class="form-row">
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_appetite') ?></label>
										<input type="text" class="form-control" name="func_bio_appetite" value="<?= $an->func_bio_appetite ?>">
										<div class="sys_msg" id="an_func_bio_appetite_msg"></div>
									</div>
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_urine') ?></label>
										<input type="text" class="form-control" name="func_bio_urine" value="<?= $an->func_bio_urine ?>">
										<div class="sys_msg" id="an_func_bio_urine_msg"></div>
									</div>
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_thirst') ?></label>
										<input type="text" class="form-control" name="func_bio_thirst" value="<?= $an->func_bio_thirst ?>">
										<div class="sys_msg" id="an_func_bio_thirst_msg"></div>
									</div>
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_bowel_movements') ?></label>
										<input type="text" class="form-control" name="func_bio_bowel_movements" value="<?= $an->illness_course ?>">
										<div class="sys_msg" id="an_func_bio_bowel_movements_msg"></div>
									</div>
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_sweat') ?></label>
										<input type="text" class="form-control" name="func_bio_sweat" value="<?= $an->func_bio_sweat ?>">
										<div class="sys_msg" id="an_func_bio_sweat_msg"></div>
									</div>
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_weight_') ?></label>
										<input type="text" class="form-control" name="func_bio_weight" value="<?= $an->func_bio_weight ?>">
										<div class="sys_msg" id="an_func_bio_weight_msg"></div>
									</div>
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_sleep') ?></label>
										<input type="text" class="form-control" name="func_bio_sleep" value="<?= $an->func_bio_sleep ?>">
										<div class="sys_msg" id="an_func_bio_sleep_msg"></div>
									</div>
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_encouragement') ?></label>
										<input type="text" class="form-control" name="func_bio_encouragement" value="<?= $an->func_bio_encouragement ?>">
										<div class="sys_msg" id="an_func_bio_encouragement_msg"></div>
									</div>
								</div>
							</div>
							<div id="anamnesis-4" class="tab-pane">
								<h5 class="font-weight-normal"><?= $this->lang->line('title_pathological') ?></h5>
								<div class="form-row">
									<div class="form-group col-md-12">
										<label><?= $this->lang->line('lb_previous_illnesses') ?></label>
										<div class="form-row">
											<?php foreach($pre_illnesses as $item){ if (in_array($item["value"], $an->patho_pre_illnesses)) $checked = "checked"; else $checked = "";?>
											<div class="form-group col-md-3">
												<div class="custom-control custom-checkbox checkbox-primary">
													<input type="checkbox" class="custom-control-input" id="<?= $item["id"] ?>" name="patho_pre_illnesses[]" value="<?= $item["value"] ?>" <?= $checked ?>>
													<label class="custom-control-label" for="<?= $item["id"] ?>"><?= $item["value"] ?></label>
												</div>
											</div>
											<?php } ?>
											<div class="form-group col-md-12">
												<input type="text" class="form-control" name="patho_pre_illnesses_other" id="other_pre_illnesses" placeholder="<?= $this->lang->line('msg_other_illness') ?>" value="<?= $an->patho_pre_illnesses_other ?>">
												<div class="sys_msg" id="an_patho_pre_illnesses_msg"></div>
											</div>
										</div>
									</div>
									<div class="form-group col-md-6">
										<label><?= $this->lang->line('lb_previous_hospitalizations') ?></label>
										<textarea class="form-control" rows="3" name="patho_pre_hospitalization"><?= $an->patho_pre_hospitalization ?></textarea>
										<div class="sys_msg" id="an_patho_pre_hospitalization_msg"></div>
									</div>
									<div class="form-group col-md-6">
										<label><?= $this->lang->line('lb_previous_surgeries') ?></label>
										<textarea class="form-control" rows="3" name="patho_pre_surgery"><?= $an->patho_pre_surgery ?></textarea>
										<div class="sys_msg" id="an_patho_pre_surgery_msg"></div>
									</div>
									<div class="form-group col-md-6">
										<label><?= $this->lang->line('lb_ram') ?></label>
										<input type="text" class="form-control" name="patho_ram" value="<?= $an->patho_ram ?>">
										<div class="sys_msg" id="an_patho_ram_msg"></div>
									</div>
									<div class="form-group col-md-6">
										<label><?= $this->lang->line('lb_transfusions') ?></label>
										<input type="text" class="form-control" name="patho_transfusion" value="<?= $an->patho_transfusion ?>">
										<div class="sys_msg" id="an_patho_transfusion_msg"></div>
									</div>
									<div class="form-group col-md-12">
										<label><?= $this->lang->line('lb_prior_medication') ?></label>
										<input type="text" class="form-control" name="patho_pre_medication" value="<?= $an->patho_pre_medication ?>">
										<div class="sys_msg" id="an_patho_pre_medication_msg"></div>
									</div>
								</div>
								<h5 class="font-weight-normal mt-3"><?= $this->lang->line('title_gynecological') ?></h5>
								<div class="form-row">
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_fur') ?></label>
										<input type="text" class="form-control" name="gyne_fur" value="<?= $an->gyne_fur ?>">
										<div class="sys_msg" id="an_gyne_fur_msg"></div>
									</div>
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_g') ?></label>
										<input type="text" class="form-control" name="gyne_g" value="<?= $an->gyne_g ?>">
										<div class="sys_msg" id="an_gyne_g_msg"></div>
									</div>
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_p') ?></label>
										<input type="text" class="form-control" name="gyne_p" value="<?= $an->gyne_p ?>">
										<div class="sys_msg" id="an_gyne_p_msg"></div>
									</div>
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_mac') ?></label>
										<input type="text" class="form-control" name="gyne_mac" value="<?= $an->gyne_mac ?>">
										<div class="sys_msg" id="an_gyne_mac_msg"></div>
									</div>
								</div>
								<h5 class="font-weight-normal mt-3"><?= $this->lang->line('title_family_background') ?></h5>
								<div class="form-row">
									<div class="form-group col-md-12">
										<textarea class="form-control" rows="3" name="family_history"><?= $an->family_history ?></textarea>
										<div class="sys_msg" id="an_family_history_msg"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php if ($appointment->is_editable){ ?>
					<div class="pt-3">
						<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_save') ?></button>
						<div class="sys_msg" id="an_result_msg"></div>
					</div>
					<?php } ?>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="col-md-12 process process_attention d-none">
	<div class="card">
		<div class="card-header pb-0 border-0">
			<h4 class="mb-0"><?= $this->lang->line('title_physical_exam') ?></h4>
		</div>
		<div class="card-body">
			<form action="#" id="form_physical_exam" class="row">
				<input type="hidden" name="appointment_id" value="<?= $appointment->id ?>">
				<div class="col-md-12">
					<div class="default-tab">
						<ul class="nav nav-tabs mb-4">
							<li class="nav-item">
								<a href="#physical_exam-1" class="nav-link active" data-toggle="tab">
									<?= $this->lang->line('title_vital_functions') ?>
								</a>
							</li>
							<li class="nav-item">
								<a href="#physical_exam-2" class="nav-link" data-toggle="tab">
									<?= $this->lang->line('title_general_exam') ?>
								</a>
							</li>
							<li class="nav-item">
								<a href="#physical_exam-3" class="nav-link" data-toggle="tab">
									<?= $this->lang->line('title_regional_examination') ?>
								</a>
							</li>
						</ul>
						<div class="tab-content">
							<div id="physical_exam-1" class="tab-pane active">
								<div class="form-row">
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_pa') ?></label>
										<input type="text" class="form-control" name="v_pa" value="<?= $ph->v_pa ?>">
										<div class="sys_msg" id="pe_v_pa_msg"></div>
									</div>
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_fc') ?></label>
										<input type="text" class="form-control" name="v_fc" value="<?= $ph->v_fc ?>">
										<div class="sys_msg" id="pe_v_fc_msg"></div>
									</div>
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_fr') ?></label>
										<input type="text" class="form-control" name="v_fr" value="<?= $ph->v_fr ?>">
										<div class="sys_msg" id="pe_v_fr_msg"></div>
									</div>
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_temperature') ?></label>
										<input type="text" class="form-control" name="v_temperature" value="<?= $ph->v_temperature ?>">
										<div class="sys_msg" id="pe_v_temperature_msg"></div>
									</div>
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_weight') ?></label>
										<input type="text" class="form-control set_bmi" name="v_weight" value="<?= $ph->v_weight ?>">
										<div class="sys_msg" id="pe_v_weight_msg"></div>
									</div>
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_height') ?></label>
										<input type="text" class="form-control set_bmi" name="v_height" value="<?= $ph->v_height ?>">
										<div class="sys_msg" id="pe_v_height_msg"></div>
									</div>
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_bmi') ?></label>
										<input type="text" class="form-control bg-light" name="v_imc" value="<?= $ph->v_imc ?>" readonly>
										<div class="sys_msg" id="pe_v_imc_msg"></div>
									</div>
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_bmi_class') ?></label>
										<input type="text" class="form-control bg-light" name="v_imc_class" value="<?= $ph->v_imc_class ?>" readonly>
										<div class="sys_msg" id="pe_v_imc_class"></div>
									</div>
								</div>
							</div>
							<div id="physical_exam-2" class="tab-pane">
								<div class="form-row">
									<div class="form-group col-md-6">
										<label><?= $this->lang->line('lb_appearance') ?></label>
										<textarea class="form-control" rows="3" name="g_appearance"><?= $ph->g_appearance ?></textarea>
										<div class="sys_msg" id="pe_g_appearance_msg"></div>
									</div>
									<div class="form-group col-md-6">
										<label><?= $this->lang->line('lb_skin') ?></label>
										<textarea class="form-control" rows="3" name="g_skin"><?= $ph->g_skin ?></textarea>
										<div class="sys_msg" id="pe_g_skin_msg"></div>
									</div>
									<div class="form-group col-md-4">
										<label><?= $this->lang->line('lb_tcsc') ?></label>
										<textarea class="form-control" rows="3" name="g_tcsc"><?= $ph->g_tcsc ?></textarea>
										<div class="sys_msg" id="pe_g_tcsc_msg"></div>
									</div>
									<div class="form-group col-md-4">
										<label><?= $this->lang->line('lb_soma') ?></label>
										<textarea class="form-control" rows="3" name="g_soma"><?= $ph->g_soma ?></textarea>
										<div class="sys_msg" id="pe_g_soma_msg"></div>
									</div>
									<div class="form-group col-md-4">
										<label><?= $this->lang->line('lb_lymphatic') ?></label>
										<textarea class="form-control" rows="3" name="g_lymphatic"><?= $ph->g_lymphatic ?></textarea>
										<div class="sys_msg" id="pe_g_lymphatic_msg"></div>
									</div>
								</div>
							</div>
							<div id="physical_exam-3" class="tab-pane">
								<div class="form-row">
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_head') ?></label>
										<textarea class="form-control" rows="3" name="r_head"><?= $ph->r_head ?></textarea>
										<div class="sys_msg" id="pe_r_head_msg"></div>
									</div>
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_neck') ?></label>
										<textarea class="form-control" rows="3" name="r_neck"><?= $ph->r_neck ?></textarea>
										<div class="sys_msg" id="pe_r_neck_msg"></div>
									</div>
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_breasts') ?></label>
										<textarea class="form-control" rows="3" name="r_breasts"><?= $ph->r_breasts ?></textarea>
										<div class="sys_msg" id="pe_r_breasts_msg"></div>
									</div>
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_chest_and_lungs') ?></label>
										<textarea class="form-control" rows="3" name="r_thorax_lungs"><?= $ph->r_thorax_lungs ?></textarea>
										<div class="sys_msg" id="pe_r_thorax_lungs_msg"></div>
									</div>
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_cardiovascular') ?></label>
										<textarea class="form-control" rows="3" name="r_cardiovascular"><?= $ph->r_cardiovascular ?></textarea>
										<div class="sys_msg" id="pe_r_cardiovascular_msg"></div>
									</div>
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_abdomen') ?></label>
										<textarea class="form-control" rows="3" name="r_abdomen"><?= $ph->r_abdomen ?></textarea>
										<div class="sys_msg" id="pe_r_abdomen_msg"></div>
									</div>
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_genitourinary') ?></label>
										<textarea class="form-control" rows="3" name="r_genitourinary"><?= $ph->r_genitourinary ?></textarea>
										<div class="sys_msg" id="pe_r_genitourinary_msg"></div>
									</div>
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_neurological') ?></label>
										<textarea class="form-control" rows="3" name="r_neurologic"><?= $ph->r_neurologic ?></textarea>
										<div class="sys_msg" id="pe_r_neurologic_msg"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php if ($appointment->is_editable){ ?>
				<div class="col-md-12 pt-3">
					<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_save') ?></button>
					<div class="sys_msg" id="pe_result_msg"></div>
				</div>
				<?php } ?>
			</form>
		</div>
	</div>
</div>
<div class="col-md-12 process process_attention d-none">
	<div class="card">
		<div class="card-header border-0">
			<h4 class="mb-0"><?= $this->lang->line('title_diagnostic_impression') ?></h4>
		</div>
		<div class="card-body pt-0">
			<div class="row">
				<?php if ($appointment->is_editable){ $next_col = 6; ?>
				<div class="col-md-6">
					<form action="#" class="form-row" id="form_search_diag">
						<div class="form-group col-md-12">
							<h5><?= $this->lang->line('lb_search') ?></h5>
							<div class="input-group">
								<input type="text" class="form-control" name="filter">
								<div class="input-group-append">
									<button class="btn btn-primary border-0" type="submit">
										<i class="fas fa-search"></i>
									</button>
								</div>
							</div>
							<div class="sys_msg" id="di_diagnosis_msg"><span class="text-info"><?= $this->lang->line('txt_enter_filter') ?></span></div>
						</div>	
					</form>	
					<div class="ap_content_list_high">
						<table class="table table-xs no_border_tb mb-0">
							<tbody id="search_diag_result"></tbody>
						</table>
					</div>
				</div>
				<?php }else $next_col = 12; ?>
				<div class="col-md-<?= $next_col ?>">
					<?php if ($appointment->is_editable){ ?>
					<h5><?= $this->lang->line('title_selected_diag') ?></h5>
					<?php } ?>
					<table class="table table-xs no_border_tb mb-0">
						<tbody id="selected_diags">
							<?php foreach($di as $d){ ?>
							<tr class="text-left">
								<td class="align-top" style="width:120px;"><?= $d->code ?></td>
								<td><?= $d->description ?></td>
								<?php if ($appointment->is_editable){ ?>
								<td class="text-right">
									<button type="button" class="btn tp-btn-light btn-danger p-0 btn_delete_diag" value="<?= $d->id ?>"><i class="fas fa-minus"></i></button>
								</td>
								<?php } ?>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="col-md-12 process process_attention d-none">
	<div class="card">
		<div class="card-header pb-0 border-0">
			<h4 class="mb-0"><?= $this->lang->line('title_result') ?></h4>
		</div>
		<div class="card-body">
			<form action="#" class="form-row" id="form_result">
				<input type="hidden" name="appointment_id" value="<?= $appointment->id ?>">
				<div class="form-group col-md-4">
					<label><?= $this->lang->line('lb_diagnosis') ?></label>
					<textarea class="form-control" rows="3" name="diagnosis"><?= $re->diagnosis ?></textarea>
				</div>
				<div class="form-group col-md-4">
					<label><?= $this->lang->line('lb_workplan') ?></label>
					<textarea class="form-control" rows="3" name="plan"><?= $re->plan ?></textarea>
				</div>
				<div class="form-group col-md-4">
					<label><?= $this->lang->line('lb_treatment') ?></label>
					<textarea class="form-control" rows="3" name="treatment"><?= $re->treatment ?></textarea>
				</div>
				<?php if ($appointment->is_editable){ ?>
				<div class="form-group col-md-12 pt-3 mb-0">
					<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_save') ?></button>
					<div class="sys_msg" id="rs_result_msg"></div>
				</div>
				<?php } ?>
			</form>
		</div>
	</div>
</div>
<div class="col-md-12 process process_attention d-none">
	<div class="card">
		<div class="card-header pb-0 border-0">
			<h4 class="mb-0"><?= $this->lang->line('title_auxiliary_exam') ?></h4>
		</div>
		<div class="card-body">
			<div class="default-tab">
				<ul class="nav nav-tabs mb-4">
					<li class="nav-item">
						<a href="#auxiliary_exams-1" class="nav-link active" data-toggle="tab">
							<?= $this->lang->line('title_laboratory') ?>
						</a>
					</li>
					<li class="nav-item">
						<a href="#auxiliary_exams-2" class="nav-link" data-toggle="tab">
							<?= $this->lang->line('title_image') ?>
						</a>
					</li>
				</ul>
				<div class="tab-content">
					<div id="auxiliary_exams-1" class="tab-pane active">
						<div class="row">
							<?php if ($appointment->is_editable){ $next_col = 6; ?>
							<div class="col-md-6">
								<h5><?= $this->lang->line('title_search') ?></h5>
								<div class="form-row">
									<div class="form-group col-md-4">
										<label><?= $this->lang->line('lb_category') ?></label>
										<select class="form-control" id="ex_category">
											<option value=""><?= $this->lang->line('txt_all') ?></option>
											<?php foreach($exam_categories as $item){ ?>
											<option value="<?= $item->id ?>"><?= $item->name ?></option>
											<?php } ?>
										</select>
									</div>
									<div class="form-group col-md-8">
										<label><?= $this->lang->line('lb_filter') ?></label>
										<input type="text" class="form-control" id="ex_search">
									</div>
								</div>
								<div class="form-row ap_content_list_high" id="list_exams">
									<div class="form-group col-md-12 mt-3">
										<h5><?= $this->lang->line('title_profiles') ?></h5>
									</div>
									<?php foreach($exam_profiles as $item){
									$class = "exam_category_".implode(" exam_category_", $item->categories);
									if (in_array($item->id, $ex["checked_profs"])) $checked = "checked"; else $checked = ""; ?>
									<div class="form-group col-md-12 examination_profiles <?= $class ?>">
										<div class="search_filter d-none"><?= $item->name." ".$item->exams ?></div>
										<div class="custom-control custom-checkbox checkbox-primary mr-3 mb-1">
											<input type="checkbox" class="custom-control-input chk_exam_profile" id="exam_profile_<?= $item->id ?>" value="<?= $item->id ?>" <?= $checked ?>>
											<label class="custom-control-label" for="exam_profile_<?= $item->id ?>">
												<?= $item->name ?> (<?= count($item->examination_ids) ?> <?= $this->lang->line('txt_exams_l') ?>)<br/>
												<small><?= $item->exams ?></small>
											</label>
										</div>
									</div>
									<?php } ?>
									<div class="form-group col-md-12 text-center d-none" id="exam_profile_no_result"><?= $this->lang->line('msg_no_result') ?></div>
									<div class="form-group col-md-12 mt-3">
										<h5><?= $this->lang->line('title_individual_exams') ?></h5>
									</div>
									<?php foreach($examinations as $item){
									if (in_array($item->id, $ex["checked_exams"])) $checked = "checked"; else $checked = ""; ?>
									<div class="form-group col-md-12 examinations exam_category_<?= $item->category_id ?>">
										<div class="search_filter d-none"><?= $item->name ?></div>
										<div class="custom-control custom-checkbox checkbox-primary mr-3 mb-1">
											<input type="checkbox" class="custom-control-input chk_exam" id="exam_<?= $item->id ?>" value="<?= $item->id ?>" <?= $checked ?>>
											<label class="custom-control-label" for="exam_<?= $item->id ?>"><?= $item->name ?></label>
										</div>
									</div>
									<?php } ?>
									<div class="form-group col-md-12 text-center d-none" id="exam_no_result"><?= $this->lang->line('msg_no_result') ?></div>
								</div>
							</div>
							<?php }else $next_col = 12; ?>
							<div class="col-md-<?= $next_col ?>">
								<?php if ($appointment->is_editable){ ?>
								<h5 class="mb-3"><?= $this->lang->line('title_selected_exams') ?></h5>
								<?php } ?>
								<table class="table table-xs no_border_tb mb-0">
									<tbody id="selected_exams">
										<?php foreach($ex_profiles as $ep){ ?>
										<tr class="text-left">
											<td class="align-top" style="width:120px;"><?= $ep->type ?></td>
											<td>
												<div><?= $ep->name ?></div>
												<div><small><?= $ep->exams ?></small></div>
											</td>
											<?php if ($appointment->is_editable){ ?>
											<td class="align-top" class="text-right">
												<button type="button" class="btn tp-btn-light btn-danger p-0 btn_delete_exam_profile" value="<?= $ep->id ?>">
													<i class="fas fa-minus"></i>
												</button>
											</td>
											<?php } ?>
										</tr>
										<?php } foreach($ex_examinations as $ee){ ?>
										<tr class="text-left">
											<td style="width:120px;"><?= $ee->type ?></td>
											<td><?= $ee->name ?></td>
											<?php if ($appointment->is_editable){ ?>
											<td class="text-right">
												<button type="button" class="btn tp-btn-light btn-danger p-0 btn_delete_exam" value="<?= $ee->id ?>">
													<i class="fas fa-minus"></i>
												</button>
											</td>
											<?php } ?>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div id="auxiliary_exams-2" class="tab-pane">
						<div class="row">
							<?php if ($appointment->is_editable){ $next_col = 6; ?>
							<div class="col-md-6">
								<h5><?= $this->lang->line('title_search') ?></h5>
								<div class="form-row">
									<div class="form-group col-md-4">
										<label><?= $this->lang->line('lb_category') ?></label>
										<select class="form-control" id="img_category">
											<option value=""><?= $this->lang->line('txt_all') ?></option>
											<?php foreach($image_categories as $item){ ?>
											<option value="<?= $item->id ?>"><?= $item->name ?></option>
											<?php } ?>
										</select>
									</div>
									<div class="form-group col-md-8">
										<label><?= $this->lang->line('lb_filter') ?></label>
										<input type="text" class="form-control" id="img_search">
									</div>
								</div>
								<div class="text-center d-none" id="img_no_result"><?= $this->lang->line('msg_no_result') ?></div>
								<div class="form-row ap_content_list_high" id="list_images">
									<?php foreach($image_categories as $item){ ?>
									<div class="form-group col-md-12 mt-3">
										<h5><?= $item->name ?></h5>
									</div>
									<?php $images = $item->images; foreach($images as $img){
									if (in_array($img->id, $checked_images)) $checked = "checked"; else $checked = ""; ?>
									<div class="form-group col-md-6 images image_category_<?= $item->id ?>">
										<div class="search_filter d-none"><?= $img->name ?></div>
										<div class="custom-control custom-checkbox checkbox-primary mr-3 mb-1">
											<input type="checkbox" class="custom-control-input chk_img" id="img_<?= $img->id ?>" value="<?= $img->id ?>" <?= $checked ?>>
											<label class="custom-control-label" for="img_<?= $img->id ?>"><?= $img->name ?></label>
										</div>
									</div>
									<?php }} ?>
								</div>
							</div>
							<?php }else $next_col = 12; ?>
							<div class="col-md-<?= $next_col ?>">
								<?php if ($appointment->is_editable){ ?>
								<h5 class="mb-3"><?= $this->lang->line('title_selected_images') ?></h5>
								<?php } ?>
								<table class="table no_border_tb mb-0">
									<tbody id="selected_images">
										<?php foreach($images_ap as $image){ ?>
										<tr class="text-left">
											<td style="width:120px;"><?= $image->category ?></td>
											<td><?= $image->image ?>
											</td>
											<?php if ($appointment->is_editable){ ?>
											<td class="text-right">
												<button type="button" class="btn tp-btn-light btn-danger btn-xs btn_delete_image" value="<?= $image->image_id ?>">
													<i class="fas fa-minus"></i>
												</button>
											</td>
											<?php } ?>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="col-md-12 process process_attention d-none">
	<div class="card">
		<div class="card-header pb-0 border-0">
			<h4 class="mb-0"><?= $this->lang->line('title_treatment') ?></h4>
		</div>
		<div class="card-body">
			<div class="default-tab">
				<ul class="nav nav-tabs mb-4">
					<li class="nav-item">
						<a href="#treatments-1" class="nav-link active" data-toggle="tab">
							<?= $this->lang->line('title_medicine') ?>
						</a>
					</li>
					<li class="nav-item">
						<a href="#treatments-2" class="nav-link" data-toggle="tab">
							<?= $this->lang->line('title_physical_therapy') ?>
						</a>
					</li>
				</ul>
				<div class="tab-content">
					<div id="treatments-1" class="tab-pane active">
						<div class="row">
							<?php if ($appointment->is_editable){ $next_col = 6; ?>
							<div class="col-md-6">
								<h5><?= $this->lang->line('title_add_medicine') ?></h5>
								<form action="#" id="form_add_medicine">
									<input type="hidden" name="appointment_id" value="<?= $appointment->id ?>">
									<div class="form-row">
										<div class="form-group col-md-12">
											<label><?= $this->lang->line('lb_medicine') ?></label>
											<select class="form-control" name="medicine_id">
												<option value="">--</option>
												<?php foreach($medicines as $item){ ?>
												<option value="<?= $item->id ?>"><?= $item->name ?></option>
												<?php } ?>
											</select>
											<div class="sys_msg" id="md_medicine_msg"></div>
										</div>
										<div class="form-group col-md-6">
											<label><?= $this->lang->line('lb_quantity') ?></label>
											<input type="number" class="form-control" name="quantity" value="1">
											<div class="sys_msg" id="md_quantity_msg"></div>
										</div>
										<div class="form-group col-md-6">
											<label><?= $this->lang->line('lb_dose') ?></label>
											<select class="form-control" name="dose">
												<option value="">--</option>
												<?php $medicine_dose = $options["medicine_dose"];
												foreach($medicine_dose as $item){ ?>
												<option value="<?= $item->id ?>"><?= $item->description ?></option>
												<?php } ?>
											</select>
											<div class="sys_msg" id="md_quantity_msg"></div>
										</div>
										<div class="form-group col-md-4">
											<label><?= $this->lang->line('lb_via_application') ?></label>
											<select class="form-control" name="via_application">
												<option value="">--</option>
												<?php $medicine_via_application = $options["medicine_via_application"];
												foreach($medicine_via_application as $item){ ?>
												<option value="<?= $item->id ?>"><?= $item->description ?></option>
												<?php } ?>
											</select>
											<div class="sys_msg" id="md_via_application_msg"></div>
										</div>
										<div class="form-group col-md-4">
											<label><?= $this->lang->line('lb_frequency') ?></label>
											<select class="form-control" name="frequency">
												<option value="">--</option>
												<?php $medicine_frequency = $options["medicine_frequency"];
												foreach($medicine_frequency as $item){ ?>
												<option value="<?= $item->id ?>"><?= $item->description ?></option>
												<?php } ?>
											</select>
											<div class="sys_msg" id="md_frequency_msg"></div>
										</div>
										<div class="form-group col-md-4">
											<label><?= $this->lang->line('lb_duration') ?></label>
											<select class="form-control" name="duration">
												<option value="">--</option>
												<?php $medicine_duration = $options["medicine_duration"];
												foreach($medicine_duration as $item){ ?>
												<option value="<?= $item->id ?>"><?= $item->description ?></option>
												<?php } ?>
											</select>
											<div class="sys_msg" id="md_duration_msg"></div>
										</div>
										<div class="form-group col-md-12 pt-3 mb-0">
											<button type="sumit" class="btn btn-primary">
												<?= $this->lang->line('btn_add') ?>
											</button>
											<div class="sys_msg" id="md_result_msg"></div>
										</div>
									</div>
								</form>
							</div>
							<?php }else $next_col = 12; ?>
							<div class="col-md-<?= $next_col ?>">
								<?php if ($appointment->is_editable){ ?>
								<h5 class="mb-3"><?= $this->lang->line('title_selected_medicines') ?></h5>
								<?php } ?>
								<table class="table no_border_tb mb-0">
									<tbody id="selected_medicines">
										<?php foreach($me as $m){ ?>
										<tr class="text-left">
											<td>
												<div><?= $m->medicine ?></div>
												<small><?= $m->sub_txt ?></small>
											</td>
											<?php if ($appointment->is_editable){ ?>
											<td class="text-right">
												<button type="button" class="btn tp-btn-light btn-danger btn-xs btn_delete_medicine" value="<?= $m->id ?>">
													<i class="fas fa-minus"></i>
												</button>
											</td>
											<?php } ?>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div id="treatments-2" class="tab-pane">
						<div class="row">
							<?php if ($appointment->is_editable){ $next_col = 6; ?>
							<div class="col-md-6">
								<h5><?= $this->lang->line('title_add_therapy') ?></h5>
								<form action="#" id="form_add_therapy">
									<input type="hidden" name="appointment_id" value="<?= $appointment->id ?>">
									<div class="form-row">
										<div class="form-group col-md-12">
											<label><?= $this->lang->line('lb_therapy') ?></label>
											<select class="form-control" name="physical_therapy_id">
												<option value="">--</option>
												<?php foreach($physical_therapies as $item){ ?>
												<option value="<?= $item->id ?>"><?= $item->name ?></option>
												<?php } ?>
											</select>
											<div class="sys_msg" id="at_physical_therapy_msg"></div>
										</div>
										<div class="form-group col-md-6">
											<label><?= $this->lang->line('lb_session') ?></label>
											<input type="number" class="form-control" name="session" value="1">
											<div class="sys_msg" id="at_session_msg"></div>
										</div>
										<div class="form-group col-md-6">
											<label><?= $this->lang->line('txt_one_session_each') ?></label>
											<div class="input-group input-normal-o">
												<input type="number" class="form-control" name="frequency" value="1">
												<select class="form-control" name="frequency_unit">
													<option value="D"><?= $this->lang->line('txt_day') ?></option>
													<option value="W"><?= $this->lang->line('txt_week') ?></option>
													<option value="M"><?= $this->lang->line('txt_month') ?></option>
													<option value="Y"><?= $this->lang->line('txt_year') ?></option>
												</select>
											</div>
											<div class="sys_msg" id="at_frequency_msg"></div>
										</div>
										<div class="form-group col-md-12 pt-3 mb-0">
											<button type="submit" class="btn btn-primary">
												<?= $this->lang->line('btn_add') ?>
											</button>
											<div class="sys_msg" id="at_result_msg"></div>
										</div>
									</div>
								</form>
							</div>
							<?php }else $next_col = 12; ?>
							<div class="col-md-<?= $next_col ?>">
								<?php if ($appointment->is_editable){ ?>
								<h5 class="mb-3"><?= $this->lang->line('title_selected_therapies') ?></h5>
								<?php } ?>
								<table class="table no_border_tb mb-0">
									<tbody id="selected_therapies">
										<?php foreach($th as $t){ ?>
										<tr class="text-left">
											<td>
												<div><?= $t->physical_therapy ?></div>
												<small><?= $t->sub_txt ?></small>
											</td>
											<?php if ($appointment->is_editable){ ?>
											<td class="text-right">
												<button type="button" class="btn tp-btn-light btn-danger btn_delete_therapy" value="<?= $t->id ?>">
													<i class="fas fa-minus"></i>
												</button>
											</td>
											<?php } ?>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="col-md-12 process process_attention d-none">
	<div class="card">
		<div class="card-header pb-0 border-0">
			<h4 class="mb-0"><?= $this->lang->line('title_sick_leave') ?></h4>
		</div>
		<div class="card-body">
			<div class="text-danger">Por desarrollar</div>
		</div>
	</div>
</div>
<?php if ($appointment->is_editable){ ?>
<div class="col-md-12 process process_attention d-none">
	<div class="card">
		<div class="card-body">
			<div class="text-center">
				<button type="button" class="btn btn-primary btn-lg" id="btn_finish" value="<?= $appointment->id ?>">
					<span class="d-none msg"><?= $this->lang->line('warning_afi') ?></span>
					<?= $this->lang->line('btn_finish_appointment') ?>
				</button>
			</div>
		</div>
	</div>
</div>
<?php } ?>