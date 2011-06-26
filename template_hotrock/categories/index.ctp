<?php
//$html->addCrumb(__('Categories', true), '/categories');
//echo $this->element('crumb_auction');
?>

<h1><?php __('Categories'); ?></h1>
<?php if(!empty($categories)) : ?>
	<?php //echo $this->element('pagination'); ?>
	<?php echo $this->element('categories'); ?>
	<?php //echo $this->element('pagination'); ?>
<?php else: ?>
	
<?php endif; ?>
