<!DOCTYPE html>
<html style="height: 100%">
	<head>
		<meta charset="UTF-8">
		<title>系统注册</title>
		<link rel="Shortcut Icon" href="img/logo_ico.gif" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<link href="./bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
		<link href="./bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css">
		<link href="css/register.css" rel="stylesheet" type="text/css">
		<script src="js/jquery-1.9.1.js"></script>
		<script src="./bootstrap/js/bootstrap.min.js"></script>
		<script src="js/register.js"></script>
	</head>
	<body style="height: 100%">
		<div class="container registerBackground">
			<div class="row-fluid">
				<div class="span12">
					<div class="register">
						<div class="row-fluid ">
							<div class="form-horizontal registerForm">
								<div class="formTitle">
									<legend><img src="img/logo.gif" />注册</legend>
								</div>
							  	<div class="control-group">
							    	<div class="controls">
								      	<input type="text" id="inputUsername" placeholder="用户名">
								      	<span id="userNameChk"></span>
								    </div>
							  	</div>
							  	<div class="control-group">
							    	<div class="controls">
							      		<input type="text" id="inputEmail" placeholder="邮箱">
							      		<span id="emailChk"></span>
							    	</div>
							  	</div>
								<div class="control-group">
								    <div class="controls">
								    	<input type="password" id="inputPassword" placeholder="密码(推荐数字,字母,符号组合)">
								    	<span id="passwordChk"></span>
								    </div>
								</div>
							  	<div class="moreInfoDiv">
							  		<hr>
							  		<span class="moreInfo">更多资料</span>	
							  	</div>
							  	<div class="moreForm">
								    <div class="control-group">
								    	<label class="control-label" for="inputSex">性别</label>
								    	<div class="controls selectSex">
								    		<label class="radio inline">
											  <input type="radio" name="sexRadios" id="optionsRadios1" value="man" checked>
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
									    		<option value="">--</option>
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
								    	<div class="controls">
								      		<input type="text" id="inputOneWord" placeholder="一句话介绍">
								    	</div>
								  	</div>
							  	</div>
							  	<div class="control-group">
							    	<div class="controls">
							      		<input class="input-small" type="text" id="inputValcode" placeholder="验证码">
							      		<img id="valcodeId" src="" alt="请输入验证码"></img>
							      		<input id="valcodeValue" type="hidden" value="" />
							      		<span id="valcodeChk"></span>
							    	</div>
							  	</div>
							  <div class="control-group">
							    <div class="controls">
							      <label class="checkbox">
							        <input type="checkbox" id="isAgree"> 我同意
							        <button class="btn-link" onclick="showUserProtocol()">用户协议</button>
							        <span id="agreeChk"></span>
							      </label>				     
							      <a href="login.php">已有账号？</a> 
							    </div>
							  </div>
							  <div class="control-group">
							    <div class="controls">
							      	<button type="submit" class="btn btn-success" id="registerBtn">注册</button>
							      	<!--不使用在前台插入标记的方式实现数据检查-->
							      	<!--<input type="hidden" id="isNameOk" value="false"/>
							      	<input type="hidden" id="isEmailOk" value="false"/>
							      	<input type="hidden" id="isPwdOk" value="false"/>-->
							      	<span id="regChk"></span>
							    </div>
							  </div>
							</div>
						</div>
						
						<!--暂时不开发第三方账号登录功能-->
						<!--<div class="row-fluid registerFooter">
							<div class="span12">
								<div class="otherLogin">
									<p>使用第三方账号直接登录</p>
									<p><a href="#"><img src="img/qq-login.png"></a>
									<a href="#"><img src="img/weibo-login.png"></a></p>
								</div>
							</div>
						</div>-->
					</div>					
				
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<?php include("View/loginRegisterFooter.php"); ?>
				</div>
			</div>
			
			<!--模态窗体，显示一个对话框-->
			<div id="dialogModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			 	<div class="modal-header">
			    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			    	<h3 id="myModalLabel">用户协议</h3>
			  	</div>
			  	<div class="modal-body">
			    	<p>当您申请用户时，表示您已经同意遵守本规章。</p>
			    	
						<p>欢迎您加入本站点参与交流和讨论，本站点为博客知识分享平台，为维护网上公共秩序和社会稳定，请您自觉遵守以下条款：</p>
					
						<p>一、不得利用本站危害国家安全、泄露国家秘密，不得侵犯国家社会集体的和公民的合法权益，不得利用本站制作、复制和传播下列信息：</p>
						　<p>（一）煽动抗拒、破坏宪法和法律、行政法规实施的；</p>
						　<p>（二）煽动颠覆国家政权，推翻社会主义制度的；</p>
						　<p>（三）煽动分裂国家、破坏国家统一的；</p>
						　<p>（四）煽动民族仇恨、民族歧视，破坏民族团结的；</p>
						　<p>（五）捏造或者歪曲事实，散布谣言，扰乱社会秩序的；</p>
						　<p>（六）宣扬封建迷信、淫秽、色情、赌博、暴力、凶杀、恐怖、教唆犯罪的；</p>
						　<p>（七）公然侮辱他人或者捏造事实诽谤他人的，或者进行其他恶意攻击的；</p>
						　<p>（八）损害国家机关信誉的；</p>
						　<p>（九）其他违反宪法和法律行政法规的；</p>
						　<p>（十）进行商业广告行为的。</p>
						
						<p>二、互相尊重，对自己的言论和行为负责。</p>
						<p>三、禁止在申请用户时使用相关本站的词汇，或是带有侮辱、毁谤、造谣类的或是有其含义的各种语言进行注册用户，否则我们会将其删除。</p>
						<p>四、禁止以任何方式对本站进行各种破坏行为。</p>
						<p>五、如果您有违反国家相关法律法规的行为，本站概不负责，您的登录信息均被记录无疑，必要时，我们会向相关的国家管理部门提供此类信息。</p>
			 	</div>
			  	<div class="modal-footer">
			    	<button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
			    	<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">我知道了</button>
			  	</div>
			</div>
			<!--模态窗体，显示一个对话框-->
			<div id="doingRegisterModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			  	<div class="modal-body">
			    	<p>正在提交注册请求，请稍等...</p>
			   </div>
			</div>
		</div>
	</body>
</html>


