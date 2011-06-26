
<div class="auction_others">
<div class="a_title">
<h1>Nhưng phiên đấu giá khác đang diễn ra</h1>
</div>   
<ul class="ending_soon_other">
			<?php foreach($auctions_ending as $auction):?>
			<li align="center" class="auction-item" title="<?php echo $auction['Auction']['id'];?>" id="auction_<?php echo $auction['Auction']['id'];?>">
				<div class="step_tag">
					<img src="/images/<?php echo $auction['Auction']['price_step'];?>d.gif"/>
				</div>
						
				<span class="ao_head"><h5><?php echo $html->link($auction['Product']['title'], array('controller'=>'auctions','action' => 'view', $auction['Auction']['id']));?></h5></span>
				<div class="ao_content">
					<div class="ao_img">
								<a href="/auctions/view/<?php echo $auction['Auction']['id']; ?>">
								<?php if(!empty($auction['Auction']['image'])):?>
									<?php echo $html->image($auction['Auction']['image'],array('class'=>'prd_img', 'width'=>'80')); ?>
								<?php else:?>
									<?php echo $html->image('product_images/thumbs/no-image.gif');?>
								<?php endif;?>
								</a>
					</div>
					<div class="ao_stats">
						<div class="ao_timer">
							<div id="timer_<?php echo $auction['Auction']['id'];?>" class="timer countdown" title="<?php echo $auction['Auction']['end_time'];?>">
								<span class="clock">- -</span>
								<span class="clock">- -</span>
								<span class="clock">- -</span>
							</div>
						</div>
						<div class="bid-bidder lastbidder">
							<?php echo $auction['LastBid']['username'];?>
						</div>
					</div>
					
				</div>
				<div class="clearBoth"></div>
				<div class="ao_bottom">
					<div class="right ao_bid">
						<div class="bid-now bidbutton clearfix">
							<?php if(!empty($auction['Auction']['isFuture'])) : ?>
								<div><?php echo $html->image('b-soon.gif');?></div>
							 <?php elseif(!empty($auction['Auction']['isClosed'])) : ?>
								<button class="bidb_disabled"/>
							 <?php else:?>
								 <?php if($session->check('Auth.User')):?>
									 <div class="bid-loading" style="display: none"><?php echo $html->image('ajax-arrows.gif');?></div>
									 <button class="bidb bid-button-link" value="<?php echo '/bid.php?id='.$auction['Auction']['id'];?>" title="<?php echo $auction['Auction']['id'];?>"  ></button>
									 <br/>
								<?php else:?>
									<button class="bidb bidb_login bid-button-login" href="<?php echo '/bid.php?id='.$auction['Auction']['id'];?>" title="<?php echo $auction['Auction']['id'];?>"  ></button>
									<!-- <div class="bid-button"><?php echo $html->link($html->image('b-login.gif'), array('controller' => 'users', 'action' => 'login'), null, null, false);?></div> -->
								<?php endif;?>
							<?php endif; ?>
						</div>
					</div>
					<div class="right ao_price jtextfill">
												<?php if(!empty($auction['Product']['fixed'])):?>
								<?php echo $number->currency($auction['Product']['fixed_price'], $appConfigurations['currency']);?>
								<span class="bid-price-fixed" style="display: none"><?php echo $number->currency($auction['Auction']['price'], $appConfigurations['currency']); ?></span>
							<?php else: ?>
								<span class="bid-price">
									<?php echo number_format($auction['Auction']['price'],0,',','.');?></span><span class="vnd">&#x20ab;</span>
							<?php endif; ?>
					</div>
				</div>
			</li>
			<?php endforeach; ?>
</ul>
</div>
