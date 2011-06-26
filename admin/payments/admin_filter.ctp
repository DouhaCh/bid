<?php
$html->addCrumb(__('Payments', true), '/admin/payments');
echo $this->element('admin/crumb');
?>

<div class="payments index">

<h2><?php __('Payment');?></h2>
<blockquote><p>All payment gateway:</p></blockquote>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Filter Payment', true), array('action' => 'filter'),array('class' => 'filterPayment')); ?></li>
	</ul>
</div>
<div class="filterBox" style="display: none">
	<?php echo $form->create('Payment', array('action' => 'filter'));?>
	<fieldset>
		<?php echo $form->input('mobivi',array ('type' => 'checkbox','label' => 'Mobivi'));?>
		<?php echo $form->input('nganluong',array ('type' => 'checkbox', 'label' => 'Ngân Lượng'));?>
		<?php echo $form->input('icoin',array ('type' => 'checkbox', 'label' => 'icoin'));?>		
		<?php $current_year = date('Y');
			echo $form->input('startdate',  array('type'=>'date', 'selected'=>$unix_timestamp, 'minYear'=>2010, 'maxYear'=>$current_year));?>
		<?php echo $form->input('enddate',  array('type'=>'date', 'selected'=>$unix_timestamp, 'minYear'=>2010, 'maxYear'=>$current_year));?>
		<?php echo $form->input('alltime',array ('type' => 'checkbox', 'label' => 'All Time (if check this, Start/End Time will not affect the result)'));?>
		<?php echo $form->input('email');?>
		<?php echo $form->input('username');?>
	</fieldset>
	<?php echo $form->end('Filter');?>
</div>
<?php if(!empty($payments)):?>
<p><h3><?php echo (__('Total', true))?>: <?php echo $total[0][0]['total']?></h3></p>
<?php echo $this->element('admin/pagination'); ?>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('id');?></th>
	<th><?php __('UserID');?></th>
	<th><?php __('Username');?></th>
	<th><?php __('Email');?></th>
	<th><?php __('Source');?></th>
	<th><?php __('Amount');?></th>
	<th><?php __('Created');?></th>
	
</tr>
<?php
$i = 0;
foreach ($payments as $payment):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td><?php echo $payment['Payment']['id']; ?></td>
		<td><?php echo $payment['Payment']['user_id']; ?></td>		
		<td><a href="/admin/users/view/<?php echo $payment['Payment']['user_id']; ?>"><?php echo $payment['User']['username']; ?></a></td>
		<td><?php echo $payment['User']['email']; ?></td>
		<td><?php echo $payment['Payment']['method']; ?></td>
		<td><?php echo $payment['Payment']['amount']; ?></td>
		<td><?php echo $payment['Payment']['created']; ?></td>		
	</tr>

<?php endforeach; ?>
</table>

<?php echo $this->element('admin/pagination'); ?>

<?php else:?>
	<p><?php __('There are no categories at the moment.');?>
<?php endif;?>
</div>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Filter Payment', true), array('action' => 'filter'),array('class' => 'filterPayment')); ?></li>
	</ul>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$('a.filterPayment').click(function(){
			$('.filterBox').toggle();
			return false;
		});
	});
</script>