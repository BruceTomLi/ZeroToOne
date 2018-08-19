/**
 * 用jquery在页面加载时给元素注册的事件，对于后来在页面中添加的元素该事件无效
 * 所以以后在编写页面动态生成的元素事件时，好的做法是直接在元素中添加onClick方法，可以将this
 * 作为参数传递
 */
$(function(){
	//将问题按钮设为激活
	$("#menuBtn a[href='question.php']").parent().addClass("active");
	$("#menuBtn a[href='question.php']").parent().siblings().removeClass();
	//页面加载时加载所有问题的列表
	getAllQuestion();
	
	//点击“返回问题列表时”返回
	$("#returnListBtn").on("click",function(){
		$(".detailsDiv").hide();
		$(".queryDiv").show();
		$(".editDiv").hide();
		$(".createDiv").hide();
		$(".searchResultDiv").hide();
	});
});

/**
 * 获取所有问题的函数
 * 不需要传入参数
 */
function getAllQuestion(){
	$("#latestLi").siblings().removeClass();
	$("#latestLi").addClass("active");
	//现将值转化成一个合理的页数值
	var page=$.trim($("#pageHidden").attr("value"));
	page=$.isNumeric(page)?page:1;//不是数字时设为1
	page=page<1?1:page;//小于1时设为1
	$.ajax({
		url:"../Controller/QuestionController.php",
		data:{action:"getAllQuestion",page:page},
		beforeSend:function(){
			$("#loadingModal").modal('show');
		},
		success:function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			var questionList="";
			result.questions.forEach(function(value,index){
				questionList+="<tr>";
				questionList+="<td><img src='../img/tradeImgs/"+value.questionType+".gif'></td>";
				questionList+="<td><p><a class='btn-link' href='questionDetails.php?questionId="+value.questionId+"'>"+value.content+"</a></p>";
				questionList+="<p><span>提问者：<a target='_blank' href='person.php?userId="+value.askerId+"'>"+value.asker+"</a></span>";
				questionList+="&nbsp;&nbsp;<span>问题类型："+value.questionType+"</span>";
				questionList+="&nbsp;&nbsp;<span>提问时间："+value.askDate+"</span></p></td>";				
				//下面加载生成的mini头像				
				var arr = "";
				var suffix= "";
				var miniHeading="../img/boy_mini.gif";
				if(value.heading!=""){
					miniHeading=value.heading;
					//分别提取后缀和处后缀外的其他部分
					arr=(value.heading).split('.');
					suffix=arr[arr.length-1];
					miniHeading=miniHeading.substr(0,miniHeading.length-suffix.length-1);
					miniHeading+="_mini."+suffix;
				}
				else{
					if(value.sex=="1"){
						miniHeading="../img/boy_mini.gif";
					}
					else{
						miniHeading="../img/girl_mini.gif";
					}
				}
				questionList+="<td><p>贡献</p><p><a target='_blank' href='person.php?userId="+value.askerId+"'>"+"<img src='"+miniHeading+"' /></a></p></td>";
				questionList+="</tr>";
			});
			$(".questionTable tbody").html(questionList);
			$("#queryDivTitle").text("问题");
			writePager(result,page,"question.php");
			
			$("#loadingModal").modal('hide');			
		}
	});		
}


/**
 * 获取排名前十位（评论数）的问题，不进行分页
 */
function getTenHotQuestions(){
	$("#mostHotLi").siblings().removeClass();
	$("#mostHotLi").addClass("active");
	$("#paginationDiv").empty();
	$.ajax({
		url:"../Controller/QuestionController.php",
		data:{action:"getTenHotQuestions"},
		beforeSend:function(){
			$("#loadingModal").modal('show');
		},
		success:function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			var questionList="";
			result.questions.forEach(function(value,index){
				questionList+="<tr>";
				questionList+="<td><img src='../img/tradeImgs/"+value.questionType+".gif'></td>";
				questionList+="<td><p><a class='btn-link' href='questionDetails.php?questionId="+value.questionId+"'>"+value.content+"</a></p>";
				questionList+="<p><span>提问者：<a target='_blank' href='person.php?userId="+value.askerId+"'>"+value.asker+"</a></span>";
				questionList+="&nbsp;&nbsp;<span>问题类型："+value.questionType+"</span>";
				questionList+="&nbsp;&nbsp;<span>提问时间："+value.askDate+"</span>";
				questionList+="&nbsp;&nbsp;<span>评论数："+value.commentCount+"</span></p></td>";
				//下面加载生成的mini头像				
				var arr = "";
				var suffix= "";
				var miniHeading="../img/boy_mini.gif";
				if(value.heading!=""){
					miniHeading=value.heading;
					//分别提取后缀和处后缀外的其他部分
					arr=(value.heading).split('.');
					suffix=arr[arr.length-1];
					miniHeading=miniHeading.substr(0,miniHeading.length-suffix.length-1);
					miniHeading+="_mini."+suffix;
				}
				else{
					if(value.sex=="1"){
						miniHeading="../img/boy_mini.gif";
					}
					else{
						miniHeading="../img/girl_mini.gif";
					}
				}
				questionList+="<td><p>贡献</p><p><a target='_blank' href='person.php?userId="+value.askerId+"'>"+"<img src='"+miniHeading+"' /></a></p></td>";
				questionList+="</tr>";
			});
			$(".questionTable tbody").html(questionList);
			$("#queryDivTitle").text("热点");
			$("#loadingModal").modal('hide');			
		}
	});		
}

/**
 * 获取十个和登录者相关的问题，不进行分页
 */
function recommendQuestionsByJob(){
	$("#recommendLi").siblings().removeClass();
	$("#recommendLi").addClass("active");
	$("#paginationDiv").empty();
	$.ajax({
		url:"../Controller/QuestionController.php",
		data:{action:"recommendQuestionsByJob"},
		beforeSend:function(){
			$("#loadingModal").modal('show');
		},
		success:function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			var questionList="";
			result.questions.forEach(function(value,index){
				questionList+="<tr>";
				questionList+="<td><img src='../img/tradeImgs/"+value.questionType+".gif'></td>";
				questionList+="<td><p><a class='btn-link' href='questionDetails.php?questionId="+value.questionId+"'>"+value.content+"</a></p>";
				questionList+="<p><span>提问者：<a target='_blank' href='person.php?userId="+value.askerId+"'>"+value.asker+"</a></span>";
				questionList+="&nbsp;&nbsp;<span>问题类型："+value.questionType+"</span>";
				questionList+="&nbsp;&nbsp;<span>提问时间："+value.askDate+"</span></p></td>";
				//下面加载生成的mini头像				
				var arr = "";
				var suffix= "";
				var miniHeading="../img/boy_mini.gif";
				if(value.heading!=""){
					miniHeading=value.heading;
					//分别提取后缀和处后缀外的其他部分
					arr=(value.heading).split('.');
					suffix=arr[arr.length-1];
					miniHeading=miniHeading.substr(0,miniHeading.length-suffix.length-1);
					miniHeading+="_mini."+suffix;
				}
				else{
					if(value.sex=="1"){
						miniHeading="../img/boy_mini.gif";
					}
					else{
						miniHeading="../img/girl_mini.gif";
					}
				}
				questionList+="<td><p>贡献</p><p><a target='_blank' href='person.php?userId="+value.askerId+"'>"+"<img src='"+miniHeading+"' /></a></p></td>";
				questionList+="</tr>";
			});
			$(".questionTable tbody").html(questionList);
			$("#queryDivTitle").text("推荐");
			$("#loadingModal").modal('hide');			
		}
	});		
}

/**
 * 获取等待用户回复的问题（没有评论的问题），也只取十个问题
 */
function getWaitReplyQuestions(){
	$("#waitReplyLi").siblings().removeClass();
	$("#waitReplyLi").addClass("active");
	$("#paginationDiv").empty();
	$.ajax({
		url:"../Controller/QuestionController.php",
		data:{action:"getWaitReplyQuestions"},
		beforeSend:function(){
			$("#loadingModal").modal('show');
		},
		success:function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			var questionList="";
			result.questions.forEach(function(value,index){
				questionList+="<tr>";
				questionList+="<td><img src='../img/tradeImgs/"+value.questionType+".gif'></td>";
				questionList+="<td><p><a class='btn-link' href='questionDetails.php?questionId="+value.questionId+"'>"+value.content+"</a></p>";
				questionList+="<p><span>提问者：<a target='_blank' href='person.php?userId="+value.askerId+"'>"+value.asker+"</a></span>";
				questionList+="&nbsp;&nbsp;<span>问题类型："+value.questionType+"</span>";
				questionList+="&nbsp;&nbsp;<span>提问时间："+value.askDate+"</span></p></td>";
				//下面加载生成的mini头像				
				var arr = "";
				var suffix= "";
				var miniHeading="../img/boy_mini.gif";
				if(value.heading!=""){
					miniHeading=value.heading;
					//分别提取后缀和处后缀外的其他部分
					arr=(value.heading).split('.');
					suffix=arr[arr.length-1];
					miniHeading=miniHeading.substr(0,miniHeading.length-suffix.length-1);
					miniHeading+="_mini."+suffix;
				}
				else{
					if(value.sex=="1"){
						miniHeading="../img/boy_mini.gif";
					}
					else{
						miniHeading="../img/girl_mini.gif";
					}
				}
				questionList+="<td><p>贡献</p><p><a target='_blank' href='person.php?userId="+value.askerId+"'>"+"<img src='"+miniHeading+"' /></a></p></td>";
				questionList+="</tr>";
			});
			$(".questionTable tbody").html(questionList);
			$("#queryDivTitle").text("等待回答");
			$("#loadingModal").modal('hide');			
		}
	});		
}
