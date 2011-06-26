<?php
class TestimonialsController extends AppController

{
	function beforeFilter(){
		parent::beforeFilter();
		if(isset($this->Auth)){
			$this->Auth->allow('index');
		}
	}

	function index() {
		$this->paginate = array(
				'conditions' => array('Testimonial.active' => 1),
				'fields' => array('Testimonial.img', 'Testimonial.content', 'Testimonial.id', 'Testimonial.auction_id', 'User.username' ),
				'contain' => array('User', 'Auction' => array('fields' => array('id','product_id'),'Product.title')),
	            'limit' => $this->appConfigurations['endedLimit'],
	            'order' => array(
	                'Auction.end_time' => 'desc'
	            )
	        );
	     $testimonials = $this->paginate();
	     $this->set('testimonials', $testimonials);	     	   
	}
	
	//add testimonial by user
	function add($auction_id) {	
	    $testimonial = $this->Testimonial->findByAuctionId($auction_id);
	    if (empty ($testimonial)) {
	    	if (empty($this->data)) {
		    	$this->set('auction_id', $auction_id);	    
	    	} else {
		    	if (is_uploaded_file($this->data['Testimonial']['image1']['tmp_name'])) {						
					$this->data['Testimonial']['img'] = $this->Testimonial->storeImage($this->data['Testimonial']['image1']);	
										
				}
	    		$this->data['Testimonial']['user_id'] = $this->Auth->User('id');
		        	//$this->data['Testimonial']['img'] = $this->data['User']['avatar'];
		        	//$this->data['Testimonial']['time'] = date('Y-m-d H:i:s');
		        	$this->data['Testimonial']['auction_id'] = $auction_id;
					$this->Testimonial->create();
					$this->Testimonial->save($this->data);
	    		//udpate the auction status
	    		// 0 is auction does not have a testimonial
	    		// 1 is auction alrady has a testimonial
	    		// 2 is testimonial of this auction is waiting to be aprroved by admin
				//$auction = $this->Testimonial->Auction->read(null, $auction_id);
				$this->Testimonial->Auction->id = $auction_id;
	       		$this->Testimonial->Auction->saveField('testimonial', 2);
				$this->Testimonial->Auction->save();
				
	    	}
	    } else {
	    	if (empty($this->data)) {
	    		$this->set('auction_id', $auction_id);
	    		$this->set('testi_content',$testimonial['Testimonial']['content']);
	    	} else {
	    		$this->Testimonial->read(null, $testimonial['Testimonial']['id']);	  
	    		if (is_uploaded_file($this->data['Testimonial']['image1']['tmp_name'])) {
	    			@unlink(realpath(WWW_ROOT . $this->Testimonial->field('img'))); 					
					$this->Testimonial->set('img', $this->Testimonial->storeImage($this->data['Testimonial']['image1']));															
				}	    		
	    		$this->Testimonial->set('content', $this->data['Testimonial']['content']);
				$this->Testimonial->save();
	    	}
	    }		
	    $this->layout='ajax_frame'; 
	}
	
	//view 1 testimonial by user
	function view($auction_id) {
		$testimonials = $this->Testimonial->find('all', array('conditions' => array('Testimonial.auction_id' => $auction_id),
								   'contain' => array('User', 'Auction' => array('Product.title'))));
		$this->set('testimonials', $testimonials);
		$this->layout='ajax_frame';
	}
	
	/* 
	 * display list of user's testimonials in admin page
	 */
	function admin_index() {
		$this->paginate = array(
				'conditions' => array('Testimonial.active' => 0),
	            'contain' => array('User', 'Auction' => array('Product.title')),
	            'limit' => $this->appConfigurations['adminPageLimit'],
	            'order' => array(
	                'Testimonial.id' => 'desc'
	            )
	        );
	     $this->set('testimonials', $this->paginate());
	}
	
	/*
	 * approve user testimonial
	 */
	function admin_approve ($testi_id){		
		$testimonial = $this->Testimonial->read(null, $testi_id);
		if (!empty($testimonial)) {
					$this->Testimonial->set('active', 1);
					$this->Testimonial->save();
					$this->_bids($testimonial['Testimonial']['user_id'],"Bid thưởng cho Testimonial của phiên ID ".$testimonial['Testimonial']['auction_id'],2000);
					
		}
		
		//udpate the auction status
				$auction = $this->Testimonial->Auction->id = $testimonial['Testimonial']['auction_id'];
	        	if (!empty($auction)) {
					$this->Testimonial->Auction->saveField('testimonial', 1);
					$this->Testimonial->Auction->save();
				}
	        	$this->redirect($this->referer());
				
	}
	
	/*
	 * delete user testimonial
	 */
	function admin_delete ($testi_id) {
		$testimonial = $this->Testimonial->read(null, $testi_id);
		if (!empty($testimonial)) {
			@unlink(realpath(WWW_ROOT . $this->Testimonial->field('img'))); 	
			$this->Testimonial->delete();
		}
	    $this->redirect($this->referer());
		
	}
	
	/*
	 * Edit user testimonial
	 */
	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Testimonial', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {			
			if ($this->Testimonial->save($this->data)) {
				$this->Session->setFlash(__('The testimonial has been updated successfully.', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('There was a problem editing the testimonial please review the errors below and try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Testimonial->read(null, $id);
		}
	}
	/*
	 * Add a testimonial by admin
	 */
	function admin_add(){
	    	if (!empty($this->data)) {
		    	if (is_uploaded_file($this->data['Testimonial']['image1']['tmp_name'])) {						
					$this->data['Testimonial']['img'] = $this->Testimonial->storeImage($this->data['Testimonial']['image1']);	
										
				}
		        	//$this->data['Testimonial']['img'] = $this->data['User']['avatar'];
		        	$this->data['Testimonial']['time'] = date('Y-m-d H:i:s');
					$this->Testimonial->create();
					$this->Testimonial->save($this->data);
	    		//udpate the auction status
	    		// 0 is auction does not have a testimonial
	    		// 1 is auction alrady has a testimonial
	    		// 2 is testimonial of this auction is waiting to be aprroved by admin
				//$auction = $this->Testimonial->Auction->read(null, $auction_id);
				$this->Testimonial->Auction->id = $this->data['Testimonial']['auction_id'];
	       		$this->Testimonial->Auction->saveField('testimonial', 2);
				$this->Testimonial->Auction->save();
				
	    	}
	    }		
	/*
	 * view a testimonial in thickbox
	 */
	function viewone($id){
		$testimonial = $this->Testimonial->findById($id);
		if (!empty($testimonial)) {
			$this->set('img', $testimonial['Testimonial']['img']);
			$this->set('content', $testimonial['Testimonial']['content']);
		}
		$this->layout='ajax_frame';
	}
	/**
	 * Function to add bids to user's account
	 */
	function _bids($user_id = null, $description = null, $credit = 0, $debit = 0, $tid= 0){
		if(!empty($user_id) && !empty($description)){
			if($this->appConfigurations['simpleBids'] == true) {
				$user = $this->Testimonial->User->find('first', array('conditions' => array('User.id' => $user_id), 'contain' => ''));
				if($credit > 0) {
					$user['User']['bid_balance'] += $credit;
				} else {
					$user['User']['bid_balance'] -= $debit;
				}
				
				$this->Testimonial->User->save($user, false);
				
				$bid['Bid']['user_id']     = $user_id;
				$bid['Bid']['description'] = $description;
				$bid['Bid']['credit']      = $credit;
				$bid['Bid']['debit']       = $debit;
				
				return $bid;
			} else {
				$bid['Bid']['user_id']     = $user_id;
				$bid['Bid']['description'] = $description;
				$bid['Bid']['type']		   = "Bid Reward";
				$bid['Bid']['credit']      = $credit;
				$bid['Bid']['debit']       = $debit;
				$bid['Bid']['code']        = $tid;
				
				$this->Testimonial->User->Bid->create();
				
				return $this->Testimonial->User->Bid->save($bid);
			}
		}else{
			return false;
		}
	}
	
	/*
	 * Function to rate the testimonial
	 */
	function rate($id) {
		if ($this->Session->check('Auth.User.id')){
			$vote = $this->Testimonial->Vote->find('all', array(
					'conditions' => array('Vote.user_id' => $this->Auth->User('id'),
										  'Vote.testimonial_id' => $id
			)));
			if (empty($vote)){
				$data = array (
					'testimonial_id' => $id,
					'user_id' => $this->Auth->User('id')					
				);
				$this->Testimonial->Vote->create();
				$this->Testimonial->Vote->save($data);
				
				$this->Testimonial->id = $id;	
				$value = $this->Testimonial->read('rate');			
				$value = $value['Testimonial']['rate'] + 1;
				$this->Testimonial->saveField('rate', $value);
				$this->Testimonial->save();
				echo $value;
				$this->layout='ajax_frame';				
			} else {
				echo -1;
			}
		} else {
			$this->Session->setFlash(__('You have to login to vote for this testimonial.', true));
		}
	}
}
