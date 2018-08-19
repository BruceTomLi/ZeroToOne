$(function(){
	getArticleList();
});
//获取文章信息
function getArticleList(){
	//现将值转化成一个合理的页数值
	var page=$.trim($("#pageHidden").attr("value"));
	page=$.isNumeric(page)?page:1;//不是数字时设为1
	page=page<1?1:page;//小于1时设为1
	$.ajax({
		url:"Controller/IndexController.php",
		data:{action:"getArticleList",page:page},
		beforeSend:function(){
			$("#loadingModal").modal('show');
		},
		success:function(data){
			var result=$.trim(data);
			var pattern=new RegExp("\{([^\{]+)[\s\S]*(\})$","gi");//使用正则表达式检测结果是否为json格式，以{开头，以}结尾，中间任意字符
			if(pattern.test(result)){
				result=$.parseJSON(result);
				var articlesHtml="";
				result.articles.forEach(function(value,index){
					articlesHtml+="<div class='hero-unit'>";
					articlesHtml+="<h1 class='artilceTitle'>"+value.title+"</h1>";
					articlesHtml+="<span>作者："+value.author+" - "+value.publishDate+"</span>";
					var onlyText="";//要先过滤掉特殊的html代码，只留下纯文本
					var sourceHtml=value.articleContent;
					var reg=new RegExp("<[^<]*>", "gi");  
					onlyText=sourceHtml.replace(reg,"");
					articlesHtml+="<p>"+onlyText.substr(0,100)+"</p>";
					articlesHtml+="<p><a class='btn btn-primary' target='_blank' href='forum/articleDetails.php?articleId="+value.articleId+"'>阅读全文</a></p>";
					articlesHtml+="</div>";
				});
				$("#articleDetail").html(articlesHtml);
				writePager(result,page,"index.php");
				$("#loadingModal").modal('hide');
			}else{
				result=(decodeURI(result));
				var reg=/\"/g;
				alert(result.replace(reg,''));
			}
		}
	});
}
