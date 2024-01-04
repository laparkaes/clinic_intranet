<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<style>
	html, body{padding: 0; font-size: 12px; font-family: 'poppins', sans-serif; line-height: 1.5; color: black;}
	.title_1{font-size: 150%;}
	.title_2{font-size: 135%;}
	.title_3{font-size: 120%;}
	.text-center{text-align: center;}
	.text-left{text-align: left;}
	
	
	.table-5 td, .table-5 th {width: 20%;}
	.table-4 td, .table-4 th {width: 25%;}
	.table-3 td, .table-3 th {width: 33.33%;}
	.table-2 td, .table-2 th {width: 50%;}

	.mt-1{margin-top: 0.5rem;}
	.mt-2{margin-top: 1rem;}
	.mt-3{margin-top: 2rem;}
	.mt-4{margin-top: 3rem;}
	
	.pre-line {white-space: pre-line;}
	
	.datatable{width: 100%;}
	.datatable td{vertical-align: top; padding-bottom: 8px;}
	
	</style>
</head>
<body>
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
<table style="width: 100%;">
	<tr>
		<td style="width: 60%; font-size: 200%; vertical-align: top;"><strong><?= $this->lang->line('w_clinical_history') ?></strong></td>
		<td style="width: 40%;">
			<table style="width: 100%;">
				<tr>
					<td><strong><?= $this->lang->line('w_history_number') ?></strong></td>
					<td style="text-align: right;"><?= $patient->doc_number ?></td>
				</tr>
				<tr>
					<td><strong><?= $this->lang->line('w_date_hour') ?></strong></td>
					<td style="text-align: right;"><?= $bd->entered_at ?></td>
				</tr>
				<tr>
					<td><strong><?= $this->lang->line('w_entry_mode') ?></strong></td>
					<td style="text-align: right;"><?= $bd->entry_mode ?></td>
				</tr>
				<tr>
					<td><strong><?= $this->lang->line('w_insurance') ?></strong></td>
					<td style="text-align: right;"><?= ($bd->insurance_name) ? $bd->insurance_name : "-" ?></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br/>
<div style="font-size: 150%; border-top: 1px solid black; border-bottom: 1px solid black;">
	<strong>1. <?= $this->lang->line('w_anamnesis') ?></strong>
</div>
<br/>
<div style="font-size: 120%;">
	<strong>1) <?= $this->lang->line('w_personal_information') ?></strong>
</div>
<table class="datatable">
	<tr>
		<td colspan="2" style="width: 50%;">
			<div><strong><?= $this->lang->line('w_name') ?></strong></div>
			<div><?= ($an->name) ? $an->name: '-' ?></div>
		</td>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_age') ?></strong></div>
			<div><?= ($an->age) ? $an->age: '-' ?></div>
		</td>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_sex') ?></strong></div>
			<div><?= ($an->sex) ? $an->sex: '-' ?></div>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="width: 50%;">
			<div><strong><?= $this->lang->line('w_address') ?></strong></div>
			<div><?= ($an->address) ? $an->address: '-' ?></div>
		</td>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_birth_place') ?></strong></div>
			<div><?= ($an->birthplace) ? $an->birthplace: '-' ?></div>
		</td>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_birth_day') ?></strong></div>
			<div><?= ($an->birthday) ? $an->birthday: '-' ?></div>
		</td>
	</tr>
	<tr>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_tel') ?></strong></div>
			<div><?= ($an->tel) ? $an->tel: '-' ?></div>
		</td>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_responsible') ?></strong></div>
			<div><?= ($an->responsible) ? $an->responsible: '-' ?></div>
		</td>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_place_of_origin') ?></strong></div>
			<div><?= ($an->provenance_place) ? $an->provenance_place: '-' ?></div>
		</td>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_last_trips') ?></strong></div>
			<div><?= ($an->last_trips) ? $an->last_trips: '-' ?></div>
		</td>
	</tr>
	<tr>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_race') ?></strong></div>
			<div><?= ($an->race) ? $an->race: '-' ?></div>
		</td>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_marital_status') ?></strong></div>
			<div><?= ($an->civil_status) ? $an->civil_status: '-' ?></div>
		</td>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_occupation') ?></strong></div>
			<div><?= ($an->occupation) ? $an->occupation: '-' ?></div>
		</td>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_religion') ?></strong></div>
			<div><?= ($an->religion) ? $an->religion: '-' ?></div>
		</td>
	</tr>
</table>
<br/>
<div style="font-size: 120%;">
	<strong>2) <?= $this->lang->line('w_current_illness') ?></strong>
</div>
<table class="datatable" style="width: 100%;">
	<tr>
		<td colspan="2" style="width: 50%;">
			<div><strong><?= $this->lang->line('w_illness_time') ?></strong></div>
			<div><?= ($an->illness_time) ? $an->illness_time: '-' ?></div>
		</td>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_start') ?></strong></div>
			<div><?= ($an->illness_start) ? $an->illness_start: '-' ?></div>
		</td>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_grade') ?></strong></div>
			<div><?= ($an->illness_course) ? $an->illness_course: '-' ?></div>
		</td>
	</tr>
	<tr>
		<td colspan="2" style="width: 50%;">
			<div><strong><?= $this->lang->line('w_main_symptoms') ?></strong></div>
			<div><?= ($an->illness_main_symptoms) ? $an->illness_main_symptoms: '-' ?></div>
		</td>
		<td colspan="2" style="width: 50%;">
			<div><strong><?= $this->lang->line('w_story') ?></strong></div>
			<div><?= ($an->illness_story) ? $an->illness_story: '-' ?></div>
		</td>
	</tr>
</table>
<br/>
<div style="font-size: 120%;">
	<strong>3) <?= $this->lang->line('w_biological_functions') ?></strong>
</div>
<table class="datatable" style="width: 100%;">
	<tr>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_appetite') ?></strong></div>
			<div><?= ($an->func_bio_appetite) ? $an->func_bio_appetite : "-" ?></div>
		</td>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_urine') ?></strong></div>
			<div><?= ($an->func_bio_urine) ? $an->func_bio_urine : "-" ?></div>
		</td>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_thirst') ?></strong></div>
			<div><?= ($an->func_bio_thirst)? $an->func_bio_thirst : "-" ?></div>
		</td>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_bowel_movements') ?></strong></div>
			<div><?= ($an->func_bio_bowel_movements) ? $an->func_bio_bowel_movements : "-" ?></div>
		</td>
	</tr>
	<tr>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_sweat') ?></strong></div>
			<div><?= ($an->func_bio_sweat) ? $an->func_bio_sweat : "-" ?></div>
		</td>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_weight_') ?></strong></div>
			<div><?= ($an->func_bio_weight) ? $an->func_bio_weight : "-" ?></div>
		</td>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_sleep') ?></strong></div>
			<div><?= ($an->func_bio_sleep) ? $an->func_bio_sleep : "-" ?></div>
		</td>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_encouragement') ?></strong></div>
			<div><?= ($an->func_bio_encouragement) ? $an->func_bio_encouragement : "-" ?></div>
		</td>
	</tr>
</table>
<br/>
<div style="font-size: 120%;">
	<strong>4) <?= $this->lang->line('w_personal_background') ?></strong>
</div>
<div style="font-size: 100%;">
	<strong>A. <?= $this->lang->line('w_pathological') ?></strong>
</div>
<table class="datatable" style="width: 100%;">
	<tr>
		<td colspan="2">
			<div><strong><?= $this->lang->line('w_previous_illnesses') ?></strong></div>
			<div><?= ($an->patho_pre_illnesses_txt) ? $an->patho_pre_illnesses_txt : "-" ?></div>
		</td>
	</tr>
	<tr>
		<td style="width: 50%;">
			<div><strong><?= $this->lang->line('w_previous_hospitalizations') ?></strong></div>
			<div class="pre-line"><?= ($an->patho_pre_hospitalization) ? $an->patho_pre_hospitalization : "-" ?></div>
		</td>
		<td style="width: 50%;">
			<div><strong><?= $this->lang->line('w_previous_surgeries') ?></strong></div>
			<div class="pre-line"><?= ($an->patho_pre_surgery)? $an->patho_pre_surgery : "-" ?></div>
		</td>
	</tr>
	<tr>
		<td style="width: 50%;">
			<div><strong><?= $this->lang->line('w_ram') ?></strong></div>
			<div class="pre-line"><?= ($an->patho_ram) ? $an->patho_ram : "-" ?></div>
		</td>
		<td style="width: 50%;">
			<div><strong><?= $this->lang->line('w_transfusions') ?></strong></div>
			<div class="pre-line"><?= ($an->patho_transfusion)? $an->patho_transfusion : "-" ?></div>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<div><strong><?= $this->lang->line('w_prior_medication') ?></strong></div>
			<div class="pre-line"><?= ($an->patho_pre_medication) ? $an->patho_pre_medication : "-" ?></div>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<div><strong><?= $this->lang->line('w_family_background') ?></strong></div>
			<div class="pre-line"><?= ($an->family_history) ? $an->family_history : "-" ?></div>
		</td>
	</tr>
</table>
<br/>
<div style="font-size: 100%;">
	<strong>B. <?= $this->lang->line('w_gynecological') ?></strong>
</div>
<table class="datatable" style="width: 100%;">
	<tr>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_fur') ?></strong></div>
			<div><?= ($an->gyne_fur) ? $an->gyne_fur : "-" ?></div>
		</td>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_g') ?></strong></div>
			<div><?= ($an->gyne_g)? $an->gyne_g : "-" ?></div>
		</td>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_p') ?></strong></div>
			<div><?= ($an->gyne_p) ? $an->gyne_p : "-" ?></div>
		</td>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_mac') ?></strong></div>
			<div><?= ($an->gyne_mac)? $an->gyne_mac : "-" ?></div>
		</td>
	</tr>
</table>
<br/>
<div style="font-size: 150%; border-top: 1px solid black; border-bottom: 1px solid black;">
	<strong>2. <?= $this->lang->line('w_physical_exam') ?></strong>
</div>
<br/>
<div style="font-size: 120%;">
	<strong>1) <?= $this->lang->line('w_vital_functions') ?></strong>
</div>
<table class="datatable" style="width: 100%;">
	<tr>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_pa') ?></strong></div>
			<div><?= ($ph->v_pa) ? $ph->v_pa : "-" ?></div>
		</td>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_fc') ?></strong></div>
			<div><?= ($ph->v_fc)? $ph->v_fc : "-" ?></div>
		</td>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_fr') ?></strong></div>
			<div><?= ($ph->v_fr) ? $ph->v_fr : "-" ?></div>
		</td>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_temperature') ?></strong></div>
			<div><?= ($ph->v_temperature)? $ph->v_temperature : "-" ?></div>
		</td>
	</tr>
	<tr>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_weight') ?></strong></div>
			<div><?= ($ph->v_weight) ? $ph->v_weight : "-" ?></div>
		</td>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_height') ?></strong></div>
			<div><?= ($ph->v_height)? $ph->v_height : "-" ?></div>
		</td>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_bmi') ?></strong></div>
			<div><?= ($ph->v_imc) ? $ph->v_imc : "-" ?></div>
		</td>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_class') ?></strong></div>
			<div><?= ($ph->v_imc_class)? $ph->v_imc_class : "-" ?></div>
		</td>
	</tr>
</table>
<br/>
<div style="font-size: 120%;">
	<strong>2) <?= $this->lang->line('w_general_exam') ?></strong>
</div>
<table class="datatable" style="width: 100%;">
	<tr>
		<td colspan="4">
			<div><strong><?= $this->lang->line('w_appearance') ?></strong></div>
			<div class="pre-line"><?= ($ph->g_appearance) ? $ph->g_appearance : "-" ?></div>
		</td>
	</tr>
</table>
<table class="datatable" style="width: 100%;">
	<tr>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_skin') ?></strong></div>
			<div class="pre-line"><?= ($ph->g_skin)? $ph->g_skin : "-" ?></div>
		</td>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_tcsc') ?></strong></div>
			<div class="pre-line"><?= ($ph->g_tcsc) ? $ph->g_tcsc : "-" ?></div>
		</td>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_soma') ?></strong></div>
			<div class="pre-line"><?= ($ph->g_soma)? $ph->g_soma : "-" ?></div>
		</td>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_lymphatic') ?></strong></div>
			<div class="pre-line"><?= ($ph->g_lymphatic)? $ph->g_lymphatic : "-" ?></div>
		</td>
	</tr>
</table>
<br/>
<div style="font-size: 120%;">
	<strong>3) <?= $this->lang->line('w_regional_examination') ?></strong>
</div>
<table class="datatable" style="width: 100%;">
	<tr>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_head') ?></strong></div>
			<div class="pre-line"><?= ($ph->r_head) ? $ph->r_head : "-" ?></div>
		</td>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_neck') ?></strong></div>
			<div class="pre-line"><?= ($ph->r_neck)? $ph->r_neck : "-" ?></div>
		</td>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_breasts') ?></strong></div>
			<div class="pre-line"><?= ($ph->r_breasts) ? $ph->r_breasts : "-" ?></div>
		</td>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_chest_and_lungs') ?></strong></div>
			<div class="pre-line"><?= ($ph->r_thorax_lungs)? $ph->r_thorax_lungs : "-" ?></div>
		</td>
	</tr>
	<tr>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_cardiovascular') ?></strong></div>
			<div class="pre-line"><?= ($ph->r_cardiovascular) ? $ph->r_cardiovascular : "-" ?></div>
		</td>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_abdomen') ?></strong></div>
			<div class="pre-line"><?= ($ph->r_abdomen)? $ph->r_abdomen : "-" ?></div>
		</td>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_genitourinary') ?></strong></div>
			<div class="pre-line"><?= ($ph->r_genitourinary) ? $ph->r_genitourinary : "-" ?></div>
		</td>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_neurological') ?></strong></div>
			<div class="pre-line"><?= ($ph->r_neurologic)? $ph->r_neurologic : "-" ?></div>
		</td>
	</tr>
</table>
<br/>
<div style="font-size: 150%; border-top: 1px solid black; border-bottom: 1px solid black;">
	<strong>3. <?= $this->lang->line('w_diagnostic_impression') ?></strong>
</div>


<table class="table text-center mt-1">
	<tr>
		<th>#</th>
		<th><?= $this->lang->line('w_cie10') ?></th>
		<th><?= $this->lang->line('w_description') ?></th>
	</tr>
	<?php foreach($di as $i => $d){ ?>
	<tr>
		<td><?= $i + 1 ?></td>
		<td><?= $d->code ?></td>
		<td class="text-left"><?= $d->description ?></td>
	</tr>
	<?php } ?>
</table>
<div class="title_2 mt-4">
	<strong>4. <?= $this->lang->line('w_result') ?></strong>
</div>
<table class="table table-3 text-center mt-1">
	<tr>
		<th><?= $this->lang->line('w_diagnosis') ?> [<?= $re->type ?>]</th>
		<th><?= $this->lang->line('w_workplan') ?></th>
		<th><?= $this->lang->line('w_treatment') ?></th>
	</tr>
	<tr>
		<td class="text-left pre-line"><?= $re->diagnosis ?></td>
		<td class="text-left pre-line"><?= $re->plan ?></td>
		<td class="text-left pre-line"><?= $re->treatment ?></td>
	</tr>
</table>
<div class="title_2 mt-4">
	<strong>5. <?= $this->lang->line('w_auxiliary_exam') ?></strong>
</div>
<div class="title_3 mt-3">
	<strong>1) <?= $this->lang->line('w_laboratory') ?></strong>
</div>
<table class="table text-center mt-1">
	<tr>
		<th>#</th>
		<th><?= $this->lang->line('w_type') ?></th>
		<th><?= $this->lang->line('w_profile') ?></th>
		<th style="width: 60%;"><?= $this->lang->line('w_exams') ?></th>
	</tr>
	<?php foreach($ex_profiles as $i => $ep){ ?>
	<tr>
		<td><?= $i + 1 ?></td>
		<td><?= $ep->type ?></td>
		<td><?= $ep->name ?></td>
		<td class="text-left"><?= $ep->exams ?></td>
	</tr>
	<?php } foreach($ex_examinations as $j => $ee){ ?>
	<tr>
		<td><?= $i + $j + 2 ?></td>
		<td><?= $ee->type ?></td>
		<td>-</td>
		<td class="text-left"><?= $ee->name ?></td>
	</tr>
	<?php } ?>
</table>
<div class="title_3 mt-3">
	<strong>2) <?= $this->lang->line('w_image') ?></strong>
</div>
<table class="table text-center mt-1">
	<tr>
		<th>#</th>
		<th><?= $this->lang->line('w_category') ?></th>
		<th style="width: 60%;"><?= $this->lang->line('w_image') ?></th>
	</tr>
	<?php foreach($im as $i => $item){ ?>
	<tr>
		<td><?= $i + 1 ?></td>
		<td><?= $item->category ?></td>
		<td class="text-left"><?= $item->name ?></td>
	</tr>
	<?php } ?>
</table>
<div class="title_2 mt-4">
	<strong>6. <?= $this->lang->line('w_treatment') ?></strong>
</div>
<div class="title_3 mt-3">
	<strong>1) <?= $this->lang->line('w_medicine') ?></strong>
</div>
<table class="table text-center mt-1">
	<tr>
		<th>#</th>
		<th><?= $this->lang->line('w_medicine') ?></th>
		<th style="width: 60%;"><?= $this->lang->line('w_detail') ?></th>
	</tr>
	<?php foreach($me as $i => $m){ ?>
	<tr>
		<td><?= $i + 1 ?></td>
		<td><?= $m->medicine ?></td>
		<td class="text-left"><?= $m->sub_txt ?></td>
	</tr>
	<?php } ?>
</table>
<div class="title_3 mt-3">
	<strong>2) <?= $this->lang->line('w_physical_therapy') ?></strong>
</div>
<table class="table text-center mt-1">
	<tr>
		<th>#</th>
		<th><?= $this->lang->line('w_therapy') ?></th>
		<th style="width: 60%;"><?= $this->lang->line('w_detail') ?></th>
	</tr>
	<?php foreach($th as $i => $t){ ?>
	<tr>
		<td><?= $i + 1 ?></td>
		<td><?= $t->physical_therapy ?></td>
		<td class="text-left"><?= $t->sub_txt ?></td>
	</tr>
	<?php } ?>
</table>
<table class="table table_2 text-center" style="margin-top: 150px; border: 1px solid #000;">
	<tr>
		<td style="width: 50%; height: 150px; border-right: 1px solid #000;"></td>
		<td></td>
	</tr>
	<tr>
		<th style="border-right: 1px solid #000;">Int. Medicina</th>
		<th>Dr. <?= $doctor->name ?> / <?= $doctor->data->license ?></th>
	</tr>
</table>
</body>
</html>