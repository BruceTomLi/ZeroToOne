<?php
	require_once(__DIR__.'/../Model/User.php');
	require_once(__DIR__.'/../Model/QuestionManager.php');
	require_once(__DIR__.'/../Model/Operator.php');
	class PersonController{
		private $user;
		private $questionManager;
		private $operator;
		
		public function __construct(){
			$this->user=new User();
			$this->questionManager=new QuestionManager();
			$this->operator=new Operator();
		}		
		/**
		 * 判断用户是否登录
		 */
		public function isUserLogon(){
			$isUserLogon=$this->user->isUserLogon()?true:false;
			return $isUserLogon;
		}
		
		/**
		 * 通过userId获取用户基本信息
		 */
		public function getUserBaseInfoByUserId(){
			$userId=$_REQUEST['userId']??"";
			$personalInfo=$this->user->getUserBaseInfoByUserId($userId);
			return $personalInfo;
		}
		
		/**
		 * 加载网站中某个人的六个数据（问问题数，创建话题数，回答问题数，回答话题数，粉丝数量，关注的人的数量）
		 */
		public function loadKindsUserInfoCount(){
			$userId=$_REQUEST['userId']??"";
			$userCountInfo=$this->operator->loadKindsUserInfoCount($userId);
			$username=$this->operator->getUserNameByUserId($userId);
			$resultArr=array("userCountInfo"=>$userCountInfo,"username"=>$username);
			return json_encode($resultArr,true);
		}
		
		/**
		 * 判断当前登录用户是否已经关注了该用户
		 */
		public function hasUserFollowedUser(){
			$userId=$_REQUEST['userId']??"";
			$hasUserFollowed=$this->user->hasUserFollowed($userId);
			if($hasUserFollowed==1){
				return true;
			}
			else if($hasUserFollowed==0){
				return false;
			}
			else{
				return $hasUserFollowed;
			}			
		}
		
		/**
		 * 选择不同的动作
		 */
		public function selectAction(){
			//判断有没有请求动作，因为有php页面直接调用
			if(isset($_REQUEST['action'])){
				//因为person.php页要直接调用，所以即使用户未登录，也不返回未登录信息
				if($this->user->isUserLogon()){
					if(isset($_REQUEST['action']) && $_REQUEST['action']=="loadKindsUserInfoCount"){
						return $this->loadKindsUserInfoCount();
					}
				}
			}
		}
	}
	
	$personController=new PersonController();
	echo $personController->selectAction();
?>