<?php
	require_once(__DIR__.'/../Model/RoleManager.php');
	class RoleController{
		private $roleManager;
		
		public function __construct(){
			$this->roleManager=new RoleManager();
		}
		
		/**
		 * 添加角色信息
		 */
		public function addRole(){
			$roleName=$_REQUEST['roleName'];
			$note=$_REQUEST['note'];
			$authorities=$_REQUEST['authorities'];
			$result=$this->roleManager->addRole($roleName, $note,$authorities);
			return json_encode($result);
		}
		
		/**
		 * 加载角色信息
		 */
		public function loadRole(){
			$result=$this->roleManager->loadRoles();
			$resultArr=array("roles"=>$result);
			return json_encode($resultArr);
		}
		
		/**
		 * 加载某个角色的信息
		 */
		public function loadRoleInfoByRoleId(){
			$roleId=$_REQUEST['roleId'];
			$result=$this->roleManager->loadRoleInfoByRoleId($roleId);
			$resultArr=array("roleInfo"=>$result);
			return json_encode($resultArr);
		}
		
		/**
		 * 加载所有的权限信息
		 */
		public function loadAuthorityInfo(){
			$result=$this->roleManager->loadAuthorityInfo();
			$resultArr=array("authorities"=>$result);
			return json_encode($resultArr);
		}
		
		/**
		 * 修改角色信息
		 */
		public function changeRoleInfo(){
			$roleId=$_REQUEST['roleId'];
			$name=$_REQUEST['name'];
			$note=$_REQUEST['note'];
			$authorities=$_REQUEST['authorities'];
			$roleInfo=array("roleId"=>$roleId,"name"=>$name,"note"=>$note,"authorities"=>$authorities);
			
			$resultArr=$this->roleManager->changeRoleInfo($roleInfo);
			return json_encode($resultArr);
		}
		
		/**
		 * 删除角色信息
		 */
		public function deleteRole(){
			$roleId=$_REQUEST['roleId'];
			
			$deleteRow=$this->roleManager->deleteRole($roleId);
			$resultArr=array("deleteRow"=>$deleteRow);
			return json_encode($resultArr);
		}
		
		/**
		 * 选择要执行哪个动作
		 */
		public function selectAction(){
			//判断有没有请求动作，因为有php页面直接调用
			if(isset($_REQUEST['action'])){
				//用户需要登录系统，并且有权限才能执行相应的action
				if($this->roleManager->isUserLogon()){
					//可以执行的action
					if($this->roleManager->hasAuthority(RoleManage)){
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="addRole"){
							return $this->addRole();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="loadRole"){
							return $this->loadRole();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="loadAuthorityInfo"){
							return $this->loadAuthorityInfo();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="loadRoleInfoByRoleId"){
							return $this->loadRoleInfoByRoleId();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="changeRoleInfo"){
							return $this->changeRoleInfo();
						}
						if(isset($_REQUEST['action']) && $_REQUEST['action']=="deleteRole"){
							return $this->deleteRole();
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

	$roleController=new RoleController();
	echo $roleController->selectAction();
?>