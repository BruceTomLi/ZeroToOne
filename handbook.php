<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>快捷手册</title>
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<link href="./bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
		<link href="./bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" type="text/css" href="css/handbook.css" />
		<script src="js/jquery-1.9.1.js"></script>
		<script src="./bootstrap/js/bootstrap.min.js"></script>
	</head>
	<body>
		<div class="container-fluid">
			<header id="header">
				<?php include("View/header.php"); ?>
			</header>
		
			<div class="row-fluid mainContent">
				<div class="span12">
					<div class="row-fluid">
						<div class="span8" id="articleContent">
							<article class="post page">								
							    <section class="post-content">
							    	<h4>完善个人信息</h4>
							    	<p>
							    		0与1网站提供快捷的注册方式，只需要用户名和邮箱即可，但是为了更好的用户体验，你可以在注册的时候填写比较完整的个人信息。							    	
							    	</p>
							    	<p>
							    		你也可以在简单注册之后，在个人“设置”页面完善个人信息，并上传自己的头像。设置你的头像，头像将会在别人查看你的信息的时候显示，
							    		也会在问题和话题的评价列表中以迷你图显示。		    	
							    	</p>
							    	<p>
							    		设置你的行业信息，我们将会为你推荐论坛中与你的行业相关的问题和话题。	    	
							    	</p>
								</section>
								<hr class="contentHr">
								<section>  
								    <h4>搜索感兴趣的问题，话题或人</h4>
							    	<p>
							    		我们为你提供了同时检索系统中的问题，话题或人的检索方式，但是只有登录系统的用户可以使用，赶紧注册登录试试吧 	
							    	</p>
								</section>
								<hr class="contentHr">
								<section>  
								    <h4>问问题或者创建话题</h4>
							    	<p>
							    		系统中创建问题的方式有两种，一种是在查看文章的时候根据该文章创建问题，另一种是创建一个新问题。创建话题方式和创建问题方式相同。
							    	</p>
							    	<p>
							    		话题中可以使用富文本，包括不同样式，不同大小的字体，超链接，图片等，但是为了防止xss攻击，系统不允许使用某些标签或属性值。
							    	</p>
							    	<p>
							    		由于系统资源有限，允许用户在提问题的时候最多上传5张图片，而且每张图片需要小于200KB，字数少于1000字。
							    	</p>
							    	<p>
							    		对于问题和话题的评论同样涉及到防止xss攻击，所以在评论和回复中只能使用纯文本。
							    	</p>
								</section>
								<hr class="contentHr">
								<section>  
								    <h4>关注问题，话题，人</h4>
							    	<p>
							    		你可以在系统中对自己感兴趣的问题，话题或者人进行关注。
							    	</p>
							    	<p>
							    		如果某些问题，话题中的信息对你有帮助，你在关注之后可以很方便地在个人设置中查找到。
							    	</p>
							    	<p>
							    		关注某个人之后，还能够看到TA的个性图谱，赶紧试试吧
							    	</p>
								</section>
								<hr class="contentHr">
								<section>  
								    <h4>忘记密码怎么办？</h4>
							    	<p>
							    		注册之后，请妥善保管密码。为了方便用户进行注册和使用，我们暂时没有实行邮箱激活账户以及邮箱找回密码的做法，
							    		但如果你还是不慎丢失了密码，请与网站管理员联系，他会根据你的个人信息确认你的身份，并帮你重置密码。
							    	</p>
								</section>
								<hr class="contentHr">
								<section>  
								    <h4>我也想写作文和发布作文怎么办？</h4>
							    	<p>
							    		系统默认不给用户开放写作和发布作品功能，这是出于网站服务器资源问题的考虑。如果你想使用该功能请与网站管理员联系，
							    		向他申请系统的作者角色。
							    	</p>
							    	<p>
							    		系统的写作功能类似于博客，作者可以写文章，并选择发布或者不发布，同时作者可以将其作为自己的在线个人知识库。
							    		与使用文件管理自己的学习笔记不同，个人知识库可以运用数据库检索自己写的所有文章并得到相关结果。
							    	</p>
								</section>
								<hr class="contentHr">
								<section>  
								    <h4>如果你是网站运营者，你该做哪些事？</h4>
							    	<p>
							    		系统设置了运营者的角色，运营者可以查看与网站相关的统计信息，比如系统用户的男女比例及人数，不同行业的人数，问题数等等。
							    	</p>
							    	<p>
							    		运营者还应当关注系统最新功能的变化，及时在系统中发布通知，并且删除久的通知。
							    	</p>
								</section>
								<hr class="contentHr">
								<section>  
								    <h4>网站的其他</h4>
							    	<p>
							    		系统还有一些其他角色，但是一般不会赋予普通用户，如果你对此感兴趣，可以向网站管理员联系并了解。
							    	</p>
							    	<p>
							    		由于其他的角色和功能普通用户基本不接触，在此不再详述。
							    	</p>
								</section>
										
							</article>
						</div>
						<div class="span4"  id="rightNav">
							<?php include("View/rightNav.php"); ?>
						</div>	
					</div>
				</div>
			</div>
			
			<footer id="footer">
				<?php include("View/footer.php"); ?>
			</footer>	
		</div>
	</body>
</html>
