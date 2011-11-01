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
				'Testimonial.featured' => 'desc',
	        	'Auction.end_time' => 'desc'
	        )
	     );
	     $testimonials = $this->paginate();
	     $this->set('testimonials', $testimonials);	     	   
	}
	
	function post($auction_id=null){
		$this->layout = false;
		
		if(empty($auction_id)){
			echo "Phiên đấu giá không hợp lệ";
			exit;
		}
		
		$auction = $this->Testimonial->Auction->read(null, $auction_id);
		
		if(empty($auction)){
			echo "Phiên đấu giá không hợp lệ";
			exit;
		}
		
		if($auction['Auction']['xu'] == "1"){
			echo "Gói XU hiện không được viết cảm nhận chiến thắng";
			exit;
		}
		
		if($auction['Auction']['winner_id'] != $this->Auth->User('id')){
			echo "Bạn không phải người chiến thắng của phiên đấu giá này";
			exit;
		}
		
		if($auction['Auction']['status_id'] < 4){
			echo "Phiên đấu giá chưa được viết cảm nhận chiến thắng";
			exit;
		}
		
		$testimonial = $this->Testimonial->findByAuctionId($auction_id);
		
		$id = empty($testimonial) ? null : $testimonial['Testimonial']['id'];
		
		if(!empty($testimonial) && $testimonial['Testimonial']['active'] == 1){
			echo "Bạn không được sửa cảm nhận đã được duyệt";
			exit;
		}
		
		if(empty($this->data)){
			if(!empty($testimonial)){
				$this->data = $testimonial;	
			}else{
				$this->data['Testimonial'] = array(
					'service_quality' => 3,
					'product_quality' => 3,
					'shipping_time' => 3,
					'shipping_charge' => 3
				);
			}
			
			$this->set('auction_id', $auction_id);
		}else{
			if (is_uploaded_file($this->data['Testimonial']['image1']['tmp_name'])) {
				//check file extension
				$blacklist = array(".php", ".phtml");
				foreach ($blacklist as $item) {
					if(preg_match("/$item$/i", $this->data['Testimonial']['image1']['name'])) {
						echo "We do not allow uploading PHP files";
						exit;
					}
				}
				//check file type
				if ($this->data['Testimonial']['image1']['type'] != "image/gif" && $this->data['Testimonial']['image1']['type'] != 'image/jpeg' && $this->data['Testimonial']['image1']['type'] != 'image/pjpeg'
				&& $this->data['Testimonial']['image1']['type'] != 'image/bmp' && $this->data['Testimonial']['image1']['type'] != 'image/png') {
					echo "Sorry, we only allow uploading image files";
					exit;
				}
				//check mime type
				$imageinfo = getimagesize($this->data['Testimonial']['image1']['tmp_name']);
				if ($imageinfo['mime'] != "image/gif" && $imageinfo['mime'] != 'image/jpeg' && $imageinfo['mime'] != 'image/pjpeg'
				&& $imageinfo['mime'] != 'image/bmp' && $imageinfo['mime'] != 'image/png') {
					echo "Sorry, we only allow uploading image files";
					exit;
				}

				$this->data['Testimonial']['img'] = $this->Testimonial->storeImage($this->data['Testimonial']['image1']);

			}else{
				echo "Image can not be empty";
				exit;
			}
			
			$this->data['Testimonial']['user_id'] = $this->Auth->User('id');
			$this->data['Testimonial']['time'] = date('Y-m-d H:i:s');
			$this->data['Testimonial']['active'] = 0;
			$this->data['Testimonial']['auction_id'] = $auction_id;
			
			if(!empty($id)){
				$this->Testimonial->id = $id;	
			}else{
				$this->Testimonial->create();
			}
			
			if($this->Testimonial->save($this->data)){
				// update auction status
				$this->Testimonial->Auction->id = $auction_id;
				$this->Testimonial->Auction->saveField('status_id', 5);
				$this->Testimonial->Auction->save();
				$this->Session->setFlash("Bạn đã đăng cảm nhận chiến thắng thành công.".$id);
				$this->redirect("/dau-gia-chien-thang");
			}else{
				$this->Session->setFlash("Có lỗi trong quá trình đăng cảm nhận chiến thắng, xin vui lòng kiểm tra lại.".$id);
				$this->redirect("/dau-gia-chien-thang");
			}
		}
	}
	
	//add testimonial by user
	function add($auction_id) {	
	    $testimonial = $this->Testimonial->findByAuctionId($auction_id);
	    if (empty ($testimonial)) {
	    	if (empty($this->data)) {
		    	$this->set('auction_id', $auction_id);	    
	    	} else {
		    	if (is_uploaded_file($this->data['Testimonial']['image1']['tmp_name'])) {	
		    		//check file extension
		    		$blacklist = array(".php", ".phtml");
					foreach ($blacklist as $item) {
						if(preg_match("/$item$/i", $this->data['Testimonial']['image1']['name'])) {
							echo "We do not allow uploading PHP files";
							exit;
						}
					}
					//check file type
					if ($this->data['Testimonial']['image1']['type'] != "image/gif" && $this->data['Testimonial']['image1']['type'] != 'image/jpeg' && $this->data['Testimonial']['image1']['type'] != 'image/pjpeg' 
								&& $this->data['Testimonial']['image1']['type'] != 'image/bmp' && $this->data['Testimonial']['image1']['type'] != 'image/png') {
						echo "Sorry, we only allow uploading image files";
						exit;
					}
					//check mime type
					$imageinfo = getimagesize($this->data['Testimonial']['image1']['tmp_name']);
		    		if ($imageinfo['mime'] != "image/gif" && $imageinfo['mime'] != 'image/jpeg' && $imageinfo['mime'] != 'image/pjpeg' 
								&& $imageinfo['mime'] != 'image/bmp' && $imageinfo['mime'] != 'image/png') {
						echo "Sorry, we only allow uploading image files";
						exit;
					}
		    		
					$this->data['Testimonial']['img'] = $this->Testimonial->storeImage($this->data['Testimonial']['image1']);	
										
				}
	    		$this->data['Testimonial']['user_id'] = $this->Auth->User('id');
		        $this->data['Testimonial']['time'] = date('Y-m-d H:i:s');
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
				$this->redirect("/dau-gia-chien-thang");
				
	    	}
	    } else {
	    	if (empty($this->data)) {
	    		$this->set('auction_id', $auction_id);
	    		$this->set('testi_imgurl', $testimonial['Testimonial']['img'] );
	    		$this->set('testi_content',$testimonial['Testimonial']['content']);
	    	} else {
	    		$this->Testimonial->read(null, $testimonial['Testimonial']['id']);	  
	    		if (is_uploaded_file($this->data['Testimonial']['image1']['tmp_name'])) {
	    			//check file extension
		    		$blacklist = array(".php", ".phtml");
					foreach ($blacklist as $item) {
						if(preg_match("/$item$/i", $this->data['Testimonial']['image1']['name'])) {
							echo "We do not allow uploading PHP files";
							exit;
						}
					}
					//check file type
					if ($this->data['Testimonial']['image1']['type'] != "image/gif" && $this->data['Testimonial']['image1']['type'] != 'image/jpeg' && $this->data['Testimonial']['image1']['type'] != 'image/pjpeg' 
								&& $this->data['Testimonial']['image1']['type'] != 'image/bmp' && $this->data['Testimonial']['image1']['type'] != 'image/png') {
						echo "Sorry, we only allow uploading image files";
						exit;
					}
					//check mime type
					$imageinfo = getimagesize($this->data['Testimonial']['image1']['tmp_name']);
		    		if ($imageinfo['type'] != "image/gif" && $imageinfo['type'] != 'image/jpeg' && $imageinfo['type'] != 'image/pjpeg' 
								&& $imageinfocreate['type'] != 'image/bmp' && $imageinfo['type'] != 'image/png') {
						echo "Sorry, we only allow uploading image files";
						exit;
					}
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
	function admin_approve ($testi_id, $featured=0){		
		$testimonial = $this->Testimonial->read(null, $testi_id);
		if (!empty($testimonial)) {
			$this->Testimonial->set('featured', $featured);
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
		//$testimonial = $this->Testimonial->findById($id);
		$testimonial = $this->Testimonial->find('first', 
								array(
								'conditions' => array('Testimonial.id' => $id),
								'fields' => array('Testimonial.img', 'Testimonial.content', 'Testimonial.id', 'Testimonial.auction_id', 'User.username','User.id' ),
								'contain' => array('User', 'Auction' => array('fields' => array('id','price','product_id'),'Product.title')),
								
								)
		);
		if (!empty($testimonial)) {
			$this->set('img', $testimonial['Testimonial']['img']);
			$this->set('content', $testimonial['Testimonial']['content']);
			$this->set('username', $testimonial['User']['username']);
			$this->set('product', $testimonial['Auction']['Product']['title']);
			$this->set('price', $testimonial['Auction']['price']);
			$this->set('user_id', $testimonial['User']['id']);
		}
		$this->layout='ajax_frame';
	}
	/**
	 * Function to add bids to user's account
	 */
	function _bids($user_id = null, $description = null, $credit = 0, $debit = 0, $tid= 0, $xu_type=3){
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
				$bid['Bid']['xu_type']     = $xu_type;
				
				return $bid;
			} else {
				$bid['Bid']['user_id']     = $user_id;
				$bid['Bid']['description'] = $description;
				$bid['Bid']['type']		   = "Bid Reward";
				$bid['Bid']['credit']      = $credit;
				$bid['Bid']['debit']       = $debit;
				$bid['Bid']['code']        = $tid;
				$bid['Bid']['xu_type']     = $xu_type;
				
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
