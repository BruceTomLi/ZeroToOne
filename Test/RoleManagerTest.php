<?php
	require_once(__DIR__."/../Model/RoleManager.php");
	require_once("TestData.php");
	use PHPUnit\Framework\TestCase;
	class RoleManagerTest extends TestCase{
		/**
		 * 下面的代码测试向数据库中添加用户
		 */
		private $roleManager;
		
		function setUp(){
			$this->roleManager=new RoleManager();
			$username=UserName;
			$password=Password;	
			$this->roleManager->login($password, $username);
		}
		//执行每个测试后退出系统
		function tearDown(){
			$this->roleManager->logout();
		}
		
		/**
		 * 测试添加角色
		 */
		function testAddRole(){
			$roleName=RoleName;
			$note=RoleNote;
			$authorities=RoleAuthority;
			
			$result=$this->roleManager->addRole($roleName, $note,$authorities);
			//如果已经进行过单元测试，那么数据库中的角色信息已经存在了，就不会再插入角色信息了（角色名不能重复）
			if($this->roleManager->isRoleNameRepeat($roleName)){
				$this->assertTrue($result=="角色名重复，请修改角色名");
			}
			else{
				$this->assertTrue(($result['affectRow']==1 && $result['authRoleRow']>0));
			}
		}
		
		/**
		 * 测试角色名是否重复
		 */
		function testIsRoleNameRepeat(){
			$roleName=RoleName;
			$isRoleNameRepeat=$this->roleManager->isRoleNameRepeat($roleName);
			$this->assertTrue($isRoleNameRepeat);
		}
		
		/**
		 * 测试加载角色信息
		 */
		function testLoadRoles(){			
			$roles=$this->roleManager->loadRoles();
			//如果已经进行过单元测试，那么数据库中的角色信息已经存在了，就不会再插入角色信息了（角色名不能重复）
			$this->assertTrue(is_array($roles) && count($roles)>0);
		}
		
		/**
		 * 测试加载某个角色的详细信息
		 */
		function testLoadRoleInfoByRoleId(){
			$roleId=RoleId;
			$roleInfo=$this->roleManager->loadRoleInfoByRoleId($roleId);
			$this->assertTrue(is_array($roleInfo) && count($roleInfo)>0);
		}
		
		/**
		 * 测试加载权限信息
		 */
		function testLoadAuthorityInfo(){
			$authorities=$this->roleManager->loadAuthorityInfo();
			//数据库中存在的权限信息记录应该是大于0的
			$this->assertTrue(is_array($authorities) && count($authorities)>0);
		}
		
		/**
		 * 测试修改角色信息
		 */
		function testChangeRoleInfo(){
			$roleInfo=array("roleId"=>RoleId,"name"=>"测试角色","note"=>"这是在单元测试里面增加一个测试角色","authorities"=>"1,99");
			$resultArr=$this->roleManager->changeRoleInfo($roleInfo);
			
			$this->assertTrue($resultArr["roleUpdateRow"]<=1 && $resultArr["roleAuthDeleteRow"]>=1 && $resultArr["roleAuthAddRow"]>=1);
			
		}
		
		/**
		 * 测试删除角色信息
		 */
		function testDeleteRole(){
			$roleId=DeleteRoleId;
			$result=$this->roleManager->deleteRole($roleId);
			$this->assertTrue(is_numeric($result) && $result>=0);//删除一次之后，第二次删除就对数据库没影响了
		}
	}
?>