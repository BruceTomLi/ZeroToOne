<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>网站信息统计</title>
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">			
		<script src="../js/jquery-1.9.1.js"></script>
		<script src="../bootstrap/js/bootstrap.min.js"></script>
		<link href="../css/manage.css" rel="stylesheet" type="text/css">
		<link href="../css/manageSelf.css" rel="stylesheet" type="text/css">
		<script src="../js/manage.js"></script>	
		<script src="../js/echarts.min.js"></script>	
		<script src="../js/operate.js"></script>
	</head>
	<body>
		<div class="container-fluid">
			<header id="manageHeader">
				<?php include(__DIR__."/../View/manageHeader.php"); ?>
			</header>
			<!--下面是页面主体部分-->
			<div class="row-fluid queryDiv">
				<div class="span12 mainContent">
					<div class="span6 manWomanCountDiv" style="height:500px;margin: 0;text-align: center;">
						<h4>网站男女人数信息统计</h4>
						<hr>
						<div id="manWomanCountContainer" style="height: 75%"></div>
					</div>	
					<div class="span6 kindsOfJobUserCountDiv" style="height:500px;margin: 0;text-align: center;">
						<h4>不同种类工作人员信息统计</h4>
						<hr>
						<div id="kindsOfJobUserCountContainer" style="height: 75%"></div>
					</div>	
					<div class="span6 kindsOfQuestionCountDiv" style="height:500px;margin: 0;text-align: center;">
						<h4>不同种类问题信息统计</h4>
						<hr>
						<div id="kindsOfQuestionCountContainer" style="height: 75%"></div>
					</div>	
					<div class="span6 kindsOfTopicCountDiv" style="height:500px;margin: 0;text-align: center;">
						<h4>不同种类话题信息统计</h4>
						<hr>
						<div id="kindsOfTopicCountContainer" style="height: 75%"></div>
					</div>									
				</div>				
			</div>
			
			<footer id="manageFooter">
				<?php include(__DIR__."/../View/manageFooter.php"); ?>
			</footer>	
				
		</div>		
	</body>
</html>
