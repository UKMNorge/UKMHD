<?php
global $hd_user_roles, $hd_user_capas, $hd_capamap, $hd_capagroups;
$hd_user_roles = array('administrator' => 'Administrator',
					   'project_leader' => 'Prosjektleder',
					   'production_leader' => 'Produksjonsleder',
					   'client_project_leader'	=> 'Oppdragsgiver',
					   'client_leader' => 'Ansatt hos oppdragsgiver',
					   'spectator' => 'Tilskuer');

function hd_register_role($role, $name) {
	global $hd_user_roles;
	$hd_user_roles[$role] = $name;
}
function hd_register_capagroup($group, $name) {
	global $hd_capagroups;
	$hd_capagroups[$group] = $name;
}
function hd_register_capa($group, $capa, $name) {
	global $hd_user_capas;
	$hd_user_capas[$capa] = array('group' => $group, 'name' => $name);
}

function hd_prepare_capas() {
	global $hd_capamap;
	$qry = new SQL("SELECT * FROM `hd_capamap`");
	$res = $qry->run();
	while($r = mysql_fetch_assoc($res))
		$hd_capamap[$r['r_name']][] = $r['c_name'];
}

function hd_roles() {
	global $hd_user_roles;
	return $hd_user_roles;
}
function hd_sys_capa($capa) {
	global $hd_capamap, $current_user;

	if(!is_array($hd_capamap[$current_user->role]))
		return false;
		
	if($current_user->role == 'administrator')
		return true;

	return @in_array($capa, $hd_capamap[$current_user->role]);
}
function hd_capa($capa, $role=false) {
	global $current_user,$hd_user_capas;
	if(!$role) {
		if($hd_user_capas[$capa]['group'] == 'global')
			return hd_sys_capa($capa);
		else
			$role = hd_production_role_translate($current_user->myrole($_GET['HD_PRODUCTION']));
	}
	if($role == 'administrator')
		return true;

	global $hd_capamap;
	if(!is_array($hd_capamap[$role]))
		return false;
		
	return @in_array($capa, $hd_capamap[$role]);
}
?>