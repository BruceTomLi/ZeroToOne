<?php
	require_once(__DIR__."/../Model/ConstData.php");
	/**
	 * 这个文件用来设置一些测试用的数据
	 * 因为测试时需要对数据库中的数据进行增删改查，可能导致测试进行完第一次之后就测试失败
	 */
	
	//下面是给User类的测试数据
	define("UserName", "王五");
	define("Password","ww@123");
	define("UserEmail","wangwu@123.com");
	define("RepeatName","tomtom");
	define("RepeatEmail","1402343@qq.com");
	define("Province","湖北省");
	define("QuestionType","电脑网络");
	define("QuestionContent","这是单元测试里面用来测试创建一个新的问题的测试内容");
	define("QuestionDescription","这里是问题描述，实际情况中可以填写超文本信息");
	define("QuestionId","5b4fe29fc28900.16360449");
	define("QuestionIdForDelete","5b6127a880ac39.27722801");
	define("ExampleComment","这里测试给问题增加一条评论");
	define("CommentId","5b4fe52284d406.43129252");
	define("ReplyId","5b533f62a5b881.48909021");
	define("ReplyCommentContent","这里测试给一条评论添加一条回复");
	define("ReplyReplyContent","这里测试给一条回复添加一条回复");
	define("FatherReplyId","5b533f62a5b881.48909021");
	
	define("NewUserName","tomtom");
	define("NewUserEmail","liyuntian@123.com");
	define("UserId","11");
	define("DisableUserId","9");//不能让王五禁用自己，禁用之后他无法再次登录系统启用自己
	//此时还没有开发出话题的功能，所以给的是一个replyId，是为了测试用户关注话题
	define("TopicType","电脑网络");
	define("TopicContent","这是单元测试里面用来测试创建一个新的话题的测试内容");
	define("TopicDescription","这里是话题描述，实际情况中可以填写超文本信息");
	define("TopicId","5b642b0667ac21.59867297");
	define("TopicIdForDelete","1");
	
	//下面是给PowerManager类的测试数据
	define("AuthorityId","99");
	define("AuthorityName","testAuthority");
	
	//下面是给RoleManager类的测试数据
	define("RoleName","测试角色");
	define("RoleNote","这是在单元测试里面增加一个测试角色");
	define("RoleAuthority","99");
	define("RoleId", "5b5abea4d23cc1.30122938");
	define("DeleteRoleId", "5b5aceffd4b556.67207669");//这个数据是一次性的，被删除之后再产生的就不是这个Id了
	
	//下面是给UserManager类的测试数据,由于使用王五作为测试用户，所以即使修改角色，也要给他所有的角色，防止修改角色后失去某些权限
	define("UserRoles","5b5ac3d9d02270.56110138,5b5abea4d23cc1.30122938,
		5b59889024a9a4.37599248,5b598819643081.13204722,5b5ac3d9d02270.56110138,
		5b6ffc255ed8a7.23436916,5b6ffc320ba204.43374433,5b6ffc4e3e8bc4.97433025,
		5b6ffc68b609b8.16208812");
	
	//下面是给Author类的测试数据
	define("ArticleTitle","测试文章标题");
	define("ArticleSize",3000);
	define("ArticleLabel","电影观后感");
	define("ArticleContent","最近我们看了一部电影，叫做“我不是药神”，讲述主人公为了延续一千多名慢粒白血病患者的生命， 不惜从印度进口违禁药，最后被警方抓到判刑的故事。如果正义和法律不能两全，我们到底是做守法奉公 的好公民呢，还是做坚持正义的救世主呢？而我想到的不仅仅是这个问题，还有，一个救世主能够做多久呢？");
	define("ArticleId","5b5eafdc32f240.91762645");
	define("ArticleIdForDelete","5b5eaf71441c55.98247029");
	
	//下面是给Operator类的测试数据
	define("NoticeId","5b698a8fb77b35.35637706");
?>