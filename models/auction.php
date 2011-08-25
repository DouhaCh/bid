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
			'Leader' => array(
				'className'  => 'User',
				'foreignKey' => 'leader_id'
			)
		);

		var $hasOne = array('Testimonial');

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

			$this->contain(array('Product' => array('Image' => 'ImageDefault')));
			$auctions = $this->find('all', array('conditions' => $conditions, 'order' => $order, 'limit' => $limit));
			
			foreach($auctions as $key => $auction) {
				// Put it back into the array
				$auctions[$key]['Auction']['end_time'] = strtotime($auction['Auction']['end_time']);

				if(!empty($auction['Product']['Image'])) {
					if(!empty($auction['Product']['Image'][0]['ImageDefault'])) {
						$auctions[$key]['Auction']['image'] = 'default_images/'.Configure::read('App.serverName').'/'.$folder.'/'.$auction['Product']['Image'][0]['ImageDefault']['image'];
					} else {
						$auctions[$key]['Auction']['image'] = 'product_images/'.$folder.'/'.$auction['Product']['Image'][0]['image'];
					}
				}
			}

			if($limit == 1){
				if(!empty($auctions[0])){
					return $auctions[0];
				}
			}

			return $auctions;
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
		
		/*function paginate ($conditions, $fields, $order, $limit, $page = 1, $recursive = null, $extra = array()) {
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
		}*/
		
		
	}
?>
