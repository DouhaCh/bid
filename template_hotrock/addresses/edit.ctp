<?php
$html->addCrumb(__('Dash Board',true), '/users');
$html->addCrumb(__('My Addresses'), '/addresses');
$html->addCrumb(__('Edit'), '/addresses/edit/'.$name);
echo $this->element('crumb_user');
?>

<h1><?php __('Update an Address');?></h1>

<?php echo $form->create(null, array('url' => '/addresses/edit/'.$name));?>
	<fieldset>
	<?php
		echo $form->input('id');
		echo $form->input('name', array('label' => __('Name *', true)));
		echo $form->input('address_1', array('label' => __('Address (line 1) *', true)));
		echo $form->input('address_2', array('label' => __('Address (line 2)', true)));
		echo $form->input('suburb', array('label' => __('Suburb / Town', true)));
		echo $form->input('city', array('label' => __('City / State / County *', true)));
		echo $form->input('postcode', array('label' => __('Post Code / Zip Code *', true)));
		echo $form->input('country_id', array('label' => __('Country *', true), 'empty' => 'Select'));
		echo $form->input('phone', array('label' => __('Phone', true)));
		echo $form->input('update_all', array('type' => 'checkbox', 'label' => __('Make all your addresses this address.', true)));
	?>
	</fieldset>
<?php echo $form->end('Update Address');?>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('<< Back to your addresses', true), array('action' => 'index'));?></li>
	</ul>
</div>