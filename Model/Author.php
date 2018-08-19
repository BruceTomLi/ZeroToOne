<?php
	require_once(__DIR__."/User.php");
	require_once("ConstData.php");
	require_once(__DIR__."/../classes/MysqlPdo.php");
	
	/**
	 * 这个“作者”类主要用来写一些文章，作者可以对自己的文章进行管理
	 */
	class Author extends User{
		/**
		 * 作者写好并保存一篇文章
		 * 文章的内容较多，将前台的参数存放在一个文章数组中进行传递
		 * 开发进行到这里时，我发现还是先开发用户角色管理和授权的管理比较合理，
		 * 因为作者只是少数人拥有的角色，而且他的功能较多，在执行操作的时候应该先判断一下
		 * 用户的登录状态和用户角色
		 */
		function writeArticle($infoArray){
			if(!$this->isCreateArticleOverCount()){
				if(!$this->isArticleTitleRepeat($infoArray['title'])){
					global $pdo;
					$articleId=uniqid("",true);
					$createDate=date("Y-m-d H:i:s");
					$publisher=$_SESSION['username'];
					$paraArr=array(":articleId"=>$articleId,":title"=>$infoArray['title'],":author"=>$infoArray['author'],
						":createDate"=>$createDate,":publisher"=>$publisher,
						":size"=>$infoArray['size'],":label"=>$infoArray['label'],
						":articleContent"=>$infoArray['content'],":isPublic"=>0,":enable"=>1);
					$sql="insert into tb_article(articleId,title,author,createDate,publisher,size,label,articleContent,isPublic,enable) ";
					$sql.="values(:articleId,:title,:author,:createDate,(select userId from tb_user where username=:publisher),:size,:label,:articleContent,:isPublic,:enable)";
					
					$writeArticleCount=$pdo->getUIDResult($sql,$paraArr);
					//删除用户多余的图片（文章，话题或者问题中的）
					$this->deleteUserSpareImages();
					return $writeArticleCount;
				}
				else{
					return urlencode("文章标题重复");
				}
			}else{
				return urlencode("今日写作数量已达上限");
			}			
		}
		
		/**
		 * 判断用户今天写作数量是否超过了限制
		 */
		function isCreateArticleOverCount(){
			global $pdo;
			$username=$_SESSION['username'];
			$paraArr=array(":username"=>$username);
			$sql="call pro_isWriteArticleOverTimes(:username)";
			$result=$pdo->getOneFiled($sql, "isOverTimes",$paraArr);
			return $result=="yes"?true:false;
		}
		
		/**
		 * 检测文章标题是否重复
		 */
		function isArticleTitleRepeat($title){
			global $pdo;
			$paraArr=array(":title"=>$title);
			$sql="select count(*) as articleCount from tb_article where title=:title";
			$articleCount=$pdo->getOneFiled($sql, "articleCount",$paraArr);
			if($articleCount>0){
				return true;
			}
			else{
				return false;
			}
		}
		
		/**
		 * 加载作者所有的文章
		 */
		function loadSelfArticles(){
			global $pdo;
			$username=$_SESSION['username'];
			$paraArr=array(":username"=>$username);
			$sql="select :username as publisherName,articleId,title,author,publishDate,publisher,size,label from tb_article where publisher=(select userId from tb_user where username=:username)";
			$articles=$pdo->getQueryResult($sql,$paraArr);
			return $articles;
		}
		
		/**
		 * 通过关键字查询作者文章
		 */
		function queryArticlesByKeyword($keyword,$page=1){
			global $pdo;
			//获取分页数据
			$count=$this->queryArticlesByKeywordCount($keyword);
			$pageTotal=ceil($count/5);//获取总页数
			//规范化页数，并返回起始页
			$startRow=$this->getStartPage($page,$pageTotal);
			
			$username=$_SESSION['username'];
			$keyword="%".$keyword."%";//使用模糊查询，前后要加百分号
			$paraArr=array(":username"=>$username,":keyword"=>$keyword);
			$sql="select :username as publisherName,articleId,title,author,publishDate,publisher,size,label from tb_article ";
			$sql.="where publisher=(select userId from tb_user where username=:username) and ";
			$sql.="(title like :keyword or label like :keyword) limit $startRow,5";
			$articles=$pdo->getQueryResult($sql,$paraArr);
			return $articles;
		}
		
		/**
		 * 获取通过关键字查询到的文章个数
		 */
		function queryArticlesByKeywordCount($keyword){
			global $pdo;				
			$username=$_SESSION['username'];
			$keyword="%".$keyword."%";//使用模糊查询，前后要加百分号
			$paraArr=array(":username"=>$username,":keyword"=>$keyword);
			$sql="select count(*) as articleCount from tb_article ";
			$sql.="where publisher=(select userId from tb_user where username=:username) and ";
			$sql.="(title like :keyword or label like :keyword)";
			$count=$pdo->getOneFiled($sql,"articleCount",$paraArr);
			return $count;
		}
		/**
		 * 加载文章详细信息
		 */
		function loadArticleDetails($articleId){
			global $pdo;
			$paraArr=array(":articleId"=>$articleId);
			$sql="select (select username from tb_user where userId=publisher) as publisherName,ta.* from tb_article ta where articleId=:articleId";
			$article=$pdo->getQueryResult($sql,$paraArr);
			return $article;
		}
		
		/**
		 * 删除自己的文章
		 */
		function deleteSelfArticle($articleId){
			global $pdo;
			$paraArr=array(":articleId"=>$articleId);
			$sql="delete from tb_article where articleId=:articleId";
			$deleteArticleRow=$pdo->getUIDResult($sql,$paraArr);
			return $deleteArticleRow;
		}
		
		/**
		 * 发布自己的文章
		 */
		function publishSelfArticle($articleId){
			global $pdo;
			$publishDate=date("Y-m-d H:i:s");
			$paraArr=array(":articleId"=>$articleId,":publishDate"=>$publishDate);
			$sql="update tb_article set publishDate=:publishDate,isPublic=1 where articleId=:articleId";
			$publishArticleRow=$pdo->getUIDResult($sql,$paraArr);
			return $publishArticleRow;
		}
		
		/**
		 * 检测作者的文章是否已经发布
		 */
		function isArticlePublished($articleId){
			global $pdo;
			$paraArr=array(":articleId"=>$articleId);
			$sql="select count(*) publishArticleCount from tb_article where articleId=:articleId and isPublic=1";
			$publishArticleCount=$pdo->getOneFiled($sql, "publishArticleCount",$paraArr);
			return $publishArticleCount;
		}
		
		/**
		 * 取消发布自己的文章
		 */
		function cancelPublishSelfArticle($articleId){
			global $pdo;
			$paraArr=array(":articleId"=>$articleId);
			$sql="update tb_article set publishDate=null,isPublic=0 where articleId=:articleId";
			$cancelPublishArticleRow=$pdo->getUIDResult($sql,$paraArr);
			return $cancelPublishArticleRow;
		}
	}
?>