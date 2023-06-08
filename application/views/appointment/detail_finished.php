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
			<h4 class="mb-0"><?= $this->lang->line('title_basic_data') ?></h4>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col-md-6">
					<h4 class="mb-3"><u><?= $this->lang->line('title_entry') ?></u></h4>
					<div class="row">
						<div class="col-md-12 mb-3">
							<h5 class="mb-1"><?= $this->lang->line('lb_entry_mode') ?></h5>
							<div>
							<?php
							$entry_mode = $options["entry_mode"];
							foreach($entry_mode as $item) if ($bd->entry_mode_id == $item->id) echo $item->description;
							?>
							</div>
						</div>
						<div class="col-md-12 mb-3">
							<h5 class="mb-1"><?= $this->lang->line('lb_schedule') ?></h5>
							<div><?= date("Y-m-d h:i a", strtotime($bd->entered_at)) ?></div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<h4 class="mb-3"><u><?= $this->lang->line('title_insurance') ?></u></h4>
					<div class="row">
						<div class="col-md-12 mb-3">
							<h5 class="mb-1"><?= $this->lang->line('lb_insured') ?></h5>
							<div>
								<?php
								if ($bd->insurance) echo $this->lang->line('txt_yes');
								else echo $this->lang->line('txt_no');
								?>
							</div>
						</div>
						<div class="col-md-12 mb-3">
							<h5 class="mb-1"><?= $this->lang->line('lb_insurance_name') ?></h5>
							<div><?= $bd->insurance_name ?></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="col-md-12">
	<div class="card">
		<div class="card-header">
			<h4 class="mb-0"><?= $this->lang->line('title_triage') ?></h4>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col-md-3 mb-3">
					<h5 class="mb-1"><?= $this->lang->line('lb_pa') ?></h5>
					<div><?= $ph->v_pa ?></div>
				</div>
				<div class="col-md-3 mb-3">
					<h5 class="mb-1"><?= $this->lang->line('lb_fc') ?></h5>
					<div><?= $ph->v_fc ?></div>
				</div>
				<div class="col-md-3 mb-3">
					<h5 class="mb-1"><?= $this->lang->line('lb_fr') ?></h5>
					<div><?= $ph->v_fr ?></div>
				</div>
				<div class="col-md-3 mb-3">
					<h5 class="mb-1"><?= $this->lang->line('lb_temperature') ?></h5>
					<div><?= $ph->v_temperature ?></div>
				</div>
				<div class="col-md-3 mb-3">
					<h5 class="mb-1"><?= $this->lang->line('lb_weight') ?></h5>
					<div><?= $ph->v_weight ?></div>
				</div>
				<div class="col-md-3 mb-3">
					<h5 class="mb-1"><?= $this->lang->line('lb_height') ?></h5>
					<div><?= $ph->v_height ?></div>
				</div>
				<div class="col-md-3 mb-3">
					<h5 class="mb-1"><?= $this->lang->line('lb_bmi') ?></h5>
					<div><?= $ph->v_imc ?></div>
				</div>
				<div class="col-md-3 mb-3">
					<h5 class="mb-1"><?= $this->lang->line('lb_bmi_class') ?></h5>
					<div><?= $ph->v_imc_class ?></div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="col-md-12">
	<div class="card">
		<div class="card-header">
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
								<div class="row">
									<div class="col-md-6 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('lb_name') ?></h5>
										<div><?= $an->name ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('lb_age') ?></h5>
										<div><?= $an->age ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('lb_sex') ?></h5>
										<div>
										<?php foreach($sex_ops as $item) if ($item->id == $an->sex_id) echo $item->description; ?>
										</div>
									</div>
									<div class="col-md-6 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('lb_address') ?></h5>
										<div><?= $an->address ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('lb_birthplace') ?></h5>
										<div><?= $an->birthplace ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('lb_birthday') ?></h5>
										<div><?= $an->birthday ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('lb_telephone') ?></h5>
										<div><?= $an->tel ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('lb_responsible') ?></h5>
										<div><?= $an->responsible ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('lb_place_of_origin') ?></h5>
										<div><?= $an->provenance_place ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('lb_last_trips') ?></h5>
										<div><?= $an->last_trips ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('lb_race') ?></h5>
										<div><?= $an->race ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('lb_marital_status') ?></h5>
										<div>
										<?php $civil_status = $options["civil_status"];
										foreach($civil_status as $item) if ($item->id == $an->civil_status_id) echo $item->description; ?>
										</div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('lb_occupation') ?></h5>
										<div><?= $an->occupation ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('lb_religion') ?></h5>
										<div><?= $an->religion ?></div>
									</div>
								</div>
							</div>
							<div id="anamnesis-2" class="tab-pane">
								<div class="row">
									<div class="col-md-4 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('lb_illness_time') ?></h5>
										<div><?= $an->illness_time ?></div>
									</div>
									<div class="col-md-4 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('lb_start') ?></h5>
										<div><?= $an->illness_start ?></div>
									</div>
									<div class="col-md-4 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('lb_grade') ?></h5>
										<div><?= $an->illness_course ?></div>
									</div>
									<div class="col-md-6 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('lb_main_symptoms') ?></h5>
										<div style="white-space: pre-line;"><?= $an->illness_main_symptoms ?></div>
									</div>
									<div class="col-md-6 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('lb_story') ?></h5>
										<div style="white-space: pre-line;"><?= $an->illness_story ?></div>
									</div>
								</div>
							</div>
							<div id="anamnesis-3" class="tab-pane">
								<div class="row">
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('lb_appetite') ?></h5>
										<div><?= $an->func_bio_appetite ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('lb_urine') ?></h5>
										<div><?= $an->func_bio_urine ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('lb_thirst') ?></h5>
										<div><?= $an->func_bio_thirst ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('lb_bowel_movements') ?></h5>
										<div><?= $an->illness_course ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('lb_sweat') ?></h5>
										<div><?= $an->func_bio_sweat ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('lb_weight_') ?></h5>
										<div><?= $an->func_bio_weight ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('lb_sleep') ?></h5>
										<div><?= $an->func_bio_sleep ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('lb_encouragement') ?></h5>
										<div><?= $an->func_bio_encouragement ?></div>
									</div>
								</div>
							</div>
							<div id="anamnesis-4" class="tab-pane">
								<h4 class="mb-3"><u><?= $this->lang->line('title_pathological') ?></u></h4>
								<div class="row">
									<div class="col-md-12 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('lb_previous_illnesses') ?></h5>
										<div>
										<?php
										echo implode(", ", $an->patho_pre_illnesses);
										if ($an->patho_pre_illnesses_other) echo ", ".$an->patho_pre_illnesses_other;
										?>
										</div>
									</div>
									<div class="col-md-6 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('lb_previous_hospitalizations') ?></h5>
										<div style="white-space: pre-line;"><?= $an->patho_pre_hospitalization ?></div>
									</div>
									<div class="col-md-6 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('lb_previous_surgeries') ?></h5>
										<div style="white-space: pre-line;"><?= $an->patho_pre_surgery ?></div>
									</div>
									
									<div class="col-md-6 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('lb_ram') ?></h5>
										<div><?= $an->patho_ram ?></div>
									</div>
									<div class="col-md-6 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('lb_transfusions') ?></h5>
										<div><?= $an->patho_transfusion ?></div>
									</div>
									<div class="col-md-12 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('lb_prior_medication') ?></h5>
										<div><?= $an->patho_pre_medication ?></div>
									</div>
								</div>
								<h4 class="my-3"><u><?= $this->lang->line('title_gynecological') ?></u></h4>
								<div class="row">
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('lb_fur') ?></h5>
										<div><?= $an->gyne_fur ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('lb_g') ?></h5>
										<div><?= $an->gyne_g ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('lb_p') ?></h5>
										<div><?= $an->gyne_p ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('lb_mac') ?></h5>
										<div><?= $an->gyne_mac ?></div>
									</div>
								</div>
								<h4 class="my-3"><u><?= $this->lang->line('title_family_background') ?></u></h4>
								<div class="row">
									<div class="col-md-12 mb-3">
										<div style="white-space: pre-line;"><?= $an->family_history ?></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="col-md-12">
	<div class="card">
		<div class="card-header">
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
										<input type="text" class="form-control" name="v_imc" value="<?= $ph->v_imc ?>" readonly>
										<div class="sys_msg" id="pe_v_imc_msg"></div>
									</div>
									<div class="form-group col-md-3">
										<label><?= $this->lang->line('lb_bmi_class') ?></label>
										<input type="text" class="form-control" name="v_imc_class" value="<?= $ph->v_imc_class ?>" readonly>
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
<div class="col-md-12">
	<div class="card">
		<div class="card-header">
			<h4 class="mb-0"><?= $this->lang->line('title_diagnostic_impression') ?></h4>
		</div>
		<div class="card-body">
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
<div class="col-md-12">
	<div class="card">
		<div class="card-header">
			<h4 class="mb-0"><?= $this->lang->line('title_result') ?></h4>
		</div>
		<div class="card-body">
			<form action="#" class="form-row" id="form_result">
				<input type="hidden" name="appointment_id" value="<?= $appointment->id ?>">
				<div class="form-group col-md-12">
					<label><?= $this->lang->line('lb_diagnosis_type') ?></label>
					<div class="form-control d-flex align-items-center">
						<?php $diagnosis_type = $options["diagnosis_type"];
						foreach($diagnosis_type as $i => $item){ 
							if ($re->diagnosis_type_id){
								if ($item->id == $re->diagnosis_type_id) $checked = "checked"; else $checked = "";
							}elseif ($i) $checked = ""; else $checked = "checked";
						?>
						<label class="radio-inline text-center mr-4 mb-0"><input type="radio" name="diagnosis_type_id" value="<?= $item->id ?>" <?= $checked ?>> <?= $item->description ?></label>
						<?php } ?>
					</div>
				</div>
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
<div class="col-md-12">
	<div class="card">
		<div class="card-header">
			<h4 class="mb-0"><?= $this->lang->line('title_auxiliary_exam') ?></h4>
		</div>
		<div class="card-body">
			<div class="default-tab">
				<ul class="nav nav-tabs mb-4">
					<li class="nav-item">
						<a href="#auxiliary_exams_lab" class="nav-link active" data-toggle="tab">
							<?= $this->lang->line('title_laboratory') ?>
						</a>
					</li>
					<li class="nav-item">
						<a href="#auxiliary_exams_img" class="nav-link" data-toggle="tab">
							<?= $this->lang->line('title_image') ?>
						</a>
					</li>
				</ul>
				<div class="tab-content">
					<div id="auxiliary_exams_lab" class="tab-pane active">
						<?php if ($appointment->is_editable){ ?>
						<div class="row">
							<div class="col-md-6">
								<div class="form-row">
									<div class="form-group col-md-12">
										<label><?= $this->lang->line('lb_profile') ?></label>
										<div class="input-group">
											<select class="form-control" id="sl_profile_exam">
												<option value="">--</option>
												<?php foreach($exam_profiles as $item){ ?>
												<option value="<?= $item->id ?>"><?= $item->name ?></option>
												<?php } ?>
											</select>
											<div class="input-group-append">
												<button class="btn btn-primary" type="button" id="btn_add_exam_profile">
													<i class="fas fa-plus"></i>
												</button>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-row">
									<div class="form-group col-md-4">
										<label><?= $this->lang->line('lb_category') ?></label>
										<select class="form-control" id="sl_exam_category">
											<option value=""><?= $this->lang->line('txt_all') ?></option>
											<?php foreach($exam_categories as $item){ ?>
											<option value="<?= $item->id ?>"><?= $item->name ?></option>
											<?php } ?>
										</select>
									</div>
									<div class="form-group col-md-8">
										<label><?= $this->lang->line('lb_exam') ?></label>
										<div class="input-group">
											<select class="form-control" id="sl_exam">
												<option value="">--</option>
												<?php foreach($examinations as $item){ ?>
												<option value="<?= $item->id ?>" class="exam_cat exam_cat_<?= $item->category_id ?>"><?= $item->name ?></option>
												<?php } ?>
											</select>
											<div class="input-group-append">
												<button class="btn btn-primary" type="button" id="btn_add_exam">
													<i class="fas fa-plus"></i>
												</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php } ?>
						<div class="row">
							<div class="table-responsive">
								<table class="table table-responsive-md">
									<thead>
										<tr>
											<th><strong><?= $this->lang->line('th_type') ?></strong></th>
											<th class="w-30"><strong><?= $this->lang->line('th_profile') ?></strong></th>
											<th><strong><?= $this->lang->line('th_exams') ?></strong></th>
											<?php if ($appointment->is_editable){ ?><th></th><?php } ?>
										</tr>
									</thead>
									<tbody id="tbody_exams_profiles">
										<?php foreach($ex_profiles as $ep){ ?>
										<tr>
											<td><?= $ep->type ?></td>
											<td><?= $ep->name ?></td>
											<td><?= $ep->exams ?></td>
											<?php if ($appointment->is_editable){ ?>
											<td>
												<button type="button" class="btn btn-danger shadow btn-xs sharp btn_remove_exam_profile" value="<?= $ep->id ?>">
													<i class="fas fa-trash"></i>
												</button>
											</td>
											<?php } ?>
										</tr>
										<?php } foreach($ex_examinations as $ee){ ?>
										<tr>
											<td><?= $ee->type ?></td>
											<td>-</td>
											<td><?= $ee->name ?></td>
											<?php if ($appointment->is_editable){ ?>
											<td class="text-right">
												<button type="button" class="btn btn-danger shadow btn-xs sharp btn_remove_exam" value="<?= $ee->id ?>">
													<i class="fas fa-trash"></i>
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
					<div id="auxiliary_exams_img" class="tab-pane">
						<?php if ($appointment->is_editable){ ?>
						<div class="row">
							<div class="col-md-12">
								<div class="form-row">
									<div class="form-group col-md-2">
										<label><?= $this->lang->line('lb_category') ?></label>
										<select class="form-control" id="sl_aux_img_category">
											<option value="">--</option>
											<?php foreach($aux_image_categories as $item){ ?>
											<option value="<?= $item->id ?>"><?= $item->name ?></option>
											<?php } ?>
										</select>
									</div>
									<div class="form-group col-md-6">
										<label><?= $this->lang->line('lb_image') ?></label>
										<select class="form-control" id="sl_aux_img">
											<option value="">--</option>
											<?php foreach($aux_images as $item){ ?>
											<option class="img_cat img_cat_<?= $item->category_id ?> d-none" value="<?= $item->id ?>"><?= $item->name ?></option>
											<?php } ?>
										</select>
									</div>
									<div class="form-group d-flex align-items-end">
										<button type="button" class="btn btn-primary" id="btn_add_img">
											<?= $this->lang->line('btn_add') ?>
										</button>
									</div>
								</div>
							</div>
						</div>
						<?php } ?>
						<div class="row">
							<div class="table-responsive">
								<table class="table table-responsive-md">
									<thead>
										<tr>
											<th><strong><?= $this->lang->line('th_category') ?></strong></th>
											<th><strong><?= $this->lang->line('th_image') ?></strong></th>
											<?php if ($appointment->is_editable){ ?><th></th><?php } ?>
										</tr>
									</thead>
									<tbody id="tbody_images">
										<?php foreach($im as $item){ ?>
										<tr>
											<td><?= $item->category ?></td>
											<td><?= $item->name ?></td>
											<?php if ($appointment->is_editable){ ?>
											<td class="text-right">
												<button type="button" class="btn btn-danger shadow btn-xs sharp btn_remove_image" value="<?= $item->image_id ?>">
													<i class="fas fa-trash"></i>
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
<div class="col-md-12">
	<div class="card">
		<div class="card-header">
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
											<select class="form-control" name="dose_id">
												<option value="">--</option>
												<?php $medicine_dose = $options["medicine_dose"];
												foreach($medicine_dose as $item){ ?>
												<option value="<?= $item->id ?>"><?= $item->description ?></option>
												<?php } ?>
											</select>
											<div class="sys_msg" id="md_quantity_msg"></div>
										</div>
										<div class="form-group col-md-4">
											<label><?= $this->lang->line('lb_application_way') ?></label>
											<select class="form-control" name="application_way_id">
												<option value="">--</option>
												<?php $application_way = $options["medicine_application_way"];
												foreach($application_way as $item){ ?>
												<option value="<?= $item->id ?>"><?= $item->description ?></option>
												<?php } ?>
											</select>
											<div class="sys_msg" id="md_via_application_msg"></div>
										</div>
										<div class="form-group col-md-4">
											<label><?= $this->lang->line('lb_frequency') ?></label>
											<select class="form-control" name="frequency_id">
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
											<select class="form-control" name="duration_id">
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
											<input type="number" class="form-control" name="session" value="1" min="1">
											<div class="sys_msg" id="at_session_msg"></div>
										</div>
										<div class="form-group col-md-6">
											<label><?= $this->lang->line('txt_one_session_each') ?></label>
											<div class="input-group input-normal-o">
												<input type="number" class="form-control" name="frequency" value="1" min="1">
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
<div class="col-md-12">
	<div class="card">
		<div class="card-header">
			<h4 class="mb-0"><?= $this->lang->line('title_sick_leave') ?></h4>
		</div>
		<div class="card-body">
			<div class="text-danger">Por desarrollar</div>
		</div>
	</div>
</div>