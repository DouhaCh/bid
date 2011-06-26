<?php
class Coupon extends AppModel{
    var $name = 'Coupon';
    var $hasMany = array('UserCoupon');
    var $belongsTo = array('CouponType');
    
    function beforeSave(){
        if(!empty($this->data['Coupon']['code'])){
            $this->data['Coupon']['code'] = strtoupper($this->data['Coupon']['code']);
        }
        
        return true;
    }
    function checkUsed($coupon_id,$user_id) {
    	$data = $this->UserCoupon->find('first',array('conditions'=>array('user_id'=>$user_id,'coupon_id'=>$coupon_id)));
    	return !empty($data); 
    		
    }
}
?>