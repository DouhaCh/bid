<div class="box clearfix">
	<div class="f-top clearfix"><h2><?php __('Rewards'); ?></h2></div>
	<div class="f-repeat clearfix">
		<div class="content">
			<?php
			$html->addCrumb(__('Rewards', true), '/rewards');
			echo $this->element('crumb_auction');
			?>
			
			<?php
				if($session->check('Auth.User')){
					$points = $this->requestAction('/users/points');
					echo sprintf(__('You currently have <strong>%d</strong> points.', true), $points);
				}
			?>
			
			<?php if($paginator->counter() > 0):?>
				<?php echo $this->element('pagination');?>
			
				<ul class="horizontal-bid-list category-list">
					<?php foreach($rewards as $reward):?>
					<li>
						<div class="align-center">
							<h3><?php echo $html->link($reward['Reward']['title'], array('action' => 'view', $reward['Reward']['id'])); ?></h3>
							<div class="thumb"><?php echo $html->image('rewards/thumbs/'.$reward['Reward']['image']); ?></div>
							
							<div class="big-price"><?php __('Points');?>: <?php echo number_format($reward['Reward']['points']); ?></div>
			
							
							<div class="bold"><?php __('Retail Price');?>: <?php echo $number->currency($reward['Reward']['rrp'], $appConfigurations['currency']); ?></div>
							<div>
								<?php echo $html->link(__('Purchase', true), array('action' => 'purchase', $reward['Reward']['id']));?>
							</div>
						</div>
					</li>
				<?php endforeach; ?>
				</ul>
				<?php echo $this->element('pagination');?>
			<?php else:?>
				<p><?php __('There are no rewards available at the moment');?></p>
			<?php endif;?>
		</div>
	</div>
	<div class="f-bottom clearfix"> &nbsp; </div>
</div>