<div id="banner">
	<div id="logo">
		<a href="/"><img src="images/logo.png" /> </a>
	</div>
	<!--End logo-->
	<div id="thongbao">
		<p>Thông báo:hiện tại server đang bảo trì quy khách vui lòng quay lai
			sau nhé. bye bye</p>
	</div>
	<div id="status">
	<?php if($session->check("Auth.User")):?>
		<div id="profile">
			<p id="username-user">Tài_khoản_1</p>
			<p id="egold-user">Egold: 600</p>
			<p id="xu-user">Xu: 700</p>
			<a href=""> <!--button nap xu--> </a>
		</div>
		<img id="avatar" src="images/Xem_03.png" />
		<?php else:?>
		<div id="login">
			<a id="user-create" href="/users/register"> <!--button  tạo tài khoản-->
			</a><br /> <a href="/users/login">Đăng nhập</a> / <a
				href="/users/reset">Quên mật khẩu</a>
		</div>
		<?php endif;?>
	</div>
</div>
<div id="navigator">
	<a href="/">Trang chủ</a>
</div>
