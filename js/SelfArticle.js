$(function(){
	//将菜单项的当前页菜单增加选中样式
	$("#menuUl>li>a[href='selfArticle.php']").parent().addClass("active");	
		
	//加载用户文章信息
	//loadSelfArticles();
	queryArticlesByKeyword();
	
	
	loadKeyword();//分页的搜索信息加载时，要在输入框中写入关键字
	
	$(".detailsBtn").on("click",function(){
		showDetails();
	});
	$(".listBtn").on("click",function(){
		showList();
	});
	$("#createBtn").on("click",function(){
		showCreate();
	});
	$("#editBtn").on("click",function(){
		showEdit();
	});
	
	$("#cancleBtn").on("click",function(){
		$("#inputTitle").val("");
		$("#inputAuthor").val("");
		$("#inputLabel").val("");
		$("#editor").html("");
		showList();
	});
});



function loadKeyword(){
	var keyword=$("#keywordHidden").attr("value");
	$("#keyword").val(keyword);
}

function showDetails(){
	$(".detailsDiv").show();
	$(".queryDiv").hide();
	$(".editDiv").hide();
	$(".createDiv").hide();
}

function showList(){
	$(".detailsDiv").hide();
	$(".queryDiv").show();
	$(".editDiv").hide();
	$(".createDiv").hide();
}

function showEdit(){
	$(".detailsDiv").hide();
	$(".queryDiv").hide();
	$(".editDiv").show();
	$(".createDiv").hide();
}

function showCreate(){
	$(".detailsDiv").hide();
	$(".queryDiv").hide();
	$(".editDiv").hide();
	$(".createDiv").show();
}


/**
 * 写文章
 */
function writeArticle(){
	var title=$.trim($("#inputTitle").val());
	if(title.length<=0){
		alert("标题不得为空");
		return;
	}
	var author=$("#inputAuthor").val();	
	var label=$("#inputLabel").val();
	var content=$("#editor").html();
	var token=$("#token").val();
	$.post(
		"../Controller/SelfArticleController.php",
		{action:"writeArticle",token:token,title:title,author:author,label:label,content:content},
		function(data){
			var result=$.trim(data);
			var pattern=new RegExp("\{([^\{]+)[\s\S]*(\})$","gi");//使用正则表达式检测结果是否为json格式，以{开头，以}结尾，中间任意字符
			if(pattern.test(result)){
				result=$.parseJSON(result);
				if(result.writeArticleCount==1){
					//loadSelfArticles();
					queryArticlesByKeyword();
					showList();
					alert("文章保存成功");				
				}
				else{
					alert("创建文章失败");
				}
			}else{
				result=(decodeURI(result));
				var reg=/\"/g;
				alert(result.replace(reg,''));
			}
		}
	);
}

/**
 * 加载用户文章信息
 */
function loadSelfArticles(){
	$.get(
		"../Controller/SelfArticleController.php",
		{action:"loadSelfArticles"},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			var articlesHtml="";
			result.articles.forEach(function(value,index){
				articlesHtml+="<tr>";
				articlesHtml+="<td><a target='_blank' href='../forum/articleDetails.php?articleId="+value.articleId+"'>"+value.title+"</a></td>";
				articlesHtml+="<td>"+value.author+"</td>";
				var publishDate=(value.publishDate==""||value.publishDate==null)?"未发布":value.publishDate;
				articlesHtml+="<td>"+publishDate+"</td>";
				articlesHtml+="<td>"+value.publisherName+"</td>";
				articlesHtml+="<td>"+value.size+" bytes</td>";
				articlesHtml+="<td>"+value.label+"</td>";
				if(value.publishDate=="" || value.publishDate==null){
					articlesHtml+="<td><button class='btn btn-success' onclick='publishSelfArticle(this)' value='"+value.articleId+"'>发布文章</button></td>";
				}
				else{
					articlesHtml+="<td><button class='btn btn-warning' onclick='cancelPublishSelfArticle(this)' value='"+value.articleId+"'>取消发布</button></td>";
				}
				articlesHtml+="<td><button class='btn btn-danger' onclick='deleteSelfArticle(this)' value='"+value.articleId+"'>删除</button></td>";
				articlesHtml+="</tr>";
			});
			$("#selfArticleTable tbody").html(articlesHtml);
		}
	);
}

function searchArticles(){
	var keyword=$.trim($("#keyword").val());
	$("#keywordHidden").attr("value",keyword);
	queryArticlesByKeyword();
}

/**
 * 查询用户文章信息
 */
function queryArticlesByKeyword(){
	var keyword=$.trim($("#keywordHidden").attr("value"));
	//现将值转化成一个合理的页数值
	var page=$.trim($("#pageHidden").attr("value"));
	page=$.isNumeric(page)?page:1;//不是数字时设为1
	page=page<1?1:page;//小于1时设为1
	$.get(
		"../Controller/SelfArticleController.php",
		{action:"queryArticlesByKeyword",keyword:keyword,page:page},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			var articlesHtml="";
			result.articles.forEach(function(value,index){
				articlesHtml+="<tr>";
				articlesHtml+="<td><a target='_blank' href='../forum/articleDetails.php?articleId="+value.articleId+"'>"+value.title+"</a></td>";
				articlesHtml+="<td>"+value.author+"</td>";
				var publishDate=(value.publishDate==""||value.publishDate==null)?"未发布":value.publishDate;
				articlesHtml+="<td>"+publishDate+"</td>";
				articlesHtml+="<td>"+value.publisherName+"</td>";
				articlesHtml+="<td>"+value.size+" bytes</td>";
				articlesHtml+="<td>"+value.label+"</td>";
				if(value.publishDate=="" || value.publishDate==null){
					articlesHtml+="<td><button class='btn btn-success' onclick='publishSelfArticle(this)' value='"+value.articleId+"'>发布文章</button></td>";
				}
				else{
					articlesHtml+="<td><button class='btn btn-warning' onclick='cancelPublishSelfArticle(this)' value='"+value.articleId+"'>取消发布</button></td>";
				}
				articlesHtml+="<td><button class='btn btn-danger' onclick='deleteSelfArticle(this)' value='"+value.articleId+"'>删除</button></td>";
				articlesHtml+="</tr>";
			});
			$("#selfArticleTable tbody").html(articlesHtml);
			var paras=(keyword=="")?"":("&keyword="+keyword);//如果关键字为空，就让参数为空
			writePager(result,page,"selfArticle.php",paras);
		}
	);
}

/**
 * 加载一篇文章的详细信息
 */
function loadArticleDetails(obj){
	var articleId=$(obj).attr("value");
	$.get(
		"../Controller/SelfArticleController.php",
		{action:"loadArticleDetails",articleId:articleId},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			result.articleDetails.forEach(function(value,index){
				$("#detailsTitle").text(value.title);
				$("#detailsLabel>span").text(value.label);
				$("#detailsAuthor>span").text(value.author);
				$("#detailsContent").html(value.content);
			});
			showDetails();
		}
	);
}

/**
 * 删除作者自己写的文章
 */
function deleteSelfArticle(obj){
	if(confirm("真的要删除该文章吗？")){
		var articleId=$(obj).attr("value");
		$.post(
			"../Controller/SelfArticleController.php",
			{action:"deleteSelfArticle",articleId:articleId},
			function(data){
				var result=$.trim(data);
				result=$.parseJSON(result);
				if(result.deleteArticleRow==1){
					//loadSelfArticles();
					queryArticlesByKeyword();
					showList();
					alert("成功删除了文章");					
				}
				else if(result.deleteArticleRow==0){
					alert("未能删除文章");
				}
				else{
					alert(result.deleteArticleRow);
				}
			}
		);
	}	
}

/**
 * 作者发布自己写的文章
 */
function publishSelfArticle(obj){
	var articleId=$(obj).attr("value");
	$.get(
		"../Controller/SelfArticleController.php",
		{action:"publishSelfArticle",articleId:articleId},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			if(result.publishArticleRow==1){
				//loadSelfArticles();
				queryArticlesByKeyword();
				showList();
				alert("发布文章成功");				
			}
			else{
				alert(result.publishArticleRow);
			}
		}
	);
}

/**
 * 作者取消发布自己写的文章
 */
function cancelPublishSelfArticle(obj){
	var articleId=$(obj).attr("value");
	$.get(
		"../Controller/SelfArticleController.php",
		{action:"cancelPublishSelfArticle",articleId:articleId},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			if(result.cancelPublishArticleRow==1){
				//loadSelfArticles();
				queryArticlesByKeyword();
				showList();
				alert("取消发布文章成功");				
			}
			else{
				alert(result.cancelPublishArticleRow);
			}
		}
	);
}
