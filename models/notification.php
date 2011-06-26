<?php
class Notification extends AppModel {

	var $name = 'Notification';



	function __construct($id = false, $table = null, $ds = null){
		parent::__construct($id, $table, $ds);

		$this->validate = array(
			
		);
	}
}
?>
