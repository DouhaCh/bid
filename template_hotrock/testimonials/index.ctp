<div id="ending-soon" class="box">
	<?php
		$html->addCrumb('Cảm nhận chiến thắng', '/testimonials/index');
		echo $this->element('crumb_auction');
	?>
	<div class="f-top clearfix"><h2>1Bidder nói gì?</h2>
	<p class="description">Cảm nhận của người chiến thắng.</p></div>
	<div class="testimonial">
		<div class="content">
		
			<?php if(!empty($testimonials)) : ?>
				<?php if(!empty($appConfigurations['endedLimit'])) : ?>
				<p><strong><?php __('Showing the last');?> <?php echo $appConfigurations['endedLimit']; ?> <?php __('testimonials.');?></strong></p>	
				<?php else : ?>	
				<?php endif; ?>
					
				<?php echo $this->element('testimonials'); ?>
					
				<?php echo $this->element('pagination'); ?>

			<?php else: ?>
				<div class="align-center off_message"><p><?php __('There are no testimonial at the moment.');?></p></div>
			<?php endif; ?>
		</div>
	<br class="clear_l">
	<div class="crumb_bar">
			<?php
			echo $this->element('crumb_auction');
			?>
	</div>
	</div>
	<div class="f-bottom-top clearfix"><p class="page_top"><a href="#" id="link_to_top"><?php echo __('PAGE TOP',true);?></a></p></div>
</div>