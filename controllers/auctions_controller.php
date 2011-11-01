<?php

class AuctionsController extends AppController {

	var $name = 'Auctions';
	var $uses = array('Auction', 'Setting', 'Country', 'Bid', 'Users');
	var $components = array('PaypalProUk', 'Epayment', 'Twitter','Cookie');
	var $helpers = array('Epayment');

	function beforeFilter(){
		parent::beforeFilter();

		if(!empty($this->Auth)) {
			$this->Auth->allow('test', 'popup', 'index', 'view', 'live', 'home', 'future', 'delCacheFile', 'closed', 'featured', 'winners', 'getcount', 'getstatus', 'ipn', 'latestwinner', 'gettickerauctions','getLatestSold', 'endingsoon', 'getfutureauctions', 'getfeatured', 'getauctions', 'getwinners', 'creditcard', 'credits', 'getendedlist', 'free', 'search', 'timeout', 'tag');
		}
	}
	
	function beforeRender(){
		parent::beforeRender();
		$this->Cookie->write('last_visit',Router::url($this->here, true));
	}

	function home() {
		//CHeck if it was a visitor
		
		if (!$this->Auth->user('id')){
			if (!empty($_GET['khuyenmai'])&&!$this->Cookie->read('referral')){
				$this->Cookie->write('referral',$_GET['khuyenmai'],false,36000);
				$this->Cookie->write('registered','0',false,36000);
			}
		}
		if(!empty($this->appConfigurations['homeFeaturedAuction'])) {
			
			// Get the featured auctions
			// *** Upcoming auctions
			$upcoming = $this->Auction->getAuctions(array(
						'Auction.active' => 1,
						'Auction.closed' => 0, 
						'Auction.special' => 'apple'), 
						4, 
						'Auction.end_time ASC');

			$this->set('upcoming', $upcoming);
			
			//*** Auctions ending soon
			$endSoon  = $this->Auction->getAuctions(array(	'Auction.end_time > '=> date("Y-m-d h:i:"), 
									'Auction.active' => 1), 
								$this->appConfigurations['homeEndingLimit'], 
								'Auction.end_time ASC'
								);
			if(!empty($featured)) {
				$combine_featured[] = $featured;
				$exclude = array_merge($endSoon, $combine_featured);
			} else {
				$exclude = $endSoon;
			}
		} else {
			$endSoon  = $this->Auction->getAuctions(array(	'Auction.end_time > '=> date("Y-m-d h:i:"), 
									'Auction.active' => 1), 
								$this->appConfigurations['homeEndingLimit'], 
								'Auction.end_time ASC');
			$exclude = $endSoon;
		}
		$this->set('auctions_end_soon', $endSoon);
	}

	function getauctions($limit = null, $start_hour = null, $end_hour = null, $order = 'asc', $exclude = array()) {
		if(!empty($exclude)) {
			$exclude = explode(',', $exclude);
		}

		if(!empty($start_hour) && !empty($end_hour)) {
			$conditions = array('closed' => 0, 'Auction.active' => 1, 'Auction.end_time >=' => date('Y-m-d H:i:s', time() + $start_hour * 3600), 'Auction.end_time <=' => date('Y-m-d H:i:s', time() + $end_hour * 3600));
		} elseif(!empty($start_hour)) {
			$conditions = array('closed' => 0, 'Auction.active' => 1, 'Auction.end_time >=' => date('Y-m-d H:i:s', time() + $start_hour * 3600));
		} elseif(!empty($end_hour)) {
			$conditions = array('closed' => 0, 'Auction.active' => 1, 'Auction.end_time <=' => date('Y-m-d H:i:s', time() + $end_hour * 3600));
		} else {
			$conditions = array('closed' => 0, 'Auction.active' => 1);
		}

		$auctions = $this->Auction->find('all', array('conditions' => $conditions, 'contain' => '', 'fields' => array('Auction.id', 'Auction.closed', 'Auction.active', 'Auction.end_time'), 'limit' => $limit, 'order' => array('Auction.end_time' => $order)));

		if(!empty($auctions)) {
			foreach($auctions as $key => $auction) {
				$auction = $this->Auction->getAuctions(array('Auction.id' => $auction['Auction']['id']), 1);
				if(!in_array($auction['Auction']['id'], $exclude)) {
					$auctions[$key] = $auction;
				} else {
					unset($auctions[$key]);
				}
			}
		}

		return $auctions;
	}

	function getfutureauctions($limit = 5) {
		return $this->Auction->getAuctions(array("Auction.start_time > '" . date('Y-m-d H:i:s') . "'", 'Auction.active' => 1), $limit, 'Auction.end_time ASC');
	}

	function getfeatured() {
		$featured  = $this->Auction->getAuctions(array("Auction.start_time < '" . date('Y-m-d H:i:s') . "' AND Auction.end_time > '" . UsersController::getEndTime() . "'", 'Auction.active' => 1, 'Auction.featured' => 1), 1, 'Auction.end_time ASC');

		// if there are no featured auctions, lets just get a closing soon auction
		if(empty($featured)) {
			$featured  = $this->Auction->getAuctions(array("Auction.start_time < '" . date('Y-m-d H:i:s') . "' AND Auction.end_time > '" . UsersController::getEndTime() . "'", 'Auction.active' => 1), 1, 'Auction.end_time ASC');
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

		return $featured;
	}

	function getwinners($limit = 10) {
		return $this->Auction->getAuctions(array('Auction.winner_id >' => 0, 'Auction.closed' => 1), $limit, 'Auction.end_time DESC');
	}

	function gettickerauctions($limit = 5, $live = false) {
		if($live == true) {
			return $this->Auction->getAuctions(array("Auction.start_time < '" . date('Y-m-d H:i:s') . "' AND Auction.end_time > '" . UsersController::getEndTime() . "'", 'Auction.active' => 1), $limit, 'Auction.end_time ASC');
		} else {
			return $this->Auction->getAuctions(array('closed' => 1, 'Auction.active' => 1, 'Auction.winner_id > ' => 0), $limit, 'Auction.end_time DESC');
		}
	}

	function index() {
		$auctions = $this->Auction->getAuctions(array(
			'end_time > '=> date("Y/m/d H:i:s"), 
			'Auction.active' => 1
		), 11);
		
		$this->set('auctions_ending', $auctions);

		$isFeed = ife($this->RequestHandler->prefers('rss') == 'rss', true, false);
		if($isFeed){
			$this->set('channel', array('title' => 'Live Auctions on '.$this->appConfigurations['name'], 'description' => 'Live Auctions on '.$this->appConfigurations['name'].' which available for bidding.'));
		}else{
			$this->pageTitle = __('Live Auctions', true);
		}
		
		echo $this->Auth->User("username");
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
	
	function delCacheFile() {
				$path = TMP.'cache'.DS;
				foreach (glob($path."cake_auction_*") as $filename) {
	   			   @unlink($filename);
				}	
		$this->layout = 'ajax_frame';
	}

	function closed() {
		//$this->cacheAction='3 days';
		if(!empty($this->appConfigurations['endedLimit'])) {
			$this->paginate = array('contain' => '', 'conditions' => array('closed' => 1, 'closed_status <>'=>2, 'Auction.active' => 1), 'limit' => $this->appConfigurations['endedLimit'], 'order' => array('Auction.end_time' => 'desc'));
			$auctions = $this->paginate();
		} else {
			$this->paginate = array('contain' => '', 'conditions' => array('closed' => 1, 'closed_status <>'=>2, 'Auction.active' => 1), 'limit' => $this->appConfigurations['pageLimit'], 'order' => array('Auction.end_time' => 'desc'));
			$auctions = $this->paginate();
		}

		if(!empty($auctions)) {
			foreach($auctions as $key => $auction) {
				$auction = $this->Auction->getAuctions(array('Auction.id' => $auction['Auction']['id']), 1);
				$auctions[$key] = $auction;
				$auctions[$key]['Auction']['saving'] = $this->Auction->getSaving($auction);
			}
		}
		
		
		$this->set('auctions', $auctions);
		
		//$this->set('auctions', $auctioncache);
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

	function winners() {
		$this->paginate = array('contain' => '', 'conditions' => array('winner_id >' => 0, 'Auction.closed' => 1, 'Auction.active' => 1), 'limit' => $this->appConfigurations['pageLimit'], 'order' => array('Auction.end_time' => 'desc'));
		$auctions = $this->paginate();

		if(!empty($auctions)) {
			foreach($auctions as $key => $auction) {
				$auction = $this->Auction->getAuctions(array('Auction.id' => $auction['Auction']['id']), 1);
				$auctions[$key] = $auction;
			}
		}

		$isFeed = ife($this->RequestHandler->prefers('rss') == 'rss', true, false);
		if($isFeed){
			$this->set('channel', array('title' => 'Latest Auctions Winner on '.$this->appConfigurations['name'], 'description' => 'Last Auctions Winners on '.$this->appConfigurations['name']));
		}else{
			$this->pageTitle = __('Winners', true);
		}

		$this->set('auctions', $auctions);
	}

	function credits($bid_debit = null) {
		$this->paginate = array('contain' => '', 'conditions' => array("start_time < '" . date('Y-m-d H:i:s') . "' AND end_time > '" . UsersController::getEndTime() . "'", 'Auction.active' => 1, 'Auction.bid_debit' => $bid_debit), 'limit' => $this->appConfigurations['pageLimit'], 'order' => array('Auction.end_time' => 'asc'));
		$auctions = $this->paginate();

		if(!empty($auctions)) {
			foreach($auctions as $key => $auction) {
				$auction = $this->Auction->getAuctions(array('Auction.id' => $auction['Auction']['id']), 1);
				$auctions[$key] = $auction;
			}
		}

		$this->set('auctions', $auctions);
		$this->set('bid_debit', $bid_debit);
		$this->pageTitle = $bid_debit.__(' Credit Auctions', true);
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
	
	function getLatestSold($limit = 1) {
		if($limit == 1){
			$type = 'first';
		}else{
			$type = 'all';
		}
		
		if (($sold = Cache::read('sold'))===false) {
					$sold = $this->Auction->getAuctions(array('Auction.closed'=>1),$limit);
					Cache::write('sold', $sold);
				}
		return $sold;
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
		
		if (is_numeric($id) && $id>0) {
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
		
		$this->layout = "auction_view";
		$auction = $this->Auction->getAuctions(array('Auction.id' => $id), 1, null, null, 'max');

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
		//$this->set('watchlist', $this->Auction->Watchlist->find('first', array('conditions' => array('Auction.id' => $id, 'User.id' => $this->Auth->user('id')))));

		$options['include_amount']['price_increment'] = $auction['Auction']['price_step'];
		
		//$this->set('bidHistories', $this->Auction->Bid->histories($auction['Auction']['id'], $this->appConfigurations['bidHistoryLimit'], $options));

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
		
		if(!empty($bidbutler)){
			$this->data["Bidbutler"] = $bidbutler["Bidbutler"];
			$this->set("bidbutler", $bidbutler);
		}
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
	function endingsoon($id = null, $limit = 5) {
		return $this->Auction->getAuctions(array('Auction.id <> '.$id, "Auction.start_time < '" . date('Y-m-d H:i:s') . "' AND Auction.end_time > '" . UsersController::getEndTime() . "'"), $limit, array('Auction.end_time ASC'));
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
			$this->data['Auction']['price'] = $product['Product']['start_price'];
			$this->data['Auction']['minimum_price'] = $product['Product']['minimum_price'];
			$this->Auction->create();
			if ($this->Auction->save($this->data)) {
				$auction_id=$this->Auction->getLastInsertId();
				
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
	
	function admin_updatestatus($id, $status) {
		$this->autoRender = false;
		//debug($this->data['Auction']['status_id']);
		//$auction = $this->Auction->read(null, $id);
		//if(empty($auction)) {
		//	$this->Session->setFlash(__('The auction ID was invalid.', true));
		//	$this->redirect(array('action'=>'index'));
		//}
		
		//$auction['Auction']['status_id'] = $this->data['Auction']['status_id'];
		//$this->Auction->save($auction);
		//$this->Session->setFlash(__('Update Status successfully.', true));
		//$this->redirect(array('action' => 'index'));
		$this->Auction->id = $id;
		$this->Auction->saveField('status_id', $status);
		$this->Auction->save();
	}
	
	function admin_filter() {
		if (!empty($this->data)){
			if ($this->Session->check($this->name.'.filterAuction')) {
				$this->Session->del($this->name.'.filterAuction');				
			}
				//$auctionname = $this->data ['Payment'] ['auctionname'];
				$paygates = array ();

				if($this->data['Auction']['0'] == 1){
						array_push($paygates, array("Auction.status_id" => 0));
				}
				if($this->data['Auction']['1'] ==1){
						array_push($paygates, array("Auction.status_id" => 1));
				}
				if($this->data['Auction']['2'] == 1){
						array_push($paygates, array("Auction.status_id" => 2));
				}
				if($this->data['Auction']['3'] == 1){
						array_push($paygates, array("Auction.status_id" => 3));
				}
				if($this->data['Auction']['4'] == 1){
						array_push($paygates, array("Auction.status_id" => 4));
				}
				if($this->data['Auction']['5'] == 1){
						array_push($paygates, array("Auction.status_id" => 5));
				}
				if ($this->data['Auction']['alltime'] == 0){
					if (isset($this->data['Auction']['startdate']) and isset($this->data['Auction']['enddate'])){
						$conditions [] = 'Auction.end_time >= \'' . $this->data['Auction']['startdate']['year'] . '-' . $this->data['Auction']['startdate']['month'] . '-' . $this->data['Auction']['startdate']['day'] . '\'';
						$conditions [] = 'Auction.end_time <= \'' . $this->data['Auction']['enddate']['year'] . '-' . $this->data['Auction']['enddate']['month'] . '-' . $this->data['Auction']['enddate']['day'] . ' 23:59:59\'';
					    
					}
				}	
				$conditions [] = 'Auction.deleted = 0';
				array_push($conditions,array('OR' => $paygates));
				$this->Session->write($this->name.'.filterAuction', $conditions);
		} else {
			if($this->Session->check($this->name.'.filterAuction')) {
				$conditions = $this->Session->read($this->name.'.filterAuction');				
			}
		}
				//$this->paginate = array ('limit' => $this->appConfigurations ['adminPageLimit']);
				//$this->set ( 'auction', $this->paginate ('Auction', $conditions) );
				$this->paginate = array('conditions' => $conditions, 'limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('end_time' => 'desc'), 'contain' => array('Product' => array('Category'), 'Status', 'Winner', 'Bid'));
				$this->set('auctions', $this->paginate('Auction'));
				$this->render('admin_index');
				
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
		for ($i=1; $i< 4; $i++ ) {
			switch ($i) {
				case 1:
					$xu_type = 1;
					break;
				case 2:
					$xu_type = 2;
					break;
				case 3:
					$xu_type = 3;
					break;	
				
			}
			$bid = $this->Auction->Bid->find('first', array(
				'conditions' => array(
					'user_id' => $auction['Auction']['winner_id'],
					'Bid.type' => 'Bid',
					'Bid.auction_id' => $id,
					'Bid.xu_type' => $xu_type
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
				'xu_type' => $xu_type,
				'created' => date('Y-m-d H:i:s', time()),
				'modified' => date('Y-m-d H:i:s', time())
			);
			
			$this->Auction->Bid->create();
			$this->Auction->Bid->save($this->data);
		}
		

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
		$topbidders = $this->Auction->Bid->find('all',array('fields'=>'User.username, User.id, User.beginner, SUM(debit) as used','conditions'=>array('auction_id'=>$auction_id),'group'=>array('user_id'),'limit'=>10,'order'=>'used DESC'));
		//$this->set('topbidders', $topbidders);
		
		$detail_topbidders = array();
		foreach ($topbidders as $bidder) {
			$temp = array();
			$temp2 = array();
			$nXUbids = $this->Auction->Bid->find('all',array('fields'=>'SUM(debit) as used','conditions'=>array('auction_id'=>$auction_id, 'user_id' => $bidder['User']['id'], 'xu_type' => 1)));
			if (!isset($nXUbids[0][0]['used'])) {
				$nXUbids['0']['0']['used'] = 0;
			}
			if (($auction['Auction']['beginner'] == 1) && ($bidder['User']['beginner'] == 1	) ) {
				$bXUbids = $this->Auction->Bid->find('all',array('fields'=>'SUM(debit) as used','conditions'=>array('auction_id'=>$auction_id, 'user_id' => $bidder['User']['id'], 'xu_type' => 2)));
			} else $bXUbids['0']['0']['used'] = 0;
			$uRXUbids = $this->Auction->Bid->find('all',array('fields'=>'SUM(debit) as used','conditions'=>array('auction_id'=>$auction_id, 'user_id' => $bidder['User']['id'], 'xu_type' => 3)));
			if (!isset($uRXUbids[0][0]['used'])) {
				$uRXUbids['0']['0']['used'] = 0;
			}
			array_push($temp, $nXUbids['0']['0']['used'], $bXUbids['0']['0']['used'], $uRXUbids['0']['0']['used']);
			array_push($temp2, $bidder['User'], $bidder['0'], $temp);
			array_push($detail_topbidders, $temp2);			
		}
		$this->set('detail_topbidders', $detail_topbidders);
		$this->set('participated',$this->Auction->Bid->find('count', array('fields'=>'COUNT(DISTINCT user_id) as count','conditions'=>array('auction_id'=>$auction_id))));

		$this->set('realbids', $this->Auction->Bid->find('count', array('conditions' => array('Bid.auction_id' => $auction_id, 'Bid.debit >' => 0, 'Bid.credit' => 0, 'User.autobidder' => 0))));		
		$this->set('realnXUbids', $this->Auction->Bid->find('count', array('conditions' => array('Bid.auction_id' => $auction_id,'Bid.xu_type' => 1, 'Bid.debit >' => 0, 'Bid.credit' => 0, 'User.autobidder' => 0))));
		if ($auction['Auction']['beginner'] == 1) {
			$this->set('realbXUbids', $this->Auction->Bid->find('count', array('conditions' => array('Bid.auction_id' => $auction_id,'Bid.xu_type' => 2, 'Bid.debit >' => 0, 'Bid.credit' => 0, 'User.autobidder' => 0))));
		}
		$this->set('realuRXUbids', $this->Auction->Bid->find('count', array('conditions' => array('Bid.auction_id' => $auction_id,'Bid.xu_type' => 3, 'Bid.debit >' => 0, 'Bid.credit' => 0, 'User.autobidder' => 0))));
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
		
		$data['to'] = $auction['Winner']['email'];
		$data['subject'] = "Bạn đã thắng một phiên đấu giá tại 1bid.vn";
		$data['template'] = 'auctions/won_auction';
		$this->_sendEmail($data);
		
		
		
		
		$this->Session->setFlash(__('The bids were successfully refunded.', true));
		//$this->redirect(array('action' => 'index'));
	}
}
//require_once('..' . DS .'controllers' . DS .'users_controller.php');

?>
