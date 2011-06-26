<?php
$html->addCrumb(__('Manage Content', true), '/admin/pages');
$html->addCrumb(Inflector::humanize($this->params['controller']), '/admin/'.$this->params['controller']);
echo $this->element('admin/crumb');
?>

<div class="news index">

<h2><?php __('News');?></h2>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Add a news article', true), array('action' => 'add')); ?></li>
	</ul>
</div>

<?php if($paginator->counter() > 0):?>

<?php echo $this->element('admin/pagination'); ?>

<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('title');?></th>
	<th><?php echo $paginator->sort('Date', 'created');?></th>
	<th class="actions"><?php __('Options');?></th>
</tr>
<?php
$i = 0;
foreach ($news as $news):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $news['News']['title']; ?>
		</td>
		<td>
			<?php echo $time->niceShort($news['News']['created']); ?>
		</td>
		<td class="actions">
			<?php echo $html->link(__('View', true), array('admin' => false, 'action' => 'view', $news['News']['id']), array('target' => '_blank')); ?>
			/ <?php echo $html->link(__('Edit', true), array('action' => 'edit', $news['News']['id'])); ?>
			/ <?php echo $html->link(__('Delete', true), array('action' => 'delete', $news['News']['id']), null, sprintf(__('Are you sure you want to delete the article titled: %s?', true), $news['News']['title'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>

<?php echo $this->element('admin/pagination'); ?>

<?php else:?>
	<p><?php __('There is no news at the moment.');?></p>
<?php endif;?>
</div>

<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Add a news article', true), array('action' => 'add')); ?></li>
	</ul>
</div>
