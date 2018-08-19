<?php
	require_once(__DIR__.'/../Model/User.php');
	require_once(__DIR__.'/../Model/SystemManager.php');
	class SystemSettingController{
		private $systemManager;
		
		public function __construct(){
			$this->systemManager=new SystemManager();
		}		
		/**
		 * 加载系统设置信息
		 */
		public function loadSystemSettingInfo(){
			$systemSetting=$this->systemManager->loadSystemSettingInfo();
			$resultArr=array("systemSetting"=>$systemSetting);
			return json_encode($resultArr);
		}
		/**
		 * 修改系统设置信息
		 */
		public function changeSystemSettingInfo(){
			$maxQuestion=$_REQUEST['maxQuestion']??20;
			$maxTopic=$_REQUEST['maxTopic']??20;
			$maxArticle=$_REQUEST['maxArticle']??20;
			$maxComment=$_REQUEST['maxComment']??100;
			$maxFindPassword=$_REQUEST['maxFindPassword']??5;
			$maxEmailCount=$_REQUEST['maxEmailCount']??100;
			$changeRow=$this->systemManager->changeSystemSettingInfo($maxQuestion,$maxTopic,$maxArticle,
					$maxComment,$maxFindPassword,$maxEmailCount);
			$resultArr=array("changeRow"=>$changeRow);
			return json_encode($resultArr);
		}

		
		/**
		 * 下面的函数选择用户的请求动作，并给出相应的相应
		 */
		function selectAction(){
			//判断有没有请求动作，因为有php页面直接调用
			if(isset($_REQUEST['action'])){
				//用户需要登录系统，并且有权限才能执行相应的action
				if($this->systemManager->isUserLogon()){
					//可以执行的action
					if($this->systemManager->hasAuthority(CommenUser)){
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="loadSystemSettingInfo"){
							return $this->loadSystemSettingInfo();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="changeSystemSettingInfo"){
							return $this->changeSystemSettingInfo();
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
	
	$systemSettingController=new SystemSettingController();
	echo $systemSettingController->selectAction();
?>