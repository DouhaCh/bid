<?php
	class Bug extends AppModel {

		var $name = 'Bug';
		
		var $actsAs = array('Containable');
		
		var $belongsTo = array('BugType','User');
		
		var $hasMany = array(
			'BugComment' => array(
				'className'  => 'BugComment',
				'foreignKey' => 'bug_id',
				'dependent'  => true
			)
		);
		
		
	}
	?>