<?php
	$html->addCrumb(__('Dash Board',true), '/users');
	$html->addCrumb(__('My Bid Butlers', true), '/bidbutlers');
	echo $this->element('crumb_user');
	?>

	<h1><?php __('My Bid Butlers');?></h1>
	
	<?php if(!empty($bidbutlers)): ?>
		<?php echo $this->element('pagination'); ?>
	<div id="bidbutler" class="module">
    	<div class="main_bar">
		</div>
		<div class="sub_bar">
			<span class="prd">SẢN PHẨM</span>
			<span class="curbid">GIÁ HIỆN TẠI / SỐ LƯỢNG BID</span>
			<span class="time">THỜI GIAN</span>
			<span class="bid_left"> LƯỢNG BID CÒN LẠI </span>
		</div>
	<ul id="more_product_content">
		<?php foreach ($bidbutlers as $bidbutler):?>
			<li class="upcoming_product auction-item" title="<?php echo $bidbutler['Auction']['id'];?>" id="auction_<?php echo $bidbutler['Auction']['id'];?>">
			<div class="up_photo">
				<a href="/auctions/view/<?php echo $bidbutler['Auction']['id']; ?>">
				<?php if(!empty($bidbutler['Auction']['Product']['Image'][0]['image'])):?>
					<?php if(!empty($bidbutler['Auction']['Product']['Image'][0]['ImageDefault']['image'])) : ?>
						<?php echo $html->image('default_images/'.$appConfigurations['serverName'].'/thumbs/'.$bidbutler['Auction']['Product']['Image'][0]['ImageDefault']['image']); ?>
					<?php else: ?>
						<?php echo $html->image('product_images/thumbs/'.$bidbutler['Auction']['Product']['Image'][0]['image']); ?>
					<?php endif; ?>
				<?php else:?>
					<?php echo $html->image('product_images/thumbs/no-image.gif');?>
				<?php endif;?>
				</a>
			</div>
			<div class="up_des"><?php echo $html->link($bidbutler['Auction']['Product']['title'], array('controller' => 'auctions','action' => 'view', $bidbutler['Auction']['id']));?>
				<p><?php echo strip_tags($text->truncate($bidbutler['Auction']['Product']['brief'], 100, '...', false, true));?></p>
            </div>
            <div class="up_cur">
			<?php if(!empty($bidbutler['Auction']['Product']['fixed'])) : ?>
			<?php echo $number->currency($bidbutler['Auction']['Product']['fixed_price'], $appConfigurations['currency']); ?>
				<span class="bid-price-fixed" style="display:none"><?php echo $number->currency($$bidbutler['Auction']['price'], $appConfigurations['currency']); ?></span>
			<?php else: ?>
				<div class="bid-price cur_price"><?php echo $number->currency($bidbutler['Auction']['price'], $appConfigurations['currency']); ?></div>
			<?php endif; ?>
				<span class="vnd">₫</span>
				<span class="cur_retail"><?php echo $number->currency($bidbutler['Auction']['Product']['rrp'], $appConfigurations['currency']); ?></span>
				<span class="cur_bidder"><a class="bid-bidder" href="#">Đang kiểm tra</a></span>
			</div>
			<div class="up_time"><div id="auctionLive_<?php echo $bidbutler['Auction']['id'];?>" class="timer countdown" title="<?php echo $bidbutler['Auction']['end_time'];?>">--:--:--</div></div>
			<div class="up_bid">
				<?php echo $bidbutler['Bidbutler']['bids']; ?>	
			</div>
			<div class="bid-message"></div>                      
		</li>
		<?php endforeach; ?>
	</ul>
	</div>
			<!--<tr>
				<td>
				<a href="/auctions/view/<?php echo $bidbutler['Auction']['id']; ?>">
				<?php if(!empty($bidbutler['Auction']['Product']['Image'])):?>
					<?php if(!empty($bidbutler['Auction']['Product']['Image'][0]['ImageDefault']['image'])) : ?>
						<?php echo $html->image('default_images/'.$appConfigurations['serverName'].'/thumbs/'.$bidbutler['Auction']['Product']['Image'][0]['ImageDefault']['image']); ?>
					<?php else: ?>
						<?php echo $html->image('product_images/thumbs/'.$bidbutler['Auction']['Product']['Image'][0]['image']); ?>
					<?php endif; ?>
				<?php else:?>
					<?php echo $html->image('product_images/thumbs/no-image.gif');?>
				<?php endif;?>
				</a>
				</td>
				<td>
					<?php echo $html->link($bidbutler['Auction']['Product']['title'], array('controller'=> 'auctions', 'action'=>'view', $bidbutler['Auction']['id'])); ?>
				</td>
				<?php if($appConfigurations['bidButlerType'] !== 'simple') : ?>
					<td>
						<?php echo $number->currency($bidbutler['Bidbutler']['minimum_price'], $appConfigurations['currency']); ?>
					</td>
					<td>
						<?php echo $number->currency($bidbutler['Bidbutler']['maximum_price'], $appConfigurations['currency']); ?>
					</td>
				<?php endif; ?>
				<td>
					<?php echo $bidbutler['Bidbutler']['bids']; ?>
				</td>
				<td>
					<?php echo $time->niceShort($bidbutler['Bidbutler']['created']); ?>
				</td>
				<td class="actions">
					<?php echo $html->link(__('Edit', true), array('action'=>'edit', $bidbutler['Bidbutler']['id'])); ?>
					| <?php echo $html->link(__('Delete', true), array('action'=>'delete', $bidbutler['Bidbutler']['id']), null, sprintf(__('Are you sure you want to delete this bid butler?', true))); ?>
				</td>
			</tr>-->

	<?php echo $this->element('pagination'); ?>

	<?php else:?>
		<p><?php __('You have no bid butlers at the moment.');?></p>
	<?php endif;?>
