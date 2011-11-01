<?php
$html->addCrumb(__('Manage Auctions', true), '/admin/auctions');
$html->addCrumb(__('Products', true), '/admin/products');
echo $this->element('admin/crumb');
?>

<div class="auctions index">

<h2><?php __('Products');?></h2>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Add a Product', true), array('action' => 'add')); ?></li>
	</ul>
</div>

<?php if(!empty($products)): ?>

<?php echo $this->element('admin/pagination'); ?>

<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort(__('Title',true), 'title');?> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortup.gif" /> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortdown.gif" /> </th>
	<th><?php echo $paginator->sort(__('Category',true), 'Category.name');?> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortup.gif" /> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortdown.gif" /> </th>
	<th><?php echo $paginator->sort("Giá thực");?> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortup.gif" /> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortdown.gif" /> </th>
	<th><?php echo $paginator->sort(__('Start Price',true));?> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortup.gif" /> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortdown.gif" /> </th>
	<th class="actions"><?php __('Options');?></th>
</tr>
<?php
$i = 0;
foreach ($products as $product):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
        			<img src="<?php echo $appConfigurations['url']?>/admin/img/add.png" alt="" align="left" />&nbsp;<?php echo $product['Product']['title']; ?> 
		</td>
		<td>
			<?php echo $html->link($product['Category']['name'], array('admin' => false, 'controller'=> 'categories', 'action'=>'view', $product['Category']['id']), array('target' => '_blank')); ?>
		</td>
		<td>
			<?php if(!empty($product['Product']['rrp'])) : ?>
				<?php echo $number->currency($product['Product']['rrp'], $appConfigurations['currency']); ?>
			<?php else: ?>
				n/a
			<?php endif; ?>
		</td>
		<td>
			<?php echo $number->currency($product['Product']['start_price'], $appConfigurations['currency']); ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('Edit', true), array('action' => 'edit', $product['Product']['id'])); ?>
			/ <?php echo $html->link(__('Images', true), array('controller' => 'images', 'action' => 'index', $product['Product']['id'])); ?>
			/ <?php echo $html->link(__('<span style="color: #f00;">Tạo phiên đấu giá</span>', true), array('controller' => 'auctions', 'action' => 'add', $product['Product']['id'])); ?>
			<?php if(!empty($product['Auction'])) : ?>
					/ <?php echo $html->link(__('Auctions', true), array('action' => 'auctions', $product['Product']['id'])); ?>
			<?php else: ?>
				/ <?php echo $html->link(__('Delete', true), array('action' => 'delete', $product['Product']['id']), null, sprintf(__('Are you sure you want to delete product titled: %s?', true), $product['Product']['title'])); ?>
			<?php endif; ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>

<?php echo $this->element('admin/pagination'); ?>

<?php else: ?>
	<p><?php __('There are no products at the moment.');?></p>
<?php endif; ?>
</div>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Add a Product', true), array('action' => 'add')); ?></li>
	</ul>
</div>
