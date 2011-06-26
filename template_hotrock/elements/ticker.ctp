<div class="auction-ticker clearfix">
	<?php $auctions = $this->requestAction('/auctions/gettickerauctions/10'); ?>

	<?php if(!empty($auctions)) : ?>
		<div class="content">
			<marquee behavior="scroll" direction="left">
			<?php foreach($auctions as $auction) : ?>
				<?php if(!empty($auction['Product']['Image'][0]['image'])) : ?>
					<?php echo $html->link($text->truncate($auction['Product']['title'],20), array('action' => 'view', $auction['Auction']['id']));?> <strong>End price:</strong> <span class="end-price"><?php echo $number->currency($auction['Auction']['price'], $appConfigurations['currency']); ?></span> <strong>Savings:</strong> <span class="savings"><?php echo $auction['Auction']['savings']['percentage'] ;?> %</span>
					<?php endif; ?>
			<?php endforeach; ?>
			</marquee>
		</div>
	<?php endif; ?>
</div>