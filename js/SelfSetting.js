$(function(){
	//将菜单项的当前页菜单增加选中样式
	$("#menuUl>li>a[href='selfSetting.php']").parent().addClass("active");
	
	//显示或者隐藏修改密码
	$("#changePasswordBtn").on("click",function(){
		$("#changePwdDiv").toggle();
	});
	//取消修改密码时，清空密码框内容，然后隐藏
	$("#cancelChangePasswordBtn").on("click",function(){
		$("#inputOldPassword").val("");
		$("#inputNewPassword").val("");
		$("#inputPasswordAgain").val("");
		$("#changePwdDiv").hide();
	});
	//显示或者隐藏修改头像
	$("#changeHeadingBtn").on("click",function(){
		$("#changeHeadingDiv").toggle();
	});
	//取消修改头像时，隐藏头像div
	$("#cancelChangeHeadingBtn").on("click",function(){
		$("#changeHeadingDiv").hide();
	});
	//页面加载时，通过session中的数据加载用户信息
	loadUserInfo();
	
	//点击修改个人信息时，进行修改
	$("#changeUserBtn").on("click",function(){
		changeSelfSetting();
	})
	
	//点击修改密码时，修改密码
	$("#submitChangePasswordBtn").on("click",function(){
		changeUserPassword();
	});
	
	//点击提交修改头像时，修改头像
	$("#submitChangeHeadingBtn").on("click",function(){
		uploadSelfHeading();
	});
	
	//加载完用户基本信息之后加载工作岗位信息和省份信息
	getJobList();
	getProvinceList();
	//用户单击城市选项时，加载省份对应的城市信息
	$("#province").on("click",function(){
		getCityList();
	});
	
	/**
	 * 当密码输入按键抬起时检测密码强度
	 */
	$('#inputOldPassword,#inputNewPassword,#inputPasswordAgain').on('keyup',function(){
		isPasswordOk(this);
	});
});

//下面是加载用户信息的函数
function loadUserInfo(){
	if($("#inputUsername").val()==""){
		$.get(
			"../Controller/SelfSettingController.php",
			{action:"loadUserInfo"},
			function(data){
				var result=$.trim(data);
				result=$.parseJSON(result);
				result.userInfo.forEach(function(value,index){
					$("#inputUsername").val(value.username);
					$("#inputEmail").val(value.email);
					if(value.sex==1){
						$("#optionsRadios1").prop("checked",true);
						$("#optionsRadios2").prop("checked",false);
					}
					else{
						$("#optionsRadios1").prop("checked",false);
						$("#optionsRadios2").prop("checked",true);
					}
					var jobHtml="<option value='"+value.job+"'>"+value.job+"</option>";
					$("#inputJob").html(jobHtml);
					var provinceHtml="<option value='"+value.province+"'>"+value.province+"</option>";
					$("#province").html(provinceHtml);
					var cityHtml="<option value='"+value.city+"'>"+value.city+"</option>";
					$("#city").html(cityHtml);
					$("#inputOneWord").val(value.oneWord);
					//用户角色这个地方应该加载所有的角色，需要从tb_user表之外的其他表获得，这里我先暂且不加载
					//用户角色是不能让用户自己修改的，所以直接将内容读取并显示出来
					$("#inputUserRole").text(value.roles);
					//将用户头像以路径形式保存在数据库中，并将图片保存在UploadImages/heading目录下面
					$("#userHeadingImg").attr("src",value.heading);
				});
			}
		);
	}	
}

//下面的函数修改用户的个人信息
function changeSelfSetting(){
	//通过名字和邮件校验时才向服务器提交
	if(isUsernameOk()&&isEmailOk()){
		var formData=new FormData();
		formData.append("username",$("#inputUsername").val());
		formData.append("email",$("#inputEmail").val());
		formData.append("sex",$('input[name="sexRadios"]:checked').val()=="man"?1:0);
		formData.append("job",$("#inputJob").val());
		formData.append("province",$("#province").val());
		formData.append("city",$("#city").val());
		formData.append("oneWord",$("#inputOneWord").val());
		formData.append("action","changeUserInfo");
		formData.append("heading",$("#inputUserHeading"));//用户头像是文件，传进来的是一个元素对象
		$.ajax({
			url:"../Controller/SelfSettingController.php",
			method:'POST',
			data:formData,
			contentType:false,
			processData:false,
			cache:false,	
			success:function(data){
				var result=$.trim(data);
				result=$.parseJSON(result);
				if(result.affectRow==1){
					alert("已经成功修改了您的信息");
				}
				else if(result.affectRow==0){
					alert("您没有改动您的信息，请勿提交");
				}
				else{
					alert(result.affectRow);
				}
			}
		});
	}	
}

/**
 * 下面的函数校验用户名填写是否规范
 */
function isUsernameOk(){
	var username=$.trim($('#inputUsername').val());
	if(username==''){
		$('#userNameChk').text("用户名不能为空").removeClass().addClass("chkError");	
		return false;
	}
	else if(username.length<2 || username.length>20){
		$('#userNameChk').text("2<用户名长度<20").removeClass().addClass("chkError");	
		return false;
	}
	else{
		return true;
	}
}

/**
 * 检测输入的email是否符合规范
 */
function isEmailOk(){
	var emailreg=/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
	var email=$.trim($('#inputEmail').val());
	email.match(emailreg);
	if(email==''){
		$('#emailChk').text("邮箱不能为空").removeClass().addClass("chkError");	
		return false;
	}
	else if(email.match(emailreg)==null){
		$('#emailChk').text("邮箱格式不正确").removeClass().addClass("chkError");	
		return false;
	}
	else{
		return true;
	}
}

/**
 * 下面的函数修改用户的密码
 */
function changeUserPassword(){
	var oldPasswordOk=isPasswordOk($("#inputOldPassword"));
	var newPasswordOk=isPasswordOk($("#inputNewPassword"));
	var againNewPasswordOk=isPasswordOk($("#inputPasswordAgain"));
	if(oldPasswordOk && newPasswordOk && againNewPasswordOk 
		&& isTwoNewPasswordSame() && !isOldNewPasswordSame()){
		var oldPassword=$("#inputOldPassword").val();
		var newPassword=$("#inputNewPassword").val();
		$.ajax({
			url:"../Controller/SelfSettingController.php",
			data:{action:"changeUserPassword",oldPassword:oldPassword,newPassword:newPassword},
			success:function(data){
				var result=$.trim(data);
				result=$.parseJSON(result);
				if(result.affectRow==1){
					alert("修改密码成功");
				}
				else if(result.affectRow==0){
					alert("密码修改失败，请检查旧密码是否正确");
				}
				else{
					alert(result.affectRow);
				}
			}
		});
	}	
}

/**
 * 下面检测密码强度是否符合要求，对三次输入的密码进行检测
 * 包括密码强度规范以及输入的两次新密码是否相同。因为需要被不同的对象调用，所以传入参数
 */
function isPasswordOk(obj){	
	var reNum = new RegExp(/\d/);
	var reLetter = new RegExp(/[a-zA-Z]/);
	var reSpecialLetter = new RegExp(/[-`=\\\[\];',./~!@#$%^&*()_+|{}:"<>?]/);
	var pwd=$(obj).val();
	var hasNum = reNum.test(pwd)?1:0;
	var hasLetter = reLetter.test(pwd)?1:0;
	var hasSpecialLetter = reSpecialLetter.test(pwd)?1:0;
	var status = hasNum +hasLetter +hasSpecialLetter;
	if(pwd.length<6 || pwd.length>18){
		$(obj).next().html("<font color='red'>6<密码长度<18</font>");
		return false;
	}
	else{
		switch(status) {
			case 1:
				$(obj).next().html("<font color='orange'>密码等级：弱</font>");
				break;
			case 2:
				$(obj).next().html("<font color='orange'>密码等级：中</font>");	
				break;
			case 3:
				$(obj).next().html("<img src='../img/action_check.png' />");
				break;
			default:
				$(obj).next().html("<font color='red'>密码等级未知</font>");	
				break;
		}
		return true;
	}
}

/**
 * 检测第二次输入的新密码和第一次输入的是否相同
 */
function isTwoNewPasswordSame(){
	var newPassword=$("#inputNewPassword").val();
	var againNewPassword=$("#inputPasswordAgain").val();
	if(newPassword!=againNewPassword){
		$("#passwordAgainChk").html("<font color='red'>确认密码不一致</font>");	
		return false;
	}
	else{
		$("#passwordAgainChk").html("<img src='../img/action_check.png' />");
		return true;
	}
}

/**
 * 检测新旧密码是否一样
 */
function isOldNewPasswordSame(){
	var oldPassword=$("#inputOldPassword").val();
	var newPassword=$("#inputNewPassword").val();
	if(oldPassword==newPassword){
		alert("新旧密码相同，不能进行修改");
		return true;
	}
	else{
		return false;
	}
}

/**
 * 上传用户头像
 */
function uploadSelfHeading(){
	var formData=new FormData();
	formData.append("action","uploadSelfHeading");
	formData.append("heading",$("#inputUserHeading")[0].files[0]);//用户头像是文件，传进来的是一个元素对象
	$.ajax({
		url:"../Controller/SelfSettingController.php",
		method:'POST',
		data:formData,
		contentType:false,
		processData:false,
		cache:false,	
		success:function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			if(result.affectRow==1){
				$("#userHeadingImg").attr("src",result.newPath);
				alert("已经成功修改了您的头像");
			}
			else if(result.affectRow==0){
				alert("已经成功修改了您的头像，头像文件名没有变化");
			}
			else{
				alert(result.affectRow);
			}
		}
	});
}

/**
 * 下面从register.js中拷贝过来加载省份，城市信息的函数
 * 但是要进行修改，因为这里用户已经有了相关信息，所以options的第一项应该是用户已有的信息
 */
/**
 * 加载职业列表
 */
function getJobList (){
	$.ajax({
		url:'../Controller/RegisterController.php',
		data:{action:"getJobList"},
		success: function(data) {
			var result = $.trim(data);
			//alert(data);
			result=$.parseJSON(result);
			var jobHtml="";
			if($('#inputJob').children().length<=1){
				jobHtml=$('#inputJob').html();
			}
			result.jobs.forEach(function(value,index){
				jobHtml=jobHtml+"<option value='"+value.job+"'>"+value.job+"</option>";
			});
			$('#inputJob').html(jobHtml);
		}		
	});
}

/**
 * 加载省
 */
function getProvinceList (){
	$.ajax({
		url:'../Controller/RegisterController.php',
		data:{action:"getProvinceList"},
		success: function(data) {
			data = $.trim(data);
			data = $.parseJSON(data);
			var provinceLists="";
			if($('#province').children().length<=1){
				provinceLists=$('#province').html();
			}
			data.forEach(function(item,index) {
				provinceLists += `<option value='${item.province}'>${item.province}</option>`;
			});
			
			$('#province').html(provinceLists);
		}		
	});	
}
/**
 * 加载城市
 */
function getCityList (){
	var province = $('#province').val();
	$.ajax({
		url:'../Controller/RegisterController.php',
		data:{action:"getCityList",province:province},
		success: function(data) {			
			data = $.trim(data);
			data = $.parseJSON(data);
			var cityLists="";
			if($('#city').children().length<=1){
				provinceLists=$('#city').html();
			}
			data.forEach(function(item,index) {
				cityLists += `<option value='${item.city}'>${item.city}</option>`;
			});
			
			$('#city').html(cityLists);
		}		
	});
}