<?php
function production_gui_settings_clarification_list(){
#	echo '<pre>'; var_dump($_POST); echo '</pre>';
	if(isset($_POST['submitclarifications'])) {
		foreach( $_POST['clarification_id'] as $i => $name) {
			$cid = $_POST['clarification_id'][$i];
			if(!empty($_POST['clarification_name_'.$cid])) {
				if($_POST['clarification_id'][$i] == 'new') {
					$qry = new SQLins('hd_production_clarifications');
					$qry->add('order', time());
				}
				else
					$qry = new SQLins('hd_production_clarifications',
									  array('c_id' => $cid));
									  
				$qry->add('c_name', $_POST['clarification_name_'.$cid]);
				$qry->add('c_type', $_POST['clarification_type_'.$cid]);
				$qry->add('c_help', $_POST['clarification_help_'.$cid]);
				if(empty($_POST['clarification_critical_'.$cid]))
					$_POST['clarification_critical_'.$cid] = 'false';
				$qry->add('c_critical', $_POST['clarification_critical_'.$cid]);

				$qry->add('l_id', $_GET['HD_ID']);
				
#				echo $qry->debug();
				$qry->run();
			} elseif($cid !== 'new') {
				$qry = new SQLdel('hd_production_clarifications', array('c_id' => $cid));
				$qry->run();
			}
		}
	}


	$liste = production_gui_settings_clarification_list_details($_GET['HD_ID']);
	?>
	<div class="alert alert-info">
		<strong>Tegnforklaring:</strong> 
		<i class="icon-check"></i>  Alle brukere med svar-rettigheter kan svare
		<i class="icon-lock"></i> Kun UKM Norge-brukere kan svare
		<div class="clear-fix"></div>
	</div>
	<h4>Avklaringer i "<?= $liste['l_name']?>"</h4>
	<form action="#" method="post">
	<ul class="unstyled">
		<li class="notsort">
			<h5>Legg til ny</h5>
		</li>
		<li class="notsort">
			<div class="span1 handle"><i class="icon-plus"></i></div>
			<input type="hidden" name="clarification_id[]" value="new" />
			<div class="input span4 input-prepend input-append">
				<input class="span3" type="text" name="clarification_name_new" placeholder="Spørsmål" />
				<span class="add-on criticaleval"><i class="icon-check false"></i><i class="icon-lock true hidden"></i></span>
			</div>
			<select class="span2" name="clarification_type_new">
				<option value="none" disabled="disabled" selected="selected">Velg type</option>
				<?php
				foreach(production_clarification_types() as $key => $val) { ?>
					<option value="<?= $key ?>"><?= $val ?></option>
				<?php
				}
				?>
			</select>
			<input class="span4" type="text" name="clarification_help_new" placeholder="Hjelpetekst / forklaring" />
			<input type="hidden" class="criticalval" name="clarification_critical_new" value="false" />
			<div class="clear-fix"></div>
		</li>
		<li class="notsort">
			<h5>Eksisterende avklaringer</h5>
		</li>
	</ul>
	<ul class="unstyled" id="clarification_order" data-list="<?= $_GET['HD_ID'] ?>">
	<?php
	foreach(production_clarifications($_GET['HD_ID']) as $id => $data) { ?>
		<li id="clar_<?= $id ?>">
			<div class="span1 handle"><i class="icon-move"></i></div>
			<input type="hidden" name="clarification_id[]" value="<?= $id ?>" />
			<div class="input span4 input-prepend input-append">
				<input class="span3" type="text" name="clarification_name_<?= $id ?>" value="<?= $data['c_name']?>" placeholder="Spørsmål" />
				<span class="add-on criticaleval"><i class="icon-check false <?= $data['c_critical'] == 'true' ? 'hidden' : '' ?>"></i><i class="icon-lock true <?= $data['c_critical'] == 'false' ? 'hidden' : '' ?>"></i></span>
			</div>
			<select class="span2" name="clarification_type_<?= $id ?>">
				<option value="none" disabled="disabled" selected="selected">Velg type</option>
				<?php
				foreach(production_clarification_types() as $key => $val) { ?>
					<option value="<?= $key ?>" <?= ($key == $data['c_type']?'selected="selected"':'')?>>
						<?= $val ?>
					</option>
				<?php
				}
				?>
			</select>
			<input class="span4" type="text" name="clarification_help_<?= $id ?>" value="<?= $data['c_help']?>" placeholder="Hjelpetekst / forklaring" />
			<input type="hidden" class="criticalval" name="clarification_critical_<?= $id ?>" value="<?= $data['c_critical'] ?>" />
			<div class="clear-fix"></div>
		</li>
	<?php 
	} ?>
	</ul>
		<input type="submit" name="submitclarifications" value="Lagre" class="btn btn-success" />
	</form>
<?php
}

function production_gui_settings_clarifications(){
	$liste = production_gui_settings_clarification_list_details($_GET['HD_ID']); ?>
	<h4>Velg liste </h4>
	<ul class="unstyled">
	<?php
	foreach(production_lists() as $id => $data) { ?>
		<li>
			<a href="<?= HD_ACTIVE_URL.'clarifications/'.$id ?>"><?= $data ?></a>
		</li>
	<?php
	}?>
	</ul>
	<?php
}
?>