<?php
$hd_menu['admin'] = array();
$hd_menu['template'] = array();

function hd_menu($name, $role='project_manager', $function, $priority, $arraykey='admin'){
	global $hd_menu;
	if(!hd_capa($role))
		return;
	$priority = hd_prioritize($hd_menu[$arraykey], $priority);
	$hd_menu[$arraykey][$priority] = array('name' => $name, 'handle' => $handle, 'function' => $function );
}

function hd_user_menu($name, $handle, $function, $priority) {
	hd_menu($name, $handle, $function, $priority, 'admin-user');
}
?>