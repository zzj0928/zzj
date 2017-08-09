<?php
// no direct access
defined( 'EXEC' ) or die( 'Restricted access' );

class index extends Base{
	public function index(){
		echo 111;
		echo "<br/>";
		$this->debj(); 
	}

	
    
	public function debx(){ 
		$dkm   = 240; //贷款月数，20年就是240个月 
		$dkTotal = 430000; //贷款总额 
		$dknl  = 0.041; //贷款年利率 
		$emTotal = $dkTotal * $dknl / 12 * pow(1 + $dknl / 12, $dkm) / (pow(1 + $dknl / 12, $dkm) - 1); //每月还款金额 
		$lxTotal = 0; //总利息 
		for ($i = 0; $i < $dkm; $i++) { 
			$lx   = $dkTotal * $dknl / 12;  //每月还款利息 
			$em   = $emTotal - $lx; //每月还款本金 
			echo "第" . ($i + 1) . "期", " 本金:", $em, " 利息:" . $lx, " 总额:" . $emTotal, "<br />"; 
			$dkTotal = $dkTotal - $em; 
			$lxTotal = $lxTotal + $lx; 
		} 
		echo "总利息:" . $lxTotal; 
	} 


	public function debj(){ 
		$dkm   = 240; //贷款月数，20年就是240个月 
		$dkTotal = 430000; //贷款总额 
		$dknl  = 0.0441; //贷款年利率 

		$em   = $dkTotal / $dkm; //每个月还款本金 
		$lxTotal = 0; //总利息 
		for ($i = 0; $i < $dkm; $i++) { 
			$lx   = $dkTotal * $dknl / 12; //每月还款利息 
			echo "第" . ($i + 1) . "期", " 本金:", $em, " 利息:" . $lx, " 总额:" . ($em + $lx), "<br />"; 
			$dkTotal -= $em; 
			$lxTotal = $lxTotal + $lx; 
		} 
		echo "总利息:" . $lxTotal; 
	} 
}
/*
熟练使用PHP、JavaScript、Jquery、Html、Css 网站开发；
熟悉C、C++、Java 的语法和常用函数，
熟练使用linux的常用命令，能在Linux下编译安装PHP、MySql、Apache、Nginx、Memcached、redis等环境；
熟练使用 MySql 数据库；
熟练使用SVN等版本控制
用过ThinkPHP、Yii框架，基于Smarty模版开发过自己框架。
*/