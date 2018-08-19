<?php
	// session_start();
	require_once(__DIR__."/../classes/SessionDBC.php");
	require_once(__DIR__."/../Model/User.php");
	
	class LogoutController{
		private $user;
		
		public function __construct(){
			$this->user=new User();
		}
		
		public function logout(){
			$this->user->logout();
			if(!isset($_SESSION['username'])){
				return true;
			}
			else{
				return false;
			}
		}
	}
	
	$logoutController=new LogoutController();
	//因为页面脚本执行出错的时候也会导致返回结果不为""，导致登录成功，所以修改上面的代码，通过存取json来报告登录状态
	echo $logoutController->logout();
?>