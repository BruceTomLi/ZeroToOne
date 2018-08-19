$(function(){
	$('#loginBtn').on("click",function(){
		login();
	});
});

function login(){
	var password=$('#inputPassword').val();
	var emailOrUsername=$('#inputAccount').val();
	$.post(
		"Controller/LoginController.php",
		{action:"login",password:password,emailOrUsername:emailOrUsername},
		function(data){
			var loginInfo=$.trim(data);
			var pattern=new RegExp("\{([^\{]+)[\s\S]*(\})$","gi");//使用正则表达式检测结果是否为json格式，以{开头，以}结尾，中间任意字符
			if(pattern.test(loginInfo)){
				loginInfo=$.parseJSON(loginInfo);
				if(loginInfo.isSuccess=="success"){
					//alert(`欢迎你，${loginInfo.username}`);
					//location="forum/question.php";
					location=loginInfo.visitUrl;
				}
				else{
					alert("登录失败");
				}
			}else{
				loginInfo=(decodeURI(loginInfo));
				var reg=/\"/g;
				alert(loginInfo.replace(reg,''));
			}
		}
	);
}


//显示找回密码对话框
function showFindPwdDialog(){
	$("#findPwdModal").modal("show");
}

//找回密码
function findPassword(){
	var email=$("#inputEmail").val();
	// alert(email);
	$.ajax({
		url:"Controller/LoginController.php",
		data:{action:"findPassword",email:email},
		beforeSend:function(data){
			var waittingEmail="&nbsp;&nbsp;<font id='waittingEmailFont' color='green'>正在发送密码，请稍后……</font>";
			$("#inputEmail").after(waittingEmail);
			$("#findPwdBtn").prop('disabled', true);
		},
		success:function(data){
			var result=$.trim(data);
			var pattern=new RegExp("\{([^\{]+)[\s\S]*(\})$","gi");//使用正则表达式检测结果是否为json格式，以{开头，以}结尾，中间任意字符
			if(pattern.test(result)){
				result=$.parseJSON(result);
				if(result.count==1){
					alert("已经向你的邮箱发送新的密码，请查看之后登录系统修改密码");
				}
			}else{
				result=(decodeURI(result));
				var reg=/\"/g;
				alert(result.replace(reg,''));
			}
			$("#waittingEmailFont").remove();
			$("#findPwdBtn").prop('disabled', false);
			$("#findPwdModal").modal("hide");
		}
	});
}
