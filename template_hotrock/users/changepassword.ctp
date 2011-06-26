<?php
$html->addCrumb(__('Dash Board',true), '/users');
$html->addCrumb(__('Change Password',true), '/users/changepassword');
echo $this->element('crumb_user');
?>

<?php echo $form->create('User', array('url' => '/users/changepassword'));?>
<div class="InputForm">
<?php echo $form->create('User');?>
	<fieldset>
		<legend><?php __('Change Password');?></legend>
		<p>Để thay đổi mật khẩu, bạn phải nhập mật khẩu cũ và mật khẩu mới 2 lần.</p>
		<?php
			echo $form->input('old_password', array('label' => 'Mật khẩu cũ:', 'value' => '', 'type' => 'password', 'div' => 'rInput'));
			echo $form->input('before_password', array('label' => 'Mật khẩu mới:', 'value' => '', 'type' => 'password', 'label' => __('New Password', true), 'div' => 'rInput'));
			echo $form->input('retype_password', array('label' => 'Nhập lại mật khẩu mới:', 'value' => '', 'type' => 'password', 'div' => 'rInput'));
			echo $form->submit('Thay đổi mật khẩu', array('type' => 'button'));
			echo '</fieldset>';
			echo $form->end();
		?>
	
</div>
