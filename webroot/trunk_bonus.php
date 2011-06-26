<?php
	// Include the config file
	require_once '../config/config.php';
	
	// just incase the database isn't called yet
	require_once '../database.php';
	$rs1 = mysql_query("SELECT user_id, credit FROM bids WHERE description = 'Bid thÆ°á»Ÿng khi gia nháº­p.'");
	while($bid = mysql_fetch_array($rs1)){
		$rs2 = mysql_query("SELECT SUM(debit) AS sum_debit FROM bids WHERE user_id = ".$bid['user_id']." HAVING SUM(debit) < ".$bid['credit']);
		while($row = mysql_fetch_array($rs2)){
			mysql_query("INSERT INTO bids(user_id, auction_id, description, type, credit, debit, created, modified) VALUES(".$bid['user_id'].", 0, 'Chuyển XU free thành điểm thưởng', 'XU transfer', 0, ".($bid['credit']-$row['sum_debit']).", NOW(), NOW())");
		}
	}
	
	echo "OK";
	
