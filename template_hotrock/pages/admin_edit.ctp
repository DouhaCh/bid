<?php
$html->addCrumb(__('Manage Content', true), '/admin/pages');
$html->addCrumb(Inflector::humanize($this->params['controller']), '/admin/'.$this->params['controller']);
$html->addCrumb(__('Edit', true), '/admin/'.$this->params['controller'].'/edit/'.$this->data['Page']['id']);
echo $this->element('admin/crumb');
?>

<div class="pages form">
<?php echo $form->create('Page');?>
	<fieldset>
 		<legend><?php __('Edit a Page');?></legend>
	<?php
		echo $form->input('id');
		echo $form->input('name', array('label' => __('Page Name *', true)));
		echo $form->input('title', array('label' => __('Meta Title *', true)));
	?>

	<label for="PageContent"><?php __('Content *');?></label>
	<?php echo $form->input('Page.content',array('class' => 'mceEditor')); ?>

	<?php
		echo $form->input('meta_description');
		echo $form->input('meta_keywords');
		echo $form->input('top_show', array('label' => __('Show this page in the top menu?', true)));
		echo $form->input('bottom_show', array('label' => __('Show this page in the bottom menu?', true)));
	?>

	</fieldset>
<?php echo $form->end(__('Save Changes', true));?>
</div>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Delete', true), array('action' => 'delete', $form->value('Page.id')), null, sprintf(__('Are you sure you want to delete this page?', true))); ?></li>
		<li><?php echo $html->link(__('<< Back to pages', true), array('action' => 'index'));?></li>
	</ul>
</div>
