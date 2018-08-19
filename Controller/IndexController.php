<?php
	require_once(__DIR__."/../Model/ArticleManager.php");
	require_once(__DIR__."/../Model/Operator.php");
	
	class IndexController{
		private $articleManager;
		private $operator;
		
		public function __construct(){
			$this->articleManager=new ArticleManager();
			$this->operator=new Operator();
		}
		
		/**
		 * 获取所有文章信息
		 */
		public function getArticleList(){
			$page=$_REQUEST['page'];
			$articles=$this->articleManager->getArticleList($page);
			$articlesCount=$this->articleManager->getArticlesCount();
			$resultArr=array("articles"=>$articles,"count"=>$articlesCount);
			return json_encode($resultArr);
		}
		
		/**
		 * 获取所有文章信息
		 */
		public function loadTwentyNotices(){
			$notices=$this->operator->loadTwentyNotices();
			$resultArr=array("notices"=>$notices);
			return json_encode($resultArr);
		}
		
		/**
		 * 选择要执行哪个动作
		 * 用户不登录都可以获取到的信息，不需要登录检测和权限认证
		 */
		public function selectAction(){
			if(isset($_REQUEST['action']) && $_REQUEST['action']=="getArticleList"){
				return $this->getArticleList();
			}
			if(isset($_REQUEST['action']) && $_REQUEST['action']=="loadTwentyNotices"){
				return $this->loadTwentyNotices();
			}
		}
	}

	$indexController=new IndexController();
	echo $indexController->selectAction();
?>