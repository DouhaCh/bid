<?php
$html->addCrumb(__('Manage Auctions', true), '/admin/auctions');
$html->addCrumb(__('Products', true), '/admin/products');
$html->addCrumb(__('Edit', true), '/admin/'.$this->params['controller'].'/edit/'.$this->data['Product']['id']);
echo $this->element('admin/crumb');
?>

<div class="auctions form">
<?php echo $form->create('Product');?>
	<fieldset>
 		<legend><?php __('Edit a Product');?></legend>

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
	</fieldset>
<?php echo $form->end(__('Save Changes >>', true));?>
</div>
<?php echo $this->element('admin/required'); ?>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('<< Back to products', true), array('action' => 'index'));?></li>
	</ul>
</div>