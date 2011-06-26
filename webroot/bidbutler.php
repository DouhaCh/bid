<?php
	
	// Include the config file
	require_once '../config/config.php';
	
	// just incase the database isn't called yet
	require_once '../database.php';
	
	// Include JSON libs
	require_once '../vendors/fastjson/fastjson.php';
	
	$auction_id = $_GET['auction_id'];
	// Starting session
	session_start();
	
	// Reading user id
	if(!empty($_SESSION['Auth']['User']['id'])){
		$user_id = $_SESSION['Auth']['User']['id'];
	}else{
		$user_id = null;
	}
	$sql="SELECT * FROM bidbutlers WHERE auction_id=".$auction_id." AND user_id=".$user_id;
	
	$bidbutler=mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC);
	
	$json = new FastJSON();
	echo $json->convert($bidbutler);

?>