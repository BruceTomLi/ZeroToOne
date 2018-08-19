<?php
	require_once(__DIR__.'/../Model/User.php');
	require_once(__DIR__.'/../Model/QuestionManager.php');
	class SelfFansController{
		private $user;
		private $questionManager;
		
		public function __construct(){
			$this->user=new User();
			$this->questionManager=new QuestionManager();
		}
		
		/**
		 * 加载用户的粉丝
		 */
		public function loadUserFans(){
			$fans=$this->user->loadUserFans();
			$resultArr=array("fans"=>$fans);
			return json_encode($resultArr);
		}
		
		public function selectAction(){
			//判断有没有请求动作，因为有php页面直接调用
			if(isset($_REQUEST['action'])){
				//用户需要登录系统，并且有权限才能执行相应的action
				if($this->user->isUserLogon()){
					//可以执行的action
					if($this->user->hasAuthority(CommenUser)){
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="loadUserFans"){
							return $this->loadUserFans();
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
	
	$selfFansController=new SelfFansController();
	echo $selfFansController->selectAction();
	//echo $questionController->replyComment();
?>