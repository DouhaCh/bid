<?php
	class Gender extends AppModel {

		var $name = 'Gender';

		var $hasMany = array(
			'User' => array(
				'className'  => 'User',
				'foreignKey' => 'gender_id',
				'dependent'  => false
			)
		);

	}
?>
