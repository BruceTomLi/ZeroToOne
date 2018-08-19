<?php
	require_once(__DIR__.'/../Model/QuestionManager.php');
	class QuestionManageController{
		private $questionManager;
		
		public function __construct(){
			$this->questionManager=new QuestionManager();
		}		
		
		/**
		 * 为管理员加载所有问题
		 */
		public function getAllQuestionListForManager(){
			$questions=$this->questionManager->getAllQuestionListForManager();
			$resultArr=array("questions"=>$questions);
			return json_encode($resultArr);
		}
		
		/**
		 * 为管理员检索问题
		 */
		public function queryQuestionsByKeyword(){
			$keyword=$_REQUEST['keyword'];
			$page=$_REQUEST['page'];
			$count=$this->questionManager->queryQuestionsByKeywordCount($keyword);
			$questions=$this->questionManager->queryQuestionsByKeyword($keyword,$page);			
			$resultArr=array("questions"=>$questions,"count"=>$count);
			return json_encode($resultArr);
		}
		
		/**
		 * 不公开问题
		 */
		public function disableQuestion(){
			$questionId=$_REQUEST['questionId'];
			$result=$this->questionManager->disableSelfQuestion($questionId,true);
			$resultArr=array("affectRow"=>$result);
			return json_encode($resultArr);
		}
		
		/**
		 * 公开问题
		 */
		public function enableQuestion(){
			$questionId=$_REQUEST['questionId'];
			$result=$this->questionManager->enableSelfQuestion($questionId,true);
			$resultArr=array("affectRow"=>$result);
			return json_encode($resultArr);
		}
		
		/**
		 * 删除问题
		 */
		public function deleteQuestion(){
			$questionId=$_REQUEST['questionId'];
			$result=$this->questionManager->deleteSelfQuestion($questionId,true);
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
				if($this->questionManager->isUserLogon()){
					//可以执行的action
					if($this->questionManager->hasAuthority(QuestionManage)){
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="getAllQuestionListForManager"){
							return $this->getAllQuestionListForManager();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="queryQuestionsByKeyword"){
							return $this->queryQuestionsByKeyword();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="disableQuestion"){
							return $this->disableQuestion();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="enableQuestion"){
							return $this->enableQuestion();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="deleteQuestion"){
							return $this->deleteQuestion();
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
	
	$questionManageController=new QuestionManageController();
	echo $questionManageController->selectAction();
?>