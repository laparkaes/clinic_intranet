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
			<h4 class="m-0"><strong>2. Examen Físico</strong></h4>
		</div>
	</div>
</div>
<div class="row g-3" id="content_physical_exam">
	<div class="col-md-12">
		<h5 class="m-0"><strong>1) Funciones Vitales</strong></h5>
	</div>
	<div class="col-md-3">
		<label class="form-label">PA</label>
		<input type="text" class="form-control" value="<?= ($ph->v_pa) ? $ph->v_pa : "-" ?>" readonly>
	</div>
	<div class="col-md-3">
		<label class="form-label">FC</label>
		<input type="text" class="form-control" value="<?= ($ph->v_fc)? $ph->v_fc : "-" ?>" readonly>
	</div>
	<div class="col-md-3">
		<label class="form-label">FR</label>
		<input type="text" class="form-control" value="<?= ($ph->v_fr) ? $ph->v_fr : "-" ?>" readonly>
	</div>
	<div class="col-md-3">
		<label class="form-label">Temperatura (ºC)</label>
		<input type="text" class="form-control" value="<?= ($ph->v_temperature)? $ph->v_temperature : "-" ?>" readonly>
	</div>
	<div class="col-md-3">
		<label class="form-label">Peso (Kg)</label>
		<input type="text" class="form-control" value="<?= ($ph->v_weight) ? $ph->v_weight : "-" ?>" readonly>
	</div>
	<div class="col-md-3">
		<label class="form-label">Estatura (Cm)</label>
		<input type="text" class="form-control" value="<?= ($ph->v_height)? $ph->v_height : "-" ?>" readonly>
	</div>
	<div class="col-md-3">
		<label class="form-label">IMC</label>
		<input type="text" class="form-control" value="<?= ($ph->v_imc) ? $ph->v_imc : "-" ?>" readonly>
	</div>
	<div class="col-md-3">
		<label class="form-label">Clasificación</label>
		<input type="text" class="form-control" value="<?= ($ph->v_imc_class)? $ph->v_imc_class : "-" ?>" readonly>
	</div>
	<div class="col-md-12">
		<h5 class="m-0 pt-3"><strong>2) Examen General</strong></h5>
	</div>
	<div class="col-md-12">
		<label class="form-label">Apariencia</label>
		<input type="text" class="form-control" value="<?= ($ph->g_appearance) ? $ph->g_appearance : "-" ?>" readonly>
	</div>
	<div class="col-md-3">
		<label class="form-label">Piel</label>
		<input type="text" class="form-control" value="<?= ($ph->g_skin)? $ph->g_skin : "-" ?>" readonly>
	</div>
	<div class="col-md-3">
		<label class="form-label">TCSC</label>
		<input type="text" class="form-control" value="<?= ($ph->g_tcsc) ? $ph->g_tcsc : "-" ?>" readonly>
	</div>
	<div class="col-md-3">
		<label class="form-label">Soma</label>
		<input type="text" class="form-control" value="<?= ($ph->g_soma)? $ph->g_soma : "-" ?>" readonly>
	</div>
	<div class="col-md-3">
		<label class="form-label">Linfático</label>
		<input type="text" class="form-control" value="<?= ($ph->g_lymphatic)? $ph->g_lymphatic : "-" ?>" readonly>
	</div>
	<div class="col-md-12">
		<h5 class="m-0 pt-3"><strong>3) Examen Regional</strong></h5>
	</div>
	<div class="col-md-3">
		<label class="form-label">Cabeza</label>
		<input type="text" class="form-control" value="<?= ($ph->r_head) ? $ph->r_head : "-" ?>" readonly>
	</div>
	<div class="col-md-3">
		<label class="form-label">Cuello</label>
		<input type="text" class="form-control" value="<?= ($ph->r_neck)? $ph->r_neck : "-" ?>" readonly>
	</div>
	<div class="col-md-3">
		<label class="form-label">Mamás</label>
		<input type="text" class="form-control" value="<?= ($ph->r_breasts) ? $ph->r_breasts : "-" ?>" readonly>
	</div>
	<div class="col-md-3">
		<label class="form-label">Tórax y Pulmones</label>
		<input type="text" class="form-control" value="<?= ($ph->r_thorax_lungs)? $ph->r_thorax_lungs : "-" ?>" readonly>
	</div>
	<div class="col-md-3">
		<label class="form-label">Cardiovascular</label>
		<input type="text" class="form-control" value="<?= ($ph->r_cardiovascular) ? $ph->r_cardiovascular : "-" ?>" readonly>
	</div>
	<div class="col-md-3">
		<label class="form-label">Abdomen</label>
		<input type="text" class="form-control" value="<?= ($ph->r_abdomen)? $ph->r_abdomen : "-" ?>" readonly>
	</div>
	<div class="col-md-3">
		<label class="form-label">Genitourinario</label>
		<input type="text" class="form-control" value="<?= ($ph->r_genitourinary) ? $ph->r_genitourinary : "-" ?>" readonly>
	</div>
	<div class="col-md-3">
		<label class="form-label">Neurológico</label>
		<input type="text" class="form-control" value="<?= ($ph->r_neurologic)? $ph->r_neurologic : "-" ?>" readonly>
	</div>
</div>
<div class="row g-3 mt-3" id="title_diagnostic_impression">
	<div class="col-md-12">
		<div class="alert alert-primary alert-dismissible fade show py-2" role="alert">
			<h4 class="m-0"><strong>3. Impresión Diagnóstica</strong></h4>
		</div>
	</div>
</div>
<div class="row g-3" id="content_diagnostic_impression">
	<div class="col-md-2">
		<label class="form-label m-0">Fecha</label>
	</div>
	<div class="col-md-2">
		<label class="form-label m-0">CIE 10</label>
	</div>
	<div class="col-md-8">
		<label class="form-label m-0">Descripción</label>
	</div>
	<?php foreach($di as $i => $d){ ?>
	<div class="col-md-2">
		<input type="text" class="form-control m-0" value="fecha aqui" readonly>
	</div>
	<div class="col-md-2">
		<input type="text" class="form-control m-0" value="<?= $d->code ?>" readonly>
	</div>
	<div class="col-md-8">
		<input type="text" class="form-control m-0" value="<?= $d->description ?>" readonly>
	</div>
	<?php } ?>
</div>
<div class="row g-3 mt-3" id="title_result">
	<div class="col-md-12">
		<div class="alert alert-primary alert-dismissible fade show py-2" role="alert">
			<h4 class="m-0"><strong>4. Resultado</strong></h4>
		</div>
	</div>
</div>
<div class="row g-3" id="content_diagnostic_impression">
	<div class="col-md-2">
		<label class="form-label">Fecha</label>
		<input type="text" class="form-control" value="fecha aqui" readonly>
	</div>
	<div class="col-md-2">
		<label class="form-label">Tipo</label>
		<input type="text" class="form-control" value="<?= $re->type ?>" readonly>
	</div>
	<div class="col-md-8">
		<label class="form-label">Diagnóstico</label>
		<input type="text" class="form-control" value="<?= ($re->diagnosis) ? $re->diagnosis : null ?>" readonly>
	</div>
	<div class="col-md-2"></div>
	<div class="col-md-5">
		<label class="form-label">Plan de Trabajo</label>
		<textarea class="form-control" rows="4" readonly><?= ($re->plan) ? $re->plan : "-" ?></textarea>
	</div>
	<div class="col-md-5">
		<label class="form-label">Tratamiento</label>
		<textarea class="form-control" rows="4" readonly><?= ($re->treatment) ? $re->treatment : "-" ?></textarea>
	</div>
</div>
<div class="row g-3 mt-3" id="title_auxiliary_exam">
	<div class="col-md-12">
		<div class="alert alert-primary alert-dismissible fade show py-2" role="alert">
			<h4 class="m-0"><strong>5. Examen Auxiliar</strong></h4>
		</div>
	</div>
</div>
<div class="row g-3" id="content_auxiliary_exam">
	<div class="col-md-12">
		<h5 class="m-0"><strong>1) Laboratorio</strong></h5>
	</div>
	<?php if ($ex_profiles or $ex_examinations){ ?>
	<div class="col-md-2">
		<label class="form-label mb-0">Fecha</label>
	</div>
	<div class="col-md-2">
		<label class="form-label mb-0">Tipo</label>
	</div>
	<div class="col-md-3">
		<label class="form-label mb-0">Perfil</label>
	</div>
	<div class="col-md-5">
		<label class="form-label mb-0">Exámenes</label>
	</div>
	<?php foreach($ex_profiles as $i => $ep){ ?>
	<div class="col-md-2">
		<input type="text" class="form-control" value="Fecha aqui" readonly>
	</div>
	<div class="col-md-2">
		<input type="text" class="form-control" value="<?= $ep->type ?>" readonly>
	</div>
	<div class="col-md-3">
		<input type="text" class="form-control" value="<?= $ep->name ?>" readonly>
	</div>
	<div class="col-md-5">
		<input type="text" class="form-control" value="<?= $ep->exams ?>" readonly>
	</div>
	<?php } foreach($ex_examinations as $j => $ee){ ?>
	<div class="col-md-2">
		<input type="text" class="form-control" value="Fecha aqui" readonly>
	</div>
	<div class="col-md-2">
		<input type="text" class="form-control" value="<?= $ee->type ?>" readonly>
	</div>
	<div class="col-md-3">
		<input type="text" class="form-control" value="-" readonly>
	</div>
	<div class="col-md-5">
		<input type="text" class="form-control" value="<?= $ee->name ?>" readonly>
	</div>
	<?php }}else{ ?>
	<div class="col-md-12">
		<label class="form-label mb-0">No se aplica</label>
	</div>
	<?php } ?>
	<div class="col-md-12">
		<h5 class="m-0 pt-3"><strong>2) Imágen</strong></h5>
	</div>
	<?php if ($im){ ?>
	<div class="col-md-2">
		<label class="form-label mb-0">Fecha</label>
	</div>
	<div class="col-md-2">
		<label class="form-label mb-0">Categoría</label>
	</div>
	<div class="col-md-8">
		<label class="form-label mb-0">Imágen</label>
	</div>
	<?php foreach($im as $i => $item){ ?>
	<div class="col-md-2">
		<input type="text" class="form-control" value="Fecha aqui" readonly>
	</div>
	<div class="col-md-2">
		<input type="text" class="form-control" value="<?= $item->category ?>" readonly>
	</div>
	<div class="col-md-8">
		<input type="text" class="form-control" value="<?= $item->name ?>" readonly>
	</div>
	<?php }}else{ ?>
	<div class="col-md-12">
		<label class="form-label mb-0">No se aplica</label>
	</div>
	<?php } ?>
</div>
<div class="row g-3 mt-3" id="title_treatment">
	<div class="col-md-12">
		<div class="alert alert-primary alert-dismissible fade show py-2" role="alert">
			<h4 class="m-0"><strong>6. Tratamiento</strong></h4>
		</div>
	</div>
</div>
<div class="row g-3" id="content_treatment">
	<div class="col-md-12">
		<h5 class="m-0"><strong>1) Medicamento</strong></h5>
	</div>
	<?php if ($me){ ?>
	<div class="col-md-2">
		<label class="form-label mb-0">Fecha</label>
	</div>
	<div class="col-md-4">
		<label class="form-label mb-0">Medicamento</label>
	</div>
	<div class="col-md-6">
		<label class="form-label mb-0">Detalle</label>
	</div>
	<?php foreach($me as $i => $m){ ?>
	<div class="col-md-2">
		<input type="text" class="form-control" value="Fecha aqui" readonly>
	</div>
	<div class="col-md-4">
		<input type="text" class="form-control" value="<?= $m->medicine ?>" readonly>
	</div>
	<div class="col-md-6">
		<input type="text" class="form-control" value="<?= $m->sub_txt ?>" readonly>
	</div>
	<?php }}else{ ?>
	<div class="col-md-12">
		<label class="form-label mb-0">No se aplica</label>
	</div>
	<?php } ?>
	<div class="col-md-12">
		<h5 class="m-0 pt-3"><strong>2) Terapia Física</strong></h5>
	</div>
	<?php if ($th){ ?>
	<div class="col-md-2">
		<label class="form-label mb-0">Fecha</label>
	</div>
	<div class="col-md-4">
		<label class="form-label mb-0">Terapia</label>
	</div>
	<div class="col-md-6">
		<label class="form-label mb-0">Detalle</label>
	</div>
	<?php foreach($th as $i => $t){ ?>
	<div class="col-md-2">
		<input type="text" class="form-control" value="Fecha aqui" readonly>
	</div>
	<div class="col-md-4">
		<input type="text" class="form-control" value="<?= $t->physical_therapy ?>" readonly>
	</div>
	<div class="col-md-6">
		<input type="text" class="form-control" value="<?= $t->sub_txt ?>" readonly>
	</div>
	<?php }}else{ ?>
	<div class="col-md-12">
		<label class="form-label mb-0">No se aplica</label>
	</div>
	<?php } ?>
</div>