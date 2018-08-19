$(function(){
	//将菜单项的当前页菜单增加选中样式
	$("#menuUl>li>a[href='article.php']").parent().addClass("active");	
	
	//加载所有的文章信息
	//loadAllArticles();
	queryArticlesByKeyword();
	loadKeyword();
	
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
 * 加载所有的文章信息
 */
function loadAllArticles(){
	$.get(
		"../Controller/ArticleController.php",
		{action:"loadAllArticles"},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			var articlesHtml="";
			result.articles.forEach(function(value,index){
				articlesHtml+="<tr>";
				if($(".handleMultiDiv").css("display")=="block"){
					articlesHtml+="<td class='forSelectMulti' style='display:block'><label class='checkbox'><input type='checkbox' value='"+value.articleId+"'></label></td>";
				}
				else{
					articlesHtml+="<td class='forSelectMulti'><label class='checkbox'><input type='checkbox' value='"+value.articleId+"'></label></td>";
				}
				articlesHtml+="<td><a target='_blank' href='../forum/articleDetails.php?articleId="+value.articleId+"'>"+value.title+"</a></td>";
				articlesHtml+="<td>"+value.author+"</td>";
				var publishDate=(value.publishDate==""||value.publishDate==null)?"未发布":value.publishDate;
				articlesHtml+="<td class='detailsInfo'>"+publishDate+"</td>";
				articlesHtml+="<td class='detailsInfo'>"+value.publisherName+"</td>";
				articlesHtml+="<td class='detailsInfo'>"+value.size+" bytes</td>";
				articlesHtml+="<td class='detailsInfo'>"+value.label+"</td>";
				if(value.enable=="1"){
					articlesHtml+="<td><button class='btn btn-warning' onclick='disableArticle(this)' value='"+value.articleId+"'>禁止发布</button></td>";
				}
				else{
					articlesHtml+="<td><button class='btn btn-success' onclick='enableArticle(this)' value='"+value.articleId+"'>允许发布</button></td>";
				}
				
				articlesHtml+="<td><button class='btn btn-danger' onclick='deleteArticle(this)' value='"+value.articleId+"'>删除</button></td>";
				articlesHtml+="</tr>";
			});
			$("#articlesTable tbody").html(articlesHtml);
		}
	);
}

function searchArticles(){
	var keyword=$.trim($("#keyword").val());
	$("#keywordHidden").attr("value",keyword);
	queryArticlesByKeyword();
}

/**
 * 查询文章信息
 */
function queryArticlesByKeyword(){
	var keyword=$.trim($("#keywordHidden").attr("value"));
	//现将值转化成一个合理的页数值
	var page=$.trim($("#pageHidden").attr("value"));
	page=$.isNumeric(page)?page:1;//不是数字时设为1
	page=page<1?1:page;//小于1时设为1
	$.get(
		"../Controller/ArticleController.php",
		{action:"queryArticlesByKeyword",keyword:keyword,page:page},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			var articlesHtml="";
			result.articles.forEach(function(value,index){
				articlesHtml+="<tr>";
				if($(".handleMultiDiv").css("display")=="block"){
					articlesHtml+="<td class='forSelectMulti' style='display:block'><label class='checkbox'><input type='checkbox' value='"+value.articleId+"'></label></td>";
				}
				else{
					articlesHtml+="<td class='forSelectMulti'><label class='checkbox'><input type='checkbox' value='"+value.articleId+"'></label></td>";
				}
				articlesHtml+="<td><a target='_blank' href='../forum/articleDetails.php?articleId="+value.articleId+"'>"+value.title+"</a></td>";
				articlesHtml+="<td>"+value.author+"</td>";
				var publishDate=(value.publishDate==""||value.publishDate==null)?"未发布":value.publishDate;
				articlesHtml+="<td class='detailsInfo'>"+publishDate+"</td>";
				articlesHtml+="<td class='detailsInfo'><a target='_blank' href='../forum/person.php?userId="+value.publisher+"'>"+value.publisherName+"</a></td>";
				articlesHtml+="<td class='detailsInfo'>"+value.size+" bytes</td>";
				articlesHtml+="<td class='detailsInfo'>"+value.label+"</td>";
				if(value.publishDate=="" || value.publishDate==null){
					articlesHtml+="<td><button class='btn btn-success' onclick='publishSelfArticle(this)' value='"+value.articleId+"'>发布文章</button></td>";
				}
				else{
					articlesHtml+="<td><button class='btn btn-warning' onclick='cancelPublishSelfArticle(this)' value='"+value.articleId+"'>取消发布</button></td>";
				}
				articlesHtml+="<td><button class='btn btn-danger' onclick='deleteSelfArticle(this)' value='"+value.articleId+"'>删除</button></td>";
				articlesHtml+="</tr>";
			});
			$("#articlesTable tbody").html(articlesHtml);
			var paras=(keyword=="")?"":("&keyword="+keyword);//如果关键字为空，就让参数为空
			writePager(result,page,"article.php",paras);
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
 * 禁用文章
 */
function disableArticle(obj){
	var articleId=$(obj).attr("value");
	$.get(
		"../Controller/ArticleController.php",
		{action:"disableArticle",articleId:articleId},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			if(result.articleCount==1){
				var enableArticleHtml="<button class='btn btn-success' onclick='enableArticle(this)' value='"+articleId+"'>允许发布</button>";
				$(obj).parent().html(enableArticleHtml);
			}
		}
	);
}

/**
 * 启用文章
 */
function enableArticle(obj){
	var articleId=$(obj).attr("value");
	$.get(
		"../Controller/ArticleController.php",
		{action:"enableArticle",articleId:articleId},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			if(result.articleCount==1){
				var disableArticleHtml="<button class='btn btn-warning' onclick='disableArticle(this)' value='"+articleId+"'>禁止发布</button>";
				$(obj).parent().html(disableArticleHtml);
			}
		}
	);
}

/**
 * 删除文章
 */
function deleteArticle(obj){
	if(confirm("真的要删除该文章吗？")){
		var articleId=$(obj).attr("value");
		$.get(
			"../Controller/ArticleController.php",
			{action:"deleteArticle",articleId:articleId},
			function(data){
				var result=$.trim(data);
				result=$.parseJSON(result);
				if(result.articleCount==1){
					$(obj).parent().parent().remove();
				}
				else{
					alert("删除失败");
				}
			}
		);
	}	
}

/**
 * 删除作者自己写的文章
 */
function deleteSelfArticle(obj){
	if(confirm("真的要删除该文章吗？")){
		deleteArticle(obj);
	}
}
/**
 * 删除作者自己写的文章
 */
function deleteArticle(obj){	
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
			}
		}
	);
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
				queryArticlesByKeyword();
				showList();			
			}
		}
	);
}

//下面是实现全选的函数，在jquery1.6之后，对于checked属性，就要用prop实现全选，原来的attr就难以完成了
function selectAll(){
	if($('#selectAll').prop('ckecked')!=true){
		$('#selectAll').prop('ckecked',true);
		$("tbody :checkbox").prop("checked",true);
	}
	else{
		$('#selectAll').prop('ckecked',false);
		$("tbody :checkbox").prop("checked",false);
	}
	
}
//下面是实现反选的函数，下面的函数实现的比较巧妙，因为jquery中的each函数比较特殊，无法使用一般的判断分支进行处理
//使用如下方式取与原有选中状态相反的状态，就能够实现取反了
function selectReverse(){	
	$('tbody input:checkbox').each(function(){
		$(this).prop('checked',!$(this).prop('checked'));
	})
}

/**
 * 禁止发布选中文章
 */
function disablePublishSelectArticles(){
	var selectedArticles=new Array();
	$('tbody input[type="checkbox"]:checked').each(function(){ 
		selectedArticles.push($(this).val()); 
	}); 
	if(selectedArticles.length==0){
		alert("您没有选中任何文章，请在选中之后再批量操作");
		return false;
	}
	else{
		$('tbody input[type="checkbox"]:checked').each(function(){ 
			cancelPublishSelfArticle(this);
		}); 
	}
}

/**
 * 允许发布选中文章
 */
function enablePublishSelectArticles(){
	var selectedArticles=new Array();
	$('tbody input[type="checkbox"]:checked').each(function(){ 
		selectedArticles.push($(this).val()); 
	}); 
	if(selectedArticles.length==0){
		alert("您没有选中任何文章，请在选中之后再批量操作");
		return false;
	}
	else{
		$('tbody input[type="checkbox"]:checked').each(function(){ 
			publishSelfArticle(this);
		}); 
	}
}

/**
 * 删除选中文章
 */
function deleteSelectArticles(){
	if(confirm("将会删除选择的文章，确定要这么做吗？")){
		var selectedArticles=new Array();
		$('tbody input[type="checkbox"]:checked').each(function(){ 
			selectedArticles.push($(this).val()); 
		}); 
		if(selectedArticles.length==0){
			alert("您没有选中任何文章，请在选中之后再批量操作");
			return false;
		}
		else{
			$('tbody input[type="checkbox"]:checked').each(function(){ 
				deleteArticle(this);
			}); 
		}
	}	
}
