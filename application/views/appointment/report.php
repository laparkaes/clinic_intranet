<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<style>
	html, body{padding: 0px; font-size: 12px; font-family: 'poppins', sans-serif; line-height: 1.5; color: black;}
	.title_1{font-size: 150%;}
	.title_2{font-size: 135%;}
	.title_3{font-size: 120%;}
	.text-center{text-align: center;}
	.text-left{text-align: left;}
	
	table {border-collapse: collapse;}
	.table {width: 100%; max-width: 100%; margin-bottom: 1rem; background-color: transparent; border-bottom: 1px solid #000;}
	.table-glue {margin-bottom: 0; border-bottom: 0;}

	th {background-color: #ddd;}
	.table td, .table th {padding: .5rem; vertical-align: top; border-top: 1px solid #000;}
	.table thead th {vertical-align: bottom; border-bottom: 2px solid #000;}
	
	.table-5 td, .table-5 th {width: 20%;}
	.table-4 td, .table-4 th {width: 25%;}
	.table-3 td, .table-3 th {width: 33.33%;}
	.table-2 td, .table-2 th {width: 50%;}

	.mt-1{margin-top: 0.5rem;}
	.mt-2{margin-top: 1rem;}
	.mt-3{margin-top: 2rem;}
	.mt-4{margin-top: 3rem;}
	
	.pre-line {white-space: pre-line;}
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
<div class="title_1 text-center">
	<strong><?= $this->lang->line('title_clinical_history') ?></strong>
</div>
<table class="table table-4 text-center mt-4">
	<tr>
		<th><?= $this->lang->line('lb_date') ?></th>
		<th><?= $this->lang->line('lb_history_number') ?></th>
		<th><?= $this->lang->line('lb_entry_mode') ?></th>
		<th><?= $this->lang->line('title_insurance') ?></th>
	</tr>
	<tr>
		<td><?= $bd->entered_at ?></td>
		<td><?= $patient->doc_number ?></td>
		<td><?= $bd->entry_mode ?></td>
		<td><?= $bd->insurance_name ?></td>
	</tr>
</table>
<div class="title_2 mt-4">
	<strong>1. <?= $this->lang->line('title_anamnesis') ?></strong>
</div>
<div class="title_3 mt-2">
	<strong>1) <?= $this->lang->line('title_personal_information') ?></strong>
</div>
<table class="table table-4 text-center mt-1">
	<tr>
		<th colspan="2"><?= $this->lang->line('lb_name') ?></th>
		<th><?= $this->lang->line('lb_age') ?></th>
		<th><?= $this->lang->line('lb_sex') ?></th>
	</tr>
	<tr>
		<td colspan="2"><?= $an->name ?></td>
		<td><?= $an->age ?></td>
		<td><?= $an->sex ?></td>
	</tr>
	<tr>
		<th colspan="2"><?= $this->lang->line('lb_address') ?></th>
		<th><?= $this->lang->line('lb_birth_place') ?></th>
		<th><?= $this->lang->line('lb_birth_day') ?></th>
	</tr>
	<tr>
		<td colspan="2"><?= $an->address ?></td>
		<td><?= $an->birthplace ?></td>
		<td><?= $an->birthday ?></td>
	</tr>
	<tr>
		<th><?= $this->lang->line('lb_tel') ?></th>
		<th><?= $this->lang->line('lb_responsible') ?></th>
		<th><?= $this->lang->line('lb_place_of_origin') ?></th>
		<th><?= $this->lang->line('lb_last_trips') ?></th>
	</tr>
	<tr>
		<td><?= $an->tel ?></td>
		<td><?= $an->responsible ?></td>
		<td><?= $an->provenance_place ?></td>
		<td><?= $an->last_trips ?></td>
	</tr>
	<tr>
		<th><?= $this->lang->line('lb_race') ?></th>
		<th><?= $this->lang->line('lb_marital_status') ?></th>
		<th><?= $this->lang->line('lb_occupation') ?></th>
		<th><?= $this->lang->line('lb_religion') ?></th>
	</tr>
	<tr>
		<td><?= $an->race ?></td>
		<td><?= $an->civil_status ?></td>
		<td><?= $an->occupation ?></td>
		<td><?= $an->religion ?></td>
	</tr>
</table>
<div class="title_3 mt-3">
	<strong>2) <?= $this->lang->line('title_current_illness') ?></strong>
</div>
<table class="table table-3 table-glue text-center mt-1">
	<tr>
		<th><?= $this->lang->line('lb_illness_time') ?></th>
		<th><?= $this->lang->line('lb_start') ?></th>
		<th><?= $this->lang->line('lb_grade') ?></th>
	</tr>
	<tr>
		<td><?= $an->illness_time ?></td>
		<td><?= $an->illness_start ?></td>
		<td><?= $an->illness_course ?></td>
	</tr>
</table>
<table class="table table-2 text-center">
	<tr>
		<th><?= $this->lang->line('lb_main_symptoms') ?></th>
		<th><?= $this->lang->line('lb_story') ?></th>
	</tr>
	<tr>
		<td class="text-left pre-line"><?= $an->illness_main_symptoms ?></td>
		<td class="text-left pre-line"><?= $an->illness_story ?></td>
	</tr>
</table>
<div class="title_3 mt-3">
	<strong>3) <?= $this->lang->line('title_biological_functions') ?></strong>
</div>
<table class="table table-4 text-center mt-1">
	<tr>
		<th><?= $this->lang->line('lb_appetite') ?></th>
		<th><?= $this->lang->line('lb_urine') ?></th>
		<th><?= $this->lang->line('lb_thirst') ?></th>
		<th><?= $this->lang->line('lb_bowel_movements') ?></th>
	</tr>
	<tr>
		<td><?= $an->func_bio_appetite ?></td>
		<td><?= $an->func_bio_urine ?></td>
		<td><?= $an->func_bio_thirst ?></td>
		<td><?= $an->func_bio_bowel_movements ?></td>
	</tr>
	<tr>
		<th><?= $this->lang->line('lb_sweat') ?></th>
		<th><?= $this->lang->line('lb_weight_') ?></th>
		<th><?= $this->lang->line('lb_sleep') ?></th>
		<th><?= $this->lang->line('lb_encouragement') ?></th>
	</tr>
	<tr>
		<td><?= $an->func_bio_sweat ?></td>
		<td><?= $an->func_bio_weight ?></td>
		<td><?= $an->func_bio_sleep ?></td>
		<td><?= $an->func_bio_encouragement ?></td>
	</tr>
</table>
<div class="title_3 mt-3">
	<strong>4) <?= $this->lang->line('title_personal_background') ?></strong>
</div>
<table class="table table-2 text-center mt-1">
	<tr>
		<th colspan="2" class="text-left">a. <?= $this->lang->line('title_pathological') ?></th>
	</tr>
	<tr>
		<th colspan="2"><?= $this->lang->line('lb_previous_illnesses') ?></th>
	</tr>
	<tr>
		<td colspan="2"><?= $an->patho_pre_illnesses_txt ?></td>
	</tr>
	<tr>
		<th><?= $this->lang->line('lb_previous_hospitalizations') ?></th>
		<th><?= $this->lang->line('lb_previous_surgeries') ?></th>
	</tr>
	<tr>
		<td class="pre-line"><?= $an->patho_pre_hospitalization ?></td>
		<td class="pre-line"><?= $an->patho_pre_surgery ?></td>
	</tr>
	<tr>
		<th><?= $this->lang->line('lb_ram') ?></th>
		<th><?= $this->lang->line('lb_transfusions') ?></th>
	</tr>
	<tr>
		<td><?= $an->patho_ram ?></td>
		<td><?= $an->patho_transfusion ?></td>
	</tr>
	<tr>
		<th colspan="2"><?= $this->lang->line('lb_prior_medication') ?></th>
	</tr>
	<tr>
		<td colspan="2"><?= $an->patho_pre_medication ?></td>
	</tr>
</table>
<table class="table table-4 text-center mt-1">
	<tr>
		<th colspan="4" class="text-left">b. <?= $this->lang->line('title_gynecological') ?></th>
	</tr>
	<tr>
		<th><?= $this->lang->line('lb_fur') ?></th>
		<th><?= $this->lang->line('lb_g') ?></th>
		<th><?= $this->lang->line('lb_p') ?></th>
		<th><?= $this->lang->line('lb_mac') ?></th>
	</tr>
	<tr>
		<td><?= $an->gyne_fur ?></td>
		<td><?= $an->gyne_g ?></td>
		<td><?= $an->gyne_p ?></td>
		<td><?= $an->gyne_mac ?></td>
	</tr>
</table>
<table class="table table-2 text-center mt-1">
	<tr>
		<th colspan="2" class="text-left">c. <?= $this->lang->line('title_family_background') ?></th>
	</tr>
	<tr>
		<td colspan="2" class="text-left pre-line"><?= $an->family_history ?></td>
	</tr>
</table>
<div class="title_2 mt-4">
	<strong>2. <?= $this->lang->line('title_physical_exam') ?></strong>
</div>
<div class="title_3 mt-3">
	<strong>1) <?= $this->lang->line('title_vital_functions') ?></strong>
</div>
<table class="table table-4 text-center mt-1">
	<tr>
		<th><?= $this->lang->line('lb_pa') ?></th>
		<th><?= $this->lang->line('lb_fc') ?></th>
		<th><?= $this->lang->line('lb_fr') ?></th>
		<th><?= $this->lang->line('lb_temperature') ?></th>
	</tr>
	<tr>
		<td><?= $ph->v_pa ?></td>
		<td><?= $ph->v_fc ?></td>
		<td><?= $ph->v_fr ?></td>
		<td><?= $ph->v_temperature ?></td>
	</tr>
	<tr>
		<th><?= $this->lang->line('lb_weight') ?></th>
		<th><?= $this->lang->line('lb_height') ?></th>
		<th><?= $this->lang->line('lb_bmi') ?></th>
		<th><?= $this->lang->line('lb_class') ?></th>
	</tr>
	<tr>
		<td><?= $ph->v_weight ?></td>
		<td><?= $ph->v_height ?></td>
		<td><?= $ph->v_imc ?></td>
		<td><?= $ph->v_imc_class ?></td>
	</tr>
</table>
<div class="title_3 mt-3">
	<strong>2) <?= $this->lang->line('title_general_exam') ?></strong>
</div>
<table class="table table-2 table-glue text-center mt-1">
	<tr>
		<th><?= $this->lang->line('lb_appearance') ?></th>
		<th><?= $this->lang->line('lb_skin') ?></th>
	</tr>
	<tr>
		<td class="text-left pre-line"><?= $ph->g_appearance ?></td>
		<td class="text-left pre-line"><?= $ph->g_skin ?></td>
	</tr>
</table>
<table class="table table-3 text-center">
	<tr>
		<th><?= $this->lang->line('lb_tcsc') ?></th>
		<th><?= $this->lang->line('lb_soma') ?></th>
		<th><?= $this->lang->line('lb_lymphatic') ?></th>
	</tr>
	<tr>
		<td class="text-left pre-line"><?= $ph->g_tcsc ?></td>
		<td class="text-left pre-line"><?= $ph->g_soma ?></td>
		<td class="text-left pre-line"><?= $ph->g_lymphatic ?></td>
	</tr>
</table>
<div class="title_3 mt-3">
	<strong>3) <?= $this->lang->line('title_regional_examination') ?></strong>
</div>
<table class="table table-4 text-center mt-1">
	<tr>
		<th><?= $this->lang->line('lb_head') ?></th>
		<th><?= $this->lang->line('lb_neck') ?></th>
		<th><?= $this->lang->line('lb_breasts') ?></th>
		<th><?= $this->lang->line('lb_chest_and_lungs') ?></th>
	</tr>
	<tr>
		<td class="text-left pre-line"><?= $ph->r_head ?></td>
		<td class="text-left pre-line"><?= $ph->r_neck ?></td>
		<td class="text-left pre-line"><?= $ph->r_breasts ?></td>
		<td class="text-left pre-line"><?= $ph->r_thorax_lungs ?></td>
	</tr>
	<tr>
		<th><?= $this->lang->line('lb_cardiovascular') ?></th>
		<th><?= $this->lang->line('lb_abdomen') ?></th>
		<th><?= $this->lang->line('lb_genitourinary') ?></th>
		<th><?= $this->lang->line('lb_neurological') ?></th>
	</tr>
	<tr>
		<td class="text-left pre-line"><?= $ph->r_cardiovascular ?></td>
		<td class="text-left pre-line"><?= $ph->r_abdomen ?></td>
		<td class="text-left pre-line"><?= $ph->r_genitourinary ?></td>
		<td class="text-left pre-line"><?= $ph->r_neurologic ?></td>
	</tr>
</table>
<div class="title_2 mt-4">
	<strong>3. <?= $this->lang->line('title_diagnostic_impression') ?></strong>
</div>
<table class="table text-center mt-1">
	<tr>
		<th>#</th>
		<th><?= $this->lang->line('th_cie10') ?></th>
		<th><?= $this->lang->line('th_description') ?></th>
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
	<strong>4. <?= $this->lang->line('title_result') ?></strong>
</div>
<table class="table table-3 text-center mt-1">
	<tr>
		<th><?= $this->lang->line('lb_diagnosis') ?> [<?= $re->type ?>]</th>
		<th><?= $this->lang->line('lb_workplan') ?></th>
		<th><?= $this->lang->line('lb_treatment') ?></th>
	</tr>
	<tr>
		<td class="text-left pre-line"><?= $re->diagnosis ?></td>
		<td class="text-left pre-line"><?= $re->plan ?></td>
		<td class="text-left pre-line"><?= $re->treatment ?></td>
	</tr>
</table>
<div class="title_2 mt-4">
	<strong>5. <?= $this->lang->line('title_auxiliary_exam') ?></strong>
</div>
<div class="title_3 mt-3">
	<strong>1) <?= $this->lang->line('title_laboratory') ?></strong>
</div>
<table class="table text-center mt-1">
	<tr>
		<th>#</th>
		<th><?= $this->lang->line('th_type') ?></th>
		<th><?= $this->lang->line('th_profile') ?></th>
		<th style="width: 60%;"><?= $this->lang->line('th_exams') ?></th>
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
	<strong>2) <?= $this->lang->line('title_image') ?></strong>
</div>
<table class="table text-center mt-1">
	<tr>
		<th>#</th>
		<th><?= $this->lang->line('th_category') ?></th>
		<th style="width: 60%;"><?= $this->lang->line('th_image') ?></th>
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
	<strong>6. <?= $this->lang->line('title_treatment') ?></strong>
</div>
<div class="title_3 mt-3">
	<strong>1) <?= $this->lang->line('title_medicine') ?></strong>
</div>
<table class="table text-center mt-1">
	<tr>
		<th>#</th>
		<th><?= $this->lang->line('th_medicine') ?></th>
		<th style="width: 60%;"><?= $this->lang->line('th_detail') ?></th>
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
	<strong>2) <?= $this->lang->line('title_physical_therapy') ?></strong>
</div>
<table class="table text-center mt-1">
	<tr>
		<th>#</th>
		<th><?= $this->lang->line('th_therapy') ?></th>
		<th style="width: 60%;"><?= $this->lang->line('th_detail') ?></th>
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

