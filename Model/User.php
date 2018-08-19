<?php
	require_once("ConstData.php");	
	require_once(__DIR__.'/../classes/SessionDBC.php');
	require_once(__DIR__."/../Config/config.php");
	require_once(__DIR__.'/../classes/MysqlPdo.php');	
	require_once(__DIR__.'/../classes/ImgCompress.php');	
	class User{
		// 下面的属性在程序中都用不到，我先注释起来
		// private $id;
		// private $username;
		// private $password;
		// private $email;
		// private $sex;
		// private $job;
		// private $province;
		// private $city;
		// private $oneWord;
		// private $heading;
				
		function __construct(){
			//在构造函数中设置时间格式，避免在其他地方重复设置
			date_default_timezone_set('PRC'); 
		}
		/**
		 * 下面的注册方法是填写完整信息之后进行注册
		 * 成功注册信息时，返回true，否则返回false
		 */
		function register($username,$password,$email,$sex,$job,$province,$city,$oneWord,$heading=null){
			if($this->chkRegisterInfo($username, $password, $email)){
				global $pdo;
				if(!$this->isUsernameRepeat($username) && !$this->isEmailRepeat($email)){
					$username=trim($username);
					$email=trim($email);
					$password=trim($password);
					//向数据库中写入用户数据
					$password=md5($password);
					$userId=uniqid('',true);
					$paraArr=array(':userId'=>$userId,':username'=>$username,':password'=>$password,':email'=>$email,
						':sex'=>$sex,':job'=>$job,':province'=>$province,':city'=>$city,':oneWord'=>$oneWord,':heading'=>$heading,':enable'=>"1",':active'=>"0");
					$sql="insert into tb_user values(:userId,:username,:password,:email,:sex,:job,:province,:city,:oneWord,:heading,:enable,:active);";
					$sql.="insert into tb_userrole values(:userId,(select roleId from tb_role where name='普通用户'));";
					$affectRow=$pdo->getUIDResult($sql,$paraArr);
					
					//给用户发送激活账号的邮件
					$url=BASE_URL.'activation.php';
					$url.='?id='.$userId.'&code='.$password;//此时密码已经通过md5加密
					$message="亲爱的用户，你注册成功了。  {$url}  请访问该地址，激活您的用户!";
					$mailOk=mail($email, "零一知享-激活用户账号",$message)==true?"yes":"no";
					$result=array("newAccount"=>$affectRow,"mailOk"=>$mailOk);
					return $result;
				}
				else{
					return "用户名或者邮箱重复，不能注册";
				}			
			}
			else{
				return "注册失败，用户名、密码或者邮箱校验失败";
			}
			
		}

		/**
		 * 激活用户
		 */
		function activeAccount($userId,$password){
			global $pdo;
			$paraArr=array(":userId"=>$userId,":password"=>$password);
			$sql="update tb_user set active=1 where userId=:userId and password=:password";
			$result=$pdo->getUIDResult($sql,$paraArr);
			return $result;
		}
		/**
		 * 下面的函数用来检测用户填写的注册信息，在执行注册函数时，应该先检测用户传给服务器的信息是否合法
		 */
		private function chkRegisterInfo($username,$password,$email){
			$isUsernameOk=false;
			$isPasswordOk=false;
			$isEmailOk=false;
			
			if(!empty($username) && strlen($username)>=3 && strlen($username)<=20){
				$isUsernameOk=true;
			}
			if(!empty($password) && strlen($password)>=6 && strlen($password)<=18){
				$isPasswordOk=true;
			}
			if(!empty($email) && filter_var($email,FILTER_VALIDATE_EMAIL)){
				$isEmailOk=true;
			}
			$isAllOk=$isUsernameOk && $isPasswordOk && $isEmailOk;
			return $isAllOk;
		}
		/**
		 * 用户在注册时检测用户名是否重复
		 */
		function isUsernameRepeat($username){
			global $pdo;
			$username=trim($username);
			$paraArr=array(":username"=>$username);
			$sql="select count(*) as userCount from tb_user where username=:username";
			$result=$pdo->getOneFiled($sql, "userCount",$paraArr);
			if($result==0){
				return false;
			}
			else{
				return true;
			}
		}
		/**
		 * 用于在注册时检测邮箱是否重复
		 */
		function isEmailRepeat($email){
			global $pdo;
			$email=trim($email);
			$paraArr=array(":email"=>$email);
			$sql="select count(*) as emailCount from tb_user where email=:email";
			$result=$pdo->getOneFiled($sql, "emailCount",$paraArr);
			if($result==0){
				return false;
			}
			else{
				return true;
			}
		}
		
		/**
		 * 下面的函数实现用户登录功能，用户登录只需要输入邮箱或者用户名，之后输入密码就可以了
		 */
		function login($password,$emailOrUsername){
			$password=md5($password);
			global $pdo;
			$paraArr=array(":password"=>$password,":emailOrUsername"=>$emailOrUsername);
			$sql="select username,active,enable from tb_user where password=:password and (email=:emailOrUsername or username=:emailOrUsername)";
			$result=$pdo->getQueryResult($sql,$paraArr);
			if(is_array($result) && count($result)>0){
				if($result[0]["active"]==0){
					return "用户还未激活";
				}
				if($result[0]["enable"]==0){
					return "用户已被禁用";
				}
				$_SESSION['username']=$result[0]['username'];
				session_write_close();//执行这个函数，php会马上执行session的“写入”和“关闭”函数
				return "success";
			}				
			else{
				return "用户名或密码错误";
			}			
		}
		/**
		 * 下面的函数检测用户的登录信息是否合法，虽然已经在js里面进行过校验，但是
		 * 不能阻止黑客不通过浏览器向服务器发起请求
		 */
		private function chkLoginInfo($password,$emailOrUsername){
			$isPwdOk=false;
			$isEmailOrUsernameOk=false;
			if(is_string($emailOrUsername) && !empty($emailOrUsername)){
				$isEmailOrUsernameOk=true;
			}
			if(is_string($password) && !empty($password)){
				$isPwdOk=true;
			}
			
			$isAllOk=$isEmailOrUsernameOk && $isPwdOk;
			return $isAllOk;
		}
		
		/**
		 * 下面定义一个函数用来让用户创建一个新的问题
		 */
		function createNewQuestion($questionType,$questionContent,$questionDescription){
			if(!$this->isCreateQuestionOverCount()){
				if(!$this->isQuestionRepeat($questionContent)){
					global $pdo;
					$questionId=uniqid("",true);
					$asker=$_SESSION['username'];
					//需要先获取到登录用户的用户ID，tb_question的数据库中需要保存用户Id而不是用户名，因为用户名虽然唯一但可变
					//但是这样需要进行两次sql查询，影响性能，我试试将两条sql语句合并成一条				
					// $paraArr=array(":username"=>$asker);
					// $sql="select userId from tb_user where username=:username";
					// $askerId=$pdo->getOneFiled($sql, "userId",$paraArr);
					
					$askerDate=date("Y-m-d H:i:s");
					$paraArr=array(":questionId"=>$questionId,":asker"=>$asker,":askDate"=>$askerDate,":questionType"=>$questionType,
						":questionContent"=>$questionContent,":questionDescription"=>$questionDescription,":enable"=>"1");
					$sql="insert into tb_question values(:questionId,(select userId from tb_user where username=:asker),:askDate,:questionType,:questionContent,:questionDescription,:enable)";
					$result=$pdo->getUIDResult($sql,$paraArr);
					//删除用户多余的图片（文章，话题或者问题中的）
					$this->deleteUserSpareImages();
					return $result;
				}else{
					return "问题重复了";
				}
			}else{
				return "今日提问数量已经达到上限";
			}			
		}
		

		/**
		 * 判断用户今天写的问题是否超过了限制
		 */
		function isCreateQuestionOverCount(){
			global $pdo;
			$username=$_SESSION['username'];
			$paraArr=array(":username"=>$username);
			$sql="call pro_isAskQuestionOverTimes(:username)";
			$result=$pdo->getOneFiled($sql, "isOverTimes",$paraArr);
			return $result=="yes"?true:false;
		}
		
		/**
		 * 下面定义的函数检测用户要添加的问题是否重复
		 */
		function isQuestionRepeat($questionContent){
			global $pdo;
			$paraArr=array(":questionContent"=>$questionContent);
			$sql="select count(*) as questionCount from tb_question where content=:questionContent";
			$count=$pdo->getOneFiled($sql, "questionCount",$paraArr);
			$result=$count>0?true:false;
			return $result;
		}
		
		/**
		 * 下面定义函数检测用户是否登录，基于session
		 * 原本打算定义单独的类对用户登录状态进行检测，但是后来发现定义在
		 * User类中更符合逻辑，而且在执行只有登录状态下才能执行的函数时
		 * 直接调用User类中的函数进行检测，更加安全
		 */		 
		function isUserLogon(){
			if(!empty($_SESSION['username'])){
				return true;
			}
			else{
				return false;
			}
		}
		
		/**
		 * 下面的函数测试用户的登出功能
		 */
		function logout(){					
			if(isset($_SESSION['username'])){
				//这里使用@是因为在session_start()函数前面如果有其他输出，就会报警告，主要是为phpunit设置的
				@session_start();
				session_destroy();
				return true;
			}
			else{
				return false;
			}
		}
		
		/**
		 * 下面是用户注册时获取工作列表的功能
		 */
		function getJobList(){
			$jobOptions = '';
			$jobPdo = new MysqlPdo();
			$sql="select job from tb_job";
			$jobLists = $jobPdo->getQueryResult($sql);
			return $jobLists;
		}
		/**
		 * 下面是用户注册时获取省份列表的功能
		 */
		function getProvinceList(){
			$provinceOptions = '';
			$provincePdo = new MysqlPdo();
			$sql="select province from tb_province";
			$provinceLists = $provincePdo->getQueryResult($sql);
			return $provinceLists;
		}
		/**
		 * 下面是用户注册时根据省份获取城市的功能
		 */
		function getCityList($province){
			global $pdo;
			$paraArr = array(":province"=>$province);
			$sql="select city from tb_city where provinceId in(select provinceId from tb_province where province = :province)";			
			$cityLists = $pdo->getQueryResult($sql,$paraArr);
			return $cityLists;
		}
		/**
		 * 获取登录后的用户名
		 */
		function getLogonUsername(){
			if(!empty($_SESSION['username'])){
				return $_SESSION['username'];
			}
			else{
				return "未登录";
			}
		}
		
		/**
		 * 获取用户提出的问题的问题列表
		 */
		function getSelfQuestionList($page=1){
			global $pdo;
			//获取分页数据
			$questionsCount=$this->getSelfQuestionCount();
			$pageTotal=ceil($questionsCount/5);//获取总页数
			//规范化页数，并返回起始页
			$startRow=$this->getStartPage($page,$pageTotal);
			
			//分页时要用到limit，由于对limit后面的参数（不能用数组参数传值）
			//在上面进行了限制，可以防止sql注入，所以对于该参数使用字符串拼接
			$paraArr=array(":asker"=>$_SESSION['username']);
			$sql="select questionId,:asker as asker,askDate,questionType,content,enable from tb_question where askerId=(select userId from tb_user where username=:asker) limit $startRow,5";
			$questionList=$pdo->getQueryResult($sql,$paraArr);
			return $questionList;
		}
		
		/**
		 * 获取个人问题的总数
		 */
		function getSelfQuestionCount(){
			global $pdo;
			$paraArr=array(":logonUser"=>$_SESSION['username']);
			$sql="select count(*) as questionsCount from tb_question where askerId=(select userId from tb_user where username=:logonUser)";
			$questionsCount=$pdo->getOneFiled($sql, "questionsCount",$paraArr);
			return $questionsCount;				
		}
		
		/**
		 * 下面通过问题的Id获取到问题的详情
		 */
		function getQuestionDetailsByQuestionId($questionId){
			global $pdo;				
			$paraArr=array(":questionId"=>$questionId);
			$sql="select * from tb_question where questionId=:questionId";
			$questionDetails=$pdo->getQueryResult($sql,$paraArr);
			return $questionDetails;
		}
		
		/**
		 * 下面通过搜索问题内容或者描述中的关键字来检索相应的问题（针对单个用户）
		 * 单个用户搜索问题的时候，无论问题是否被禁用，都要能搜索出来进行管理
		 */
		function getQuestionListByContentOrDescription($keyword,$page=1){
			global $pdo;
			//获取分页数据
			$count=$this->getQuestionListByContentOrDescriptionCount($keyword);
			$pageTotal=ceil($count/5);//获取总页数
			//规范化页数，并返回起始页
			$startRow=$this->getStartPage($page,$pageTotal);
			
			$username=$_SESSION['username'];
			$keyword="%".$keyword."%";//使用模糊查询，前后要加百分号
			$paraArr=array(":keyword"=>$keyword,":asker"=>$username);
			$sql="select :asker as asker,tq.* from tb_question tq where askerId=(select userId from tb_user where username=:asker) ";
			$sql.="and (content like :keyword or questionDescription like :keyword) limit $startRow,5";
			$questionList=$pdo->getQueryResult($sql,$paraArr);
			return $questionList;
		}
		
		/**
		 * 获取关键字搜索出的问题条数
		 */
		function getQuestionListByContentOrDescriptionCount($keyword){
			global $pdo;
			$username=$_SESSION['username'];
			$keyword="%".$keyword."%";//使用模糊查询，前后要加百分号
			$paraArr=array(":keyword"=>$keyword,":asker"=>$username);
			$sql="select count(*) as questionCount from tb_question tq where askerId=(select userId from tb_user where username=:asker) and (content like :keyword or questionDescription like :keyword)";
			$count=$pdo->getOneFiled($sql,"questionCount",$paraArr);
			return $count;
		}
		
		/**
		 * 下面的函数用来禁用用户个人的问题
		 * 在查询中使用当前登录者，防止黑客禁用其他人的问题
		 * hasAuthority这个参数是给管理员用的，如果判断出用户非问题创建者，但是有问题管理权限，也可以管理问题
		 */
		function disableSelfQuestion($questionId,$hasAuthority=false){
			global $pdo;
			$logonUser=$_SESSION['username'];
			$paraArr=array();
			if($hasAuthority){
				$paraArr=array(":questionId"=>$questionId);
				$sql="update tb_question set enable=0 where questionId=:questionId ";
			}
			else{
				$paraArr=array(":questionId"=>$questionId,":logonUser"=>$logonUser);
				$sql="update tb_question set enable=0 where questionId=:questionId ";
				$sql.="and askerId=(select userId from tb_user where username=:logonUser)";
			}				
			$result=$pdo->getUIDResult($sql,$paraArr);
			return $result;
		}
		
		/**
		 * 下面的函数用来启用用户个人问题
		 * 在查询中使用当前登录者，防止黑客启用其他人的问题
		 * hasAuthority这个参数是给管理员用的，如果判断出用户非问题创建者，但是有问题管理权限，也可以管理问题
		 */
		function enableSelfQuestion($questionId,$hasAuthority=false){
			global $pdo;
			$logonUser=$_SESSION['username'];
			$paraArr=array();
			if($hasAuthority){
				$paraArr=array(":questionId"=>$questionId);
				$sql="update tb_question set enable=1 where questionId=:questionId ";
			}
			else{
				$paraArr=array(":questionId"=>$questionId,":logonUser"=>$logonUser);
				$sql="update tb_question set enable=1 where questionId=:questionId ";
				$sql.="and askerId=(select userId from tb_user where username=:logonUser)";
			}
			$result=$pdo->getUIDResult($sql,$paraArr);
			return $result;
		}
		
		/**
		 * 下面的函数用来让用户评论一个问题
		 */
		function commentQuestion($questionId,$content){
			if(!$this->isUserCommentReplyCountOverTimes()){
				global $pdo;
				//向数据库中增加评论信息
				$commenter=$_SESSION['username'];
				$commentId=uniqid("",true);
				
				$commentDate=date("Y-m-d H:i:s");
				$paraArr=array(":commentId"=>$commentId,":questionId"=>$questionId,
					":commenter"=>$commenter,":commentDate"=>$commentDate,":content"=>$content,":enable"=>"1");
				$sql="insert into tb_comment values(:commentId,:questionId,(select userId from tb_user where username=:commenter),:commentDate,:content,:enable)";
				$affectRow=$pdo->getUIDResult($sql,$paraArr);
				
				$comment=$this->getCommentByCommentId($commentId);
				
				$resultArr=array("affectRow"=>$affectRow,"createdComment"=>$comment);				
				
				return $resultArr;
			}else{
				return "今日评论和回复数量已经达到上限";//如果这里不编码，在浏览器中显示就是16进制编码了	
			}			
		}
		
		/**
		 * 判断用户当天评论数是否超过了限制
		 */
		function isUserCommentReplyCountOverTimes(){
			global $pdo;
			//向数据库中增加评论信息
			$username=$_SESSION['username'];
			$paraArr=array(":username"=>$username);
			$sql="call pro_isUserCommentReplyCountOverTimes(:username)";
			$result=$pdo->getOneFiled($sql, "isOverTimes",$paraArr);
			return $result=="yes"?true:false;
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
		 * 下面的函数通过问题号加载相应的评论
		 */
		function getCommentsForQuestion($questionId){
			global $pdo;
			$logonUser=$_SESSION['username']??"";
			$paraArr=array(":logonUser"=>$logonUser,":questionId"=>$questionId);
			//$sql="select * from tb_comment where questionId=:questionId";
			//$sql="select case when commenter=:logonUser then 'true' else 'false' end as isCommenter,commentId,";
			//$sql.="questionId,commenter,commentDate,content from tb_comment where questionId=:questionId;";
			//由于逻辑变得有点复杂（需要判断是否为当前登录者，需要获取该评论的回复数，所以改为使用存储过程
			$sql="call pro_getComments(:logonUser,:questionId)";
			$result=$pdo->getQueryResult($sql,$paraArr);
			return $result;
		}
		
		/**
		 * 下面的函数根据评论号删除一个评论
		 */
		function disableCommentForQuestion($commentId){
			if($this->isCommentEnable($commentId)){
				global $pdo;
				$commenter=$_SESSION['username'];
				$paraArr=array(":commentId"=>$commentId,":commenter"=>$commenter);
				//加入关于评论者的条件，保证这里只有评论者可以删除自己的评论，而不是任意一个登录的人
				$sql="update tb_comment set enable=0 where commentId=:commentId and commenterId=(select if(count(userId)>0,userId,'') from tb_user where username=:commenter)";
				$result=$pdo->getUIDResult($sql,$paraArr);
				return $result;
			}else{
				return "评论已经被删除";
			}
		}
		
		/**
		 * 下面的函数检测一个评论是否可用
		 */
		function isCommentEnable($commentId){
			global $pdo;
			$paraArr=array(":commentId"=>$commentId);
			$sql="select enable from tb_comment where commentId=:commentId";
			$result=$pdo->getOneFiled($sql,"enable",$paraArr)=="1"?true:false;
			return $result;
		}
		
		/**
		 * 下面的函数给评论添加相应的回复
		 */
		function createReplyForComment($fatherReplyId,$commentId,$content){
			if(!$this->isUserCommentReplyCountOverTimes()){
				//向数据库中插入新的回复信息
				global $pdo;
				
				$replyer=$_SESSION['username'];
				$replyId=uniqid("",true);
				
				$replyDate=date("Y-m-d H:i:s");
				$paraArr=array(":replyId"=>$replyId,":fatherReplyId"=>$fatherReplyId,":commentId"=>$commentId,
					":replyer"=>$replyer,":replyDate"=>$replyDate,":content"=>$content,":enable"=>"1");
				$sql="insert into tb_reply values(:replyId,:fatherReplyId,:commentId,(select userId from tb_user where username=:replyer),:replyDate,:content,:enable)";
	
				$insertRow=$pdo->getUIDResult($sql,$paraArr);
				//获取刚插入的回复信息
				$replyContent=$this->getReplyByReplyId($replyId);
				$resultArr=array("insertRow"=>$insertRow,"replyContent"=>$replyContent);
				return $resultArr;
			}
			else{
				return "今日评论和回复数量已经达到上限";
			}
			
		}
		
		
		/**
		 * 下面的函数给评论回复添加相应的回复
		 */
		function createReplyForReply($fatherReplyId,$commentId,$content){
			if(!$this->isUserCommentReplyCountOverTimes()){
				//向数据库中插入新的回复信息
				global $pdo;
				
				$replyer=$_SESSION['username'];
				$replyId=uniqid("",true);
				
				$replyDate=date("Y-m-d H:i:s");
				$paraArr=array(":replyId"=>$replyId,":fatherReplyId"=>$fatherReplyId,":commentId"=>$commentId,
					":replyer"=>$replyer,":replyDate"=>$replyDate,":content"=>$content,":enable"=>"1");
				$sql="insert into tb_reply values(:replyId,:fatherReplyId,:commentId,(select userId from tb_user where username=:replyer),:replyDate,:content,:enable)";
				$insertRow=$pdo->getUIDResult($sql,$paraArr);
				//获取刚插入的回复信息
				$replyContent=$this->getReplyByReplyId($replyId);
				$resultArr=array("insertRow"=>$insertRow,"replyContent"=>$replyContent);
				return $resultArr;
			}
			else{
				return "今日评论和回复数量已经达到上限";
			}
		}
		
		
		/**
		 * 下面的函数给评论删除相应的回复
		 */
		function disableReplyForComment($replyId){
			if($this->isReplyEnable($replyId)){
				global $pdo;
				$replyer=$_SESSION['username'];
				$paraArr=array(":replyId"=>$replyId,":replyer"=>$replyer);
				$sql="update tb_reply set enable=0 where replyId=:replyId and replyerId=(select if(count(userId)>0,userId,'') from tb_user where username=:replyer)";
				$result=$pdo->getUIDResult($sql,$paraArr);
				return $result;
			}else{
				return "回复已经被删除";
			}
		}
		
		/**
		 * 下面的函数检测一个回复是否可用
		 */
		function isReplyEnable($replyId){
			global $pdo;
			$paraArr=array(":replyId"=>$replyId);
			$sql="select enable from tb_reply where replyId=:replyId";
			$result=$pdo->getOneFiled($sql,"enable",$paraArr)=="1"?true:false;
			return $result;
		}
		
		/**
		 * 下面的函数给回复删除相应的回复
		 * 实际上使用的是上面给评论删除回复同样的方法，所以下面的方法用不到
		 */
		/*function disableReplyForReply($replyId){
			global $pdo;
			$replyer=$_SESSION['username'];
			$paraArr=array(":replyId"=>$replyId,":replyer"=>$replyer);
			$sql="update tb_reply set enable=0 where replyId=:replyId and replyerId=(select if(count(userId)>0,userId,'') from tb_user where username=:replyer)";
			$result=$pdo->getUIDResult($sql,$paraArr);
			return $result;
		}*/
		
		/**
		 * 下面的函数给一个评论加载相应的回复信息
		 */
		function getReplysForComment($commentId){
			global $pdo;
			$paraArr=array(":commentId"=>$commentId);
			//下面的sql语句已经对应比较复杂的逻辑了，可以考虑使用mysql的函数或者存储过程来完成
			//主要作用1.获得当前reply对应的父reply的replyer 2.判断reply表中的replyer是否为当前登录者
			//作用1是为了在前台显示谁回复了谁的信息，作用2是为了在前台决定是否显示删除按钮
			$sql="call pro_getReplys(:commentId)";
			$result=$pdo->getQueryResult($sql,$paraArr);
			return $result;
		}
		
		/**
		 * 下面的函数获取单个问题的评论数
		 */
		function getCommentCountByQuestionId($questionId){
			global $pdo;
			$paraArr=array(":questionId"=>$questionId);
			$sql="select count(commentId) as commentCount from tb_comment where questionId=:questionId and enable=1";
			$commentCount=$pdo->getOneFiled($sql, "commentCount",$paraArr);
			return $commentCount;
		}
		
		/**
		 * 下面的函数获取单个评论的回复数
		 */
		function getReplyCountByCommentId($commentId){
			global $pdo;
			$paraArr=array(":commentId"=>$commentId);
			$sql="select count(replyId) as replyCount from tb_reply where commentId=:commentId and enable=1";
			$replyCount=$pdo->getOneFiled($sql, "replyCount",$paraArr);
			return $replyCount;
		}
		
		/**
		 * 下面的函数通过replyId获取reply
		 * 主要是为了方便用户在回复完信息之后可以加载出新的信息
		 */
		function getReplyByReplyId($replyId){
			global $pdo;
			$paraArr=array(":replyId"=>$replyId);
			$sql="call pro_getReplyByReplyId(:replyId)";
			$result=$pdo->getQueryResult($sql,$paraArr);
			return $result;
		}
		
		/**
		 * 用户登录且打开管理页面时，加载用户信息（通过session中的username）
		 */
		function loadUserInfo(){
			global $pdo;
			$username=$_SESSION['username'];
			$paraArr=array(":username"=>$username);
			//这里不应对密码进行查询，但是要加载角色，为避免复杂逻辑，从视图中加载
			$sql="select roles,userId,username,email,sex,job,province,city,oneWord,heading from view_usershasroles where username=:username";
			$result=$pdo->getQueryResult($sql,$paraArr);
			return $result;
		}
		
		/**
		 * 下面的函数修改用户自己的信息
		 */
		function changeSelfInfo($userInfo){
			global $pdo;
			$username=$_SESSION['username'];
			$newUsername=$userInfo['username'];
			$email=$userInfo['email'];
			$sex=$userInfo['sex'];
			$job=$userInfo['job'];
			$province=$userInfo['province'];
			$city=$userInfo['city'];
			$oneWord=$userInfo['oneWord'];
			$paraArr=array(":username"=>$username,":newUsername"=>$newUsername,":email"=>$email,
				":sex"=>$sex,":job"=>$job,":province"=>$province,
				":city"=>$city,":oneWord"=>$oneWord);
			//传入的用户名和邮箱没有被占用时才能更新
			if(!$this->isUsernameUsedByOthers($userInfo['username']) && !$this->isEmailUsedByOthers($userInfo['email'])){
				$sql="update tb_user set username=:newUsername,email=:email,sex=:sex,job=:job,province=:province,city=:city,";
				$sql.="oneWord=:oneWord where username=:username";
				$result=$pdo->getUIDResult($sql,$paraArr);
				if($result==1){
					//如果进行了修改，就改变session中的username值，以便用户可以继续正常浏览网页
					$_SESSION['username']=$userInfo['username'];
				}
				return $result;
			}
			else{
				return "用户名或者邮箱重复重复，无法更新";
			}
		}
		
		/**
		 * 下面的函数检测用户用户要修改的用户名是否被其他人占用了， 和之前的isUsernameRepeat函数不同，
		 * 这种情况是自身占用了该名称，要改成新的名称，检测新的名称是否被占用，因为changeSelfInfo的修改包括了用户名，
		 * 存在新旧用户名相同的情况，无论是否发生更改，都会在执行sql语句时修改。邮箱的情况也一样
		 */
		function isUsernameUsedByOthers($newUsername){
			$oldUsername=$_SESSION['username'];
			global $pdo;
			//直接检测和现在用户名不同，且和新用户名相同的记录数
			$paraArr=array(":oldUsername"=>$oldUsername,":newUsername"=>$newUsername);
			$sql="select count(username) as userCount from tb_user where username=:newUsername and username!=:oldUsername";
			$result=$pdo->getOneFiled($sql, "userCount",$paraArr);
			if($result==0){
				return false;
			}
			else{
				return true;
			}		
		}
		
		/**
		 * 下面检测邮箱是否被占用
		 */
		function isEmailUsedByOthers($newEmail){
			global $pdo;
			$username=$_SESSION['username'];
			//直接检测和现在用户名不同，且和新邮箱相同的记录数
			$paraArr=array("username"=>$username,":newEmail"=>$newEmail);
			$sql="select count(email) emailCount from tb_user where email=:newEmail and username!=:username";
			$emailCount=$pdo->getOneFiled($sql, "emailCount",$paraArr);
			
			if($emailCount==0){
				return false;
			}
			else{
				return true;
			}
		}
		
		/**
		 * 下面的函数修改用户自己的密码
		 */
		function changeSelfPassword($oldPassword,$newPassword){
			if(strlen($oldPassword)<18 && strlen($newPassword)<18){
				global $pdo;
				$oldPassword=md5($oldPassword);
				$newPassword=md5($newPassword);
				$username=$_SESSION['username'];
				$paraArr=array(":oldPassword"=>$oldPassword,":newPassword"=>$newPassword,":username"=>$username);
				$sql="update tb_user set password=:newPassword where username=:username and password=:oldPassword";
				$affectRow=$pdo->getUIDResult($sql,$paraArr);
				return $affectRow;
			}
			else{
				return "密码长度不符合要求";
			}
		}
		
		/**
		 * 下面的函数让用户对一个问题/话题/人进行关注
		 * 理论上应该在插入之前判断用户是否已经关注了，但是这样会引起对数据库进行两次查询
		 * 降低网站整体性能。我将在sql语句中进行判断，让一次查询一次插入变成仅有一次插入
		 */
		function addFollow($starId,$followType){
			if(!$this->isFollowExist($starId)){
				global $pdo;
				$logonUser=$_SESSION['username'];
				$followId=uniqid("",true);
				$paraArr=array(":fansName"=>$logonUser,":followId"=>$followId,":starId"=>$starId,":followType"=>$followType);
				//下面的语句会判断该关注是否已经存在，如果存在了，就不会插入数据了
				//$sql="insert into tb_follow (followId,starId,fansId,type) select :followId,:starId,(select userId from tb_user where username=:logonUser),:followType ";
				//$sql.="from dual where not exists (select * from tb_follow where starId=:starId and fansId=(select userId from tb_user where username=:logonUser))";
				$sql="insert into tb_follow values(:followId,:starId,(select userId from tb_user where username=:fansName),:followType)";
				
				$affectRow=$pdo->getUIDResult($sql,$paraArr);
				return $affectRow;
			}else{
				return "关注已经存在";
			}
		}
		
		/**
		 * 判断关注是否已经存在
		 */
		function isFollowExist($starId){
			global $pdo;
			$logonUser=$_SESSION['username'];
			$paraArr=array(":fansName"=>$logonUser,":starId"=>$starId);
			//下面的语句会判断该关注是否已经存在，如果存在了，就不会插入数据了
			$sql="select count(*) as followCount from tb_follow where starId=:starId and fansId=(select userId from tb_user where username=:fansName)";
			
			$count=$pdo->getOneFiled($sql, "followCount",$paraArr);
			return $count>0?true:false;	
		}
		
		/**
		 * 下面的函数让用户对一个问题/话题/人取消关注
		 */
		function deleteFollow($starId){
			global $pdo;
			$logonUser=$_SESSION['username'];
			//理论上传入followId，对相应的follow记录进行删除也是可以的，但是这里传入userId和questionId感觉更符合语境
			$paraArr=array(":logonUser"=>$logonUser,":starId"=>$starId);
			//取消关注就简单了，不管用判断用户是否关注过
			$sql="delete from tb_follow where starId=:starId and fansId=(select userId from tb_user where username=:logonUser)";
			$affectRow=$pdo->getUIDResult($sql,$paraArr);
			return $affectRow;
		}
		
		/**
		 * 下面的函数检测用户是否关注了问题/话题/人
		 */
		function hasUserFollowed($starId){
			global $pdo;
			$logonUser="";
			if($this->isUserLogon()){
				$logonUser=$_SESSION['username'];
			}			
			//理论上传入followId，对相应的follow记录进行删除也是可以的，但是这里传入userId和questionId感觉更符合语境
			$paraArr=array(":logonUser"=>$logonUser,":starId"=>$starId);
			//取消关注就简单了，不管用判断用户是否关注过
			$sql="select count(*) as followCount from tb_follow where starId=:starId and fansId=(select userId from tb_user where username=:logonUser)";
			$followCount=$pdo->getOneFiled($sql,"followCount",$paraArr);
			//能查询到关注信息，说明用户关注了问题，结果是1或者0，可以代表true或者false
			return $followCount;
		}
		
		/**
		 * 下面的函数加载用户关注的问题/话题/人，在用户管理自己的关注的时候被用到
		 * 前面的函数不涉及到问题/话题表，所以可以通用，下面的函数加载问题/话题/人信息，就需要单独写
		 */
		function loadUserFollowedQuestions(){
			global $pdo;
			$username=$_SESSION['username'];
			$paraArr=array(":logonUser"=>$username);
			$sql="select (select username from tb_user where userId=tq.askerId) as asker,tq.* from tb_question tq where questionId in(select starId from tb_follow where fansId=(select userId from tb_user where username=:logonUser))";
			$followedQuestions=$pdo->getQueryResult($sql,$paraArr);
			return $followedQuestions;
		}
		
		/**
		 * 下面的函数加载用户关注的人
		 */
		function loadUserFollowedUsers(){
			global $pdo;
			$username=$_SESSION['username'];
			$paraArr=array(":logonUser"=>$username);
			$sql="select userId,username,sex,email,oneWord from tb_user where userId in (select starId from tb_follow where fansId in(select userId from tb_user where username=:logonUser))";
			$followedUsers=$pdo->getQueryResult($sql,$paraArr);
			return $followedUsers;
		}
		
		/**
		 * 下面的函数用来上传用户头像
		 */
		function uploadSelfHeading($fileName,$realName,$isUnitTest=false){
			global $pdo;
			//通过登录用户名获取到相应的userId
			$username=$_SESSION['username'];
			$paraArr=array(":username"=>$username);
			$sql="select userId from tb_user where username=:username";
			$userId=$pdo->getOneFiled($sql,"userId",$paraArr);
			
			//根据用户Id创建相应的文件夹保存用户的头像
			$directory="../UploadImages/heading/$userId";
			if(file_exists($directory)){
				//如果文件夹存在，就先删除文件夹（里面有原来的图片）
				$this->deleteDirectory($directory);
			}
			if(!file_exists($directory)){
				//如果文件夹不存在，就创建文件夹，避免报错
				mkdir("$directory", 0777, true );
			}
			$newPath="$directory/$realName";	
			//取出没有后缀的文件名，并由此得出新的mini文件名
			$suffix = substr(strrchr($realName, '.'), 1);
			$realNameNoSuffix = basename($realName,".".$suffix);
			$realMiniName=$realNameNoSuffix."_mini.".$suffix;
			$newMiniPath="$directory/$realMiniName";
			
			$newPath=iconv('utf-8','gbk',$newPath);//保存文件前转化编码
			$newMiniPath=iconv('utf-8','gbk',$newMiniPath);//保存文件前转化编码
			if(is_uploaded_file($fileName)){							
				move_uploaded_file($fileName, $newPath);	
				//保存图片的压缩版
				$this->compressHeadingImg($newPath,$newMiniPath);			
			}
			
			//函数is_uploaded_file会检测文件是不是通过http上传的，
			//如果不是结果为false，这不方便进行单元测试，所以加了下面的代码
			if($isUnitTest==true){
				copy($fileName, $newPath);
				//保存图片的压缩版
				$this->compressHeadingImg($newPath,$newMiniPath);	
			}				
			
			$newPath=iconv('gbk','utf-8',$newPath);//将数据传回前端前再次把编码转化回来
			$fileUploadOk=file_exists($newPath)?1:0;
			//将用户上传的图片地址更新到tb_user表的heading字段中				
			$paraArr=array(":heading"=>$newPath,":userId"=>$userId);
			$sql="update tb_user set heading=:heading where userId=:userId";	
			$affectRow=$pdo->getUIDResult($sql,$paraArr);		
			
			//记录文件上传结果（是否存到了对应文件夹），记录数据库更新结果				
			$resultArr=array("fileUploadOk"=>$fileUploadOk,"newPath"=>$newPath,"affectRow"=>$affectRow);
			//返回的地址是html页面对应要显示图片的地址
			return $resultArr;
		}

		/**
		 * 下面的函数用来压缩保存图片，压缩的图片用来在论坛中显示头像
		 */
		private function compressHeadingImg($source,$dst_img){
			if(file_exists($source)){
				$percent=1;
				$sourceSize=filesize($source);
				if($sourceSize>=20000){
					$percent=0.1;//大于20KB的图压缩到10%					
				}
				else if($sourceSize>10000 && $sourceSize<20000){
					$percent=0.2;//10KB到20KB之间的图压缩到20%
				}
				else if($sourceSize>5000 && $sourceSize<=10000){
					$percent=0.3;//5KB到10KB之间的图压缩到30%
				}
				else if($sourceSize>3000 && $sourceSize<=5000){
					$percent=0.5;//3KB到5KB之间的图压缩到50%
				}
				$image = (new ImgCompress($source,$percent))->compressImg($dst_img);				
			}
		}
		 
		/**
		 * 下面的函数通过递归的方式删除文件夹下的文件，最后删除文件夹
		 */
		private function deleteDirectory($dir){
		    $result = false;
		    if ($handle = opendir("$dir")){
		        $result = true;
		        while ((($file=readdir($handle))!==false) && ($result)){
		            if ($file!='.' && $file!='..'){
	            	    if (is_dir("$dir/$file")){
		                    $result = deleteDirectory("$dir/$file");
		                } else {
		                    $result = unlink("$dir/$file");
		                }
		            }
		        }
		        closedir($handle);
		        if ($result){
		            $result = rmdir($dir);
		        }
		    }
		    return $result;
		}
		
		/**
		 * 通过UserId获取用户的信息，用来在一个人查看其他用户个人信息的时候显示出相应的基本信息
		 * 包括用户名，用户头像，用户邮件，用户性别。使用这个方法不需要用户处于登录状态
		 */
		function getUserBaseInfoByUserId($userId){
			global $pdo;
			$paraArr=array("userId"=>$userId);
			$sql="select userId,username,email,oneWord,sex,heading from tb_user where userId=:userId";
			$personalInfo=$pdo->getQueryResult($sql,$paraArr);
			if(count($personalInfo)>0){
				$resultArr=array("personalInfo"=>$personalInfo);
				return $resultArr;
			}
			else{
				return "未获取到用户信息";
			}
		}
		
		/**
		 * 下面的函数用来让一个用户关注另外一个用户
		 */
		function addUserFollow($starId){
			global $pdo;
			$logonUser=$_SESSION['username'];
			$followId=uniqid("",true);
			$paraArr=array(":logonUser"=>$logonUser,":followId"=>$followId,":starId"=>$starId);
			//下面的语句会判断该关注是否已经存在，如果存在了，就不会插入数据了
			$sql="insert into tb_follow (followId,starId,fansId,type) select :followId,:starId,(select userId from tb_user where username=:logonUser),'1' ";
			$sql.="from dual where not exists (select * from tb_follow where starId=:starId and fansId=(select userId from tb_user where username=:logonUser))";
			
			$affectRow=$pdo->getUIDResult($sql,$paraArr);
			return $affectRow;
		}
		
		/**
		 * 加载用户的粉丝
		 */
		function loadUserFans(){
			global $pdo;
			$logonUser=$_SESSION['username'];
			$paraArr=array(":logonUser"=>$logonUser);
			$sql="select * from tb_user where userId in(select fansId from tb_follow where starId=(select userId from tb_user where username=:logonUser))";
			
			$fans=$pdo->getQueryResult($sql,$paraArr);
			return $fans;
		}
		
		/**
		 * 用户删除自己的问题
		 * hasAuthority这个参数是给管理员用的，如果判断出用户非问题创建者，但是有问题管理权限，也可以管理问题
		 */
		function deleteSelfQuestion($questionId,$hasAuthority=false){
			global $pdo;
			$logonUser=$_SESSION['username'];
			$paraArr=array();
			if($hasAuthority){
				$paraArr=array(":questionId"=>$questionId);
				$sql="delete from tb_question where questionId=:questionId";
			}
			else{
				$paraArr=array(":logonUser"=>$logonUser,":questionId"=>$questionId);
				$sql="delete from tb_question where questionId=:questionId and askerId =(select userId from tb_user where  username=:logonUser)";
			}
			
			$deleteQuestionCount=$pdo->getUIDResult($sql,$paraArr);
			return $deleteQuestionCount;
		}
		
		/**
		 * 下面的函数用来在分页的时候规范页数信息，并获得起始页
		 */
		function getStartPage($page,$pageTotal){
			//将传入的$page限制在合适范围
			$page=intval($page)?$page:1;//不是数字时设为1
			$page=floor($page)<1?1:floor($page);//小于1时设为1
			$page=floor($page)>$pageTotal?$pageTotal:floor($page);//大于最大页数时设为最大页数
			$startRow=$page*5-5;//数据库index是从0开始的
			return $startRow;
		}
		
		/**
		 * 加载问题类型
		 */
		function loadQuestionTypes(){
			global $pdo;
			$sql="select name from tb_types where belongTo='question'";
			
			$types=$pdo->getQueryResult($sql);
			return $types;
		}
		
		/**
		 * 加载作文类型
		 */
		function loadArticleTypes(){
			global $pdo;
			$sql="select name from tb_types where belongTo='article'";
			
			$types=$pdo->getQueryResult($sql);
			return $types;
		}
		
		/**
		 * 加载话题类型
		 */
		function loadTopicTypes(){
			global $pdo;
			$sql="select name from tb_types where belongTo='topic'";
			
			$types=$pdo->getQueryResult($sql);
			return $types;
		}
		
		/*************************下面是针对话题设计的函数，基本上和问题差不多********************/
		/**
		 * 下面定义一个函数用来让用户创建一个新的话题
		 */
		function createNewTopic($topicType,$topicContent,$topicDescription){
			if(!$this->isCreateTopicOverCount()){
				if(!$this->isTopicRepeat($topicContent)){
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
					//删除用户多余的图片（文章，话题或者问题中的）
					$this->deleteUserSpareImages();
					return $result;
				}
				else{
					return "话题重复了";
				}
			}else{
				return "今日创建话题数量已经达到上限";
			}					
		}
		
		/**
		 * 判断用户今天写的话题是否超过了限制
		 */
		function isCreateTopicOverCount(){
			global $pdo;
			$username=$_SESSION['username'];
			$paraArr=array(":username"=>$username);
			$sql="call pro_isCreateTopicOverTimes(:username)";
			$result=$pdo->getOneFiled($sql, "isOverTimes",$paraArr);
			return $result=="yes"?true:false;
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
		
		/**
		 * 获取个人话题的总数
		 */
		function getSelfTopicCount(){
			global $pdo;
			$paraArr=array(":logonUser"=>$_SESSION['username']);
			$sql="select count(*) as topicsCount from tb_topic where askerId=(select userId from tb_user where username=:logonUser)";
			$topicsCount=$pdo->getOneFiled($sql, "topicsCount",$paraArr);
			return $topicsCount;
		}
		
		/**
		 * 下面通过话题的Id获取到话题的详情
		 */
		function getTopicDetailsByTopicId($topicId){
			global $pdo;
			$paraArr=array(":topicId"=>$topicId);
			$sql="select * from tb_topic where topicId=:topicId";
			$topicDetails=$pdo->getQueryResult($sql,$paraArr);
			return $topicDetails;
		}
		
		/**
		 * 下面通过搜索话题内容或者描述中的关键字来检索相应的话题（针对单个用户）
		 * 单个用户搜索话题的时候，无论话题是否被禁用，都要能搜索出来进行管理
		 */
		function getTopicListByContentOrDescription($keyword,$page=1){
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
		
		/**
		 * 获取关键字搜索出的话题条数
		 */
		function getTopicListByContentOrDescriptionCount($keyword){
			global $pdo;
			$username=$_SESSION['username'];
			$keyword="%".$keyword."%";//使用模糊查询，前后要加百分号
			$paraArr=array(":keyword"=>$keyword,":asker"=>$username);
			$sql="select count(*) as topicCount from tb_topic tt where askerId=(select userId from tb_user where username=:asker) and (content like :keyword or topicDescription like :keyword)";
			$count=$pdo->getOneFiled($sql,"topicCount",$paraArr);
			return $count;
		}
		
		/**
		 * 下面的函数用来禁用用户个人的话题
		 * 在查询中使用当前登录者，防止黑客禁用其他人的话题
		 * hasAuthority这个参数是给管理员用的，如果判断出用户非话题创建者，但是有话题管理权限，也可以管理话题
		 */
		function disableSelfTopic($topicId,$hasAuthority=false){
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
		
		/**
		 * 下面的函数用来启用用户个人话题
		 * 在查询中使用当前登录者，防止黑客启用其他人的话题
		 * hasAuthority这个参数是给管理员用的，如果判断出用户非话题创建者，但是有话题管理权限，也可以管理话题
		 */
		function enableSelfTopic($topicId,$hasAuthority=false){
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
		
		/**
		 * 下面的函数用来让用户评论一个话题
		 */
		function commentTopic($topicId,$content){
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
			if($this->isCommentEnable($commentId)){
				global $pdo;
				$commenter=$_SESSION['username'];
				$paraArr=array(":commentId"=>$commentId,":commenter"=>$commenter);
				//加入关于评论者的条件，保证这里只有评论者可以删除自己的评论，而不是任意一个登录的人
				$sql="update tb_comment set enable=0 where commentId=:commentId and commenterId=(select if(count(userId)>0,userId,'') from tb_user where username=:commenter)";
				$result=$pdo->getUIDResult($sql,$paraArr);
				return $result;
			}
			else{
				return "评论已经被删除";
			}
		}
		
		/**
		 * 下面的函数获取单个话题的评论数
		 */
		function getCommentCountByTopicId($topicId){
			global $pdo;
			$paraArr=array(":topicId"=>$topicId);
			$sql="select count(commentId) as commentCount from tb_comment where questionId=:topicId and enable=1";
			$commentCount=$pdo->getOneFiled($sql, "commentCount",$paraArr);
			return $commentCount;
		}
				
		/**
		 * 用户删除自己的话题
		 * hasAuthority这个参数是给管理员用的，如果判断出用户非话题创建者，但是有话题管理权限，也可以管理话题
		 */
		function deleteSelfTopic($topicId,$hasAuthority=false){
			global $pdo;
			$logonUser=$_SESSION['username'];
			$paraArr=array();
			$sql="";
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
		
		/**
		 * 下面的函数加载用户关注的问题/话题/人，在用户管理自己的关注的时候被用到
		 * 前面的函数不涉及到问题/话题表，所以可以通用，下面的函数加载问题/话题/人信息，就需要单独写
		 */
		function loadUserFollowedTopics(){
			global $pdo;
			$username=$_SESSION['username'];
			$paraArr=array(":logonUser"=>$username);
			$sql="select (select username from tb_user where userId=tt.askerId) as asker,tt.* from tb_topic tt where topicId in(select starId from tb_follow where fansId=(select userId from tb_user where username=:logonUser))";
			$followedTopics=$pdo->getQueryResult($sql,$paraArr);
			return $followedTopics;
		}
		
		/**
		 * 用户搜索问题、话题或人,最多显示30条结果
		 */
		function queryUserOrQuestionOrTopic($keyword){
			global $pdo;
			$keyword="%".$keyword."%";
			$paraArr=array(":keyword"=>$keyword);
			$sql="select 'user' as type,userId as id,username as content from tb_user where username like :keyword ";
			$sql.="union select 'question' as type,questionId as id,content from tb_question where content like :keyword ";
			$sql.="union select 'topic' as type,topicId as id,content from tb_topic where content like :keyword limit 20";
			$result=$pdo->getQueryResult($sql,$paraArr);
			return $result;
		}
		
		/**
		 * 获取热门问题，这里只获取排名（评论数）在前十位的问题
		 */
		function getTenHotQuestions(){
			global $pdo;
			$sql="select (select sex from tb_user where userId=askerId) as sex,(select username from tb_user where userId=askerId) as asker,(select heading from tb_user where userId=askerId) as heading,";
			$sql.="vg.commentCount,tq.* from view_gettenhotquestionsid vg,tb_question tq where tq.questionId=vg.questionId";
			$questions=$pdo->getQueryResult($sql);
			return $questions;
		}
		
		/**
		 * 根据用户所在行业进行推荐
		 */
		function recommendQuestionsByJob(){
			global $pdo;
			$username="";
			if($this->isUserLogon()){
				$username=$_SESSION['username'];
			}
			$paraArr=array(":username"=>$username);
			$sql="select (select sex from tb_user where userId=askerId) as sex,(select username from tb_user where userId=askerId) as asker,(select heading from tb_user where userId=askerId) as heading,";
			$sql.="tq.* from tb_question tq where questionType=(select job from tb_user where username=:username) order by askDate desc limit 10";
			$questions=$pdo->getQueryResult($sql,$paraArr);
			return $questions;
		}
		
		/**
		 * 获取等待用户回复的问题（没有评论的问题），也只取十个问题
		 */
		function getWaitReplyQuestions(){
			global $pdo;
			$sql="select (select sex from tb_user where userId=askerId) as sex,(select username from tb_user where userId=askerId) as asker,(select heading from tb_user where userId=askerId) as heading,";
			$sql.="tq.* from tb_question tq where tq.questionId not in(select distinct questionId from tb_comment) order by askDate asc limit 10";
			$questions=$pdo->getQueryResult($sql);
			return $questions;
		}
		
		/**
		 * 获取热门话题，这里只获取排名（评论数）在前十位的问题
		 */
		function getTenHotTopics(){
			global $pdo;
			$sql="select (select sex from tb_user where userId=askerId) as sex,(select username from tb_user where userId=askerId) as asker,(select heading from tb_user where userId=askerId) as heading,";
			$sql.="vg.commentCount,tt.* from view_gettenhottopicsid vg,tb_topic tt where tt.topicId=vg.questionId";
			$topics=$pdo->getQueryResult($sql);
			return $topics;
		}
		
		/**
		 * 获取热门用户，这里只获取排名（提出的问题数和话题总数）在前十位的问题
		 */
		function getTenHotUsers(){
			global $pdo;
			$sql="select sum(totalCount) as total,askerId,asker from view_userquestiontopiccount ";
			$sql.="group by askerId order by total desc limit 10;";
			$users=$pdo->getQueryResult($sql);
			return $users;
		}
		
		/**
		 * 获取今日话题，仅取十条
		 */
		function getTodayTopics(){
			global $pdo;
			
			$todayStart= date('Y-m-d 00:00:00', time());    //今天开始时间
			$todayEnd= date('Y-m-d 23:59:59', time());  //今天结束时间
			$paraArr=array(":startTime"=>$todayStart,":endTime"=>$todayEnd);
			$sql="select * from tb_topic where askDate between :startTime and :endTime order by askDate desc limit 10";
			$topics=$pdo->getQueryResult($sql,$paraArr);
			return $topics;
		}
		
		/**
		 * 通过用户Id获取用户名
		 */
		function getUserNameByUserId($userId){
			global $pdo;
			$paraArr=array(":userId"=>$userId);
			$sql="select username from tb_user where userId=:userId";
			$username=$pdo->getOneFiled($sql, "username",$paraArr);
			return $username;
		}
		
		/**
		 * 获取最大提问数量
		 */	
		function getMaxQuestion(){
			global $pdo;
			$sql="select theValue from tb_systemsetting where theKey='每日最大提问数量'";
			$maxQuestion=$pdo->getOneFiled($sql, "theValue");
			return $maxQuestion;
		}	
		/**
		 * 获取最大话题数量
		 */	
		function getMaxTopic(){
			global $pdo;
			$sql="select theValue from tb_systemsetting where theKey='每日最大创建话题数量'";
			$maxTopic=$pdo->getOneFiled($sql, "theValue");
			return $maxTopic;
		}	
		/**
		 * 获取最大作文数量
		 */	
		function getMaxArticle(){
			global $pdo;
			$sql="select theValue from tb_systemsetting where theKey='每日最大写作数量'";
			$maxArticle=$pdo->getOneFiled($sql, "theValue");
			return $maxArticle;
		}	
		/**
		 * 每日用户最大评论数量
		 */	
		function getMaxComment(){
			global $pdo;
			$sql="select theValue from tb_systemsetting where theKey='每日用户最大评论数量'";
			$maxComment=$pdo->getOneFiled($sql, "theValue");
			return $maxComment;
		}	
		/**
		 * 每日用户找回密码数量
		 */	
		function getMaxFindPassword(){
			global $pdo;
			$sql="select theValue from tb_systemsetting where theKey='每日用户找回密码数量'";
			$maxFindPassword=$pdo->getOneFiled($sql, "theValue");
			return $maxFindPassword;
		}	
		/**
		 * 每日系统邮件总量
		 */	
		function getMaxSendEmailCount(){
			global $pdo;
			$sql="select theValue from tb_systemsetting where theKey='每日系统邮件总量'";
			$maxSendEmailCount=$pdo->getOneFiled($sql, "theValue");
			return $maxSendEmailCount;
		}		
		
		/**
		 * 获取用户权限信息
		 * 用于判断用户是否有权限访问相应的功能
		 */
		function getUserAuthority(){
			if($this->isUserLogon()){
				$username=$_SESSION['username'];
				global $pdo;
				$paraArr=array(":username"=>$username);
				$sql="call pro_getUserAuthority(:username)";
				$authorities=$pdo->getQueryResult($sql,$paraArr);
				return $authorities;
			}else{
				return array();	
			}
		}
		
		/**
		 * 判断用户是否有相应权限
		 */		
		public function hasAuthority($authorityName){
			$authorityName=trim($authorityName);
			$authorities=$this->getUserAuthority();
			foreach($authorities as $authority){
				foreach($authority as $key=>$value){
					if(trim($value)==$authorityName){
						return true;
					}				
				}
			}
			return false;
		}
		
		/**
		 * 产生一个随机的token，用于防止csrf攻击
		 * 简单起见，使用php产生随机数
		 */
		function createToken(){
			$token=uniqid("token",true);
			return $token;
		}
		
		/**
		 * 判断图片是否已经保存在数据库中
		 */
		function isImageSavedInDB($imagePath){
			global $pdo;
			$username=$_SESSION['username']??"";
			$paraArr=array(":username"=>$username,":imagePath"=>$imagePath);
			$sql="call pro_isImageSavedInDB(:username,:imagePath)";
			$isSaved=$pdo->getOneFiled($sql,"isImageSavedInDB",$paraArr);
			return $isSaved=="yes"?true:false;
		}
		
		/**
		 * 删除一个人上传的多余的图片
		 * 理论上在用户在编辑器中传入图片的时候，可以传入用户编辑器中图片路径的数组，并检查这个数组外
		 * 的其他文件夹中的图片，用这个函数删除多余图片，这样比较严谨
		 */
		function deleteUserSpareImages(){
			$username=$_SESSION['username']??"";
			//要先转化编码才能找到
			$username=iconv('utf-8','gbk',$username);	
			$directory="../UploadImages/{$username}/";
			//要判断用户文件夹是否存在
			if(is_dir($directory)){
				//打开该文件夹
				if ($handle = opendir($directory)) {
					//遍历所有文件名称
				    while (false !== ($file = readdir($handle))) {
				        if ($file != "." && $file != "..") {
				        	//删除数据库中不存在的图片
				            if(!$this->isImageSavedInDB($file)){
				            	$file=$directory.$file;
				            	unlink($file);
				            }
				        }
				    }
				    closedir($handle);
					return true;
				}
			}else{
				return "directory not exist";
			}			
		}
		
		/**
		 * 找回密码
		 */
		function findPassword($email){
			$email=trim($email);
			if($this->isEmailExist($email)){
				if(!$this->isSendEmailOverTimes()){
					if(!$this->isFindPasswordOverTimes($email)){
						//产生一个随机数作为密码
						$randomPwd=mt_rand(100000, 999999);
						//发送邮件通知用户新密码
						$emailContent= "亲爱的用户，你的新密码是".$randomPwd."，请尽快登录系统修改密码";
						$mailOk=mail($email,"零一知享-找回密码", $emailContent);
						if($mailOk){							
							global $pdo;
							//记录发送的邮件							
							$this->recordEmail($emailContent, $email, "findPassword");
							//更新数据库中的密码
							$password=md5($randomPwd);
							$paraArr=array(":password"=>$password,":email"=>$email);
							$sql="update tb_user set password=:password where email=:email";
							$count=$pdo->getUIDResult($sql,$paraArr);
							return $count;
						}else{
							return "我们试图给你的邮箱发送密码，但是失败了，请联系管理员找回密码";
						}
						
					}else{
						return "今日找回密码此处已经达到上限，如果你没有收到邮件，请与网站管理员联系";
					}
				}else{
					return "今日系统邮件数已经用完，请明日再试";
				}
			}else{
				return "该邮箱不存在";
			}
		}
		
		/**
		 * 记录系统发送的邮件
		 */
		function recordEmail($emailContent,$reciverEmail,$emailType){
			global $pdo;
			$emailId=uniqid("",true);
			$sendTime=date("Y-m-d H:i:s");
			$paraArr=array(":emailId"=>$emailId,":sendTime"=>$sendTime,":emailContent"=>$emailContent,":reciverEmail"=>$reciverEmail,":emailType"=>$emailType);
			$sql="insert into tb_email values(:emailId,:sendTime,:emailContent,(select userId from tb_user where email=:reciverEmail),:emailType)";
			$result=$pdo->getUIDResult($sql,$paraArr);
			return $result==1?true:false;
		}
		
		/**
		 * 邮箱是否存在
		 */
		function isEmailExist($email){
			global $pdo;
			$paraArr=array(":email"=>$email);
			$sql="select count(*) as emailCount from tb_user where email=:email";
			$count=$pdo->getOneFiled($sql, "emailCount", $paraArr);
			return $count>0?true:false;
		}
		
		/**
		 * 今日发邮件次数是否达到上限
		 */
		function isSendEmailOverTimes(){
			global $pdo;
			$sql="call pro_isSendEmailOverTimes()";
			$emailOverTimes=$pdo->getOneFiled($sql, "emailOverTimes");
			return $emailOverTimes=="yes"?true:false;
		}
		
		/**
		 * 今日找回密码次数是否达到上限
		 */
		function isFindPasswordOverTimes($email){
			global $pdo;
			$paraArr=array(":email"=>$email);
			$sql="call pro_isFindPasswordOverTimes(:email)";
			$pwdOverTimes=$pdo->getOneFiled($sql, "pwdOverTimes", $paraArr);
			return $pwdOverTimes=="yes"?true:false;
		}
	}
?>
