
<?php
$html->addCrumb(__('Categories', true), '/categories');
if(!empty($parents)) :
	foreach($parents as $parent) :
		$html->addCrumb($parent['Category']['name'], '/categories/view/'.$parent['Category']['id']);
	endforeach;
endif;
echo $this->element('crumb_auction');
?>
<h1><?php echo $category['Category']['name']; ?></h1>

<?php if(!empty($categories)) : ?>
	<?php if(!empty($auctions)) : ?>
	<h2><?php __('Subcategories'); ?></h2>
	<?php endif; ?>
	<?php echo $this->element('categories'); ?>
<?php endif; ?>

<div id="ending-soon" class="box">
	<div class="f-repeat clearfix">
		<div class="content">
		
			<?php if(!empty($auctions)) : ?>
				<?php if(!empty($appConfigurations['endedLimit'])) : ?>
				<p><strong><?php __('Showing the last');?> <?php echo $appConfigurations['endedLimit']; ?> <?php __('auctions.');?></strong></p>	
				<?php else : ?>	
				<?php endif; ?>
					
				<?php echo $this->element('auctions'); ?>
					
					<?php echo $this->element('pagination'); ?>

			<?php else: ?>
				<div class="align-center off_message"><p><?php __('There are no auctions in this category at the moment.');?></p></div>
			<?php endif; ?>
		</div>
	<br class="clear_l">
	<div class="crumb_bar">
		<?php
			echo $this->element('crumb_auction');
		?>
	</div>
	</div>
	<div class="f-bottom-top clearfix"><p class="page_top"><a href="#" id="link_to_top">PAGE TOP</a></p></div>
</div>
