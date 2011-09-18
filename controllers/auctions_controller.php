<?php

class AuctionsController extends AppController {

	var $name = 'Auctions';
	var $uses = array('Auction', 'Setting', 'Country', 'Bid', 'Users');
	var $components = array('PaypalProUk', 'Epayment', 'Twitter','Cookie');
	var $helpers = array('Epayment');

	function beforeFilter(){
		parent::beforeFilter();

		if(!empty($this->Auth)) {
			$this->Auth->allow('test', 'popup', 'index', 'view', 'live', 'home', 'future', 'closed', 'featured', 'winners', 'getcount', 'getstatus', 'ipn', 'latestwinner', 'gettickerauctions','getLatestSold', 'endingsoon', 'getfutureauctions', 'getfeatured', 'getauctions', 'getwinners', 'creditcard', 'credits', 'getendedlist', 'free', 'search', 'timeout', 'tag');
		}
	}
	
	function beforeRender(){
		parent::beforeRender();
		$this->Cookie->write('last_visit',Router::url($this->here, true));
	}

	function _getStringTime($timestamp){
		$diff 	= $timestamp - time();
		if($diff < 0) $diff = 0;

		$day    = floor($diff / 86400);
		if($day < 1){
			$day = '';
		}else{
			$day = $day.'d';
		}

		$diff   -= $day * 86400;

		$hour   = floor($diff / 3600);
		if($hour < 10) $hour = '0'.$hour;
		$diff   -= $hour * 3600;

		$minute = floor($diff / 60);
		if($minute < 10) $minute = '0'.$minute;
		$diff   -= $minute * 60;

		$second = $diff;
		if($second < 10) $second = '0'.$second;

		return trim($day.' '.$hour.':'.$minute.':'.$second);
	}

	function getcount($type = null)	{
		if($type == 'live') {
			$count = $this->Auction->find('count', array('conditions' => "start_time < '" . date('Y-m-d H:i:s') . "' AND end_time > '" . UsersController::getEndTime() . "'"));
		} elseif($type == 'comingsoon') {
			$count = $this->Auction->find('count', array('conditions' => "start_time > '" . date('Y-m-d H:i:s') . "'"));
		} elseif($type == 'closed') {
			$count = $this->Auction->find('count', array('conditions' => array('closed' => 1)));
		}

		return $count;
	}

	function home() {
		//CHeck if it was a visitor
		
		if (!$this->Auth->user('id')){
			if (!empty($_GET['khuyenmai'])&&!$this->Cookie->read('referral')){
				$this->Cookie->write('referral',$_GET['khuyenmai'],false,36000);
				$this->Cookie->write('registered','0',false,36000);
				/*App::import('model','Referral');
				
				$refer = new Referral();
				
				$refdata = array('Referral'=>array (	
													'user_id'=>'0',
												  	'referrer_id'=>$_GET['khuyenmai'],
													'ip'=>$_SERVER['REMOTE_ADDR'],
													'confirmed'=>0
												)
								 );
				
				$refer->add($refdata);*/
			}
		}
		if(!empty($this->appConfigurations['homeFeaturedAuction'])) {
			
			// Get the featured auctions
			// *** Upcoming auctions
			$upcoming = $this->Auction->getAuctions(array(	'Auction.start_time > '=>date('Y-m-d H:i:s'),
						'Auction.active' => 1, 
						'Auction.featured' => 1), 
						5, 
						'Auction.end_time ASC');

			$this->set('upcoming', $upcoming);
			
			
			// *** Featured auction
			/*
			$featured  = $this->Auction->getAuctions(array(		'Auction.end_time > '=>UsersController::getEndTime(),
										'Auction.active' => 1, 
										'Auction.featured' => 1), 
									1, 'Auction.end_time ASC');

			// if there are no featured auctions, lets just get a closing soon auction
			if(empty($featured)) {
				$featured  = $this->Auction->getAuctions(array(	'Auction.end_time > '=>UsersController::getEndTime(),
										'Auction.active' => 1),
										1, 'Auction.end_time ASC');
			}

			if(!empty($featured['Auction']['image'])){
				if(!empty($featured['Product']['Image'][0]['ImageDefault'])) {
					$featured['Auction']['image'] = 'default_images/'.Configure::read('App.serverName').'/max/'.$featured['Product']['Image'][0]['ImageDefault']['image'];
					$featured['Auction']['thumb'] = 'default_images/'.Configure::read('App.serverName').'/thumbs/'.$featured['Product']['Image'][0]['ImageDefault']['image'];
				} else {
					$featured['Auction']['image'] = 'product_images/max/'.$featured['Product']['Image'][0]['image'];
					$featured['Auction']['thumb'] = 'product_images/thumbs/'.$featured['Product']['Image'][0]['image'];
				}
			}

			if(!empty($featured)) {
				$this->set('featured', $featured);
				$excludeFeatured[]['Auction']['id'] = $featured['Auction']['id'];
			} else {
				$excludeFeatured = array();
			}
			*/
			//*** Auctions ending soon
			$endSoon  = $this->Auction->getAuctions(array(	'Auction.end_time > '=>UsersController::getEndTime(), 
									'Auction.active' => 1), 
								$this->appConfigurations['homeEndingLimit'], 
								'Auction.end_time ASC', 
								$excludeFeatured);
			if(!empty($featured)) {
				$combine_featured[] = $featured;
				$exclude = array_merge($endSoon, $combine_featured);
			} else {
				$exclude = $endSoon;
			}
		} else {
			$endSoon  = $this->Auction->getAuctions(array(	'Auction.end_time > '=>UsersController::getEndTime(), 
									'Auction.active' => 1), 
								$this->appConfigurations['homeEndingLimit'], 
								'Auction.end_time ASC');
			$exclude = $endSoon;
		}
		$this->set('auctions_end_soon', $endSoon);

		//*** Live auctions
		$live = $this->Auction->getAuctions(array(	'Auction.start_time'=>date('Y-m-d H:i:s'),
								'Auction.end_time >'=>UsersController::getEndTime(), 
								'Auction.active' => 1, 
								'Auction.featured' => 1), 
							$this->appConfigurations['homeFeaturedLimit'], 
							'Auction.end_time ASC', 
							$exclude);
		if(empty($live)) {
			$live = $this->Auction->getAuctions(array(	'Auction.start_time < '=>date('Y-m-d H:i:s'),
									'Auction.end_time >'=>UsersController::getEndTime(), 
									'Auction.active' => 1), 
								$this->appConfigurations['homeFeaturedLimit'], 
								'Auction.end_time ASC', 
								$exclude);
		}

		$this->set('auctions_live', $live);
	}

	function index() {
		$auctions = $this->Auction->getAuctions(
			array(
				'end_time > '=> date("Y/m/d H:i:s"), 
				'Auction.active' => 1
			),
			11
		);

		$this->set('auctions_ending', $auctions);
	}

	function future() {
		$this->paginate = array('contain' => '', 'conditions' => array("start_time > '" . date('Y-m-d H:i:s') . "'", 'Auction.active' => 1), 'limit' => $this->appConfigurations['pageLimit'], 'order' => array('Auction.end_time' => 'asc'));
		$auctions = $this->paginate();

		if(!empty($auctions)) {
			foreach($auctions as $key => $auction) {
				$auction = $this->Auction->getAuctions(array('Auction.id' => $auction['Auction']['id']), 1);
				$auctions[$key] = $auction;
			}
		}

		$this->set('auctions', $auctions);
		$this->pageTitle = __('Coming Soon Auctions', true);
		$this->set('future', 1);
	}

	function closed() {
		if(!empty($this->appConfigurations['endedLimit'])) {
			$this->paginate = array('contain' => '', 'conditions' => array('closed' => 1, 'closed_status <>'=>2, 'Auction.active' => 1), 'limit' => $this->appConfigurations['endedLimit'], 'order' => array('Auction.end_time' => 'desc'));
			$auctions = $this->paginate();
		} else {
			$this->paginate = array('contain' => '', 'conditions' => array('closed' => 1, 'closed_status <>'=>2, 'Auction.active' => 1), 'limit' => $this->appConfigurations['pageLimit'], 'order' => array('Auction.end_time' => 'desc'));
			$auctions = $this->paginate();
		}

		if(!empty($auctions)) {
			$auctioncache = Cache::read('auction_closed', 'short');
			if (empty($auctioncache)) {
				foreach($auctions as $key => $auction) {
					$auction = $this->Auction->getAuctions(array('Auction.id' => $auction['Auction']['id']), 1);
					$auctions[$key] = $auction;
				}	
				Cache::write('auction_closed', $auctions, 'short');
				$auctioncache = $auctions;
				
			}
			
		}

		$this->set('auctions', $auctioncache);
		$this->pageTitle = __('Closed Auctions', true);
	}
	
	function featured() {
	// *** Featured auction
		if(!empty($this->appConfigurations['endedLimit'])) {
			$this->paginate = array(
				'contain' => '',
				'conditions' => array(
					'Auction.end_time > '=>UsersController::getEndTime(),
					'Auction.active' => 1, 
					'Auction.featured' => 1,
					'Auction.active' => 1
				),
				'limit' => $this->appConfigurations['endedLimit'],
				'order' => array('Auction.end_time' => 'desc')
			);
			$auctions = $this->paginate();
		} else {
			$this->paginate = array('contain' => '', 'conditions' => array(
						'Auction.end_time > '=>UsersController::getEndTime(),
						'Auction.active' => 1, 
						'Auction.featured' => 1,
						'Auction.active' => 1), 'limit' => $this->appConfigurations['pageLimit'], 'order' => array('Auction.end_time' => 'desc'));
			$auctions = $this->paginate();
		}

		if(!empty($auctions)) {
			foreach($auctions as $key => $auction) {
				$auction = $this->Auction->getAuctions(array('Auction.id' => $auction['Auction']['id']), 1);
				$auctions[$key] = $auction;
			}
		}

		$this->set('auctions', $auctions);
		$this->pageTitle = __('Featured Auctions', true);
	}

	function free() {
		$this->paginate = array('contain' => 'Product', 'conditions' => array("start_time < '" . date('Y-m-d H:i:s') . "' AND end_time > '" . UsersController::getEndTime() . "'", 'Auction.active' => 1, 'Product.free' => 1), 'limit' => $this->appConfigurations['pageLimit'], 'order' => array('Auction.end_time' => 'asc'));
		$auctions = $this->paginate();

		if(!empty($auctions)) {
			foreach($auctions as $key => $auction) {
				$auction = $this->Auction->getAuctions(array('Auction.id' => $auction['Auction']['id']), 1);
				$auctions[$key] = $auction;
			}
		}

		$this->set('auctions', $auctions);
		$this->pageTitle = __('Free Auctions', true);
	}

	function search($search = null) {
		if(!empty($this->data['Auction']['search'])) {
			$this->redirect('/auctions/search/'.$this->data['Auction']['search']);
		}

		if(!empty($search)) {
			$this->paginate = array('contain' => 'Product', 'conditions' => array("(Product.title LIKE '%$search%' OR Product.description LIKE '%$search%') AND start_time < '" . date('Y-m-d H:i:s') . "' AND end_time > '" . UsersController::getEndTime() . "'", 'Auction.active' => 1), 'limit' => $this->appConfigurations['pageLimit'], 'order' => array('Auction.end_time' => 'asc'));
			$auctions = $this->paginate();

			if(!empty($auctions)) {
				foreach($auctions as $key => $auction) {
					$auction = $this->Auction->getAuctions(array('Auction.id' => $auction['Auction']['id']), 1);
					$auctions[$key] = $auction;
				}
			}

			$this->set('auctions', $auctions);
			$this->pageTitle = __('Free Auctions', true);

			$this->set('search', $search);
		} else {
			$this->Session->setFlash(__('You did not enter anything to search for.', true));
			$this->redirect('/');
		}
	}

	function view($id = null) {
		
		App::import('vendor', 'vietdecode/vietdecode');
		
		if (is_numeric($id)) {
			$auction=$this->Auction->findById($id);
			$this->redirect($this->AuctionLinkFlat($id, vietdecode($auction['Product']['title'])));
		} else {
			preg_match('/([0-9]+)$/U', $id, $matches);
			$id=$matches[0];
		}

		if(empty($id)) {
			$this->Session->setFlash(__('Invalid Auction.', true));
			$this->redirect(array('action' => 'index'));
		}

		$auction = $this->Auction->getAuctions(array('Auction.id' => $id), 1, null, null, 'max');
		//print_r($auction)
		if (empty($auction)) {
			$this->Session->setFlash(__('Invalid Auction.', true));
			$this->redirect(array('action' => 'index'));
		}

		//adjust the buy-it-now price depending on configuration
		$auction['Product']['buy_now']=$this->Auction->binPrice($auction['Auction']['id'], $auction['Product']['buy_now'], $this->Auth->user('id'));
		
		if ($this->Auction->canBuyNow($auction, $this->Auth->user('id'))) {
			$this->set('buy_it_now', true);
		} else {
			$this->set('buy_it_now', false);
		}
		
		$this->set('auction', $auction);
		$this->set('watchlist', $this->Auction->Watchlist->find('first', array('conditions' => array('Auction.id' => $id, 'User.id' => $this->Auth->user('id')))));

		$options['include_amount']['price_increment'] = $auction['Auction']['price_step'];
		
		$this->set('bidHistories', $this->Auction->Bid->histories($auction['Auction']['id'], $this->appConfigurations['bidHistoryLimit'], $options));
		
		$this->set('parents', $this->Auction->Product->Category->getpath($auction['Product']['category_id']));
		$this->set('bidIncrease', $this->requestAction('/settings/get/price_increment/'.$id));
		$this->set('timeIncrease', $this->requestAction('/settings/get/time_increment/'.$id));

		$this->pageTitle = $auction['Product']['title'];
		if(!empty($auction['Product']['meta_description'])) {
			$this->set('meta_description', $auction['Product']['meta_description']);
		}
		if(!empty($auction['Product']['meta_keywords'])) {
			$this->set('meta_keywords', $auction['Product']['meta_keywords']);
		}

		$hits['Auction']['id'] = $auction['Auction']['id'];
		$hits['Auction']['hits'] = $auction['Auction']['hits'] + 1;
		$this->Auction->save($hits);
	
		//get bidbutler
		$bidbutler=$this->Auction->Bidbutler->find('first',
			array(
				'conditions' => array('auction_id'=>$id, 'user_id'=>$this->Auth->user('id'), 'Bidbutler.active'=>'1')
			)
		);
		$this->set('bidbutler',$bidbutler);
	}
	
	function tag($id){
		
		$pt=$this->Auction->Product->ProductTag->find('all',array(
			'conditions' => array('tag_id' => $id),
			'fields' => array('ProductTag.product_id')
		));
		
		$auct_arr= array();
		
		// *** Tagged Autions
		$subQuery="(select product_id from product_tags where tag_id=".$id.")";
		$tag = $this->Auction->getAuctions(
			array('product_id in '.$subQuery), 
				  $this->appConfigurations['homeEndingLimit'], 
				  'Auction.end_time ASC');

		$this->set('auctions_tag', $tag);
		
	}

	function won() {
		$this->paginate = array('conditions' => array('Auction.winner_id' => $this->Auth->user('id')), 'limit' => 50, 'order' => array('Auction.end_time' => 'desc'), 'contain' => array('Product' => 'Image', 'Status'));
		$auctions = $this->Auction->Product->Translation->translate($this->paginate());
		$this->set('auctions', $auctions);

		$this->pageTitle = __('Won Auctions', true);
	}
	
	function admin_index() {
		$this->paginate = array('limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('Auction.end_time DESC', 'Auction.closed ASC'), 'contain' => array('Product' => array('Category'), 'Status', 'Winner', 'Bid'));
		$this->set('auctions', $this->paginate('Auction'));

		$this->Session->write('auctionsPage', $this->params['url']['url']);
	}

	function admin_live() {
		$this->paginate = array('conditions' => "start_time < '" . date('Y-m-d H:i:s') . "' AND end_time > '" . UsersController::getEndTime() . "'", 'limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('closed' => 'asc', 'end_time' => 'asc'), 'contain' => array('Product' => array('Category'), 'Status', 'Winner', 'Bid'));
		$this->set('auctions', $this->paginate('Auction'));

		$this->set('extraCrumb', array('title' => __('Live Auctions',true), 'url' => 'live'));

		$this->render('admin_index');
		$this->Session->write('auctionsPage', $this->params['url']['url']);
	}

	function admin_comingsoon(){
		$this->paginate = array('conditions' => "start_time > '" . date('Y-m-d H:i:s') . "'", 'limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('end_time' => 'asc'), 'contain' => array('Product' => array('Category'), 'Status', 'Winner', 'Bid'));
		$this->set('auctions', $this->paginate('Auction'));

		$this->set('extraCrumb', array('title' => 'Coming Soon', 'url' => 'comingsoon'));

		$this->render('admin_index');
		$this->Session->write('auctionsPage', $this->params['url']['url']);
	}

	function admin_closed(){
		$this->paginate = array('conditions' => array('closed' => 1), 'limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('end_time' => 'desc'), 'contain' => array('Product' => array('Category'), 'Status', 'Winner', 'Bid'));
		$this->set('auctions', $this->paginate('Auction'));

		$this->set('extraCrumb', array('title' => __('Closed Auctions',true), 'url' => 'closed'));

		$this->render('admin_index');
		$this->Session->write('auctionsPage', $this->params['url']['url']);
	}

	function admin_won($status_id = null) {
		if(!empty($status_id)){
			$conditions = array('winner_id >' => 0, 'Winner.autobidder' => 0, 'Auction.status_id' => $status_id);
		}else{
			$conditions = array('winner_id >' => 0, 'Winner.autobidder' => 0);
		}
		$this->paginate = array('conditions' => $conditions, 'limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('end_time' => 'asc'), 'contain' => array('Product' => array('Category'), 'Status', 'Winner', 'Bid'));

		$this->set('auctions', $this->paginate('Auction'));
		$this->set('statuses', $this->Auction->Status->find('list'));
		$this->set('selected', $status_id);
		$this->set('extraCrumb', array('title' => __('Won Auctions', true), 'url' => 'won'));

		//$this->render('admin_index');
	}

	function admin_autobidders() {
		$conditions = array('winner_id >' => 0, 'Winner.autobidder' => 1);

		$this->paginate = array('conditions' => $conditions, 'limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('end_time' => 'asc'), 'contain' => array('Product' => array('Category'), 'Status', 'Winner', 'Bid'));

		$this->set('auctions', $this->paginate('Auction'));
	}

	function admin_adminusers() {
		$conditions = array('winner_id >' => 0, 'Winner.admin' => 1);

		$this->paginate = array('conditions' => $conditions, 'limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('end_time' => 'asc'), 'contain' => array('Product' => array('Category'), 'Status', 'Winner', 'Bid'));

		$this->set('auctions', $this->paginate('Auction'));
	}

	function admin_user($user_id = null) {
		if(empty($user_id)) {
			$this->Session->setFlash(__('Invalid User.', true));
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}
		$user = $this->Auction->Winner->read(null, $user_id);
		if(empty($user)) {
			$this->Session->setFlash(__('Invalid User.', true));
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}
		$this->set('user', $user);

		$this->paginate = array('conditions' => array('Auction.winner_id' => $user_id, 'Auction.deleted' => '0'), 'limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('Auction.end_time' => 'asc'), 'contain' => array('Winner', 'Product' => 'Category'));
		$this->set('auctions', $this->paginate());
	}

	function admin_winner($id = null) {
		if (empty($id)) {
			$this->Session->setFlash(__('Invalid Auction', true));
			$this->redirect(array('action'=>'index'));
		}
		$auction = $this->Auction->read(null, $id);
		if(empty($auction)) {
			$this->Session->setFlash(__('Invalid Auction', true));
			$this->redirect(array('action'=>'index'));
		}

		if(!empty($this->data)) {
			$this->Auction->save($this->data);
	
			if($this->data['Auction']['inform'] == 1) {
				$data						   = $this->Auction->read(null, $id);
				$data['Status']['comment'] 	   = $this->data['Auction']['comment'];
				$data['to'] 				   = $auction['Winner']['email'];
					$data['subject'] 			   = sprintf(__('%s - Auction Status Updated', true), $this->appConfigurations['name']);
					$data['template'] 			   = 'auctions/status';
					$this->_sendEmail($data);
			}
	
			$this->Session->setFlash(__('The auction status was successfully updated.', true));
			$this->redirect(array('action' => 'winner', $auction['Auction']['id']));
		}

		$this->set('auction', $auction);

		$user = $this->Auction->Winner->read(null, $auction['Auction']['winner_id']);
		$this->set('user', $user);

		$this->Auction->Winner->Address->UserAddressType->recursive = -1;
		$addresses = $this->Auction->Winner->Address->UserAddressType->find('all');
		$userAddress = array();
		$addressRequired = 0;
		if(!empty($addresses)) {
			foreach($addresses as $address) {
				$userAddress[$address['UserAddressType']['name']] = $this->Auction->Winner->Address->find('first', array('conditions' => array('Address.user_id' => $auction['Auction']['winner_id'], 'Address.user_address_type_id' => $address['UserAddressType']['id'])));
			}
		}
		$this->set('address', $userAddress);

		$this->set('selectedStatus', $auction['Auction']['status_id']);
		$this->set('statuses', $this->Auction->Status->find('list'));
	}

	function admin_add($product_id = null) {
		if(empty($product_id)) {
			$this->Session->setFlash(__('The product ID was invalid.', true));
			$this->redirect(array('controller' => 'products', 'action'=>'index'));
		}
		$product = $this->Auction->Product->read(null, $product_id);
		if(empty($product)) {
			$this->Session->setFlash(__('The product ID was invalid.', true));
			$this->redirect(array('controller' => 'products', 'action'=>'index'));
		}
		$this->set('product', $product);

		if (!empty($this->data)) {
			if($product_id == 1){
				$this->data['Auction']['xu'] = 1;
			}else{
				$this->data['Auction']['xu'] = 0;
			}
			
			$this->data['Auction']['product_id'] = $product_id;
			if ($this->data['Auction']['reverse']) {
				$this->data['Auction']['price'] = $product['Product']['rrp'];
			} else {
				$this->data['Auction']['price'] = $product['Product']['start_price'];
			}
			$this->data['Auction']['minimum_price'] = $product['Product']['minimum_price'];
			if(!empty($product['SettingIncrement'])) {
				$this->data['Auction']['bid_debit'] = $product['SettingIncrement'][0]['bid_debit'];
			}
			if(empty($this->data['Auction']['hidden_reserve'])) {
				$this->data['Auction']['hidden_reserve'] = 0;
			}
			
			//product_id of XU packages
			if(in_array($product_id, array('177', '91', '96', '93', '100'))){
				$this->data['Auction']['xu'] = 1;
			}else{
				$this->data['Auction']['xu'] = 0;
			}
			
			$this->Auction->create();
			if ($this->Auction->save($this->data)) {

				$auction_id=$this->Auction->getLastInsertId();
				
				//*** See if we need to Tweet
				$config=Configure::read('Twitter');
		
				if (isset($config['enabled'])===true) {
					$this->Twitter->tweet($product, $auction_id, $config);
				}
				
				$this->Session->setFlash(__('The auction has been added successfully.', true));
				if($this->Session->check('auctionsPage')) {
					$this->redirect('/'.$this->Session->read('auctionsPage'));
				} else {
					$this->redirect(array('controller' => 'products', 'action'=>'auctions', $product_id));
				}
			} else {
				$this->Session->setFlash(__('There was a problem adding the auction please review the errors below and try again.', true));
			}
		} else {
			$this->data['Auction']['active'] = 1;
		}
		$this->set('categories', $this->Auction->Product->Category->generatetreelist(null, null, null, '-'));
		
		//write RSS
		/*include("RSS.php"); 
  		$rss = new RSS(); 
	  	$rss->writeFile();*/
	}

	function admin_edit($id = null) {
		if (empty($id)) {
			$this->Session->setFlash(__('Invalid Auction', true));
			$this->redirect(array('action'=>'index'));
		}
		$auction = $this->Auction->read(null, $id);
		if(empty($auction)) {
			$this->Session->setFlash(__('The auction ID was invalid.', true));
			$this->redirect(array('action'=>'index'));
		}
		$product = $this->Auction->Product->read(null, $auction['Product']['id']);
		if(empty($product)) {
			$this->Session->setFlash(__('The product ID was invalid.', true));
			$this->redirect(array('controller' => 'products', 'action'=>'index'));
		}
		$this->set('product', $product);

		if (!empty($this->data)) {
			if(empty($this->data['Auction']['hidden_reserve'])) {
				$this->data['Auction']['hidden_reserve'] = 0;
			}
			if ($this->Auction->save($this->data)) {
				//update user bid_balance on ape-server
				$cmd = '_updateAuction';
				$data = array(
					'auction_id' => $id,
				);
				$this->apePush($cmd, $data);
				
				$this->Session->setFlash(__('The auction has been updated successfully.', true));
				if($this->Session->check('auctionsPage')) {
					$this->redirect('/'.$this->Session->read('auctionsPage'));
				} else {
					$this->redirect(array('controller' => 'products', 'action'=>'auctions', $auction['Product']['id']));
				}
			} else {
				$this->Session->setFlash(__('There was a problem updating the auction please review the errors below and try again.', true));
			}
		} else {
			$this->data = $auction;
		}

		$this->set('categories', $this->Auction->Product->Category->generatetreelist(null, null, null, '-'));
	}

	function admin_refund($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Auction.', true));
			$this->redirect(array('action' => 'index'));
		}

		$auction = $this->Auction->read(null, $id);
		if(empty($auction)) {
			$this->Session->setFlash(__('The auction ID was invalid.', true));
			$this->redirect(array('action'=>'index'));
		}

		$auction['Auction']['leader_id'] = 0;
		$auction['Auction']['status_id'] = 0;
		$auction['Auction']['winner_id'] = 0;
		$this->Auction->save($auction);

		$this->Auction->Bid->deleteAll(array('Bid.auction_id' => $id));
		$this->Session->setFlash(__('The bids were successfully refunded.', true));
		$this->redirect(array('action' => 'index'));
	}
	
	function admin_refundwinner($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Auction.', true));
			$this->redirect(array('action' => 'index'));
		}

		$auction = $this->Auction->read(null, $id);
		if(empty($auction)) {
			$this->Session->setFlash(__('The auction ID was invalid.', true));
			$this->redirect(array('action'=>'index'));
		}
		
		$bid = $this->Auction->Bid->find('first', array(
			'conditions' => array(
				'user_id' => $auction['Auction']['winner_id'],
				'Bid.type' => 'Bid',
				'Bid.auction_id' => $id
			),
			'fields' => array('SUM(Bid.debit) AS debit')
		));
		
		$this->data['Bid'] = array(
			'user_id' => $auction['Auction']['winner_id'],
			'auction_id' => '0',
			'description' => 'Bid refund',
			'type' => 'Bid reward',
			'credit' => $bid[0]['debit'],
			'debit' => '0',
			'created' => date('Y-m-d H:i:s', time()),
			'modified' => date('Y-m-d H:i:s', time())
		);
		
		$this->Auction->Bid->create();
		$this->Auction->Bid->save($this->data);

		$this->Session->setFlash(__('The bids were successfully refunded.', true));
		$this->redirect(array('action' => 'index'));
	}

	function admin_stats($auction_id = null) {
		if(empty($auction_id)) {
			$this->Session->setFlash(__('Invalid Auction.', true));
			$this->redirect(array('action' => 'index'));
		}
		$auction = $this->Auction->find('first', array('conditions' => array('Auction.id' => $auction_id), 'contain' => 'Product'));
		
		if(empty($auction)) {
			$this->Session->setFlash(__('Invalid Auction.', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('auction', $auction);

		if(!empty($realBidsOnly)) {
			$conditions = array('Bid.auction_id' => $auction_id, 'Bid.debit >' => 0, 'Bid.credit' => 0, 'User.autobidder' => 0);
			$this->set('realBidsOnly', $realBidsOnly);
		} else {
			$conditions = array('Bid.auction_id' => $auction_id, 'Bid.debit >' => 0, 'Bid.credit' => 0);
		}
		
		$this->set('chatlog',$this->Auction->Comment->find('all',array('conditions'=>array('auction_id'=>$auction_id))));
		$this->set('topbidders',$this->Auction->Bid->find('all',array('fields'=>'User.username, SUM(debit) as used','conditions'=>array('auction_id'=>$auction_id),'group'=>array('user_id'),'limit'=>10,'order'=>'used DESC')));
		
		$this->set('participated',$this->Auction->Bid->find('count', array('fields'=>'COUNT(DISTINCT user_id) as count','conditions'=>array('auction_id'=>$auction_id))));

		$this->set('realbids', $this->Auction->Bid->find('count', array('conditions' => array('Bid.auction_id' => $auction_id, 'Bid.debit >' => 0, 'Bid.credit' => 0, 'User.autobidder' => 0))));
		$this->set('autobids', $this->Auction->Bid->find('count', array('conditions' => array('Bid.auction_id' => $auction_id, 'Bid.debit >' => 0, 'Bid.credit' => 0, 'User.autobidder' => 1))));

		$priceIncrement = $this->requestAction('/settings/get/price_increment/'.$auction_id.'/0');
		$this->set('priceIncrement', $priceIncrement);

		$priceDifference = $auction['Auction']['minimum_price'] - $auction['Auction']['price'];
		if($priceDifference > 0) {
			$realBidsRequired = ceil($priceDifference / $priceIncrement);
		} else {
			$realBidsRequired = 0;
		}
		$this->set('realBidsRequired', $realBidsRequired);
	}

	function admin_delete($id = null) {
		//ini_set('max_execution_time', 600);

		if (!$id) {
			$this->Session->setFlash(__('Invalid auction id.', true));

		}
		if ($this->Auction->del($id)) {
			$this->Session->setFlash(__('The auction was deleted successfully.', true));
		} else {
			$this->Session->setFlash(__('There was a problem deleting this auction.', true));
		}
		$this->redirect(array('action' => 'index'));
		
		// we need to archive any bids before deleting the auction
		/*$expiry_date = date('Y-m-d H:i:s', time() - ($this->appConfigurations['cleaner']['clear'] * 24 * 60 * 60));

		$bids = $this->Auction->Bid->find('all', array('conditions' => array('Bid.auction_id' => $id), 'contain' => ''));
		if($this->appConfigurations['simpleBids'] == false) {
			if(!empty($bids)) {
				foreach($bids as $bid) {
					$archived = $this->Auction->Bid->find('first', array('conditions' => array('Bid.description' => __('Archived Bids', true), 'Bid.user_id' => $bid['Bid']['user_id']), 'contain' => ''));
					if(!empty($archived)) {
						$archived['Bid']['debit'] 	+= $bid['Bid']['debit'];
						$archived['Bid']['created'] = $expiry_date;
						$this->Auction->Bid->save($archived);
					} else {
						$archive['Bid']['user_id'] 		= $bid['Bid']['user_id'];
						$archive['Bid']['description']  = __('Archived Bids', true);
						$archive['Bid']['debit'] 		= $bid['Bid']['debit'];
						$archive['Bid']['created'] 		= $expiry_date;

						$this->Auction->Bid->create();
						$this->Auction->Bid->save($archive);
					}

					$this->Auction->Bid->delete($bid['Bid']['id']);
				}
			}
		}

		if ($this->Auction->del($id)) {
			$this->Session->setFlash(__('The auction was deleted successfully.', true));
		} else {
			$this->Session->setFlash(__('There was a problem deleting this auction.', true));
		}
		$this->redirect(array('action' => 'index'));*/
	}
	
	function admin_emailwinner($id){
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Auction.', true));
			$this->redirect(array('action' => 'index'));
		}

		$auction = $this->Auction->read(null, $id);
		
		$this->set('auction', $auction);
		
		if(empty($auction)) {
			$this->Session->setFlash(__('The auction ID was invalid.', true));
			$this->redirect(array('action'=>'index'));
		}
		
		$this->Session->setFlash(__('The bids were successfully refunded.', true));
		//$this->redirect(array('action' => 'index'));
	}
}
?>
