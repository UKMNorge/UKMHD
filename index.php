<?php
require_once('hd-loader.php');

#echo '<pre>'; var_dump($_SERVER); echo '</pre>';
if(isset($_SERVER['REDIRECT_URL']) && !empty($_SERVER['REDIRECT_URL'])) {
	$mod = preg_replace('/[^a-z]+/','', $_SERVER['REDIRECT_URL']);
} else {
	$mod = 'front';
}
?>
<!DOCTYPE html>
<html>
<head>
<title>hd.UKM.no</title>
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

<link href="<?= HD_CORE_URL ?>css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="<?= HD_CORE_URL ?>css/hd.css" rel="stylesheet" media="screen">

</head>
<body>

<?php require_once('hd-public/'.$mod.'.inc.php'); ?>

<script type="text/javascript">
 
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-46216680-6']);
  _gaq.push(['_trackPageview']);
 
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
 
</script>
</body>
</html>
