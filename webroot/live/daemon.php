<?php
include('../../config/config.php');

include('../../database.php');

include('../../daemon_functions.php');

$expireTime = time() + 4;

while (time() < $expireTime) {
	daemonBidbutler();
	
	daemonAuction();
	
	// sleep for 1 second
	sleep(1);
	mysql_free_result($sql);
}