$(function(){
	loadTwentyNotices();
});
//获取文章信息
function loadTwentyNotices(){
	$.ajax({
		url:"Controller/IndexController.php",
		data:{action:"loadTwentyNotices"},
		success:function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			var noticesHtml="";
			result.notices.forEach(function(value,index){
				if(index>=3){
					noticesHtml+="<p class='hide'><a href='forum/noticeDetails.php?noticeId="+value.noticeId+"'>"+value.title+"</a>";
				}
				else{
					noticesHtml+="<p class='threeNotice'><a href='forum/noticeDetails.php?noticeId="+value.noticeId+"'>"+value.title+"</a>";
				}
				noticesHtml+="&nbsp;&nbsp;<span class='pull-right'>"+value.createTime+"</p>";
			});
			$("#noticesDiv").html(noticesHtml);
		}
	});
}

function moreNotices(){
	$("#noticesDiv p").toggle();
	$(".threeNotice").show();
}
