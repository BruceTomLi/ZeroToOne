<?php
	/*文件名：config.php
	 * 创建者：Tom Li
	 * 联系方式：1401717460@qq.com
	 * 最后修改时间：2018/8/18
	 * 
	 * 配置文件的作用：
	 * 1.将站点的设置配置在一个地方
	 * 2.将站点的URI和URL最为持久化数据保存
	 * 3.设置错误处理的方式
	 */
	 
	 #**********************************#
	 #********设置************#
	 
	 //错误将被发送到这个邮箱中
	 $contact_email='1401717460@qq.com';
	 
	 //判定我们当前使用的服务器是在本地服务器上还是在真实的服务器上，为了方便运行测试用例，使用??"localhost"
	 $httpHost=$_SERVER['HTTP_HOST']??"localhost";
	 $host=substr($httpHost,0,5);
	 if(in_array($host, array('local','127.0','192.1'))){
	 	$local=true;
	 }
	 else{
	 	$local=false;
	 }
	 
	 //判定本地文件和站点的URL地址，允许在不同的服务器环境中进行开发
	 if($local){
	 	//在本地的时候允许进行调试
	 	$debug=true;
		
		//定义持久化数据
		define('BASE_URI', 'J:\wamp\www\ZeroToOne');
		define('BASE_URL', 'http://localhost/ZeroToOne/');
		//define('DB','J:\wamp\www\deepLearnPHP\DevelopeWebProject\includes\mysql.inc.php');
		require_once(__DIR__."/../Config/dbTest.php");
	 }
	 else{
	 	define('BASE_URI', '/var/www/ZeroToOne');
		define('BASE_URL', 'http://106.12.110.95/');
		//define('DB','J:\wamp\www\deepLearnPHP\DevelopeWebProject\includes\mysql.inc.php');
		require_once(__DIR__."/../Config/dbProduct.php");
	 }
	 
	 /*
	  * 最重要的设置！
	  * 通过$debug变量来设置错误管理
	  * 为了调试一个特殊的页面，可以将下面的代码添加到该页面中
	  * if($p=='thismodule') debug=true;
	  * require('./includes/config.inc.php');
	  * 要调试一个站点，直接设置如下
	  * debug=true;
	  */
	  
	  //先假设debug是关闭状态的
	  if(!isset($debug)){
	  	$debug=false;
	  }
	  
	  #******设置结束*****#
	  
	  #***********错误管理*********#
	  
	  //创建自定义的错误处理函数
	  function my_error_handler($e_number,$e_message,$e_file,$e_line,$e_vars){
	  	global $debug,$contact_email;
		
		//创建错误处理信息
		$message="An error occurred in script '$e_file' on line $e_line:$e_message";
		
		//将变量信息增加到message变量中,print_r函数的第二个参数设置为true代表不在此处输出结果
		$message.=print_r($e_vars,1);
		
		if($debug){//如果是调试模式，就显示错误
			echo "<div class='error'>$message</div>";
			debug_print_backtrace();
		}
		else{//否则应该将错误信息发送到邮件中
			error_log($message,1,$contact_email);
			//如果错误信息严重，就在页面上显示简单错误信息提示，而对于不影响程序运行的错误不进行提示
			if(($e_number!=E_NOTICE)&&($e_number<2048)){
				echo "<div class='error'>A system error occurred.We apologize for the inconvenience.</div>";
			}			
		}
	  }
	  
	  //使用错误信息处理函数为自定义的函数；使用phpunit测试程序的时候先注释下面这行，不然会一直报错
	  set_error_handler('my_error_handler');
	  #***********错误管理结束*********#
	  
?>