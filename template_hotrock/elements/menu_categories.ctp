<script type="text/javascript">
$(document).ready(function () {$("ul.usermenu li:even").addClass("alt");
	var state=0;
	$('.categories_menu').click(function () {
			$('.categories_content').slideToggle(100);
	});

	$('ul.menu li a').mouseover(function () {
		$(this).animate({ fontSize: "13px", paddingLeft: "10px"}, 50 );
	});
		
	$('ul.menu li a').mouseout(function () {
		$(this).animate({ fontSize: "11px", paddingLeft: "0px"}, 50 );
	});
});
</script>
<div class="categories_wrapper">
	<div class="categories_menu"/>
	<h3>Danh mục</h3>
	</div>
	
	<div class="categories_content_wrapper">
			
		<div class="categories_content">
			<div class="width clear">
			<div style="position: relative; z-index: 0;" id="relative_div"></div>
				<?php if(!empty($menuCategories)) : ?>
					<ul class="menu level1">
							<?php foreach($menuCategories as $menuCategory): ?>
								<li class="child <?php if (isset($current_category) && ($current_category == $menuCategory['Category']['id'])) echo 'current';?>">$html->link($category['Category']['name'], array('action' => 'view', $category['Category']['id']),array('class'=>'cat_link'))</li>
							<?php endforeach; ?>
					</ul>
					<a href="/categories" style="font-size:10px;font-weight:bold;position:absolute;bottom:0px;right:5px">[xem tất cả]</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>