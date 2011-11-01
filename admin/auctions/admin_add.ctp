<?php
$html->addCrumb('Manage Auctions', '/admin/auctions');
$html->addCrumb($product['Product']['title'], '/admin/products/edit/'.$product['Product']['id']);
$html->addCrumb(__('Add Auction', true), '/admin/'.$this->params['controller'].'/add/'.$product['Product']['id']);
echo $this->element('admin/crumb');
?>

<div class="auctions form">
<?php echo $form->create('Auction', array('url' => '/admin/auctions/add/'.$product['Product']['id']));?>
	<fieldset>
 		<legend><?php echo sprintf(__('Add an Auction for: %s', true), $product['Product']['title']);?></legend>
		<?php
			echo $form->input('start_time', array('timeFormat' => '24+second', 'label' => __('Start Time *', true)));
			echo $form->input('end_time', array('timeFormat' => '24+second', 'label' => __('End Time *', true)));
			echo $form->input('price_step', array('label' => 'Bước giá','value'=> 10));
			echo $form->input('bp_cost', array('label' => 'Số xu/bid','value'=> 100));
			echo $form->input('time_increment', array('label' => 'Thời gian tăng','value'=> 10));
			echo $form->input('rapid', array('label' => "Chơi tốc độ", 'type'=>'checkbox'));
			echo $form->input('featured', array('label' => "Tiêu biểu"));
			echo $form->input('nail_bitter', array('label' => 'Không đặt bid tự động', true));
			echo $form->input('beginner', array('label' => 'Phiên đấu giá cho người mới chơi'));
			echo $form->input('active', array('label' => 'Hiển thị'));
		?>

	</fieldset>
<?php echo $form->end(__('Add Auction >>', true));?>
</div>
<?php echo $this->element('admin/required'); ?>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('<< Back to products', true), array('controller' => 'products', 'action' => 'index'));?></li>
		<li><?php echo $html->link(__('<< Back to auctions', true), array('action' => 'index'));?></li>
	</ul>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$('#maxEnd').click(function(){
			if($('#maxEnd').attr('checked')){
				$('#maxEndTimeBlock').show(0);
			}else{
				$('#maxEndTimeBlock').hide(0);
			}
		});

		if($('#maxEnd').attr('checked')){
			$('#maxEndTimeBlock').show(0);
		}else{
			$('#maxEndTimeBlock').hide(0);
		}
	});
</script>
