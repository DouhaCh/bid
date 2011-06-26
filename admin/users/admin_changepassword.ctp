<div id="leftcol">
    <?php echo $this->element('menu_user', array('cache' => Configure::read('Cache.time')));?>
</div>
<div id="rightcol">
	<?php
	$html->addCrumb(__('Change Password', true), '/users/changepassword');
	echo $this->element('crumb_user');
	?>

	<?php echo $form->create('User', array('url' => '/users/changepassword'));?>
		<fieldset>
			<legend><?php __('Change Password');?></legend>
			<p>To change your password enter in your old password and your new password twice.</p>
			<?php
				echo $form->input('old_password', array('value' => '', 'type' => 'password'));
				echo $form->input('before_password', array('value' => '', 'type' => 'password', 'label' => __('New Password', true)));
				echo $form->input('retype_password', array('value' => '', 'type' => 'password'));
			?>
		</fieldset>
	<?php echo $form->end(__('Change Password', true));?>
</div>
