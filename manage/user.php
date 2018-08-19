<?php
	//获取问题页数，用于分页显示
	$page=$_REQUEST['page']??1;
	$keyword=$_REQUEST['keyword']??"";
	$role=$_REQUEST['role']??"";
	$sex=$_REQUEST['sex']??"";
	$enable=$_REQUEST['enable']??"";
	$queryInfo=array("page"=>$page,"keyword"=>$keyword,"role"=>$role,"sex"=>$sex,"enable"=>$enable);
	$queryJson=json_encode($queryInfo,true);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>管理用户</title>
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
		<link href="../bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css">		
		<script src="../js/jquery-1.9.1.js"></script>
		<script src="../bootstrap/js/bootstrap.min.js"></script>
		<link href="../css/user.css" rel="stylesheet" type="text/css">
		<script src="../js/MyPager.js"></script>
		<script src="../js/user.js"></script>
	</head>
	<body>
		<!--存放页数信息，以便于分页显示-->
		<div>
			<input type="hidden" id="queryJsonHidden" value='<?php echo $queryJson; ?>'/>
		</div>
		<div class="container-fluid">
			<header id="manageHeader">
				<?php include(__DIR__."/../View/manageHeader.php"); ?>
			</header>
			<!--下面是页面主体部分-->
			<div class="row-fluid queryDiv">
				<div class="span12 mainContent">
					<div class="manageMenu">	
						<div class="form-search input-append pull-left">
							<input class="input-medium" type="text" id="keyword" placeholder="用户名/邮箱" /> 
							<button type="submit" class="btn" onclick="searchUsers()" id="searchUserBtn">查找</button>
						</div>
						
						<ul class="nav nav-tabs pull-right">
							<li>
								<button id="hideOrShowAdvanceSearchBtn" class="btn-link">隐藏/显示高级搜索</a>
							</li>
							<li>
								<button id="hideOrShowDetailsBtn" class="btn-link">隐藏/显示用户详情</a>
							</li>
							<li>
								<button id="hideOrShowAbleBtn" class="btn-link">隐藏/显示禁用用户</a>
							</li>
						</ul>
					</div>
					<div class="advanceSearchDiv">
						<select id="selectRole">
							  <option>所有角色</option>
							  <option>普通用户</option>
							  <option>权限管理员</option>
							  <option>作者</option>
						</select>
						<select id="selectSex">
							  <option>所有性别</option>
							  <option>男</option>
							  <option>女</option>
						</select>
						<select id="selectEnable">
							  <option>禁用/启用</option>
							  <option>禁用</option>
							  <option>启用</option>
						</select>
					</div>
					<div class="handleMultiDiv">
						<label class="checkbox inline">
					      	<input type="checkbox" id="selectAll" onclick="selectAll()"> 全选
					    </label>
					    <label class="checkbox inline">
					      	<input type="checkbox" id="selectReverse" onclick="selectReverse()"> 反选
					    </label>
					    <div class="selectUserDiv pull-right">					    	
						    <button id="disabledBtn" class="btn btn-warning inline" onclick="disableSelectedUsers()">禁用选中用户</button>
							<button id="enabledBtn" class="btn btn-success inline" onclick="enableSelectedUsers()">启用选中用户</button>
							<button id="disabledQueryBtn" class="btn btn-warning inline" onclick="disableQueryUsers()">禁用查询用户</button>
							<button id="enabledQueryBtn" class="btn btn-success inline" onclick="enableQueryUsers()">启用查询用户</button>
						</div>
					</div>
					<div class="tableDiv">
						<table class="table" id="usersTable">		
						<thead>
							<tr>
								<th class="forSelectMulti">选择</th>
								<th>用户名</th>
								<th class="detailsInfo">邮箱</th>
								<th class="detailsInfo">性别</th>
								<th class="detailsInfo">职业</th>
								<th class="detailsInfo">所在省份</th>
								<th class="detailsInfo">所在城市</th>
								<th class="detailsInfo">一句话介绍</th>
								<th>用户角色</th>
								<th>禁用</th>
								<th>改角色</th>
								<th>重置密码</th>
								<th>激活</th>
							</tr>							
						</thead>
						<tbody>
							<tr>
								<td class="forSelectMulti">
									<label class="checkbox">
								      	<input type="checkbox">
								    </label>
								</td>
								<td>
									Tom Li
								</td>
								<td class="detailsInfo">
									1401717460@qq.com
								</td>
								<td class="detailsInfo">
									男
								</td>
								<td class="detailsInfo">
									程序猿
								</td>
								<td class="detailsInfo">
									江苏省
								</td>
								<td class="detailsInfo">
									昆山市
								</td>
								<td class="detailsInfo">
									努力做一个好的软件开发者
								</td>
								<td>
									普通用户，作者，权限管理员
								</td>
								<td>
									<button class="btn btn-warning">禁用</button>
								</td>
								<td>
									<button class="btn btn-info editBtn">改角色</button>
								</td>
								<td>
									<button class="btn-link">重置密码</button>
								</td>
								<td>
									<button class="btn-link">激活</button>
								</td>
							</tr>
							
						</tbody>
					</table>
					</div>
					<div class="pagination" id="paginationDiv">
						<ul>
							<li>
								<a href="#">上一页</a>
							</li>
							<li>
								<a href="#">1</a>
							</li>
							<li>
								<a href="#">2</a>
							</li>
							<li>
								<a href="#">3</a>
							</li>
							<li>
								<a href="#">4</a>
							</li>
							<li>
								<a href="#">5</a>
							</li>
							<li>
								<a href="#">下一页</a>
							</li>
						</ul>
					</div>
				</div>
				
				</div>			
			
			<div class="row-fluid editDiv">
				<div class="span12 mainContent">
					<div class="row-fluid ">
						<ul class="nav nav-tabs pull-right">
							<li>
								<button class="btn-link" id="backUserList" onclick="backUserList()">返回用户列表</button>
							</li>
						</ul>
						<div class="form-horizontal" id="editUserDiv">
							<div class="formTitle">
								<legend>修改用户角色</legend>
							</div>
						  	<div class="control-group">
						  		<label class="control-label" for="username">用户名</label>
						    	<div class="controls">
							      	<p id="username">显示用户名</p>
							    </div>
						  	</div>
						  	<div class="control-group">
						  		<label class="control-label" for="email">邮箱</label>
						    	<div class="controls">
						      		<p id="email">显示邮箱</p>
						    	</div>
						  	</div>
						  	<div class="control-group">
						  		<label class="control-label" for="inputUserRole">用户角色</label>
						    	<div class="controls" id="userRoleDiv">
						      		<label class="checkbox">
								      	<input type="checkbox">普通用户</input>
								    </label>
								    <label class="checkbox">
								      	<input type="checkbox">权限管理员</input>
								    </label>
								    <label class="checkbox">
								      	<input type="checkbox">作者</input>
								    </label>
						    	</div>
						  	</div>
							<div class="control-group">
							    <div class="controls">
								    <button type="submit" class="btn btn-info" onclick="updateUserRoleInfo(this)" id="changeUserRoleBtn">修改</button>
								    <button type="submit" class="btn btn-warning" onclick="backUserList()" id="changeUserRoleCancleBtn">取消</button>
							    </div>
							</div>
						</div>
						<hr>	
					</div>						
				</div>
			</div>
			<footer id="manageFooter">
				<?php include(__DIR__."/../View/manageFooter.php"); ?>
			</footer>	
				
		</div>		
		<!--模态窗体，用户重置用户密码-->
		<div id="dialogModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		 	<div class="modal-header">
		    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		    	<h3 id="myModalLabel">重置用户密码</h3>
		  	</div>
		  	<div class="modal-body">
		    	<p>用户新密码：</p>
		    	<input type="text" placeholder="用户密码" id="newUserPwd" />
		 	</div>
		  	<div class="modal-footer">
		    	<button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
		    	<button class="btn btn-primary" id="resetPwdBtn" onclick="resetUserPwd(this)">重置其密码</button>
		  	</div>
		</div>
	</body>
</html>
