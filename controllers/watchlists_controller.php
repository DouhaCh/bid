<?php
class WatchlistsController extends AppController {

	var $name = 'Watchlists';
	var $helpers = array('Html', 'Form');

	function index() {
		$this->paginate = array('conditions' => array('User.id' => $this->Auth->user('id')), 'limit' => 50, 'order' => array('Auction.end_time' => 'asc'), 'contain' => array('User', 'Auction' => array('Product' => 'Image')));
		$this->set('watchlists', $this->paginate());
	}

	function add($auction_id = null) {
		if(!empty($auction_id)){
			
			$result=array('message'=>'nothing happned');
			$watchlist = $this->Watchlist->find('first', array('conditions' => array('Auction.id' => $auction_id, 'User.id' => $this->Auth->user('id'))));
			if(empty($watchlist)){
				$watchlist['Watchlist']['auction_id'] = $auction_id;
				$watchlist['Watchlist']['user_id'] 	  = $this->Auth->user('id');
				
				if($this->Watchlist->save($watchlist)){
					$result['message']=__('The auction has been added to your watch list.', true);
					//$this->redirect($this->referer('/auctions/view/'.$auction_id));
				}else{
					$result['message']=__('The auction cannot be added to the watchlist.', true);
					//$this->redirect($this->referer('/auctions/view/'.$auction_id));
				}
			}else{
				$result['message']=__('The auction is already in your watchlist.', true);
				//$this->redirect($this->referer('/auctions/view/'.$auction_id));
			}
		}else{
			$result['message']=__('Invalid auction.', true);
			//$this->redirect($this->referer('/auctions/view/'.$auction_id));
		}
		$this->set('aPosts',$result);
		//header('Content-type: text/json');
		//echo $fastjson->convert($result);
	}

	function delete($auction_id = null) {
		$this->layout='ajax_frame';
		
		$watchlist=$this->Watchlist->find('first', array(
			'condition' => array(
				'auction_id' => $auction_id,
				'user_id' => $this->Auth->user('id'),
			),
			'fields' => 'id',
		));
		$id=$watchlist['Watchlist']['id'];
		
		if (!$id) {
			echo __('Invalid id for Watchlist', true);
		}
		if ($this->Watchlist->del($id)) {
			echo __('The auction has been deleted from your watchlist.', true);
		}
	}
}
?>