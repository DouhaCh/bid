<?php
	session_start();

	include('../../config.php');

	include('../../database.php');

	include('../../daemon_functions.php');
	
	$auction_id = $_GET['auction_id'];
	
	if(!empty($auction_id)){
		$auction = getAuctionById($auction_id);
		if(empty($auction)){
			echo "Phiên đấu giá không hợp lệ";
			return;
		}
	}else{
		echo "Phiên đấu giá không hợp lệ";
		return;
	}
	
	echo bid($auction);