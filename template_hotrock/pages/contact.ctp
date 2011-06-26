<h1><?php __('Contact Us');?></h1>

<p><?php __('Contact us using the contact form below');?>:</p>

<?php echo $form->create(null, array('url' => '/contact')); ?>

<fieldset>
	<legend></legend>

	<?php
	echo $form->input('name', array('label' => 'Name *'));
	echo $form->input('email', array('label' => 'Email *'));
	if(!empty($departments)) :
		echo $form->input('department_id', array('label' => 'Department *', 'empty' => 'Select', ));
	endif;
	echo $form->input('phone');
	echo $form->input('message', array('label' => 'Enquiry *', 'type' => 'textarea'));

	echo $form->end(__('Contact Us', true));
	?>
</fieldset>
