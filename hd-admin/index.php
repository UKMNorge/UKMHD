<?php
define('IS_ADMIN',true);
require_once('../hd-loader.php');
require_once('logon.php');

apply_hook('admin-menu');

if(isset($_GET['HD_MODULE'])){
	$mod = $_GET['HD_MODULE'];
} else {
	@ksort($hd_menu['admin']);
	$mod = @reset($hd_menu['admin']);
	$mod = $mod['function'];
}
require_once('header.php');
if(function_exists($mod))
	$mod();
require_once('footer.php');
?>