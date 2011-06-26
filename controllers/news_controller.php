<?php
class NewsController extends AppController {

	var $name = 'News';

	function beforeFilter(){
		parent::beforeFilter();

		if(!empty($this->Auth)) {
			$this->Auth->allow('index', 'getlatest', 'view');
		}
	}

	function index() {
		$this->paginate = array('limit' => 10, 'order' => array('created' => 'desc'));
		$this->set('news', $this->paginate());

		$this->pageTitle = __('Latest News', true);
	}

	function view($id = null) {
		if(empty($id)) {
			$this->Session->setFlash(__('Invalid News.', true));
			$this->redirect(array('action'=>'index'));
		}

		$news = $this->News->read(null, $id);
		if(empty($news)) {
			$this->Session->setFlash(__('Invalid News.', true));
			$this->redirect(array('action'=>'index'));
		}

		$this->set('news', $news);
		$this->pageTitle = $news['News']['title'];
        if(!empty($news['News']['meta_description'])) {
			$this->set('meta_description', $news['News']['meta_description']);
		}
		if(!empty($news['News']['meta_keywords'])) {
			$this->set('meta_keywords', $news['News']['meta_keywords']);
		}
	}

	function getlatest($limit = 1) {
		if($limit == 1){
			$type = 'first';
		}else{
			$type = 'all';
		}
		$newscache = Cache::read('news_latest'.$limit, 'short');
		if (empty($newscache)) {
			$newscache = $this->News->find($type, array('limit' => $limit, 'order' => array('created' => 'desc')));
			Cache::write('news_latest'.$limit, $newscache, 'short');
		}
		return $newscache;
	}

	function admin_index() {
		$this->paginate = array('limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('created' => 'desc'));
		$this->set('news', $this->paginate());
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->News->create();	
			switch ($this->data['News']['newstype_id']){
				case 1:
					$this->data['News']['title'] = "[Thông Báo] ".$this->data['News']['title'];
					break;
				case 2:
					$this->data['News']['title'] = "[Lỗi] ".$this->data['News']['title'];
					break;	
			}
			if ($this->News->save($this->data)) {
				$this->Session->setFlash(__('The news has been added successfully.', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('There was a problem adding the news please review the errors below and try again.', true));
			}
		}
		$this->set('newstypes', $this->News->Newstype->find('list'));		
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid News', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			switch ($this->data['News']['newstype_id']){
				case 1:
					$this->data['News']['title'] = "[Thông Báo] ".$this->data['News']['title'];
					break;
				case 2:
					$this->data['News']['title'] = "[Lỗi] ".$this->data['News']['title'];
					break;	
			}
			if ($this->News->save($this->data)) {
				$this->Session->setFlash(__('The news has been updated successfully.', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('There was a problem editing the news please review the errors below and try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->News->read(null, $id);
		}
		$this->set('newstypes', $this->News->Newstype->find('list'));		
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for News', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->News->del($id)) {
			$this->Session->setFlash(__('The news was successfully deleted.', true));
			$this->redirect(array('action'=>'index'));
		}
	}
}
?>