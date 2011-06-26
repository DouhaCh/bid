<?php

	// Include the config file
	require_once '../config/config.php';
	
	// just incase the database isn't called yet
	require_once '../database.php';
	
	// Include JSON libs
	require_once '../vendors/fastjson/fastjson.php';
	
	$sql = "SELECT username, SUM( credit ) - SUM( debit ) AS bid_balance
	FROM `bids`
	JOIN users ON bids.user_id = users.id
	WHERE users.admin =0
	AND users.active =1
	AND users.changed =1
	GROUP BY user_id
	HAVING SUM( credit ) - SUM( debit ) >100
	ORDER BY SUM( credit ) - SUM( debit ) DESC";
	
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
	