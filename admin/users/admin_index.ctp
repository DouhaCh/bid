<?php
$html->addCrumb(__('Manage Users', true), '/admin/users');
$html->addCrumb(__('Users', true), '/admin/users');
echo $this->element('admin/crumb');
?>

<h2><?php __('Users');?></h2>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Create a new user', true), array('action' => 'add')); ?></li>
		<li><?php echo $html->link(__('Search user', true), array('action' => 'search'), array('class' => 'searchUser')); ?></li>
	</ul>
</div>
<div class="searchBox" style="display: none">
	<?php echo $form->create('User', array('action' => 'search'));?>
	<fieldset>
		<?php echo $form->input('email');?>
		<?php echo $form->input('username');?>
		<?php $current_year = date('Y');
			echo $form->input('startdate',  array('type'=>'date', 'label' => 'Start Date',  'selected'=>$unix_timestamp, 'minYear'=>2010, 'maxYear'=>$current_year));?>
		<?php echo $form->input('enddate',  array('type'=>'date', 'label' => 'End Date', 'selected'=>$unix_timestamp, 'minYear'=>2010, 'maxYear'=>$current_year));?>
		<?php echo $form->input('alltime',array ('type' => 'checkbox', 'label' => 'All Time (if check this, Start/End Time will not affect the result)'));?>
		<?php echo $form->input('active',array ('type' => 'checkbox','label' => 'Kích hoạt', 'checked' => 1));?>		
	</fieldset>
	<?php echo $form->end('Search');?>
</div>
<?php if(!empty($users)): ?>

<?php echo $this->element('admin/pagination'); ?>

<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('username');?> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortup.gif" /> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortdown.gif" /> </th>
	<th><?php echo $paginator->sort('email');?></th>
	<th><?php echo $paginator->sort("Hoạt động", "User.active");?> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortup.gif" /> <img src="<?php echo $appConfigurations['url']?>/admin/img/sortdown.gif" /> </th>
	<th><?php echo $paginator->sort("Ngày tạo", "User.created");?></th>
	<th class="actions"><?php __('Options');?></th>
</tr>
<?php
$i = 0;
foreach ($users as $user):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td><?php echo $user['User']['username']; ?></td>
		<td><a href="mailto:<?php echo $user['User']['email']; ?>"><?php echo $user['User']['email']; ?></a></td>
		<td><?php if($user['User']['active'] == 1) : ?>Yes<?php else: ?>No<?php endif; ?></td>
		<td><?php echo $user['User']['created']; ?></td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('action' => 'view', $user['User']['id'])); ?>
			<?php if(empty($user['User']['active'])):?>
				/ <?php echo $html->link(__('Resend Activation Email', true), array('action' => 'resend', $user['User']['id'])); ?>
			<?php endif;?>
			/ <?php echo $html->link(__('Edit', true), array('action' => 'edit', $user['User']['id'])); ?>
			<?php if(empty($appConfigurations['simpleBids'])) : ?>
				/ <?php echo $html->link(__('Bids', true), array('controller' => 'bids', 'action' => 'user', $user['User']['id'])); ?>
			<?php endif; ?>
			<?php if(!empty($user['Bidbutler'])) : ?>
				/ <?php echo $html->link(__('Bid Butlers', true), array('controller' => 'bidbutlers', 'action' => 'user', $user['User']['id'])); ?>
			<?php endif; ?>
			<?php if(!empty($user['Auction'])) : ?>
				/ <?php echo $html->link("Đã chiến thắng", array('controller' => 'auctions', 'action' => 'user', $user['User']['id'])); ?>
			<?php endif; ?>
			<?php if(empty($user['User']['admin'])) : ?>
				<?php if(!empty($user['User']['active'])):?>
					/ <?php echo $html->link(__('Suspend', true), array('action' => 'suspend', $user['User']['id']), null, sprintf(__('Are you sure you want to suspend the user: %s?', true), $user['User']['username'])); ?>
					/ <?php echo $html->link(__('Delete', true), array('action' => 'delete', $user['User']['id']), null, sprintf(__('Are you sure you want to delete the user: %s?  Users should only be deleted if they have requested their account to be deleted or as a last minute resort, as it can have consequences on other areas of the website.  We recommend using the suspend option instead.', true), $user['User']['username'])); ?>	
				<?php else : ?>
					/ <?php echo $html->link(__('Delete', true), array('action' => 'delete', $user['User']['id']), null, sprintf(__('Are you sure you want to delete the user: %s?', true), $user['User']['username'])); ?>
				<?php endif; ?>
			<?php endif; ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>

<?php echo $this->element('admin/pagination'); ?>

<?php else:?>
<p><?php __('There are no users at the moment.');?></p>
<?php endif;?>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Create a new user', true), array('action' => 'add')); ?></li>
	</ul>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$('a.searchUser').click(function(){
			$('.searchBox').toggle();
			return false;
		});
	});
</script>