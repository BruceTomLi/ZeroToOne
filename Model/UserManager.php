<?php
	require_once(__DIR__."/User.php");
	require_once(__DIR__."/../classes/MysqlPdo.php");
	
	/**
	 * 这个“用户管理员”类主要用来修改用户的角色
	 */
	class UserManager extends User{
		/**
		 * 加载所有的用户信息
		 */
		function loadAllUserInfo(){
			global $pdo;
			$sql="call pro_getUsersHasRoles()";
			$users=$pdo->getQueryResult($sql);
			return $users;
		}
		
		/**
		 * 加载一个用户的信息(主要是需要角色信息)，用户修改用户的角色
		 */
		function loadUserRoleInfo($userId){
			global $pdo;
			$paraArr=array(":userId"=>$userId);
			$sql="call pro_getUserRoles(:userId)";
			$userRoles=$pdo->getQueryResult($sql,$paraArr);
			return $userRoles;
		}
		/**
		 * 加载所有的角色信息
		 */
		function loadAllRoles(){
			global $pdo;
			$sql="select * from tb_role";
			$roles=$pdo->getQueryResult($sql);
			return $roles;
		}
		
		/**
		 * 更新用户角色信息
		 */
		function updateUserRoleInfo($userId,$roles){
			global $pdo;
			//从数组中解析出所有角色
			$roleArr=explode(",", $roles);
			//删除tb_userrole表中原来用户的角色信息
			$paraArr=array(":userId"=>$userId);
			$sql="delete from tb_userrole where userId=:userId";
			$userRoleDeleteRow=$pdo->getUIDResult($sql,$paraArr);
			//向tb_userrole中添加新的用户角色信息
			$userRoleAddRow=0;
			foreach($roleArr as $role){
				$paraArr=array(":userId"=>$userId,":roleId"=>$role);
				$sql="insert into tb_userrole values(:userId,:roleId)";
				if($pdo->getUIDResult($sql,$paraArr)==1){
					$userRoleAddRow++;
				}
			}
			//总结对数据库中用户角色表的影响
			$resultArr=array("userRoleDeleteRow"=>$userRoleDeleteRow,"userRoleAddRow"=>$userRoleAddRow);
			return $resultArr;
		}
		
		/**
		 * 根据关键字搜索用户
		 */
		function searchUserByKeyword($keyword,$role,$sex,$enable,$page=1){
			global $pdo;
			//获取分页数据
			$count=$this->searchUserByKeywordCount($keyword,$role,$sex,$enable);
			$pageTotal=($count%5==0)?($count/5):ceil($count/5);//获取总页数，如果是5的倍数，就获取除以5的结果，否则ceil
			//规范化页数，并返回起始页
			$startRow=$this->getStartPage($page,$pageTotal);
			
			$keyword="%".$keyword."%";
			$paraArr=array(":keyword"=>$keyword);
			//从tb_user中获取的结果没有用户的角色信息，如果像之前一样使用存储过程会加大问题难度，这里我使用了视图
			$sql="select * from view_usershasroles where (username like :keyword or email like :keyword) ";
			if($role!=""){
				$extendArr=array(":role"=>$role);
				$paraArr=array_merge($paraArr,$extendArr);
				$sql.="and userId in (select userId from tb_userrole where roleId=(select roleId from tb_role where name=:role)) ";
			}
			if($sex!=""){
				$extendArr=array(":sex"=>$sex);
				$paraArr=array_merge($paraArr,$extendArr);
				$sql.="and sex=:sex ";
			}
			if($enable!=""){
				$extendArr=array(":enable"=>$enable);
				$paraArr=array_merge($paraArr,$extendArr);
				$sql.="and enable=:enable ";
			}
			$sql.="limit $startRow,5";
			$users=$pdo->getQueryResult($sql,$paraArr);
			return $users;
		}

		/**
		 * 获取搜索结果记录数
		 */
		function searchUserByKeywordCount($keyword,$role,$sex,$enable){
			global $pdo;				
			$keyword="%".$keyword."%";
			$paraArr=array(":keyword"=>$keyword);
			//从tb_user中获取的结果没有用户的角色信息，如果像之前一样使用存储过程会加大问题难度，这里我使用了视图
			$sql="select count(*) as userCount from view_usershasroles where (username like :keyword or email like :keyword) ";
			if($role!=""){
				$extendArr=array(":role"=>$role);
				$paraArr=array_merge($paraArr,$extendArr);
				$sql.="and userId in (select userId from tb_userrole where roleId=(select roleId from tb_role where name=:role)) ";
			}
			if($sex!=""){
				$extendArr=array(":sex"=>$sex);
				$paraArr=array_merge($paraArr,$extendArr);
				$sql.="and sex=:sex ";
			}
			if($enable!=""){
				$extendArr=array(":enable"=>$enable);
				$paraArr=array_merge($paraArr,$extendArr);
				$sql.="and enable=:enable ";
			}
			$count=$pdo->getOneFiled($sql, "userCount",$paraArr);
			return $count;
		}
		
		/**
		 * 禁用用户
		 */
		function disableUser($userId){
			global $pdo;
			$paraArr=array(":userId"=>$userId);
			$sql="update tb_user set enable=0 where userId=:userId";
			$disabledUser=$pdo->getUIDResult($sql,$paraArr);
			return $disabledUser;
		}
		
		/**
		 * 启用用户
		 */
		function enableUser($userId){
			global $pdo;
			$paraArr=array(":userId"=>$userId);
			$sql="update tb_user set enable=1 where userId=:userId";
			$disabledUser=$pdo->getUIDResult($sql,$paraArr);
			return $disabledUser;
		}
		
		/**
		 * 禁用查询结果查到的用户
		 */
		function disableQueryUsers($keyword,$role,$sex,$enable){
			global $pdo;				
			$keyword="%".$keyword."%";
			$paraArr=array(":keyword"=>$keyword);
			$sql="update tb_user set enable=0 where (username like :keyword or email like :keyword) ";
			if($role!=""){
				$extendArr=array(":role"=>$role);
				$paraArr=array_merge($paraArr,$extendArr);
				$sql.="and userId in (select userId from tb_userrole where roleId=(select roleId from tb_role where name=:role)) ";
			}
			if($sex!=""){
				$extendArr=array(":sex"=>$sex);
				$paraArr=array_merge($paraArr,$extendArr);
				$sql.="and sex=:sex ";
			}
			if($enable!=""){
				$extendArr=array(":enable"=>$enable);
				$paraArr=array_merge($paraArr,$extendArr);
				$sql.="and enable=:enable ";
			}
			$count=$pdo->getUIDResult($sql,$paraArr);
			return $count;
		}
		
		/**
		 * 启用查询结果查到的用户
		 */
		function enableQueryUsers($keyword,$role,$sex,$enable){
			global $pdo;				
			$keyword="%".$keyword."%";
			$paraArr=array(":keyword"=>$keyword);
			$sql="update tb_user set enable=1 where (username like :keyword or email like :keyword) ";
			if($role!=""){
				$extendArr=array(":role"=>$role);
				$paraArr=array_merge($paraArr,$extendArr);
				$sql.="and userId in (select userId from tb_userrole where roleId=(select roleId from tb_role where name=:role)) ";
			}
			if($sex!=""){
				$extendArr=array(":sex"=>$sex);
				$paraArr=array_merge($paraArr,$extendArr);
				$sql.="and sex=:sex ";
			}
			if($enable!=""){
				$extendArr=array(":enable"=>$enable);
				$paraArr=array_merge($paraArr,$extendArr);
				$sql.="and enable=:enable ";
			}
			$count=$pdo->getUIDResult($sql,$paraArr);
			return $count;
		}
		
		/**
		 * 重置用户密码
		 */
		function resetUserPassword($userId,$newPassword){
			global $pdo;
			$newPassword=md5($newPassword);
			$paraArr=array(":userId"=>$userId,":newPassword"=>$newPassword);
			$sql="update tb_user set password=:newPassword where userId=:userId";
			$result=$pdo->getUIDResult($sql,$paraArr);
			return $result;
		}
		
		/**
		 * 激活用户
		 */
		function activeUser($userId){
			global $pdo;
			$paraArr=array(":userId"=>$userId);
			$sql="update tb_user set active=1 where userId=:userId";
			$result=$pdo->getUIDResult($sql,$paraArr);
			return $result;
		}
	}
?>