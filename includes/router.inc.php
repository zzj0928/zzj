<?php
/**
 * 路由规则
 * @author zzj
 * http://www.zzj.com/index.php?c=index&a=index
 */
// no direct access
defined( 'EXEC' ) or die( 'Restricted access' );

//实例化类
$load=new Base();

//实列化请求类 用于获取GET或POST
$request=$load->library('params');

//获取参数
$option=@$request->get('c');
$task =@$request->get('a');

//如果未设置文件
if(empty($option)){
  $option=$load->config('index_router');
}
include_once (PATH_BASE.DS.'controls'.DS.'controls_'.$option.'.php');

$Control=new $option();

if(empty($task)){
  $Control->index();
}else{
  $Control->$task();
}