<?php
	require_once(__DIR__."/../Model/UserManager.php");
	require_once("TestData.php");
	use PHPUnit\Framework\TestCase;
	class RoleManagerTest extends TestCase{
		/**
		 * 下面的代码测试向数据库中添加用户
		 */
		private $userManager;
		
		function setUp(){
			$this->userManager=new UserManager();
			$username=UserName;
			$password=Password;
			$this->userManager->login($password, $username);
		}
		//执行每个测试后退出系统
		function tearDown(){
			$this->userManager->logout();
		}
		
		/**
		 * 测试给加载所有用户信息
		 */
		function testLoadAllUserInfo(){
			$users=$this->userManager->loadAllUserInfo();
			$this->assertTrue(is_array($users) && count($users)>0);		
		}
		
		/**
		 * 测试给加载用户信息(主要是角色信息)
		 */
		function testLoadUserRoleInfo(){
			$userId=UserId;
			$userRoles=$this->userManager->loadUserRoleInfo($userId);
			$this->assertTrue(is_array($userRoles) && count($userRoles)>0);
		}
		
		/**
		 * 测试加载所有的角色信息
		 */
		function testLoadAllRoles(){
			$roles=$this->userManager->loadAllRoles();
			$this->assertTrue(is_array($roles) && count($roles)>0);
		}
		
		/**
		 * 测试修改用户角色信息
		 */
		function testUpdateUserRoleInfo(){
			$userId=UserId;
			$roles=UserRoles;
			$resultArr=$this->userManager->updateUserRoleInfo($userId, $roles);
			$this->assertTrue($resultArr['userRoleDeleteRow']>0 && $resultArr['userRoleAddRow']>0);
		}
		
		/**
		 * 测试通过关键字搜索用户信息
		 */
		function testSearchUserByKeyword(){
			$keyword="wangwu";
			$role=RoleName;//这里的roleId是测试角色
			$sex=1;
			$enable=1;
			$users=$this->userManager->searchUserByKeyword($keyword, $role, $sex, $enable);
			$this->assertTrue(is_array($users) && count($users)>0);
		}
		
		/**
		 * 测试禁用用户，禁用之后将导致用户无法登录，导致其他测试无法进行，所以在用户没有logout之前同时测试禁用和启用
		 */
		function testDisableUser(){
			$userId=DisableUserId;
			//禁用用户
			$disabledUser=$this->userManager->disableUser($userId);
			$this->assertTrue(is_numeric($disabledUser) && $disabledUser>0);
			//启用用户
			$enabledUser=$this->userManager->enableUser($userId);
			$this->assertTrue(is_numeric($enabledUser) && $enabledUser>0);
		}
		
		/**
		 * 测试启用用户，上面的测试已经测试了启用用户，这个测试用例是可有可无的
		 */
		function testEnableUser(){
			$userId=DisableUserId;
			$enabledUser=$this->userManager->enableUser($userId);
			$this->assertTrue(is_numeric($enabledUser) && $enabledUser>=0);
		}
		
		/**
		 * 测试禁用查询到的用户
		 */
		function testDisableQueryUsers(){
			$keyword="李云天";
			$role=RoleName;//这里的roleId是测试角色
			$sex=1;
			$enable="";
			$count=$this->userManager->disableQueryUsers($keyword, $role, $sex, $enable);
			$this->assertTrue(is_numeric($count) && $count>0);
		}
		
		/**
		 * 测试启用查询到的用户
		 */
		function testEnableQueryUsers(){
			$keyword="李云天";
			$role=RoleName;//这里的roleId是测试角色
			$sex=1;
			$enable="";
			$count=$this->userManager->enableQueryUsers($keyword, $role, $sex, $enable);
			$this->assertTrue(is_numeric($count) && $count>0);
		}
		
		/**
		 * 测试重置用户密码
		 */
		function testResetUserPassword(){
			$userId="10";
			$newPassword="ls@123";
			$count=$this->userManager->resetUserPassword($userId, $newPassword);
			$this->assertTrue(is_numeric($count) && $count>=0);//已经修改过就不再修改了
		}
		
		/**
		 * 测试激活用户
		 */
		function testActiveUser(){
			$userId="7";
			$result=$this->userManager->activeUser($userId);
			$this->assertTrue(is_numeric($result) && $result>=0);
		}
	}
?>