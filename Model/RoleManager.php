<?php
	require_once(__DIR__."/User.php");
	require_once(__DIR__."/../classes/MysqlPdo.php");
	
	/**
	 * 这个“作者”类主要用来写一些文章，作者可以对自己的文章进行管理
	 */
	class RoleManager extends User{
		/**
		 * 下面的函数增加角色信息
		 */
		function addRole($roleName,$note,$authorities){
			if(!$this->isRoleNameRepeat($roleName)){
				global $pdo;				
				//在权限表中添加权限信息
				$roleId=uniqid("",true);
				$paraArr=array(":roleId"=>$roleId,":roleName"=>$roleName,":note"=>$note,":enable"=>"1");
				$sql="insert into tb_role values(:roleId,:roleName,:note,:enable)";
				$affectRow=$pdo->getUIDResult($sql,$paraArr);
				//在权限角色表中添加角色的权限信息
				$authorityArr=explode(",", $authorities);
				$authRoleRow=0;
				foreach($authorityArr as $authorityId){
					$paraArr=array(":authorityId"=>$authorityId,":roleId"=>$roleId);
					$sql="insert into tb_roleauthority values(:roleId,:authorityId)";
					if($pdo->getUIDResult($sql,$paraArr)==1){
						$authRoleRow++;
					}
				}
				//记录添加的角色信息行数和角色权限信息行数
				$affectArr=array("affectRow"=>$affectRow,"authRoleRow"=>$authRoleRow);					
				return $affectArr;
			}
			else{
				return "角色名重复，请修改角色名";
			}
		}
		
		/**
		 * 角色名不能有重复，这里用一个函数判断角色名是否重复
		 */
		function isRoleNameRepeat($roleName){
			global $pdo;				
			$paraArr=array(":roleName"=>$roleName);
			$sql="select count(*) as roleCount from tb_role where name=:roleName";
			$roleCount=$pdo->getOneFiled($sql,"roleCount",$paraArr);
			if($roleCount>0){
				return true;
			}
			else{
				return false;
			}
		}
		
		/**
		 * 加载角色信息
		 */
		function loadRoles(){
			global $pdo;
			$sql="call pro_getRolesAndAuths()";
			$roles=$pdo->getQueryResult($sql);
			//因为可以在mysql数据库中查询出每个角色对应的权限信息，并且以字符串的形式（一个字段）返回
			//所以这里就不需要去foreach循环获取每个role的权限，比较之下，在mysql中进行运算效率要高一些
			return $roles;
		}
		
		/**
		 * 加载某个角色的信息
		 */
		function loadRoleInfoByRoleId($roleId){
			global $pdo;
			$paraArr=array(":roleId"=>$roleId);
			$sql="call pro_getRolesAndAuthsByRoleId(:roleId)";
			$roleInfo=$pdo->getQueryResult($sql,$paraArr);
			//因为可以在mysql数据库中查询出每个角色对应的权限信息，并且以字符串的形式（一个字段）返回
			//所以这里就不需要去foreach循环获取每个role的权限，比较之下，在mysql中进行运算效率要高一些
			return $roleInfo;
		}
		/**
		 * 加载权限信息
		 * 角色管理里面也需要加载权限信息，因为需要给角色分配相应的权限
		 */
		function loadAuthorityInfo(){
			global $pdo;
			$sql="select * from tb_authority";
			$authorities=$pdo->getQueryResult($sql);
			return $authorities;
		}
		
		/**
		 * 修改角色信息
		 * 传入的参数是角色的信息数组
		 */
		function changeRoleInfo($roleInfo){
			global $pdo;
			//从数组中解析出角色信息
			$roleId=$roleInfo['roleId'];
			$roleName=$roleInfo['name'];
			$note=$roleInfo['note'];
			$authorities=$roleInfo['authorities'];
			$authArr=explode(",", $authorities);
			//修改tb_role信息
			$paraArr=array(":roleId"=>$roleId,":name"=>$roleName,":note"=>$note);
			$sql="update tb_role set name=:name,note=:note where roleId=:roleId";
			$roleUpdateRow=$pdo->getUIDResult($sql,$paraArr);
			//修改tb_roleAuthority信息，先删除原来的信息，在插入新的信息（避免需要对比新旧权限的复杂逻辑）
			$paraArr=array(":roleId"=>$roleId);
			$sql="delete from tb_roleauthority where roleId=:roleId";
			$roleAuthDeleteRow=$pdo->getUIDResult($sql,$paraArr);
			$roleAuthAddRow=0;
			foreach($authArr as $auth){
				$paraArr=array(":authorityId"=>$auth,":roleId"=>$roleId);
				$sql="insert into tb_roleauthority values(:roleId,:authorityId)";
				if($pdo->getUIDResult($sql,$paraArr)==1){
					$roleAuthAddRow++;
				}
			}
			//总结对数据库的影响并返回
			$resultArr=array("roleUpdateRow"=>$roleUpdateRow,"roleAuthDeleteRow"=>$roleAuthDeleteRow,"roleAuthAddRow"=>$roleAuthAddRow);
			return $resultArr;
		}

		/**
		 * 删除一个角色
		 * 理论上删除角色，不仅需要删除role表中的记录，还需要删除用户角色表中的记录，
		 * 还需要删除角色权限表中的记录。不过我在mysql数据库中设置了外键关联删除，所以代码中可以不写相应的删除逻辑
		 */
		function deleteRole($roleId){
			global $pdo;
			$paraArr=array(":roleId"=>$roleId);
			$sql="delete from tb_role where roleId=:roleId";
			$deleteRow=$pdo->getUIDResult($sql,$paraArr);
			return $deleteRow;
		}
		
	}
?>