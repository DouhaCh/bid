<?php
$html->addCrumb(__('Manage Auctions', true), '/admin/auctions');
$html->addCrumb(__('Products', true), '/admin/products');
$html->addCrumb(__('Add', true), '/admin/'.$this->params['controller'].'/add');
echo $this->element('admin/crumb');
?>

<div class="auctions form">
<?php echo $form->create('Product', array('type'=>'file'));?>
	<fieldset>
 		<legend><?php __('Add a Product');?></legend>

<div style="font-size:12px; margin-bottom:15px;"><span class="required">*</span> denotes mandatory field.</div>
		<?php
			echo $form->input('title', array('label' => 'Tên sản phẩm <span class="required">*</span> <span class="HelpToolTip"><img src="/admin/img/help.png" alt="" border="0" /><span class="HelpToolTip_Title" style="display:none;">Choose a product title</span><span class="HelpToolTip_Contents" style="display:none;">This product title will appear on-site. For example \'Sony Playstation 3 Black 160GB with controller\' is much better than \'Playstation 3\' only.</span></span>'));
		?>

		<label for="PageContent"><?php echo "Mô tả";?></label>
		<?php echo $form->input('Product.description', array('class' => 'mceEditor')); ?>
		<p>&nbsp;</p>

		<?php
			echo $form->input('category_id', array('label' => 'Category <span class="required">*</span> <span class="HelpToolTip"><img src="/admin/img/help.png" alt="" border="0" /><span class="HelpToolTip_Title" style="display:none;">Choose a category</span><span class="HelpToolTip_Contents" style="display:none;">Select a category from the drop down menu to list the product in this category. Only one can be selected.</span></span>', 'empty' => 'Select Category'));
			echo $form->input('rrp', array('label' => 'Giá thực <span class="HelpToolTip"><img src="/admin/img/help.png" alt="" border="0" /><span class="HelpToolTip_Title" style="display:none;">RRP/MSRP</span> <span class="HelpToolTip_Contents" style="display:none;">The RRP is the Recommended Retail Price, sometimes known as the <b>MSRP</b>, this will be shown on your website as \'worth up to \'.</span></span>'));
			echo $form->input('start_price', array('label' => 'Start Price <span class="required">*</span>  <span class="HelpToolTip"><img src="/admin/img/help.png" alt="" border="0" /><span class="HelpToolTip_Title" style="display:none;">Start price</span><span class="HelpToolTip_Contents" style="display:none;">The basic price at which you want your auction to begin. This will be publicly viewable in some templates.</span></span>'));
		?>

<h2>Upload ảnh</h2>
<blockquote><p>Chọn 3 hình ảnh để đưa lên.</p></blockquote>
		<?php echo $form->input('image1', array('type' => 'file', 'label'=>'Image 1')); ?>
		<?php echo $form->input('image2', array('type' => 'file', 'label'=>'Image 2')); ?>
		<?php echo $form->input('image3', array('type' => 'file', 'label'=>'Image 3')); ?>
		

	</fieldset>
<?php echo $form->end(__('Add this Product >>', true));?>
</div>
<br />
<br />

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('<< Back to products', true), array('action' => 'index'));?></li>
	</ul>
</div>
<?php if($appConfigurations['bidIncrements'] == 'single' && !empty($packagePrice)) : ?>
<div id="priceIncrement" style="display: none"><?php echo $priceIncrement;?></div>
<div id="markUp" style="display: none">1.<?php echo $markUp; ?></div>
<div id="packagePrice" style="display: none"><?php echo $packagePrice; ?></div>
<script type="text/javascript">
	$(document).ready(function(){
		$('#ProductRrp').keyup(function(){
			var rrp = $('#ProductRrp').val();
			var priceIncrement = $('#priceIncrement').text();
			var markUp = $('#markUp').text();
			var packagePrice = $('#packagePrice').text();
			var minimumPrice = rrp * parseFloat(markUp) / parseFloat(packagePrice) * parseFloat(priceIncrement);

			if(minimumPrice){
				$('#ProductMinimumPrice').val(minimumPrice);
			}else{
				$('#ProductMinimumPrice').val(0);
			}
		});
	});
</script>
<?php endif; ?>
