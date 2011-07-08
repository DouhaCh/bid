<div id="header">
	<div class="left">
		<a href="">&nbsp;</a>
	</div><!--End .left-->
	<div id="status" class="right">
		<?php if($session->check('Auth.User')):?>
		<div>Chào mừng <?php echo $session->read('Auth.User.username');?></div>
		<?php else:?>
		<a href="/users/login">Đăng nhập</a> |
		<a href="/users/register">Đăng kí</a>
		<?php endif;?>
	</div><!--end .right-->
	<div id="navigator">
		<a href="">Trang chủ</a> |
		<a href="">Hướng dẫn</a> |
		<a href="">Nạp xèng</a>
	</div><!--End #navigator-->
</div><!--end #header-->