<?php
$html->addCrumb('Manage Users', '/admin/users');
$html->addCrumb(Inflector::humanize($this->params['controller']), '/admin/'.$this->params['controller']);
$html->addCrumb('View', '/admin/'.$this->params['controller'].'/view/'.$user['User']['id']);
echo $this->element('admin/crumb');

?>

<h2><?php echo $user['User']['first_name']; ?> <?php echo $user['User']['last_name']; ?> (aka <?php echo $user['User']['username']; ?>)</h2>

<dl><?php $i = 0; $class = ' class="altrow"';?>
	<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Date of Birth'); ?></dt>
	<dd<?php if ($i++ % 2 == 0) echo $class;?>>
		<?php echo $time->format('d F Y', $user['User']['date_of_birth']); ?>
	</dd>

	<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Gender'); ?></dt>
	<dd<?php if ($i++ % 2 == 0) echo $class;?>>
		<?php echo $user['Gender']['name']; ?>
	</dd>

	<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Active'); ?></dt>
	<dd<?php if ($i++ % 2 == 0) echo $class;?>>
		<?php if($user['User']['active'] == 1) : ?>Yes<?php else: ?>No<?php endif; ?>
	</dd>

	<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Newsletter'); ?></dt>
	<dd<?php if ($i++ % 2 == 0) echo $class;?>>
		<?php if($user['User']['newsletter'] == 1) : ?>Yes<?php else: ?>No<?php endif; ?>
	</dd>

	<?php if(!empty($referral)) : ?>
	<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Referred by'); ?></dt>
	<dd<?php if ($i++ % 2 == 0) echo $class;?>>
		<?php echo $html->link($referral['Referrer']['username'], array('action' => 'view', $referral['Referrer']['id'])); ?>
		<?php if($referral['Referral']['confirmed'] == 0) : ?>(this referral is still pending.)<?php endif; ?>
	</dd>
	<?php endif; ?>
	
	<p><?php echo $html->link(__('Reward testimonial', true), array('controller' => 'users', 'action' => 'rewardtestimonial', $user['User']['id'])); ?></p>

</dl>

<h2><?php __('User\'s Address Details'); ?></h2>


			<table class="results" cellpadding="0" cellspacing="0">
			<tr>
				<th><?php __('Id');?></th>
				<th><?php __('Name');?></th>
				<th><?php __('Gender');?></th>
				<th><?php __('Email');?></th>
				<th><?php __('Address');?></th>
				<th><?php __('SID');?></th>
				<th><?php __('Phone Number');?></th>
				<th><?php __('Verified');?></th>
			</tr>

			<tr>				
				<td><?php echo $user['User']['id']; ?></td>
				<td><?php echo $user['User']['first_name'].' '.$user['User']['last_name']; ?></td>
				<td><?php echo $user['Gender']['name']; ?></td>
				<td><?php echo $user['User']['email']; ?></td>
				<td><?php echo $user['User']['address']; ?></td>
				<td><?php echo $user['User']['sid']; ?></td>
				<td><?php echo $user['User']['mobile']; ?></td>
				<td><?php echo $user['User']['verified']; ?></td>
			</tr>
			</table>

<?php $delete = 1; ?>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Edit User', true), array('action' => 'edit', $user['User']['id'])); ?></li>
		<?php if(empty($appConfigurations['simpleBids'])) : ?>
			<li><?php echo $html->link(__('Bids', true), array('controller' => 'bids', 'action' => 'user', $user['User']['id'])); ?></li>
		<?php endif; ?>	
		<?php if(!empty($user['Bid'])) : ?>
			<?php $delete = 0; ?>
		<?php endif; ?>
		<?php if(!empty($user['Bidbutler'])) : ?>
			<?php $delete = 0; ?>
			<li><?php echo $html->link(__('Bid Butlers', true), array('controller' => 'bidbutlers', 'action' => 'user', $user['User']['id'])); ?></li>
		<?php endif; ?>
		<?php if(!empty($user['Auction'])) : ?>
			<?php $delete = 0; ?>
			<li><?php echo $html->link(__('Won Auctions', true), array('controller' => 'auctions', 'action' => 'user', $user['User']['id'])); ?></li>
		<?php endif; ?>
		<?php if(!empty($user['Account'])) : ?>
			<?php $delete = 0; ?>
			<li><?php echo $html->link(__('Account', true), array('controller' => 'accounts', 'action' => 'user', $user['User']['id'])); ?></li>
		<?php endif; ?>
		<?php if(!empty($user['Referred'])) : ?>
			<?php $delete = 0; ?>
			<li><?php echo $html->link(__('Referred Users', true), array('controller' => 'referrals', 'action' => 'user', $user['User']['id'])); ?></li>
		<?php endif; ?>
		<?php if(!empty($user['AffiliateCode'])) : ?>
			<?php $delete = 0; ?>
			<li><?php echo $html->link(__('Affiliate Account', true), array('controller' => 'affiliates', 'action' => 'user', $user['User']['id'])); ?></li>
		<?php endif; ?>
		<?php if(!empty($delete)) : ?>
			<li><?php echo $html->link(__('Delete User', true), array('action' => 'delete', $user['User']['id']), null, sprintf(__('Are you sure you want to delete this user?', true))); ?> </li>
		<?php endif; ?>
		<li><?php echo $html->link(__('<< Back to users', true), array('action' => 'index')); ?> </li>
	</ul>
</div>
