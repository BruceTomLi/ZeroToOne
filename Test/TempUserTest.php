/**
 * 下面测试用户能否创建一个新话题
 */
function testCreateNewTopic(){
	//测试这个功能需要先登录
	$username=UserName;
	$password=Password;			
	$result=$this->user->login($password, $username);
	
	$topicType=TopicType;
	$topicContent=TopicContent;
	$topicDescription=TopicDescription;
	$result=$this->user->createNewTopic($topicType, $topicContent, $topicDescription);
	//话题重复时不添加话题
	if(!$this->user->isTopicRepeat($topicContent)){				
		$this->assertEquals($result,1);
	}
	else{
		$this->assertEquals($result,0);
	}			
	
	//测试完之后退出登录
	$this->user->logout();
}

/**
 * 测试获取单个用户的话题列表
 */
function testGetSelfTopicList(){
	//测试这个功能需要先登录
	$username=UserName;
	$password=Password;			
	$result=$this->user->login($password, $username);
	
	$topicList=$this->user->getSelfTopicList();
	$this->assertTrue(count($topicList)>0);
	
	//测试完之后退出登录
	$this->user->logout();
}

/**
 * 测试获取单个用户的话题个数
 */
function testGetSelfTopicCount(){
	//测试这个功能需要先登录
	$username=UserName;
	$password=Password;			
	$result=$this->user->login($password, $username);
	
	$count=$this->user->getSelfTopicCount();
	$this->assertTrue($count>0);
	
	//测试完之后退出登录
	$this->user->logout();
}

/**
 * 测试获取单个用户的话题详情
 */
function testGetTopicDetailsByTopicId(){
	//测试这个功能需要先登录
	$username=UserName;
	$password=Password;	
	$result=$this->user->login($password, $username);
	
	$topicId=TopicId;			
	$topicDescription=$this->user->getTopicDetailsByTopicId($topicId);
	$this->assertTrue(!empty($topicDescription));
	//测试完之后退出登录
	$this->user->logout();
}

/**
 * 下面测试用户通过话题内容或者详细描述来检索一个话题
 */
function testGetTopicListByContentOrDescription(){
	//测试这个功能需要先登录
	$username=UserName;
	$password=Password;	
	$result=$this->user->login($password, $username);
	
	$keyword="单元测试";
	$topicList=$this->user->getTopicListByContentOrDescription($keyword);
	//话题被disable之后就查不到了
	$this->assertTrue(count($topicList)>0);
	//测试完之后退出登录
	$this->user->logout();
}

/**
 * 下面测试用户通过话题内容或者详细描述来检索一个话题的个数
 */
function testGetTopicListByContentOrDescriptionCount(){
	//测试这个功能需要先登录
	$username=UserName;
	$password=Password;	
	$result=$this->user->login($password, $username);
	
	$keyword="单元测试";
	$count=$this->user->getTopicListByContentOrDescriptionCount($keyword);
	$this->assertTrue($count>0);
	//测试完之后退出登录
	$this->user->logout();
}

/**
 * 下面测试用户禁用一个话题
 */
function testDisableSelfTopic(){
	//测试这个功能需要先登录
	$username=UserName;
	$password=Password;	
	$result=$this->user->login($password, $username);
	
	$topicId=TopicId;
	$result=$this->user->disableSelfTopic($topicId);
	//下面的测试条件是因为用户可能已经禁用了话题，那么数据库修改的结果就是影响函数为0
	$this->assertTrue($result==1 || $result==0);
	//测试完之后退出登录
	$this->user->logout();
}
/**
 * 下面测试用户启用一个话题
 */
function testEnableSelfTopic(){
	//测试这个功能需要先登录
	$username=UserName;
	$password=Password;	
	$result=$this->user->login($password, $username);
	
	$topicId=TopicId;
	$result=$this->user->enableSelfTopic($topicId);
	//下面的测试条件是因为用户可能已经启用了话题，那么数据库修改的结果就是影响函数为0
	$this->assertTrue($result==1 || $result==0);
	//测试完之后退出登录
	$this->user->logout();
}

/**
 * 下面测试用户给话题添加一条评论
 */
function testCommentTopic(){
	//测试这个功能需要先登录
	$username=UserName;
	$password=Password;	
	$result=$this->user->login($password, $username);
	
	$topicId=TopicId;
	$content=ExampleComment;
	$result=$this->user->commentTopic($topicId, $content);
	//下面的测试条件是因为用户可能已经删除了话题，那么数据库修改的结果就是影响函数为0
	$this->assertTrue($result["affectRow"]==1 || $result["affectRow"]==0);
	//测试完之后退出登录
	$this->user->logout();
}

/**
 * 下面测试通过话题号加载用户对该话题的评论
 */
function testGetCommentsForTopic(){
	$topicId=TopicId;
	$result=$this->user->getCommentsForTopic($topicId);
	$this->assertTrue(!empty($result));
}

/**
 * 下面测试禁用一个话题的一条评论
 */
function testDisableCommentForTopic(){
	//测试这个功能需要先登录
	$username=UserName;
	$password=Password;	
	$result=$this->user->login($password, $username);
	
	$commentId=CommentId;
	$result=0;
	if($this->user->isCommentEnable($commentId)){
		$result=$this->user->disableCommentForTopic($commentId);
		$this->assertTrue($result==1);
	}
	else{
		$this->assertTrue($result==0);
	}
	
	//测试完之后退出登录
	$this->user->logout();
}

/**
 * 下面测试根据话题号获取评论数
 */
function testGetCommentCountByTopicId(){
	$topicId=TopicId;
	$result=$this->user->getCommentCountByTopicId($topicId);
	$this->assertTrue($result>0);
}

/**
 * 测试加载用户关注的话题
 */
function testLoadUserFollowedTopics(){
	//测试这个功能需要先登录
	$username=UserName;
	$password=Password;	
	$this->user->login($password, $username);
	
	$followedTopics=$this->user->loadUserFollowedTopics();
	//如果获取到用户关注的话题，结果数量就大于0
	$this->assertTrue(count($followedTopics)>0);
	
	//测试完之后退出登录
	$this->user->logout();	
}

/**
 * 测试用户删除自己的话题
 */
function testDeleteSelfTopic(){
	//测试这个功能需要先登录
	$username=UserName;
	$password=Password;	
	$this->user->login($password, $username);
	
	$topicId=TopicIdForDelete;
	$deleteTopicCount=$this->user->deleteSelfTopic($topicId);
	//删除掉话题，1次之后失效
	$this->assertTrue($deleteTopicCount>=0);
	
	//测试完之后退出登录
	$this->user->logout();	
}