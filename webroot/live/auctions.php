<?php
	include('../../database.php');

	include('../../daemon_functions.php');

	$ids = $_REQUEST['auctions'];
	
	$ids = json_decode($ids);
	
	$q = "SELECT auctions.*, users.username, users.avatar FROM auctions LEFT JOIN users ON auctions.leader_id = users.id WHERE auctions.id IN (".implode(",", $ids).") AND auctions.active = 1 AND auctions.closed = 0 AND auctions.deleted = 0";
	$rs = mysql_query($q);
		
	$data = array();
	$data['auctions'] = array();
	while($auction = mysql_fetch_array($rs, MYSQL_ASSOC)){
		$item = array(
			'id' => $auction['id'],
			'username' => empty($auction['username']) ? "Chua co ai" : $auction['username'],
			'avatar' => empty($auction['avatar']) ? "default.jpg" : $auction['avatar'],
			'price' => $auction['price'],
			'end_time' => strtotime($auction['end_time'])
		);
		
		array_push($data['auctions'], $item);
	}
	
	echo json_encode($data);
	