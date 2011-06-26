<?php $pages = $this->requestAction('/pages/getpages/top');?>
<ul class="sub-menu">
<?php if(!empty($pages)):?>
	<?php foreach($pages as $page):?>
    	<li><?php echo $html->link($page['Page']['name'], array('controller' => 'pages', 'action' => 'view', $page['Page']['slug'])); ?> | </li>
    <?php endforeach;?>
<?php endif;?>
<li><?php echo $html->link(__('Contact', true), array('controller' => 'contact', 'action'=>'index')); ?></li>
<?php if($session->read('Auth.User.admin') == 1): ?>
	<li> | <?php echo $html->link('Admin', array('controller' => 'admin', 'action'=>'index')); ?></li>
<?php endif; ?>
</ul>