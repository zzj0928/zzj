<?php

// no direct access
defined( 'EXEC' ) or die( 'Restricted access' );

class params extends Base{

	public function get($name=''){
		if (empty($name)) {
			return $_GET;
		}else{
			return $_GET[$name];
		}
	}
	
	public function post($name=''){
		if (empty($name)) {
			return $_POST;
		}else{
			return $_POST[$name];
		}
	}

}