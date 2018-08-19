function logout(){
	$.get(
		"../Controller/LogoutController.php",
		function(data){
			var isLogout=$.trim(data);
			if(isLogout){
				window.location.reload();
			}
			else{
				alert("注销失败");
			}
		}
	);
}

/**
 * 获取查询的人、问题或话题
 */
function queryUserOrQuestionOrTopic(){
	var keyword=$.trim($("#keyword").val());
	if(keyword!=""){
		$.get(
			"../Controller/QuestionController.php",
			{action:"queryUserOrQuestionOrTopic",keyword:keyword},
			function(data){				
				var result=$.trim(data);
				result=$.parseJSON(result);
				var usersHtml="";
				var questionsHtml="";
				var topicsHtml="";
				if(result.queryResult=="1" || result.queryResult=="2"){
					alert("用户需要登录系统，且关键字不能为空");
				}
				else{
					result.queryResult.forEach(function(value,index){
						if(value.type=="user"){
							usersHtml+="<p><a target='_blank' href='person.php?userId="+value.id+"'>"+value.content+"</a></p>";
						}
						if(value.type=="question"){
							questionsHtml+="<p><a target='_blank' href='questionDetails.php?questionId="+value.id+"'>"+value.content+"</a></p>";
						}
						if(value.type=="topic"){
							topicsHtml+="<p><a target='_blank' href='topicDetails.php?topicId="+value.id+"'>"+value.content+"</a></p>";
						}
					});
					$("#relatedUsers").html(usersHtml);
					$("#relatedQuestions").html(questionsHtml);
					$("#relatedTopics").html(topicsHtml);
					showQueryResult();
				}
			}
		);
	}
	else{
		alert("关键字不能为空");
	}	
}

/**
 * 显示查询结果
 */
function showQueryResult(){
	$(".searchResultDiv").show();
	$(".detailsDiv").hide();
	$(".queryDiv").hide();
	$(".editDiv").hide();
	$(".createDiv").hide();
}