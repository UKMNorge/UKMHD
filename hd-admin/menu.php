<div class="nav-collapse collapse">
	<ul class="nav">
	<?php
	@ksort($hd_menu['admin']);
	if(is_array($hd_menu['admin']))
		foreach($hd_menu['admin'] as $menu){ ?>
			<li><a href="<?= HD_ADMIN_URL.$menu['function']?>/"><?= $menu['name']?></a></li>
		<?php	
		}?>
	</ul>
</div><!--/.nav-collapse -->
