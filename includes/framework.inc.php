<?php

// no direct access
defined( 'EXEC' ) or die( 'Restricted access' );

//load error class
require_once( PATH_CLASS.DS.'error.class.php'); //包含错误处理文件
//load base class
require_once( PATH_CLASS.DS.'base.class.php' ); //包含基类文件

//实例化基类
$load=new Base();

//实列化请求类 用于获取GET或POST
//加截一个自己写的类的方法
//把自己写的类放在class 文件夹下面,文件名的格式为demo.class.php
//那么类名就为 class demo{}
//实便化的方式为demo=load->library(‘demo’);
//调用demo类的Get函数则为 demo->Get(‘参数’);

$params=$load->library('params');
//不同的目录用不同的URL方式
require_once( PATH_BASE.DS.'includes'.DS.'router.inc.php' );

?>