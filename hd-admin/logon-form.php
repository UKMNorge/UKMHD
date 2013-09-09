<form class="form-signin">
<h2 class="form-signin-heading">Logg inn</h2>
HD-admin er et passordbeskyttet område.
<a href="<?= $facebook->getLoginUrl($params)?>" id="facelogon" class="btn btn-large btn-primary" type="submit">Logg inn med facebook</a>
</form>
<?php register_jscode_foot("jQuery('body').addClass('logonform');",500); ?>