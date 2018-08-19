<?php
	//获取问题页数，用于分页显示
	$page=$_REQUEST['page']??1;
	$keyword=$_REQUEST['keyword']??"";
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>管理文章</title>
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
		<link href="../bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css">		
		<script src="../js/jquery-1.9.1.js"></script>
		<script src="../bootstrap/js/bootstrap.min.js"></script>
		<link href="../css/manage.css" rel="stylesheet" type="text/css">
		<script src="../js/manage.js"></script>
		<script src="../js/MyPager.js"></script>
		<script src="../js/article.js"></script>
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
							<input class="input-medium" type="text" id="keyword" placeholder="文章名/标签/文章内容" /> 
							<button type="submit" class="btn" onclick="searchArticles()">查找</button>
						</div>
						
						<ul class="nav nav-tabs pull-right">	
							<li>
								<button id="hideOrShowDetailsBtn" class="btn-link">隐藏/显示文章详情</a>
							</li>
							<li>
								<button id="hideOrShowAbleBtn" class="btn-link">隐藏/显示禁用文章</a>
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
						    <button id="disabledBtn" class="btn btn-warning inline" onclick="disablePublishSelectArticles()">禁止发布选中文章</button>
							<button id="enabledBtn" class="btn btn-success inline" onclick="enablePublishSelectArticles()">允许发布选中文章</button>
							<!--<button id="disabledQueryBtn" class="btn btn-warning inline">禁用查询文章</button>-->
							<!--<button id="enabledQueryBtn" class="btn btn-success inline">启用查询文章</button>-->
							<button id="deleteBtn" class="btn btn-danger inline" onclick="deleteSelectArticles()">删除选中文章</button>
							<!--<button id="deleteQueryBtn" class="btn btn-warning inline">删除查询文章</button>-->
						</div>
					</div>
					<div class="tableDiv">
						<table class="table" id="articlesTable">		
						<thead>
							<tr>
								<th class="forSelectMulti">选择</th>
								<th>标题</th>
								<th>作者</th>
								<th class="detailsInfo">发布日期</th>
								<th class="detailsInfo">发布者</th>
								<th class="detailsInfo">大小</th>
								<th class="detailsInfo">标签</th>
								<th>禁止发布</th>
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
									穷且益坚，不坠青云之志
								</td>
								<td>
									山外山
								</td>								
								<td class="detailsInfo">
									2018/7/9
								</td>
								<td class="detailsInfo">
									山外山
								</td>
								<td class="detailsInfo">
									894KB
								</td>
								<td class="detailsInfo">
									贫穷，富贵，道德，正义，诱惑，犯罪
								</td>
								<td>
									<button class='btn btn-info' value='"+value.articleId+"'>查看详情</button>
								</td>
								<td>
									<button class='btn btn-warning' value='"+value.articleId+"'>禁止发布</button>
								</td>
								<td>
									<button class="btn btn-danger" value='"+value.articleId+"'>删除</button>
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
			
			<div class="row-fluid detailsDiv">
				<div class="span12 mainContent">
					<div class="row-fluid ">
						<ul class="nav nav-tabs pull-right">
							<li>
								<button class="btn-link listBtn">返回文章列表</button>
							</li>
						</ul>
						<article id="articleDetail">
							<h4 id="detailsTitle">穷且益坚，不坠青云之志</h4>
							<p id="detailsLabel">标签：<span>电影观后感</span></p>
							<p id="detailsAuthor">作者：<span>山外山</span></p>
							<section id="detailsContent">
								<p>
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
								</p>
							</section>
						</article>
					</div>						
				</div>
			</div>
						
			<footer id="manageFooter">
				<?php include(__DIR__."/../View/manageFooter.php"); ?>
			</footer>	
				
		</div>		
	</body>
</html>
