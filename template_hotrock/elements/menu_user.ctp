<script type="text/javascript">
$(document).ready(function () {$("ul.usermenu li:even").addClass("alt");
	
	$('.user_panel_button').click(function () {
		$('.cp_menu_body').slideToggle('fast');
	
	});
	$('ul.usermenu li a').mouseover(function () {
		$(this).animate({ fontSize: "14px", paddingLeft: "20px"}, 50 );
	});
		
	$('ul.usermenu li a').mouseout(function () {
		$(this).animate({ fontSize: "12px", paddingLeft: "10px"}, 50 );
	});
});

</script>
	<button class="user_panel_button"><?php echo __('User Menus', true);?></button>
		<div class="cp_menu_body">
				<ul class="usermenu">
					<li><?php echo $html->link(__('Edit Profile', true), array('controller' => 'users', 'action' => 'edit'));?></li>
					<li><?php echo $html->link(__('Change Password', true), array('controller' => 'users', 'action' => 'changepassword'));?></li>
					<li><?php echo $html->link(__('My Addresses', true), array('controller' => 'addresses', 'action' => 'index'));?></li>
				</ul>
	
				<ul class="usermenu">
					<li><?php echo $html->link(__('Nạp XU', true), array('controller' => 'nap-xu'));?></li>
					<li><?php echo $html->link(__('Lịch sử giao dịch', true), array('controller' => 'bids', 'action' => 'index'));?></li>
					<li><?php echo $html->link(__('Bid tự động', true), array('controller' => 'bidbutlers', 'action' => 'index'));?></li>
					<!--
					<?php if(empty($appConfigurations['credits']['active'])) : ?>
						<li><?php echo $html->link(__('My Credits', true), array('controller' => 'credits', 'action' => 'index'));?></li>
					<?php endif; ?> -->
					<li><?php echo $html->link(__('Won Auctions', true), array('controller' => 'dau-gia-chien-thang'));?></li>
					<!-- <li><?php echo $html->link(__('Referrals', true), array('controller' => 'referrals', 'action' => 'index'));?></li> -->
					<li><?php echo $html->link(__('Giới thiệu bạn bè', true), array('controller' => 'gioi-thieu-ban'));?></li>
					<li><?php echo $html->link(__('Danh sách theo dõi', true), array('controller' => 'theo-doi', 'action' => 'index'));?></li>
				</ul>
		</div>
