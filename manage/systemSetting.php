<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>管理系统设置</title>
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">			
		<script src="../js/jquery-1.9.1.js"></script>
		<script src="../bootstrap/js/bootstrap.min.js"></script>
		<link href="../css/manage.css" rel="stylesheet" type="text/css">
		<link href="../css/manageSelf.css" rel="stylesheet" type="text/css">
		<script src="../js/manage.js"></script>		
		<script src="../js/systemSetting.js"></script>
	</head>
	<body>
		<div class="container-fluid">
			<header id="manageHeader">
				<?php include(__DIR__."/../View/manageHeader.php"); ?>
			</header>
			<!--下面是页面主体部分-->
			<?php
				require_once("../Controller/SystemSettingController.php");
				$systemSettingController=new SystemSettingController();
				$systemSetting=$systemSettingController->loadSystemSettingInfo();
				$systemSetting=json_decode($systemSetting,true);
				//获取表中的信息
				$maxQuestion=$systemSetting['systemSetting'][0]['maxQuestion'];
				$maxTopic=$systemSetting['systemSetting'][0]['maxTopic'];
				$maxArticle=$systemSetting['systemSetting'][0]['maxArticle'];
				$maxComment=$systemSetting['systemSetting'][0]['maxComment'];
				$maxFindPassword=$systemSetting['systemSetting'][0]['maxFindPassword'];
				$maxEmailCount=$systemSetting['systemSetting'][0]['maxEmailCount'];
			?>
			<div class="row-fluid queryDiv">
				<div class="span12 mainContent">
					<div class="row-fluid ">
						<div class="form-horizontal registerForm">
							<div class="formTitle">
								<legend>系统设置</legend>
							</div>
						  	<div class="control-group">
						  		<label class="control-label" for="inputMaxQuestion">每日最大提问数量</label>
						    	<div class="controls">
							      	<input type="text" id="inputMaxQuestion" placeholder="每日最大提问数量" value="<?php echo $maxQuestion; ?>">
							    </div>
						  	</div>
						  	<div class="control-group">
						  		<label class="control-label" for="inputMaxTopic">每日最大创建话题数量</label>
						    	<div class="controls">
						      		<input type="text" id="inputMaxTopic" placeholder="每日最大创建话题数量" value="<?php echo $maxTopic; ?>">
						    	</div>
						  	</div>
						  	
						  	<div class="control-group">
						  		<label class="control-label" for="inputMaxArticle">每日最大写作数量</label>
						    	<div class="controls">
							      	<input type="text" id="inputMaxArticle" placeholder="每日最大写作数量" value="<?php echo $maxArticle; ?>">
							    </div>
						  	</div>
						  	<div class="control-group">
						  		<label class="control-label" for="inputMaxComment">每日用户最大评论数量</label>
						    	<div class="controls">
						      		<input type="text" id="inputMaxComment" placeholder="每日用户最大评论数量" value="<?php echo $maxComment; ?>">
						    	</div>
						  	</div>
						  	
						  	<div class="control-group">
						  		<label class="control-label" for="inputMaxFindPwd">每日用户找回密码数量</label>
						    	<div class="controls">
							      	<input type="text" id="inputMaxFindPwd" placeholder="每日用户找回密码数量" value="<?php echo $maxFindPassword; ?>">
							    </div>
						  	</div>
						  	<div class="control-group">
						  		<label class="control-label" for="inputMaxEmailCount">每日邮件总量</label>
						    	<div class="controls">
						      		<input type="text" id="inputMaxEmailCount" placeholder="每日邮件总量" value="<?php echo $maxEmailCount; ?>">
						    	</div>
						  	</div>							
						  						  	
							<div class="control-group">
							    <div class="controls">
							      	<button class="btn btn-info" id="changeSystemSettingBtn" onclick="changeSystemSettingInfo()">保存修改</button>
							    </div>
							</div>
						</div>
						<hr>	
					</div>						
				</div>
			</div>
			
			<footer id="manageFooter">
				<?php include(__DIR__."/../View/manageFooter.php"); ?>
			</footer>	
				
		</div>		
	</body>
</html>
