<?php
class ShoutsController extends AppController {

	var $name = 'Shouts';

	function beforeFilter(){
		parent::beforeFilter();

		if(!empty($this->Auth)){
			$this->Auth->allow('view','shoutbox');
		}
	}
	
	function add(){
		
		$this->autoRender = false;
		
		$this->data['Shout']['message']=$_POST['message'];
		$this->data['Shout']['user_id']=$this->Auth->user('id');
		$this->data['Shout']['time']=date('Y-m-d H:i:s', strtotime('now'));
		
		$this->Shout->create();
		$this->Shout->save($this->data);
		
		if($this->appConfigurations['bpPerChat']) {
			App::import('model','Bid');
			$Bid = new Bid();
			$this->data['Bid']['user_id']=$this->Auth->user('id');
			$this->data['Bid']['auction_id']='0';
			$this->data['Bid']['description']='Chat';
			$this->data['Bid']['type']='Chat';
			$this->data['Bid']['credit']='0';
			$this->data['Bid']['debit']=$this->appConfigurations['bpPerChat'];
			$this->data['Bid']['create']=date('Y-m-d H:i:s', strtotime('now'));
			$this->data['Bid']['modified']=date('Y-m-d H:i:s', strtotime('now'));
			$Bid->save($this->data);
		}
		
		
	}
}
?>
