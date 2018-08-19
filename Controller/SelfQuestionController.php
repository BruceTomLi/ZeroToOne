<?php
	require_once(__DIR__.'/../Model/User.php');
	require_once(__DIR__.'/../Model/QuestionManager.php');
	class SelfQuestionController{
		private $user;
		private $questionManager;
		
		public function __construct(){
			$this->user=new User();
			$this->questionManager=new QuestionManager();
		}
				
		
		public function createNewQuestion(){
			$token=$_REQUEST['token']??"";
			if($token==$_SESSION['token']){
				$questionType=$_REQUEST['questionType']??"";
				$questionContent=substr(trim($_REQUEST['questionContent']??""),0,250);
				$questionDescription=$_REQUEST['questionDescription'];
				$size=strlen($questionDescription);
				if($size<2000){
					$count=$this->user->createNewQuestion($questionType, $questionContent, $questionDescription);
					if(is_numeric($count)){
						$count=array("count"=>$count);
						return json_encode($count);
					}			
					return urlencode($count);
				}else{
					return json_encode(urlencode("问题内容长度超过上限（最多1K字）"));
				}
			}else{
				return json_encode(urlencode("该请求被认为是骇客CSRF攻击"));
			}
			
		}
		
		public function getSelfQuestionList(){
			$page=$_REQUEST['page'];
			$count=$this->user->getSelfQuestionCount();
			$questions=$this->user->getSelfQuestionList($page);
			$resultArr=array("questions"=>$questions,"count"=>$count);
			return json_encode($resultArr);
		}
		
		
		public function searchQuestionListByContentOrDescription(){
			$keyword=$_REQUEST['keyword'];
			$page=$_REQUEST['page'];
			$count=$this->user->getQuestionListByContentOrDescriptionCount($keyword);
			$questions=$this->user->getQuestionListByContentOrDescription($keyword,$page);
			$resultArr=array("questions"=>$questions,"count"=>$count);
			return json_encode($resultArr);
			return $questionList;
		}
		
		public function disableSelfQuestion(){
			$questionId=$_REQUEST['questionId'];
			$affectRow=$this->user->disableSelfQuestion($questionId);
			$resultArr=array("affectRow"=>$affectRow);
			$result=json_encode($resultArr);
			return $result;
		}
		
		public function enableSelfQuestion(){
			$questionId=$_REQUEST['questionId'];
			$affectRow=$this->user->enableSelfQuestion($questionId);
			$resultArr=array("affectRow"=>$affectRow);
			$result=json_encode($resultArr);
			return $result;
		}
		//删除自己的问题
		public function deleteSelfQuestion(){
			$questionId=$_REQUEST['questionId'];
			$affectRow=$this->user->deleteSelfQuestion($questionId);
			$resultArr=array("deleteRow"=>$affectRow);
			$result=json_encode($resultArr);
			return $result;
		}
		
		
		/**
		 * 加载所有问题类型
		 */
		public function loadQuestionTypes(){
			$resultArr=array("types"=>$this->user->loadQuestionTypes());
			return json_encode($resultArr);
		}
		
		public function selectAction(){
			//判断有没有请求动作，因为有php页面直接调用
			if(isset($_REQUEST['action'])){
				//用户需要登录系统，并且有权限才能执行相应的action
				if($this->user->isUserLogon()){
					//可以执行的action
					if($this->user->hasAuthority(CommenUser)){
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="createNewQuestion"){
							return $this->createNewQuestion();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="getSelfQuestionList"){
							return $this->getSelfQuestionList();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="searchQuestionListByContentOrDescription"){
							return $this->searchQuestionListByContentOrDescription();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="disableSelfQuestion"){
							return $this->disableSelfQuestion();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="enableSelfQuestion"){
							return $this->enableSelfQuestion();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="deleteSelfQuestion"){
							return $this->deleteSelfQuestion();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="loadQuestionTypes"){
							return $this->loadQuestionTypes();
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
	
	$SelfQuestionController=new SelfQuestionController();
	echo $SelfQuestionController->selectAction();
	//echo $SelfQuestionController->replyComment();
?>