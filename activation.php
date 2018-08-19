<?php
	require_once("Model/User.php");
	$userId=$_REQUEST['id']??"";
	$password=$_REQUEST['code']??"";
	if(!empty($userId) && !empty($password)){
		$user=new User();
		$result=$user->activeAccount($userId, $password);
		if($result==1){
			echo "激活账号成功，请<a href='login.php'>登录</a>，3秒后自动跳转";
			header("refresh:3;url=login.php"); 
			// header("Location: login.php");
		}else{
			echo "激活账号失败或者已经激活过了，你可以联系管理员帮你激活账号";
		}
	}else{
		echo "参数不符合规范，无法激活，<a href='index.php'>访问主页</a>";
	}
?>
<script src="js/jquery-1.9.1.js"></script>
<script>
	var shortcutHtml='<link rel="Shortcut Icon" href="img/logo_ico.gif" />';
	$('head').append(shortcutHtml);
</script>