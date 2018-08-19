$(function(){
	//将菜单项的当前页菜单增加选中样式
	$("#menuUl>li>a[href='user.php']").parent().addClass("active");
	
	$("#hideOrShowDetailsBtn").on("click",function(){
		showOrHideDetails();
	});
	
	$("#hideOrShowAbleBtn").on("click",function(){
		showOrHideAbleSelect();
	});
	
	$("#hideOrShowAdvanceSearchBtn").on("click",function(){
		showOrHideAdvanceSearch();
	});
	
	//加载所有用户信息
	//loadAllUserInfo();
	searchUserByKeyword();
	
	loadKeyword();
	
	//加载角色信息
	loadAllRoles();
});

function loadKeyword(){
	var queryInfo=$.parseJSON($("#queryJsonHidden").attr("value"));
	$("#keyword").val(queryInfo['keyword']);
	$("#selectRole").val(queryInfo['role']);
	$("#selectSex").val(queryInfo['sex']);
	$("#selectEnable").val(queryInfo['enable']);	
}

/**
 * 单击“隐藏详情”时显示或者隐藏
 */
function showOrHideDetails(){		
	//$("#userDetailsBtn").text()!="显示详情"?$("#userDetailsBtn").text("显示详情"):$("#userDetailsBtn").text("隐藏详情");
	$('.detailsInfo').toggle();	
}

/**
 * 显示或者隐藏搜索用户的高级选项
 */
function showOrHideAbleSelect(){
	$(".handleMultiDiv,.enableOrDisableDiv,.forSelectMulti").toggle();
}

/**
 * 隐藏或显示高级搜索
 */
function showOrHideAdvanceSearch(){
	$(".advanceSearchDiv").toggle();
}

/**
 * 加载所有用户的信息
 */
function loadAllUserInfo(){
	$.get(
		"../Controller/UserController.php",
		{action:"loadAllUserInfo"},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			var usersHtml="";
			result.forEach(function(value,index){
				usersHtml+="<tr>";
				usersHtml+="<td class='forSelectMulti'><label class='checkbox'><input type='checkbox'></label></td>";
				usersHtml+="<td>"+value.username+"</td>";
				usersHtml+="<td class='detailsInfo'>"+value.email+"</td>";
				var sex=value.sex==1?"男":"女";
				usersHtml+="<td class='detailsInfo'>"+sex+"</td>";
				usersHtml+="<td class='detailsInfo'>"+value.job+"</td>";
				usersHtml+="<td class='detailsInfo'>"+value.province+"</td>";
				usersHtml+="<td class='detailsInfo'>"+value.city+"</td>";
				usersHtml+="<td class='detailsInfo'>"+value.oneWord+"</td>";
				usersHtml+="<td>"+value.roles+"</td>";
				if(value.enable=="1"){
					usersHtml+="<td><button class='btn btn-warning' onclick='disableUser(this)' value='"+value.userId+"'>禁用</button></td>";
				}
				else{
					usersHtml+="<td><button class='btn btn-success' onclick='enableUser(this)' value='"+value.userId+"'>启用</button></td>";
				}
				usersHtml+="<td><button class='btn btn-info editBtn' value='"+value.userId+"' onclick='showEdit(this)'>改角色</button></td>";
				usersHtml+="<td><button class='btn-link' value='"+value.userId+"' onclick='resetUserPwd(this)'>重置密码</button></td>";
				usersHtml+="</tr>";
			});
			$("#usersTable tbody").html(usersHtml);
		}
	);
}

/**
 * 显示编辑用户信息
 */
function showEdit(obj){
	loadUserRoleInfo(obj);
	$(".queryDiv").hide();	
	$(".editDiv").show();	
}

/**
 * 显示所有用户信息
 */
function backUserList(){
	$(".editDiv").hide();
	$(".queryDiv").show();
}

/**
 * 显示所有用户信息
 */
function showQueryDiv(){
	searchUserByKeyword();
	$(".editDiv").hide();
	$(".queryDiv").show();
}

/**
 * 加载单个用户的信息（主要是角色信息）
 */
function loadUserRoleInfo(obj){
	var userId=$(obj).attr("value");
	$.get(
		"../Controller/UserController.php",
		{action:"loadUserRoleInfo",userId:userId},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			result.forEach(function(value,index){
				$("#username").text(value.username);
				$("#email").text(value.email);
				var roles=value.roleIds.split(',');	
				var checkboxes=$("#userRoleDiv input:checkbox");
				//在进行遍历的时候，可以用for，each等不同的方法，重点是在jquery1.9中，对checkbox的选中和不选操作，要用prop进行
				//使用attr只能操作一次
				for(var i=0;i<checkboxes.length;i++){
					if($.inArray($(checkboxes[i]).attr("value"),roles)>=0){
						$(checkboxes[i]).prop("checked",true);
					}
					else{
						$(checkboxes[i]).prop("checked",false);
					}
				}
				$("#changeUserRoleBtn").attr("value",value.userId);
			});
		}
	);
}

/**
 * 加载所有的角色信息
 */
function loadAllRoles(){
	$.get(
		"../Controller/UserController.php",
		{action:"loadAllRoles"},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			var roleHtml="<option>所有角色</option>";
			var editRoleHtml="";
			result.forEach(function(value,index){
				roleHtml+="<option value='"+value.name+"'>"+value.name+"</option>";
				editRoleHtml+="<label class='checkbox'><input type='checkbox' value='"+value.roleId+"'>"+value.name+"</input></label>";
			});
			$("#selectRole").html(roleHtml);
			$("#userRoleDiv").html(editRoleHtml);
		}
	);
}

/**
 * 更新用户角色信息
 */
function updateUserRoleInfo(obj){
	var userId=$(obj).attr("value");
	//获取勾选的角色信息
	var roles="";
	$("#userRoleDiv input:checkbox:checked").each(function(){
		roles+=$(this).val()+",";
	});
	if(roles.length==0){
		//如果不选权限，不能创建角色
		alert("未选择任何权限，不能修改角色");
		return;
	}
	else{
		//去除最后一个逗号
		roles=roles.substring(0,roles.length-1);
	}
	
	$.get(
		"../Controller/UserController.php",
		{action:"updateUserRoleInfo",userId:userId,roles:roles},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			if(result.userRoleAddRow>0){
				showQueryDiv();
				//通过在角色权限表中增加的行数大于0可以判断修改角色信息是成功的
				alert("修改用户角色信息成功");				
			}
			else{
				alert(result.userRoleAddRow);
			}
		}
	);
}

function searchUsers(){
	var keyword=$.trim($("#keyword").val());
	var role=$("#selectRole").val();
	var sex=$("#selectSex").val();
	var enable=$("#selectEnable").val();
	var queryInfo=$.parseJSON($("#queryJsonHidden").attr("value"));
	queryInfo['keyword']=keyword;
	queryInfo['role']=role;
	queryInfo['sex']=sex;
	queryInfo['enable']=enable;
	queryInfo=JSON.stringify(queryInfo);
	$("#queryJsonHidden").attr("value",queryInfo);
	searchUserByKeyword();
}


/**
 * 通过关键字搜索用户信息
 */
function searchUserByKeyword(){
	var queryInfo=$.parseJSON($("#queryJsonHidden").attr("value"));
	var keyword=$.trim(queryInfo['keyword']);
	var role=(queryInfo['role']=="所有角色" || queryInfo['role']=="")?"":queryInfo['role'];
	var sex=(queryInfo['sex']=="所有性别" || queryInfo['sex']=="")?"":(queryInfo['sex']=="男"?"1":"0");
	var enable=(queryInfo['enable']=="禁用/启用" || queryInfo['enable']=="")?"":(queryInfo['enable']=="禁用"?"0":"1");
	//现将值转化成一个合理的页数值
	var page=$.trim(queryInfo['page']);
	page=$.isNumeric(page)?page:1;//不是数字时设为1
	page=page<1?1:page;//小于1时设为1
	$.get(
		"../Controller/UserController.php",
		{action:"searchUserByKeyword",keyword:keyword,role:role,sex:sex,enable:enable,page:page},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			var usersHtml="";
			result.users.forEach(function(value,index){
				usersHtml+="<tr>";
				if($(".handleMultiDiv").css("display")=="block"){
					usersHtml+="<td class='forSelectMulti' style='display:block'><label class='checkbox'><input type='checkbox' value='"+value.userId+"'></label></td>";
				}
				else{
					usersHtml+="<td class='forSelectMulti'><label class='checkbox'><input type='checkbox' value='"+value.userId+"'></label></td>";
				}
				usersHtml+="<td>"+value.username+"</td>";
				usersHtml+="<td class='detailsInfo'>"+value.email+"</td>";
				var sex=value.sex==1?"男":"女";
				usersHtml+="<td class='detailsInfo'>"+sex+"</td>";
				usersHtml+="<td class='detailsInfo'>"+value.job+"</td>";
				usersHtml+="<td class='detailsInfo'>"+value.province+"</td>";
				usersHtml+="<td class='detailsInfo'>"+value.city+"</td>";
				usersHtml+="<td class='detailsInfo'>"+value.oneWord+"</td>";
				usersHtml+="<td>"+value.roles+"</td>";
				if(value.enable=="1"){
					usersHtml+="<td><button class='btn btn-warning' onclick='disableUser(this)' value='"+value.userId+"'>禁用</button></td>";
				}
				else{
					usersHtml+="<td><button class='btn btn-success' onclick='enableUser(this)' value='"+value.userId+"'>启用</button></td>";
				}
				usersHtml+="<td><button class='btn btn-info editBtn' value='"+value.userId+"' onclick='showEdit(this)'>改角色</button></td>";
				usersHtml+="<td><button class='btn-link' value='"+value.userId+"' onclick='showResetPwdDiv(this)'>重置密码</button></td>";
				usersHtml+="<td><button class='btn-link' value='"+value.userId+"' onclick='activeUser(this)'>激活</button></td>";
				usersHtml+="</tr>";
			});
			$("#usersTable tbody").html(usersHtml);
			var paras=(keyword=="")?"":("&keyword="+keyword);//如果关键字为空，就让参数为空
			paras=(role=="")?paras:(paras+"&role="+role);
			paras=(sex=="")?paras:(paras+"&sex="+sex);
			paras=(enable=="")?paras:(paras+"&enable="+enable);
			writePager(result,page,"user.php",paras);
		}
	);
}

/**
 * 显示重置密码的模态窗体
 */
function showResetPwdDiv(obj){
	var userId=$(obj).attr("value");
	$("#resetPwdBtn").attr("value",userId);
	$("#dialogModal").modal('show');
}

/**
 * 重置用户密码
 */
function resetUserPwd(obj){
	var userId=$(obj).attr("value");
	var newPassword=$("#newUserPwd").val();
	$.ajax({
		url:"../Controller/UserController.php",
		data:{action:"resetUserPassword",userId:userId,newPassword:newPassword},
		success:function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			if(result.count==1){
				alert("已经重置了该用户的密码");
				$("#dialogModal").modal('hide');
			}else{
				alert("重置密码失败");
			}
		}
	});
}

/**
 * 激活用户的账号
 */
function activeUser(obj){
	var userId=$(obj).attr("value");
	$.get(
		"../Controller/UserController.php",
		{action:"activeUser",userId:userId},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			if(result.count==1){
				alert("已经激活该用户");
			}else{
				alert("未激活用户，可能该用户已经是激活状态");
			}
		}
	);
}

/**
 * 禁用用户
 */
function disableUser(obj){
	var userId=$(obj).attr("value");
	$.get(
		"../Controller/UserController.php",
		{action:"disableUser",userId:userId},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			if(result.disabledUser==1){
				//下面被注释掉的代码很适合单个禁用用户的操作，只需要修改一个按钮
				//var enableBtnHtml="<button class='btn btn-success' onclick='enableUser(this)' value='"+userId+"'>启用</button>";
				//$(obj).parent().html(enableBtnHtml);	
				//下面的代码重新加载页面上的5条用户信息，可以用于批量禁用用户
				searchUserByKeyword();
			}
		}
	);
}

/**
 * 禁用用户
 */
function enableUser(obj){
	var userId=$(obj).attr("value");
	$.get(
		"../Controller/UserController.php",
		{action:"enableUser",userId:userId},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			if(result.eabledUser==1){
				//下面被注释掉的代码很适合单个启用用户的操作，只需要修改一个按钮
				//var disableBtnHtml="<button class='btn btn-warning' onclick='disableUser(this)' value='"+userId+"'>禁用</button>";
				//$(obj).parent().html(disableBtnHtml);
				//下面的代码重新加载页面上的5条用户信息，可以用于批量启用用户
				searchUserByKeyword();
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
 * 禁用选中的用户
 */
function disableSelectedUsers(){
	var selectedUsers=new Array();
	$('tbody input[type="checkbox"]:checked').each(function(){ 
		selectedUsers.push($(this).val()); 
	}); 
	if(selectedUsers.length==0){
		alert("您没有选中任何用户，请在选中之后再批量禁用");
		return false;
	}
	else{
		$('tbody input[type="checkbox"]:checked').each(function(){ 
			//通过上面定义的禁用单个用户的方法批量禁用用户，这个方法的效率是低下的，需要向服务器多次发起请求
			//由于是只有管理员（通常为一两个人使用的方法，所以这里为了简化编程，暂时不考虑效率
			disableUser(this);
		}); 
	}
}

/**
 * 启用选中的用户
 */
function enableSelectedUsers(){
	var selectedUsers=new Array();
	$('tbody input[type="checkbox"]:checked').each(function(){ 
		selectedUsers.push($(this).val()); 
	}); 
	if(selectedUsers.length==0){
		alert("您没有选中任何用户，请在选中之后再批量启用");
		return false;
	}
	else{
		$('tbody input[type="checkbox"]:checked').each(function(){ 
			//通过上面定义的禁用单个用户的方法批量禁用用户，这个方法的效率是低下的，需要向服务器多次发起请求
			//由于是只有管理员（通常为一两个人使用的方法，所以这里为了简化编程，暂时不考虑效率
			enableUser(this);
		}); 
	}
}

/**
 * 禁用查询的用户
 */
function disableQueryUsers(){
	if(confirm("将禁用所有查询页的用户，确实要这么做吗？")){
		var queryInfo=$.parseJSON($("#queryJsonHidden").attr("value"));
		var keyword=$.trim(queryInfo['keyword']);
		var role=(queryInfo['role']=="所有角色" || queryInfo['role']=="")?"":queryInfo['role'];
		var sex=(queryInfo['sex']=="所有性别" || queryInfo['sex']=="")?"":(queryInfo['sex']=="男"?"1":"0");
		var enable=(queryInfo['enable']=="禁用/启用" || queryInfo['enable']=="")?"":(queryInfo['enable']=="禁用"?"0":"1");
		$.get(
			"../Controller/UserController.php",
			{action:"disableQueryUsers",keyword:keyword,role:role,sex:sex,enable:enable},
			function(data){
				var result=$.trim(data);
				result=$.parseJSON(result);
				if(result.count>0){
					searchUserByKeyword();
					alert("禁用了"+result.count+"个用户");
				}
			}
		);
	}	
}

/**
 * 启用查询的用户
 */
function enableQueryUsers(){
	if(confirm("将启用所有查询页的用户，确实要这么做吗？")){
		var queryInfo=$.parseJSON($("#queryJsonHidden").attr("value"));
		var keyword=$.trim(queryInfo['keyword']);
		var role=(queryInfo['role']=="所有角色" || queryInfo['role']=="")?"":queryInfo['role'];
		var sex=(queryInfo['sex']=="所有性别" || queryInfo['sex']=="")?"":(queryInfo['sex']=="男"?"1":"0");
		var enable=(queryInfo['enable']=="禁用/启用" || queryInfo['enable']=="")?"":(queryInfo['enable']=="禁用"?"0":"1");
		$.get(
			"../Controller/UserController.php",
			{action:"enableQueryUsers",keyword:keyword,role:role,sex:sex,enable:enable},
			function(data){
				var result=$.trim(data);
				result=$.parseJSON(result);
				if(result.count>0){
					searchUserByKeyword();
					alert("启用了"+result.count+"个用户");
				}
			}
		);
	}	
}
