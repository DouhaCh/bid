<?php

	// Include the config file
	require_once '../config/config.php';
	
	// just incase the database isn't called yet
	require_once '../database.php';
	
	// Include JSON libs
	require_once '../vendors/fastjson/fastjson.php';
	
	$sql = "SELECT id, end_time FROM auctions WHERE closed=0";
	
	for($i=0; $i<10; $i++){
		if(!empty($_GET['auction_'.$i])){
			$sql.=" OR id=".$_GET['auction_'.$i];
		}
	}
	$arr = array();
	$q = mysql_query($sql);
	while($result = mysql_fetch_array($q, MYSQL_ASSOC)){
		array_push($arr, $result);
	}
	$json = new FastJSON();
	echo $json->convert($arr);
?>
	