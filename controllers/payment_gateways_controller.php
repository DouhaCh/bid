<?php
class PaymentGatewaysController extends AppController{

	var $name = 'PaymentGateways';
	var $uses = array('Auction', 'Package', 'Bid', 'Setting', 'Account', 'Referral', 'Coupon', 'Order','Payment', 'Promotion','Coupon','Rbid');
	var $components = array('Cookie');
	var $helpers = array('Dotpay', 'Epayment', 'GoogleCheckout');
	
	
	
	function beforeFilter(){
		parent::beforeFilter();
		if(isset($this->Auth)){
			$this->Auth->allow('nganluong','nganluong_complete','icoin_complete','icoin','icoin_direct','newsms', 'mobivi', 'mobivi_complete', 'returning', 'dotpay_ipn', 'epayment_ipn', 'google_checkout_ipn',
						   'paypal_ipn', 'paypal_pro_ipn', 'secure_pay_ipn',
						   'authorizenet_ipn', 'plimus_ipn', 'test');
		}
	}
	
	function test($amount){
		$this->autoRender = false;
		$this->_bids($this->Auth->user('id'),"Nạp tiền bằng thẻ di động 3000000 VND",$amount,0,"ABC",'Bid Charge - Icoin',1);
	}
	
	/**
	 * Function to calculate hmac
	 */
	function _hmac($data, $key){
		// RFC 2104 HMAC implementation for php.
		// Creates an md5 HMAC.
		// Eliminates the need to install mhash to compute a HMAC
		// Hacked by Lance Rushing
		$b = 64; // byte length for md5
		if (strlen($key) > $b) {
		   $key = pack("H*",md5($key));
		}
		$key  = str_pad($key, $b, chr(0x00));
		$ipad = str_pad('', $b, chr(0x36));
		$opad = str_pad('', $b, chr(0x5c));
		$k_ipad = $key ^ $ipad ;
		$k_opad = $key ^ $opad;
		
		return md5($k_opad  . pack("H*",md5($k_ipad . $data)));
	}

	/**
	 * Function to set an auction's status
	 */
	function _setAuctionStatus($auction_id, $status_id){
		if(!empty($auction_id) && !empty($status_id)){
			$auction['Auction']['id']        = $auction_id;
			$auction['Auction']['status_id'] = $status_id;
			
			return $this->Auction->save($auction, false);
		}else{
			return false;
		}
	}
	
	
	/**
	 * Function to add credit to user's account
	 */
	function _credit($auction_id, $credit = 0, $debit = 0, $user_id = null){
		if(!empty($auction_id)){
		    if(!empty($user)){
			$credit['Credit']['user_id'] = $user_id;
		    }else{
			$credit['Credit']['user_id'] = $this->Auth->user('id');
		    }
		    $credit['Credit']['auction_id'] = $auction_id;
		    $credit['Credit']['credit']     = $credit;
		    $credit['Credit']['debit']      = $debit;
		
		    $this->Auction->Credit->create();
		
		    return $this->Auction->Credit->save($credit);
		}else{
		    return false;
		}
	}

	/**
	 * Function to add bids to user's account
	 */
	function _bids($user_id = null, $description = null, $credit = 0, $debit = 0, $tid= 0, $type='Bid Charge', $xu_type = 3){
		if(!empty($user_id) && !empty($description)){
			if($this->appConfigurations['simpleBids'] == true) {
				$user = $this->User->find('first', array('conditions' => array('User.id' => $user_id), 'contain' => ''));
				if($credit > 0) {
					$user['User']['bid_balance'] += $credit;
				} else {
					$user['User']['bid_balance'] -= $debit;
				}
				
				$this->User->save($user, false);
				
				$bid['Bid']['user_id']     = $user_id;
				$bid['Bid']['description'] = $description;
				$bid['Bid']['credit']      = $credit;
				$bid['Bid']['debit']       = $debit;
				$bid['Bid']['xu_type']     = $xu_type;
				
				return $bid;
			} else {
				// apply promotion filter to discard SMS from promotion
				if($credit > 300){
					$this->Promotion->promote($credit, $user_id);
				}
				
				// for first-time paid
				if($this->Auth->User('charged') == 0){
					$this->Promotion->firstPay($user_id, $credit);
				}
				
				$bid['Bid']['user_id']     = $user_id;
				$bid['Bid']['description'] = $description;
				$bid['Bid']['credit']      = $credit;
				$bid['Bid']['debit']       = $debit;
				$bid['Bid']['code']        = $tid;
			    $bid['Bid']['type']		   = $type;
				$bid['Bid']['xu_type']     = $xu_type;
			    
				$this->Bid->create();
				return $this->Bid->save($bid);
			}
		}else{
			return false;
		}
	}

	/**
	 * Function to add an account entry to user's account
	 */
	function _account($user_id = null, $name, $bids = 0, $price){
		if(!empty($user_id) && !empty($name)){
			$account['Account']['user_id'] = $user_id;
			$account['Account']['name']    = $name;
			$account['Account']['bids']    = $bids;
			$account['Account']['price']   = $price;
			
			$this->Account->create();
			$this->Account->save($account, false);
			$this->log("_account: account record added ($user_id, $name, $bids, $price)", 'payment');
			
			return true;
		}else{
			return false;
		}
	}

	/**
	 * Function to get auction
	 */
	function _getAuction($auction_id, $user_id, $redirect = true){
		$auction = $this->Auction->find('first', array('conditions' => array('Auction.id' => $auction_id)));
		
		if(!empty($auction)){
			// Check if logged user is the winner
			if($auction['Auction']['winner_id'] != $user_id){
				if($redirect){
					$this->Session->setFlash(__('Invalid auction', true));
					$this->redirect(array('controller' => 'auctions', 'action' => 'won'));
				}else{
					return false;
				}
			}
			
			// Check auction status is not paid
			if($auction['Auction']['status_id'] != 1) {
				if($redirect){
					$this->Session->setFlash(__('You have already paid for this auction.', true));
					$this->redirect(array('controller' => 'auctions', 'action' => 'won'));
				}else{
					return false;
				}
			}
			
			// Get the total cost
			$total = 0;
			if(!empty($auction['Product']['fixed'])) {
				$total = $auction['Product']['fixed_price'] + $auction['Product']['delivery_cost'];
			} else {
				$total = $auction['Auction']['price'] + $auction['Product']['delivery_cost'];
			}
			
			// Check credit
			if(Configure::read('App.credits.active')){
				$credits  = $this->Auction->Credit->balance($user_id, Configure::read('App.credits.expiry'));
				$original = $total;
				$total    = $total - $credits;
		
				if($total < 0) {
					$total = 0;
					$auction['Credit']['debit'] = $original;
				} else {
					$auction['Credit']['debit'] = $credits;
				}
			}
			
			$auction['Auction']['total'] = $total;
		}
		
		return $auction;
	}

	/**
	 * Function to get package
	 */
	function _getPackage($package_id, $user_id = null){
		if(!empty($package_id)){
			// Set the user id in session for coupon
			// in package's afterFind()
			if(!empty($user_id)){
	
				// better to put it on PaymentGateway array than
				// Auth.User.id since it will open security hole
				$this->Session->write('PaymentGateway.user_id', $user_id);
	
				// Check validity of user's coupon
				if(Configure::read('App.coupons')){
					if($coupon = Cache::read('coupon_user_'.$user_id)){
						$coupon = $this->Coupon->findByCode(strtoupper($coupon['Coupon']['code']));
					}
				}
			}
	
			return $this->Package->find('first', array('conditions' => array('Package.id' => $package_id)));
		}else{
		    return false;
		}
	}

	/**
	 * Function to check if the addressed already completed
	 */
	function _isAddressCompleted($user_id = null){
		if(empty($user_id)){
		    $user_id = $this->Auth->user('id');
		}
		
		return $this->Auction->Winner->Address->isCompleted($user_id);
	}

	/**
	* Check if this is user first won auction
	*/
	function _firstWin($user_id = null, $auction_id) {
		if(empty($user_id)){
		    $user_id = $this->Auth->user('id');
		}
		
		$won = $this->Auction->find('count', array('conditions' => array('Auction.winner_id' => $user_id)));
		if($won == 1) {
			// Give 'em first winning bonus bid
			$setting = $this->Setting->get('free_won_auction_bids');
			if((is_numeric($setting)) && $setting > 0) {
				$credit = $credit;
			} elseif(substr($setting, -1) == '%' && is_numeric(substr($setting, 0, -1))) {
				$bids  = $this->find('all', array('conditions' => array('Bid.user_id' => $user_id, 'Bid.auction_id' => $auction_id), 'fields' => "SUM(Bid.debit) as 'bids'"));
				if(empty($bids[0][0]['bids'])) {
					$bids[0][0]['bids'] = 0;
				}
	
				$credit = $bids[0][0]['bids'] * (substr($setting, 0, -1) / 100);
				$credit = ceil($credit);
			}
	
			if(!empty($credit) && $credit > 0){
				$description = __('Free bids given for winning your first auction.', true);
				$this->_bids($user_id, $description, $credit, 0);
			}

			return true;
		}else{
			return false;
		}
	}

	/**
	* Check if this is user first bid package purchase
	*/
	function _checkFirstPurchase($user_id = null, $bids = null){
		if(!empty($user_id)){
		    $purchasedBefore = $this->User->Account->find('first', array('conditions' => array('Account.user_id' => $user_id, 'Account.bids >' => 0)));
		    if(empty($purchasedBefore)) {
			// Get the setting
			$setting = $this->Setting->get('free_bid_packages_bids');
		
					// If setting for free bids is not 0
					if((is_numeric($setting)) && $setting > 0) {
						$credit = $setting;
					} elseif(substr($setting, -1) == '%' && is_numeric(substr($setting, 0, -1))) {
						$credit = $bids * (substr($setting, 0, -1) / 100);
					}
		
					$description = __('Free bids given for purchasing bids for the first time.', true);
					$this->_bids($user_id, $description, $credit, 0);
		    }else{
			return false;
		    }
		}else{
		    return false;
		}
	}
	
	/**
	* Check if user referred by another user
	*/
	function _checkReferral($user_id = null,$credit=0){
	// Now we check if this user was referred so we can give the free bids away
		$referral = $this->Referral->find('first', array('conditions' => array('user_id' => $user_id, 'confirmed <' => 2)));
	
		if(!empty($referral)) {
			// Get the setting for free bids
			//$setting = $this->Setting->get('free_referral_bids');
	
			$description = __('Bid thưởng do người bạn giới thiệu nạp XU:', true).' '.$referral['User']['username'];
			$this->_bids($referral['Referral']['referrer_id'], $description, $setting, $credit);
	
			// Finally set the referral as confirmed
			$referral['Referral']['confirmed'] = 2;
			unset($referral['Referral']['modified']);
	
			// Save the referral
			$this->User->Referral->save($referral);
		}
	}

	/**
	 * Check reward point whether it's on or off and add points into
	 * user account if necessary
	 */
	function _checkRewardPoints($package_id = null, $user_id = null){
		if($package_id && $user_id){
			// Adding points
			if(Configure::read('App.rewardsPoint')){

				// Set the user id in session for coupon
				// in package's afterFind()
				if(!empty($user_id)){

					// better to put it on PaymentGateway array than
					// Auth.User.id since it will open security hole
					$this->Session->write('PaymentGateway.user_id', $user_id);

					// Check validity of user's coupon
					if(Configure::read('App.coupons')){
						if($coupon = Cache::read('coupon_user_'.$user_id)){
							$coupon = $this->Coupon->findByCode(strtoupper($coupon['Coupon']['code']));
						}
					}
				}

				$package = $this->Package->findById($package_id);

				if(!empty($package['PackagePoint']['points'])){
					$point = $this->User->Point->findByUserId($user_id);
					if(!empty($point)){
						$point['Point']['points'] += $package['PackagePoint']['points'];
					}else{
						$point['Point']['user_id'] = $user_id;
						$point['Point']['points']  = $package['PackagePoint']['points'];

						$this->User->Point->create();
					}

					$this->User->Point->save($point);
				}
			}
		}
	}

	/**
	 * Function for returning from payment gateway
	 */
	function returning($model){
		if(!empty($model)){
			switch($model){
				case 'auction':
					$this->Session->setFlash(__('Your payment was successful.  We will notify you when your item has been shipped.', true));
					$this->redirect(array('controller' => 'auctions', 'action' => 'won'));
					break;

				case 'package':
					$this->Session->setFlash(__('You payment was successful and your bids are available for you use.  If your bids are not available yet, please allow a couple of minutes for them to become available.', true), 'default', array('class'=>'success'));
					$this->redirect(array('controller' => 'accounts', 'action' => 'index'));
					break;
			}
		}else{
			die('I hate you');
		}
	}

	/**
	 * Function for cancelling from payment gateway
	 */
	function cancel($model){
		if(!empty($model)){
			switch($model){
				case 'auction':
					$this->Session->setFlash(__('Your payment was canceled.', true));
					$this->redirect(array('controller' => 'auctions', 'action' => 'won'));
					break;

				case 'package':
					$this->Session->setFlash(__('Your payment was canceled.', true));
					$this->redirect(array('controller' => 'accounts', 'action' => 'index'));
					break;
			}
		}else{
			die('I hate you');
		}
	}

	/**
	* Function for sending notification after auction payment
	*/
	function _sendAuctionNotification($auction, $user_id = null){
		// Get users
		$user = $this->User->findById($user_id);
		
		$data['template'] = 'payment_gateways/auction_pay';
		$data['layout']   = 'default';
		
		// Send to both user and admin
		$data['to'] 	  = array($user['User']['email'], $this->appConfigurations['email']);
		
		$data['subject']  = __('Won Auction Payment', true);
		$data['User']	  = $user['User'];
		
		$this->set('auction', $auction);
		$this->set('user', $data);
		
		if($this->_sendEmail($data)){
		    return true;
		}else{
		    return false;
		}
	}
	
	/**
	* Function for sending notification after package purchase
	*/
	function _sendPackageNotification($package, $user_id = null){
		// Get users
		$user = $this->User->findById($user_id);
		
		$data['template'] = 'payment_gateways/package_buy';
		$data['layout']   = 'default';
		
		// Send to both user and admin
		$data['to'] 	  = array($user['User']['email'], $this->appConfigurations['email']);
		
		$data['subject']  = __('Bid Package Purchased', true);
		$data['User']	  = $user['User'];
		
		$this->set('data', $data);
		$this->set('package', $package);
		
		if($this->_sendEmail($data)){
		    return true;
		}else{
		    return false;
		}
	}
	
    /**
     * Google Checkout function
     * In case the XML API contains multiple open tags
     * with the same value, then invoke this function and
     * perform a foreach on the resultant array.
     * This takes care of cases when there is only one unique tag
     * or multiple tags.Examples of this are "anonymous-address",
     * "merchant-code-string" from the merchant-calculations-callback API
     */
    function get_arr_result($child_node) {
        $result = array();
        if(isset($child_node)) {
            if(is_associative_array($child_node)) {
                $result[] = $child_node;
            } else {
                foreach($child_node as $curr_node){
                    $result[] = $curr_node;
                }
            }
        }
        return $result;
    }

    /* Returns true if a given variable represents an associative array */
    function is_associative_array( $var ) {
        return is_array( $var ) && !is_numeric( implode( '', array_keys( $var ) ) );
    }

    /**
     * Dotpay payment gateway
     * http://www.dotpay.eu
     */
 
    function _getResponseDescription($responseCode) {

	    switch ($responseCode) {
	        case "0" : $result = "Giao dich thanh cong"; break;
	        case "1" : $result = "Ngan hang tu choi thanh toan: the/tai khoan bi khoa"; break;
	        case "2" : $result = "Loi so 2"; break;
	        case "3" : $result = "The het han"; break;
	        case "4" : $result = "Qua so lan giao dich cho phep. (Sai OTP, qua han muc trong ngay)"; break;
	        case "5" : $result = "Khong co tra loi tu Ngan hang"; break;
	        case "6" : $result = "Loi giao tiep voi Ngan hang"; break;
	        case "7" : $result = "Tai khoan khong du tien"; break;
	        case "8" : $result = "Loi du lieu truyen"; break;
	        case "9" : $result = "Kieu giao dich khong duoc ho tro"; break;
	        default  : $result = "Loi khong xac dinh"; 
	    }
	    return $result;
	}
	
	
	
	//  -----------------------------------------------------------------------------
	
	// If input is null, returns string "No Value Returned", else returns input
	function _null2unknown($data) {
	    if ($data == "" ||$data == null) {
	        return "No Value Returned";
	    } else {
	        return $data;
	    }
	} 
	
	##############################################################################
	################################ iCoin #######################################
	##############################################################################
	    
	function icoin_topup() {
		App::import('model','Package');
		$pkg = new Package();
		$package = $pkg->find('first',array('conditions'=>array('code'=>$this->params['pass'][0])));
		
				
		// Define Constants
		// ----------------
		// This is secret for encoding the MD5 hash
		// This secret will vary from merchant to merchant
		// To not create a secure hash, let SECURE_SECRET be an empty string - ""
		// $SECURE_SECRET = "secure-hash-secret";
		$SECURE_SECRET = "6xfNBrWQRIB9J0nCGpt8bdC4yOg=";
		// add the start of the vpcURL querystring parameters
		$vpcURL = "https://icoin.vn/paymentRequest?";
		//$access_code = "8FkwXk3CXh";
		$strURLReturn = "http://1bid.vn/payment_gateways/icoin_topup_complete";
        $today = date("His");
        $timestamp = date("Y-m-d:H:i:s");
        $transaction_id = $timestamp.$this->Auth->user('id').$this->params['pass'][0];
        
		// Remove the Virtual Payment Client URL from the parameter hash as we 
		// do not want to send these fields to the Virtual Payment Client.
		//unset($_POST["virtualPaymentClientURL"]); 
		//unset($_POST["SubButL"]);
		
		// Create the request to the Virtual Payment Client which is a URL encoded GET
		// request. Since we are looping through all the data we may as well sort it in
		// case we want to create a secure hash and add it to the VPC data if the
		// merchant secret has been provided.
		$md5HashData = $SECURE_SECRET;
		$price = $package['Package']['price'];
		//check coupon
		$this->Session->del($this->name.'.coupon');	
			      		$coupon = $this->Coupon->getCouponByUser($this->Auth->User('id'));
			      		if (isset($coupon)) {
			      			switch ($coupon['Coupon']['type']) {
			      				case 0:
			      					$session = array(
			      						'coupon_id' => $coupon['Coupon']['id'],
			      						'userscoupon_id' => $coupon['UsersCoupon']['id'],
			      						'multi' => $coupon['multi'],
			      						'type' =>'Reward Coupon',
			      						'amount' => $coupon['Coupon']['amount'],
			      						'code' => $coupon['Coupon']['code']
			      					);
			      					$this->Session->write($this->name.'.coupon', $session);
			      					break;
			      				case 1:
			      					$session = array(
			      						'coupon_id' => $coupon['Coupon']['id'],
			      						'userscoupon_id' => $coupon['UsersCoupon']['id'],
			      						'multi' => $coupon['multi'],
			      						'type' =>'Discount Coupon',
			      						'amount' => $coupon['Coupon']['amount'],
			      						'code' => $coupon['Coupon']['code']
			      					
			      					);
			      					$this->Session->write($this->name.'.coupon', $session);
			      					$price = $coupon['Coupon']['amount'] * $package['Package']['price'];
			      					
			      					break;
			      			}
			      		}			
			      			
		$data= array(
			//'accesscode' => $access_code,
			'callback' => $strURLReturn,
			'merchant_key' => "dynabyte1bid",
			'timestamp' => $timestamp,
			'order_code' => $this->params['pass'][0],
			'amount' => $price,
			'extend_1' => $package['Package']['name'],
			'extend_2' => $this->Auth->user('id'),
			'extend_3' => $this->Auth->user('email')
		);
		ksort ($data);
		// set a parameter to show the first pair in the URL
		$appendAmp = 0;
		
		foreach($data as $key => $value) {
		
		    // create the md5 input and URL leaving out any fields that have no value
		    if (strlen($value) > 0) {
		        
		        // this ensures the first paramter of the URL is preceded by the '?' char
		        if ($appendAmp == 0) {
		            $vpcURL .= urlencode($key) . '=' . urlencode($value);
		            $appendAmp = 1;
		        } else {
		            $vpcURL .= '&' . urlencode($key) . "=" . urlencode($value);
		        }
		        $md5HashData .= $value;
		    }
		}
		//echo "<br>".$md5HashData;
		
		// Create the secure hash and append it to the Virtual Payment Client Data if
		// the merchant secret has been provided.
		if (strlen($SECURE_SECRET) > 0) {
		    $vpcURL .= "&signature=" . strtoupper(md5($md5HashData));
		}
		
		// FINISH TRANSACTION - Redirect the customers using the Digital Order
		// ===================================================================
		header("Location: ".$vpcURL);
				
	}
	
	function icoin_topup_complete() {
				
		// Define Constants
		// ----------------
		// This is secret for encoding the MD5 hash
		// This secret will vary from merchant to merchant
		// To not create a secure hash, let SECURE_SECRET be an empty string - ""
		// $SECURE_SECRET = "secure-hash-secret";
		$SECURE_SECRET = "6xfNBrWQRIB9J0nCGpt8bdC4yOg=";
		
		// If there has been a merchant secret set then sort and loop through all the
		// data in the Virtual Payment Client response. While we have the data, we can
		// append all the fields that contain values (except the secure hash) so that
		// we can create a hash and validate it against the secure hash in the Virtual
		// Payment Client response.
		
		// NOTE: If the vpc_TxnResponseCode in not a single character then
		// there was a Virtual Payment Client error and we cannot accurately validate
		// the incoming data from the secure hash. */
		
		// get and remove the vpc_TxnResponseCode code from the response fields as we
		// do not want to include this field in the hash calculation
		$vpc_Txn_Secure_Hash = $_GET["signature"];
		unset($_GET["signature"]);
		
		// set a flag to indicate if hash has been validated
		$errorExists = false;
		if (strlen($SECURE_SECRET) > 0 && $_GET["status"] != "No Value Returned") {
		
		    $md5HashData = $SECURE_SECRET;
		
		    // sort all the incoming vpc response fields and leave out any with no value
			ksort($_GET);
		    foreach($_GET as $key => $value) {
		        if ($key != "signature" or strlen($value) > 0) {
		            $md5HashData .= $value;
		        }
		    }
			
			//echo strtoupper(md5($md5HashData));
		    // Validate the Secure Hash (remember MD5 hashes are not case sensitive)
			// This is just one way of displaying the result of checking the hash.
			// In production, you would work out your own way of presenting the result.
			// The hash check is all about detecting if the data has changed in transit.
		    if (strtoupper($vpc_Txn_Secure_Hash) == strtoupper(md5($md5HashData))) {
		        // Secure Hash validation succeeded, add a data field to be displayed
		        // later.
		        $hashValidated = "<FONT color='#00AA00'><strong>CORRECT</strong></FONT>";
		    } else {
		        // Secure Hash validation failed, add a data field to be displayed
		        // later.
		        $hashValidated = "<FONT color='#FF0066'><strong>INVALID HASH</strong></FONT>";
		        $errorExists = true;
		    }
		} else {
		    // Secure Hash was not validated, add a data field to be displayed later.
		    $hashValidated = "<FONT color='orange'><strong>Not Calculated - No 'SECURE_SECRET' present.</strong></FONT>";
		}
		$this->set('hashValidated',$hashValidated);
		
		// Define Variables
		// ----------------
		
			    $data =array('amount'=> $_GET['amount'],
			    	'package' => $_GET['extend_1'],
					'user_id' => $_GET['extend_2'],
					'email' => $_GET['extend_3'],
					'status' => $_GET['status'],
			    	'order_code' => $_GET['order_code']
					);
				App::import('model','Package');
				$pkg = new Package();
				$length = strlen($data['order_code']);
				$pos = strripos($data['order_code'],"bp");
				$order_code = substr($data['order_code'],$pos,($length - $pos));
				$package = $pkg->find('first',array('conditions'=>array('code'=>$order_code)));
			if ($data['amount']>0 && $data['status']==0){
				
		      		//$bid = floor($data['amount']/15);
		      		if (!$this->Bid->find('count',array('conditions'=>array('code'=>$data['tid'])))) {
			      		
		      				    			
			      		$this->_bids($data['user_id'],"Nạp tiền qua tài khoản iCoin".$data['amount']." VND",$package['Package']['bids'],0,$data['order_code'],'Bid Charge - Icoin',1);
		    		 	
			      		$str = $data['status'].": ".$data['email']." ".$data['order_code'];
			      		$this->Payment->logPayment($data['user_id'],$package['Package']['code'],'icoin',$data['amount'],$str,$package['Package']['bids'],1);
			      		
			      		//check if using coupon:
		      			if($this->Session->check($this->name.'.coupon')) {
		      				if ($session['type'] == 'Reward Coupon') {
			      				$session = $this->Session->read($this->name.'.coupon');	
								$reward_xu = $session['amount'] * $package['Package']['bids'];
				      			$this->Rbid->insertCoupon($this->Auth->User('id'), $reward_xu, $session['code']);
				      			$expired = date("Y-m-d H:i:s");
								if ($session['multi'] == 0) {
				      						$this->Coupon->id = $session['coupon_id'];
				      						$this->Coupon->saveField('active', 2);
				      						$this->Coupon->saveField('expired', $expired);
				      					}
				      			$this->Coupon->UsersCoupon->id = $session['usersCoupon_id'];
				      			$this->Coupon->UsersCoupon->saveField('expired', $expired);
				      			$this->Session->del($this->name.'.coupon');	
		      				} elseif ($session['type'] == 'Discount Coupon') {
		      						$expired = date("Y-m-d H:i:s");
			      					if ($coupon['multi'] == 0) {
			      						$this->Coupon->id = $coupon['Coupon']['id'];
			      						$this->Coupon->saveField('active', 2);
			      						$this->Coupon->saveField('expired', $expired);
			      					}
			      					$this->Coupon->UsersCoupon->id = $coupon['UsersCoupon']['id'];
			      					$this->Coupon->UsersCoupon->saveField('expired', $expired);
		      				}
						}	
			      		
		    			$this->_checkReferral($data['user_id'],floor($xu/10));
		      		}
			} else {
				$this->Session->setFlash("Có lỗi phát sinh trong quá trình thanh toán, bạn hãy thử lại lần nữa. Nếu vẫn không được xin liên hệ với Tổ trợ giúp của 1bid.vn hoặc trực tiếp với trợ giúp của hệ thống Icoin");
			}
		$this->redirect('/users/update');
		// Define Variables
		// ----------------
		// Extract the available receipt fields from the VPC Response
		// If not present then let the value be equal to 'No Value Returned'
		
		// Standard Receipt Data
		// *******************
		// END OF MAIN PROGRAM
		// *******************
		
		// FINISH TRANSACTION - Process the VPC Response Data
		// =====================================================
		// For the purposes of demonstration, we simply display the Result fields on a
		// web page.
		
		// Show 'Error' in title if an error condition
		$errorTxt = "";
		
		// Show this page as an error page if vpc_TxnResponseCode equals '0'
		if ($txnResponseCode != "0" || $txnResponseCode == "No Value Returned" || $errorExists) {
		    $errorTxt = "Error ";
		}
		    
		// This is the display title for 'Receipt' page 
		$title = $_GET["Title"];
		
		// The URL link for the receipt to do another transaction.
		// Note: This is ONLY used for this example and is not required for 
		// production code. You would hard code your own URL into your application
		// to allow customers to try another transaction.
		//TK//$againLink = URLDecode($_GET["AgainLink"]);
	}
		
	
	
	
function icoin_direct(){
	
		if (!empty($this->data)) {
			App::import('vendor','icoin_direct');
			
			
			$data['user']=$this->Auth->user('username');
			$data['tel'] = "".$this->Auth->user('phone');
			if ($data['tel']=="") {
				$data['tel']="0945111236";
			}
			$data['email'] = $this->Auth->user('email');
			$data['card'] = $this->data['cardno'].":".$this->data['cardtype'];
			//print_r($data);
			
			$soapClient = new VMS_Soap_Client('http://123.30.179.27:8081/webservice/VDCTelcoAPI?wsdl','dynabyte1bid','1bid123',8,'1bid');
			$data2=$soapClient->doCardCharge($data['user'],$data['card'],$data['email'],$data['tel']);  
			//print_r($data);
			if ($data2['status']==1){
				
				if ($data2['DRemainAmount']>0){
					switch ($data2['DRemainAmount']){
						case 10000:
							$xu = 600 / 2;
							break;
						case 20000:
							$xu = 1200 / 2;
							break;
						case 30000:
							$xu = 1800 / 2;
							break;
						case 50000:
							$xu = 3000 / 2;
							break;
						case 100000:
							$xu = 6000 / 2;
							break;
						case 200000:
							$xu = 12500 / 2;
							break;
						case 300000:
							$xu = 20000 / 2;
							break;
						case 500000:
							$xu = 33500 / 2;
							break;
			
					}
		      		//$bid = floor($data['amount']/15);
		      		if (!$this->Bid->find('count',array('conditions'=>array('code'=>$data2['transid'])))) {
			      		/* $ticket = ($data['DRemainAmount']>100000)?4:1;
			      		App::import('model','Lottery');
			      		$lot = new Lottery();
			      		$lot->give($data['user_id'],$ticket,5); */
			      		//print_r($data);
		    			$this->_bids($this->Auth->user('id'),"Nạp tiền bằng thẻ di động ".$data2['DRemainAmount']." VND",$xu,0,$data2['transid'],'Bid Charge - Icoin',1);
		    		 	/*App::import('model','Payment');
			      		$pm = new Payment();*/
			      		$str = $data2['status'].": ".$data['email']." ".$data2['transid'];
			      		$this->Payment->logPayment($this->Auth->user('id'),$data2['DRemainAmount'],'icoin_direct',$data2['DRemainAmount'],$str,$xu,1);
			      		//check if using coupon:
		      			$coupon = $this->Coupon->getCouponByUser($this->Auth->User('id'));
			      		if (isset($coupon)) {
			      			if ($coupon['Coupon']['type'] == 0) {
			      				$reward_xu = $coupon['Coupon']['amount'] * $xu;
				      			$this->Rbid->insertCoupon($this->Auth->User('id'),$reward_xu,$coupon['Coupon']['code']);
				      			$expired = date("Y-m-d H:i:s");
								if ($coupon['Coupon']['multi'] == 0) {
				      						$this->Coupon->id = $coupon['Coupon']['id'];
				      						$this->Coupon->saveField('active', 2);
				      						$this->Coupon->saveField('expired', $expired);
				      					}
				      			$this->Coupon->UsersCoupon->id = $coupon['UsersCoupon']['id'];
				      			$this->Coupon->UsersCoupon->saveField('expired', $expired);
			      			}
			      		}	
			      		
		    			$this->_checkReferral($this->Auth->user('id'),floor($xu/10));
		      		}
				}
				else {
					$this->Session->setFlash("Có lỗi phát sinh trong quá trình thanh toán, bạn hãy thử lại lần nữa. Nếu vẫn không được xin liên hệ với Tổ trợ giúp của 1bid.vn (ErrCode: AmountZero) ");
				}
			
			$soapClient->doLogout();
			
			
			$this->redirect('/users/update');
			} else {
				switch($data2['status']) {
					case -1:
						$error = "Lỗi: Thẻ đã được sử dụng";
						break;
					case -2:
						$error = "Lỗi: Thẻ đã khóa";
						break;
					case -3:
						$error = "Lỗi: Thẻ đã hết hạn sử dụng";
						break;
					case -4:
						$error = "Lỗi: Thẻ chưa được kích hoạt";
						break;
					case -10:
						$error = "Lỗi: Mã thẻ không đúng định dạng";
						break;
					case -0:
						$error = "Lỗi: Lỗi khác";
						break;
					case -99:
						$error = "Lỗi: Lỗi hệ thống nạp bên Mobifone ";
						break;
					case 2:
						$error = "Lỗi: Không login sử dụng các hàm charge";
						break;
					case 9:
						$error = "Lỗi: Sai thông tin partner";
						break;
					case 3:
						$error = "Lỗi: Lỗi hệ thống VDCO";
						break;
					case 4:
						$error = "Lỗi: Thẻ không sử dụng được";
						break;
					case 5:
						$error = "Lỗi: Thực hiện lệnh sai 10 lần liên tiếp.";
						break;
					case 6:
						$error = "Lỗi: Thực hiện lệnh logout không thành công";
						break;
					case 8:
						$error = "Lỗi: Charge thẻ bị lỗi hệ thống lỗi này cần ghi nhận lại để kiểm soát và đối soát (lỗi này ít xảy ra nhưng sẽ để đối soát xem thẻ nạp thành công hay chưa)";
						break;
					case 10:
						$error = "Lỗi: Sai format thông tin email , mobile gửi";
						break;
					case 7:
						$error = "Lỗi: Lỗi hệ thống VMS quá tải tạm thời dừng kênh nạp thẻ VMS";
						break;
					
					
						
				}
				$this->Session->setFlash($error);
			}
		}
	}
    function icoin_complete(){
		// Define Constants
		// ----------------
		// This is secret for encoding the MD5 hash
		// This secret will vary from merchant to merchant
		// To not create a secure hash, let SECURE_SECRET be an empty string - ""
		// $SECURE_SECRET = "secure-hash-secret";
		$SECURE_SECRET = "6xfNBrWQRIB9J0nCGpt8bdC4yOg=";
		
		// If there has been a merchant secret set then sort and loop through all the
		// data in the Virtual Payment Client response. While we have the data, we can
		// append all the fields that contain values (except the secure hash) so that
		// we can create a hash and validate it against the secure hash in the Virtual
		// Payment Client response.
		
		// NOTE: If the vpc_TxnResponseCode in not a single character then
		// there was a Virtual Payment Client error and we cannot accurately validate
		// the incoming data from the secure hash. */
		
		// get and remove the vpc_TxnResponseCode code from the response fields as we
		// do not want to include this field in the hash calculation
		$vpc_Txn_Secure_Hash = $_GET["signature"];
		unset($_GET["signature"]);
		
		// set a flag to indicate if hash has been validated
		$errorExists = false;
		if (strlen($SECURE_SECRET) > 0 && $_GET["status"] != "No Value Returned") {
		
		    $md5HashData = $SECURE_SECRET;
		
		    // sort all the incoming vpc response fields and leave out any with no value
		    ksort($_GET);
		    foreach($_GET as $key => $value) {
		        if ($key != "signature" or strlen($value) > 0) {
		            $md5HashData .= $value;
		        }
		    }
		    
		    // Validate the Secure Hash (remember MD5 hashes are not case sensitive)
			// This is just one way of displaying the result of checking the hash.
			// In production, you would work out your own way of presenting the result.
			// The hash check is all about detecting if the data has changed in transit.
		    if (strtoupper($vpc_Txn_Secure_Hash) == strtoupper(md5($md5HashData))) {
		        // Secure Hash validation succeeded, add a data field to be displayed
		        // later.
		        echo "<FONT color='#00AA00'><strong>CORRECT</strong></FONT>";

				
		    } else {
		        // Secure Hash validation failed, add a data field to be displayed
		        // later.
		        
		        echo "<FONT color='#FF0066'><strong>INVALID HASH</strong></FONT>";
		        $errorExists = true;
		    }
		} else {
		    // Secure Hash was not validated, add a data field to be displayed later.
		    $hashValidated = "<FONT color='orange'><strong>Not Calculated - No 'SECURE_SECRET' present.</strong></FONT>";
		}
		
		$this->set('hashValidated',$hashValidated);
		//http://www.1bid.vn/payment_gateways/icoin_complete?amount=10000&access_code=test_dynabyte1bid_telco_accesscode&username=nhandang&againLink=icoin.vn&status=0&&&&channel=telco_card&signature=528005C1CB2DD4F262C750D88CE9B0F7&channel_type=Mobifone&transaction_id=ICOIN_161624
		//http://www.1bid.vn/payment_gateways/icoin_complete?amount=100000&access_code=20e62d43ef&username=minhnhan%40dynabyte.vn&status=0&callback=http://www.1bid.vn/payment_gateways/icoin_complete&extend_2=minhnhan%40dynabyte.vn&extend_1=32&merchant_key=dynabyte1bid&channel=telco_card&signature=4CA760155E0E375067A8DAB472251530&channel_type=Vinaphone&transaction_id=ICOIN_172556
		// Define Variables
		// ----------------
		
			    $data =array('amount'=> $_GET['amount'],
					'user_id' => $_GET['extend_1'],
					'email' => $_GET['extend_2'],
					'status' => $_GET['status'],
					'tid' => $_GET['transaction_id'],
					);
			if ($data['amount']>0){
					switch ($data['amount']){
						case 10000:
							$xu = 600 / 2;
							break;
						case 20000:
							$xu = 1200 / 2;
							break;
						case 30000:
							$xu = 1800 / 2;
							break;
						case 50000:
							$xu = 3000 / 2;
							break;
						case 100000:
							$xu = 6000 / 2;
							break;
						case 200000:
							$xu = 12500 / 2;
							break;
						case 300000:
							$xu = 20000 / 2;
							break;
						case 500000:
							$xu = 33500 / 2;
							break;
			
					}
		      		//$bid = floor($data['amount']/15);
		      		if (!$this->Bid->find('count',array('conditions'=>array('code'=>$data['tid'])))) {
			      		$ticket = ($data['amount']>100000)?4:1;
			      		App::import('model','Lottery');
			      		$lot = new Lottery();
			      		$lot->give($data['user_id'],$ticket,5);
			      		//print_r($data);
		    			$this->_bids($data['user_id'],"Nạp tiền bằng thẻ di động ".$data['amount']." VND",$xu,0,$data['tid'],'Bid Charge - Icoin',1);
		    		
			      		$str = $data['status'].": ".$data['email']." ".$data['tid'];
			      		$this->Payment->logPayment($data['user_id'],$data['amount'],'icoin',$data['amount'],$str,$xu,1);
			      		//check if using coupon:
		      			if($this->Session->check($this->name.'.coupon')) {
		      				if ($session['type'] == 'Reward Coupon') {
								$session = $this->Session->read($this->name.'.coupon');	
								$reward_xu = $session['amount'] * $xu;
				      			$this->Rbid->insertCoupon($this->Auth->User('id'),$reward_xu,$session['code']);
				      			$expired = date("Y-m-d H:i:s");
								if ($session['multi'] == 0) {
				      						$this->Coupon->id = $session['coupon_id'];
				      						$this->Coupon->saveField('active', 2);
				      						$this->Coupon->saveField('expired', $expired);
				      					}
				      			$this->Coupon->UsersCoupon->id = $session['usersCoupon_id'];
				      			$this->Coupon->UsersCoupon->saveField('expired', $expired);
				      			$this->Session->del($this->name.'.coupon');	
		      				}
						}	
			      		
		    			$this->_checkReferral($data['user_id'],floor($xu/10));
		      		}
			} else {
				$this->Session->setFlash("Có lỗi phát sinh trong quá trình thanh toán, bạn hãy thử lại lần nữa. Nếu vẫn không được xin liên hệ với Tổ trợ giúp của 1bid.vn hoặc trực tiếp với trợ giúp của hệ thống Icoin");
			}
		$this->redirect('/users/update');
		// Extract the available receipt fields from the VPC Response
		// If not present then let the value be equal to 'No Value Returned'
		
		// Standard Receipt Data
		// *******************
		// END OF MAIN PROGRAM
		// *******************
		
		// FINISH TRANSACTION - Process the VPC Response Data
		// =====================================================
		// For the purposes of demonstration, we simply display the Result fields on a
		// web page.
		
		// Show 'Error' in title if an error condition
		$errorTxt = "";
		
		// Show this page as an error page if vpc_TxnResponseCode equals '0'
		if ($txnResponseCode != "0" || $txnResponseCode == "No Value Returned" || $errorExists) {
		    $errorTxt = "Error ";
		}
		    
		// This is the display title for 'Receipt' page 
		
		// The URL link for the receipt to do another transaction.
		// Note: This is ONLY used for this example and is not required for 
		// production code. You would hard code your own URL into your application
		// to allow customers to try another transaction.
		//TK//$againLink = URLDecode($_GET["AgainLink"]);
		$this->set('errorTxt',$errorTxt);
		$this->set('data',$_GET);
    }
    
	function icoin($type='1'){
		if ($type==1) {
			$access_code='20e62d43ef';
		} elseif ($type==2) {
			$access_code='8FkwXk3CXh';
		}
		//check coupon
		$price = 0;
		$this->Session->del($this->name.'.coupon');	
			      		$coupon = $this->Coupon->getCouponByUser($this->Auth->User('id'));
			      		if (isset($coupon)) {
			      			switch ($coupon['Coupon']['type']) {
			      				case 0:
			      					$session = array(
			      						'coupon_id' => $coupon['Coupon']['id'],
			      						'userscoupon_id' => $coupon['UsersCoupon']['id'],
			      						'multi' => $coupon['multi'],
			      						'type' =>'Reward Coupon',
			      						'amount' => $coupon['Coupon']['amount'],
			      						'code' => $coupon['Coupon']['code']
			      					);
			      					$this->Session->write($this->name.'.coupon', $session);
			      					break;
			      				case 1:
			      					$session = array(
			      						'coupon_id' => $coupon['Coupon']['id'],
			      						'userscoupon_id' => $coupon['UsersCoupon']['id'],
			      						'multi' => $coupon['multi'],
			      						'type' =>'Discount Coupon',
			      						'amount' => $coupon['Coupon']['amount'],
			      						'code' => $coupon['Coupon']['code']
			      					
			      					);
			      					$this->Session->write($this->name.'.coupon', $session);
			      					$price = $coupon['Coupon']['amount'] * $package['Package']['price'];
			      					$expired = date("Y-m-d H:i:s");
			      					if ($coupon['multi'] == 0) {
			      						$this->Coupon->id = $coupon['Coupon']['id'];
			      						$this->Coupon->saveField('active', 2);
			      						$this->Coupon->saveField('expired', $expired);
			      					}
			      					$this->Coupon->UsersCoupon->id = $coupon['UsersCoupon']['id'];
			      					$this->Coupon->UsersCoupon->saveField('expired', $expired);
			      					break;
			      			}
			      		}	
		$data= array(
			'accesscode' => $access_code,
			'callback' => 'http://www.1bid.vn/payment_gateways/icoin_complete',
			'merchant_key' => "dynabyte1bid",
			'timestamp' => date("Y-m-d:H:i:s"),
			'transaction_id' => "ICOIN_".date("His"),
			'username'=>$this->Auth->user('email'),
			'extend_1'=>$this->Auth->user('id'),
			'extend_2'=>$this->Auth->user('email')
		);
		ksort($data);
		/*
        $strURL = "https://icoin.vn/topupRequest";
        $strURLReturn = "http://www.1bid.vn/payment_gateways/icoin_complete/";
        $today = date("His");
        $transaction_id = "ICOIN_" . $today;
        $timestamp = date("Y-m-d:H:i:s");
        */
        $SECURE_SECRET = "6xfNBrWQRIB9J0nCGpt8bdC4yOg=";
        $md5HashData = $SECURE_SECRET;
        $appendAmp = 0;
        $vpcURL= "https://icoin.vn/topupRequest?";
		foreach($data as $key => $value) {
		
		    // create the md5 input and URL leaving out any fields that have no value
		    if (strlen($value) > 0) {
		        
		        // this ensures the first paramter of the URL is preceded by the '?' char
		        if ($appendAmp == 0) {
		            $vpcURL .= urlencode($key) . '=' . urlencode($value);
		            $appendAmp = 1;
		        } else {
		            $vpcURL .= '&' . urlencode($key) . "=" . urlencode($value);
		        }
		        $md5HashData .= $value;
		    }
		}
		// the merchant secret has been provided.
		if (strlen($SECURE_SECRET) > 0) {
    		$vpcURL .= "&signature=" . strtoupper(md5($md5HashData));
		}
		// FINISH TRANSACTION - Redirect the customers using the Digital Order
		// ===================================================================
		header("Location: ".$vpcURL);
        
    }
    
    ##############################################################################
	############################ nganluong #######################################
	##############################################################################
        
    function nganluong(){
		
    	App::import('vendor','payments/nganluong');
		App::import('model','Package');
		$pkg = new Package();
		$data = $pkg->find('first',array('conditions'=>array('code'=>$this->params['pass'][0])));
		if (!empty($data)) {
			//check coupon
			$price = $data['Package']['price'];
			$this->Session->del($this->name.'.coupon');	
			      		$coupon = $this->Coupon->getCouponByUser($this->Auth->User('id'));
			      		if (isset($coupon)) {
			      			switch ($coupon['Coupon']['type']) {
			      				case 0:
			      					$session = array(
			      						'coupon_id' => $coupon['Coupon']['id'],
			      						'userscoupon_id' => $coupon['UsersCoupon']['id'],
			      						'multi' => $coupon['multi'],
			      						'type' =>'Reward Coupon',
			      						'amount' => $coupon['Coupon']['amount'],
			      						'code' => $coupon['Coupon']['code']
			      					);
			      					$this->Session->write($this->name.'.coupon', $session);
			      					break;
			      				case 1:
			      					$session = array(
			      						'coupon_id' => $coupon['Coupon']['id'],
			      						'userscoupon_id' => $coupon['UsersCoupon']['id'],
			      						'multi' => $coupon['multi'],
			      						'type' =>'Discount Coupon',
			      						'amount' => $coupon['Coupon']['amount'],
			      						'code' => $coupon['Coupon']['code']
			      					
			      					);
			      					$this->Session->write($this->name.'.coupon', $session);
			      					$price = $coupon['Coupon']['amount'] * $data['Package']['price'];
			      					
			      					break;
			      			}
			      		}	
			$nl = new NL_Checkout();
			$return_url =Configure::read('nganluong.return_url');
			$receiver=Configure::read('nganluong.email');		
			$order_code=$this->params['pass'][0];
			$price = $price;
			$transaction_info=$this->Auth->user('id');		
			$url = $nl->buildCheckoutUrl($return_url, $receiver, $transaction_info, $order_code, $price);
			$this->redirect($url);
		}
    }
   
    
    function nganluong_complete(){
    	if (isset($_GET["order_code"])) {
    		App::import('vendor','payments/nganluong');
    		$nl = new NL_Checkout();
			//L·∫•y th√¥ng tin giao d·ªãch
			$transaction_info=$_GET["transaction_info"];
			//L·∫•y m√£ ƒë∆°n h√†ng 
			$order_code=$_GET["order_code"];
			//L·∫•y t·ªïng s·ªë ti·ªÅn thanh to√°n t·∫°i ng√¢n l∆∞·ª£ng 
			$price=$_GET["price"];
			//L·∫•y m√£ giao d·ªãch thanh to√°n t·∫°i ng√¢n l∆∞·ª£ng
			$payment_id=$_GET["payment_id"];
			//L·∫•y lo·∫°i giao d·ªãch t·∫°i ng√¢n l∆∞·ª£ng (1=thanh to√°n ngay ,2=thanh to√°n t·∫°m gi·ªØ)
			$payment_type=$_GET["payment_type"];
			//L·∫•y th√¥ng tin chi ti·∫øt v·ªÅ l·ªói trong qu√° tr√¨nh giao d·ªãch
			$error_text=$_GET["error_text"];
			//L·∫•y m√£ ki·ªÉm tra t√≠nh h·ª£p l·ªá c·ªßa ƒë·∫ßu v√†o 
			$secure_code=$_GET["secure_code"];
			$check = $nl->verifyPaymentUrl($transaction_info, $order_code, $price, $payment_id, $payment_type, $error_text, $secure_code);
    		
    		if ($check) {
			$bid = $this->Bid->find('count',array('conditions' => array('code' => $payment_id)));
			if ($bid==0){
				{
		    			$package = $this->Package->find('first', array('conditions' => array('code' => $order_code)));
		    			
		    			$ticket = ($package['Package']['bids']>6000)?4:1;
		      			App::import('model','Lottery');
		      			
		      			//$lot = new Lottery();
		      			//$lot->give($transaction_info,$ticket,5);
		      			/*App::import('model','Payment');
		      			$pm = new Payment();*/
		      			$str = $transaction_info." ".$price." ".$payment_type." ".$error_text;
		      			$this->Payment->logPayment($this->Auth->User('id'),$order_code,'nganluong',$price,$str,$package['Package']['bids'],1);
		    			$this->_bids($transaction_info,$package['Package']['name'],$package['Package']['bids'],0,$payment_id,'Bid Charge - NganLuong',1);
		    			
		    			//check if using coupon:
		      			if($this->Session->check($this->name.'.coupon')) {
		      				if ($session['type'] == 'Reward Coupon') {
			      				$session = $this->Session->read($this->name.'.coupon');	
								$reward_xu = $session['amount'] * $package['Package']['bids'];
				      			$this->Rbid->insertCoupon($this->Auth->User('id'),$reward_xu,$session['code']);
				      			$expired = date("Y-m-d H:i:s");
								if ($session['multi'] == 0) {
				      						$this->Coupon->id = $session['coupon_id'];
				      						$this->Coupon->saveField('active', 2);
				      						$this->Coupon->saveField('expired', $expired);
				      					}
				      			$this->Coupon->UsersCoupon->id = $session['usersCoupon_id'];
				      			$this->Coupon->UsersCoupon->saveField('expired', $expired);
				      			$this->Session->del($this->name.'.coupon');	
		      				} elseif ($session['type'] == 'Discount Coupon') {
		      						$expired = date("Y-m-d H:i:s");
			      					if ($coupon['multi'] == 0) {
			      						$this->Coupon->id = $coupon['Coupon']['id'];
			      						$this->Coupon->saveField('active', 2);
			      						$this->Coupon->saveField('expired', $expired);
			      					}
			      					$this->Coupon->UsersCoupon->id = $coupon['UsersCoupon']['id'];
			      					$this->Coupon->UsersCoupon->saveField('expired', $expired);
		      				}
						}	
		    			
		    			
		    			$this->_checkReferral($transaction_info,$package['Package']['bids']/10);
		    			$this->redirect('/users/update');
				}
			}
    		} else {
    			$this->Session->setFlash("Có lỗi phát sinh trong quá trình thanh toán, xin liên hệ với Tổ trợ giúp của 1bid.vn để tìm hiểu thêm chi tiết");
				$this->redirect('/users/update');
    		}
    		
    	} 
    }
    
    ##############################################################################
	################################ vCoin #######################################
	##############################################################################
    
    /*
     * vcoin 
     */
    function vcoin() {
    	//debug($this->data);
	    $url = "http://api.vtcebank.vn:8888/api/card.asmx?WSDL";
		$key = "trung886643";
		$partnerid = '886643';
		$userid = $this->Auth->user('id');
    	$vtcCardSerial = $this->data['cardid'];
		$vtcCardCode = $this->data['cardcode'];
		try {
			$client = new SoapClient($url);
			
			##############################################################################
			#################### Check Card Status #######################################
			##############################################################################
			$xmlCard_status =	
			"<?xml version='1.0' encoding='utf-16'?>
				<CardRequest>
					<Function>CheckCardStatus</Function>
						<CardID>".$vtcCardSerial."</CardID>
						<CardCode></CardCode>
						<Description>".$userid."</Description>
				</CardRequest>";		
			//Ma hoa AES 24bit Post Data
			$CardStatus_Encrypt = $this->Encrypt($xmlCard_status, $key);
			
			//Call API VTC Ebank
			$CardStatus_Encrypt_Params = array('PartnerID' => $partnerid, 'RequestData' => $CardStatus_Encrypt);		
			$resultCard_Status = $client->__soapCall('Request', array('parameters' => $CardStatus_Encrypt_Params));
			
			//Decrypt Ket qua tra ve
			$Decrypt_CardStatus = $this->Decrypt($resultCard_Status -> RequestResult, $key);
			
			$Decrypt_CardStatus = preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $Decrypt_CardStatus);  
			
			$CardStatus_xml = simplexml_load_string($Decrypt_CardStatus);
			
			//Cac gia tri
			$CardStatus 			= $CardStatus_xml->ResponseStatus;
			$DescriptionCardStatus	= $CardStatus_xml->Descripton;
			if ($CardStatus == 0) {
				##############################################################################
				#################### Check Card Value #######################################
				##############################################################################
				$xmlCardValue = 
				"<?xml version='1.0' encoding='utf-16'?>
					<CardRequest>
						<Function>CheckCardValue</Function>
						<CardID>".$vtcCardSerial."</CardID>
						<CardCode>".$vtcCardCode."</CardCode>
						<Description>".$userid."</Description>
					</CardRequest>";
				
				
				$CardValue_Encrypt = $this->Encrypt($xmlCardValue, $key);
					
				$CardValue_Encrypt_Params = array('PartnerID' => $partnerid, 'RequestData' => $CardValue_Encrypt);
				$resultCardValue = $client->__soapCall('Request', array('parameters' => $CardValue_Encrypt_Params));
				
				$Decrypt_CardValue = $this->Decrypt($resultCardValue -> RequestResult, $key);
				
				$Decrypt_CardValue = preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $Decrypt_CardValue);  
				
				$CardValue_xml = simplexml_load_string($Decrypt_CardValue);
				
				$CardValue 				= $CardValue_xml->ResponseStatus;
				$DescriptionCardValue 	= $CardValue_xml->Descripton;
				
				switch ($CardValue) {
					case -1:
						$this->Session->setFlash("Thẻ đã sử dụng.");
						exit;
						break;
					case -2: 
						$this->Session->setFlash("Thẻ đã bị khóa.");
						exit;
						break;
					case -3:					
						$this->Session->setFlash("Thẻ đã hết hạn sử dụng.");
						exit;
						break;
					case -4:
						$this->Session->setFlash("Thẻ chưa kích hoạt.");
						exit;
						break;
					case -10:
						$this->Session->setFlash("Mã thẻ không hợp lệ.");
						exit;
						break;
					case -11:
						$this->Session->setFlash("Mã số bí mật của thẻ không hợp lệ.");
						exit;
						break;				
					case -12:
						$this->Session->setFlash("Thẻ không tồn tại.");
						exit;
						break;
					default:
						break;
				}			
				
				// if > 9999 => valid card
				if ($CardValue > 9999) {
					##############################################################################
					#################### UseCard #######################################
					##############################################################################
					$xmlUseCard = 
					"<?xml version='1.0' encoding='utf-16'?>
						<CardRequest>
							<Function>UseCard</Function>
							<CardID>".$vtcCardSerial."</CardID>
							<CardCode>".$vtcCardCode."</CardCode>
							<Description>".$userid."</Description>
						</CardRequest>";
					
					$UseCard_Encrypt = $this->Encrypt($xmlUseCard, $key);
					$UseCard_Params = array('PartnerID' => $partnerid, 'RequestData' => $UseCard_Encrypt);
					$resultUseCard = $client->__soapCall('Request', array('parameters' => $UseCard_Params));
					
					$Decrypt_UseCard = $this->Decrypt($resultUseCard->RequestResult, $key);
					
					$Decrypt_UseCard = preg_replace('/(<\?xml[^?]+?)utf-16/i', '$1utf-8', $Decrypt_UseCard);  
					
					$UseCard_xml = simplexml_load_string($Decrypt_UseCard);
					
					$UseCard 			= $UseCard_xml->ResponseStatus;
					$DescriptionUseCard = $UseCard_xml->Descripton;
					
					//if ($UseCard == $CardValue) {
						$bid = $this->Bid->find('count',array('conditions' => array('code' => $vtcCardSerial)));
						
						if ($bid==0){
							switch ($UseCard){
								case 10000:
									$xu = 600;
									break;
								case 20000:
									$xu = 1200;
									break;
								case 30000:
									$xu = 1800;
									break;
								case 50000:
									$xu = 3000;
									break;
								case 100000:
									$xu = 6000;
									break;
								case 200000:
									$xu = 12500;
									break;
								case 300000:
									$xu = 20000;
									break;
								case 500000:
									$xu = 33500;
									break;
					
							}
							
		    				$this->_bids($userid,"Vcoin ". $UseCard,$xu,0,$vtcCardSerial,'Bid Charge - Vcoin',1);
		    				$str = "Vcoin ". $vtcCardSerial . $vtcCardCode;
		    				$this->Payment->logPayment($userid,"Vcoin ". $vtcCardSerial,'vcoin',$UseCard,$str,$xu,1);
		    				
			    			//check if using coupon:
			      			$coupon = $this->Coupon->getCouponByUser($this->Auth->User('id'));
				      		if (isset($coupon)) {
				      			if ($coupon['Coupon']['type'] == 0) {
				      				$reward_xu = $coupon['Coupon']['amount'] * $xu;
				      				$this->Rbid->insertCoupon($this->Auth->User('id'),$reward_xu,$coupon['Coupon']['code']);
					      			$expired = date("Y-m-d H:i:s");
									if ($coupon['Coupon']['multi'] == 0) {
					      						$this->Coupon->id = $coupon['Coupon']['id'];
					      						$this->Coupon->saveField('active', 2);
					      						$this->Coupon->saveField('expired', $expired);
					      			}
					      			$this->Coupon->UsersCoupon->id = $coupon['UsersCoupon']['id'];
					      			$this->Coupon->UsersCoupon->saveField('expired', $expired);
				      			}
				      		}	
		    				
							$this->Session->setFlash("Bạn đã nạp tiền thành công. Xin vui lòng ấn cập nhật số XU.");
		    				
						}
					//}
				}
				
			} else {
				switch ($CardStatus) {
					case -1:
						$this->Session->setFlash("Thẻ đã sử dụng.");
						break;
					case -2: 
						$this->Session->setFlash("Thẻ đã bị khóa.");
						break;
					case -3:					
						$this->Session->setFlash("Thẻ đã hết hạn sử dụng.");
						break;
					case -4:
						$this->Session->setFlash("Thẻ chưa kích hoạt.");
						break;
					case -10:
						$this->Session->setFlash("Mã thẻ không hợp lệ.");
						
						break;
					case -11:
						$this->Session->setFlash("Mã số bí mật của thẻ không hợp lệ.");
						break;				
					case -12:
						$this->Session->setFlash("Thẻ không tồn tại.");
						break;
				}			
			}
			$this->redirect('/packages');
			
		}
		catch(Exception $e){ 
			return '-1';
		}
    }
    
	//Ma hoa
	function Encrypt($input, $key_seed)
	{
		$input = trim($input);
		$block = mcrypt_get_block_size('tripledes', 'ecb');
		$len = strlen($input);
		$padding = $block - ($len%$block);
		$input .= str_repeat(chr($padding), $padding);
		// generate a 24 byte key from the md5 of the seed
		$key = substr(md5($key_seed),0,24); 
		$iv_size = mcrypt_get_iv_size(MCRYPT_TRIPLEDES, MCRYPT_MODE_ECB); 
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND); 
		// encrypt 
		$encrypted_data = mcrypt_encrypt(MCRYPT_TRIPLEDES, $key, $input, 
		MCRYPT_MODE_ECB, $iv);
		// clean up output and return base64 encoded 
		return base64_encode($encrypted_data); 	
	}
	
	function Decrypt($input, $key_seed)
	{ 
		$input = base64_decode($input); 
		$key = substr(md5($key_seed),0,24); 
		$text=mcrypt_decrypt(MCRYPT_TRIPLEDES, $key, $input, MCRYPT_MODE_ECB,'12345678'); 
		$block = mcrypt_get_block_size('tripledes', 'ecb'); 
		$packing = ord($text{strlen($text) - 1}); 
		if($packing and ($packing < $block))
		{ 
			for($P = strlen($text) - 1; $P >= strlen($text) - $packing; $P--)
			{ 
			 	if(ord($text{$P}) != $packing)
				 $packing = 0;			 
			} 
		}	 
		$text = substr($text,0,strlen($text) - $packing); 
		return $text; 
	} 

    ##############################################################################
	################################## SMS #######################################
	##############################################################################
	/*
     * receive sms
     * GET 
     * /newsms?from=%2B84916781144&to=8368&smsc=VINA&smsid=2074540&time=2008-01-02+21%3A06%3A51&content=1B+blitzer 
     * HTTP/1.1
     */
    function newsms() {
    	
    	//get GET parameters
    	$from = urldecode($_GET["from"]);
    	$to = $_GET["to"];
    	$smsc = $_GET["smsc"];
    	$smsid = $_GET["smsid"];
    	$time = urldecode($_GET["time"]);
    	$content = urldecode($_GET["content"]);
		
    	//check if form EVN -> drop
    	if(strcasecmp($smsc,"evn") == 0) {
    		exit;
    	}
    	
    	//parsing sms content
    	$code = substr($content,0,2);
    	$username = substr($content,3);
    	
    	//check ip VMG server
    	if ($_SERVER['REMOTE_ADDR']=='123.30.23.84'){
	    	if (strcasecmp($code,"xu") == 0) {
	    		$user = $this->User->find('first',
	    						   array( 'conditions' => array('User.username' => $username),
	    						   		  'fields' => array('User.id'),
										  'contain' => false
	    						   )
	    		);			
	    		$transID = $smsc.$smsid;
	    		$day = substr($time,0,10);
	    		$daylimit = $smsc." ".$from." ".$day;
	    		
	    		if (isset($user)) {
	    			$isExist = $this->Bid->find('count',array('conditions'=>array('code'=>$transID)));
	    			$isLimit = $this->Bid->find('count',array('conditions'=>array('description'=>$daylimit)));
	    			
	    			if ($isExist == 0) {	
	    				if ($isLimit < 10) { 
		    				/*App::import('model','Payment');
					      	$pm = new Payment();*/
					      	$str = $from." ".$to." ".$smsc." ".$smsid." ".$time." ".$content;
					      	
					      	//send mt to VMG
					      	///sendsms?vaspid=NHADAT24H&password=***&smscid=strSMSC&smsid=iSmscId&from=strServiceNumber
					      	//&to=strToNumber&bill=iBill&smstype=iType&content=strContent&extcontent=strExtContent&serviceid=strServiceID
					      	$passwordMT = urlencode("Bid123*");
					      	$toMT = urlencode($_GET['from']);
					      	$contentMT = urlencode("1bid.vn:Ban da nap XU thanh cong vao tai khoan ". $username ." qua dich vu SMS. Xin cam on!");
					      	$contentMT = str_ireplace("%20","+", $contentMT);
					      	
					      	$server = "http://billing.8x68.com:8068/sendsms?";
					      	$para = "vaspid=BID&password=". $passwordMT ."&smscid=". $smsc ."&smsid=". $smsid ."&from=". $to ."&to=". $toMT ."&bill=1&smstype=0&content=". $contentMT ."&extcontent=&serviceid=XU";
				
					      	do {
					      		$results = file_get_contents($server.$para);
					      		if (results != false) {
					      			$this->_bids($user['User']['id'],$daylimit,300,0,$transID,"Bid Charge-SMS",1);
					      			$this->Payment->logPayment($user['User']['id'],$transID,'SMS',15000,$str,300,1);
					      			break;
					      		}
					      	} while (0);   
					      	//add XU for user
					      	/*if (results != false) {
					      		$this->_bids($user['User']['id'],$smsc."-SMS",400,0,$transID,"Bid Charge-SMS");
					      		$this->Payment->logPayment($user['User']['id'],$transID,'SMS',15000,$str);
					      		
					      	}*/
	    				} else {
	    					//send mt to VMG
					      	///sendsms?vaspid=NHADAT24H&password=***&smscid=strSMSC&smsid=iSmscId&from=strServiceNumber
					      	//&to=strToNumber&bill=iBill&smstype=iType&content=strContent&extcontent=strExtContent&serviceid=strServiceID
					      	$passwordMT = urlencode("Bid123*");
					      	$toMT = urlencode($_GET['from']);
					      	$contentMT = urlencode("1bid.vn:Ban da vuot qua gioi han qua 10 tin nhan trong 1 ngay voi so thue bao nay. Xin vui long nap XU tu so thue bao khac.");			      	
					      	$contentMT = str_ireplace("%20","+", $contentMT);
					      	$server = "http://billing.8x68.com:8068/sendsms?";
					      	$para = "vaspid=BID&password=". $passwordMT ."&smscid=". $smsc ."&smsid=". $smsid ."&from=". $to ."&to=". $toMT ."&bill=1&smstype=0&content=". $contentMT ."&extcontent=&serviceid=XU";
					      	do {
					      		$results = file_get_contents($server.$para);
					      		if (results != false) {
					      			
					      			break;
					      		}
					      	} while (0);  
				      	
	    				}
	    			}
	    		} else {
	    				//send mt to VMG
				      	///sendsms?vaspid=NHADAT24H&password=***&smscid=strSMSC&smsid=iSmscId&from=strServiceNumber
				      	//&to=strToNumber&bill=iBill&smstype=iType&content=strContent&extcontent=strExtContent&serviceid=strServiceID
				      	$passwordMT = urlencode("Bid123*");
				      	$toMT = urlencode($_GET['from']);
				      	$contentMT = urlencode("1bid.vn:Nap XU khong thanh cong. Vui long kiem tra lai ten tai khoan hoac lien he voi 1bid de duoc tro giup.");			      	
				      	$contentMT = str_ireplace("%20","+", $contentMT);
				      	$server = "http://billing.8x68.com:8068/sendsms?";
				      	$para = "vaspid=BID&password=". $passwordMT ."&smscid=". $smsc ."&smsid=". $smsid ."&from=". $to ."&to=". $toMT ."&bill=1&smstype=0&content=". $contentMT ."&extcontent=&serviceid=XU";
				      	do {
				      		$results = file_get_contents($server.$para);
				      		if (results != false) {
				      			
				      			break;
				      		}
				      	} while (0);  
				      	
	    		}
	    	} else {
	    		//send mt to VMG
				      	///sendsms?vaspid=NHADAT24H&password=***&smscid=strSMSC&smsid=iSmscId&from=strServiceNumber
				      	//&to=strToNumber&bill=iBill&smstype=iType&content=strContent&extcontent=strExtContent&serviceid=strServiceID
				      	$passwordMT = urlencode("Bid123*");
				      	$toMT = urlencode($_GET['from']);
				      	$contentMT = urlencode("1bid.vn:Nap XU khong thanh cong. Vui long kiem tra lai cu phap hoac lien he voi 1bid de duoc tro giup.");			      	
				      	$contentMT = str_ireplace("%20","+", $contentMT);
				      	$server = "http://billing.8x68.com:8068/sendsms?";
				      	$para = "vaspid=BID&password=". $passwordMT ."&smscid=". $smsc ."&smsid=". $smsid ."&from=". $to ."&to=". $toMT ."&bill=1&smstype=0&content=". $contentMT ."&extcontent=&serviceid=XU";
				      	do {
				      		$results = file_get_contents($server.$para);
				      		if (results != false) {
				      			
				      			break;
				      		}
				      	} while (0);  
	    	} 
    	
    	}
    }
    

    ##############################################################################
	################################ mobivi ######################################
	##############################################################################    
	/**
     * mobivi
     */
    function mobivi(){
    
    	App::import('vendor','payments/clsmobivicheckout');
		App::import('model','Package');
		
		$pkg = new Package();
		$discount=0.8;
		$data = $pkg->find('first',array('conditions'=>array('code'=>$this->params['pass'][0])));
		if (!empty($data)){
			// Create Mobivi Checkout object			
			$checkout_url = Configure::read('mobivi.checkout_url');
			$mbv = new MobiviCheckout($checkout_url);
			// Load merchant private key
			$key_path = Configure::read('mobivi.private_key');
			$result = $mbv->read_private_key($key_path);
			if ($result){
				$this->Session->setFlash("C√É¬≥ l√°¬ª‚Äîi ph√É¬°t sinh trong qu√É¬° tr√É¬¨nh th√°¬ª¬±c thi, xin li√É¬™n h√°¬ª‚Ä° ngay v√°¬ª‚Ä∫i 1bid.vn √Ñ‚Äò√°¬ª∆í √Ñ‚Äò√Ü¬∞√°¬ª¬£c tr√°¬ª¬£ gi√É¬∫p.");
			}
			// Get info
			$order_code=$this->params['pass'][0];
			$product_price = $data['Package']['price'];
			
			//check coupon
			$this->Session->del($this->name.'.coupon');	
			      		$coupon = $this->Coupon->getCouponByUser($this->Auth->User('id'));
			      		if (isset($coupon)) {
			      			switch ($coupon['Coupon']['type']) {
			      				case 0:
			      					$session = array(
			      						'coupon_id' => $coupon['Coupon']['id'],
			      						'userscoupon_id' => $coupon['UsersCoupon']['id'],
			      						'multi' => $coupon['multi'],
			      						'type' =>'Reward Coupon',
			      						'amount' => $coupon['Coupon']['amount'],
			      						'code' => $coupon['Coupon']['code']
			      					);
			      					$this->Session->write($this->name.'.coupon', $session);
			      					break;
			      				case 1:
			      					$session = array(
			      						'coupon_id' => $coupon['Coupon']['id'],
			      						'userscoupon_id' => $coupon['UsersCoupon']['id'],
			      						'multi' => $coupon['multi'],
			      						'type' =>'Discount Coupon',
			      						'amount' => $coupon['Coupon']['amount'],
			      						'code' => $coupon['Coupon']['code']
			      					
			      					);
			      					$this->Session->write($this->name.'.coupon', $session);
			      					$product_price = $coupon['Coupon']['amount'] * $data['Package']['price'];
			      					
			      					break;
			      			}
			      		}
			//ON DISCOUNT
			//$product_price = $product_price * $discount;
			
			$product_quantity = 1;
			$transaction_info=$this->Auth->user('id');
			// Create Mobivi Checkout Request object
			$mbvRequest = new MobiviCheckoutRequest();
			// Generate unique serial id (not use)
			$mbvRequest->SerialID = uniqid();
			// Invoice's id
			$mbvRequest->InvoiceID      = rand();
			// Invoice's amount
			$mbvRequest->Amount         = $product_price;
			// Invoice's tax (not use)
			$mbvRequest->Tax            = 0;
			// Invoice's description
			$mbvRequest->Description    = htmlentities($order_code, ENT_QUOTES, 'UTF-8');
			// Merchant's name
			$mbvRequest->From           = "1bid.vn";
			// Customer's name
			$mbvRequest->To             = htmlentities($transaction_info, ENT_QUOTES, 'UTF-8');
			// Done URL
			$mbvRequest->DoneURL        = Configure::read('mobivi.done_url');
			// Request URL
			$mbvRequest->ReturnURL      = Configure::read('mobivi.return_url');
			// Add items			
			$mbvRequest->add_invoice_item(htmlentities($order_code, ENT_QUOTES, 'UTF-8'), $product_price, $product_quantity, false, htmlentities($order_code, ENT_QUOTES, 'UTF-8'), "");
			//
			$merchant = Configure::read('mobivi.merchant_ewallet');
			$response = $mbv->send($merchant, $mbvRequest, $redirect_url);
			$mbv->dispose();
			// Check result and redirect to checkout
			if ($response) {
				header("Location: $redirect_url");
				return true;
			} else {
				$this->Session->setFlash($redirect_url);
			}
		}	
		
    }
    
    function mobivi_complete(){
		//MySQL connect info
		App::import('model','Lottery');
		//$lot = new Lottery();
		
		$mysql_db = Configure::read('Database.database');
		$mysql_user = Configure::read('Database.login');
		$mysql_pass = Configure::read('Database.password');
		$mysql_host = Configure::read('Database.host');		
		$con = mysql_connect($mysql_host, $mysql_user, $mysql_pass);
		mysql_select_db($mysql_db, $con);
		
    	App::import('vendor','payments/clsmobivicheckout');
		
    	// 2. Create Mobivi Notification object and read Mobivi public key
		$mbvNotify = new MobiviNotification();
		$mbvNotify->read_verify_cert(Configure::read('mobivi.mobivi_cert'));
		
		//handle the $_POST content
		$post_data = file_get_contents ('php://input'); 
		$string1 = urldecode($post_data); 
		$pos1 = stripos($string1, "message=");
		$string1 = substr($string1, $pos1+8);
		$pos2 = stripos($string1, "&encsig=");
		$msg = substr($string1,0,$pos2);		
		$enc = substr($string1,$pos2+8);
		
		// 3. Parse the notification from Mobivi
		$mbvMessage = $mbvNotify->parse($msg,$enc);
		$mbvNotify->dispose();
		if ($mbvMessage == NULL) {
		// Null message?
			//error_log("Mobivi: The message was invalid.");						
		} elseif ($mbvMessage instanceof MobiviNewOrder) {
			$mbvRequest = $mbvMessage->CheckoutRequest;
			foreach ($mbvRequest->InvoiceItems as $item) {
				$order_code = $item["ItemID"];
			}
			// A new transaction is created on Mobivi system for your invoice.
			// Get the transaction information and attach it with your invoice.
			// YOUR CODE HERE
			// YOUR CODE HERE
			// YOUR CODE HERE
		
			//In this sample, we just print out the messeage.
			// error_log("NewOrderNotification:");
			// error_log("    SerialID: " . $mbvMessage->SerialID);
			// error_log("    TransactionID: " . $mbvMessage->TransactionID);
			// error_log("    State: " . $mbvMessage->State);
			// error_log("    Invoice:");
			// error_log("        InvoiceID: " . $mbvRequest->InvoiceID);
			// error_log("        From: " . $mbvRequest->InvoiceFrom);
			// error_log("        To: " . $mbvRequest->InvoiceTo);
			// error_log("        Description: " . $mbvRequest->InvoiceDescription);
			// error_log("        Amount: " . $mbvRequest->InvoiceAmount);
			// error_log("        Tax: " . $mbvRequest->InvoiceTax);
			// error_log("        Items: " . count($mbvRequest->InvoiceItems) . " item(s)");
			// foreach ($mbvRequest->InvoiceItems as $item) {
				// error_log("            " . $item["ItemID"] . ". " . $item["Name"] . " <" . $item["Description"] . ">: " . $item["UnitPrice"] . "x" . $item["Quantity"] . " Taxable: " . $item["Taxable"]);
			// }
			/*$query4 = "INSERT INTO mobivi (time,serial_id,trans_id,order_code,user_id) VALUES (NOW(),'". $mbvMessage->SerialID ."','". $mbvMessage->TransactionID ."','". $order_code ."','". $mbvRequest->InvoiceTo ."')";
			mysql_query($query4,$con);
			$query4 = "INSERT INTO payments(user_id,package_id,method,description,created,modified) VALUES ('". $mbvRequest->InvoiceTo ."','". $order_code ."','mobivi','MobiviNewOrder(". $mbvMessage->SerialID .")(". $mbvMessage->TransactionID .")',NOW(),NOW())";
			mysql_query($query4,$con);	*/
			$str = "MobiviNewOrder(". $mbvMessage->SerialID .")(". $mbvMessage->TransactionID .")";
			$this->Payment->logPayment($mbvRequest->InvoiceTo,$order_code,'mobivi',0,$str);
						
		} elseif ($mbvMessage instanceof MobiviTransactionStateChange) {
			// Transaction status changed.
			// Get the change information and update your invoice.
			// YOUR CODE HERE
			// YOUR CODE HERE
			// YOUR CODE HERE
					
			//In this sample, we just print out the messeage.
			// error_log("TransactionStateChangeNotification:");
			// error_log("    SerialID: " . $mbvMessage->SerialID);
			// error_log("    TransactionID: " . $mbvMessage->TransactionID);
			// error_log("    State: " . $mbvMessage->State);
			switch ($mbvMessage->State){
				case "confirmed":
					$query5 = "SELECT user_id, package_id FROM payments WHERE description LIKE '%". $mbvMessage->TransactionID ."%'";
					$result = mysql_query($query5,$con);
					while ($row = mysql_fetch_assoc($result)) {
						$order_code = $row["package_id"];
						$user_id = $row["user_id"];
					}	      		
					$package = $this->Package->find('first', array('conditions' => array('code' => $order_code)));
	    			$ticket = ($package['Package']['bids']>6000)?4:1;
					
		      		
		      		//$lot->add($user_id,$ticket,5);	
		      		if (!$this->Bid->find('count',array('conditions'=>array('code'=>$mbvMessage->TransactionID)))) {		      		
						$this->_bids($user_id,$package['Package']['name'],$package['Package']['bids'],0,$mbvMessage->TransactionID,'Bid Charge - MobiVi',1);
						/*$query5 = "INSERT INTO mobivi (time,serial_id,trans_id,order_code,user_id) VALUES (NOW(),'". $mbvMessage->SerialID ."','". $mbvMessage->TransactionID ."','". $package['Package']['bids'] ."','". $user_id ."')";
						mysql_query($query5,$con);
						$query5 = "INSERT INTO payments(user_id,package_id,method,amount,description,created,modified) VALUES ('". $user_id ."','". $order_code ."','mobivi','". $package['Package']['price'] ."','confirmed(". $mbvMessage->SerialID .")(". $mbvMessage->TransactionID .")',NOW(),NOW())";
						mysql_query($query5,$con);*/
						$str = "confirmed(". $mbvMessage->SerialID .")(". $mbvMessage->TransactionID .")";
						$this->Payment->logPayment($user_id,$order_code,'mobivi',$package['Package']['price'],$str,$package['Package']['bids'],1);
						//check if using coupon:
		      			if($this->Session->check($this->name.'.coupon')) {
		      				if ($session['type'] == 'Reward Coupon') {
			      				$session = $this->Session->read($this->name.'.coupon');	
								$reward_xu = $session['amount'] * $package['Package']['bids'];
				      			$this->Rbid->insertCoupon($this->Auth->User('id'),$reward_xu,$session['code']);
				      			$expired = date("Y-m-d H:i:s");
								if ($session['multi'] == 0) {
				      						$this->Coupon->id = $session['coupon_id'];
				      						$this->Coupon->saveField('active', 2);
				      						$this->Coupon->saveField('expired', $expired);
				      					}
				      			$this->Coupon->UsersCoupon->id = $session['usersCoupon_id'];
				      			$this->Coupon->UsersCoupon->saveField('expired', $expired);
				      			$this->Session->del($this->name.'.coupon');	
		      				} elseif ($session['type'] == 'Discount Coupon') {
		      						$expired = date("Y-m-d H:i:s");
			      					if ($coupon['multi'] == 0) {
			      						$this->Coupon->id = $coupon['Coupon']['id'];
			      						$this->Coupon->saveField('active', 2);
			      						$this->Coupon->saveField('expired', $expired);
			      					}
			      					$this->Coupon->UsersCoupon->id = $coupon['UsersCoupon']['id'];
			      					$this->Coupon->UsersCoupon->saveField('expired', $expired);
		      				}
						}	
		    			$this->_checkReferral($user_id,$package['Package']['bids']/10);
						$this->Session->setFlash("B·∫°n ƒë√£ mua Bid th√†nh c√¥ng. Vui l√≤ng refresh ƒë·ªÉ c·∫≠p nh·∫≠t l·∫°i t√†i kho·∫£n. Xin c·∫£m ∆°n!");
		      		}
	    			break;
				case "cancelled":
					$query5 = "SELECT user_id, package_id FROM payments WHERE description LIKE '%". $mbvMessage->TransactionID ."%'";
					$result = mysql_query($query5,$con);					
					while ($row = mysql_fetch_assoc($result)) {
						$order_code = $row["package_id"];
						$user_id = $row["user_id"];
					}
					/*$query5 = "INSERT INTO mobivi (time,serial_id,trans_id,order_code,user_id) VALUES (NOW(),'". $mbvMessage->SerialID ."','". $mbvMessage->TransactionID ."','". $mbvMessage->State ."','". $user_id ."')";
					mysql_query($query5,$con);
					$query5 = "INSERT INTO payments(user_id,package_id,method,description,created,modified) VALUES ('". $user_id ."','". $order_code ."','mobivi','cancelled(". $mbvMessage->SerialID .")(". $mbvMessage->TransactionID .")',NOW(),NOW())";
					mysql_query($query5,$con);*/
					$str = "cancelled(". $mbvMessage->SerialID .")(". $mbvMessage->TransactionID .")";
					$this->Payment->logPayment($user_id,$order_code,'mobivi',0,$str);
					$this->Session->setFlash("B·∫°n ƒë√£ h·ªßy b·ªè qu√° tr√¨nh thanh to√°n!");
					break;
				default:
					break;
			}			
		
		} else {
			// Something wrong?
			// error_log("Unexpected type, the bug is somewhere.");
			// error_log($mbvMessage);    	
			$this->Session->setFlash("C√≥ l·ªói trong qu√° tr√¨nh thanh to√°n. Xin vui l√≤ng th·ª≠ l·∫°i!");			
		}
    }
}

    
    
 
?>