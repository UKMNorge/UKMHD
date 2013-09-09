<?php
function production_gui_settings_lists(){
	if(isset($_POST['list_new'])) {
		foreach($_POST as $key => $val) {
			if( strpos($key, 'list_') === 0 ) {
				if($key == 'list_new') {
					$qry = new SQLins('hd_production_lists');
					$qry->add('order', time());
				} else
					$qry = new SQLins('hd_production_lists', array('l_id'=> str_replace('list_','', $key)) );

				$qry->add('l_name', $val);
				
				if($key != 'list_new' || ($key == 'list_new' && !empty($_POST['list_new'])))
					$qry->run();
			}			
		}
	} ?>
	<h4>Lister</h4>
	<form action="#submit" method="post">
	<ul class="unstyled span12">
		<li>
			<div class="input-append">
				<input type="text" name="list_new" placeholder="Ny liste.." />
				<span class="add-on submitform"><i class="icon-plus-sign"></i></span>
			</div>
		</li>
		<li>&nbsp;</li>
	</ul>
	<ul class="unstyled span12" id="clarification_lists_order">
	<?php
	foreach(production_lists() as $id => $name) { ?>
		<li id="clar_list_<?= $id ?>">
			<div class="input-append">
				<input type="text" name="list_<?= $id ?>" value="<?= $name ?>" />
				<span class="add-on"><i class="icon-move"></i></span>
			</div>
		</li>
	<?php
	} ?>
	</ul>
	<input type="submit" name="submitlists" value="Lagre" class="btn btn-success" />
	</form>
	<?php
}
?>