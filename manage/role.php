<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>管理角色</title>
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
		<link href="../bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css">		
		<script src="../js/jquery-1.9.1.js"></script>
		<script src="../bootstrap/js/bootstrap.min.js"></script>
		<link href="../css/role.css" rel="stylesheet" type="text/css">
		<script src="../js/role.js"></script>
	</head>
	<body>
		<div class="container-fluid">
			<header id="manageHeader">
				<?php include(__DIR__."/../View/manageHeader.php"); ?>
			</header>
			<!--下面是页面主体部分-->
			<div class="row-fluid queryDiv">
				<div class="span12 mainContent">
					<div class="tableDiv">
						<ul class="nav nav-tabs pull-right">
							<li>
								<button id="createRoleBtn" onclick="showCreateDiv()" class="btn-link">新建角色</a>
							</li>
						</ul>
						<table class="table" id="rolesTable">		
						<thead>
							<tr>
								<th>角色名</th>
								<th>角色描述</th>
								<th>拥有权限</th>
								<th>修改</th>
								<th>删除</th>
							</tr>							
						</thead>
						<tbody>
							<tr>
								<td>
									权限管理员
								</td>
								<td>
									可以管理用户信息，角色信息，权限信息
								</td>
								<td>
									管理用户信息,管理角色信息,管理权限信息
								</td>
								<td>
									<button class="btn btn-info editBtn" value="roleId" onclick="showChangeDiv(this)">修改</button>
								</td>
								<td>
									<button class="btn btn-info" value="roleId" onclick="deleteRole(this)">删除</button>
								</td>
							</tr>
						</tbody>
					</table>
					</div>
				</div>
				
			</div>		
			
			<div class="row-fluid createDiv">
				<div class="span12 mainContent">
					<div class="row-fluid ">
						<ul class="nav nav-tabs pull-right">
							<li>
								<button class="btn-link listBtn" onclick="showQueryDiv()">返回角色列表</button>
							</li>
						</ul>
						<div class="form-horizontal">
							<div class="formTitle">
								<legend>创建角色</legend>
							</div>
						  	<div class="control-group">
						  		<label class="control-label" for="newRolename">角色名</label>
						    	<div class="controls">
							      	<input type="text" id="newRolename" placeholder="角色名">
							    </div>
						  	</div>
						  	<div class="control-group">
						  		<label class="control-label" for="newRoleDescription">角色描述</label>
						    	<div class="controls">
						      		<textarea id="newRoleDescription" placeholder="角色描述"></textarea>
						    	</div>
						  	</div>
							<div class="control-group">
								<label class="control-label" for="newAuthority">拥有权限</label>
							    <div class="controls authorityCheckbox" id="newAuthorityCheckbox">
							    	<label class="checkbox">
								      	<input type="checkbox">管理用户信息</input>
								    </label>
								    <label class="checkbox">
								      	<input type="checkbox">管理角色信息</input>
								    </label>
								    <label class="checkbox">
								      	<input type="checkbox">管理权限信息</input>
								    </label>
								    <label class="checkbox">
								      	<input type="checkbox">管理所有文章信息</input>
								    </label>
								    <label class="checkbox">
								      	<input type="checkbox">普通用户权限</input>
								    </label>
							    </div>
							</div>
						  	
							 <div class="control-group">
							    <div class="controls">
								    <button type="submit" class="btn btn-info" onclick="addRole()" id="createRoleBtn">创建</button>
								    <button type="submit" class="btn btn-warning" id="createRoleCancleBtn">取消</button>
							    </div>
							 </div>
						</div>
						<hr>	
					</div>						
				</div>
			</div>	
			
			<div class="row-fluid editDiv">
				<div class="span12 mainContent">
					<div class="row-fluid ">
						<ul class="nav nav-tabs pull-right">
							<li>
								<button class="btn-link listBtn" onclick="showQueryDiv()">返回角色列表</button>
							</li>
						</ul>
						<div class="form-horizontal">
							<div class="formTitle">
								<legend>编辑角色</legend>
							</div>
						  	<div class="control-group">
						  		<label class="control-label" for="inputRolename">角色名</label>
						    	<div class="controls">
							      	<input type="text" id="inputRolename" placeholder="角色名">
							    </div>
						  	</div>
						  	<div class="control-group">
						  		<label class="control-label" for="inputRoleDescription">角色描述</label>
						    	<div class="controls">
						      		<textarea id="inputRoleDescription" placeholder="角色描述"></textarea>
						    	</div>
						  	</div>
							<div class="control-group" id="editAuthDiv">
								<label class="control-label" for="inputAuthority">拥有权限</label>
							    <div class="controls authorityCheckbox" id="changeAuthorityCheckbox">
							    	<label class="checkbox">
								      	<input type="checkbox">管理用户信息</input>
								    </label>
								    <label class="checkbox">
								      	<input type="checkbox">管理角色信息</input>
								    </label>
								    <label class="checkbox">
								      	<input type="checkbox">管理权限信息</input>
								    </label>
								    <label class="checkbox">
								      	<input type="checkbox">管理所有文章信息</input>
								    </label>
								    <label class="checkbox">
								      	<input type="checkbox">普通用户权限</input>
								    </label>
							    </div>
							</div>
						  	
							 <div class="control-group">
							    <div class="controls">
								    <button type="submit" class="btn btn-info" onclick="changeRoleInfo(this)" value="roleId" id="changeRoleBtn">修改</button>
								    <button type="submit" class="btn btn-warning" onclick="cancelChangeRoleInfo()" id="changeRoleCancleBtn">取消</button>
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
	</body>
</html>
