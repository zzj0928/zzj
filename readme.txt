һ.�ļ��ṹ
����index.php ����ļ�
�� class ���ŵ��ļ���
�������� base.class.php ����
�������� error.class.php ��������
�������� parms.class.php ��ȡ������
�� control.class.php ������
�� model.class.php ģ����
�� template smartTemplate �����ļ���
�� db adodb �����ļ���
�� includes �����ļ���
      �� defines.inc.php �����·���ļ�
�������� frameword.inc.php ��ܴ����ļ�
�������� router.inc.php ·���ļ�,���ݲ���,��ת��ͬ·��
�� models ģʽ���·��
�� views ģ���ļ�����·��
�� controls ��ſ�������ļ���
�� config.php �����ļ�
�� admin ��̨
��
��
��.����ͼ

��. ����ļ�,index.php
<?php

define("EXEC",1); //��ʼ��һ������,�������ļ����������������ļ�������.

define('PATH_BASE',dirname(__FILE__)); //��ȡ����ļ���·��

define('DS', DIRECTORY_SEPARATOR); //Ŀ¼�ķָ�,��/�� �򡯡�

define( 'PATH_ROOT', PATH_BASE ); //վ��ĸ�Ŀ¼,�����������ļ����������,���������Ŀ¼��,��Ҫ����Ӧ�޸�.����,
if (!defined('PATH_ROOT')) {
	//��·������
	$parts = explode( DS, PATH_BASE );
	//ȥ�����һ��·����
	array_pop( $parts );
	define( 'PATH_ROOT', implode( DS, $parts ) );
}

require_once(PATH_ROOT.DS.'includes'.DS.'defines.inc.php'); //���ļ��е�·��
require_once(PATH_ROOT.DS.'includes'.DS.'framework.inc.php');

 // echo $request->Get('a');//�����Ļ�ȡGET����
 // $request->Post('a');//��ȡPOST����

?>

��. ·�������ļ� defines.inc.php
<?php
/*
*/
// no direct access
defined( 'EXEC' ) or die( 'Restricted access' ); //�������ִ������ļ�,������ִ��

//վ��·��
define( 'PATH_SITE', PATH_ROOT );
//�����ļ�·��
define( 'PATH_INCLUDE', PATH_ROOT.DS.'includes');
//���ļ�·��
define( 'PATH_CLASS', PATH_ROOT.DS.'class' );
//�����ļ�·��
define( 'PATH_CONFIG', PATH_ROOT );

?>

��. ����ļ�·�� frameword.inc.php
<?php

// no direct access
defined( 'EXEC' ) or die( 'Restricted access' );

//load error class
require_once( PATH_CLASS.DS.'error.class.php'); //�����������ļ�
//load base class
require_once( PATH_CLASS.DS.'base.class.php' ); //���������ļ�

//ʵ��������
$load=new Base();

//ʵ�л������� ���ڻ�ȡGET��POST
//�ӽ�һ���Լ�д����ķ���
//���Լ�д�������class �ļ�������,�ļ����ĸ�ʽΪdemo.class.php
//��ô������Ϊ class demo{}
//ʵ�㻯�ķ�ʽΪdemo=load->library(��demo��);
//����demo���Get������Ϊ demo->Get(��������);

$params=$load->library('params');
//��ͬ��Ŀ¼�ò�ͬ��URL��ʽ
require_once( PATH_BASE.DS.'includes'.DS.'router.inc.php' );

?>

��. ���ຯ�������˳��õĹ��� base.class.php
<?php

// no direct access
defined( 'EXEC' ) or die( 'Restricted access' );

class Base extends Error
{

	var $path_root ='';
	var $path_class ='';
	var $path_control ='';

	/*/���캯��
	*@parame path Ӧ��·��
	*return null
	*/
	public function Base(){
		$this->path_root =PATH_ROOT;
		$this->path_class =PATH_CLASS;
		$this->path_control=PATH_BASE.DS.'controls';
	}

	//����ģ��
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

��. ����������control.class.php
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

��. ģ�͸��� model.class.php
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

��. ·���ļ�, router.inc.php
����URL,ָ������Ӧ�Ŀ�����,������վ��URL������ʽ��д
<?php
// no direct access
defined( 'EXEC' ) or die( 'Restricted access' );

//ʵ������
$load=new Base();

//ʵ�л������� ���ڻ�ȡGET��POST
$request=$load->library('params');

//��ȡ����
$option=@$request->get('c');
$task =@$request->get('a');

//���δ�����ļ�
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

ʮ,ʹ�÷���
ʹ�÷���.
��ͼV:�ڰ�ģ���ļ�����VIEW�ļ�������.������ʽΪ,login.html
����C:��Ҫ��ô������ļ�����control�ļ�������,
��ʽΪ.demo.php 
class demo extends Control
{
function edit()
{
data=this->user->issuer();//����ģ��
this->view(��login��,data);//���LOGINΪ��ͼ�е�login.html�ļ�
}
}
ģ�� M:���ļ����� model�ļ�������.��ʽΪ.user.php

class user extends Model
{
function issuer()
{
���ݿ��ײ�Ĳ���,���ؽ��
}
}
�Զ�����ĵ���,�������Class�ļ�������.��ʽΪsession.class.php
��ʵ����.
session=load->library(��session��);
Ȼ���ٵ������еĺ���
session->sessionId();
����:
����URL: http://127.0.0.1/com/admin/?c=test&a=getUser (ע:�����URL��ʽ������router.inc.php�����޸�.ͬʱΪ���ò�ͬĿ¼�ĵ�ַ��ʽ��һ��,����ǰ̨�ͺ�̨,��Щ,��router.inc.php�������������ļ���includesĿ¼����) ������/admin/control/�ļ��������test.php�ļ�test���getUser ����,���task����Ϊ��,�����index����.
�ļ�:/admin/control/test.php
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
this->Alert(���û����Ѵ��ڡ�); //error���ж�����
}
else
{
this->model('member')->addUser();
}
}
}
?>
���this->model('member')���ģ�;����õ�,������������Ĺ��캯���г�ʼ��,��Ҫÿ�ζ�ʵ����.��:
Var member;
function test()
{
this->member=this->model('member');
}
����,Ӧ�õ�ʱ��ֻҪ. this->member ->addUser();
this->model(��member��)->getUser();
Ϊ��������ļ���ӦĿ¼�µ�Models �ļ��������member.php�ļ������getUser();����
�ļ�:/admin/models/member.php
<?php
class member extends Model
{
function getUser()
{
//return this->db->GetOne("select username from user where userId=1");//���ַ�ʽ�������ݿ�
//this->request->Get(��test��) 
//this->request->Post(��test��) ���ַ�ʽ��ȡ����,�ڸ������Ѷ��� 
return 'admin';
} 
//��֤�û����Ƿ��Ѿ�����
function isUser()
{
userName=this->request->Post(��username��);
passWord=this->request->Post(��password��);
return this->db->GetOne(��select count(*) from user where username=��userName�� and password=��passWord����);
}
}
?>
this->view(��test��,data); 
��ʾ��ͼ,����/admin/views/test.html�ļ�,��SmartTemplate ��ķ�ʽ���� data Ϊ����
�ⲿ����鿴smartTemplate ���ʹ�÷���