<?php 
$this->lang->load("surgery", "spanish");
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

$rooms = $this->general->all("surgery_room", "name", "asc");
?>
<div class="card-body">
	<h5 class="card-title"><?= $this->lang->line('w_add_surgery') ?></h5>
	<div class="row">
		<div class="col-md-6">
			<form class="row g-3" id="sur_register_form">
				<div class="col-md-12">
					<strong><?= $this->lang->line('w_attention') ?></strong>
				</div>
				<div class="col-md-12">
					<label class="form-label"><?= $this->lang->line('w_specialty') ?></label>
					<?php if ($doctor){ ?>
					<select class="form-select" id="sur_specialty" name="sur[specialty_id]">
						<option value="<?= $doctor->specialty_id ?>" selected><?= $doctor->specialty ?></option>
					</select>
					<?php }else{ ?>
					<select class="form-select" id="sur_specialty" name="sur[specialty_id]">
						<option value="">--</option>
						<?php foreach($specialties as $item){ if ($item->doctor_qty){ ?>
						<option value="<?= $item->id ?>"><?= $item->name ?></option>
						<?php }} ?>
					</select>
					<?php } ?>
					<div class="sys_msg" id="sur_specialty_msg"></div>
				</div>
				<div class="col-md-12">
					<label class="form-label">
						<span><?= $this->lang->line('w_doctor') ?></span>
						<i class="bi bi-clock ms-2" id="ic_doctor_weekly_sur" data-bs-toggle="modal" data-bs-target="#md_doctor_weekly_sur"></i>
					</label>
					<?php if ($doctor){ ?>
					<select class="form-select" id="sur_doctor" name="sur[doctor_id]">
						<option value="<?= $doctor->person->id ?>" selected><?= $doctor->person->name ?></option>
					</select>
					<?php }else{ $enabled_id = $this->general->status("enabled")->id; ?>
					<select class="form-select" id="sur_doctor" name="sur[doctor_id]">
						<option value="">--</option>
						<?php foreach($doctors as $item){ if ($item->status_id == $enabled_id){ ?>
						<option class="spe spe_<?= $item->specialty_id ?> d-none" value="<?= $item->person_id ?>"><?= $item->name ?></option>
						<?php }} ?>
					</select>
					<?php } ?>
					<div class="sys_msg" id="sur_doctor_msg"></div>
				</div>
				<div class="col-md-4">
					<label class="form-label"><?= $this->lang->line('w_date') ?></label>
					<input type="text" class="form-control" id="sur_date" name="sch[date]" value="<?= date('Y-m-d') ?>">
					<div class="sys_msg" id="sur_date_msg"></div>
				</div>
				<div class="col-md-8">
					<label class="form-label"><?= $this->lang->line('w_time') ?></label>
					<div class="input-group">
						<select class="form-select" id="sur_hour" name="sch[hour]">
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
						<select class="form-select" id="sur_min" name="sch[min]">
							<option value="" selected>--</option>
							<option value="00">00</option>
							<option value="15">15</option>
							<option value="30">30</option>
							<option value="45">45</option>
						</select>
					</div>
					<div class="sys_msg" id="sur_schedule_msg"></div>
				</div>
				<div class="col-md-8">
					<label class="form-label">
						<span><?= $this->lang->line('w_room') ?></span>
						<i class="bi bi-alarm ms-2" id="ic_room_weekly_sur" data-bs-toggle="modal" data-bs-target="#md_room_weekly_sur"></i>
					</label>
					<select class="form-select" id="sur_room_id" name="sur[room_id]">
						<option value="">--</option>
						<?php foreach($rooms as $r){ ?>
						<option value="<?= $r->id ?>"><?= $r->name ?></option>
						<?php } ?>
					</select>
					<div class="sys_msg" id="sur_room_msg"></div>
				</div>
				<div class="col-md-4">
					<label class="form-label"><?= $this->lang->line('w_duration') ?></label>
					<select class="form-select" name="sch[duration]">
						<option value="">--</option>
						<?php foreach($duration_ops as $op){ ?>
						<option value="<?= $op["value"] ?>"><?= $op["txt"] ?></option>
						<?php } ?>
					</select>
					<div class="sys_msg" id="sur_duration_msg"></div>
				</div>
				<div class="col-md-12 pt-3">
					<strong><?= $this->lang->line('w_patient') ?></strong>
				</div>
				<?php if ($patient){ ?>
				<input type="hidden" id="aa_pt_id" name="sur[patient_id]" value="<?= $patient->id ?>">
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
				<input type="hidden" id="sur_pt_id" name="sur[patient_id]" value="">
				<div class="col-md-12">
					<label class="form-label"><?= $this->lang->line('w_document') ?></label>
					<select class="form-select" id="sur_pt_doc_type_id" name="pt[doc_type_id]">
						<?php foreach($doc_types as $item){ if ($item->sunat_code){ ?>
						<option value="<?= $item->id ?>"><?= $item->description ?></option>
						<?php }} ?>
					</select>
					<div class="sys_msg" id="sur_pt_doc_type_msg"></div>
				</div>
				<div class="col-md-12">
					<label class="form-label">Numero</label>
					<div class="input-group">
						<input type="text" class="form-control" id="sur_pt_doc_number" name="pt[doc_number]" placeholder="<?= $this->lang->line('w_number') ?>">
						<button class="btn btn-primary" type="button" id="btn_search_pt_sur">
							<i class="bi bi-search"></i>
						</button>
					</div>
					<div class="sys_msg" id="sur_pt_doc_number_msg"></div>
				</div>
				<div class="col-md-8">
					<label class="form-label"><?= $this->lang->line('w_name') ?></label>
					<input type="text" class="form-control" id="sur_pt_name" name="pt[name]">
					<div class="sys_msg" id="sur_pt_name_msg"></div>
				</div>
				<div class="col-md-4">
					<label class="form-label"><?= $this->lang->line('w_tel') ?></label>
					<input type="text" class="form-control" id="sur_pt_tel" name="pt[tel]">
					<div class="sys_msg" id="sur_pt_tel_msg"></div>
				</div>
				<?php } ?>
				<div class="col-md-12">
					<label class="form-label"><?= $this->lang->line('w_remark') ?> (<?= $this->lang->line('w_optional') ?>)</label>
					<textarea class="form-control" rows="4" name="sur[remark]" placeholder="<?= $this->lang->line('t_remark') ?>"></textarea>
				</div>
				<div class="col-md-12 pt-3">
					<button type="submit" class="btn btn-primary"><?= $this->lang->line('btn_register') ?></button>
				</div>
			</form>
		</div>
		<div class="col-md-6">
			<div class="row g-3">
				<div class="col-md-12"><strong><?= $this->lang->line('w_doctor_agenda') ?></strong></div>
				<div class="col-md-12"><div id="sur_schedule"></div></div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="md_doctor_weekly_sur" tabindex="-1">
	<div class="modal-dialog modal-fullscreen">
		<div class="modal-content">
			<div class="modal-body">
				<div id="bl_weekly_schedule_sur"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-bs-dismiss="modal"><?= $this->lang->line('btn_close') ?></button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="md_room_weekly_sur" tabindex="-1">
	<div class="modal-dialog modal-fullscreen">
		<div class="modal-content">
			<div class="modal-body">
				<div id="bl_room_weekly_sur"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-bs-dismiss="modal"><?= $this->lang->line('btn_close') ?></button>
			</div>
		</div>
	</div>
</div>
<script>
document.addEventListener("DOMContentLoaded", () => {
	function reset_person_sur(){
		$("#sur_pt_name").val("");
		$("#sur_pt_tel").val("");
	}
	
	function load_doctor_schedule_sur(){
		$("#sur_schedule").html('<div class="text-center mt-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
		load_doctor_schedule_n($("#sur_doctor").val(), $("#sur_date").val()).done(function(res) {
			$("#sur_schedule").html(res);
			$("#sur_schedule .sch_cell").on('click',(function(e) {set_time_dom("#sur_hour", "#sur_min", this);}));
			set_time_sl("sur", "#sur_schedule");
		});
	}

	set_date_picker("#sur_date", new Date());
	load_doctor_schedule_sur();
	
	$("#sur_register_form").submit(function(e) {
		e.preventDefault();
		$("#sur_register_form .sys_msg").html("");
		ajax_form_warning(this, "clinic/surgery/register", "wm_surgery_register").done(function(res) {
			set_msg(res.msgs);
			swal_redirection(res.type, res.msg, res.move_to);
		});
	});

	$("#sur_specialty").change(function() {
		load_doctor_schedule_sur();
		$("#sur_doctor").val("");
		$("#sur_doctor .spe").addClass("d-none");
		$("#sur_doctor .spe_" + $(this).val()).removeClass("d-none");
	});
	
	$("#sur_doctor").change(function() {
		load_doctor_schedule_sur();
	});
	
	$("#sur_date").focusout(function() {
		load_doctor_schedule_sur();
	});
	
	$("#sur_pt_doc_type_id").change(function() {reset_person_sur();});
	$("#sur_pt_doc_number").keyup(function() {reset_person_sur();});
	
	$("#btn_search_pt_sur").click(function() {
		var data = {doc_type_id: $("#sur_pt_doc_type_id").val(), doc_number: $("#sur_pt_doc_number").val()};
		
		ajax_simple(data, "ajax_f/search_person").done(function(res) {
			swal(res.type, res.msg);
			if (res.type == "success"){
				$("#sur_pt_name").val(res.person.name);
				$("#sur_pt_tel").val(res.person.tel);
			}else reset_person_sur();
		});
	});
	
	$("#ic_doctor_weekly_sur").click(function() {
		load_doctor_schedule_weekly($("#sur_doctor").val(), null, "bl_weekly_schedule_sur");
	});
	
	$("#ic_room_weekly_sur").click(function() {
		load_room_availability($("#sur_room_id").val(), null, "bl_room_weekly_sur");
	});
	
	$("#sur_hour, #sur_min").change(function() {
		set_time_sl("sur", "#sur_schedule");
	});
});
</script>