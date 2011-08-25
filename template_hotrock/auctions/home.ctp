<div id="hotitem">
	<div class="tophot">
		<a href="#">Đang đấu</a>
	</div>
	<!--End .top-->
	<div class="bottomhot">
	<?php
	$i=0;
	foreach($auctions_live as $auction):
	if(++$i<=9):
	?>
		<div class="item">
		<?php echo $this->element('auction_1', array('auction' => $auction));?>
		</div>
		<?php endif;?>
		<?php endforeach;?>
		<div class="clear"></div>
	</div>
	<!--end .bottom-->
</div>
<!--end hotitem-->