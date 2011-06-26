<?php
$html->addCrumb(__('Manage Testimonials', true), '/admin/testimonials');
$html->addCrumb(__('Add', true), '/admin/'.$this->params['controller'].'/add');
echo $this->element('admin/crumb');
?>

<div class="news form">
<?php echo $form->create('Testimonial', array('type'=>'file'));?>
	<fieldset>
 		<legend><?php __('Add a new testimonial');?></legend>
	<?php
		echo $form->input('image1', array('type' => 'file', 'label'=>'Insert Image'));		
		echo $form->input('user_id', array('label' => 'User ID'));
		echo $form->input('auction_id', array('label' => 'Auction ID'));		
		echo $form->input('content', array('label' => 'Content', 'class' => 'mceEditor', 'type' => 'textarea'));		
	?>
	</fieldset>
<?php echo $form->end(__('Add Testimonial', true));?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('<< Back to testimonials', true), array('action' => 'index'));?></li>
	</ul>
</div>