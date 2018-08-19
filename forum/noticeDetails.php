<?php
	require_once(__DIR__."/../Controller/NoticeManageController.php");
	$noticeManageController=new NoticeManageController();
	$noticeId=$_REQUEST['noticeId']??"";
	$result=$noticeManageController->loadNoticeDetails($noticeId);
	$resultArr=json_decode($result,true);
	$noticeDetails=$resultArr["notice"][0]??"";
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>公告详情页</title>
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
		<link href="../bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css">
		<link href="../css/index.css" rel="stylesheet" type="text/css">
		<script src="../js/jquery-1.9.1.js"></script>
		<script src="../bootstrap/js/bootstrap.min.js"></script>
		<script src="../js/index.js"></script>
		<link href="../css/articleDetails.css" rel="stylesheet" type="text/css">
	</head>
	<body>
		<div class="container-fluid">
			<div class="row-fluid">
				<div class="span12" id="questionHeader">
					<?php include(__DIR__."/../View/questionHeader.php"); ?>
				</div>
			</div>
			<!--下面是页面主体部分-->
			<div class="row-fluid">
				<div class="span12">
					<div class="row-fluid detailsDiv">
						<div class="span9 mainContent">
							<article id="articleDetail">
								<h4 id="detailsTitle"><?php echo $noticeDetails['title']??"未获取到标题"; ?></h4>
								<p id="detailsAuthor">作者：<span><?php echo $noticeDetails['creator']??"未获取到公告发布者"; ?></span></p>
								<section id="detailsContent">
									<?php
										echo $noticeDetails['content']??"未获取到公告内容";
									?>
								</section>
							</article>
						</div>
						<div class="span3 right-nav">
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
				
		</div>			
	</body>
</html>
