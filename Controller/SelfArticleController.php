<?php
	require_once(__DIR__.'/../Model/User.php');
	require_once(__DIR__.'/../Model/Author.php');
	class SelfArticleController{
		private $author;
		
		public function __construct(){
			$this->author=new Author();
		}		
		
		/**
		 * 写文章
		 */
		public function writeArticle(){
			$token=$_REQUEST['token']??"";
			if($token==$_SESSION['token']){
				//传入的内容过多就截断
				$title=substr(trim($_REQUEST['title']??""),0,50);
				$author=substr(trim($_REQUEST['author']??""),0,25);
				$label=substr(trim($_REQUEST['label']??""),0,50);
				$content=$_REQUEST['content']??"";
				$size=strlen($content);
				if($size<20000){
					$infoArray=array("title"=>$title,"author"=>$author,"label"=>$label,"content"=>$content,"size"=>$size);
					
					$writeArticleCount=$this->author->writeArticle($infoArray);
					if(is_numeric($writeArticleCount)){
						$writeArticleCount=array("writeArticleCount"=>$writeArticleCount);
					}			
					return json_encode($writeArticleCount);
				}else{
					return json_encode(urlencode("文章内容长度超过上限（最多1W字）"));
				}
				
			}else{
				return json_encode(urlencode("该请求被认为是骇客CSRF攻击"));
			}
		}
		
		/**
		 * 加载作者所有的文章
		 */
		public function loadSelfArticles(){
			$articles=$this->author->loadSelfArticles();
			$resultArr=array("articles"=>$articles);
			return json_encode($resultArr);
		}
		
		/**
		 * 查询作者的文章
		 */
		public function queryArticlesByKeyword(){
			$keyword=$_REQUEST['keyword']??"";
			$page=$_REQUEST['page']??1;
			$count=$this->author->queryArticlesByKeywordCount($keyword);
			$articles=$this->author->queryArticlesByKeyword($keyword,$page);
			$resultArr=array("articles"=>$articles,"count"=>$count);
			return json_encode($resultArr);
		}
		
		/**
		 * 加载作者一篇文章的详情
		 */
		public function loadArticleDetails($articleId){			
			$articleDetails=$this->author->loadArticleDetails($articleId);
			$resultArr=array("articleDetails"=>$articleDetails);
			return json_encode($resultArr,true);
		}
		
		/**
		 * 删除作者自己写的作文
		 */
		public function deleteSelfArticle(){
			$articleId=$_REQUEST['articleId'];
			$deleteArticleRow=$this->author->deleteSelfArticle($articleId);
			$resultArr=array("deleteArticleRow"=>$deleteArticleRow);
			return json_encode($resultArr);
		}
		
		/**
		 * 发布作者自己的文章
		 */
		public function publishSelfArticle(){
			$articleId=$_REQUEST['articleId'];
			$publishArticleRow=$this->author->publishSelfArticle($articleId);
			$resultArr=array("publishArticleRow"=>$publishArticleRow);
			return json_encode($resultArr);
		}
		
		/**
		 * 取消发布自己的文章
		 */
		public function cancelPublishSelfArticle(){
			$articleId=$_REQUEST['articleId'];
			$cancelPublishArticleRow=$this->author->cancelPublishSelfArticle($articleId);
			$resultArr=array("cancelPublishArticleRow"=>$cancelPublishArticleRow);
			return json_encode($resultArr);
		}
		 
		 
		public function selectAction(){
			//判断有没有请求动作，因为有php页面直接调用
			if(isset($_REQUEST['action'])){
				//用户需要登录系统，并且有权限才能执行相应的action
				if($this->author->isUserLogon()){
					//可以执行的action
					if($this->author->hasAuthority(WriteArticle)){
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="writeArticle"){
							return $this->writeArticle();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="loadSelfArticles"){
							return $this->loadSelfArticles();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="queryArticlesByKeyword"){
							return $this->queryArticlesByKeyword();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="loadArticleDetails"){
							return $this->loadArticleDetails();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="deleteSelfArticle"){
							return $this->deleteSelfArticle();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="publishSelfArticle"){
							return $this->publishSelfArticle();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="cancelPublishSelfArticle"){
							return $this->cancelPublishSelfArticle();
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
	
	$selfArticleController=new SelfArticleController();
	echo $selfArticleController->selectAction();
	//echo $questionController->replyComment();
?>