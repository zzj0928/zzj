<?php
/**
 * 基类
 * @author zzj
 */
// no direct access
defined( 'EXEC' ) or die( 'Restricted access' );

class Base extends Date
{

  var $path_root ='';
  var $path_class ='';
  var $path_control ='';
  var $path_model ='';
  var $db ='';

  /*/构造函数
  *@parame path 应用路径
  *return null
  */
  public function Base(){
    if(is_file(PATH_COMMON.DS.'conf'.DS.'config.php') ){
      // config.php文件返回一个数组
      // C函数判断是一个数组，则会将这个数组赋值给 $_config，下面我们用在这个变量里面读取配置 
      C(include PATH_COMMON.DS.'conf'.DS.'config.php');
    }
    $this->path_root =PATH_ROOT;
    $this->path_class =PATH_CLASS;
    $this->path_control=PATH_BASE.DS.'controls';
    $this->path_model=PATH_BASE.DS.'models';
    load_ext_file(PATH_COMMON);
  }

  //加载模型
  //load model
  function model($name){
    $name = 'model_' . $name;
    include_once (PATH_BASE.DS.'models'.DS.$name.'.php');
    if($this->model==null){
      $this->model=new $name();
    }
    return $this->model;
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
  //create template 
  function template($file){
    include_once (PATH_CLASS.DS.'template'.DS.'class.smarttemplate.php');
    $Template = new Smarttemplate($file);
    $Template->template_dir=PATH_BASE.C('template_dir');
    $Template->cache_dir =PATH_BASE.C('cache_dir');

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

}