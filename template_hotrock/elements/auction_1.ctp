<div class="auction-type-1 auction-item" id="auction_<?php echo $auction['Auction']['id'];?>" title="<?php echo $auction['Auction']['id'];?>">
	<div class="auction-title"><a href="#"><?php echo $auction['Product']['title'];?></a></div>
	<div class="auction-time"> 00 : 00 : 00 </div>
	<div class="auction-image-container"><a href="#"><?php if(!empty($auction['Auction']['image'])):?>
										<?php echo $html->image($auction['Auction']['image'],array('class'=>'prd_img', 'width'=>'163', 'height' => '129')); ?>
									<?php else:?>
										<?php echo $html->image('product_images/thumbs/no-image.gif');?>
									<?php endif;?></a></div>
	<div class="auction-price-container">
		Giá hiện tại : <span class="auction-price money"><?php echo $auction['Auction']['price'];?></span> VNĐ
	</div>
	<div class="auction-bidder-container">
		Người đấu : <span class="auction-bidder"> - </span>
	</div>
	<div class="auction-rrp-container">
		Giá trị thực : <span class="auction-rrp money"><?php echo $auction['Product']['rrp']?></span> VNĐ
	</div>
	<div class="auction-bid-container">
		<a class="auction-bid-link" href="<?php echo $auction['Auction']['id']?>">Đấu</a>
	</div>
	<div class="auction-bid-cost"><?php echo $auction['Auction']['bp_cost']?>đ</div>
</div>