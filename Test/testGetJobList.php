<?php
	require_once("../register_chk.php");
	function testGetJobList(){
		$result=getJobList();
		$result=json_decode($result);
		print_r($result);
	}
	
	//testGetJobList();
?>