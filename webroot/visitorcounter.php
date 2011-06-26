<?php

	// Include the config file
	require_once '../config/config.php';
	
	// just incase the database isn't called yet
	require_once '../database.php';

	$auction_id=$_POST['auction_id'];
	
	//delete who don't have any active 5 minutes
	$sql="DELETE FROM visitors WHERE TIMESTAMPDIFF(MINUTE,modified,NOW())>5";
	$result= mysql_query($sql);
	
	//insert or update
	$sql="SELECT username FROM users JOIN visitors ON users.id=visitors.user_id WHERE auction_id=$auction_id";
	$result= mysql_query($sql);
	echo "<strong> Số người đang online:".mysql_num_rows($result)." </strong><br>";
	while($row=mysql_fetch_array($result))
		echo $row['username'].',';
	
?>