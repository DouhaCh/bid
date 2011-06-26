<?php
class NotificationsController extends AppController {

	var $name = 'Notifications';
	
	function index(){
		if(!empty($this->appConfigurations['endedLimit'])) {
			$this->paginate = array('contain' => false, 'conditions' => array('user_id' => $this->Auth->user('id')), 'limit' => $this->appConfigurations['endedLimit'], 'order' => array('id' => 'desc'));
			$notifications = $this->paginate();
		} else {
			$this->paginate = array('contain' => false, 'conditions' => array('user_id' => $this->Auth->user('id')), 'limit' => $this->appConfigurations['pageLimit'], 'order' => array('id' => 'desc'));
			$notifications = $this->paginate();
		}
		$this->set('notifications', $notifications);
		
		$productNotifications=$this->Notification->find('all', array(
			'conditions' => array(
				'user_id' => $this->Auth->user('id'),
				'type'	  => 'Sản phẩm',
			),
			'limit' => 10,
			'order' => 'id DESC'
		));
		$this->set('productNotifications', $productNotifications);
		
		$bidbutlerNotifications=$this->Notification->find('all', array(
			'conditions' => array(
				'user_id' => $this->Auth->user('id'),
				'type'	  => 'Bid tự động',
			),
			'limit' => 10,
			'order' => 'id DESC'
		));
		$this->set('bidbutlerNotifications', $bidbutlerNotifications);
		
		$this->pageTitle = __('Notifications', true);
	}

	function view($id){
		$this->layout = 'ajax_frame';  
		
		$notification = $this->Notification->findById($id);
		
		$this->set('notification', $notification);
		
		//set notification to read
		$this->Notification->id = $id;
		$this->data['Notification']['status'] = '1';
		
		$this->Notification->save($this->data);
	}

	
}
?>