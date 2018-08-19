$(function(){
	//将菜单项的当前页菜单增加选中样式
	$("#menuUl>li>a[href='questions.php']").parent().addClass("active");	
	
	//给问题管理员加载所有问题
	//getAllQuestionListForManager();
	queryQuestionsByKeyword();
	
	loadKeyword();
});
function loadKeyword(){
	var keyword=$("#keywordHidden").attr("value");
	$("#keyword").val(keyword);
}
/**
 * 加载所有的问题，包括被禁用的问题
 */
function getAllQuestionListForManager(){
	$.get(
		"../Controller/QuestionManageController.php",
		{action:"getAllQuestionListForManager"},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			var questionList="";
			result.questions.forEach(function(value,index){
				questionList+="<tr>";
				if($(".handleMultiDiv").css("display")=="block"){
					questionList+="<td class='forSelectMulti' style='display:block'><label class='checkbox'><input type='checkbox' value='"+value.questionId+"'></label></td>";
				}
				else{
					questionList+="<td class='forSelectMulti'><label class='checkbox'><input type='checkbox' value='"+value.questionId+"'></label></td>";
				}
				questionList+="<td>"+value.askerName+"</td>";
				questionList+="<td>"+value.askDate+"</td>";
				questionList+="<td>"+value.questionType+"</td>";
				questionList+="<td><a target='_blank' href='../forum/questionDetails.php?questionId="+value.questionId+"'>"+value.content+"</a></td>";

				if(value.enable=="1"){
					questionList+="<td><button class='btn btn-warning' onClick='disableQuestion(this)' value='"+value.questionId+"'>不公开</button></td>";
				}
				else{
					questionList+="<td><button class='btn btn-success' onClick='enableQuestion(this)' value='"+value.questionId+"'>公开</button></td>";
				}
				questionList+="<td><button class='btn btn-danger' onClick='deleteQuestion(this)' value='"+value.questionId+"'>删除</button></td>";
				
				questionList+="</tr>";
			});
			$("#allQuestionsTable tbody").html(questionList);
		}
	);
}

function searchQuestion(){
	var keyword=$.trim($("#keyword").val());
	$("#keywordHidden").attr("value",keyword);
	queryQuestionsByKeyword();
}
/**
 * 检索问题，包括被禁用的问题
 */
function queryQuestionsByKeyword(){
	var keyword=$.trim($("#keywordHidden").attr("value"));
	//现将值转化成一个合理的页数值
	var page=$.trim($("#pageHidden").attr("value"));
	page=$.isNumeric(page)?page:1;//不是数字时设为1
	page=page<1?1:page;//小于1时设为1
	$.get(
		"../Controller/QuestionManageController.php",
		{action:"queryQuestionsByKeyword",keyword:keyword,page:page},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			var questionList="";
			result.questions.forEach(function(value,index){
				questionList+="<tr>";
				if($(".handleMultiDiv").css("display")=="block"){
					questionList+="<td class='forSelectMulti' style='display:block'><label class='checkbox'><input type='checkbox' value='"+value.questionId+"'></label></td>";
				}
				else{
					questionList+="<td class='forSelectMulti'><label class='checkbox'><input type='checkbox' value='"+value.questionId+"'></label></td>";
				}
				questionList+="<td><a target='_blank' href='../forum/person.php?userId="+value.askerId+"'>"+value.asker+"</a></td>";
				questionList+="<td>"+value.askDate+"</td>";
				questionList+="<td>"+value.questionType+"</td>";
				questionList+="<td><a target='_blank' href='../forum/questionDetails.php?questionId="+value.questionId+"'>"+value.content+"</a></td>";

				if(value.enable=="1"){
					questionList+="<td><button class='btn btn-warning' onClick='disableQuestion(this)' value='"+value.questionId+"'>不公开</button></td>";
				}
				else{
					questionList+="<td><button class='btn btn-success' onClick='enableQuestion(this)' value='"+value.questionId+"'>公开</button></td>";
				}
				questionList+="<td><button class='btn btn-danger' onClick='deleteQuestion(this)' value='"+value.questionId+"'>删除</button></td>";
				
				questionList+="</tr>";
			});
			$("#allQuestionsTable tbody").html(questionList);
			var paras=(keyword=="")?"":("&keyword="+keyword);//如果关键字为空，就让参数为空
			writePager(result,page,"questions.php",paras);
		}
	);
}

function disableQuestion(obj){
	var questionId=$(obj).attr("value");
	$.post(		
		"../Controller/QuestionManageController.php",
		{action:"disableQuestion",questionId:questionId},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			if(result.affectRow==1){
				//将原本的禁用连接改为启用连接
				//var enableBtnHtml="<button class='btn btn-success' onClick='enableQuestion(this)' value='"+questionId+"'>公开</button>";
				//$(obj).parent().html(enableBtnHtml);
				queryQuestionsByKeyword();
			}
		}
	);
}

/**
 * 下面是将问题改为启用状态的函数
 */
function enableQuestion(obj){
	var questionId=$(obj).attr("value");
	$.post(		
		"../Controller/QuestionManageController.php",
		{action:"enableQuestion",questionId:questionId},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			if(result.affectRow==1){
				//将原本的启用连接改为禁用连接
				//var disableBtnHtml="<button class='btn btn-warning' onClick='disableQuestion(this)' value='"+questionId+"'>不公开</button>";
				//$(obj).parent().html(disableBtnHtml);
				queryQuestionsByKeyword();
			}
		}
	);
}

/**
 * 删除自己的问题
 */
function deleteQuestion(obj){
	if(confirm("确定要删除该问题吗？")){
		deleteSingleQuestion(obj);
	}
}

/**
 * 删除自己的问题
 */
function deleteSingleQuestion(obj){
	var questionId=$(obj).attr("value");
	$.post(
		"../Controller/QuestionManageController.php",
		{action:"deleteQuestion",questionId:questionId},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			if(result.affectRow==1){
				//$(obj).parent().parent().remove();
				queryQuestionsByKeyword();
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
 * 禁用问题
 */
function disableSelectQuestions(){
	var selectedQuestions=new Array();
	$('tbody input[type="checkbox"]:checked').each(function(){ 
		selectedQuestions.push($(this).val()); 
	}); 
	if(selectedQuestions.length==0){
		alert("您没有选中任何文章，请在选中之后再批量操作");
		return false;
	}
	else{
		$('tbody input[type="checkbox"]:checked').each(function(){ 
			disableQuestion(this);
		}); 
	}
}

/**
 * 启用问题
 */
function enableSelectQuestions(){
	var selectedQuestions=new Array();
	$('tbody input[type="checkbox"]:checked').each(function(){ 
		selectedQuestions.push($(this).val()); 
	}); 
	if(selectedQuestions.length==0){
		alert("您没有选中任何文章，请在选中之后再批量操作");
		return false;
	}
	else{
		$('tbody input[type="checkbox"]:checked').each(function(){ 
			enableQuestion(this);
		}); 
	}
}

/**
 * 删除问题
 */
function deleteSelectQuestions(){
	if(confirm("将会删除所有选中问题，确定要这么做吗？")){
		var selectedQuestions=new Array();
		$('tbody input[type="checkbox"]:checked').each(function(){ 
			selectedQuestions.push($(this).val()); 
		}); 
		if(selectedQuestions.length==0){
			alert("您没有选中任何文章，请在选中之后再批量操作");
			return false;
		}
		else{
			$('tbody input[type="checkbox"]:checked').each(function(){ 
				deleteSingleQuestion(this);
			}); 
		}
	}
}