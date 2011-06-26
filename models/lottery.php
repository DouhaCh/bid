<?php
class Lottery extends AppModel {

	var $name = 'Lottery';

	//var $belongsTo = array('User');
	
	function add($uid,$amount=1){
		$data=array('user_id'=>$uid,'active'=>1);
		for ($i=0; $i<$amount; $i++) {
			$this->create();
			$this->save($data);
		}
	}
	
	
}
?>
