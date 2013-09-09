<?php
function HD_gui_program_days($days){ ?>
	<h2><?= _('Program') ?></h2>
	
    <form class="form-inline">
    <label class="checkbox"><?= _('Legg til dag') ?></label>
    <input type="text" class="input-small" placeholder="<?= _('Kallenavn') ?>">
    <button type="button" class="btn hd-submit" data-action="program_ajax" data-handle="add_day"><?= _('Legg til') ?></button>
	</form>
	
	<h3><?= _('Dager') ?></h3>
	<ul id="hpd_dayslist" class="unstyled">
	
	</ul>
<?php
}
?>