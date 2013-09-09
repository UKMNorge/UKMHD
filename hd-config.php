<?php
require_once('config.php');

define('HD_BASEDIR', dirname(__FILE__).'/');
define('HD_INSTALL_PATH', '/');
define('HD_INSTALL_URI', 'http://hd.ukm.no/');

/* UKM LOADER */ if(!defined('UKM_HOME')) define('UKM_HOME', '/home/ukmno/public_html/UKM/'); 
define('WP_PLUGIN_DIR', UKM_HOME.'../wp-content/plugins/');
?>