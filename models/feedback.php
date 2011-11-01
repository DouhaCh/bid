<?php

class Feedback extends AppModel {
	
	var $name = 'Feedback';
	
	var $belongsTo = array('User');
	var $actsAs = array(
					'Containable'
				);

	function __construct($id = false, $table = null, $ds = null){
				parent::__construct($id, $table, $ds);
	
				$this->validate = array(
					'content' => array(
						'minlength' => array(
							'rule' => array('minLength', '1'),
							'message' => __('A message is required.', true)
							)
					)
				);
		}
}
?>