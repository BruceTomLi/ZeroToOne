$(function(){
	//将菜单项的当前页菜单增加选中样式
	$("#menuUl>li>a[href='authority.php']").parent().addClass("active");
	
	//加载权限信息
	loadAuthorityInfo();
});

//加载权限信息
function loadAuthorityInfo(){
	$.get(
		"../Controller/PowerController.php",
		{action:"loadAuthorityInfo"},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			var authorityHtml="";
			result.forEach(function(value,index){
				authorityHtml+="<tr>";
				authorityHtml+="<td>"+value.name+"</td>";
				authorityHtml+="<td>"+value.note+"</td>";
				authorityHtml+="<td><button class='btn btn-info editBtn' onclick='showChangeAuthorityInfo(this)' value='"+value.name+"'>修改</button></td>";
				authorityHtml+="</tr>";
			});
			$("#authorityTable>tbody").html(authorityHtml);
		}
	);
}

//显示修改权限信息的div
function showChangeAuthorityInfo(obj){
	var authorityName=$(obj).attr("value");
	var note=$(obj).parent().prev().html();
	$("#authorityName").text(authorityName);
	$("#inputAuthorityDescription").val(note);
	
	$(".editDiv").show();
	$(".queryDiv").hide();
	
}

//修改权限描述信息
function changeAuthorityInfo(){
	var authorityName=$("#authorityName").text();
	var note=$("#inputAuthorityDescription").val();
	$.get(
		"../Controller/PowerController.php",
		{action:"changeAuthorityInfo",authorityName:authorityName,note:note},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			if(result.affectRow==1){
				alert("权限信息修改成功");
				$(".editDiv").hide();
				$(".queryDiv").show();
				loadAuthorityInfo();
			}
			else if(result.affectRow==0){
				alert("没有修改权限信息，请勿提交");
			}
			else{
				alert(result.affectRow);
			}
		}
	);
}

//取消修改
function cancelChangeAuthority(){
	$(".editDiv").hide();
	$(".queryDiv").show();
}
