<?php
	// 这个页面会在论坛页，用户个人问题页，问题管理员页中被使用，所以把它抽象出来，避免在三个地方重复
	$questionId=$_REQUEST['questionId']??"";
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>问题详情页</title>
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="../bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css">	
	<script src="../js/jquery-1.9.1.js"></script>
	<script src="../bootstrap/js/bootstrap.min.js"></script>
	<link href="../css/questionDetails.css" rel="stylesheet" type="text/css">
	<script src="../js/questionDetails.js"></script>
</head>
<body>
	<!--将php中获取的问题信息存入隐藏字段，然后让js解析它，避免将大量php的代码解析逻辑写到html页面上-->
	<div>
		<input id="questionIdHidden" type="hidden" value='<?php echo $questionId; ?>'/>
	</div>
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span12" id="questionHeader">
				<?php include(__DIR__."/../View/questionHeader.php"); ?>
			</div>
		</div>
		<div class="row-fluid contentDiv">
			<div class="span12 mainContent">	
				<div class="row-fluid detailsDiv">
					<div class="span9">
						<ul class="nav nav-tabs pull-right">
							<li>
								<a href='question.php'>返回问题列表</a>
							</li>
						</ul>
						<section class="questionDescription">
							<div id="questionDetails">								
								<h4>问题内容</h4>
								<hr>
								<p><span>描述：</span>问题描述</p>
								<p><span>时间：</span><span>2018/7/8</span></p>
								<p>
									<button class="btn-link" id="addCommentBtn" value="问题Id"  onClick='showCommentDiv(this)'>添加评论</button>
									<button class='btn-link' id='addFollowBtn' value='问题Id' onClick='addQuestionFollow(this)'>添加关注</button>							
								</p>
								<input type="hidden" id="questionIdHidden" value="问题Id"  />	
							</div>													
							<div id="addCommentDiv">
								<textarea></textarea><br />
								<button class="btn btn-success" value="" id="submitCommentBtn" onClick='commentQuestion(this)'>评论</button>
								<button class="btn btn-warning" id="cancelAddComment" onClick='cancelAddComment(this)'>取消</button>
							</div>
						</section>	
						<div id="questionAnswers">
							<!--<h4>4个回复</h4>
							<hr>
							<ul>
								<li>
									<ul>
										<li>
											<span><a target='_blank' href='../forum/person.php?userId=用户Id'>天外天：</span>
											<span>
												评论内容
											</span>
											<button class='btn-link disableBtn' value='评论Id' onClick='disableCommentForQuestion(this)'>删除</button>
											<button class='btn-link replyBtn' value='评论Id' onClick='showReplyDiv(this)'>回复</button>
											<button class='btn-link detailsBtn' value='评论Id' onClick='getReplysForComment(this)'>详情</button>
											<div class='replyCommnetDiv'>
												<textarea></textarea>
												<br />
												<button class='btn btn-success replyCommentBtn' value='评论Id' onClick='replyComment(this)'>回复</button>
												<button class='btn btn-warning cancelReplyComment' onclick='cancelReplyComment(this)'>取消</button>
											</div>
										</li>									
										<div class="replysForComment">
											<li>
												<span>水中水&nbsp;回复&nbsp;天外天：</span>
												<span>
													是的，我也觉得可以在软件没有修复所有bug的时候上线
												</span>
												<button class='btn-link disableBtn' value='传入replyId' onClick='disableReplyForReply(this)'>删除</button>
												<button class='btn-link replyBtn showReplyReplyBtn' value='传入replyId' onClick='showReplyDiv(this)'>回复</button>
												<div class='replyReplyDiv'>
													<textarea></textarea>
													<br />
													<button class='btn btn-success replyReplyBtn' onClick='replyReply(this)'>回复</button>
													<button class='btn btn-warning cancelReplyReply' onclick='cancelReplyReply(this)'>取消</button>
												</div>
											</li>
										</div>										
									</ul>
								</li>				
							</ul>	-->
						</div>
						
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


