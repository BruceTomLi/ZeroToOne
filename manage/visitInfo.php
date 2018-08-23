<?php
	//获取话题页数，用于分页显示
	$page=$_REQUEST['page']??1;
	$keyword=$_REQUEST['keyword']??"";
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>访问者信息</title>
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<!--<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
		<link href="../bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css">		
		<script src="../js/jquery-1.9.1.js"></script>
		<script src="../bootstrap/js/bootstrap.min.js"></script>-->
		<link href="https://cdn.bootcss.com/bootstrap/2.3.2/css/bootstrap.min.css" rel="stylesheet" type="text/css">
		<link href="https://cdn.bootcss.com/bootstrap/2.3.2/css/bootstrap-responsive.min.css" rel="stylesheet">
		<script src="https://cdn.bootcss.com/jquery/2.0.0/jquery.min.js"></script>
		<script src="https://cdn.bootcss.com/bootstrap/2.3.2/js/bootstrap.min.js"></script>
		
		<link href="../css/manage.css" rel="stylesheet" type="text/css">
		<script src="../js/manage.js"></script>
		<script src="../js/MyPager.js"></script>
		<script src="../js/visitInfo.js"></script>
	</head>
	<body>
		<!--存放页数信息，以便于分页显示-->
		<div>
			<input type="hidden" id="pageHidden" value="<?php echo $page; ?>"/>
			<input type="hidden" id="keywordHidden" value="<?php echo $keyword; ?>"/>
		</div>
		<div class="container-fluid">
			<header id="manageHeader">
				<?php include(__DIR__."/../View/manageHeader.php"); ?>
			</header>
			<!--下面是页面主体部分-->
			<div class="row-fluid queryDiv">
				<div class="span12 mainContent">
					<div class="manageMenu">	
						<div class="form-search input-append pull-left">
							<input class="input-medium" type="text" id="keyword" placeholder="访问者名字/IP" /> 
							<button type="submit" class="btn" id="searchVisitInfoBtn" onclick="queryVisitInfo()">查找</button>
						</div>
					</div>
					<div class="selfTableDiv">
						<table class="table" id="visitInfoTable">		
							<thead>
								<tr>
									<th>访问者</th>
									<th>最后访问时间</th>
									<th>IP地址</th>
									<th>访问次数</th>
								</tr>							
							</thead>
							<tbody>
								<tr>
									<td>
										山外山
									</td>
									<td>
										2018/7/9
									</td>
									<td>
										localhost
									</td>
									<td>
										5
									</td>
								</tr>							
							</tbody>
						</table>
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
				
			</div>			
				
			
			<footer id="manageFooter">
				<?php include(__DIR__."/../View/manageFooter.php"); ?>
			</footer>	
				
		</div>		
	</body>
</html>
