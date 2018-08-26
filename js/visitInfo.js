/**
 * 用jquery在页面加载时给元素注册的事件，对于后来在页面中添加的元素该事件无效
 * 所以以后在编写页面动态生成的元素事件时，好的做法是直接在元素中添加onClick方法，可以将this
 * 作为参数传递
 */
$(function(){
	//将话题按钮设为激活
	$("#menuBtn a[href='visitInfo.php']").parent().addClass("active");
	$("#menuBtn a[href='visitInfo.php']").parent().siblings().removeClass();
	//页面加载时加载所有话题的列表
	searchVisitInfo();
	loadKeyword();//分页的搜索信息加载时，要在输入框中写入关键字
});

function loadKeyword(){
	var keyword=$("#keywordHidden").attr("value");
	$("#keyword").val(keyword);
}

function queryVisitInfo(){
	var keyword=$.trim($("#keyword").val());
	$("#keywordHidden").attr("value",keyword);
	searchVisitInfo();
}

/**
 * 获取所有话题的函数
 * 不需要传入参数
 */
function searchVisitInfo(){
	var keyword=$.trim($("#keywordHidden").attr("value"));
	//现将值转化成一个合理的页数值
	var page=$.trim($("#pageHidden").attr("value"));
	page=$.isNumeric(page)?page:1;//不是数字时设为1
	page=page<1?1:page;//小于1时设为1
	$.ajax({
		url:"../Controller/OperatorController.php",
		data:{action:"searchVisitInfo",page:page,keyword:keyword},
		success:function(data){
			var result=$.trim(data);
			var pattern=new RegExp("\{([^\{]+)[\s\S]*(\})$","gi");//使用正则表达式检测结果是否为json格式，以{开头，以}结尾，中间任意字符
			if(pattern.test(result)){
				result=$.parseJSON(result);
				var visitInfoList="";
				result.visitInfos.forEach(function(value,index){
					visitInfoList+="<tr>";
					visitInfoList+="<td>"+value.visitor+"</td>";
					visitInfoList+="<td>"+value.visitTime+"</td>";
					visitInfoList+="<td>"+value.visitorIP+"</td>";
					visitInfoList+="<td>"+value.visitCount+"</td>";
					visitInfoList+="<td>"+value.callCount+"</td>";
					visitInfoList+="</tr>";
				});
				$("#visitInfoTable tbody").html(visitInfoList);
				var paras=(keyword=="")?"":("&keyword="+keyword);//如果关键字为空，就让参数为空
				writePager(result,page,"visitInfo.php",paras);	
			}else{
				result=decodeURI(result);
				var reg=/\"/g;
				alert(result.replace(reg,''));
			}
		}
	});		
}

