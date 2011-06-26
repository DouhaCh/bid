<?php
class CouponsController extends AppController {

	var $name = 'Coupons';
	var $helpers = array('Html', 'Form');
	
	function _bids($user_id = null, $description = null, $credit = 0, $debit = 0){
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
				
				return $bid;
			} else {
				$bid['Bid']['user_id']     = $user_id;
				$bid['Bid']['description'] = $description;
				$bid['Bid']['credit']      = $credit;
				$bid['Bid']['debit']       = $debit;
				
				$this->Bid->create();
				
				return $this->Bid->save($bid);
			}
		}else{
			return false;
		}
	}
	
	function beforeFilter(){
		parent::beforeFilter();

		if(!empty($this->Auth)){
			$this->Auth->allow('apply');
		}
	}
	function redeem($code){
			$this->layout='ajax_frame';
			if(!empty($code)){
				$coupon = $this->Coupon->findByCode(strtoupper($code));
				if (!empty($coupon)) {
					if (!$this->Coupon->checkUsed($coupon['Coupon']['id'],$this->Auth->user('id'))) {
						App::import('model','Bid');
						$bid = new Bid();
						$bid->addBid($this->Auth->user('id'),'Mã Coupon'.$coupon['Coupon']['code'],$coupon['Coupon']['saving'],0 );
						$this->Coupon->UserCoupon->save(array('user_id'=>$this->Auth->user('id'),'coupon_id'=>$coupon['Coupon']['id']));
						$data['mes'] = "Coupon đã được nạp thành công";
						$data['err'] = 0;
					} else {
						$data['mes'] = "Bạn đã sử dụng coupon này";
						$data['err'] = 0;
					}
					
				}
				else {
					$data['mes'] = "Mã coupon sai, xin hãy kiểm tra lại.";
					$data['err'] = 1;
				}
			}
			else {
					$data['mes'] = "Xin hãy nhập mã coupon";
					$data['err'] = 1;
			}
			echo json_encode($data);
			
	}
	function apply() {
		if(!empty($this->data['Coupon']['code'])){
			$coupon = $this->Coupon->findByCode(strtoupper($this->data['Coupon']['code']));
			if(!empty($coupon)) {
				$this->Session->write('Coupon', $coupon);
				$this->Session->setFlash(__('The coupon has been applied.',true), 'default', array('class' => 'success'));
			} else {
				$this->Session->setFlash(__('Invalid Coupon',true));
			}
		} else {
			$this->Session->setFlash(__('Invalid Coupon',true));
		}

		$this->redirect(array('controller' => 'users', 'action' => 'register'));
	}
	
	function admin_index() {
		$this->Coupon->recursive = 0;
		$this->set('coupons', $this->paginate());
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->Coupon->create();
			if ($this->Coupon->save($this->data)) {
				$this->Session->setFlash(__('The Coupon has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Coupon could not be saved. Please, try again.', true));
			}
		}
		$couponTypes = $this->Coupon->CouponType->find('list');

		// Show the option for FREE REWARDS only if reward points is on
		if(!Configure::read('App.rewardsPoint')) {
			unset($couponTypes[5]);
		}
		$this->set(compact('couponTypes'));
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Coupon', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->Coupon->save($this->data)) {
				$this->Session->setFlash(__('The Coupon has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The Coupon could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Coupon->read(null, $id);
		}
		$couponTypes = $this->Coupon->CouponType->find('list');
		
		// Show the option for FREE REWARDS only if reward points is on
		if(!Configure::read('App.rewardsPoint')) {
			unset($couponTypes[5]);
		}

		$this->set(compact('couponTypes'));
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Coupon', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Coupon->del($id)) {
			$this->Session->setFlash(__('Coupon deleted', true));
			$this->redirect(array('action'=>'index'));
		}
	}

}
?>