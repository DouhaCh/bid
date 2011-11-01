<?php
class SsoController extends AppController {

	var $name = 'Sso';
	
	var $uses = array();

	function beforeFilter(){
		$this->Auth->allow("s", "confirm");
	}
	
	function s($token){
		Configure::write("debug", "0");
		$this->autoRender = false;
		
		$token_data = Cache::read("token_".$token);
		
		if(!empty($token_data)){
			Cache::write("token_".$token, $this->data["User"]);
			echo "Success";
		}else{
			echo "Wrong or expired token";
		}
	}
	
	function confirm(){
		$token = $this->params["url"]["token"];
		$url = $this->params["url"]["source"];
		$user = Cache::read("token_".$token);
		
		if(!empty($user)){
			$this->Session->write("Auth.User", $user);
		}
			
		$this->Session->write("sso.checked", true);
		$this->redirect($url);
	}
}
?>
