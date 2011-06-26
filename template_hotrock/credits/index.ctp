
<div id="rightcol">
	<?php
	$html->addCrumb(__('My Credits', true), '/credits');
	echo $this->element('crumb_user');
	?>

	<h1><?php __('My Credits');?></h1>
	<?php if(!empty($credits)): ?>
		<?php echo $this->element('pagination'); ?>

		<table class="results" cellpadding="0" cellspacing="0">
			<tr>
				<th><?php echo $paginator->sort('Date', 'created');?></th>
				<th><?php echo $paginator->sort('Description', 'Auction.title');?></th>
				<th><?php echo $paginator->sort('debit');?></th>
				<th><?php echo $paginator->sort('credit');?></th>
			</tr>

			<tr>
				<td colspan="3"><strong><?php __('Current Credits');?></strong></td>
				<td><strong><?php echo $creditBalance; ?></strong></td>
			</tr>
		<?php
		$i = 0;
		foreach ($credits as $credit):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
			<tr<?php echo $class;?>>
				<td>
					<?php echo $time->niceShort($credit['Credit']['created']); ?>
				</td>
				<td>
					<?php if($credit['Credit']['debit'] > 0) : ?>
						<?php __('Credits used on:'); ?> <?php echo $html->link($credit['Auction']['Product']['title'], array('controller' => 'auctions', 'action' => 'view', $credit['Auction']['id']));?>
					<?php else : ?>
						<?php __('Credits earned on:'); ?> <?php echo $html->link($credit['Auction']['Product']['title'], array('controller' => 'auctions', 'action' => 'view', $credit['Auction']['id']));?>
					<?php endif; ?>
				</td>
				<td><?php echo $credit['Credit']['debit']; ?></td>
				<td><?php echo $credit['Credit']['credit']; ?></td>
			</tr>
		<?php endforeach; ?>
		</table>

		<?php echo $this->element('pagination'); ?>

	<?php else:?>
		<p><?php __('You have no credits at the moment.');?></p>
	<?php endif;?>
</div>
