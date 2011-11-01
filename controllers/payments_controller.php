<?php 
class PaymentsController extends AppController
{
	function admin_promotion($limit=3000000, $rate=0.15){
		$this->autoRender = false;
		
		$users = $this->Payment->find('all', array(
			'fields' => array('Payment.user_id', 'SUM(Payment.amount) AS sum'),
			'conditions' => array('Payment.created > ' => date('Y-m-d')." 00:00:00"),
			'group' => array('Payment.user_id HAVING SUM(Payment.amount) > '.$limit)
		));
		
		print_r($users);
		
		$Bid = $this->User->Bid;
		foreach($users as $user){
			$bid = $Bid->find('first', array(
				'conditions' => array(
					'Bid.created > ' => date('Y-m-d').' 00:00:00',
					'Bid.user_id' => $user['Payment']['user_id'],
					'Bid.code != ' => ''
				),
				'fields' => array('SUM(credit) AS sum')
			));
			
			$Bid->create();
			
			$Bid->save(array(
				'Bid' => array(
					'user_id' => $user['Payment']['user_id'],
					'auction_id' => 0,
					'description' => "Promotion",
					'type' => "Bid reward",
					'credit' => $bid['0']['sum'] * $rate,
					'debit' => 0,
					'created' => date('Y-m-d H:i:s'),
					'modified' => date('Y-m-d H:i:s')
				)
			));
		}
	}
	
	function admin_index($page=null) {
//		$total = $this->Payment->find('all', array('fields' => array('SUM(Payment.amount) AS total')));
//		$this->paginate = array(
//				'conditions' => array('Payment.amount >' => 0),					
//	            'limit' => $this->appConfigurations['adminPageLimit']	            
//	        );	 
//	    $this->set('payments', $this->paginate());	
//	    $this->set('total', $total);
		if ($page == null) {
			if ($this->Session->check($this->name.'.filter')) {
					$this->Session->del($this->name.'.filter');				
			}
			$paygates = array ();
			array_push($paygates, array("Payment.method LIKE" => "%mobivi%"));
			array_push($paygates, array("Payment.method LIKE" => "%nganluong%"));
			array_push($paygates, array("Payment.method LIKE" => "%icoin%"));
			array_push($paygates, array("Payment.method LIKE" => "%sms%"));
			array_push($paygates, array("Payment.method LIKE" => "%vcoin%"));
			$conditions [] = 'Payment.created >= \'' . date("Y-m-d") . '\'';
			$conditions [] = 'Payment.created <= \'' . date("Y-m-d") . ' 23:59:59\'';
			$conditions [] = 'Payment.amount > 0';
			array_push($conditions,array('OR' => $paygates));
			$this->Session->write($this->name.'.filter', $conditions);
		} else {
			if($this->Session->check($this->name.'.filter')) {
				$conditions = $this->Session->read($this->name.'.filter');	
			}
		}
				$total = $this->Payment->find('all', array('fields' => array('SUM(Payment.amount) AS total'), 'conditions' => $conditions));
				$this->paginate = array ('limit' => $this->appConfigurations ['adminPageLimit']);
				$this->set ( 'payments', $this->paginate ('Payment', $conditions) );
				$this->set('total', $total);
		
	}
	
	function admin_filter(){
		if (!empty($this->data)){
			if ($this->Session->check($this->name.'.filter')) {
				$this->Session->del($this->name.'.filter');				
			}
				$email = $this->data ['Payment'] ['email'];
				$username = $this->data ['Payment'] ['username'];
				$paygates = array ();

				if($this->data['Payment']['mobivi'] == 1){
						array_push($paygates, array("Payment.method LIKE" => "%mobivi%"));
				}
				if($this->data['Payment']['nganluong'] ==1){
						array_push($paygates, array("Payment.method LIKE" => "%nganluong%"));
				}
				if($this->data['Payment']['icoin'] == 1){
						array_push($paygates, array("Payment.method LIKE" => "%icoin%"));
				}
				if($this->data['Payment']['sms'] == 1){
						array_push($paygates, array("Payment.method LIKE" => "%sms%"));
				}
				if($this->data['Payment']['vcoin'] == 1){
						array_push($paygates, array("Payment.method LIKE" => "%vcoin%"));
				}
				if ($this->data['Payment']['alltime'] == 0){
					if (isset($this->data['Payment']['startdate']) and isset($this->data['Payment']['enddate'])){
						$conditions [] = 'Payment.created >= \'' . $this->data['Payment']['startdate']['year'] . '-' . $this->data['Payment']['startdate']['month'] . '-' . $this->data['Payment']['startdate']['day'] . '\'';
						$conditions [] = 'Payment.created <= \'' . $this->data['Payment']['enddate']['year'] . '-' . $this->data['Payment']['enddate']['month'] . '-' . $this->data['Payment']['enddate']['day'] . ' 23:59:59\'';
					}
				}
			
				
				if (isset ( $this->data ['Payment'] ['username'] )) {
					$conditions [] = 'User.username LIKE \'%' . $this->data ['Payment'] ['username'] . '%\'';
				}
				if (isset ( $this->data ['Payment'] ['email'] )) {
					$conditions [] = 'User.email LIKE \'%' . $this->data ['Payment'] ['email'] . '%\'';
				}		
				$conditions [] = 'Payment.amount > 0';
				array_push($conditions,array('OR' => $paygates));
				$this->Session->write($this->name.'.filter', $conditions);
		} else {
			if($this->Session->check($this->name.'.filter')) {
				$conditions = $this->Session->read($this->name.'.filter');				
			}
		}
				$total = $this->Payment->find('all', array('fields' => array('SUM(Payment.amount) AS total'), 'conditions' => $conditions));
				$this->paginate = array ('limit' => $this->appConfigurations ['adminPageLimit']);
				$this->set ( 'payments', $this->paginate ('Payment', $conditions) );
				$this->set('total', $total);
			
			
			
	}
}