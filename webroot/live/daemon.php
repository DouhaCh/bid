<?php
include('../../config/config.php');

include('../../database.php');

include('../../daemon_functions.php');

$expireTime = time() + 4;

while (time() < $expireTime) {
	daemonBidbutler();
	
	daemonAuction();
	
	// sleep for 0.5 of a second
	usleep(500000);
	mysql_free_result($sql);
}