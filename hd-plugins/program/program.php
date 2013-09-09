<?php
/* 
Plugin Name: Program
Plugin URI: http://www.ukm.no
Description: Program for HD-admin
Author: UKM Norge / M Mandal 
Version: 1.0 
Author URI: http://www.ukm-norge.no
*/

#hook('admin-menu', 'HD_program_menu');
hook('HDajax_program_ajax', 'HD_program_ajax');

/**
 * HD_program function
 * 
 * Module menu handle
 *
 * @return void
 */	
function HD_program_menu() {
	register_js(HD_PLUGIN_URL.'program/program.js',100);
	hd_menu('Program', 'program','HD_program_gui', 100);
}

/**
 * HD_program_gui function
 * 
 * Module main GUI handle
 *
 * @return void
 */	
function HD_program_gui(){
	HD_program_days();
}

/**
 * HD_program_days function
 * 
 * Module DAYS handle
 *
 * @return void
 */	
function HD_program_days(){
	require_once(HD_PLUGIN_DIR.'/program/gui/days.gui.php');
	$qry = new SQL("SELECT * FROM `hd_program_days` ORDER BY `hpd_name` ASC");
	while($r = mysql_fetch_assoc($qry->run())){
		$days[$r['hpd_id']] = $r['hpd_name'];
	}
	HD_gui_program_days($days);
}

/**
 * HD_program_ajax function
 * 
 * Module main AJAX handle
 *
 * @return void
 */	
function HD_program_ajax(){
	require_once(HD_PLUGIN_DIR.'/program/ajax.program.php');
	$function = 'HD_program_ajax_'.$_POST['handle'];
	$function();
}
?>