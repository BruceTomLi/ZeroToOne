<?php
	require_once(__DIR__."/../Controller/SelfArticleController.php");
	require_once(__DIR__."/../Controller/ArticleController.php");
	$selfArticleController=new SelfArticleController();
	$articleId=$_REQUEST['articleId']??"";
	$result=$selfArticleController->loadArticleDetails($articleId);
	$resultArr=json_decode($result,true);
	$articleDetails=$resultArr["articleDetails"][0]??"";
	$articleTitle=$articleDetails['title']??"";
	//获取文章相关的问题和话题信息
	$articleController=new ArticleController();
	$questionInfo=$articleController->getQuestionOfArticle($articleTitle);
	$questionCount=$questionInfo[0]["questionCount"];
	$questionId=$questionInfo[0]["questionId"]??"";
	$topicInfo=$articleController->getTopicOfArticle($articleTitle);
	$topicCount=$topicInfo[0]["topicCount"];
	$topicId=$topicInfo[0]["topicId"]??"";
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>文章详情页</title>
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
								<h4 id="detailsTitle"><?php echo $articleDetails['title']??"未获取到文章标题"; ?></h4>
								<p id="detailsLabel">标签：<span><?php echo $articleDetails['label']??"未获取到文章标签"; ?></span></p>
								<p id="detailsAuthor">作者：<span><?php echo $articleDetails['author']??"未获取到文章作者"; ?></span></p>
								<section id="detailsContent">
									<?php
										echo $articleDetails['articleContent']??"未获取到文章内容";
										if(isset($articleDetails['articleContent'])){
											if($questionCount>0){
												echo "<br><br><p><a target='_blank' href='questionDetails.php?questionId={$questionId}'>查看文章的问题</a></p>";
											}
											else{
												echo "<br><br><p><a target='_blank' href='../manage/selfQuestion.php?articleTitle={$articleTitle}'>创建文章的问题</a></p>";
											}
											if($topicCount>0){
												echo "<p><a target='_blank' href='topicDetails.php?topicId={$topicId}'>查看文章的话题</a></p>";	
											}
											else{
												echo "<p><a target='_blank' href='../manage/selfTopic.php?articleTitle={$articleTitle}'>创建文章的话题</a></p>";
											}
										}										
									?>
									<!--<p>
										最近我们看了一部电影，叫做“我不是药神”，讲述主人公为了延续一千多名慢粒白血病患者的生命，
									不惜从印度进口违禁药，最后被警方抓到判刑的故事。如果正义和法律不能两全，我们到底是做守法奉公
									的好公民呢，还是做坚持正义的救世主呢？而我想到的不仅仅是这个问题，还有，一个救世主能够做多久呢？
									</p>
									<p>
										如果问题不能从根本上得到解决，那么救世主被关进监狱以后，等待患者的也就只有死路一条了。
									从经济学的角度来看，格列宁处于一种药物的垄断地位，所以它才敢把药物的价格定到天价，让患者只需几个
									月的时间就倾家荡产。
									</p>
									<p>
										所以源头上来说，只有在这种药被足够有道德的人掌握，并且以合理的定价出售，或者
									是当这种药可以被其他药厂以其他不侵权的制药方式制作的其他药物代替的时候，形成一种存在竞争的市场，
									药物的价格才会合理化。
									</p>
									<p>
										普通人是不可能组织大量人力物力去开发新药的，这是一个以经济为中心的社会，然而在每个人都在想
									努力赚钱的同时，很多仁义道德诚信都在被背弃。然而即便是想去做研究的人，他们也有家人，要生活下去，
									也需要钱，所以他们就不得不为了生存去做可以让他们快点赚到钱的工作。
									</p>
									<p>
										影片的最后提到了政府的参与让药价低了下来。然而，为什么我们总要在付出足够多的生命的代价之后，
									才能开始醒悟一点呢？
									</p>-->
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
