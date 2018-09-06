<?php
/**
 * 入口文件
 * @author zzj
 */
define("EXEC",1); //初始化一个常量,保存别的文件必须先有这个入口文件的引用.确保入口唯一

define('PATH_BASE',dirname(__FILE__)); //获取入口文件的路径

define('DS', DIRECTORY_SEPARATOR); //目录的分隔,’/’ 或’’

define( 'PATH_ROOT', PATH_BASE ); //站点的跟目录,跟据你的入口文件存放来定义,如果放在子目录下,则要做相应修改.
if (!defined('PATH_ROOT')) {
	//把路径分组
	$parts = explode( DS, PATH_BASE );
	//去除最后一个路径名
	array_pop( $parts );
	define( 'PATH_ROOT', implode( DS, $parts ) );
}

require_once(PATH_ROOT.DS.'includes'.DS.'defines.inc.php'); //各文件夹的路径
require_once(PATH_ROOT.DS.'includes'.DS.'framework.inc.php');
