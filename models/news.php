<?php
class News extends AppModel {

	var $name = 'News';
	var $belongsTo = array('Newstype');

	function __construct($id = false, $table = null, $ds = null){
		parent::__construct($id, $table, $ds);
		$this->validate = array(
			'title' => array(
				'rule' => array('minLength', 1),
				'message' => __('Title is a required field.', true)
			),
			'brief' => array(
				'rule' => array('minLength', 1),
				'message' => __('Brief is a required field.', true)
			),
			'content' => array(
				'rule' => array('minLength', 1),
				'message' => __('Description is a required field.', true)
			),
		);
	}
	
	function beforeSave() {
		if(!empty($this->data)){
			$path = TMP.'cache'.DS;
			foreach (glob($path."cake_news_*") as $filename) {
   			   @unlink($filename);
			}			
		}
		return true;
	}
}
?>
