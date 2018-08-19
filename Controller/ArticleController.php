<?php
	require_once(__DIR__.'/../Model/Author.php');
	require_once(__DIR__.'/../Model/ArticleManager.php');
	class ArticleController{
		//引入author是为了使用它的loadArticleDetails方法
		private $author;
		private $articleManager;
		
		public function __construct(){
			$this->author=new Author();
			$this->articleManager=new ArticleManager();
		}
		
		/**
		 * 加载所有的文章信息
		 */
		function loadAllArticles(){
			$articles=$this->articleManager->loadAllArticles();
			$resultArr=array("articles"=>$articles);
			return json_encode($resultArr);
		}
		
		/**
		 * 查询文章
		 */
		public function queryArticlesByKeyword(){
			$keyword=$_REQUEST['keyword']??"";
			$page=$_REQUEST['page']??1;
			$count=$this->articleManager->queryArticlesByKeywordCount($keyword);
			$articles=$this->articleManager->queryArticlesByKeyword($keyword,$page);
			$resultArr=array("articles"=>$articles,"count"=>$count);
			return json_encode($resultArr);
		}
		
		/**
		 * 禁用文章
		 */
		function disableArticle(){
			$articleId=$_REQUEST['articleId'];
			$articleCount=$this->articleManager->disableArticle($articleId);
			$resultArr=array("articleCount"=>$articleCount);
			return json_encode($resultArr);
		}
		
		/**
		 * 启用文章
		 */
		function enableArticle(){
			$articleId=$_REQUEST['articleId'];
			$articleCount=$this->articleManager->enableArticle($articleId);
			$resultArr=array("articleCount"=>$articleCount);
			return json_encode($resultArr);
		}
		
		/**
		 * 删除文章
		 */
		function deleteArticle(){
			$articleId=$_REQUEST['articleId'];
			$articleCount=$this->articleManager->deleteArticle($articleId);
			$resultArr=array("articleCount"=>$articleCount);
			return json_encode($resultArr);
		}
		
		/**
		 * 获取文章相关的问题信息，被php页直接调用
		 */
		function getQuestionOfArticle($articleTitle){
			$questionInfo=$this->articleManager->getQuestionOfArticle($articleTitle);
			return $questionInfo;
		}
		
		/**
		 * 获取文章相关的话题信息，被php页直接调用
		 */
		function getTopicOfArticle($articleTitle){
			$topicInfo=$this->articleManager->getTopicOfArticle($articleTitle);
			return $topicInfo;
		}
		
		/**
		 * 选择要执行哪个动作
		 */
		public function selectAction(){
			//判断有没有请求动作，因为有php页面直接调用
			if(isset($_REQUEST['action'])){
				//用户需要登录系统，并且有管理作文的权限才能执行相应的action
				if($this->articleManager->isUserLogon()){
					//文章管理员可以执行的action
					if($this->articleManager->hasAuthority(ArticleManage)){
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="loadAllArticles"){
							return $this->loadAllArticles();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="queryArticlesByKeyword"){
							return $this->queryArticlesByKeyword();
						}
					}
					//文章管理员和作者可以执行的action
					else if($this->articleManager->hasAuthority(WriteArticle) || $this->articleManager->hasAuthority(ArticleManage)){
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="disableArticle"){
							return $this->disableArticle();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="enableArticle"){
							return $this->enableArticle();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="deleteArticle"){
							return $this->deleteArticle();
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

	$articleManager=new ArticleController();
	echo $articleManager->selectAction();
?>