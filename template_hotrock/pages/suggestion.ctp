<h1><?php __('Suggestion box');?></h1>

<p><?php __('Send us your suggestion using the suggestion form below');?>:</p>

<?php echo $form->create(null, array('url' => '/suggestion')); ?>

<fieldset>
	<legend></legend>

	<?php
	echo $form->input('name', array('label' => 'Name *'));
	echo $form->input('email', array('label' => 'Email *'));
	echo $form->input('phone');
	echo $form->input('message', array('label' => 'Suggestion *', 'type' => 'textarea'));

	echo $form->end(__('Send Suggestion', true));
	?>
</fieldset>
