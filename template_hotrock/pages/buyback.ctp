<fieldset>
	<?php echo $form->create('Buyback', array('url' => array('controller' => 'pages', 'action' => 'buyback')));?>

		<?php echo $form->input('product', array('options' => $products));?>
		<?php echo $form->input('brand');?>
		<?php echo $form->input('power', array('options' => $powers));?>
		<?php echo $form->input('condition', array('options' => $conditions, 'label' => __('Overall Conditions',true)));?>
		<?php echo $form->input('have', array('options' => $have, 'multiple' => 'checkbox', 'label' => __('I have',true)));?>
		<?php echo $form->input('additional_notes', array('type' => 'textarea', 'label' => __('Additional Notes(Technical Specs)', true)));?>
		<?php echo $form->input('name');?>
		<?php echo $form->input('email', array('label' => __('Email Address',true)));?>

	<?php echo $form->end(__('OK, I\'m ready to make some cash', true));?>
</fieldset>