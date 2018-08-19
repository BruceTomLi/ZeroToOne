<?php
	require_once(__DIR__.'/../Model/User.php');
	require_once(__DIR__.'/../Model/Operator.php');
	class NoticeManageController{
		private $operator;
		
		public function __construct(){
			$this->operator=new Operator();
		}	
		
		/**
		 * 创建公告信息
		 */
		public function createNewNotice(){
			$title=$_REQUEST['title'];
			$content=$_REQUEST['content']??"";
			
			$insertCount=$this->operator->createNewNotice($title,$content);
			$resultArr=array("insertCount"=>$insertCount);
			$result=json_encode($resultArr);
			return $result;
		}
		
		/**
		 * 加载公告信息
		 */
		public function loadNotices(){
			$notices=$this->operator->loadNotices();
			$resultArr=array("notices"=>$notices);
			$result=json_encode($resultArr);
			return $result;
		}
		
		/**
		 * 删除公告信息
		 */
		public function deleteNotice(){
			$noticeId=$_REQUEST['noticeId']??"";
			$deleteRow=$this->operator->deleteNotice($noticeId);
			$resultArr=array("deleteRow"=>$deleteRow);
			$result=json_encode($resultArr);
			return $result;
		}

		/**
		 * 加载公告信息
		 */
		public function loadNoticeDetails(){
			$noticeId=$_REQUEST['noticeId']??"";
			$notice=$this->operator->loadNoticeDetails($noticeId);
			$resultArr=array("notice"=>$notice);
			$result=json_encode($resultArr);
			return $result;
		}
		
		public function selectAction(){
			//判断有没有请求动作，因为有php页面直接调用
			if(isset($_REQUEST['action'])){
				//用户需要登录系统，并且有权限才能执行相应的action
				if($this->operator->isUserLogon()){
					//可以执行的action
					if($this->operator->hasAuthority(NoticeManage)){
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="createNewNotice"){
							return $this->createNewNotice();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="loadNotices"){
							return $this->loadNotices();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="deleteNotice"){
							return $this->deleteNotice();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="loadNoticeDetails"){
							return $this->loadNoticeDetails();
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