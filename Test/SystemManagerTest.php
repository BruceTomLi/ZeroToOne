<?php
	require_once(__DIR__."/../Model/SystemManager.php");
	require_once("TestData.php");
	use PHPUnit\Framework\TestCase;
	class SystemManagerTest extends TestCase{
		/**
		 * 下面的代码测试向数据库中添加用户
		 */
		private $systemManager;
		
		function setUp(){
			$this->systemManager=new SystemManager();
			$username=UserName;
			$password=Password;	
			$this->systemManager->login($password, $username);
		}
		//执行每个测试后退出系统
		function tearDown(){
			$this->systemManager->logout();
		}
		
		/**
		 * 测试加载系统设置信息
		 */
		function testLoadSystemSettingInfo(){
			$systemSetting=$this->systemManager->loadSystemSettingInfo();
			$this->assertTrue(is_array($systemSetting) && count($systemSetting)>0);
		}
		/**
		 * 测试修改系统设置信息
		 */
		function testChangeSystemSettingInfo(){
			$maxQuestion='20';
			$maxTopic='20';
			$maxArticle='20';
			$maxComment='20';
			$maxFindPassword='20';
			$maxVisitPerMinute='20';
			$changeRow=$this->systemManager->changeSystemSettingInfo($maxQuestion,$maxTopic,$maxArticle,
					$maxComment,$maxFindPassword,$maxVisitPerMinute);
			$this->assertTrue(is_numeric($changeRow) && $changeRow>0);
		}		
	}
?>