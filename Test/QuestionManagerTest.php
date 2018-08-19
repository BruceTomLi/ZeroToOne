<?php
	require_once(__DIR__."/../Model/QuestionManager.php");
	require_once("TestData.php");
	use PHPUnit\Framework\TestCase;
	
	class QuestionManagerTest extends TestCase{
		private $questionManager;
		
		function setUp(){
			$this->questionManager=new QuestionManager();
			$username=UserName;
			$password=Password;
			$this->questionManager->login($password, $username);
		} 
		//执行每个测试后退出系统
		function tearDown(){
			$this->questionManager->logout();
		}
		/**
		 * 下面的代码测试问题管理员加载所有的问题列表
		 */
		function testGetAllQuestionList(){
			$result=$this->questionManager->getAllQuestionList();
			$this->assertTrue(is_array($result) && count($result)>0);
		}
		
		/**
		 * 测试获取问题详情
		 */
		function testGetQuestionDetails(){
			$questionId=QuestionId;
			$result=$this->questionManager->getQuestionDetails($questionId);
			$this->assertTrue(is_array($result) && count($result)>0);
		}
		/**
		 * 测试为管理员获取所有问题
		 */
		function testGetAllQuestionListForManager(){
			$questions=$this->questionManager->getAllQuestionListForManager();
			$this->assertTrue(is_array($questions) && count($questions)>0);
		}
		
		/**
		 * 测试检索问题
		 */
		function testQueryQuestionsByKeyword(){
			$keyword="测试";
			$questions=$this->questionManager->queryQuestionsByKeyword($keyword);
			$this->assertTrue(is_array($questions) && count($questions)>0);
		}
		
	}
?>