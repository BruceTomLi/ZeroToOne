$(function(){
	//将菜单项的当前页菜单增加选中样式
	$("#menuUl>li>a[href='selfFans.php']").parent().addClass("active");
	
	//加载出粉丝信息
	loadUserFans();
});

/**
 * 加载用户的粉丝信息
 */
function loadUserFans(){
	$.get(
		"../Controller/SelfFansController.php",
		{action:"loadUserFans"},
		function(data){
			var fans=$.trim(data);
			fans=$.parseJSON(fans);
			var fansHtml="";
			fans.fans.forEach(function(value,index){
				fansHtml+="<tr>";
				//下面加载生成的mini头像				
				var arr = "";
				var suffix= "";
				var miniHeading="../img/boy_mini.gif";
				if(value.heading!=""){
					miniHeading=value.heading;
					//分别提取后缀和处后缀外的其他部分
					arr=(value.heading).split('.');
					suffix=arr[arr.length-1];
					miniHeading=miniHeading.substr(0,miniHeading.length-suffix.length-1);
					miniHeading+="_mini."+suffix;
				}
				else{
					if(value.sex=="1"){
						miniHeading="../img/boy_mini.gif";
					}
					else{
						miniHeading="../img/girl_mini.gif";
					}
				}
				fansHtml+="<td><a href='../forum/person.php?userId="+value.userId+"' target='_blank'><img style='width:30px;height:30px;' src='"+miniHeading+"'></a></td>";
				fansHtml+="<td><a href='../forum/person.php?userId="+value.userId+"' target='_blank'>"+value.username+"</a>";
				var fansSex=value.sex==1?"男":"女";
				fansHtml+="<td>"+fansSex+"</td>";
				// fansHtml+="<td>"+value.email+"</td>";
				fansHtml+="<td>"+value.oneWord+"</td>";
				fansHtml+="</tr>";
			});
			$("#fansTable tbody").html(fansHtml);			
		}
	);
}
