<?php
$html->addCrumb(__('Manage Auctions', true), '/admin/auctions');
$html->addCrumb($auction['Product']['title'], '/admin/auctions/edit/'.$auction['Auction']['id']);
$html->addCrumb(__('Bids Placed', true), '/admin/bids/auctions'.$auction['Auction']['id']);
echo $this->element('admin/crumb');
?>

<h2><?php echo $auction['Product']['title'] ?> <?php __('Statistics'); ?></h2>

<dl><?php $i = 0; $class = ' class="altrow"';?>
	<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Current Price:'); ?></dt>
	<dd<?php if ($i++ % 2 == 0) echo $class;?>>
		<?php echo $number->currency($auction['Auction']['price'], $appConfigurations['currency']); ?>
	</dd>

	<?php if(!empty($appConfigurations['autobids'])) : ?>
		<dt<?php if ($i % 2 == 0) echo $class;?>>Thời gian kết thúc</dt>
		
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $auction['Auction']['end_time']; ?>
		</dd>
	
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Số lượng Bid đã được đặt:'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $realbids; ?>
		</dd>
		
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Each time a bid is placed the price will increase by:'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $number->currency($priceIncrement, $appConfigurations['currency']); ?>
		</dd>
		
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Số lượng người tham gia phiên đấu giá:'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $participated ?>
		</dd>
		<dt <?php if ($i % 2 == 0) echo $class;?>>Danh sách Top 10 bidders</dt>
		<dd>
		<table>
				
			<?php foreach($topbidders as $bidder):?>
				<tr>
				<td><?php echo ($bidder['User']['username']);?></td>
				<td><?php echo ($bidder[0]['used']);?> xu</td>
				</tr>
				
			<?php endforeach;?>
		</table>
		</dd>
		<dt <?php if ($i % 2 == 0) echo $class;?>>Chat Log</dt>
		<dd>
			<ul>
				<?php foreach ($chatlog as $chat):?>
				<li>
					<strong><?php echo $chat['User']['username']?></strong> (<?php echo $chat['Comment']['time']?>)
					<p><?php echo $chat['Comment']['message']?></p>
				</li>
				<?php endforeach;?>
			</ul>
		</dd>
		
	<?php endif; ?>

</dl>