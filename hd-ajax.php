<?php
define('IS_ADMIN',true);
require_once('hd-loader.php');
if(isset($_POST['action'])){
	$hook = 'HDajax_'.$_POST['action'];
	apply_hook($hook);
}
die(false);
?>