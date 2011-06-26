<?php
class ReferralsController extends AppController {

	var $name = 'Referrals';
	var $uses = array('Referral', 'Setting');
    function beforeFilter()
        {
        	parent::beforefilter();
        	$this->Cookie->write('last_visit',Router::url($this->here, true));
        }
	function index() {
		$this->paginate = array('conditions' => array('Referral.referrer_id' => $this->Auth->user('id'), 'Referral.confirmed' => 1), 'limit' => 20, 'order' => array('Referral.modified' => 'desc'));
		$this->set('referrals', $this->paginate());

		$this->set('setting', $this->Setting->get('free_referral_bids'));
		$this->pageTitle = __('Referrals', true);
	}
	
	function withdraw() {
		$this->layout='ajax_frame';
		if ($this->Auth->user('id'))
		{
			App::import('model','Bid');
			$bid = new Bid();
			$refInfo = $this->Referral->getData($this->Auth->user('id'));
			if ($refInfo['unclaimed']['visit']+$refInfo['unclaimed']['register']==0) {
				$result =json_encode(array('error'=>1,'message'=>'Bạn chưa được thưởng thêm XU mới'));
			} else {
				$bidData = array ('Bid'=>array (
					'user_id' => $this->Auth->user('id'),
					'auction_id' => 0,
					'type'	=> 'Bid Reward',
					'description' => 'Referral Reward',
					'credit' => $refInfo['unclaimed']['visit']*$this->appConfigurations['bidPerVisit']+$refInfo['unclaimed']['register']*$this->appConfigurations['bidPerRegister'],
					'debit' => 0
				));
				$result = '';
				if ($bid->save($bidData)){
					$this->Referral->updateAll(
							array('claim'=>1),
							array('referrer_id'=>$this->Auth->user('id'),'user_id'=>0)
							);
					$this->Referral->updateAll(
							array('claim'=>1),
							array('referrer_id'=>$this->Auth->user('id'),'user_id >'=>0,'confirmed'=>1)
					);
							
					$result =json_encode(array('error'=>0,'message'=>'Xu đã được nạp vào tài khoản chính của bạn. Cám ơn bạn rất nhiều!'));
				}
			}
		} else {
			$result =json_encode(array('error'=>0,'message'=>'Xu đã được nạp vào tài khoản chính của bạn. Cám ơn bạn rất nhiều!'));
			$this->redirect(array(
                                    'controller' => 'users',
                                    'action' => 'login'
                                ));
		}
		$this->set('data',$result);
	}

	function pending() {
		$this->UserReferral->recursive = 0;
		$this->paginate = array('conditions' => array('Referral.referrer_id' => $this->Auth->user('id'), 'Referral.confirmed' => 0), 'limit' => 20, 'order' => array('Referral.modified' => 'desc'));
		$this->set('referrals', $this->paginate());
		$this->pageTitle = __('Pending Referrals', true);
	}

	function admin_index() {
		$this->paginate = array('limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('Referral.created' => 'desc'));
		$this->set('referrals', $this->paginate());
	}

	function admin_user($user_id = null) {
		if(empty($user_id)) {
			$this->Session->setFlash(__('Invalid User.', true));
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}
		$user = $this->Referral->User->read(null, $user_id);
		if(empty($user)) {
			$this->Session->setFlash(__('Invalid User.', true));
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}
		$this->set('user', $user);

		$this->paginate = array('conditions' => array('Referral.referrer_id' => $user_id), 'limit' => $this->appConfigurations['adminPageLimit'], 'order' => array('Referral.created' => 'desc'));
		$this->set('referrals', $this->paginate());
	}
}
?>
