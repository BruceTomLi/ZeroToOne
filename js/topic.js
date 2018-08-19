/**
 * 用jquery在页面加载时给元素注册的事件，对于后来在页面中添加的元素该事件无效
 * 所以以后在编写页面动态生成的元素事件时，好的做法是直接在元素中添加onClick方法，可以将this
 * 作为参数传递
 */
$(function(){
	//将话题按钮设为激活
	$("#menuBtn a[href='topic.php']").parent().addClass("active");
	$("#menuBtn a[href='topic.php']").parent().siblings().removeClass();
	//页面加载时加载所有话题的列表
	getAllTopic();
	
	//点击“返回话题列表时”返回
	$("#returnListBtn").on("click",function(){
		$(".detailsDiv").hide();
		$(".queryDiv").show();
		$(".editDiv").hide();
		$(".createDiv").hide();
		$(".searchResultDiv").hide();
	});
});

/**
 * 获取所有话题的函数
 * 不需要传入参数
 */
function getAllTopic(){
	//现将值转化成一个合理的页数值
	var page=$.trim($("#pageHidden").attr("value"));
	page=$.isNumeric(page)?page:1;//不是数字时设为1
	page=page<1?1:page;//小于1时设为1
	$.ajax({
		url:"../Controller/TopicController.php",
		data:{action:"getAllTopic",page:page},
		beforeSend:function(){
			$("#loadingModal").modal('show');
		},
		success:function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			var topicList="<tr>";
			result.topics.forEach(function(value,index){
				if((index!=0 && index%2==0)){
					topicList+="</tr><tr>";//同一行显示两个话题
				}
				topicList+="<td class='span1'><img src='../img/tradeImgs/"+value.topicType+".gif'></td>";
				topicList+="<td class='span4'><p><span><a class='btn-link' href='topicDetails.php?topicId="+value.topicId+"'>"+value.content+"</a></span></p>";
				topicList+="<p><span>发起者：<a target='_blank' href='person.php?userId="+value.askerId+"'>"+value.asker+"</a></span></p>";
				topicList+="<p><span>话题类型："+value.topicType+"</span></p>";	
				topicList+="<p><span>发起时间："+value.askDate+"</span></p></td>";
			});
			topicList+="</tr>";
			$(".topicTable tbody").html(topicList);
			writePager(result,page,"topic.php");
			
			$("#loadingModal").modal('hide');			
		}
	});		
}

