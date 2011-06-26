<?php
	require_once '../config/config.php';
	
	// just incase the database isn't called yet
	require_once '../database.php';
	
require_once('nusoap.php');
$secure_pass = 'imissuak'; // Mật khẩu giao tiếp API của Merchant với NgânLượng.vn
function UpdateOrder($transaction_info,$order_code,$payment_id,$payment_type,$secure_code)
{                    
    global $secure_pass;
	// Kiểm tra chuỗi bảo mật
   	$secure_code_new = md5($transaction_info.' '.$order_code.' '.$payment_id.' '.$payment_type.' '.$secure_pass);
	if($secure_code_new != $secure_code)
	{
		return -1; // Sai mã bảo mật
	}
	else // Thanh toán thành công
	{	
		// Trường hợp là thanh toán tạm giữ. Hãy đưa thông báo thành công và cập nhật hóa đơn phù hợp
		if($payment_type == 2)
		{
			
			// Lập trình thông báo thành công và cập nhật hóa đơn
		}
		// Trường hợp thanh toán ngay. Hãy đưa thông báo thành công và cập nhật hóa đơn phù hợp
		elseif($payment_type == 1)
		{		
			// Lập trình thông báo thành công và cập nhật hóa đơn			
		}
		
		$query = "SELECT * FROM packages WHERE code LIKE '$order_code'";
		$package = mysql_query($query);
		if ($row=mysql_fetch_array($package)){
			$query="INSERT INTO bids(user_id, auction_id,description,type,credit, debit, created, modified) VALUES ($transaction_info,0,'".$row['name']."','Bid Charge',".$row['bids'].",0,NOW(),NOW())";
			mysql_query($query);
			//Valentine bonus
			$query="INSERT INTO bids(user_id, auction_id,description,type,credit, debit, created, modified) VALUES ($transaction_info,0,'".$row['name']."','Bid Reward',".$row['bids'].",0,NOW(),NOW())";
			mysql_query($query);
    		//$this->_checkReferral($transaction_info,$package['Package']['bids']/10);
    		//$this->redirect('/users/update');
		}
	}
}

function RefundOrder($transaction_info,$order_code,$payment_id,$refund_payment_id,$payment_type,$secure_code)
{                    
    global $secure_pass;
	// Kiểm tra chuỗi bảo mật
   	$secure_code_new = md5($transaction_info.' '.$order_code.' '.$payment_id.' '.$refund_payment_id.' '.$secure_pass);
	if($secure_code_new != $secure_code)
	{
		return -1; // Sai mã bảo mật
	}	
	else // Trường hợp hòan trả thành công
	{
		// Lập trình thông báo hoàn trả thành công và cập nhật hóa đơn			
	}
}
// Khai bao chung WebService
$server = new nusoap_server();
$server->configureWSDL('WS_WITH_SMS',NS);
$server->wsdl->schemaTargetNamespace=NS;
// Khai bao cac Function
$server->register('UpdateOrder',array('transaction_info'=>'xsd:string','order_code'=>'xsd:string','payment_id'=>'xsd:int','payment_type'=>'xsd:int','secure_code'=>'xsd:string'),array('result'=>'xsd:int'),NS);
$server->register('RefundOrder',array('transaction_info'=>'xsd:string','order_code'=>'xsd:string','payment_id'=>'xsd:int','refund_payment_id'=>'xsd:int','payment_type'=>'xsd:int','secure_code'=>'xsd:string'),array('result'=>'xsd:int'),NS);
// Khoi tao Webservice
$HTTP_RAW_POST_DATA = (isset($HTTP_RAW_POST_DATA)) ? $HTTP_RAW_POST_DATA :'';
$server->service($HTTP_RAW_POST_DATA);
?>