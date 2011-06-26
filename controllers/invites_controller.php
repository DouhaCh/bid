<?php
class InvitesController extends AppController{
    var $name = 'Invites';
    var $uses = array();
    var $components = array('Importer');

    function import($service = null) {
        Configure::write('debug', 0);
        $this->layout = 'js/ajax';
        $data = array();
        $result  = '';

        $data['login']    = !empty($_POST['login']) ? trim($_POST['login']) : null;
        $data['password'] = !empty($_POST['password']) ? trim($_POST['password']) : null;

        if(!empty($data['login']) && !empty($data['password'])){

            switch($service){
                case 'aol':
                    $result = $this->Importer->aol($data);
                    break;

                case 'gmail':
                    $result = $this->Importer->gmail($data);
                    break;

                case 'hotmail':
                    $result = $this->Importer->hotmail($data);
                    break;

                case 'msn_mail':
                    $result = $this->Importer->msn_mail($data);
                    break;

                case 'yahoo':
                    $result = $this->Importer->yahoo($data);
                    break;
            }
        }

        if(!empty($result[1])){
            $this->set('result', implode(', ', $result[1]));
        }
    }

    function index() {
		if (!empty($this->data)) {
            $emails = $this->data['Invite']['friends_email'];

            $data['to']       = explode(',', $emails);
            $data['template'] = 'invites/invite';
            $data['subject']  = __('Check out this website', true);
            $data['message']  = $this->data['Invite']['message'];
            $data['from'] 	  = $this->Auth->user('first_name').' '.$this->Auth->user('last_name').' <'.$this->Auth->user('email').'>';

			// lets make this send through mail to prevent spammers
			$data['delivery'] = 'mail';

            $this->_sendBulkEmail($data);

            $this->Session->setFlash(__('We have sent the email invitations to your friends.', true), 'default', array('class'=>'success'));
            $this->redirect(array('controller'=>'invites', 'action'=>'index'));
        } else {
        	$this->set('user_id',$this->Auth->user('id'));
        	App::import('model','Referral');
        	$ref = new Referral();
        	$invite_info = $ref->getData($this->Auth->user('id'));
        	$this->set('info',$invite_info);
        	/*
            $this->data['Invite']['message'] = $this->Setting->get('user_invite_message');
           	$this->data['Invite']['message'] = str_replace('SITENAME', $this->appConfigurations['name'], $this->data['Invite']['message']);
           	$this->data['Invite']['message'] = str_replace('URL', $this->appConfigurations['url'].'/users/register/'.$this->Auth->user('username'), $this->data['Invite']['message']);
           	$this->data['Invite']['message'] = str_replace('SENDER', $this->Auth->user('first_name'), $this->data['Invite']['message']);
           	$this->data['Invite']['message'] = str_replace('\n', "\n", $this->data['Invite']['message']);
           	*/
        }
	}
}
?>