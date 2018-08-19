$(function(){	
	$("#hideOrShowDetailsBtn").on("click",function(){
		showOrHideDetails();
	});
	
	$("#hideOrShowAbleBtn").on("click",function(){
		showOrHideAbleSelect();
	});
	
	$("#hideOrShowAdvanceSearchBtn").on("click",function(){
		showOrHideAdvanceSearch();
	});
	
	$(".detailsBtn").on("click",function(){
		showDetailsInfo();
	});
	
	$(".listBtn").on("click",function(){
		showList();
	});
	
	$(".editBtn").on("click",function(){
		showEdit();
	});
	
});

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

function showDetailsInfo(){
	$(".detailsDiv").show();
	$(".queryDiv").hide();
}

function showList(){
	$(".detailsDiv").hide();
	$(".editDiv").hide();
	$(".queryDiv").show();
}

function showOrHideAdvanceSearch(){
	$(".advanceSearchDiv").toggle();
}

function showEdit(){
	$(".editDiv").show();
	$(".queryDiv").hide();
}
