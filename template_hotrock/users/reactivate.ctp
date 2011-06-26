<?php
$html->addCrumb(__('Dash Board',true), '/users');
$html->addCrumb('Gửi lại mã kích hoạt', '/users/reactivate');
echo $this->element('crumb_user');
?>         					
<div class="users reset-page">
	<h1 class="page-title"><?php __('Gửi lại mã kích hoạt');?></h1>
	
	<div style="padding: 0 0 0px;"><?php __('Nếu bạn chưa nhận được email kích hoạt, hãy nhập lại email ở đây và nhấn gửi. Trước tiên, hãy kiểm tra lại trong mục spam.');?></div>
	
	<div id="reset-form">
		<?php
			echo $form->create('User', array('action' => 'reactivate'));
			echo '<fieldset>';
			echo $form->input('email', array('label' => 'Địa chỉ Email'));
			echo $form->submit('Gửi', array('type' => 'button'));
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
</div>