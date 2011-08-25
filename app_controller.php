<?php
class AppController extends Controller {
    var $helpers = array('Html', 'Form', 'Time', 'Number', 'Javascript', 'Cache', 'Text');
    var $components = array('Auth', 'Mailer', 'Cookie', 'RequestHandler', 'Paypal');
    var $uses = array('Setting', 'Currency', 'Language', 'User');
    var $view = 'Theme';

    var $appConfigurations;
    var $emailConfigurations;
    var $paypalConfigurations;
    
           /** 
         * Refreshes the Auth to get new data 
         * @param string $field 
         * @param string $value 
         * @return void 
         */ 
        function _refreshAuth($field = '', $value = '') { 
                if (!empty($field) && !empty($value)) { 
                        $this->Session->write($this->Auth->sessionKey .'.'. $field, $value); 
                } else { 
                        if (isset($this->User)) { 
                                $this->Auth->login($this->User->read(false, $this->Auth->user('id'))); 
                        } else { 
                                $this->Auth->login(ClassRegistry::init('User')->findById($this->Auth->user('id'))); 
                        } 
                } 
        } 
        
    

    function beforeFilter() {
	$this->appConfigurations = Configure::read('App');	
	$this->RequestHandler->setContent('json', 'text/x-json');	
	$this->set('appConfigurations', $this->appConfigurations);
	
	//see if we need to invoke stats logger
	/*if (Configure::read('Stats') && Configure::read('Stats.enabled')===true) {
		
		if (preg_match('@^(/daemons|/dwinner|/dcleaner)@Ui', $this->here)) {
			//don't log daemon calls
		} elseif (Configure::read('Stats.log_admin')==false && $this->Auth->user('admin')) {
			//don't log admin
			
		} else {
			//good to log
			
			App::import('Vendor', 'phptraffica/write_logs');
			log_phpTA(array(	'site_id'=>836796,
						'db_server'=>Configure::read('Database.host'),
						'db_user'=>Configure::read('Database.login'),
						'db_password'=>Configure::read('Database.password'),
						'db_database'=>Configure::read('Database.database'),
						
						));
		}
	}*/
	
	
	/*// lets check to see if there is a lang other than the default set
	$lang = $this->Language->find('first', array('conditions' => array('Language.server_name' => $_SERVER['SERVER_NAME'], 'default' => 0), 'contain' => ''));
	Configure::write('Lang.id', $lang['Language']['id']);*/
	
	// Set the theme if it exists
	if(!empty($this->appConfigurations['theme'])) {
	    $this->theme = $this->appConfigurations['theme'];
	}
	
	// lets set the currencyRate
	/*$currency = strtolower($this->appConfigurations['currency']);
	$rate     = Cache::read('currency_'.$currency.'_rate');
	if(empty($rate)){
		$currencyRate = $this->Currency->find('first', array('fields' => 'rate', 'conditions' => array('Currency.currency' => $currency)));
		if(!empty($currencyRate)){
			Cache::write('currency_'.$currency.'_rate', $currencyRate['Currency']['rate']);
		}
	}
	$this->set('rate', $rate);*/
	
	// Change the layout to admin if the prefix is admin
	if (isset($this->params['prefix']) && $this->params['prefix'] == 'admin') {
		$this->layout = 'admin';
	
		// lets do some SSL checking and make the admin SSL
		if(!empty($this->appConfigurations['sslUrl'])) {
			if($_SERVER['REQUEST_URI'] == '/'.$this->params['url']['url']) {
				if(empty($_SERVER['HTTPS'])) {
					$this->redirect($this->appConfigurations['sslUrl'].$_SERVER['REQUEST_URI']);
				}
			}
		}
	} else {
		// ssl checking
		if(!empty($this->appConfigurations['sslUrl'])) {
			if(!empty($_SERVER['HTTPS'])) {
				if($_SERVER['REQUEST_URI'] == '/'.$this->params['url']['url']) {
					$allowedUrls = array('/users/register', '/users/login', '/users/edit');
					if(!in_array($_SERVER['REQUEST_URI'], $allowedUrls) && (!isset($this->appConfigurations['sslRedirect']) || !$this->appConfigurations['sslRedirect'])) {
						$this->redirect($this->appConfigurations['url'].$_SERVER['REQUEST_URI']);
					}
				} elseif($_SERVER['REQUEST_URI'] == '/'  && (!isset($this->appConfigurations['sslRedirect']) || !$this->appConfigurations['sslRedirect'])) {
					$this->redirect($this->appConfigurations['url']);
				}
			}
		}
		
		if(empty($this->params['requested'])){
			// lets get the default meta tags
			$this->pageTitle = $this->Setting->get('default_meta_title');
			$this->set('meta_description', $this->Setting->get('default_meta_description'));
			$this->set('meta_keywords', $this->Setting->get('default_meta_keywords'));
			$this->set('auction_peak_start', $this->Setting->get('auction_peak_start'));
			$this->set('auction_peak_end', $this->Setting->get('auction_peak_end'));
		}
		
		if(!empty($this->params['url']) &&($this->params['url']['url'] !== 'users/login') && ($this->params['url']['url'] !== 'users/logout')) {
		    if(!$this->Auth->user('admin')) {
			    // Only call it if not requested(requestAction) and not admin
				if($_SERVER['REQUEST_URI'] !== '/offline' && empty($this->params['requested'])) {
					$setting = $this->Setting->get('site_live');
		
				    if($setting == 'no') {
					$this->redirect('/offline');
				    }
				}
		    }
		}
		}
	
		if(empty($this->params['requested']) && isset($this->Auth)){
			$this->_checkAuth();
		}
	}

	function _checkAuth(){
		// Setup the field for auth
		$this->Auth->fields = array(
			'username' => 'username',
			'password' => 'password'
		);


		$this->Auth->loginAction = array(
			'controller' => 'users',
			'action'     => 'login'
		);

		// Where the auth will redirect user after logout
		$this->Auth->logoutRedirect = array(
			'controller' => 'users',
			'action'     => 'login'
		);

		// Set the error message
		$this->Auth->loginError = sprintf(__('Invalid %s or %s. Please try again.', true),
										  $this->Auth->fields['username'],
										  $this->Auth->fields['password']);

		// Set to off since we do something inside login
		$this->Auth->autoRedirect = false;

		// Set the type of authorization
		$this->Auth->authorize = 'controller';

		// Check if user has a remember me cookie
		if(!$this->Auth->user()) {
			if($id = $this->Cookie->read('User.id')) {
				$user = $this->User->find('first', array('conditions' => array('User.id' => $id), 'contain' => ''));
				if($this->Auth->login($user)) {
					if(Configure::read('App.forum')){
						$this->PhpBB3->login($user['User']['username'], $user['User']['key'], $user['User']['email']);
					}

					$this->Session->del('Message.Auth');
				} else {
					$this->Cookie->del('User.id');
				}
			}
		}

		if($this->Auth->user()) {
			if(empty($user)) {
				$user = $this->User->find('first', array('conditions' => array('User.id' => $this->Auth->user('id')), 'contain' => ''));
			}
			if($user['User']['active'] == 0) {
				// Deleting remember me cookie if it exists
				if($this->Cookie->read('User.id')){
					$this->Cookie->del('User.id');
				}
				$this->Auth->logout();
			}

			// online users stuff
			if(!Cache::read('user_count_'.$user['User']['id'])) {
				// lets set the cache to 10 mintes for the online user
				Cache::write('user_count_'.$user['User']['id'], microtime(), 600);
			}
		}

	}

	function beforeRender(){
		parent::beforeRender();
				
		if ($this->Auth->user('id')) {
			$this->set('bidBalance', $this->User->Bid->balance($this->Auth->user('id')));
		}
		
		if(empty($this->params['requested']) && empty($this->params['prefix'])) {
			// Import when needed only
			App::import('Model', array('Auction', 'Category'));
			$auction = new Auction();
			$category = new Category();

			$menuCategories = $category->getlist('parent', 'all', 'count');
			$menuCategoriesSelect = $category->getlist('parent', 'list');
			$menuCategoriesCount = $auction->countAll(array('live', 'comingsoon', 'closed', 'free'));
			$this->set(compact('menuCategories', 'menuCategoriesSelect', 'menuCategoriesCount'));
		}
		
		// SCD mode
		if (Configure::read('SCD') && Configure::read('SCD.isSCD')===true) {
			if ($this->Session->check('switch_template')) {
				Configure::write('App.theme', $this->Session->read('switch_template'));
			}
			$this->set('is_scd', true);
			$this->set('template_list', Configure::read('SCD.templates'));
			$this->set('template', Configure::read('App.theme'));
		}
		
	}

    function isAuthorized(){
        if(!empty($this->params['admin']) && $this->Auth->user('admin') != 1){
            return false;
        }

        return true;
    }

    /**
     * Function to check if now peak or not
     *
     * @return int One if true, zero otherwise
     */
    function isPeakNow($returnDates = false) {
		$this->layout = 'js/ajax';

		if($returnDates == false) {
			$isPeakNow = Cache::read('peak');
		} else {
			$isPeakNow = null;
		}

		if(strlen($isPeakNow) == 0) {
			$data = array();
			$isPeakNow = 0;

	        $data['auction_peak_start'] = $this->Setting->get('auction_peak_start');
	        $data['auction_peak_end'] = $this->Setting->get('auction_peak_end');

			$auction_peak_start_time = explode(':', $data['auction_peak_start']);
			$auction_peak_end_time   = explode(':', $data['auction_peak_end']);

			$peak_start_hour   = $auction_peak_start_time[0];
			$peak_start_minute = $auction_peak_start_time[1];

			$peak_end_hour   = $auction_peak_end_time[0];
			$peak_end_minute = $auction_peak_end_time[1];

			$peak_length = $peak_end_hour - $peak_start_hour;

			if($peak_length <= 0) {
				$peak_start = date('Y-m-d') . ' ' . $data['auction_peak_start'] . ':00';
				$peak_end   = date('Y-m-d', time() + 86400) . ' ' . $data['auction_peak_end'] . ':00';
			} else {
				$peak_start = date('Y-m-d') . ' ' . $data['auction_peak_start'] . ':00';
				$peak_end   = date('Y-m-d') . ' ' . $data['auction_peak_end'] . ':00';
			}

			// 19/02/2009 - Michael - lets do some adjustments on the peak times
			if($peak_end > date('Y-m-d H:i:s', time() + 86400)) {
				$peak_end   = date('Y-m-d') . ' ' . $data['auction_peak_end'] . ':00';
			}

			if($peak_start > date('Y-m-d H:i:s')) {
				$peak_start   = date('Y-m-d', time() - 86400) . ' ' . $data['auction_peak_start'] . ':00';
			}
			if($peak_start < date('Y-m-d H:i:s', time() - 86400)) {
				$peak_start   = date('Y-m-d') . ' ' . $data['auction_peak_start'] . ':00';
			}
			// peak start and end time should never be more than 24 hours apart
			if(strtotime($peak_end) - strtotime($peak_start) > 86400) {
				// lets adjust peak end back to where it should be
				$peak_end   = date('Y-m-d') . ' ' . $data['auction_peak_end'] . ':00';
			}

			// lets check to see if the different is STILL more than 24 hours
			if(strtotime($peak_end) - strtotime($peak_start) > 86400) {
				// lets adjust peak end back to where it should be - back 1 day
				$peak_end   = date('Y-m-d H:i:s', strtotime($peak_end) - 86400);
			}

			if($returnDates == true) {
				$data['peak_end']   = $peak_end;
				$data['peak_start'] = $peak_start;
				return $data;
			}

			$now = time();

			if($now > strtotime($peak_start) && $now < strtotime($peak_end)) {
				$isPeakNow = 1;
			}

			Cache::write('peak', $isPeakNow, '+1 minute');
		}

		return $isPeakNow;
	}

    /**
     * Function to send email
     *
     * @param array $data An array containing smtp parameter including body
     * @return boolean Return true if success, false otherwise
     */
	/*
    function _sendEmail($data) {
        $this->emailConfigurations = Configure::read('Email');
        if(!empty($data)) {
            // Array for configurations
            $configurations = array();

            // Optional, I will use main configuraiton or mail if empty
            if(!empty($data['delivery'])){
                $this->Email->delivery = $data['delivery'];
            } else {
            	$this->Email->delivery = $this->emailConfigurations['delivery'];
            }

            // If the delivery is smtp, then put the smtp configurations
            if($this->Email->delivery == 'smtp') {
				// Check configurations
	            foreach(Configure::read('Email') as $name => $value){
	            	if(!empty($value)){
	                	$configurations[$name] = $value;
	                }
	           	}
	           	// Put email options
            	$this->Email->smtpOptions = $configurations;
            }

            // Required parameter, will use app default if not set
            if(!empty($data['from'])){
                $this->Email->from = trim($data['from']);
            } else {
                $this->Email->from = $this->appConfigurations['name'].' <'.$this->appConfigurations['email'].'>';
            }

            // Required parameter, will return false if not set
            if(!empty($data['to'])){
                $this->Email->to = trim($data['to']);
                $this->set('recipient', $this->Email->to);
            }else{
                $this->log('_sendMail(), the \'to\' parameter cannot be empty');
                return false;
            }

            // Required parameter, will return false if not set
            if(!empty($data['subject'])){
                $this->Email->subject = trim($data['subject']);
            }else{
                $this->log('_sendMail(), the \'subject\' parameter cannot be empty');
                return false;
            }

            // Required parameter, will return false if not set
            if(!empty($data['template'])){
                $this->Email->template = $data['template'];
            }else{
                $this->log('_sendMail(), the \'template\' parameter cannot be empty');
                return false;
            }

            // Optional, I will use both if main conf/passed data empty
            if(!empty($data['sendAs'])){
                $this->Email->sendAs = $data['sendAs'];
            }else{
                if(!empty($this->emailConfigurations['sendAs'])){
                    $this->Email->sendAs = $this->emailConfigurations['sendAs'];
                }else{
                    $this->Email->sendAs = 'both';
                }
            }

            // Optional, I will use default if empty
            if(!empty($data['layout'])){
                $this->Email->layout = $data['layout'];
            }else{
                if(!empty($this->emailConfigurations['layout'])) {
                    $this->Email->layout = $this->emailConfigurations['layout'];
                }else{
                    $this->Email->layout = 'default';
                }
            }

            // Optional, can be empty
            if(!empty($data['cc'])){
                if(is_array($data['cc'])){
                    foreach($data['cc'] as $key => $address){
                        // Trim address from any whitespace
                        $data['cc'][$key] = trim($address);
                    }
                }else{
                    $this->Email->cc = trim($data['cc']);
                }
            }

            // Optional, can be empty
            if(!empty($data['bcc'])){
                if(is_array($data['bcc'])){
                    foreach($data['bcc'] as $key => $address){
                        $data['bcc'][$key] = trim($address);
                    }
                }else{
                    $this->Email->cc = trim($data['bcc']);
                }
            }

            // Set the data to template
            $this->set('data', $data);

            // Send the email
            if($this->Email->send()){

				// Reset email after sending
				$this->Email->reset();
                return true;
            }else{
                if($this->Email->delivery == 'smtp'){
                    $this->log(sprintf('_sendMail(), sending email failed. %s', $this->Email->smtpError));
                }else{
                    $this->log('_sendMail(), sending email failed.');
                }
                return false;
            }
        }else{
            $this->log('_sendMail(), data parameter required.');
            return false;
        }
    }
	*/

    function _sendBulkEmail($data, $delay = 100){
        if(!empty($data)){
            // If the to is array then loop through recipient
            if(is_array($data['to'])){
                $recipients = $data['to'];

                // Loop through recipient
                foreach($recipients as $recipient){
                    // Trim the data, remove the whitespace
                    $data['to'] = trim($recipient);

                    // Send the email
                    $this->_sendEmail($data);

                    // Reseting email before sending again
                    $this->Mailer->ClearAllRecipients();

                    // Delay the email sending
                    usleep($delay);
                }
            }else{
                // Put in to temporary variable
                $recipients = $data['to'];

                // Split up the recipient in case user enter
                // the recipient as comma separated value
                $recipients = preg_split('/[\s,]/', $recipients);

                // Loop through recipient after split
                foreach($recipients as $key => $recipient){

                    // Remove the whitespace from recipient address, if any
                    $recipients[$key] = trim($recipient);
                }

                // Put back the recipient as an array
                $data['to'] = $recipients;

                // Recursive call
                $this->_sendBulkEmail($data);
            }
        }else{
            return false;
        }
    }

	/**
	 * Function to get price rate used for beforeSave and afterFind
	 *
	 * @return float The rate which user choose
	 */
	function _getRate(){
		$currency = strtolower($this->appConfigurations['currency']);
		$rate = Cache::read('currency_'.$currency.'_rate');

		if(!empty($rate)){
			return $rate;
		}else{
			return 1;
		}
	}

	/**
     * Function to send email
     *
     * @param array $data An array containing smtp parameter including body
     * @return boolean Return true if success, false otherwise
     */
	function _sendEmail($data) {
		if(!empty($data)) {
		    if($this->Mailer){
			$emailConfigurations = Configure::read('Email');
			if(!empty($emailConfigurations['Host'])){
			    $this->Mailer->Host = $emailConfigurations['Host'];
			}else{
			    $this->log('_sendMail(), Host parameter required');
			    return false;
			}
		
			if(!empty($emailConfigurations['Port'])){
			    $this->Mailer->Port = $emailConfigurations['Port'];
			}else{
			    $this->log('_sendMail(), Port parameter required');
			    return false;
			}
		
			// Is this mailer using smtp
			if(!empty($emailConfigurations['IsSMTP'])){
			    $this->Mailer->IsSMTP();
		
			    if(!empty($emailConfigurations['Username'])){
				$this->Mailer->Username = $emailConfigurations['Username'];
			    }else{
				$this->log('_sendMail(), Username parameter required if using SMTP');
				return false;
			    }
		
			    if(!empty($emailConfigurations['Password'])){
				$this->Mailer->Password = $emailConfigurations['Password'];
			    }else{
				$this->log('_sendMail(), Password parameter required if using SMTP');
				return false;
			    }
			}
		
			if(!empty($emailConfigurations['SMTPAuth'])){
			    $this->Mailer->SMTPAuth = $emailConfigurations['SMTPAuth'];
			}
		
			if(!empty($emailConfigurations['SMTPSecure'])){
			    $this->Mailer->SMTPSecure = $emailConfigurations['SMTPSecure'];
			}
		
			if(!empty($emailConfigurations['WordWrap'])){
			    $this->Mailer->WordWrap = $emailConfigurations['WordWrap'];
			}
		
			if(!empty($emailConfigurations['From'])){
			    $this->Mailer->From = $emailConfigurations['From'];
			}else{
			    $this->log('_sendMail(), From parameter required');
			    return false;
			}
			
			if (!empty($emailConfigurations['CharSet'])) {
			   $this->Mailer->CharSet=$emailConfigurations['CharSet'];
			}
		
			if(!empty($emailConfigurations['FromName'])){
			    $this->Mailer->FromName = $emailConfigurations['FromName'];
			}else{
			    $this->log('_sendMail(), From name parameter required');
			    return false;
			}
		
			if(!empty($data['subject'])){
			    $this->Mailer->Subject = $data['subject'];
			}else{
			    $this->log('_sendMail(), Subject parameter required');
			    return false;
			}
		
			if(!empty($data['to'])){
				if(is_array($data['to'])){
					foreach($data['to'] as $recipient){
						if(is_array($recipient) && !empty($recipient['name'])){
							$this->Mailer->AddAddress($recipient['to'], $recipient['name']);
						}elseif (is_array($recipient)) {
							$this->Mailer->AddAddress($recipient['to']);
						} else {
							$this->Mailer->AddAddress($recipient);
						}
					}
				} else {
					if(!empty($data['name'])){
						$this->Mailer->AddAddress($data['to'], $data['name']);
					} else {
						$this->Mailer->AddAddress($data['to']);
					}
				}
			} else {
			    $this->log('_sendMail(), To parameter required');
			    return false;
			}
		
			$this->set('data', $data);
			$this->autoRender = false;
			$this->autoLayout = false;
		
			if(empty($data['layout'])){
			    $data['layout'] = 'default';
			}
		
			if(empty($data['template'])){
			    $data['template'] = $this->action;
			}
		
			if(!empty($emailConfigurations['IsHTML'])){
			    $this->Mailer->IsHTML($emailConfigurations['IsHTML']);
		
			    $this->layout = 'email'.DS.'html'.DS.$data['layout'];
		
						$this->viewPath = 'elements'.DS.'email'.DS.'html';
			    $bodyHtml       = $this->render($data['template']);
			    
			    $this->output='';
			    
			    $this->Mailer->MsgHTML($bodyHtml);
			}else{
			    $this->layout = 'email'.DS.'text'.DS.$data['layout'];
		
						$this->viewPath = 'elements'.DS.'email'.DS.'text';
			    $bodyText     = $this->render($data['template']);
		
			    $this->Mailer->AltBody = $bodyText;
			}
		
			if(!$ret = $this->Mailer->Send()) {
			    $this->log($this->Mailer->ErrorInfo);
			    $ret=false;
			}
			
			$this->Mailer->ClearAddresses();
			$this->Mailer->ClearAllRecipients();
			$this->Mailer->ClearCustomHeaders();
			$this->Mailer->ClearAttachments();
			
			return $ret;
		    }else{
			$this->log('_sendMail(), PhpMailer component required.');
			return false;
		    }
		}else{
		    $this->log('_sendMail(), data parameter required.');
		    return false;
		}
	}
    
	function currency($number, $currency = 'USD', $options = array()) {
		$default = array(
			'before'=>'', 'after' => '', 'zero' => '0', 'places' => 2, 'thousands' => ',',
			'decimals' => '.','negative' => '()', 'escape' => true
		);
		$currencies = array(
			'USD' => array(
				'before' => '$', 'after' => 'c', 'zero' => 0, 'places' => 2, 'thousands' => ',',
				'decimals' => '.', 'negative' => '()', 'escape' => true
			),
			'GBP' => array(
				'before'=>'&#163;', 'after' => 'p', 'zero' => 0, 'places' => 2, 'thousands' => ',',
				'decimals' => '.', 'negative' => '()','escape' => false
			),
			'EUR' => array(
				'before'=>'&#8364;', 'after' => 'c', 'zero' => 0, 'places' => 2, 'thousands' => '.',
				'decimals' => ',', 'negative' => '()', 'escape' => false
			),
			'AUD' => array(
				'before' => '$', 'after' => 'c', 'zero' => 0, 'places' => 2, 'thousands' => ',',
				'decimals' => '.', 'negative' => '()', 'escape' => true
			),
			'NZD' => array(
				'before' => 'NZ$', 'after' => 'c', 'zero' => 0, 'places' => 2, 'thousands' => ',',
				'decimals' => '.', 'negative' => '()', 'escape' => true
			),
			'PLN' => array(
				'before'=>'z&#322;', 'after' => '', 'zero' => 0, 'places' => 2, 'thousands' => '',
				'decimals' => ',', 'negative' => '()', 'escape' => false
			),
			'LEI' => array(
				'before'=>'', 'after' => 'LEI', 'zero' => 0, 'places' => 2, 'thousands' => '',
				'decimals' => ',', 'negative' => '()', 'escape' => false
			),
			'NOK' => array(
				'before' => 'kr ', 'after' => '', 'zero' => 0, 'places' => 2, 'thousands' => ',',
				'decimals' => '.', 'negative' => '()', 'escape' => true
			)
		);

		if (isset($currencies[$currency])) {
			$default = $currencies[$currency];
		} elseif (is_string($currency)) {
			$options['before'] = $currency;
		}

		$options = array_merge($default, $options);

		$result = null;

		if ($number == 0 ) {
			if ($options['zero'] !== 0 ) {
				return $options['zero'];
			}
			$options['after'] = null;
		} elseif ($number < 1 && $number > -1 ) {
			if(Configure::read('App.noCents') == true) {
				$options['after'] = null;
			} else {
				if(!empty($options['after'])){
					$multiply = intval('1' . str_pad('', $options['places'], '0'));
					$number = $number * $multiply;
					$options['before'] = null;
					$options['places'] = null;
				}
			}
		} else {
			$options['after'] = null;
		}

		$abs = abs($number);
		$places = 0;
		if (is_int($options)) {
			$places = $options;
		}

		$separators = array(',', '.', '-', ':');

		$before = $after = null;
		if (is_string($options) && !in_array($options, $separators)) {
			$before = $options;
		}
		$thousands = ',';
		if (!is_array($options) && in_array($options, $separators)) {
			$thousands = $options;
		}
		$decimals = '.';
		if (!is_array($options) && in_array($options, $separators)) {
			$decimals = $options;
		}

		$escape = true;
		if (is_array($options)) {
			$options = array_merge(array('before'=>'$', 'places' => 2, 'thousands' => ',', 'decimals' => '.'), $options);
			extract($options);
		}

		$result = $before . number_format($number, $places, $decimals, $thousands) . $after;

		
		
		
		if ($number < 0 ) {
			if($options['negative'] == '()') {
				$result = '(' . $result .')';
			} else {
				$result = $options['negative'] . $result;
			}
		}
		return $result;
	}
	
	function AuctionLink($id, $product_name) {
		return array('action' => self::urlTitle($product_name.'-'.$id));
	}
	
	function AuctionLinkFlat($id, $product_name) {
		return '/dau-gia/'.self::urlTitle($product_name.'-'.$id);
	}
	
	function CategoryLink($id, $category_name) {
		return array('action' => self::urlTitle($category_name.'-'.$id));
	}
	
	function CategoryLinkFlat($id, $category_name) {
		return '/danh-muc/'.self::urlTitle($category_name.'-'.$id);
	}
	
	function urlTitle($title) {
		$title=str_replace(' & ', ' and ', $title);
		$title=preg_replace('/[^A-Za-z0-9]/','-',$title);
		while (strstr($title, '--'))
			$title=str_replace('--','-',$title);
		
		return $title;
	}
	
	function demoDisabled() {
		if (Configure::read('SCD') && Configure::read('SCD.isSCD')===true) {
			exit('This feature is disabled in the demo version. <p><a href="javascript:history.back()">Go back</a>');
		}
	}
}
?>
