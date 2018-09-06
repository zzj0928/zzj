<?php
/**
 * 框架主体文件引入
 * @author zzj
 */
// no direct access
defined( 'EXEC' ) or die( 'Restricted access' );
//load error class
require_once( PATH_CLASS.DS.'error.class.php'); //包含错误处理文件
//load base class
require_once( PATH_CLASS.DS.'base.class.php' ); //包含基类文件
require_once( PATH_CLASS.DS.'control.class.php'); //包含错误处理文件
require_once( PATH_CLASS.DS.'model.class.php'); //包含错误处理文件
//不同的目录用不同的URL方式
require_once( PATH_BASE.DS.'includes'.DS.'router.inc.php' );
