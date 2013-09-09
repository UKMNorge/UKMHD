<?php
$tabs = new tabs('prod_settings');
foreach(production_lists() as $id => $name) {
	$tabs->add($id, $name);
}
global $hd_active_tab;
$liste = production_gui_settings_clarification_list_details($hd_active_tab);
if(hd_capa('production_clarification_edit')) {?>
<form action="#" method="post">
<?php
}
$tabs->draw();

$clarifications = production_clarifications($hd_active_tab);
require_once('class.clarification.php');

foreach($clarifications as $clarification) {
	$clar = new clarification($clarification['c_id']);
	$clar->draw();
}
?>
<div class="clearfix"></div>
<?php
if(hd_capa('production_clarification_edit')) {?>
	<input type="submit" class="btn btn-success clearfix" value="Lagre" name="savelist" />
</form>
<?php } else {
hd_register_jscode_foot("
jQuery(document).ready(function(){
	jQuery('input, textarea').attr('disabled','disabled');
});",300);
}
if(!hd_capa('production_clarification_setup')) {
hd_register_jscode_foot("
jQuery(document).ready(function(){
	jQuery('div.clar_critical input, div.clar_critical textarea').attr('disabled','disabled');
});",200);
	
}