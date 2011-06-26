<?php
class Setting extends AppModel {

	var $name = 'Setting';

	function __construct($id = false, $table = null, $ds = null){
		parent::__construct($id, $table, $ds);

		$this->validate = array(
			'value' => array(
				'rule' => array('minLength', 1),
				'message' => __('Value is required.', true)
			)
		);
	}

	function get($name) {
		if(!empty($name)) {
			$setting = Cache::read($name);
			if(!empty($setting)) {
				return $setting;
			} else {
				$setting = $this->findByName($name);
				if(!empty($setting)) {
					Cache::write($setting['Setting']['name'], $setting['Setting']['value'], '+1 month');
					return $setting['Setting']['value'];
				} else {
					return false;
				}
			}
		} else {
			return false;
		}
	}

	function beforeSave(){
		if(!empty($this->data)){
			if(!empty($this->data['Setting']['name'])){
				Cache::delete($this->data['Setting']['name']);
				Cache::write($this->data['Setting']['name'], $this->data['Setting']['value'], '+1 month');
			}
		}

		return true;
	}
}
?>
