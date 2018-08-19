<?php
	require_once(__DIR__."/../classes/SessionDBC.php");
	//记录下用户打开网页时使用的url，在登录之后直接跳转到这个url
	$_SESSION['visitUrl']=$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
?>
<link rel="stylesheet" type="text/css" href="../css/questionHeader.css" />
<script src="../js/questionHeader.js"></script>
<script src="../js/checkUserLogon.js"></script>
<script>
	var shortcutHtml='<link rel="Shortcut Icon" href="../img/logo_ico.gif" />';
	$('head').append(shortcutHtml);
</script>
<div  id="questionHeaderDetails">
	<div class="navbar">
		<div class="navbar-inner defaultBootstrapMenu">
			<div class="container-fluid">
				<a data-target=".navbar-responsive-collapse" data-toggle="collapse" class="btn btn-navbar">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a> 
				<!--<a href="#" class="brand">网站名</a>-->
				<div class="nav-collapse collapse navbar-responsive-collapse">
					<div id="searchQuestion" class="pull-left">
						<div class="form-search input-append">
							<input class="input-medium" id="keyword" placeholder="搜索问题、话题或人" type="text" /> 
							<button type="submit" class="btn" onclick="queryUserOrQuestionOrTopic()">查找</button>
						</div>
					</div>
					<div id="menuBtn" class="pull-right">
						<ul class="nav nav-tabs inline questionMenuUl">
							<li>
								<a href="../index.php">首页</a>
							</li>
							<li>
								<a href="question.php">问题</a>
							</li>
							<li>
								<a href="topic.php">话题</a>
							</li>
						</ul>
						<?php
							if(isset($_SESSION['username'])){
								echo "<div class='inline pull-right'><span id='#welcomeInfo'>欢迎你，{$_SESSION['username']}</span>";
								echo "<a class='btn btn-info' href='../manage/selfSetting.php'>设置</a>";
								echo "<button id='logoutBtn' onClick='logout()' class='btn btn-warning'>注销</button></div>";
							}
							else{
								echo "<div class='inline pull-right'><a href='../login.php' class='btn btn-success inline'>登录</a>";
								echo "<a href='../register.php' class='btn btn-info inline'>注册</a></div>";
							}
						?>
					</div>									
				</div>								
			</div>
		</div>		
	</div>		
</div>

