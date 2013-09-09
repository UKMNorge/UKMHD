<?php
class hd_user {
	public function __construct($face_id, $u_id=false) {
		if(!$face_id)
			$current_user_qry = new SQL("SELECT * FROM `hd_users` WHERE `u_id` = '#uid'",
										array('uid'=>(int)$u_id));
		else 
			$current_user_qry = new SQL("SELECT * FROM `hd_users` WHERE `face_id` = '#faceid'",
										array('faceid'=>$face_id));
		$current_user_info = $current_user_qry->run('array');
		
		if(!$current_user_info)
			return false;
		
		foreach($current_user_info as $key => $val) {
			$classkey = $this->_classkey($key);
				
			$this->$classkey = $val;
		}
		$this->name = $this->firstname .' '. $this->lastname;
		
		if(!empty($current_user_info['productions'])) {
			$this->productions = unserialize($current_user_info['productions']);
		} else {
			$this->productions = false;
		}
	}
	
	private function _classkey($key) {
		if(strpos($key, 'u_') === 0) 
			return substr($key, 2);

		return $classkey = $key;
	}
	
	public function get($key) {
		return $this->$key;
	}
	
	public function set($key, $val) {
		$classkey = $this->_classkey($key);
		$this->$classkey = $val;
		if(is_array($val)) {
			$dbval = serialize($val);
		} else {
			$dbval = $val;
		}
		
		$qry = new SQLins('hd_users', array('face_id'=>$this->face_id));
		$qry->add($key, $dbval);
		#echo $qry->debug();
		$qry->run();
	}
	
	public function myrole($project) {
		if(!isset($this->productions[$project]))
			return false;
		return $this->productions[$project];
	}
}