<?php
$html->addCrumb(__('Testimonials', true), '/admin/testimonials');
echo $this->element('admin/crumb');
?>

<div class="testimonials index">

<h2><?php __('Testimonial');?></h2>
<blockquote><p>Here you can edit and approve the testimonials of users available on your website.</p></blockquote>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Add Testimonial', true), array('action' => 'add')); ?></li>
	</ul>
</div>

<?php if($paginator->counter() > 0):?>

<?php echo $this->element('admin/pagination'); ?>

<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('id');?></th>
	<th><?php __('Picture');?></th>
	<th><?php __('Username');?></th>
	<th><?php __('Auction');?></th>
	<th><?php __('Content');?></th>
	<th><?php __('Time');?></th>
	<th class="actions"><?php __('Options');?></th>
</tr>
<?php
$i = 0;
foreach ($testimonials as $testimonial):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td><?php echo $testimonial['Testimonial']['id']; ?></td>
		<td><img src="<?php echo $testimonial['Testimonial']['img']; ?>"></img></td>
		<td><?php echo $testimonial['User']['username']; ?></td>
		<td><a href="/auctions/view/<?php echo $testimonial['Auction']['id']; ?>"><?php echo $testimonial['Auction']['Product']['title']; ?></a></td>
		<td><?php echo $testimonial['Testimonial']['content']; ?></td>
		<td><?php echo $testimonial['Testimonial']['time']; ?></td>
		<td class="actions">
			<?php echo $html->link(__('Approve', true), array('action'=>'approve', $testimonial['Testimonial']['id'])); ?>
			/ <?php echo $html->link(__('Edit', true), array('action'=>'edit', $testimonial['Testimonial']['id'])); ?>
			/ <?php echo $html->link(__('Delete', true), array('action'=>'delete', $testimonial['Testimonial']['id']), null, sprintf(__('Are you sure you want to delete the testimonial: %s?', true), $testimonial['Testimonial']['content'])); ?>
		</td>
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
		<li><?php echo $html->link(__('Search Testimonial', true), array('action' => '')); ?></li>
	</ul>
</div>
