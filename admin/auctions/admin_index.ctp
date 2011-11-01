<?php
$html->addCrumb(__('Manage Auctions', true), '/admin/auctions');
if(!empty($extraCrumb)) :
	$html->addCrumb($extraCrumb['title'], '/admin/auctions/'.$extraCrumb['url']);
endif;
echo $this->element('admin/crumb');
?>

<div class="auctions index">

<h2><?php __('Auctions');?></h2>

<?php if(!empty($statuses)):?>
	<?php if(!empty($appConfigurations['autobids'])) : ?>
		<div class="actions">
			<ul>
				<li><?php echo $html->link(__('View auctions won by autobidders', true), array('controller' => 'auctions', 'action' => 'autobidders')); ?></li>
				<li><?php echo $html->link(__('View auctions won by admin users', true), array('controller' => 'auctions', 'action' => 'adminusers')); ?></li>
			</ul>
		</div>
	<?php endif; ?>
	
	<p><?php __('View by status :');?>
	<?php echo $form->create('Auction', array('action' => 'won'));?>
	<?php echo $form->input('status_id', array('id' => 'selectStatus', 'selected' => $selected, 'options' => $statuses, 'label' => false));?>
	<?php echo $form->end();?></p>
<?php endif;?>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Manage your Products', true), array('controller' => 'products', 'action' => 'index')); ?></li>
	</ul>
</div>

<?php if($paginator->counter() > 0):?>

<?php echo $this->element('admin/pagination'); ?>

<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort(__('ID',true), 'Auction.id');?></th>
	<th><?php echo $paginator->sort(__('Title',true), 'Product.title');?></th>
	<th><?php echo $paginator->sort(__('Featured',true));?></th>
	<th><?php echo $paginator->sort(__('End Time', true), 'end_time');?></th>
	<th><?php echo $paginator->sort(__('Price', true), 'price');?></th>
	<th><?php echo $paginator->sort(__('Active', true));?></th>
	<th><?php echo $paginator->sort(__('Hits', true));?></th>
	<th class="actions"><?php __('Options');?></th>
</tr>
<?php
$i = 0;
foreach ($auctions as $auction):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $auction['Auction']['id']; ?>
		</td>
		<td>
			<?php echo $auction['Product']['title']; ?>
		</td>
		<td>
			<?php echo !empty($auction['Auction']['featured']) ? __('Yes', true) : __('No', true); ?>
		</td>
		<td>
			<?php echo $time->niceShort($auction['Auction']['end_time']); ?>
		</td>
		<td>
			<?php echo $number->currency($auction['Auction']['price'], $appConfigurations['currency']); ?>
		</td>
		<td>
			<?php echo !empty($auction['Auction']['active']) ? __('Yes', true) : __('No', true); ?>
		</td>
		<td>
			<?php echo $auction['Auction']['hits']; ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('admin' => false, 'action' => 'view', $auction['Auction']['id']), array('target' => '_blank')); ?>
			<?php if(!empty($auction['Winner']['id'])) : ?>
			<?php elseif(empty($auction['Auction']['closed'])) : ?>
				/ <?php echo $html->link(__('Edit', true), array('action' => 'edit', $auction['Auction']['id'])); ?>
			<?php endif; ?>
			<?php if(!empty($auction['Bid'])) : ?>
				/ <?php echo $html->link(__('Bids Placed', true), array('controller' => 'bids', 'action' => 'auction', $auction['Auction']['id'])); ?>
			<?php endif; ?>
			/ <?php echo $html->link(__('Delete', true), array('action' => 'delete', $auction['Auction']['id']), null, sprintf(__('Bạn chắc chắn xóa phiên đấu giá: %s?', true), $auction['Product']['title'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>

<?php echo $this->element('admin/pagination'); ?>

<?php else: ?>
	<p><?php __('There are no auctions at the moment.');?></p>
<?php endif; ?>
</div>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Manage your Products', true), array('controller' => 'products', 'action' => 'index')); ?></li>
	</ul>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		if($('#selectStatus').length){
			$('#selectStatus').change(function(){
				location.href = '/admin/auctions/won/' + $('#selectStatus').val();
			});
		}
	});
</script>