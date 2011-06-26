	<div class="crumb_bar">
			<?php
			$html->addCrumb(__('Các phiên đấu giá sắp kết thúc', true), '/auctions/');
			//echo $this->element('crumb_auction');
			?>
	</div>
<div id="ending-soon" class="box">
	<div class="f-repeat clearfix">
		<div class="content">
		
			<?php if(!empty($auctions_end_soon)) : ?>
			<!--
				<div class="filter_bar">
				 	<strong>Truy cứu trong danh mục:</strong> Tất cả <img src="/images/arrow_dropdown.png">
				</div>	
				<div class="sep_bar"></div>
				<div class="clearBoth"></div>
			-->
				<div id="ending-soon" class="module">
	<div class="boxIndent">
		<div class="clear">
			<table id="product_list" align="center">
				<tr>
				<?php $i=0;?>
				<?php foreach($auctions_end_soon as $auction):?>
					<td align="center" class="auction-item <?php if ($auction['Auction']['featured']) echo 'hot'; elseif ($auction['Auction']['special']=='Valentine') echo 'val';?>" title="<?php echo $auction['Auction']['id'];?>" id="auction_<?php echo $auction['Auction']['id'];?>">
						<div class="product">
							<div class="bp_tag poshytip" title="Giá trị mỗi lần bid là <i><?php echo $auction['Auction']['bp_cost'];?>xu</i>" ><?php echo $auction['Auction']['bp_cost'];?></div>
							<?php if (!$auction['Auction']['nail_bitter']):?>
							<div class="bidomatic">
								<img src="/images/autobid.png" class="poshytip" title="Có thể sử dụng <br/><i> Bid-o-matic!</i>"/>
							</div>
							<?php endif;?>  
							<div class="item_des">
							
								<div class="step_tag">
									<img src="/images/<?php echo $auction['Auction']['price_step'];?>d.gif"/>
								</div>
								<h5><?php echo $html->link($auction['Product']['title'], array('action' => 'view', $auction['Auction']['id']));?></h5>
				
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
									<span class="bid-price-fixed" style="display: none"><?php echo $number->currency($auction['Auction']['price'], $appConfigurations['currency']); ?></span>
								<?php else: ?>
									<span class="bid-price">
										<?php echo number_format($auction['Auction']['price'],0,',','.');?></span><span class="vnd">&#x20ab;</span>
								<?php endif; ?>
							</div>
							<div class="bid-bidder lastbidder"><?php echo $auction['LastBid']['username']?></div>
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
				

			<?php else: ?>
				<div class="align-center off_message"><p><?php __('There are no live auctions at the moment.');?></p></div>
			<?php endif; ?>
		</div>
	<br class="clear_l">

	</div>
</div>

<?php if(!empty($auctions_live)) : ?>
<div id="more_product" class="module">
                            <div class="main_bar">
                            <h3><span>Các phiên khác đang diễn ra</span></h3>
                            </div>
                            <div class="sub_bar">
                            <span class="prd">SẢN PHẨM</span>
                            <span class="curbid">GIÁ HIỆN TẠI / SỐ LƯỢNG BID</span>
                            <span class="time">THỜI GIAN</span>
                            </div>
                            <ul id="more_product_content">
                            	<?php foreach($auctions_live as $auction):?>
                                <li class="upcoming_product auction-item" title="<?php echo $auction['Auction']['id'];?>" id="auction_<?php echo $auction['Auction']['id'];?>">
                                    <div class="up_photo">
                                    <a href="/auctions/view/<?php echo $auction['Auction']['id']; ?>">
										<?php if(!empty($auction['Auction']['image'])):?>
											<?php echo $html->image($auction['Auction']['image']); ?>
										<?php else:?>
											<?php echo $html->image('product_images/thumbs/no-image.gif');?>
										<?php endif;?>
										</a>
									</div>
                                    <div class="up_des"><?php echo $html->link($auction['Product']['title'], array('action' => 'view', $auction['Auction']['id']));?>
                                        <p><?php echo strip_tags($text->truncate($auction['Product']['brief'], 100, '...', false, true));?></p>
                                    </div>
                                    <div class="up_cur">
	                                    <?php if(!empty($auction['Product']['fixed'])) : ?>
										<?php echo $number->currency($auction['Product']['fixed_price'], $appConfigurations['currency']); ?>
										<span class="bid-price-fixed" style="display:none"><?php echo $number->currency($auction['Auction']['price'], $appConfigurations['currency']); ?></span>
											<?php else: ?>
												<div class="bid-price cur_price"><?php echo $number->currency($auction['Auction']['price'], $appConfigurations['currency']); ?></div>
											<?php endif; ?>
                                        <span class="vnd">₫</span>
                                        <span class="cur_retail"><?php echo $number->currency($auction['Product']['rrp'], $appConfigurations['currency']); ?></span>
                                        <span class="cur_bidder"><a class="bid-bidder" href="#"><?php echo $auction['LastBid']['username']?></a></span>
                                    </div>
                                    <div class="up_time"><div id="auctionLive_<?php echo $auction['Auction']['id'];?>" class="timer countdown" title="<?php echo $auction['Auction']['end_time'];?>">--:--:--</div></div>
                                    <div class="up_bid">	
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
                                </li>
                                <?php endforeach;?>
                               
                            </ul>
                        </div>
                        
<?php endif; ?>
