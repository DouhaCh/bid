<?php 
class Payment extends AppModel {

	var $name = 'Payment';

	var $belongsTo = array('User');
	
	var $actsAs = array ('Containable');
	
	function logPayment($user_id, $package_id, $method, $amount, $description, $xu=0, $xu_type='Others') {
		if(empty($method)){
		$data = array ('Payment' => array ('user_id' => $user_id,
						   'package_id' => $package_id,
						   'method' => $method,
						   'description' => $description));
		} else {
			$data = array ('Payment' => array ('user_id' => $user_id,
						   'package_id' => $package_id,
						   'amount' => $amount,
						   'method' => $method,
						   'description' => $description,
						   'xu' => $xu,
			               'xu_type' => $xu_type));
		}
		$this->create();
		$this->save($data);
	}
}
?>