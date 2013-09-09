<?php

echo '<h1>Innstillinger for produksjon</h1>';
$tabs = new tabs('prod_settings');
$tabs->add('clarifications','Avklaringer');
$tabs->add('lists','Lister');

$tabs->draw();
global $hd_active_tab;
switch($hd_active_tab) {
	case 'lists':
		require_once('gui.settings.lists.inc.php');
		production_gui_settings_lists();
		break;
	case 'clarifications':
		require_once('gui.settings.clarifications.inc.php');
		if(isset($_GET['HD_ID']))
			production_gui_settings_clarification_list();
		else
			production_gui_settings_clarifications();
		break;
}
?>