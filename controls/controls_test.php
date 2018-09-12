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


    // $sql = "INSERT INTO `my_test` (`name`, `addtime`) VALUES ('gsdf', '2018-09-11 18:07:22')"; 
    $sql = "select * from `my_test` order by id desc"; 
    $res = Date::quere($sql);
    echo_json($res);
  	
  	exit;
  	///////////////////////////////
    $a = '12345678';
    $str = '';
    for ($i=0; $i < strlen($a); $i++) {
      $str .= $this->model('test')->numToStr($a[$i]);
      $arr[] = $this->model('test')->numToStr($a[$i]);
    }
    $returnArr['code']=200;
    $returnArr['info']='ok';
    $returnArr['data']=$arr;
    echo_json($returnArr);
  }

}