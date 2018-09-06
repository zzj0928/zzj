<?php
/*
 * 常量定义
 * @author zzj
*/
// no direct access
defined( 'EXEC' ) or die( 'Restricted access' ); //如果单独执行这个文件,将不能执行

//站点路径
define( 'PATH_SITE', PATH_ROOT );
//包含文件路径
define( 'PATH_INCLUDE', PATH_ROOT.DS.'includes');
//类文件路径
define( 'PATH_CLASS', PATH_ROOT.DS.'class' );
//配置文件路径
define( 'PATH_CONFIG', PATH_ROOT );