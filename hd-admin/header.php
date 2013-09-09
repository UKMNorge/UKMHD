<!DOCTYPE html>
<html>
<head>
<title>HD-admin</title>
<style>
body {
	padding-top: 60px;
}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script>
window.fbAsyncInit = function() {
  FB.init({
    appId      : '141016739323676',
    status     : true, 
    cookie     : true,
	xfbml      : true
  });
};
(function(d){
   var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
   js = d.createElement('script'); js.id = id; js.async = true;
   js.src = "//connect.facebook.net/nb_NO/all.js";
   d.getElementsByTagName('head')[0].appendChild(js);
 }(document));
</script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
<script src="<?= HD_CORE_URL ?>js/bootstrap.min.js"></script>
<script src="<?= HD_CORE_URL ?>js/timeago.js"></script>
<script src="<?= HD_CORE_URL ?>js/jqplug.autoresize.js"></script>
<?php
if(is_array($hd_jsfiles['head'])) {
	foreach($hd_jsfiles['head'] as $file){ ?>
		<script src="<?= $file ?>"></script>
<?php 
	}
} ?>
<link href="<?= HD_CORE_URL ?>css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="<?= HD_CORE_URL ?>css/hd-admin.css" rel="stylesheet" media="screen">
<?php
if(is_array($hd_cssfiles)) {
	foreach($hd_cssfiles as $file){ ?>
		<link href="<?= $file ?>" rel="stylesheet" media="screen">
<?php 
	}
} ?>
</head>
<div id="fb-root"></div>
<?php
if(!isset($HIDE_MENU)) { ?>
<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
	
	    <div class="btn-group active_user hidden-phone">
		    <a class="btn btn-small dropdown-toggle" data-toggle="dropdown" href="#">
		    <?= $current_user->name ?>
		    <img src="http://graph.facebook.com/<?= $current_user->username?>/picture" width="30" />
		    </a>
		    <ul class="dropdown-menu">
		    	<?php 
		    	if(is_array($hd_menu['admin-user'])) {
		    		ksort($hd_menu['admin-user']);
			    	foreach($hd_menu['admin-user'] as $menu){ ?>
			    		<li><a href="<?= HD_ADMIN_URL.$menu['function']?>/"><?= $menu['name']?></a></li>
				    <?php
			    	}
		    	}?>
		    </ul>
	    </div>
		<div class="container">
			<a class="btn btn-navbar pull-right" id="userprofilemobile" data-toggle="collapse" data-target=".user-nav-collapse">
				<img src="http://graph.facebook.com/<?= $current_user->username?>/picture" width="30" />
			</a>
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			
			<a class="brand" href="<?= HD_ADMIN_CORE_URL ?>">HD-admin</a>
			<?php require_once('menu.php'); ?>
		</div>
		<div class="container visible-phone">
			<div class="user-nav-collapse collapse">
				<ul class="nav">
				    <li><a href="#">Min profil</a></li>
					<?php 
			    	if(is_array($hd_menu['admin-user'])) {
			    		ksort($hd_menu['admin-user']);
				    	foreach($hd_menu['admin-user'] as $menu){ ?>
				    		<li>
				    			<a href="<?= HD_ADMIN_URL.$menu['function']?>/"><?= $menu['name']?></a>
				    		</li>
					    <?php
				    	}
			    	}?>
				</ul>
			</div>
		</div>
	</div>
</div>
<?php } ?>
<div class="container">