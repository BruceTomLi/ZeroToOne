<?php
	require_once(__DIR__.'/../Model/User.php');
	require_once(__DIR__.'/../Model/TopicManager.php');
	class TopicController{
		private $user;
		private $topicManager;
		
		public function __construct(){
			$this->user=new User();
			$this->topicManager=new TopicManager();
		}
		
		public function isUserLogon(){
			$isUserLogon=$this->user->isUserLogon()?1:0;
			$resultArr=array("isUserLogon"=>$isUserLogon);
			return json_encode($resultArr);
		}
		
		public function userLogonInfo(){
			return $this->user->getLogonUsername();
		}
		
		
		public function getSelfTopicDetails(){
			$topicId=$_REQUEST['topicId']??"";
			$topicDetails=json_encode($this->user->getTopicDetailsByTopicId($topicId));
			return $topicDetails;
		}
		
		public function getAllTopic(){
			$page=$_REQUEST['page']??1;
			$count=$this->topicManager->getAllTopicCount();
			$topics=$this->topicManager->getAllTopicList($page);
			$resultArr=array("topics"=>$topics,"count"=>$count);
			$result=json_encode($resultArr);
			return $result;
		}
		/**
		 * 下面的方法和getSelfTopicDetails()一样用来加载一个信息，
		 * 但是调用的是TopicManager的方法而不是User的方法，不需要用户登录
		 */
		public function getTopicDetails(){
			$topicId=$_REQUEST['topicId']??"";
			//需要获取话题详情，以及话题相关的评论
			$topicDetails=$this->topicManager->getTopicDetails($topicId);
			$topicComments=$this->user->getCommentsForTopic($topicId);
			//获取用户是否关注了该话题
			$hasUserFollowedTopic=$this->user->hasUserFollowed($topicId);
			$commentCount=count($topicComments);
			$resultArr=array("topicDetails"=>$topicDetails,"topicComments"=>$topicComments,
				"hasUserFollowedTopic"=>$hasUserFollowedTopic,"commentCount"=>$commentCount);
			$result=json_encode($resultArr);
			return $result;
		}
		
		/**
		 * 下面的方法用来让用户提交一个话题
		 */
		public function commentTopic(){
			$topicId=$_REQUEST['topicId'];
			$content=$_REQUEST['content'];
			$result=$this->user->commentTopic($topicId, $content);
			if(is_array($result)){				
				return json_encode($result);
			}else{
				return urlencode($result);
			}
		}
		
		/**
		 * 下面删除一条评论信息
		 */
		public function disableCommentForTopic(){
			$commentId=$_REQUEST['commentId'];
			$resultArr=array("affectRow"=>$this->user->disableCommentForTopic($commentId));
			$result=json_encode($resultArr);
			return $result;
		}
		
		/**
		 * 获取评论的回复信息
		 */
		public function getReplysForComment(){
			$commentId=$_REQUEST['commentId'];
			$logonUser="";
			if($this->user->isUserLogon()){
				$logonUser=$this->user->getLogonUsername();
			}
			$resultArr=array("logonUser"=>$logonUser,"replys"=>$this->user->getReplysForComment($commentId));
			if(is_array($result)){
				return json_encode($result);
			}
			return urlencode($result);
		}
		
		/**
		 * 回复一条评论
		 */
		public function replyComment(){			
			$commentId=$_REQUEST['commentId']??"";
			$fatherReplyId=$_REQUEST['fatherReplyId']??"";
			$content=$_REQUEST['content']??"";
			$result="";
			$result=$this->user->createReplyForComment($fatherReplyId,$commentId, $content);
			if(is_array($result)){
				return json_encode($result);
			}
			return urlencode($result);
		}
		/**
		 * 回复一条回复
		 */
		public function replyReply(){
			$commentId=$_REQUEST['commentId'];
			$fatherReplyId=$_REQUEST['fatherReplyId'];
			$content=$_REQUEST['content'];
			$result="";
			//由于需要在回复评论之后加载出评论者和评论的内容，所以在下面获取评论者信息
			$result=$this->user->createReplyForReply($fatherReplyId,$commentId, $content);
			if(is_array($result)){
				return json_encode($result);
			}else{
				return urlencode($result);
			}		
			
		}
		
		/**
		 * 删除一条对评论的回复
		 */
		public function disableReplyForComment(){
			$replyId=$_REQUEST['replyId'];
			$result=array("disableRow"=>$this->user->disableReplyForComment($replyId));
			if(is_array($result)){
				return json_encode($result);
			}
			return urlencode($result);
		}
		
		/**
		 * 删除一条对回复的回复
		 */
		public function disableReplyForReply(){
			$replyId=$_REQUEST['replyId'];
			$result=array("disableRow"=>$this->user->disableReplyForReply($replyId));
			if(is_array($result)){
				return json_encode($result);
			}
			return urlencode($result);
		}
		
		/**
		 * 用户关注一个话题
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
		 * 用户取消关注一个话题
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
				//不用登录也可以执行的动作
				if(isset($_REQUEST['action']) && $_REQUEST['action']=="getAllTopic"){
					return $this->getAllTopic();
				}
				if(isset($_REQUEST['action']) && $_REQUEST['action']=="getTopicDetails"){
					return $this->getTopicDetails();
				}
				if(isset($_REQUEST['action']) && $_REQUEST['action']=="isUserLogon"){
					return $this->isUserLogon();
				}
				//用户需要登录系统，并且有权限才能执行相应的action
				if($this->topicManager->isUserLogon()){
					//可以执行的action
					if($this->topicManager->hasAuthority(CommenUser)){						
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="userLogonInfo"){
							return $this->userLogonInfo();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="getSelfTopicDetails"){
							return $this->getSelfTopicDetails();
						}
						
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="commentTopic"){
							return $this->commentTopic();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="disableCommentForTopic"){
							return $this->disableCommentForTopic();
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
	
	$topicController=new TopicController();
	echo $topicController->selectAction();
	//echo $topicController->replyComment();
?>