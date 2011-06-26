<?php
	class Referral extends AppModel {

		var $name = 'Referral';

		var $belongsTo = array(
			'User' => array(
				'className'  => 'User',
				'foreignKey' => 'user_id'
			), 
			'Referrer' => array(
				'className'  => 'User',
				'foreignKey' => 'referrer_id'
			)
		);

		function getData($user_id) {
			$data['overall']['visit'] = $this->find('count',array('conditions'=>array('user_id'=>0,'referrer_id'=>$user_id)));
			$data['overall']['register'] = $this->find('count',array('conditions'=>array('user_id >'=>0,'confirmed'=>1,'referrer_id'=>$user_id)));
			$data['today']['visit'] = $this->find('count',array('conditions'=>array('user_id'=>0,'referrer_id'=>$user_id,'Referral.created >'=> date('Y-m-d', strtotime("now")))));
			$data['today']['register'] = $this->find('count',array('conditions'=>array('user_id >'=>0,'confirmed'=>1,'referrer_id'=>$user_id,'Referral.created >'=> date('Y-m-d', strtotime("now")))));
			$data['unclaimed']['visit'] = $this->find('count',array('conditions'=>array('user_id'=>0,'referrer_id'=>$user_id,'claim'=>0)));
			$data['unclaimed']['register'] = $this->find('count',array('conditions'=>array('user_id >'=>0,'confirmed'=>1,'referrer_id'=>$user_id,'claim'=>0)));
			return $data;
		}
		
		function add($data) {
			if ($data['Referral']['user_id']==0) {
				$count = $this->find('count',array('condition'=>array('ip'=>$data['Referral']['ip'],'user_id'=>0)));
				if ($count==0) {
					$this->create();
					$this->save($data);
				}
				
			}
			else {
				$count = $this->find('count',array('condition'=>array('ip'=>$data['Referral']['ip'],'user_id >'=>0)));
				if ($count==0) {
					$this->create();
					$this->save($data);
				}
			}
		}
	}
?>