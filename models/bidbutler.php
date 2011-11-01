<?php
	class Bidbutler extends AppModel {

		var $name = 'Bidbutler';

		var $belongsTo = array('User', 'Auction');

		var $actsAs = array('Containable');

	}
?>
