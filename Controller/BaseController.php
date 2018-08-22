<?php
	class BaseController{
		public function returnRusult($result,$count="count"){
			//如果返回值是数字，就封装到数组中，用json格式发送
			if(is_numeric($result)){
				$result=array($count=>$result);
				return json_encode($result);
			}
			//如果返回值是数组，就用json格式发送，否则（如果是字符串），就使用urlencode编码之后发送
			if(is_array($result)){
				return json_encode($result);
			}else{
				return urlencode($result);
			}
		}
		
		public function returnArrayJson($result){
			//如果返回值是数组，就用json格式发送，否则（如果是字符串），就使用urlencode编码之后发送
			if(is_array($result)){
				return json_encode($result,true);
			}else{
				return urlencode($result);
			}
		}
	}
?>