<?php
	require_once(__DIR__."/../Model/TopicManager.php");
	require_once("TestData.php");
	use PHPUnit\Framework\TestCase;
	
	class TopicManagerTest extends TestCase{
		private $topicManager;
		
		function setUp(){
			$this->topicManager=new TopicManager();
			$username=UserName;
			$password=Password;
			$this->topicManager->login($password, $username);
		} 
		//执行每个测试后退出系统
		function tearDown(){
			$this->topicManager->logout();
		}
		/**
		 * 下面的代码测试问题管理员加载所有的问题列表
		 */
		function testGetAllTopicList(){
			$result=$this->topicManager->getAllTopicList();
			$this->assertTrue(is_array($result) && count($result)>0);
		}
		
		/**
		 * 测试获取问题详情
		 */
		function testGetTopicDetails(){
			$topicId=TopicId;
			$result=$this->topicManager->getTopicDetails($topicId);
			$this->assertTrue(is_array($result) && count($result)>0);
		}
		/**
		 * 测试为管理员获取所有问题
		 */
		function testGetAllTopicListForManager(){
			$topics=$this->topicManager->getAllTopicListForManager();
			$this->assertTrue(is_array($topics) && count($topics)>0);
		}
		
		/**
		 * 测试检索问题
		 */
		function testQueryTopicsByKeyword(){
			$keyword="测试";
			$topics=$this->topicManager->queryTopicsByKeyword($keyword);
			$this->assertTrue(is_array($topics) && count($topics)>0);
		}
		
	}
?>