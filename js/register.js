// var isNameOk=false,isEmailOk=false,isPwdOk=false,isValcodeOk=false,isAgree=false;
$(function(){
	var isShow = false;//没显示	
	
	/**
	 * 单击“更多资料”时显示或者隐藏
	 */
	$('.moreInfo').on('click',function(){
		$('.moreForm').toggle();
		if(!isShow) {
			$('.moreInfo').html('收起');
			var height=$(window).height()+100;
			$("html").css("height",height+"px");
		}else {
			$('.moreInfo').html('更多资料');
			var height=$(window).height();
			$("html").css("height",height+"px");
		}
		isShow = !isShow;
		
	});	
	/**
	 * 单击注册按钮时注册
	 */
	$("#registerBtn").on('click',function(){	
		var canRegister=chkAllInput();
		if(canRegister){		
			register();
		}
	});
	/**
	 * 当名称输入框焦点离开时检测名字是否可用
	 */
	$('#inputUsername').on('blur',function(){
		chkInputUserName();
	});
	/**
	 * 当邮箱输入按键抬起时检测邮箱是否可用
	 */
	$('#inputEmail').on('keyup',function(){
		chkInputEmail();
	});
	$('#inputEmail').on('blur',function(){
		chkInputEmail();
	});
	/**
	 * 当密码输入按键抬起时检测密码强度
	 */
	$('#inputPassword').on('keyup',function(){
		chkInputPassword();
	});
	$('#inputPassword').on('blur',function(){
		chkInputPassword();
	});
	/**
	 * 页面加载和点击验证码图片时更新验证码
	 */
	showValcode();
	$('#valcodeId').on('click',function(){
		showValcode();
	});
	
	$('#inputValcode').on('keyup',function(){
		chkInputValcode();
	});
	
	$('#isAgree').on('click',function(){
		chkIsUserAgree();
	});
	
	getJobList();
	getProvinceList();
	
	$('#province').on('change',function(){
		getCityList();
	});
	
});

/**
 * 显示用户协议信息
 */
function showUserProtocol(){
	$("#dialogModal").modal("show");
}

/**
 * 检测用户是否同意了合同
 */
function chkIsUserAgree(){
	if(!$('#isAgree').is(':checked')){
		$("#agreeChk").text("必须同意用户协议才能注册").removeClass().addClass('chkError');
		return false;
	}else{
		$("#agreeChk").html("<img src='img/action_check.png' />");
		return true;
	}
}
/**
 * 检测所有输入的信息
 */
function chkAllInput(){
	var isValcodeOk=chkInputValcode();
	var isAgree=chkIsUserAgree();
	var isNameOk=chkInputUserName();
	var isEmailOk=chkInputEmail();
	var isPwdOk=chkInputPassword();
	var flag = isNameOk && isEmailOk && isPwdOk && isValcodeOk && isAgree;
	if(flag) {			
		$('#regChk').html("<img src='img/action_check.png' />");
		register();
	}else {
		$('#regChk').text("您的信息未通过校验").removeClass().addClass('chkWarning');
	}
}
/**
 * 显示验证码
 */
function showValcode(){
	num='';
	for($i=0;$i<4;$i++){
		type=Math.random()*3;
		if(type<=1){
			num+=(Math.floor(Math.random()*10)).toString();//随机的0-9的整数
		}
		else if(type>1 && type<=2){
			num+=String.fromCharCode(Math.ceil(Math.random()*25)+65);//随机小写字母
		}
		else{
			num+=String.fromCharCode(Math.ceil(Math.random()*25)+97);//随机大写字母
		}
	}
	$('#valcodeId').attr("src","classes/valcode.php?num="+num);
	$('#valcodeValue').val(num);
}
/**
 * 检测输入的username信息
 */
function chkInputUserName(){
	var username=$.trim($('#inputUsername').val());
	if(username==''){
		$('#userNameChk').text("用户名不能为空").removeClass().addClass("chkError");	
		// isNameOk=false;
		return false;
	}
	else if(username.length<2 || username.length>20){
		$('#userNameChk').text("2<用户名长度<20").removeClass().addClass("chkError");	
		// isNameOk=false;
		return false;
	}
	else{
		return chkUsername();		
	}
}
/**
 * 检测输入的email信息
 */
function chkInputEmail(){
	var emailreg=/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
	var email=$.trim($('#inputEmail').val());
	email.match(emailreg);
	if(email==''){
		$('#emailChk').text("邮箱不能为空").removeClass().addClass("chkError");	
		// isEmailOk=false;
		return false;
	}
	else if(email.match(emailreg)==null){
		$('#emailChk').text("邮箱格式不正确").removeClass().addClass("chkError");	
		// isEmailOk=false;
		return true;
	}
	else{
		return chkEmail();
	}
}

/**
 * 检测输入的密码信息,验证密码强度
 */
function chkInputPassword2(){
	var pwd=$('#inputPassword').val();
	if(pwd.length<6 || pwd.length>18){
		$('#passwordChk').html("6<密码长度<18").removeClass().addClass("chkError");
	}
	else{
		if(pwd.match(/[\S]*[\s]+[\S]*/)){
			$('#passwordChk').html("密码中不能含有空白字符").removeClass().addClass("chkError");
		}
		//下面是用来验证密码强度的正则表达式和代码，思路要清晰，密码等级是从弱到强，所以相应的判断也是嵌套的
		else if(pwd.match(/\d+/) || 
			pwd.match(/[a-zA-Z]+/) ||
			pwd.match(/[-`=\\\[\];',./~!@#$%^&*()_+|{}:"<>?]+/)){
			$('#passwordChk').html("密码等级：弱").removeClass().addClass("chkWarning");
			
			//弱强度密码是数字，大小写字母，特殊字符的两两组合
			if(pwd.match(/[a-zA-Z]+\d+/) || pwd.match(/\d+[a-zA-Z]+/) ||
				pwd.match(/[-`=\\\[\];',./~!@#$%^&*()_+|{}:"<>?]+\d+/) ||
				pwd.match(/\d+[-`=\\\[\];',./~!@#$%^&*()_+|{}:"<>?]+/) ||
				pwd.match(/[-`=\\\[\];',./~!@#$%^&*()_+|{}:"<>?]+[a-zA-Z]+/) ||
				pwd.match(/[a-zA-Z]+[-`=\\\[\];',./~!@#$%^&*()_+|{}:"<>?]+/))
			{
				$('#passwordChk').html("密码等级：中").removeClass().addClass("chkWarning");		
				
				if(pwd.match(/([\s\S]*)\d+[a-zA-Z]+[-`=\\\[\];',./~!@#$%^&*()_+|{}:"<>?]+([\s\S]*)/)||
				pwd.match(/([\s\S]*)\d+[-`=\\\[\];',./~!@#$%^&*()_+|{}:"<>?]+[a-zA-Z]+([\s\S]*)/)||
				pwd.match(/([\s\S]*)[a-zA-Z]+\d+[-`=\\\[\];',./~!@#$%^&*()_+|{}:"<>?]+([\s\S]*)/)||
				pwd.match(/([\s\S]*)[a-zA-Z]+[-`=\\\[\];',./~!@#$%^&*()_+|{}:"<>?]+\d+([\s\S]*)/)||
				pwd.match(/([\s\S]*)[-`=\\\[\];',./~!@#$%^&*()_+|{}:"<>?]+\d+[a-zA-Z]+([\s\S]*)/)||
				pwd.match(/([\s\S]*)[-`=\\\[\];',./~!@#$%^&*()_+|{}:"<>?]+[a-zA-Z]+\d+([\s\S]*)/))
				{
					$('#passwordChk').html("<img src='img/action_check.png' />");
				}
			}
			return true;
		}			
	}
	return false;
}

function chkInputPassword(){
	
	var reNum = new RegExp(/\d/);
	
	var reLetter = new RegExp(/[a-zA-Z]/);
	
	var reSpecialLetter = new RegExp(/[-`=\\\[\];',./~!@#$%^&*()_+|{}:"<>?]/);
	
	var pwd=$('#inputPassword').val();
	
	var hasNum = reNum.test(pwd)?1:0;
	
	var hasLetter = reLetter.test(pwd)?1:0;
	
	var hasSpecialLetter = reSpecialLetter.test(pwd)?1:0;
	
	var status = hasNum +hasLetter +hasSpecialLetter;
	
	if(pwd.length<6 || pwd.length>18){
		$('#passwordChk').html("6<密码长度<18").removeClass().addClass("chkError");
		// isPwdOk=false;
		return false;
	}
	else{
		switch(status) {
			case 1:
				$('#passwordChk').html("密码等级：弱").removeClass().addClass("chkWarning");
				break;
			case 2:
				$('#passwordChk').html("密码等级：中").removeClass().addClass("chkWarning");	
				break;
			case 3:
				$('#passwordChk').html("<img src='img/action_check.png' />");	
				break;
			default:
				$('#passwordChk').html("密码强度未知").removeClass().addClass("chkError");	
				break;
		}
		// isPwdOk=true;
		return true;
	}
}
/**
 * 检测验证码是否正确
 */
function chkInputValcode(){
	if($('#inputValcode').val().toLowerCase()==$('#valcodeValue').val().toLowerCase()){
		$('#valcodeChk').html("<img src='img/action_check.png' />");
		// isValcodeOk=true;
		return true;
	}
	else{
		$('#valcodeChk').html('验证码输入错误').removeClass().addClass("chkError");
		// isValcodeOk=false;
		return false;
	}	
}
/**
 * 加载职业列表
 */
function getJobList (){
	$.ajax({
		url:'Controller/RegisterController.php',
		data:{action:"getJobList"},
		success: function(data) {
			var result = $.trim(data);
			result=$.parseJSON(result);
			var jobLists="<option value='empty'>--</option>";
			result.jobs.forEach(function(value,index){
				jobLists=jobLists+"<option value='"+value.job+"'>"+value.job+"</option>";
			});
			$('#inputJob').html(jobLists);
		}		
	});
}

/**
 * 加载省
 */
function getProvinceList (){
	$.ajax({
		url:'Controller/RegisterController.php',
		data:{action:"getProvinceList"},
		success: function(data) {
			data = $.trim(data);
			data = $.parseJSON(data);
			var provinceLists="<option value=''>--</option>";
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
		url:'Controller/RegisterController.php',
		data:{action:"getCityList",province:province},
		success: function(data) {			
			data = $.trim(data);
			data = $.parseJSON(data);
			var cityLists="<option value=''>--</option>";
			data.forEach(function(item,index) {
				cityLists += `<option value='${item.city}'>${item.city}</option>`;
			});
			
			$('#city').html(cityLists);
		}		
	});
}
/**
 * 下面的函数获取用户填写的信息并提交
 */
function register(){
	var username=$('#inputUsername').val();
	var email=$('#inputEmail').val();
	var password=$('#inputPassword').val();
	var sex=$("input[name='sexRadios']:checked").val()=="man"?1:0;
	var job=$('#inputJob').val();
	var province=$('#province').val();
	var city=$('#city').val();
	var oneWord=$('#inputOneWord').val();
	var heading='';
	$.ajax({
		url:'Controller/RegisterController.php',
		type:"POST",
		data:{action:"register",username:username,email:email,password:password,sex:sex,job:job,province:province,city:city,oneWord:oneWord,heading:heading},
		beforeSend:function(){
			$("#doingRegisterModal").modal("show");
		},
		success:function(data){
			var result=$.trim(data);
			var pattern=new RegExp("\{([^\{]+)[\s\S]*(\})$","gi");//使用正则表达式检测结果是否为json格式，以{开头，以}结尾，中间任意字符
			if(pattern.test(result)){
				result=$.parseJSON(result);
				if(result.newAccount==1 && result.mailOk=="yes"){//需要同时插入用户信息和用户角色信息，所以影响的行数应该是2行
					alert("注册成功,请通过你的邮箱激活账号之后登录系统");
					window.location.href="login.php";
				}else if(result.newAccount==1 && result.mailOk=="no"){
					alert("已经注册账号,但是未成功发送邮件，你可以联系管理员帮你激活账号");
				}else{
					alert("注册失败");
				}
			}else{
				result=(decodeURI(result));
				var reg=/\"/g;
				alert(result.replace(reg,''));
			}			
		},
		complete:function(){
			$("#doingRegisterModal").modal("hide");
		}
	});
}

/**
 * 下面的函数检测用户名是否重名
 */
function chkUsername(){
	var isNameOk=false;
	//去除用户名前后的空格
	var username=$.trim($('#inputUsername').val());
	$.ajax({
		url:'Controller/RegisterController.php',
		async:false,
		data:{action:"chkUsername",username:username},
		success:function(data){
			data=$.trim(data);
			if(data==1){
				$('#userNameChk').html("用户名重复").removeClass().addClass("chkError");
				// isNameOk=false;
				isNameOk=false;
				// return false;
			}
			else{
				$('#userNameChk').html("<img src='img/action_check.png' />");
				// isNameOk=true;
				// return true;
				isNameOk=true;
			}
		}		
	});
	return isNameOk;
}


function chkUsername2(){
	isUserNameOk=false;
	//去除用户名前后的空格
	var username=$.trim($('#inputUsername').val());
	var data =$.ajax({
		url:'Controller/RegisterController.php',
		data:{action:"chkUsername",username:username},
		success:function(data){
			return data;
		}
	}
	);
	console.log(data);
	data=$.trim(data);
			if(data==1){
				$('#userNameChk').html("用户名重复").removeClass().addClass("chkError");
			}
			else{
				$('#userNameChk').html("<img src='img/action_check.png' />");			
	}
	//console.log(a)
}

/**
 * 下面的函数检测邮箱是否重复，因为用户可以通过邮箱登录系统，所以用户邮箱也应当是唯一的
 */
function chkEmail(){
	var isEmailOk=false;
	//去除用户名前后的空格
	var email=$.trim($('#inputEmail').val());
	$.ajax({
		url:'Controller/RegisterController.php',
		async:false,
		data:{action:"chkEmail",email:email},
		success:function(data){
			data=$.trim(data);
			if(data==1){
				$('#emailChk').html("邮箱已被使用").removeClass().addClass("chkError");
				isEmailOk=false;
				// return false;
			}
			else{
				$('#emailChk').html("<img src='img/action_check.png' />");
				isEmailOk=true;
				// return true;
			}
		}
	});
	return isEmailOk;
}
