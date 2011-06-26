<div id="select-categories">
	<?php if(!empty($menuCategories)) : ?>
	<ul id="nav"><!--
--><li class="list"><a href="<?php echo $appConfigurations['url']; ?>/auctions">All items (<?php echo $menuCategoriesCount['live']; ?>)</a></li><!--
--><li class="list"><a href="<?php echo $appConfigurations['url']; ?>/categories/view/3">Television</a></li><!--
--><li class="list"><a href="<?php echo $appConfigurations['url']; ?>/categories/view/4">Cash &amp; coupons</a></li><!--
--><li style="border-right:none;">
			<a>Other Auctions<span style="margin-left:1px;">▼</span></a>
			<ul>
				<li><a href="<?php echo $appConfigurations['url']; ?>/categories/view/9">Telephones</a></li>
				<li><a href="<?php echo $appConfigurations['url']; ?>/categories/view/1">Other</a></li>
				<li><a href="/auctions/future">Upcoming auctions (<?php echo $menuCategoriesCount['comingsoon']; ?>)</a></li>
				<li><a href="/auctions/closed">Closed auctions (<?php echo $menuCategoriesCount['closed']; ?>)</a></li>
				<li><a href="/auctions/winners">Winners</a></li>
				<li><a href="/categories">Categories</a>
			</ul>
		</li>
	</ul>
	<?php endif; ?>
</div>