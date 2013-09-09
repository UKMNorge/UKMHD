<?php

class tabs {
	public function __construct($class){
		$this->css_class = $class;
		$this->active = isset($_GET['HD_TAB']) ? $_GET['HD_TAB'] : false;
		global $hd_active_tab;
		$hd_active_tab = $this->active;
	}
	
	public function add($id, $name) {
		$this->tabs[$id] = $name;
	}
	
	public function draw($return=false) {
		global $hd_active_tab;
		$html = '<div class="navbar">'
              	.'<div class="navbar-inner">'
              	.'<ul class="nav">';
		$i = 0;
		if(is_array($this->tabs)) {
			foreach($this->tabs as $id => $name) {
				if(!$this->active && $i == 0) {
					$this->active = $id;
					$hd_active_tab = $this->active;
				}
				$html .= '<li '.($this->active == $id ? ' class="active"' : '').'>'
						. '<a href="'.HD_ACTIVE_URL.$id.'/" id="tab_'.$class.'_'.$id.'">'.$name.'</a>'
						.'</li>';
			}
		}
		$html .= '</ul>'
				.'</div>'
				.'</div>';
				
		if($return)
			return $html;
		echo $html;
	}
}
?>