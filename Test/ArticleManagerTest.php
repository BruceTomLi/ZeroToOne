<?php
	require_once(__DIR__."/../Model/ArticleManager.php");
	require_once("TestData.php");
	use PHPUnit\Framework\TestCase;
	class ArticleManagerTest extends TestCase{
		/**
		 * 下面的代码测试向数据库中添加用户
		 */
		private $articleManager;
		
		//执行每个测试前先登录系统
		function setUp(){			
			$this->articleManager=new ArticleManager();
			$username=UserName;
			$password=Password;
			$this->articleManager->login($password, $username);
		}
		//执行每个测试后退出系统
		function tearDown(){
			$this->articleManager->logout();
		}
		
		/**
		 * 测试加载所有的文章
		 * 需要登录
		 */
		function testLoadAllArticles(){
			$articles=$this->articleManager->loadAllArticles();
			$this->assertTrue(is_array($articles) && count($articles)>0);				
		}
		
		/**
		 * 测试禁用文章
		 * 需要登录
		 */
		function testDisableArticle(){
			$articleId=ArticleId;
			$articleCount=$this->articleManager->disableArticle($articleId);
			$this->assertTrue(is_numeric($articleCount) && $articleCount>0);			
		}
		
		/**
		 * 测试启用文章
		 * 需要登录
		 */
		function testEnableArticle(){
			$articleId=ArticleId;
			$articleCount=$this->articleManager->enableArticle($articleId);
			$this->assertTrue(is_numeric($articleCount) && $articleCount>0);
		}
		
		/**
		 * 测试删除文章
		 * 选用登录
		 */
		function testDeleteArticle(){
			$articleId=ArticleIdForDelete;
			$deleteArticleRow=$this->articleManager->deleteArticle($articleId);
			$this->assertTrue(is_numeric($deleteArticleRow) && $deleteArticleRow>=0);
		}
		
		/**
		 * 测试获取文章列表
		 */
		function testGetArticleList(){
			$articles=$this->articleManager->getArticleList();
			$this->assertTrue(is_array($articles) && count($articles)>0);
		}
		
		/**
		 * 测试获取文章总数
		 */
		function testGetArticlesCount(){
			$articlesCount=$this->articleManager->getArticlesCount();
			echo $articlesCount;
			$this->assertTrue(is_numeric($articlesCount) && $articlesCount>0);
		}
		/**
		 * 测试通过关键字检索文章
		 * 需要登录
		 */
		function testQueryArticlesByKeyword(){
			$keyword="二维";
			$articles=$this->articleManager->queryArticlesByKeyword($keyword);
			$this->assertTrue(is_array($articles) && count($articles)>0);
		}
		
		/**
		 * 测试获取文章相关的问题
		 */
		function testGetQuestionOfArticle(){
			$articleTitle="测试";
			$questionInfo=$this->articleManager->getQuestionOfArticle($articleTitle);
			$this->assertTrue(is_array($questionInfo) && $questionInfo[0]["questionCount"]>=0);
		}
		
		/**
		 * 测试获取文章相关的话题
		 * 
		 */
		function testGetTopicOfArticle(){
			$articleTitle="测试";
			$topicInfo=$this->articleManager->getTopicOfArticle($articleTitle);
			$this->assertTrue(is_array($topicInfo) && $topicInfo[0]["topicCount"]>=0);
		}
			
	}
?>