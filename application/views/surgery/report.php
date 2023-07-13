<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<style>
	html, body{padding: 1rem; font-size: 12px; font-family: 'poppins', sans-serif; line-height: 1.5; color: black;}
	h2{margin:0; padding: 0;}
	hr{border-top: 1px solid black; border-bottom: 0;}
	table{width: 100%;}
	table td{vertical-align: top; padding-top: 5px;}
	table td:first-child{padding-left: 0;}
	table td:last-child{padding-right: 0;}
	pre{font-family: 'poppins', sans-serif; font-size: inherit; margin: 0;}
	i{text-decoration: underline;}
	.w-10{width: 10%;}
	.w-20{width: 20%;}
	.w-25{width: 25%;}
	.w-30{width: 30%;}
	.w-33{width: 33.33%;}
	.w-50{width: 50%;}
	.w-66{width: 66.66%;}
	.w-75{width: 75%;}
	.w-100{width: 100%;}
	.p-0{padding: 0;}
	.text-center{text-align: center;}
	.text-uppercase{text-transform: uppercase;}
	</style>
</head>
<body>
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
<div class="text-center text-uppercase"><strong><?= $this->lang->line('title_clinical_history') ?></strong></div>
<br/>
<br/>
<table>
	<tr>
		<td class="w-20"><i><?= $this->lang->line('lb_history_number') ?></i></td>
		<td class="w-30">: <?= $patient->doc_number ?></td>
		<td class="w-20"><i><?= $this->lang->line('lb_entry_mode') ?></i></td>
		<td class="w-30">: <?= $bd->entry_mode_txt ?></td>
	</tr>
	<tr>
		<td class="w-20"><i><?= $this->lang->line('lb_date') ?></i></td>
		<td class="w-30">: <?= $bd->time_f." ".$bd->date_f ?></td>
		<?php if (!strcmp("y", $bd->insurance)){ ?>
		<td class="w-20"><i><?= $this->lang->line('title_insurance') ?></i></td>
		<td class="w-30">: <?= $bd->insurance_name ?></td>
		<?php } ?>
	</tr>
</table>
<br/>
<hr>
<br/>
<div class="text-uppercase"><strong>1. <?= $this->lang->line('title_anamnesis') ?></strong></div>
<br/>
<div class="text-uppercase">1) <?= $this->lang->line('title_personal_information') ?></div>
<table>
	<tr>
		<td class="w-50" colspan="2"><i><?= $this->lang->line('lb_name') ?></i>: <?= $an->name ?></td>
		<td class="w-25"><i><?= $this->lang->line('lb_age') ?></i>: <?= $an->age_txt ?></td>
		<td class="w-25"><i><?= $this->lang->line('lb_sex') ?></i>: <?= $an->sex_txt ?></td>
	</tr>
	<tr>
		<td class="w-50" colspan="2"><i><?= $this->lang->line('lb_address') ?></i>: <?= $an->address ?></td>
		<td class="w-50" colspan="2"><i><?= $this->lang->line('lb_birth_place_day') ?></i>: <?= $an->birthplace.", ".$an->birthday_f ?></td>
	</tr>
	<tr>
		<td class="w-25"><i><?= $this->lang->line('lb_tel') ?></i>: <?= $an->tel ?></td>
		<td class="w-25"><i><?= $this->lang->line('lb_responsible') ?></i>: <?= $an->responsible ?></td>
		<td class="w-25"><i><?= $this->lang->line('lb_place_of_origin') ?></i>: <?= $an->provenance_place ?></td>
		<td class="w-25"><i><?= $this->lang->line('lb_last_trips') ?></i>: <?= $an->last_trips ?></td>
	</tr>
	<tr>
		<td class="w-25"><i><?= $this->lang->line('lb_race') ?></i>: <?= $an->race ?></td>
		<td class="w-25"><i><?= $this->lang->line('lb_marital_status') ?></i>: <?= $an->civil_status_txt ?></td>
		<td class="w-25"><i><?= $this->lang->line('lb_occupation') ?></i>: <?= $an->occupation ?></td>
		<td class="w-25"><i><?= $this->lang->line('lb_religion') ?></i>: <?= $an->religion ?></td>
	</tr>
</table>
<br/>
<div class="text-uppercase">2) <?= $this->lang->line('title_current_illness') ?></div>
<table>
	<tr>
		<td class="w-33"><i><?= $this->lang->line('lb_illness_time') ?></i>: <?= $an->illness_time ?></td>
		<td class="w-33"><i><?= $this->lang->line('lb_start') ?></i>: <?= $an->illness_start ?></td>
		<td class="w-33"><i><?= $this->lang->line('lb_grade') ?></i>: <?= $an->illness_course ?></td>
	</tr>
	<tr>
		<td class="w-33"><i><?= $this->lang->line('lb_main_symptoms') ?></i></td>
		<td class="w-66" colspan="2"><pre><?= $an->illness_main_symptoms ?></pre></td>
	</tr>
	<tr>
		<td class="w-33"><i><?= $this->lang->line('lb_story') ?></i></td>
		<td class="w-66" colspan="2"><pre><?= $an->illness_story ?></pre></td>
	</tr>
</table>
<br/>
<div class="text-uppercase">3) <?= $this->lang->line('title_biological_functions') ?></div>
<table>
	<tr>
		<td class="w-25"><i><?= $this->lang->line('lb_appetite') ?></i>: <?= $an->func_bio_appetite ?></td>
		<td class="w-25"><i><?= $this->lang->line('lb_urine') ?></i>: <?= $an->func_bio_urine ?></td>
		<td class="w-25"><i><?= $this->lang->line('lb_thirst') ?></i>: <?= $an->func_bio_thirst ?></td>
		<td class="w-25"><i><?= $this->lang->line('lb_bowel_movements') ?></i>: <?= $an->func_bio_bowel_movements ?></td>
	</tr>
	<tr>
		<td class="w-25"><i><?= $this->lang->line('lb_sweat') ?></i>: <?= $an->func_bio_sweat ?></td>
		<td class="w-25"><i><?= $this->lang->line('lb_weight_') ?></i>: <?= $an->func_bio_weight ?></td>
		<td class="w-25"><i><?= $this->lang->line('lb_sleep') ?></i>: <?= $an->func_bio_sleep ?></td>
		<td class="w-25"><i><?= $this->lang->line('lb_encouragement') ?></i>: <?= $an->func_bio_encouragement ?></td>
	</tr>
</table>
<br/>
<div class="text-uppercase">4) <?= $this->lang->line('title_personal_background') ?></div>
<br/>
<div class="text-uppercase">a. <?= $this->lang->line('title_pathological') ?></div>
<table>
	<tr>
		<td class="w-100" colspan="4"><i><?= $this->lang->line('lb_previous_illnesses') ?></i>: <?= $an->patho_pre_illnesses_txt ?></td>
	</tr>
	<tr>
		<td class="w-50" colspan="2"><i><?= $this->lang->line('lb_previous_hospitalizations') ?></i><pre><?= $an->patho_pre_hospitalization ?></pre></td>
		<td class="w-50" colspan="2"><i><?= $this->lang->line('lb_previous_surgeries') ?></i><pre><?= $an->patho_pre_surgery ?></pre></td>
	</tr>
	<tr>
		<td class="w-50" colspan="2"><i><?= $this->lang->line('lb_ram') ?></i>: <?= $an->patho_ram ?></td>
		<td class="w-50" colspan="2"><i><?= $this->lang->line('lb_transfusions') ?></i>: <?= $an->patho_transfusion ?></td>
	</tr>
	<tr>
		<td class="w-100" colspan="4"><i><?= $this->lang->line('lb_prior_medication') ?></i>: <?= $an->patho_pre_medication ?></td>
	</tr>
</table>
<br/>
<div class="text-uppercase">b. <?= $this->lang->line('title_gynecological') ?></div>
<table>
	<tr>
		<td class="w-25"><i><?= $this->lang->line('lb_fur') ?></i>: <?= $an->gyne_fur ?></td>
		<td class="w-25"><i><?= $this->lang->line('lb_g') ?></i>: <?= $an->gyne_g ?></td>
		<td class="w-25"><i><?= $this->lang->line('lb_p') ?></i>: <?= $an->gyne_p ?></td>
		<td class="w-25"><i><?= $this->lang->line('lb_mac') ?></i>: <?= $an->gyne_mac ?></td>
	</tr>
</table>
<br/>
<div class="text-uppercase">c. <?= $this->lang->line('title_family_background') ?></div>
<table>
	<tr>
		<td class="w-100" colspan="4"><pre><?= $an->family_history ?></pre></td>
	</tr>
</table>
<br/>
<hr>
<br/>
<div class="text-uppercase"><strong>2. <?= $this->lang->line('title_physical_exam') ?></strong></div>
<br/>
<div class="text-uppercase">1) <?= $this->lang->line('title_vital_functions') ?></div>
<table>
	<tr>
		<td class="w-25"><i><?= $this->lang->line('lb_pa') ?></i>: <?= $ph->v_pa ?></td>
		<td class="w-25"><i><?= $this->lang->line('lb_fc') ?></i>: <?= $ph->v_fc ?></td>
		<td class="w-25"><i><?= $this->lang->line('lb_fr') ?></i>: <?= $ph->v_fr ?></td>
		<td class="w-25"><i><?= $this->lang->line('lb_temperature') ?></i>: <?= $ph->v_temperature ?></td>
	</tr>
	<tr>
		<td class="w-25"><i><?= $this->lang->line('lb_weight') ?></i>: <?= $ph->v_weight ?></td>
		<td class="w-25"><i><?= $this->lang->line('lb_height') ?></i>: <?= $ph->v_height ?></td>
		<td class="w-25"><i><?= $this->lang->line('lb_bmi') ?></i>: <?= $ph->v_imc ?></td>
		<td class="w-25"><i><?= $this->lang->line('lb_class') ?></i>: <?= $ph->v_imc_class ?></td>
	</tr>
</table>
<br/>
<div class="text-uppercase">2) <?= $this->lang->line('title_general_exam') ?></div>
<table>
	<tr>
		<td class="w-33"><i><?= $this->lang->line('lb_appearance') ?></i><pre><?= $ph->g_appearance ?></pre></td>
		<td class="w-33"><i><?= $this->lang->line('lb_skin') ?></i><pre><?= $ph->g_skin ?></pre></td>
		<td class="w-33"><i><?= $this->lang->line('lb_tcsc') ?></i><pre><?= $ph->g_tcsc ?></pre></td>
	</tr>
	<tr>
		<td class="w-33"><i><?= $this->lang->line('lb_soma') ?></i><pre><?= $ph->g_soma ?></pre></td>
		<td class="w-33"><i><?= $this->lang->line('lb_lymphatic') ?></i><pre><?= $ph->g_lymphatic ?></pre></td>
	</tr>
</table>
<br/>
<div class="text-uppercase">2) <?= $this->lang->line('title_regional_examination') ?></div>
<table>
	<tr>
		<td class="w-25"><i><?= $this->lang->line('lb_head') ?></i>: <?= $ph->r_head ?></td>
		<td class="w-25"><i><?= $this->lang->line('lb_neck') ?></i>: <?= $ph->r_neck ?></td>
		<td class="w-25"><i><?= $this->lang->line('lb_breasts') ?></i>: <?= $ph->r_breasts ?></td>
		<td class="w-25"><i><?= $this->lang->line('lb_chest_and_lungs') ?></i>: <?= $ph->r_thorax_lungs ?></td>
	</tr>
	<tr>
		<td class="w-25"><i><?= $this->lang->line('lb_cardiovascular') ?></i>: <?= $ph->r_cardiovascular ?></td>
		<td class="w-25"><i><?= $this->lang->line('lb_abdomen') ?></i>: <?= $ph->r_abdomen ?></td>
		<td class="w-25"><i><?= $this->lang->line('lb_genitourinary') ?></i>: <?= $ph->r_genitourinary ?></td>
		<td class="w-25"><i><?= $this->lang->line('lb_neurological') ?></i>: <?= $ph->r_neurologic ?></td>
	</tr>
</table>
<br/>
<hr>
<br/>
<div class="text-uppercase"><strong>3. <?= $this->lang->line('lb_diagnostic_impression') ?></strong></div>
<br/>
<table>
	<?php foreach($di as $d){ ?>
	<tr><td><?= $d->code." - ".$d->description ?></td></tr>
	<?php } ?>
</table>
<br/>
<hr>

<div style="display: none;">
	<br/>
	<div class="text-uppercase"><strong>4. <?= $this->lang->line('title_result') ?></strong></div>
	<br/>
	<table>
		<tr>
			<td class="w-33"><i><?= $this->lang->line('lb_diagnosis') ?></i><pre><?= $re->diagnosis ?></pre></td>
			<td class="w-33"><i><?= $this->lang->line('lb_workplan') ?></i><pre><?= $re->plan ?></pre></td>
			<td class="w-33"><i><?= $this->lang->line('lb_treatment') ?></i><pre><?= $re->treatment ?></pre></td>
		</tr>
	</table>
	<br/>
	<hr>
	<br/>
	<div class="text-uppercase"><strong>5. <?= $this->lang->line('title_auxiliary_exam') ?></strong></div>
	<br/>
	<table>
		<?php foreach($ex_profiles as $p){ ?>
		<tr>
			<td class="w-25"><?= $this->lang->line('txt_profile') ?></td>
			<td class="w-75"><?= $p->name ?><br/><?= $p->exams ?></td>
		</tr>
		<?php } foreach($ex_examinations as $e){ ?>
		<tr>
			<td class="w-25"><?= $this->lang->line('txt_exam') ?></td>
			<td class="w-75" colspan="3"><?= $e->name ?></td>
		</tr>
		<?php } foreach($images_ap as $i){ ?>
		<tr>
			<td class="w-25"><?= $i->category ?></td>
			<td class="w-75" colspan="3"><?= $i->image ?></td>
		</tr>
		<?php } ?>
	</table>
	<br/>
	<hr>
	<br/>
	<div class="text-uppercase"><strong>6. <?= $this->lang->line('title_treatment') ?></strong></div>
	<br/>
	<table>
		<?php foreach($me as $m){ ?>
		<tr>
			<td class="w-25"><?= $this->lang->line('lb_medicine') ?></td>
			<td class="w-75"><?= $m->medicine ?><br/><?= $m->sub_txt ?></td>
		</tr>
		<?php } foreach($th as $t){ ?>
		<tr>
			<td class="w-25"><?= $this->lang->line('title_physical_therapy') ?></td>
			<td class="w-75"><?= $t->physical_therapy ?><br/><?= $t->sub_txt ?></td>
		</tr>
		<?php } ?>
	</table>
</div>
<table style="margin-top: 100px; text-align: center;">
	<tr>
		<td class="w-10"></td>
		<td class="w-30" style="border-bottom: 1px solid black;"></td>
		<td class="w-20"></td>
		<td class="w-30" style="border-bottom: 1px solid black;"></td>
		<td class="w-10"></td>
	</tr>
	<tr>
		<td class="w-10"></td>
		<td class="w-30">Int. Medicina</td>
		<td class="w-20"></td>
		<td class="w-30">Firma Medico</td>
		<td class="w-10"></td>
	</tr>
</table>
</body>
</html>

