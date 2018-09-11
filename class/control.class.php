<?php
// no direct access
defined( 'EXEC' ) or die( 'Restricted access' );
/**
* 加载控制器类
* @author zzj 20180911
*/
class Control extends Base{
  var $request;
  function Control(){
    $this->request=$this->library('params');
  }
}