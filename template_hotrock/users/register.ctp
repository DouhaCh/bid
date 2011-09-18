<div id="users-register">
	<div id="left-content">
		<div id="formregister">
			<h4>Đăng kí tài khoản</h4>
			<?php echo $form->create(array("action"=>"register"));?>
				<?php echo $form->input("username", array("label" => "Tên đăng nhập:", "class"=>"ip"))?>
				<?php echo $form->input("password", array("label" => "Mật khẩu:", "class"=>"ip"))?>
				<?php echo $form->input("repassword", array("label" => "Xác nhận mật khẩu:", "class"=>"ip"))?>
				<?php echo $form->input("mobile", array("label" => "Điện thoại", "class"=>"ip"))?>
				<input type="checkbox" id="checkagree" name="checkagree" />
				<label class="checkbox" for="checkagree"> Đồng ý chấp nhận
					<span><a href="/">Quy định & Điều khoản</a></span>
				</label><br /> <input type="submit" value="" id="submit-register" />
			<?php echo $form->end();?>
			</form>
		</div>
		<!--End Form-->
	</div>
	<!--End right-content-->
	<div id="right-content">
		<div id="nowplaying">
			<h3>Đang Diễn Ra:</h3>
			<table>
				<tr id="title-table">
					<td>Sản phẩm</td>
					<td></td>
					<td>Giá hiện tại</td>
					<td>Thời gian</td>

				</tr>
				<!-- Sản phẩm 1-->
				<?php foreach ($auctions_running as $auction):?>
				<?php echo $this->element('home_running_auction', array('auction' => $auction));?>
				<?php endforeach;?>
			</table>
		</div>
		<!--End bid-->
	</div>
	<!--Eng left-content-->

	<div class="clear"></div>

</div>
