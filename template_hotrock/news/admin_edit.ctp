<?php
$html->addCrumb(__('Manage Content', true), '/admin/pages');
$html->addCrumb(Inflector::humanize($this->params['controller']), '/admin/'.$this->params['controller']);
$html->addCrumb(__('Edit', true), '/admin/'.$this->params['controller'].'/edit/'.$this->data['News']['id']);
echo $this->element('admin/crumb');
?>

<div class="news form">
<?php echo $form->create('News');?>
	<fieldset>
 		<legend><?php __('Edit a News Article');?></legend>
	<?php
		echo $form->input('id');
		echo $form->input('title', array('label' => 'Title *'));
		echo $form->input('newstype_id', array('type'=>'select', 'label' => 'Type*'));
		echo $form->input('priority', array('options' => array(1,2,3,4,5), 'label' => 'Priority*'));
		echo $form->input('brief', array('label' => 'Brief *'));
		echo $form->input('content', array('label' => 'Content *', 'class' => 'mceEditor'));
		echo $form->input('meta_description');
		echo $form->input('meta_keywords');
	?>
	</fieldset>
<?php echo $form->end(__('Save Article', true));?>
</div>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('<< Back to news articles', true), array('action' => 'index'));?></li>
	</ul>
</div>
