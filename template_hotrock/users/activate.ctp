<?php
$html->addCrumb(__('Dash Board',true), '/users');
$html->addCrumb('Kich hoat tai khoan', '/users/activate');
echo $this->element('crumb_user');
?>         					
<div class="users reset-page">
	<h1 class="page-title"><?php __('Kích hoạt tài khoản');?></h1>
	<p> Hãy kiểm tra email bạn đăng ký, nếu bạn chưa nhận được email của chúng tôi, click vào <a href="/users/reactivate"> Đây </a> để gửi lại email kích hoạt</p>
	<div id="reset-form">
		<?php
			echo $form->create('User', array('action' => 'activate'));
			echo '<fieldset>';
			echo $form->input('key', array('label' => 'Mã kích hoạt:'));
			echo $form->submit('Kich hoat', array('type' => 'button'));
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