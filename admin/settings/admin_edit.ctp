<?php
$html->addCrumb(__('Settings', true), '/admin/settings');
$html->addCrumb(__('Edit', true), '/admin/'.$this->params['controller'].'/edit/'.$this->data['Setting']['id']);
echo $this->element('admin/crumb');
?>

<div class="settings form">
<?php echo $form->create('Setting');?>
	<fieldset>
 		<legend><?php echo sprintf(__('Edit the %s setting', true), Inflector::humanize($this->data['Setting']['name']));?></legend>
	<?php
		echo $form->input('id');
		echo $form->hidden('name');
		echo $form->input('value', array('label' => __('Value *', true)));
	?>
	</fieldset>
<?php echo $form->end(__('Save Changes', true));?>
</div>

<?php echo $this->element('admin/required'); ?>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('<< Back to settings', true), array('action'=>'index'));?></li>
	</ul>
</div>
