<?php
// no direct access
defined( 'EXEC' ) or die( 'Restricted access' );

/**
* 测试类 
* 地址：http://www.my.com/index.php?c=test&a=xxx
* @author zzj 20180911
*/
class test extends Control{
  //测试首页
  public function index(){
    $a = '12345678';
    $str = '';
    for ($i=0; $i < strlen($a); $i++) {
      $str .= $this->model('test')->numToStr($a[$i]);
    }
    echo $str;
  }

}