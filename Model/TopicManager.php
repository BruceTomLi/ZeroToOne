<?php
	require_once(__DIR__."/User.php");
	require_once(__DIR__."/../classes/MysqlPdo.php");
	
	/**
	 * 这个“问题管理员”类主要用来写一些批量管理问题的业务
	 */
	class TopicManager extends User{
		/**
		 * 下面的方法用来按照时间顺序加载用户提出的问题
		 * 由于前台的问题页需要加载所有问题供用户浏览，所以这里调用函数是不需要用户登录的
		 */
		function getAllTopicList($page=1){
			global $pdo;
			//获取分页数据
			$count=$this->getAllTopicCount();
			$pageTotal=($count%5==0)?($count/5):ceil($count/5);//获取总页数，如果是5的倍数，就获取除以5的结果，否则ceil
			//规范化页数，并返回起始页
			$startRow=$this->getStartPage($page,$pageTotal);
			$sql="select (select username from tb_user where userId=tt.askerId) as asker,tt.* from tb_topic tt where enable=1 order by askDate desc limit $startRow,5";
			$result=$pdo->getQueryResult($sql);
			return $result;
		}
		
		/**
		 * 获取所有话题的个数
		 */
		function getAllTopicCount(){
			global $pdo;
			$sql="select count(*) as topicCount from tb_topic where enable=1";
			$count=$pdo->getOneFiled($sql, "topicCount");
			return $count;
		}
		
		/**
		 * 获取某个问题的详细信息
		 * 是给未登录用户设置的方法，不需要检测登录
		 */
		function getTopicDetails($topicId){
			global $pdo;
			$paraArr=array(":topicId"=>$topicId);			
			$sql="select * from tb_topic where topicId=:topicId";
			$result=$pdo->getQueryResult($sql,$paraArr);
			return $result;
		}
		
		/**
		 * 获取所有的问题，给管理员设置的，用于管理所有用户的问题，所以enable为1或者0都要显示
		 */
		function getAllTopicListForManager(){
			global $pdo;
			$sql="select (select username from tb_user where userId=tt.askerId) as askerName,tt.* from tb_topic tt order by askDate desc";
			$topics=$pdo->getQueryResult($sql);
			return $topics;
		}
		
		/**
		 * 通过关键字检索问题
		 */
		function queryTopicsByKeyword($keyword,$page=1){
			global $pdo;
			//获取分页数据
			$count=$this->queryTopicsByKeywordCount($keyword);
			$pageTotal=ceil($count/5);//获取总页数
			//规范化页数，并返回起始页
			$startRow=$this->getStartPage($page,$pageTotal);
			
			$keyword="%".$keyword."%";//使用模糊查询，前后要加百分号
			$paraArr=array(":keyword"=>$keyword);
			$sql="select (select username from tb_user where userId=tt.askerId) as asker,tt.* from tb_topic tt where  ";
			$sql.="(content like :keyword or topicDescription like :keyword) limit $startRow,5";
			$topicList=$pdo->getQueryResult($sql,$paraArr);
			return $topicList;
		}
		
		/**
		 * 获取检索的记录数
		 */
		function queryTopicsByKeywordCount($keyword){
			global $pdo;				
			$keyword="%".$keyword."%";//使用模糊查询，前后要加百分号
			$paraArr=array(":keyword"=>$keyword);
			$sql="select count(*) as topicCount from tb_topic tt where  ";
			$sql.="(content like :keyword or topicDescription like :keyword)";
			$count=$pdo->getOneFiled($sql, "topicCount",$paraArr);
			return $count;
		}
				
	}
?>