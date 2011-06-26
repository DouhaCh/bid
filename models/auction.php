<?php

require_once('..' . DS .'controllers' . DS .'users_controller.php');

	class Auction extends AppModel {

		var $name = 'Auction';

		var $actsAs = array('Containable');

		var $belongsTo = array(
			'Product' => array(
				'className'  => 'Product',
				'foreignKey' => 'product_id'
			),
			'Status' => array(
				'className'  => 'Status',
				'foreignKey' => 'status_id'
			),
			'Winner' => array(
				'className'  => 'User',
				'foreignKey' => 'winner_id'
			),
			'Leader' => array(
				'className'  => 'User',
				'foreignKey' => 'leader_id'
			)
		);

		var $hasOne = array(
			'Message', 'AuctionEmail', 'Testimonial'
		);

		var $hasMany = array(
			'Bidbutler'  => array(
				'className'  => 'Bidbutler',
				'foreignKey' => 'auction_id',
				'limit'      => 10,
				'dependent'  => true
			),

			'Bid' => array(
				'className'  => 'Bid',
				'foreignKey' => 'auction_id',
				'order'      => 'Bid.id DESC',
				'limit'      => 10,
				'dependent'  => true
			),
			
			'Bet' => array(
				'className'  => 'Bet',
				'foreignKey' => 'auction_id',
				'order'      => 'Bet.id DESC',
				'limit'      => 10,
				'dependent'  => true
			),

			'Autobid' => array(
				'className'  => 'Autobid',
				'foreignKey' => 'auction_id',
				'limit'      => 10,
				'dependent'  => true
			),

			'Smartbid' => array(
				'className'  => 'Smartbid',
				'foreignKey' => 'auction_id',
				'limit'      => 10,
				'dependent'  => true
			),

			'Credit' => array(
				'className'  => 'Credit',
				'foreignKey' => 'auction_id',
				'limit'      => 10,
				'dependent'  => true
			),

			'Watchlist' => array(
				'className'  => 'Watchlist',
				'foreignKey' => 'auction_id',
				'limit' 	 => 10,
				'dependent'  => true
			),
			
			'Comment' => array(
				'className'  => 'Comment',
				'foreignKey' => 'auction_id',
				'limit' 	 => 10,
				'dependent'  => true
			),
			'Visitor' => array(
				'className'  => 'Visitor',
				'foreignKey' => 'auction_id',
				'limit' 	 => 10,
				'dependent'  => true
			),
			
		);


		/**
		 * Function to get auctions
		 *
		 * @param array $conditions The conditions
		 * @param int $limit How many auction will be retrieved
		 * @param string $order Ordering string
		 * @return array Auctions array
		 */
		function __construct($id = false, $table = null, $ds = null){
			parent::__construct($id, $table, $ds);
			$this->validate=array(
			'price_step' => array(
					'rule'=> 'numeric',
					'message' => __('Number only.', true),
					'allowEmpty' => false
				),
			'bp_cost' => array(
					'rule'=> 'numeric',
					'message' => __('Number only.', true),
					'allowEmpty' => false
				),
			'max_bid' => array(
					'rule'=> 'numeric',
					'message' => __('Number only.', true),
					'allowEmpty' => false
				),
			'time_increments' => array(
					'rule'=> 'numeric',
					'message' => __('Number only.', true),
					'allowEmpty' => false
				),				
			);
		}
		function getAuctions($conditions = null, $limit = null, $order = 'Auction.end_time DESC', $exclude = false, $folder = 'thumbs') {
			$excludeId = array();
			if(!empty($exclude)){
				foreach($exclude as $excludeAuction){
					$excludeId[] = $excludeAuction['Auction']['id'];
				}	
			}

			if(!empty($conditions) && !empty($excludeId)){
				if(is_array($conditions)){
					$conditions[] = 'Auction.id NOT IN (' . implode(',', $excludeId) .')';
				}
			}

			$this->contain(array('Product' => array('Image' => 'ImageDefault', 'Limit')), 'Winner', 'Watchlist');
			$auctions = $this->find('all', array('conditions' => $conditions, 'order' => $order, 'limit' => $limit));

			// process any translations
			//$auctions = $this->Product->Translation->translate($auctions);
			App::import('model','User');
			
			foreach($auctions as $key => $auction) {
				// Check if auction already started
				if(strtotime($auction['Auction']['start_time']) > time()) {
					$auctions[$key]['Auction']['isFuture'] = true;
				} else {
					$auctions[$key]['Auction']['isClosed'] = $auction['Auction']['closed'];
				}
				
				$tempMod = new User();
				$user=$tempMod->find('first', array(
					'conditions' => array('User.id' => $auction['Auction']['leader_id']),
					'fields'	 => array('username', 'avatar'),
					'contain'	 => false
				));
				
				$auctions[$key]['User']=$user['User'];

				// Put it back into the array
				$auctions[$key]['Auction']['end_time'] = strtotime($auction['Auction']['end_time']);
				
				// Get savings
				if($auction['Product']['rrp'] > 0) {
					if(!empty($auction['Product']['fixed'])) {
						if($auction['Product']['fixed_price'] > 0) {
							$auctions[$key]['Auction']['savings']['percentage'] = round(100 - ($auction['Product']['fixed_price'] / $auction['Product']['rrp'] * 100), 2);
						} else {
							$auctions[$key]['Auction']['savings']['percentage'] = 100;
						}
							$auctions[$key]['Auction']['savings']['price']  = $auction['Product']['rrp'] - $auction['Product']['fixed_price'];
					} else {
						$auctions[$key]['Auction']['savings']['percentage'] = round(100 - ($auction['Auction']['price'] / $auction['Product']['rrp'] * 100), 2);
						$auctions[$key]['Auction']['savings']['price']      = $auction['Product']['rrp'] - $auction['Auction']['price'];
					}
				} else {
					$auctions[$key]['Auction']['savings']['percentage'] = 0;
					$auctions[$key]['Auction']['savings']['price']      = 0;
				}

				if(!empty($auction['Product']['Image'])) {
					if(!empty($auction['Product']['Image'][0]['ImageDefault'])) {
						$auctions[$key]['Auction']['image'] = 'default_images/'.Configure::read('App.serverName').'/'.$folder.'/'.$auction['Product']['Image'][0]['ImageDefault']['image'];
					} else {
						$auctions[$key]['Auction']['image'] = 'product_images/'.$folder.'/'.$auction['Product']['Image'][0]['image'];
					}
				}

				$lastBid = $this->Bid->lastBid($auction['Auction']['id']);
				//var_dump($lastBid);
				if(!empty($lastBid)) {
					$auctions[$key]['LastBid'] = $lastBid;
					
					$temp = explode("@", $auctions[$key]['LastBid']['username']);
					$auctions[$key]['LastBid']['username'] = $temp[0];
					
					if(strlen($auctions[$key]['LastBid']['username'])>12){
						$auctions[$key]['LastBid']['username'] = substr($auctions[$key]['LastBid']['username'], 0, 12)."...";
					}
				} else {
					$auctions[$key]['LastBid']['username'] = __('No bids placed yet', true);
				}

				$auction['Auction']['serverTimestamp'] = time();

				//$auctions[$key]['Histories'] = $this->Bid->histories($auction['Auction']['id'], $this->appConfigurations['bidHistoryLimit'], $historiesOptions);
			}

			if($limit == 1){
				if(!empty($auctions[0])){
					return $auctions[0];
				}
			}

			return $auctions;
		}

		/**
		 * Function to put a bid for an auction. It can be used for single bid
		 * or bidbutler daemon
		 *
		 * @param array $data Data which consist of auction settings like peak_start, end, etc
		 * @return mixed Can be PEAK_ONLY, BID_NOT_ENOUGH, or OK
		 */
		function bid($data = array(), $autobid = false, $bid_description = null) {
			$canBid = true;
			$message = '';
			$flash = '';

			// Get the auctions
			$this->contain();
			$fieldList = array('Auction.id', 'Auction.product_id', 'Auction.start_time', 'Auction.end_time', 'Auction.price', 'Auction.peak_only', 'Auction.closed', 'Auction.minimum_price', 'Auction.autobids', 'Auction.max_end', 'Auction.max_end_time', 'Auction.penny');

			$auction = $this->find('first', array('conditions' => array('Auction.id' => $data['auction_id']), 'fields' => $fieldList));

			if(!empty($auction)){
				// check to see if this is a free auction
				if(!empty($this->appConfigurations['freeAuctions'])) {
					$product = $this->Product->find('first', array('conditions' => array('Product.id' => $auction['Auction']['product_id']), 'fields' => 'Product.free', 'contain' => ''));
					if(!empty($product['Product']['free'])) {
						$data['bid_debit'] = 0;
					}
				}

				if(!empty($this->appConfigurations['limits']['active'])) {
					$limits_exceeded = $this->requestAction('/limits/canbid/'.$data['auction_id'].'/'.$data['user_id']);
					if($limits_exceeded == false) {
						$message = __('You cannot bid on this auction as your have exceeded your bidding limit.', true);
						$canBid = false;
					}
				}

				// Check if the auction has been end - this only applies to NON autobidders
				if((!empty($auction['Auction']['closed']) || strtotime($auction['Auction']['end_time']) <= time()) && $autobid == false) {
					$message = __('Auction has been closed', true);
					$canBid = false;
				}

				// Check if the auction has been not started yet
				if(!empty($auction['Auction']['start_time'])) {
					if(strtotime($auction['Auction']['start_time']) > time()){
						$message = __('Auction has not started yet', true);
						$canBid = false;
					}
				}

				// Check if the auction is peak only and if the now is peak time
				if(!empty($auction['Auction']['peak_only'])){
					if(empty($data['isPeakNow'])){
						$message = __('This is a peak auction', true);
						$canBid = false;
					}
				}

				// Get user balance
				if($autobid == true || $this->appConfigurations['bidButlerDeploy'] == 'group') {
					$balance = $data['bid_debit'];
				} else {
					$balance = $this->Bid->balance($data['user_id']);
				}

				// this goes last to prevent the double bid issues
				$latest_bid = $this->Bid->lastBid($data['auction_id']);
				if(!empty($latest_bid) && $latest_bid['user_id'] == $data['user_id']){
					$message = __('You cannot bid as you are already the highest bidder', true);
					$canBid = false;
				}

				if($canBid == true) {
					// Checking if user has enough bid to place
					if($balance >= $data['bid_debit']) {

						// Check if it's bidbutler call
						if(!empty($data['bid_butler'])) {
							// Find the bidbutler
							$this->Bidbutler->contain();
							$bidbutler = $this->Bidbutler->find('first', array('conditions' => array('Bidbutler.id' => $data['bid_butler'])));

							// If bidbutler found
							if(!empty($bidbutler)){
								if($bidbutler['Bidbutler']['bids'] >= $data['bid_debit']) {
									// Decrease the bid butler bids
									$bidbutler['Bidbutler']['bids'] -= $data['bid_debit'];

									// Save it
									$this->Bidbutler->save($bidbutler, false);
								} else {
									// Get out of here, the bids on bidbutler was empty
									return $auction;
								}
							}
						}

						// Formatting auction time and price increment
						if(!empty($auction['Auction']['penny'])) {
							$auction['Auction']['price'] 	+= 0.01;
						} else {
							$auction['Auction']['price'] 	+= $data['price_increment'];
						}

						$auction['Auction']['end_time'] = date('Y-m-d H:i:s', strtotime($auction['Auction']['end_time']) + $data['time_increment']);
						
						// lets make sure the auction time is now less than now
						if(strtotime($auction['Auction']['end_time']) < time()) {
							$auction['Auction']['end_time'] = date('Y-m-d H:i:s', time() + $data['time_increment']);
						}
						
						// lets check the max end time to see if the end_time is greater than the max_end_time
						if(!empty($auction['Auction']['max_end'])) {
							if(strtotime($auction['Auction']['end_time']) > strtotime($auction['Auction']['max_end_time'])) {
								$auction['Auction']['end_time'] = $auction['Auction']['max_end_time'];
							}
						}

						// lets extend the minimum price if its an auto bidder
						if($autobid == true) {
							if(!empty($auction['Auction']['penny'])) {
								$auction['Auction']['minimum_price'] += 0.01;
							} else {
								$auction['Auction']['minimum_price'] += $data['price_increment'];
							}
							$auction['Auction']['autobids'] += 1;
						} else {
							$auction['Auction']['autobids'] = 0;
						}

						// Formatting user bid transaction
						$bid['Bid']['user_id'] 	  = $data['user_id'];
						$bid['Bid']['auction_id'] = $auction['Auction']['id'];
						$bid['Bid']['credit']     = 0;

						if(!empty($data['bid_butler']) && Configure::read('App.bidButlerType') == 'advanced') {
							$bid['Bid']['debit']      = 0;
						} else {
							$bid['Bid']['debit']      = $data['bid_debit'];
						}
						
						// Insert proper description, bid or bidbutler
						if(!empty($bid_description)) {
							$bid['Bid']['description'] = $bid_description;
						} elseif(!empty($data['bid_butler'])){
							$bid['Bid']['description'] = __('Bid Butler', true);
						} else {
							$bid['Bid']['description'] = __('Single Bid', true);
						}

						// lets check for double bids - 01/03/2008 - Michael - lets only include bids in this check due to error from group deploy for bid butlers
						$auction['Auction']['double_bids_check'] = false;
						$bid['Bid']['double_bids_check'] = true;

						// Saving bid
						if(is_array($data['user_id'])) {
							foreach($data['user_id'] as $user){
								// 2008-02-27 21:44:20 -- Maulana
								// Update the leader id, set the leader_id to
								// latest user_id in array Q
								$auction['Auction']['leader_id'] = $user;
								$this->save($auction);

								$bid['Bid']['user_id'] = $user;
								$this->Bid->create();
								$this->Bid->save($bid);

								if($this->appConfigurations['simpleBids'] == true) {
									$winner = $this->Winner->find('first', array('conditions' => array('Winner.id' => $data['user_id']), 'contain' => ''));
									$winner['Winner']['bid_balance'] -= $bid['Bid']['debit'];
									$winner['Winner']['modified'] = date('Y-m-d H:i:s');
									$this->Winner->save($winner);
								} elseif($autobid == true) {
									// 18/2/2009 - this has been added for "grouped" bids.  We need to update the modified date for the autobidders
									$winner = $this->Winner->find('first', array('conditions' => array('Winner.id' => $data['user_id']), 'contain' => ''));
									$winner['Winner']['modified'] = date('Y-m-d H:i:s');
									$this->Winner->save($winner, false);
								}
							}
						} else {
							// 2008-02-27 21:44:20 -- Maulana
							// Update the leader id to $data['user_id']. since
							// it's not an array we can put it directly
							$auction['Auction']['leader_id'] = $data['user_id'];

							$this->Bid->create();
							$this->Bid->save($bid);

							if($this->appConfigurations['simpleBids'] == true) {
								$winner = $this->Winner->find('first', array('conditions' => array('Winner.id' => $data['user_id']), 'contain' => ''));
								$winner['Winner']['bid_balance'] -= $bid['Bid']['debit'];
								$winner['Winner']['modified'] = date('Y-m-d H:i:s');
								$this->Winner->save($winner, false);
							} elseif($autobid == true) {
								// 18/2/2009 - this has been added for "grouped" bids.  We need to update the modified date for the autobidders
								$winner = $this->Winner->find('first', array('conditions' => array('Winner.id' => $data['user_id']), 'contain' => ''));
								$winner['Winner']['modified'] = date('Y-m-d H:i:s');
								$this->Winner->save($winner, false);
							}
						}

						// Saving auction
						$this->save($auction);

						$message = __('Your bid was placed', true);

						if(!empty($this->appConfigurations['flashMessage'])) {
							App::import('Helper', array('Number'));
							$number = new NumberHelper();

							// New flash message
							if(!empty($data['bid_butler'])){
								if(!empty($data['bid_butler_count'])){
									$flash = sprintf(__('%d Bid Butler + %s + %s seconds', true), $data['bid_butler_count'], $number->currency($data['price_increment'], $this->appConfigurations['currency']), $data['time_increment']);
								}else{
									$flash = sprintf(__('1 Bid Butler + %s + %s seconds', true), $number->currency($data['price_increment'], $this->appConfigurations['currency']), $data['time_increment']);
								}
							}else{
								$flash = sprintf(__('1 Single bid + %s + %s seconds', true), $number->currency($data['price_increment'], $this->appConfigurations['currency']), $data['time_increment']);
							}
						}

						$auction['Auction']['success'] = true;
						$auction['Bid']['description'] = $bid['Bid']['description'];
						$auction['Bid']['user_id'] = $bid['Bid']['user_id'];

						// lets add in the bid information for smartBids - we need this
						$result['Bid'] = $bid['Bid'];
					} else {
						$message = __('You have no more bids in your account', true);
					}
				}

				$result['Auction']['id']      = $auction['Auction']['id'];
				$result['Auction']['message'] = $message;
				$result['Auction']['element'] = 'auction_'.$auction['Auction']['id'];

				if(!empty($this->appConfigurations['flashMessage'])){
					$auction['Auction']['flash']   = $flash;

					$auctionMessage = $this->Message->findByAuctionId($auction['Auction']['id']);
					$auctionMessage['Message']['auction_id'] = $auction['Auction']['id'];
					$auctionMessage['Message']['message']    = $flash;

					if(empty($auctionMessage)){
						$this->Message->create();
					}
					$this->Message->save($auctionMessage);
				}

				// now lets refund any bid credits not used before returning the data IF advanced mode is on
				if($this->appConfigurations['bidButlerType'] == 'advanced') {
		        	$this->Bid->refundBidButlers($auction['Auction']['id'], $auction['Auction']['price']);
		        }

				return $result;
			} else {
				return false;
			}
		}

		/**
		 * Function to check to see we can close the auction
		 *
		 * @param INT $id
		 * @param INT $isPeakNow
		 * @return true if can close, false otherwise
		 */
		function checkCanClose($id, $isPeakNow, $timeCheck = true) {
			// DO NOT CHANGE THIS CODE WITHOUT APPROVAL OF MICHAEL HOUGHTON FIRST

			$auction = $this->find('first', array('conditions' => array('Auction.id' => $id), 'contain' => 'Product'));

			if($timeCheck == true) {
				// lets check to see if the end_max_time is on, and if so we HAVE to close the auction
				if(!empty($auction['Auction']['max_end'])) {
					if(strtotime($auction['Auction']['max_end_time']) < time()) {
						return true;
					}
				}

				// lets check it has actually expired
				if(strtotime($auction['Auction']['end_time']) > time()) {
					return false;
				}
			}

			// lets only closing a peak auction during the peak times
			if($auction['Auction']['peak_only'] == 1 && !$isPeakNow) {
				return false;
			}

			// now lets make sure the minimum price has been meet checking on the autobid limit too\
			/*
			if($auction['Product']['autobid'] == 1 && ($auction['Auction']['price'] < $auction['Auction']['minimum_price'])) {
				if($auction['Product']['autobid_limit'] > 0) {
					if($auction['Auction']['autobids'] <= $auction['Product']['autobid_limit']) {
						return false;
					}
				} else {
					return false;
				}
			}*/

			return true;
		}

		function afterFind($results, $primary = false){
			// Parent method redefined
			$results = parent::afterFind($results, $primary);

			// Getting rate for current currency
			$rate = $this->_getRate();

			if(!empty($results)){
				// This for find('all')
				if(!empty($results[0]['Auction'])){
					// Loop over find result and convert the price with rate
					foreach($results as $key => $result){
						if(!empty($results[$key]['Auction']['price'])){
							$results[$key]['Auction']['price'] = $result['Auction']['price'] * $rate;
						}

						if(!empty($results[$key]['Auction']['minimum_price'])){
							$results[$key]['Auction']['minimum_price'] = $result['Auction']['minimum_price'] * $rate;
						}
					}

				// This for find('first')
				}elseif(!empty($results['Auction'])){
					if(!empty($results['Auction']['price'])){
						$results['Auction']['price'] = $results['Auction']['price'] * $rate;
					}

					if(!empty($results['Auction']['minimum_price'])){
						$results['Auction']['minimum_price'] = $results['Auction']['minimum_price'] * $rate;
					}
				}
			}

			// Return back the results
			return $results;
		}

		function beforeSave(){
			if(!empty($this->data)){
				$path = TMP.'cache'.DS;
				foreach (glob($path."cake_auction_*") as $filename) {
	   			   @unlink($filename);
				}			
			}
			$this->clearCache();

			// Price currency rate revert back to application default (USD)
			// Get the rate
			$rate = 1 / $this->_getRate();

			// Convert it back to USD
			if(!empty($this->data['Auction']['price'])){
				$this->data['Auction']['price'] = $this->data['Auction']['price'] * $rate;
			}

			if(!empty($this->data['Auction']['minimum_price'])){
				$this->data['Auction']['minimum_price'] = $this->data['Auction']['minimum_price'] * $rate;
			}

			// double bid fix - if the variable double_bids_check is passed then it will check for this
			if(!empty($this->data['Auction']['double_bids_check'])) {
				$doubleBid = $this->Bid->doubleBidsCheck($this->data['Auction']['id'], $this->data['Auction']['leader_id']);
				if($doubleBid == false) {
					return false;
				}
			}

			return true;
		}

		function countAll($types = null){
			if(!empty($types)){
				if(is_array($types)){
					$results = array();

					foreach($types as $type){
						$results[$type] = $this->count($type);
					}
				}else{
					$results = $this->count($types);
				}

				return $results;
			}else{
				return false;
			}
		}

		function count($type = null){
			if(!empty($type)){
				switch($type){
					case 'live':
						$count = $this->find('count', array('conditions' => "start_time < '" . date('Y-m-d H:i:s') . "' AND end_time > '" . UsersController::getEndTime() . "'"));
						break;

					case 'comingsoon':
						$count = $this->find('count', array('conditions' => "start_time > '" . date('Y-m-d H:i:s') . "'"));
						break;

					case 'closed':
						$count = $this->find('count', array('conditions' => array('closed' => 1)));
						break;

					case 'free':
						$count = $this->find('count', array('conditions' => "Product.free = 1 AND start_time < '" . date('Y-m-d H:i:s') . "' AND end_time > '" . UsersController::getEndTime() . "'"));
						break;

					default:
						$count = 0;
				}

				return $count;
			}else{
				return false;
			}
		}

		function afterSave($created){
			parent::afterSave($created);
			
			//write to RSS
			//App::import('vendor','rss/rss');
			//include("RSS.php"); 
  			//$rss = new RSS(); 
	  		//$rss->writeFile();
	  		
			$this->clearCache();
			return true;
		}

		function afterDelete(){
			parent::afterDelete();

			$this->clearCache();
			return true;
		}

		function clearCache() {
			if(!empty($this->data['Auction']['id'])) {
				Cache::delete('auction_view_'.$this->data['Auction']['id']);
				Cache::delete('auction_'.$this->data['Auction']['id']);
				Cache::delete('daemons_extend_auctions');
				Cache::delete('last_bid_'.$this->data['Auction']['id']);
			}
		}
		
		function beforeFind($queryData) {
			//exclude deleted auctions from all find() requests
			if (is_array($queryData['conditions'])) {
				$queryData['conditions']['Auction.deleted']=0;
			} else {
				$queryData['conditions'].=' AND Auction.deleted=0';
			}
			return $queryData;
		}
		
		function del($id) {
			//*** set the deleted flag on this product record
			$this->save(array('id'=>$id,'deleted'=>1));
			
			return true;
		}
		
		function binPrice($auction_id, $buy_now_price, $user_id) {
			
			//see if buy it now is disabled for this item
			if ($buy_now_price==0) return 0;
			
			//see how many bids this user placed
			$bid_count=$this->Bid->find('count', array('conditions'=>array('Bid.auction_id'=>$auction_id,'Bid.user_id'=>$user_id)));
			
			if (Configure::read('App.buyNow.bid_discount')===true) {
				
				//see if this user has bought it before, if so no discount will be offered
				$prevBought=$this->find('count', array('conditions'=>array(	'winner_id'=>$user_id,
												'closed_status'=>2,
												'parent_id'=>$auction_id)));
				if ($prevBought>0) {
					$discount=0;
				} else {
					$discount=($bid_count*Configure::read('App.buyNow.bid_price'));
				}
				if ($discount>=$buy_now_price) {
					//don't know why this would ever happen, but good to have a handler for it
					return 0.01;
				} else {
					return  $buy_now_price - $discount;
				}
			} else {
				return $buy_now_price;
			}
		}
		
		function canBuyNow($auction, $user_id) {
			if (($this->appConfigurations['buyNow']!==true 
				or $this->appConfigurations['buyNow']['enabled']!==true)
			 	&& !$auction['Product']['buy_now']) {
				return false;
			}
			if (Configure::read('App.buyNow.before_closed')==false) {
				//if the auction isn't closed, we can't buy
				if (!$auction['Auction']['closed']) return false;
			}
			if (Configure::read('App.buyNow.after_closed')==false) {
				//if the auction is closed, we can't buy
				
				if ($auction['Auction']['closed']) return false;
				
			} elseif ($auction['Auction']['closed']) {
				//we can buy after closed, check if we're within the time range
				
				if (Configure::read('App.buyNow.must_bid_before')==true) {
					//if this user hasn't bid before, they can't buy it
					
					$bids=$this->Bid->find('count', array('conditions'=>array('Bid.user_id'=>$user_id, 'Bid.auction_id'=>$auction['Auction']['id'])));
					if ($bids===0) return false;
				}
				
				$hours_after_closed=doubleval(Configure::read('App.buyNow.hours_after_closed'));
				if ($hours_after_closed<0.25) return false;
				
				//end times are sometimes date_format, sometimes timestamp ?
				if (is_numeric($auction['Auction']['end_time'])) {
					$end_time=$auction['Auction']['end_time'];
				} else {
					$end_time=strtotime($auction['Auction']['end_time']);
				}
				
				if (((time() - $end_time)/60/60) > ($hours_after_closed)) {
					//too much time has elapsed
					return false;
				}
				
			}
			
			
			
			//we must be able to buy then
			return true;
			
		}
		
		function userWonMoney($user_id){
			$result=$this->Product->find('first', array(
				'conditions' => array('Auction.winner_id' => $user_id),
				'fields'	 => array('SUM(Product.rrp)-SUM(Auction.price) as TOTAL'),
			));
			
			return $result[0]['TOTAL'];
		}
		
		function paginate ($conditions, $fields, $order, $limit, $page = 1, $recursive = null, $extra = array()) {
			$args = func_get_args();
			$uniqueCacheId = '';
			foreach ($args as $arg) {
				$uniqueCacheId .= serialize($arg);
			}
			if (!empty($extra['contain'])) {
				$contain = $extra['contain'];
			}
			if(!empty($extra['joins'])){
				$joins = $extra['joins'];
			}
			$uniqueCacheId = md5($uniqueCacheId);
			$pagination = Cache::read('auction_pagination-'.$this->alias.'-'.$uniqueCacheId, 'short');
			if (empty($pagination)) {
				$pagination = $this->find('all', compact('conditions', 'fields', 'order', 'limit', 'page', 'recursive', 'group', 'contain','joins'));
				Cache::write('auction_pagination-'.$this->alias.'-'.$uniqueCacheId, $pagination, 'short');
			}
			return $pagination;
		}
	
		function paginateCount ($conditions = null, $recursive = 0, $extra = array()) {
			$args = func_get_args();
			$uniqueCacheId = '';
			foreach ($args as $arg) {
				$uniqueCacheId .= serialize($arg);
			}
			$uniqueCacheId = md5($uniqueCacheId);
			if (!empty($extra['contain'])) {
				$contain = $extra['contain'];
			}
			if(!empty($extra['joins'])){
				$joins = $extra['joins'];
			}
			$paginationcount = Cache::read('auction_paginationcount-'.$this->alias.'-'.$uniqueCacheId, 'short');
			if (empty($paginationcount)) {
				$paginationcount = $this->find('count', compact('conditions', 'contain', 'recursive','joins'));
				Cache::write('auction_paginationcount-'.$this->alias.'-'.$uniqueCacheId, $paginationcount, 'short');
			}
			return $paginationcount;
		}
		
		
	}
?>
