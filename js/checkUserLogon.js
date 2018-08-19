/**
 * 检测用户是否登录，原本想写在questionDetails.js中，因为某些原因失败
 */
function chkUserLogonByWelcome(){
	var isLogon="false";
	if($("#welcomeInfo").length>0){
		isLogon="true";
	}
	return isLogon;	
}