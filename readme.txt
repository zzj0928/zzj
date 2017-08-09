一.文件结构
├　index.php 入口文件
├ class 类存放的文件夹
　　　├ base.class.php 基类
　　　├ error.class.php 错误处理类
　　　├ parms.class.php 获取参数类
├ control.class.php 控制类
├ model.class.php 模型类
├ template smartTemplate 类存放文件夹
├ db adodb 类存放文件夹
├ includes 包含文件类
      ├ defines.inc.php 定义各路径文件
　　　├ frameword.inc.php 框架处理文件
　　　├ router.inc.php 路由文件,跟据参数,跳转不同路径
├ models 模式存放路径
├ views 模版文件保存路径
├ controls 存放控制类的文件夹
├ config.php 配置文件
├ admin 后台
├
├
二.简单类图

三. 入口文件,index.php
<?php

define("EXEC",1); //初始化一个常量,保存别的文件必须先有这个入口文件的引用.

define('PATH_BASE',dirname(__FILE__)); //获取入口文件的路径

define('DS', DIRECTORY_SEPARATOR); //目录的分隔,’/’ 或’’

define( 'PATH_ROOT', PATH_BASE ); //站点的跟目录,跟据你的入口文件存放来定义,如果放在子目录下,则要做相应修改.如下,
if (!defined('PATH_ROOT')) {
	//把路径分组
	$parts = explode( DS, PATH_BASE );
	//去除最后一个路径名
	array_pop( $parts );
	define( 'PATH_ROOT', implode( DS, $parts ) );
}

require_once(PATH_ROOT.DS.'includes'.DS.'defines.inc.php'); //各文件夹的路径
require_once(PATH_ROOT.DS.'includes'.DS.'framework.inc.php');

 // echo $request->Get('a');//参数的获取GET方法
 // $request->Post('a');//获取POST参数

?>

四. 路径定义文件 defines.inc.php
<?php
/*
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

?>

五. 框架文件路径 frameword.inc.php
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

六. 基类函数包含了常用的功能 base.class.php
<?php

// no direct access
defined( 'EXEC' ) or die( 'Restricted access' );

class Base extends Error
{

	var $path_root ='';
	var $path_class ='';
	var $path_control ='';

	/*/构造函数
	*@parame path 应用路径
	*return null
	*/
	public function Base(){
		$this->path_root =PATH_ROOT;
		$this->path_class =PATH_CLASS;
		$this->path_control=PATH_BASE.DS.'controls';
	}

	//加载模型
	//load model
	function model($name){
		include_once ($this->path_model.DS.$name.'.php');

		$model=null;
		if($model==null){
			$model=new name();
		}
		return $model;

	}

	//output page
	/*
	*@paramater name file name
	*@paramater data replace
	*
	*/
	function view($name,$data){
		$Template=self::template($name);
		$Template->output($data);
		unset($Template);
	}

	//create database connection
	function database(){
		include_once (PATH_CLASS.DS.'db'.DS.'adodb.inc.php');
		$this->db = ADONewConnection();
		$this->db->createdatabase = true;
		$result = $this->db->Connect(self::config('db_host') , self::config('db_user'), self::config('db_password'), self::config('db_database') );
		if(!$result){
			die("Could not connect to the database.");
		}else{
			$this->db->Execute("set names 'utf8'");
			return $this->db;
		}
	}

	//create template 
	function template($file){
		include_once (PATH_CLASS.DS.'template'.DS.'class.smarttemplate.php');
		$Template = new Smarttemplate($file);
		$Template->template_dir=PATH_BASE.self::config('template_dir');
		$Template->cache_dir =PATH_BASE.self::config('cache_dir');

		return $Template;
	}

	//import class 
	function library($className){
		if(empty($className)){
			return null;
			exit();
		}else{
			require_once(PATH_CLASS.DS.$className.'.class.php');
			return new $className();
		}
	}

	//return config value
	function config($parameter){
		$conf = require_once(PATH_CONFIG.DS.'config.php');

		// return CONFIG::Ini()->$parameter;
		return $conf[$parameter];
	}
}
?>

七. 控制器父类control.class.php
<?php
class Control extends Base{
	var $request;
	function Control(){
		$this->request=$this->library('params');
	}

	//output page
	/*
	*@paramater name file name
	*@paramater data replace
	*
	*/
	function view($name,$data=''){
		$Template=$this->template($name.'.html');
		$Template->assign('site_root',$this->config('site_root'));

		$Template->assign($data);
		$Template->output();
		unset($Template);
	}

	//load model
	function model($name){

		include_once (PATH_BASE.DS.'models'.DS.$name.'.php');

		if($this->model==null){
			$this->model=new $name();
		}
		return $this->model;

	}

}

?>

八. 模型父类 model.class.php
 <?php

class Model extends Base
{
	var $request;
	var $db;
	function Model(){
		$this->request=$this->library('params');
	}

	//create database connection
	function database(){
		if($this->db!=null){
			return $this->db;
			exit;
		}
		include_once (PATH_CLASS.DS.'db'.DS.'adodb.inc.php');
		$this->db = ADONewConnection();
		$this->db->createdatabase = true;
		$result = $this->db->Connect($this->config('db_host') , $this->config('db_user'), $this->config('db_password'), $this->config('db_database') );
		if(!$result){
			die("Could not connect to the database.");
		}else{
			$this->db->Execute("set names 'utf8'");
			return $this->db;
		}
	}
}

?>

九. 路由文件, router.inc.php
跟据URL,指定到相应的控制器,跟据网站的URL表现形式编写
<?php
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
include_once (PATH_BASE.DS.'controls'.DS.$option.'.php');

$Control=new $option();

if(empty($task)){
	$Control->index();
}else{
	$Control->$task();
}


?>

十,使用方法
使用方法.
视图V:在把模板文件放在VIEW文件夹里面.命名方式为,login.html
控制C:把要怎么处理的文件放在control文件夹里面,
格式为.demo.php 
class demo extends Control
{
function edit()
{
data=this->user->issuer();//调用模型
this->view(‘login’,data);//这个LOGIN为视图中的login.html文件
}
}
模型 M:把文件放在 model文件夹里面.格式为.user.php

class user extends Model
{
function issuer()
{
数据库或底层的操作,返回结果
}
}
自定义类的调用,把类放在Class文件夹下面.格式为session.class.php
先实例化.
session=load->library(‘session’);
然后再调用类中的函数
session->sessionId();
例子:
如下URL: http://127.0.0.1/com/admin/?c=test&a=getUser (注:这里的URL格式可以在router.inc.php里面修改.同时为了让不同目录的地址格式不一样,比如前台和后台,因些,把router.inc.php规则放在了入口文件的includes目录下面) 则会调用/admin/control/文件夹下面的test.php文件test类的getUser 函数,如果task参数为空,则调用index函数.
文件:/admin/control/test.php
<?php
class test extends Control
{
function index()
{
} 
function getUser()
{
data['user']=this->model('member')->getUser();
this->view('test',data);
} 
function reg()
{
if(this->model('member')->isUser())
{
this->Alert(‘用户名已存在’); //error类中定义了
}
else
{
this->model('member')->addUser();
}
}
}
?>
如果this->model('member')这个模型经常用到,可以在引用类的构造函数中初始化,不要每次都实例化.如:
Var member;
function test()
{
this->member=this->model('member');
}
这样,应用的时候只要. this->member ->addUser();
this->model(‘member’)->getUser();
为调用入口文件相应目录下的Models 文件夹下面的member.php文件里面的getUser();函数
文件:/admin/models/member.php
<?php
class member extends Model
{
function getUser()
{
//return this->db->GetOne("select username from user where userId=1");//这种方式处理数据库
//this->request->Get(‘test’) 
//this->request->Post(‘test’) 这种方式获取参数,在父类中已定义 
return 'admin';
} 
//验证用户名是否已经存在
function isUser()
{
userName=this->request->Post(‘username’);
passWord=this->request->Post(‘password’);
return this->db->GetOne(“select count(*) from user where username=’userName’ and password=’passWord’”);
}
}
?>
this->view(‘test’,data); 
显示视图,调用/admin/views/test.html文件,用SmartTemplate 类的方式生成 data 为数组
这部份请查看smartTemplate 类的使用方法