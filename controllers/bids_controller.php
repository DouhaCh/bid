<?php
class BidsController extends AppController {

	var $name = 'Bids';

	function beforeFilter(){
		parent::beforeFilter();

		if(isset($this->Auth)){
			$this->Auth->allow('histories', 'balance', 'unique');
		}
	}

	function histories($auction_id = null){
		Configure::write('debug', 0);
		$this->layout = 'js/ajax';

		if(!empty($auction_id)){
			$histories = $this->Bid->histories($auction_id, $this->appConfigurations['bidHistoryLimit']);
			$this->set('histories', $histories);
		}
	}

	function balance($user_id = null){
		if(!empty($user_id)){
			return $this->User->Bid->balance($user_id);
		} else
			return $this->User->Bid->balance($this->Auth->user('id'));
	}

	function unique($auction_id = null) {
		$bids = $this->Bid->unique($auction_id);

		if(!empty($this->params['requested'])){
			return $bids;
		}else{
			Configure::write('debug', 0);
			$this->layout = 'js/ajax';
			$this->set('count', $bids);
		}
	}

	function index() {
		if($this->appConfigurations['simpleBids'] == false) {
			$this->paginate = array(
				'conditions' => array('Bid.user_id' => $this->Auth->user('id')),
				'limit' => 20,
				'order' => array('Bid.id' => 'desc'),
				'contain' => array('Auction' => array('Product')),
				'fields' => array('MAX(Bid.modified) as date', 'Bid.auction_id', 'products.title','Bid.description', 'Bid.type', 'SUM(Bid.credit) as credit', 'SUM(Bid.debit) as debit'),
				'group'  => array('Bid.user_id', 'Bid.auction_id', 'Bid.type'),
				'joins'=> array(
					array(
						'table' => 'auctions',
        				'type' => 'left outer',
        				'foreignKey' => false,
        				'conditions'=> array('auctions.id = Bid.auction_id')
					),
					array(
						'table' => 'products',
						'type' => 'left outer',
        				'foreignKey' => false,
        				'conditions'=> array('auctions.product_id = products.id')
					)
				)
			);
			$bids = $this->Bid->Auction->Product->Translation->translate($this->paginate());
			$this->set('bids', $bids);
		}

		$this->set('bidBalance', $this->User->Bid->balance($this->Auth->user('id')));

		$this->pageTitle = __('My Bids', true);
	}

	function admin_index() {
		$this->paginate = array('conditions' => array('Bid.auction_id > ' => 0, 'Bid.debit >' => 0, 'Bid.credit' => 0), 'limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('Bid.id' => 'desc'), 'contain' => array('User', 'Auction' => 'Product'));
		$this->set('bids', $this->paginate());
	}

	function admin_auction($auction_id = null, $realBidsOnly = false) {
		if(empty($auction_id)) {
			$this->Session->setFlash(__('Invalid Auction.', true));
			$this->redirect(array('controller' => 'auctions', 'action' => 'index'));
		}
		$auction = $this->Bid->Auction->find('first', array('conditions' => array('Auction.id' => $auction_id), 'contain' => 'Product'));

		if(empty($auction)) {
			$this->Session->setFlash(__('Invalid Auction.', true));
			$this->redirect(array('controller' => 'auctions', 'action' => 'index'));
		}
		$this->set('auction', $auction);
		
		if(!empty($realBidsOnly)) {
			$conditions = array('Bid.auction_id' => $auction_id, 'Bid.debit >' => 0, 'Bid.credit' => 0, 'User.autobidder' => 0);
			$this->set('realBidsOnly', $realBidsOnly);
		} else {
			$conditions = array('Bid.auction_id' => $auction_id, 'Bid.debit >' => 0, 'Bid.credit' => 0);
		}
		
		$this->paginate = array('conditions' => $conditions, 'limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('Bid.id' => 'desc'), 'contain' => array('User', 'Auction' => 'Product'));
		$this->set('bids', $this->paginate());
	}
	
	function admin_allusers(){
		$bids = $this->Bid->find('all', array(
			'conditions' => array('User.admin' => '0'),
			'order' => array('SUM(credit)-SUM(debit)' => 'desc'), 'contain' => array('User'),
			'fields' => array('User.username', "SUM(credit) - SUM(debit) AS bid_balance"),
			'group' => array('User.id HAVING SUM(credit)-SUM(debit) > 100')
		));
		
		$this->set('bids', $bids);
	}
	
	function admin_errorrefund($auction_id,$percent=0.1,$return_winner=false){
		$auction = $this->Bid->Auction->findById($auction_id);
		$bids = $this->Bid->find('all', array(
			'conditions' => array('Bid.auction_id' => $auction_id),
			'fields' => array('Bid.user_id', 'SUM(debit) AS total'),
			'group' => array('User.id')
		));
		
		foreach($bids as $bid){
			if ($return_winner || $bid['Bid']['user_id']!=$auction['Auction']['winner_id']) {
				$this->Bid->create();
				$this->Bid->save(array(
					'Bid' => array(
						'user_id' => $bid['Bid']['user_id'],
						'auction_id' => '0',
						'description' => 'Trả lại Xu cho phiên '.$auction_id,
						'type'	=> 'Bid refund',
						'credit' => $bid['0']['total'] * $percent,
						'debit' => '0',
						'code' => '0',
						'created' => date('Y-m-d H:i:s'),
						'modified' => date('Y-m-d H:i:s')
					)
				));
			}
		}
		
		$this->Session->setFlash('Trả lại XU thành công', 'default', array(
        	'class' => 'success'
        ));
		$this->redirect(array(
			'controller' => 'auctions',
        	'action' => 'index'
        ));
	}

	function admin_user($user_id = null) {
		if(empty($user_id)) {
			$this->Session->setFlash(__('Invalid User.', true));
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}
		$user = $this->Bid->User->read(null, $user_id);
		if(empty($user)) {
			$this->Session->setFlash(__('Invalid User.', true));
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}
		$this->set('user', $user);

		$this->paginate = array('conditions' => array('Bid.user_id' => $user_id), 'limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('Bid.created' => 'desc'), 'contain' => array('Auction' => array('Product')));
		$this->set('bids', $this->paginate());

		$this->set('userBidBalance', $this->User->Bid->balance($user_id));
	}

	function admin_add($user_id = null) {
		if(empty($user_id)) {
			$this->Session->setFlash(__('Invalid User.', true));
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}
		$user = $this->Bid->User->read(null, $user_id);
		if(empty($user)) {
			$this->Session->setFlash(__('Invalid User.', true));
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}

		if(!empty($this->data)) {
			$this->data['Bid']['user_id'] = $user_id;
			if(!empty($this->data['Bid']['total'])) {
				if($this->data['Bid']['total'] > 0) {
					$this->data['Bid']['credit'] = $this->data['Bid']['total'];
				} else {
					$this->data['Bid']['debit'] = $this->data['Bid']['total'] * -1;
				}
			}

			if($this->Bid->save($this->data)) {
				//update user bid_balance on ape-server
				$cmd = '_updateUser';
				$data = array(
					'user_id' => $user_id,
				);
				$this->apePush($cmd, $data);
				
				$this->Session->setFlash(__('The bid transaction has been added successfully.', true));
				$this->redirect(array('action' => 'user', $user_id));
			} else {
				$this->Session->setFlash(__('There was a problem adding the bid transaction please review the errors below and try again.', true));
			}
		}

		$this->set('user', $user);
	}

	function admin_delete($id = null) {
		if(empty($id)) {
			$this->Session->setFlash(__('Invalid id for bid', true));
			$this->redirect(array('controller' => 'users', 'action'=>'index'));
		}
		$bid = $this->Bid->read(null, $id);
		if(empty($bid)) {
			$this->Session->setFlash(__('Invalid id for bid', true));
			$this->redirect(array('controller' => 'users', 'action'=>'index'));
		}

		if ($this->Bid->del($id)) {
			$this->Session->setFlash(__('The bid transaction was successfully deleted.', true));
		} else {
			$this->Session->setFlash(__('There was a problem deleting this bid transation', true));
		}
		$this->redirect(array('action'=>'user', $bid['Bid']['user_id']));
	}

	// this function is used to generate the User.bid_balance if simpleBids is set to true.
	// this works by checking the simpleBids is false.  Once this is run, set simpleBids to true.
	function admin_simple() {
		if($this->appConfigurations['simpleBids'] == false) {
			ini_set('max_execution_time', 0);

			$users = $this->User->find('all', array('conditions' => array('User.autobidder' => 0), 'contain' => ''));
			if(!empty($users)) {
				foreach($users as $user) {
					$user['User']['bid_balance'] = $this->Bid->balance($user['User']['id']);
					$this->User->save($user);
				}
			}
		}
	}
}
?>