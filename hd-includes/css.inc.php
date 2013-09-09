<?php
$hd_cssfiles;
function register_css($file, $priority) {
	global $hd_cssfiles;
	$priority = hd_prioritize($hd_cssfiles, $priority);
	$hd_cssfiles[$priority] = $file;
}
?>