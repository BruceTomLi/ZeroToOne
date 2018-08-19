$(function(){
	getTenHotTopics();
	getTenHotUsers();
});

/**
 * 获取最热门的十个话题
 */
function getTenHotTopics(){
	$.ajax({
		url:"../Controller/QuestionController.php",
		data:{action:"getTenHotTopics"},
		success:function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			var topicsHtml="";
			result.topics.forEach(function(value,index){
				//最开始只显示3行
				if(index>=3){
					topicsHtml+="<tr class='hide'>";
				}
				else{
					topicsHtml+="<tr class='threeQuestionsTrs'>";
				}
				// topicsHtml+="<td><img src='../img/tradeImgs/"+value.topicType+".gif'></td>";
				topicsHtml+="<td><p><a target='_blank' class='btn-link' href='topicDetails.php?topicId="+value.topicId+"'>"+value.content+"</a>&nbsp;&nbsp;<span>"+value.commentCount+"条评论</span></p></td>";
				topicsHtml+="</tr>";				
			});
			$("#hotTopicsTable tbody").html(topicsHtml);	
		}
	});		
}

function moreHotTopics(){
	$("#hotTopicsTable tbody tr").toggle();
	$(".threeQuestionsTrs").show();
}

/**
 * 获取最活跃的十个人
 */
function getTenHotUsers(){
	$.ajax({
		url:"../Controller/QuestionController.php",
		data:{action:"getTenHotUsers"},
		success:function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			var userHtml="";
			result.users.forEach(function(value,index){
				//最开始只显示3行
				if(index>=3){
					userHtml+="<tr class='hide'>";
				}
				else{
					userHtml+="<tr class='threeUsersTrs'>";
				}
				// topicsHtml+="<td><img src='../img/tradeImgs/"+value.topicType+".gif'></td>";
				userHtml+="<td><p><a target='_blank' class='btn-link' href='person.php?userId="+value.askerId+"'>"+value.asker+"</a>&nbsp;&nbsp;<span>"+value.total+"个问题和话题</span></p></td>";
				userHtml+="</tr>";				
			});
			$("#hotUsersTable tbody").html(userHtml);	
		}
	});		
}

function moreHotUsers(){
	$("#hotUsersTable tbody tr").toggle();
	$(".threeUsersTrs").show();
}