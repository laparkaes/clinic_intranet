<?php if ($schedules){ ?>
<div class="list-group">
	<?php foreach($schedules as $item){ ?>
	<div class="list-group-item list-group-item-action" aria-current="true">
		<div class="d-flex w-100 justify-content-between">
			<div><?= $item["reserved"] ?> _ <?= $item["patient"] ?></div>
			<div><span class="badge bg-<?= $item["status"]->color ?>"><?= $this->lang->line($item["status"]->code) ?></span></div>
		</div>
		<small><?= $this->lang->line($item["type"]) ?> | <?= $item["specialty"] ?></small>
	</div>
	<?php } ?>
</div>
<?php }else echo "No hay reservas."; ?>