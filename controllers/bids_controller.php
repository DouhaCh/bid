<?php
class BidsController extends AppController {

	var $name = 'Bids';

	var $uses = array('Bid');
	
	function index() {
		$this->paginate = array(
			"conditions" => array(
				"user_id" => $this->Auth->User("id")
			),
			"order" => "Bid.created DESC",
			"contain" => array("Auction" => "Product")
		);

		$this->set("bids", $this->paginate());

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
		$conditions = array('Bid.auction_id' => $auction_id, 'Bid.debit >' => 0, 'Bid.credit' => 0);

		$this->paginate = array('conditions' => $conditions, 'limit' => 30, 'order' => array('Bid.id' => 'desc'), 'contain' => array('User'));
		$this->set('bids', $this->paginate());
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

	function admin_history($auction_id){
		$this->paginate = array('conditions' => array("Auction.id" => $auction_id), 'limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('Bid.id' => 'desc'), 'contain' => array('User'));
		$this->set('bids', $this->paginate());
	}
}
?>