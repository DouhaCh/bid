<table class="categories">
<tr>
<?php $i=0;?>
<?php foreach($categories as $category): ?>
	<td class="<?php if ($i%3!=2) echo 'vLine';?> <?php if ($i>2) echo " hLine";?>">
		<div class="cat_box">
			<div class="widthIndent">
				<div class="category_img">
					<?php if(!empty($category['Category']['image'])) : ?>
						<?php echo $html->link($html->image('categories_images/'.$category['Category']['image'], array('border' => 0)), array('action' => 'view', $category['Category']['id']), null, null, false); ?>
					<?php else : ?>
						<?php echo $html->link($html->image('categories_images/no-image.gif', array('border' => 0)), array('action' => 'view', $category['Category']['id']), null, null, false); ?>
					<?php endif; ?>
				</div>
				<div class="category_title">
					<?php echo $html->link($category['Category']['name'], array('action' => 'view', $category['Category']['id']),array('class'=>'cat_link')); ?>
				</div>
			</div>
			
		</div>
	</td>
	<?php $i++;?>
	<?php if($i%3==0):?>
	</tr>
	<tr>
	<?php endif;?>
<?php endforeach;?>
</tr>
</table>
