<?php
class UserCoupon extends AppModel {

	var $name = 'UserCoupon';

	var $belongsTo = array(
			'User' => array(
				'className'  => 'User',
				'foreignKey' => 'user_id'
			),

			'Coupon' => array(
				'className' => 'Coupon',
				'foreignKey' => 'coupon_id'
			)
		);
}
?>