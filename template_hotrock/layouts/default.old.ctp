<?php $head_ban = array('home'); ?>
<?php $left_col = array('home','users'); ?>
<?php $cat_panel = array("Auctions.view"); ?>
<?php $no_left = array('Pages'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $html->charset(); ?>
	<title>
		<?php echo $appConfigurations['name'];?>::
		<?php echo 'Đấu giá xu!' ?> 
	</title>
	<style type="text/css">
		img, a, input, button { behavior: url(/iepngfix.htc);}
	</style> 
	
	<?php
		if(!empty($meta_description)) :
			echo $html->meta('description', $meta_description);
		endif;
		if(!empty($meta_keywords)) :
			echo $html->meta('keywords', $meta_keywords);
		endif;
		
		echo $javascript->link('jquery/jquery-1.4.4.min');
		echo $javascript->link('jquery/s3Slider');
		echo $javascript->link('jquery/superfish');
		echo $javascript->link('jquery/jquery.jgrowl.js');
		echo $javascript->link('live');
		echo $javascript->link('app');
		
		echo $html->css('/css/reset');
		echo $html->css('/css/style');
		echo $html->css('/css/default');
		echo $html->css('/js/jquery/jquery.jgrowl');
		
		echo $scripts_for_layout;
	?>
</head>

<body>
	<div id="wraper">
		<?php echo $this->element('header');?>
        <div id="container">
        	<?php echo $this->element('feature');?>
            <div id="content">
            	<?php echo $content_for_layout; ?>
            </div>
        	 <?php echo $this->element('right');?>
             <?php echo $this->element('linkout');?>
        </div>
        <?php echo $this->element('footer');?>
    </div>
</body>
</html> 
