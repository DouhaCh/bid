<?php
	$mc = new Memcached();
	echo "AA";
	var_dump($mc);
	$mc->connect('127.0.0.1', 11211);
	//$mc->connect('127.0.0.1', 11211) or die ("Could not connect");
	var_dump($mc);
?>