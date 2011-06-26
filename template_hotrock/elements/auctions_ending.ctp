<div id="endingAuctions">
	<h2> Các phiên đấu giá sắp kết thúc </h2>
	<br>
	<div class="boxIndent">
	<div class="clear">
		<table id="product_list" align="center">
			<tr>
			<?php foreach($auctions_ending as $auction):?>
			<td align="center" class="auction-item" title="<?php echo $auction['Auction']['id'];?>" id="auction_<?php echo $auction['Auction']['id'];?>">
					<div class="product">
						<div class="bp_tag poshytip" title="Giá trị mỗi lần bid là <i><?php echo $auction['Auction']['bp_cost'];?>bp</i>" ><?php echo $auction['Auction']['bp_cost'];?></div>
						<?php if (!$auction['Auction']['nail_bitter']):?>
						<div class="bidomatic">
							<img src="/images/autobid.png" class="poshytip" title="Có thể sử dụng <br/><i> Bid-o-matic!</i>"/>
						</div>
						<?php endif;?>  
						<div class="item_des">
							
							<div class="step_tag">
								<img src="/images/<?php echo $auction['Auction']['price_step'];?>d.png"/>
							</div>
							<h5><?php echo $html->link($auction['Product']['title'], array('controller'=>'auctions','action' => 'view', $auction['Auction']['id']));?></h5>
				
								<a href="/auctions/view/<?php echo $auction['Auction']['id']; ?>">
								<?php if(!empty($auction['Auction']['image'])):?>
									<?php echo $html->image($auction['Auction']['image'],array('class'=>'prd_img', 'width'=>'105')); ?>
								<?php else:?>
									<?php echo $html->image('product_images/thumbs/no-image.gif');?>
								<?php endif;?>
								</a>
								<p class="item_price"><?php echo $number->currency($auction['Product']['rrp'], $appConfigurations['currency']);?></p>

						</div>
						<div class="item_time">
						<div id="timer_<?php echo $auction['Auction']['id'];?>" class="timer countdown" title="<?php echo $auction['Auction']['end_time'];?>">--:--:--</div>
						</div>
						<div class="cur_price">
							<?php if(!empty($auction['Product']['fixed'])):?>
								<?php echo $number->currency($auction['Product']['fixed_price'], $appConfigurations['currency']);?>
								<span class="bid-price-fixed" style="display: none"><?php echo number_format($auction['Auction']['price'],0,',','.');?></span>
							<?php else: ?>
								<span class="bid-price">
									<?php echo $auction['Auction']['price'];?></span><span class="vnd">&#x20ab;</span>
							<?php endif; ?>
						</div>
						<div class="bid-bidder lastbidder"><?php echo $auction['LastBid']['username'];?></div>
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
						<div class="bid-message"></div>
					</div>
				</td>
				<?php endforeach; ?>
				</tr>
			</table>
		</div>
	</div>
</div>