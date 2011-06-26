<?php
	class BugType extends AppModel {

		var $name = 'BugType';
		
		var $hasMany = array(
			'Bug' => array(
				'className'  => 'Bug',
				'foreignKey' => 'bug_type_id',
				'dependent'  => false
			)
		);
		
	}
?>