<!--模态窗体，显示一个对话框-->
<div id="dialogModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
 	<div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    	<h3 id="myModalLabel">Modal header</h3>
  	</div>
  	<div class="modal-body">
    	<p>One fine body…</p>
 	</div>
  	<div class="modal-footer">
    	<button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
    	<button class="btn btn-primary">Save changes</button>
  	</div>
</div>

<!--模态窗体，显示内容正在加载-->
<div id="loadingModal" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  	<div class="modal-body" style="text-align: center;">
  		<h3>加载中……</h3>
  		<!--如果显示图片，就涉及到了一个图片定位的问题，在不同目录中的文件引用相同的路径就会报错，暂时还没有好方法解决-->
    	<!--<img src="../img/loading.gif">-->
 	</div>
</div>