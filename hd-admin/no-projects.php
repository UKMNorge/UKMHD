<form class="form-noprojects">
<h2 class="form-noprojects-heading">Velg produksjon</h2>
<?php
if(!$current_user->productions) { ?>
	<div class="alert alert-block">
	<strong>Din facebook-bruker er ikke tilknyttet noen produksjoner.</strong><br />
	Hvis du finner igjen produksjonen ditt i listen nedenfor kan du be om tilgang
	</div>
<?php } else { ?>
	<h4>Dine produksjoner</h4>
	<ul class="unstyled">
	<?php
	foreach($current_user->productions as $pid => $role) { ?>
		<li>
			<a href="<?= HD_ADMIN_CORE_URL.$pid ?>/"><?= $pid ?></a>
			<div class="pull-right"><?= hd_production_role($role) ?></div>
		</li>
	<?php
	} ?>
	</ul>
<?php } ?>
<h4>Alle produksjoner</h4>
<ul class="unstyled">
<?php
foreach(hd_productions() as $pid => $data) {
	if(is_array($current_user->productions) && array_key_exists($pid, $current_user->productions))
		continue; ?>
	<li>
		<?php
		if(isset($_GET['request']) && $_GET['request'] == $pid) {
			$request = hd_production_request($current_user->id, $_GET['request']);

			if(is_string($request))
				$message = 'Forespørsel sendt '. $request;
			elseif(is_bool($request) && $request === true)
				$message = 'Forespørsel sendt!';
			else
				$message = 'Kunne ikke lagre forespørsel';
			?>
			<?= $data['name'] ?>
			<div class="pull-right"><?= $message ?></div>
			
		<?php
		} elseif(hd_production_query_request($current_user->id, $pid)) {?>
			<?= $data['name'] ?>
			<div class="pull-right">
				Forespørsel sendt <?= hd_production_query_request($current_user->id, $pid) ?>
			</div>
		<?php
		} else { ?>
			<a href="?request=<?= $pid ?>"><?= $data['name'] ?></a>
			<div class="pull-right"><?= $data['date'] ?></div>
		<?php
		} ?>
	</li>
	<?php
}?>
</ul>
<?php register_jscode_foot("jQuery('body').addClass('logonform');",500); ?>