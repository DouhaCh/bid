<?php if(!empty($auctions_tag)) : ?>
<div id="tag" class="module">
	<div class="boxIndent">
		<div class="clear">
			<table id="product_list" align="center">
				<tr>
				<?php $i=0;?>
				<?php foreach($auctions_tag as $auction):?>
				<td align="center" class="auction-item" title="<?php echo $auction['Auction']['id'];?>" id="auction_<?php echo $auction['Auction']['id'];?>">
					<div class="product">
						<div class="item_des">
							<div class="step_tag"></div>
							<h5><?php echo $html->link($auction['Product']['title'], array('action' => 'view', $auction['Auction']['id']));?></h5>
				
								<a href="/auctions/view/<?php echo $auction['Auction']['id']; ?>">
								<?php if(!empty($auction['Auction']['image'])):?>
									<?php echo $html->image($auction['Auction']['image'],array('class'=>'corner iradius6', 'width'=>'105')); ?>
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
								<span class="bid-price-fixed" style="display: none"><?php echo $number->currency($auction['Auction']['price'], $appConfigurations['currency']); ?></span>
							<?php else: ?>
								<span class="bid-price">
									<?php echo $auction['Auction']['price'];?></span><span class="vnd">&#x20ab;</span>
							<?php endif; ?>
						</div>
						<div class="bid-bidder lastbidder">&#x110;ang ki&#x1ec3;m tra</div>
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
				<?php $i++;?>
				<?php if ($i%4==0):?></tr><tr><?php endif;?>
				<?php endforeach; ?>
				<?php if ($i<4):?>
					<?php for ($j=0; $j<4-$i; $j++): ?>
					<td class="td_disabled"></td>
					<?php endfor;?>
				<?php endif;?>
				</tr>
			</table>
		</div>
	</div>
</div>
<?php endif; ?>