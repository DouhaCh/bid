<?php
class BidbutlersController extends AppController {

	var $name = 'Bidbutlers';

	function index() {
		$this->paginate = array('conditions' => array('Auction.closed' => 0, 'Bidbutler.user_id' => $this->Auth->user('id')), 'limit' => 20, 'order' => array('Bidbutler.created' => 'desc'), 'contain' => array('Auction' => array('Product' => 'Image')));
		$bidbutlers = $this->Bidbutler->Auction->Product->Translation->translate($this->paginate());
		$this->set('bidbutlers', $bidbutlers);
		$this->pageTitle = __('My Bid Butlers', true);
	}

	function add($auction_id = null) {
		$this->autoRender = false;  
		
		if (!$auction_id || empty($this->data)) {
			echo "0::Bid tự động không hợp lệ";
			return;
		}
		
		$auction = $this->Bidbutler->Auction->find('first', array('conditions' => array('Auction.id' => $auction_id)));
		
		if(empty($auction)) {
			echo "0::Phiên đấu giá không hợp lệ";
			return;
		}

		if(!empty($auction['Auction']['nail_bitter'])) {
			echo "0::Phiên đấu giá không cho phép đặt bid tự động";
			return;
		}
		
		if(strtotime($auction['Auction']['end_time']) < time()) {
			echo "0::Phiên đấu giá đã kết thúc";
			return;
		}
		
		$bidbutler = $this->Bidbutler->find('first', array(
			'conditions' => array(
				'user_id' => $this->Auth->user('id'),
				'auction_id' => $auction_id,
				'Bidbutler.active' => 1
			)
		));
		
		if(!empty($bidbutler)){
			echo "0::Bạn đã đặt bid tự động cho phiên đấu giá này";
			return;
		}
		
		if (!empty($this->data)) {
			$this->data['Bidbutler']['user_id'] = $this->Auth->user('id');
			$this->data['Bidbutler']['auction_id'] = $auction_id;
			$this->data['Bidbutler']['start_amount'] = $this->data['Bidbutler']['bids'];
			$this->data['Bidbutler']['active'] = 1;
			$this->Bidbutler->create();
			
			if ($this->Bidbutler->save($this->data)) {
				echo "1::Bổ sung bid tự động thành công";
			}else{
				echo "0::Có lỗi trong quá trình bổ sung bid tự động";
			}
		}
	}

	function edit($auction_id = null) {
		$this->autoRender = false;  
		
		if (!$auction_id || empty($this->data)) {
			echo "0::Bid tự động không hợp lệ";
			return;
		}
		
		$auction = $this->Bidbutler->Auction->find('first', array('conditions' => array('Auction.id' => $auction_id)));
		
		if(empty($auction)) {
			echo "0::Phiên đấu giá không hợp lệ";
			return;
		}

		if(!empty($auction['Auction']['nail_bitter'])) {
			echo "0::Phiên đấu giá không cho phép đặt bid tự động";
			return;
		}
		
		if(strtotime($auction['Auction']['end_time']) < time()) {
			echo "0::Phiên đấu giá đã kết thúc";
			return;
		}
		
		$bidbutler = $this->Bidbutler->find('first', array(
			'conditions' => array(
				'user_id' => $this->Auth->user('id'),
				'auction_id' => $auction_id,
				'Bidbutler.active' => 1
			)
		));
		
		if(empty($bidbutler)){
			echo "0::Bạn chưa đặt bid tự động cho phiên đấu giá này";
			return;
		}
		
		if (!empty($this->data)) {
			$this->Bidbutler->id = $bidbutler["Bidbutler"]["id"];
			
			if ($this->Bidbutler->save($this->data)) {
				echo "1::Cập nhật bid tự động thành công";
			}else{
				echo "0::Có lỗi trong quá trình cập nhật bid tự động";
			}
		}
	}

	function delete($auction_id = null) {
		$this->autoRender = false;  
		
		if (!$auction_id) {
			echo "0::Bid tự động không hợp lệ";
			return;
		}
		
		$auction = $this->Bidbutler->Auction->find('first', array('conditions' => array('Auction.id' => $auction_id)));
		
		if(empty($auction)) {
			echo "0::Phiên đấu giá không hợp lệ";
			return;
		}

		if(!empty($auction['Auction']['nail_bitter'])) {
			echo "0::Phiên đấu giá không cho phép đặt bid tự động";
			return;
		}
		
		if(strtotime($auction['Auction']['end_time']) < time()) {
			echo "0::Phiên đấu giá đã kết thúc";
			return;
		}
		
		$bidbutler = $this->Bidbutler->find('first', array(
			'conditions' => array(
				'user_id' => $this->Auth->user('id'),
				'auction_id' => $auction_id,
				'Bidbutler.active' => 1
			)
		));
		
		if(empty($bidbutler)){
			echo "0::Bạn chưa đặt bid tự động cho phiên đấu giá này";
			return;
		}
			
		$this->Bidbutler->id = $bidbutler["Bidbutler"]["id"];
		$this->data["Bidbutler"] = array(
			"active" => 0,
			"closed" => 1,
			"reason" => "Xóa bởi người dùng"
		);
			
		if ($this->Bidbutler->save($this->data)) {
			echo "1::Xóa bid tự động thành công";
		}else{
			echo "0::Có lỗi trong quá trình cập nhật bid tự động";
		}
	}

	function admin_user($user_id = null) {
		if(empty($user_id)) {
			$this->Session->setFlash(__('Invalid User.', true));
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}
		$user = $this->Bidbutler->User->read(null, $user_id);
		if(empty($user)) {
			$this->Session->setFlash(__('Invalid User.', true));
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}
		$this->set('user', $user);

		$this->paginate = array('conditions' => array('Bidbutler.user_id' => $user_id), 'contain' => array('User', 'Auction' => 'Product'), 'limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('Bidbutler.created' => 'desc'));
		$this->set('bidbutlers', $this->paginate());
	}

	function admin_delete($id = null) {
		if(empty($id)) {
			$this->Session->setFlash(__('Invalid id for bid butler', true));
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}
		$bidbutler = $this->Bidbutler->read(null, $id);
		if(empty($bidbutler)) {
			$this->Session->setFlash(__('Invalid id for bid butler', true));
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}

		if ($this->Bidbutler->del($id)) {
			$this->Session->setFlash(__('The bid butler was successfully deleted.', true));
		} else {
			$this->Session->setFlash(__('There was a problem deleting this bid butler.', true));
		}
		$this->redirect(array('action'=>'user', $bidbutler['Bidbutler']['user_id']));
	}
}
?>