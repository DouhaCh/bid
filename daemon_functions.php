<?php
function bid($auction, $auto_bid = false, $bidbutler = null){
	if(!empty($_SESSION['Auth']['User'])){
		$user = $_SESSION['Auth']['User'];
	}elseif(!empty($bidbutler['user_id'])){
		$user = findUserById($bidbutler['user_id']);
	}elseif(!empty($auto_bid['user_id'])){
			
	}else{
		return "Người chơi không hợp lệ";
	}

	$checkBid = checkBid($user, $auction);
	if(!empty($checkBid)) return $checkBid;

	$data = array();
	// insert bid
	$data['Bid'] = array();
	$data['Bid']['user_id'] = $user['id'];
	$data['Bid']['auction_id'] = $auction['id'];
	$data['Bid']['description'] = empty($bidbutler) ? "Single Bid" : "Bid Buddy";
	$data['Bid']['credit'] = 0;
	$data['Bid']['debit'] = $auction['bp_cost'];
	$data['Bid']['created'] = date('Y-m-d H:i:s');
	$data['Bid']['modified'] = date('Y-m-d H:i:s');
	mysql_query("INSERT INTO bids(user_id, auction_id, description, credit, debit, created, modified) VALUES(".$data['Bid']['user_id'].", ".$data['Bid']['auction_id'].", '".$data['Bid']['description']."', '".$data['Bid']['credit']."', '".$data['Bid']['debit']."', '".$data['Bid']['created']."', '".$data['Bid']['modified']."')");

	// update auction
	$data['Auction'] = array();
	if($auction['reverse'] == 1){
		$data['Auction']['price'] = $auction['price'] - $auction['price_step'];
	}else{
		$data['Auction']['price'] = $auction['price'] + $auction['price_step'];
	}

	$data['Auction']['leader_id'] = $user['id'];
	if($auction['end_time'] - time() < $auction['peak_time']){
		if($auction['rapid'] == 1){
			$data['Auction']['end_time'] = date('Y-m-d H:i:s', time() + $auction['peak_time']);
		}else{
			$data['Auction']['end_time'] = date('Y-m-d H:i:s', $auction['end_time'] + $auction['time_increment']);
		}
	}else{
		$data['Auction']['end_time'] = date('Y-m-d H:i:s', $auction['end_time']);
	}

	$data['Auction']['modified'] = date('Y-m-d H:i:s');
	
	mysql_query("UPDATE auctions SET price = ".$data['Auction']['price'].", leader_id = ".$data['Auction']['leader_id'].", end_time = '".$data['Auction']['end_time']."', modified = '".$data['Auction']['modified']."' WHERE id = ".$auction['id']);

	// update bidbutler
	if(!empty($bidbutler)){
		$data['Bidbutler']['bids'] = $bidbutler['bids'] - $auction['bp_cost'];
		$data['Bidbutler']['modified'] = time();
		$data['Bidbutler']['closed'] = $bidbutler['bids'] < 2 * $auction['bp_cost'] ? 1 : 0;
		$data['Bidbutler']['active'] = $bidbutler['bids'] < 2 * $auction['bp_cost'] ? 0 : 1;
		$mysql_query("UPDATE `bidbutlers` SET `bids` = '".$data['Bidbutler']['bids']."', closed  = '".$data['Bidbutler']['closed']."', active = '".$data['Bidbutler']['active']."', modified = '".$data['Bidbutler']['modified']."' WHERE id = ".$bidbutler['id']);
	}

	echo "Bạn đã đặt thành công";
}

function getAuctionById($id){
	$auction = mysql_fetch_array(mysql_query("SELECT auctions.*, users.username, users.avatar FROM auctions LEFT JOIN users ON auctions.leader_id = users.id WHERE auctions.id = ".$id), MYSQL_ASSOC);
	$auction['end_time'] = strtotime($auction['end_time']);
	$auction['start_time'] = strtotime($auction['start_time']);
	return $auction;
}

function getUserById($id){
	$user = mysql_fetch_array(mysql_query("SELECT users.* FROM users WHERE users.id = ".$id), MYSQL_ASSOC);
	$balance = mysql_fetch_array(mysql_query("SELECT SUM(credit) - SUM(debit) AS balance FROM bids WHERE user_id = ".$id), MYSQL_ASSOC);
	$user['bid_balance'] = $balance['balance'];
	return $user;
}

function checkBid($user, $auction){
	// check last bid
	if($user['id'] == $auction['leader_id']){
		return "Bạn đang là người dẫn đầu";
	}

	// check beginner
	if($user['beginner'] == 0 && $auction['beginner'] == 1){
		return "Phiên đấu gía chỉ dành cho người mới chơi";
	}

	// check time
	if(time() < $auction['start_time']){
		return "Phiên đấu giá chưa bắt đầu";
	}

	if(time() > $auction['end_time']){
		return "Phiên đấu giá đã kết thúc";
	}

	// check reverse
	if($auction['reverse'] == 1 && $auction['price'] == 0){
		return "Reverse auction ended";
	}

	// check balance
	if($user['bid_balance'] < $auction['bp_cost']){
		return "Tài khoản của bạn đã hết, hãy nạp thêm để chơi tiép";
	}

	// check active
	if($user['active'] == 0){
		return 'Tài khoản của bạn chưa kích hoạt hoặc bị ban bởi hệ thống';
	}

	return;
}

function daemonBidbutler(){
	$bidButlerTime = 5;

	$bidButlerEndTime = date('Y-m-d H:i:s', time() + $bidButlerTime);

	// Find the bidbutler entry
	$sql = mysql_query("SELECT 	b.auction_id,
							a.price, 
							a.reverse,
							a.end_time,
							b.id, 
							b.minimum_price, 
							b.maximum_price, 
							b.user_id
							FROM auctions a, bidbutlers b
							WHERE a.id = b.auction_id 
							   AND a.deleted=0
							   AND a.end_time < '$bidButlerEndTime' 
							   AND a.closed = 0 
							   AND a.active = 1
							   AND b.active = 1
							   AND b.deleted = 0
							   AND b.closed = 0
							   AND b.bids >= a.bp_cost
							   AND b.minimum_price <= a.price
							   AND b.maximum_price >= a.price
							ORDER BY rand()");
		
	$totalRows = mysql_num_rows($sql);
	if($totalRows > 0) {
		while($bidbutler = mysql_fetch_array($sql, MYSQL_ASSOC)) {
			$auction = getAuctionById($bidbutler['auction_id']);
			if(canBidBuddy($bidbutler, $auction)) {
				bid($auction, null, $bidbutler);
				
				$data = array(
					'Bidbutler' => array(
						'bids' => $bidbutler['bids'] - $auction['bp_cost'],
						'modified' => date('Y-m-d H:i:s')
					)
				);
				
				mysql_query("UPDATE bidbutlers SET bids = ".$data['Bidbutler']['bids'].",
							modified = '".$data['Bidbutler']['modified']."'
							WHERE id = ".$bidbutler['id']);
			}
		}
	}
}

function daemonAuction(){
	// cron for auction close
	$sql = mysql_query("SELECT * FROM auctions WHERE end_time <= '".date('Y-m-d H:i:s')."'
							AND closed = 0 
							AND active = 1 
							AND deleted = 0");
	
	$total_rows = mysql_num_rows($sql);

	if($total_rows > 0) {
		while($auction = mysql_fetch_array($sql, MYSQL_ASSOC)) {
			// before we declare this user the winner, lets run some test to make sure the auction can definitely close
			if(checkCanClose($auction['id']) == false) {
				$endTime = date('Y-m-d H:i:s', strtotime($endTime) + 86400);
				mysql_query("UPDATE auctions SET end_time = '$endTime', modified = '".date('Y-m-d H:i:s')."' WHERE id = ".$auction['id']);
			} else {
				closeAuction($auction);
			}
		}
	}
}

function checkCanClose($auction_id){
	return true;
}

function closeBidbutler($id, $reason){
	mysql_query("UPDATE bidbutlers SET closed = 1,
				active = 0, reason = '".$reason."', modified = '".date('Y-m-d H:i:s')."'
				WHERE id = ".$id);
}

function canBidBuddy($bidbutler, $auction){
	if($bidbutler['minimum_price'] < $auction['price'] && $bidbutler['user_id'] != $auction['leader_id']){
		if($bidbutler['maximum_price'] > $auction['price'] && $bidbutler['bids'] >= $auction['bp_cost']){
			return true;
		}else{
			closeBidbutler($bidbutler['id'], "Bb close");
			return false;
		}
	}else{
		return false;
	}
}

function closeAuction($auction){
	mysql_query("UPDATE auctions SET closed = 1,
				winner_id = leader_id,
				modified = '".date('Y-m-d H:i:s')."'
				WHERE id = '".$auction['id']."'");
	mysql_query("UPDATE bidbutlers SET closed = 1, 
				reason = 'action close', active = 0 
				WHERE auction_id = '".$auction['id']."' 
				AND active = 1");
	echo "Close auction ".$auction['id'];
}