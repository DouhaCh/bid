<div class="price_list module">
	<div class="module_box">
		<div class="module_header">Bảng giá các gói XU</div>
		<div class="module_content">
		<a href="#" class="selected" id="l_cash" > Bằng tiền mặt</a> | <a href="#" id="l_prepaid" >Bằng thẻ cào</a>	
		<script type="text/javascript">
			$(document).ready(function(){
				$('.price_list a#l_cash').click(function(){
						$('.price_tab').hide();
						$('#normal_package').toggle();
						
						$('.price_list a').removeClass('selected');
						$(this).addClass('selected');
						return false;
					});
				$('.price_list a#l_prepaid').click(function(){
					$('.price_tab').hide();
					$('#icoin_package').toggle();
					$('.price_list a').removeClass('selected');
					$(this).addClass('selected');
					return false;
					
				});
			});
		</script>
		<table style="text-align:left;font-size:11px;display:none" class="price_tab" id="icoin_package">
		<thead  style="border-bottom:#AAA 1px solid">
		<td style="text-align:left;padding-left:5px"><strong>Thẻ mênh giá</strong></td>
			<td style="text-align:right;padding-right:10px;"><strong>Lượng Xu</strong></td>
			</thead>
		<tbody>
		<tr style="border-bottom:#EEE 1px solid">
			<td style="text-align:left;">Thẻ <strong>10.000đ</strong></td>
			<td style="text-align:right">600 XU</td>
		</tr>
		<tr style="border-bottom:#EEE 1px solid">
			<td style="text-align:left;">Thẻ <strong>20.000đ</strong></td>
			<td style="text-align:right">1.200 XU</td>
		</tr>
		<tr style="border-bottom:#EEE 1px solid">
			<td style="text-align:left;">Thẻ <strong>30.000đ</strong></td>
			<td style="text-align:right">1.800 XU</td>
		</tr>
		<tr style="border-bottom:#EEE 1px solid">
			<td style="text-align:left;">Thẻ <strong>50.000đ</strong></td>
			<td style="text-align:right">3.000 XU</td>
		</tr>
		
		<tr style="border-bottom:#EEE 1px solid">
			<td style="text-align:left;">Thẻ <strong>100.000đ</strong></td>
			<td style="text-align:right">6.000 XU</td>
		</tr>
		<tr style="border-bottom:#EEE 1px solid">
			<td style="text-align:left;">Thẻ <strong>200.000đ</strong></td>
			<td style="text-align:right">12.500 XU</td>
		</tr>
		<tr style="border-bottom:#EEE 1px solid">
			<td style="text-align:left;">Thẻ <strong>300.000đ</strong></td>
			<td style="text-align:right">20.000 XU</td>
		</tr>
		<tr style="border-bottom:#EEE 1px solid">
			<td style="text-align:left;">Thẻ <strong>500.000đ</strong></td>
			<td style="text-align:right">33.500 XU</td>
		</tr>
		</tbody>
		</table>
		
		<table style="text-align:left;font-size:11px" class="price_tab" id="normal_package">
		<thead  style="border-bottom:#AAA 1px solid">
		<td style="text-align:left;padding-left:5px"><strong>GÓI XU</strong></td>
			<td style="text-align:right;padding-right:10px;"><strong>GIÁ</strong></td>
			</thead>
		<tbody>
		<tr style="border-bottom:#EEE 1px solid">
			<td style="text-align:left;">Gói <strong>2K</strong> XU</td>
			<td style="text-align:right">30.000đ</td>
		</tr>
		<tr style="border-bottom:#EEE 1px solid">
			<td style="text-align:left;">Gói <strong>4K</strong> XU</td>
			<td style="text-align:right">60.000đ</td>
		</tr>
		<tr style="border-bottom:#EEE 1px solid">
			<td style="text-align:left;">Gói <strong>10K</strong> XU</td>
			<td style="text-align:right">150.000đ</td>
		</tr>
		<tr style="border-bottom:#EEE 1px solid">
			<td style="text-align:left;">Gói <strong>20K</strong> XU</td>
			<td style="text-align:right">300.000đ</td>
		</tr>
		<tr style="border-bottom:#EEE 1px solid">
			<td style="text-align:left;">Gói <strong>40K</strong> XU</td>
			<td style="text-align:right">600.000đ</td>
		</tr>
		<tr style="border-bottom:#EEE 1px solid">
			<td style="text-align:left;">Gói <strong>60K</strong> XU</td>
			<td style="text-align:right">900.000đ</td>
		</tr>
		<tr style="border-bottom:#EEE 1px solid">
			<td style="text-align:left;">Gói <strong>80K</strong> XU</td>
			<td style="text-align:right">1.200.000đ</td>
		</tr>
		<tr >
			<td style="text-align:left;">Gói <strong>100K</strong> XU</td>
			<td style="text-align:right">1.500.000đ</td>
		</tr>
		</tbody>
		</table>
		
		</div>
		<div class="module_footer_2" style="text-align:right"><a href="/nap-xu" style="padding-right:10px">Nạp XU ngay</a></div>
	</div>
	
    
</div>