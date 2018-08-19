<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>管理权限</title>
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
		<link href="../bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css">		
		<script src="../js/jquery-1.9.1.js"></script>
		<script src="../bootstrap/js/bootstrap.min.js"></script>
		<link href="../css/manage.css" rel="stylesheet" type="text/css">
		<script src="../js/manage.js"></script>
		<script src="../js/authority.js"></script>
		<link href="../css/authority.css" rel="stylesheet" type="text/css">
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
						<table class="table" id="authorityTable">		
						<thead>
							<tr>
								<th>权限名</th>
								<th>权限描述</th>
								<th>修改</th>
							</tr>							
						</thead>
						<tbody>
							<tr>
								<td>
									管理用户信息
								</td>
								<td>
									可以对用户进行禁用，修改基本信息，查询等操作
								</td>
								<td>
									<button class="btn btn-info editBtn">修改</button>
								</td>
							</tr>
							<tr>
								<td>
									管理角色信息
								</td>
								<td>
									可以对角色进行修改，查询等操作
								</td>
								<td>
									<button class="btn btn-info editBtn">修改</button>
								</td>
							</tr>
						</tbody>
					</table>
					</div>
				</div>
				
			</div>			
			
			<div class="row-fluid editDiv">
				<div class="span12 mainContent">
					<div class="row-fluid ">
						<ul class="nav nav-tabs pull-right">
							<li>
								<button class="btn-link listBtn">返回权限列表</button>
							</li>
						</ul>
						<div class="form-horizontal authorityDetailsForm">
							<div class="formTitle">
								<legend>编辑权限</legend>
							</div>
						  	<div class="control-group">
						  		<label class="control-label" for="authorityName">权限名</label>
						    	<div class="controls">
							      	<p id="authorityName">权限名</p>
							    </div>
						  	</div>
						  	<div class="control-group">
						  		<label class="control-label" for="inputAuthorityDescription">权限描述</label>
						    	<div class="controls">
						      		<textarea id="inputAuthorityDescription" placeholder="权限描述"></textarea>
						    	</div>
						  	</div>
						  	
							 <div class="control-group">
							    <div class="controls">
								    <button type="submit" class="btn btn-info" onclick="changeAuthorityInfo()" id="changeAuthorityBtn">修改</button>
								    <button type="submit" class="btn btn-warning" onclick="cancelChangeAuthority()" id="changeAuthorityCancelBtn">取消</button>
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
