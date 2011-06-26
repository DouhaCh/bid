<div id="more_product" class="module">
                            <div class="sub_bar">
                            <span class="prd">SẢN PHẨM</span>
                            <span class="curbid">GIÁ HIỆN TẠI / SỐ LƯỢNG BID</span>
                            <span class="time">THỜI GIAN</span>
                            </div>
                            <ul id="product_list">
                            	<?php foreach($auctions as $auction):?>
                                <li class="upcoming_product auction-ended" title="<?php echo $auction['Auction']['id'];?>" id="auction_<?php echo $auction['Auction']['id'];?>">
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
                                        <span class="vnd"></span>
                                        <span class="cur_retail"><?php echo $number->currency($auction['Product']['rrp'], $appConfigurations['currency']); ?></span>
                                        <span class="cur_bidder"><a class="bid-bidder" href="#"><?php echo $auction['LastBid']['username']?></a></span>
                                    </div>
                                    <div class="up_time"><div class="auc_ended"></div></div>
                                  
                                </li>
                                <?php endforeach;?>
                               
                            </ul>
                        </div>
                        