<div class="random-testimonial module">
	<div class="module_box">
		<div class="module_header testimonial"></div>
		<div class="module_content">
		<?php if ($random_testimonial!=null):?>
			<p style="text-align:left">
			<span style="color: #FF3706;font-weight: bold;margin: 0px;padding: 0px 0px 9px;text-transform: uppercase;text-align:left">
			<?php echo $random_testimonial['User']['username'];?></span> <span style="font-size:11px;">đã chiến thắng <a href="/auctions/view/<?php echo $random_testimonial['Auction']['id']?>"><?php echo $random_testimonial['Auction']['Product']['title']?></a> với giá <?php echo $random_testimonial['Auction']['price']?>đ</span> 
			</p>
			<div style="background:url('http://tpl.static.1bid.vn/images/testi_bg.gif') no-repeat top left;width:190px;height:117px;">
			<a href="/testimonials/viewone/<?php echo $random_testimonial['Testimonial']['id']; ?>?keepThis=true&TB_iframe=true&height=400&width=600" title="View Testimonial" class="thickbox"><img src="http://share.static.1bid.vn<?php echo $random_testimonial['Testimonial']['img']; ?>" style="max-width:185px;max-height:112px;padding:3px;" /></a>	
			</div>
			<p><?php echo (substr($random_testimonial['Testimonial']['content'],0,200)."...")?>" <a href="/testimonials/viewone/<?php echo $random_testimonial['Testimonial']['id']; ?>?keepThis=true&TB_iframe=true&height=400&width=600" title="View Testimonial" class="thickbox"><em>Xem đầy đủ >>></em></a></p>
			<!--
			<p><strong>Thông tin:</strong>
			<table style="font-size:11px" border="1">
				<tr>
					<td class="dark" style="background-color:#C5C5C5">
						Sản phẩm: 
					</td>
					<td class="white" style="background-color:#F0F0F0">
						<?php print_r($random_testimonial['Auction']['Product']['title']);?>
					</td>
				</tr>
				<tr>
					<td class="dark" style="background-color:#C5C5C5">
						Giá: 
					</td>
					<td class="white" style="background-color:#F0F0F0">
						<?php print_r($random_testimonial['Auction']['price']);?>
					</td>
				</tr>
			</table>
			</p> -->
		</div>
	
		<div class="module_footer_2">
			<a href="/cam-nhan/">Xem hết tất cả</a></div>
		<?php else:?>
		
		<p>Đang cập nhật</p>
		</div>
		<div class="module_footer_1">
		</div>
		<?php endif;?>
	</div>
	
        
	
</div>