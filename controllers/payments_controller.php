<?php 
class PaymentsController extends AppController
{
	function admin_index() {
		$total = $this->Payment->find('all', array('fields' => array('SUM(Payment.amount) AS total')));
		$this->paginate = array(
				'conditions' => array('Payment.amount >' => 0),					
	            'limit' => $this->appConfigurations['adminPageLimit']	            
	        );	 
	    $this->set('payments', $this->paginate());	
	    $this->set('total', $total);
	}
	
	function admin_filter(){
		if (!empty($this->data)){
			if ($this->Session->check($this->name.'.filter')) {
				$this->Session->del($this->name.'.filter');				
			}
			$email = $this->data ['Payment'] ['email'];
				$username = $this->data ['Payment'] ['username'];
				$conditions = array ();
				if(($this->data['Payment']['mobivi'] == 1) and ($this->data['Payment']['nganluong']==1) and ($this->data['Payment']['icoin']==1)){
					
				} else if ($this->data['Payment']['mobivi'] == 1){
					if ($this->data['Payment']['nganluong'] == 1) {
						$conditions [] = 'Payment.method NOT LIKE \'%icoin%\'';
					}else if ($this->data['Payment']['icoin'] == 1){
						$conditions [] = 'Payment.method NOT LIKE \'%nganluong%\'';
					} else {
						$conditions [] = 'Payment.method LIKE \'%mobivi%\'';
					}
				} else if ($this->data['Payment']['nganluong'] ==1){
					if ($this->data['Payment']['mobivi'] == 1) {
						$conditions [] = 'Payment.method NOT LIKE \'%icoin%\'';
					}else if ($this->data['Payment']['icoin'] == 1){
						$conditions [] = 'Payment.method NOT LIKE \'%mobivi%\'';
					} else {
						$conditions [] = 'Payment.method LIKE \'%nganluong%\'';
					}
				} else if ($this->data['Payment']['icoin'] == 1){
					if ($this->data['Payment']['mobivi'] == 1) {
						$conditions [] = 'Payment.method NOT LIKE \'%nganluong%\'';
					}else if ($this->data['Payment']['nganluong'] == 1){
						$conditions [] = 'Payment.method NOT LIKE \'%mobivi%\'';
					} else {
						$conditions [] = 'Payment.method LIKE \'%icoin%\'';
					}
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