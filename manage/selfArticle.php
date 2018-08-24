<?php
	//获取问题页数，用于分页显示
	$page=$_REQUEST['page']??1;
	$keyword=$_REQUEST['keyword']??"";
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>零一知享-个人文章管理</title>
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<meta name="keywords" content="零一知享,lingyiyiling,lingyizhixiang">
		<meta name="description" content="零一知享个人文章管理">
		<!--<link href="../bootstrap/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
	    <link href="../bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css">
		<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">	
		<script src="../js/jquery-1.9.1.js"></script>
		<script src="../bootstrap/js/bootstrap.min.js"></script>-->
		<link href="https://cdn.bootcss.com/bootstrap/2.3.0/css/bootstrap.no-icons.min.css" rel="stylesheet">
		<link href="https://cdn.bootcss.com/bootstrap/2.3.2/css/bootstrap.min.css" rel="stylesheet" type="text/css">
		<link href="https://cdn.bootcss.com/bootstrap/2.3.2/css/bootstrap-responsive.min.css" rel="stylesheet">
		<script src="https://cdn.bootcss.com/jquery/2.0.0/jquery.min.js"></script>
		<script src="https://cdn.bootcss.com/bootstrap/2.3.2/js/bootstrap.min.js"></script>
		
		<link href="../external/google-code-prettify/prettify.css" rel="stylesheet">
	    <link href="../external/font-awesome.css" rel="stylesheet">
	    <script src="../external/jquery.hotkeys.js"></script>
		<script src="../external/google-code-prettify/prettify.js"></script>
		<link href="../css/manageSelf.css" rel="stylesheet" type="text/css">
		<script src="../bootstrap-wysiwyg.js"></script>
	    <script src="../js/loadInitEditor.js"></script>
	    <script src="../js/MyPager.js"></script>
	    <script src="../js/SelfArticle.js"></script>
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
					<div class="selfArticleManageMenu">	
						<div class="form-search input-append pull-left">
							<input class="input-medium" id="keyword" type="text" placeholder="文章标题/标签/内容" /> 
							<button type="submit" class="btn" onclick="searchArticles()">查找</button>
						</div>
						
						<ul class="nav nav-tabs pull-right">
							<li>
								<button id="createBtn" class="btn-link">新建文章</a>
							</li>
						</ul>
					</div>
					<div class="selfArticleTableDiv">
						<table class="table" id="selfArticleTable">		
						<thead>
							<tr>								
								<th>标题</th>
								<th>作者</th>
								<th>发布日期</th>
								<th>发布者</th>
								<th>文章大小</th>
								<th>文章标签</th>
								<th>发布</th>	
								<th>删除</th>	
								<th>修改</th>		
							</tr>							
						</thead>
						<tbody>
							<tr>
								<td>
									穷且益坚，不坠青云之志
								</td>
								<td>
									山外山
								</td>
								<td>
									2018/7/9
								</td>
								<td>
									山外山
								</td>
								<td>
									894KB
								</td>
								<td>
									贫穷，富贵，道德，正义，诱惑，犯罪
								</td>				
								<td>
									<button class="btn btn-success">发布文章</button>
								</td>			
								<td>
									<button class="btn btn-danger">删除</button>
								</td>
								<td>
									<a href="#" >修改文章</a>
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
			
			<!--下面是创建文章的html代码-->
			<div class="row-fluid createDiv">
				<div class="span12 mainContent">
					<div class="row-fluid ">
						<div class="form-horizontal registerForm">
							<div class="formTitle">
								<legend>写文章</legend>
								<button class="btn-link pull-right listBtn">返回文章列表</button>
							</div>
						  	<div class="control-group">
						  		<label class="control-label" for="inputTitle">标题</label>
						    	<div class="controls">
							      	<input type="text" id="inputTitle" placeholder="最多100字">
							    </div>
						  	</div>
						  	<div class="control-group">
						  		<label class="control-label" for="inputAuthor">作者</label>
						    	<div class="controls">
						      		<input type="text" id="inputAuthor" placeholder="最多25字">
						    	</div>
						  	</div>
							<div class="control-group">
								<label class="control-label" for="inputLabel">文章标签</label>
							    <div class="controls">
							    	<input type="text" id="inputLabel" placeholder="最多150字">
							    </div>
							</div>
							
							<!--下面是关于超文本编辑器的部分-->
							<div class="control-group">
								<label class="control-label" for="inputCpntent">正文内容(少于1万字)</label>
								<div class="controls">
									<div class="editorDiv">
										<div id="alerts"></div>
									  	<div class="btn-toolbar" data-role="editor-toolbar" data-target="#editor">
									      	<div class="btn-group">
									        <a class="btn dropdown-toggle" data-toggle="dropdown" title="Font">
									        	<i class="icon-font"></i>
									        	<b class="caret"></b>
									        </a>
									          <ul class="dropdown-menu">
									          </ul>
									        </div>
									      	<div class="btn-group">
									        <a class="btn dropdown-toggle" data-toggle="dropdown" title="Font Size">
									        	<i class="icon-text-height"></i>&nbsp;
									        	<b class="caret"></b>
									        </a>
									        <ul class="dropdown-menu">
									          <li><a data-edit="fontSize 5"><font size="5">Huge</font></a></li>
									          <li><a data-edit="fontSize 3"><font size="3">Normal</font></a></li>
									          <li><a data-edit="fontSize 1"><font size="1">Small</font></a></li>
									        </ul>
									      </div>
									      	<div class="btn-group">
										        <a class="btn" data-edit="bold" title="Bold (Ctrl/Cmd+B)"><i class="icon-bold"></i></a>
										        <a class="btn" data-edit="italic" title="Italic (Ctrl/Cmd+I)"><i class="icon-italic"></i></a>
										        <a class="btn" data-edit="strikethrough" title="Strikethrough"><i class="icon-strikethrough"></i></a>
										        <a class="btn" data-edit="underline" title="Underline (Ctrl/Cmd+U)"><i class="icon-underline"></i></a>
										    </div>
									      	<div class="btn-group">
										        <a class="btn" data-edit="insertunorderedlist" title="Bullet list"><i class="icon-list-ul"></i></a>
										        <a class="btn" data-edit="insertorderedlist" title="Number list"><i class="icon-list-ol"></i></a>
										        <a class="btn" data-edit="outdent" title="Reduce indent (Shift+Tab)"><i class="icon-indent-left"></i></a>
										        <a class="btn" data-edit="indent" title="Indent (Tab)"><i class="icon-indent-right"></i></a>
									      	</div>
									      	<div class="btn-group">
										        <a class="btn" data-edit="justifyleft" title="Align Left (Ctrl/Cmd+L)"><i class="icon-align-left"></i></a>
										        <a class="btn" data-edit="justifycenter" title="Center (Ctrl/Cmd+E)"><i class="icon-align-center"></i></a>
										        <a class="btn" data-edit="justifyright" title="Align Right (Ctrl/Cmd+R)"><i class="icon-align-right"></i></a>
										        <a class="btn" data-edit="justifyfull" title="Justify (Ctrl/Cmd+J)"><i class="icon-align-justify"></i></a>
										    </div>
									      	<div class="btn-group">
											  	<a class="btn dropdown-toggle" data-toggle="dropdown" title="Hyperlink"><i class="icon-link"></i></a>
											    <div class="dropdown-menu">
												    <input class="span2" style="min-width: 120px;" placeholder="URL" type="text" data-edit="createLink"/>
												    <button class="btn" type="button">Add</button>
									        	</div>
									        	<a class="btn" data-edit="unlink" title="Remove Hyperlink"><i class="icon-cut"></i></a>
									
									      	</div>
									      
									      	<div class="btn-group">
										       <a class="btn" title="Insert picture (or just drag & drop)" id="pictureBtn">
										        	<input type="file" data-role="magic-overlay" style="width:42px;height: 33px;position: relative;left: -2px;top:-2px;" data-edit="insertImage" />
										        	<i class="icon-picture"></i>										        	
										        </a>
										        
									      	</div>
									      	<div class="btn-group">
										        <a class="btn" data-edit="undo" title="Undo (Ctrl/Cmd+Z)"><i class="icon-undo"></i></a>
										        <a class="btn" data-edit="redo" title="Redo (Ctrl/Cmd+Y)"><i class="icon-repeat"></i></a>
									      	</div>
									      	<input type="text" data-edit="inserttext" id="voiceBtn" x-webkit-speech="">
									    </div>
									
									  	<div id="editor">
									  	</div>
									</div>
								</div>
							</div>
							<div class="control-group">
							    <div class="controls">
							      	<button type="submit" class="btn btn-info" onclick="writeArticle()" id="createArticleBtn">创建文章</button>
							      	<button type="submit" class="btn btn-warning" id="cancleBtn">取消</button>
							    </div>
							</div>
							<hr>
						</div>						
					</div>
				</div>
			</div>	
			
			
			<footer id="manageFooter">
				<?php include(__DIR__."/../View/manageFooter.php"); ?>
			</footer>	
				
		</div>		
	</body>
</html>
