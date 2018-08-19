<?php
	require_once(__DIR__."/User.php");
	require_once(__DIR__."/../classes/MysqlPdo.php");
	
	/**
	 * 这个权限管理类主要用来对用户的权限信息进行管理
	 */
	class PowerManager extends User{
		/**
		 * 下面的函数修改权限信息
		 * 系统的权限是固定的几种，不能在前端动态增加权限
		 * tb_authority表中的id和name都应该写死在数据库中，
		 * 只提供权限的说明信息供权限管理员进行修改
		 */
		function changeAuthorityInfo($authorityName,$note){
			global $pdo;
			$paraArr=array(":note"=>$note,":name"=>$authorityName);
			$sql="update tb_authority set note=:note where name=:name";
			$affectRow=$pdo->getUIDResult($sql,$paraArr);
			return $affectRow;
		}
		
		/**
		 * 加载权限信息
		 */
		function loadAuthorityInfo(){
			global $pdo;
			$sql="select * from tb_authority";
			$authorities=$pdo->getQueryResult($sql);
			return $authorities;
		}
		
	}
?>