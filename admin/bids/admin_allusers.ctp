<?php
/*$html->addCrumb(__('Manage Auctions', true), '/admin/auctions');
$html->addCrumb($auction['Product']['title'], '/admin/auctions/edit/'.$auction['Auction']['id']);
$html->addCrumb(__('Bids Placed', true), '/admin/bids/auctions'.$auction['Auction']['id']);
echo $this->element('admin/crumb');*/
?>

<div class="auctions index">

<table cellpadding="0" cellspacing="0">
<tr>
	<th>Rank</th>
	<th>Username</th>
	<th>Bid balance</th>
</tr>
<?php
$i = 0;
foreach ($bids as $bid):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
<tr>
	<td><?php echo $i;?></td>
	<td><?php echo $bid['User']['username']?></td>
	<td><?php echo $bid['0']['bid_balance']?></td>
</tr>
<?php endforeach; ?>
</table>
</div>
