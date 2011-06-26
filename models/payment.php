<?php 
class Payment extends AppModel {

	var $name = 'Payment';

	var $belongsTo = array('User');
	
	var $actsAs = array ('Containable');
	
	function writeLog($user_id, $package_id, $method, $amount, $description) {
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
						   'description' => $description));
		}
		$this->create();
		$this->save($data);
	}
}
?>