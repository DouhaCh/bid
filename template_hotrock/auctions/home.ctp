<div class="top">
                    	<form action="" name="phanloai" method="post" id="phanloai">
                        	<label id="lbphanloai"><span>Các 100 phiên đấu giá đang diễn ra | </span>Phân loại sản phẩm  </label>
                        	<select>
                            	<option value="0">Tất cả</option>
                                <option value="0">Theo giá</option>
                                <option value="0">Theo ...</option>
                            </select>
                        </form>
                </div><!--end .top-->
                <div id="hotitem">
                	<div class="tophot">
                    	<a href="">"Nóng" nhất</a>
                    
                    </div><!--End .top-->
                    <div class="bottomhot">
                    <?php
                    	$i=0; 
                    	foreach($auctions_end_soon as $auction):
                    	if(++$i<=9):
                    ?>
                    <div class="item">
                    <?php echo $this->element('auction_1', array('auction' => $auction));?>
                    </div>
                    <?php endif;?>
                    <?php endforeach;?>
                     <div class="clear"></div>
                    </div><!--end .bottom-->
                </div><!--end hotitem-->
   
                <div id="directbid">
                	<div class="topdirectbid">
                    	<a href="">ĐANG DIỄN RA</a>
                    </div><!--end .top-->
                    <div class="bodydirectbid">

                    	<table>
                            <tr class="title">
                                <td>SẢN PHẨM</td>
                                <td>&nbsp;</td>
                                <td>GIÁ HIỆN TẠI NGƯỜI ĐẶT GIÁ</td>
                                <td>THỜI GIAN</td>
                                <td>&nbsp;</td>
                            </tr>
                            <?php for($i=0; $i<5; $i++):?>
                     		<tr class="item">
                     			<td colspan="5">
                     		<?php echo $this->element('auction_2');?>
                     			</td>
                     		</tr>
                     		<?php endfor;?> 
                        </table>
                    </div><!--End .body-->
                </div><!--end #directbid-->