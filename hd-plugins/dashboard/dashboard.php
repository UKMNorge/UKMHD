<?php
/* 
Plugin Name: Dashboard
Plugin URI: http://www.ukm.no
Description: Dashboard for HD-admin
Author: UKM Norge / M Mandal 
Version: 1.0 
Author URI: http://www.ukm-norge.no
*/

hook('admin-menu', 'HD_dash');
hook('plugins-loaded', 'dash_capas');
hook('admin-dashboard', 'dashboard_dash_welcome', 80);
hook('admin-dashboard', 'dashboard_dash', 120);

function HD_dash() {
	hd_menu('Dashboard', 'dashboard_read','HD_dash_gui', 10);
}

function dash_capas() {
	hd_register_capagroup('dash', 'Dashboard');
	hd_register_capa('dash', 'dashboard_read','Se');
}

function HD_dash_gui(){
	apply_hook('admin-dashboard');
}

function dashboard_dash_welcome() {
	require_once('gui.dash-welcome.inc.php');
}

function dashboard_dash() { ?>
<div class="span7 pull-left well well-small">
	<?php
	include_once(HD_HOME.'hd-public/omtekst.inc.php');
	?>
	</div>
	<?php
	
	hd_register_jscode_foot("jQuery(document).ready(function(){
	jQuery('p.explanation').hide();
	jQuery('a.explain').attr('title', 'Klikk for å lese litt mer - du blir ikke sendt videre til en ny side :)');
	jQuery('a.explain').attr('alt', 'Klikk for å lese litt mer - du blir ikke sendt videre til en ny side :)');
	
	jQuery('a.explain').click(function(event){
		event.preventDefault();
		var show = jQuery(this).attr('data-what');
		jQuery('#'+show).siblings('p').hide();
		jQuery('#'+show).siblings('div.explanation').hide();
		jQuery('#'+show).removeClass('hidden').show();
	});
	
	jQuery('a.explanationclose').click(function(event){
		event.preventDefault();
		id = jQuery(this).parents('div.explanation').attr('id');
		jQuery('#'+id).hide();
		jQuery('#'+id).siblings('p').show();
	});
});
", 150);
}
?>