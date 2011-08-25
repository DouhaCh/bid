<?php
	include('../../database.php');

	include('../../daemon_functions.php');

	$ids = $_REQUEST['auctions'];
	
	$ids = json_decode($ids);
	
	$q = "SELECT auctions.*, users.username FROM auctions LEFT JOIN users ON auctions.leader_id = users.id WHERE auctions.id IN (".implode(",", $ids).") AND auctions.active = 1 AND auctions.deleted = 0";
	$rs = mysql_query($q);
		
	$data = array();
	$data['auctions'] = array();
	while($auction = mysql_fetch_array($rs, MYSQL_ASSOC)){
		$item = array(
			'id' => $auction['id'],
			'username' => empty($auction['username']) ? "Chưa có ai" : $auction['username'],
			'price' => $auction['price'],
			'end_time' => strtotime($auction['end_time']),
			'closed' => $auction['closed']
		);
		
		array_push($data['auctions'], $item);
	}
	
	echo json_encode($data);
	