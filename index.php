<?php
	$page=$_REQUEST['page']??1;//接收页数信息，以便于分页显示
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>系统主页</title>
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<link href="./bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
		<link href="./bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css">
		<link href="css/index.css" rel="stylesheet" type="text/css">
		<script src="js/jquery-1.9.1.js"></script>
		<script src="./bootstrap/js/bootstrap.min.js"></script>
		<script src="js/MyPager.js"></script>
		<script src="js/index.js"></script>
	</head>
	<body>		
		<!--存放页数信息，以便于分页显示-->
		<div>
			<input type="hidden" id="pageHidden" value="<?php echo $page; ?>"/>
		</div>
		<div class="container-fluid">
			<header id="header">
				<?php include("View/header.php"); ?>
			</header>
			<!--下面是页面主体部分-->
			<div class="row-fluid mainContent">
				<div class="span12">
					<div class="row-fluid">
						<div class="span8">
							<div  id="articleDetail">
								<div class="hero-unit">
									<h1 class="artilceTitle">
										示例信息
									</h1>
									<span>作者：李云天 • 2018年7月17日</span>
									<p>
										如果你看到了这句话，说明系统没有正常加载文章信息
									</p>
									<p>
										<button  class="btn btn-primary defaultBtn">阅读全文</button>
									</p>
								</div>
								<div class="hero-unit">
									<h1 class="artilceTitle">
										示例信息
									</h1>
									<span>作者：李云天 • 2018年7月17日</span>
									<p>
										如果你看到了这句话，说明系统没有正常加载文章信息
									</p>
									<p>
										<button  class="btn btn-primary defaultBtn">阅读全文</button>
									</p>
								</div>
								<div class="hero-unit">
									<h1 class="artilceTitle">
										示例信息
									</h1>
									<span>作者：李云天 • 2018年7月17日</span>
									<p>
										如果你看到了这句话，说明系统没有正常加载文章信息
									</p>
									<p>
										<button  class="btn btn-primary defaultBtn">阅读全文</button>
									</p>
								</div>
							</div>
							
							<div class="pagination" id="paginationDiv">
								<ul>
									<li>
										<a href="#">上一页</a>
									</li>
									<li>
										<a href="#">1</a>
									</li>
									<li>
										<a href="#">2</a>
									</li>
									<li>
										<a href="#">3</a>
									</li>
									<li>
										<a href="#">4</a>
									</li>
									<li>
										<a href="#">5</a>
									</li>
									<li>
										<a href="#">下一页</a>
									</li>
								</ul>
							</div>
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
				
			<?php include(__DIR__."/View/modals.php"); ?>
		</div>			
	</body>
</html>
