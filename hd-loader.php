<?php
$GETVARS = explode('/', $_SERVER['REQUEST_URI']);
$GETVAR_KEYS = array('HD_PRODUCTION','HD_MODULE','HD_TAB','HD_ID');
for($i=2; $i<sizeof($GETVAR_KEYS)+2; $i++) {
	if(!empty($GETVARS[$i]))
		$_GET[$GETVAR_KEYS[$i-2]] = $GETVARS[$i];
}


require_once('hd-config.php');

if(!defined('IS_ADMIN'))
	define('IS_ADMIN',false);
	
if(!defined('HD_HOME')) 
	define('HD_HOME', dirname(__FILE__).'/');
	
if(!defined('HD_CORE_URL')) 
	define('HD_CORE_URL', 'http://'.$_SERVER['HTTP_HOST'].'/');

if(!defined('HD_ADMIN_CORE_URL')) 
	define('HD_ADMIN_CORE_URL', 'http://'.$_SERVER['HTTP_HOST'].'/hd-admin/');
		
if(!defined('HD_URL')) 
	define('HD_URL', 'http://'.$_SERVER['HTTP_HOST'].'/'
					.(isset($_GET['HD_PRODUCTION']) ? $_GET['HD_PRODUCTION'].'/' : ''));

if(!defined('HD_ADMIN_URL')) 
	define('HD_ADMIN_URL', 'http://'.$_SERVER['HTTP_HOST'].'/hd-admin/'
					.(isset($_GET['HD_PRODUCTION']) ? $_GET['HD_PRODUCTION'].'/' : ''));

if(!defined('HD_ACTIVE_URL')) 
	define('HD_ACTIVE_URL', HD_ADMIN_URL . $_GET['HD_MODULE'].'/');


function hd_load($filestring){
	## GENERATE FILE ARRAY
	$array = explode('|',$filestring);

	## LOOP FILE ARRAY
	foreach($array as $trash => $file) {
		## IF A SLASH IS INCLUDED, ITS THE FOLDER
		if(strpos($file,'/') === false)
			$folder = 'hd-includes/';
		else
			$folder ='';
		
		## ANOTHER FILETYPE THAN THE BASIC INC?
		if(strpos($file, '.') === false)
			$file .= '.inc';
		
		$file = str_replace('.php','', $file);
		
		$inc = HD_HOME. $folder . $file . '.php';
		## CHECK IF EXISTS BEFORE INCLUDING
#		if(file_exists($inc))
			require_once($inc);
	}
}
require_once('hd-core.php');
?>