<script>
	$(document).ready(function() {
		$('.up_remove').click(function() {
			$.ajax({
            	url: '/watchlists/delete/'+$(this).parent().attr('title'),
            	complete: function(data){
            		$.jGrowl(data.responseText);
            	}
			});
			$(this).parent().remove();
		})
	});
</script>

<?php
$html->addCrumb(__('My Watchlist', true), '/watchlists');
echo $this->element('crumb_user');
?>

<h1><?php __('My Watchlist');?></h1>

<?php if($paginator->counter() > 0):?>
	<?php echo $this->element('pagination'); ?>
<div id="watchlist" class="module">
    <div class="main_bar">
	</div>
	<div class="sub_bar">
		<span class="prd">SẢN PHẨM</span>
		<span class="curbid">GIÁ HIỆN TẠI / SỐ LƯỢNG BID</span>
		<span class="time">THỜI GIAN</span>
	</div>
	<ul id="more_product_content">
	<?php foreach ($watchlists as $watchlist):?>
		<li class="upcoming_product auction-item" title="<?php echo $watchlist['Auction']['id'];?>" id="auction_<?php echo $watchlist['Auction']['id'];?>">
			<div class="up_photo">
				<a href="/auctions/view/<?php echo $watchlist['Auction']['id']; ?>">
				<?php if(!empty($watchlist['Auction']['Product']['Image'][0]['image'])):?>
					<?php if(!empty($watchlist['Auction']['Product']['Image'][0]['ImageDefault']['image'])) : ?>
						<?php echo $html->image('default_images/'.$appConfigurations['serverName'].'/thumbs/'.$watchlist['Auction']['Product']['Image'][0]['ImageDefault']['image']); ?>
					<?php else: ?>
						<?php echo $html->image('product_images/thumbs/'.$watchlist['Auction']['Product']['Image'][0]['image']); ?>
					<?php endif; ?>
				<?php else:?>
					<?php echo $html->image('product_images/thumbs/no-image.gif');?>
				<?php endif;?>
				</a>
			</div>
			<div class="up_des"><?php echo $html->link($watchlist['Auction']['Product']['title'], array('controller' => 'auctions', 'action' => 'view', $watchlist['Auction']['id']));?>
				<p><?php echo strip_tags($text->truncate($watchlist['Auction']['Product']['brief'], 100, '...', false, true));?></p>
            </div>
            <div class="up_cur">
			<?php if(!empty($watchlist['Auction']['Product']['fixed'])) : ?>
			<?php echo $number->currency($watchlist['Auction']['Product']['fixed_price'], $appConfigurations['currency']); ?>
				<span class="bid-price-fixed" style="display:none"><?php echo $number->currency($$watchlist['Auction']['price'], $appConfigurations['currency']); ?></span>
			<?php else: ?>
				<div class="bid-price cur_price"><?php echo $number->currency($watchlist['Auction']['price'], $appConfigurations['currency']); ?></div>
			<?php endif; ?>
				<span class="vnd">₫</span>
				<span class="cur_retail"><?php echo $number->currency($watchlist['Auction']['Product']['rrp'], $appConfigurations['currency']); ?></span>
				<span class="cur_bidder"><a class="bid-bidder" href="#">Đang kiểm tra</a></span>
			</div>
			<div class="up_time"><div id="auctionLive_<?php echo $watchlist['Auction']['id'];?>" class="timer countdown" title="<?php echo $watchlist['Auction']['end_time'];?>">--:--:--</div></div>
			<div class="up_bid">	
				<div class="bid-now bidbutton clearfix">
				<?php if(!empty($watchlist['Auction']['isFuture'])) : ?>
					<div><?php echo $html->image('b-soon.gif');?></div>
				<?php elseif(!empty($watchlist['Auction']['isClosed'])) : ?>
					<button class="bidb_disabled"/>
				<?php else:?>
					<?php if($session->check('Auth.User')):?>
					<div class="bid-loading" style="display: none"><?php echo $html->image('ajax-arrows.gif');?></div>
					<button class="bidb bid-button-link" value="<?php echo '/bid.php?id='.$watchlist['Auction']['id'];?>" title="<?php echo $watchlist['Auction']['id'];?>"  ></button>
					<br/>
					<?php else:?>
					<button class="bidb bidb_login bid-button-login" href="<?php echo '/bid.php?id='.$watchlist['Auction']['id'];?>" title="<?php echo $watchlist['Auction']['id'];?>"  ></button>
					<!-- <div class="bid-button"><?php echo $html->link($html->image('b-login.gif'), array('controller' => 'users', 'action' => 'login'), null, null, false);?></div> -->
					<?php endif;?>
				<?php endif; ?>
			</div>
			</div>
			<div class="up_remove">
				<a href="#">
					<?php echo $html->image('/images/delete.gif'); ?>
				</a>
			</div>
			<div class="bid-message"></div>                      
		</li>
	<?php endforeach;?>
                               
	</ul>
</div>
                      
<?php else:?>
	<?php __('You are not watching any auctions at the moment.');?>
<?php endif;?>