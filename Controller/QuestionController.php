<?php
	require_once(__DIR__.'/../Model/User.php');
	require_once(__DIR__.'/../Model/QuestionManager.php');
	class QuestionController{
		private $user;
		private $questionManager;
		
		public function __construct(){
			$this->user=new User();
			$this->questionManager=new QuestionManager();
		}
		
		public function isUserLogon(){
			$isUserLogon=$this->user->isUserLogon()?1:0;
			$resultArr=array("isUserLogon"=>$isUserLogon);
			return json_encode($resultArr);
		}
		
		public function userLogonInfo(){
			return $this->user->getLogonUsername();
		}
		
		
		public function getSelfQuestionDetails(){
			$questionId=$_REQUEST['questionId']??"";
			$questionDetails=json_encode($this->user->getQuestionDetailsByQuestionId($questionId));
			return $questionDetails;
		}
		
		public function getAllQuestion(){
			$page=$_REQUEST['page']??1;
			$count=$this->questionManager->getAllQuestionCount();
			$questions=$this->questionManager->getAllQuestionList($page);
			$resultArr=array("count"=>$count,"questions"=>$questions);
			return json_encode($resultArr);
		}
		
		/**
		 * 获取最热门的十个问题
		 */
		public function getTenHotQuestions(){
			$questions=$this->user->getTenHotQuestions();
			$resultArr=array("questions"=>$questions);
			return json_encode($resultArr);
		}
		
		/**
		 * 获取最热门的十个话题
		 */
		public function getTenHotTopics(){
			$topics=$this->user->getTenHotTopics();
			$resultArr=array("topics"=>$topics);
			return json_encode($resultArr);
		}
		/**
		 * 获取最活跃的人
		 */
		public function getTenHotUsers(){
			$users=$this->user->getTenHotUsers();
			$resultArr=array("users"=>$users);
			return json_encode($resultArr);
		}
		
		/**
		 * 获取今日话题，仅取十条
		 */
		public function getTodayTopics(){
			$topics=$this->user->getTodayTopics();
			$resultArr=array("topics"=>$topics);
			return json_encode($resultArr);
		}
		
		/**
		 * 获取十个和登陆者相关类型的问题
		 */
		public function recommendQuestionsByJob(){
			$questions=$this->user->recommendQuestionsByJob();
			$resultArr=array("questions"=>$questions);
			return json_encode($resultArr);
		}
		
		/**
		 * 获取等待用户回复的问题（没有评论的问题），也只取十个问题
		 */
		public function getWaitReplyQuestions(){
			$questions=$this->user->getWaitReplyQuestions();
			$resultArr=array("questions"=>$questions);
			return json_encode($resultArr);
		}

		/**
		 * 获取查询的人、问题或话题
		 */
		public function queryUserOrQuestionOrTopic(){
			$keyword=$_REQUEST['keyword']??"";
			//不允许未登录用户查询
			if($this->user->isUserLogon()){
				if(!$keyword==""){
					$result=$this->user->queryUserOrQuestionOrTopic($keyword);
					$result=array("queryResult"=>$result);
					return json_encode($result);
				}else{
					$result=array("queryResult"=>"1");
					return json_encode($result);
				}
			}
			else{
				$result=array("queryResult"=>"2");
				return json_encode($result);
			}
		}
		
		/**
		 * 下面的方法和getSelfQuestionDetails()一样用来加载一个信息，
		 * 但是调用的是QuestionManager的方法而不是User的方法，不需要用户登录
		 */
		public function getQuestionDetails(){
			$questionId=$_REQUEST['questionId']??"";
			//需要获取问题详情，以及问题相关的评论
			$questionDetails=$this->questionManager->getQuestionDetails($questionId);
			$questionComments=$this->user->getCommentsForQuestion($questionId);
			//获取用户是否关注了该问题
			$hasUserFollowedQuestion=$this->user->hasUserFollowed($questionId);
			$commentCount=count($questionComments);
			$resultArr=array("questionDetails"=>$questionDetails,"questionComments"=>$questionComments,
				"hasUserFollowedQuestion"=>$hasUserFollowedQuestion,"commentCount"=>$commentCount);
			$result=json_encode($resultArr);
			return $result;
		}
		
		/**
		 * 下面的方法用来让用户提交一个问题
		 */
		public function commentQuestion(){
			$questionId=$_REQUEST['questionId'];
			$content=$_REQUEST['content'];
			$resultArr=$this->user->commentQuestion($questionId, $content);
			// if(!is_array($resultArr)){
				// $resultArr=array("errorInfo"=>$resultArr);
			// };			
			return json_encode($resultArr);
		}
		
		/**
		 * 下面删除一条评论信息
		 */
		public function disableCommentForQuestion(){
			$commentId=$_REQUEST['commentId'];
			$resultArr=array("affectRow"=>$this->user->disableCommentForQuestion($commentId));
			$result=json_encode($resultArr);
			return $result;
		}
		
		/**
		 * 获取评论的回复信息
		 */
		public function getReplysForComment(){
			$commentId=$_REQUEST['commentId'];
			$logonUser=$this->user->getLogonUsername();
			$replys=$this->user->getReplysForComment($commentId);
			if(is_array($replys)){
				$result=array("logonUser"=>$logonUser,"replys"=>$replys);
				return json_encode($result);
			}
			return urlencode($replys);
		}
		
		/**
		 * 回复一条评论
		 */
		public function replyComment(){			
			$commentId=$_REQUEST['commentId']??"";
			$fatherReplyId=$_REQUEST['fatherReplyId']??"";
			$content=$_REQUEST['content']??"";
			//由于需要在回复评论之后加载出评论者和评论的内容，所以在下面获取评论者信息
			$result=$this->user->createReplyForComment($fatherReplyId,$commentId, $content);
			return json_encode($result);
		}
		/**
		 * 回复一条回复
		 */
		public function replyReply(){
			$commentId=$_REQUEST['commentId'];
			$fatherReplyId=$_REQUEST['fatherReplyId'];
			$content=$_REQUEST['content'];
			//由于需要在回复评论之后加载出评论者和评论的内容，所以在下面获取评论者信息
			$result=$this->user->createReplyForReply($fatherReplyId,$commentId, $content);
			return json_encode($result);
		}
		
		/**
		 * 删除一条对评论的回复
		 */
		public function disableReplyForComment(){
			$replyId=$_REQUEST['replyId'];
			$result=array("disableRow"=>$this->user->disableReplyForComment($replyId));
			$result=json_encode($result);
			return $result;
		}
		
		/**
		 * 删除一条对回复的回复
		 */
		public function disableReplyForReply(){
			$replyId=$_REQUEST['replyId'];
			$result=array("disableRow"=>$this->user->disableReplyForReply($replyId));
			$result=json_encode($result);
			return $result;
		}
		
		/**
		 * 用户关注一个问题
		 */
		public function addFollow(){
			$starId=$_REQUEST['starId'];
			$followType=$_REQUEST['followType']??"unknown";
			$affectRow=$this->user->addFollow($starId,$followType);
			$resultArr=array("affectRow"=>$affectRow);
			$result=json_encode($resultArr);
			return $result;
		}
		
		/**
		 * 用户取消关注一个问题
		 */
		public function deleteFollow(){
			$starId=$_REQUEST['starId'];
			$affectRow=$this->user->deleteFollow($starId);
			$resultArr=array("affectRow"=>$affectRow);
			$result=json_encode($resultArr);
			return $result;
		}		
		
		public function selectAction(){
			//判断有没有请求动作，因为有php页面直接调用
			if(isset($_REQUEST['action'])){
				//不用登录就可以执行的动作
				if(isset($_REQUEST['action']) && $_REQUEST['action']=="getAllQuestion"){
					return $this->getAllQuestion();
				}
				if(isset($_REQUEST['action']) && $_REQUEST['action']=="getQuestionDetails"){
					return $this->getQuestionDetails();
				}
				if(isset($_REQUEST['action']) && $_REQUEST['action']=="getTenHotQuestions"){
					return $this->getTenHotQuestions();
				}
				if(isset($_REQUEST['action']) && $_REQUEST['action']=="recommendQuestionsByJob"){
					return $this->recommendQuestionsByJob();
				}
				if(isset($_REQUEST['action']) && $_REQUEST['action']=="getWaitReplyQuestions"){
					return $this->getWaitReplyQuestions();
				}
				if(isset($_REQUEST['action']) && $_REQUEST['action']=="getTenHotTopics"){
					return $this->getTenHotTopics();
				}
				if(isset($_REQUEST['action']) && $_REQUEST['action']=="getTenHotUsers"){
					return $this->getTenHotUsers();
				}
				if(isset($_REQUEST['action']) && $_REQUEST['action']=="getTodayTopics"){
					return $this->getTodayTopics();
				}
				
				//用户需要登录系统，并且有权限才能执行相应的action
				if($this->questionManager->isUserLogon()){
					//有普通用户权限可以执行的action
					if($this->questionManager->hasAuthority(CommenUser)){
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="userLogonInfo"){
							return $this->userLogonInfo();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="getSelfQuestionDetails"){
							return $this->getSelfQuestionDetails();
						}
						
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="commentQuestion"){
							return $this->commentQuestion();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="disableCommentForQuestion"){
							return $this->disableCommentForQuestion();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="getReplysForComment"){
							return $this->getReplysForComment();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="replyComment"){
							return $this->replyComment();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="replyReply"){
							return $this->replyReply();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="disableReplyForComment"){
							return $this->disableReplyForComment();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="disableReplyForReply"){
							return $this->disableReplyForReply();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="addFollow"){
							return $this->addFollow();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="deleteFollow"){
							return $this->deleteFollow();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="isUserLogon"){
							return $this->isUserLogon();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="queryUserOrQuestionOrTopic"){
							return $this->queryUserOrQuestionOrTopic();
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
	
	$questionController=new QuestionController();
	echo $questionController->selectAction();
	//echo $questionController->replyComment();
?>