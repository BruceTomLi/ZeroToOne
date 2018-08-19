<?php
	require_once(__DIR__."/../classes/MysqlPdo.php");
	require_once("TestData.php");
	use PHPUnit\Framework\TestCase;
	class AuthorTest extends TestCase{
		private $pdo;
		
		function setUp(){
			$this->pdo=new MysqlPdo();
		}
		
		/**
		 * 测试将数据进行html编码之后保存到数据库中
		 */
		function testSaveCharsByHtmlCode(){			
			$chars="<script>alert('haha');</script><a href='http://www.baidu.com'>巴蒂</a>";
			$paraArr=array(":chars"=>$chars);
			$sql="insert into tb_testhtmlcode values(null,:chars)";
			$row=$this->pdo->getUIDResult($sql,$paraArr);
			$this->assertEquals($row,1);
			
			$paraArr=array(":chars"=>"%".$chars."%");
			$sql="select content from tb_testhtmlcode where content like :chars";
			$content=$this->pdo->getOneFiled($sql, "content",$paraArr);
			$this->assertTrue($chars!=$content);
		}
	}
?>