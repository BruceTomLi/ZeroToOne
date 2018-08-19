<?php
	//获取话题页数，用于分页显示
	$page=$_REQUEST['page']??1;
	$keyword=$_REQUEST['keyword']??"";
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>管理所有话题</title>
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
		<link href="../bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css">		
		<script src="../js/jquery-1.9.1.js"></script>
		<script src="../bootstrap/js/bootstrap.min.js"></script>
		<link href="../css/manage.css" rel="stylesheet" type="text/css">
		<script src="../js/manage.js"></script>
		<script src="../js/MyPager.js"></script>
		<script src="../js/topics.js"></script>
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
							<input class="input-medium" type="text" id="keyword" placeholder="话题内容" /> 
							<button type="submit" class="btn" id="searchTopicBtn" onclick="searchTopic()">查找</button>
						</div>
						<ul class="nav nav-tabs pull-right">
							<li>
								<button id="hideOrShowAbleBtn" class="btn-link">隐藏/显示删除话题</a>
							</li>
						</ul>
					</div>
					<div class="handleMultiDiv">
						<div class="selectAllOrNotDiv pull-left">
							<label class="checkbox inline">
						      	<input type="checkbox" id="selectAll" onclick="selectAll()"> 全选
						    </label>
						    <label class="checkbox inline">
						      	<input type="checkbox" id="selectReverse" onclick="selectReverse()"> 反选
						    </label>
						</div>
						
					    <div class="enableOrDisableDiv pull-right">					    	
						    <button id="disabledBtn" class="btn btn-warning inline" onclick="disableSelectTopics()">不公开选中话题</button>
							<button id="enabledBtn" class="btn btn-success inline" onclick="enableSelectTopics()">公开选中话题</button>
							<!--<button id="disabledQueryBtn" class="btn btn-warning inline">不公开查询话题</button>-->
							<!--<button id="enabledQueryBtn" class="btn btn-success inline">公开查询话题</button>-->
							<button id="deleteBtn" class="btn btn-danger inline" onclick="deleteSelectTopics()">删除选中话题</button>
							<!--<button id="deleteQueryBtn" class="btn btn-warning inline">删除查询话题</button>-->
						</div>
					</div>
					<div class="selfTableDiv">
						<table class="table" id="allTopicsTable">		
							<thead>
								<tr>
									<th class="forSelectMulti">选择</th>
									<th>提问者</th>
									<th>提问日期</th>
									<th>话题类型</th>
									<th>话题内容</th>
									<th>公开/不公开</th>
									<th>删除</th>
								</tr>							
							</thead>
							<tbody>
								<tr>
									<td class="forSelectMulti">
										<label class="checkbox">
									      	<input type="checkbox">
									    </label>
									</td>
									<td>
										山外山
									</td>
									<td>
										2018/7/9
									</td>
									<td>
										IT类
									</td>
									<td>
										<a target='_blank' href='../forum/topicDetails.php?topicId=话题Id'>软件必须修复所有bug之后才开始发布吗？</a>
									</td>		
									<td>
										<button class="btn btn-warning" value="话题Id">不公开</button>
									</td>
									<td>
										<button class="btn btn-danger" value="话题Id">删除</button>
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
