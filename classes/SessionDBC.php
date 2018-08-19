<?php
	/**这个文件里面定义了在数据库中存储session信息的方法，并且在文件的末尾开启session
	 * 之前使用的是面向过程的写法，这里使用面向对象的写法，将函数封装到SessionHandler类中
	 */
	require_once("MysqlPdo.php");
	 
	class SessionDBC{
		/**
		 * 定义打开session的函数，这里使用pdo，所以直接返回true
		 */
		static function open_session(){
			return true;
		}
		/**
		 * 定义关闭session的函数
		 */
		static function close_session(){
			return true;
		}
		/**
		 * 定义读取session的函数
		 */
		static function read_session($sid){
			global $pdo;
			$sql="select data from tb_session where id=:id";
			$paraArr=array(":id"=>$sid);
			if(!empty($pdo)){
				$result=$pdo->getOneFiled($sql,"data", $paraArr);//这个函数没取到值时返回空字符串
				return $result;
			}
			else{
				return "";
			}
			
		}
		/**
		 * 定义写session的函数
		 */
		static function write_session($sid,$data){
			global $pdo;
			$sql="replace into tb_session(id,data) values(:id,:data)";
			$paraArr=array(":id"=>$sid,":data"=>$data);
			if(!empty($pdo)){
				$result=$pdo->getUIDResult($sql,$paraArr);
				return true;
			}
			else{
				return false;
			}			
		}
		/**
		 * 定义销毁session的函数
		 */
		static function destory_session($sid){
			global $pdo;
			$sql="delete from tb_session where id=:id";
			$paraArr=array(":id"=>$sid);
			$result=$pdo->getUIDResult($sql,$paraArr);
			$_SESSION=array();
			return true;
		}
		/**
		 * 定义清理session的函数，这个函数会根据对session的设置，按照一定的几率执行
		 */
		static function clean_session($expire){
			global $pdo;
			$sql="delete from tb_session where date_add(last_accessed,interval :expire second)<now()";
			$expire=(int)$expire;
			$paraArr=array(":expire"=>$expire);
			$result=$pdo->getUIDResult($sql,$paraArr);
			return true;
		}
	}
	 
	session_set_save_handler('SessionDBC::open_session', 'SessionDBC::close_session', 
			'SessionDBC::read_session', 'SessionDBC::write_session', 
			'SessionDBC::destory_session', 'SessionDBC::clean_session');
	
	session_start();
?>