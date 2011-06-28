<div id="right">
	<div id="huongdanthamgia">
		<a href="#">Hướng Dẫn Tham Gia >></a>
		<p>(*) Dành 3 phút để tìm hiểu cách đấu giá</p>
	</div>
	<div id="sapdienra">
		<div class="title_right">
			<a href="#">Sắp diễn ra</a>
		</div>

		<div class="bodysapdienra">
			<?php for($i=0; $i<5; $i++):?>
				<?php echo $this->element('auction_3');?>
			<?php endfor;?>
		</div>
		<!--End .#bodysapdienra-->

		<div class="bottomitemsapdienra">
			<p>600 phiên đấu giá sắp diễn ra</p>
		</div>
		<!--End bottom-->
	</div>
	<?php echo $this->element('random_testimonial');?>
	<?php echo $this->element('top_users');?>

</div>
<!--end #right-->
