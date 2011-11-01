<?php
	class Review extends AppModel {

		var $name = 'Review';
		
		var $actsAs = array('Containable');
		
		var $belongsTo = array('Auction','User');
		
	}
?>