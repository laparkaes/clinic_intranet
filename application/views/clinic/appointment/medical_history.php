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
<div class="row g-3">
	<div class="col-md-3">
		<label class="form-label"># de Historia</label>
		<input type="text" class="form-control" value="<?= $patient->doc_number ?>" readonly>
	</div>
	<div class="col-md-3">
		<label class="form-label">Fecha y Hora</label>
		<input type="text" class="form-control" value="<?= $bd->entered_at ?>" readonly>
	</div>
	<div class="col-md-3">
		<label class="form-label">Modo de Ingreso</label>
		<input type="text" class="form-control" value="<?= $bd->entry_mode ?>" readonly>
	</div>
	<div class="col-md-3">
		<label class="form-label">Seguro</label>
		<input type="text" class="form-control" value="<?= ($bd->insurance_name) ? $bd->insurance_name : "-" ?>" readonly>
	</div>
</div>
<div class="row g-3 mt-3" id="title_anamnesis">
	<div class="col-md-12">
		<div class="alert alert-primary alert-dismissible fade show py-2" role="alert">
			<h4 class="m-0"><strong>1. Anamnesis</strong></h4>
		</div>
	</div>
</div>
<div class="row g-3" id="content_anamnesis">
	<div class="col-md-12">
		<h5 class="m-0"><strong>1) Datos Personales</strong></h5>
	</div>
	<div class="col-md-6">
		<label class="form-label">Nombre</label>
		<input type="text" class="form-control" value="<?= ($an->name) ? $an->name: '-' ?>" readonly>
	</div>
	<div class="col-md-6">
		<label class="form-label">Responsable</label>
		<input type="text" class="form-control" value="<?= ($an->responsible) ? $an->responsible: '-' ?>" readonly>
	</div>
	<div class="col-md-3">
		<label class="form-label">Edad</label>
		<input type="text" class="form-control" value="<?= ($an->age) ? $an->age: '-' ?>" readonly>
	</div>
	<div class="col-md-3">
		<label class="form-label">Fecha de Nacimiento</label>
		<input type="text" class="form-control" value="<?= ($an->birthday) ? $an->birthday: '-' ?>" readonly>
	</div>
	<div class="col-md-3">
		<label class="form-label">Lugar de Nacimiento</label>
		<input type="text" class="form-control" value="<?= ($an->birthplace) ? $an->birthplace: '-' ?>" readonly>
	</div>
	<div class="col-md-3">
		<label class="form-label">Lugar de Procedencia</label>
		<input type="text" class="form-control" value="<?= ($an->provenance_place) ? $an->provenance_place: '-' ?>" readonly>
	</div>
	<div class="col-md-3">
		<label class="form-label">Teléfono</label>
		<input type="text" class="form-control" value="<?= ($an->tel) ? $an->tel: '-' ?>" readonly>
	</div>
	<div class="col-md-3">
		<label class="form-label">Sexo</label>
		<input type="text" class="form-control" value="<?= ($an->sex) ? $an->sex: '-' ?>" readonly>
	</div>
	<div class="col-md-3">
		<label class="form-label">Estado Civil</label>
		<input type="text" class="form-control" value="<?= ($an->civil_status) ? $an->civil_status: '-' ?>" readonly>
	</div>
	<div class="col-md-3">
		<label class="form-label">Raza</label>
		<input type="text" class="form-control" value="<?= ($an->race) ? $an->race: '-' ?>" readonly>
	</div>
	<div class="col-md-3">
		<label class="form-label">Ocupación</label>
		<input type="text" class="form-control" value="<?= ($an->occupation) ? $an->occupation: '-' ?>" readonly>
	</div>
	<div class="col-md-3">
		<label class="form-label">Religión</label>
		<input type="text" class="form-control" value="<?= ($an->religion) ? $an->religion: '-' ?>" readonly>
	</div>
	<div class="col-md-6">
		<label class="form-label">Últimos Viajes</label>
		<input type="text" class="form-control" value="<?= ($an->last_trips) ? $an->last_trips: '-' ?>" readonly>
	</div>
	<div class="col-md-12">
		<label class="form-label">Dirección</label>
		<input type="text" class="form-control" value="<?= ($an->address) ? $an->address: '-' ?>" readonly>
	</div>
	<div class="col-md-12 pt-3">
		<h5 class="m-0"><strong>2) Enfermedad Actual</strong></h5>
	</div>
	<div class="col-md-4">
		<label class="form-label">Tiempo de Enfermedad</label>
		<input type="text" class="form-control" value="<?= ($an->illness_time) ? $an->illness_time: '-' ?>" readonly>
	</div>
	<div class="col-md-4">
		<label class="form-label">Inicio</label>
		<input type="text" class="form-control" value="<?= ($an->illness_start) ? $an->illness_start: '-' ?>" readonly>
	</div>
	<div class="col-md-4">
		<label class="form-label">Curso</label>
		<input type="text" class="form-control" value="<?= ($an->illness_course) ? $an->illness_course: '-' ?>" readonly>
	</div>
	<div class="col-md-4">
		<label class="form-label">Síntomas Principales</label>
		<input type="text" class="form-control" value="<?= ($an->illness_main_symptoms) ? $an->illness_main_symptoms: '-' ?>" readonly>
	</div>
	<div class="col-md-8">
		<label class="form-label">Relato</label>
		<input type="text" class="form-control" value="<?= ($an->illness_story) ? $an->illness_story: '-' ?>" readonly>
	</div>
	<div class="col-md-12 pt-3">
		<h5 class="m-0"><strong>3) Funciones Biológicas</strong></h5>
	</div>
	<div class="col-md-3">
		<label class="form-label">Apetito</label>
		<input type="text" class="form-control" value="<?= ($an->func_bio_appetite) ? $an->func_bio_appetite : "-" ?>" readonly>
	</div>
	<div class="col-md-3">
		<label class="form-label">Orina</label>
		<input type="text" class="form-control" value="<?= ($an->func_bio_urine) ? $an->func_bio_urine : "-" ?>" readonly>
	</div>
	<div class="col-md-3">
		<label class="form-label">Sed</label>
		<input type="text" class="form-control" value="<?= ($an->func_bio_thirst)? $an->func_bio_thirst : "-" ?>" readonly>
	</div>
	<div class="col-md-3">
		<label class="form-label">Deposiciones</label>
		<input type="text" class="form-control" value="<?= ($an->func_bio_bowel_movements) ? $an->func_bio_bowel_movements : "-" ?>" readonly>
	</div>
	<div class="col-md-3">
		<label class="form-label">Sudor</label>
		<input type="text" class="form-control" value="<?= ($an->func_bio_sweat) ? $an->func_bio_sweat : "-" ?>" readonly>
	</div>
	<div class="col-md-3">
		<label class="form-label">Peso</label>
		<input type="text" class="form-control" value="<?= ($an->func_bio_weight) ? $an->func_bio_weight : "-" ?>" readonly>
	</div>
	<div class="col-md-3">
		<label class="form-label">Sueño</label>
		<input type="text" class="form-control" value="<?= ($an->func_bio_sleep) ? $an->func_bio_sleep : "-" ?>" readonly>
	</div>
	<div class="col-md-3">
		<label class="form-label">Ánimo</label>
		<input type="text" class="form-control" value="<?= ($an->func_bio_encouragement) ? $an->func_bio_encouragement : "-" ?>" readonly>
	</div>
	<div class="col-md-12 pt-3">
		<h5 class="m-0"><strong>4) Antecedentes Personales</strong></h5>
	</div>
	<div class="col-md-12">
		<strong>A. Patológicos</strong>
	</div>
	<div class="col-md-4">
		<label class="form-label">Enfermedades Previas</label>
		<input type="text" class="form-control" value="<?= ($an->patho_pre_illnesses_txt) ? $an->patho_pre_illnesses_txt : "-" ?>" readonly>
	</div>
	<div class="col-md-4">
		<label class="form-label">Hospitalizaciones Previas</label>
		<input type="text" class="form-control" value="<?= ($an->patho_pre_hospitalization) ? $an->patho_pre_hospitalization : "-" ?>" readonly>
	</div>
	<div class="col-md-4">
		<label class="form-label">Cirugías Previas</label>
		<input type="text" class="form-control" value="<?= ($an->patho_pre_surgery)? $an->patho_pre_surgery : "-" ?>" readonly>
	</div>
	<div class="col-md-4">
		<label class="form-label">RAM</label>
		<input type="text" class="form-control" value="<?= ($an->patho_ram) ? $an->patho_ram : "-" ?>" readonly>
	</div>
	<div class="col-md-4">
		<label class="form-label">Transfusiones</label>
		<input type="text" class="form-control" value="<?= ($an->patho_transfusion)? $an->patho_transfusion : "-" ?>" readonly>
	</div>
	<div class="col-md-4">
		<label class="form-label">Medicación Previa</label>
		<input type="text" class="form-control" value="<?= ($an->patho_pre_medication) ? $an->patho_pre_medication : "-" ?>" readonly>
	</div>
	<div class="col-md-12">
		<label class="form-label">Antecedentes Familiares</label>
		<input type="text" class="form-control" value="<?= ($an->family_history) ? $an->family_history : "-" ?>" readonly>
	</div>
	<div class="col-md-12">
		<strong>B. Ginecológico</strong>
	</div>
	<div class="col-md-3">
		<label class="form-label">FUR</label>
		<input type="text" class="form-control" value="<?= ($an->gyne_fur) ? $an->gyne_fur : "-" ?>" readonly>
	</div>
	<div class="col-md-3">
		<label class="form-label">G</label>
		<input type="text" class="form-control" value="<?= ($an->gyne_g)? $an->gyne_g : "-" ?>" readonly>
	</div>
	<div class="col-md-3">
		<label class="form-label">P</label>
		<input type="text" class="form-control" value="<?= ($an->gyne_p) ? $an->gyne_p : "-" ?>" readonly>
	</div>
	<div class="col-md-3">
		<label class="form-label">MAC</label>
		<input type="text" class="form-control" value="<?= ($an->gyne_mac)? $an->gyne_mac : "-" ?>" readonly>
	</div>
</div>
<div class="row g-3 mt-3" id="title_physical_exam">
	<div class="col-md-12">
		<div class="alert alert-primary alert-dismissible fade show py-2" role="alert">
			<h4 class="m-0"><strong>2. Examen Físico </strong></h4>
		</div>
	</div>
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
<table class="datatable" style="width: 100%;">
	<tr>
		<td style="width: 8%; padding-bottom: 0;">
			<div><strong>#</strong></div>
		</td>
		<td style="width: 17%; padding-bottom: 0;">
			<div><strong><?= $this->lang->line('w_cie10') ?></strong></div>
		</td>
		<td style="width: 75%; padding-bottom: 0;">
			<div><strong><?= $this->lang->line('w_description') ?></strong></div>
		</td>
	</tr>
	<?php foreach($di as $i => $d){ ?>
	<tr>
		<td style="padding-bottom: 0;"><?= $i + 1 ?></td>
		<td style="padding-bottom: 0;"><?= $d->code ?></td>
		<td style="padding-bottom: 0;"><?= $d->description ?></td>
	</tr>
	<?php } ?>
</table>
<br/>
<div style="font-size: 150%; border-top: 1px solid black; border-bottom: 1px solid black;">
	<strong>4. <?= $this->lang->line('w_result') ?></strong>
</div>
<table class="datatable" style="width: 100%;">
	<tr>
		<td style="width: 25%;">
			<div><strong><?= $this->lang->line('w_diagnosis') ?></strong></div>
			<div class="pre-line"><?= $re->type ?><br/><?= ($re->diagnosis) ? $re->diagnosis : null ?></div>
		</td>
		<td style="width: 37.5%;">
			<div><strong><?= $this->lang->line('w_workplan') ?></strong></div>
			<div class="pre-line"><?= ($re->plan) ? $re->plan : "-" ?></div>
		</td>
		<td style="width: 37.5%;">
			<div><strong><?= $this->lang->line('w_treatment') ?></strong></div>
			<div class="pre-line"><?= ($re->treatment) ? $re->treatment : "-" ?></div>
		</td>
	</tr>
</table>
<br/>
<div style="font-size: 150%; border-top: 1px solid black; border-bottom: 1px solid black;">
	<strong>5. <?= $this->lang->line('w_auxiliary_exam') ?></strong>
</div>
<div style="font-size: 120%;">
	<strong>1) <?= $this->lang->line('w_laboratory') ?></strong>
</div>
<?php if ($ex_profiles or $ex_examinations){ ?>
<table class="datatable" style="width: 100%;">
	<tr>
		<td style="width: 8%; padding-bottom: 0;">
			<div><strong>#</strong></div>
		</td>
		<td style="width: 17%; padding-bottom: 0;">
			<div><strong><?= $this->lang->line('w_type') ?></strong></div>
		</td>
		<td style="width: 25%; padding-bottom: 0;">
			<div><strong><?= $this->lang->line('w_profile') ?></strong></div>
		</td>
		<td style="width: 50%; padding-bottom: 0;">
			<div><strong><?= $this->lang->line('w_exams') ?></strong></div>
		</td>
	</tr>
	<?php foreach($ex_profiles as $i => $ep){ ?>
	<tr>
		<td style="padding-bottom: 0;"><?= $i + 1 ?></td>
		<td style="padding-bottom: 0;"><?= $ep->type ?></td>
		<td style="padding-bottom: 0;"><?= $ep->name ?></td>
		<td style="padding-bottom: 0;"><?= $ep->exams ?></td>
	</tr>
	<?php } foreach($ex_examinations as $j => $ee){ ?>
	<tr>
		<td style="padding-bottom: 0;"><?= $i + $j + 2 ?></td>
		<td style="padding-bottom: 0;"><?= $ee->type ?></td>
		<td style="padding-bottom: 0;">-</td>
		<td style="padding-bottom: 0;"><?= $ee->name ?></td>
	</tr>
	<?php } ?>
</table>
<?php }else{ ?>
<div>No se aplica</div>
<?php } ?>
<br/>
<div style="font-size: 120%;">
	<strong>2) <?= $this->lang->line('w_image') ?></strong>
</div>
<?php if ($im){ ?>
<table class="datatable" style="width: 100%;">
	<tr>
		<td style="width: 8%; padding-bottom: 0;">
			<div><strong>#</strong></div>
		</td>
		<td style="width: 17%; padding-bottom: 0;">
			<div><strong><?= $this->lang->line('w_category') ?></strong></div>
		</td>
		<td style="width: 75%; padding-bottom: 0;">
			<div><strong><?= $this->lang->line('w_image') ?></strong></div>
		</td>
	</tr>
	<?php foreach($im as $i => $item){ ?>
	<tr>
		<td style="padding-bottom: 0;"><?= $i + 1 ?></td>
		<td style="padding-bottom: 0;"><?= $item->category ?></td>
		<td style="padding-bottom: 0;"><?= $item->name ?></td>
	</tr>
	<?php } ?>
</table>
<?php }else{ ?>
<div>No se aplica</div>
<?php } ?>
<br/>
<div style="font-size: 150%; border-top: 1px solid black; border-bottom: 1px solid black;">
	<strong>6. <?= $this->lang->line('w_treatment') ?></strong>
</div>
<div style="font-size: 120%;">
	<strong>1) <?= $this->lang->line('w_medicine') ?></strong>
</div>
<?php if ($me){ ?>
<table class="datatable" style="width: 100%;">
	<tr>
		<td style="width: 8%; padding-bottom: 0;">
			<div><strong>#</strong></div>
		</td>
		<td style="width: 17%; padding-bottom: 0;">
			<div><strong><?= $this->lang->line('w_medicine') ?></strong></div>
		</td>
		<td style="width: 75%; padding-bottom: 0;">
			<div><strong><?= $this->lang->line('w_detail') ?></strong></div>
		</td>
	</tr>
	<?php foreach($me as $i => $m){ ?>
	<tr>
		<td style="padding-bottom: 0;"><?= $i + 1 ?></td>
		<td style="padding-bottom: 0;"><?= $m->medicine ?></td>
		<td style="padding-bottom: 0;"><?= $m->sub_txt ?></td>
	</tr>
	<?php } ?>
</table>
<?php }else{ ?>
<div>No se aplica</div>
<?php } ?>
<br/>
<div style="font-size: 120%;">
	<strong>2) <?= $this->lang->line('w_physical_therapy') ?></strong>
</div>
<?php if ($th){ ?>
<table class="datatable">
	<tr>
		<td style="width: 8%; padding-bottom: 0;">
			<div><strong>#</strong></div>
		</td>
		<td style="width: 17%; padding-bottom: 0;">
			<div><strong><?= $this->lang->line('w_therapy') ?></strong></div>
		</td>
		<td style="width: 75%; padding-bottom: 0;">
			<div><strong><?= $this->lang->line('w_detail') ?></strong></div>
		</td>
	</tr>
	<?php foreach($th as $i => $t){ ?>
	<tr>
		<td style="padding-bottom: 0;"><?= $i + 1 ?></td>
		<td style="padding-bottom: 0;"><?= $t->physical_therapy ?></td>
		<td style="padding-bottom: 0;"><?= $t->sub_txt ?></td>
	</tr>
	<?php } ?>
</table>
<?php }else{ ?>
<div>No se aplica</div>
<?php } ?>