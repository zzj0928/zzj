<?php

class Model extends Base
{
	var $request;
	var $db;
	function Model(){
		$this->request=$this->library('params');
	}

	//create database connection
	function database(){
		if($this->db!=null){
			return $this->db;
			exit;
		}
		include_once (PATH_CLASS.DS.'db'.DS.'adodb.inc.php');
		$this->db = ADONewConnection();
		$this->db->createdatabase = true;
		$result = $this->db->Connect($this->config('db_host') , $this->config('db_user'), $this->config('db_password'), $this->config('db_database') );
		if(!$result){
			die("Could not connect to the database.");
		}else{
			$this->db->Execute("set names 'utf8'");
			return $this->db;
		}
	}
}

?>