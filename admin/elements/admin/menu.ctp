
<?php echo $javascript->link('javascript2'); ?>
</div>
<div class="menu_bar">
	<div style="height: 0px;">&nbsp;</div>


	<div>
		<div id="header_menu">
			<ul>
				<li class="dropdown"><a
					href="<?php echo $appConfigurations['url']?>/admin/"><table
							cellspacing="0">
							<tbody>
								<tr>
									<td class="dropdown-tab-left"><img height="38" width="12"
										alt=""
										src="<?php echo $appConfigurations['url']?>/admin/img/left.gif" />
									</td>
									<td class="dropdown-tab-icon" style="background-image: url(<?php echo $appConfigurations['url']?>/admin/img/middle.gif);"><img
										height="16" width="16" alt=""
										src="<?php echo $appConfigurations['url']?>/admin/img/home_view.gif" />
									</td>
									<td class="dropdown-tab-label" style="background-image: url(<?php echo $appConfigurations['url']?>/admin/img/middle.gif);"><span>Home</span>
									</td>
									<td class="dropdown-tab-arrow" style="background-image: url(<?php echo $appConfigurations['url']?>/admin/img/middle.gif);"></td>
									<td class="dropdown-tab-right"><img height="38" width="12"
										alt=""
										src="<?php echo $appConfigurations['url']?>/admin/img/right.gif" />
									</td>
								</tr>
							</tbody>
						</table> </a>
				
				<li class="dropdown">
					<a href="/admin/products/index">
						<table cellspacing="0">
							<tbody>
								<tr>
									<td class="dropdown-tab-left"><img height="38" width="12"
										alt=""
										src="<?php echo $appConfigurations['url']?>/admin/img/left.gif" />
									</td>
									<td class="dropdown-tab-icon" style="background-image: url(<?php echo $appConfigurations['url']?>/admin/img/middle.gif);"><img
										height="16" width="16" alt=""
										src="<?php echo $appConfigurations['url']?>/admin/img/manage_products.gif" />
									</td>
									<td class="dropdown-tab-label" style="background-image: url(<?php echo $appConfigurations['url']?>/admin/img/middle.gif);">
										<span>Quản lý phiên đấu giá</span>
									</td>
									<td class="dropdown-tab-arrow" style="background-image: url(<?php echo $appConfigurations['url']?>/admin/img/middle.gif);"><img
										height="4" width="8"
										src="<?php echo $appConfigurations['url']?>/admin/img/arrow.gif" />
									</td>
									<td class="dropdown-tab-right"><img height="38" width="12"
										alt=""
										src="<?php echo $appConfigurations['url']?>/admin/img/right.gif" />
									</td>
								</tr>
							</tbody>
						</table> 
					</a>
					<ul>
						<li>
							<a class="menu_fixedw" href="<?php echo $appConfigurations['url']?>/admin/products/index">
								<strong>Sản phẩm</strong><span>Thêm, sửa, xóa danh sách sản phẩm.</span> </a></li>
						<li><a class="menu_fixedw"
							href="<?php echo $appConfigurations['url']?>/admin/products/add"><strong>Thêm sản phẩm
									</strong><span>Thêm sản phẩm vào dữ liệu</span>
						</a></li>
						<li><a class="menu_fixedw"
							href="<?php echo $appConfigurations['url']?>/admin/auctions/live">
								<strong>Quản lý phiên đấu giá</strong>
								<span>Thêm, sửa, xóa, quản lý các phiên đấu giá</span> </a></li>
						<li><a class="menu_fixedw"
							href="<?php echo $appConfigurations['url']?>/admin/bids/index">
								<strong>Quản lý đặt giá</strong><span>Xem các giao dịch đặt giá của người chơi</span>
						</a></li>
					</ul>
				</li>
				<li class="dropdown"><a
					href="<?php echo $appConfigurations['url']?>/admin/users"><table
							cellspacing="0">
							<tbody>
								<tr>
									<td class="dropdown-tab-left"><img height="38" width="12"
										alt=""
										src="<?php echo $appConfigurations['url']?>/admin/img/left.gif" />
									</td>
									<td class="dropdown-tab-icon" style="background-image: url(<?php echo $appConfigurations['url']?>/admin/img/middle.gif);"><img
										height="16" width="16" alt=""
										src="<?php echo $appConfigurations['url']?>/admin/img/crowd_view.gif" />
									</td>
									<td class="dropdown-tab-label" style="background-image: url(<?php echo $appConfigurations['url']?>/admin/img/middle.gif);">
										<span>Quản lý người chơi</span>
									</td>
									<td class="dropdown-tab-arrow" style="background-image: url(<?php echo $appConfigurations['url']?>/admin/img/middle.gif);"><img
										height="4" width="8"
										src="<?php echo $appConfigurations['url']?>/admin/img/arrow.gif" />
									</td>
									<td class="dropdown-tab-right"><img height="38" width="12"
										alt=""
										src="<?php echo $appConfigurations['url']?>/admin/img/right.gif" />
									</td>
								</tr>
							</tbody>
						</table> </a>
					<ul>
						<li>
							<a class="menu_fixedw" href="<?php echo $appConfigurations['url']?>/admin/users/index"><strong>Người chơi</strong>
								<span>Xem, tìm kiếm, chỉnh sửa thông tin người chơi</span>
							</a>
						</li>
					</ul>
				</li>
				
				<li class="dropdown"><a href="#"><table cellspacing="0">
							<tbody>
								<tr>
									<td class="dropdown-tab-left"><img height="38" width="12"
										alt=""
										src="<?php echo $appConfigurations['url']?>/admin/img/left.gif" />
									</td>
									<td class="dropdown-tab-icon" style="background-image: url(<?php echo $appConfigurations['url']?>/admin/img/middle.gif);"><img
										height="16" width="16" alt="" src="../../admin/img/home.png" />
									</td>
									<td class="dropdown-tab-label" style="background-image: url(<?php echo $appConfigurations['url']?>/admin/img/middle.gif);"><span>Quick
											Links</span></td>
									<td class="dropdown-tab-arrow" style="background-image: url(<?php echo $appConfigurations['url']?>/admin/img/middle.gif);"><img
										height="4" width="8"
										src="<?php echo $appConfigurations['url']?>/admin/img/arrow.gif" />
									</td>
									<td class="dropdown-tab-right"><img height="38" width="12"
										alt=""
										src="<?php echo $appConfigurations['url']?>/admin/img/right.gif" />
									</td>
								</tr>
							</tbody>
						</table> </a>
					<ul>
						<li><a class="menu_fixedw" href="/admin/products/add">
							<strong>Thêm sản phẩm</strong> </a></li>

						<li>
							<a class="menu_fixedw" href="/admin/auctions/add">
								<strong>Tạo phiên đấu giá mới</strong>
							</a>
						</li>
					</ul>
				</li>
		</div>
		<div style="clear: both;"></div>