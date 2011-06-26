<?php
$html->addCrumb(__('Dash Board',true), '/users');
$html->addCrumb('Thiệt lập lại mật mã', '/users');
echo $this->element('crumb_user');
?>         					
<div class="users reset-page">
	<h1 class="page-title"><?php __('Forgotten Your Password?');?></h1>
	
	<div style="padding: 0 0 0px;"><?php __('If you have forgotten your username and password simply enter in your email address below.');?></div>
	
	
	
	<div id="reset-form">
		<?php
			echo $form->create('User', array('action' => 'reset'));
			echo '<fieldset>';
			echo $form->input('email', array('label' => 'Địa chỉ Email'));
			echo $form->submit('Gửi thông tin', array('type' => 'button'));
			echo '</fieldset>';
		 	echo $form->end();
		 	
		 ?>
	</div>
	<div id="more">
		<h3 class="heading"><?php __('Don\'t have an account?');?></h3>
		<p><?php echo sprintf(__('If so you may want to %s now.', true), $html->link(__('sign up', true), array('action'=>'register')));?></p>
		
		<h3 class="heading"><?php __('Already a Member?');?></h3>
		<p><?php echo sprintf(__('If so you may want to %s now.', true), $html->link(__('login', true), array('action'=>'login')));?></p>
	</div>
	<div class="clearBoth"></div>
	<div style="padding: 0 0 5px;font-size:11px;"><?php __('Your login details will be emailed to you and your password will be reset.');?><div>
</div>