
<div class="span4 pull-right well well-small">
<h3>Status avklaringer</h3>
<div class="alert alert-info">Statusbarene nedenfor viser hvor mange avklaringer som er besvart i hver kategori. 
<br />
<strong>OBS:</strong> Statusbarene er veiledende, da systemet teller et svar for et svar, uavhengig av kvalitet og nøyaktighet.
</div>
<?php
foreach( production_lists() as $l_id => $l_name ) {
	$liste = production_list_details($l_id);
	$clarifications = production_clarifications($l_id);
	$answers = new SQL("SELECT COUNT(`cv_id`) AS `answers`
						FROM `hd_production_clarification_values` AS `val`
						JOIN `hd_production_clarifications` AS `clar` ON (`val`.`c_id` = `clar`.`c_id`)
						WHERE `clar`.`l_id` = '#lid'
						AND `val`.`p_id` = '#pid'
						AND `cv_value` != ''",
						array('lid' => $l_id,
							  'pid' => $_GET['HD_PRODUCTION'] ) );
#	echo $answers->debug();
	$answers = (int) $answers->run('field','answers');
	
	$percent = sizeof($clarifications) == 0
				? 100
				: ($answers / sizeof($clarifications))*100;
	

	?>
	<div class="container span3">
		<div class="pull-right visible-phone"><small><?= $answers ?> av <?= sizeof($clarifications) ?></small></div>
		<div class="liste"><?= $liste['l_name'] ?></div>
		<div class="pull-right hidden-phone" id="statusnumbers"><small><?= $answers ?> av <?= sizeof($clarifications) ?></small></div>
		<div class="progress progress-striped <?= $percent == 100 ? 'progress-success' : ''?>">
			<div class="bar" style="width: <?= $percent ?>%"></div>
		</div>
	</div>
	<?php
}
?>
</div>