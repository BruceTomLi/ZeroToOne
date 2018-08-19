<?php
	require_once(__DIR__."/../classes/SessionDBC.php");
	require_once(__DIR__."/../Model/User.php");
	
	class LoginController{
		private $user;
		
		public function __construct(){
			$this->user=new User();
		}
		
		public function login(){
			$password=$_POST['password']??null;
			$emailOrUsername=$_POST['emailOrUsername']??null;
			$result=$this->user->login($password, $emailOrUsername);
			$visitUrl=$_SESSION['visitUrl']??"forum/question.php";
			if($result=="success"){				
				$result=array("isSuccess"=>$result,"visitUrl"=>$visitUrl);
				$result=json_encode($result);
				return $result;//登录成功的情况
			}		
			else{
				return urlencode($result);//登录失败的情况
			}
		}
		
		public function findPassword(){
			$email=$_REQUEST['email']??"";
			$count=$this->user->findPassword($email);
			if(is_numeric($count)){
				$resultArr=array("count"=>$count);
				return json_encode($resultArr);
			}else{
				return urlencode($count);
			}			
		}
		
		public function selectAction(){
			if(isset($_REQUEST['action']) && $_REQUEST['action']=="login"){
				return $this->login();
			}
			if(isset($_REQUEST['action']) && $_REQUEST['action']=="findPassword"){
				return $this->findPassword();
			}
		}
	}
	
	$loginController=new LoginController();
	//因为页面脚本执行出错的时候也会导致返回结果不为""，导致登录成功，所以修改上面的代码，通过存取json来报告登录状态
	echo $loginController->selectAction();
?>