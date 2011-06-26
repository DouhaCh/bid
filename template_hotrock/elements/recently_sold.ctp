<?php $auctions = $this->requestAction('/auctions/getlatestsold/5');?>

<div class="random-testimonial module">
	<div class="module_box">
		<div class="module_header">Sản phẩm mới bán</div>
		<div class="module_content">
			<ul>
				<?php foreach ($auctions as $auction):?>
				
				<li style="padding-bottom:10px;">
					<div style="text-align:center;padding:3px;color:#36AFCB;font-weight:bold"><a href="/auctions/view/<?php echo $auction['Auction']['id']?>"><?php echo $auction['Product']['title'];?></a></div>
					<div style="float:left;width:60px;height:60px"><a href="/auctions/view/<?php echo $auction['Auction']['id']?>"><img width="60"src="/img/<?php echo $auction['Auction']['image'];?>"/></a></div>
					<div style="float:left;width:120px">
						<div style="color: #FF3706;font-size: 20px;">
							<?php echo number_format($auction['Auction']['price']);?>₫
						</div>
						<div style="font-size: 10px;text-decoration: line-through">
							<?php echo number_format($auction['Product']['rrp']);?>₫
						</div>
						<div style="color: #FF3706;font-size:11px;line-height:11px;">
							<?php echo $auction['LastBid']['username'];?>
						</div>
					</div>
					<div class="clearBoth"></div>
				</li>
				<?php endforeach;?>
			</ul>
			<div class="clearBoth"></div>
		</div>
		<div class="module_footer_1"></div>
	</div>
	
    
</div>