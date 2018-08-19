$(function(){
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
