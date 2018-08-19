$(function(){
	//将菜单项的当前页菜单增加选中样式
	$("#menuUl>li>a[href='topics.php']").parent().addClass("active");	
	
	//给话题管理员加载所有话题
	//getAllTopicListForManager();
	queryTopicsByKeyword();
	
	loadKeyword();
});
function loadKeyword(){
	var keyword=$("#keywordHidden").attr("value");
	$("#keyword").val(keyword);
}
/**
 * 加载所有的话题，包括被禁用的话题
 */
function getAllTopicListForManager(){
	$.get(
		"../Controller/TopicManageController.php",
		{action:"getAllTopicListForManager"},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			var topicList="";
			result.topics.forEach(function(value,index){
				topicList+="<tr>";
				if($(".handleMultiDiv").css("display")=="block"){
					topicList+="<td class='forSelectMulti' style='display:block'><label class='checkbox'><input type='checkbox' value='"+value.topicId+"'></label></td>";
				}
				else{
					topicList+="<td class='forSelectMulti'><label class='checkbox'><input type='checkbox' value='"+value.topicId+"'></label></td>";
				}
				topicList+="<td><a target='_blank' href='../forum/person.php?userId="+value.askerId+"'>"+value.askerName+"</a></td>";
				topicList+="<td>"+value.askDate+"</td>";
				topicList+="<td>"+value.topicType+"</td>";
				topicList+="<td><a target='_blank' href='../forum/topicDetails.php?topicId="+value.topicId+"'>"+value.content+"</a></td>";

				if(value.enable=="1"){
					topicList+="<td><button class='btn btn-warning' onClick='disableTopic(this)' value='"+value.topicId+"'>不公开</button></td>";
				}
				else{
					topicList+="<td><button class='btn btn-success' onClick='enableTopic(this)' value='"+value.topicId+"'>公开</button></td>";
				}
				topicList+="<td><button class='btn btn-danger' onClick='deleteTopic(this)' value='"+value.topicId+"'>删除</button></td>";
				
				topicList+="</tr>";
			});
			$("#allTopicsTable tbody").html(topicList);
		}
	);
}

function searchTopic(){
	var keyword=$.trim($("#keyword").val());
	$("#keywordHidden").attr("value",keyword);
	queryTopicsByKeyword();
}
/**
 * 检索话题，包括被禁用的话题
 */
function queryTopicsByKeyword(){
	var keyword=$.trim($("#keywordHidden").attr("value"));
	//现将值转化成一个合理的页数值
	var page=$.trim($("#pageHidden").attr("value"));
	page=$.isNumeric(page)?page:1;//不是数字时设为1
	page=page<1?1:page;//小于1时设为1
	$.get(
		"../Controller/TopicManageController.php",
		{action:"queryTopicsByKeyword",keyword:keyword,page:page},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			var topicList="";
			result.topics.forEach(function(value,index){
				topicList+="<tr>";
				if($(".handleMultiDiv").css("display")=="block"){
					topicList+="<td class='forSelectMulti' style='display:block'><label class='checkbox'><input type='checkbox' value='"+value.topicId+"'></label></td>";
				}
				else{
					topicList+="<td class='forSelectMulti'><label class='checkbox'><input type='checkbox' value='"+value.topicId+"'></label></td>";
				}
				topicList+="<td><a target='_blank' href='../forum/person.php?userId="+value.askerId+"'>"+value.asker+"</a></td>";
				topicList+="<td>"+value.askDate+"</td>";
				topicList+="<td>"+value.topicType+"</td>";
				topicList+="<td><a target='_blank' href='../forum/topicDetails.php?topicId="+value.topicId+"'>"+value.content+"</a></td>";

				if(value.enable=="1"){
					topicList+="<td><button class='btn btn-warning' onClick='disableTopic(this)' value='"+value.topicId+"'>不公开</button></td>";
				}
				else{
					topicList+="<td><button class='btn btn-success' onClick='enableTopic(this)' value='"+value.topicId+"'>公开</button></td>";
				}
				topicList+="<td><button class='btn btn-danger' onClick='deleteTopic(this)' value='"+value.topicId+"'>删除</button></td>";
				
				topicList+="</tr>";
			});
			$("#allTopicsTable tbody").html(topicList);
			var paras=(keyword=="")?"":("&keyword="+keyword);//如果关键字为空，就让参数为空
			writePager(result,page,"topics.php",paras);
		}
	);
}

function disableTopic(obj){
	var topicId=$(obj).attr("value");
	$.post(		
		"../Controller/TopicManageController.php",
		{action:"disableTopic",topicId:topicId},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			if(result.affectRow==1){
				//将原本的禁用连接改为启用连接
				//var enableBtnHtml="<button class='btn btn-success' onClick='enableTopic(this)' value='"+topicId+"'>公开</button>";
				//$(obj).parent().html(enableBtnHtml);
				queryTopicsByKeyword();
			}
		}
	);
}

/**
 * 下面是将话题改为启用状态的函数
 */
function enableTopic(obj){
	var topicId=$(obj).attr("value");
	$.post(		
		"../Controller/TopicManageController.php",
		{action:"enableTopic",topicId:topicId},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			if(result.affectRow==1){
				//将原本的启用连接改为禁用连接
				//var disableBtnHtml="<button class='btn btn-warning' onClick='disableTopic(this)' value='"+topicId+"'>不公开</button>";
				//$(obj).parent().html(disableBtnHtml);
				queryTopicsByKeyword();
			}
		}
	);
}

/**
 * 删除自己的话题
 */
function deleteTopic(obj){
	if(confirm("确定要删除该话题吗？")){
		deleteSingleTopic(obj);
	}
}

/**
 * 删除自己的话题
 */
function deleteSingleTopic(obj){
	var topicId=$(obj).attr("value");
	$.post(
		"../Controller/TopicManageController.php",
		{action:"deleteTopic",topicId:topicId},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			if(result.affectRow==1){
				//$(obj).parent().parent().remove();
				queryTopicsByKeyword();
			}
		}
	);
}

//下面是实现全选的函数，在jquery1.6之后，对于checked属性，就要用prop实现全选，原来的attr就难以完成了
function selectAll(){
	if($('#selectAll').prop('ckecked')!=true){
		$('#selectAll').prop('ckecked',true);
		$("tbody :checkbox").prop("checked",true);
	}
	else{
		$('#selectAll').prop('ckecked',false);
		$("tbody :checkbox").prop("checked",false);
	}
	
}
//下面是实现反选的函数，下面的函数实现的比较巧妙，因为jquery中的each函数比较特殊，无法使用一般的判断分支进行处理
//使用如下方式取与原有选中状态相反的状态，就能够实现取反了
function selectReverse(){	
	$('tbody input:checkbox').each(function(){
		$(this).prop('checked',!$(this).prop('checked'));
	})
}

/**
 * 禁用话题
 */
function disableSelectTopics(){
	var selectedTopics=new Array();
	$('tbody input[type="checkbox"]:checked').each(function(){ 
		selectedTopics.push($(this).val()); 
	}); 
	if(selectedTopics.length==0){
		alert("您没有选中任何文章，请在选中之后再批量操作");
		return false;
	}
	else{
		$('tbody input[type="checkbox"]:checked').each(function(){ 
			disableTopic(this);
		}); 
	}
}

/**
 * 启用话题
 */
function enableSelectTopics(){
	var selectedTopics=new Array();
	$('tbody input[type="checkbox"]:checked').each(function(){ 
		selectedTopics.push($(this).val()); 
	}); 
	if(selectedTopics.length==0){
		alert("您没有选中任何文章，请在选中之后再批量操作");
		return false;
	}
	else{
		$('tbody input[type="checkbox"]:checked').each(function(){ 
			enableTopic(this);
		}); 
	}
}

/**
 * 删除话题
 */
function deleteSelectTopics(){
	if(confirm("将会删除所有选中话题，确定要这么做吗？")){
		var selectedTopics=new Array();
		$('tbody input[type="checkbox"]:checked').each(function(){ 
			selectedTopics.push($(this).val()); 
		}); 
		if(selectedTopics.length==0){
			alert("您没有选中任何文章，请在选中之后再批量操作");
			return false;
		}
		else{
			$('tbody input[type="checkbox"]:checked').each(function(){ 
				deleteSingleTopic(this);
			}); 
		}
	}
}