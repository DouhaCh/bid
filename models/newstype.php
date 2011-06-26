<?php
	class Newstype extends AppModel {

		var $name = 'Newstype';

		var $hasMany = array(
			'News' => array(
				'className'  => 'News',
				'foreignKey' => 'newstype_id',
				'dependent'  => false
			)
		);

	}
?>
