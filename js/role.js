$(function(){
	//将菜单项的当前页菜单增加选中样式
	$("#menuUl>li>a[href='role.php']").parent().addClass("active");
	
	//加载所有角色信息
	loadRole();
	//加载所有权限信息到创建Div和修改Div中
	loadAuthorityInfo();
});

//点击新建角色时，显示createDiv，隐藏另外的相关Div
function showCreateDiv(){
	$(".queryDiv").hide();
	$(".editDiv").hide();	
	$(".createDiv").show();
}

//点击返回角色列表时，显示queryDiv，隐藏其他
function showQueryDiv(){	
	$(".editDiv").hide();
	$(".createDiv").hide();
	//重新用loadRole()加载角色信息，因为前面可能已经修改了角色信息
	loadRole();
	$(".queryDiv").show();
}
//点击修改按钮时，显示修改角色的Div
function showChangeDiv(obj){
	$(".queryDiv").hide();	
	$(".createDiv").hide();	
	loadRoleInfoByRoleId(obj);
	$(".editDiv").show();
}

//点击提交的时候添加角色信息
function addRole(){
	var roleName=$("#newRolename").val();
	var roleDescription=$("#newRoleDescription").val();
	//获取勾选的权限信息
	var authorities="";
	$("#newAuthorityCheckbox input:checkbox:checked").each(function(){
		authorities+=$(this).val()+",";
	});
	if(authorities.length==0){
		//如果不选权限，不能创建角色
		alert("未选择任何权限，不能创建角色");
		return;
	}
	else{
		//去除最后一个逗号
		authorities=authorities.substring(0,authorities.length-1);
	}
	
	$.get(
		"../Controller/RoleController.php",
		{action:"addRole",roleName:roleName,note:roleDescription,authorities:authorities},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			if(result.affectRow==1){
				showQueryDiv();
				alert("添加角色信息成功");
			}
			else{
				alert(result.affectRow);
			}
		}
	);
}

//加载权限信息，在创建角色的时候需要给角色添加权限
function loadAuthorityInfo(){
	$.get(
		"../Controller/RoleController.php",
		{action:"loadAuthorityInfo"},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			var authorityHtml="";
			result.authorities.forEach(function(value,index){
				authorityHtml+="<label class='checkbox'>";
				authorityHtml+="<input type='checkbox' value='"+value.authorityId+"'>"+value.name+"</input>";
				authorityHtml+="</label>";
			});
			$(".authorityCheckbox").html(authorityHtml);
		}
	);
}

//加载角色信息
function loadRole(){
	$.get(
		"../Controller/RoleController.php",
		{action:"loadRole"},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			var rolesHtml="";
			result.roles.forEach(function(value,index){
				rolesHtml+="<tr>";
				rolesHtml+="<td>"+value.name+"</td>";
				rolesHtml+="<td>"+value.note+"</td>";
				//除了要加载角色的信息，还需要加载角色下面的所有权限，我使用了从mysql中返回字符串的方法，可以直接输出字符串
				rolesHtml+="<td>"+value.auths+"</td>";
				rolesHtml+="<td><button class='btn btn-info editBtn' value='"+value.roleId+"' onclick='showChangeDiv(this)'>修改</button></td>";
				rolesHtml+="<td><button class='btn btn-danger' value='"+value.roleId+"' onclick='deleteRole(this)'>删除</button></td>";
				rolesHtml+="</tr>";
			});
			$("#rolesTable tbody").html(rolesHtml);
		}
	);
}

//加载某个角色信息，用于在修改角色信息时加载
function loadRoleInfoByRoleId(obj){
	var roleId=$(obj).attr("value");
	$("#changeRoleBtn").attr("value",roleId);
	$.ajax({
		url:"../Controller/RoleController.php",
		data:{action:"loadRoleInfoByRoleId",roleId:roleId},
		cache:false,
		success:function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			result.roleInfo.forEach(function(value,index){
				$("#inputRolename").val(value.name);
				$("#inputRoleDescription").val(value.note);
				var auths=value.authIds.split(',');	
				var checkboxes=$("#editAuthDiv input:checkbox");
				//在进行遍历的时候，可以用for，each等不同的方法，重点是在jquery1.9中，对checkbox的选中和不选操作，要用prop进行
				//使用attr只能操作一次
				for(var i=0;i<checkboxes.length;i++){
					if($.inArray($(checkboxes[i]).attr("value"),auths)>=0){
						$(checkboxes[i]).prop("checked",true);
					}
					else{
						$(checkboxes[i]).prop("checked",false);
					}
				}				
			});
		}
	});
}

/**
 * 修改角色信息
 */
function changeRoleInfo(obj){
	var roleId=$(obj).attr("value");
	var name=$("#inputRolename").val();
	var note=$("#inputRoleDescription").val();
	//获取勾选的权限信息
	var authorities="";
	$("#changeAuthorityCheckbox input:checkbox:checked").each(function(){
		authorities+=$(this).val()+",";
	});
	if(authorities.length==0){
		//如果不选权限，不能创建角色
		alert("未选择任何权限，不能修改角色");
		return;
	}
	else{
		//去除最后一个逗号
		authorities=authorities.substring(0,authorities.length-1);
	}
	
	$.get(
		"../Controller/RoleController.php",
		{action:"changeRoleInfo",roleId:roleId,name:name,note:note,authorities:authorities},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			if(result.roleAuthAddRow>0){
				showQueryDiv();
				//通过在角色权限表中增加的行数大于0可以判断修改角色信息是成功的
				alert("修改角色信息成功");				
			}
			else{
				alert(result.roleUpdateRow);
			}
		}
	);
}

/**
 * 取消修改权限
 * 和showQueryDiv不同，由于取消了修改，不需要重新加载角色信息
 */
function cancelChangeRoleInfo(){
	$(".editDiv").hide();
	$(".createDiv").hide();
	$(".queryDiv").show();
}

/**
 * 删除角色
 */
function deleteRole(obj){
	if(confirm("确定要删除角色信息吗？")){
		var roleId=$(obj).attr("value");
		$.get(
			"../Controller/RoleController.php",
			{action:"deleteRole",roleId:roleId},
			function(data){
				var result=$.trim(data);
				result=$.parseJSON(result);
				if(result.deleteRow==1){
					loadRole();
					alert("成功删除了角色");
				}
				else{
					alert(result.deleteRow);
				}
			}
		);
	}	
}
