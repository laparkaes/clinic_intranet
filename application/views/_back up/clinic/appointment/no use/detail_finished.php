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
			<h4 class="mb-0"><?= $this->lang->line('w_basic_data') ?></h4>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col-md-6">
					<h4 class="mb-3"><u><?= $this->lang->line('w_entry') ?></u></h4>
					<div class="row">
						<div class="col-md-12 mb-3">
							<h5 class="mb-1"><?= $this->lang->line('w_entry_mode') ?></h5>
							<div>
							<?php
							$entry_mode = $options["entry_mode"];
							foreach($entry_mode as $item) if ($bd->entry_mode_id == $item->id) echo $item->description;
							?>
							</div>
						</div>
						<div class="col-md-12 mb-3">
							<h5 class="mb-1"><?= $this->lang->line('w_schedule') ?></h5>
							<div><?= date("Y-m-d h:i a", strtotime($bd->entered_at)) ?></div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<h4 class="mb-3"><u><?= $this->lang->line('w_insurance') ?></u></h4>
					<div class="row">
						<div class="col-md-12 mb-3">
							<h5 class="mb-1"><?= $this->lang->line('w_insured') ?></h5>
							<div>
								<?php
								if ($bd->insurance) echo $this->lang->line('w_yes');
								else echo $this->lang->line('w_no');
								?>
							</div>
						</div>
						<div class="col-md-12 mb-3">
							<h5 class="mb-1"><?= $this->lang->line('w_insurance_name') ?></h5>
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
			<h4 class="mb-0"><?= $this->lang->line('w_anamnesis') ?></h4>
		</div>
		<div class="card-body">
			<form action="#" class="row" id="form_anamnesis">
				<input type="hidden" name="appointment_id" value="<?= $appointment->id ?>">
				<div class="col-md-12">
					<div class="default-tab">
						<ul class="nav nav-tabs mb-4">
							<li class="nav-item">
								<a href="#anamnesis-1" class="nav-link active" data-toggle="tab">
									<?= $this->lang->line('w_personal_information') ?>
								</a>
							</li>
							<li class="nav-item">
								<a href="#anamnesis-2" class="nav-link" data-toggle="tab">
									<?= $this->lang->line('w_current_illness') ?>
								</a>
							</li>
							<li class="nav-item">
								<a href="#anamnesis-3" class="nav-link" data-toggle="tab">
									<?= $this->lang->line('w_biological_functions') ?>
								</a>
							</li>
							<li class="nav-item">
								<a href="#anamnesis-4" class="nav-link" data-toggle="tab">
									<?= $this->lang->line('w_personal_background') ?>
								</a>
							</li>
						</ul>
						<div class="tab-content">
							<div id="anamnesis-1" class="tab-pane active">
								<div class="row">
									<div class="col-md-6 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_name') ?></h5>
										<div><?= $an->name ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_age') ?></h5>
										<div><?= $an->age ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_sex') ?></h5>
										<div>
										<?php foreach($sex_ops as $item) if ($item->id == $an->sex_id) echo $item->description; ?>
										</div>
									</div>
									<div class="col-md-6 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_address') ?></h5>
										<div><?= $an->address ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_birthplace') ?></h5>
										<div><?= $an->birthplace ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_birthday') ?></h5>
										<div><?= $an->birthday ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_telephone') ?></h5>
										<div><?= $an->tel ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_responsible') ?></h5>
										<div><?= $an->responsible ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_place_of_origin') ?></h5>
										<div><?= $an->provenance_place ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_last_trips') ?></h5>
										<div><?= $an->last_trips ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_race') ?></h5>
										<div><?= $an->race ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_marital_status') ?></h5>
										<div>
										<?php $civil_status = $options["civil_status"];
										foreach($civil_status as $item) if ($item->id == $an->civil_status_id) echo $item->description; ?>
										</div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_occupation') ?></h5>
										<div><?= $an->occupation ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_religion') ?></h5>
										<div><?= $an->religion ?></div>
									</div>
								</div>
							</div>
							<div id="anamnesis-2" class="tab-pane">
								<div class="row">
									<div class="col-md-4 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_illness_time') ?></h5>
										<div><?= $an->illness_time ?></div>
									</div>
									<div class="col-md-4 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_start') ?></h5>
										<div><?= $an->illness_start ?></div>
									</div>
									<div class="col-md-4 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_grade') ?></h5>
										<div><?= $an->illness_course ?></div>
									</div>
									<div class="col-md-6 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_main_symptoms') ?></h5>
										<div style="white-space: pre-line;"><?= $an->illness_main_symptoms ?></div>
									</div>
									<div class="col-md-6 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_story') ?></h5>
										<div style="white-space: pre-line;"><?= $an->illness_story ?></div>
									</div>
								</div>
							</div>
							<div id="anamnesis-3" class="tab-pane">
								<div class="row">
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_appetite') ?></h5>
										<div><?= $an->func_bio_appetite ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_urine') ?></h5>
										<div><?= $an->func_bio_urine ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_thirst') ?></h5>
										<div><?= $an->func_bio_thirst ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_bowel_movements') ?></h5>
										<div><?= $an->illness_course ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_sweat') ?></h5>
										<div><?= $an->func_bio_sweat ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_weight_') ?></h5>
										<div><?= $an->func_bio_weight ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_sleep') ?></h5>
										<div><?= $an->func_bio_sleep ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_encouragement') ?></h5>
										<div><?= $an->func_bio_encouragement ?></div>
									</div>
								</div>
							</div>
							<div id="anamnesis-4" class="tab-pane">
								<h4 class="mb-3"><u><?= $this->lang->line('w_pathological') ?></u></h4>
								<div class="row">
									<div class="col-md-12 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_previous_illnesses') ?></h5>
										<div>
										<?php
										echo implode(", ", $an->patho_pre_illnesses);
										if ($an->patho_pre_illnesses_other) echo ", ".$an->patho_pre_illnesses_other;
										?>
										</div>
									</div>
									<div class="col-md-6 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_previous_hospitalizations') ?></h5>
										<div style="white-space: pre-line;"><?= $an->patho_pre_hospitalization ?></div>
									</div>
									<div class="col-md-6 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_previous_surgeries') ?></h5>
										<div style="white-space: pre-line;"><?= $an->patho_pre_surgery ?></div>
									</div>
									
									<div class="col-md-6 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_ram') ?></h5>
										<div><?= $an->patho_ram ?></div>
									</div>
									<div class="col-md-6 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_transfusions') ?></h5>
										<div><?= $an->patho_transfusion ?></div>
									</div>
									<div class="col-md-12 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_prior_medication') ?></h5>
										<div><?= $an->patho_pre_medication ?></div>
									</div>
								</div>
								<h4 class="my-3"><u><?= $this->lang->line('w_gynecological') ?></u></h4>
								<div class="row">
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_fur') ?></h5>
										<div><?= $an->gyne_fur ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_g') ?></h5>
										<div><?= $an->gyne_g ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_p') ?></h5>
										<div><?= $an->gyne_p ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_mac') ?></h5>
										<div><?= $an->gyne_mac ?></div>
									</div>
								</div>
								<h4 class="my-3"><u><?= $this->lang->line('w_family_background') ?></u></h4>
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
			<h4 class="mb-0"><?= $this->lang->line('w_physical_exam') ?></h4>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col-md-12">
					<div class="default-tab">
						<ul class="nav nav-tabs mb-4">
							<li class="nav-item">
								<a href="#physical_exam-1" class="nav-link active" data-toggle="tab">
									<?= $this->lang->line('w_vital_functions') ?>
								</a>
							</li>
							<li class="nav-item">
								<a href="#physical_exam-2" class="nav-link" data-toggle="tab">
									<?= $this->lang->line('w_general_exam') ?>
								</a>
							</li>
							<li class="nav-item">
								<a href="#physical_exam-3" class="nav-link" data-toggle="tab">
									<?= $this->lang->line('w_regional_examination') ?>
								</a>
							</li>
						</ul>
						<div class="tab-content">
							<div id="physical_exam-1" class="tab-pane active">
								<div class="row">
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_pa') ?></h5>
										<div><?= $ph->v_pa ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_fc') ?></h5>
										<div><?= $ph->v_fc ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_fr') ?></h5>
										<div><?= $ph->v_fr ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_temperature') ?></h5>
										<div><?= $ph->v_temperature ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_weight') ?></h5>
										<div><?= $ph->v_weight ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_height') ?></h5>
										<div><?= $ph->v_height ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_bmi') ?></h5>
										<div><?= $ph->v_imc ?></div>
									</div>
									<div class="col-md-3 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_bmi_class') ?></h5>
										<div><?= $ph->v_imc_class ?></div>
									</div>
								</div>
							</div>
							<div id="physical_exam-2" class="tab-pane">
								<div class="row">
									<div class="col-md-6 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_appearance') ?></h5>
										<div style="white-space: pre-line;"><?= $ph->g_appearance ?></div>
									</div>
									<div class="col-md-6 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_skin') ?></h5>
										<div style="white-space: pre-line;"><?= $ph->g_skin ?></div>
									</div>
									<div class="col-md-4 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_tcsc') ?></h5>
										<div style="white-space: pre-line;"><?= $ph->g_tcsc ?></div>
									</div>
									<div class="col-md-4 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_soma') ?></h5>
										<div style="white-space: pre-line;"><?= $ph->g_soma ?></div>
									</div>
									<div class="col-md-4 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_lymphatic') ?></h5>
										<div style="white-space: pre-line;"><?= $ph->g_lymphatic ?></div>
									</div>
								</div>
							</div>
							<div id="physical_exam-3" class="tab-pane">
								<div class="row">
									<div class="col-md-4 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_head') ?></h5>
										<div style="white-space: pre-line;"><?= $ph->r_head ?></div>
									</div>
									<div class="col-md-4 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_neck') ?></h5>
										<div style="white-space: pre-line;"><?= $ph->r_neck ?></div>
									</div>
									<div class="col-md-4 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_breasts') ?></h5>
										<div style="white-space: pre-line;"><?= $ph->r_breasts ?></div>
									</div>
									<div class="col-md-4 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_chest_and_lungs') ?></h5>
										<div style="white-space: pre-line;"><?= $ph->r_thorax_lungs ?></div>
									</div>
									<div class="col-md-4 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_cardiovascular') ?></h5>
										<div style="white-space: pre-line;"><?= $ph->r_cardiovascular ?></div>
									</div>
									<div class="col-md-4 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_abdomen') ?></h5>
										<div style="white-space: pre-line;"><?= $ph->r_abdomen ?></div>
									</div>
									<div class="col-md-6 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_genitourinary') ?></h5>
										<div style="white-space: pre-line;"><?= $ph->r_genitourinary ?></div>
									</div>
									<div class="col-md-6 mb-3">
										<h5 class="mb-1"><?= $this->lang->line('w_neurological') ?></h5>
										<div style="white-space: pre-line;"><?= $ph->r_neurologic ?></div>
									</div>
								</div>
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
			<h4 class="mb-0"><?= $this->lang->line('w_diagnostic_impression') ?></h4>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-responsive-md mb-0">
					<thead>
						<tr>
							<th style="width: 80px;"><strong>#</strong></th>
							<th style="width: 130px;"><strong><?= $this->lang->line('w_cie10') ?></strong></th>
							<th><strong><?= $this->lang->line('w_description') ?></strong></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($di as $i => $d){ ?>
						<tr>
							<td><?= number_format($i + 1) ?></td>
							<td><?= $d->code ?></td>
							<td><?= $d->description ?></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<div class="col-md-12">
	<div class="card">
		<div class="card-header">
			<h4 class="mb-0"><?= $this->lang->line('w_result') ?></h4>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col-md-12 mb-3">
					<h5 class="mb-1"><?= $this->lang->line('w_diagnosis_type') ?></h5>
					<div>
					<?php 
					$diagnosis_type = $options["diagnosis_type"];
					foreach($diagnosis_type as $i => $item)
						if ($re->diagnosis_type_id)
							if ($item->id == $re->diagnosis_type_id) echo $item->description;
					?>
					</div>
				</div>
				<div class="col-md-4 mb-3">
					<h5 class="mb-1"><?= $this->lang->line('w_diagnosis') ?></h5>
					<div style="white-space: pre-line;"><?= $re->diagnosis ?></div>
				</div>
				<div class="col-md-4 mb-3">
					<h5 class="mb-1"><?= $this->lang->line('w_workplan') ?></h5>
					<div style="white-space: pre-line;"><?= $re->plan ?></div>
				</div>
				<div class="col-md-4 mb-3">
					<h5 class="mb-1"><?= $this->lang->line('w_treatment') ?></h5>
					<div style="white-space: pre-line;"><?= $re->treatment ?></div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="col-md-12">
	<div class="card">
		<div class="card-header">
			<h4 class="mb-0"><?= $this->lang->line('w_auxiliary_exam') ?></h4>
		</div>
		<div class="card-body">
			<div class="default-tab">
				<ul class="nav nav-tabs mb-4">
					<li class="nav-item">
						<a href="#auxiliary_exams_lab" class="nav-link active" data-toggle="tab">
							<?= $this->lang->line('w_laboratory') ?>
						</a>
					</li>
					<li class="nav-item">
						<a href="#auxiliary_exams_img" class="nav-link" data-toggle="tab">
							<?= $this->lang->line('w_image') ?>
						</a>
					</li>
				</ul>
				<div class="tab-content">
					<div id="auxiliary_exams_lab" class="tab-pane active">
						<div class="table-responsive">
							<table class="table table-responsive-md mb-0">
								<thead>
									<tr>
										<th style="width: 50px;"><strong>#</strong></th>
										<th><strong><?= $this->lang->line('w_type') ?></strong></th>
										<th class="w-30"><strong><?= $this->lang->line('w_profile') ?></strong></th>
										<th><strong><?= $this->lang->line('w_exams') ?></strong></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($ex_profiles as $i => $ep){ ?>
									<tr>
										<td><?= number_format($i + 1) ?></td>
										<td><?= $ep->type ?></td>
										<td><?= $ep->name ?></td>
										<td><?= $ep->exams ?></td>
									</tr>
									<?php } $i++; foreach($ex_examinations as $j => $ee){ ?>
									<tr>
										<td><?= number_format($i + $j + 1) ?></td>
										<td><?= $ee->type ?></td>
										<td>-</td>
										<td><?= $ee->name ?></td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
					<div id="auxiliary_exams_img" class="tab-pane">
						<div class="table-responsive">
							<table class="table table-responsive-md mb-0">
								<thead>
									<tr>
										<th style="width: 50px;"><strong>#</strong></th>
										<th><strong><?= $this->lang->line('w_category') ?></strong></th>
										<th><strong><?= $this->lang->line('w_image') ?></strong></th>
									</tr>
								</thead>
								<tbody id="tbody_images">
									<?php foreach($im as $i => $item){ ?>
									<tr>
										<td><?= number_format($i + 1) ?></td>
										<td><?= $item->category ?></td>
										<td><?= $item->name ?></td>
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
<div class="col-md-12">
	<div class="card">
		<div class="card-header">
			<h4 class="mb-0"><?= $this->lang->line('w_treatment') ?></h4>
		</div>
		<div class="card-body">
			<div class="default-tab">
				<ul class="nav nav-tabs mb-4">
					<li class="nav-item">
						<a href="#treatments-1" class="nav-link active" data-toggle="tab">
							<?= $this->lang->line('w_medicine') ?>
						</a>
					</li>
					<li class="nav-item">
						<a href="#treatments-2" class="nav-link" data-toggle="tab">
							<?= $this->lang->line('w_physical_therapy') ?>
						</a>
					</li>
				</ul>
				<div class="tab-content">
					<div id="treatments-1" class="tab-pane active">
						<div class="table-responsive">
							<table class="table table-responsive-md mb-0">
								<thead>
									<tr>
										<th style="width:50px;"><strong>#</strong></th>
										<th><strong><?= $this->lang->line('w_medicine') ?></strong></th>
										<th><strong><?= $this->lang->line('w_detail') ?></strong></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($me as $i => $item){ ?>
									<tr>
										<td><?= number_format($i + 1) ?></td>
										<td><?= $item->medicine ?></td>
										<td><?= $item->sub_txt ?></td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
					<div id="treatments-2" class="tab-pane">
						<div class="table-responsive">
							<table class="table table-responsive-md mb-0">
								<thead>
									<tr>
										<th style="width:50px;"><strong>#</strong></th>
										<th><strong><?= $this->lang->line('w_physical_therapy') ?></strong></th>
										<th><strong><?= $this->lang->line('w_detail') ?></strong></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($th as $i => $item){ ?>
									<tr>
										<td><?= number_format($i + 1) ?></td>
										<td><?= $item->physical_therapy ?></td>
										<td><?= $item->sub_txt ?></td>
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