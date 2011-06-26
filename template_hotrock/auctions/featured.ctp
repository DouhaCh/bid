<div id="ending-soon" class="box">
	<?php
		$html->addCrumb(__('Featured Auctions', true), '/auctions/featured');
		echo $this->element('crumb_auction');
	?>
	<div class="f-top clearfix"><h2>Những phiên đấu giá hot</h2>
	<p class="description">iPhone 4, iPad 3G, tivi HD ... những món hàng hấp dẫn mà ai cũng ước mong sở hữu !</p></div>
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
				<div class="align-center off_message"><p><?php __('There are no featured auctions at the moment.');?></p></div>
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