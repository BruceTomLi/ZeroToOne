<?php
	require_once("../Controller/PersonController.php");
	// 这个是查看个人信息的显示页面，如果使用ajax的方式加载信息，会让问题变得复杂而且没有必要
	//需要获取用户传过来的用户Id，根据用户Id获取相应的用户信息
	$personController=new PersonController();
	$personalInfo=$personController->getUserBaseInfoByUserId();
	$userIdHidden="";
	if(!empty($personalInfo["personalInfo"])){		
		$username=$personalInfo["personalInfo"][0]["username"];
		$userId=$personalInfo["personalInfo"][0]["userId"]??"";
		$userIdHidden=$userId;
		$userId="'".$userId."'";
		$heading=$personalInfo["personalInfo"][0]['heading'];
		$heading="'".$heading."'";
		// $email=$personalInfo["personalInfo"][0]['email'];
		$oneWord=$personalInfo['personalInfo'][0]['oneWord'];
		$sex=$personalInfo["personalInfo"][0]['sex']=="1"?"男":"女";
	}
	
	//获取用户的登录状态，如果用户没登录，不显示邮箱信息；为防止用户被骚扰，不显示邮箱
	// if(!$personController->isUserLogon()){
		// $email="登录之后可以看到邮箱";
	// }		
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>用户传</title>
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
		<link href="../bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css">
		<script src="../js/jquery-1.9.1.js"></script>
		<script src="../bootstrap/js/bootstrap.min.js"></script>
		<link href="../css/person.css" rel="stylesheet" type="text/css">
		<script src="../js/echarts.min.js"></script>
		<script src="../js/person.js"></script>
	</head>
	<body>
		<!--存放用户Id信息，便于js获取并处理-->
		<div>
			<input type="hidden" id="userIdHidden" value="<?php echo $userIdHidden??""; ?>"/>
		</div>
		<div class="container-fluid">
			<div class="row-fluid">
				<div class="span12" id="questionHeader">
					<?php include(__DIR__."/../View/questionHeader.php"); ?>
				</div>
			</div>
			<div class="row-fluid queryDiv">
				<div class="span12 mainContent">
					<div class="row-fluid">
						<div class="span9 left-content">
							<h3>个人简述</h3>
							<section id="personalResume">
								<!--<p><img src="../UploadImages/sky.jpg" /></p>
								<p>称呼：<span>Tom Li</span></p>
								<p>性别：<span>男</span></p>
								<p>邮箱：<span>1401717460@qq.com</span></p>-->
								<p><img src=<?php echo $heading??""; ?> /></p>
								<p>称呼：<span><?php echo $username??"未获取到名称"; ?></span></p>
								<p>性别：<span><?php echo $sex??"未获取到性别"; ?></span></p>
								<!--为防止用户被骚扰，不显示邮箱-->
								<!--<p>邮箱：<span><?php echo $email??"未获取到邮箱"; ?></span></p>-->
								<p>一句话介绍：<span><?php echo $oneWord??"未获取到一句话介绍"; ?></span></p>
								<?php
									if(!$personController->hasUserFollowedUser()){
								?>
										<p><button class='btn btn-success' id='followTa' onclick='followUser(this)' value=<?php echo $userId??""; ?>>关注Ta</button></p>
								<?php
									}
									else{
								?>										
										<div id="userInfoCountDiv" style="height:320px;margin: 0;text-align: center;">
											<div id="userInfoCountContainer" style="height: 100%"></div>											
										</div>
										<p><button class="btn btn-warning" id="cancelfollowTa" onclick="cancelFollowUser(this)" value=<?php echo $userId??""; ?>>取消关注Ta</button></p>
								<?php
									}
								?>
								
							</section>
							<section id="personalFollow">
								<!--这里可以加载个人关注信息，但是我考虑到个人关注信息也算是个人隐私，不便于随便让别人看到，
								所以暂时不实现这个功能-->
							</section>
						</div>
						<div class="span3 right-nav">
							<?php include(__DIR__."/../View/questionRightNav.php"); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12" id="questionFooter">
					<?php include(__DIR__."/../View/questionFooter.php"); ?>
				</div>
			</div>
		</div>
	</body>
</html>
