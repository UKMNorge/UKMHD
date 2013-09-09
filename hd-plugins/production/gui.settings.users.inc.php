<?php
function production_gui_settings_bruker(){
	$roles = array();
	$user = new hd_user($_GET['HD_ID']);

	foreach($_POST as $key => $val) {
		if( strpos($key, 'production_') === 0 && $val !== 'none') {
			$roles[str_replace('production_', '', $key)] = $val;
		} else {
			if($key == 'u_mobile')
				$val = (int) str_replace(' ','', $val);

			$user->set($key, $val);
		}
	}
	if(isset($_POST['submitprojectroles'])) {
		$user->set('productions', $roles);
	}
	
	$projects = new SQL("SELECT * FROM `hd_projects` ORDER BY `p_id` ASC");
	$projects = $projects->run();
	?>
	<form action="#submit" method="POST">
		<h1>
			<img src="http://graph.facebook.com/<?= $user->face_id ?>/picture?width=100&height=100" width="75" />
			<?= $user->name ?>
		</h1>
		<h4>Personalia</h4>
		<div class="span4">På facebook</div>
		<div class="span4">
			<a href="<?= $user->facelink ?>" target="_blank"><?= $user->facelink ?></a>
		</div>
		<div class="clearfix"></div>
		
		<div class="span4">Mobil</div>
		<div class="span4">
			<input name="u_mobile" type="tel" value="<?= $user->mobile ?>" />
		</div>
		
		<div class="clearfix"></div>
		<h4>Brukertype</h4>
		<div class="span4">Mens noen rettigheter er globale, er andre knyttet til prosjekter. For å sikre at en prosjektleder får tilgang til f.eks. brukere (global) selv om h*n kun er tilskuer i det aktive produksjonen må denne innstillingen settes. Brukerens rettigheter til produksjonen vil følge produksjonen, ikke den globale brukertypen.
		</div>
		<div class="span4">
			<select name="u_role">
			<?php
			foreach( hd_roles() as $role => $name ) { ?>
				<option value="<?= $role ?>" <?= $role == $user->role ? 'selected="selected"' : '' ?>><?= $name ?></option>
			<?php
			}
			?>
			</select>
		</div>
		
		<div class="clearfix"></div>
		<h4>Rolle i produksjoner</h4>
		<input type="hidden" name="userid" value="<?= $user->face_id?>" />
		<ul class="unstyled span12">
		<?php
		while($p = mysql_fetch_assoc($projects)){?>
			<li>
				<div class="span4"><?= utf8_encode($p['p_name']) ?></div>
				<div class="span4">
					<select name="production_<?= $p['p_id']?>">
					<?php 
						foreach(hd_production_roles() as $key => $val) {
							$selected = ($user->myrole($p['p_id'])==$key ? 'selected="selected"':'');
						?>
						<option value="<?= $key ?>" <?= $selected ?>><?= $val ?></option>
					<?php } ?>
					</select>
				</div>
			</li>
		<?php
		} ?>
		</ul>
		<input type="submit" name="submitprojectroles" value="Lagre" class="btn btn-success" />
		&nbsp;
		<a class="" href="../">Avbryt, tilbake til oversikten</a>
	</form>
<?php
}

function production_gui_settings_brukere(){
	if(isset($_POST) && sizeof($_POST) > 0) {
		production_gui_settings_expect_update();
	}
	
	$users = new SQL("SELECT `face_id` FROM `hd_users`
					  ORDER BY `u_firstname`, `u_lastname` ASC");
	$users = $users->run();
	?>
	<form action="#" method="post">
	<h2>Velg registrert bruker</h2>
	<ul class="unstyled">
	<?php
	while($u = mysql_fetch_assoc($users)) {
		$user = new hd_user($u['face_id']);?>
		<li><a href="<?= HD_ACTIVE_URL ?>users/<?= $user->face_id ?>"><?= $user->name ?></a></li>
	<?php
	} ?>
	</ul>
	
	<h2>Forventede brukere</h2>
	Listen inneholder brukere som er forventet å registrere seg, men som enda ikke har gjort det.
	<div class="alert alert-info">Hvis du inviterer samme person til flere produksjoner, er det viktig at du setter samme system-rolle på alle invitasjonene</div>
	<ul class="unstyled">
	<li><strong>Legg til ny</strong></li>
	<?php
	production_gui_settings_expect(array('re_id' => 'new'));
	?>
	<li><strong>Rediger eksisterende</strong></li>
	<?php
	$awaiting = new SQL("SELECT * 
						 FROM `hd_project_request_expected`
						 ORDER BY `re_id` DESC");
	$awaiting = $awaiting->run(); 
	if($awaiting){
		while($r = mysql_fetch_assoc($awaiting)) {
			production_gui_settings_expect($r);
		}
	}
	?>
	</ul>
	<input class="btn btn-success" type="submit" value="Lagre" />
	</form>
	<?php
}


function production_gui_settings_expect($r) { ?>
	<li class="waiting">
		<input type="hidden" name="re_id[]" value="<?= $r['re_id'] ?>" />
		<div class="input-prepend pull-left" >
			<span class="add-on"><i class="icon-<?= $r['re_id'] == 'new' ? 'plus' : 'pencil'?>"></i></span>
			<input type="text" class="span2" name="nicename_<?= $r['re_id'] ?>" value="<?= $r['nicename'] ?>" />
		</div>
		gis tilgang til 
		<select name="p_id_<?= $r['re_id'] ?>" class="span2">
		<?php
		foreach(hd_productions() as $p_id => $p_data) { ?>
			<option value="<?= $p_id ?>" <?= $p_id == $r['p_id'] ? 'selected="selected"' : '' ?>><?= $p_data['name'] ?></option>
		<?php
		} ?>
		</select>
		som
		<select name="p_role_<?= $r['re_id'] ?>" class="span2">
		<?php
		foreach(hd_production_roles() as $r_id => $r_name) { ?>
			<option value="<?= $r_id ?>" <?= $r_id == $r['p_role'] ? 'selected="selected"' : '' ?>><?= $r_name ?></option>
		<?php
		} ?>
		</select>
		og systemet i helhet som
		<select name="u_role_<?= $r['re_id'] ?>" class="span2">
		<?php
		foreach(hd_roles() as $r_id => $r_name) { ?>
			<option value="<?= $r_id ?>" <?= $r_id == $r['u_role'] ? 'selected="selected"' : '' ?>><?= $r_name ?></option>
		<?php
		} ?>
		</select>				
	</li>

<?php
}


function production_gui_settings_expect_update() {
	#echo '<pre>'; var_dump($_POST); echo '</pre>';
	foreach($_POST['re_id'] as $re_id) {
		if(empty($_POST['nicename_'.$re_id])) {
			if(!empty($re_id)) {
				$del = new SQLdel('hd_project_request_expected', array('re_id' => $re_id));
				$del = $del->run();
			}
		} else {				
			if($re_id == 'new')
				$sql = new SQLins('hd_project_request_expected');
			else
				$sql = new SQLins('hd_project_request_expected', array('re_id' => $re_id));
			
			$sql->add('p_id', $_POST['p_id_'.$re_id]);
			$sql->add('u_role', $_POST['u_role_'.$re_id]);
			$sql->add('p_role', $_POST['p_role_'.$re_id]);
			$sql->add('nicename', $_POST['nicename_'.$re_id]);
			$sql->add('comparename', preg_replace("/[^a-z0-9]+/", '', strtolower($_POST['nicename_'.$re_id])));
			$sql->run();
#			echo $sql->debug();
		}
	}
}
?>