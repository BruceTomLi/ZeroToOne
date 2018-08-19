<?php
	require_once("SessionDBC.php");
	/**
	 * 下面是图片处理类，用来处理上传的图片
	 * 原本上传图片也可以算是User功能的一种，但是为了方便代码复用，这里抽象出ImageHandler进行处理
	 */
	class ImageHandler{
		/**
		 * 下面的程序将在富文本编辑器中加入的图片上传到服务器的文件夹中
		 */
		function uploadImage(){
			$fileName=$_FILES['file']['tmp_name']??"";
			$realName=$_FILES['file']['name']??"";
			$realName=iconv('utf-8','gbk',$realName);	
			
			//进行图片类型检查
			if (!($_FILES["file"]["type"] == "image/gif") && !($_FILES["file"]["type"] == "image/jpeg")
				&& !($_FILES["file"]["type"] == "image/png") && !($_FILES["file"]["type"] == "image/pjpeg")
				&& !($_FILES["file"]["type"] == "image/x-png"))
			{
				return json_encode(urlencode("上传的文件类型不符合要求，仅支持200KB以下gif,jpg,png格式图片"));
			}	
			
			if(filesize($fileName)<200000){
				//防止因为session过期导致页面出错，因为上传图片没有直接经过User类的登录检测
				if(isset($_SESSION['username'])){
					$username=$_SESSION['username'];
					$username=iconv('utf-8','gbk',$username);	
					$newPath="../UploadImages/{$username}/".$realName;
					$directory="../UploadImages/{$username}";
					if (!file_exists($directory)) {
					    mkdir($directory, 0777, true );
					}
					if(is_uploaded_file($fileName)){
						if(!file_exists($newPath)){
							move_uploaded_file($fileName, $newPath);	
						}							
					}
					$newPath=iconv('gbk','utf-8',$newPath);
					$resultArr=array("newPath"=>$newPath);
					//返回的地址是html页面对应的地址
					return json_encode($resultArr);
				}else{
					return json_encode(urlencode("session已经过期，请重新登录系统"));
				}
			}else{
				return json_encode(urlencode("你上传的图片体积过大，系统只支持上传小于200KB的图片"));
			}					
		}
		
	}
	
	$imageHandler=new ImageHandler();
	echo $imageHandler->uploadImage();
	//print_r($_FILES['file']);
?>