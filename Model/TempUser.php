/**
 * 下面定义一个函数用来让用户创建一个新的话题
 */
function createNewTopic($topicType,$topicContent,$topicDescription){			
	if($this->isUserLogon() && !$this->isTopicRepeat($topicContent)){
		global $pdo;
		$topicId=uniqid("",true);
		$asker=$_SESSION['username'];
		//需要先获取到登录用户的用户ID，tb_topic的数据库中需要保存用户Id而不是用户名，因为用户名虽然唯一但可变
		//但是这样需要进行两次sql查询，影响性能，我试试将两条sql语句合并成一条				
		// $paraArr=array(":username"=>$asker);
		// $sql="select userId from tb_user where username=:username";
		// $askerId=$pdo->getOneFiled($sql, "userId",$paraArr);
		
		$askerDate=date("Y-m-d H:i:s");
		$paraArr=array(":topicId"=>$topicId,":asker"=>$asker,":askDate"=>$askerDate,":topicType"=>$topicType,
			":topicContent"=>$topicContent,":topicDescription"=>$topicDescription,":enable"=>"1");
		$sql="insert into tb_topic values(:topicId,(select userId from tb_user where username=:asker),:askDate,:topicType,:topicContent,:topicDescription,:enable)";
		$result=$pdo->getUIDResult($sql,$paraArr);
		return $result;
	}
	else{
		return null;
	}
}

/**
 * 下面定义的函数检测用户要添加的话题是否重复
 */
function isTopicRepeat($topicContent){
	global $pdo;
	$paraArr=array(":topicContent"=>$topicContent);
	$sql="select count(*) as topicCount from tb_topic where content=:topicContent";
	$count=$pdo->getOneFiled($sql, "topicCount",$paraArr);
	$result=$count>0?true:false;
	return $result;
}

/**
 * 获取用户提出的话题的话题列表
 */
function getSelfTopicList($page=1){
	if($this->isUserLogon()){
		global $pdo;
		//获取分页数据
		$topicsCount=$this->getSelfTopicCount();
		$pageTotal=ceil($topicsCount/5);//获取总页数
		//规范化页数，并返回起始页
		$startRow=$this->getStartPage($page,$pageTotal);
		
		//分页时要用到limit，由于对limit后面的参数（不能用数组参数传值）
		//在上面进行了限制，可以防止sql注入，所以对于该参数使用字符串拼接
		$paraArr=array(":asker"=>$_SESSION['username']);
		$sql="select topicId,:asker as asker,askDate,topicType,content,enable from tb_topic where askerId=(select userId from tb_user where username=:asker) limit $startRow,5";
		$topicList=$pdo->getQueryResult($sql,$paraArr);
		return $topicList;
	}
	else{
		return "未登录系统，无法进行操作";
	}
}

/**
 * 获取个人话题的总数
 */
function getSelfTopicCount(){
	if($this->isUserLogon()){
		global $pdo;
		$paraArr=array(":logonUser"=>$_SESSION['username']);
		$sql="select count(*) as topicsCount from tb_topic where askerId=(select userId from tb_user where username=:logonUser)";
		$topicsCount=$pdo->getOneFiled($sql, "topicsCount",$paraArr);
		return $topicsCount;
	}
	else{
		return "未登录系统，无法进行操作";
	}
}

/**
 * 下面通过话题的Id获取到话题的详情
 */
function getTopicDetailsByTopicId($topicId){
	if($this->isUserLogon()){
		global $pdo;				
		$paraArr=array(":topicId"=>$topicId);
		$sql="select * from tb_topic where topicId=:topicId";
		$topicDetails=$pdo->getQueryResult($sql,$paraArr);
		return $topicDetails;
	}
	else{
		return null;
	}
}

/**
 * 下面通过搜索话题内容或者描述中的关键字来检索相应的话题（针对单个用户）
 * 单个用户搜索话题的时候，无论话题是否被禁用，都要能搜索出来进行管理
 */
function getTopicListByContentOrDescription($keyword,$page=1){
	if($this->isUserLogon()){
		global $pdo;
		//获取分页数据
		$count=$this->getTopicListByContentOrDescriptionCount($keyword);
		$pageTotal=ceil($count/5);//获取总页数
		//规范化页数，并返回起始页
		$startRow=$this->getStartPage($page,$pageTotal);
		
		$username=$_SESSION['username'];
		$keyword="%".$keyword."%";//使用模糊查询，前后要加百分号
		$paraArr=array(":keyword"=>$keyword,":asker"=>$username);
		$sql="select :asker as asker,tt.* from tb_topic tt where askerId=(select userId from tb_user where username=:asker) ";
		$sql.="and (content like :keyword or topicDescription like :keyword) limit $startRow,5";
		$topicList=$pdo->getQueryResult($sql,$paraArr);
		return $topicList;
	}
	else{
		return "未登录无法进行操作";
	}
}

/**
 * 获取关键字搜索出的话题条数
 */
function getTopicListByContentOrDescriptionCount($keyword){
	if($this->isUserLogon()){
		global $pdo;
		$username=$_SESSION['username'];
		$keyword="%".$keyword."%";//使用模糊查询，前后要加百分号
		$paraArr=array(":keyword"=>$keyword,":asker"=>$username);
		$sql="select count(*) as topicCount from tb_topic tt where askerId=(select userId from tb_user where username=:asker) and (content like :keyword or topicDescription like :keyword)";
		$count=$pdo->getOneFiled($sql,"topicCount",$paraArr);
		return $count;
	}
	else{
		return "未登录无法进行操作";
	}
}

/**
 * 下面的函数用来禁用用户个人的话题
 * 在查询中使用当前登录者，防止黑客禁用其他人的话题
 * hasAuthority这个参数是给管理员用的，如果判断出用户非话题创建者，但是有话题管理权限，也可以管理话题
 */
function disableSelfTopic($topicId,$hasAuthority=false){
	if($this->isUserLogon()){
		global $pdo;
		$logonUser=$_SESSION['username'];
		$paraArr=array();
		if($hasAuthority){
			$paraArr=array(":topicId"=>$topicId);
			$sql="update tb_topic set enable=0 where topicId=:topicId ";
		}
		else{
			$paraArr=array(":topicId"=>$topicId,":logonUser"=>$logonUser);
			$sql="update tb_topic set enable=0 where topicId=:topicId ";
			$sql.="and askerId=(select userId from tb_user where username=:logonUser)";
		}				
		$result=$pdo->getUIDResult($sql,$paraArr);
		return $result;
	}
	else{
		return "禁用话题失败，你没有登录系统";
	}
}

/**
 * 下面的函数用来启用用户个人话题
 * 在查询中使用当前登录者，防止黑客启用其他人的话题
 * hasAuthority这个参数是给管理员用的，如果判断出用户非话题创建者，但是有话题管理权限，也可以管理话题
 */
function enableSelfTopic($topicId,$hasAuthority=false){
	if($this->isUserLogon()){
		global $pdo;
		$logonUser=$_SESSION['username'];
		$paraArr=array();
		if($hasAuthority){
			$paraArr=array(":topicId"=>$topicId);
			$sql="update tb_topic set enable=1 where topicId=:topicId ";
		}
		else{
			$paraArr=array(":topicId"=>$topicId,":logonUser"=>$logonUser);
			$sql="update tb_topic set enable=1 where topicId=:topicId ";
			$sql.="and askerId=(select userId from tb_user where username=:logonUser)";
		}
		$result=$pdo->getUIDResult($sql,$paraArr);
		return $result;
	}
	else{
		return "启用话题失败，你没有登录系统";
	}
}

/**
 * 下面的函数用来让用户评论一个话题
 */
function commentTopic($topicId,$content){
	if($this->isUserLogon()){
		global $pdo;
		//向数据库中增加评论信息
		$commenter=$_SESSION['username'];
		$commentId=uniqid("",true);
		$commentDate=date("Y-m-d H:i:s");
		$paraArr=array(":commentId"=>$commentId,":topicId"=>$topicId,
			":commenter"=>$commenter,":commentDate"=>$commentDate,":content"=>$content,":enable"=>"1");
		$sql="insert into tb_comment values(:commentId,:topicId,(select userId from tb_user where username=:commenter),:commentDate,:content,:enable)";
		$affectRow=$pdo->getUIDResult($sql,$paraArr);
		
		$comment=$this->getCommentByCommentId($commentId);
		
		$resultArr=array("affectRow"=>$affectRow,"createdComment"=>$comment);				
		
		return $resultArr;
	}
	else{
		return null;
	}
}

/**
 * 下面的函数通过commentId来获取相应的comment，以便于用户在提交一个评论之后
 * 可以不刷新页面就看到新的评论信息，因为commentId是在服务器端产生的，所以
 * 光凭借前端的信息不足以用jquery动态添加元素
 */
function getCommentByCommentId($commentId){
	global $pdo;
	$paraArr=array(":commentId"=>$commentId);
	$sql="select (select username from tb_user where userId=tc.commenterId) as commenter,tc.* from tb_comment tc where commentId=:commentId";
	$result=$pdo->getQueryResult($sql,$paraArr);
	return $result;
}


/**
 * 下面的函数通过话题号加载相应的评论
 */
function getCommentsForTopic($topicId){
	global $pdo;
	$logonUser=$_SESSION['username']??"";
	$paraArr=array(":logonUser"=>$logonUser,":topicId"=>$topicId);
	//$sql="select * from tb_comment where topicId=:topicId";
	//$sql="select case when commenter=:logonUser then 'true' else 'false' end as isCommenter,commentId,";
	//$sql.="topicId,commenter,commentDate,content from tb_comment where topicId=:topicId;";
	//由于逻辑变得有点复杂（需要判断是否为当前登录者，需要获取该评论的回复数，所以改为使用存储过程
	$sql="call pro_getComments(:logonUser,:topicId)";
	$result=$pdo->getQueryResult($sql,$paraArr);
	return $result;
}

/**
 * 下面的函数根据评论号删除一个评论
 */
function disableCommentForTopic($commentId){
	if($this->isUserLogon() && $this->isCommentEnable($commentId)){
		global $pdo;
		$commenter=$_SESSION['username'];
		$paraArr=array(":commentId"=>$commentId,":commenter"=>$commenter);
		//加入关于评论者的条件，保证这里只有评论者可以删除自己的评论，而不是任意一个登录的人
		$sql="update tb_comment set enable=0 where commentId=:commentId and commenterId=(select if(count(userId)>0,userId,'') from tb_user where username=:commenter)";
		$result=$pdo->getUIDResult($sql,$paraArr);
		return $result;
	}
	else{
		return 0;
	}
}

/**
 * 下面的函数获取单个话题的评论数
 */
function getCommentCountByTopicId($topicId){
	global $pdo;
	$paraArr=array(":topicId"=>$topicId);
	$sql="select count(commentId) as commentCount from tb_comment where topicId=:topicId and enable=1";
	$commentCount=$pdo->getOneFiled($sql, "commentCount",$paraArr);
	return $commentCount;
}
		
/**
 * 用户删除自己的话题
 * hasAuthority这个参数是给管理员用的，如果判断出用户非话题创建者，但是有话题管理权限，也可以管理话题
 */
function deleteSelfTopic($topicId,$hasAuthority=false){
	if($this->isUserLogon()){				
		global $pdo;
		$logonUser=$_SESSION['username'];
		$paraArr=array();
		if($hasAuthority){
			$paraArr=array(":topicId"=>$topicId);
			$sql="delete from tb_topic where topicId=:topicId";
		}
		else{
			$paraArr=array(":logonUser"=>$logonUser,":topicId"=>$topicId);
			$sql="delete from tb_topic where topicId=:topicId and askerId =(select userId from tb_user where  username=:logonUser)";
		}
		
		$deleteTopicCount=$pdo->getUIDResult($sql,$paraArr);
		return $deleteTopicCount;
	}
	else{
		return "没有登录系统，无法进行该操作";
	}
}
