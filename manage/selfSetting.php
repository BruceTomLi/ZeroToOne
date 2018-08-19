<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>管理个人设置</title>
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">			
		<script src="../js/jquery-1.9.1.js"></script>
		<script src="../bootstrap/js/bootstrap.min.js"></script>
		<link href="../css/manage.css" rel="stylesheet" type="text/css">
		<link href="../css/manageSelf.css" rel="stylesheet" type="text/css">
		<script src="../js/manage.js"></script>		
		<script src="../js/SelfSetting.js"></script>
	</head>
	<body>
		<div class="container-fluid">
			<header id="manageHeader">
				<?php include(__DIR__."/../View/manageHeader.php"); ?>
			</header>
			<!--下面是页面主体部分-->
			<div class="row-fluid queryDiv">
				<div class="span12 mainContent">
					<div class="row-fluid ">
						<div class="form-horizontal registerForm">
							<div class="formTitle">
								<legend>编辑我的信息</legend>
							</div>
						  	<div class="control-group">
						  		<label class="control-label" for="inputUsername">用户名</label>
						    	<div class="controls">
							      	<input type="text" id="inputUsername" placeholder="用户名">
							      	<span id="userNameChk"></span>
							    </div>
						  	</div>
						  	<div class="control-group">
						  		<label class="control-label" for="inputEmail">邮箱</label>
						    	<div class="controls">
						      		<input type="text" id="inputEmail" placeholder="邮箱">
						      		<span id="emailChk"></span>
						    	</div>
						  	</div>
							
						  	<div class="moreForm">
							    <div class="control-group">
							    	<label class="control-label" for="inputSex">性别</label>
							    	<div class="controls selectSex">
							    		<label class="radio inline">
										  <input type="radio" name="sexRadios" id="optionsRadios1" value="man">
										  男
										</label>
										<label class="radio inline">
										  <input type="radio" name="sexRadios" id="optionsRadios2" value="woman">
										  女
										</label>
							    	</div>							  		
								</div>
								<div class="control-group">
							  		<label class="control-label" for="inputJob">行业</label>
								    <div class="controls">
								    	<select id="inputJob">
								    		<!--<option value="">--</option>-->
								    	</select>
								    </div>
								</div>
								<div class="control-group">
							  		<label class="control-label" for="inputCity">所在城市</label>
								    <div class="controls">
								    	<select class="input-small" id="province">
								    		<!--<option value="province">请选择省份或直辖市</option>-->
								    	</select>
								    	<select class="input-small" id="city">
								    		<!--<option value="city">请选择城市</option>-->
								    	</select>
								    </div>
								</div>
								<div class="control-group">
									<label class="control-label" for="inputOneWord">一句话介绍</label>
							    	<div class="controls">
							      		<input type="text" id="inputOneWord" placeholder="一句话介绍">
							    	</div>
							  	</div>
						  	</div>
						  	<div class="control-group">
						  		<label class="control-label" for="inputUserRole">用户角色</label>
						    	<div class="controls">
						      		<p id="inputUserRole">这里将会加载用户角色，并且用逗号分隔</p>
						    	</div>
						  	</div>						  	
							<div class="control-group">
							    <div class="controls">
							      	<button class="btn btn-info" id="changeUserBtn">修改信息</button>
							      	<button class="btn-link btn-warning" id="changeHeadingBtn">修改头像</button>
							      	<button class="btn-link btn-warning" id="changePasswordBtn">修改密码</button>
							    </div>
							</div>
						</div>
						<hr>	
					</div>						
				</div>
				
				<div class="span12" id="changeHeadingDiv">
					<div class="row-fluid mainContent">
						<div class="form-horizontal changePwdForm">
							<div class="formTitle">
								<legend>修改我的头像</legend>
							</div>
							<div class="control-group">
						  		<label class="control-label" for="inputUserHeading">用户头像(小于100KB)</label>
						    	<div class="controls">
						      		<input type="file" id="inputUserHeading" name="heading" /><br>
						      		<img id="userHeadingImg" src="../UploadImages/fishing.jpg" />
						    	</div>
						  	</div>
							
							<div class="control-group">
							    <div class="controls">
							    	<button class="btn btn-info" id="submitChangeHeadingBtn">修改</button>
									<button class="btn btn-warning" id="cancelChangeHeadingBtn">取消</button>
							    </div>	
							</div>
						</div>
					</div>
				</div>
				
				<div class="span12" id="changePwdDiv">
					<div class="row-fluid mainContent">
						<div class="form-horizontal changePwdForm">
							<div class="formTitle">
								<legend>修改我的密码</legend>
							</div>
							<div class="control-group">
								<label class="control-label" for="inputOldPassword">旧密码</label>
							    <div class="controls">
							    	<input type="password" id="inputOldPassword" placeholder="旧密码">
							    	<span id="oldPasswordChk"></span>
							    </div>	
							</div>
							<div class="control-group">
								<label class="control-label" for="inputNewPassword">新密码</label>
							    <div class="controls">
							    	<input type="password" id="inputNewPassword" placeholder="新密码">
							    	<span id="newPasswordChk"></span>
							    </div>	
							</div>
							<div class="control-group">
								<label class="control-label" for="inputPasswordAgain">确认密码</label>
							    <div class="controls">
							    	<input type="password" id="inputPasswordAgain" placeholder="确认密码">
							    	<span id="passwordAgainChk"></span>
							    </div>	
							</div>
							<div class="control-group">
							    <div class="controls">
							    	<button class="btn btn-info" id="submitChangePasswordBtn">修改</button>
									<button class="btn btn-warning" id="cancelChangePasswordBtn">取消</button>
									<input type="hidden" id="isPwdOk" value="false"/>
							    </div>	
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<footer id="manageFooter">
				<?php include(__DIR__."/../View/manageFooter.php"); ?>
			</footer>	
				
		</div>		
	</body>
</html>
