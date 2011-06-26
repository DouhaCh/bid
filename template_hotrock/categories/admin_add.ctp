<?php
$html->addCrumb(__('Settings', true), '/admin/settings');
$html->addCrumb(Inflector::humanize($this->params['controller']), '/admin/'.$this->params['controller']);
$html->addCrumb(__('Add', true), '/admin/'.$this->params['controller'].'/add');
echo $this->element('admin/crumb');
?>

<div class="categories form">

<?php echo $form->create('Category', array('type' => 'file'));?>
	<fieldset>
 		<legend><?php __('Add Category');?></legend>

	<label for="CategoryParentId"><?php __('Parent Category');?></label>
	<?php echo $form->select('parent_id', $parentCategories, null, array(), 'No Parent (top level)'); ?>
	<?php
		echo $form->input('name', array('label' => __('Name *', true)));
		echo $form->input('meta_description');
		echo $form->input('meta_keywords');
	?>
	<?php echo $form->input('image', array('type' => 'file'));?>
	</fieldset>
<?php echo $form->end(__('Add Category >>', true));?>
</div>

<?php echo $this->element('admin/required'); ?>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('<< Back to categories', true), array('action' => 'index'));?></li>
	</ul>
</div>
