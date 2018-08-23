<?php
	require_once(__DIR__.'/../Model/User.php');
	require_once(__DIR__.'/../Model/Operator.php');
	require_once("BaseController.php");
	class NoticeManageController extends BaseController{
		private $operator;
		
		public function __construct(){
			$this->operator=new Operator();
		}	
		
		/**
		 * 加载网站注册的男女人数信息
		 */
		public function loadManWomanCount(){
			$manWomanCount=$this->operator->loadManWomanCount();
			$result=array("manWomanCount"=>$manWomanCount);
			return $this->returnRusult($result);
		}
		
		/**
		 * 加载网站不同种类的问题的数量
		 */
		public function loadKindsOfQuestionCount(){
			$questionTypeCount=$this->operator->loadKindsOfQuestionCount();
			$result=array("questionTypeCount"=>$questionTypeCount);
			return $this->returnRusult($result);
		}
		
		/**
		 * 加载网站不同种类的话题的数量
		 */
		public function loadKindsOfTopicCount(){
			$topicTypeCount=$this->operator->loadKindsOfTopicCount();
			$result=array("topicTypeCount"=>$topicTypeCount);
			return $this->returnRusult($result);
		}
		
		/**
		 * 加载网站不同种类的工作的人员的数量
		 */
		public function loadKindsOfJobUserCount(){
			$jobUserCount=$this->operator->loadKindsOfJobUserCount();
			$result=array("jobUserCount"=>$jobUserCount);
			return $this->returnRusult($result);
		}
		
		/**
		 * 加载网站中某个人的六个数据（问问题数，创建话题数，回答问题数，回答话题数，粉丝数量，关注的人的数量）
		 */
		public function loadKindsUserInfoCount(){
			$userInfoCount=$this->operator->loadKindsUserInfoCount();
			$result=array("userInfoCount"=>$userInfoCount);
			return $this->returnRusult($result);
		}
		
		/**
		 * 加载网站中的个人信息
		 */
		public function searchVisitInfo(){
			$keyword=$_REQUEST['keyword']??"";
			$page=$_REQUEST['page']??1;
			$count=$this->operator->searchVisitInfoCount($keyword);
			$visitInfoList=$this->operator->searchVisitInfo($keyword,$page);
			$visitInfoList=array("visitInfos"=>$visitInfoList,"count"=>$count);
			return $this->returnRusult($visitInfoList);
		}
		
		/**
		 * 选择不同的动作
		 */
		public function selectAction(){
			//判断有没有请求动作，因为有php页面直接调用
			if(isset($_REQUEST['action'])){
				//用户需要登录系统，并且有权限才能执行相应的action
				if($this->operator->isUserLogon()){
					//下面这个action只要是登录的用户就可以执行（获取用户个性图谱）
					if(isset($_REQUEST['action']) && $_REQUEST['action']=="loadKindsUserInfoCount"){
						return $this->loadKindsUserInfoCount();
					}
					//可以执行的action
					if($this->operator->hasAuthority(Operate)){
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="loadManWomanCount"){
							return $this->loadManWomanCount();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="loadKindsOfQuestionCount"){
							return $this->loadKindsOfQuestionCount();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="loadKindsOfTopicCount"){
							return $this->loadKindsOfTopicCount();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="loadKindsOfJobUserCount"){
							return $this->loadKindsOfJobUserCount();
						}	
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="searchVisitInfo"){
							return $this->searchVisitInfo();
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
	
	$noticeManageController=new NoticeManageController();
	echo $noticeManageController->selectAction();
?>