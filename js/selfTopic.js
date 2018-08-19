$(function(){
	//将菜单项的当前页菜单增加选中样式
	$("#menuUl>li>a[href='selfTopic.php']").parent().addClass("active");
	
	$("#createTopicBtn").on("click",function(){
		createNewTopic();
	});
		
	//getSelfTopicList();//为了方便显示分页，使用下面的函数。但是在添加话题成功之后还会调用它
	
	searchTopicListByContentOrDescription();
	
	loadKeyword();//分页的搜索信息加载时，要在输入框中写入关键字
		
	//点击“返回话题列表时”返回
	$("#returnListBtn").on("click",function(){
		$(".detailsDiv").hide();
		$(".queryDiv").show();
		$(".editDiv").hide();
		$(".createDiv").hide();
	});
	
	//加载话题分类，用于用户在创建话题时选择
	loadTopicTypes();
	
	//如果是从文章页创建话题，就显示创建的div
	createTopicByArticleTitle();
	
});

/**
 * 如果是从文章页创建话题，就显示创建的div
 */
function createTopicByArticleTitle(){
	var articleTitle=$("#articleTitleHidden").attr("value");
	if(articleTitle!=""){
		$("#inputTopicContent").val(articleTitle);
		$(".detailsDiv").hide();
		$(".queryDiv").hide();
		$(".editDiv").hide();
		$(".createDiv").show();
	}
}

function loadKeyword(){
	var keyword=$("#keywordHidden").attr("value");
	$("#keyword").val(keyword);
}

function createNewTopic(){
	var inputTopicType=$("#inputTopicType").val();
	var inputTopicContent=$.trim($("#inputTopicContent").val());
	if(inputTopicContent.length<=0){
		alert("话题不得为空");
		return;
	}
	var topicDescription=$("#editor").html();
	var token=$("#token").val();
	$.post(
		"../Controller/SelfTopicController.php",
		{action:"createNewTopic",token:token,topicType:inputTopicType,
			topicContent:inputTopicContent,topicDescription:topicDescription},
		function(data){
			var result=$.trim(data);
			var pattern=new RegExp("\{([^\{]+)[\s\S]*(\})$","gi");//使用正则表达式检测结果是否为json格式，以{开头，以}结尾，中间任意字符
			if(pattern.test(result)){
				result=$.parseJSON(result);
				if(result.count==1){
					alert("话题添加成功");
					getSelfTopicList();
				}
				else{
					alert("话题添加失败，请检查是否话题标题重复了");
				}
			}else{
				result=decodeURI(result);
				var reg=/\"/g;
				alert(result.replace(reg,''));
			}
		}
	);
}

/**
 * 下面的函数是用来获取用户个人话题列表的，但是为了方便使用分页，在实际中没有使用到下面的函数
 * 而是在所有时候都使用的searchTopicListByContentOrDescription()
 */
function getSelfTopicList(){
	//现将值转化成一个合理的页数值
	var page=$.trim($("#pageHidden").attr("value"));
	page=$.isNumeric(page)?page:1;//不是数字时设为1
	page=page<1?1:page;//小于1时设为1
	$.post(
		"../Controller/SelfTopicController.php",
		{action:"getSelfTopicList",page:page},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			var topicList="";
			result.topics.forEach(function(value,index){
				topicList+="<tr>";
				// topicList+="<td>"+value.asker+"</td>";
				topicList+="<td>"+value.askDate+"</td>";
				topicList+="<td>"+value.topicType+"</td>";
				topicList+="<td><a target='_blank' href='../forum/topicDetails.php?topicId="+value.topicId+"'>"+value.content+"</a></td>";

				if(value.enable=="1"){
					topicList+="<td><button class='btn btn-warning' onClick='disableSelfTopic(this)' value='"+value.topicId+"'>不公开</button></td>";
				}
				else{
					topicList+="<td><button class='btn btn-success' onClick='enableSelfTopic(this)' value='"+value.topicId+"'>公开</button></td>";
				}
				topicList+="<td><button class='btn btn-danger' onClick='deleteSelfTopic(this)' value='"+value.topicId+"'>删除</button></td>";
				topicList+="</tr>";
			});
			$("#topicsTable tbody").html(topicList);
			//调用MyPager.js中定义的写分页信息
			writePager(result,page,"selfTopic.php");
		}
	);
	$(".detailsDiv").hide();
	$(".queryDiv").show();
	$(".editDiv").hide();
	$(".createDiv").hide();
}

function searchTopic(){
	var keyword=$.trim($("#keyword").val());
	$("#keywordHidden").attr("value",keyword);
	searchTopicListByContentOrDescription();
}

function searchTopicListByContentOrDescription(){
	var keyword=$.trim($("#keywordHidden").attr("value"));
	//现将值转化成一个合理的页数值
	var page=$.trim($("#pageHidden").attr("value"));
	page=$.isNumeric(page)?page:1;//不是数字时设为1
	page=page<1?1:page;//小于1时设为1
	$.get(
		"../Controller/SelfTopicController.php",
		{action:"searchTopicListByContentOrDescription",keyword:keyword,page:page},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			var topicList="";
			result.topics.forEach(function(value,index){
				topicList+="<tr>";
				// topicList+="<td>"+value.asker+"</td>";
				topicList+="<td>"+value.askDate+"</td>";
				topicList+="<td>"+value.topicType+"</td>";
				topicList+="<td><a target='_blank' href='../forum/topicDetails.php?topicId="+value.topicId+"'>"+value.content+"</a></td>";

				if(value.enable=="1"){
					topicList+="<td><button class='btn btn-warning' onClick='disableSelfTopic(this)' value='"+value.topicId+"'>不公开</button></td>";
				}
				else{
					topicList+="<td><button class='btn btn-success' onClick='enableSelfTopic(this)' value='"+value.topicId+"'>公开</button></td>";
				}
				topicList+="<td><button class='btn btn-danger' onClick='deleteSelfTopic(this)' value='"+value.topicId+"'>删除</button></td>";
				topicList+="</tr>";
			});
			$("#topicsTable tbody").html(topicList);
			
			var paras=(keyword=="")?"":("&keyword="+keyword);//如果关键字为空，就让参数为空
			writePager(result,page,"selfTopic.php",paras);
		}
	);
}

function disableSelfTopic(obj){
	var topicId=$(obj).attr("value");
	$.post(		
		"../Controller/SelfTopicController.php",
		{action:"disableSelfTopic",topicId:topicId},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			if(result.affectRow==1){
				//将原本的禁用连接改为启用连接
				var enableBtnHtml="<button class='btn btn-success' onClick='enableSelfTopic(this)' value='"+topicId+"'>公开</button>";
				$(obj).parent().html(enableBtnHtml);
			}
			else if(result.affectRow==0){
				alert("话题已经禁用，无需再次禁用");
			}
			else{
				alert(result.affectRow);
			}
		}
	);
}

/**
 * 下面是将话题改为启用状态的函数
 */
function enableSelfTopic(obj){
	var topicId=$(obj).attr("value");
	$.post(		
		"../Controller/SelfTopicController.php",
		{action:"enableSelfTopic",topicId:topicId},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			if(result.affectRow==1){
				//将原本的启用连接改为禁用连接
				var disableBtnHtml="<button class='btn btn-warning' onClick='disableSelfTopic(this)' value='"+topicId+"'>不公开</button>";
				$(obj).parent().html(disableBtnHtml);
			}
			else if(result.affectRow==0){
				alert("话题已经启用，无需再次启用");
			}
			else{
				alert(result.affectRow);
			}
		}
	);
}

/**
 * 删除自己的话题
 */
function deleteSelfTopic(obj){
	if(confirm("确定要删除该话题吗？")){
		var topicId=$(obj).attr("value");
		$.post(
			"../Controller/SelfTopicController.php",
			{action:"deleteSelfTopic",topicId:topicId},
			function(data){
				var result=$.trim(data);
				result=$.parseJSON(result);
				if(result.deleteRow==1){
					$(obj).parent().parent().remove();
				}
				else if(result.deleteRow==0){
					alert("话题删除失败");
				}
				else{
					alert(result.affectRow);
				}
			}
		);
	}
}

/**
 * 加载用户话题
 */
function loadTopicTypes(){
	$.get(
		"../Controller/SelfTopicController.php",
		{action:"loadTopicTypes"},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			var typeHtml="";
			result.types.forEach(function(value,index){
				typeHtml+="<option value='"+value.name+"'>"+value.name+"</option>";
			});
			$("#inputTopicType").html(typeHtml);
		}
	);
}
