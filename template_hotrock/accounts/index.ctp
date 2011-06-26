<?php
$html->addCrumb(__('Dash Board',true), '/users');
$html->addCrumb('My Account', '/accounts');
echo $this->element('crumb_user');
?>

<h1><?php __('My Account');?></h1>
<?php if(!empty($accounts)): ?>
	<?php echo $this->element('pagination'); ?>

	<table class="results" cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo $paginator->sort('Date', 'created');?></th>
			<th><?php echo $paginator->sort('Description', 'Account.name');?></th>
			<th><?php echo $paginator->sort('Amount', 'Auction.price');?></th>
		</tr>
	<?php
	$i = 0;
	foreach ($accounts as $account):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
		<tr<?php echo $class;?>>
			<td>
				<?php echo $time->niceShort($account['Account']['created']); ?>
			</td>
			<td>
				<?php echo $account['Account']['name']; ?>
				<?php if($account['Account']['auction_id']) : ?>
					<a href="/auctions/view/<?php echo $account['Account']['auction_id']; ?>"><?php __('View this Auction');?></a>
				<?php elseif($account['Account']['bids']) : ?>
					- <?php echo sprintf(__('%d Bids Purchased', true), $account['Account']['bids']); ?>
				<?php endif; ?>
			</td>
			<td>
				<?php echo $number->currency($account['Account']['price'], $appConfigurations['currency']); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>

	<?php echo $this->element('pagination'); ?>

<?php else:?>
	<p><?php __('You have no account transations at the moment.');?></p>
<?php endif;?>
