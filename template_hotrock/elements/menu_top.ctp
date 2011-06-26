<ul class="menu-nav">
<?php if($session->check('Auth.User')):?>
	<li><?php echo $html->link(__('Home',true), '/', null, null, false); ?></li>
	<li><?php echo $html->link(__('Hướng dẫn',true), array('controller' => 'thong-tin', 'action'=>'gioi-thieu-ve-1bid'), null, null, false); ?></li>
	<li><?php echo $html->link(__('Blog',true), 'http://blog.1bid.vn', null, null, false); ?></li>
	<li><?php echo $html->link(__('My Account',true), array('controller' => 'users', 'action'=>'index'), null, null, false); ?></li>
	
<?php else:?>
   <li><?php echo $html->link(__('Home',true), '/', null, null, false); ?></li>
	<li><?php echo $html->link(__('Hướng dẫn',true), array('controller' => 'thong-tin', 'action'=>'gioi-thieu-ve-1bid'), null, null, false); ?></li>
	<li><?php echo $html->link(__('Blog',true), 'http://blog.1bid.vn', null, null, false); ?></li>
<?php endif;?>
</ul>