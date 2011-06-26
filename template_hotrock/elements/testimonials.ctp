
<style type="text/css">
.quote {
	color: #808080;
	font-weight: bold;
	font-style: italic;
}
.user {
	color: #F00;
}
</style>
<script>
$(document).ready(function(){
	$(".likeit").click(function(){
		var likebutton = $(this);		
		$.ajax({
			url: "/testimonials/rate/"+$(this).attr('alt'),
			success: function(data){
				if (data < 0) {
					$.jGrowl("<?php echo __('You have already vote for this testimonial.', true); ?>");					
				} else {
					likebutton.prev().html(data);
					$.jGrowl("<?php echo __('You have voted successfully!', true); ?>");
				}
			}
		});	
	});
});
</script>
	<?php foreach ($testimonials as $testimonial): ?>
		<div class="test_elem">
			<div class="user_img">
				<a href="<?php echo $testimonial['Testimonial']['img'];?>" title="User Image" class="thickbox" ><img src="<?php echo $testimonial['Testimonial']['img'];?>" alt="Single Image"/></a>
			</div>
			<div class="info_right">
			<h3><?php echo $testimonial['User']['username']; ?></h3>
			<p class="created">đăng ngày <?php echo date('d-m-Y',strtotime($testimonial['Testimonial']['created']));?> lúc <?php echo date('H:m:s',strtotime($testimonial['Testimonial']['created']));?></p>
			<div class="bubble"><?php echo (substr($testimonial['Testimonial']['content'],0,120)."...") ?><a href="/testimonials/viewone/<?php echo $testimonial['Testimonial']['id']; ?>?keepThis=true&TB_iframe=true&height=400&width=600" title="View Testimonial" class="thickbox"><em>(Xem đầy đủ)</em></a></div>
			
			</div>
			<div class="clearBoth"></div>
			<div class="auction_info"><span class="user"><?php echo $testimonial['User']['username']; ?></span> thắng phiên đấu giá <span class="user"><a href="/auctions/view/<?php echo $testimonial['Auction']['id']; ?>"> "<?php echo $testimonial['Auction']['Product']['title']; ?>" </a></span>: </div>
			
			
			<div class="like" alt="<?php echo $testimonial['Testimonial']['id']; ?>"></div>
			<div class="right"><?php echo $testimonial['Testimonial']['vote']; ?></div>
		</div>
	<?php endforeach; ?>

