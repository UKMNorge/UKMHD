<?php
/* 
Plugin Name: Production
Plugin URI: http://www.ukm.no
Description: Prosjektverktøy for HD-produksjoner
Author: UKM Norge / M Mandal 
Version: 1.0 
Author URI: http://www.ukm-norge.no
*/

hook('plugins-loaded', 'production_capas');
hook('admin-menu', 'production_menu');
hook('admin-dashboard', 'production_dash', 100);
hook('HDajax_clar_order', 'production_ajax_clar');
hook('HDajax_clar_lists_order', 'production_ajax_clar_lists');

function production_ajax_clar(){
	require_once('ajax.production.php');
	save_clarification_order();
}
function production_ajax_clar_lists(){
	require_once('ajax.production.php');
	save_clarification_lists_order();
}

function production_capas() {
	hd_register_capagroup('production', 'Produksjoner');
	hd_register_capa('production', 'production_settings','Innstillinger');
	hd_register_capa('production', 'production_clarification_setup', 'Oppsett-avklaringer');
	hd_register_capa('production', 'production_clarification_edit', 'Avklare');
	hd_register_capa('production', 'production_clarification_read', 'Se avklaringer');
}

/**
 * HD_program function
 * 
 * Module menu handle
 *
 * @return void
 */	
function production_menu() {
	register_js(HD_PLUGIN_URL.'production/production.js',100);
	register_css(HD_PLUGIN_URL.'production/style.production.css',100);
	register_css(HD_PLUGIN_URL.'production/style.dashboard.production.css',100);
	register_css('http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css', 80);

	hd_menu('Produksjonen', 'production_clarification_read','production_gui', 100);
	hd_user_menu('Brukere', 'admin_users','production_gui_users', 45);
	hd_user_menu('Produksjoner', 'production_settings','production_gui_settings', 50);
}


function production_dash(){
	require_once('gui.dashboard.inc.php');
}
/**
 * production_gui function
 * 
 * Module main GUI handle
 *
 * @return void
 */	
function production_gui(){
	require_once('gui.production.php');
}

function production_gui_settings(){
	require_once('gui.settings.inc.php');
}

function production_gui_users(){
	require_once('gui.settings.users.inc.php');
	if(isset($_GET['HD_ID']))
		production_gui_settings_bruker();	
	else
		production_gui_settings_brukere();
}

function production_rights() {
	hd_production_rights(); 
}

function production_lists(){
	$qry = new SQL("SELECT * FROM `hd_production_lists`
					ORDER BY `order` ASC, `l_name` ASC");
	$res = $qry->run();
	if(!$res || mysql_num_rows($res)==0)	
		return array();
	while($r = mysql_fetch_assoc($res)) {
		$lists[$r['l_id']] = $r['l_name'];
	}
	return $lists;
}
function production_list_details($l_id) {
	return production_gui_settings_clarification_list_details($l_id);
}
function production_gui_settings_clarification_list_details($l_id) {
	$liste = new SQL("SELECT `l_name` 
					  FROM `hd_production_lists`
					  WHERE `l_id` = '#listid'",
					  array('listid' => $l_id));
	return $liste->run('array');
}


function production_clarifications($l_id){
	$qry = new SQL("SELECT * FROM `hd_production_clarifications`
					WHERE `l_id` = '#lid'
					ORDER BY `order` ASC",
					array('lid'=>$l_id));
	$res = $qry->run();
	if(!$res || mysql_num_rows($res)==0)	
		return array();
	while($r = mysql_fetch_assoc($res)) {
		$clars[$r['c_id']] = $r;
	}
	return $clars;
}

function production_clarification_types() {
	return array('yesno' => 'Ja / Nei-spørsmål',
				 'text'	=> 'Tekstboks, liten',
				 'longtext' => 'Tekstboks, stor',
				 'date' => 'Dato',
				 'datetime' => 'Dato og tidspunkt',
 				 'time' => 'Tidspunkt',
 				 'gmaps' => 'MAPS Gateadresse',
				 'faceevent' => 'FACEBOOK arrangements-ID',
				 'dropboximage' => 'DROPBOX bilde (vis bilde)',
				 'helper' => 'HJELPETEKST',
				 'helper_wide' => 'HJELPETEKST bred'
				 );
}