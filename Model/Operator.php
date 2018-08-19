<?php
	require_once(__DIR__."/User.php");
	require_once(__DIR__."/../classes/MysqlPdo.php");
	
	/**
	 * 这个“运营者”类主要用来管理流量信息，以及管理公告信息
	 */
	class Operator extends User{
		/**
		 * 创建公告
		 */
		function createNewNotice($title,$content){
			if(!$this->isNoticeRepeat($title)){
					global $pdo;
					$user=$_SESSION['username'];
					$noticeId=uniqid("",true);
					
					$createTime=date("Y-m-d H:i:s");
					$paraArr=array(":noticeId"=>$noticeId,":title"=>$title,":noticeContent"=>$content,":publisher"=>$user,"createTime"=>$createTime);
					$sql="insert into tb_notice values(:noticeId,:title,:noticeContent,(select userId from tb_user where username=:publisher),:createTime)";
					$insertRow=$pdo->getUIDResult($sql,$paraArr);
					return $insertRow;
			}else{
				return "公告标题重复";	
			}
		}
		/**
		 * 检测公告是否重复，避免创建重复的公告
		 */
		function isNoticeRepeat($title){
			global $pdo;
			$title=trim($title);
			$paraArr=array(":title"=>$title);
			$sql="select count(*) as noticeCount from tb_notice where title=:title";
			$count=$pdo->getOneFiled($sql, "noticeCount",$paraArr);
			return $count>0?true:false;
		}
		
		/**
		 * 删除公告
		 */
		function deleteNotice($noticeId){
			global $pdo;
			$paraArr=array(":noticeId"=>$noticeId);
			$sql="delete from tb_notice where noticeId=:noticeId";
			$deleteRow=$pdo->getUIDResult($sql,$paraArr);
			return $deleteRow;
		}
		
		/**
		 * 加载公告信息
		 */
		function loadNotices(){
			global $pdo;
			$sql="select (select username from tb_user where userId=publisherId) as creator,tn.* from tb_notice tn order by tn.createTime desc";
			$notices=$pdo->getQueryResult($sql);
			return $notices;
		}
		
		/**
		 * 加载20条公告信息
		 */
		function loadTwentyNotices(){
			global $pdo;
			$sql="select (select username from tb_user where userId=publisherId) as creator,tn.* from tb_notice tn order by tn.createTime desc limit 20";
			$notices=$pdo->getQueryResult($sql);
			return $notices;
		}
		
		/**
		 * 加载公告详细信息
		 */
		function loadNoticeDetails($noticeId){
			global $pdo;
			$paraArr=array(":noticeId"=>$noticeId);
			$sql="select (select username from tb_user where userId=publisherId) as creator,tn.* from tb_notice tn where tn.noticeId=:noticeId";
			$notice=$pdo->getQueryResult($sql,$paraArr);
			return $notice;
		}
		
		/**
		 * 加载网站注册的男女人数信息
		 */
		function loadManWomanCount(){
			global $pdo;
			$sql="select * from view_manwomancount";
			$manWomanCount=$pdo->getQueryResult($sql);
			return $manWomanCount;
		}
		
		/**
		 * 加载网站不同种类的问题的数量
		 */
		function loadKindsOfQuestionCount(){
			global $pdo;
			$sql="select * from view_questiontypecount";
			$questionTypeCount=$pdo->getQueryResult($sql);
			
			return $questionTypeCount;
		}
		
		/**
		 * 加载网站不同种类的话题的数量
		 */
		function loadKindsOfTopicCount(){
			global $pdo;
			$sql="select * from view_topictypecount";
			$topicTypeCount=$pdo->getQueryResult($sql);
			return $topicTypeCount;
		}
		
		/**
		 * 加载网站不同种类的工作的人员的数量
		 */
		function loadKindsOfJobUserCount(){
			global $pdo;
			$sql="select * from view_userjobtypecount";
			$jobUserCount=$pdo->getQueryResult($sql);
			return $jobUserCount;
		}
		
		/**
		 * 加载网站中某个人的六个数据（问问题数，创建话题数，回答问题数，回答话题数，粉丝数量，关注的人的数量）
		 */
		function loadKindsUserInfoCount($userId){
			global $pdo;
			$paraArr=array(":userId"=>$userId);
			$sql="call pro_getUserInfoCountByUserId(:userId)";
			$userInfoCount=$pdo->getQueryResult($sql,$paraArr);
			return $userInfoCount;
		}
	}
?>