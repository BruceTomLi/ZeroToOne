<?php
	require_once(__DIR__."/../Model/Author.php");
	require_once("TestData.php");
	use PHPUnit\Framework\TestCase;
	class AuthorTest extends TestCase{
		private $author;
		
		//执行每个测试前先登录系统
		function setUp(){			
			$this->author=new Author();
			$username=UserName;
			$password=Password;
			$this->author->login($password, $username);
		}
		//执行每个测试后退出系统
		function tearDown(){
			$this->author->logout();
		}
		
		/**
		 * 测试文章标题是否重复
		 */
		function testIsArticleTitleRepeat(){
			$title=ArticleTitle;
			$isArticleTitleRepeat=$this->author->isArticleTitleRepeat($title);
			$this->assertTrue($isArticleTitleRepeat);
		}
		
		/**
		 * 测试作者写文章
		 */
		function testWriteArticle(){
			$infoArray=array("title"=>ArticleTitle,"author"=>UserName,"size"=>ArticleSize,"label"=>ArticleLabel,
					"content"=>ArticleContent);
			//文章标题没重复时才会写入一篇文章
			$writeArticleCount=$this->author->writeArticle($infoArray);
			if($this->author->isArticleTitleRepeat(ArticleTitle)){
				//需要断言返回值和编码后的中文相同				
				$this->assertTrue($writeArticleCount==urlencode("文章标题重复"));
			}
			else{
				$this->assertTrue(is_numeric($writeArticleCount) && $writeArticleCount>0);
			}
		}
		
		/**
		 * 测试加载作者所有文章
		 */
		function testLoadSelfArticles(){
			$articles=$this->author->loadSelfArticles();
			$this->assertTrue(is_array($articles) && count($articles)>0);
		}
		
		/**
		 * 加载作者一篇文章的详情
		 */
		function testLoadArticleDetails(){
			$articleId=ArticleId;
			$article=$this->author->loadArticleDetails($articleId);
			$this->assertTrue(is_array($article) && count($article)>0);
		}		
		
		/**
		 * 测试作者删除自己的文章
		 */
		function testDeleteSelfArticle(){
			$articleId=ArticleIdForDelete;
			$deleteArticleRow=$this->author->deleteSelfArticle($articleId);
			//文章被删除一次之后，就不再有效果
			$this->assertTrue(is_numeric($deleteArticleRow) && $deleteArticleRow>=0);
		}
		
		/**
		 * 测试发布自己的文章
		 */
		function testPublishSelfArticle(){
			$articleId=ArticleId;
			$publishArticleRow=$this->author->publishSelfArticle($articleId);
			$this->assertTrue(is_numeric($publishArticleRow) && $publishArticleRow>=0);//可能已经发布了
		}
		
		/**
		 * 测试检测文章是否已经发布
		 */
		function testIsArticlePublished(){
			$articleId=ArticleId;
			$publishArticleCount=$this->author->isArticlePublished($articleId);
			$this->assertEquals($publishArticleCount,1);			
		}
		
		/**
		 * 测试检测取消发布自己的文章
		 */
		function testCancelPublishSelfArticle(){
			$articleId=ArticleId;
			$cancelPublishArticleRow=$this->author->cancelPublishSelfArticle($articleId);
			$this->assertEquals($cancelPublishArticleRow,1);
		}
		
		/**
		 * 测试通过关键字检索自己的文章
		 */
		function testQueryArticlesByKeyword(){
			$keyword="测试";
			$articles=$this->author->queryArticlesByKeyword($keyword);
			$this->assertTrue(is_array($articles) && count($articles)>0);
		}
	}

?>