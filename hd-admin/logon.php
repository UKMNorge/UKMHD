<?php
global $current_user;
##########################################
## GET USER FACEBOOK DATA, SET $FACE

##########################################
## USER TRIES TO APPROVE APP, VALIDATE TOKEN
## AND REDIRECT TO DASHBOARD
if(isset($_GET['code'])) {
	$confirm = hd_curl('https://graph.facebook.com/oauth/access_token'
		.'?client_id='.UKMface_APP_ID()
		.'&redirect_uri='.urlencode(HD_INSTALL_URI.'hd-admin/')
		.'&client_secret='.UKMface_APP_SECRET()
		.'&code='.$_GET['code']);
		
	$user_profile = UKMface_userdata();

	$db_user = new SQL("SELECT `u_id` FROM `hd_users` WHERE `face_id` = '#faceid'",
						array('faceid' => $user_profile['id']));
	$db_user = $db_user->run('field', 'u_id');
	
	if(!$db_user)
		$create = new SQLins('hd_users');
	else
		$create = new SQLins('hd_users', array('u_id' => $db_user ));

	$create->add('face_id', $user_profile['id']);
	$create->add('u_firstname', $user_profile['first_name']);
	$create->add('u_lastname', $user_profile['last_name']);
	$create->add('u_facelink', $user_profile['link']);
	$create->add('u_username', $user_profile['username']);

	$create->run();
	
	// Should new user be assigned a role?
	
	$comparename = preg_replace("/[^a-z0-9]+/", '', strtolower($user_profile['first_name'].$user_profile['last_name']));
	$roletest = new SQL("SELECT *
						 FROM `hd_project_request_expected`
						 WHERE `comparename` = '#comp'",
						 array('comp' => $comparename));
	$roletest = $roletest->run();
	if($roletest) {
		$new_user = new hd_user($user_profile['id']);
		$u_productions = $new_user->productions;

		while($r = mysql_fetch_assoc($roletest)) {
			$new_user->set('u_role', $r['u_role']);
			$u_productions[$r['p_id']] = $r['p_role'];
			$del = new SQLdel('hd_project_request_expected', array('re_id' => $r['re_id']));
			$del->run();
		}
		$new_user->set('productions',$u_productions);
	}
	
	die('<h3>Videresender...</h3><p><a href="'.HD_INSTALL_URI.'hd-admin/">G&aring; til </a></p>'
		.  ' <script>window.location = \''.HD_INSTALL_URI.'hd-admin/\';</script>');
}

##########################################
## FETCH USER PROJECT RELATIONS

##########################################
## THE USER IS NOT IDENTIFIED, ASK FOR LOGON
if($FACE == 0 || !$current_user) {
	$HIDE_MENU = true;
	require_once('header.php');
	require_once('logon-form.php');
	require_once('footer.php');
	die();
}

##########################################
## USER HAS NO PROJECT RELATIONS
if($_GET['HD_PRODUCTION'] == 'choose' || !$current_user->productions) {
	$HIDE_MENU = true;
	require_once('header.php');
	require_once('no-projects.php');
	require_once('footer.php');
	die();	
}

##########################################
## USER HAS ONE PROJECT RELATION AND IS NOT IN IT AT THE MOMENT
if(sizeof($current_user->productions)==1 && !isset($_GET['HD_PRODUCTION'])) {
	$productions = $current_user->productions;
	reset($productions);
	$prodkey = key($productions);

	header("Location: ".HD_INSTALL_URI.'hd-admin/'.$prodkey.'/');
	exit();
} 
if(sizeof($current_user->productions) > 1 && !isset($_GET['HD_PRODUCTION'])) {
	header("Location: ".HD_INSTALL_URI.'hd-admin/choose/');
	exit();
}