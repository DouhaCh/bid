<?php
	// Include the config file
	require_once '../config/config.php';
	
	// just incase the database isn't called yet
	require_once '../database.php';
	
	// Starting session
	session_start();
	
	$q = $_GET["q"];
	
	if(!empty($_GET['user_id'])){
		$user_id = $_GET['user_id'];
	}	
	
	switch($q){
		case 1:
			$username = $_REQUEST["data"]["User"]["username"];
			$sql = "SELECT * FROM users WHERE username = '".$username."'";
			if(mysql_num_rows(mysql_query($sql))>0){
				echo 'false';
			}else{
				echo 'true';
			};
			break;
		case 2:
			$mobile = $_REQUEST["data"]["User"]["mobile"];
			$sql = "SELECT * FROM users WHERE mobile = '".$mobile."'  AND id != ".$user_id;
			if(mysql_num_rows(mysql_query($sql))>0){
				echo 'false';
			}else{
				echo 'true';
			};
			break;
		case 3:
			$sid = $_REQUEST["data"]["User"]["sid"];
			$sql = "SELECT * FROM users WHERE sid = '".$sid."' AND id != ".$user_id;
			if(mysql_num_rows(mysql_query($sql))>0){
				echo 'false';
			}else{
				echo 'true';
			};
			break;
		case 4:
			$email = $_REQUEST["data"]["User"]["email"];
			$sql = "SELECT * FROM users WHERE email = '".$email."' AND id != ".$user_id;
			if(mysql_num_rows(mysql_query($sql))>0){
				echo 'false';
			}else{
				echo 'true';
			};
			break;
			
	}
?>