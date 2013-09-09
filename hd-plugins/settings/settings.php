<?php
/* 
Plugin Name: Settings
Plugin URI: http://www.ukm.no
Description: Standardmodul, håndterer innstillinger
Author: UKM Norge / M Mandal 
Version: 1.0 
Author URI: http://www.ukm-norge.no
*/
hook('plugins-loaded', 'settings_capas');
hook('admin-menu', 'settings_menu');
hook('HDajax_production_ajax', 'production_ajax');

/**
 * HD_program function
 * 
 * Module menu handle
 *
 * @return void
 */	
function settings_menu() {
#	register_js(HD_PLUGIN_URL.'production/production.js',100);
	hd_user_menu('System', 'admin_users','settings_gui', 55);
}

function settings_capas() {
	hd_register_capagroup('global', 'Systemglobale innstillinger');
	hd_register_capa('global', 'admin_settings','Systeminnstillinger');
	hd_register_capa('global', 'admin_users', 'Brukerinnstillinger');
}

/**
 * production_gui function
 * 
 * Module main GUI handle
 *
 * @return void
 */	
function settings_gui(){
	$tabs = new tabs('system_settings');
	$tabs->add('capabilities','Brukerroller');
	
	$tabs->draw();
	global $hd_active_tab;
	switch($hd_active_tab) {
		case 'capabilities':
			require_once('gui.capabilites.settings.php');
			gui_capas();
			break;
	}
}
