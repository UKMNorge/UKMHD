<?php
function hd_sys_config_capa($capa, $role) {
	global $hd_capamap;
	if(!is_array($hd_capamap[$role]))
		return false;
		
	return @in_array($capa, $hd_capamap[$role]);
}
function gui_capas() {
	if(sizeof($_POST) > 0)
		gui_capas_save();
		
	global $hd_user_roles, $hd_capamap, $hd_user_capas, $hd_capagroups;
	$capagroup = ''; ?>
	<form action="#" method="post">
	<?php
	foreach( $hd_user_roles as $role => $role_name ) { ?>
		<div class="span3 well well-small">
			<h5><?= $role_name ?></h5>
			<?php
			foreach( $hd_user_capas as $capa => $capa_data) {
				if($capagroup != $capa_data['group']) {
					$capagroup = $capa_data['group']; ?>
					<strong><?= $hd_capagroups[$capa_data['group']] ?></strong>
				<?php } ?>
				<label>
					<input type="checkbox" name="<?= $role ?>[]" value="<?= $capa ?>" <?= 
						hd_sys_config_capa($capa, $role) ? 'checked="checked"' : '' ?>/>
					<?= $capa_data['name'] ?>
				</label>
			<?php
			} ?>
		</div>
	<?php		
	} ?>
	<div class="clearfix"></div>
	<input type="submit" value="Lagre" class="btn btn-success" />
	</form>
	<?php
}

function gui_capas_save() {
	$sql = new SQLdel('hd_capamap');
	$sql->truncate();
#	echo $sql->debug();
	$sql = $sql->run();
	foreach( $_POST as $role => $val ) {
		foreach($val as $capakey) {
			$ins = new SQLins('hd_capamap');
			$ins->add('c_name', $capakey);
			$ins->add('r_name', $role);
			$ins->run();
		}
	}
	global $hd_capamap;
	$hd_capamap = array();
	hd_prepare_capas();
}