<div id="ending-soon" class="box">
	<?php
		$html->addCrumb(__('Closed Auctions', true), '/auctions/closed');
		echo $this->element('crumb_auction');
	?>
	<div class="f-top clearfix"><h2><?php __('Closed Auctions'); ?></h2>
	<p class="description">Những phiên đấu giá này đã kết thúc. Hãy đón chờ những phiên đấu giá hấp dẫn khác trong thời gian tới.</p></div>
	<div class="f-repeat clearfix">
		<div class="content">
		
			<?php if(!empty($auctions)) : ?>
				<?php if(!empty($appConfigurations['endedLimit'])) : ?>
				<p><strong><?php __('Showing the last');?> <?php echo $appConfigurations['endedLimit']; ?> <?php __('auctions.');?></strong></p>	
				<?php else : ?>	
				<?php endif; ?>
					
				<?php echo $this->element('auction_closed'); ?>
					
				<?php echo $this->element('pagination'); ?>

			<?php else: ?>
				<div class="align-center off_message"><p><?php __('There are no closed auctions at the moment.');?></p></div>
			<?php endif; ?>
		</div>
	<br class="clear_l">
	<div class="crumb_bar">
			<?php
			echo $this->element('crumb_auction');
			?>
	</div>
	</div>
	<div class="f-bottom-top clearfix"><p class="page_top"><a href="#" id="link_to_top"><?php echo __('PAGE TOP',true);?></a></p></div>
</div>