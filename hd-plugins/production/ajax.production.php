<?php
function save_clarification_order(){
	$order = 0;
	foreach($_POST['data'] as $id) {
		$order++;
		$sql = new SQLins('hd_production_clarifications', array('c_id' => str_replace('clar_','',$id)));
		$sql->add('order', $order);
		$sql->run();
	}
}

function save_clarification_lists_order(){
	$order = 0;
	foreach($_POST['data'] as $id) {
		$order++;
		$sql = new SQLins('hd_production_lists', array('l_id' => str_replace('clar_list_','',$id)));
		$sql->add('order', $order);
		echo $sql->debug();
		$sql->run();
	}
}