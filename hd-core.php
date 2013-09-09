<?php
/**
 * hd_find_priority function
 * 
 * Searches the array for available priority key.
 * If set, search for next free higher prioritized spot
 *
 * @access public
 * @return int priority
 */	
function hd_prioritize(&$array, $priority){
	while(isset($array[$priority])){
		$priority--;
	}
	return $priority;
}
hd_load('sql|menu');

/**
 * hd_option_get function
 * 
 * Returns HD config settings
 *
 * @access public
 * @param string $option_name
 * @param bool $force_reload=false
 * @return var option
 */	
$hd_options = array();
function hd_option_get($option_name, $force_reload=false){
	global $hd_options;
	
	if(!$force_reload && isset($hd_options[$option_name]))
		return $hd_options[$option_name];
	
	$sql = new SQL("SELECT `option_value` FROM `hd_config` WHERE `option_name` = '#option_name'",
					array('option_name'=>$option_name));
	$hd_options[$option_name] = unserialize($sql->run('field', 'option_value'));
	
	return $hd_options[$option_name];
}

/**
 * hd_option_set function
 * 
 * Updates (or on fail inserts) option to config table
 *
 * @access public
 * @param string $option_name
 * @param var $option_value
 * @return void
 */	
function hd_option_set($option_name, $option_value){
	global $hd_options;
	
	$hd_options[$option_name] = $option_value;
	$db = new SQLupd('hd_config', array('option_name'=>$option_name));
	$db->add('option_name', $option_name);
	$db->add('option_value', serialize($option_value));
	$db->run();
}

/**
 * hd_strtime function
 * 
 * Formats a database timestamp to readable string
 *
 * @access public
 * @param string timestamp
 * @return string date formatted
 */	
function hd_strtime($string) {
	return date('d.m.y', strtotime(str_replace('_','-',substr($string,0,10))));
}
hd_load('user|roles_capas');
require_once('UKM/inc/facebook.inc.php');
global $current_user;
UKMface_cff();
$current_user = new hd_user($FACE);
if($current_user->id == 0)
	$current_user = false;

$params = array(
  redirect_uri => HD_INSTALL_URI.'hd-admin/'
);

hd_load('productions|curl|tabs|plugins|js|css');

hd_prepare_capas();