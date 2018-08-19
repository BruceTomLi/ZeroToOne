<?php
	require_once(__DIR__."/../Model/PowerManager.php");
	require_once("TestData.php");
	use PHPUnit\Framework\TestCase;
	class PowerManagerTest extends TestCase{
		/**
		 * 下面的代码测试向数据库中添加用户
		 */
		private $powerManager;
		//执行每个测试前先登录系统
		function setUp(){
			$this->powerManager=new PowerManager();
			$username=UserName;
			$password=Password;	
			$this->powerManager->login($password, $username);
		}
		//执行每个测试后退出系统
		function tearDown(){
			$this->powerManager->logout();
		}
		/**
		 * 测试修改权限描述信息
		 */
		function testChangeAuthorityInfo(){
			$authorityName=AuthorityName;
			$note="这是在单元测试里面测试修改一个测试权限的说明信息";
			
			$affectRow=$this->powerManager->changeAuthorityInfo($authorityName, $note);
			//如果已经进行过单元测试，那么数据库中的权限信息已经修改了，就不会再发生更新
			$this->assertTrue(is_numeric($affectRow) && $affectRow>=0);
		}
		
		/**
		 * 测试加载权限信息
		 */
		function testLoadAuthorityInfo(){
			$authorities=$this->powerManager->loadAuthorityInfo();
			//数据库中存在的权限信息记录应该是大于0的
			$this->assertTrue(is_array($authorities) && count($authorities)>0);
		}
	}
?>