<?php
class BugCommentsController extends AppController {

	var $name = 'BugComments';
	
	function beforeFilter(){
		parent::beforeFilter();

		if(!empty($this->Auth)){
			$this->Auth->allow('index', 'view');
		}
	}
	
	function add()
	{
		if (!empty($this->data))
		{
			$this->data['BugComment']['user_id']=$this->Auth->user('id');
			$this->BugComment->save($this->data);
			$this->redirect($this->data['BugComment']['l_url']);
		}
	}
	function index()
	{
	}
}
?>