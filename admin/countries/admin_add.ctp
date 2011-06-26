<?php
$html->addCrumb(__('Settings', true), '/admin/settings');
$html->addCrumb(__('Countries', true), '/admin/countries');
$html->addCrumb(__('Add', true), '/admin/'.$this->params['controller'].'/add');
echo $this->element('admin/crumb');
?>

<div class="countries form">
<?php echo $form->create();?>
	<fieldset>
 		<legend><?php __('Add a Country');?></legend>
	<?php
		echo $form->input('name');
	?>
	</fieldset>
<?php echo $form->end(__('Add Country', true));?>
</div>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('<< Back to countries', true), array('action' => 'index'));?></li>
	</ul>
</div>
