<?php
$html->addCrumb(__('Manage News', true), '/admin/news');
$html->addCrumb(Inflector::humanize($this->params['controller']), '/admin/'.$this->params['controller']);
$html->addCrumb(__('Add', true), '/admin/'.$this->params['controller'].'/add');
echo $this->element('admin/crumb');
?>

<div class="news form">
<?php echo $form->create('News');?>
	<fieldset>
 		<legend><?php __('Add a News Article');?></legend>
	<?php
		echo $form->input('title', array('label' => 'Title *'));
		echo $form->input('brief', array('label' => 'Brief *'));
		echo $form->input('content', array('label' => 'Content *'));
		echo $form->input('meta_description');
		echo $form->input('meta_keywords');
	?>
	</fieldset>
<?php echo $form->end(__('Add Article', true));?>
</div>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('<< Back to news articles', true), array('action' => 'index'));?></li>
	</ul>
</div>
