<?php
	require_once("../classes/Valcode.php");
	use PHPUnit\Framework\TestCase;
	
	class ValcodeTest extends TestCase{
		/**
		 * 下面的函数检测一下当传入值是空串（未从$_GET中获取到值）时，打印出错信息
		 * 由于函数的作用是向浏览器输出一个图片，这里不方便进行测试，故未对输出图片进行测试
		 * 可以直接执行Valcode.php文件中的函数，在浏览器中查看生成的图片进行测试
		 */		
		 
		function testCreateValcodeImg(){
			$valcode=Valcode::createValcodeImg(Valcode::checkCode());
			$this->assertEquals("未产生验证码，请检查是否正确传入了参数！",$valcode);			
		}		
	}
?>