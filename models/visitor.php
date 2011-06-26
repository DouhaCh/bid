<?php
class Visitor extends AppModel {

	var $name = 'Visitor';

	var $belongsTo = array('User', 'Auction');
	
	function afterSave()
	{
		return 'OKAA';
	}
}
?>
