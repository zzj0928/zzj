<?php
/**
 * 基类
 * @author zzj
 */
// no direct access
defined( 'EXEC' ) or die( 'Restricted access' );

class Date{
  var $db ='';

  /*/构造函数
  *@parame path 应用路径
  *return null
  */
  public function Date(){
  }

  //create database connection
  function database(){
    include_once (PATH_CLASS.DS.'db'.DS.'adodb.inc.php');
    $this->db = ADONewConnection('mysqli');
    $this->db->createdatabase = true;
    $result = $this->db->Connect(C('DB_HOST') , C('DB_USER'), C('DB_PWD'), C('DB_NAME') );
    if(!$result){
      die("Could not connect to the database.");
    }else{
      $this->db->execute("set names 'utf8'");
      return $this->db;
    }
  }
  function execSql($sql=""){
    if (!$sql) {
      return false;
    }
    $conn = $this->database();
    $rst = $conn -> Execute($sql) or die('执行错误');
    $conn -> close();
    return $rst;
    
  }

  function quere($sql=''){
    if (!$sql) {
      return false;
    }
    $conn = $this->database();
    $rst = $conn->Execute($sql);
    $res = array();
    // var_dump($rst);exit;
    while(!$rst -> EOF){          //如果没有错误，则配合wihle语句循环输出结果
      $res[] = $rst -> fields;  
      $rst -> movenext();         //指针下移
    }

    $rst -> close();
    $conn -> close();
    return $res;
  }


}