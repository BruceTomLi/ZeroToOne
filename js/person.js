$(function(){
	loadKindsUserInfoCount();	
});

/**
 * 关注用户的函数
 */
function followUser(obj){
	var userId=$(obj).attr("value");
	if(userId!=""){
		$.get(
			"../Controller/QuestionController.php",
			{action:"addFollow",starId:userId,followType:"user"},
			function(data){
				var result=$.trim(data);
				result=$.parseJSON(result);
				if(result.affectRow==1){
					alert("成功关注了该用户");
					window.location.reload();
					// var cancelFollowUserHtml="<button class='btn btn-warning' id='cancelfollowTa' onclick='cancelFollowUser(this)' value='"+userId+"'>取消关注Ta</button>";
					// $(obj).parent().html(cancelFollowUserHtml);
				}
				else if(result.affectRow==0){
					alert("你已经关注了该用户，不需要重新关注");
				}
				else{
					alert(result.affectRow);
				}
			}
		);
	}
	else{
		alert("未获取到用户信息，无法进行关注");
	}
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
				alert("取消关注了该用户");
				window.location.reload();
				// var followUserHtml="<button class='btn btn-success' id='followTa' onclick='followUser(this)' value='"+userId+"'>关注Ta</button>";
				// $(obj).parent().html(followUserHtml);
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

function loadKindsUserInfoCount(){
	var userId=$("#userIdHidden").attr("value");
	$.get(
		"../Controller/PersonController.php",
		{action:"loadKindsUserInfoCount",userId:userId},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			result.userCountInfo.forEach(function(value,index){	
				var askQuestionCount=parseInt(value.askQuestionCount)>10?10:parseInt(value.askQuestionCount);
				var createTopicCount=parseInt(value.createTopicCount)>10?10:parseInt(value.createTopicCount);
				var commentQuestionCount=parseInt(value.commentQuestionCount)>10?10:parseInt(value.commentQuestionCount);
				var commentTopicCount=parseInt(value.commentTopicCount)>10?10:parseInt(value.commentTopicCount);
				var fansCount=parseInt(value.fansCount)>10?10:parseInt(value.fansCount);
				var starsCount=parseInt(value.starsCount)>10?10:parseInt(value.starsCount);
				//在使用echarts的时候，需要将数据封装在数组中传递，否则报错
				var countArr=[askQuestionCount,createTopicCount,commentQuestionCount,
								commentTopicCount,fansCount,starsCount];
				var username=[result.username];
				showKindsUserInfoCount(username,countArr);
			});
		}
	);
}

/**
 * 加载网站男女人数信息统计
 */
function showKindsUserInfoCount(username,countArr){
	var dom = document.getElementById("userInfoCountContainer");
	var myChart = echarts.init(dom,'light');
	var app = {};//暂未发现其途
	window.onresize = myChart.resize;
	option = null;
	option = {
		title:{text:'Ta的个性图谱(>10按10显示)',left:'center'},
		tooltip:{},//这行代码使得鼠标放在饼图上时，显示信息，如果没有，就不现实
	    radar:[
	    	{
	    		indicator:[
	    			{text:'问题数',max:10},
	    			{text:'话题数',max:10},
	    			{text:'回答问题数',max:10},
	    			{text:'回答话题数',max:10},
	    			{text:'粉丝数',max:10},
	    			{text:'关注人数',max:10},
	    		],
            	radius: 90
	    	}
	    ],
	    series : [
	        {
	            name: 'Ta的个性图谱',
	            type: 'radar',
	            radarIndex: 0,
	            data:[
	                {
	                    value: countArr,//这里使用的参数是js的数组
	                    name: username,//这里使用的参数是js的数组，尽管数组中只有一个元素
	                    areaStyle: {
	                        normal: {
	                            opacity: 0.5,
	                            color: new echarts.graphic.RadialGradient(0.5, 0.5, 1, [
	                                {
	                                    color: '#B8D3E4',
	                                    offset: 0
	                                },
	                                {
	                                    color: '#72ACD1',
	                                    offset: 0
	                                }
	                            ])
	                        }
	                    }
	                }
	            ]
	        }
	    ]
	};
	if (option && typeof option === "object") {
	    myChart.setOption(option, true);
	}
}