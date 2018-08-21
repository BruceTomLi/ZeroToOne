//获取窗口可视范围的高度
function getClientHeight(){  
    var clientHeight=0;  
    if(document.body.clientHeight&&document.documentElement.clientHeight){  
        var clientHeight=(document.body.clientHeight<document.documentElement.clientHeight)?document.body.clientHeight:document.documentElement.clientHeight;
    }else{  
        var clientHeight=(document.body.clientHeight>document.documentElement.clientHeight)?document.body.clientHeight:document.documentElement.clientHeight;
    }  
    return clientHeight;  
}
//获取窗口滚动条高度
function getScrollTop(){  
    var scrollTop=0;  
    if(document.documentElement&&document.documentElement.scrollTop){  
        scrollTop=document.documentElement.scrollTop;  
    }else if(document.body){  
        scrollTop=document.body.scrollTop;  
    }  
    return scrollTop;  
}
//获取文档内容实际高度
function getScrollHeight(){  
    return Math.max(document.body.scrollHeight,document.documentElement.scrollHeight);  
}

/**
 * 设置背景高度
 */
function setBackgroundHeight(){
	// 窗口可视范围的高度
    var height=getClientHeight(),
        // 窗口滚动条高度
        theight=getScrollTop(),
        // 窗口可视范围的高度
        rheight=getScrollHeight(),
        // 滚动条距离底部的高度
        bheight=rheight-theight-height;
    
    $("html").css("height",rheight+"px");
}