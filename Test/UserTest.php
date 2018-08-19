<?php
	require_once(__DIR__."/../classes/SessionDBC.php");
	require_once(__DIR__."/../Model/User.php");
	require_once("TestData.php");
	use PHPUnit\Framework\TestCase;
	class UserTest extends TestCase{
		/**
		 * 下面的代码测试向数据库中添加用户
		 */
		private $user;
		/**
		 * 原本是在User模型中检测用户是否登录系统并有相应权限的，但是发现这样会导致代码大量重复
		 * 于是决定将检测用户登录和权限的代码放到Controller的SelectAction中进行，对于需要检测登录
		 * 和用户权限的action进行检查
		 */
		function setUp(){
			$this->user=new User();
			$username=UserName;
			$password=Password;
			$result=$this->user->login($password, $username);
		} 
		//执行每个测试后退出系统
		function tearDown(){
			$this->user->logout();
		}
		/**
		 * 测试用户注册功能，由于会检测用户名和邮箱是否重复，所以第一次注册为true，之后未false
		 */
		function testRegister(){
			$username="tom li";
			$email="1402343@qq.com";
			$password="testpwd";
			$sex=1;
			$job="IT";
			$province="江苏";
			$city="昆山";
			$oneWord="I am a programmer";
			$heading="img/test.jpg";
			
			//已经注册过一次的再注册一次会失败，因为用户名和邮箱重复了
			$result=$this->user->register($username, $password, $email, $sex, $job, $province, $city, $oneWord,$heading);
			if($this->user->isUsernameRepeat($username) || $this->user->isEmailRepeat($email)){
				$this->assertTrue($result=="用户名或者邮箱重复，不能注册");
			}
			else{
				$this->assertTrue(is_array($result) && $result["newAccount"]==1);
			}			
		}
		
		/**
		 * 测试激活用户的账号
		 */
		function testActiveAccount(){
			$userId="9";//这是张三的账号
			$password="582c1c164bee3323ed890b3093c0a439";
			$result=$this->user->activeAccount($userId, $password);
			$this->assertTrue(is_numeric($result) && $result>=0);
		}
		 
		/**
		 * 下面测试用户名是否重复，测试数据重复，结果为true
		 */
		function testIsUserNameRepeat(){
			$username=RepeatName;
			$result=$this->user->isUsernameRepeat($username);
			$this->assertTrue($result);
		}
		
		/**
		 * 下面测试邮箱是否重复，测试用的邮箱重复，结果为true
		 */
		function testIsEmailRepeat(){
			$email=RepeatEmail;
			$result=$this->user->isEmailRepeat($email);
			$this->assertTrue($result);
		}
		/**
		 * 下面测试用户登录功能，测试用的数据可以获取到用户名为“王五”的用户
		 */
		function testLogin(){
			$username=UserName;
			$password=Password;			
			$result=$this->user->login($password, $username);
			$this->assertEquals($result,"success");
		}
		
		/**
		 * 下面测试检测用户是否登录了系统，因为testLogin()的效果，session中记录了王五的登录信息，结果应该为true
		 * 之后清除session，在测试未登录的情况
		 */
		function testIsUserLogon(){
			$this->assertTrue($this->user->isUserLogon() && $_SESSION['username']==UserName);
		}
		
		/**
		 * 下面测试用户登出系统的功能
		 */
		function testLogout(){
			$this->assertTrue($this->user->logout());
			$this->assertTrue(empty($_SESSION['username']));
		}
		
		/**
		 * 下面测试用户获取工作类型列表信息的功能，断言获取到的工作列表非空
		 */
		function testGetJobList(){
			$jobList=$this->user->getJobList();
			$this->assertTrue(is_array($jobList) && count($jobList)>0);
		}
		
		/**
		 * 下面测试用户注册时获取省份列表的功能
		 */
		function testGetProvinceList(){
			$provinceList=$this->user->getProvinceList();
			$this->assertTrue(is_array($provinceList) && count($provinceList)>0);
		}
		
		/**
		 * 下面测试用户注册时根据省份获取城市列表的功能
		 */
		function testGetCityList(){
			$province=Province;
			$cities=$this->user->getCityList($province);
			$this->assertTrue(is_array($cities) && count($cities)>0);
		}
		
		///////////////////下面的大多数功能需要用户登录才能实现//////////////////////////
		/**
		 * 下面测试用户能否创建一个新问题
		 */
		function testCreateNewQuestion(){
			$questionType=QuestionType;
			$questionContent=QuestionContent;
			$questionDescription=QuestionDescription;
			$result=$this->user->createNewQuestion($questionType, $questionContent, $questionDescription);
			//问题重复时不添加问题
			if(!$this->user->isQuestionRepeat($questionContent)){				
				$this->assertEquals($result,1);
			}
			else{
				$this->assertEquals($result,"问题重复了");
			}			
		}
		
		/**
		 * 测试获取单个用户的问题列表
		 */
		function testGetSelfQuestionList(){
			$questionList=$this->user->getSelfQuestionList();
			$this->assertTrue(is_array($questionList) && count($questionList)>0);
		}
		
		/**
		 * 测试获取单个用户的问题个数
		 */
		function testGetSelfQuestionCount(){
			$count=$this->user->getSelfQuestionCount();
			$this->assertTrue(is_numeric($count) && $count>0);
		}
		
		/**
		 * 测试获取单个用户的问题详情
		 */
		function testGetQuestionDetailsByQuestionId(){
			$questionId=QuestionId;			
			$questionDescription=$this->user->getQuestionDetailsByQuestionId($questionId);
			$this->assertTrue(is_array($questionDescription) && count($questionDescription)>0);
		}
		
		/**
		 * 下面测试用户通过问题内容或者详细描述来检索一个问题
		 */
		function testGetQuestionListByContentOrDescription(){
			$keyword="单元测试";
			$questionList=$this->user->getQuestionListByContentOrDescription($keyword);
			//问题被disable之后就查不到了
			$this->assertTrue(is_array($questionList) && count($questionList)>0);
		}
		
		/**
		 * 下面测试用户通过问题内容或者详细描述来检索一个问题的个数
		 */
		function testGetQuestionListByContentOrDescriptionCount(){
			$keyword="单元测试";
			$count=$this->user->getQuestionListByContentOrDescriptionCount($keyword);
			$this->assertTrue(is_numeric($count) && $count>0);
		}
		
		/**
		 * 下面测试用户禁用一个问题
		 */
		function testDisableSelfQuestion(){
			$questionId=QuestionId;
			$result=$this->user->disableSelfQuestion($questionId);
			//下面的测试条件是因为用户可能已经禁用了问题，那么数据库修改的结果就是影响函数为0
			$this->assertTrue(is_numeric($result) && ($result==1 || $result==0));
		}
		/**
		 * 下面测试用户启用一个问题
		 */
		function testEnableSelfQuestion(){
			$questionId=QuestionId;
			$result=$this->user->enableSelfQuestion($questionId);
			//下面的测试条件是因为用户可能已经启用了问题，那么数据库修改的结果就是影响函数为0
			$this->assertTrue(is_numeric($result) && ($result==1 || $result==0));
		}
		
		/**
		 * 下面测试用户给问题添加一条评论
		 */
		function testCommentQuestion(){
			$questionId=QuestionId;
			$content=ExampleComment;
			$result=$this->user->commentQuestion($questionId, $content);
			//下面的测试条件是因为用户可能已经删除了问题，那么数据库修改的结果就是影响函数为0
			$this->assertTrue($result["affectRow"]==1 || $result["affectRow"]==0);
		}
		
		/**
		 * 下面测试通过问题号加载用户对该问题的评论
		 */
		function testGetCommentsForQuestion(){
			$questionId=QuestionId;
			$result=$this->user->getCommentsForQuestion($questionId);
			$this->assertTrue(is_array($result) && count($result)>0);
		}
		
		/**
		 * 下面测试禁用一个问题的一条评论
		 */
		function testDisableCommentForQuestion(){
			$commentId=CommentId;
			$result=0;
			if($this->user->isCommentEnable($commentId)){
				$result=$this->user->disableCommentForQuestion($commentId);
				$this->assertTrue(is_numeric($result) && $result==1);
			}
			else{
				$this->assertTrue(is_numeric($result) && $result==0);
			}
		}
		
		/**
		 * 下面测试通过评论号加载用户对该评论的回复
		 * 这个测试每运行一次都会在数据库中增加一条信息
		 */
		function testCreateReplyForComment(){
			$fatherReplyId=CommentId;
			$commentId=CommentId;
			$content=ReplyCommentContent;
			$result=$this->user->createReplyForComment($fatherReplyId, $commentId, $content);
			//添加一条回复就是向数据库中插入了一条对评论的回复记录
			$this->assertEquals($result["insertRow"],1);
		}
		
		/**
		 * 下面测试通过评论号加载用户对该评论的回复
		 * 这个测试每运行一次都会在数据库中增加一条信息
		 */
		function testCreateReplyForReply(){
			$fatherReplyId=FatherReplyId;
			$commentId=CommentId;
			$content=ReplyReplyContent;
			$result=$this->user->createReplyForReply($fatherReplyId, $commentId, $content);
			//添加一条回复就是向数据库中插入了一条值，为0是该评论不存在
			$this->assertTrue($result["insertRow"]==1);
		}
		
		/**
		 * 下面测试通过回复号删除相应评论的回复
		 * 这个测试执行第一次的时候会删除数据库中的数据，之后在执行就不会了
		 * 下面的函数写的没问题，但是测试之后会删除数据，所以先注释
		 */
		function testDisableReplyForComment(){
			$replyId=ReplyId;
			$result=0;
			if($this->user->isReplyEnable($replyId)){
				$result=$this->user->disableReplyForComment($replyId);
				//禁用回复信息的评论就是在数据库中将评论信息改为禁用，也可能已经禁用
				$this->assertTrue($result==1);
			}
			else{
				$this->assertTrue($result==0);
			}
		}
		
		/**
		 * 下面测试加载一个评论的所有回复信息
		 */
		function testGetReplysForComment(){
			$commentId=CommentId;
			$result=$this->user->getReplysForComment($commentId);
			$this->assertTrue(is_array($result) && count($result)>0);
		}
		
		/**
		 * 下面测试通过评论号获取评论信息
		 */
		function testGetCommentByCommentId(){
			$commentId=CommentId;
			$result=$this->user->getCommentByCommentId($commentId);
			$this->assertTrue(is_array($result) && count($result)>0);
		}
		
		/**
		 * 下面测试根据问题号获取评论数
		 */
		function testGetCommentCountByQuestionId(){
			$questionId=QuestionId;
			$result=$this->user->getCommentCountByQuestionId($questionId);
			$this->assertTrue(is_numeric($result) && $result>0);
		}
		
		/**
		 * 下面测试根据评论号获取回复数
		 */
		function testGetReplyCountByCommentId(){
			$commentId=CommentId;
			$result=$this->user->getReplyCountByCommentId($commentId);
			$this->assertTrue(is_numeric($result) && $result>0);
		}
		
		/**
		 * 下面测试通过replyId获取reply
		 */
		function testGetReplyByReplyId(){
			$replyId=ReplyId;
			$result=$this->user->getReplyByReplyId($replyId);
			$this->assertTrue(is_array($result) && count($result)>0);
		}
		
		/**
		 * 下面测试加载已经登录的用户的个人信息
		 */
		function testLoadUserInfo(){
			$result=$this->user->loadUserInfo();
			$this->assertTrue(is_array($result) && count($result)>0);			
		}
		
		function testIsUsernameUsedByOthers(){
			//先测试原用户名，是被自己占用的，断言false
			$newUsername=UserName;			
			$result=$this->user->isUsernameUsedByOthers($newUsername);
			$this->assertFalse($result);
			
			//再测试新用户名，是被被别人占用的，断言true
			$newUsername=NewUserName;			
			$result=$this->user->isUsernameUsedByOthers($newUsername);
			$this->assertTrue($result);	
		}
		
		/**
		 * 测试用户要修改的邮箱是否和其他人重复
		 */
		function testIsEmailUsedByOthers(){			
			//先测试原邮箱，是被自己占用的，断言false
			$newEmail=UserEmail;	
			$result=$this->user->isEmailUsedByOthers($newEmail);
			$this->assertFalse($result);
			
			//再测试新用户名，是被被别人占用的，断言true
			$newEmail=NewUserEmail;			
			$result=$this->user->isEmailUsedByOthers($newEmail);
			$this->assertTrue($result);	
		}
		
		/**
		 * 下面测试修改用户自己的信息
		 */
		function testChangeSelfInfo(){
			$userInfo=array("username"=>UserName,"email"=>UserEmail,
				"sex"=>"1","job"=>"经济金融","province"=>"上海","city"=>"上海市",
				"oneWord"=>"我是一个保洁员");
			
			$result=$this->user->changeSelfInfo($userInfo);
			$this->assertTrue(is_numeric($result) && $result>=0);	
		}
		
		/**
		 * 下面测试用户修改密码的功能
		 */
		function testChangeSelfPassword(){
			$oldPassword=Password;
			$newPassword=Password;
			
			$affectRow=$this->user->changeSelfPassword($oldPassword,$newPassword);
			//新旧密码一样时，不会更新任何记录
			$this->assertTrue($affectRow==1 || $affectRow==0);
		}
		
		/**
		 * 下面的函数测试用户关注一个问题/话题/人
		 */
		function testAddFollow(){
			//关注一个问题
			$questionId=QuestionId;
			$followType="question";			
			$questionRow=$this->user->addFollow($questionId,$followType);
			$this->assertTrue($questionRow==1 || $questionRow==0);
			
			//关注一个人
			$userId=UserId;
			$followType="user";
			$userRow=$this->user->addFollow($userId,$followType);
			$this->assertTrue($userRow==1 || $userRow==0);
			
			//关注一话题
			$topicId=TopicId;
			$followType="topic";
			$topicRow=$this->user->addFollow($topicId,$followType);
			$this->assertTrue($topicRow==1 || $topicRow==0);
		}
		
		/**
		 * 下面的函数测试用户取消关注一个问题
		 */
		function testDeleteFollow(){
			//取消关注问题
			$questionId=QuestionId;
			$affectRow=$this->user->deleteFollow($questionId);
			$this->assertTrue($affectRow==1 || $affectRow==0);
			
			//取消关注话题
			$topicId=TopicId;
			$affectRow=$this->user->deleteFollow($topicId);
			$this->assertTrue($affectRow==1 || $affectRow==0);
			
			//取消关注人
			$userId=UserId;
			$affectRow=$this->user->deleteFollow($userId);
			$this->assertTrue($affectRow==1 || $affectRow==0);
		}
		
		/**
		 * 下面的函数测试用户是否关注了某个问题/话题/人
		 */
		function testHasUserFollowed(){
			//用户是否关注了问题
			$questionId=QuestionId;
			$followCount=$this->user->hasUserFollowed($questionId);
			//用户关注了问题时，结果为1；用户没有关注时，结果为0。因为上面测试关注和取消关注的函数的影响，结果应该为0
			$this->assertTrue($followCount==0);
			
			//用户是否关注了话题
			$topicId=TopicId;
			$followCount=$this->user->hasUserFollowed($topicId);
			//用户关注了问题时，结果为1；用户没有关注时，结果为0。因为上面测试关注和取消关注的函数的影响，结果应该为0
			$this->assertTrue($followCount==0);
			
			//用户是否关注了人
			$userId=UserId;
			$followCount=$this->user->hasUserFollowed($userId);
			//用户关注了问题时，结果为1；用户没有关注时，结果为0。因为上面测试关注和取消关注的函数的影响，结果应该为0
			$this->assertTrue($followCount==0);	
		}
		
		/**
		 * 测试加载用户关注的问题
		 */
		function testLoadUserFollowedQuestions(){
			$followedQuestions=$this->user->loadUserFollowedQuestions();
			//如果获取到用户关注的问题，结果数量就大于0
			$this->assertTrue(is_array($followedQuestions) && count($followedQuestions)>0);	
		}
		
		/**
		 * 测试加载用户关注的人
		 */
		function testLoadUserFollowedUsers(){
			$followedUsers=$this->user->loadUserFollowedUsers();
			//如果获取到用户关注的问题，结果数量就大于0
			$this->assertTrue(is_array($followedUsers) && count($followedUsers)>0);
		}
		
		/**
		 * 测试用户上传自己的头像
		 */
		function testUploadSelfHeading(){
			$fileName="../UploadImages/phpunitTest/flower.jpg";
			$realName="heading.jpg";
			$isUnitTest=true;
			
			$resultArr=$this->user->uploadSelfHeading($fileName, $realName,$isUnitTest);
			//如果用户上传的是相同文件名的文件，那么文件夹里的文件会修改，但是数据库里面的记录不会更新
			$this->assertTrue($resultArr['fileUploadOk']==1 && $resultArr['affectRow']>=0);
		}
		
		/**
		 * 下面测试用户通过UserId加载一个人的基本信息
		 */
		function testGetUserBaseInfoByUserId(){
			$userId=UserId;
			$personalInfo=$this->user->getUserBaseInfoByUserId($userId);
			$this->assertTrue(is_array($personalInfo) && count($personalInfo)>0);
		}
		
		/**
		 * 下面测试加载用户的粉丝
		 */
		function testLoadUserFans(){
			$fans=$this->user->loadUserFans();
			//如果用户上传的是相同文件名的文件，那么文件夹里的文件会修改，但是数据库里面的记录不会更新
			$this->assertTrue(is_array($fans) && count($fans)>0);
		}
		
		/**
		 * 测试用户删除自己的问题
		 */
		function testDeleteSelfQuestion(){
			$questionId=QuestionIdForDelete;
			$deleteQuestionCount=$this->user->deleteSelfQuestion($questionId);
			//删除掉问题，1次之后失效
			$this->assertTrue(is_numeric($deleteQuestionCount) && $deleteQuestionCount>=0);
		}
		
		/**
		 * 测试加载问题类型，话题类型，作文类型
		 */
		function testLoadTypes(){
			$types1=$this->user->loadQuestionTypes();
			$types2=$this->user->loadArticleTypes();
			$types3=$this->user->loadTopicTypes();
			$this->assertTrue(is_array($types1) && is_array($types2) && is_array($types3));
			//删除掉问题，1次之后失效
			$this->assertTrue(count($types1)>0 && count($types2)>0 && count($types3)>0);
		}
		 
		/**
		 * 下面演示sql注入
		 */
		/*function testSqlInject(){
			global $pdo;
			$username="test";
			$password="unknown' or 'a'='a";
			$paraArr=array(':username'=>$username,':password'=>$password);
			// $sql="select count(*) as sumCount from tb_user where username='$username' and password='$password'";
			$sql="select count(*) as sumCount from tb_user where username=:username and password=:password";		
			echo $sql;
			$rows=$pdo->getOneFiled($sql, 'sumCount',$paraArr);
			echo $rows;
		}*/
		
		/***************************下面部分是关于话题的测试******************************/
		/**
		 * 下面测试用户能否创建一个新话题
		 */
		function testCreateNewTopic(){
			$topicType=TopicType;
			$topicContent=TopicContent;
			$topicDescription=TopicDescription;
			$result=$this->user->createNewTopic($topicType, $topicContent, $topicDescription);
			//话题重复时不添加话题
			if(!$this->user->isTopicRepeat($topicContent)){				
				$this->assertEquals($result,1);
			}
			else{
				$this->assertEquals($result,"话题重复了");
			}			
		}
		
		/**
		 * 测试获取单个用户的话题列表
		 */
		function testGetSelfTopicList(){
			$topicList=$this->user->getSelfTopicList();
			$this->assertTrue(is_array($topicList) && count($topicList)>0);
		}
		
		/**
		 * 测试获取单个用户的话题个数
		 */
		function testGetSelfTopicCount(){
			$count=$this->user->getSelfTopicCount();
			$this->assertTrue(is_numeric($count) && $count>0);
		}
		
		/**
		 * 测试获取单个用户的话题详情
		 */
		function testGetTopicDetailsByTopicId(){
			$topicId=TopicId;			
			$topicDescription=$this->user->getTopicDetailsByTopicId($topicId);
			$this->assertTrue(is_array($topicDescription) && count($topicDescription)>0);
		}
		
		/**
		 * 下面测试用户通过话题内容或者详细描述来检索一个话题
		 */
		function testGetTopicListByContentOrDescription(){
			$keyword="单元测试";
			$topicList=$this->user->getTopicListByContentOrDescription($keyword);
			//话题被disable之后就查不到了
			$this->assertTrue(is_array($topicList) && count($topicList)>0);
		}
		
		/**
		 * 下面测试用户通过话题内容或者详细描述来检索一个话题的个数
		 */
		function testGetTopicListByContentOrDescriptionCount(){
			$keyword="单元测试";
			$count=$this->user->getTopicListByContentOrDescriptionCount($keyword);
			$this->assertTrue(is_numeric($count) && $count>0);
		}
		
		/**
		 * 下面测试用户禁用一个话题
		 */
		function testDisableSelfTopic(){
			$topicId=TopicId;
			$result=$this->user->disableSelfTopic($topicId);
			//下面的测试条件是因为用户可能已经禁用了话题，那么数据库修改的结果就是影响函数为0
			$this->assertTrue($result==1 || $result==0);
		}
		/**
		 * 下面测试用户启用一个话题
		 */
		function testEnableSelfTopic(){
			$topicId=TopicId;
			$result=$this->user->enableSelfTopic($topicId);
			//下面的测试条件是因为用户可能已经启用了话题，那么数据库修改的结果就是影响函数为0
			$this->assertTrue($result==1 || $result==0);
		}
		
		/**
		 * 下面测试用户给话题添加一条评论
		 */
		function testCommentTopic(){
			$topicId=TopicId;
			$content="呵呵哒";
			$result=$this->user->commentTopic($topicId, $content);
			//下面的测试条件是因为用户可能已经删除了话题，那么数据库修改的结果就是影响函数为0
			$this->assertTrue($result["affectRow"]==1);// || $result["affectRow"]==0);
		}
		
		/**
		 * 下面测试通过话题号加载用户对该话题的评论
		 */
		function testGetCommentsForTopic(){
			$topicId=TopicId;
			$result=$this->user->getCommentsForTopic($topicId);
			$this->assertTrue(is_array($result) && count($result)>0);
		}
		
		/**
		 * 下面测试禁用一个话题的一条评论
		 */
		function testDisableCommentForTopic(){
			$commentId=CommentId;
			$result=0;
			if($this->user->isCommentEnable($commentId)){
				$result=$this->user->disableCommentForTopic($commentId);
				$this->assertTrue($result==1);
			}
			else{
				$this->assertTrue($result==0);
			}
			
		}
		
		/**
		 * 下面测试根据话题号获取评论数
		 */
		function testGetCommentCountByTopicId(){
			$topicId=TopicId;
			$result=$this->user->getCommentCountByTopicId($topicId);
			$this->assertTrue(is_numeric($result) && $result>0);
		}
		
		/**
		 * 测试加载用户关注的话题
		 */
		function testLoadUserFollowedTopics(){
			$followedTopics=$this->user->loadUserFollowedTopics();
			//如果获取到用户关注的话题，结果数量就大于0
			$this->assertTrue(is_array($followedTopics) && count($followedTopics)>=0);
		}
		
		/**
		 * 测试用户删除自己的话题
		 */
		function testDeleteSelfTopic(){
			$topicId=TopicIdForDelete;
			$deleteTopicCount=$this->user->deleteSelfTopic($topicId);
			//删除掉话题，1次之后失效
			$this->assertTrue(is_numeric($deleteTopicCount) && $deleteTopicCount>=0);
		}
		/**
		 * 测试搜索问题、话题或人
		 */
		function testQueryUserOrQuestionOrTopic(){
			$keyword="测试";
			$result=$this->user->queryUserOrQuestionOrTopic($keyword);
			//删除掉话题，1次之后失效
			$this->assertTrue(is_array($result) && count($result)>0);	
		}
		/**
		 * 测试搜索排名前十的热点问题
		 */
		function testGetTenHotQuestions(){
			$questions=$this->user->getTenHotQuestions();
			$this->assertTrue(is_array($questions) && count($questions)>0);	
		}
		/**
		 * 测试搜索和用户行业相关的10个最新问题，用户未登录的情况
		 */
		function testRecommendQuestionsByJob(){
			$this->user->logout();
			//未登录的情况下获取到0条信息
			$remQuestions=$this->user->recommendQuestionsByJob();
			$this->assertTrue(is_array($remQuestions) && count($remQuestions)==0);		
		}
		
		/**
		 * 测试搜索和用户行业相关的10个最新问题，用户登录的情况
		 */
		function testRecommendQuestionsByJobLogon(){
			$result=$this->user->recommendQuestionsByJob();
			$this->assertTrue(is_array($result) && count($result)>0);
		}
		
		/**
		 * 测试获取等待用户回复的问题（没有评论的问题），也只取十个问题
		 */
		function testGetWaitReplyQuestions(){
			//未登录的情况下获取到0条信息
			$waitReplyQuestions=$this->user->getWaitReplyQuestions();
			$this->assertTrue(is_array($waitReplyQuestions) && count($waitReplyQuestions)>0);
		}
		
		/**
		 * 测试搜索排名前十的热点话题
		 */
		function testGetTenHotTopics(){
			$topics=$this->user->getTenHotTopics();
			$this->assertTrue(is_array($topics) && count($topics)>0);
		}
		
		/**
		 * 测试搜索排名前十的人
		 */
		function testGetTenHotUsers(){
			$users=$this->user->getTenHotUsers();
			$this->assertTrue(is_array($users) && count($users)>0);
		}
		
		/**
		 * 测试获取今日十条话题
		 */
		function testGetTodayTopics(){
			$topics=$this->user->getTodayTopics();
			$this->assertTrue(is_array($topics) && count($topics)>=0);
		}
		
		/**
		 * 测试通过用户Id获取用户名
		 */
		function testGetUserNameByUserId(){
			$userId=UserId;
			$username=$this->user->getUserNameByUserId($userId);
			$this->assertEquals($username,"王五");
		}
		
		/**
		 * 测试获取系统设置信息
		 */
		function testGetSystemSettingInfo(){
			$this->assertTrue($this->user->getMaxQuestion()>0);
			$this->assertTrue($this->user->getMaxTopic()>0);
			$this->assertTrue($this->user->getMaxArticle()>0);
			$this->assertTrue($this->user->getMaxComment()>0);
			$this->assertTrue($this->user->getMaxFindPassword()>0);
			$this->assertTrue($this->user->getMaxSendEmailCount()>0);
		}
		
		/**
		 * 测试获取用户的权限
		 */
		function testGetUserAuthority(){
			$authorities=$this->user->getUserAuthority();
			$this->assertTrue(is_array($authorities) && count($authorities)>=0);
			//测试完之后退出登录
			$this->user->logout();
			
			//未登录情况下结果为0（空数组）
			$authorities=$this->user->getUserAuthority();
			$this->assertTrue(is_array($authorities) && count($authorities)==0);
		}
		
		/**
		 * 测试用户评论和回复次数是否超过了限制
		 */
		function testIsUserCommentReplyCountOverTimes(){
			$result=$this->user->isUserCommentReplyCountOverTimes();
			$this->assertFalse($result);
		}
		
		/**
		 * 测试用户评论和回复次数是否超过了限制
		 */
		function testIsCreateQuestionOverCount(){
			$result=$this->user->isCreateQuestionOverCount();
			$this->assertFalse($result);
		}
		
		/**
		 * 测试生成token
		 */
		function testCreateToken(){
			$result=$this->user->createToken();
			$this->assertTrue(is_string($result));
		}
		
		/**
		 * 测试图片路径是否保存在数据库中
		 */
		function testIsImageSavedInDB(){
			$imagePath="测试";
			$result=$this->user->isImageSavedInDB($imagePath);
			$this->assertTrue($result);
		}
		
		/**
		 * 测试删除用户多余的图片
		 */
		function testDeleteUserSpareImages(){
			$result=$this->user->deleteUserSpareImages();
			//这个测试不方便断言，可以直接查看文件夹中文件的变化
			$this->assertTrue($result);
		}
		
		/**
		 * 测试判断邮箱是否存在，用于用户找回密码
		 */
		function testIsEmailExist(){
			$email="wangwu@123.com";
			$emailExist=$this->user->isEmailExist($email);
			$this->assertTrue($emailExist);
		}
		
		/**
		 * 测试今日邮件总数是否超量
		 */
		function testIsSendEmailOverTimes(){
			$emailOverTimes=$this->user->isSendEmailOverTimes();
			$this->assertFalse($emailOverTimes);
		}
		
		/**
		 * 测试找回密码总数是否超过限制（一般只允许用户一天使用找回密码功能3,5次）
		 */
		function testIsFindPasswordOverTimes(){
			$email="wangwu@123.com";
			$pwdOverTimes=$this->user->isFindPasswordOverTimes($email);
			$this->assertFalse($pwdOverTimes);
		}
		
		/**
		 * 测试找回密码
		 */
		function testFindPassword(){
			$email="noexistemail@fuckemail.com";
			$result=$this->user->findPassword($email);
			//$this->assertTrue(is_numeric($result) && $result==1);//实际邮箱测试没有问题，为节约邮件资源，使用一个不存在的邮箱
			$this->assertTrue(is_string($result) && $result=="该邮箱不存在");
		}
		
		/**
		 * 测试记录发送的邮件
		 */
		function testRecordEmail(){
			$emailContent="亲爱的，我想你了";
			$reciverEmail="13396097230@163.com";
			$emailType="activeAccount";
			$result=$this->user->recordEmail($emailContent, $reciverEmail, $emailType);
			$this->assertTrue($result);
		}
		
	}

?>