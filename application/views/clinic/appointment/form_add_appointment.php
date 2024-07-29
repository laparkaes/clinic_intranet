<?php 
$this->lang->load("appointment", "spanish");
if (!$doctor){
	$aux_f = ["status_id" => $this->general->status("enabled")->id];
	$specialties = $this->general->all("specialty", "name", "asc");
	foreach($specialties as $s){
		$aux_f["specialty_id"] = $s->id;
		$s->doctor_qty = $this->general->counter("doctor", $aux_f);
	}
	unset($aux_f["specialty_id"]);
	
	$doctors = $this->general->filter("doctor", $aux_f);
	foreach($doctors as $d){
		if (!$this->general->id("person", $d->person_id)) echo $d->person_id."<br/>";
		$d->name = $this->general->id("person", $d->person_id)->name;
	}
	usort($doctors, function($a, $b) {return strcmp(strtoupper($a->name), strtoupper($b->name));});
}
?>
<div class="card-body">
	<h5 class="card-title"><?= $this->lang->line('w_add_appointment') ?></h5>
	<div class="row">
		<div class="col-md-6">
			<form class="row g-3" id="app_register_form">
				<div class="col-md-12">
					<strong><?= $this->lang->line('w_attention') ?></strong>
				</div>
				<div class="col-md-12">
					<label class="form-label"><?= $this->lang->line('w_specialty') ?></label>
					<?php if ($doctor){ ?>
					<select class="form-select" id="aa_specialty" name="app[specialty_id]">
						<option value="<?= $doctor->specialty_id ?>" selected><?= $doctor->specialty ?></option>
					</select>
					<?php }else{ ?>
					<select class="form-select" id="aa_specialty" name="app[specialty_id]">
						<option value="">--</option>
						<?php foreach($specialties as $item){ if ($item->doctor_qty){ ?>
						<option value="<?= $item->id ?>"><?= $item->name ?></option>
						<?php }} ?>
					</select>
					<?php } ?>
					<div class="sys_msg" id="aa_specialty_msg"></div>
				</div>
				<div class="col-md-12">
					<label class="form-label">
						<span><?= $this->lang->line('w_doctor') ?></span>
						<i class="bi bi-clock ms-2" id="ic_doctor_weekly_aa" data-bs-toggle="modal" data-bs-target="#md_doctor_weekly_app"></i>
					</label>
					<?php if ($doctor){ ?>
					<select class="form-select" id="aa_doctor" name="app[doctor_id]">
						<option value="<?= $doctor->person->id ?>" selected><?= $doctor->person->name ?></option>
					</select>
					<?php }else{ $enabled_id = $this->general->status("enabled")->id; ?>
					<select class="form-select" id="aa_doctor" name="app[doctor_id]">
						<option value="">--</option>
						<?php foreach($doctors as $item){ if ($item->status_id == $enabled_id){ ?>
						<option class="spe spe_<?= $item->specialty_id ?> d-none" value="<?= $item->person_id ?>"><?= $item->name ?></option>
						<?php }} ?>
					</select>
					<?php } ?>
					<div class="sys_msg" id="aa_doctor_msg"></div>
				</div>
				<div class="col-md-4">
					<label class="form-label"><?= $this->lang->line('w_date') ?></label>
					<input type="text" class="form-control date_picker" id="aa_date" name="sch[date]" value="<?= date('Y-m-d') ?>">
					<div class="sys_msg" id="aa_date_msg"></div>
				</div>
				<div class="col-md-8">
					<label class="form-label"><?= $this->lang->line('w_time') ?></label>
					<div class="input-group">
						<select class="form-select" id="aa_hour" name="sch[hour]">
							<option value="" selected>--</option>
							<?php for($i = 9; $i <= 18; $i++){ if ($i < 12) $pre = "AM"; else $pre = "PM"; ?>
							<option value="<?= $i ?>">
								<?php 
								switch(true){
									case $i < 12: echo $i." AM"; break;
									case $i == 12: echo $i." M"; break;
									case $i > 12: echo ($i - 12)." PM"; break;
								}
								?>
							</option>
							<?php } ?>
						</select>
						<span class="input-group-text">:</span>
						<select class="form-select" id="aa_min" name="sch[min]">
							<option value="" selected>--</option>
							<option value="00">00</option>
							<option value="15">15</option>
							<option value="30">30</option>
							<option value="45">45</option>
						</select>
					</div>
					<div class="sys_msg" id="aa_schedule_msg"></div>
				</div>
				<div class="col-md-12 pt-3">
					<strong><?= $this->lang->line('w_patient') ?></strong>
				</div>
				<?php if ($patient){ ?>
				<input type="hidden" id="aa_pt_id" name="app[patient_id]" value="<?= $patient->id ?>">
				<div class="col-md-12">
					<input type="hidden" name="pt[doc_type_id]" value="<?= $patient->doc_type_id ?>">
					<input type="hidden" name="pt[doc_number]" value="<?= $patient->doc_number ?>">
					<label class="form-label"><?= $this->lang->line('w_document') ?></label>
					<input type="text" class="form-control" value="<?= $patient->doc_type." ".$patient->doc_number ?>">
					<div class="sys_msg" id="pt_doc_msg"></div>
				</div>
				<div class="col-md-8">
					<label class="form-label"><?= $this->lang->line('w_name') ?></label>
					<input type="text" class="form-control" name="pt[name]" value="<?= $patient->name ?>">
					<div class="sys_msg" id="pt_name_msg"></div>
				</div>
				<div class="col-md-4">
					<label class="form-label"><?= $this->lang->line('w_tel') ?></label>
					<input type="text" class="form-control" name="pt[tel]" value="<?= $patient->tel ?>">
					<div class="sys_msg" id="pt_tel_msg"></div>
				</div>
				<?php }else{ ?>
				<input type="hidden" id="aa_pt_id" name="app[patient_id]" value="">
				<div class="col-md-12">
					<label class="form-label"><?= $this->lang->line('w_document') ?></label>
					<select class="form-select" id="aa_pt_doc_type_id" name="pt[doc_type_id]">
						<?php foreach($doc_types as $item){ if ($item->sunat_code){ ?>
						<option value="<?= $item->id ?>"><?= $item->description ?></option>
						<?php }} ?>
					</select>
					<div class="sys_msg" id="aa_pt_doc_type_msg"></div>
				</div>
				<div class="col-md-12">
					<label class="form-label"><?= $this->lang->line('w_number') ?></label>
					<div class="input-group">
						<input type="text" class="form-control" id="aa_pt_doc_number" name="pt[doc_number]">
						<button class="btn btn-primary" type="button" id="btn_search_pt_aa">
							<i class="bi bi-search"></i>
						</button>
					</div>
					<div class="sys_msg" id="aa_pt_doc_number_msg"></div>
				</div>
				<div class="col-md-8">
					<label class="form-label"><?= $this->lang->line('w_name') ?></label>
					<input type="text" class="form-control" id="aa_pt_name" name="pt[name]">
					<div class="sys_msg" id="aa_pt_name_msg"></div>
				</div>
				<div class="col-md-4">
					<label class="form-label"><?= $this->lang->line('w_tel') ?></label>
					<input type="text" class="form-control" id="aa_pt_tel" name="pt[tel]">
					<div class="sys_msg" id="aa_pt_tel_msg"></div>
				</div>
				<?php } ?>
				<div class="col-md-12">
					<label class="form-label"><?= $this->lang->line('w_remark') ?> (<?= $this->lang->line('w_optional') ?>)</label>
					<textarea class="form-control" rows="4" name="app[remark]" placeholder="<?= $this->lang->line('t_remark') ?>"></textarea>
				</div>
				<div class="col-md-12 pt-3">
					<div class="form-check">
						<input class="form-check-input" type="checkbox" name="as_free" id="as_free">
						<label class="form-check-label" for="as_free">
							<?= $this->lang->line('t_free_appointment') ?>
						</label>
					</div>
				</div>
				<div class="col-md-12 pt-3">
					<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_register') ?></button>
				</div>
			</form>
		</div>
		<div class="col-md-6 mb-3">
			<div class="mb-3"><strong><?= $this->lang->line('w_doctor_agenda') ?></strong></div>
			<div id="aa_schedule"></div>
		</div>
	</div>
</div>
<div class="modal fade" id="md_doctor_weekly_app" tabindex="-1">
	<div class="modal-dialog modal-fullscreen">
		<div class="modal-content">
			<div class="modal-body">
				<div id="bl_doctor_weekly_app"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= $this->lang->line('btn_close') ?></button>
			</div>
		</div>
	</div>
</div>
<script>
document.addEventListener("DOMContentLoaded", () => {
	function reset_person_app(){
		$("#aa_pt_name").val("");
		$("#aa_pt_tel").val("");
	}

	function load_doctor_schedule_app(){
		$("#aa_schedule").html('<div class="text-center mt-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
		load_doctor_schedule_n($("#aa_doctor").val(), $("#aa_date").val()).done(function(res) {
			$("#aa_schedule").html(res);
			$("#aa_schedule .sch_cell").on('click',(function(e) {set_time_dom("#aa_hour", "#aa_min", this);}));
			set_time_sl("aa", "#aa_schedule");
		});
	}
	
	set_date_picker("#aa_date", new Date());
	load_doctor_schedule_app();
	
	$("#app_register_form").submit(function(e) {
		e.preventDefault();
		$("#app_register_form .sys_msg").html("");
		ajax_form_warning(this, "clinic/appointment/register", "wm_appointment_register").done(function(res) {
			set_msg(res.msgs);
			swal_redirection(res.type, res.msg, res.move_to);
		});
	});
	
	$("#aa_specialty").change(function() {
		load_doctor_schedule_app();
		$("#aa_doctor").val("");
		$("#aa_doctor .spe").addClass("d-none");
		$("#aa_doctor .spe_" + $(this).val()).removeClass("d-none");
	});
	
	$("#aa_doctor").change(function() {
		load_doctor_schedule_app();
	});
	
	$("#aa_date").focusout(function() {
		load_doctor_schedule_app();
	});
	
	$("#aa_pt_doc_type_id").change(function() {reset_person_app();});
	$("#aa_pt_doc_number").keyup(function() {reset_person_app();});
	
	$("#btn_search_pt_aa").click(function() {
		var data = {doc_type_id: $("#aa_pt_doc_type_id").val(), doc_number: $("#aa_pt_doc_number").val()};
		
		ajax_simple(data, "ajax_f/search_person").done(function(res) {
			swal(res.type, res.msg);
			if (res.type == "success"){
				$("#aa_pt_name").val(res.person.name);
				$("#aa_pt_tel").val(res.person.tel);
			}else reset_person_app();
		});
	});
	
	$("#ic_doctor_weekly_aa").click(function() {
		load_doctor_schedule_weekly($("#aa_doctor").val(), null, "bl_doctor_weekly_app");
	});
	
	$("#aa_hour, #aa_min").change(function() {
		set_time_sl("aa", "#aa_schedule");
	});
});
</script>