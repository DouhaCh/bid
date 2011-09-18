<div id="left-body">
	<div id="top-left-body">
		<p>
			<span class="blue">Có <span class="red">100 phiên đặt giá </span>đang diễn ra | </span>Phân loại sản phẩm
		</p>
		<!-- <form action="" method="post" name="phanloaisp">
			<input name="phanloaisp" id="phanloaisp" type="text" />
		</form> -->
	</div>
	<!--End top-left-body-->
	<div id="bid-container">
		<!-- Panel Bid hết giờ-->
		<?php
			$i=0; 
			foreach($auctions_ending as $auction):
				if($i<6):
        			echo $this->element('home_ending_auction', array('auction' => $auction));
        		elseif($i==6):
        ?>
        <div id="nowplaying">
			<h3>Đang Diễn Ra:</h3>
			<table>
        <?php 
        			echo $this->element('home_running_auction', array('auction' => $auction));
        		else:
        			echo $this->element('home_running_auction', array('auction' => $auction)); 
        		endif;
        		$i++;
        	endforeach;
        ?>
        	</table>
        </div>
	</div>
</div>
<!--end #left-body-->

<div
	id="right-body">
	<div id="top-right-body">
		<h3>Hỗ trợ:</h3>
		<a href="#"><img src="images/yahoo.png" alt="Yahoo" /> </a> <a
			href="#"><img src="images/s_chat.png" alt="Skype" /> </a> <a href="#"><img
			src="images/facebook.png" alt="Facebook" /> </a>

	</div>
	<!--End #top-right-body-->
	<div id="user-victory">
		<h3>Người chiến thắng:</h3>
		<p class="nickname-victory">tên_tài_khoản_02 đã chiến thắng</p>
		<p class="giaithuong-victory">Gói bột giặt Omo với giá 100vnđ</p>
		<a href=""><img src="images/user_trungthuong.png"
			alt="tên_tài_khoản_02" /> </a>
		<p class="user-feedback">Thật là may mắn là winner trong phiên hot
			visa, combo, asus va giờ là combo wolf...Còn gì tuyệt vời hơn nữa...
			>>></p>
	</div>
	<!--End #user-victory-->

	<!--  
	<div id="sapketthuc">
		<h3>Sắp kết thúc: (30 phiên)</h3>
		<div class="bid-panel-sapketthuc">
			<div class="top-bid-panel-sapketthuc">
				<a href=""><img src="images/sp_small.png" align="tên sản phẩm" /> </a>
				<a class="title-saoketthuc" href="">Điện thoại Nokia X3-02</a>
				<p class="prime-market">Giá thị trường: 3.500.000vnđ</p>
				<p class="prime-cur">Giá hiện tại: 100iGold</p>
				<p class="prime-rate">Bước giá: 5iGold</p>
				<p class="time-sapketthuc">00:00:00</p>
			</div>
			<p class="nickname-sapketthuc">Tên_nick_name_01</p>
			<a class="big-sapketthuc" href=""></a>
		</div>

		<div class="bid-panel-sapketthuc">
			<div class="top-bid-panel-sapketthuc">
				<a href=""><img src="images/sp_small.png" align="tên sản phẩm" /> </a>
				<a class="title-saoketthuc" href="">Điện thoại Nokia X3-02</a>
				<p class="prime-market">Giá thị trường: 3.500.000vnđ</p>
				<p class="prime-cur">Giá hiện tại: 100iGold</p>
				<p class="prime-rate">Bước giá: 5iGold</p>
				<p class="time-sapketthuc">00:00:00</p>
			</div>
			<p class="nickname-sapketthuc">Tên_nick_name_01</p>
			<a class="big-sapketthuc" href=""></a>
		</div>
	</div> -->
	
	<!--End #sapketthuc-->
	<div id="quangcao">
		<a href="#"><img src="images/nap_egold.png" alt="Egold" /> </a> <a
			href="#"><img src="images/egold_sms.png" alt="SMS" /> </a>
	</div>

</div>
<!--End #right-body-->
