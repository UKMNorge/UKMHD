<?php
$hd_jsfiles = array();
function hd_register_js($file,$priority,$placement='head') {
	register_js($file,$priority,$placement);
}
function register_js($file,$priority,$placement='head') {
	global $hd_jsfiles;
	$priority = hd_prioritize($array, $priority);
	$hd_jsfiles[$placement][$priority] = $file;
}
function hd_register_jscode_foot($code, $priority){
	register_jscode_foot($code, $priority);
}
function register_jscode_foot($code, $priority) {
	register_js($code, $priority, 'footer');
}
?>