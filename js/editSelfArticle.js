$(function(){
	loadArticleDetailsForEdit();
});

/**
 * 加载一篇文章的详细信息
 */
function loadArticleDetailsForEdit(){
	var articleId=$("#articleIdHidden").attr("value");
	if(articleId!=""){
		$.get(
			"../Controller/SelfArticleController.php",
			{action:"loadArticleDetailsForEdit",articleId:articleId},
			function(data){
				var result=$.trim(data);
				var pattern=new RegExp("\{([^\{]+)[\s\S]*(\})$","gi");//使用正则表达式检测结果是否为json格式，以{开头，以}结尾，中间任意字符
				if(pattern.test(result)){
					result=$.parseJSON(result);
					result.articleDetails.forEach(function(value,index){
						$("#changeTitle").val(value.title);
						$("#changeLabel").val(value.label);
						$("#changeAuthor").val(value.author);
						$("#editor").html(value.articleContent);
					});
				}else{
					result=(decodeURI(result));
					var reg=/\"/g;
					alert(result.replace(reg,''));
				}
			}
		);
	}	
}

function changeArticle(){
	var articleId=$("#articleIdHidden").attr("value");
	var articleTitle=$.trim($("#changeTitle").val());
	var articleLabel=$.trim($("#changeLabel").val());
	var articleAuthor=$.trim($("#changeAuthor").val());
	var articleContent=$("#editor").html();
	var token=$("#token").val();
	if(articleId!=""){
		$.post(
			"../Controller/SelfArticleController.php",
			{action:"saveEditArticle",token:token,articleId:articleId,title:articleTitle,
				label:articleLabel,author:articleAuthor,content:articleContent},
			function(data){
				var result=$.trim(data);
				var pattern=new RegExp("\{([^\{]+)[\s\S]*(\})$","gi");//使用正则表达式检测结果是否为json格式，以{开头，以}结尾，中间任意字符
				if(pattern.test(result)){
					result=$.parseJSON(result);
					if(result.count==1){
						alert("文章修改成功");	
						history.back(-1);//返回之前的页面
					}
					else{
						alert("文章未进行修改");
					}
				}else{
					result=(decodeURI(result));
					var reg=/\"/g;
					alert(result.replace(reg,''));
				}
			}
		);
	}
}
