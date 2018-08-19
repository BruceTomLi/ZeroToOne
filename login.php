<!DOCTYPE html>
<html style="height: 100%">
	<head>
		<meta charset="UTF-8">
		<title>系统登录</title>
		<link rel="Shortcut Icon" href="img/logo_ico.gif" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<link href="./bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
		<link href="./bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css">
		<link href="css/login.css" rel="stylesheet" type="text/css">
		<script src="js/jquery-1.9.1.js"></script>
		<script src="./bootstrap/js/bootstrap.min.js"></script>
		<script src="js/login.js"></script>
	</head>
	<body style="height: 100%">
		<div class="container loginBackground">
			<div class="row-fluid">
				<div class="span12">
					<div class="login">
						<div class="row-fluid loginContent">
							<div class="form">
								<legend><img src="img/logo.gif" />登录</legend>
							  <div class="control-group">
							    <label class="control-label" for="inputAccount">账号</label>
							    <div class="controls">
							      <input type="text" id="inputAccount" placeholder="邮箱/用户名">
							    </div>
							  </div>
							  <div class="control-group">
							    <label class="control-label" for="inputPassword">密码</label>
							    <div class="controls">
							      <input type="password" id="inputPassword" placeholder="密码">
							    </div>
							  </div>
							  <div class="control-group">
							    <div class="controls">
							        	<!--<input type="checkbox"><span>记住我</span>&nbsp;&nbsp;-->
							        <p>
							        	<a href="#" onclick="showFindPwdDialog()">忘记密码</a>&nbsp;&nbsp;
							        	<a href="index.php">游客访问</a>
							        </p>
							      	<button type="submit" class="btn btn-success" id="loginBtn">登录</button>
							    </div>
							  </div>
							</div>
							<!--<div class="otherLogin">
								<div class="form">
									<legend>第三方账号登录</legend>
								  <div class="control-group">
								    <div class="controls">
								     	<a href="#"><img src="img/qq-login.png"></a>
								    </div>
								  </div>
								  <div class="control-group">
								    <div class="controls">
								      <a href="#"><img src="img/weibo-login.png"></a>
								    </div>
								  </div>
								</div>
							</div>-->
						</div>
						<div class="row-fluid loginFooter">
							<div class="span12">
								<span>还没有账号？</span>&nbsp;								
								<a href="register.php">立即注册</a>&nbsp;
								<a href="forum/question.php">回答阅读</a>&nbsp;
							</div>
						</div>
					</div>					
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<?php include("View/loginRegisterFooter.php"); ?>
				</div>
			</div>
			
			<!--模态窗体，显示一个对话框-->
			<div id="findPwdModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			 	<div class="modal-header">
			    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			    	<h3 id="myModalLabel">找回密码</h3>
			  	</div>
			  	<div class="modal-body">
			    	<p>请输入注册时使用的邮箱:</p>
			    	<p><input type="text" id="inputEmail" /></p>			    	
			 	</div>
			  	<div class="modal-footer">
			    	<button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
			    	<button id="findPwdBtn" class="btn btn-primary" onclick="findPassword()">找回密码</button>
			  	</div>
			</div>
		</div>
	</body>
</html>
