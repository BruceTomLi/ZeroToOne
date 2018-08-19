<?php
	require_once(__DIR__."/User.php");
	require_once(__DIR__."/../classes/MysqlPdo.php");
	
	/**
	 * 这个“系统管理员”类主要用来管理系统设置信息，比如每天用户可以写的问题数量，话题数量等等
	 */
	class SystemManager extends User{
		/**
		 * 加载系统设置信息
		 */
		function loadSystemSettingInfo(){
			global $pdo;
			$sql="select * from view_systemsetting";
			$systemSetting=$pdo->getQueryResult($sql);
			return $systemSetting;
		}
		
		/**
		 * 修改系统设置
		 */
		function changeSystemSettingInfo($maxQuestion=20,$maxTopic=20,$maxArticle=20,
					$maxComment=100,$maxFindPassword=5,$maxEmailCount=50){
			global $pdo;
			$paraArr=array(":maxQuestion"=>$maxQuestion,":maxTopic"=>$maxTopic,":maxArticle"=>$maxArticle,
					":maxComment"=>$maxComment,":maxFindPassword"=>$maxFindPassword,":maxEmailCount"=>$maxEmailCount);
			$outputArr=array("@outUpdateRow");//如果存储过程中有多个输出值，可以在数组中相应增加其参数名称
			$sql="call pro_UpdateSomeSystemSetting(:maxQuestion,:maxTopic,:maxArticle,:maxComment,:maxFindPassword,:maxEmailCount,@outUpdateRow);";
			
			$changeArr=$pdo->getQueryResultForStoredProceduresOutput($sql,$outputArr,$paraArr);
			// print_r($changeArr);//测试的时候为了观察情况才打印的
			$changeRow=$changeArr[0]["@outUpdateRow"]??0;
			return $changeRow;
		}
		
	}
?>