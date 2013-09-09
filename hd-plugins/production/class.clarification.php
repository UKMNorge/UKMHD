<?php

class clarification {
	var $span = 5;
	public function __construct($clar_id){
		$qry = new SQL("SELECT * FROM `hd_production_clarifications`
						WHERE `c_id` = '#cid'",
						array( 'cid' => $clar_id ));
		$res = $qry->run('array');
		foreach( $res as $key => $val ) {
			$this->$key = $val;
		}
		$this->update();
		$this->load();
		$this->span();
#		$this->help();
	}
	
	private function help(){
		if($this->c_type == 'dropboximage') {
			$this->value = $this->c_help;
			$this->c_help = '';
			hd_load('curl');
		}
	}
	private function span() {
		if($this->c_type == 'longtext')
			$this->span = 10;
	}
	public function update() {
		global $current_user;
		if(isset($_POST['clarify_'.$this->c_id])) {
			$this->value = $_POST['clarify_'.$this->c_id];
			
			$test = new SQL("SELECT `cv_id` FROM `hd_production_clarification_values`
							 WHERE `c_id` = '#cid'
							 AND `p_id` = '#pid'",
							 array('cid' => $this->c_id, 
							 	   'pid' => $_GET['HD_PRODUCTION']));
			$test = $test->run('field','cv_id');
			if(is_numeric($test) && $test > 0)
				$sql = new SQLins('hd_production_clarification_values', array('cv_id' => $test) );
			else
				$sql = new SQLins('hd_production_clarification_values');
			
			$sql->add('c_id', $this->c_id);
			$sql->add('p_id', $_GET['HD_PRODUCTION']);
			$sql->add('cv_value', $this->cleanValue());
			$sql->add('cv_clarifier', $current_user->id);
			
			$sql->run();
		}
	}
	
	private function cleanValue() {
#		if($this->c_type == 'longtext')
#			return nl2br($this->value);
		
		if($this->c_type == 'faceevent') {
			if(!empty($this->value)) {
				if(is_numeric($this->value))
					return $this->value;
					
				$search = 'facebook.com/events/';
				$start = strpos($this->value, $search) + strlen($search);
				$stop = @strpos($this->value, '/', $start);
				return substr($this->value, $start, ($stop - $start));
			}
		}
		
		return $this->value;
	}
	
	public function load() {
		
		$test = new SQL("SELECT * FROM `hd_production_clarification_values`
						 WHERE `c_id` = '#cid'
						 AND `p_id` = '#pid'",
						 array('cid' => $this->c_id, 
						 	   'pid' => $_GET['HD_PRODUCTION']));
		$test = $test->run('array');
		if(!isset($this->value)) {
			$this->value = $test['cv_value'];
		}
		if(empty($test['cv_clarifier']))
			$this->updated_by = false;
		else {
			$this->updated_by = new hd_user(false, $test['cv_clarifier']);
			$this->updated = $test['cv_time'];
		}
	}
	
	public function draw() { ?>
		<div class="clarification well well-small span<?= $this->span ?> pull-left <?= $this->c_critical == 'true' ? 'clar_critical':''?>" id="c_<?= $this->c_id ?>"  >
			<div class="info">
				<div class="title"><?= $this->c_name ?></div>
				<div class="help"><?= $this->c_help ?></div>
			</div>
			<div class="input span<?= $this->span ?> input-prepend input-append">
				<?php
				switch($this->c_type) {
					case 'datetime':
					case 'text': ?>
						<input type="text" name="clarify_<?= $this->c_id ?>" value="<?= $this->value ?>" />
						<?php
						break;
					case 'date': ?>
						<input type="text" class="datepicker" name="clarify_<?= $this->c_id ?>" value="<?= $this->value ?>" />
						<?php
						break;
					case 'gmaps': ?>
						<input type="text" name="clarify_<?= $this->c_id ?>" value="<?= $this->value ?>" data-linkbase="http://maps.google.no/?q=" />
						 <span class="add-on inputlink"><i class="icon-globe"></i></span>
						<?php
						break;
					case 'longtext': ?>
						<textarea class="span9" name="clarify_<?= $this->c_id ?>"><?= $this->value ?></textarea>
						<?php
						break;
					case 'yesno': ?>
						<label><input type="radio" name="clarify_<?= $this->c_id ?>" <?= ($this->value == 'true' ? 'checked="checked"':'') ?> value="true"> ja </label>
						<label><input type="radio" name="clarify_<?= $this->c_id ?>" <?= ($this->value == 'false' ? 'checked="checked"':'') ?> value="false"> nei </label>
						<?php
						break;
					case 'faceevent': ?>
						<input type="text" name="clarify_<?= $this->c_id ?>" value="<?= $this->value ?>" data-linkbase="https://facebook.com/events/" />
						<span class="add-on inputlink"><i class="icon-calendar"></i></span>
						<?php
						break;
					case 'dropboximage': ?>
						<a href="<?= $this->value ?>" target="_blank">
							<img src="<?= $this->value?>" class="span<?= ($this->span)-1 ?>" />
						</a>
						<div class="clearfix"></div>
						<?php
						if(hd_capa('production_clarification_setup')) { ?>
							<input type="text" class="span<?= ($this->span)-1 ?>" name="clarify_<?= $this->c_id ?>" value="<?= $this->value ?>" />
						<?php
						}
						break;
				} ?>
			</div>
			<?php $this->clarifier() ?>
		</div>
		<?php 
	}
	
	private function clarifier() {
		if($this->c_type == 'dropboximage')
			return;
		if(!$this->updated_by){ ?>
				<div class="clarifier"><a class="assign" href="javascript:alert('funksjon kommer');">[klikk her for å tildele]</a></div>
		<?php
		} else { ?>
			<div class="clarifier"><?= $this->updated_by->name ?> for <time class="timeago" datetime="<?= $this->updated ?>"><?= $this->updated ?></time></div>
		<?php }
	}
}