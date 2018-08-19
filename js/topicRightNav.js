$(function(){
	getTodayTopics();
});
/**
 * 获取今日话题，仅取十条
 */
function getTodayTopics(){
	$.ajax({
		url:"../Controller/QuestionController.php",
		data:{action:"getTodayTopics"},
		success:function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			var topicHtml="";
			result.topics.forEach(function(value,index){
				//最开始只显示3行
				if(index>=3){
					topicHtml+="<tr class='hide'>";
				}
				else{
					topicHtml+="<tr class='threeTopicsTrs'>";
				}
				topicHtml+="<td><p><a target='_blank' class='btn-link' href='topicDetails.php?topicId="+value.topicId+"'>"+value.content+"</a></p></td>";
				topicHtml+="</tr>";				
			});
			$("#todayTopicsTable tbody").html(topicHtml);	
		}
	});		
}

function moreTodayTopics(){
	$("#todayTopicsTable tbody tr").toggle();
	$(".threeTopicsTrs").show();
}