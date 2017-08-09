<?php

// no direct access
defined( 'EXEC' ) or die( 'Restricted access' );

class Base extends Error
{

	var $path_root ='';
	var $path_class ='';
	var $path_control ='';

	/*/构造函数
	*@parame path 应用路径
	*return null
	*/
	public function Base(){
		$this->path_root =PATH_ROOT;
		$this->path_class =PATH_CLASS;
		$this->path_control=PATH_BASE.DS.'controls';
	}

	//加载模型
	//load model
	function model($name){
		include_once ($this->path_model.DS.$name.'.php');

		$model=null;
		if($model==null){
			$model=new name();
		}
		return $model;

	}

	//output page
	/*
	*@paramater name file name
	*@paramater data replace
	*
	*/
	function view($name,$data){
		$Template=self::template($name);
		$Template->output($data);
		unset($Template);
	}

	//create database connection
	function database(){
		include_once (PATH_CLASS.DS.'db'.DS.'adodb.inc.php');
		$this->db = ADONewConnection();
		$this->db->createdatabase = true;
		$result = $this->db->Connect(self::config('db_host') , self::config('db_user'), self::config('db_password'), self::config('db_database') );
		if(!$result){
			die("Could not connect to the database.");
		}else{
			$this->db->Execute("set names 'utf8'");
			return $this->db;
		}
	}

	//create template 
	function template($file){
		include_once (PATH_CLASS.DS.'template'.DS.'class.smarttemplate.php');
		$Template = new Smarttemplate($file);
		$Template->template_dir=PATH_BASE.self::config('template_dir');
		$Template->cache_dir =PATH_BASE.self::config('cache_dir');

		return $Template;
	}

	//import class 
	function library($className){
		if(empty($className)){
			return null;
			exit();
		}else{
			require_once(PATH_CLASS.DS.$className.'.class.php');
			return new $className();
		}
	}

	//return config value
	function config($parameter){
		$conf = require_once(PATH_CONFIG.DS.'config.php');

		// return CONFIG::Ini()->$parameter;
		return $conf[$parameter];
	}
}
?>