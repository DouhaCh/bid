<?php
	class Bid extends AppModel {

		var $name = 'Bid';

		var $actsAs = array('Containable');

		var $belongsTo = array('Auction', 'User');

		function __construct($id = false, $table = null, $ds = null){
			parent::__construct($id, $table, $ds);

			$this->validate = array(
				'description' => array(
					'rule' => array('minLength', 1),
					'message' => __('Description is a required field.', true)
				)
			);
		}

		function beforeSave(){
			$this->_clearCache();
			
			// double bid fix - if the variable double_bids_check is passed then it will check for this
			if(!empty($this->data['Bid']['double_bids_check'])) {
				$doubleBid = $this->doubleBidsCheck($this->data['Bid']['auction_id'], $this->data['Bid']['user_id']);
				if($doubleBid == false) {
					return false;
				}
			}
			
			return true;
		}

		function beforeDelete(){
			$this->_clearCache();
			return true;
		}

		function _clearCache(){
			if(!empty($this->data['Bid']['user_id'])){
				Cache::delete('bids_balance_'.$this->data['Bid']['user_id']);
			}
		}

		/**
		 * Function to get bid balance
		 *
		 * @param int $user_id User id
		 * @return int Balance of user's bid
		 */
		function balance($user_id) {
			if(!empty($this->appConfigurations['simpleBids']) && $this->appConfigurations['simpleBids'] == true) {
				$user = $this->User->find('first', array('conditions' => array('User.id' => $user_id), 'contain' => ''));
				return $user['User']['bid_balance'];
			} else {
				$credit = $this->find('all', array('conditions' => array('Bid.user_id' => $user_id), 'fields' => "SUM(Bid.credit) as 'credit'"));
				if(empty($credit[0][0]['credit'])) {
					$credit[0][0]['credit'] = 0;
				}

				$debit  = $this->find('all', array('conditions' => array('Bid.user_id' => $user_id), 'fields' => "SUM(Bid.debit) as 'debit'"));
				if(empty($debit[0][0]['debit'])) {
					$debit[0][0]['debit'] = 0;
				}

				return $credit[0][0]['credit'] - $debit[0][0]['debit'];
			}
		}

        /**
         * Function to get bid history for an auction
         *
         * @param int $auction_id Id of an auction
         * @param int $limit Number of history which will be retrieved
         * @return array Array of bid histories
         */
         function histories($auction_id = null, $limit = 10, $options = array()) {
            $this->Auction->contain();
            $auction = $this->Auction->findById($auction_id);

            $lastPrice = $auction['Auction']['price'];
            $histories = $this->find('all', array('conditions' => array('Auction.id' => $auction_id), 'fields' => array('Bid.id', 'Bid.debit', 'Bid.created', 'Bid.description', 'User.username'), 'limit' => $limit, 'order' => 'Bid.id DESC'));
			
            if(!empty($options['include_amount']['price_increment'])){
                foreach($histories as $key => $history){
                    $histories[$key]['Bid']['amount'] = $lastPrice;
                    $lastPrice -= $options['include_amount']['price_increment'];
                    $temp = explode("@", $histories[$key]['User']['username']);
					$histories[$key]['User']['username'] = $temp[0];
					
               		if(strlen($histories[$key]['User']['username'])>12){
						$histories[$key]['User']['username'] = substr($histories[$key]['User']['username'], 0, 12)."...";
					}

                }
            }
            return $histories;
        }

		/**
         * Function to get the last bidder information
         *
         * @param int $auction_id Id of an auction
         * @return array the lastest bidder
         */
        function lastBid($auction_id = null) {
			// Use contain user only and get bid.auction_id instead of auction.id
			// cause it needs the auction included in result array
			$lastBid = $this->find('first', array('conditions' => array('Bid.auction_id' => $auction_id, 'Bid.type' => 'Bid'), 'order' => 'Bid.id DESC', 'contain' => array('User')));
			$bid = array();
			
			//var_dump($lastBid);
			if(!empty($lastBid)) {
				$bid = array(
					'debit'       => $lastBid['Bid']['debit'],
					'created'     => $lastBid['Bid']['created'],
					'username'    => $lastBid['User']['username'],
					'description' => $lastBid['Bid']['description'],
					'user_id'     => $lastBid['User']['id'],
					'autobidder'  => $lastBid['User']['autobidder']
				);
			}
			return $bid;

        }
        
	function addBid($user_id = null, $description = null, $credit = 0, $debit = 0){
		if(!empty($user_id) && !empty($description)){
			if($this->appConfigurations['simpleBids'] == true) {
				$user = $this->User->find('first', array('conditions' => array('User.id' => $user_id), 'contain' => ''));
				if($credit > 0) {
					$user['User']['bid_balance'] += $credit;
				} else {
					$user['User']['bid_balance'] -= $debit;
				}
				
				$this->User->save($user, false);
				
				$bid['Bid']['user_id']     = $user_id;
				$bid['Bid']['description'] = $description;
				$bid['Bid']['credit']      = $credit;
				$bid['Bid']['debit']       = $debit;
				
				return $bid;
			} else {
				$bid['Bid']['user_id']     = $user_id;
				$bid['Bid']['description'] = $description;
				$bid['Bid']['credit']      = $credit;
				$bid['Bid']['debit']       = $debit;
				
				$this->create();
				
				return $this->save($bid);
			}
		}else{
			return false;
		}
	}
	
        function doubleBidsCheck($auction_id, $user_id) {
        	$lastBid = $this->lastBid($auction_id);

        	if(!empty($lastBid)) {
        		if($lastBid['user_id'] == $user_id) {
        			return false;
        		} else {
        			return true;
        		}
        	} else {
        		return true;
        	}
        }

		function unique($auction_id = null){
			$bids = $this->find('all', array('conditions' => array('Bid.auction_id' => $auction_id), 'fields' => "COUNT(DISTINCT Bid.user_id) AS 'count'"));

			if(!empty($bids[0][0]['count'])){
				return $bids[0][0]['count'];
			}else{
				return 0;
			}
		}

		function refundBidButlers($auction_id, $price = null) {
			if(!empty($price)) {
				$conditions = array('Bidbutler.auction_id' => $auction_id, 'Bidbutler.bids >' => 0, 'Bidbutler.maximum_price <' => $price);
			} else {
				$conditions = array('Bidbutler.auction_id' => $auction_id, 'Bidbutler.bids >' => 0);
			}

			$bidbutlers = $this->Auction->Bidbutler->find('all', array('conditions' => $conditions, 'contain' => ''));
			if(!empty($bidbutlers)) {
				foreach($bidbutlers as $bidbutler) {
					$data['Bid']['user_id'] 	= $bidbutler['Bidbutler']['user_id'];
					$data['Bid']['description'] = __('Bid Butler Refunded Bids', true);
					$data['Bid']['credit']      = $bidbutler['Bidbutler']['bids'];

					$this->create();
					$this->save($data);

					$bidbutler['Bidbutler']['bids'] = 0;
					$this->Auction->Bidbutler->save($bidbutler);
				}
			}
		}
	}
?>
