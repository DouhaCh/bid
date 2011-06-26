<div class="users login-page">
	<h1 class="page-title"><?php __('Login');?></h1>

	<div id='login_form'>
		<?php
			echo $form->create('User', array('action' => 'login'));
			echo '<fieldset>';
			echo $form->input('username', array('label' => __('Username', true), 'div' => 'rInput'));
			echo $form->input('password', array('label' => __('Password', true), 'div' => 'rInput'));
			echo $form->submit('Đăng nhập', array('type' => 'button'));
			echo '</fieldset>';
			echo $form->end();
		?>
		
	</div>
	<div id="more">
		<h2 class="heading"><?php __('Don\'t have an account?');?></h3>
		<p><?php echo sprintf(__('If so you may want to %s now.', true), $html->link(__('sign up', true), array('action'=>'register')));?></p>

		<h2><?php __('Forgotten Your Password?');?></h2>
		<p><?php echo sprintf(__('Click here to %s.', true), $html->link(__('reset your password', true), array('action'=>'reset')));?>
		</p>
	</div>
	<div class='clearboth'> </div>
</div>
