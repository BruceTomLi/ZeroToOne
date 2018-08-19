<?php
	require_once(__DIR__.'/../Model/TopicManager.php');
	class TopicManageController{
		private $topicManager;
		
		public function __construct(){
			$this->topicManager=new TopicManager();
		}		
		
		/**
		 * 为管理员加载所有话题
		 */
		public function getAllTopicListForManager(){
			$topics=$this->topicManager->getAllTopicListForManager();
			$resultArr=array("topics"=>$topics);
			return json_encode($resultArr);
		}
		
		/**
		 * 为管理员检索话题
		 */
		public function queryTopicsByKeyword(){
			$keyword=$_REQUEST['keyword'];
			$page=$_REQUEST['page'];
			$count=$this->topicManager->queryTopicsByKeywordCount($keyword);
			$topics=$this->topicManager->queryTopicsByKeyword($keyword,$page);			
			$resultArr=array("topics"=>$topics,"count"=>$count);
			return json_encode($resultArr);
		}
		
		/**
		 * 不公开话题
		 */
		public function disableTopic(){
			$topicId=$_REQUEST['topicId'];
			$result=$this->topicManager->disableSelfTopic($topicId,true);
			$resultArr=array("affectRow"=>$result);
			return json_encode($resultArr);
		}
		
		/**
		 * 公开话题
		 */
		public function enableTopic(){
			$topicId=$_REQUEST['topicId'];
			$result=$this->topicManager->enableSelfTopic($topicId,true);
			$resultArr=array("affectRow"=>$result);
			return json_encode($resultArr);
		}
		
		/**
		 * 删除话题
		 */
		public function deleteTopic(){
			$topicId=$_REQUEST['topicId'];
			$result=$this->topicManager->deleteSelfTopic($topicId,true);
			$resultArr=array("affectRow"=>$result);
			return json_encode($resultArr);
		}
		
		/**
		 * 选择要执行哪个动作
		 */
		public function selectAction(){
			//判断有没有请求动作，因为有php页面直接调用
			if(isset($_REQUEST['action'])){
				//用户需要登录系统，并且有权限才能执行相应的action
				if($this->topicManager->isUserLogon()){
					//可以执行的action
					if($this->topicManager->hasAuthority(TopicManage)){
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="getAllTopicListForManager"){
							return $this->getAllTopicListForManager();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="queryTopicsByKeyword"){
							return $this->queryTopicsByKeyword();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="disableTopic"){
							return $this->disableTopic();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="enableTopic"){
							return $this->enableTopic();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="deleteTopic"){
							return $this->deleteTopic();
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
	
	$topicManageController=new TopicManageController();
	echo $topicManageController->selectAction();
?>