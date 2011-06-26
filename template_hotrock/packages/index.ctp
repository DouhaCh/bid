<div class="wrapper" id="packages_w">
<div class="info">
</div>
<script type="text/javascript">
$(document).ready(function (){
	var pkg_code='';
	$('#prepaid').click(function (){
		$('.hidden').hide();
		$('#step_2prepaid').show();
		location.href="#step_2prepaid";
	});
	$('#SMS').click(function (){
		alert('Chúng tôi đang triển khai phương thức này, xin thử lại trong gian nữa.');
	});
	$('.hidden').hide();
	$('#online').click(function(){
		$('.hidden').hide();
		$('#step_2online').show();
		location.href="#step_2online";
	});
	$('#prepaid').click(function(){
		$('.hidden').hide();
		$('#step_2prepaid').show();
		location.href="#step_2prepaid";
	});
	$('#bank_transfer').click(function(){
		$('.hidden').hide();
		$('#step_2bank').show();
		location.href="#step_2bank"; 
	});
	$('.cat_link').click(function(){
		pkg_code = $(this).attr('title');
		$('.category_title').css('background-color','#E6E6E6');
		$('.category_title a').css('color','#848489');
		$('#ct_'+pkg_code).css('background-color','#FE3706');
		$('#ct_'+pkg_code+' a').css('color','#FFF');
		$('#nganluong_b').attr('href','/payment_gateways/nganluong/'+pkg_code);
		$('#mobivi_b').attr('href','/payment_gateways/mobivi/'+pkg_code);
		$('#step_3online').show();
		location.href="#step_3online"; 
		return false;
	});
	$('.pkg_img').click(function(){
		pkg_code = $(this).attr('title');
		$('.category_title').css('background-color','#E6E6E6');
		$('.category_title a').css('color','#848489');
		$('#ct_'+pkg_code).css('background-color','#FE3706');
		$('#ct_'+pkg_code+' a').css('color','#FFF');
		$('#nganluong_b').attr('href','/payment_gateways/nganluong/'+pkg_code);
		$('#mobivi_b').attr('href','/payment_gateways/mobivi/'+pkg_code);
		$('#step_3online').show();
		location.href="#step_3online"; 
		return false;
	});
});
</script>
<div class="step" id="step_1">
	<div class="step_t">
		<div class="step_header"><p>Nhập mã khuyến mại</p></div>
	</div>
	<div class="step_box">
	<p>Nếu bạn có mã khuyến mại, hãy điền vào dưới đây để nhận</p>
	<label for="coupon_code">Mã khuyến mại</label>
	<input type="text" style="border:1px solid #888;font-size:13px;"class="text" id="coupon_code" value="" maxlength="80" name="coupon_code">
	</div>
</div>

<div class="step" id="step_1">
	<div class="step_t">
		<div class="step_header"><p>Chọn hình thức thanh toán</p></div>
	</div>
	<div id="payment_method" class="step_box">
		<div class="p_method" id="prepaid">
			<h3>Thẻ cào</h3>
			<img src="/images/prepaid.jpg"/>
		</div>
		<div  class="p_method" id="SMS">
			<h3>Gửi SMS</h3>
			<img src="/images/sms.jpg"/>
		</div>
		<div  class="p_method" id="online">
			<h3>Thanh toán trực tuyến</h3>
			<img src="/images/online.jpg"/>
		</div>
		<div  class="p_method last" id="bank_transfer">
			<h3>Ngân Hàng</h3>
			<img src="/images/bank.jpg"/>
		</div>
		<div class="clearBoth"></div>
	</div>
</div>
<div class="step hidden" id="step_2online">
	<div class="step_t">
		<div class="step_header"><p>Chọn gói xu</p></div>
	</div>
	<div class="packages_list step_box">
	
		<table class="categories">
			<tr>
			<?php $i=0;?>
			<?php foreach($packages as $package): ?>
				<td class="<?php if ($i%3!=2) echo 'vLine';?> <?php if ($i>2) echo " hLine";?>" style="height: 200px">
					<div class="cat_box">
						<div class="widthIndent">
							<div class="category_img">
								<?php if(!empty($package['Package']['image'])) : ?>
									<?php echo $html->image($package['Package']['image'], array('border' => 0,'class'=>'pkg_img','title'=>$package['Package']['code'])); ?>
								<?php else : ?>
									<?php echo $html->image('categories_images/no-image.gif', array('border' => 0,'class'=>'pkg_img','title'=>$package['Package']['code']));?>
								<?php endif; ?>
							</div>
							<p align="center" style="font-size:11px">Giá: <?php echo $number->currency($package['Package']['price'])?></p>
							<div class="category_title" id="ct_<?php echo $package['Package']['code']?>">
								<?php echo $html->link($package['Package']['name'], array('action' => 'view', $package['Package']['id']),array('class'=>'cat_link','id'=>$package['Package']['code'],'title'=>$package['Package']['code'])); ?>
							</div>
						</div>
						
					</div>
				</td>
				<?php $i++;?>
				<?php if($i%3==0):?>
				</tr>
				<tr>
				<?php endif;?>
			<?php endforeach;?>
			</tr>
		</table>
				
	</div>
</div>
<div class="step hidden" id="step_3online">
	<div class="step_t">
		<div class="step_header"><p>Thanh toán trực tuyến</p></div>
	</div>
	<div class="step_box">
		<table>
		<tr>
			<td style="width:50%" align="center">
			<div><a href="/payment_gateways/nganluong" class="submit" id="nganluong_b">	<img src ="/images/p_nganluong.gif"/></a></div>
			<div style="width:200px; text"><a href="http://help.nganluong.vn/1bid.vn.html" style="font-size:11px" target="_blank">Xem hướng dẫn</a>
			</div>
			</td>
			<td style="width:50%" align="center">
			<div><a href="/payment_gateways/mobivi" class="submit" id="mobivi_b"><img src ="/images/p_mobivi.gif"/></a></div>
			<div></div>
			</td>
		</tr>
		<tr>
			
		</tr>
		</table>
	</div>
</div>

<div class="step hidden" id="step_2prepaid">
	<div class="step_t">
		<div class="step_header"><p>Sử dụng thẻ trả trước</p></div>
	</div>
	<div class="step_box">
		<table>
		<tr>
			<td style="width:50%" align="center">
				<div><a href="#" onclick="window.open('/payment_gateways/icoin','','location=1,status=1,scrollbars=1,width=1000,height=600');" id="a_icoin"><img src="/images/icoin.gif"/></a></div>
				<div style="width:200px; text">
					<h2>Sử dụng thẻ nạp di động</h2>
					<p align="justify">Thông qua hệ thống Icoin, bạn có thể sử dụng thẻ nạp di động có bán trên toàn quốc để nạp XU. Bạn phải có một tài khoản trên Icoin và nạp thẳng đến cho chúng tôi qua link trên. <strong>Lưu ý: Hệ thống hiện tại chưa cho phép sự dụng số dư ICOIN để nạp XU mà chỉ có thể sử dụng thẻ cào di động của Mobiphone và Vinaphone để nạp trực tiếp</strong></p>
					
				</div>
			</td>
			<td style="width:50%" align="center">
				<a href="#" onclick="alert('Đang được tích hợp');" id="a_paynet"><img src="/images/paynet.gif"/></a>
				<div style="width:200px; text">
					<h2>Sử dụng thẻ Paynet</h2>
					<p align="justify">Với hơn 1000 điểm bán thẻ trên toàn quốc, bạn có thể tìm mua thẻ của Paynet tại bất kì bưu điện nào gần bạn.
					Hãy sự dụng mã Paynet để nạp tài khoản của bạn 1 cách nhanh chóng và tiện lới</p>
				</div>
			</td>
		</tr>
		</table>
		<div class="tutorial" style="display:none">
					<p>Để mua gói XU của 1bid bằng thẻ trả trước, bạn cần mua thẻ 1bid trả trước được phát hành bởi Paynet. Quy trình nạp thẻ như sau:</p>
				<ol style="padding-left:1in">
				<li>Mua thẻ 1bid trả trước qua các đại lý bán thẻ của Paynet trên toàn quốc</li>
				<li>Nhập “Mã thẻ 1bid trả trước” vào ô dưới đây rồi nhấn nút “Nạp thẻ”</li>
				<li>Kiểm tra tài khoản 1bid của bạn để xác nhận XU đã được cộng vào</li>
				</ol>
		</div>

	
	</div>
</div>




<div class="step hidden" id="step_2bank">
	<div class="step_t">
		<div class="step_header"><p>Chuyển khoản Ngân hàng</p></div>
		</div>
		<div class="step_box" style="text-align:left;padding:10px">
						<p>
							&nbsp;</p>
						<p>
							Bạn c&oacute; thể lựa chọn phương thức chuyển khoản qua ng&acirc;n h&agrave;ng để thanh to&aacute;n cho c&aacute;c giao dịch tại 1bid.vn, bao gồm: mua g&oacute;i XU, thanh to&aacute;n tiền sản phẩm đ&atilde; chiến thắng, thanh to&aacute;n cước vận chuyển sản phẩm,&hellip;</p>
						<p>
							Khi bạn d&ugrave;ng h&igrave;nh thức chuyển khoản ng&acirc;n h&agrave;ng, ngo&agrave;i số tiền bạn muốn chuyển, bạn sẽ phải thanh to&aacute;n th&ecirc;m một khoản <strong>ph&iacute; chuyển khoản</strong> &aacute;p dụng t&ugrave;y ng&acirc;n h&agrave;ng bạn chọn.</p>
						<p>
							1bid.vn sẽ thanh to&aacute;n cho bạn khoản ph&iacute; chuyển khoản n&agrave;y khi bạn mua g&oacute;i XU c&oacute; mệnh gi&aacute; từ 300.000đ trở l&ecirc;n (từ g&oacute;i 20.000 XU trở l&ecirc;n)</p>
						<p>
							Khi chuyển khoản cho 1bid tại ng&acirc;n h&agrave;ng, bạn cần điền những th&ocirc;ng tin sau v&agrave;o phiếu gửi tiền để ch&uacute;ng t&ocirc;i c&oacute; thể nạp XU v&agrave;o đ&uacute;ng t&agrave;i khoản cho bạn:</p>
						<ul style="padding-left:30px">
							<li>
						
									<strong>Số điện thoại</strong>
							</li>
							<li>
								<strong>Email bạn đăng k&yacute; với 1bid.vn</strong></li>
							<li>
								<strong>Số tiền muốn chuyển: XXX</strong></li>
							<li>
								<strong>Nội dung thanh to&aacute;n:&nbsp; Nạp XU v&agrave;o t&agrave;i khoản ABC &ndash; G&oacute;i XU XYZ</strong></li>
						</ul>
						<p style="padding-top:5px">
							Trong đ&oacute;:</p>
						<ul style="padding-left:30px">
							<li>
								<strong>XXX</strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; mệnh gi&aacute; tương ứng của g&oacute;i XU bạn muốn mua</li>
							<li>
								<strong>ABC</strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; t&ecirc;n đăng nhập của bạn tại 1bid</li>
							<li>
								<strong>XYZ</strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; g&oacute;i Xu bạn muốn mua (v&iacute; dụ: g&oacute;i XU 300.000đ)</li>
						</ul>
						<p style="padding-top:5px">
							Dưới đ&acirc;y l&agrave; th&ocirc;ng tin t&agrave;i khoản ng&acirc;n h&agrave;ng của 1bid</p>
							
						<ol>
							<img src="/images/bank_vietcom.gif"/>
							<li>
								
								<strong>Vietcombank:</strong>
								<ul>
									<li>
										Số TK: &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 0301-000-999-669</li>
									<li>
										Tên TK: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Công Ty CP TMĐT Dynabyte</li>
									<li>
										Chi nh&aacute;nh: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Vietcombank Ho&agrave;n Kiếm &ndash; 23 Phan Chu Trinh, Ho&agrave;n Kiếm, H&agrave; Nội</li>
								</ul>
							</li>
						<p style="margin-left:1.0in;">
							&nbsp;</p>
							<img src="/images/bank_techcom.gif"/>
							<li>
								
								<strong>Techcombank:</strong>
								<ul>
									<li>
										Số TK: &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1032-3726-910-016</li>
									<li>
										Tên TK: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Công Ty CP TMĐT Dynabyte</li>
									<li>
										Chi nh&aacute;nh: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Techcombank Thăng Long &ndash; 181 Nguyễn Lương Bằng, Đống Đa, H&agrave; Nội</li>
								</ul>
							</li>
						<p style="margin-left:1.0in;">
							&nbsp;</p>
							<img src="/images/bank_donga.gif"/>
							<li>
								
								<strong>DongA Bank:</strong>
								<ul>
									<li>
										Số TK: &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 0068-6910-0001</li>
									<li>
										Tên TK: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Công Ty CP TMĐT Dynabyte</li>
									<li>
										Chi nh&aacute;nh: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Ng&acirc;n h&agrave;ng Đ&ocirc;ng &Aacute; &ndash; 181 Nguyễn Lương Bằng, Đống Đa, H&agrave; Nội</li>
								</ul>
							</li>
						</ol>
						<br/>
						<p style="padding-top:5px">
						Nếu có vướng mắc trong quá trình chuyển khoản, bạn hãy liên lạc với chúng tôi tại:
						<p style="padding-left:60px">Email:		 <a href="mailto:support@1bid.vn">support@1bid.vn</a><br/>
						Điện thoại: 	(04) 3574 2666</p>
							</p>		
	</div>
</div>
</div>