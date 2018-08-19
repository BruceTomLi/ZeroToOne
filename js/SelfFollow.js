$(function(){
	//页面载入时将菜单项改为active状态
	$("#menuUl>li>a[href='selfFollow.php']").parent().addClass("active");
	//页面加载时加载出用户关注的人的列表
	loadUserFollowedUsers();
	//页面加载时加载出用户关注的问题的列表
	loadUserFollowedQuestions();
	//页面加载时加载出用户关注的话题的列表
	loadUserFollowedTopics();
	//显示关注的用户
	showFollowUsers();
});

/**
 * 加载用户关注的问题
 */
function loadUserFollowedQuestions(){
	$.get(
		"../Controller/SelfFollowedController.php",
		{action:"loadUserFollowedQuestions"},		
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);			
			var questionList="";
			result.forEach(function(value,index){
				questionList+="<tr>";
				questionList+="<td><a target='_blank' href='../forum/questionDetails.php?questionId="+value.questionId+"'>"+value.content+"</a></td>";
				questionList+="<td>"+value.asker+"</td>";
				questionList+="<td>"+value.askDate+"</td>";
				questionList+="<td>"+value.questionType+"</td>";				
				questionList+="</tr>";
			});
			$(".hasFollowedQuestion>.selfTable>tbody").html(questionList);
		}
	);
}

/**
 * 加载用户关注的话题
 */
function loadUserFollowedTopics(){
	$.get(
		"../Controller/SelfFollowedController.php",
		{action:"loadUserFollowedTopics"},		
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);			
			var topicList="";
			result.forEach(function(value,index){
				topicList+="<tr>";
				topicList+="<td><a target='_blank' href='../forum/topicDetails.php?topicId="+value.topicId+"'>"+value.content+"</a></td>";
				topicList+="<td>"+value.asker+"</td>";
				topicList+="<td>"+value.askDate+"</td>";
				topicList+="<td>"+value.topicType+"</td>";				
				topicList+="</tr>";
			});
			$(".hasFollowedTopic>.selfTable>tbody").html(topicList);
		}
	);
}

/**
 * 加载用户关注的人
 */
function loadUserFollowedUsers(){
	$.get(
		"../Controller/SelfFollowedController.php",
		{action:"loadUserFollowedUsers"},		
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);			
			var userList="";
			result.forEach(function(value,index){
				userList+="<tr>";
				userList+="<td><a target='_blank' href='../forum/person.php?userId="+value.userId+"'>"+value.username+"</a></td>";
				var userSex=value.sex=='1'?'男':'女';
				userList+="<td>"+userSex+"</td>";
				userList+="<td>"+value.email+"</td>";
				userList+="<td>"+value.oneWord+"</td>";
				userList+="</tr>";
			});
			$(".hasFollowedUser>.selfTable>tbody").html(userList);
		}
	);
}

/**
 * 取消关注用户的函数
 */
function cancelFollowUser(obj){
	var userId=$(obj).attr("value");
	$.get(
		"../Controller/SelfFollowedController.php",
		{action:"cancelFollowUser",userId:userId},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			if(result.affectRow==1){
				$(obj).parent().parent().remove();
				alert("取消关注了该用户");
			}
			else if(result.affectRow==0){
				alert("你已经取消关注了该用户，不用重复操作");
			}
			else{
				alert(result.affectRow);
			}
		}
	);
}
//显示关注的用户
function showFollowUsers(){
	$("#fuserLi").addClass("active");
	$("#fuserLi").siblings().removeClass();
	$("#followedUsersDiv").show();
	$("#followedQuestionsDiv").hide();
	$("#followedTopicsDiv").hide();
}
//显示关注的问题
function showFollowQuestions(){
	$("#fquestionLi").addClass("active");
	$("#fquestionLi").siblings().removeClass();
	$("#followedUsersDiv").hide();
	$("#followedQuestionsDiv").show();
	$("#followedTopicsDiv").hide();
}
//显示关注的话题
function showFollowTopics(){
	$("#ftopicLi").addClass("active");
	$("#ftopicLi").siblings().removeClass();
	$("#followedUsersDiv").hide();
	$("#followedQuestionsDiv").hide();
	$("#followedTopicsDiv").show();
}
