<?php
/**
 * hook function
 * 
 * Registers function name to hook
 *
 * @access public
 * @param $hook
 * @param $functionname
 * @param $priority=500
 * @return void
 */	
$hd_hooks = array();
function hook($hook, $functionname, $priority=500){
	global $hd_hooks;
	$priority = hd_prioritize($hd_hooks[$hook], $priority);
	$hd_hooks[$hook][$priority] = $functionname;
}

/**
 * apply_hook function
 * 
 * Run all registered functions to given hook in prioritized order
 *
 * @access public
 * @param $hook
 * @return void
 */	
function apply_hook($hook){
	global $hd_hooks;
	if(is_array($hd_hooks[$hook])) {
		ksort($hd_hooks[$hook]);
		foreach($hd_hooks[$hook] as $functionname) {
			if(function_exists($functionname)) {
				$functionname();
			}
		}
	}
}

/**
 * Retrieve metadata from a file.
 *
 * Started from Wordpress files
 * @see http://codex.wordpress.org/File_Header
 *
 * @since 2.9.0
 * @param string $file Path to the file
 * @param array $default_headers List of headers, in the format array('HeaderKey' => 'Header Name')
 */
function get_file_data( $file, $all_headers, $context = '' ) {
	$fp = fopen( $file, 'r' );
	$file_data = fread( $fp, 8192 );
	fclose( $fp );

	$file_data = str_replace( array("\r",': '), array("\n",':'), $file_data );
	foreach ( $all_headers as $field => $regex ) {
		if ( preg_match( '/^[ \t\/*#@]*' . preg_quote( $regex, '/' ) . ':(.*)$/mi', $file_data, $match ) && $match[1] )
			$all_headers[ $field ] = ( $match[1] );
		else
			$all_headers[ $field ] = '';
	}
	return $all_headers;
}

define ('HD_PLUGIN_DIR', HD_BASEDIR.'hd-plugins');
define ('HD_PLUGIN_URL', HD_INSTALL_URI.'hd-plugins/');
if (!is_dir(HD_PLUGIN_DIR))
	die('Plugin folder missing');

$plugins_folder = HD_PLUGIN_DIR;
// Read content of plugin folder
if ($dh = opendir($plugins_folder)) {
	// Loop all files and folders in plugins
    while (($file = readdir($dh)) !== false) {
    	$plugin_folder = $plugins_folder.'/'.$file;
    	// if plugin is folder
    	if(is_dir($plugin_folder) && $file != '..' && $file != '.'){
    		if ($plug_dh = opendir($plugin_folder)) {
    			while(($plugin_file = readdir($plug_dh)) !== false){
    				if(is_file($plugin_folder.'/'.$plugin_file) && $file !== '..' && $file != '.'){
    					$plugin = get_file_data( $plugin_folder.'/'.$plugin_file, array('PluginName'=>'Plugin Name'));
    					if(!empty($plugin['PluginName'])){
	    					// HAHA - TEMPORARY
		    				hd_option_set('plugin_active_'.strtolower($plugin['PluginName']),true);
		    				hd_option_set('plugin_file_'.strtolower($plugin['PluginName']),$plugin_file);
		    				
		    				if(hd_option_get('plugin_active_'.strtolower($plugin['PluginName']))){
			    				require_once($plugin_folder.'/'.hd_option_get('plugin_file_'.strtolower($plugin['PluginName'])));
		    				}
		    			}
	    			}
    			}
	    	}
		}
    }
    closedir($dh);
}
apply_hook('plugins-loaded');
?>