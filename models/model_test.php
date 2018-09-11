<?php
// no direct access
defined( 'EXEC' ) or die( 'Restricted access' );

class model_test extends Model{
  public function test(){
    return "hello word";
  }
  //数字转中文方法
  public function numToStr($num=0){
    $numArray =array('零','一','二','三','四','五','六','七','八','九');
    return $numArray[$num];
  }
}