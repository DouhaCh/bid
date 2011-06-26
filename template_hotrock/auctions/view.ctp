<script>
	$(document).ready( function() {
						
		$(document).ready(function(){
			$('.rInput input').poshytip({
				className: 'tip-darkgray',
				showOn: 'focus',
				alignTo: 'target',
				alignX: 'left',
				alignY: 'center',
				offsetX: 10
			});


			$('#slider').slider({
				range: "min",
				value: 300,
				min: 1,
				max: 500,
				slide: function( event, ui ) {
					return false;
					$( "#amount" ).html(ui.value);
				}

			});
		});
		
		<?php
			if(!empty($bidbutler))
				echo "$('#autobidInfo').show();";
			else
				echo "$('#autobidAdd').show();";
				
			if(!empty($bet))
				echo "$('#betInfo').show();";
			else
				echo "$('#betAdd').show();";
		?>
	});
	
	function updateEmo(id) {
		
			$("#CommentsEmo").attr('value',id);
			tb_remove();
			$(".emoSelected").attr('src','/img/emoticons/'+id+'.png');
			$(".emoSelected").attr('alt','/img/emoticons/'+id+'.png');
			return false;
	};
	
	
</script>

<input type="hidden" id="bp_cost" value="<?php echo $auction['Auction']['bp_cost'];?>"/>
               <div class="clear col2 auction<?php if ($auction['Auction']['closed']!=1):?>-item<?php else:?>-ended<?php endif;?>" title="<?php echo $auction['Auction']['id'];?>" id="auction_<?php echo $auction['Auction']['id'];?>">
                    <div class="item_main" >
                        <div id="item_title">
                            <h1 class="left"><?php echo $auction['Product']['title']; ?>
                            </h1>

                            <div class="right">
                                <iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2F1bid.vn&amp;layout=button_count&amp;show_faces=false&amp;width=96&amp;action=like&amp;font&amp;colorscheme=light&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:96px; height:21px;" allowTransparency="true"></iframe>
                            </div>
                            <div class="clearBoth">
                            </div>
                            <p class="pad10">Tag: 
                                <?php foreach ($tag as $tag_record):?>
								<a href="../auctions/tag/<?php echo $tag_record['Tag']['id'];?>">
									<?php echo $tag_record['Tag']['tag_name'];?>
								</a>
								<?php endforeach; ?>
                            </p>
                        </div>                    
                        <div class="clearBoth">
                        </div>
                      
                        	
               
                        <div class="wrapper">
                            <div id="des_left">
                            	<div id="auction_info">
                            		<div id="b_xu" class="badges poshytip" title="Mỗi lần bạn đặt bid, tài khoản của bạn sẽ bị trừ đi <?php echo $auction['Auction']['bp_cost'];?> XU"><?php echo $auction['Auction']['bp_cost'];?></div>
                            		
                            		<div id="b_sec" class="badges poshytip" title="Mỗi khi có đặt bid ở những giây cuối, đồng hồ sẽ tự động cộng thêm <?php echo $auction['Auction']['time_increment'];?> giây."><?php echo $auction['Auction']['time_increment'];?></div>
                            		<div id="b_step" class="badges poshytip" title="Mỗi khi có người đặt bid, giá sản phẩm sẽ tăng thêm <?php echo $auction['Auction']['price_step'];?> đồng"><?php echo $auction['Auction']['price_step'];?>₫</div>
                            		<?php if ($auction['Auction']['max_bid']>0):?><div id="b_limit" class="badges_2 poshytip" title="Mỗi thành viên chỉ được phép đặt tối đa <?php echo $auction['Auction']['max_bid'];?> lượt trong phiên đấu giá này"><?php echo $auction['Auction']['max_bid'];?></div><?php endif;?>
                            		<?php if (!$auction['Auction']['nail_bitter']):?><div id="b_auto" class="badges poshytip" title="Bạn được phép sử dụng Autobid trong phiên đấu giá này"></div><?php endif;?>
                                </div>   
                                <div id="slideshow-container">                       	
                                <div id="slideshow" class="img_big">
                                    <div class="auction-image">
										<?php if(!empty($auction['Auction']['image'])):?>
											<?php echo $html->image($auction['Auction']['image'], array('class'=>'productImageMax', 'border' => 0));?>
										<?php else:?>
											<?php echo $html->image('product_images/max/no-image.gif', array('border' => 0));?>
										<?php endif; ?>
									</div>
                                </div>
                                </div>
                                	<div class="clearBoth"></div>                                
                                <div id="thumbs">
	                                <ul class="thumbs noscript">
	                                	<?php if(!empty($auction['Product']['Image']) && count($auction['Product']['Image']) > 1):?>
	                                		<?php $i=0;$imgclass="img_small";?>
                            				<?php foreach($auction['Product']['Image'] as $image):?>
                            					<?php if($i==2) $imgclass="img_small img_last";?>
												<?php if(!empty($image['ImageDefault'])) : ?>
													<li class="<?php echo $imgclass;?>"><?php echo $html->link($html->image('default_images/'.$appConfigurations['serverName'].'/thumbs/'.$image['ImageDefault']['image']), '/img/'.$appConfigurations['currency'].'/default_images/max/'.$image['ImageDefault']['image'], array('class' => 'productImageThumb', 'border' => 0), null, false);?></li>
												<?php else: ?>
													<li class="<?php echo $imgclass;?>"><?php echo $html->link($html->image('product_images/thumbs/'.$image['image']), '/img/product_images/max/'.$image['image'], array('class' => 'productImageThumb', 'border' => 0), null, false);?></li>
												<?php endif; ?>
												<?php $i++;?>
												
											<?php endforeach;?>
                   						<?php endif;?>
                                                                        
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="des_right">
                                <div id="cp_top">
                                </div>
                                
                                <div id="cp_main">
                                	<div id="timer_<?php echo $auction['Auction']['id'];?>" class="timer countdown" title="<?php echo $auction['Auction']['end_time'];?>">
                                		<?php if ($auction['Auction']['closed']!=1):?>
	                                    <div class="clock">- -
	                                    </div>
	                                    <div class="clock">- -
	                                    </div>
	                                    <div class="clock">- -
	                                    </div>
	                                    <?php else:?>
	                                    <div class="auc_ended"></div> 
	                                    <?php endif;?>
                                    </div>
                                    <!--<div class="cp_clk clock_sm"></div> -->
                                    <div class="right">
                                        <button class="cp_alarmb">Báo thức
                                        </button>
                                        <button class="cp_watchb" value="/watchlists/add/<?php echo $auction['Auction']['id'];?>" alt="<?php echo $auction['Auction']['id'];?>">Theo dõi
                                        </button>
                                    </div>
                                    <div class="clearBoth">
                                    </div>
                                    <div id="cur_price">
                                        <div class="left"><?php echo __('Current Price:',true);?>
                                        </div>
                                        <div id="prc_value" class="right"><?php if(!empty($auction['Product']['fixed'])):?>
								<?php echo $number->currency($auction['Product']['fixed_price'], $appConfigurations['currency']);?>
								<span class="bid-price-fixed" style="display: none"><?php echo $number->currency($auction['Auction']['price'], $appConfigurations['currency']); ?></span>
							<?php else: ?>
								<span class="bid-price">
									<?php echo number_format($auction['Auction']['price'],0,',','.'); ?>
								</span>
							<?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="bid-now">
									<?php if(!empty($auction['Auction']['isFuture'])) : ?>
										<button id="cp_bidb_future class="cp_bid_button">
                                    	</button>
									 <?php elseif(!empty($auction['Auction']['isClosed'])) : ?>
										<button id="cp_bidb_sold" class="cp_bid_button">
                                    	</button>
									 <?php else:?>
										 <?php if($session->check('Auth.User')):?>
											<div class="bid-loading" style="display: none"><?php echo $html->image('ajax-arreows.gif');?></div>
											<button id="cp_bidb" class="cp_bid_button bid-button-link" value="/bid.php?id=<?php echo $auction['Auction']['id'];?>" title="<?php echo $auction['Auction']['id'];?>"></button>
										<?php else:?>
											<button id="cp_bidb_login" class="cp_bid_button bid-button-login"></button>
											
										<?php endif;?>
									<?php endif; ?>
								</div>
     								<?php if ($buy_it_now):?>
                                    <button id="cp_buyitnow" ></button>
                                    <?php else:?>
                                    <button id="cp_buyitnow" disable="true"></button>
                                    <?php endif;?>
                                    <div class="cp_status bid-message" id="cp_stats">
                                    </div>
                                    <div id="cp_cur_winner">
                                        <p><?php echo __('The last bidder',true);?>
                                        </p>
                                        <p>
                                            <a href="#">
                                            	<span class="bid-bidder">
                                            		<?php echo $auction['LastBid']['username']; ?>
                                            	</span>
                                            </a>
                                        </p>
                                        <img class="bid-bidder-ava" src=""/>
                                    </div>
                                </div>
                                <div id="cp_bot">
                                </div>
                                <div id="bid_info">
                                	Khi đặt bid trong <span class="highlight peak"> <?php echo $auction['Auction']['peak_time']?></span> giây cuối cùng, bộ đếm sẽ cộng thêm <span class="highlight time_increment"><?php echo $auction['Auction']['time_increment']?></span> giây.
                                	Mỗi lần đặt bid tốn <span class="highlight bp_cost"><?php echo $auction['Auction']['bp_cost']?>XU</span>, giá sản phẩm sẽ tăng thêm <span class="highlight price_step"><?php echo $auction['Auction']['price_step']?>₫</span>.
                                </div>
                                <div id="auction_data">

                                    <div id="ad_menu" class="blackmenu">
                                        <ul class="menu-nav">
                                            <li class="item28">
                                                <a class="tab1"><?php echo __('Bid History',true);?>
                                                </a>
                                            </li>
                                            <li class="item29 ">
                                                <a class="tab2"><?php echo __('Currently Watching',true);?>
                                                </a>
                                            </li>
                                            <li class="item53 active">
                                                <a class="tab3">Chatroom
                                                </a>
                                            </li>
                                        </ul>
                                    </div>    
                                    <div id="ad_content" class="tabbed">
                                        <div id="tab1" style="display:none">
											<div id="bidHistoryContainer">
                                            <table id="bidHistoryTable">
                                                <thead>
                                                    <tr>
                                                        <td class="his_time">Thời điểm
                                                        </td>
                                                        <td class="his_name">Tên người chơi
                                                        </td>
                                                        <td class="his_price">Mức giá
                                                        </td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                	 <?php if(!empty($bidHistories)):?>	
	                                                    <?php foreach($bidHistories as $bid):?>
	                                    				<tr>
					                                        <td class="his_time"><?php echo $time->niceShort($bid['Bid']['created']);?></td>
					                                        <td class="his_name"><?php echo $bid['User']['username'];?></td>
					                                        <td class="his_price"><?php echo $bid['Bid']['amount'];?>₫</td>
	                                    				</tr>
	                                    				<?php endforeach;?>
	                                    			<?php else:?>
	                                    				<p> Hiện chưa có bid nào được đặt </p>    
                                    				<?php endif;?>                                                     	                                                                                                                                                                                                                                                                                                                                  
                                                </tbody>
                                            </table>
                        					</div>
                                        </div>
                                        <div id="tab2" style="display: none;">
                                            <span class="namelist">
                                            </span>

                                        </div>
                                        <div id="tab3" style="display: none;">
                                        <div class="shoutBoxContainer" id="<?php echo $auction['Auction']['id'];?>">
                                        <?php if($session->check('Auth.User')):?>
                                        	<div class="shoutForm">
                                        		<?php echo $form->create('Comments', array(''));?>
 												<?php echo $form->input('message', array('label' => ''));?>
 												<div class="emoSelect">
 												<a class="thickbox" href="/icon.php?height=180&width=275">
 												<?php echo $html->image("emoticons/4.png",
 													array(
														"alt" => "/img/emoticons/4.png",
														"class" => "emoSelected" 
													));?>
												</a>
												</div>
												<?php echo $form->hidden('emo');?>
 												<?php echo $form->end(__('Chém!',true));?>
 											</div>
 										<?php endif;?>
 											
											
											
											<div class="clearBoth"> </div>
											<div id="mess_wrapper">
											<div class="shoutbox">
												<div id="MessageList">
													<?php foreach($comments as $comment):?>
													<?php if($comment['User']['admin']=='1'):?>
														<div class='chat_item admin'>
									        		<?php else:?>
														<div class='chat_item'>
													<?php endif;?>	
									        		<div class='commentInfo'><?php echo $comment['User']['username']?></div>
									        			<div class='commentBox'>
									        				<div class='commentBoxShape'> </div>
									        				<div class="commentContent">
									        					<div class="commentEmo"><img src="<?php echo $comment['Comment']['emo']?>"> </div>
									        					<div class="commentMessage"><?php echo $comment['Comment']['message']?></div>		
									        					<div class="commentDate"><?php echo $comment['Comment']['time']?></div>
									        				</div>
									        			</div>	
									        		</div>
													<?php endforeach;?>
													<div class="clearBoth"> </div>
												</div>
											</div>
											</div>
										</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

<div class="right" id="right_col">
                        <div id="saving" class="roundtop">
                        	<h1> Mức độ tiết kiệm </h1>
                            <table>
                            	<tr>
                                	<td class="alignleft">Giá trị sản phẩm</td>
                                    <td class="alignright money"><span class="product-rrp"><?php echo number_format($auction['Product']['rrp'],0,',','.'); ?> </span>₫</td>
                                </tr>
                            	<tr>
                                	<td class="alignleft">Giá hiện tại:</td>
                                    <td class="alignright money">
										<span class="bid-price"><?php echo number_format($auction['Auction']['price'],0,',','.'); ?> </span>₫
									</td>
                                </tr> 
                                <?php if($session->check('Auth.User')):?>
                            	<tr>
                                	<td class="alignleft">Lượng bid đã bỏ ra:</td>
                                    <td class="alignright money">
                                    	<span class="bid-used">
											<?php
                                            	if(!empty($bidUsed))
                                            		echo number_format($bidUsed*15,0,',','.');
												else
													echo 0;
											?>
                                        </span>₫
                                    </td>
                                </tr>                        
                                <?php endif;?>                                                                                                  
                            </table>
                            <div class="seperator"></div>
                            <table>
                                <tr>
                                	<td class="alignleft">Tổng giá trị tiết kiệm:</td>
                                    <td class="alignright money"><span class="bid-saving-price"><?php echo number_format($auction['Auction']['savings']['price'],0,',','.');?></span>₫</td>
                                </tr>
                            </table>
                        </div>                      
                        <div class="roundbottom">                       
                        </div>
                        <div class="seperator w100"></div>
                        
                        
                        <?php if($session->check('Auth.User')&&$auction['Auction']['closed']!=1):?>
                        <div id="autobid" class="rightmenu" alt="<?php echo $auction['Auction']['id'];?>">
                        	<input type="hidden" id="bidbutlerid" value="<?php echo $bidbutler['Bidbutler']['id']?>"/>
                        	<h1>Bid tự động</h1>
                        	<div class="content">
                        	<div id="autobidAdd" style="display:none">
                            	<?php echo $form->create('Bidbutler', array('url'=>'/bidbutlers/add/'.$auction['Auction']['id']));?>
                            	<?php echo $form->input('minimum_price', array('label'=>'Giá tối thiểu(VND):', 'div'=>'rInput', 'title'=>'Khi mà giá sản phẩm lớn hơn số này, Bid tự động sẽ bắt đầu hoạt động'));?>
                            	<?php echo $form->input('maximum_price', array('label'=>'Giá tối đa(VND):', 'div'=>'rInput', 'title'=>'Khi mà giá sản phẩm lớn hơn số này, Bid tự động sẽ tự động tắt'));?>
                            	<?php echo $form->input('bids_quantity', array('label'=>'Số lượng XU:', 'div'=>'rInput', 'title'=>'Số lượng XU bạn sẽ bỏ ra cho Bid tự động'));?>
                            	<?php echo $form->submit(__('Add',true), array('type' => 'button', 'class' => 'button'));?>
                            	<?php echo $form->end();?>
                            </div>
                            
                            <div id="autobidEdit" style="display:none">
								<label> Giá tối thiểu:</label>
								<p> <input id='BidbutlerMinimum_price' type='text' value='<?php echo $bidbutler['Bidbutler']['minimum_price']?>'/>
								<span class='vnd'> ₫ </span> </p>
								<div class='clearBoth'> </div>
								<label> Giá tối đa:</label>
								<p> <input id='BidbutlerMaximum_price' type='text' value='<?php echo $bidbutler['Bidbutler']['maximum_price']?>'/>
								<span class='vnd'> ₫ </span> </p>
								<div class='clearBoth'> </div>
								<label> Số lượng bids:</label>
								<p> <input id='BidbutlerBids' type='text' value='<?php echo $bidbutler['Bidbutler']['bids']?>'/><span class='vnd'>  </span></p>
								<div class='clearBoth'> </div>
								<div class='editDelete'> <input type='button' id='saveChangeb' class='button' value='<?php echo __('Save changes');?>'> </div>
                            </div>
                            
                            <div id="autobidInfo" style="display:none">
                        		<label> Giá tối thiểu:</label> <p> <span id='BidbutlerMinimum_price'><?php echo $bidbutler['Bidbutler']['minimum_price'];?></span> <span class='vnd'> ₫ </span> </p>
                        		<div class='clearBoth'> </div>
                        		<label> Giá tối đa:</label> <p> <span id='BidbutlerMaximum_price'><?php echo $bidbutler['Bidbutler']['maximum_price'];?></span> <span class='vnd'> ₫ </span> </p>
                        		<div class='clearBoth'> </div>
                        		<label> Số lượng bids:</label> <p> <span id='BidbutlerBids'><?php echo $bidbutler['Bidbutler']['bids'];?></span> </p>
                        		<div class='clearBoth'> </div>
                        		<div class='editDelete'> 
                        		<input type='button' id='editb' class='button' value='Sửa'/>
                        		<input type='button' id='deleteb' class='button' value='Xóa'/>
                            	</div>
                            </div>
                            </div>
                        </div>
                        <div class="seperator w100"></div>
                        
                        <div id='bet' alt="<?php echo $auction['Auction']['id'];?>">
                            <div id='betInfo' class="rightmenu" style="display:none">
                        		<h1>Bạn đã đặt cược sản phẩm này:</h1>
                        		<div class="content">
                        		<label> Số XU bạn đặt:</label>
                        		<p> <span id="BetBids" class="XU"><?php echo $bet['Bet']['bids']?></span> XU</p>
                        		<div class='clearBoth'> </div>
                        		<label> Kết quả bạn dự đoán:</label>
                        			<p>
                        				<span id='BetValue' alt='<?php echo $bet['Bet']['value']?>'>
                        				<?php
                        					if($bet['Bet']['value']==0){
                        						echo "Chẵn";
                        					}else{
                        						echo "Lẻ";
                        					}
                        				?>
                        				</span>
                        			</p>
                        		<label> Dự đoán chính xác bạn được:</label>
                        		<p> <span id="credit" class="XU">-</span> XU</p>
                        		</div>
                        		<div class='clearBoth'> </div>
                        	</div>
                        	<div class="seperator w100"></div>
                        	<div id='betStatistics' class="rightmenu">
                        		<h1>Thống kê đặt cược:</h1>
                        		<div class="content">
                        		<div>
                        			<div class="left"><label class="left" style="width:40px; padding-top: 7px;">Chẵn:</label></div>
                        			<a class='thickbox' href="/bet.php?height=110&width=295&type=0&aid=<?php echo $auction['Auction']['id'];?>&uid=<?php echo $session->read('Auth.User.id');?>">
                        			<div id="even_bet" class="bet_bar left">
                        				<div id="even" class="left" style="width: 3px;"></div>
                        				<div id="even_end" class="left"></div>
                        				<div id="even_percent" class="left">0</div>
                        			</div>
                        			</a>
                        		</div>
                        		
                        		<div class="clearBoth"></div>
                        		<div>
                        			<div class="left"><label class="left" style="width:40px; padding-top: 7px;">Lẻ:</label></div>
                        			<a class='thickbox' href="/bet.php?height=110&width=295&type=1&aid=<?php echo $auction['Auction']['id'];?>&uid=<?php echo $session->read('Auth.User.id');?>">
                        			<div id="odd_bet" class="bet_bar left">
                        				<div id="odd" class="left" style="width: 3px;"></div>
                        				<div id="odd_end" class="left"></div>
                        				<div id="odd_percent" class="left">0</div>
                        			</div>
                        			</a>
                        		</div>
                        		<div class="clearBoth"></div>
                        		<div style="text-align: center"> Tổng số XU trong "gà": <span id="sum" class="XU">0</span> XU</div>
                        		</div>
                        	</div>
                        
                        </div>
                        
                        <?php endif;?>
                        <div id="r_chatroom">
                        </div>
                       
                    </div>
                    <div class="clearBoth"></div>
                    <div id="center_col">
                        <div id="product_info_nav" class="blackmenu">
                            <ul class="menu-nav">
                                <li class="item28 active">
                                    <a class="pi_tab1">
                                        <span>Giới thiệu sản phẩm
                                        </span>
                                    </a>

                                  
                                </li>
                                <li class="item29">
                                    <a class="pi_tab2">
                                        <span>Thông số kỹ thuật
                                        </span>
                                    </a>
                                </li>
                                <li class="item53">
                                    <a class="pi_tab3">
                                        <span>Chế độ bảo hành
                                        </span>
                                    </a>
                                </li>

                            </ul>
                        </div> 
                        <div id="product_info_content" class="tabbed">
                                <div id="pi_tab1">                               
                                   	<?php echo $auction['Product']['description'];?>
                                   	<div class="clearBoth"></div>
                                </div>
                                <div id="pi_tab2">
                                	<?php echo $auction['Product']['specification'];?>
                                	<div class="clearBoth"></div>
                                </div>
                                <div id="pi_tab3">
                                	<?php echo $auction['Product']['warranty'];?>
                                	<div class="clearBoth"></div>
                                </div>
                        </div>        
                                                               
                    </div>
               </div>