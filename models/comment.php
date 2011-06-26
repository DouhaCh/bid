<?php
class Comment extends AppModel {

	var $name = 'Comment';

	var $belongsTo = array('User');
	
	function __construct($id = false, $table = null, $ds = null){
			parent::__construct($id, $table, $ds);

			$this->validate = array(
				'message' => array(
					'minlength' => array(
						'rule' => array('minLength', '1'),
						'message' => __('A message is required.', true)
						)
				)
			);
	}
	
	
}
?>
