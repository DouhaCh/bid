<?php
	include('../../database');

	include('../../daemon_functions.php');
	
	$auction_id = $_GET['auction_id'];
	
	echo bid($auction_id);
	
	