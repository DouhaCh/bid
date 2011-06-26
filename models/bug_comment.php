<?php
	class BugComment extends AppModel {

		var $name = 'BugComment';
		var $actsAs = array('Containable');
		var $belongsTo = array('User');

	}
?>