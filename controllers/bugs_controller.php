<?php
class BugsController extends AppController {

	var $name = 'Bugs';
	function beforeFilter(){
		parent::beforeFilter();

		if(!empty($this->Auth)){
			$this->Auth->allow('index', 'view');
		}
		$this->layout='ajax_frame';
	}
	
	function index($type=0) {
		$bug_types=$this->Bug->BugType->find('list');
		$bug_types[0]="Tất cả";
		$this->set('bug_types',$bug_types );
		if ($type==0)
			$bugs= $this->Bug->find('all',array('order' => array('Bug.created' => 'desc')));
		else
			$bugs= $this->Bug->find('all',array('conditions' => array('BugType.id' => $type),'order' => array('Bug.created' => 'desc'))); 
		$this->set('bugs',$bugs);
		
		$this->pageTitle = __('Bug Report', true);
	}
	
	function add()
	{
		if(!empty($this->data)) {
			$this->data['Bug']['user_id']=$this->Auth->user('id');
			$this->Bug->save($this->data);
			 $this->redirect($this->appConfigurations['url'] . '/bugs');
		}
		else {
		$this->set('bug_types', $this->Bug->BugType->find('all'));
		$this->pageTitle = __('Thông báo lỗi', true);
		}
	}
	function view($id)
	{
		$bugs= $this->Bug->find('first',array('conditions' => array('Bug.id' => $id),'order' => array('Bug.created' => 'desc')));
		$this->set('bugs',$bugs);
		$this->set('comments',$this->Bug->BugComment->find('all',array('conditions' => array('BugComment.bug_id' => $id))));
	}
	function vote($id)
	{
		$bugs= $this->Bug->find('first',array('conditions' => array('Bug.id' => $id)));
		$bugs['Bug']['vote']=$bugs['Bug']['vote']+1;
		$this->set('bugs',$bugs);
		$this->Bug->set($bugs);
		$this->Bug->save();
	}
}
?>
