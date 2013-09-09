<?php
/* 
Part of: UKM Norge core
Description: SQL-klasse for bruk av SQL-sp¿rringer opp mot UKM-databasen.
Author: UKM Norge / M Mandal 
Version: 2.0 
*/

##################################################################################################################
##################################################################################################################
##												SQL CLASS TO RUN QUERIES										##
##################################################################################################################
##################################################################################################################
class SQL {
	var $sql;
	var $db;
	
	function SQL($sql, $keyval=array()) {
		global $db;
		foreach($keyval as $key => $val) {
			if (get_magic_quotes_gpc())
				$val = stripslashes($val);
			$this->connect();
			$sql = str_replace('#'.$key, mysql_real_escape_string(trim(strip_tags($val))), $sql);
		}
		$this->sql = $sql;
	}
	function connect() {
		$this->db = mysql_connect(HD_DB_HOST, HD_DB_USER, HD_DB_PASSWORD) or die(mysql_error());
		if (!$this->db) die($ERR);
		mysql_select_db(HD_DB_NAME,$this->db);	
	}
	
	function run($what='resource', $name='') {
		$this->connect();
		$temp = mysql_query($this->sql, $this->db);
		if(!$temp) return false;
		switch($what) {
			case 'field':
				$temp = mysql_fetch_array($temp);
				return $temp[$name];
			case 'array':
				return mysql_fetch_assoc($temp);
			default:
				return $temp;
		}
	}
	function debug() {
		return $this->sql.'<br />';
	}
	
	function insid() {
		return mysql_insert_id( $this->db );
	}
}

##################################################################################################################
##################################################################################################################
##												SQL CLASS TO DELETE STUFF										##
##################################################################################################################
##################################################################################################################
class SQLdel {
	var $db;
	var $sql;
	
	function SQLdel($table, $where=array()) {
		$this->table = $table;
		$wheres = '';
		$max = sizeof($where);
		$i = 0;
		foreach($where as $field => $val) {
			$i++;
			if( intval( $val ) > 0 )
                $wheres .= "`".$field."` = ".$val;
            else
                $wheres .= "`".$field."` = '".$val."'";
                
			if($i<$max)
				$wheres .= ' AND ';
		}
		
		$this->sql = 'DELETE FROM `'.$table.'` WHERE '.$wheres.';';
		$this->connect();
	}
	
	function truncate() {
		$this->sql = 'DELETE FROM `'.$this->table.'`';# ALTER TABLE `'.$this->table.'` AUTO_INCREMENT = 1';
	}
	
	function connect() {
		$this->db = @mysql_connect(HD_DB_HOST, HD_DB_WRITE_USER, HD_DB_PASSWORD) or die(mysql_error());
		if (!$this->db) die($ERR);
		mysql_select_db(HD_DB_NAME,$this->db);
	}
	
	function debug() {
		return $this->run(false);
	}	
	
	function run($run=true) {
		if(!$run)
			return $this->sql.'<br />';
		$qry = mysql_query($this->sql, $this->db);
		//echo mysql_error();
		return mysql_affected_rows();
	}
}
##################################################################################################################
##################################################################################################################
##												SQL CLASS TO INSERT STUFF										##
##################################################################################################################
##################################################################################################################
class SQLupd extends SQLins {
	public function __construct($table, $where){
		parent::SQLins($table, $where);
		$this->setInsIfUpdateFailed();
	}
}
class SQLins {
	var $wheres = ' WHERE ';
	var $db;
	var $insertIfUpdateFailed = false;
	
	function setInsIfUpdateFailed(){
		$this->insertIfUpdateFailed = true;
	}
	
	function SQLins($table, $where=array()) {
		$this->table = $table;
		## IF THIS IS A UPDATE-QUERY
		if(sizeof($where) > 0) {
			$this->update = true;
			foreach($where as $key => $val) {
				$this->wheres .= "`".$key."`='".$val."' AND ";
			}
			$this->wheres = substr($this->wheres, 0, (strlen($this->wheres)-5));
		## IF THIS IS A INSERT-ARRAY
		} else {
			$this->update = false;	
		}
	}
	
	function add($key, $val) {
		$this->keys[] = $key;
		$this->vals[] = $val;
	}
	
	function debug() {
		return $this->run(false);
	}
	
	function run($run=true) {
		$keylist = $vallist = '';
		if($this->update) {
			## init query
			$sql = 'UPDATE `'.$this->table.'` SET ';
			## set new values
			for($i=0; $i<sizeof($this->keys); $i++) {
				$sql .= "`".$this->keys[$i]."` = '".$this->vals[$i]."', ";
			}
			$sql = substr($sql, 0, (strlen($sql)-2));

			## add where
			$sql .= $this->wheres;	
		} else {
			## set the new values
			for($i=0; $i<sizeof($this->keys); $i++) {
				$keylist .= '`'.$this->keys[$i].'`, ';
				$vallist .= "'".$this->vals[$i]."', ";
			}
			$keylist = substr($keylist, 0, (strlen($keylist)-2));
			$vallist = substr($vallist, 0, (strlen($vallist)-2));
			## complete query
			$sql = 'INSERT INTO `'.$this->table.'` ('.$keylist.') VALUES ('.$vallist.');';
		}
			
		$this->connect();
		if(!$run) return $sql.'<br />';
				#'<div class="widefat" style="margin: 12px; margin-top: 18px; width: 730px;padding:10px; background: #f1f1f1;">'.$sql.'</div>';
		else{
			$res = mysql_query($sql, $this->db);
			if($this->insertIfUpdateFailed && mysql_affected_rows()==0){
				$check = new SQL("SELECT * FROM `".$this->table."` ".$this->wheres);
				$check = $check->run();
				if(mysql_num_rows($check)==0){
					$this->update = false;
					$this->run();
				}
			}
			return mysql_affected_rows();
		}
	}
	
	function connect() {
		$this->db = @mysql_connect(HD_DB_HOST, HD_DB_WRITE_USER, HD_DB_PASSWORD) or die($ERR);
		if (!$this->db) die($ERR);
		mysql_select_db(HD_DB_NAME,$this->db);
	}
	function insid() {
		return mysql_insert_id( $this->db );
	}
}
?>