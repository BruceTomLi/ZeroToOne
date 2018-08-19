<?php
	require_once(__DIR__.'/../Model/UserManager.php');
	class UserController{
		private $userManager;
		
		public function __construct(){
			$this->userManager=new UserManager();
		}
		/**
		 * 加载所有用户信息
		 */
		public function loadAllUserInfo(){
			$users=$this->userManager->loadAllUserInfo();
			return json_encode($users);
		}
		
		/**
		 * 加载用户信息（主要是角色信息）
		 */
		public function loadUserRoleInfo(){
			$userId=$_REQUEST['userId'];
			$userRoles=$this->userManager->loadUserRoleInfo($userId);
			return json_encode($userRoles);
		}
		
		/**
		 * 加载所有的角色信息（用户修改用户角色时，用户勾选相应的角色）
		 */
		public function loadAllRoles(){
			$roles=$this->userManager->loadAllRoles();
			return json_encode($roles);
		}
		
		/**
		 * 更新用户的角色信息
		 */
		public function updateUserRoleInfo(){
			$userId=$_REQUEST['userId'];
			$roles=$_REQUEST['roles'];
			$resultArr=$this->userManager->updateUserRoleInfo($userId,$roles);
			return json_encode($resultArr);
		}

		/**
		 * 通过关键字检索用户信息
		 */
		public function searchUserByKeyword(){
			$page=$_REQUEST['page']??1;
			$keyword=$_REQUEST['keyword']??"";
			$role=$_REQUEST['role']??"";
			$sex=$_REQUEST['sex']??"";
			$enable=$_REQUEST['enable']??"";
			$count=$this->userManager->searchUserByKeywordCount($keyword, $role, $sex, $enable);
			$users=$this->userManager->searchUserByKeyword($keyword, $role, $sex, $enable,$page);
			$resultArr=array("users"=>$users,"count"=>$count);
			return json_encode($resultArr);
		}
		
		/**
		 * 禁用用户
		 */
		public function disableUser(){
			$userId=$_REQUEST['userId'];
			$disabledUser=$this->userManager->disableUser($userId);
			$resultArr=array("disabledUser"=>$disabledUser);
			return json_encode($resultArr);			
		}
		
		/**
		 * 启用用户
		 */
		public function enableUser(){
			$userId=$_REQUEST['userId'];
			$enabledUser=$this->userManager->enableUser($userId);
			$resultArr=array("eabledUser"=>$enabledUser);
			return json_encode($resultArr);			
		}
		
		/**
		 * 批量禁用用户
		 */
		public function disableQueryUsers(){
			$page=$_REQUEST['page']??1;
			$keyword=$_REQUEST['keyword']??"";
			$role=$_REQUEST['role']??"";
			$sex=$_REQUEST['sex']??"";
			$enable=$_REQUEST['enable']??"";
			$count=$this->userManager->disableQueryUsers($keyword, $role, $sex, $enable);
			$resultArr=array("count"=>$count);
			return json_encode($resultArr);
		}
		
		/**
		 * 批量启用用户
		 */
		public function enableQueryUsers(){
			$page=$_REQUEST['page']??1;
			$keyword=$_REQUEST['keyword']??"";
			$role=$_REQUEST['role']??"";
			$sex=$_REQUEST['sex']??"";
			$enable=$_REQUEST['enable']??"";
			$count=$this->userManager->enableQueryUsers($keyword, $role, $sex, $enable);
			$resultArr=array("count"=>$count);
			return json_encode($resultArr);
		}
		
		/**
		 * 重置用户的密码
		 */
		public function resetUserPassword(){
			$userId=$_REQUEST['userId']??"";
			$newPassword=trim($_REQUEST['newPassword']??"");
			$count=$this->userManager->resetUserPassword($userId, $newPassword)??0;
			$resultArr=array("count"=>$count);
			return json_encode($resultArr);
		}

		/**
		 * 激活用户账号
		 */
		public function activeUser(){
			$userId=$_REQUEST['userId']??"";
			$count=$this->userManager->activeUser($userId)??0;
			$resultArr=array("count"=>$count);
			return json_encode($resultArr);
		}
		 
		/**
		 * 选择要执行哪个动作
		 */
		public function selectAction(){
			//判断有没有请求动作，因为有php页面直接调用
			if(isset($_REQUEST['action'])){
				//用户需要登录系统，并且有权限才能执行相应的action
				if($this->userManager->isUserLogon()){
					//可以执行的action
					if($this->userManager->hasAuthority(UserManage)){
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="loadAllUserInfo"){
							return $this->loadAllUserInfo();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="loadUserRoleInfo"){
							return $this->loadUserRoleInfo();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="loadAllRoles"){
							return $this->loadAllRoles();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="updateUserRoleInfo"){
							return $this->updateUserRoleInfo();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="searchUserByKeyword"){
							return $this->searchUserByKeyword();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="disableUser"){
							return $this->disableUser();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="enableUser"){
							return $this->enableUser();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="disableQueryUsers"){
							return $this->disableQueryUsers();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="enableQueryUsers"){
							return $this->enableQueryUsers();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="resetUserPassword"){
							return $this->resetUserPassword();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="activeUser"){
							return $this->activeUser();
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

	$userController=new UserController();
	echo $userController->selectAction();
?>