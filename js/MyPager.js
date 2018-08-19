/**
 * 下面是试图将不同地方的分页js代码抽象到一个js文件中，让代码可以重用，并且容易修改
 * result是查询的结果，page是当前页，webpage是指超链接中要跳转到的页
 */
function writePager(result,page,webpage="index.php",paras=""){
	//设置每页显示几个页码，这是为了兼容小屏幕设备可以在一行显示所有分页按钮，原本我是设为5的
	var pageCount=4;
	//还要加上分页信息，这里是直接复制的index的文章中的分页代码，只修改了href对应的php页
	var pagerHtml="<ul><li><a href='"+webpage+"?page=1"+paras+"'><<</a></li>";//首页html
	var pages=(result.count%5==0)?(result.count/5):((Math.floor(result.count/5))+1);//以5行为单位分页，小于5行时认为是1页
	page=(page<=pages)?(parseInt(page)):(parseInt(pages));
	var startPage=page-((page%pageCount==0)?pageCount:(page%pageCount))+1;//有页码的起始页，比如第5页，5-5+1=1；第12页，13-3+1=11；
	var endPage=(startPage+(pageCount-1)<=pages)?(startPage+(pageCount-1)):pages;//结束页为起始页+3之后和总页数的比较中的较小值
	pagerHtml+="<li><a href='"+webpage+"?page="+(page-pageCount<1?1:(page-pageCount))+paras+"'><</a></li>";//上一页html，简单-4就行，程序会判断是否超过范围
	if(startPage>=1){
		for(var i=startPage;i<=endPage;i++){
			pagerHtml+="<li><a href='"+webpage+"?page="+i+paras+"'>"+i+"</a></li>";//中间页html
		}
	}	
	pagerHtml+="<li><a href='"+webpage+"?page="+(page+pageCount>pages?pages:(page+pageCount))+paras+"'>></a></li>";//下一页html，简单+4就行，程序会判断是否超过范围
	pagerHtml+="<li><a href='"+webpage+"?page="+pages+paras+"'>>></a></li>";//尾页html
	pagerHtml+="</ul>";
	pagerHtml+="<p>总页数："+pages+" 当前页："+page+"</p>";
	
	$("#paginationDiv").html(pagerHtml);
}
