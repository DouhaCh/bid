<?php
$html->addCrumb(__('Manage Content', true), '/admin/pages');
$html->addCrumb(__('Testimonial', true), '/admin/testimonials');
$html->addCrumb(__('Edit', true), '/admin/'.$this->params['controller'].'/edit/'.$this->data['Testimonial']['id']);
echo $this->element('admin/crumb');
?>

<div class="news form">
<?php echo $form->create('Testimonial');?>
	<fieldset>
 		<legend><?php __('Edit a Testimonial');?></legend>
	<a href="#"><img src=" <?php echo $this->data['Testimonial']['img']; ?>" width="100" height="100" style="padding:0;margin:2px 5px 0px 0;float:left" /></a>
	<?php
		echo $form->input('id');		
		echo $form->input('content', array('label' => 'Content *', 'class' => 'mceEditor', 'type' => 'textarea'));
	?>
	</fieldset>
<?php echo $form->end(__('Save Testimonial', true));?>
</div>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('<< Back to testimonial', true), array('action' => 'index'));?></li>
	</ul>
</div>
