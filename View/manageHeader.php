<?php
	require_once(__DIR__.'/../classes/SessionDBC.php');
	require_once(__DIR__."/../Controller/SelfSettingController.php");
	require_once(__DIR__."/../Model/User.php");
	$selfSettingController=new SelfSettingController();	
	//记录下用户打开网页时使用的url，在登录之后直接跳转到这个url
	$_SESSION['visitUrl']=$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
	if(!$selfSettingController->isUserLogon()){
		header("Location:../login.php");
		die;
	}
	//创建用户对象，用于判断他的权限
	$user=new User();
	//为了防止csrf攻击，这里使用token
	$_SESSION['token']=$user->createToken()??"thisIsToken";
	$token=$_SESSION['token'];
	
	
?>
<link href="../bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../css/header.css">
<script src="../js/checkUserLogon.js"></script>	
<script>
	var shortcutHtml='<link rel="Shortcut Icon" href="../img/logo_ico.gif" />';
	$('head').append(shortcutHtml);
</script>
<div id="manageHeaderDetails">
	<!--记录token-->
	<input type="hidden" value="<?php echo $token; ?>" id="token" name="token"></input>
	<!--下面是页面菜单部分-->
	<div class="row-fluid">
		<div class="span12">
			<div class="navbar">
				<div class="navbar-inner">
					<div class="container-fluid headContent">
						<a data-target=".navbar-responsive-collapse" data-toggle="collapse" class="btn btn-navbar">
						 	<span class="icon-bar"></span>
						 	<span class="icon-bar"></span>
						 	<span class="icon-bar"></span>
						</a>
						<div class="nav-collapse collapse navbar-responsive-collapse">	
							<div class="pull-left">			
								<ul class="nav navbar-nav" id="menuUl">		
									<!--加载普通用户菜单-->
									<?php if($user->hasAuthority(CommenUser)){ ?>						
										<li><a href="selfSetting.php">我的信息</a></li>
										<li><a href="selfQuestion.php">我的问题</a></li>
										<li><a href="selfTopic.php">我的话题</a></li>
										<li><a href="selfFollow.php">我的关注</a></li>
										<li><a href="selfFans.php">我的粉丝</a></li>
									<?php } ?>
									<!--加载作者菜单-->
									<?php if($user->hasAuthority(WriteArticle)){ ?>
										<li><a href="selfArticle.php">我的文章</a></li>
									<?php } ?>
									<!--加载运营者菜单-->
									<?php if($user->hasAuthority(Operate)){ ?>
										<li><a href="notices.php">公告</a></li>
						      			<li><a href="operate.php">运营</a></li>
									<?php } ?>
									<!--加载问题管理员菜单-->
									<?php if($user->hasAuthority(QuestionManage)){ ?>
										<li><a href="questions.php">问题</a></li>
									<?php } ?>
									
									<!--加载话题管理员菜单-->
									<?php if($user->hasAuthority(TopicManage)){ ?>
										<li><a href="topics.php">话题</a></li>
									<?php } ?>
									<!--加载文章管理员菜单-->
									<?php if($user->hasAuthority(ArticleManage)){ ?>
										<li><a href="article.php">文章</a></li>
									<?php } ?>
										
									<!--加载系统管理员菜单-->
									<?php if($user->hasAuthority(UserManage)){ ?>
										<li><a href="user.php">用户</a></li>
									<?php } ?>
									<?php if($user->hasAuthority(RoleManage)){ ?>
						      			<li><a href="role.php">角色</a></li>
									<?php } ?>
									<?php if($user->hasAuthority(AuthorityManage)){ ?>
						      			<li><a href="authority.php">权限</a></li>
									<?php } ?>
									<?php if($user->hasAuthority(ChangeSystemSetting)){ ?>
						      			<li><a href="systemSetting.php">系统</a></li>
									<?php } ?>
									<!--主页中已经有了关于页面，设置页面中不再使用-->
						      		<!--<li><a href="about.php">关于</a></li>-->		
								</ul>
							</div>	
							<div class="pull-right">
								<ul class="nav navbar-nav inline">
									<li><a href="../forum/question.php" style="color: blue;">返回</a></li>
								</ul>								
							</div>					
						</div>								
					</div>
				</div>						
			</div>
			<!--下面的元素是为了让程序检测到用户已经登录了-->
			<span id="#welcomeInfo"></span>
		</div>
	</div>
</div>
