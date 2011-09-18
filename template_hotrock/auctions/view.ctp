<?php echo $html->css("style_view");?>
<div id="quangcao">
	<a href=""><img src="/images/banner_quangcao.png" alt="Jet" />
	</a>
</div>
<!--End quangCao-->
<div class="auction-item" auction_id="<?php echo $auction["Auction"]["id"];?>" id="auction_<?php echo $auction["Auction"]["id"];?>">
	<div id="product">
		<div id="top-product">
			<div id="left-top-product">
				<h4>Sản Phẩm</h4>
				<h3><?php echo $auction["Product"]["title"];?></h3>
			</div>
			<p id="nhacgio">Nhắc giờ</p>
			<img class="clock" src="/images/clock.png" />
		</div>
		<div id="body-product">
			<div id="image-main-product">
				<img class="image-product" src="/images/sp_big.png" alt="sp" />
			</div>
			<!--End main image-->
			<div id="bid-product">
				<p id="time-product" class="-auction-time">00:00:00</p>
				<p id="prime-product">
					Giá thị trường:<span> <?php echo $auction["Product"]["rrp"]?> vnđ</span>
				</p>
				<p id="igold-product">
					Giá hiện tại: <span class="-auction-price">100</span> Egold
				</p>
				<p id="savemoney-product">Bạn tiết kiệm đc: <span class="-auction-saving">12.000.000</span> vnđ</p>
				<p id="top-user">
					Đang dẫn đầu: <span class="-auction-bidder"><?php $auction["LastBid"]["username"]?></span>
				</p>
				<a href="#" class="-auction-bid"> <!--End --> </a>
			</div>
			<!--End info-->
			<div id="ex-images-product">
				<ul id="featured" class="clearfix">
					<li><a href="images/bt_sp2.png"><img class="image-size"
							src="images/bt_sp2.png" alt="san pham" /> </a></li>
					<li><a href="images/bt_sp3.png"><img class="image-size"
							src="images/bt_sp3.png" alt="san pham" /> </a></li>
					<li><a href="images/image_sp.png"><img class="image-size"
							src="images/image_sp.png" alt="san pham" /> </a></li>
				</ul>
			</div>
			<!--End ex-images-product-->
		</div>
		<!--End body-product-->
	</div>
</div>
<!--End product-->
<div id="info-product">
	<ul class="tabs">
		<li><a href="#tab1">Sản phẩm</a></li>
		<li><a href="#tab2">Thông số kĩ thuật</a></li>
	</ul>
	<!--End tabs-->
	<div class="tab_container">
		<div id="tab1" class="tab_content">
			<p><?php echo $auction["Product"]["description"]?></p>
		</div>
		<!--End Tab 1-->
		<div id="tab2" class="tab_content">
			<p>San pham 1</p>
		</div>
		<!--End tab 2-->
	</div>
</div>
<!--end #info-product-->
<div id="prime-history">
	<h3>Lịch sử giá:</h3>
</div>
<!--End #advertise-->


<div class="clear"></div>
