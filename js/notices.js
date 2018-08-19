$(function(){
	//将菜单项的当前页菜单增加选中样式
	$("#menuUl>li>a[href='notices.php']").parent().addClass("active");	
	
	$(".detailsBtn").on("click",function(){
		showDetails();
	});
	$(".listBtn").on("click",function(){
		showList();
	});
	$("#createBtn").on("click",function(){
		showCreate();
	});
	$("#editBtn").on("click",function(){
		showEdit();
	});
	
	$("#cancleBtn").on("click",function(){
		showList();
	});
	
	//加载公告信息
	loadNotices();
});

function showDetails(){
	$(".detailsDiv").show();
	$(".queryDiv").hide();
	$(".editDiv").hide();
	$(".createDiv").hide();
}

function showList(){
	$(".detailsDiv").hide();
	$(".queryDiv").show();
	$(".editDiv").hide();
	$(".createDiv").hide();
}

function showEdit(){
	$(".detailsDiv").hide();
	$(".queryDiv").hide();
	$(".editDiv").show();
	$(".createDiv").hide();
}

function showCreate(){
	$(".detailsDiv").hide();
	$(".queryDiv").hide();
	$(".editDiv").hide();
	$(".createDiv").show();
}

/**
 * 写公告
 */
function createNewNotice(){
	var title=$.trim($("#inputTitle").val());
	var content=$("#editor").html();
	$.post(
		"../Controller/NoticeManageController.php",
		{action:"createNewNotice",title:title,content:content},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			if(result.insertCount==1){
				alert("公告保存成功");		
				loadNotices();
			}
			else{
				alert("创建公告失败，请检查标题是否重复");
			}
		}
	);
}

/**
 * 加载公告
 */
function loadNotices(){
	var title=$.trim($("#inputTitle").val());
	var content=$("#editor").html();
	$.post(
		"../Controller/NoticeManageController.php",
		{action:"loadNotices"},
		function(data){
			var result=$.trim(data);
			result=$.parseJSON(result);
			var noticesHtml="";
			result.notices.forEach(function(value,index){
				noticesHtml+="<tr>";
				noticesHtml+="<td><a target='_blank' href='../forum/noticeDetails.php?noticeId="+value.noticeId+"'>"+value.title+"</a></td>";
				noticesHtml+="<td>"+value.createTime+"</td>";
				noticesHtml+="<td><a target='_blank' href='../forum/person.php?userId="+value.publisherId+"'>"+value.creator+"</a></td>";
				noticesHtml+="<td><button class='btn btn-danger' value='"+value.noticeId+"' onclick='deleteNotice(this)'>删除</button></td>";
				noticesHtml+="</tr>";
			});
			$("#noticesTable tbody").html(noticesHtml);
			showList();
		}
	);
}

/**
 * 加载公告
 */
function deleteNotice(obj){
	if(confirm("确定要删除公告信息吗？")){
		var noticeId=$(obj).attr("value");
		$.post(
			"../Controller/NoticeManageController.php",
			{action:"deleteNotice",noticeId:noticeId},
			function(data){
				var result=$.trim(data);
				result=$.parseJSON(result);
				if(result.deleteRow==1){
					loadNotices();
				}
				else{
					alert("删除失败");
				}
			}
		);
	}	
}