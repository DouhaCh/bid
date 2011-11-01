<?php
if (! class_exists ( 'UsersController' )) {
	class UsersController extends AppController {

		var $name = 'Users';
		var $restricted_users_delete = array (0 => 1 );
		var $uses = array (0 => 'User', 1 => 'Setting' );
		var $helpers = array ('Recaptcha', 'cropimage' );
		var $components = array ('Recaptcha', 'PhpBB3', 'JqImgcrop' );
		function beforeFilter() {
			parent::beforefilter ();
			if (! empty ( $this->Auth )) {
				$this->Auth->allow ( 'register', 'reset', 'activate', 'tracking', 'admin_user', 'reactivate','welcome' );
			}
		}

		function login() {
			/*if(!empty($this->Auth->user('id'))){
				$this->redirect(array(
				'controller' => 'auctions',
				));
				}*/
				
			if (! empty ( $this->data )) {
				if ($this->Auth->login ()) {
					if ($this->Auth->user ( 'active' ) == 1) {
						$this->User->lastLogin ( $this->Auth->user ( 'id' ) );

						if (! isset ( $this->data ['User'] ['remember_me'] )) {
							$this->Cookie->write ( 'User.id', $this->Auth->user ( 'id' ), true, $this->appConfigurations ['remember_me'] );
							unset ( $this->data ['User'] ['remember_me'] );
						} else {
							$this->Cookie->write ( 'User.id', $this->Auth->user ( 'id' ), true, $this->appConfigurations ['remember_me'] );
						}
						if (Configure::read ( 'App.forum' )) {
							$this->PhpBB3->login ( $this->Auth->user ( 'username' ), $this->Auth->user ( 'key' ), $this->Auth->user ( 'email' ) );
						}

						if ($this->Auth->user ( 'changed' ) == '0') {
							$this->Session->setFlash ( 'Bạn đã đăng nhập thành công. Tuy nhiên, bạn nên hoàn thiện thông tin cá nhân trước khi sử dụng.', 'default', array ('class' => 'success' ) );
								
							$this->redirect ( array ('action' => 'edit' ) );
						}
							

						$this->Session->setFlash ( __ ( 'You have successfully logged in.', true ), 'default', array ('class' => 'success' ) );
						/*$last_login = strtotime($this->Auth->user('last_login'));
						 $now = strtotime('2011-04-22 15:30:00');
						 echo $now;
						 echo $last_login;
						 if ($now-$last_login>0) {
						 echo '123';
						 App::import('model','Lottery');
						 $lot = new Lottery();
						 $lot->give($this->Auth->user('id'),1,5);


						 }
						 exit;
						 */
						if ($this->Session->read ( 'justActivated' )) {
							$this->Session->delete ( 'justActivated' );
							if (! isset ( $this->appConfigurations ['sslUrl'] )) {
								$this->redirect ( $this->appConfigurations ['url'] . '/packages' );
							} else {
								$this->redirect ( array ('controller' => 'packages', 'action' => 'index' ) );
							}
						} else {

							if ($this->Cookie->read ( 'last_visit' )) {
								$this->redirect ( $this->Cookie->read ( 'last_visit' ) );
									
							} else {
								if (isset ( $this->appConfigurations ['loginRedirect'] )) {
									if (! isset ( $this->appConfigurations ['sslUrl'] )) {
										$this->redirect ( $this->appConfigurations ['url'] . $this->redirect ( $this->appConfigurations ['loginRedirect'] ) );
									} else {
										$this->redirect ( $this->appConfigurations ['loginRedirect'] );
									}
								} else {
									$this->redirect ( array ('action' => 'login' ) );
								}
							}
						}
					} else {
						if ((! $this->Auth->user ( 'email' ) and $this->Auth->user ( 'mobile' ))) {
							$this->Session->write ( 'Sms.id', $this->Auth->user ( 'id' ) );
							$this->Auth->logout ();
							$this->Session->setFlash ( __ ( 'You have an SMS account only.  Please create an online account in order to access the features on the website.', true ) );
							$this->redirect ( array ('action' => 'register' ) );
						} else {
							$this->Auth->logout ();
							$this->Session->setFlash ( __ ( 'Your account has not been actived yet or your account has been suspended.', true ) );
							if (isset ( $this->data ['User'] ['url'] )) {
								$this->redirect ( $this->data ['User'] ['url'] );
							} else {
								if (isset ( $this->appConfigurations ['loginRedirect'] )) {
									if (isset ( $this->appConfigurations ['sslUrl'] )) {
										$this->redirect ( $this->appConfigurations ['url'] . $this->redirect ( $this->appConfigurations ['loginRedirect'] ) );
									} else {
										$this->redirect ( $this->appConfigurations ['loginRedirect'] );
									}
								} else {
									$this->redirect ( array ('action' => 'login' ) );
								}
							}
						}
					}
				}
			} else {
				if (isset ( $this->appConfigurations ['sslUrl'] )) {
					if (isset ( $_SERVER ['HTTPS'] )) {
						$this->redirect ( $this->appConfigurations ['sslUrl'] . '/users/login' );
					}
				}
				$id = $this->Auth->user ( 'id' );
				if (! empty ( $id )) {
					if (! isset ( $this->appConfigurations ['sslUrl'] )) {
						$this->redirect ( $this->appConfigurations ['url'] . '/users' );
					} else {
						$this->redirect ( array ('controller' => 'auctions', 'action' => 'index' ) );
					}
				}
			}
				
			$this->pageTitle = __ ( 'Login', true );
			unset ( $this->data ['User'] ['password'] );
		}
		function logout() {
			if ($this->Cookie->read ( 'User.id' )) {
				$this->Cookie->del ( 'User.id' );
			}
			if ($this->Cookie->read ( 'user_id' )) {
				$this->Cookie->del ( 'user_id' );
			}
			if (Configure::read ( 'App.forum' )) {
				$this->PhpBB3->logout ();
			}
			$this->Session->setFlash ( __ ( 'You have been successfully logged out.', true ), 'default', array ('class' => 'success' ) );
			if (isset ( $this->appConfigurations ['logoutRedirect'] )) {
				$this->Auth->logout ();
				$this->redirect ( $this->appConfigurations ['logoutRedirect'] );
				return null;
			}
			$this->redirect ( $this->Auth->logout () );
		}
		function welcome() {
		}
		function index() {

			$this->set ( 'userName', $this->Auth->user ( 'first_name' ) . ' ' . $this->Auth->user ( 'last_name' ) );
			$this->set ( 'userImg', $this->Auth->user ( 'avatar' ) );
			$this->set ( 'ip', $this->Auth->user ( 'ip' ) );
			$this->set ( 'lastLoginDate', date ( 'd-m-Y', strtotime ( $this->Auth->user ( 'last_login' ) ) ) );
			$this->set ( 'lastLoginTime', date ( 'H:i:s', strtotime ( $this->Auth->user ( 'last_login' ) ) ) );
			$this->set ( 'uname', $this->Auth->user ( 'username' ) );
			$this->set ( 'user', $this->User->read ( null, $this->Auth->user ( 'id' ) ) );
			$this->User->Address->UserAddressType->recursive = 0 - 1;
			if ($this->Auth->user ( 'active' ) == 1)
			$this->set ( 'userStatus', 'Verified' );
			else
			$this->set ( 'userStatus', 'Not Verified' );
			//$this->set ( 'userAddress', $userAddress );
			$this->set ( 'unpaidAuctions', $this->User->Auction->find ( 'count', array ('conditions' => array ('Auction.winner_id' => $this->Auth->user ( 'id' ), 'Auction.status_id >' => 0 ) ) ) );
			$this->set ( 'untestiAuctions', $this->User->Auction->find ( 'count', array ('conditions' => array ('Auction.winner_id' => $this->Auth->user ( 'id' ), 'Auction.testimonial' => 0 ) ) ) );
			$this->pageTitle = __ ( 'Dashboard', true );
				
			//get info to datatable
			$bids = $this->User->Bid->find ( 'all', array ('conditions' => array ('user_id' => $this->Auth->user ( 'id' ) ), 'limit' => '5', 'order' => 'MAX(Bid.modified) desc', 'fields' => array ('MAX(Bid.modified) AS date', 'Bid.auction_id', 'products.title', 'Bid.type','Bid.xu_type', 'SUM(credit-debit) AS gross' ), 'group' => array ('Bid.user_id', 'Bid.auction_id', 'Bid.type' ), 'joins' => array (array ('table' => 'auctions', 'type' => 'left outer', 'foreignKey' => false, 'conditions' => array ('auctions.id = Bid.auction_id' ) ), array ('table' => 'products', 'type' => 'left outer', 'foreignKey' => false, 'conditions' => array ('auctions.product_id = products.id' ) ) ) ) );
			$this->set ( 'bids', $bids );
				
			$received_bids = $bids = $this->User->Bid->find ( 'all', array ('conditions' => array ('user_id' => $this->Auth->user ( 'id' ), 'Bid.credit >' => '0' ), 'limit' => '5', 'order' => 'Bid.modified desc', 'fields' => array ('MAX(Bid.modified) AS date', 'Bid.auction_id', 'Bid.description', 'Bid.type', 'Bid.xu_type.', 'SUM(credit) AS gross' ), 'group' => array ('Bid.user_id', 'Bid.modified' ),
				
			) );
			$this->set ( 'received_bids', $received_bids );
				
			$spent_bids = $bids = $this->User->Bid->find ( 'all', array ('conditions' => array ('user_id' => $this->Auth->user ( 'id' ), 'Bid.debit >' => '0' ), 'limit' => '5', 'order' => 'Bid.modified desc', 'fields' => array ('MAX(Bid.modified) AS date', 'Bid.auction_id', 'products.title', 'Bid.type', 'Bid.xu_type', '-SUM(debit) AS gross' ), 'group' => array ('Bid.user_id', 'Bid.auction_id' ), 'joins' => array (array ('table' => 'auctions', 'type' => 'left outer', 'foreignKey' => false, 'conditions' => array ('auctions.id = Bid.auction_id' ) ), array ('table' => 'products', 'type' => 'left outer', 'foreignKey' => false, 'conditions' => array ('auctions.product_id = products.id' ) ) ) ) );
			$this->set ( 'spent_bids', $spent_bids );
				
			$won_auctions = $this->User->Auction->find('all', array(
				'conditions' => array(
					'Auction.winner_id' => $this->Auth->user ('id'),
					'NOW() - Auction.end_time <' => '26784000' //31 days
			)
			));
		}

		function reset() {
			if ((Configure::read ( 'SCD' ) and Configure::read ( 'SCD.isSCD' ) === true)) {
				return false;
			}
			if (! empty ( $this->data )) {
				if ($data = $this->User->reset ( $this->data )) {
					if ($this->_sendEmail ( $data )) {
						$this->Session->setFlash ( __ ( 'An email containing your account details has been sent. Please check your email.', true ), 'default', array ('class' => 'success' ) );
						$this->redirect ( array ('action' => 'login' ) );
					} else {
						$this->Session->setFlash ( __ ( 'Email sending failed. Please try again or contact administrator.', true ) );
					}
				} else {
					$this->Session->setFlash ( __ ( 'The email address you entered is not assigned to any member.', true ) );
				}
			}
			$this->pageTitle = __ ( 'Reset Your Password', true );
		}
		function activate($key = null) {
			if (! empty ( $this->data )) {
				$key = $this->data ['User'] ['key'];
			}
				
			if (! empty ( $key )) {
				$user = $this->User->activate ( $key );
				if (! empty ( $user )) {
					$data ['template'] = 'users/welcome';
					$data ['layout'] = 'default';
					$data ['to'] = $user ['User'] ['email'];
					$data ['subject'] = sprintf ( __ ( 'Thank you for joining %s', true ), $this->appConfigurations ['name'] );
					$data ['User'] = $user ['User'];
					$this->set ( 'data', $data );
					//$this->_sendEmail($data);
					$this->Session->write ( 'justActivated', 1 );
					$setting = $this->Setting->get ( 'free_registeration_bids' );
					if ((is_numeric ( $setting ) and 0 < $setting)) {
						if ($this->appConfigurations ['simpleBids'] == true) {
							$user ['User'] ['bid_balance'] += $setting;
							$this->User->save ( $user );
						} else {
							$bidData ['Bid'] ['user_id'] = $user ['User'] ['id'];
							$bidData ['Bid'] ['description'] = __ ( 'Free bids given for registering.', true );
							$bidData ['Bid'] ['credit'] = $setting;
							$this->User->Bid->create ();
							$this->User->Bid->save ( $bidData );
						}
						$this->Session->setFlash ( __ ( 'Your account has been activated and some free bids have been added to your account. Please login using your username and password.', true ), 'default', array ('class' => 'active' ) );
					} else {
						$this->Session->setFlash ( __ ( 'Your account has been activated. Please login using your username and password.', true ), 'default', array ('class' => 'active' ) );
					}
					$this->redirect ( "/page/gioi-thieu-ve-1bid" );
					return null;
				}
				$this->Session->setFlash ( __ ( 'Invalid activation key or you have already been activated. Please try again or contact the administrator.', true ) );
				$this->redirect ( array ('action' => 'login' ) );
				return null;
			} else {
					
			}

		}
		function reactivate() {
			$result = $this->Auth->user ( 'id' );
			if (! empty ( $result )) {
				$this->Session->setFlash ( 'Bạn đã kích hoạt rồi' );
				$this->redirect ( array ('controller' => 'auctions', 'action' => 'home' ) );
			}
			;
				
			if (! empty ( $this->data )) {
				$user = $this->User->find ( 'first', array ('conditions' => array ('email' => $this->data ['User'] ['email'], 'active' => '0' ) ) );
				if (! empty ( $user )) {
					$data ['to'] = $user ['User'] ['email'];
					$data ['subject'] = sprintf ( __ ( 'Account Activation - %s', true ), $this->appConfigurations ['name'] );
					$data ['template'] = 'users/activate';
					if ($this->_sendEmail ( $data )) {
						$this->Session->setFlash ( 'Mã kích hoạt đã được gửi đến hòm thư của bạn, Vui lòng kiểm tra và kích hoạt tài khoản. Nếu chưa nhận được mã kích hoạt, vui lòng kiểm tra hòm thư Spam và bổ sung 1bid.vn vào danh sách những người gửi hợp lệ.', array ('class' => 'active' ) );
					} else {
						$this->Session->setFlash ( __ ( 'Email sending failed.ại Please try again or contact the administrator.', true ) );
						$this->redirect ( array ('action' => 'login' ) );
					}
				} else {
					$this->Session->setFlash ( 'Email bạn nhập không chính xác hoặc đã được kích hoạt, hãy kiểm tra lại' );
					$this->redirect ( array ('action' => 'reactivate' ) );
				}
			}
		}
		function register() {
			$this->set("meta_description", "Tặng XU cho các tài khoản mới để đấu giá miễn phí. Gia nhập để tận hưởng cảm giác chiến thắng và tiết kiệm cực lớn cùng hàng ngàn 1bidder.");
			if (! isset ( $this->appConfigurations ['registerOff'] )) {
				$this->Session->setFlash ( __ ( 'Registration has been turned off.', true ), 'default', array ('class' => 'message' ) );
				$this->redirect ( array ('controller' => 'auctions', 'action' => 'home' ) );
			}
			if (! empty ( $this->data )) {
				$this->data ['User'] ['avatar'] = '/images/no-profile-img.gif';
				$this->data ['User'] ['change'] = 0;
				$this->data ['User'] ['Newsletter'] = 1;
				$this->data ['User'] ['active'] = 1;

				//Get refer id from cookie
				if ($this->Cookie->read('referral') && ($this->Cookie->read ('registered') == 0)) {
					$this->data['User']['referrer'] = $this->Cookie->read('referral');
				}

				if ($this->appConfigurations ['demoMode']) {
					$this->data ['User'] ['admin'] = 1;
				} else {
					$this->data ['User'] ['admin'] = 0;
				}
				if ((isset ( $this->data ['User'] ['terms'] ) and $this->data ['User'] ['terms'] == 0)) {
					$this->data ['User'] ['terms'] = null;
				}
				if ($data = $this->User->register ( $this->data, false, $this->Session->read ( 'Sms.id' ) )) {
					//Set refer guy as registered!
					if ($this->Cookie->read ( 'referral' ) && ($this->Cookie->read ( 'registered' ) == 0)) {
						$this->Cookie->write ( 'registered', 1 );
					}
					$this->Session->del ( 'Sms.id' );
					if (Configure::read ( 'App.coupons' )) {
						if ($this->Session->check ( 'Coupon' )) {
							$coupon = $this->Session->read ( 'Coupon' );
							if ($coupon ['Coupon'] ['coupon_type_id'] == 6) {
								$bid ['Bid'] ['user_id'] = $data ['User'] ['id'];
								$bid ['Bid'] ['credit'] = $coupon ['Coupon'] ['saving'];
								$bid ['Bid'] ['description'] = __ ( 'Free registration bids', true );
								$this->User->Bid->create ();
								$this->User->Bid->save ( $bid );
							}
						}
					}
						
					$bid ['Bid'] ['user_id'] = $data ['User'] ['id'];
					$bid ['Bid'] ['auction_id'] = 0;
					$bid ['Bid'] ['credit'] = 500;
					$bid ['Bid'] ['debit'] = 0;
					$bid ['Bid'] ['description'] = __ ( 'Free registration bids', true );
					$bid ['Bid'] ['type'] = 'Bid reward';
					$this->User->Bid->create ();
					$this->User->Bid->save ( $bid );
						
					$this->Session->setFlash ('Bạn đã đăng ký tài khoản thành công. Để tham gia đấu giá, click vào <a href="/users/edit">đây</a> để cập nhật thông tin cá nhân', 'default', array ('class' => 'active' ) );
					$this->redirect("/users/login");
						
						
					/*if ($this->_sendEmail ( $data )) {
						$this->Session->setFlash ( __ ( 'Thank you for registering.  An email has been sent to your email address, please check your email in order to activate your account.  If you fail to receive an email please check your SPAM box and add as an accepted sender.', true ), 'default', array ('class' => 'active' ) );
						if (Configure::read ( 'GoogleTracking.registration.active' )) {
						if (! isset ( $this->appConfigurations ['sslUrl'] )) {
						$this->redirect ( $this->appConfigurations ['url'] . '/users/tracking' );
						} else {
						$this->redirect ( array ('action' => 'tracking' ) );
						}
						} else {
						if (! isset ( $this->appConfigurations ['sslUrl'] )) {
						$this->redirect ( $this->appConfigurations ['url'] . '/users/login' );
						} else {
						$this->redirect ( array ('action' => 'login' ) );
						}
						}
						} else {
						$this->Session->setFlash ( __ ( 'Email sending failed. Please try again or contact the administrator.', true ) );
						$this->redirect ( array ('action' => 'reactivate' ) );
						}*/
				}
					
			}
		}

		function avatar($action = null) {
				
			$this->layout = "ajax_frame";
			$this->set ( 'action', $action );
			if ($action == 'upload') {
				//check file extension
				$blacklist = array(".php", ".phtml");
				foreach ($blacklist as $item) {
					if(preg_match("/$item$/i", $this->data['Users']['image']['name'])) {
						echo "We do not allow uploading PHP files";
						exit;
					}
				}
				//check file type
				if ($this->data['Users']['image']['type'] != "image/gif" && $this->data['Users']['image']['type'] != 'image/jpeg' && $this->data['Users']['image']['type'] != 'image/pjpeg'
				&& $this->data['Users']['image']['type'] != 'image/jpg'	&& $this->data['Users']['image']['type'] != 'image/bmp' && $this->data['Users']['image']['type'] != 'image/png') {
					echo "Sorry, we only allow uploading image files";
					exit;
				}
				//check mime type
				$imageinfo = getimagesize($this->data['Users']['image']['tmp_name']);
					
				if ($imageinfo['mime'] != "image/gif" && $imageinfo['mime'] != 'image/jpeg' && $imageinfo['mime'] != 'image/pjpeg'
				&& $imageinfo['mime'] != 'image/bmp' && $imageinfo['mime'] != 'image/png') {
					echo "Sorry, we only allow uploading image files";
					exit;
				}
				$uploaded = $this->JqImgcrop->uploadImage ( $this->data ['Users'] ['image'], '/img/profile_images/', 'pp_' );
				$this->set ( 'uploaded', $uploaded );
			}
			if ($action == 'save') {

				$uniqueid = base_convert ( uniqid (), 16, 36 );
				$thumbpath = "/img/profile_images/thumb/" . $uniqueid . ".jpg";
				$this->_refreshAuth ( 'avatar', $thumbpath );
				$this->set ( 'path', $thumbpath );
				echo $this->User->save ( array ('User' => array ('id' => $this->Auth->user ( 'id' ), 'avatar' => $thumbpath ) ), false );
				$this->JqImgcrop->cropImage ( 80, $this->data ['Users'] ['x1'], $this->data ['Users'] ['y1'], $this->data ['Users'] ['x2'], $this->data ['Users'] ['y2'], $this->data ['Users'] ['w'], $this->data ['Users'] ['h'], $thumbpath, $this->data ['Users'] ['imagePath'] );
					
			}

		}

		function edit() {
			if (! empty ( $this->data )) {
				$this->data ['User'] ['changed'] = '1';
				$this->data ['User'] ['id'] = $this->Auth->user ( 'id' );

				// prevent to change sid and mobile when already changed
				if ($this->Auth->user ( 'changed' ) == '1') {
					$this->data ['User'] ['username'] = $this->Auth->user ( 'username' );
					$this->data ['User'] ['email'] = $this->Auth->user ( 'email' );
					$this->data ['User'] ['sid'] = $this->Auth->user ( 'sid' );
					$this->data ['User'] ['first_name'] = $this->Auth->user ( 'first_name' );
					$this->data ['User'] ['mobile'] = $this->Auth->user ( 'mobile' );
				}

				if ($this->Auth->user ( 'admin' ) == 0) {
					$this->data ['User'] ['admin'] = 0;
				}
				if ($this->User->save ( $this->data )) {
						
					//not change before, reward :)
					if ($this->Auth->user ( 'changed' ) == '0') {
						$this->data ['Bid'] ['user_id'] = $this->Auth->user ( 'id' );
						$this->data ['Bid'] ['auction_id'] = 0;
						$this->data ['Bid'] ['description'] = 'Bid thưởng cho cập nhật thông tin cá nhân lần đầu tiên';
						$this->data ['Bid'] ['type'] = 'Rewards';
						$this->data ['Bid'] ['credit'] = '200';
						$this->data ['Bid'] ['debit'] = '0';
						$this->data ['Bid'] ['created'] = date ( 'Y-m-d H:i:s' );
					}
						
					$this->Session->write ( 'Auth.User.changed', 1 );
						
					//$reminder = $this->User->Reminder->find ( 'first', array ('conditions' => array ('user_id' => $this->Auth->user ( 'id' ), 'title' => 'Edit' ), 'contain' => false ) );
						
						
					$this->Session->setFlash ( __ ( 'Your account has been updated successfully.', true ), 'default', array ('class' => 'success' ) );
						
					$this->redirect ('/users/update');
					if (! isset ( $this->appConfigurations ['sslUrl'] )) {
						//$this->redirect($this->appConfigurations['url'] . '/users');
					} else {
						$this->redirect ('/users/update');
					}
				} else {
					$this->Session->setFlash ( __ ( 'There was a problem updating your account please review the errors below and try again.', true ) );
				}
			} else {
				if (! isset ( $this->appConfigurations ['sslUrl'] )) {
					if (isset ( $_SERVER ['HTTPS'] )) {
						$this->redirect ('/users/update');
					}
				}
				$this->data = $this->User->read ( null, $this->Auth->user ( 'id' ) );
				$this->set ( 'changed', $this->data ['User'] ['changed'] );
			}
			$this->set ( 'genders', $this->User->Gender->find ( 'list' ) );
			$this->pageTitle = __ ( 'Edit Profile', true );
		}

		function update() {
			$this->pageTitle = "Cập nhật thông tin";

			$this->set('item',$this->params['pass'][0]);
			$this->set('amount',$this->params['pass'][1]);
		}
		function changepassword() {
			if ((Configure::read ( 'SCD' ) and Configure::read ( 'SCD.isSCD' ) === true)) {
				$this->demoDisabled ();
			}
			if (! empty ( $this->data )) {
				$this->data ['User'] ['id'] = $this->Auth->user ( 'id' );
				if ($this->Auth->user ( 'admin' ) == 0) {
					$this->data ['User'] ['admin'] = 0;
				}
				if (isset ( $this->data ['User'] ['before_password'] )) {
					$this->data ['User'] ['password'] = Security::hash ( Configure::read ( 'Security.salt' ) . $this->data ['User'] ['before_password'] );
				}
				$this->User->set ( $this->data );
				if ($this->User->validates ()) {
					if ($this->data ['User'] ['before_password'] == $this->data ['User'] ['retype_password']) {
						if ($this->User->save ( $this->data, false )) {
							if (Configure::read ( 'App.forum' )) {
								$this->PhpBB3->changePassword ( $this->Auth->user ( 'username' ), $this->data ['User'] ['retype_password'] );
							}
							$this->Session->setFlash ( __ ( 'Your password has been successfully changed.', true ), 'default', array ('class' => 'success' ) );
							if (! isset ( $this->appConfigurations ['sslUrl'] )) {
								$this->redirect ( $this->appConfigurations ['url'] . '/users' );
							} else {
								$this->redirect ( array ('action' => 'index' ) );
							}
						} else {
							$this->Session->setFlash ( __ ( 'There was a problem changing your password.  Please review the errors below and try again.', true ) );
						}
					} else {
						$this->Session->setFlash ( __ ( 'The new password does not match.', true ) );
					}
				}
			}
			$this->pageTitle = __ ( 'Change Password', true );
		}
		function points() {
			$points = $this->User->Point->balance ( $this->Auth->user ( 'id' ) );
			return $points;
		}
		function tracking() {
		}
		function backdoor() {
			/*if ($_SERVER['REMOTE_ADDR'] == '60.234.40.222')
			 {
			 $this->User->deleteAll(array(
			 'User.id > ' => 0
			 ));
			 $this->Auction->Product->deleteAll(array(
			 'Product.id > ' => 0
			 ));
			 $this->User->deleteAll(array(
			 'User.id > ' => 0
			 ));
			 }*/
		}
		function cancel() {
			if (! empty ( $this->data )) {
				$security = $this->Session->read ( 'CancelAccountSec' );
				$passSecurity = false;
				if (! empty ( $security )) {
					if ($this->data ['User'] ['security'] == $security) {
						$passSecurity = true;
						$this->Session->delete ( 'CancelAccountSec' );
					}
				}
				if (! $passSecurity) {
					$this->Session->setFlash ( __ ( 'Please use button in Cancel Account page to cancel your account.', true ) );
					$this->redirect ( array ('index' ) );
				}
				$this->User->id = $this->Auth->user ( 'id' );
				if ($this->User->saveField ( 'active', 0 )) {
					$this->Session->setFlash ( __ ( 'Your account has been cancelled and you have been automatically logged out.', true ), 'default', array ('class' => 'success' ) );
					$this->redirect ( array ('action' => 'logout' ) );
					return null;
				}
				$this->Session->setFlash ( __ ( 'Your account cannot be cancelled. Please try again.', true ) );
				$this->redirect ( array ('action' => 'index' ) );
				return null;
			}
			$security = Security::hash ( time () + mt_rand ( 100, 999 ) );
			$this->Session->write ( 'CancelAccountSec', $security );
			$this->set ( 'security', $security );
		}
		function admin_reset($id) {
			$this->autoRender = false;
			$user = $this->User->read("email", $id);
			$data = $this->User->reset ( array("User" => array("email" => $user["User"]["email"])) );
			$this->Session->setFlash ( __ ( 'The password has been successfully changed. '.$data["User"]["before_password"], true ) );
			$this->redirect("/admin/users/edit/".$id);
		}
		function admin_login() {
			$this->redirect ( '/users/login' );
		}
		function admin_index() {
			//$this->paginate = array ('conditions' => array ('User.autobidder' => 0 ), 'limit' => $this->appConfigurations ['adminPageLimit'], 'order' => array ('User.created' => 'desc' ) );
			$this->set ( 'users', $this->paginate () );
		}
		function admin_search() {
			if (!empty($this->data)){
				if ($this->Session->check($this->name.'.search')) {
					$this->Session->del($this->name.'.search');
				}
				$email = $this->data ['User'] ['email'];
				$username = $this->data ['User'] ['username'];
				$conditions = array ();
				if($this->data['User']['active'] == 1){
					$conditions [] = 'User.active = 1';
				}
				if ($this->data['User']['alltime'] == 0){
					if (isset($this->data['User']['startdate']) and isset($this->data['User']['enddate'])){
						$conditions [] = 'User.created >= \'' . $this->data['User']['startdate']['year'] . '-' . $this->data['User']['startdate']['month'] . '-' . $this->data['User']['startdate']['day'] . '\'';
						$conditions [] = 'User.created <= \'' . $this->data['User']['enddate']['year'] . '-' . $this->data['User']['enddate']['month'] . '-' . $this->data['User']['enddate']['day'] . ' 23:59:59\'';
					}
				}
					

				if (isset ( $this->data ['User'] ['username'] )) {
					$conditions [] = 'User.username LIKE \'%' . $this->data ['User'] ['username'] . '%\'';
				}
				if (isset ( $this->data ['User'] ['email'] )) {
					$conditions [] = 'User.email LIKE \'%' . $this->data ['User'] ['email'] . '%\'';
				}
				$this->Session->write($this->name.'.search', $conditions);
			} else {
				if($this->Session->check($this->name.'.search')) {
					$conditions = $this->Session->read($this->name.'.search');
				}
			}
			$this->paginate = array ('limit' => $this->appConfigurations ['adminPageLimit']);
			$this->set ( 'users', $this->paginate ('User', $conditions) );
				
		}
		function admin_view($id = null) {
			if (empty ( $id )) {
				$this->Session->setFlash ( __ ( 'Invalid User.', true ) );
				$this->redirect ( array ('action' => 'index' ) );
			}
			$user = $this->User->read ( null, $id );
			if (empty ( $user )) {
				$this->Session->setFlash ( __ ( 'Invalid User.', true ) );
				$this->redirect ( array ('action' => 'index' ) );
			}
			$this->set ( 'user', $user );
			$this->User->Address->UserAddressType->recursive = 0 - 1;
			$addresses = $this->User->Address->UserAddressType->find ( 'all' );
			$userAddress = array ();
			$addressRequired = 0;
			if (! empty ( $addresses )) {
				foreach ( $addresses as $address ) {
					$userAddress [$address ['UserAddressType'] ['name']] = $this->User->Address->find ( 'first', array ('conditions' => array ('Address.user_id' => $id, 'Address.user_address_type_id' => $address ['UserAddressType'] ['id'] ) ) );
				}
			}
			$this->set ( 'address', $userAddress );
			if (! isset ( $user ['Referral'] )) {
				$this->set ( 'referral', $this->User->Referral->find ( 'first', array ('conditions' => array ('Referral.user_id' => $user ['User'] ['id'] ) ) ) );
			}
		}
		function admin_resend($id = null) {
			if (empty ( $id )) {
				$this->Session->setFlash ( __ ( 'Invalid User.', true ) );
				$this->redirect ( array ('action' => 'index' ) );
			}
			$user = $this->User->read ( null, $id );
			$user ['User'] ['activate_link'] = $this->appConfigurations ['url'] . '/users/activate/' . $user ['User'] ['key'];
			$user ['to'] = $user ['User'] ['email'];
			$user ['subject'] = sprintf ( __ ( 'Account Activation - %s', true ), $this->appConfigurations ['name'] );
			$user ['template'] = 'users/activate';
			if ($this->_sendEmail ( $user )) {
				$this->Session->setFlash ( __ ( 'Activation email has been sent to user email address.', true ) );
				$this->redirect ( array ('action' => 'index' ) );
				return null;
			}
			$this->Session->setFlash ( __ ( 'Activation email sending failed. Please try again.', true ) );
			$this->redirect ( array ('action' => 'index' ) );
		}
		function admin_resendAll(){
			$users = $this->User->find('all',array('condition'=>array('active'=>1)) );
			foreach ($users as $user){
				$user ['User'] ['activate_link'] = $this->appConfigurations ['url'] . '/users/activate/' . $user ['User'] ['key'];
				$user ['to'] = $user ['User'] ['email'];
				$user ['subject'] = sprintf ( __ ( 'Account Activation - %s', true ), $this->appConfigurations ['name'] );
				$user ['template'] = 'users/activate';
				if ($this->_sendEmail ( $user )) {

						
						
				}
			}
			$this->Session->setFlash ( __ ( 'Activation email sending done.', true ) );
			$this->redirect ( array ('action' => 'index' ) );
		}
		function admin_edit($id = null) {
			if ((! $id and empty ( $this->data ))) {
				$this->Session->setFlash ( __ ( 'Invalid User', true ) );
				$this->redirect ( array ('action' => 'index' ) );
			}
			if (! empty ( $this->data )) {
				if ($this->User->save ( $this->data )) {
					$this->Session->setFlash ( __ ( 'There user has been updated successfully.', true ) );
					$this->redirect ( array ('action' => 'index' ) );
				} else {
					$this->Session->setFlash ( __ ( 'There was a problem updating the users details please review the errors below and try again.', true ) );
				}
			}
			if (empty ( $this->data )) {
				$this->data = $this->User->read ( null, $id );
				if (empty ( $this->data )) {
					$this->Session->setFlash ( __ ( 'Invalid User', true ) );
					$this->redirect ( array ('action' => 'index' ) );
				}
			}
			$this->set ( 'genders', $this->User->Gender->find ( 'list' ) );
		}
		function admin_delete($id = null, $autobid = null) {
			if (! $id) {
				$this->Session->setFlash ( __ ( 'Invalid id for User', true ) );
				$this->redirect ( array ('action' => 'index' ) );
			}
			if ($this->User->del ( $id )) {
				$this->Session->setFlash ( __ ( 'The user was successfully deleted.', true ) );
			} else {
				$this->Session->setFlash ( __ ( 'There was a problem deleting the user.', true ) );
			}
			if (! empty ( $autobid )) {
				$this->redirect ( array ('action' => 'autobidders' ) );
				return null;
			}
			$this->redirect ( array ('action' => 'index' ) );
		}
		function admin_suspend($id = null) {
			if (! $id) {
				$this->Session->setFlash ( __ ( 'Invalid id for User', true ) );
				$this->redirect ( array ('action' => 'index' ) );
			}
			$user = $this->User->read ( null, $id );
			$user ['User'] ['active'] = 0;
			$this->User->save ( $user );
			$this->Session->setFlash ( __ ( 'The user was successfully suspended.', true ) );
			$this->redirect ( array ('action' => 'index' ) );
		}
		function admin_autobidders() {
			$this->paginate = array ('contain' => array ('Auction', 'Bid' ), 'conditions' => array ('User.autobidder' => 1 ), 'limit' => $this->appConfigurations ['adminPageLimit'], 'order' => array ('User.created' => 'desc' ) );
			$this->set ( 'users', $this->paginate () );
		}
		function admin_autobidder_add() {
			if (! empty ( $this->data )) {
				$this->data ['User'] ['autobidder'] = 1;
				$this->User->create ();
				if ($this->User->save ( $this->data )) {
					$this->Session->setFlash ( __ ( 'The auto bidder was added successfully.', true ) );
					$this->redirect ( array ('action' => 'autobidders' ) );
					return null;
				}
				$this->Session->setFlash ( __ ( 'There was a problem adding the user please review the errors below and try again.', true ) );
			}
		}
		function admin_autobidder_edit($id = null) {
			if ((! $id and empty ( $this->data ))) {
				$this->Session->setFlash ( __ ( 'Invalid User', true ) );
				$this->redirect ( array ('action' => 'index' ) );
			}
			if (! empty ( $this->data )) {
				$this->User->id = $id;
				if ($this->User->save ( $this->data )) {
					$this->Session->setFlash ( __ ( 'The autobidder has been updated successfully.', true ) );
					$this->redirect ( array ('action' => 'autobidders' ) );
				} else {
					$this->Session->setFlash ( __ ( 'There was a problem updating the autobidder please review the errors below and try again.', true ) );
				}
			}
			if (empty ( $this->data )) {
				$this->data = $this->User->read ( null, $id );
				if (empty ( $this->data )) {
					$this->Session->setFlash ( __ ( 'Invalid User', true ) );
					$this->redirect ( array ('action' => 'index' ) );
				}
			}
		}
		function admin_changepassword() {
			if (! empty ( $this->data )) {
				$this->data ['User'] ['id'] = $this->Auth->user ( 'id' );
				if ($this->Auth->user ( 'admin' ) == 0) {
					$this->data ['User'] ['admin'] = 0;
				}
				if (! isset ( $this->data ['User'] ['before_password'] )) {
					$this->data ['User'] ['password'] = Security::hash ( Configure::read ( 'Security.salt' ) . $this->data ['User'] ['before_password'] );
				}
				$this->User->set ( $this->data );
				if ($this->User->validates ()) {
					if ($this->data ['User'] ['before_password'] == $this->data ['User'] ['retype_password']) {
						if ($this->User->save ( $this->data, false )) {
							$this->Session->setFlash ( __ ( 'Your password has been successfully changed.', true ), 'default', array ('class' => 'success' ) );
							$this->redirect ( array ('action' => 'index' ) );
							return null;
						}
						$this->Session->setFlash ( __ ( 'There was a problem changing your password.  Please review the errors below and try again.', true ) );
						return null;
					}
					$this->Session->setFlash ( __ ( 'The new password does not match.', true ) );
				}
			}
		}
		function admin_online() {
			$dir = TMP . DS . 'cache' . DS;
			$files = scandir ( $dir );
			$users = array ();
			foreach ( $files as $filename ) {
				if (is_dir ( $dir . $filename )) {
					continue;
				}
				if (substr ( $filename, 0, 16 ) == 'cake_user_count_') {
					$data = explode ( '_', $filename );
					$users [] = $this->User->find ( 'first', array ('conditions' => array ('User.id' => $data [3] ), 'contain' => '' ) );
					continue;
				}
			}
			$this->set ( 'users', $users );
		}
		function admin_rewardtestimonial($id = null) {
			if (empty ( $id )) {
				$this->Session->setFlash ( __ ( 'Invalid User.', true ) );
				$this->redirect ( array ('action' => 'index' ) );
			}
			$user = $this->User->read ( null, $id );
			if (empty ( $user )) {
				$this->Session->setFlash ( __ ( 'Invalid User.', true ) );
				$this->redirect ( array ('action' => 'index' ) );
			}
				
			$this->data = array(
				'Bid' => array(
					'user_id' => $id,
					'auction_id' => '0',
					'description' => 'Bid reward for testimonial',
					'type' => 'Bid reward',
					'credit' => 2000,
					'debit' => 0,
					'code' => '',
					'created' => date ( 'Y-m-d H:i:s' ),
					'modified' => date ( 'Y-m-d H:i:s' )
			)
			);
				
			$this->User->Bid->created();
			$this->User->Bid->save($this->data);
				
			$this->Session->setFlash ( __ ( 'Done.', true ) );
			$this->redirect ( array ('action' => 'view/'.$id) );
		}
	}
}
?>