$(function(){
	//将菜单项的当前页菜单增加选中样式
	$("#menuUl>li>a[href='systemSetting.php']").parent().addClass("active");	
});

/**
 * 修改系统设置
 */
function changeSystemSettingInfo(){
	if(checkSettingInfo()){	
		var maxQuestion=parseInt($.trim($("#inputMaxQuestion").val()));
		var maxTopic=parseInt($.trim($("#inputMaxTopic").val()));
		var maxArticle=parseInt($.trim($("#inputMaxArticle").val()));
		var maxComment=parseInt($.trim($("#inputMaxComment").val()));
		var maxFindPassword=parseInt($.trim($("#inputMaxFindPwd").val()));
		var maxEmailCount=parseInt($.trim($("#inputMaxEmailCount").val()));
		$.ajax({
			url:"../Controller/SystemSettingController.php",
			data:{action:"changeSystemSettingInfo",maxQuestion:maxQuestion,maxTopic:maxTopic,maxArticle:maxArticle,
				maxComment:maxComment,maxFindPassword:maxFindPassword,maxEmailCount:maxEmailCount},
			success:function(data){
				var result=$.trim(data);
				result=$.parseJSON(result);
				if(result.changeRow>0){
					alert("系统设置修改成功");
					window.location.reload();
				}
				else if(result.changeRow==0){
					alert("信息未改动，请勿提交");
				}
				else{
					alert("发生了未知错误");
				}
			}
		});		
	}
	else{
		alert("设置中的数据类型不合规范");
	}
}

/**
 * 检测输入信息是否为符合要求的数字
 */
function checkSettingInfo(){
	var maxQuestion=$.trim($("#inputMaxQuestion").val());
	var maxTopic=$.trim($("#inputMaxTopic").val());
	var maxArticle=$.trim($("#inputMaxArticle").val());
	var maxComment=$.trim($("#inputMaxComment").val());
	var maxFindPassword=$.trim($("#inputMaxFindPwd").val());
	var maxEmailCount=$.trim($("#inputMaxEmailCount").val());
	if($.isNumeric(maxQuestion) && $.isNumeric(maxTopic) && $.isNumeric(maxArticle)
		 && $.isNumeric(maxComment) && $.isNumeric(maxFindPassword) && $.isNumeric(maxEmailCount)
		 && maxQuestion>=0 && maxTopic>=0 && maxArticle>=0 
		 && maxComment>=0 && maxFindPassword>=0 && maxEmailCount>=0){
		 	return true;
	}
	else{
		return false;
	}
}
