<?php
/**
 * hd_productions function
 * 
 * Return all productions as an array
 *
 * @access public
 * @return array productions
 */	
function hd_production($pid) {
	$sql = new SQL("SELECT *
					FROM `hd_projects`
					WHERE `p_id` = '#pid'",
					array('pid' => $pid));
	return $sql->run('array');;
}

function hd_productions() {
	$productions = array();
	$sql = new SQL("SELECT *
					FROM `hd_projects`
					ORDER BY `p_id` ASC");
	$res = $sql->run();
	while($r = mysql_fetch_assoc($res)) {
		$date = hd_strtime($r['p_id']);
		$productions[$r['p_id']] = array('name' => utf8_encode($r['p_name']),
										 'date' => $date);
	}
	return $productions;
}

function hd_production_query_request($user_id, $production) {
	$sql = new SQL("SELECT *
				FROM `hd_project_requests`
				WHERE `u_id` = '#uid'
				AND `p_id` = '#pid'",
				array( 'uid' => $user_id,
					   'pid' => $production)
				);
	$res = $sql->run();
	if(mysql_num_rows($res) == 1) {
		$row = mysql_fetch_assoc($res);
		return hd_strtime($row['time']);
	}
	return false;
}

function hd_production_request($user_id, $production) {
	$request = hd_production_query_request($user_id, $production);
	if(is_string($request))
		return $request;
	
	$ins = new SQLins('hd_project_requests');
	$ins->add('u_id', $user_id);
	$ins->add('p_id', $production);
	$res = $ins->run();
	
	return $res !== -1;
}

function hd_production_role($role) {
	$roles = hd_production_roles();
	return $roles[$role];
}

function hd_production_role_translate($production_role) {
	$trans = hd_production_roles_transtable();
	return $trans[$production_role];
}
function hd_production_roles_transtable() {
	return array('none' => false,
				  'project_manager' => 'project_leader',
				  'project_producer' => 'production_leader',
		  		  'client_customer' => 'client_project_leader',
				  'client_sound_tech' => 'client_leader',
				  'client_light_tech' => 'client_leader',
				  'camera_operator' => 'spectator',
				  );
}
function hd_production_roles() {
	return array('none' => 'Ikke med i prosjektet',
				  'project_manager' => 'Prosjektleder UKM Norge',
				  'project_producer' => 'Buss-ansvarlig',
		  		  'client_customer' => 'Prosjektleder oppdragsgiver',
				  'client_sound_tech' => 'Lydtekniker',
				  'client_light_tech' => 'Lystekniker',
				  'camera_operator' => 'Kameraoperatør',
				  );
}