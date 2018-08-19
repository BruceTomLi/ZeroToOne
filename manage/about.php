<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>管理权限</title>
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
		<link href="../bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css">		
		<script src="../js/jquery-1.9.1.js"></script>
		<script src="../bootstrap/js/bootstrap.min.js"></script>
		<link href="../css/manage.css" rel="stylesheet" type="text/css">
	</head>
	<body>
		<div class="container-fluid">
			<header id="manageHeader">
				<?php include(__DIR__."/../View/manageHeader.php"); ?>
			</header>
			<!--下面是页面主体部分-->
			<div class="row-fluid queryDiv">
				<div class="span12 mainContent">
					<h3>系统管理简述</h3>
					<p>
						系统管理说明：系统管理页主要是提供给拥有管理权限的用户对系统进行管理。
					</p>
					<p>
						系统管理员：Tom Li
					</p>
					<p>
						系统管理员Email：1401717460@qq.com
					</p>
					<p>
						系统管理员手机：13312345678
					</p>
				</div>
			</div>
			
			<footer id="manageFooter">
				<?php include(__DIR__."/../View/manageFooter.php"); ?>
			</footer>	
				
		</div>		
	</body>
</html>
