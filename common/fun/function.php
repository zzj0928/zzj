<?php
/**
 * 自定义函数库
 * @author zzj 210180911
 *
 **/
// no direct access
defined( 'EXEC' ) or die( 'Restricted access' );
require_once( PATH_COMMON.DS.'fun'.DS.'my.php'); //
/**
 * 加载动态扩展文件
 * @return void
 */
function load_ext_file($path) {
    // 加载自定义外部文件
    if(C('LOAD_EXT_FILE')) {
        $files      =  explode(',',C('LOAD_EXT_FILE'));
        foreach ($files as $file){
            $file   = $path.DS.'fun'.DS.$file.'.php';
            if(is_file($file)) include $file;
        }
    }
    // 加载自定义的动态配置文件
    if(C('LOAD_EXT_CONFIG')) {
        $configs    =  C('LOAD_EXT_CONFIG');
        if(is_string($configs)) $configs =  explode(',',$configs);
        foreach ($configs as $key=>$config){
            $file   = $path.DS.'conf'.DS.$config.'.php';
            if(is_file($file)) {
                is_numeric($key)?C(include $file):C($key,include $file);
            }
        }
    }
}

/**
 * 获取和设置配置参数 支持批量定义
 * @param string|array $name 配置变量
 * @param mixed $value 配置值
 * @param mixed $default 默认值
 * @return mixed
 */
function C($name=null, $value=null,$default=null) {
    static $_config = array();
    // 无参数时获取所有
    if (empty($name)) {
        return $_config;
    }
    // 优先执行设置获取或赋值
    if (is_string($name)) {
        if (!strpos($name, '.')) {
            $name = strtoupper($name);
            if (is_null($value))
                return isset($_config[$name]) ? $_config[$name] : $default;
            $_config[$name] = $value;
            return null;
        }
        // 二维数组设置和获取支持
        $name = explode('.', $name);
        $name[0]   =  strtoupper($name[0]);
        if (is_null($value))
            return isset($_config[$name[0]][$name[1]]) ? $_config[$name[0]][$name[1]] : $default;
        $_config[$name[0]][$name[1]] = $value;
        return null;
    }
    // 批量设置
    if (is_array($name)){
        $_config = array_merge($_config, array_change_key_case($name,CASE_UPPER));
        return null;
    }
    return null; // 避免非法参数
}

/**
 * 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @return mixed
 */
function get_client_ip($type = 0) {
    $type       =  $type ? 1 : 0;
    static $ip  =   NULL;
    if ($ip !== NULL) return $ip[$type];
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos    =   array_search('unknown',$arr);
        if(false !== $pos) unset($arr[$pos]);
        $ip     =   trim($arr[0]);
    }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip     =   $_SERVER['HTTP_CLIENT_IP'];
    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip     =   $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u",ip2long($ip));
    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}

//输出json格式的数据
function echo_json( $array ){
    $string = '';

    if( version_compare( PHP_VERSION, '5.4.0', '<' ) ){
        $str = json_encode( $array );
        $str = preg_replace_callback( "#\\\u([0-9a-f]{4})#i", function( $matchs ){
                return iconv( 'UCS-2BE', 'UTF-8', pack( 'H4', $matchs[1] ) );
        }, $str );
        $string = $str;
    }else{
        $string = json_encode( $array, JSON_UNESCAPED_UNICODE );
    }

    echo $string;
    exit;
}

//不可逆加密函数
function encryption( $string ){
    return md5( sha1( $string ) );
}

//是否是微信环境
function is_weixin(){
    $result = false;
    if( stripos( $_SERVER['HTTP_USER_AGENT'], "MicroMessenger" ) !== false ){ $result = true; }
    return $result;
}

//获取应用浏览环境
function get_client_env(){
    $result = '';
    if( stripos( $_SERVER['HTTP_USER_AGENT'], "MicroMessenger" ) !== false ){
        $result = 'WEIXIN_APP';
    }else if( stripos( $_SERVER['HTTP_USER_AGENT'], "AlipayClient" ) !== false ){
        $result = 'ALIPAY_APP';
    }else if( stripos( $_SERVER['HTTP_USER_AGENT'], "DianlfMerClient" ) !== false ){
        $result = 'DLFMER_APP';
    }

    return $result;
}

//curl的get请求
function curl_get( $url ){
    $ch = curl_init();
    $header = "Accept-Charset: utf-8";
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)");
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $temp = curl_exec($ch);
    curl_close($ch);
    return $temp;
}

//curl 的post 请求
function curl_post( $p_url, $p_post ){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $p_url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.117 Safari/537.36');
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $p_post);// http_build_query()
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_TIMEOUT, 300);
    $output = curl_exec($ch);
    if($error=curl_error($ch)){  
        die('curl错误号:'.$error);  
    }
    curl_close($ch);
    return $output;
}

/**日志函数
 * 如果开启了SeasLog日志，则通过SeasLog写日志，如果没有开通，则以file_put_contents写
 * @param $log String | Array
 * @param $level String 
 *
 */
function write_log( $log='', $level='info') {
   
}



/**
*实现字符串编码格式实现转换 */
function auto_charset($fContents,$from='',$to=''){
    if(empty($from)) $from = C('TEMPLATE_CHARSET');
    if(empty($to)) $to = C('OUTPUT_CHARSET');
    $from = strtoupper($from)=='UTF8'? 'utf-8':$from;
    $to = strtoupper($to)=='UTF8'? 'utf-8':$to;
    if( strtoupper($from) === strtoupper($to) || empty($fContents) || (is_scalar($fContents) && !is_string($fContents)) ){
    //如果编码相同或者非字符串标量则不转换
        return $fContents;
    }
    if(is_string($fContents) ) {
        if(function_exists('mb_convert_encoding')){
            return mb_convert_encoding ($fContents, $to, $from);
        }elseif(function_exists('iconv')){
            return iconv($from,$to,$fContents);
        }else{
            halt(L('_NO_AUTO_CHARSET_'));
            return $fContents;
        }
    }
    elseif(is_array($fContents)){
        foreach ( $fContents as $key => $val ) {
            $_key = auto_charset($key,$from,$to);
            $fContents[$_key] = auto_charset($val,$from,$to);
            if($key != $_key ) {
                unset($fContents[$key]);
            }
        }
        return $fContents;
    }
    elseif(is_object($fContents)) {
        $vars = get_object_vars($fContents);
        foreach($vars as $key=>$val) {
            $fContents->$key = auto_charset($val,$from,$to);
        }
        return $fContents;
    }
    else{
        return $fContents;
    }
}

/**
* 截取中文字符串
*/
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true){
    $len = abslength($str);
    if(function_exists("mb_substr")){
      if($suffix && $len > $length)
        return mb_substr($str, $start, $length, $charset)."...";
      else
        return mb_substr($str, $start, $length, $charset);
    }elseif(function_exists('iconv_substr')) {
    if($suffix && $len > $length)
       return iconv_substr($str,$start,$length,$charset)."...";
    else
       return iconv_substr($str,$start,$length,$charset);
    }
    $re['utf-8'] = "/[x01-x7f]|[xc2-xdf][x80-xbf]|[xe0-xef][x80-xbf]{2}|[xf0-xff][x80-xbf]{3}/";
    $re['gb2312'] = "/[x01-x7f]|[xb0-xf7][xa0-xfe]/";
    $re['gbk'] = "/[x01-x7f]|[x81-xfe][x40-xfe]/";
    $re['big5'] = "/[x01-x7f]|[x81-xfe]([x40-x7e]|xa1-xfe])/";
    preg_match_all($re[$charset], $str, $match);
    $slice = join("",array_slice($match[0], $start, $length));
    if($suffix) return $slice.'...';
    return $slice;
}
/**
 * 计算中文字符串的长度
 */
function abslength($str,$charset='utf-8'){     
    $len=strlen($str);     
    $i=0; $j=0;    
    while($i<$len)     
    {     
        if(preg_match("/^[".chr(0xa1)."-".chr(0xf9)."]+$/",$str[$i]) && $charset== 'utf-8'){     
            $i+=3;  //注意TP中的编码都是utf-8，所以+3;如果是GBK改为+2  
        }elseif(strtoupper($charset) == 'GBK'){     
            $i+=2;     
        }else{
            $i+=1;
        }     
        $j++;  
    }  
    return $j;  
}
//手机格式验证
function _checkPhone($phone){
    return preg_match("/^13[0-9]{1}[0-9]{8}$|^14[0-9]{1}[0-9]{8}$|^15[0-9]{1}[0-9]{8}$|^16[0-9]{1}[0-9]{8}$|^17[0-9]{1}[0-9]{8}$|^18[0-9]{1}[0-9]{8}$/", $phone)?true:false;
}
//判断是否是否纯汉字
function utf8_str($str){  
    $mb = mb_strlen($str,'utf-8');  
    $st = strlen($str);  
    if($st == $mb)  
        return 1; //英文  
    if($st%$mb==0 && $st%3==0)  
        return 2; //汉字 
    return 3; //汉英组合  
} 

/**
 * 验证身份证号
 * @param $vStr
 * @return bool
 */
function is_credit_no( $vStr ){
    $vCity = array(
        '11','12','13','14','15','21','22',
        '23','31','32','33','34','35','36',
        '37','41','42','43','44','45','46',
        '50','51','52','53','54','61','62',
        '63','64','65','71','81','82','91'
    );
 
    if (!preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $vStr)) return false;
 
    if (!in_array(substr($vStr, 0, 2), $vCity)) return false;
 
    $vStr = preg_replace('/[xX]$/i', 'a', $vStr);
    $vLength = strlen($vStr);
 
    if ($vLength == 18){
        $vBirthday = substr($vStr, 6, 4) . '-' . substr($vStr, 10, 2) . '-' . substr($vStr, 12, 2);
    } else {
        $vBirthday = '19' . substr($vStr, 6, 2) . '-' . substr($vStr, 8, 2) . '-' . substr($vStr, 10, 2);
    }
 
    if (date('Y-m-d', strtotime($vBirthday)) != $vBirthday) return false;
    if ($vLength == 18){
        $vSum = 0;
 
        for ($i = 17 ; $i >= 0 ; $i--){
            $vSubStr = substr($vStr, 17 - $i, 1);
            $vSum += (pow(2, $i) % 11) * (($vSubStr == 'a') ? 10 : intval($vSubStr , 11));
        }
 
        if($vSum % 11 != 1) return false;
    }
 
    return true;
}


//生成手机验证码:数字
function make_verify_code( $length = 6 ){
    $verify_code = '';
    for( $i=0; $i< $length; $i++ ){
        $verify_code .= rand( 0, 9 );
    }
    return $verify_code;
}

/*
 * 对多位数组进行排序
 * @param $multi_array 数组
 * @param $sort_key需要传入的键名
 * @param $sort排序类型
 */
function multi_array_sort( $multi_array, $sort_key, $sort='ASC' ) {
    if( is_array( $multi_array ) ) {
        foreach( $multi_array as $row_array ) {
            if( is_array( $row_array ) ) {
                $key_array[] = $row_array[$sort_key];
            }else{
                return false;
            }
        }
    }else{
        return false;
    }

    $sort_method = $sort == 'ASC' ? SORT_ASC : SORT_DESC;
    array_multisort( $key_array, $sort_method, $multi_array );

    return $multi_array;
}

//从Url中提取一级域名
function get_top_domain( $url ){
    $result = false;
    $referer_path = parse_url( $url );
    if( $referer_path['host'] ){
        $explode_domain = explode( '.', $referer_path['host'] );
        $result = $explode_domain[count($explode_domain)-2] . '.' . $explode_domain[count($explode_domain)-1];
    }
            
    return $result;
}

//是否存在连续相同的字符检测密码强度：
function check_password_safety( $string, $check_type='int' ){
    $result['status'] = 1;
    $result['msg'] = 'ok';

    $string = trim( $string );
    if( $string ){
        if( in_array( $string, array('123456,123456789','123') ) ){
            $result['status'] = -1;
            $result['msg'] = '密码过于简单';
        }else if( preg_match( '/([0-9])\1{5,}/', $string, $res ) ){
            $result['status'] = -3;
            $result['msg'] = '密码中含有连续相同数字';
        }else if( preg_match( '/([a-zA-Z])\1{5,}/', $string, $res ) ){
            $result['status'] = -4;
            $result['msg'] = '密码中含有连续相同字母';
        }else if( $check_type=='int' && preg_match( '/[^\d]+/', $string, $res ) ){
            $result['status'] = -5;
            $result['msg'] = '密码中含有非数字的字符';
        }else if( $check_type=='char' && preg_match( '/[^a-zA-Z]+/', $string, $res ) ){
            $result['status'] = -6;
            $result['msg'] = '密码中含有非字母的字符';
        }
    }else{
        $result['status'] = 0;
        $result['msg'] = '密码为空';
    }

    return $result;
}

//对称加密函数：
function sym_encrypt($string = '', $skey = 'cxkey') {
    $strArr = str_split(base64_encode($string));
    $strCount = count($strArr);
    foreach (str_split($skey) as $key => $value){
        $key < $strCount && $strArr[$key].=$value;
    }        
    return str_replace(array('=', '+', '/'), array('O0_0O', '_000o', 'o00_'), join('', $strArr));
}

//对称解密函数
function sym_decrypt($string = '', $skey = 'cxkey') {
    $strArr = str_split(str_replace(array('O0_0O', '_000o', 'o00_'), array('=', '+', '/'), $string), 2);
    $strCount = count($strArr);
    foreach (str_split($skey) as $key => $value){
        $key <= $strCount  && isset($strArr[$key]) && $strArr[$key][1] === $value && $strArr[$key] = $strArr[$key][0];
     }
    return base64_decode(join('', $strArr));
}

//发送邮件
function send_mail( $subject, $message, $addressee_array, $addAttachment=array() ){
    
}

//递归创建目录
function createDir( $path ){
    if ( !file_exists( $path ) ){
        createDir( dirname( $path ) );
        mkdir( $path );
    }
}

//获取指定长度的随机字符串
function makeRandomStr( $length=2 ){
    $string = '';

    $pattern = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
    for( $i = 0; $i < $length; $i++ ) {
        $string .= $pattern{mt_rand ( 0, 61 )};
    }

    return $string;
}

/**
 * XML转数组
 * @param string $xmlstring XML字符串
 * @return array XML数组
 */
function simplest_xml_to_array($xmlstring) {
    return json_decode(json_encode((array) simplexml_load_string($xmlstring)), true);
}