<?php
	require_once(__DIR__.'/../Model/PowerManager.php');
	class PowerController{
		private $powerManager;
		
		public function __construct(){
			$this->powerManager=new PowerManager();
		}
		
		/**
		 * 加载权限信息
		 */
		public function loadAuthorityInfo(){
			$resultArr=$this->powerManager->loadAuthorityInfo();
			return json_encode($resultArr);
		}
		
		/**
		 * 修改权限信息
		 */
		public function changeAuthorityInfo(){
			$authorityName=$_REQUEST['authorityName'];
			$note=$_REQUEST['note'];
			$affectRow=$this->powerManager->changeAuthorityInfo($authorityName, $note);
			$result=array("affectRow"=>$affectRow);
			return json_encode($result);
		}
		
		/**
		 * 选择要执行哪个动作
		 */
		public function selectAction(){
			//判断有没有请求动作，因为有php页面直接调用
			if(isset($_REQUEST['action'])){
				//用户需要登录系统，并且有权限才能执行相应的action
				if($this->powerManager->isUserLogon()){
					//可以执行的action
					if($this->powerManager->hasAuthority(AuthorityManage)){
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="loadAuthorityInfo"){
							return $this->loadAuthorityInfo();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="changeAuthorityInfo"){
							return $this->changeAuthorityInfo();
						}
					}
					//否则返回无权限信息
					else{
						return NoAuthority;
					}
				}
				else{
					return NotLogon;
				}
			}
		}
	}

	$powerController=new PowerController();
	echo $powerController->selectAction();
?>