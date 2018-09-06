<?php
class Control extends Base{
	var $request;
	function Control(){
		$this->request=$this->library('params');
	}

	//output page
	/*
	*@paramater name file name
	*@paramater data replace
	*
	*/
	function view($name,$data=''){
		$Template=$this->template($name.'.html');
		$Template->assign('site_root',$this->config('site_root'));

		$Template->assign($data);
		$Template->output();
		unset($Template);
	}

	//load model
	function model($name){

		include_once (PATH_BASE.DS.'models'.DS.$name.'.php');

		if($this->model==null){
			$this->model=new $name();
		}
		return $this->model;

	}

}

?>