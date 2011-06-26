<div id="ending-soon" class="box">
	<div class="f-top clearfix"><h2><?php __('Live Auctions'); ?></h2>
	<p>Các sản phẩm đang đấu giá</p></div>
	<div class="f-repeat clearfix">
		<div class="content">
			<?php if(!empty($auctions)) : ?>
				<?php echo $this->element('auctions'); ?>
				<?php echo $this->element('pagination'); ?>
			<?php else: ?>
				<div class="align-center off_message"><p><?php __('There are no live auctions at the moment.');?></p></div>
			<?php endif; ?>
		</div>
	<br class="clear_l">
	<div class="crumb_bar">
			<?php
			$html->addCrumb(__('Live Auctions', true), '/auctions');
			echo $this->element('crumb_auction');
			?>
	</div>
	</div>
</div>
