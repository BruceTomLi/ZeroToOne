<?php
	require_once(__DIR__.'/../classes/SessionDBC.php');
	require_once(__DIR__.'/../Model/User.php');
	$page=$_REQUEST['page']??1;
	$user=new User();
	$hasQuestionAuthority=$user->hasAuthority(CommenUser);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>问题页</title>
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="../bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css">	
	<script src="../js/jquery-1.9.1.js"></script>
	<script src="../bootstrap/js/bootstrap.min.js"></script>
	<script src="../js/MyPager.js"></script>
	<script src="../js/question.js"></script>
	<link href="../css/question.css" rel="stylesheet" type="text/css">
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
						<h3 class="pull-left" id="queryDivTitle" style="font-weight: normal;width:200px;">问题</h3>
						<?php 
							if($hasQuestionAuthority){
								echo "<span style='position:relative;top:20px;left:-100px;'><a target='_blank' href='../manage/selfQuestion.php'>我要提问</a></span>";
							}
						?>
						<ul class="nav nav-tabs pull-right">
							<li class='active' id="latestLi">
								<a href="#" onclick="getAllQuestion()">最新</a>
							</li>
							<li id="mostHotLi">
								<a href="#" onclick="getTenHotQuestions()">热门</a>
							</li>
							<li id="recommendLi">
								<a href="#" onclick="recommendQuestionsByJob()">推荐</a>
							</li>	
							<li id="waitReplyLi">
								<a href="#" onclick="getWaitReplyQuestions()">等待回复</a>
							</li>
						</ul>
						<table class="table questionTable">								
							<tbody>
								<tr>
									<td>
										<img src="../img/boy.gif">
									</td>
									<td>
										<p><a href="questionDetails.php">Ghost 0.7.0 中文版正式发布</a></p>
										<p><span>分类</span>• 幸福快乐滴lion 回复了问题 • 4 人关注 • 4 个回复 • 1600 次浏览 • 23 小时前</p>
									</td>
									<td>
										<p>贡献</p>
										<p><img src="../img/boy.gif"></p>
									</td>
								</tr>
								<tr>
									<td>
										<img src="../img/boy.gif">
									</td>
									<td>
										<p><a href="questionDetails.php">Ghost 0.7.0 中文版正式发布</a></p>
										<p><span>分类</span>• 幸福快乐滴lion 回复了问题 • 4 人关注 • 4 个回复 • 1600 次浏览 • 23 小时前</p>
									</td>
									<td>
										<p>贡献</p>
										<p><img src="../img/boy.gif"></p>
									</td>
								</tr>
								<tr>
									<td>
										<img src="../img/boy.gif">
									</td>
									<td>
										<p><a href="questionDetails.php">Ghost 0.7.0 中文版正式发布</a></p>
										<p><span>分类</span>• 幸福快乐滴lion 回复了问题 • 4 人关注 • 4 个回复 • 1600 次浏览 • 23 小时前</p>
									</td>
									<td>
										<p>贡献</p>
										<p><img src="../img/boy.gif"></p>
									</td>
								</tr>
								<tr>
									<td>
										<img src="../img/boy.gif">
									</td>
									<td>
										<p><a href="questionDetails.php">Ghost 0.7.0 中文版正式发布</a></p>
										<p><span>分类</span>• 幸福快乐滴lion 回复了问题 • 4 人关注 • 4 个回复 • 1600 次浏览 • 23 小时前</p>
									</td>
									<td>
										<p>贡献</p>
										<p><img src="../img/boy.gif"></p>
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
						<?php include(__DIR__."/../View/questionRightNav.php"); ?>
					</div>
				</div>	
				
				<div class="row-fluid searchResultDiv hide">
					<div class="span9">
						<ul class="nav nav-tabs pull-right">
							<li>
								<button class="btn-link" id="returnListBtn">返回问题列表</button>
							</li>
						</ul>
						<?php include(__DIR__."/../View/queryedDiv.php"); ?>
					</div>
					<div class="span3">
						<?php include(__DIR__."/../View/questionRightNav.php"); ?>
					</div>
				</div>
				
				<div class="row-fluid detailsDiv">
					<div class="span9">
						<ul class="nav nav-tabs pull-right">
							<li>
								<button class="btn-link" id="returnListBtn">返回问题列表</button>
							</li>
						</ul>					
					</div>
					
					<div class="span3">
						<?php include(__DIR__."/../View/questionRightNav.php"); ?>
					</div>
				</div>		
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12" id="questionFooter">
				<?php include(__DIR__."/../View/questionFooter.php"); ?>
			</div>
		</div>
		
		<?php include(__DIR__."/../View/modals.php"); ?>
	</div>
</body>
</html>
