<?php
	/**
	 * 这个类里面封装一个Valcode类，用来产生图片资源
	 */
	
	
	class Valcode{
		public static function checkCode():string{
			$code=$_GET['num']??'';
			return $code;
		}
		
		public static function createValcodeImg(string $valcode){
			if($valcode!=null && $valcode!=''){
				header("content-type:image/png");
				$num=$valcode;
				$imagewidth=70;//画布宽度
				$imageheight=20;//画布高度
				$numimage=imagecreate($imagewidth,$imageheight);//创建画布
				imagecolorallocate($numimage,240,240,240);//设置画布颜色
				for($i=0;$i<strlen($num);$i++){//循环读取随机数
					$x=mt_rand(1,8)+$imagewidth*$i/4;//x坐标为1到8的随机数，定位到前1/4宽度
					$y=mt_rand(1,$imageheight/4);//y坐标为1到高度的1/4之间
					$color=imagecolorallocate($numimage,mt_rand(0,150),mt_rand(0,150),mt_rand(0,150));//定义画布颜色
					imagestring($numimage,5,$x,$y,$num[$i],$color);//将随机数写入画布中，使用imagettftext函数可以写入更有个性的文本，但是实现相对复杂，这里不再展开
				}
				
				for($i=0;$i<200;$i++){
					$randcolor=imagecolorallocate($numimage,rand(200,255),rand(200,255),rand(200,255));//定义随机颜色
					imagesetpixel($numimage,rand()%70,rand()%20,$randcolor);//在图片中写入干扰线，70和20分别接近60和18
				}
				imagepng($numimage);//输出到浏览器，给出文件名时输出到文件bool imagepng ( resource $image [, string $filename ] )
				imagedestroy($numimage);//释放图像资源文件
			}
			else{
				return "未产生验证码，请检查是否正确传入了参数！";
			}
		}
	}

	Valcode::createValcodeImg(Valcode::checkCode());
?>