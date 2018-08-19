<?php
	require_once(__DIR__.'/../classes/SessionDBC.php');
	require_once(__DIR__.'/../Model/User.php');
	$page=$_REQUEST['page']??1;
	$user=new User();
	$hasTopicAuthority=$user->hasAuthority(CommenUser);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>话题页</title>
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
		<link href="../bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css">
		<link href="../css/topic.css" rel="stylesheet" type="text/css">
		<script src="../js/jquery-1.9.1.js"></script>
		<script src="../js/MyPager.js"></script>
		<script src="../bootstrap/js/bootstrap.min.js"></script>
		<script src="../js/topic.js"></script>
	</head>
	<body>
		<!--存放页数信息，以便于分页显示-->
		<div>
			<input type="hidden" id="pageHidden" value="<?php echo $page; ?>"/>
		</div>
		<div class="container-fluid">
			<div class="row-fluid">
				<div class="span12" id="questionHeader">
					<?php include(__DIR__."/../View/questionHeader.php"); ?>
				</div>
			</div>
			<div class="row-fluid contentDiv">
				<div class="span12 mainContent">
					<div class="row-fluid queryDiv">
						<div class="span9">
							<h3 class="pull-left" style="font-weight: normal;width:200px;">热门话题</h3>
							<?php 
								if($hasTopicAuthority){
									echo "<span style='position:relative;top:20px;left:-75px;'><a target='_blank' href='../manage/selfTopic.php'>我要建话题</a></span>";
								}
							?>
							<!--下面是显示不同时间段的话题，暂不开发该功能-->
							<!--<ul class="nav nav-tabs pull-right">
								<li>
									<a href="#">全部</a>
								</li>
								<li>
									<a href="#">7天</a>
								</li>
								<li>
									<a href="#">30天</a>
								</li>
							</ul>-->
							<table class="table topicTable">								
								<tbody>
									<tr>
										<td>
											<img src="../img/topic.png">
										</td>
										<td>
											<p><span class="label label-info">Ghost</span></p>
											<p>7 个讨论 2 个关注</p>
											<p>7 天新增 1 个讨论, 30 天新增 1 个讨论</p>
										</td>
										<td>
											<img src="../img/topic.png">
										</td>
										<td>
											<p><span class="label label-info">Ghost</span></p>
											<p>7 个讨论 2 个关注</p>
											<p>7 天新增 1 个讨论, 30 天新增 1 个讨论</p>
										</td>
									</tr>
									<tr>
										<td>
											<img src="../img/topic.png">
										</td>
										<td>
											<p><span class="label label-info">Ghost</span></p>
											<p>7 个讨论 2 个关注</p>
											<p>7 天新增 1 个讨论, 30 天新增 1 个讨论</p>
										</td>
										<td>
											<img src="../img/topic.png">
										</td>
										<td>
											<p><span class="label label-info">Ghost</span></p>
											<p>7 个讨论 2 个关注</p>
											<p>7 天新增 1 个讨论, 30 天新增 1 个讨论</p>
										</td>
									</tr>									
								</tbody>
							</table>
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
						<div class="span3 right-nav">
							<?php include(__DIR__."/../View/topicRightNav.php"); ?>
						</div>
					</div>
					
					<div class="row-fluid searchResultDiv hide">
						<div class="span9">
							<ul class="nav nav-tabs pull-right">
								<li>
									<button class="btn-link" id="returnListBtn">返回话题列表</button>
								</li>
							</ul>
							<?php include(__DIR__."/../View/queryedDiv.php"); ?>
						</div>
						<div class="span3">
							<?php include(__DIR__."/../View/topicRightNav.php"); ?>
						</div>
					</div>
				</div>								
			</div>
			
			<div class="row-fluid">
				<div class="span12" id="questionFooter">
					<?php include(__DIR__."/../View/questionFooter.php"); ?>
				</div>
			</div>
		</div>
		
		<?php include(__DIR__."/../View/modals.php"); ?>
	</body>
</html>
