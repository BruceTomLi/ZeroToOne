<?php
	require_once(__DIR__."/../Model/Operator.php");
	require_once("TestData.php");
	use PHPUnit\Framework\TestCase;
	class AuthorTest extends TestCase{
		private $operator;
		//执行每个测试前先登录系统
		function setUp(){
			$this->operator=new Operator();
			$username=UserName;
			$password=Password;
			$this->operator->login($password, $username);
		}
		//执行每个测试后退出系统
		function tearDown(){
			$this->operator->logout();
		}
		/**
		 * 测试创建新的公告
		 */
		function testCreateNewNotice(){
			$title="测试创建公告1415";
			$content="测试创建的公告的公告内容1415";
			$insertRow=$this->operator->createNewNotice($title,$content);
			if($this->operator->isNoticeRepeat($title)){
				$this->assertTrue($insertRow=="公告标题重复");
			}
			else{
				$this->assertTrue(is_numeric($insertRow) && $insertRow>0);
			}
		}
		
		/**
		 * 测试删除公告
		 */
		function testDeleteNotice(){
			$noticeId="5b6987533bf553.01646383";
			$deleteRow=$this->operator->deleteNotice($noticeId);
			$this->assertTrue(is_numeric($deleteRow) && $deleteRow>=0);
		}
		
		/**
		 * 测试加载所有的公告信息
		 */
		function testLoadNotices(){
			$notices=$this->operator->loadNotices();
			$this->assertTrue(is_array($notices) && count($notices)>0);
		}
		
		/**
		 * 测试加载20条公告信息
		 */
		function testLoadTwentyNotices(){
			$notices=$this->operator->loadTwentyNotices();
			$this->assertTrue(is_array($notices) && count($notices)>0);
		}
		
		/**
		 * 测试加载公告详情
		 */
		function testLoadNoticeDetails(){
			$noticeId=NoticeId;
			$notice=$this->operator->loadNoticeDetails($noticeId);
			$this->assertTrue(is_array($notice) && count($notice)>0);
		}
		
		/**
		 * 测试获取系统注册人数的男女人数信息
		 */
		function testLoadManWomanCount(){
			$manWomanCount=$this->operator->loadManWomanCount();
			$this->assertTrue(is_array($manWomanCount) && count($manWomanCount)>0);
		}
		
		/**
		 * 测试获取系统不同种类的问题的数量
		 */
		function testLoadKindsOfQuestionCount(){
			$questionTypeCount=$this->operator->loadKindsOfQuestionCount();
			$this->assertTrue(is_array($questionTypeCount) && count($questionTypeCount)>0);
		}
		
		/**
		 * 测试获取系统不同种类的话题的数量
		 */
		function testLoadKindsOfTopicCount(){
			$topicTypeCount=$this->operator->loadKindsOfTopicCount();
			$this->assertTrue(is_array($topicTypeCount) && count($topicTypeCount)>0);
		}
		
		/**
		 * 测试获取系统不同种类的工作的人员的数量
		 */
		function testLoadKindsOfJobUserCount(){
			$jobUserCount=$this->operator->loadKindsOfJobUserCount();
			$this->assertTrue(is_array($jobUserCount) && count($jobUserCount)>0);
		}
		
		/**
		 * 测试获取系统不同种类的工作的人员的数量
		 */
		function testLoadKindsUserInfoCount(){
			$userId=UserId;
			$userInfoCount=$this->operator->loadKindsUserInfoCount($userId);
			$this->assertTrue(is_array($userInfoCount) && count($userInfoCount)>0);
		}
	}
?>