<?php
class CommentsController extends AppController {

	var $name = 'Comments';

	function beforeFilter(){
		parent::beforeFilter();

		if(!empty($this->Auth)){
			$this->Auth->allow('view','shoutbox');
		}
	}
	function view($id) {
		$comments=$this->Comment->find('all',
			array(
				'conditions' => array('auction_id'=>$id)
			)
		);
		
		$this->set('comments',$comments);
	}
	
	function add(){
		
		$this->data['Comment']['message']=$_POST['message'];
		$this->data['Comment']['auction_id']=$_POST['id'];
		$this->data['Comment']['user_id']=$this->Auth->user('id');
		$this->data['Comment']['time']=date('Y-m-d H:i:s', strtotime('now'));
		$this->data['Comment']['emo']=$_POST['emo'];
		
		$this->Comment->create();
		$this->Comment->save($this->data);
		
		
		if($this->appConfigurations['bpPerChat']) {
			App::import('model','Bid');
			$Bid = new Bid();
			$this->data['Bid']['user_id']=$this->Auth->user('id');
			$this->data['Bid']['auction_id']=$_POST['id'];
			$this->data['Bid']['description']='Chat';
			$this->data['Bid']['type']='Chat';
			$this->data['Bid']['credit']='0';
			$this->data['Bid']['debit']=$this->appConfigurations['bpPerChat'];
			$this->data['Bid']['create']=date('Y-m-d H:i:s', strtotime('now'));
			$this->data['Bid']['modified']=date('Y-m-d H:i:s', strtotime('now'));
			
			$Bid->save($this->data);
			
		}
		
		
	}
	
	function shoutbox($id) {
		
		$comments=$this->Comment->find('all',
			array(
				'conditions' => array('auction_id'=>$id),
				'order' => 'Comment.id desc',
			)
		);
					
		$this->set('comments',$comments);
		
	}

	
}
?>
